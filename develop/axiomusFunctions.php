<?php
/**
 * Created by PhpStorm.
 * User: zitttz
 * Date: 27.02.17
 * Time: 20:12
 */
include_once (_PS_MODULE_DIR_ . 'axiomuspostcarrier/AxiomusApi.php');



function getAxiomusResponse($address, $x=0, $y=0, $z=0, $val=0, $weight=1)
{
//    $_POST['token'] = "76793d5test0cf77"; // Тестовый токен.


    /*
    ВНИМАНИЕ! Стоимость доставки по тестовому токену ошибочна!
    А также в методе API Geo - point, всегда выставлены параметры limit = 2 и maxdis = 2500.
    Для верных данных используйте Ваш токен выданный компанией Axiomus!
    */

// Шаг 1. Создаем объект для обработки данных
//    $axiomus = new AxiomusApi(Configuration::get("RS_AXIOMUS_TOKEN")); //ToDo а нужен ли нам этот класс, token отличается от ukey

    $result = array();
// Шаг 2. Получаем данные о месте и координаты через Яндекс.Геокодер
    if ($ya_geo = $axiomus->ya_geocoder($address)) {
        /*
            $ya_geo = Array (
                [lon] => 37.596049
                [lat] => 55.585189
                [area] => Москва
                [region] =>
                [city] => Москва
            )
        */

// Шаг 3. Получаем список ближайших пунктов выдачи заказов (по одному ПВЗ каждой компании в радиусе 2,5км от указанной точки) и список компаний (method = point / API Geo)

// Формируем запрос к API Geo
        $data_geo['method'] = "point"; // Метод
        $data_geo['maxdis'] = 2500; // Радиус 2,5км
        $data_geo['show_companies'] = 'true'; // Показать компании
        $data_geo['limit'] = 1; // Выбрать по одному ПВЗ каждой компании
        $data_geo['lon'] = $ya_geo['lon']; // Долгота
        $data_geo['lat'] = $ya_geo['lat']; // Широта

// Отправляем запрос на API Geo и получаем ответ - ассоциативный массив
        if ($result_geo = $axiomus->send_data($axiomus->url_geo, $data_geo)) {
            /*
                $result_geo = Array (
                    [companies] => Array (
                        [0] => Аксиомус
                        [1] => Почта РФ
                        [2] => DPD
                        [4] => TopDelivery
                        [51] => BoxBerry
                        )
                    [ok] => 1
                    [geo] => Array (
                        [lon] => 37.596049
                        [lat] => 55.585189
                        )
                    [pvz] => Array (
                        [0] => Array (
                            [company] => Почта РФ
                            [name] => Отделение почты РФ, Индекс 117405
                            [address] => г. Москва, шоссе Варшавское дом 143
                            [schedule] => пн-пт :08:00-20:00 перерыв 13:00-14:00 сб :09:00-18:00 перерыв 13:00-14:00 вс :выходной
                            [geo] => 55.583561, 37.600109
                            [dist] => 0.313 км
                            [distance] => 313
                            [lon] => 37.600109
                            [lat] => 55.583561
                            [type_company] => 1
                            [code] => 117405
                            )
                        [1] => Array (
                            [company] => BoxBerry
                            [name] => Москва Россошанская_9738_С
                            [address] => 117535, Москва г, Россошанская ул, д.3к1Ас2
                            [schedule] => пн-пт: 11.00-20.00
                            [geo] => 55.5957707, 37.6063254
                            [dist] => 1.344 км
                            [distance] => 1343
                            [lon] => 37.6063254
                            [lat] => 55.5957707
                            [type_company] => 51
                            [code] => 97381
                            [tariff_zone] => 1
                            [cod_allow] => 1
                            [days] => 1
                            )
                        )
                    )
                )
            */

// Шаг 4. Рассчитываем стоимость доставки по каждой компании (method = delivery / API Calc)

// Формируем основной массив для запроса к API Calc
            $data_delivery['method'] = "delivery"; // Метод
            $data_delivery['city'] = $ya_geo['city']; // Город (из Яндекс.Геокодера)
            $data_delivery['area'] = $ya_geo['area']; // Область (из Яндекс.Геокодера)
            $data_delivery['region'] = $ya_geo['region']; // Регион (из Яндекс.Геокодера)
            $data_delivery['x'] = $x; // Ширина
            $data_delivery['y'] = $y; // Длина
            $data_delivery['z'] = $z; // Высота
            $data_delivery['val'] = $val; // Объявленная стоимость (руб)
            $data_delivery['weight'] = $weight; // Вес отправления (кг)
            $data_delivery['lon'] = $ya_geo['lon']; // Долгота
            $data_delivery['lat'] = $ya_geo['lat']; // Широта

// Создаем массив для записи результата
            $result_delivery = array();

// В цикле получаем стоимость доставки по каждой компаниии и записываем в результирующий массив
            foreach ($result_geo['companies'] as $key => $value) {

                // Все компании кроме Почты РФ, т.к. у них доступен только самовывоз из почтового отделения
                if (intval($key) !== 1) {
                    $data_delivery['type_company'] = intval($key);
                    if ($result_delivery_tmp = $axiomus->send_data($axiomus->url_calc, $data_delivery)) {
                        $result_delivery[] = $result_delivery_tmp;
                    }
                }
            }

            if (!empty($result_delivery)) {
                /*
                    $result_delivery = Array (
                        [0] => Array (
                            [Axiomus] => Array (
                                [delivery] => Array (
                                    [0] => Array (
                                        [typeName] => Доставка
                                        [tarifName] => Основной
                                        [cod_allow] => 1
                                        [time] => 0
                                        [date] => 28.06.2016
                                        [price] => 100
                                        )
                                    )
                                )
                            )
                        [1] => Array (
                            [DPD] => Array (
                                [delivery] => Array (
                                    [0] => Array (
                                        [cod_allow] => 1
                                        [price] => 100
                                        [time] => 1
                                        [date] => 29.06.2016
                                        [typeName] => Доставка
                                        [tarifName] => DPD CONSUMER
                                        )
                                    [1] => Array (
                                        [cod_allow] => 1
                                        [price] => 100
                                        [time] => 1
                                        [date] => 29.06.2016
                                        [typeName] => Доставка
                                        [tarifName] => DPD CLASSIC PARCEL
                                        )
                                    )
                                )
                            )
                        [2] => Array (
                            [TopDelivery] => Array (
                                [delivery] => Array (
                                    [0] => Array (
                                        [price] => 100
                                        [time] => 1
                                        [date] => 29.06.2016
                                        [cod_allow] => 1
                                        [typeName] => Доставка
                                        [tarifName] => Основной
                                        )
                                    )
                                )
                            )
                        [3] => Array (
                            [BoxBerry] => Array (
                                [delivery] => Array (
                                    [0] => Array (
                                        [typeName] => Доставка
                                        [tarifName] => Основной
                                        [cod_allow] => 1
                                        [price] => 100
                                        [time] => 2
                                        [date] => 30.06.2016
                                        )
                                    )
                                )
                            )
                        )
                */

// Шаг 5. Рассчитываем стоимость доставки до каждого ранее найденного ПВЗ (method = carry / API Calc)

// Если ПВЗ не найдены, то пропускаем этот шаг
                if ($result_geo['ok'] == 1) {

// Формируем основной массив для запроса к API Calc
                    $data_carry['method'] = "carry"; // Метод
                    $data_carry['city'] = $ya_geo['city']; // Город (из Яндекс.Геокодера)
                    $data_carry['area'] = $ya_geo['area']; // Область (из Яндекс.Геокодера)
                    $data_carry['region'] = $ya_geo['region']; // Регион (из Яндекс.Геокодера)
                    $data_carry['x'] = $x; // Ширина
                    $data_carry['y'] = $y; // Длина
                    $data_carry['z'] = $z; // Высота
                    $data_carry['val'] = $val = 18000; // Объявленная стоимость (руб)
                    $data_carry['weight'] = $weight = 4.2; // Вес отправления (кг)

// Создаем массив для записи результата
                    $result_carry = array();
                    $i = 0;
                    foreach ($result_geo['pvz'] as $key => $value_array) {
                        $send_array = array_merge($value_array, $data_carry); //
                        if ($result_carry_tmp = $axiomus->send_data($axiomus->url_calc, $send_array)) {
                            $result_carry[$i]['pvz'] = $value_array;
                            $result_carry[$i]['calc'] = $result_carry_tmp;
                            $i++;
                        }
                    }

                    if (!empty($result_carry)) {
                        /*
                        $result_carry = Array (
                            [0] => Array (
                                [pvz] => Array (
                                    [company] => Почта РФ
                                    [name] => Отделение почты РФ, Индекс 117405
                                    [address] => г. Москва, шоссе Варшавское дом 143
                                    [schedule] => пн-пт :08:00-20:00 перерыв 13:00-14:00 сб :09:00-18:00 перерыв 13:00-14:00 вс :выходной
                                    [geo] => 55.583561, 37.600109
                                    [dist] => 0.313 км
                                    [distance] => 313
                                    [lon] => 37.600109
                                    [lat] => 55.583561
                                    [type_company] => 1
                                    [code] => 117405
                                    )
                                [calc] => Array (
                                    [PRF] => Array (
                                        [carry] => Array (
                                            [0] => Array (
                                                [error] => Превышен максимально допустимый вес бандероли (максимальный вес - 2,5кг).
                                            )
                                            [1] => Array (
                                                [cod_allow] => 0
                                                [price] => 280
                                                [time] => 3
                                                [date] => 01.07.2016
                                                [tarifName] => Посылка
                                                [typeName] => Самовывоз
                                                )
                                            )
                                        )
                                    )
                                )
                            [1] => Array (
                                [pvz] => Array (
                                    [company] => BoxBerry
                                    [name] => Москва Россошанская_9738_С
                                    [address] => 117535, Москва г, Россошанская ул, д.3к1Ас2
                                    [schedule] => пн-пт: 11.00-20.00
                                    [geo] => 55.5957707, 37.6063254
                                    [dist] => 1.344 км
                                    [distance] => 1343
                                    [lon] => 37.6063254
                                    [lat] => 55.5957707
                                    [type_company] => 51
                                    [code] => 97381
                                    [tariff_zone] => 1
                                    [cod_allow] => 1
                                    [days] => 1
                                    )
                                [calc] => Array (
                                    [BoxBerry] => Array (
                                        [carry] => Array (
                                            [0] => Array (
                                                [cod_allow] => 1
                                                [price] => 100
                                                [time] => 1
                                                [tarifName] => Основной
                                                [typeName] => Самовывоз
                                                [date] => 29.06.2016
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        */

                    } else {
                        $result['error'] = 'Ошибка получения стоимости самовывоза';
                        return $result;
                    }
                } else {
                    // Создаем заглушку для результата если не найдены пункты выдачи
                    $result_carry = array();
                }

// Шаг 6. Собираем массив с полным ответом.

                $result['delivery'] = $result_delivery;
                $result['carry'] = $result_carry;

// В результате мы получили массив с данными о стоимости доставки до двери по всем компаниям и с данными о стоимости самовывоза (с информацией о пунктах выдачи)
                return $result;
                /*
                    $result = Array (
                        [delivery] = Array (
                            [0] => Array (
                                [Axiomus] => Array (
                                    [delivery] => Array (
                                        [0] => Array (
                                            [typeName] => Доставка
                                            [tarifName] => Основной
                                            [cod_allow] => 1
                                            [time] => 0
                                            [date] => 28.06.2016
                                            [price] => 100
                                            )
                                        )
                                    )
                                )
                            [1] => Array (
                                [DPD] => Array (
                                    [delivery] => Array (
                                        [0] => Array (
                                            [cod_allow] => 1
                                            [price] => 100
                                            [time] => 1
                                            [date] => 29.06.2016
                                            [typeName] => Доставка
                                            [tarifName] => DPD CONSUMER
                                            )
                                        [1] => Array (
                                            [cod_allow] => 1
                                            [price] => 100
                                            [time] => 1
                                            [date] => 29.06.2016
                                            [typeName] => Доставка
                                            [tarifName] => DPD CLASSIC PARCEL
                                            )
                                        )
                                    )
                                )
                            [2] => Array (
                                [TopDelivery] => Array (
                                    [delivery] => Array (
                                        [0] => Array (
                                            [price] => 100
                                            [time] => 1
                                            [date] => 29.06.2016
                                            [cod_allow] => 1
                                            [typeName] => Доставка
                                            [tarifName] => Основной
                                            )
                                        )
                                    )
                                )
                            [3] => Array (
                                [BoxBerry] => Array (
                                    [delivery] => Array (
                                        [0] => Array (
                                            [typeName] => Доставка
                                            [tarifName] => Основной
                                            [cod_allow] => 1
                                            [price] => 100
                                            [time] => 2
                                            [date] => 30.06.2016
                                            )
                                        )
                                    )
                                )
                            )
                        [carry] = Array (
                            [0] => Array (
                                [pvz] => Array (
                                    [company] => Почта РФ
                                    [name] => Отделение почты РФ, Индекс 117405
                                    [address] => г. Москва, шоссе Варшавское дом 143
                                    [schedule] => пн-пт :08:00-20:00 перерыв 13:00-14:00 сб :09:00-18:00 перерыв 13:00-14:00 вс :выходной
                                    [geo] => 55.583561, 37.600109
                                    [dist] => 0.313 км
                                    [distance] => 313
                                    [lon] => 37.600109
                                    [lat] => 55.583561
                                    [type_company] => 1
                                    [code] => 117405
                                    )
                                [calc] => Array (
                                    [PRF] => Array (
                                        [carry] => Array (
                                            [0] => Array (
                                                [error] => Превышен максимально допустимый вес бандероли (максимальный вес - 2,5кг).
                                                )
                                            [1] => Array (
                                                [cod_allow] => 0
                                                [price] => 280
                                                [time] => 3
                                                [date] => 01.07.2016
                                                [tarifName] => Посылка
                                                [typeName] => Самовывоз
                                                )
                                            )
                                        )
                                    )
                                )
                            [1] => Array (
                                [pvz] => Array (
                                    [company] => BoxBerry
                                    [name] => Москва Россошанская_9738_С
                                    [address] => 117535, Москва г, Россошанская ул, д.3к1Ас2
                                    [schedule] => пн-пт: 11.00-20.00
                                    [geo] => 55.5957707, 37.6063254
                                    [dist] => 1.344 км
                                    [distance] => 1343
                                    [lon] => 37.6063254
                                    [lat] => 55.5957707
                                    [type_company] => 51
                                    [code] => 97381
                                    [tariff_zone] => 1
                                    [cod_allow] => 1
                                    [days] => 1
                                    )
                                [calc] => Array (
                                    [BoxBerry] => Array (
                                        [carry] => Array (
                                            [0] => Array (
                                                [cod_allow] => 1
                                                [price] => 100
                                                [time] => 1
                                                [tarifName] => Основной
                                                [typeName] => Самовывоз
                                                [date] => 29.06.2016
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        )
                */

// Далее Вы можете использовать этот массив на Ваше усмотрение. Удачи!

            } else {
                $result['error'] = 'Ошибка получения стоимости доставки';
                return $result;
            }

        } else {
            $result['error'] = 'Ошибка получения списка ближайших пунктов выдачи заказов!';
            return $result;
        }

    } else {
        $result['error'] = 'Ошибка геокодирования!';
        return $result;
    }
}