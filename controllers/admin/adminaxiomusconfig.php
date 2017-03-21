<?php

include_once (_PS_MODULE_DIR_ . 'axiomuspostcarrier/models/AxiomusPost.php');

class AdminAxiomusConfigController extends ModuleAdminController
{

    public $settingsArray = [];
    public $maintab = 0;
    public $subtab = 0;

    public function __construct()
    {
        Configuration::updateValue('RS_AXIOMUS_MSCW_AXIOMUS_PRICE', $this->getArrPrice('MSCW','AXIOMUS'));
        $this->settingsArray = $this->getSettingsArray(); //ToDo может в приват?

//        $this->table = 'axiomus_config';
        $this->AxiomusPost = new AxiomusPost();
//        $this->identifier = 'id';

        //$this->context = Context::getContext();
        $this->bootstrap = true;
        parent::__construct();

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
            )
        );

        $this->fields_options = array(
            'general' => array(
                'title' => $this->l('Основная конфигурация'),
                'fields' => array(
                    'RS_AXIOMUS_TOKEN' => array(
                        'title' => $this->l('Токен к Axiomus API'),
                        'cast' => 'strval',
                        'type' => 'text',
                        'size' => '16'
                    ),
                    'RS_AXIOMUS_CACHE_HOURLIFE' => array(
                        'title' => $this->l('Время жизни записи в кеше (часов)'),
                        'cast' => 'intval',
                        'type' => 'text',
                        'suffix' => 'час',
                        'size' => '2' //ToDo не забыть валидацию
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Сохранить'),
                ),
            ),
            'use_delivery' => array(
                'title' => $this->l('Использование доставки'),
                'fields' => array(
                    'RS_AXIOMUS_USE_AXIOMUS_DELIVERY' => array(
                        'title' => $this->l('Использовать доставку Axiomus'),
                        'desc' => $this->l('Если включено будет создана доставка Axiomus'),
                        'cast' => 'boolval',
                        'type' => 'bool'
                    ),
                    'RS_AXIOMUS_USE_TOPDELIVERY_DELIVERY' => array(
                        'title' => $this->l('Использовать доставку TopDelivery'),
                        'desc' => $this->l('Если включено будет создана доставка TopDelivery через Axiomus API'),
                        'cast' => 'boolval',
                        'type' => 'bool'
                    ),
                    'RS_AXIOMUS_USE_DPD_DELIVERY' => array(
                        'title' => $this->l('Использовать доставку DPD'),
                        'desc' => $this->l('Если включено будет создана доставка DPD через Axiomus API'),
                        'cast' => 'boolval',
                        'type' => 'bool'
                    ),
                    'RS_AXIOMUS_USE_BOXBERRY_DELIVERY' => array(
                        'title' => $this->l('Использовать доставку BoxBerry'),
                        'desc' => $this->l('Если включено будет создана доставка BoxBerry через Axiomus API'),
                        'cast' => 'boolval',
                        'type' => 'bool'
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Сохранить'),
                ),
            ),
            'use_carry' => array(
                'title' => $this->l('Использование самовывоза'),
                'fields' => array(
                    'RS_AXIOMUS_USE_AXIOMUS_CARRY' => array(
                        'title' => $this->l('Использовать пункты самовывоза Axiomus'),
                        'desc' => $this->l('Если включено будет создана доставка Axiomus с пунктами самовывоза'),
                        'cast' => 'boolval',
                        'type' => 'bool'
                    ),
                    'RS_AXIOMUS_USE_TOPDELIVERY_CARRY' => array(
                        'title' => $this->l('Использовать пункты самовывоза TopDelivery'),
                        'desc' => $this->l('Если включено будет создана доставка TopDelivery через Axiomus API с пунктами самовывоза'),
                        'cast' => 'boolval',
                        'type' => 'bool'
                    ),
                    'RS_AXIOMUS_USE_DPD_CARRY' => array(
                        'title' => $this->l('Использовать пункты самовывоза DPD'),
                        'desc' => $this->l('Если включено будет создана доставка DPD через Axiomus API с пунктами самовывоза'),
                        'cast' => 'boolval',
                        'type' => 'bool'
                    ),
                    'RS_AXIOMUS_USE_BOXBERRY_CARRY' => array(
                        'title' => $this->l('Использовать пункты самовывоза BoxBerry'),
                        'desc' => $this->l('Если включено будет создана доставка BoxBerry через Axiomus API с пунктами самовывоза'),
                        'cast' => 'boolval',
                        'type' => 'bool'
                    ),
                    'RS_AXIOMUS_USE_RUSSIANPOST_CARRY' => array(
                        'title' => $this->l('Использовать пункты самовывоза Почты России'),
                        'desc' => $this->l('Если включено будет создана доставка Почта России через Axiomus API с пунктами самовывоза'),
                        'cast' => 'boolval',
                        'type' => 'bool'
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Сохранить'),
                ),
            )
        );
    }

    /*******************************************
     * Form to add new
     * */
    public function getTemplatePath()
    {
        return _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/';
    }

    public function createTemplate($tpl_name) {
        if (file_exists($this->getTemplatePath() . $tpl_name) && $this->viewAccess())
            return $this->context->smarty->createTemplate($this->getTemplatePath() . $tpl_name, $this->context->smarty);
        return parent::createTemplate($tpl_name);
    }

    public function initContent(){
        $this->_path = _PS_ROOT_DIR_.$this->module->getPathUri();
        $this->context->controller->addJS($this->_path.'/views/js/jquery.timepicker.min.js');
        $this->context->controller->addCSS($this->_path.'/views/css/jquery.timepicker.css', 'all');

        parent::initContent();

        $this->context->smarty->assign($this->getSettingsArray());
        $this->context->smarty->assign($this->AxiomusPost->getWeightPriceArray());
        $this->context->smarty->assign('AxiomusPost', $this->AxiomusPost);
        $this->setTemplate('view.tpl');

        /* DO STUFF HERE */
//        $posts = array();

//        $this->context->smarty->assign('posts', $posts);

    }

    public function getSettingsArray(){
        return [
            //CPU
            'maintab' => $this->maintab,
            'subtab' => $this->subtab,
            //Moscow
            'use_mscw_axiomus'              => Configuration::get('RS_AXIOMUS_MSCW_USE_AXIOMUS'),
            'use_mscw_topdelivery'          => Configuration::get('RS_AXIOMUS_MSCW_USE_TOPDELIVERY'),
            'use_mscw_dpd'                  => Configuration::get('RS_AXIOMUS_MSCW_USE_DPD'),
            'use_mscw_boxberry'             => Configuration::get('RS_AXIOMUS_MSCW_USE_BOXBERRY'),
            'use_mscw_axiomus_carry'        => Configuration::get('RS_AXIOMUS_MSCW_USE_AXIOMUS_CARRY'),
            'use_mscw_topdelivery_carry'    => Configuration::get('RS_AXIOMUS_MSCW_USE_TOPDELIVERY_CARRY'),
            'use_mscw_dpd_carry'            => Configuration::get('RS_AXIOMUS_MSCW_USE_DPD_CARRY'),
            'use_mscw_boxberry_carry'       => Configuration::get('RS_AXIOMUS_MSCW_USE_BOXBERRY_CARRY'),
            'use_mscw_russianpost_carry'    => Configuration::get('RS_AXIOMUS_MSCW_USE_RUSSIANPOST_CARRY'),
            //Piter
            'use_ptr_axiomus'              => Configuration::get('RS_AXIOMUS_PTR_USE_AXIOMUS'),
            'use_ptr_topdelivery'          => Configuration::get('RS_AXIOMUS_PTR_USE_TOPDELIVERY'),
            'use_ptr_dpd'                  => Configuration::get('RS_AXIOMUS_PTR_USE_DPD'),
            'use_ptr_boxberry_delivery'    => Configuration::get('RS_AXIOMUS_PTR_USE_BOXBERRY'),
            'use_ptr_axiomus_carry'        => Configuration::get('RS_AXIOMUS_PTR_USE_AXIOMUS_CARRY'),
            'use_ptr_topdelivery_carry'    => Configuration::get('RS_AXIOMUS_PTR_USE_TOPDELIVERY_CARRY'),
            'use_ptr_dpd_carry'            => Configuration::get('RS_AXIOMUS_PTR_USE_DPD_CARRY'),
            'use_ptr_boxberry_carry'       => Configuration::get('RS_AXIOMUS_PTR_USE_BOXBERRY_CARRY'),
            'use_ptr_russianpost_carry'    => Configuration::get('RS_AXIOMUS_PTR_USE_RUSSIANPOST_CARRY'),
            //Settings
            'axiomus_token'                => Configuration::get('RS_AXIOMUS_TOKEN'),
            'axiomus_cache_hourlife'       => Configuration::get('RS_AXIOMUS_CACHE_HOURLIFE'),
            //Moscow
            'mscw_axiomus_manual'          => Configuration::get('RS_AXIOMUS_MSCW_AXIOMUS_MANUAL'),
            'mscw_axiomus_increment'       => Configuration::get('RS_AXIOMUS_MSCW_AXIOMUS_INCREMENT'),
//            'mscw_axiomus_weight'          => Configuration::getMultiple('RS_AXIOMUS_MSCW_AXIOMUS_PRICE'),
        ];
    }

    public function postProcess()
    {
        //ToDo добавить проверку токена
        if (!($object = $this->loadObject(true))) {
            return;
        }

        $this->settingsArray = $this->getSettingsArray();

        if (Tools::isSubmit('submitUseDelivery')) {
            $this->maintab = 0;
            $this->subtab = 0;
            //Delivery
            if ((boolean)$_POST['use-mscw-axiomus'] != $this->settingsArray['use_mscw_axiomus']) {
                if ((boolean)$_POST['use-mscw-axiomus']) {
                    $res = $this->module->installCarrier('Axiomus', 'DELIVERY');

                } else {
                    $res = $this->module->uninstallCarrier('Axiomus', 'DELIVERY');
                }
                if ($res){
                    Configuration::updateValue('RS_AXIOMUS_MSCW_USE_AXIOMUS', $_POST['use-mscw-axiomus']);
                }
            }

            if ((boolean)$_POST['use-mscw-topdelivery'] != $this->settingsArray['use_mscw_topdelivery']) {
                if ((boolean)$_POST['use-mscw-topdelivery']) {
                    $res = $this->module->installCarrier('TopDelivery', 'DELIVERY');
                } else {
                    $res = $this->module->uninstallCarrier('TopDelivery', 'DELIVERY');
                }
                if ($res){
                    Configuration::updateValue('RS_AXIOMUS_MSCW_USE_TOPDELIVERY', $_POST['use-mscw-topdelivery']);
                }
            }
            if ((boolean)$_POST['use-mscw-dpd'] != $this->settingsArray['use_mscw_dpd']) {
                if ((boolean)$_POST['use-mscw-dpd']) {
                    $res = $this->module->installCarrier('DPD', 'DELIVERY');
                } else {
                    $res = $this->module->uninstallCarrier('DPD', 'DELIVERY');
                }
                if ($res){
                    Configuration::updateValue('RS_AXIOMUS_MSCW_USE_DPD', $_POST['use-mscw-dpd']);
                }
            }
            if ((boolean)$_POST['use-mscw-boxberry'] != $this->settingsArray['use_mscw_boxberry']) {
                if ((boolean)$_POST['use-mscw-boxberry']) {
                    $res = $this->module->installCarrier('BoxBerry', 'DELIVERY');
                } else {
                    $res = $this->module->uninstallCarrier('BoxBerry', 'DELIVERY');
                }
                if ($res){
                    Configuration::updateValue('RS_AXIOMUS_MSCW_USE_BOXBERRY', $_POST['use-mscw-boxberry']);
                }
            }
            //Carry
            if ((boolean)$_POST['use-mscw-axiomus-carry'] != $this->settingsArray['use_mscw_axiomus_carry']) {
                if ((boolean)$_POST['use-mscw-axiomus-carry']) {
                    $res = $this->module->installCarrier('Axiomus', 'CARRY');
                } else {
                    $res = $this->module->uninstallCarrier('Axiomus', 'CARRY');
                }
                if ($res){
                    Configuration::updateValue('RS_AXIOMUS_MSCW_USE_AXIOMUS_CARRY', $_POST['use-mscw-axiomus-carry']);
                }
            }
            if ((boolean)$_POST['use-mscw-topdelivery-carry'] != $this->settingsArray['use_mscw_topdelivery_carry']) {
                if ((boolean)$_POST['use-mscw-topdelivery-carry']) {
                    $res = $this->module->installCarrier('TopDelivery', 'CARRY');
                } else {
                    $res = $this->module->uninstallCarrier('TopDelivery', 'CARRY');
                }
                if ($res){
                    Configuration::updateValue('RS_AXIOMUS_MSCW_USE_TOPDELIVERY_CARRY', $_POST['use-mscw-topdelivery-carry']);
                }
            }
            if ((boolean)$_POST['use-mscw-dpd-carry'] != $this->settingsArray['use_mscw_dpd_carry']) {
                if ((boolean)$_POST['use-mscw-dpd-carry']) {
                    $res = $this->module->installCarrier('DPD', 'CARRY');
                } else {
                    $res = $this->module->uninstallCarrier('DPD', 'CARRY');
                }
                if ($res){
                    Configuration::updateValue('RS_AXIOMUS_MSCW_USE_DPD_CARRY', $_POST['use-mscw-dpd-carry']);
                }
            }
            if ((boolean)$_POST['use-mscw-boxberry-carry'] != $this->settingsArray['use_mscw_boxberry_carry']) {
                if ((boolean)$_POST['use-mscw-boxberry-carry']) {
                    $res = $this->module->installCarrier('BoxBerry', 'CARRY');
                } else {
                    $res = $this->module->uninstallCarrier('BoxBerry', 'CARRY');
                }
                if ($res){
                    Configuration::updateValue('RS_AXIOMUS_MSCW_USE_BOXBERRY_CARRY', $_POST['use-mscw-boxberry-carry']);
                }
            }
            if ((boolean)$_POST['use-mscw-russianpost-carry'] != $this->settingsArray['use_mscw_russianpost_carry']) {
                if ((boolean)$_POST['use-mscw-russianpost-carry']) {
                    $res = $this->module->installCarrier('RussianPost', 'CARRY');
                } else {
                    $res = $this->module->uninstallCarrier('RussianPost', 'CARRY');
                }
                if ($res){
                    Configuration::updateValue('RS_AXIOMUS_MSCW_USE_RUSSIANPOST_CARRY', $_POST['use-mscw-russianpost-carry']);
                }
            }
        }
        if (Tools::isSubmit('submitSettings')) {
            $this->maintab = 2;
            $this->subtab = 0;
            if ($_POST['axiomus-token'] != $this->settingsArray['axiomus_token']) {
                Configuration::updateValue('RS_AXIOMUS_TOKEN', $_POST['axiomus-token']);
            }
            if ($_POST['axiomus-cache-hourlife'] != $this->settingsArray['axiomus_cache_hourlife']) {
                Configuration::updateValue('RS_AXIOMUS_CACHE_HOURLIFE', $_POST['axiomus-cache-hourlife']);
            }
        }
        if (Tools::isSubmit('submitMscwAxiomusSettings')){
            $this->maintab = 0;
            $this->subtab = 1;
            if ((boolean)$_POST['mscw-axiomus-manual'] != $this->settingsArray['mscw_axiomus_manual']) {
                Configuration::updateValue('RS_AXIOMUS_MSCW_AXIOMUS_MANUAL', $_POST['mscw-axiomus-manual']);
            }
        }
        if (Tools::isSubmit('submitMscwAxiomusIncrement')) {
            $this->maintab = 0;
            $this->subtab = 1;
            if ($_POST['mscw-axiomus-increment'] != $this->settingsArray['mscw_axiomus_increment']) {
                Configuration::updateValue('RS_AXIOMUS_MSCW_AXIOMUS_INCREMENT', $_POST['mscw-axiomus-increment']);
            }
        }
        if (Tools::isSubmit('submitMscwAxiomusWeightPrice')) {
            $this->maintab = 0;
            $this->subtab = 1;

            foreach ($this->AxiomusPost->priceTypes as $type) { //ToDo валидация POST
                $this->AxiomusPost->setWeightPrice('mscw', 'axiomus', $type, $_POST['mscw-axiomus-price-'.$type]);
            }
        }
        if (Tools::isSubmit('submitMscwAxiomusConditionPrice')) {
            $this->maintab = 0;
            $this->subtab = 1;

            $this->AxiomusPost->insertConditionPrice('mscw', 'axiomus', $_POST['mscw-axiomus-price-sumfrom'], $_POST['mscw-axiomus-price-sumto'], $_POST['mscw-axiomus-price-timefrom'], $_POST['mscw-axiomus-price-timeto'], $_POST['mscw-axiomus-price-kadfrom'], $_POST['mscw-axiomus-price-kadto'], $_POST['mscw-axiomus-price-sum']);
        }
        if (Tools::isSubmit('deleteMscwAxiomusConditionPrice')) {
            $this->maintab = 0;
            $this->subtab = 1;

            $this->AxiomusPost->deleteConditionPrice((int)$_POST['mscw-axiomus-price-id']);
        }

        parent::postProcess();
    }

    public function getArrPrice($city, $delivery){
        //ToDo Запрос к бд
        $arr = [0 => '00', 1=>'11',3=>'33', 5=>'55',10=>'1010',15=>'1515',25=>2525];
        return $arr;
    }

    public function processSave()
    {
        parent::processSave();
    }

    public function renderView(){
        parent::renderView();
    }

    public function initProcess()
    {

        parent::initProcess();
//        global $smarty;
//        $smarty->display(_PS_MODULE_DIR_ .'axiomuspostcarrier/views/templates/admin/view.tpl');



    }

    protected function processUpdateOptions()
    {
        parent::processUpdateOptions();
    }

    public function getStateName($echo, $row)
    {
        $id_state = $row['id_state'];

        $state = new State($id_state);
        $cn = new Country(177);

        if ($state->id) {
            $country = Country::getNameById(Context::getContext()->language->id, $state->id_country);
            return "{$state->name} ({$country})";
        }

        return $this->l('Out of the World');
    }

    public function renderList()
    {
        $this->tpl_list_vars['postZones'] = array(
            array(
                'id_post_zone' => 1,
                'name' => $this->l('Zone 1'),
            ),
            array(
                'id_post_zone' => 2,
                'name' => $this->l('Zone 2'),
            ),
            array(
                'id_post_zone' => 3,
                'name' => $this->l('Zone 3'),
            ),
            array(
                'id_post_zone' => 4,
                'name' => $this->l('Zone 4'),
            ),
            array(
                'id_post_zone' => 5,
                'name' => $this->l('Zone 5'),
            ),
        );

        return parent::renderList();
    }

}
