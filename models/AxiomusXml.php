<?php

/**
 * Created by PhpStorm.
 * User: zitttz
 * Date: 13.03.17
 * Time: 10:29
 */
class AxiomusXml
{
    public function sendTo($id)
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
            $axiomus_ukey = Configuration::get('RS_AXIOMUS_TOKEN');

            $xml_single_order = new SimpleXMLElement('<singleorder/>');
            $xml_single_order->addChild('mode', 'new');
            $xml_auth = $xml_single_order->addChild('auth');
            $xml_auth->addAttribute('ukey', $axiomus_ukey);
            $xml_order = $xml_single_order->addChild('order');
            $xml_order->addAttribute('inner_id', "Заказ #" . $id);
            $xml_order->addAttribute('name', $address->firstname. ' ' . $address->lastname); //ToDo добавить отчество?
            $xml_order->addAttribute('address', $address->city . ', ул. ' . $address->address1); //ToDo что делать с address2?
//            $xml_order->addAttribute('from_mkad', $data['delivery_distance']); //ToDo проверить можно ли без него
//            $xml_order->addAttribute('d_date', date('Y-m-d', strtotime($_POST['pickup_date'])));
            $xml_order->addAttribute('b_time', $_POST['timeFrom']);
            $xml_order->addAttribute('e_time', $_POST['timeTo']);
            $xml_order->addAttribute('incl_deliv_sum', $order->total_shipping); //ToDo переработать этот вопрос
            $xml_order->addAttribute('places', 1); //ToDo количество занимаемых мест.. переработать
            $xml_order->addAttribute('city', $address->city);
            $xml_order->addChild('contacts', 'тел. ' . ($address->phone!='')?$address->phone:$address->phone_mobile); //ToDo переработать телефоны
//            $xml_order->addChild('sms', 'тел. ');
//            $xml_order->addChild('sms_sender', 'тел. ');
//            $xml_order->addChild('description', $additionalInfo);
            $xml_services = $xml_order->addChild('services');
            $xml_services->addAttribute('cash', 'no');
            $xml_services->addAttribute('cheque', 'no'); //ToDo переработать
            $xml_services->addAttribute('selsize', 'no');
            $xml_items = $xml_order->addChild('items');
        }
    }
}

