<?php

include_once (_PS_MODULE_DIR_ . 'axiomuspostcarrier/models/AxiomusPost.php');

class AdminAxiomusConfigController extends ModuleAdminController
{
    public $settingsArray = [];
    public $maintab = 0;
    public $subtab = 0;

    public function __construct()
    {
        $this->settingsArray = AxiomusPost::getSettingsArray(true); //ToDo может в приват?

        //$this->context = Context::getContext();
        $this->bootstrap = true;
        parent::__construct();
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

        $this->context->smarty->assign([
            'maintab' => $this->maintab,
            'subtab' => $this->subtab
        ]);
        $this->context->smarty->assign(AxiomusPost::getSettingsArray(true));
        $this->context->smarty->assign($this->module->AxiomusPost->getWeightPriceArray());
        $this->context->smarty->assign('AxiomusPost', $this->module->AxiomusPost);
        $this->setTemplate('view.tpl');
    }



    /**
     *
     */
    public function postProcess()
    {
        //ToDo добавить проверку токена
        if (!($object = $this->loadObject(true))) {
            return;
        }

        $this->settingsArray = AxiomusPost::getSettingsArray(true);

        if (Tools::isSubmit('submitUseDelivery')) {
            $this->maintab = 0;
            $this->subtab = 0;
            //Delivery
            if ((boolean)$_POST['use-mscw-axiomus'] != $this->settingsArray['use_mscw_axiomus']) {
                Configuration::updateValue('RS_AXIOMUS_MSCW_USE_AXIOMUS', $_POST['use-mscw-axiomus']);
            }
            if ((boolean)$_POST['use-mscw-strizh'] != $this->settingsArray['use_mscw_strizh']) {
                Configuration::updateValue('RS_AXIOMUS_MSCW_USE_STRIZH', $_POST['use-mscw-strizh']);
            }
            if ((boolean)$_POST['use-mscw-pecom'] != $this->settingsArray['use_mscw_pecom']) {
                Configuration::updateValue('RS_AXIOMUS_MSCW_USE_PECOM', $_POST['use-mscw-pecom']);
            }
            //Carry
            if ((boolean)$_POST['use-mscw-axiomus-carry'] != $this->settingsArray['use_mscw_axiomus_carry']) {
                Configuration::updateValue('RS_AXIOMUS_MSCW_USE_AXIOMUS_CARRY', $_POST['use-mscw-axiomus-carry']);
            }
            if ((boolean)$_POST['use-mscw-dpd-carry'] != $this->settingsArray['use_mscw_dpd_carry']) {
                Configuration::updateValue('RS_AXIOMUS_MSCW_USE_DPD_CARRY', $_POST['use-mscw-dpd-carry']);
            }
            if ((boolean)$_POST['use-mscw-boxberry-carry'] != $this->settingsArray['use_mscw_boxberry_carry']) {
                Configuration::updateValue('RS_AXIOMUS_MSCW_USE_BOXBERRY_CARRY', $_POST['use-mscw-boxberry-carry']);
            }
            if ((boolean)$_POST['use-mscw-russianpost-carry'] != $this->settingsArray['use_mscw_russianpost_carry']) {
                Configuration::updateValue('RS_AXIOMUS_MSCW_USE_RUSSIANPOST_CARRY', $_POST['use-mscw-russianpost-carry']);
            }
            if ((boolean)$_POST['use-mscw-pecom-carry'] != $this->settingsArray['use_mscw_pecom_carry']) {
                Configuration::updateValue('RS_AXIOMUS_MSCW_USE_PECOM_CARRY', $_POST['use-mscw-pecom-carry']);
            }
        }

        //weightype
        if (Tools::isSubmit('submitMscwAxiomusWeightType')) {
            $this->maintab = 0;
            $this->subtab = 0;
            $this->module->AxiomusPost->insertWeightType($_POST['mscw-axiomus-weighttype-name'], $_POST['mscw-axiomus-weighttype-weightfrom'], $_POST['mscw-axiomus-weighttype-weightto']);
        }
        if (Tools::isSubmit('updateMscwAxiomusWeightType')) {
            $this->maintab = 0;
            $this->subtab = 0;
            $this->module->AxiomusPost->updateWeightType($_POST['mscw-axiomus-weighttype-id'], $_POST['mscw-axiomus-weighttype-name'], $_POST['mscw-axiomus-weighttype-weightfrom'], $_POST['mscw-axiomus-weighttype-weightto']);
        }
        if (Tools::isSubmit('deleteMscwAxiomusWeightType')) {
            $this->maintab = 0;
            $this->subtab = 0;
            $this->module->AxiomusPost->deleteWeightType((int)$_POST['mscw-axiomus-weighttype-id']);
        }
        //timetype
        if (Tools::isSubmit('submitMscwAxiomusTimeType')) {
            $this->maintab = 0;
            $this->subtab = 0;
            $this->module->AxiomusPost->insertTimeType($_POST['mscw-axiomus-timetype-name'], $_POST['mscw-axiomus-timetype-timefrom'], $_POST['mscw-axiomus-timetype-timeto']);
        }
        if (Tools::isSubmit('updateMscwAxiomusTimeType')) {
            $this->maintab = 0;
            $this->subtab = 0;
            $this->module->AxiomusPost->updateTimeType($_POST['mscw-axiomus-timetype-id'], $_POST['mscw-axiomus-timetype-name'], $_POST['mscw-axiomus-timetype-timefrom'], $_POST['mscw-axiomus-timetype-timeto']);
        }
        if (Tools::isSubmit('deleteMscwAxiomusTimeType')) {
            $this->maintab = 0;
            $this->subtab = 0;
            $this->module->AxiomusPost->deleteTimeType((int)$_POST['mscw-axiomus-timetype-id']);
        }
        //kadtype
        if (Tools::isSubmit('submitMscwAxiomusKadType')) {
            $this->maintab = 0;
            $this->subtab = 1;
            $this->module->AxiomusPost->insertKadType('Москва', $_POST['mscw-axiomus-kadtype-name'], $_POST['mscw-axiomus-kadtype-rangefrom'], $_POST['mscw-axiomus-kadtype-rangeto']);
        }
        if (Tools::isSubmit('updateMscwAxiomusKadType')) {
            $this->maintab = 0;
            $this->subtab = 1;
            $this->module->AxiomusPost->updateKadType($_POST['mscw-axiomus-kadtype-id'], 'Москва', $_POST['mscw-axiomus-kadtype-name'], $_POST['mscw-axiomus-kadtype-rangefrom'], $_POST['mscw-axiomus-kadtype-rangeto']);
        }
        if (Tools::isSubmit('deleteMscwAxiomusKadType')) {
            $this->maintab = 0;
            $this->subtab = 1;
            $this->module->AxiomusPost->deleteKadType((int)$_POST['mscw-axiomus-kadtype-id']);
        }
        //weightprice
        if (Tools::isSubmit('submitMscwAxiomusWeightPrice')) {
            $this->maintab = 0;
            $this->subtab = 1;
            $this->module->AxiomusPost->insertWeightPrice('Москва', 'axiomus',$_POST['mscw-axiomus-weightprice-carry'], $_POST['mscw-axiomus-weightprice-type'], $_POST['mscw-axiomus-weightprice-sum']);
        }
        if (Tools::isSubmit('updateMscwAxiomusWeightPrice')) {
            $this->maintab = 0;
            $this->subtab = 1;
            $this->module->AxiomusPost->updateWeightPrice($_POST['mscw-axiomus-weightprice-id'], 'Москва', 'axiomus',$_POST['mscw-axiomus-weightprice-carry'], $_POST['mscw-axiomus-weightprice-type'], $_POST['mscw-axiomus-weightprice-sum']);
        }
        if (Tools::isSubmit('deleteMscwAxiomusWeightPrice')) {
            $this->maintab = 0;
            $this->subtab = 1;
            $this->module->AxiomusPost->deleteConditionPrice((int)$_POST['mscw-axiomus-weightprice-id']);
        }
        //conditionprice
        if (Tools::isSubmit('submitMscwAxiomusConditionPrice')) {
            $this->maintab = 0;
            $this->subtab = 1;
            if(isset($_POST['mscw-axiomus-conditionprice-carry'])){
                $carry = ($_POST['mscw-axiomus-conditionprice-carry']=='on')?true:false;
            }else{
                $carry = false;
            }
            $this->module->AxiomusPost->insertConditionPrice('Москва', 'axiomus', $carry, $_POST['mscw-axiomus-conditionprice-sumfrom'], $_POST['mscw-axiomus-conditionprice-sumto'], $_POST['mscw-axiomus-conditionprice-timetype'], $_POST['mscw-axiomus-conditionprice-kadtype'], $_POST['mscw-axiomus-conditionprice-sum']);
        }
        if (Tools::isSubmit('updateMscwAxiomusConditionPrice')) {
            $this->maintab = 0;
            $this->subtab = 1;
            $this->module->AxiomusPost->updateConditionPrice($_POST['mscw-axiomus-conditionprice-id'], 'Москва', 'axiomus', ($_POST['mscw-axiomus-conditionprice-carry']=='on')?true:false, $_POST['mscw-axiomus-conditionprice-sumfrom'], $_POST['mscw-axiomus-conditionprice-sumto'], $_POST['mscw-axiomus-conditionprice-timetype'], $_POST['mscw-axiomus-conditionprice-kadtype'], $_POST['mscw-axiomus-conditionprice-sum']);
        }
        if (Tools::isSubmit('deleteMscwAxiomusConditionPrice')) {
            $this->maintab = 0;
            $this->subtab = 1;
            $this->module->AxiomusPost->deleteConditionPrice((int)$_POST['mscw-axiomus-conditionprice-id']);
        }
        //cachecarry
        if (Tools::isSubmit('submitRefreshCacheCarryAddressesAxiomus')){
            $this->maintab = 3;
            $this->subtab = 2;
            $this->module->AxiomusPost->refreshCarryAddressCacheAxiomus();
        }
        if (Tools::isSubmit('submitRefreshCacheCarryAddressesDPD')){
            $this->maintab = 3;
            $this->subtab = 2;
            $this->module->AxiomusPost->refreshCarryAddressCacheDPD();
        }
        if (Tools::isSubmit('submitRefreshCacheCarryAddressesBoxBerry')){
            $this->maintab = 3;
            $this->subtab = 2;
            $this->module->AxiomusPost->refreshCarryAddressCacheBoxBerry();
        }
        if (Tools::isSubmit('submitRefreshCacheCarryAddressesPecom')){
            $this->maintab = 3;
            $this->subtab = 2;
            $this->module->AxiomusPost->refreshCarryAddressCachePecom();
        }
        //carry
        if (Tools::isSubmit('submitMscwAxiomusCarryPrice')){
            $this->maintab = 0;
            $this->subtab = 6;
            $this->module->AxiomusPost->setCarryPrice('Москва', 'axiomus', (int)$_POST['mscw-carry-axiomus-price']);
        }
        if (Tools::isSubmit('submitMscwDPDPrice')){
            $this->maintab = 0;
            $this->subtab = 7;
            $this->module->AxiomusPost->setCarryPrice('Москва', 'dpd', (int)$_POST['mscw-carry-dpd-price']);
        }
        if (Tools::isSubmit('submitMscwBoxBerryPrice')){
            $this->maintab = 0;
            $this->subtab = 8;
            $this->module->AxiomusPost->setCarryPrice('Москва', 'boxberry', (int)$_POST['mscw-carry-boxberry-price']);
        }

        if (Tools::isSubmit('submitSettingsAxiomus')) {
            $this->maintab = 3;
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

        //Settings-pecom
        if (Tools::isSubmit('submitSettingsPecomSender')) {
            $this->maintab = 3;
            $this->subtab = 1;
            foreach (AxiomusPost::getSettingsArray(false)['pecom_sender'] as $key => $item) {
                if ($_POST[$key] != Configuration::get($item)) {
                    Configuration::updateValue($item, $_POST[$key]);
                }
            }
        }
        if (Tools::isSubmit('submitSettingsPecomDefault')) {
            $this->maintab = 3;
            $this->subtab = 1;
            foreach (AxiomusPost::getSettingsArray(false)['pecom_default'] as $key => $item) {
                if ($_POST[$key] != Configuration::get($item)) {
                    Configuration::updateValue($item, $_POST[$key]);
                }
            }
        }
        if (Tools::isSubmit('submitSettingsPecom')) {
            $this->maintab = 3;
            $this->subtab = 1;
            foreach (AxiomusPost::getSettingsArray(false)['pecom_settings'] as $key => $item) {
                if ($_POST[$key] != Configuration::get($item)) {
                    Configuration::updateValue($item, $_POST[$key]);
                }
            }
        }

//        $ls = AxiomusXml::getCarryAddressesPecom();

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
