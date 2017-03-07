<?php

/**
 *
 * TODO: Разобраться с исключениями, они здесь просто напрашиваются
 *
 * */
if (!defined('_PS_VERSION_'))
    exit;

require_once(_PS_MODULE_DIR_ . 'axiomuspostcarrier/models/AxiomusPost.php');
require_once(_PS_MODULE_DIR_ . 'axiomuspostcarrier/axiomusFunctions.php');

class axiomuspostcarrier extends CarrierModule
{

    private $model;
    // Хоть и неочевидно, но здесь это должно быть. Кем-то присваивается.
    public $id_carrier;

    private $_postErrors = array();

    public function __construct()
    {

        $this->name = 'axiomuspostcarrier';
        $this->tab = 'shipping_logistics';
        $this->version = '1.0.6';
        $this->author = 'Robert Spectrum';

        parent::__construct();

        $this->displayName = $this->l('Axiomus Post');
        $this->description = $this->l('Calculate a shipping cost using Axiomus Post formulas');

        $this->AxiomusPost = new AxiomusPost();

        $this->carrierIdStr = 'RS_AXIOMUS_POST_CARRIER_ID';
    }

    private function getCarrierName()
    {
        if (Configuration::get('RS_AXIOMUS_USE_AXIOMUS_DELIVERY')) {
            if ($this->id_carrier == (int)Configuration::get('RS_AXIOMUS_ID_AXIOMUS_DELIVERY')) {
                return 'Axiomus delivery';
            }
        }
        if (Configuration::get('RS_AXIOMUS_USE_TOPDELIVERY_DELIVERY')) {
            if ($this->id_carrier == (int)Configuration::get('RS_AXIOMUS_ID_TOPDELIVERY_DELIVERY')) {
                return 'TopDelivery delivery';
            }
        }
        if (Configuration::get('RS_AXIOMUS_USE_DPD_DELIVERY')) {
            if ($this->id_carrier == (int)Configuration::get('RS_AXIOMUS_ID_DPD_DELIVERY')) {
                return 'DPD delivery';
            }
        }
        if (Configuration::get('RS_AXIOMUS_USE_BOXBERRY_DELIVERY')) {
            if ($this->id_carrier == (int)Configuration::get('RS_AXIOMUS_ID_BOXBERRY_DELIVERY')) {
                return 'BoxBerry delivery';
            }
        }
        if (Configuration::get('RS_AXIOMUS_USE_AXIOMUS_CARRY')) {
            if ($this->id_carrier == (int)Configuration::get('RS_AXIOMUS_ID_AXIOMUS_CARRY')) {
                return 'Axiomus carry';
            }
        }
        if (Configuration::get('RS_AXIOMUS_USE_TOPDELIVERY_CARRY')) {
            if ($this->id_carrier == (int)Configuration::get('RS_AXIOMUS_ID_TOPDELIVERY_CARRY')) {
                return 'TopDelivery carry';
            }
        }
        if (Configuration::get('RS_AXIOMUS_USE_DPD_CARRY')) {
            if ($this->id_carrier == (int)Configuration::get('RS_AXIOMUS_ID_DPD_CARRY')) {
                return 'DPD carry';
            }
        }
        if (Configuration::get('RS_AXIOMUS_USE_BOXBERRY_CARRY')) {
            if ($this->id_carrier == (int)Configuration::get('RS_AXIOMUS_ID_BOXBERRY_CARRY')) {
                return 'BoxBerry carry';
            }
        }
        if (Configuration::get('RS_AXIOMUS_USE_RUSSIANPOST_CARRY')) {
            if ($this->id_carrier == (int)Configuration::get('RS_AXIOMUS_ID_RUSSIANPOST_CARRY')) {
                return 'RussianPost carry';
            }
        }
        return '';
    }

