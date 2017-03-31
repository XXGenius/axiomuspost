<?php

/**
 * Created by PhpStorm.
 * User: zitttz
 * Date: 13.03.17
 * Time: 10:29
 */
include_once (_PS_MODULE_DIR_ . 'axiomuspostcarrier/models/AxiomusPost.php');

class AxiomusXml
{
public $ukey = 'XXcd208495d565ef66e7dff9f98764XX';
public $uid = 92;
public $url = "http://axiomus.ru/test/api_xml_test.php";

    public function sendTo($id, $method)
    {
//ToDo валидация входных данных
//        	elseif (isset($_POST['delivery']) AND $_POST['delivery'] == 'axiomus_deliver')
//	{
//		if ($_POST['pickup_date'] == '') {
//			$errors['pickup_date'] = 'Введите дату выполнения заявки';
//		}
//		else
//		{
//			if ($data['delivery_region'] == 'piter')
//			{
//				$city = 1;
//				$min_datetime = strtotime(date('Y-m-d 00:00:00', time()+2*86400));
//				if (strtotime($_POST['pickup_date']) < $min_datetime)
//				{
//					$errors['pickup_date'] = 'Дата выполнения должна быть не ранее, чем послезавтра.';
//				}
//			}
//			else
//			{
//				$city = 0;
//				$min_datetime = strtotime(date('Y-m-d 00:00:00', time()+86400));
//				if (strtotime($_POST['pickup_date']) < $min_datetime)
//				{
//					$errors['pickup_date'] = 'Дата выполнения должна быть не ранее, чем завтра.';
//				}
//			}
//
//		}
//		if (( ! preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/i', $_POST['timeFrom'])) || ( ! preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/i', $_POST['timeTo']))) {
//			$errors['time'] = 'Введите время доставки.';
//		}
//		elseif ($_POST['timeFrom'] > $_POST['timeTo'])
//		{
//			$errors['time'] = 'Время начала доставки не может быть большим, чем время окончания.';
//		}
//		if ($_POST['positionsCount'] <= 0)
//		{
//			$errors['positionsCount'] = 'Введите количество мест.';
//		}
        $errors = [];
        if (!count($errors)) {
            $order = new Order($id);
            $cart = new Cart($order->id_cart);
            $customer = new Customer($order->id_customer);
            $address = new Address($order->id_address_delivery);

            if ($address->city == 'Москва'){
                $city = 0;
            }elseif($address->city == 'Санкт-Петербург'){
                $city = 1;
            }else{
                return false;
            }
            $products = $cart->getProducts();

            $AxiomusPost = new AxiomusPost();
            $axiomus_order = $AxiomusPost->getOrderByIdCart($cart->id);

            $d_date = new DateTime($axiomus_order['date']);
//            $d_date->add(DateInterval::createfromdatestring('+ 24 hour'));
            $timetype = $AxiomusPost->getTimeTypeById($axiomus_order['timetype']);
            $b_time = new DateTime($timetype['timefrom']);
            $e_time = new DateTime($timetype['timeto']);
            $kadtype = $AxiomusPost->getKadTypeById($axiomus_order['kadtype']);
            $from_mkad = ($kadtype['rangefrom']+$kadtype['rangeto'])/2; //ToDo может все таки не среднее значение

            $xml_single_order = new SimpleXMLElement('<singleorder/>');
            $xml_single_order->addChild('mode', $method);
            $xml_auth = $xml_single_order->addChild('auth');
            $xml_auth->addAttribute('ukey', $this->ukey);
            $xml_order = $xml_single_order->addChild('order');
            $xml_order->addAttribute('inner_id', "Заказ #" . $id);
            $xml_order->addAttribute('name', $customer->firstname . ' ' . $customer->lastname); //ToDo добавить отчество?
            $xml_order->addAttribute('address', $address->city . ', ул. ' . $address->address1); //ToDo что делать с address2?
            $xml_order->addAttribute('from_mkad', $from_mkad);
            $xml_order->addAttribute('d_date', $d_date->format('Y-m-d'));
            $xml_order->addAttribute('b_time', $b_time->format('H:i'));
            $xml_order->addAttribute('e_time', $e_time->format('H:i'));
            $xml_order->addAttribute('incl_deliv_sum', $order->total_shipping); //ToDo переработать этот вопрос
            $xml_order->addAttribute('places', 1); //ToDo количество занимаемых мест.. переработать
            $xml_order->addAttribute('city', $city);
            $xml_order->addChild('contacts', ($address->phone_mobile == '') ? $address->phone : $address->phone_mobile); //ToDo переработать телефоны
//            $xml_order->addChild('sms', 'тел. ');
//            $xml_order->addChild('sms_sender', 'тел. ');
//            $xml_order->addChild('description', $additionalInfo);
            $xml_services = $xml_order->addChild('services');
            $xml_services->addAttribute('cash', 'no');
            $xml_services->addAttribute('cheque', 'no'); //ToDo переработать
            $xml_services->addAttribute('selsize', 'no');
            $xml_items = $xml_order->addChild('items');

            $sum = $order->total_shipping;
            $totalQuantity = 0;
            $names = 0;

            foreach ($products as $product) {
                $xml_item = $xml_items->addChild('item');
                $xml_item->addAttribute('name', $product['name']);
                $xml_item->addAttribute('weight', $product['weight']);
                $xml_item->addAttribute('quantity', $product['quantity']);
                $xml_item->addAttribute('price', $product['total']); //ToDo или total_wt?
                $totalQuantity += $product['quantity'];
            }

            $checksum = md5($this->uid."u". count($products) . $totalQuantity );
            $xml_auth->addAttribute('checksum', $checksum);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $this->url); // set url to post to
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
				return false;
			}
        }
    }

    public function deleteTo($id)
    {
//ToDo валидация входных данных
//        	elseif (isset($_POST['delivery']) AND $_POST['delivery'] == 'axiomus_deliver')
//	{
//		if ($_POST['pickup_date'] == '') {
//			$errors['pickup_date'] = 'Введите дату выполнения заявки';
//		}
//		else
//		{
//			if ($data['delivery_region'] == 'piter')
//			{
//				$city = 1;
//				$min_datetime = strtotime(date('Y-m-d 00:00:00', time()+2*86400));
//				if (strtotime($_POST['pickup_date']) < $min_datetime)
//				{
//					$errors['pickup_date'] = 'Дата выполнения должна быть не ранее, чем послезавтра.';
//				}
//			}
//			else
//			{
//				$city = 0;
//				$min_datetime = strtotime(date('Y-m-d 00:00:00', time()+86400));
//				if (strtotime($_POST['pickup_date']) < $min_datetime)
//				{
//					$errors['pickup_date'] = 'Дата выполнения должна быть не ранее, чем завтра.';
//				}
//			}
//
//		}
//		if (( ! preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/i', $_POST['timeFrom'])) || ( ! preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/i', $_POST['timeTo']))) {
//			$errors['time'] = 'Введите время доставки.';
//		}
//		elseif ($_POST['timeFrom'] > $_POST['timeTo'])
//		{
//			$errors['time'] = 'Время начала доставки не может быть большим, чем время окончания.';
//		}
//		if ($_POST['positionsCount'] <= 0)
//		{
//			$errors['positionsCount'] = 'Введите количество мест.';
//		}
        $errors = [];
        if (!count($errors)) {
            $order = new Order($id);
            $cart = new Cart($order->id_cart);

            $AxiomusPost = new AxiomusPost();
            $axiomus_order = $AxiomusPost->getOrderByIdCart($cart->id);
            $okey = $axiomus_order['okay'];

            $xml_single_order = new SimpleXMLElement('<singleorder/>');
            $xml_single_order->addChild('mode', 'delete');
            $xml_auth = $xml_single_order->addChild('auth');
            $xml_auth->addAttribute('ukey', $this->ukey);
            $xml_single_order->addChild('okey', $okey);


            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $this->url); // set url to post to
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
    }

    public function getCarryAddresses($method){
        $errors = [];
        if (!count($errors)) {
            $AxiomusPost = new AxiomusPost();

            $xml_single_order = new SimpleXMLElement('<singleorder/>');
            $xml_single_order->addChild('mode', $method);
            $xml_auth = $xml_single_order->addChild('auth');
            $xml_auth->addAttribute('ukey', $this->ukey);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $this->url); // set url to post to
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
                return $result->status; //ToDo т.к. не можем проверить анулирование
                return false;
            }
        }
    }
}



