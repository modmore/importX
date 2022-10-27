<?php

/** @var modMenu $menu */
$menu = $modx->newObject('modMenu');
$menu->fromArray([
    'text' => 'importx',
    'description' => 'importx.description',
    'parent' => 'components',
    'namespace' => 'importx',
    'action' => 'home',
], '', true, true);

return $menu;
