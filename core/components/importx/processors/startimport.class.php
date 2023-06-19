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
class StartImport extends modProcessor {
    protected $importX;

    public function initialize(): bool
    {
        $init = parent::initialize();

        $path = $this->modx->getOption('importx.core_path', null, $this->modx->getOption('core_path') . 'components/importx/');
        $this->importX = $this->modx->getService('importx', 'importX', $path);

        /* @var modX|MODX\Revolution\modX $modx */
        $this->importX->post = $this->getProperties();//$scriptProperties;
        $this->importX->initialize();
        sleep(1);
        $this->importX->log('info', $this->modx->lexicon('importx.log.runningpreimport'));
        sleep(1);

        return $init;
    }


    public function process()
    {
        /* Get the data and prepare it */
        $this->importX->getData();
        sleep(1);
        $lines = $this->importX->prepareData();

        if ($lines === false) {
            $this->importX->log('complete','');
            return $this->modx->error->failure();
        }

        $this->importX->log('info', $this->modx->lexicon('importx.log.importvaluesclean', ['count' => count($lines)]));
        $resourceCount = 0;

        $processor = 'resource/' . $this->modx->getOption('importx.processor', null, 'create');

        foreach ($lines as $line) {
            // Set default class_key if MODX 3.x, and no value is specified
            $line = $this->setDefaultClassKey($line);

            /* @var modProcessorResponse $response */
            $response = $this->modx->runProcessor($processor, $line);
            if ($response->isError()) {
                if ($response->hasFieldErrors()) {
                    $fieldErrors = $response->getAllErrors();
                    $errorMessage = implode("\n", $fieldErrors);
                }
                else {
                    $errorMessage = $this->modx->lexicon('importx.err.savefailed') . "\n" . print_r($response->getMessage(),true);
                }

                $this->importX->log('warn', $resourceCount.' of ' . count($lines) . ' resources were imported successfully');
                $this->importX->log('error', $errorMessage);

                return $this->importX->log('complete','');
            }
            else {
                $resourceCount++;
            }
        }

        sleep(1);
        $this->importX->log('info', $this->modx->lexicon('importx.log.complete', ['count' => $resourceCount]));
        sleep(1);
        $this->importX->log('complete', '');
        sleep(1);

        return $this->success();
    }

    /**
     * @param array $record
     * @return array
     */
    protected function setDefaultClassKey(array $record): array
    {
        $modxVersion = $this->modx->getVersionData();
        if (version_compare($modxVersion['full_version'], '3.0.0-dev', '>=') && empty($line['class_key'])) {
            $record['class_key'] = 'MODX\Revolution\modDocument';
        }

        return $record;
    }
}
return 'StartImport';