    public function getOrderShippingCost($params, $shipping_cost)
    { //В $params лежит  объект типа Cart.

//        $axiomus = new AxiomusApi(Configuration::get("RS_AXIOMUS_TOKEN"));
//        $axiomus->sendXML();
        // TODO: разобраться с проверкой кук
//        if ($this->id_carrier != (int) Configuration::get('RS_AXIOMUS_POST_CARRIER_ID')){
//            return false;
//        }else {
        /*
         * Здесь напишем функцию которая ищет адрес и вес из новой таблицы бд
         * Если такая запись находится то берем данные оттуда, если нет, то ищем новые данные
         * Срок жизни записи в такой таблице меняется в админке
         */
        $carrierName = $this->getCarrierName();
        $totalsum = 0;
        $totalPrice = 0;
        $addr = new Address($params->id_address_delivery);
        if (!Validate::isLoadedObject($addr))
            return false;
        $products = $params->getProducts();
        $totalWeight = $params->getTotalWeight();
        foreach ($products as $product) {
            $totalPrice += (float)$product['total_wt']; //ToDo а точно ли не total?
        }

        $cachePrice = $this->AxiomusPost->getPriceInCache($addr->id, $totalWeight);
        if ($cachePrice != false) {
            if ($carrierName == 'Axiomus delivery') {
                $sum_carrier = $cachePrice['price_axiomus_delivery'];
            } elseif ($carrierName == 'TopDelivery delivery') {
                $sum_carrier = $cachePrice['price_topdelivery_delivery'];
            } elseif ($carrierName == 'DPD delivery') {
                $sum_carrier = $cachePrice['price_dpd_delivery'];
            } elseif ($carrierName == 'BoxBerry delivery') {
                $sum_carrier = $cachePrice['price_boxberry_delivery'];
            } elseif ($carrierName == 'Axiomus carry') {
                $sum_carrier = $cachePrice['price_axiomus_carry'];
            } elseif ($carrierName == 'TopDelivery carry') {
                $sum_carrier = $cachePrice['price_topdelivery_carry'];
            } elseif ($carrierName == 'DPD carry') {
                $sum_carrier = $cachePrice['price_dpd_carry'];
            } elseif ($carrierName == 'BoxBerry carry') {
                $sum_carrier = $cachePrice['price_boxberry_carry'];
            } elseif ($carrierName == 'RussianPost carry') {
                $sum_carrier = $cachePrice['price_russianpost_carry'];
            } else {
                $sum_carrier = 0; //Заглушка
            }
            $totalsum = $sum_carrier; //Тут отдавать цену для всех доставок если они включены
        } else {
            $width = 0;
            $height = 0;
            $depth = 0;
            $val = $totalPrice;
            $weight = $totalWeight;
            $addressString = $addr->city . ', ' . $addr->address1; //ToDo добавить проверку на заполнение этих параметров //ToDo не забыть про address2

            $axiomusResponse = getAxiomusResponse($addressString, $height, $width, $depth, $val, $weight);
            if (empty($axiomusResponse['error'])) {
                $priceAxiomusDelivery = null;
                $priceTopDeliveryDelivery = null;
                $priceDPDDelivery = null;
                $priceBoxBerryDelivery = null;
                $priceAxiomusCarry = null;
                $priceTopDeliveryCarry = null;
                $priceDPDCarry = null;
                $priceBoxBerryCarry = null;
                $priceRussianPostCarry = null;
                foreach ($axiomusResponse['delivery'] as $delivery){
                    if (isset($delivery['Axiomus']['delivery'][0]['price'])) $priceAxiomusDelivery = $delivery['Axiomus']['delivery'][0]['price'];
                    if (isset($delivery['TopDelivery']['delivery'][0]['price'])) $priceTopDeliveryDelivery = $delivery['TopDelivery']['delivery'][0]['price'];
                    if (isset($delivery['DPD']['delivery'][0]['price'])) $priceDPDDelivery = $delivery['DPD']['delivery'][0]['price'];
                    if (isset($delivery['BoxBerry']['delivery'][0]['price'])) $priceBoxBerryDelivery = $delivery['BoxBerry']['delivery'][0]['price'];
                }
                //ToDo переработать самовывоз
//                foreach ($axiomusResponse['carry'] as $carry){
//                    if (isset($carry['calc']['Axiomus'])){
//
//                    }
//                    if (isset($carry['calc']['TopDelivery'])){
//                        $pvzTopDelivery[] = $carry['pvz'];
//                        $priceTopDeliveryCarry[] = $carry['calc']['TopDelivery'][''];
//                    }
//                    if (isset($carry['calc']['DPD'])){
//                        $pvzDPD[] = $carry['pvz'];
//                        $priceDPDCarry[] = $carry['calc']['DPD'][''];
//                    }
//                    if (isset($carry['calc']['BoxBerry'])){
//
//                    }
//                    if (isset($carry['calc']['PRF'])){
//
//                    }
//
//                }
                $this->AxiomusPost->insertRowCache($addr->id, $totalWeight, $priceAxiomusDelivery, $priceTopDeliveryDelivery, $priceDPDDelivery, $priceBoxBerryDelivery, $priceAxiomusCarry, $priceTopDeliveryCarry, $priceDPDCarry, $priceBoxBerryCarry, $priceRussianPostCarry);

                //ToDo не забыть проверить на неправильных адресах
                if ($carrierName == 'Axiomus delivery') {
                    $sum_carrier = $priceAxiomusDelivery;
                } elseif ($carrierName == 'TopDelivery delivery') {
                    $sum_carrier = $priceTopDeliveryDelivery;
                } elseif ($carrierName == 'DPD delivery') {
                    $sum_carrier = $priceDPDDelivery;
                } elseif ($carrierName == 'BoxBerry delivery') {
                    $sum_carrier = $priceBoxBerryDelivery;
                } elseif ($carrierName == 'Axiomus carry') {
                    $sum_carrier = $priceAxiomusCarry;
                } elseif ($carrierName == 'TopDelivery carry') {
                    $sum_carrier = $priceTopDeliveryCarry;
                } elseif ($carrierName == 'DPD carry') {
                    $sum_carrier = $priceDPDCarry;
                } elseif ($carrierName == 'BoxBerry carry') {
                    $sum_carrier = $priceBoxBerryCarry;
                } elseif ($carrierName == 'RussianPost carry') {
                    $sum_carrier = $priceRussianPostCarry;
                } else {
                    $sum_carrier = 0; //Заглушка
                }

                $totalsum = $sum_carrier;
            }else{
                $totalsum = 0;
            }
        }
        return ($totalsum==0?false:$totalsum);
    }

