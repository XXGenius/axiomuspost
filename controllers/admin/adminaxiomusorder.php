<?php

class AdminAxiomusOrderController extends ModuleAdminController
{

    public function __construct()
    {

        $this->table = 'axiomus_order';
        $this->className = 'AxiomusPost';
        $this->identifier = 'id';

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

        $this->fields_list = array(
            'id' => array(
                'title' => $this->l('id'),
                'align' => 'center',
//                'width' => 20
            ),
            'order_id' => array(
                'title' => $this->l('id заказа'),
                'width' => 'auto',
//                'callback' => 'getStateName',
            ),
            'order_code' => array(
                'title' => $this->l('Код заказа'),
                'width' => 'auto'
            ),
            'carry' => array(
                'title' => $this->l('Самовывоз'),
                'active' => 'status',
                'type' => 'bool',
                'align' => 'center'
            ),
            'company_name' => array(
                'title' => $this->l('Компания'),
                'width' => 'auto'
            ),
            'address' => array(
                'title' => $this->l('Адрес'),
                'width' => 'auto'
            ),
            'track_number' => array(
                'title' => $this->l('Трек-номер'),
                'width' => 'auto'
            ),
            'status' => array(
                'title' => $this->l('Статус'),
                'width' => 'auto'
            ),
            'finish_datetime' => array(
                'title' => $this->l('Завершено'),
                'width' => 'auto'
            ),
            'send_datetime' => array(
                'title' => $this->l('Отправлено'),
                'width' => 'auto'
            ),
            'create_datetime' => array(
                'title' => $this->l('Создано'),
                'width' => 'auto'
            ),
        );
    }

    /*******************************************
     * Form to add new
     * */
//
//    public function renderForm()
//    {
//
//        $this->fields_form = array(
//            'legend' => array(
//                'title' => 'Legend',
//            ),
//            'input' => array(
//                array(
//                    'type' => 'select',
//                    'label' => $this->l('Country'),
//                    'name' => 'id_country',
//                    'options' => array(
//                        'query' => Country::getCountries($this->context->language->id, true, true),
//                        'id' => 'id_country',
//                        'name' => 'name',
//                        //'default' => array('value'=>$this->context->country->id, 'label'=>$this->l($this->context->country->name)),//array() or value???
//                    ),
//                    'required' => true,
//                ),
//                array(
//                    'type' => 'select',
//                    'label' => $this->l('State'),
//                    'name' => 'id_state',
//                    'required' => true,
//                    'options' => array(
//                        'query' => State::getStates(),
//                        'id' => 'id_state',
//                        'name' => 'name'
//                    ),
//                ),
//                array(
//                    'type' => 'select',
//                    'label' => 'Tariff Zone',
//                    'name' => 'id_post_zone',
//                    'options' => array(
//                        'query' => array(
//                            array(
//                                'id' => 1,
//                                'value' => 'Zone 1'
//                            ),
//                            array(
//                                'id' => 2,
//                                'value' => 'Zone 2'
//                            ),
//                            array(
//                                'id' => 3,
//                                'value' => 'Zone 3'
//                            ),
//                            array(
//                                'id' => 4,
//                                'value' => 'Zone 4'
//                            ),
//                            array(
//                                'id' => 5,
//                                'value' => 'Zone 5'
//                            ),
//                        ),
//                        'id' => 'id',
//                        'name' => 'value'
//                    ),
//                    'required' => true
//                ),
//                array(
//                    'type' => 'radio',
//                    'label' => $this->l('Status'),
//                    'name' => 'active',
//                    'required' => false,
//                    'is_bool' => true,
//                    'class' => 't',
//                    'values' => array(
//                        array(
//                            'id' => 'active_on',
//                            'value' => 1,
//                            'label' => $this->l('Enabled')
//                        ),
//                        array(
//                            'id' => 'active_off',
//                            'value' => 0,
//                            'label' => $this->l('Disabled')
//                        ),
//                    ),
//                    'desc' => $this->l('Enable delivery to this Country/State'),
//                ),
//            ),
//            'submit' => array(
//                'title' => $this->l('Save'),
//                'class' => 'button',
//            ),
//        );
//
//        return parent::renderForm();
//    }

