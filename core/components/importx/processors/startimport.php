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

/* @var modX $modx */
$modx->importx->post = $scriptProperties;
$modx->importx->initialize();
sleep(1);
$modx->importx->log('info',$modx->lexicon('importx.log.runningpreimport'));
sleep(1);

/* Get the data and prepare it */
$modx->importx->getData();
sleep(1);
$lines = $modx->importx->prepareData();

if ($lines === false) {
    $modx->importx->log('complete','');
    return $this->modx->error->failure();    
}

$this->modx->importx->log('info',$modx->lexicon('importx.log.importvaluesclean',array('count' => count($lines))));
$resourceCount = 0;

$processor = 'resource/'.$modx->getOption('importx.processor',null,'create');
foreach ($lines as $line) {
    /* @var modProcessorResponse $response */
    $response = $modx->runProcessor($processor,$line);
    if ($response->isError()) {
        if ($response->hasFieldErrors()) {
            $fieldErrors = $response->getAllErrors();
            $errorMessage = implode("\n",$fieldErrors);
        } else {
            $errorMessage = $modx->lexicon('importx.err.savefailed')."\n".print_r($response->getMessage(),true);
        }
        $this->modx->importx->log('warn',$resourceCount.' of '.count($lines).' resources were imported successfully');
        $this->modx->importx->log('error',$errorMessage);
        return $this->modx->importx->log('complete','');
    } else {
        $resourceCount++;
    }
}
sleep(1);
$this->modx->importx->log('info',$modx->lexicon('importx.log.complete',array('count' => $resourceCount)));
sleep(1); 
$this->modx->importx->log('complete','');
sleep(1);
return $modx->error->success();
?>