    public function getOrderShippingCostExternal($params)
    {

        //ToDo добавить возможность в адмике выбирать нужно ли использовать диапазоны и этот метод
        return $this->getOrderShippingCost($params, 0);
    }

    public function install()
    {

        $idCarrier = $this->installCarrier('Axiomus', 'DELIVERY');

        $res = false;

        // Не удалось создать
        if (!$idCarrier) {
            return false;
        }

        if (!$this->AxiomusPost->createTable()) {
            $this->uninstallAllCarrier();
        }

        // Здесь мы создаем пункт вехнего подменю.
        $idTab = Tab::getIdFromClassName('AdminAxiomusConfig');
        if (!$idTab) {
            $tab = new Tab();
            $tab->class_name = 'AdminAxiomusConfig';
            $tab->module = 'axiomuspostcarrier';
            $tab->id_parent = Tab::getIdFromClassName('AdminParentShipping');

            $languages = Language::getLanguages(false);
            foreach ($languages as $lang) {
                $tab->name[$lang['id_lang']] = 'Axiomus';
            }

            $res = $tab->save();
            // Если что-то пошло не так, удалим перевозчика и закруглимся
            if (!$res) {
                $this->uninstallAllCarrier();
                return false;
            }
        } else {
            $tab = new Tab($idTab);
        }
        Configuration::updateValue('RS_AXIOMUS_CONFIG_TAB_ID', $tab->id);

        // Здесь мы создаем пункт вехнего подменю.
        $idTab = Tab::getIdFromClassName('AdminAxiomusOrder');
        if (!$idTab) {
            $tab = new Tab();
            $tab->class_name = 'AdminAxiomusOrder';
            $tab->module = 'axiomuspostcarrier';
            $tab->id_parent = Tab::getIdFromClassName('AdminParentOrders');

            $languages = Language::getLanguages(false);
            foreach ($languages as $lang) {
                $tab->name[$lang['id_lang']] = 'Axiomus';
            }

            $res = $tab->save();
            // Если что-то пошло не так, удалим перевозчика и закруглимся
            if (!$res) {
                $this->uninstallAllCarrier();
                return false;
            }
        } else {
            $tab = new Tab($idTab);
        }
        Configuration::updateValue('RS_AXIOMUS_ORDER_TAB_ID', $tab->id);


        // Если родительский метод не срабатывает, то все удаляем,
        // и самоустраняемся
        if (!parent::install()) {
            parent::uninstall();
            $this->uninstallTab($tab->id);
            $this->uninstallAllCarrier();
            $this->AxiomusPost->dropTable();
            return false;
        }

        $this->registerHook('actionCarrierUpdate');
        $this->registerHook('actionValidateOrder');
        $this->registerHook('actionOrderStatusPostUpdate');

        Configuration::updateValue('RS_AXIOMUS_ID_AXIOMUS_DELIVERY', (int)$idCarrier);

        Configuration::updateValue('RS_AXIOMUS_TOKEN', '76793d5test0cf77');
        Configuration::updateValue('RS_AXIOMUS_CACHE_HOURLIFE', 24);

        Configuration::updateValue('RS_AXIOMUS_USE_AXIOMUS_DELIVERY', 1);
        Configuration::updateValue('RS_AXIOMUS_USE_TOPDELIVERY_DELIVERY', null);
        Configuration::updateValue('RS_AXIOMUS_USE_DPD_DELIVERY', null);
        Configuration::updateValue('RS_AXIOMUS_USE_BOXBERRY_DELIVERY', null);

        Configuration::updateValue('RS_AXIOMUS_USE_AXIOMUS_CARRY', null);
        Configuration::updateValue('RS_AXIOMUS_USE_TOPDELIVERY_CARRY', null);
        Configuration::updateValue('RS_AXIOMUS_USE_DPD_CARRY', null);
        Configuration::updateValue('RS_AXIOMUS_USE_BOXBERRY_CARRY', null);
        Configuration::updateValue('RS_AXIOMUS_USE_RUSSIANPOST_CARRY', null);

        return true;
    }

