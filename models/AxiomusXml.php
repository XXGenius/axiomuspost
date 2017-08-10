<?php

/**
 * Created by PhpStorm.
 * User: zitttz
 * Date: 13.03.17
 * Time: 10:29
 */
require_once (_PS_MODULE_DIR_ . 'axiomuspostcarrier/models/AxiomusPost.php');
require_once (_PS_MODULE_DIR_ . 'axiomuspostcarrier/api_pecom/pecom_kabinet.php');

class AxiomusXml
{
public static $ukeyAxiomus = 'XXcd208495d565ef66e7dff9f98764XX';
public static $uidAxiomus = 92;
public static $urlAxiomus = "http://axiomus.ru/test/api_xml_test.php";

public static $usernamePecom = '';
public static $apikeyPecom = '';

public $delivery_date;
public $b_date, $e_date;
public $weight;

public $volume; //Объем
public $volumeOne = 0.016355;
public $positionsCount = 1;

public $width = 0.33;
public $length = 0.15;
public $height = 0.33;
public $isFragile = True;
public $isGlass = True;
public $isLiquid = True;
public $isOtherType = False;
public $isOtherTypeDescription = null;
public $isOpenCar = false;
public $isSideLoad = false;
public $isSpecialEquipment = false;
public $isUncovered = false;
public $isDayByDay = false;
public $whoRegister = 1;
public $responsiblePerson = 'Павлов Леонид Сергеевич , ИП Павлов Леонид Сергеевич, Директор'; //ToDo Перенести в админку

public $isHP = true;
public $isHPPositionCount = 1;
public $isInsurance = false; //Дополнительная страховка
public $isInsurancePrice = 0; //Стоимость груза для страхования
public $isSealing = false;  //Пломбировка груза(до 3х кг)
public $isSealingPositionCount = null;
public $isStrapping = false; // Упаковка груза стреппинг‑лентой [Boolean]
public $isDocumentsReturn = false;// Возврат документов [Boolean]
public $isLoading = true;// Необходима погрузка силами «ПЭК» [Boolean]

public $order;
public $cart;
public $customer;
public $address;
public $products;

public $errors = [];
public $moduleOrders;
public $timefrom;
public $timeto;
public $kadfrom;
public $kadto;

public $address_line;
public $okey;

public $customer_fullName; //ToDo возможно не нужное поле, возможно оно есть в глубинах преста

public $sender = [];

public $pecomDeliveryNeeded;
public $pecomDeliveryNeededAddress;
public $pecomDeliveryNeededAddressComment;

