<?php
/*
 * importX
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
    function logConsole($type,$msg) {
        global $modx;
        switch ($type) {
            case 'error':
                sleep(1);
                $modx->log(modX::LOG_LEVEL_ERROR,'FATAL ERROR: '.$msg);
                sleep(2);
                $modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
                sleep(1);
                return $modx->error->failure($msg);
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

    $importx = &$modx->importx;
    $modx->lexicon->load('importx:default');

    $sep = (isset($_POST['separator'])) ? $_POST['separator'] : $importx->config['separator'];
    if ($sep == '') { $sep = ';'; }

    $parent = (isset($_POST['parent']))? $_POST['parent'] : 0;
    if (!is_numeric($parent)) {
        return logConsole('error',$modx->lexicon('importx.err.parentnotnumeric'));
    }
    if ($parent < 0) {
        return logConsole('error',$modx->lexicon('importx.err.parentlessthanzero'));
    }

    $csv = (isset($_POST['csv'])) ? trim($_POST['csv']) : false;
    if (strlen($csv) < 10) {
        return logConsole('error',$modx->lexicon('importx.err.invalidcsv'),true);
    }

    // Check default settings
    if (isset($_POST['published'])) { $published = ($_POST['published'] == 'on') ? 1 : 0; }
    else { $published = 0; }
    if (isset($_POST['searchable'])) { $searchable = ($_POST['searchable'] == 'on') ? 1 : 0; }
    else { $searchable = 0; }
    if (isset($_POST['hidemenu'])) { $hidemenu = ($_POST['hidemenu'] == 'on') ? 1 : 0; }
    else { $hidemenu = 0; }

    $lines = explode("\n",$csv);
    if (count($lines) <= 1) {
        return logConsole('error',$modx->lexicon('importx.err.notenoughdata'),true);
    }
    
    $headingline = trim($lines[0]);
    if (substr($headingline,-strlen($sep)) == $sep) {
        $headingline = substr($headingline,0,-strlen($sep));
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
        return logConsole('error',$modx->lexicon('importx.err.invalidheader',array('fields' => $he)),true);
    }

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
            if ((!isset($lines[$line]['searchable'])) && (isset($searchable))) { $lines[$line]['searchable'] = $searchable; }
            if ((!isset($lines[$line]['hidemenu'])) && (isset($hidemenu))) { $lines[$line]['hidemenu'] = $hidemenu; }
        }
    }

    if (count($err) > 0) {
        return logConsole('error',$modx->lexicon('importx.err.elementmismatch',array('line' => implode(', ',$err))));
    }
    logConsole('info','No errors found while checking the import values. Running import...');
    $resourceCount = 0;
    foreach ($lines as $line) {
        $response = $modx->runProcessor('resource/create',$line);
        if ($response->isError()) {
            if ($response->hasFieldErrors()) {
                $fieldErrors = $response->getAllErrors();
                $errorMessage = implode("\n",$fieldErrors);
            } else {
                $errorMessage = $modx->lexicon('importx.err.savefailed')."\n".print_r($response->getMessage(),true);
            }
            return logConsole('error',$modx->error->failure($errorMessage));
        } else {
            $resourceCount++;
        }
    }
    sleep(1);
    logConsole('info','Importing completed. '.$resourceCount.' resources have been imported.');
    sleep(1);
    logConsole('info','COMPLETED');
    sleep(1);
    return $modx->error->success("Done.");
?>