    // TODO: подумать, что и как. Оно должно деинсталлироваться, даже если возникли какие-то ошибки
    public function uninstall()
    {
        $res = true;

        $res = $this->unregisterHook('ActionCarrierUpdate');
        $res = $this->unregisterHook('actionValidateOrder');
        $res = $this->unregisterHook('actionOrderStatusPostUpdate');
        $res = $this->uninstallTab();
        $res = $this->uninstallAllCarrier();
        $res = $this->AxiomusPost->dropTable();

        Configuration::updateValue('RS_AXIOMUS_POST_TAB_ID', null);
        Configuration::updateValue('RS_AXIOMUS_POST_CARRIER_ID', null);

        Configuration::updateValue('RS_AXIOMUS_TOKEN', null);
        Configuration::updateValue('RS_AXIOMUS_CACHE_HOURLIFE', null);

        Configuration::updateValue('RS_AXIOMUS_USE_AXIOMUS_DELIVERY', null);
        Configuration::updateValue('RS_AXIOMUS_USE_TOPDELIVERY_DELIVERY', null);
        Configuration::updateValue('RS_AXIOMUS_USE_DPD_DELIVERY', null);
        Configuration::updateValue('RS_AXIOMUS_USE_BOXBERRY_DELIVERY', null);

        Configuration::updateValue('RS_AXIOMUS_USE_AXIOMUS_CARRY', null);
        Configuration::updateValue('RS_AXIOMUS_USE_TOPDELIVERY_CARRY', null);
        Configuration::updateValue('RS_AXIOMUS_USE_DPD_CARRY', null);
        Configuration::updateValue('RS_AXIOMUS_USE_BOXBERRY_CARRY', null);
        Configuration::updateValue('RS_AXIOMUS_USE_RUSSIANPOST_CARRY', null);

        if (!$res || !parent::uninstall())
            return false;

        return true;
    }

