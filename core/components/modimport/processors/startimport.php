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
    
    $modimport = &$modx->modimport;
    $modx->lexicon->load('modimport:default');
    
    $sep = $modimport->config['seperator'];
    if ($sep == '') { $sep = ';'; }
    
    $parent = (int)$_POST['parent'];
    if (!is_numeric($parent)) { return $modx->error->failure($modx->lexicon('modimport.err.parentnotnumeric'));  }
    if ($parent < 0) { return $modx->error->failure($modx->lexicon('modimport.err.parentlessthanzero')); }
    
    $csv = $_POST['csv'];
    if (strlen($csv) < 10) { return $modx->error->failure($modx->lexicon('modimport.err.invalidcsv')); }
    
    $lines = explode("\n",$csv);
    if (count($lines) <= 1) { return $modx->error->failure($modx->lexicon('modimport.err.notenoughdata')); }
    
    $headingline = trim($lines[0]);
    if (substr($headingline,-1) == ";") { 
        $headingline = substr($headingline,0,-1);
    }
    $headings = explode($sep,$headingline);
    $headingcount = count($headings);
    
    // Validate the headers...
    $he = array();
    $fields = array('id', 'type', 'contentType', 'pagetitle', 'longtitle', 'description', 'alias', 'link_attributes', 'published', 'pub_date', 'unpub_date', 'parent', 'isfolder', 'introtext', 'content', 'richtext', 'template', 'menuindex', 'searchable', 'cacheable', 'createdby', 'createdon', 'editedby', 'editedon', 'deleted', 'deletedon', 'deletedby', 'publishedon', 'publishedby', 'menutitle', 'donthit', 'haskeywords', 'hasmetatags', 'privateweb', 'privatemgr', 'content_dispo', 'hidemenu', 'class_key', 'context_key', 'content_type');
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
        return $modx->error->failure($modx->lexicon('modimport.err.invalidheader',array('fields' => $he)));
    }
    
    unset($lines[0]);
    foreach ($lines as $line => $lineval) {
        $curline = explode($sep,$lineval);
        if ($headingcount != count($curline)) {
            // Make this return to the log, instead of halting all processes
            return $modx->error->failure($modx->lexicon('modimport.err.elementmismatch',array('line' => $line)));
        }
        $lines[$line] = array_combine($headings,$curline);
    }
    
    foreach ($lines as $line) {
        $response = $modx->runProcessor('resource/create',$line);
        if ($response->isError()) {
            if ($response->hasFieldErrors()) {
                $fieldErrors = $response->getAllErrors();
                $errorMessage = implode("\n",$fieldErrors);
            } else {
                $errorMessage = $modx->lexicon('modimport.err.savefailed')."\n".$response->getMessage();
            }
            return $modx->error->failure($errorMessage);
        }
    }
    
    
    
    return $modx->error->success("\n\nFor debugging in alpha.. \n\n".print_r($lines,true));
    
    
    
    
    
    
    
    
    
    
    return $modx->error->failure('Unknown error.');
    
?>