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

public static $usernamePecom = 'zitttz';
public static $apikeyPecom = '43406356D86720B3AA160DA8C299E2DA035079E0';
public static $urlPecom = '';

public $delivery_date;
public $weight;

public $volume; //Объем
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

public $isInsurance = false; //Дополнительная страховка
public $isInsurancePrice = 0; //Стоимость груза для страхования
public $isSealing = false;  //Пломбировка груза(до 3х кг)
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

public $sender = [ //ToDo переложить это в админку
//        'inn' => '7707083894',
        'city' => 'Москва',
        'title' => 'ИП Тест Тестов',
        'person' => 'Тестов Тест Тестович',
        'phone' => '(495) 222-77-30',
        'email' => 'testtesttest@gmail.com',
        'addressOffice' => 'г. Москва, Волоколамское шоссе, д.34, офис 116',
        'addressStock' => 'г. Москва, Волоколамское шоссе, д.34, офис 116',
        'cargoDocumentNumber' => '-',
        'workTimeFrom' => '10:00',
        'workTimeTo' => '18:00',
        'lunchBreakFrom' => '14:00',
        'lunchBreakTo' => '15:00',
        'isAuthorityNeeded' => TRUE,
        'identityCard' => [
            'type' => 10
        ],
//        'inn' => '690209 931823',
//        'city' => 'Москва',
//        'title' => 'ИП Павлов Леонид Сергеевич',
//        'person' => 'Павлов Леонид Сергеевич',
//        'phone' => '(495) 212-17-30',
//        'email' => 'leonid.s.pavlov@gmail.com',
//        'addressOffice' => 'г. Москва, Волоколамское шоссе, д.89, корп. 1, стр. 2, офис 116',
//        'addressStock' => 'г. Москва, Волоколамское шоссе, д.89, корп. 1, стр. 2, офис 116',
//        'cargoDocumentNumber' => '-',
//        'workTimeFrom' => '10:00',
//        'workTimeTo' => '18:00',
//        'lunchBreakFrom' => '14:00',
//        'lunchBreakTo' => '15:00',
//        'isAuthorityNeeded' => TRUE,
    //ToDo в API полей больше
];

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
        $this->okey = $this->moduleOrders['okay'];

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
        $this->timefrom = $res['rangefrom'];
        $this->timeto = $res['rangeto'];

        $this->address_line = $this->address->city . ', ул. ' . $this->address->address1;
        if(!empty($this->address->address2)){
            $this->address_line .= ', '.$this->address->address2;
        }

        $this->volume = 0.016355*$this->positionsCount;

        $this->customer_fullName = $this->customer->firstname . ' ' . $this->customer->lastname;

        $this->pecomDeliveryNeeded = false; //Переделать, дать пользователю выбирать нужна ли ему доставка до двери в регионе
        $this->pecomDeliveryNeededAddress = null;
        $this->pecomDeliveryNeededAddressComment = null;

    }

    public function sendToAxiomus($method)
    {
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
        $xml_auth->addAttribute('ukey', $this->ukeyAxiomus);
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

        $checksum = md5($this->uidAxiomus."u". count($this->products) . $totalQuantity );
        $xml_auth->addAttribute('checksum', $checksum);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->urlAxiomus); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . urlencode($xml_single_order->asXML())); // add POST fields
        $result_xml = curl_exec($ch);


        $result = simplexml_load_string($result_xml);

        $status_code = $result->status['code'];

        if ($status_code == 0)
        {
            $authId = $result->auth[0];
            $oid = (int)$result->auth['objectid']; //id заявки в системе axiomus
            return ['oid' => $oid,'okey' => $authId];
        }
        else
        {
            $this->errors[10] = 'Ответ от Axiomus пришел без номера заявки'; //Добавить код ошибки и описание из ответа
            return false;
        }

    }

    public function deleteToAxiomus()
    {




        $xml_single_order = new SimpleXMLElement('<singleorder/>');
        $xml_single_order->addChild('mode', 'delete');
        $xml_auth = $xml_single_order->addChild('auth');
        $xml_auth->addAttribute('ukey', $this->ukeyAxiomus);
        $xml_single_order->addChild('okey', $this->okey);


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->urlAxiomus); // set url to post to
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

    public function sendToPecom(){

        // Создание экземпляра класса
        $sdk = new PecomKabinet($this->usernamePecom, $this->apikeyPecom);
        // Вызов метода

        $request = array(
            'common' => array(
                'type' => 3,
                'applicationDate' => $this->delivery_date->format('Y-m-d'),
                'description' => "Заказ #".$this->order->id,
                'weight' => $this->weight,
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
                'isHPPositionsCount' => $this->positionsCount,
                'isInsurance' => $this->isInsurance,
                'isInsurancePrice' => $this->isInsurancePrice,
                'isSealing' => $this->isSealing,
                'isSealingPositionsCount' => ($this->isSealing)?$this->positionsCount:null,
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
                    'type' => 10
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
            $okay = $result->cargos[0]->cargoCode;

            $sdk->close();
            return ['oid' => $oid, 'okay' => $okay];
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

    public function deleteToPecom(){
        // Создание экземпляра класса
        $sdk = new PecomKabinet($this->usernamePecom, $this->apikeyPecom);
        // Вызов метода

        $request = [
            $this->moduleOrders['okay']
        ];

        $result = $sdk->call('requests', 'requestcancellation', $request);

        if ( ! isset($result->error)) { //ToDo Никак не могу проверить выполнение. Возвращает пустой массив
            if ((boolean)$result->success){
                $sdk->close();
                return true;
            }else{
                $this->errors[30] = $result->description;
                $sdk->close();
                return true; //ToDo Убрать true после проверки удаления
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

    public static function getCarryAddressesPecom(){
        // Создание экземпляра класса
        $sdk = new PecomKabinet(self::$usernamePecom, self::$apikeyPecom);
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
}



