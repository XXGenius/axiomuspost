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
            $this->context = new Context($_POST['context_id']);
            $sendNewAxiomus = new AxiomusXml();
            $AxiomusPost = new AxiomusPost();
            $order = new Order((int)$_POST['order_id']);
            $carrier_id = (int)Configuration::get('RS_AXIOMUS_ID_UNDEFINED_DELIVERY');
            if($_POST['action']=='new') {
                if($_POST['delivery']=='axiomus') {
                    $Response = $sendNewAxiomus->sendTo((int)$_POST['order_id'], 'new');
                    $carrier_id = (int)Configuration::get('RS_AXIOMUS_ID_AXIOMUS_DELIVERY');
                }elseif ($_POST['delivery']=='strizh'){
                    $Response = $sendNewAxiomus->sendTo((int)$_POST['order_id'], 'new_strizh');
                    $carrier_id = (int)Configuration::get('RS_AXIOMUS_ID_STRIZH_DELIVERY');
                }elseif($_POST['delivery']=='pek'){
                    $Response = $sendNewAxiomus->sendTo((int)$_POST['order_id'], 'new_pek');
                    $carrier_id = (int)Configuration::get('RS_AXIOMUS_ID_PEK_DELIVERY');
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
                    $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_200_SEND_ORDER_STATUS_ID'));

                    $AxiomusPost->setOrderResponse($order->id_cart, $Response['oid'], $Response['okey']);

                    //ToDo гдето отметить что все успешно прошло
                    echo true;
                } else {
                    echo false;
                }
            }elseif ($_POST['action']=='delete'){
                $status = $sendNewAxiomus->deleteTo((int)$_POST['order_id']);
                //ToDo не могу проверить статус "не допускается аннулирование заявки"
                if (!empty($status)){ //ToDo предположим что тут значит сработало
                    $order->setWsShippingNumber(''); //ToDo а точно ли oid = номер отслеживания?
                    $order->shipping_number = '';
                    $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_90_CANCEL_ORDER_STATUS_ID'));
                }
            }
    }
}