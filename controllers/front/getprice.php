<?php
/**
 * http://serverpresta16new.com/index.php?fc=module&module=axiomuspostcarrier&controller=getpostaxiomus
 */
require_once(_PS_MODULE_DIR_ . 'axiomuspostcarrier/models/AxiomusPost.php');

class axiomuspostcarrierGetPriceModuleFrontController extends ModuleFrontController
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
//        parent::init();
        if(isset($_POST['carry'])){
            if($_POST['carry']=='0'){
                $AxiomusPost = new AxiomusPost();
                if ($_POST['city']=='msw'){
                    $city = 'Москва';
                }elseif ($_POST['city']=='ptr'){
                    $city = 'Санкт-Петербург'; //ToDo добавить таблицу для других городов
                }
                $price = $AxiomusPost->getPrice('Москва', (boolean)$_POST['carry'], $_POST['weight'], $_POST['price'], (isset($_POST['kad'])?(int)$_POST['kad']:null), (isset($_POST['time'])?(int)$_POST['time']:null));
                echo $price;
                exit;
            }elseif ($_POST['type']=='1'){

            }else{
                echo 'not carry 0';
            }
        }else{
            return 'not carry';
        }
    }
}