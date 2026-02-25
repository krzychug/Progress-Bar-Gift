<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class DabstoryCareKit extends Module
{
    const CFG_ENABLED = 'DCK_ENABLED';
    const CFG_THRESHOLD = 'DCK_THRESHOLD';
    const CFG_GIFT_VALUE = 'DCK_GIFT_VALUE';

    public function __construct()
    {
        $this->name = 'dabstorycarekit';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Dabstory';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Dabstory Care Kit');
        $this->description = $this->l('Widget progu prezentowego w modalu koszyka.');
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('displayCartModalContent')
            && $this->registerHook('displayProductActions')
            && $this->registerHook('displayShoppingCartFooter')
            && $this->registerHook('displayHeader')
            && Configuration::updateValue(self::CFG_ENABLED, 1)
            && Configuration::updateValue(self::CFG_THRESHOLD, 499.99)
            && Configuration::updateValue(self::CFG_GIFT_VALUE, 34.99);
    }

    public function uninstall()
    {
        return parent::uninstall()
            && Configuration::deleteByName(self::CFG_ENABLED)
            && Configuration::deleteByName(self::CFG_THRESHOLD)
            && Configuration::deleteByName(self::CFG_GIFT_VALUE);
    }

    public function hookDisplayHeader(array $params)
    {
        $this->context->controller->addCSS($this->_path . 'views/css/carekit.css');

        // Przekazanie URL AJAX do JavaScript
        Media::addJsDef([
            'carekitAjaxUrl' => $this->context->link->getModuleLink(
                $this->name,
                'widget',
                [],
                null,
                null,
                null,
                true  // AJAX = true
            ),
        ]);

        $this->context->controller->addJS($this->_path . 'views/js/carekit.js');
    }

    public function hookDisplayCartModalContent(array $params)
    {
        return $this->renderCareKitWidget();
    }

    public function hookDisplayProductActions(array $params)
    {
        return $this->renderCareKitWidget();
    }
    
    public function hookDisplayShoppingCartFooter(array $params)
    {
        return $this->renderCareKitWidget();
    }

    
    public function getWidgetHtml(): string
    {
        return $this->renderCareKitWidget();
    }


    private function renderCareKitWidget(): string
    {
        if (!(bool) Configuration::get(self::CFG_ENABLED)) {
            return '';
        }

        $data = $this->getCareKitData();
        $this->context->smarty->assign($data);

        return $this->display(__FILE__, 'views/templates/hook/carekit-widget.tpl');
    }

    protected function getCareKitData()
    {
        $threshold = (float) Configuration::get(self::CFG_THRESHOLD);
        $giftValue = (float) Configuration::get(self::CFG_GIFT_VALUE);

        $productsTotal = 0.0;
        $cart = $this->context->cart;

        if (Validate::isLoadedObject($cart) && (int) $cart->id > 0) {
            $productsTotal = (float) $cart->getOrderTotal(true, Cart::ONLY_PRODUCTS);
        }

        $remaining = ($threshold > 0) ? max(0, $threshold - $productsTotal) : 0;
        $percentage = ($threshold > 0) ? min(100, ($productsTotal / $threshold) * 100) : 0;

        return [
            'carekit_current_products' => $productsTotal,
            'carekit_threshold' => $threshold,
            'carekit_remaining' => $remaining,
            'carekit_gift_value' => $giftValue,
            'carekit_percentage' => $percentage,
        ];
    }
}
