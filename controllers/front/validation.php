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
            $this->AxiomusPost->insertOrder($cart->id, 'axiomus', (int)$_POST['delivery-type'], (int)$_POST['kad-type'], (int)$_POST['time-type']);
//            $id_cart, $delivery, $carry, $kad, $time
            //ToDo добавить валидацию и запись параметров в бд
            Tools::redirect('index.php?controller=order&step=3');
        }else{
            Tools::redirect('index.php');
        }

    }
}