<?php

$s = array(
    'Default' => array(
        'datatype' => 'csv',
        'processor' => 'create',
    ),
);

$settings = array();
foreach ($s as $area => $sets) {
    foreach ($sets as $key => $value) {
        if (is_string($value) || is_int($value)) { $type = 'textfield'; }
        elseif (is_bool($value)) { $type = 'combo-boolean'; }
        else { $type = 'textfield'; }

        $settings['importx.'.$key] = $modx->newObject('modSystemSetting');
        $settings['importx.'.$key]->set('key', 'importx.'.$key);
        $settings['importx.'.$key]->fromArray(array(
            'value' => $value,
            'xtype' => $type,
            'namespace' => 'importx',
            'area' => $area
        ));
    }
}

return $settings;
