<?php
/**
 * Обновление статуса из POST запроса AXIOMUS
 * Для запуска обновления статуса через web-скрипт вам потребуется произвести
 * настройку в карточке клиента - указать адрес web-скрипта в поле «Адрес web-
 * вызова на изменение статуса заявки».
 */
//ToDo добавить проверку что запрос идет с домена axiomus

if (!empty($_POST['oid'])){
    if (!empty($_POST['status'])) {
        $oid = (int)$_POST['oid'];
        $status = (int)$_POST['status'];

        $id_order = Db::getInstance()->getValue('
			SELECT `id_order`
			FROM `' . _DB_PREFIX_ . 'order_carrier`
			WHERE `tracking_number` = ' . $oid);

        if ($id_order) {
            $history = new OrderHistory();
            $history->id_order = (int)$id_order;
            switch ($status) {
                case -10: //Отклонена (Заказ отклонен, причина указана в поле Примечания.Измените или отмените заказ)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_10_RETURN_ORDER_STATUS_ID'), (int)$id_order);
                case 0: //В обработке (Заявка оформлена в нашей системе, ожидает дальнейшего исполнения)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_0_PROGRES_ORDER_STATUS_ID'), (int)$id_order);
                case 1: //Укомплектован (Заказ укомплектован на складе)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_1_COMPLETE_ORDER_STATUS_ID'), (int)$id_order);
                case 2: //Товар на складе (Заказ принят на складе)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_2_INSTOCK_ORDER_STATUS_ID'), (int)$id_order);
                case 3: //Нет товара (Товар по данной заявке отсутствует)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_3_NOPRODUCT_ORDER_STATUS_ID'), (int)$id_order);
                case 4: //Исполнение (Заказ исполняется нашим сотрудником)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_4_PERFORMANCE_ORDER_STATUS_ID'), (int)$id_order);
                case 5: //В процессе (Сложности с заказом, наш сотрудник свяжется с Вами)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_5_INPROCESS_ORDER_STATUS_ID'), (int)$id_order);
                case 6: //Перенос доставки (Заказ перенесен на другую дату исполнения)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_6_TRANSFERDELIVERY_ORDER_STATUS_ID'), (int)$id_order);
                case 20: //Комплектация (Заказ комплектуется)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_20_COMPLETED_ORDER_STATUS_ID'), (int)$id_order);
                case 30: //Сортировка (Заказ отсортирован на складе)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_30_SORT_ORDER_STATUS_ID'), (int)$id_order);
                case 70: //Поступил в ПВЗ (Заказ поступил для исполнения на ПВЗ)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_70_INPVZ_ORDER_STATUS_ID'), (int)$id_order);
                case 80: //*Исполнен (Заказ исполнен нашим сотрудником, доставляется на склад)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_80_FULFILLED_ORDER_STATUS_ID'), (int)$id_order);
                case 81: //Предотмена (Заявлена предварительная отмена заявки)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_81_PRECANCEL_ORDER_STATUS_ID'), (int)$id_order);
                case 90: //Отмена (Заявка отменена)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_90_CANCEL_ORDER_STATUS_ID'), (int)$id_order);
                case 100: //Выполнен (Заявка исполнена)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_100_FINISHED_STATUS_ID'), (int)$id_order);
                case 105: //Отправлен (Заказ отправлен в внешнюю службу доставки)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_105_SENDPARTNER_ORDER_STATUS_ID'), (int)$id_order);
                case 107: //Вручен (Заказ вручен получателю)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_107_AWARDED_ORDER_STATUS_ID'), (int)$id_order);
                case 110: //Частичный отказ (Покупатель отказался от части товаров в заказе. Товар поступил на склад)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_110_FAILURE_ORDER_STATUS_ID'), (int)$id_order);
                case 120: //Полный отказ (Покупатель/Заказчик отказался от заказа. Товар поступил на склад)
                    $history->changeIdOrderState(Configuration::get('RS_AXIOMUS_120_FULLFAILURE_ORDER_STATUS_ID'), (int)$id_order);
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