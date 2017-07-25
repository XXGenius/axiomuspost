<?php
/**
 * http://serverpresta16new.com/index.php?fc=module&module=axiomuspostcarrier&controller=getpostaxiomus
 */
require_once(_PS_MODULE_DIR_ . 'axiomuspostcarrier/models/AxiomusPost.php');

class axiomuspostcarrierGetPriceCarryModuleFrontController extends ModuleFrontController
{
    public function init()
    {
        /**
         * Обновление статуса из POST запроса AXIOMUS
         * Для запуска обновления статуса через web-скрипт вам потребуется произвести
         * настройку в карточке клиента - указать адрес web-скрипта в поле «Адрес web-
         * вызова на изменение статуса заявки».
         */
        //ToDo добавить проверку что запрос идет с домена axiomus
        //ToDo статус меняется, но не меняется на страницы просмотра заказа
        parent::init();

        //Легкая проверка на инъекции
        $pattern = preg_quote('#$%^&*()+=-[]\';,./{}|\":<>?~', '#');
        if (!isset($_POST['region'])) {
            exit;
        } else {
            $region = htmlspecialchars($_POST['region']);
            if (preg_match("#[{$pattern}]#", $_POST['region'])) {
                exit;
            }
        }
        if (!isset($_POST['point_id'])) {
            exit;
        } else {
            $point = htmlspecialchars($_POST['point_id']);
            if (preg_match("#[{$pattern}]#", $_POST['point_id'])) {
                exit;
            }
        }
        if (!isset($_POST['cart_id'])) {
            exit;
        } else {
            $cart_id = htmlspecialchars($_POST['cart_id']);
            if (preg_match("#[{$pattern}]#", $_POST['cart_id'])) {
                exit;
            }
        }

        $res = AxiomusPost::getCarryPrice($region, $cart_id, $point);

//            $AxiomusPost = new AxiomusPost();
//            $price = $AxiomusPost->getPrice($_POST['cart_id'], $_POST['city'], (boolean)$_POST['carry'], $_POST['weight'], $_POST['price'],(isset($_POST['carrytype'])?(int)$_POST['carrytype']:null), (isset($_POST['kad'])?(int)$_POST['kad']:null), (isset($_POST['time'])?(int)$_POST['time']:null));
//            echo $price;


        if (!empty($res))  echo $res;
        exit;


    }
}