    public function postProcess()
    {
        //ToDo добавить проверку токена
        if (!($object = $this->loadObject(true))) {
            return;
        }

        if (Tools::isSubmit('submitOptionsaxiomus_post')) {
            //Delivery

            if ((boolean)$_POST['RS_AXIOMUS_USE_AXIOMUS_DELIVERY'] != (boolean)Configuration::get('RS_AXIOMUS_USE_AXIOMUS_DELIVERY')) {
                if ((boolean)$_POST['RS_AXIOMUS_USE_AXIOMUS_DELIVERY']) {
                    $this->module->installCarrier('Axiomus', 'DELIVERY');
                } else {
                    $this->module->uninstallCarrier('Axiomus', 'DELIVERY');
                }
            }
            if ((boolean)$_POST['RS_AXIOMUS_USE_TOPDELIVERY_DELIVERY'] != (boolean)Configuration::get('RS_AXIOMUS_USE_TOPDELIVERY_DELIVERY')) { 
                if ((boolean)$_POST['RS_AXIOMUS_USE_TOPDELIVERY_DELIVERY']) {
                    $this->module->installCarrier('TopDelivery', 'DELIVERY');
                } else {
                    $this->module->uninstallCarrier('TopDelivery', 'DELIVERY');
                }
            }
            if ((boolean)$_POST['RS_AXIOMUS_USE_DPD_DELIVERY'] != (boolean)Configuration::get('RS_AXIOMUS_USE_DPD_DELIVERY')) { 
                if ((boolean)$_POST['RS_AXIOMUS_USE_DPD_DELIVERY']) {
                    $this->module->installCarrier('DPD', 'DELIVERY');
                } else {
                    $this->module->uninstallCarrier('DPD', 'DELIVERY');
                }
            }
            if ((boolean)$_POST['RS_AXIOMUS_USE_BOXBERRY_DELIVERY'] != (boolean)Configuration::get('RS_AXIOMUS_USE_BOXBERRY_DELIVERY')) { 
                if ((boolean)$_POST['RS_AXIOMUS_USE_BOXBERRY_DELIVERY']) {
                    $this->module->installCarrier('BoxBerry', 'DELIVERY');
                } else {
                    $this->module->uninstallCarrier('BoxBerry', 'DELIVERY');
                }
            }
            //Carry
            if ((boolean)$_POST['RS_AXIOMUS_USE_AXIOMUS_CARRY'] != (boolean)Configuration::get('RS_AXIOMUS_USE_AXIOMUS_CARRY')) { 
                if ((boolean)$_POST['RS_AXIOMUS_USE_AXIOMUS_CARRY']) {
                    $this->module->installCarrier('Axiomus', 'CARRY');
                } else {
                    $this->module->uninstallCarrier('Axiomus', 'CARRY');
                }
            }
            if ((boolean)$_POST['RS_AXIOMUS_USE_TOPDELIVERY_CARRY'] != (boolean)Configuration::get('RS_AXIOMUS_USE_TOPDELIVERY_CARRY')) { 
                if ((boolean)$_POST['RS_AXIOMUS_USE_TOPDELIVERY_CARRY']) {
                    $this->module->installCarrier('TopDelivery', 'CARRY');
                } else {
                    $this->module->uninstallCarrier('TopDelivery', 'CARRY');
                }
            }
            if ((boolean)$_POST['RS_AXIOMUS_USE_DPD_CARRY'] != (boolean)Configuration::get('RS_AXIOMUS_USE_DPD_CARRY')) { 
                if ((boolean)$_POST['RS_AXIOMUS_USE_DPD_CARRY']) {
                    $this->module->installCarrier('DPD', 'CARRY');
                } else {
                    $this->module->uninstallCarrier('DPD', 'CARRY');
                }
            }
            if ((boolean)$_POST['RS_AXIOMUS_USE_BOXBERRY_CARRY'] != (boolean)Configuration::get('RS_AXIOMUS_USE_BOXBERRY_CARRY')) { 
                if ((boolean)$_POST['RS_AXIOMUS_USE_BOXBERRY_CARRY']) {
                    $this->module->installCarrier('BoxBerry', 'CARRY');
                } else {
                    $this->module->uninstallCarrier('BoxBerry', 'CARRY');
                }
            }
            if ((boolean)$_POST['RS_AXIOMUS_USE_RUSSIANPOST_CARRY'] != (boolean)Configuration::get('RS_AXIOMUS_USE_RUSSIANPOST_CARRY')) { 
                if ((boolean)$_POST['RS_AXIOMUS_USE_RUSSIANPOST_CARRY']) {
                    $this->module->installCarrier('RussianPost', 'CARRY');
                } else {
                    $this->module->uninstallCarrier('RussianPost', 'CARRY');
                }
            }
        }
        parent::postProcess();
    }



    public function processSave()
    {
        parent::processSave();
    }

    public function initProcess()
    {
        parent::initProcess();
    }

    protected function processUpdateOptions()
    {
        parent::processUpdateOptions();
    }
//
//    public function getStateName($echo, $row)
//    {
//        $id_state = $row['id_state'];
//
//        $state = new State($id_state);
//        $cn = new Country(177);
//
//        if ($state->id) {
//            $country = Country::getNameById(Context::getContext()->language->id, $state->id_country);
//            return "{$state->name} ({$country})";
//        }
//
//        return $this->l('Out of the World');
//    }
//
//    public function renderList()
//    {
//        $this->tpl_list_vars['postZones'] = array(
//            array(
//                'id_post_zone' => 1,
//                'name' => $this->l('Zone 1'),
//            ),
//            array(
//                'id_post_zone' => 2,
//                'name' => $this->l('Zone 2'),
//            ),
//            array(
//                'id_post_zone' => 3,
//                'name' => $this->l('Zone 3'),
//            ),
//            array(
//                'id_post_zone' => 4,
//                'name' => $this->l('Zone 4'),
//            ),
//            array(
//                'id_post_zone' => 5,
//                'name' => $this->l('Zone 5'),
//            ),
//        );
//
//        return parent::renderList();
//    }

}
