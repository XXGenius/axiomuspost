<?php
/**
 * http://serverpresta16new.com/index.php?fc=module&module=axiomuspostcarrier&controller=getpostaxiomus
 */
require_once(_PS_MODULE_DIR_ . 'axiomuspostcarrier/models/AxiomusPost.php');

class axiomuspostcarrierGetCarryModuleFrontController extends ModuleFrontController
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
        if(isset($_POST['carry'])) {
            if (isset($_POST['city'])) {
                $AxiomusPost = new AxiomusPost();
                $AddressesArray = $AxiomusPost->getCarryAddresses($_POST['carry'], $_POST['city'], null);

                echo json_encode($AddressesArray);
                exit;
            } else {
                return 'not city';
            }
        }else{
            echo 'not carry';
        }
    }
}