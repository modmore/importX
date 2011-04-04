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
    
    $sep = $modimport->config['seperator'];
    if ($sep == '') { $sep = ';'; }
    
    $parent = (int)$_POST['parent'];
    if (!is_numeric($parent)) { return $modx->error->failure($modx->lexicon('modimport.err.parentnotnumeric'));  }
    if ($parent < 0) { return $modx->error->failure($modx->lexicon('modimport.err.parentlessthanzero')); }
    
    $csv = $_POST['csv'];
    if (strlen($csv) < 10) { return $modx->error->failure($modx->lexicon('modimport.err.invalidcsv')); }
    
    $lines = explode("\n",$csv);
    if (count($lines) <= 1) { return $modx->error->failure($modx->lexicon('modimport.err.notenoughdata')); }
    
    $firstline = explode($sep,$lines[0]);
    $firstlinecount = count($firstline);
    unset($lines[0]);
    
    foreach ($lines as $line => $lineval) {
        $curline = explode($sep,$lineval);
        if ($firstlinecount != count($curline)) {
            // Make this return to the log, instead of halting all processes
            return $modx->error->failure($modx->lexicon('modimport.err.elementmismatch',array('line' => $line)));
        }
        $lines[$line] = array_combine($firstline,$curline);
    }
    
    foreach ($lines as $line) {
        $nr = $modx->newObject('modResource');
        $nr->fromArray($line);
        if (!is_numeric($line['parent'])) { $nr->set('parent',$parent); }
        
        if ($nr->save()) { /*return $modx->error->success('It seemed to work..');*/ }
        else { return $modx->error->failure($modx->lexicon('modimport.err.savefailed')); }
    }
    
    
    
    return $modx->error->success("\n\nFor debugging in alpha.. \n\n".print_r($lines,true));
    
    
    
    
    
    
    
    
    
    
    return $modx->error->failure('Unknown error.');
    
?>