//
//            $sum = $data['delivery_calc_price'];
//            $quantity = 0;
//            $names = 0;
//
//            $order_goods = unserialize($data['goods']);
//            if (count($order_goods) > 0) {
//                $r = myfq('SELECT `id`, `delivery_weight`, `title`, `action`, `volume`, `price` FROM `type4` WHERE 	`id` IN (' . implode(' , ', array_keys($order_goods)) . ')', null);
//
//                while ($f = mysql_fetch_array($r)) {
//                    $f['action'] = $order_goods[$f['id']]['action'];
//                    $f['volume'] = $order_goods[$f['id']]['volume'];
//                    $f['price'] = $order_goods[$f['id']]['price'];
//
//                    $price = ($f['action'] == 1) ? $f['price'] : $f['volume'];
//                    $xml_item = $xml_items->addChild('item');
//                    $xml_item->addAttribute('name', $f['title']);
//                    $xml_item->addAttribute('weight', str_replace(',', '.', $f['delivery_weight'] / 1000));
//                    $xml_item->addAttribute('quantity', $order_goods[$f['id']]['cnt']);
//                    $xml_item->addAttribute('price', $price);
//                    $sum += $order_goods[$f['id']]['cnt'] * $price;
//                    $quantity += $order_goods[$f['id']]['cnt'];
//                    $names++;
//                }
//            }
//
//            $order_coupons = unserialize($data['coupons']);
//
//            if (count($order_coupons) > 0) {
//                $r = myfq('SELECT * FROM `coupons` WHERE 1', NULL);
//                $allcoupons = array();
//                while ($row = mysql_fetch_assoc($r)) {
//                    $allcoupons[] = $row;
//                }
//                foreach ($order_coupons as $code => $cval) {
//                    $_couponMatch = FALSE;
//                    foreach ($allcoupons as $_coupon) {
//                        if ($_coupon['code'] == $cval['setId'] AND $_coupon['set_code'] == $cval['scode']) {
//                            $row = $_coupon;
//                            $_couponMatch = TRUE;
//                            break;
//                        }
//                    }
//                    if (!$_couponMatch) {
//                        continue;
//                    }
//                    $sum += $row['price'];
//                    $xml_item = $xml_items->addChild('item');
//                    $xml_item->addAttribute('name', $row['title'] . ' (по купону ' . $code . ')');
//                    $xml_item->addAttribute('weight', str_replace(',', '.', $row['weight'] / 1000));
//                    $xml_item->addAttribute('quantity', 1);
//                    $xml_item->addAttribute('price', $row['price']);
//                    $quantity++;
//                    $names++;
//                }
//            }
//
//            $checksum = md5($axiomus_uid . $names . $quantity . $sum . date('Y-m-d', strtotime($_POST['pickup_date'])) . ' ' . $_POST['timeFrom'] . 'no/' . $nal . '/no');
//            $xml_auth->addAttribute('checksum', $checksum);
//
//            $ch = curl_init();
//
//            curl_setopt($ch, CURLOPT_URL, "http://axiomus.ru/hydra/api_xml.php"); // set url to post to
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
//            curl_setopt($ch, CURLOPT_POST, 1); // set POST method
//            curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . urlencode($xml_single_order->asXML())); // add POST fields
//            $result_xml = curl_exec($ch);
//
//
//            $result = simplexml_load_string($result_xml);
//
//            $status_code = $result->status['code'];
//            if ($status_code == 0) {
//                $delivery_id = $result->auth['objectid'];
//                myfq("update `orders` set `delivery_id` = '%s' , `delivery_date_from` = '%s' , `status` = 'delivery_request' where `order_id` = %d ", $delivery_id, date('Y-m-d', strtotime($_POST['pickup_date'])), $mpath[3]);
//                $mail = new MyMail();
//                $mail->content_type = 'text/html; charset=utf-8';
//                $mail->to = $data['email'];
//                $mail->from = 'Oliva&Co <noreply@oliva-and-co.com>';
//                $mail->subject = "Ваш заказ №" . $mpath[3] . " передан в службу доставки";
//                $mail->msg = "Добрый день, " . $data['last_name'] . ' ' . $data['first_name'] . ' ' . $data['middle_name'] . "<br>Ваш заказ №" . $mpath[3] . " собран и передан в службу доставки.<br>
//Планируемая дата доставки " . date('d.m.Y', strtotime($_POST['pickup_date'])) . ".<br>
//Курьер свяжется с Вами для уточнения деталей доставки.<br>
//Все вопросы о доставке, Вы можете уточнить в курьерской службе «Аксиомус» по телефонам в Москве: +7(495) 669-3524, +7(495) 740-6068 и Санкт-Петербурге: +7 (812) 966-10-03, +7 (812) 951-84-14.<br><br>
//С уважением,<br />Команда Oliva&Co<br /><img src='http://" . getenv('SERVER_NAME') . "/img/logo.jpg' alt='Команда Oliva&Co' />";
//                $mail->rigorous_email_check = 0;
////				$mail->send();
//                ?><!--<h2 style="color: green">Заявка успешно отправлена в Аксиомус</h2><div style="display:none;">--><?//
//                echo '<textarea>' . $xml_single_order->asXML() . '</textarea>';
//                echo '<textarea>' . $result_xml . '</textarea></div>';
//            } else {
//                ?><!--<h2 style="color: red">Ошибка отправки! Сохраните отладочную информацию:</h2>--><?//
//                echo '<textarea>' . $xml_single_order->asXML() . '</textarea>';
//                echo '<textarea>' . $result_xml . '</textarea>';
//            }