    public function __construct($order_id)
    {
        $this->order = new Order($order_id);
        $this->cart = new Cart($this->order->id_cart);
        $this->customer = new Customer($this->order->id_customer);
        $this->address = new Address($this->order->id_address_delivery);

        $this->products = $this->cart->getProducts();
        $this->weight = $this->cart->getTotalWeight();

        $AxiomusPost = new AxiomusPost(); //Может тут static

        $res = $AxiomusPost->getOrderByIdCart($this->cart->id);
        if (!$res){
            $errors[0] = 'Нет записи в таблице orders модуля, возможно заказ сделан когда модуль не был установлен';
            return;
        }
        $this->moduleOrders = $res;

        $this->delivery_date = new DateTime($this->moduleOrders['date']);
        $this->okey = $this->moduleOrders['okey'];

        $res = $AxiomusPost->getTimeTypeById($this->moduleOrders['timetype']);
        if (!$res){
            $errors[1] = 'id = '.$this->moduleOrders['timetype'].',в таблице timetype не существует';
            return;
        }
        $this->timefrom = new DateTime($res['timefrom']);
        $this->timeto = new DateTime($res['timeto']);

        $res = $AxiomusPost->getKadTypeById((int)$this->moduleOrders['kadtype']);
        if (!$res){
            $errors[2] = 'id = '.$this->moduleOrders['kadtype'].',в таблице kadtype не существует';
            return;
        }
        $this->kadfrom = $res['rangefrom'];
        $this->kadto = $res['rangeto'];

        $this->address_line = $this->address->city . ', ул. ' . $this->address->address1;
        if(!empty($this->address->address2)){
            $this->address_line .= ', '.$this->address->address2;
        }

        $this->volumeOne = Configuration::get('RS_PECOM_VOLUME_ONE');
        $this->volume = $this->volumeOne*$this->positionsCount; //0.016355

        $this->isFragile = (boolean)Configuration::get('RS_PECOM_IS_FLAGILE');
        $this->isGlass = (boolean)Configuration::get('RS_PECOM_IS_GLASS');
        $this->isLiquid = (boolean)Configuration::get('RS_PECOM_IS_LIQUID');
        $this->isOtherType = (boolean)Configuration::get('RS_PECOM_IS_OTHERTYPE');
        $this->isOtherTypeDescription = Configuration::get('RS_PECOM_OTHERTYPE_DESCRIPTION');
        $this->isOpenCar = (boolean)Configuration::get('RS_PECOM_IS_OPENCAR');
        $this->isSideLoad = (boolean)Configuration::get('RS_PECOM_IS_SIDELOAD');
        $this->isSpecialEquipment = (boolean)Configuration::get('RS_PECOM_IS_SPECIAL_EQ');
        $this->isUncovered = (boolean)Configuration::get('RS_PECOM_IS_UNCOVERED');
        $this->isDayByDay = (boolean)Configuration::get('RS_PECOM_IS_DAYBYDAY');
        $this->whoRegister = (int)Configuration::get('RS_PECOM_REGISTER_TYPE');
        $this->responsiblePerson = Configuration::get('RS_PECOM_RESPONSIBLE');
        $this->isHP = (boolean)Configuration::get('RS_PECOM_IS_HP');
        $this->isHPPositionCount = (int)Configuration::get('RS_PECOM_HP_POSITION_COUNT');

        $this->isInsurance = (boolean)Configuration::get('RS_PECOM_IS_INSURANCE');
        $this->isInsurancePrice = Configuration::get('RS_PECOM_INSURANCE_PRICE');
        $this->isSealing = (boolean)Configuration::get('RS_PECOM_IS_SEALING');
        $this->isSealingPositionCount = Configuration::get('RS_PECOM_SEALING_POSITION_COUNT');

        $this->isStrapping = (boolean)Configuration::get('RS_PECOM_IS_STRAPPING');
        $this->isDocumentsReturn = (boolean)Configuration::get('RS_PECOM_IS_DOCUMENTS_RETURN');
        $this->isLoading = (boolean)Configuration::get('RS_PECOM_IS_LOADING');

        $this->customer_fullName = $this->customer->firstname . ' ' . $this->customer->lastname;


        $this->pecomDeliveryNeeded = false; //Переделать, дать пользователю выбирать нужна ли ему доставка до двери в регионе
        $this->pecomDeliveryNeededAddress = null;
        $this->pecomDeliveryNeededAddressComment = null;

        $this->sender = [
            'city' => Configuration::get('RS_PECOM_SENDER_CITY'),
            'title' => Configuration::get('RS_PECOM_SENDER_TITLE'),
            'person' => Configuration::get('RS_PECOM_SENDER_PERSON'),
            'phone' => Configuration::get('RS_PECOM_SENDER_PHONE'),
            'email' => Configuration::get('RS_PECOM_SENDER_EMAIL'),
            'addressOffice' => Configuration::get('RS_PECOM_SENDER_ADDRESS_OFFICE'),
            'addressOfficeComment' => Configuration::get('RS_PECOM_SENDER_ADDRESS_OFFICE_COOMENT'),
            'addressStock' => Configuration::get('RS_PECOM_SENDER_ADDRESS_STOCK'),
            'addressStockComment' => Configuration::get('RS_PECOM_SENDER_ADDRESS_STOCK_COMMENT'),
            'cargoDocumentNumber' => '-',
            'workTimeFrom' => Configuration::get('RS_PECOM_SENDER_WORK_TIME_FROM'),
            'workTimeTo' => Configuration::get('RS_PECOM_SENDER_WORK_TIME_TO'),
            'lunchBreakFrom' => Configuration::get('RS_PECOM_SENDER_LUNCH_BREAK_FROM'),
            'lunchBreakTo' => Configuration::get('RS_PECOM_SENDER_LUNCH_BREAK_TO'),
            'isAuthorityNeeded' => (boolean)Configuration::get('RS_PECOM_SENDER_IS_AUTH_NEEDED'),
            'identityCard' => [
                'type' => (int)Configuration::get('RS_PECOM_SENDER_IDENTITY_TYPE'),
                'series' => Configuration::get('RS_PECOM_SENDER_IDENTITY_SERIES'),
                'number' => Configuration::get('RS_PECOM_SENDER_NUMBER'),
                'date' => Configuration::get('RS_PECOM_SENDER_DATE')
            ]
        ];

        self::$ukeyAxiomus = Configuration::get('RS_AXIOMUS_UKEY');
        self::$uidAxiomus = Configuration::get('RS_AXIOMUS_UID');
        self::$urlAxiomus = Configuration::get('RS_AXIOMUS_URL');

        self::$usernamePecom = Configuration::get('RS_PECOM_NICKNAME');
        self::$apikeyPecom = Configuration::get('RS_PECOM_API');
    }

