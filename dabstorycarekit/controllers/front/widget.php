<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class DabstorycareKitWidgetModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $this->ajaxRender(json_encode([
            'html' => $this->module->getWidgetHtml(),
        ]));
    }
}
