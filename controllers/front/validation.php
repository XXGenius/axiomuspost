<?php

/**
 * Created by PhpStorm.
 * User: zitttz
 * Date: 15.03.17
 * Time: 20:19
 */
include_once (_PS_MODULE_DIR_ . 'axiomuspostcarrier/models/AxiomusPost.php');

class axiomuspostcarrierValidationModuleFrontController extends ModuleFrontController
{
    public $AxiomusPost;

    public function postProcess(){
        if(Tools::isSubmit('processCarrier')){
            $cart = $this->context->cart;
            $customer = new Customer($cart->id_customer);

            $this->AxiomusPost = new AxiomusPost();
            $this->AxiomusPost->setOrder($cart->id, 'axiomus',$_POST['delivery-date'], (int)$_POST['delivery-type'], (int)$_POST['kad-type'], (int)$_POST['time-type']);

            $this->context->cart->update();
            $carrier_id =  (int)Configuration::get('RS_AXIOMUS_ID_AXIOMUS_DELIVERY');
            $cart->id_carrier = $carrier_id;
            $delivery_option = $this->context->cart->getDeliveryOption();
            $delivery_option[(int)$this->context->cart->id_address_delivery] = $carrier_id.',';
            $this->context->cart->setDeliveryOption($delivery_option);
            $this->context->cart->save();

            //ToDo добавить валидацию
            Tools::redirect('index.php?controller=order&step=3&cgv=1');
        }else{
            Tools::redirect('index.php');
        }

    }
}