    public function sendToAxiomus($method, $params)
    {
        if(isset($params['axiomus_position_count'])){
            $this->positionsCount = (int)$params['axiomus_position_count'];
        }

        if ($this->address->city == 'Москва'){
            $city = 0;
        }elseif($this->address->city == 'Санкт-Петербург'){
            $city = 1;
        }else{
            return false;
        }

        $from_mkad = ($this->kadfrom + $this->kadto)/2; //ToDo может все таки не среднее значение


        $xml_single_order = new SimpleXMLElement('<singleorder/>');
        $xml_single_order->addChild('mode', $method);
        $xml_auth = $xml_single_order->addChild('auth');
        $xml_auth->addAttribute('ukey', self::$ukeyAxiomus);
        $xml_order = $xml_single_order->addChild('order');
        $xml_order->addAttribute('inner_id', "Заказ #" . $this->order->id);
        $xml_order->addAttribute('name',  $this->customer_fullName); //ToDo добавить отчество?
        $xml_order->addAttribute('address', $this->address_line);
        $xml_order->addAttribute('from_mkad', $from_mkad);
        $xml_order->addAttribute('d_date', $this->delivery_date->format('Y-m-d'));
        $xml_order->addAttribute('b_time', $this->timefrom->format('H:i'));
        $xml_order->addAttribute('e_time', $this->timeto->format('H:i'));
        $xml_order->addAttribute('incl_deliv_sum', $this->order->total_shipping); //ToDo не уверен насчет правильности, а может несколько вариантов?
        $xml_order->addAttribute('places', $this->positionsCount);
        $xml_order->addAttribute('city', $city);
        $xml_order->addChild('contacts', ($this->address->phone_mobile == '') ? $this->address->phone : $this->address->phone_mobile);
//            $xml_order->addChild('sms', 'тел. ');
//            $xml_order->addChild('sms_sender', 'тел. ');
//            $xml_order->addChild('description', $additionalInfo);
        $xml_services = $xml_order->addChild('services');
        $xml_services->addAttribute('cash', 'no');
        $xml_services->addAttribute('cheque', 'no'); //ToDo переработать
        $xml_services->addAttribute('selsize', 'no');
        $xml_items = $xml_order->addChild('items');

        $sum = $this->order->total_shipping;
        $totalQuantity = 0;
        $names = 0;

        foreach ($this->products as $product) {
            $xml_item = $xml_items->addChild('item');
            $xml_item->addAttribute('name', $product['name']);
            $xml_item->addAttribute('weight', $product['weight']);
            $xml_item->addAttribute('quantity', $product['quantity']);
            $xml_item->addAttribute('price', $product['total']); //ToDo или total_wt?
            $totalQuantity += $product['quantity'];
        }

        $checksum = md5(self::$uidAxiomus."u". count($this->products) . $totalQuantity );
        $xml_auth->addAttribute('checksum', $checksum);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::$urlAxiomus); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . urlencode($xml_single_order->asXML())); // add POST fields
        $result_xml = curl_exec($ch);


        $result = simplexml_load_string($result_xml);

        $status_code = $result->status['code'];

        if ($status_code == 0)
        {
            $authId = (string)$result->auth[0];
            $oid = (int)$result->auth['objectid']; //id заявки в системе axiomus
            return ['oid' => $oid,'okey' => $authId];
        }elseif ($status_code == 4){ //После 22:00 часов не допускается заявка на выбранный день
            $this->errors[11] = (string)$result->status;
            return false;
        } else {
            $this->errors[10] = 'Ответ от Axiomus пришел без номера заявки'; //Добавить код ошибки и описание из ответа
            return false;
        }

    }

    public function sendToAxiomusCarry($params)
    {
        if(isset($params['axiomus_position_count'])){
            $this->positionsCount = (int)$params['axiomus_position_count'];
        }

        if ($this->address->city == 'Москва'){
            $city = 0;
        }elseif($this->address->city == 'Санкт-Петербург'){
            $city = 1;
        }else{
            return false;
        }


        if(isset($params['mscw-carry-axiomus-daycount'])){
            $b_date = new DateTime();
            $e_date = new DateTime();
            $e_date->modify('+'.$params['mscw-carry-axiomus-daycount'].' day');
            $this->b_date = $b_date;
            $this->e_date = $e_date;
        }

        $incl_deliv_sum = AxiomusPost::getCarryPriceByName($this->address->city, 'axiomus');
        $AxiomusPost = new AxiomusPost();



        $xml_single_order = new SimpleXMLElement('<singleorder/>');
        $xml_single_order->addChild('mode', 'new_carry');
        $xml_auth = $xml_single_order->addChild('auth');
        $xml_auth->addAttribute('ukey', self::$ukeyAxiomus);
        $xml_order = $xml_single_order->addChild('order');
        $xml_order->addAttribute('inner_id', "Самовывоз #" . $this->order->id);
        $xml_order->addAttribute('name',  $this->customer_fullName); //ToDo добавить отчество?

        $xml_order->addAttribute('b_date', $this->b_date->format('Y-m-d'));
        $xml_order->addAttribute('e_date', $this->e_date->format('Y-m-d'));

        $xml_order->addAttribute('office', $this->moduleOrders['carry_code']);

        $xml_order->addAttribute('incl_deliv_sum', $incl_deliv_sum); //ToDo тут цена берется из админки
        $xml_order->addAttribute('places', $this->positionsCount);


        $xml_order->addChild('contacts', ($this->address->phone_mobile == '') ? $this->address->phone : $this->address->phone_mobile);
//            $xml_order->addChild('sms', 'тел. ');
//            $xml_order->addChild('sms_sender', 'тел. ');
//            $xml_order->addChild('description', $additionalInfo);
        $xml_services = $xml_order->addChild('services');
        $xml_services->addAttribute('cash', 'no');
        $xml_services->addAttribute('cheque', 'no'); //ToDo переработать
        $xml_services->addAttribute('selsize', 'no');
        $xml_items = $xml_order->addChild('items');

        $sum = $this->order->total_shipping;
        $totalQuantity = 0;
        $names = 0;

        foreach ($this->products as $product) {
            $xml_item = $xml_items->addChild('item');
            $xml_item->addAttribute('name', $product['name']);
            $xml_item->addAttribute('weight', $product['weight']);
            $xml_item->addAttribute('quantity', $product['quantity']);
            $xml_item->addAttribute('price', $product['total']); //ToDo или total_wt?
            $totalQuantity += $product['quantity'];
        }

        $checksum = md5(self::$uidAxiomus."u". count($this->products) . $totalQuantity );
        $xml_auth->addAttribute('checksum', $checksum);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::$urlAxiomus); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . urlencode($xml_single_order->asXML())); // add POST fields
        $result_xml = curl_exec($ch);


        $result = simplexml_load_string($result_xml);

        if ($result) {
            $status_code = $result->status['code'];

            if ($status_code == 0) {
                $authId = (string)$result->auth[0];
                $oid = (int)$result->auth['objectid']; //id заявки в системе axiomus
                return ['oid' => $oid, 'okey' => $authId];
            } elseif ($status_code == 4) { //После 22:00 часов не допускается заявка на выбранный день
                $this->errors[11] = (string)$result->status;
                return false;
            } else {
                $this->errors[10] = 'Ответ от Axiomus пришел без номера заявки'; //Добавить код ошибки и описание из ответа
                return false;
            }
        }else{
            $this->errors[100] = 'Ответ от Axiomus не пришел';
            return false;
        }
    }


    public function sendToDpdCarry($params)
    {

        $incl_deliv_sum = AxiomusPost::getCarryPriceByName($this->address->city, 'dpd');

        $this->b_date = new DateTime();
        $this->b_date->modify('+1 day');

        $xml_single_order = new SimpleXMLElement('<singleorder/>');
        $xml_single_order->addChild('mode', 'new_dpd');
        $xml_auth = $xml_single_order->addChild('auth');
        $xml_auth->addAttribute('ukey', self::$ukeyAxiomus);
        $xml_order = $xml_single_order->addChild('order');
        $xml_order->addAttribute('inner_id', "Самовывоз #" . $this->order->id);
        $xml_order->addAttribute('name',  $this->customer_fullName); //ToDo добавить отчество?

        $xml_order->addAttribute('b_date', $this->b_date->format('Y-m-d'));

        $xml_services = $xml_order->addChild('address');
        $xml_services->addAttribute('index', '123123');
        $xml_services->addAttribute('region', $this->address->city);
        $xml_services->addAttribute('area', $this->address->city);
        $xml_services->addAttribute('street','carry, not street');
        $xml_services->addAttribute('house', 'carry, not house');

        $xml_order->addAttribute('carrymode', $this->moduleOrders['carry_code']);

        $xml_order->addAttribute('incl_deliv_sum', $incl_deliv_sum); //ToDo тут цена берется из админки
//        $xml_order->addAttribute('places', $this->positionsCount);


        $xml_order->addChild('contacts', ($this->address->phone_mobile == '') ? $this->address->phone : $this->address->phone_mobile);
//            $xml_order->addChild('sms', 'тел. ');
//            $xml_order->addChild('sms_sender', 'тел. ');
//            $xml_order->addChild('description', $additionalInfo);
        $xml_services = $xml_order->addChild('services');
        $xml_services->addAttribute('valuation', 'no');
//        $xml_services->addAttribute('fragile', 'no'); //ToDo переработать
//        $xml_services->addAttribute('cod', 'no');
//        $xml_services->addAttribute('big', 'no');
//        $xml_services->addAttribute('waiting', 'no');
        $xml_items = $xml_order->addChild('items');

        $sum = $this->order->total_shipping;
        $totalQuantity = 0;
        $names = 0;

        foreach ($this->products as $product) {
            $xml_item = $xml_items->addChild('item');
            $xml_item->addAttribute('name', $product['name']);
            $xml_item->addAttribute('weight', $product['weight']);
            $xml_item->addAttribute('quantity', $product['quantity']);
            $xml_item->addAttribute('price', $product['total']); //ToDo или total_wt?
            $totalQuantity += $product['quantity'];
        }

        $checksum = md5(self::$uidAxiomus."u". count($this->products) . $totalQuantity );
        $xml_auth->addAttribute('checksum', $checksum);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::$urlAxiomus); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . urlencode($xml_single_order->asXML())); // add POST fields
        $result_xml = curl_exec($ch);


        $result = simplexml_load_string($result_xml);

        if ($result) {
            $status_code = $result->status['code'];

            if ($status_code == 0) {
                $authId = (string)$result->auth[0];
                $oid = (int)$result->auth['objectid']; //id заявки в системе axiomus
                return ['oid' => $oid, 'okey' => $authId];
            } elseif ($status_code == 4) { //После 22:00 часов не допускается заявка на выбранный день
                $this->errors[11] = (string)$result->status;
                return false;
            } else {
                $this->errors[10] = 'Ответ от Axiomus пришел без номера заявки'; //Добавить код ошибки и описание из ответа
                return false;
            }
        }else{
            $this->errors[100] = 'Ответ от Axiomus не пришел';
            return false;
        }
    }

    public function sendToBoxBerryCarry($params)
    {

        $incl_deliv_sum = AxiomusPost::getCarryPriceByName($this->address->city, 'boxberry');

        $this->delivery_date = new DateTime();
        $this->delivery_date->modify('+1 day');

        $xml_single_order = new SimpleXMLElement('<singleorder/>');
        $xml_single_order->addChild('mode', 'new_boxberry_pickup');
        $xml_auth = $xml_single_order->addChild('auth');
        $xml_auth->addAttribute('ukey', self::$ukeyAxiomus);
        $xml_order = $xml_single_order->addChild('order');
        $xml_order->addAttribute('inner_id', "Самовывоз #" . $this->order->id);
        $xml_order->addAttribute('name',  $this->customer_fullName); //ToDo добавить отчество?

        $xml_order->addAttribute('d_date', $this->delivery_date->format('Y-m-d'));

        $xml_services = $xml_order->addChild('address');
        $xml_services->addAttribute('office_code', $this->moduleOrders['carry_code']);

        $xml_order->addAttribute('incl_deliv_sum', $incl_deliv_sum); //ToDo тут цена берется из админки

        $xml_order->addChild('contacts', ($this->address->phone_mobile == '') ? $this->address->phone : $this->address->phone_mobile);
        $xml_services = $xml_order->addChild('services');

//        $xml_services->addAttribute('cod', 'no');
//        $xml_services->addAttribute('checkup', 'no');

        $xml_items = $xml_order->addChild('items');

        $sum = $this->order->total_shipping;
        $totalQuantity = 0;
        $names = 0;

        foreach ($this->products as $product) {
            $xml_item = $xml_items->addChild('item');
            $xml_item->addAttribute('name', $product['name']);
            $xml_item->addAttribute('weight', $product['weight']);
            $xml_item->addAttribute('quantity', $product['quantity']);
            $xml_item->addAttribute('price', $product['total']); //ToDo или total_wt?
            $totalQuantity += $product['quantity'];
        }

        $checksum = md5(self::$uidAxiomus."u". count($this->products) . $totalQuantity );
        $xml_auth->addAttribute('checksum', $checksum);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::$urlAxiomus); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . urlencode($xml_single_order->asXML())); // add POST fields
        $result_xml = curl_exec($ch);


        $result = simplexml_load_string($result_xml);

        if ($result) {
            $status_code = $result->status['code'];

            if ($status_code == 0) {
                $authId = (string)$result->auth[0];
                $oid = (int)$result->auth['objectid']; //id заявки в системе axiomus
                return ['oid' => $oid, 'okey' => $authId];
            } elseif ($status_code == 4) { //После 22:00 часов не допускается заявка на выбранный день
                $this->errors[11] = (string)$result->status;
                return false;
            } else {
                $this->errors[10] = 'Ответ от Axiomus пришел без номера заявки'; //Добавить код ошибки и описание из ответа
                return false;
            }
        }else{
            $this->errors[100] = 'Ответ от Axiomus не пришел';
            return false;
        }
    }

    public function deleteToAxiomus()
    {




        $xml_single_order = new SimpleXMLElement('<singleorder/>');
        $xml_single_order->addChild('mode', 'delete');
        $xml_auth = $xml_single_order->addChild('auth');
        $xml_auth->addAttribute('ukey', self::$ukeyAxiomus);
        $xml_single_order->addChild('okey', $this->okey);


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::$urlAxiomus); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . urlencode($xml_single_order->asXML())); // add POST fields
        $result_xml = curl_exec($ch);


        $result = simplexml_load_string($result_xml);

        $status_code = $result->status['code'];

        if ($status_code == 0)
        {
            return $result->status;
        }
        else
        {
            return $result->status; //ToDo т.к. не можем проверить анулирование
            return false;
        }
    }


    public static function getCarryAddressesAxiomus($method){

        $xml_single_order = new SimpleXMLElement('<singleorder/>');
        $xml_single_order->addChild('mode', $method);
        $xml_auth = $xml_single_order->addChild('auth');
        $xml_auth->addAttribute('ukey', self::$ukeyAxiomus);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::$urlAxiomus); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . urlencode($xml_single_order->asXML())); // add POST fields
        $result_xml = curl_exec($ch);

        $result = simplexml_load_string($result_xml);

        $status_code = $result->status['code'];

        if ($status_code == 0)
        {
            return $result;
        }
        else
        {
            return $result->status; //ToDo проверить и наполнить $this->error[]
            return false;
        }

    }

    public function sendToPecom($params, $cart_id = null)
    {


        if(isset($params['position_count'])){
            $this->positionsCount = (int)$params['position_count'];
            $this->volume = $this->positionsCount*$this->volumeOne;
        }

        if(isset($params['pecom_is_fragile']))$this->isFragile=(boolean)$params['pecom_is_fragile'];
        if(isset($params['pecom_is_glass']))$this->isGlass=(boolean)$params['pecom_is_glass'];
        if(isset($params['pecom_is_liquid']))$this->isLiquid=(boolean)$params['pecom_is_liquid'];
        if(isset($params['pecom_is_othertype']))$this->isOtherType=(boolean)$params['pecom_is_othertype'];
        if(isset($params['pecom_othertype_description']))$this->isOtherTypeDescription=$params['pecom_othertype_description'];
        if(isset($params['pecom_is_opencar']))$this->isOpenCar=(boolean)$params['pecom_is_opencar'];
        if(isset($params['pecom_is_sideload']))$this->isSideLoad=(boolean)$params['pecom_is_sideload'];
        if(isset($params['pecom_is_special_eq']))$this->isSpecialEquipment=(boolean)$params['pecom_is_special_eq'];
        if(isset($params['pecom_is_uncovered']))$this->isUncovered=(boolean)$params['pecom_is_uncovered'];
        if(isset($params['pecom_is_daybyday']))$this->isDayByDay=(boolean)$params['pecom_is_daybyday'];
        if(isset($params['pecom_register_type']))$this->whoRegister=$params['pecom_register_type'];
        if(isset($params['pecom_responsible_person']))$this->responsiblePerson=$params['pecom_responsible_person'];
        if(isset($params['pecom_is_hp']))$this->isHP=(boolean)$params['pecom_is_hp'];
        if(isset($params['pecom_hp_position_count']))$this->isHPPositionCount=$params['pecom_hp_position_count'];
        if(isset($params['pecom_is_insurance']))$this->isInsurance=(boolean)$params['pecom_is_insurance'];
        if(isset($params['pecom_insurance_price']))$this->isInsurancePrice=$params['pecom_insurance_price'];
        if(isset($params['pecom_is_sealing']))$this->isSealing=(boolean)$params['pecom_is_sealing'];
        if(isset($params['pecom_sealing_position_count']))$this->isSealingPositionCount=$params['pecom_sealing_position_count'];
        if(isset($params['pecom_is_strapping']))$this->isStrapping=(boolean)$params['pecom_is_strapping'];
        if(isset($params['pecom_is_documents_return']))$this->isDocumentsReturn=(boolean)$params['pecom_is_documents_return'];
        if(isset($params['pecom_is_loading']))$this->isLoading=(boolean)$params['pecom_is_loading'];

        if (isset($cart_id)) {
            $cart = new Cart($cart_id);
            $address = new Address($cart->id_address_delivery);
            $address_string = (!empty($address->address1))?$address->address1:''.', '.(!empty($address->address1))?$address->address2:'';
            $this->pecomDeliveryNeeded = true; //Переделать, дать пользователю выбирать нужна ли ему доставка до двери в регионе
            $this->pecomDeliveryNeededAddress = $address_string;
            $this->pecomDeliveryNeededAddressComment = null;
        }
        // Создание экземпляра класса
        $sdk = new PecomKabinet(self::$usernamePecom, self::$apikeyPecom);
        // Вызов метода

        $request = array(
            'common' => array(
                'type' => 3,
                'applicationDate' => $this->delivery_date->format('Y-m-d'),
                'description' => "Заказ #".$this->order->id,
                'weight' => $this->weight==0?0.02:$this->weight,
                'volume' => $this->volume,
                'positionsCount' => $this->positionsCount,
                'width' => $this->width,
                'length' => $this->length,
                'height' => $this->height,
                'isFragile' => $this->isFragile, //Хрупкий
                'isGlass' => $this->isGlass, //Стекло
                'isLiquid' => $this->isLiquid, // Жидкость
                'isOtherType' => $this->isOtherType, //Другой тип
                'isOtherTypeDescription' => $this->isOtherTypeDescription, //Другой тип описание

                'isOpenCar' => $this->isOpenCar,
                'isSideLoad' => $this->isSideLoad,
            'isSpecialEquipment' => $this->isSpecialEquipment,
                'isUncovered' => $this->isUncovered,
                'isDayByDay' => $this->isDayByDay,
                'whoRegisterApplication' => $this->whoRegister,
                'responsiblePerson' => $this->responsiblePerson,
            ),
            'services' => array(
                'isHP' => $this->isHP,
                'isHPPositionsCount' => $this->isHPPositionCount,
                'isInsurance' => $this->isInsurance,
                'isInsurancePrice' => $this->isInsurancePrice,
                'isSealing' => $this->isSealing,
                'isSealingPositionsCount' => ($this->isSealing)?$this->isSealingPositionCount:null,
                'isStrapping' => $this->isStrapping,
                'isDocumentsReturn' => $this->isDocumentsReturn,
                'isLoading' => $this->isLoading,
            ),
            'sender' => $this->sender,
            'receiver' => array(
                'city' => $this->address->city,
                'title' =>   $this->customer_fullName,
                'person' => $this->customer_fullName,
                'phone' => ($this->address->phone_mobile == '') ? $this->address->phone : $this->address->phone_mobile,
                'email' => $this->customer->email,
                'isCityDeliveryNeeded' => $this->pecomDeliveryNeeded,
                'isCityDeliveryNeededAddress' => $this->pecomDeliveryNeededAddress,
                'isCityDeliveryNeededAddressComment' => $this->pecomDeliveryNeededAddressComment,
                'identityCard' => [
                    'type' => 10,
                    'series' => 5675,
                    'number' => 345345,
                    'date' => '1985-01-01'
                ]
                //ToDo в Api больше полей
            ),
            'payments' => array(
                'pickUp' => array(
                    'type' => 1,
                    "paymentCity" => "Москва",
                ),
                'moving' => array(
                    'type' => 1,
                    "paymentCity" => "Москва",
                ),
                'delivery' => array(
                    'type' => 1,
                    "paymentCity" => "Москва",
                ),
//					'insurance' => array( //Оплата страховки
//						'type' => 1,
//						"paymentCity" => "Москва",
//					),
            ),

        );

        $result = $sdk->call('cargopickup', 'submit', $request);

        if ( ! isset($result->error)) {
            $oid = (int)$result->documentId;
            $okey = $result->cargos[0]->cargoCode;

            $sdk->close();
            return ['oid' => $oid, 'okey' => $okey];
        }else{
            $errorText =  'Ошибка ответа ПЭК. '.$result->error->title.'. ';
            foreach ($result->error->fields as $fields){
                $errorText .= 'Ошибка в поле: '.$fields->Key.' . '.$fields->Value[0].'. ';
            }
            $this->errors[20] = $errorText;
            $sdk->close();
            return false;
        }
    }

    public function deleteToPecom()
    {
        // Создание экземпляра класса
        $sdk = new PecomKabinet(self::$usernamePecom, self::$apikeyPecom);
        // Вызов метода

        $request = [
            $this->moduleOrders['okey']
        ];

        $result = $sdk->call('requests', 'requestcancellation', $request);

        if ( ! isset($result->error)) { //ToDo Никак не могу проверить выполнение. Возвращает пустой массив
            if(!empty($result)) {
                if ((boolean)$result->success) {
                    $sdk->close();
                    return true;
                } else {
                    $this->errors[30] = $result->description;
                    $sdk->close();
                    return true; //ToDo Убрать true после проверки удаления
                }
            }else{
                return true;//ToDo Убрать true после проверки удаления
            }
        }else{
            $errorText =  'Ошибка ответа ПЭК. '.$result->error->title.'. ';
            foreach ($result->error->fields as $fields){
                $errorText .= 'Ошибка в поле: '.$fields->Key.' . '.$fields->Value[0].'. ';
            }
            $this->errors[20] = $errorText;
            $sdk->close();
            return false;
        }
    }

    public static function getCarryAddressesPecom()
    {


        // Создание экземпляра класса
        $sdk = new PecomKabinet(Configuration::get('RS_PECOM_NICKNAME'), Configuration::get('RS_PECOM_API'));
        // Вызов метода

        $request = [
        ];

        $result = $sdk->call('BRANCHES', 'ALL', $request);

        if (!isset($result->error)) {
            return $result;
        }else{
            $errorText =  'Ошибка ответа ПЭК. '.$result->error->title.'. ';
            foreach ($result->error->fields as $fields){
                $errorText .= 'Ошибка в поле: '.$fields->Key.' . '.$fields->Value[0].'. ';
            }
            $sdk->close();
            return $errorText;
        }
    }

    public static function GetPricePecom($city, $cart_id, $is_carry = true)
    {
        // Создание экземпляра класса
        $sdk = new PecomKabinet(Configuration::get('RS_PECOM_NICKNAME'), Configuration::get('RS_PECOM_API'));
        $cart = new Cart($cart_id);
        $totalWeight = $cart->getTotalWeight();
        $products = (float)$cart->nbProducts();
        $volume = 0.016355 * $products;
        // Вызов метода
        $code = Db::getInstance()->getRow("SELECT `bitrixId` FROM ps_axiomus_city_pecom where `title` = '{$city}'");
        $cityData= array('title'=>$city);
        $price = $cart->getordertotal();
         if(empty ($code)) {
             $bitrixId = $sdk->call('branches', 'findbytitle',$cityData );
             if (isset($bitrixId->succes)) {
                 $code = $bitrixId->items[0]->cityId;
                 $sdk->close();
             }else{
                 return false;
             }
         }
        $request = [
            'senderCityId' => 446, //код города отправителя
            'receiverCityId' => (int)$code['bitrixId'], //код города получателя
            'isInsurancePrice' => (float)$price,//оценочная стоимость , руб
            'isPickUp' => 0, //нужен забор
            'isDelivery' => ($is_carry)?0:1,//нужна доставка
            'Cargos' => [[ // Данные о грузах [Array]
                'length'=> 0, // Длина груза, м [Number]
                'maxSize'=> 0,
                'height' => 0,
                'width'=> 0, // Ширина груза, м
                'volume' => $volume, // Объем груза, м3
                'isHP' =>0, // Жесткая упаковка [Boolean]
                'sealingPositionsCount'=> 0, // Количество мест для пломбировки [Number]
                'weight' => ($totalWeight != 0)? $totalWeight: 0.5, // Вес, кг [Number]
                'overSize' => 0 // Негабаритный груз [Boolean]
            ]]
        ];

        $result = $sdk->call('calculator', 'calculateprice', $request);

        if (!isset($result)){
            return false;
        }elseif (!isset($result->error)) {
            $sum = $result->transfers[0]->costTotal ;

            if ($is_carry) {
                $res = Db::getInstance()->autoExecuteWithNullValues('ps_axiomus_pecom_carry', [
                    'is_avia' => false,
                    'city' => (string)$city,
                    'costTotal' => (int)$sum,
                    'receiverCityId' => (int)$code['bitrixId'],
                    'price' => (int)$price
                ], 'INSERT');
            }else{
                $res = Db::getInstance()->autoExecuteWithNullValues('ps_axiomus_pecom_price_delivery', [
                    'is_avia' => false,
                    'city' => (string)$city,
                    'costTotal' => (int)$sum,
                    'receiverCityId' => (int)$code['bitrixId'],
                    'price' => (int)$price
                ], 'INSERT');
            }
            return $sum;
        }else{

            $errorText =  'Ошибка ответа ПЭК. '.$result->error->title.'. ';
            foreach ($result->error->fields as $fields){
                $errorText .= 'Ошибка в поле: '.$fields->Key.' . '.$fields->Value[0].'. ';
            }

            $sdk->close();
            return false;
        }
    }
}



