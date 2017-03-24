<?php
/**
 * http://serverpresta16new.com/index.php?fc=module&module=axiomuspostcarrier&controller=getpostaxiomus
 */
class axiomuspostcarrierGetPostAxiomusModuleFrontController extends ModuleFrontController
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
        if (!empty($_POST['oid'])){
            if (isset($_POST['status'])) {
                $oid = (int)$_POST['oid'];
                $status = (int)$_POST['status'];

                $id_order = Db::getInstance()->getValue('
			SELECT `id_order`
			FROM `' . _DB_PREFIX_ . 'order_carrier`
			WHERE `tracking_number` = ' . $oid);

                if ($id_order) {
                    $order = new Order($id_order);

                    switch ($status) {
                        case -10: //Отклонена (Заказ отклонен, причина указана в поле Примечания.Измените или отмените заказ)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_10_RETURN_ORDER_STATUS_ID'));

                            break;
                        case 0: //В обработке (Заявка оформлена в нашей системе, ожидает дальнейшего исполнения)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_0_PROGRES_ORDER_STATUS_ID'));
                            break;
                        case 1: //Укомплектован (Заказ укомплектован на складе)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_1_COMPLETE_ORDER_STATUS_ID'));
                            break;
                        case 2: //Товар на складе (Заказ принят на складе)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_2_INSTOCK_ORDER_STATUS_ID'));
                            break;
                        case 3: //Нет товара (Товар по данной заявке отсутствует)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_3_NOPRODUCT_ORDER_STATUS_ID'));
                            break;
                        case 4: //Исполнение (Заказ исполняется нашим сотрудником)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_4_PERFORMANCE_ORDER_STATUS_ID'));
                            break;
                        case 5: //В процессе (Сложности с заказом, наш сотрудник свяжется с Вами)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_5_INPROCESS_ORDER_STATUS_ID'));
                            break;
                        case 6: //Перенос доставки (Заказ перенесен на другую дату исполнения)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_6_TRANSFERDELIVERY_ORDER_STATUS_ID'));
                            break;
                        case 20: //Комплектация (Заказ комплектуется)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_20_COMPLETED_ORDER_STATUS_ID'));
                            break;
                        case 30: //Сортировка (Заказ отсортирован на складе)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_30_SORT_ORDER_STATUS_ID'));
                            break;
                        case 70: //Поступил в ПВЗ (Заказ поступил для исполнения на ПВЗ)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_70_INPVZ_ORDER_STATUS_ID'));
                            break;
                        case 80: //*Исполнен (Заказ исполнен нашим сотрудником, доставляется на склад)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_80_FULFILLED_ORDER_STATUS_ID'));
                            break;
                        case 81: //Предотмена (Заявлена предварительная отмена заявки)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_81_PRECANCEL_ORDER_STATUS_ID'));
                            break;
                        case 90: //Отмена (Заявка отменена)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_90_CANCEL_ORDER_STATUS_ID'));
                            break;
                        case 100: //Выполнен (Заявка исполнена)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_100_FINISHED_STATUS_ID'));
                            break;
                        case 105: //Отправлен (Заказ отправлен в внешнюю службу доставки)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_105_SENDPARTNER_ORDER_STATUS_ID'));
                            break;
                        case 107: //Вручен (Заказ вручен получателю)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_107_AWARDED_ORDER_STATUS_ID'));
                            break;
                        case 110: //Частичный отказ (Покупатель отказался от части товаров в заказе. Товар поступил на склад)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_110_FAILURE_ORDER_STATUS_ID'));
                            break;
                        case 120: //Полный отказ (Покупатель/Заказчик отказался от заказа. Товар поступил на склад)
                            $order->setCurrentState((int)Configuration::get('RS_AXIOMUS_120_FULLFAILURE_ORDER_STATUS_ID'));
                            break;
                    }
                    echo 'Статус для ' . $id_order . '(' . $oid . ')' . ' обновлен на:' . $status;
                } else {
                    echo 'Не найден order с таким трекномером:' . $oid;

                }
            }else{
                echo 'status пустой';

            }

        }else{
            echo 'oid пустой';

        }
        exit;
    }
}