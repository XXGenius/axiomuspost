<?php

/**
 * Created by PhpStorm.
 * User: zitttz
 * Date: 15.03.17
 * Time: 20:19
 * ModuleFrontController ModuleFrontControllerCore
 * @type CustomerCore Customer
 */
include_once (_PS_MODULE_DIR_ . 'axiomuspostcarrier/models/AxiomusPost.php');

class axiomuspostcarrierValidationOneClickModuleFrontController extends ModuleFrontController
{
    public $AxiomusPost;

    public function postProcess(){

        $cart = $this->context->cart;
        $delivery_id = (int)Configuration::get('RS_AXIOMUS_ID_UNDEFINED_DELIVERY');

        $this->AxiomusPost = new AxiomusPost();


            if ($_POST['delivery-type'] == '1'){ //Самовывоз
                if($_POST['select-region'] == '0'){//Москва

                }elseif($_POST['select-region'] == '1') {//Питер

                }elseif ($_POST['select-region'] == '2'){ //Регион

                }else{
                    echo json_encode(['success' => false]);
                    exit;
                }
            }elseif($_POST['delivery-type'] == '0'){ //Доставка
                $delivery_date = new DateTime($_POST['delivery_date']);
                $date_now = new DateTime();
                if ($delivery_date < $date_now){
                    echo json_encode(['error' => 'date_delivery<date_now']);
                }
            }else{
                echo json_encode(['success' => false]);
                exit;
            }

            $res = $this->AxiomusPost->setOrder($cart->id, $delivery_id, $_POST);

            if ($res) {
                $this->context->cart->update(); //ToDo Почему carry-name а не carry-id
                $carrier_id = (int)Configuration::get('RS_AXIOMUS_ID_UNDEFINED_DELIVERY'); //ToDo добавить такой же обработчик для кнопки "отправить в Axiomus"
                $cart->id_carrier = $carrier_id;
                $delivery_option = $this->context->cart->getDeliveryOption();
                $delivery_option[(int)$this->context->cart->id_address_delivery] = $carrier_id . ',';
                $this->context->cart->setDeliveryOption($delivery_option);
                $this->context->cart->save();

                echo json_encode(['success' => true]);
                exit;
            }else{
                echo json_encode(['success' => false]);
                exit;
            }

    }
}