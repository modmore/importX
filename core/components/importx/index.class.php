<?php

class IndexManagerController extends modExtraManagerController {

    /**
     * Defines the name or path to the default controller to load.
     * @return string
     */
    public static function getDefaultController(): string
    {
        return 'home';
    }
}