    public function hookActionOrderStatusPostUpdate($params)
    {
        //ToDo реализовать отправку в Axiomus
        //$params['newOrderStatus'] // after status changed
        $par = $params; // after order is placed
    }

    public function hookActionValidateOrder($params)
    {
        $carrier_id = $params['cart']->id_carrier;
        //ToDo реализовать добавление записи в таблицу axiomus_order
        //do whatever with the carrier
    }

    // Хук на обновление информации о перевозчике
    public function hookActionCarrierUpdate($params)
    {

        if ((int)$params['id_carrier'] == (int)Configuration::get('RS_AXIOMUS_POST_CARRIER_ID')) {
            Configuration::updateValue('RS_AXIOMUS_POST_CARRIER_ID', (int)$params['carrier']->id);
        }
    }

    private function uninstallAllCarrier(){
        $this->uninstallCarrier('Axiomus', 'DELIVERY');
        $this->uninstallCarrier('TopDelivery', 'DELIVERY');
        $this->uninstallCarrier('DPD', 'DELIVERY');
        $this->uninstallCarrier('BoxBerry', 'DELIVERY');
        $this->uninstallCarrier('Axiomus', 'CARRY');
        $this->uninstallCarrier('TopDelivery', 'CARRY');
        $this->uninstallCarrier('DPD', 'CARRY');
        $this->uninstallCarrier('BoxBerry', 'CARRY');
        $this->uninstallCarrier('RussianPost', 'CARRY');
    }

    public function uninstallCarrier($name = '', $type = '')
    {
        //ToDo нужно ли удалять RangePrice
        $carrierId = (int)Configuration::get('RS_AXIOMUS_ID_' . strtoupper($name) . '_' . strtoupper($type));

        if (!is_null($carrierId)) {
            $carrier = new Carrier($carrierId);

            if (!is_null($carrier->id)) {
                $langDefault = (int)Configuration::get('PS_LANG_DEFAULT');

                $carriers = Carrier::getCarriers($langDefault, true, false, false, NULL, PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE);//ToDo что за PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE

                // Если наш перевозчик был по умолчанию, назначим кого-нибудь другого
                if (Configuration::get('PS_CARRIER_DEFAULT') == $carrierId) {
                    foreach ($carriers as $c) {
                        if ($c['active'] && !$c['deleted'] && ($c['name'] != $carrier->name)) {
                            Configuration::updateValue('PS_CARRIER_DEFAULT', $c['id_carrier']);
                        }
                    }
                }

                $zones = Zone::getZones(true);
                foreach ($zones as $z) {
                    if ($z['id_zone'] == (int)7) { //ToDo может вынести в админку выбор зоны, не у всех она Europe (non-EU)
                        $sql = "DELETE FROM `" . _DB_PREFIX_ . "carrier_zone` WHERE `id_carrier` = {$carrierId}";
                        Db::getInstance()->execute($sql);
                        $sql = "DELETE FROM `" . _DB_PREFIX_ . "delivery` WHERE `id_carrier` = {$carrierId}";
                        Db::getInstance()->execute($sql);
                    }
                }

                if (!$carrier->deleted) {
                    $carrier->deleted = 1;
                    if (!$carrier->update())
                        return false;
                }
            }
        }

        Configuration::updateValue('RS_AXIOMUS_ID_' . strtoupper($name) . '_' . strtoupper($type), null);
        return true;
    }

