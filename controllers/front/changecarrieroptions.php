<?php
/**
 * http://serverpresta16new.com/index.php?fc=module&module=axiomuspostcarrier&controller=changecarrieroptions
 */
require_once(_PS_MODULE_DIR_ . 'axiomuspostcarrier/models/AxiomusPost.php');

class axiomuspostcarrierChangeCarrierOptionsModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $display_column_left = false;
    public $AxiomusPost;
    public function __construct()
    {
        parent::__construct();
        $this->AxiomusPost = new AxiomusPost();
    }

    public function initContent()
    {
        $this->context->controller->addJqueryUI('ui.datepicker');
        $this->_path = _PS_ROOT_DIR_.$this->module->getPathUri();
        $this->context->controller->addJS($this->_path.'/views/js/jquery.timepicker.min.js');
        $this->context->controller->addJS($this->_path.'/views/js/jquery.timepicker.min.js');
        $this->context->controller->addCSS($this->_path.'/views/css/jquery.timepicker.css', 'all');

        $this->display_column_left = false;
        parent::initContent();

        $cart = $this->context->cart;
        $totalWeight = $cart->getTotalWeight();
        $products = $cart->getProducts();
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += (float)$product['total_wt']; //ToDo а точно ли не total?
        }

        $addr = new Address($cart->id_address_delivery);
//        if (!$this->module->checkCurrency($cart))
//            Tools::redirect('index.php?controller=order');

        $this->context->smarty->assign(array(
            'tomorrow' => strtotime('+1 day'),

            'city' => $addr->city,
            'weight' =>  $totalWeight,
            'productprice' => $totalPrice,

            'AxiomusPost' => $this->AxiomusPost,

            //Moscow
            'use_mscw_axiomus'              => Configuration::get('RS_AXIOMUS_MSCW_USE_AXIOMUS'),
            'use_mscw_strizh'               => Configuration::get('RS_AXIOMUS_MSCW_USE_STRIZH'),
            'use_mscw_pecom'                  => Configuration::get('RS_AXIOMUS_MSCW_USE_pecom'),
            'use_mscw_axiomus_carry'        => Configuration::get('RS_AXIOMUS_MSCW_USE_AXIOMUS_CARRY'),
            'use_mscw_dpd_carry'            => Configuration::get('RS_AXIOMUS_MSCW_USE_DPD_CARRY'),
            'use_mscw_boxberry_carry'       => Configuration::get('RS_AXIOMUS_MSCW_USE_BOXBERRY_CARRY'),
            'use_mscw_russianpost_carry'    => Configuration::get('RS_AXIOMUS_MSCW_USE_RUSSIANPOST_CARRY'),
            'use_mscw_pecom_carry'                  => Configuration::get('RS_AXIOMUS_MSCW_USE_PECOM_CARRY'),
            //Piter
            'use_ptr_axiomus'              => Configuration::get('RS_AXIOMUS_PTR_USE_AXIOMUS'),
            'use_ptr_strizh'               => Configuration::get('RS_AXIOMUS_PTR_USE_STRIZH'),
            'use_ptr_pecom'                  => Configuration::get('RS_AXIOMUS_PTR_USE_pecom'),
            'use_ptr_axiomus_carry'        => Configuration::get('RS_AXIOMUS_PTR_USE_AXIOMUS_CARRY'),
            'use_ptr_dpd_carry'            => Configuration::get('RS_AXIOMUS_PTR_USE_DPD_CARRY'),
            'use_ptr_boxberry_carry'       => Configuration::get('RS_AXIOMUS_PTR_USE_BOXBERRY_CARRY'),
            'use_ptr_russianpost_carry'    => Configuration::get('RS_AXIOMUS_PTR_USE_RUSSIANPOST_CARRY'),
            'use_ptr_pecom_carry'                  => Configuration::get('RS_AXIOMUS_MSCW_USE_PECOM_CARRY'),
            //region
            'use_region_axiomus_carry'        => Configuration::get('RS_AXIOMUS_REGION_USE_AXIOMUS_CARRY'),
            'use_region_dpd_carry'            => Configuration::get('RS_AXIOMUS_REGION_USE_DPD_CARRY'),
            'use_region_boxberry_carry'       => Configuration::get('RS_AXIOMUS_REGION_USE_BOXBERRY_CARRY'),
            'use_region_pecom_carry'          => Configuration::get('RS_AXIOMUS_REGION_USE_PECOM_CARRY'),

            'nbProducts' => $cart->nbProducts(),
            'cust_currency' => $cart->id_currency,
            'cart_id' => $cart->id,
            'this_path' => $this->module->getPathUri(),
            'this_path_bw' => $this->module->getPathUri(),
            'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->module->name.'/'
        ));

        $this->setTemplate('delivery-page.tpl'); //ToDo добавить вывод мест carry
        $this->setTemplate('delivery_v_2.tpl');
    }

    public function postProcess()
    {


        parent::postProcess();
    }
}