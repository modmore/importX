<?php 
/*
 * importX
 *
 * Copyright 2011-2014 by Mark Hamstra (https://www.markhamstra.com)
 * Development funded by Working Party, a Sydney based digital agency.
 *
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 */
class importX {
    public $modx;
    public $config = array();
    public $data = '';
    public $type = 'csv';
    /* @var prepareImport $prepClass*/
    private $prepClass = null;
    public $preparedData = array();
    public $errors = array();
    public $defaults = array();
    public $post = array();
    
    function __construct (modX &$modx,array $config = array()) {
        $this->modx =& $modx;
 
        $basePath = $this->modx->getOption('importx.core_path',$config,$this->modx->getOption('core_path').'components/importx/');
        $assetsUrl = $this->modx->getOption('importx.assets_url',$config,$this->modx->getOption('assets_url').'components/importx/');
        $this->config = array_merge(array(
            'basePath' => $basePath,
            'corePath' => $basePath,
            'modelPath' => $basePath.'model/',
            'processorsPath' => $basePath.'processors/',
            'chunksPath' => $basePath.'elements/chunks/',
            'jsUrl' => $assetsUrl.'js/',
            'cssUrl' => $assetsUrl.'css/',
            'assetsUrl' => $assetsUrl,
            'connectorUrl' => $assetsUrl.'connector.php',
            
            'separator' => ';'
        ),$config);
        $this->modx->lexicon->load('importx:default');
        
        $this->type = $this->modx->getOption('importx.datatype',null,'csv');
        
    }

    public function initialize() {
        $this->getDefaults();
        $this->setExecutionLimit();
        $this->modx->request->registerLogging($this->post);
        
        $this->config['separator'] = (isset($this->post['separator']) && !empty($this->post['separator'])) ? $this->post['separator'] : $this->config['separator'];
    }
    
    public function getData() {
        // Handle file uploads
        if (!empty($_FILES['csv-file']['name']) && !empty($_FILES['csv-file']['tmp_name'])) {
            $this->log('info',$this->modx->lexicon('importx.log.fileuploadfound',array('filename' => $_FILES['csv-file']['name'])));
            $data = file_get_contents($_FILES['csv-file']['tmp_name']);
            if ($data === false) { return $this->log('error',$this->modx->lexicon('importx.err.fileuploadfailed')); }
        }
        
        // Only if no file was uploaded check the manual input
        if ((!isset($data) || $data === false) &&
            (isset($this->post['csv']) && !empty($this->post['csv']))) {
            $data = trim($this->post['csv']);
        }
        
        // When no CSV detected (file or manual input), throw an error for that.
        if (!isset($data) || ($data === false) || empty($data)) {
            return $this->log('error',$this->modx->lexicon('importx.err.nocsv'));
        }
        
        // Check a minimum length-ish (debatable - might be a useless check really)
        if (strlen($data) < 10) {
            return $this->log('error',$this->modx->lexicon('importx.err.invalidcsv'),true);
        }
        
        $this->data = $data;
        return $data;
    }
    
    public function log ($type,$msg) {
        switch ($type) {
            case 'error':
                $this->modx->log(modX::LOG_LEVEL_ERROR,'Error: '.$msg);
                break;
            case 'complete':
                $this->modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
                sleep(1);
                break;
            case 'warn':
                $this->modx->log(modX::LOG_LEVEL_WARN,$msg);
                break;
            default:
            case 'info':
                $this->modx->log(modX::LOG_LEVEL_INFO,$msg);
                break;
        }
    }
    
    public function prepareData() {
        $file = $this->config['processorsPath'].'prepare/'.$this->type.'.php';
        if (file_exists($file)) {
            $class = include $file;
            if ($class) {
                $this->prepClass = new $class($this->modx, $this, $this->data);
                $this->preparedData = $this->prepClass->process();
                if (is_array($this->preparedData)) {
                    return $this->preparedData;
                } else {
                    foreach ($this->errors as $err) {
                        $this->log('error',$err);
                    }
                    return false;
                }
            }
        }
        $this->log('error',$this->modx->lexicon('importx.log.classnf'));
        return false;
    }

    private function getDefaults() {
        $this->defaults['published'] = (isset($this->post['published']) && $this->post['published'] == 'on') ? true : false;
        $this->defaults['searchable'] = (isset($this->post['searchable']) && $this->post['searchable'] == 'on') ? true : false;
        $this->defaults['hidemenu'] = (isset($this->post['hidemenu']) && $this->post['hidemenu'] == 'on') ? true : false;
        $this->defaults['context_key'] = 'web';
        $this->defaults['parent'] = '';
        
        $parent = (isset($this->post['parent']))? $this->post['parent'] : 0;
        if (!is_numeric($parent)) {
            if ($this->modx->getObject('modContext',$parent)) {
                $this->defaults['context_key'] = $parent;
                $this->defaults['parent'] = 0;
            } else {
                $this->log('error',$this->modx->lexicon('importx.err.parentnotnumeric'));
            }
        }
        elseif ($parent < 0) {
            $this->log('error',$this->modx->lexicon('importx.err.parentlessthanzero'));
        } 
        else {
            $this->defaults['parent'] = $parent;
        }
    }

    private function setExecutionLimit() {
        /* Set a time out limit */
        if (ini_get('safe_mode')) {
            $this->log('warn',$this->modx->lexicon('importx.log.safemodeon'));
        } else {
            set_time_limit(0);
            $limit = ini_get('max_execution_time');
            $limit = ($limit > 0) ? $limit : $this->modx->lexicon('importx.infinite');
            $this->log('info',$this->modx->lexicon('importx.log.timelimit',array('limit' => $limit)));
        }
    }
}