    public function installCarrier($name = '', $type = '')
    {
        $carrier = new Carrier();
        $carrier->name = $name;

        $carrier->active = true; // TODO: проверить -- это точно обязательно?
        $carrier->deleted = 0; // TODO: проверить -- это точно обязательно?


        $carrier->shipping_handling = false; // TODO: это может быть интересным -- стоимость упаковки и пр.
        $carrier->range_behavior = 0; //Нужно ли использовать стандартный range

        $carrier->delay[(int)Configuration::get('PS_LANG_DEFAULT')] = ($type == 'DELIVERY' ? 'В течении 1-2 суток' : 'Самовывоз');

        //связь с модулем
        $carrier->shipping_external = true;
        $carrier->is_module = true;
        $carrier->external_module_name = 'axiomuspostcarrier';

        $carrier->need_range = true; //разобратся почему не работает без лимитов

        $carrier->max_width = 150; //см
        $carrier->max_height = 150;
        $carrier->max_depth = 150;
        $carrier->max_weight = 25; //масимальный вес у axiomus

        if ($carrier->add()) {
            // Добавим перевозчика всем группам пользователей
            $groups = Group::getGroups(true);
            foreach ($groups as $group)
                Db::getInstance()->autoExecute(_DB_PREFIX_ . 'carrier_group', array(
                    'id_carrier' => (int)$carrier->id,
                    'id_group' => (int)$group['id_group']
                ), 'INSERT');

            $rangePrice = new RangePrice(); //ToDo возможно не нужно для $carrier->need_range = false
            $rangePrice->id_carrier = $carrier->id;
            $rangePrice->delimiter1 = '0';
            $rangePrice->delimiter2 = '100500';
            $rangePrice->add();

            $rangeWeight = new RangeWeight();
            $rangeWeight->id_carrier = $carrier->id;
            $rangeWeight->delimiter1 = '0';
            $rangeWeight->delimiter2 = '100500';
            $rangeWeight->add();

            $zones = Zone::getZones(true); //ToDo Переработать добавление зон
            foreach ($zones as $z) {
                if ($z['id_zone'] == (int)7) { //ToDo может вынести в админку выбор зоны, не у всех она Europe (non-EU)
                    Db::getInstance()->autoExecuteWithNullValues(_DB_PREFIX_ . 'carrier_zone', array('id_carrier' => (int)$carrier->id, 'id_zone' => (int)$z['id_zone']), 'INSERT');
                    Db::getInstance()->autoExecuteWithNullValues(_DB_PREFIX_ . 'delivery', array('id_carrier' => (int)$carrier->id, 'id_range_price' => (int)$rangePrice->id, 'id_range_weight' => NULL, 'id_zone' => (int)$z['id_zone'], 'price' => '0'), 'INSERT');
                    Db::getInstance()->autoExecuteWithNullValues(_DB_PREFIX_ . 'delivery', array('id_carrier' => (int)$carrier->id, 'id_range_price' => NULL, 'id_range_weight' => (int)$rangeWeight->id, 'id_zone' => (int)$z['id_zone'], 'price' => '0'), 'INSERT');
                }
            }

            copy($this->getLocalPath() . $name . '.jpg', _PS_SHIP_IMG_DIR_ . '/' . (int)$carrier->id . '.jpg');
            Configuration::updateValue('RS_AXIOMUS_ID_' . strtoupper($name) . '_' . strtoupper($type), (int)($carrier->id));
            return (int)($carrier->id);
        }
        return false;
    }

    private function uninstallTab()
    {
        $res = true;

        $idTab = Tab::getIdFromClassName('AdminAxiomusConfig');
        if ($idTab) {
            $tab = new Tab($idTab);
            $res = $tab->delete();
        }
        $idTab = Tab::getIdFromClassName('AdminAxiomusOrder');
        if ($idTab) {
            $tab = new Tab($idTab);
            $res = $tab->delete();
        }

        return $res; //ToDo Возвращает переписанный res
    }


    private function carrierId($val = NULL)
    {

        if (!is_null($val))
            Configuration::updateValue($this->carrierIdStr, $val);

        return Configuration::get($this->carrierIdStr);
    }

}