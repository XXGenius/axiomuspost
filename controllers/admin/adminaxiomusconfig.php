<?php

class AdminAxiomusConfigController extends ModuleAdminController
{

    public $settingsArray = [];

    public function __construct()
    {
        $this->settingsArray = $this->getSettingsArray(); //ToDo может в приват?

//        $this->table = 'axiomus_config';
//        $this->className = 'AxiomusPost';
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
        parent::initContent();
        $this->context->smarty->assign($this->getSettingsArray());
        $this->setTemplate('view.tpl');

        /* DO STUFF HERE */
//        $posts = array();

//        $this->context->smarty->assign('posts', $posts);

    }

    public function getSettingsArray(){
        return [
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
        ];
    }

    public function getContent()
    {
        global $smarty;
        $smarty->display(_PS_MODULE_DIR_ .'axiomuspostcarrier/views/templates/admin/view.tpl');
        exit;
    }

    public function renderForm()
    {

        $this->fields_form = array(
            'legend' => array(
                'title' => 'Legend',
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->l('Country'),
                    'name' => 'id_country',
                    'options' => array(
                        'query' => Country::getCountries($this->context->language->id, true, true),
                        'id' => 'id_country',
                        'name' => 'name',
                        //'default' => array('value'=>$this->context->country->id, 'label'=>$this->l($this->context->country->name)),//array() or value???
                    ),
                    'required' => true,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('State'),
                    'name' => 'id_state',
                    'required' => true,
                    'options' => array(
                        'query' => State::getStates(),
                        'id' => 'id_state',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => 'Tariff Zone',
                    'name' => 'id_post_zone',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 1,
                                'value' => 'Zone 1'
                            ),
                            array(
                                'id' => 2,
                                'value' => 'Zone 2'
                            ),
                            array(
                                'id' => 3,
                                'value' => 'Zone 3'
                            ),
                            array(
                                'id' => 4,
                                'value' => 'Zone 4'
                            ),
                            array(
                                'id' => 5,
                                'value' => 'Zone 5'
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'value'
                    ),
                    'required' => true
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Status'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        ),
                    ),
                    'desc' => $this->l('Enable delivery to this Country/State'),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button',
            ),
        );

        return parent::renderForm();
    }

    public function postProcess()
    {
        //ToDo добавить проверку токена
        if (!($object = $this->loadObject(true))) {
            return;
        }

        $this->settingsArray = $this->getSettingsArray();

        if (Tools::isSubmit('submitUseDelivery')) {
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
            if ($_POST['axiomus-token'] != $this->settingsArray['axiomus_token']) {
                Configuration::updateValue('RS_AXIOMUS_TOKEN', $_POST['axiomus-token']);
            }
            if ($_POST['axiomus-cache-hourlife'] != $this->settingsArray['axiomus_cache_hourlife']) {
                Configuration::updateValue('RS_AXIOMUS_CACHE_HOURLIFE', $_POST['axiomus-cache-hourlife']);
            }
        }
        parent::postProcess();
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
