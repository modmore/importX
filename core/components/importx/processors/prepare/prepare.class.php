<?php

class prepareImport {
    public $data;
    /* @var modX $modx */
    public $modx;
    public $importx;
    
    public function __construct(modX &$modx, importX &$importx, $raw) {
        $this->modx =& $modx;
        $this->importx =& $importx;
        $this->data = $raw;
    }
    function process() {
        /* Do your processing here, returning an array with all the properly formatted data */
        return array(
            array(
                'pagetitle' => 'This is a simple array',
                'longtitle' => 'This is a simple array to show how to present the data',
                'tv2' => 'Including TV values'
            ),
            array(
                'pagetitle' => 'This is a simple array',
                'longtitle' => 'This is a simple array to show how to present the data',
                'tv2' => 'Including TV values'
            ),
        );
    }
}

/* Return the class name */
return 'prepareImport';
