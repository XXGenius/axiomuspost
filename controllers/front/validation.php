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

            $delivery_date = new DateTime($_POST['delivery-date']);
            $date_now = new DateTime();
            if ($delivery_date<$date_now){
                Tools::redirect("index.php?controller=order&step=2");
            }

            $cart = $this->context->cart;
            $customer = new Customer($cart->id_customer);

            $this->AxiomusPost = new AxiomusPost();
            $delivery_id = ((int)$_POST['delivery-type']==0)?(int)Configuration::get('RS_AXIOMUS_ID_UNDEFINED_DELIVERY'):$_POST['carry-name'];
            $res = $this->AxiomusPost->setOrder($cart->id, $delivery_id, $_POST['delivery-date'], (int)$_POST['kad-type'], (int)$_POST['time-type'], (int)$_POST['delivery-type'], (int)$_POST['carry-address']);
            if ($res) {
                if ($_POST['delivery-type'] == 1){
                    $this->context->cart->update(); //ToDo Почему carry-name а не carry-id
                    $carrier_id = (int)$_POST['carry-name']; //ToDo добавить такой же обработчик для кнопки "отправить в Axiomus"
                    $cart->id_carrier = $carrier_id;
                    $delivery_option = $this->context->cart->getDeliveryOption();
                    $delivery_option[(int)$this->context->cart->id_address_delivery] = $carrier_id . ',';
                    $this->context->cart->setDeliveryOption($delivery_option);
                    $this->context->cart->save();

                    //ToDo добавить валидацию
                    Tools::redirect('index.php?controller=order&step=3&cgv=1');
                }elseif($_POST['delivery-type'] == 0){

                    $this->context->cart->update(); //ToDo Почему carry-name а не carry-id
                    $carrier_id = (int)Configuration::get('RS_AXIOMUS_ID_UNDEFINED_DELIVERY'); //ToDo добавить такой же обработчик для кнопки "отправить в Axiomus"
                    $cart->id_carrier = $carrier_id;
                    $delivery_option = $this->context->cart->getDeliveryOption();
                    $delivery_option[(int)$this->context->cart->id_address_delivery] = $carrier_id . ',';
                    $this->context->cart->setDeliveryOption($delivery_option);
                    $this->context->cart->save();

                    Tools::redirect('index.php?controller=order&step=3&cgv=1');
                }else{
                    return;
                }
            }else{
                return;
            }
        }else{
            Tools::redirect('index.php');
        }

    }
}