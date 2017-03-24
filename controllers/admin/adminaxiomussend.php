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

            $sendNewAxiomus = new AxiomusXml();
            $AxiomusPost = new AxiomusPost();
            $order = new Order((int)$_POST['order_id']);

            if($_POST['action']=='new') {
                $Response = $sendNewAxiomus->sendTo((int)$_POST['order_id']);
                if ($Response != false) {

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