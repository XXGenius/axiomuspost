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
//    public $AxiomusPost;
    // Хоть и неочевидно, но здесь это должно быть. Кем-то присваивается.
    public $id_carrier;
    public $value_prefix = 'RS_AXIOMUS';
    public $deliveryNames = ['undefined', 'axiomus', 'strizh', 'pecom'];
    public $carryNames = ['axiomus', 'dpd', 'boxberry', 'russianpost', 'pecom'];
    private $_postErrors = array();

    public function __construct()
    {
        $this->name = 'axiomuspostcarrier';
        $this->tab = 'shipping_logistics';
        $this->version = '1.0.6';
        $this->author = 'Robert Spectrum';
        $this->controllers = array('changecarrieroptions'); //ToDo зачем это и может добавить все контроллеры?
        $this->displayName = $this->l('Axiomus Post');
        $this->description = $this->l('Calculate a shipping cost using Axiomus Post formulas');

        parent::__construct();

        $this->AxiomusPost = new AxiomusPost();


    }

    private function getCarrierName()
    {
        if (Configuration::get('RS_AXIOMUS_USE_AXIOMUS_DELIVERY')) {
            if ($this->id_carrier == (int)Configuration::get('RS_AXIOMUS_ID_AXIOMUS_DELIVERY')) {
                return 'Axiomus';
            }
        }
        if (Configuration::get('RS_AXIOMUS_USE_TOPDELIVERY_DELIVERY')) {
            if ($this->id_carrier == (int)Configuration::get('RS_AXIOMUS_ID_TOPDELIVERY_DELIVERY')) {
                return 'TopDelivery';
            }
        }
        if (Configuration::get('RS_AXIOMUS_USE_DPD_DELIVERY')) {
            if ($this->id_carrier == (int)Configuration::get('RS_AXIOMUS_ID_DPD_DELIVERY')) {
                return 'DPD';
            }
        }
        if (Configuration::get('RS_AXIOMUS_USE_BOXBERRY_DELIVERY')) {
            if ($this->id_carrier == (int)Configuration::get('RS_AXIOMUS_ID_BOXBERRY_DELIVERY')) {
                return 'BoxBerry';
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

    public function getOrderShippingCost($cart, $shipping_cost)
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
        $totalWeight = $cart->getTotalWeight();
        $products = $cart->getProducts();

        foreach ($products as $product) {
            $totalPrice += (float)$product['total_wt']; //ToDo а точно ли не total?
        }
        $addr = new Address($cart->id_address_delivery);
        if (!Validate::isLoadedObject($addr))
            return false;

        if((boolean)Configuration::get('RS_AXIOMUS_MSCW_AXIOMUS_MANUAL')){
            if($this->AxiomusPost->issetOrder($cart->id)){
                $price = $this->AxiomusPost->getPriceByCartId($cart->id);
                return $price['price_weight']+$price['price_condition'];
            }
            return false;
        }else{

            $cachePrice = $this->AxiomusPost->getPriceInCache($addr->id, $totalWeight);
            if ($cachePrice != false) {
                if ($carrierName == 'Axiomus') {
                    $sum_carrier = $cachePrice['price_axiomus_delivery'];
                } elseif ($carrierName == 'TopDelivery') {
                    $sum_carrier = $cachePrice['price_topdelivery_delivery'];
                } elseif ($carrierName == 'DPD') {
                    $sum_carrier = $cachePrice['price_dpd_delivery'];
                } elseif ($carrierName == 'BoxBerry') {
                    $sum_carrier = $cachePrice['price_boxberry_delivery'];
                } elseif ($carrierName == 'Axiomus-carry') {
                    $sum_carrier = $cachePrice['price_axiomus_carry'];
                } elseif ($carrierName == 'TopDelivery-carry') {
                    $sum_carrier = $cachePrice['price_topdelivery_carry'];
                } elseif ($carrierName == 'DPD-carry') {
                    $sum_carrier = $cachePrice['price_dpd_carry'];
                } elseif ($carrierName == 'BoxBerry-carry') {
                    $sum_carrier = $cachePrice['price_boxberry_carry'];
                } elseif ($carrierName == 'RussianPost-carry') {
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
                    foreach ($axiomusResponse['delivery'] as $delivery) {
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
                } else {
                    $totalsum = 0;
                }
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


        // Не удалось создать
        if (!$this->_installCarriers()) {
            return false;
        }

        if (!$this->_installOrderStatus() | !$this->AxiomusPost->createTabless() | !$this->AxiomusPost->insertStartValueDb()) {
            $this->uninstall();
            return false;
        }

        $this->AxiomusPost->refreshCarryAddressCacheAxiomus();
        $this->AxiomusPost->refreshCarryAddressCacheDPD();
        $this->AxiomusPost->refreshCarryAddressCacheBoxBerry();
        $this->AxiomusPost->refreshCarryAddressCachePecom();

        $this->_createMenuTab();

        $this->_setSettingsValues();


        if (!parent::install()) {
            parent::uninstall();
            $this->uninstall();
            return false;
        }
        $this->_registerHooks();
        $this->registerHook('displayBeforeCarrier');
        $this->registerHook('displayCarrierList');
        $this->registerHook('actionCarrierProcess');

        $this->registerHook('actionCarrierUpdate');
        $this->registerHook('actionValidateOrder');
        $this->registerHook('actionOrderStatusPostUpdate');

        $this->registerHook('displayAdminOrderTabShip');
        $this->registerHook('displayAdminOrderContentShip');
        return true;
    }

    private function _installCarriers(){
        foreach ($this->deliveryNames as $deliveryName){
            $this->_installCarrier($deliveryName, 'DELIVERY');
        }
        foreach ($this->carryNames as $carryName){
            $this->_installCarrier($carryName, 'CARRY');
        }
        return true;
    }

    private function _installCarrier($name = '', $type = '')
    {
        $carrier = new Carrier();
        $carrier->name = $name;

        $carrier->active = true;
        $carrier->deleted = 0;


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

            copy($this->getLocalPath() .'img/'. $name . '.jpg', _PS_SHIP_IMG_DIR_ . '/' . (int)$carrier->id . '.jpg');
            Configuration::updateValue($this->getCarrierValueIdName($name, $type), (int)($carrier->id));
            return (int)($carrier->id);
        }
        return false;
    }

    public function getCarrierValueIdName($name, $type)
    {
        return $this->value_prefix.'_ID_'. strtoupper($name) . '_' . strtoupper($type);
    }

    private function _installOrderStatus(){
        if (!Configuration::get('RS_AXIOMUS_200_SEND_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Отправлено в Axiomus');
            $orderState->send_email = false;
            $orderState->color = '#0cd6ff';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_200_SEND_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_10_RETURN_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Заказ отклонен (измените или отмените)');
            $orderState->send_email = false;
            $orderState->color = '#8f0621';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_10_RETURN_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_0_PROGRES_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'В обработке(ожидайте)');
            $orderState->send_email = false;
            $orderState->color = '#ffde40';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_0_PROGRES_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_1_COMPLETE_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Укомплектован на складе');
            $orderState->send_email = false;
            $orderState->color = '#FF8C00';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_1_COMPLETE_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_2_INSTOCK_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Принят на складе');
            $orderState->send_email = false;
            $orderState->color = '#b0ff4c';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_2_INSTOCK_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_3_NOPRODUCT_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Нет товара');
            $orderState->send_email = false;
            $orderState->color = '#ffae88';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_3_NOPRODUCT_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_4_PERFORMANCE_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Исполняется');
            $orderState->send_email = false;
            $orderState->color = '#00bfdb';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_4_PERFORMANCE_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_5_INPROCESS_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Сложности с заказом');
            $orderState->send_email = false;
            $orderState->color = '#ffde1c';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_5_INPROCESS_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_6_TRANSFERDELIVERY_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Перенос на другую дату');
            $orderState->send_email = false;
            $orderState->color = '#e190ff';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_6_TRANSFERDELIVERY_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_20_COMPLETED_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Комплектуется');
            $orderState->send_email = false;
            $orderState->color = '#6fd300';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_20_COMPLETED_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_30_SORT_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Сортировка');
            $orderState->send_email = false;
            $orderState->color = '#6fd300';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_30_SORT_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_70_INPVZ_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Поступил в ПВЗ');
            $orderState->send_email = false;
            $orderState->color = '#7cd6ff';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_70_INPVZ_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_80_FULFILLED_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Исполнен(доставляется на склад)');
            $orderState->send_email = false;
            $orderState->color = '#7cd6ff';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_80_FULFILLED_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_81_PRECANCEL_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Предотмена');
            $orderState->send_email = false;
            $orderState->color = '#8f0621';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_81_PRECANCEL_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_90_CANCEL_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Отменен');
            $orderState->send_email = false;
            $orderState->color = '#8f0621';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_90_CANCEL_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_100_FINISHED_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Выполнен');
            $orderState->send_email = false;
            $orderState->color = '#c5c5c5';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_100_FINISHED_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_105_SENDPARTNER_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Отправлен(в внешнуюю службу)');
            $orderState->send_email = false;
            $orderState->color = '#c5c5c5';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_105_SENDPARTNER_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_107_AWARDED_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Вручен покупателю');
            $orderState->send_email = false;
            $orderState->color = '#e5dcff';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_107_AWARDED_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_110_FAILURE_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Частичный отказ');
            $orderState->send_email = false;
            $orderState->color = '#ffcd98';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_110_FAILURE_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        if (!Configuration::get('RS_AXIOMUS_120_FULLFAILURE_ORDER_STATUS_ID'))//if status does not exist
        {
            $orderState = new OrderState();
            $orderState->name =  array_fill(0,10,'Полный отказ');
            $orderState->send_email = false;
            $orderState->color = '#8f0621';
            $orderState->shipped = true;
            $orderState->moduleName = 'axiomuspostcarrier';
            $orderState->hidden = false;
            $orderState->delivery = true;
            $orderState->logable = false;
            $orderState->invoice = false;
            if ($orderState->add())//save new order status
            {
                Configuration::updateValue('RS_AXIOMUS_120_FULLFAILURE_ORDER_STATUS_ID', (int)$orderState->id);
            }
        }
        return true;
    }

    private function _createMenuTab(){
        $idTab = Tab::getIdFromClassName('AdminAxiomusSend');
        if (!$idTab) {
            $tab = new Tab();
            $tab->class_name = 'AdminAxiomusSend';
            $tab->module = 'axiomuspostcarrier';
            $tab->id_parent = Tab::getIdFromClassName('AdminParentShipping');

            $tab->active = false;
            $languages = Language::getLanguages(false);
            foreach ($languages as $lang) {
                $tab->name[$lang['id_lang']] = 'Axiomus Send';
            }

            $res = $tab->save();
            // Если что-то пошло не так, удалим перевозчика и закруглимся
            if (!$res) {
                $this->_uninstallAllCarrier();
                return false;
            }
        } else {
            $tab = new Tab($idTab);
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
                $this->_uninstallAllCarrier();
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
                $this->_uninstallAllCarrier();
                return false;
            }
        } else {
            $tab = new Tab($idTab);
        }
        Configuration::updateValue('RS_AXIOMUS_ORDER_TAB_ID', $tab->id);

        return true;
    }

    private function _registerHooks(){

        return true;
    }

    private function _setSettingsValues(){

        Configuration::updateValue('RS_AXIOMUS_MSCW_USE_AXIOMUS', 1);
        Configuration::updateValue('RS_AXIOMUS_MSCW_USE_STRIZH', 1);
        Configuration::updateValue('RS_AXIOMUS_MSCW_USE_PECOM', 1);
        Configuration::updateValue('RS_AXIOMUS_MSCW_USE_AXIOMUS_CARRY', 1);
        Configuration::updateValue('RS_AXIOMUS_MSCW_USE_DPD_CARRY', 1);
        Configuration::updateValue('RS_AXIOMUS_MSCW_USE_BOXBERRY_CARRY', 1);
        Configuration::updateValue('RS_AXIOMUS_MSCW_USE_RUSSIANPOST_CARRY', 1);
        Configuration::updateValue('RS_AXIOMUS_MSCW_USE_PECOM_CARRY', 1);
            //Piter
        Configuration::updateValue('RS_AXIOMUS_PTR_USE_AXIOMUS', 1);
        Configuration::updateValue('RS_AXIOMUS_PTR_USE_STRIZH', 1);
        Configuration::updateValue('RS_AXIOMUS_PTR_USE_PECOM', 1);
        Configuration::updateValue('RS_AXIOMUS_PTR_USE_AXIOMUS_CARRY', 1);
        Configuration::updateValue('RS_AXIOMUS_PTR_USE_DPD_CARRY', 1);
        Configuration::updateValue('RS_AXIOMUS_PTR_USE_BOXBERRY_CARRY', 1);
        Configuration::updateValue('RS_AXIOMUS_PTR_USE_RUSSIANPOST_CARRY', 1);
        Configuration::updateValue('RS_AXIOMUS_PTR_USE_PECOM_CARRY', 1);
            //region
        Configuration::updateValue('RS_AXIOMUS_REGION_USE_AXIOMUS_CARRY', 1);
        Configuration::updateValue('RS_AXIOMUS_REGION_USE_DPD_CARRY', 1);
        Configuration::updateValue('RS_AXIOMUS_REGION_USE_BOXBERRY_CARRY', 1);
        Configuration::updateValue('RS_AXIOMUS_REGION_USE_RUSSIANPOST_CARRY', 1);
        Configuration::updateValue('RS_AXIOMUS_REGION_USE_PECOM_CARRY', 1);
            //Settings
        Configuration::updateValue('RS_AXIOMUS_TOKEN', 1);
        Configuration::updateValue('RS_AXIOMUS_CACHE_HOURLIFE', 1);
            //Moscow
        Configuration::updateValue('RS_AXIOMUS_MSCW_AXIOMUS_MANUAL', 1);
        Configuration::updateValue('RS_AXIOMUS_MSCW_AXIOMUS_INCREMENT', 0);

        Configuration::updateValue('RS_AXIOMUS_TOKEN', '76793d5test0cf77');
        Configuration::updateValue('RS_AXIOMUS_CACHE_HOURLIFE', 24);

        Configuration::updateValue('RS_PECOM_NICKNAME', 'zitttz'); //ToDo перед релизом убрать
        Configuration::updateValue('RS_PECOM_API', '43406356D86720B3AA160DA8C299E2DA035079E0');

        Configuration::updateValue('RS_PECOM_SENDER_CITY', 'Москва');
        Configuration::updateValue('RS_PECOM_SENDER_TITLE', 'ИП Тестов Тест Тестович');
        Configuration::updateValue('RS_PECOM_SENDER_PERSON', 'Тестов Тест Тестович');
        Configuration::updateValue('RS_PECOM_SENDER_PHONE', '(495) 111-12-12');
        Configuration::updateValue('RS_PECOM_SENDER_EMAIL', 'testov.test@gmail.com');
        Configuration::updateValue('RS_PECOM_SENDER_ADDRESS_OFFICE', 'г. Москва, Волоколамское шоссе, д.41, корп. 1, стр. 2, офис 16');
        Configuration::updateValue('RS_PECOM_SENDER_ADDRESS_OFFICE_COOMENT', '');
        Configuration::updateValue('RS_PECOM_SENDER_ADDRESS_STOCK', 'г. Москва, Волоколамское шоссе, д.41, корп. 1, стр. 2, офис 16');
        Configuration::updateValue('RS_PECOM_SENDER_ADDRESS_STOCK_COMMENT', '');
        Configuration::updateValue('RS_PECOM_SENDER_WORK_TIME_FROM', '10:00');
        Configuration::updateValue('RS_PECOM_SENDER_WORK_TIME_TO', '18:00');
        Configuration::updateValue('RS_PECOM_SENDER_LUNCH_BREAK_FROM', '14:00');
        Configuration::updateValue('RS_PECOM_SENDER_LUNCH_BREAK_TO', '15:00');
        Configuration::updateValue('RS_PECOM_SENDER_IS_AUTH_NEEDED', TRUE);
        Configuration::updateValue('RS_PECOM_SENDER_IDENTITY_TYPE', 10 );
        Configuration::updateValue('RS_PECOM_SENDER_IDENTITY_SERIES', 1111);
        Configuration::updateValue('RS_PECOM_SENDER_NUMBER', 123123);
        Configuration::updateValue('RS_PECOM_SENDER_DATE', '10.10.07');
        Configuration::updateValue('RS_PECOM_VOLUME_ONE', 0.016355);
        Configuration::updateValue('RS_PECOM_IS_FLAGILE', true);
        Configuration::updateValue('RS_PECOM_IS_GLASS', true);
        Configuration::updateValue('RS_PECOM_IS_LIQUID', true);
        Configuration::updateValue('RS_PECOM_IS_OTHERTYPE', false);
        Configuration::updateValue('RS_PECOM_OTHERTYPE_DESCRIPTION', null);
        Configuration::updateValue('RS_PECOM_IS_OPENCAR', false);
        Configuration::updateValue('RS_PECOM_IS_SIDELOAD', false);
        Configuration::updateValue('RS_PECOM_IS_SPECIAL_EQ', false);
        Configuration::updateValue('RS_PECOM_IS_DAYBYDAY', false);
        Configuration::updateValue('RS_PECOM_REGISTER_TYPE', 1);
        Configuration::updateValue('RS_PECOM_RESPONSIBLE', 'Тестов Тест Тестович , ИП Тестов Тест Тестович, Директор');
        Configuration::updateValue('RS_PECOM_IS_HP', true);
        Configuration::updateValue('RS_PECOM_HP_POSITION_COUNT', 1);
        Configuration::updateValue('RS_PECOM_IS_INSURANCE', false);
        Configuration::updateValue('RS_PECOM_IS_INSURANCE_PRICE', 0);
        Configuration::updateValue('RS_PECOM_IS_SEALING', false);
        Configuration::updateValue('RS_PECOM_SEALING_POSITION_COUNT', null);
        Configuration::updateValue('RS_PECOM_IS_STRAPPING', false);
        Configuration::updateValue('RS_PECOM_IS_DOCUMENTS_RETURN', false);
        Configuration::updateValue('RS_PECOM_IS_LOADING', true);
/*
        Configuration::updateValue('RS_PECOM_SENDER_CITY', 'Москва');
        Configuration::updateValue('RS_PECOM_SENDER_TITLE', 'ИП Павлов Леонид Сергеевич');
        Configuration::updateValue('RS_PECOM_SENDER_PERSON', 'Павлов Леонид Сергеевич');
        Configuration::updateValue('RS_PECOM_SENDER_PHONE', '(495) 212-17-30');
        Configuration::updateValue('RS_PECOM_SENDER_EMAIL', 'leonid.s.pavlov@gmail.com');
        Configuration::updateValue('RS_PECOM_SENDER_ADDRESS_OFFICE', 'г. Москва, Волоколамское шоссе, д.89, корп. 1, стр. 2, офис 116');
        Configuration::updateValue('RS_PECOM_SENDER_ADDRESS_OFFICE_COOMENT', '');
        Configuration::updateValue('RS_PECOM_SENDER_ADDRESS_STOCK', 'г. Москва, Волоколамское шоссе, д.89, корп. 1, стр. 2, офис 116');
        Configuration::updateValue('RS_PECOM_SENDER_ADDRESS_STOCK_COMMENT', '');
        Configuration::updateValue('RS_PECOM_SENDER_WORK_TIME_FROM', '10:00');
        Configuration::updateValue('RS_PECOM_SENDER_WORK_TIME_TO', '18:00');
        Configuration::updateValue('RS_PECOM_SENDER_LUNCH_BREAK_FROM', '14:00');
        Configuration::updateValue('RS_PECOM_SENDER_LUNCH_BREAK_TO', '15:00');
        Configuration::updateValue('RS_PECOM_SENDER_IS_AUTH_NEEDED', TRUE);
        Configuration::updateValue('RS_PECOM_SENDER_IDENTITY_TYPE', 10 );
        Configuration::updateValue('RS_PECOM_SENDER_IDENTITY_SERIES', 1111);
        Configuration::updateValue('RS_PECOM_SENDER_NUMBER', 123123);
        Configuration::updateValue('RS_PECOM_SENDER_DATE', '10.10.07');
        Configuration::updateValue('RS_PECOM_VOLUME_ONE', 0.016355);
        Configuration::updateValue('RS_PECOM_IS_FLAGILE', true);
        Configuration::updateValue('RS_PECOM_IS_GLASS', true);
        Configuration::updateValue('RS_PECOM_IS_LIQUID', true);
        Configuration::updateValue('RS_PECOM_IS_OTHERTYPE', false);
        Configuration::updateValue('RS_PECOM_OTHERTYPE_DESCRIPTION', null);
        Configuration::updateValue('RS_PECOM_IS_OPENCAR', false);
        Configuration::updateValue('RS_PECOM_IS_SIDELOAD', false);
        Configuration::updateValue('RS_PECOM_IS_SPECIAL_EQ', false);
        Configuration::updateValue('RS_PECOM_IS_DAYBYDAY', false);
        Configuration::updateValue('RS_PECOM_REGISTER_TYPE', 1);
        Configuration::updateValue('RS_PECOM_RESPONSIBLE', 'Павлов Леонид Сергеевич , ИП Павлов Леонид Сергеевич, Директор');
        Configuration::updateValue('RS_PECOM_IS_HP', true);
        Configuration::updateValue('RS_PECOM_HP_POSITION_COUNT', 1);
        Configuration::updateValue('RS_PECOM_IS_INSURANCE', false);
        Configuration::updateValue('RS_PECOM_IS_INSURANCE_PRICE', 0);
        Configuration::updateValue('RS_PECOM_IS_SEALING', false);
        Configuration::updateValue('RS_PECOM_SEALING_POSITION_COUNT', null);
        Configuration::updateValue('RS_PECOM_IS_STRAPPING', false);
        Configuration::updateValue('RS_PECOM_IS_DOCUMENTS_RETURN', false);
        Configuration::updateValue('RS_PECOM_IS_LOADING', true);*/

    }

    public function uninstall()
    {
        if (!$this->_unregisterHooks() |
        !$this->_uninstallTabs() |
        !$this->_uninstallAllCarrier() |
        !$this->_uninstallOrderStatus() |
        !$this->_unsetSettingsValues() |
        !$this->AxiomusPost->dropTables() | !parent::uninstall()){
            return false;
        }

        return true;
    }

    private function _unregisterHooks(){
        $this->unregisterHook('ActionCarrierUpdate');
        $this->unregisterHook('actionValidateOrder');
        $this->unregisterHook('actionOrderStatusPostUpdate');
        $this->unregisterHook('displayBeforeCarrier');
        $this->unregisterHook('displayCarrierList');
        $this->unregisterHook('actionCarrierProcess');
        $this->unregisterHook('displayAdminOrderTabShip');
        $this->unregisterHook('displayAdminOrderContentShip');
        return true;
    }

    private function _uninstallTabs()
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
        $idTab = Tab::getIdFromClassName('AdminAxiomusSend');
        if ($idTab) {
            $tab = new Tab($idTab);
            $res = $tab->delete();
        }

        return $res; //ToDo Возвращает переписанный res
    }

    private function _uninstallAllCarrier(){
        foreach ($this->deliveryNames as $deliveryName){
            $this->_uninstallCarrier($deliveryName, 'DELIVERY');
        }
        foreach ($this->carryNames as $carryName){
            $this->_uninstallCarrier($carryName, 'CARRY');
        }
        return true;
    }

    private function _uninstallCarrier($name = '', $type = '')
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
                    if (!$carrier->update()) {
                        return false;
                    }
                }
                return true;
            }
        }else{
            return false;
        }

        Configuration::updateValue('RS_AXIOMUS_ID_' . strtoupper($name) . '_' . strtoupper($type), null);
        return true;
    }

    private function _uninstallOrderStatus(){
        if(!Configuration::get('RS_AXIOMUS_200_SEND_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_200_SEND_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_200_SEND_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_10_RETURN_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_10_RETURN_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_10_RETURN_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_0_PROGRES_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_0_PROGRES_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_0_PROGRES_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_1_COMPLETE_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_1_COMPLETE_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_1_COMPLETE_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_2_INSTOCK_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_2_INSTOCK_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_2_INSTOCK_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_3_NOPRODUCT_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_3_NOPRODUCT_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_3_NOPRODUCT_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_4_PERFORMANCE_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_4_PERFORMANCE_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_4_PERFORMANCE_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_5_INPROCESS_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_5_INPROCESS_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_5_INPROCESS_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_6_TRANSFERDELIVERY_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_6_TRANSFERDELIVERY_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_6_TRANSFERDELIVERY_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_20_COMPLETED_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_20_COMPLETED_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_20_COMPLETED_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_30_SORT_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_30_SORT_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_30_SORT_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_70_INPVZ_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_70_INPVZ_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_70_INPVZ_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_80_FULFILLED_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_80_FULFILLED_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_80_FULFILLED_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_81_PRECANCEL_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_81_PRECANCEL_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_81_PRECANCEL_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_90_CANCEL_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_90_CANCEL_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_90_CANCEL_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_100_FINISHED_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_100_FINISHED_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_100_FINISHED_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_105_SENDPARTNER_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_105_SENDPARTNER_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_105_SENDPARTNER_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_107_AWARDED_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_107_AWARDED_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_107_AWARDED_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_110_FAILURE_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_110_FAILURE_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_110_FAILURE_ORDER_STATUS_ID', '');
        }
        if(!Configuration::get('RS_AXIOMUS_120_FULLFAILURE_ORDER_STATUS_ID')) {
            $orderState = new OrderState((int)Configuration::get('RS_AXIOMUS_120_FULLFAILURE_ORDER_STATUS_ID'));
            $orderState->delete();
            Configuration::updateValue('RS_AXIOMUS_120_FULLFAILURE_ORDER_STATUS_ID', '');
        }
        return true;
    }

    private function _unsetSettingsValues(){
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
        return true;
    }

    public function hookDisplayAdminOrderTabShip($params = null){
        return $this->display('axiomuspostcarrier', 'views/templates/admin/tabship.tpl');
    }

    public function hookDisplayAdminOrderContentShip($params = null){

        $order_axiomus = $this->AxiomusPost->getOrderByIdCart($this->context->cart->id);
        if ($order_axiomus['carry']){
            $order_axiomus['type'] = 'Самовывоз';
            $order_axiomus['kadname'] = 'Это самовывоз';
            $order_axiomus['timename'] = 'Это самовывоз';
//            $order_axiomus['deliveryname'] =
        }else{
            $order_axiomus['type'] = 'Доставка';
            $order_axiomus['kadname'] = $this->AxiomusPost->getKadTypeById((int)$order_axiomus['kadtype'])['name'];
            $order_axiomus['timename'] = $this->AxiomusPost->getTimeTypeById((int)$order_axiomus['timetype'])['name'];
        }

        if(!empty($params['order']->shipping_number)){
            $axiomusSucces = true;
            $axiomusSuccesCode = (int)$params['order']->shipping_number;
        }else{
            $axiomusSucces = false;
            $axiomusSuccesCode = '';
        }

        $link = $this->context->link->getAdminLink('AdminAxiomusSend');

        $carrier = new Carrier($this->context->cart->id_carrier);
        $deliveryName = $carrier->name;

        $this->context->smarty->assign('deliveries_used', [
            'axiomus' => Configuration::get('RS_AXIOMUS_MSCW_USE_AXIOMUS'),
            'strizh'  => Configuration::get('RS_AXIOMUS_MSCW_USE_STRIZH'),
            'pecom'   => Configuration::get('RS_AXIOMUS_MSCW_USE_PECOM')
        ]);
        $this->context->smarty->assign('order_axiomus_data', $order_axiomus);


        $this->context->smarty->assign($this->AxiomusPost::getSettingsArray(true)['pecom_default']);
        $this->context->smarty->assign('delivery_name', $deliveryName);
        $this->context->smarty->assign('axiomus_succes', $axiomusSucces);
        $this->context->smarty->assign('axiomus_succes_code', $axiomusSuccesCode);
        $this->context->smarty->assign('order_id', $params['order']->id);
        $this->context->smarty->assign('cart_id', $params['cart']->id);
        $this->context->smarty->assign('_axiomus_module_path', _PS_MODULE_DIR_.$this->name);
        $this->context->smarty->assign('_axiomus_sendto_link', $link);
        return $this->display('axiomuspostcarrier', 'views/templates/admin/contentship.tpl');

    }

    public function hookActionValidateOrder($params)
    {
        $carrier_id = $params['cart']->id_carrier;
        //ToDo реализовать добавление записи в таблицу axiomus_order?
        //do whatever with the carrier
    }

    // Хук на обновление информации о перевозчике
    public function hookActionCarrierUpdate($params)
    {

        if ((int)$params['id_carrier'] == (int)Configuration::get('RS_AXIOMUS_POST_CARRIER_ID')) {
            Configuration::updateValue('RS_AXIOMUS_POST_CARRIER_ID', (int)$params['carrier']->id);
        }
    }

    /**
     * Срабатывает до выбора Доставки
     * @param $params
     */
    public function hookDisplayBeforeCarrier($params){

        $this->smarty->assign(array(//ToDo надо ли это
            'this_path' => $this->_path,
            'this_path_bw' => $this->_path,
            'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/'
        ));
        $sendparams = [];
        $link = $this->context->link->getModuleLink('axiomuspostcarrier', 'changecarrieroptions', $sendparams);
        Tools::redirectAdmin($link);
    }

    /**
     * Срабатывает после выбора Доставки
     * @param $params
     */
    public function hookActionCarrierProcess($params){


    }

    /**
     * Срабатывает при выборе перевозчика сверху
     * @param $arr
     */
    public function hookDisplayCarrierList($arr){

    }

//    public function hookActionOrderStatusPostUpdate($params)
//    {
//        $this->AxiomusPost->insertRowOrder($params['id_order'], $params['cart']->id, $params['newOrderStatus']->id);
//    }

}