<?php

require_once(_PS_MODULE_DIR_ . 'axiomuspostcarrier/models/AxiomusPost.php');
require_once(_PS_MODULE_DIR_ . 'axiomuspostcarrier/models/AxiomusXml.php');


class AdminAxiomussendController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function init()
    {
//        if (isset($_GET['token']))
//            if ($_GET['token']!=$this->token)
//                return;
        if (!($object = $this->loadObject(true)))
            return;
//            $this->context = new Context($_POST['context_id']);
            $sendNewAxiomus = new AxiomusXml((int)$_POST['order_id']);
            $AxiomusPost = new AxiomusPost();
            $order = new Order((int)$_POST['order_id']);
            $carrier_id = (int)Configuration::get('RS_AXIOMUS_ID_UNDEFINED_DELIVERY');

            if($_POST['action']=='new') {
                if($_POST['delivery']=='axiomus') {
                    $Response = $sendNewAxiomus->sendToAxiomus('new');
                    $carrier_id = (int)Configuration::get('RS_AXIOMUS_ID_AXIOMUS_DELIVERY');
                }elseif ($_POST['delivery']=='strizh'){
                    $Response = $sendNewAxiomus->sendToAxiomus('new_strizh');
                    $carrier_id = (int)Configuration::get('RS_AXIOMUS_ID_STRIZH_DELIVERY');
                }elseif($_POST['delivery']=='pecom'){
                    $Response = $sendNewAxiomus->sendToPecom($_POST);
                    $carrier_id = (int)Configuration::get('RS_AXIOMUS_ID_PECOM_DELIVERY');
                }else{
                    return;
                }
                if ($Response != false) {

                    $order_carrier = new OrderCarrier($order->id);
                    $order_carrier->id_carrier = $carrier_id;
                    $order_carrier->save();

                    $cart = new Cart((int)$_POST['cart_id']);
                    $cart->update();
                    $cart->id_carrier = $carrier_id;

                    $delivery_option = $cart->getDeliveryOption();
                    $delivery_option[(int)$cart->id_address_delivery] = $carrier_id . ',';
                    $cart->setDeliveryOption($delivery_option);
                    $cart->save();

                    $order->setWsShippingNumber($Response['oid']); //ToDo а точно ли oid = номер отслеживания?
                    $order->shipping_number = $Response['oid'];
                    $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_200_SEND_ORDER_STATUS_ID')); //ToDo для ПЭК сделать свой статус

                    $AxiomusPost->setOrderResponse($order->id_cart, $Response['oid'], $Response['okay']);

                    //ToDo гдето отметить что все успешно прошло
                    echo true;
                    exit;
                } else {
                    echo json_encode($sendNewAxiomus->errors);
                    exit;
                }
            }elseif ($_POST['action']=='delete'){
                if($_POST['delivery']=='axiomus' || $_POST['delivery']=='strizh') {
                    $status = $sendNewAxiomus->deleteToAxiomus((int)$_POST['order_id']);
                    //ToDo не могу проверить статус "не допускается аннулирование заявки"
                    if (!empty($status)){ //ToDo предположим что тут значит сработало
                        $order->setWsShippingNumber(''); //ToDo а точно ли oid = номер отслеживания?
                        $order->shipping_number = '';
                        $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_90_CANCEL_ORDER_STATUS_ID'));
                    }
                }elseif($_POST['delivery']=='pecom'){ //ToDo не забыть подчищать нашу таблицу orders
                    $status = $sendNewAxiomus->deleteToPecom();
                    if ($status){ //ToDo предположим что тут значит сработало
                        $carrier_id = (int)Configuration::get('RS_AXIOMUS_ID_UNDEFINED_DELIVERY');
                        $order_carrier = new OrderCarrier($order->id);
                        $order_carrier->id_carrier = $carrier_id;
                        $order_carrier->save();

                        $cart = new Cart((int)$_POST['cart_id']);
                        $cart->update();
                        $cart->id_carrier = $carrier_id;

                        $delivery_option = $cart->getDeliveryOption();
                        $delivery_option[(int)$cart->id_address_delivery] = $carrier_id . ',';
                        $cart->setDeliveryOption($delivery_option);
                        $cart->save();

                        $order->setWsShippingNumber(''); //ToDo а точно ли oid = номер отслеживания?
                        $order->shipping_number = '';
                        $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_90_CANCEL_ORDER_STATUS_ID'));
                        echo true;
                        exit;
                    }else{
                        return;
                    }
                }else{
                    return;
                }
            }
    }
}