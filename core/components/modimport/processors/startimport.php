<?php
/*
 * modImport
 *
 * Copyright 2011 by Mark Hamstra (http://www.markhamstra.nl)
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
    //sleep(0);
//error_reporting(0);
    function logConsole($type,$msg) {
        global $modx;
        switch ($type) {
            case 'error':
                sleep(1);
                $modx->log(modX::LOG_LEVEL_ERROR,'FATAL ERROR: '.$msg);
                sleep(2);
                $modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
                sleep(1);
                exit($modx->error->failure($msg)); // Die to prevent the process to go on anyway!
            break;
            default:
            case 'info':
                $modx->log(modX::LOG_LEVEL_INFO,$msg);
                //return true;
            break;
            case 'warn':
                $modx->log(modX::LOG_LEVEL_WARN,$msg);
                return true;
            break;
        }
    }
//sleep(1);
    $modx->log(modX::LOG_LEVEL_INFO,'Running pre-import tests on submitted data...');
sleep(1);

    $modimport = &$modx->modimport;
    $modx->lexicon->load('modimport:default');

    $sep = $modimport->config['seperator'];
    if ($sep == '') { $sep = ';'; }

    $parent = (isset($_POST['parent']))? $_POST['parent'] : 0;
    if (!is_numeric($parent)) {
        return logConsole('error',$modx->lexicon('modimport.err.parentnotnumeric'));
    }
    if ($parent < 0) {
        return logConsole('error',$modx->lexicon('modimport.err.parentlessthanzero'));
    }

    $csv = (isset($_POST['csv'])) ? trim($_POST['csv']) : false;
    if (strlen($csv) < 10) {
        return logConsole('error',$modx->lexicon('modimport.err.invalidcsv'),true);
    }

    $published = (isset($_POST['published'])) ? $_POST['published'] : -1;
    if ($published == 'on') { $published = 1; }
    else { $published = 0; }

    $lines = explode("\n",$csv);
    if (count($lines) <= 1) {
        return logConsole('error',$modx->lexicon('modimport.err.notenoughdata'),true);
    }
    
    $headingline = trim($lines[0]);
    if (substr($headingline,-1) == ";") { 
        $headingline = substr($headingline,0,-1);
    }
    $headings = explode($sep,$headingline);
    $headingcount = count($headings);

    // Validate the headers...
    $he = array(); // HeadingErrors
    $fields = array('id', 'type', 'contentType', 'pagetitle', 'longtitle',  'alias', 'description', 'link_attributes', 'published', 'pub_date', 'unpub_date', 'parent', 'isfolder', 'introtext', 'content', 'richtext', 'template', 'menuindex', 'searchable', 'cacheable', 'createdby', 'createdon', 'editedby', 'editedon', 'deleted', 'deletedon', 'deletedby', 'publishedon', 'publishedby', 'menutitle', 'donthit', 'haskeywords', 'hasmetatags', 'privateweb', 'privatemgr', 'content_dispo', 'hidemenu', 'class_key', 'context_key', 'content_type');
    foreach ($headings as $h) {
        $h = trim($h);
        if (!in_array($h,$fields)) {
            if (substr($h,0,2) != 'tv') {
                $he[] = $h;
            }
            else {
                if (intval(substr($h,2)) <= 0) {
                    $he[] = $h.' ("'.substr($h,2).'" is expected to be an integer)';
                } else {
                    $tvo = $modx->getObject('modTemplateVar',substr($h,2));
                    if (!$tvo) {
                        $he[] = $h.' (no TV with an ID of '.substr($h,2).')';
                    }
                }
            }
        }
    }

    if (count($he) > 0) { 
        $he = implode(', ',$he);
        return logConsole('error',$modx->lexicon('modimport.err.invalidheader',array('fields' => $he)),true);
    }
    //if (!in_array('alias',$headings)) { $headings[] = 'alias'; }

    unset($lines[0]);
    logConsole('info','No errors in pre-import found. Preparing import values...');
    sleep(1);

    $err = array();
    foreach ($lines as $line => $lineval) {
        $curline = explode($sep,$lineval);
        if ($headingcount != count($curline)) {
            $err[]  = $line;
        } else {
            $lines[$line] = array_combine($headings,$curline);
            if ((!isset($lines[$line]['parent'])) && (isset($parent))) { $lines[$line]['parent'] = $parent; }
            if ((!isset($lines[$line]['published'])) && (isset($published))) { $lines[$line]['published'] = $published; }
        }
    }

    if (count($err) > 0) {
        logConsole('error',$modx->lexicon('modimport.err.elementmismatch',array('line' => implode(', ',$err))));
    }
    logConsole('info','No errors found while checking the import values. Importing...');
    foreach ($lines as $line) {
        //logConsole('info','Processing: '.print_r($line,true));
        logConsole('info','before processor');
        $response = $modx->runProcessor('resource/create',$line);
        logConsole('info','this doesnt show up');
        if ($response->isError()) {
            if ($response->hasFieldErrors()) {
                $fieldErrors = $response->getAllErrors();
                $errorMessage = implode("\n",$fieldErrors);
            } else {
                $errorMessage = $modx->lexicon('modimport.err.savefailed')."\n".$response->getMessage();
            }
            return logConsole('error','Oopsie. '.$modx->error->failure($errorMessage));
        } else {
            //logConsole('info','Added resource. '.print_r($line,true));
            //$modx->log(modX::LOG_LEVEL_INFO,"Added a resource with these details: ".print_r($line,true));
        }
    }
    sleep(2);
    logConsole('info','COMPLETED');
    sleep(1);
    return $modx->error->success("Done.");
    //return $modx->error->success("\n\nFor debugging in alpha.. \n\n".print_r($lines,true));

    //return $modx->error->failure('Unknown error.');
    
?>