<?php

/**
 * Created by PhpStorm.
 * User: zitttz
 * Date: 15.03.17
 * Time: 20:19
 */
class axiomuspostcarrierValidationModuleFrontController extends ModuleFrontController
{
    public function postProcess(){
        $cart = $this->context->cart;
        $customer = new Customer($cart->id_customer);
        //ToDo добавить валидацию и запись параметров в бд
        Tools::redirect('index.php?controller=order&step=3');
    }
}