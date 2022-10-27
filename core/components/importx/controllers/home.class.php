<?php

class ImportxHomeManagerController extends modExtraManagerController
{
    /** @var importX $importX */
    public $importx;

    public function initialize()
    {
        $path = $this->modx->getOption('importx.core_path', null, $this->modx->getOption('core_path') . 'components/importx/') . 'model/importx/';
        $this->importx = $this->modx->getService('importx', importX::class, $path);
    }

    /**
     * @return string[]
     */
    public function getLanguageTopics(): array
    {
        return ['importx:default'];
    }

    /**
     * @return string
     */
    public function getPageTitle(): string
    {
        return $this->modx->lexicon('importx');
    }

    /**
     * @return void
     * @throws \xPDO\xPDOException
     */
    public function loadCustomCssJs(): void
    {
        $this->addJavascript($this->importx->config['jsUrl'] . 'mgr/importx.js');
        $this->addJavascript($this->importx->config['jsUrl'] . 'mgr/widget.home.panel.js');
        $this->addJavascript($this->importx->config['jsUrl'] . 'mgr/widget.home.form.js');
        $this->addLastJavascript($this->importx->config['jsUrl'] . 'mgr/section.index.js');

        $defaults = [];
        $defaults['richtext'] = (bool)$this->modx->getOption('richtext_default', null, true);
        $defaults['template'] = (int)$this->modx->getOption('default_template', null, 1);
        $defaults['hidemenu'] = (bool)$this->modx->getOption('hidemenu_default', null, false);
        $defaults['published'] = (bool)$this->modx->getOption('publish_default', null, true);
        $defaults['searchable'] = (bool)$this->modx->getOption('search_default', null, true);
        $defaults = $this->modx->toJSON($defaults);

        $this->addHtml('<script type="text/javascript">
Ext.onReady(function() {
    importX.config = ' . $this->modx->toJSON($this->importx->config) . ';
    importX.defaults = ' . $defaults . ';
    
    Ext.QuickTips.init();
    MODx.load({ xtype: "importx-page-home"});
});
</script>');
    }

    /**
     * @return string
     */
    public static function getDefaultController(): string
    {
        return 'home';
    }

    /**
     * @return string
     */
    public function getTemplateFile(): string
    {
        return $this->importx->config['templatesPath'].'home.tpl';
    }
}