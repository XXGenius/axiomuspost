<?php

/**
 * Created by PhpStorm.
 * User: zitttz
 * Date: 27.02.17
 * Time: 20:15
 */
class AxiomusApi
{
    public function __construct($token){
        $this->token = $token;
    }

// Ссылки на API
    public $url_geo = "https://axiomus.ru/calc/api_geo.php"; // API Geo (география)
    public $url_calc = "https://axiomus.ru/calc/calc.php"; // API Calc (калькулятор)

    public $url_test = "http://axiomus.ru/test/xml/api_xml_test.php"; //url-тестовых запросов
    public $ukey_test = "XXcd208495d565ef66e7dff9f98764XX";
    public $uid_test = 92;

    public function sendXML(){

        //load xml-data from file
        $filename = '1.xml';

        $handle = fopen($filename, "r");
        $xml = fread($handle, filesize($filename));
        $xml = <<<XML
<?xml version='1.0' standalone='yes'?>
<singleorder>
    <mode>get_version</mode>
</singleorder>
XML;

        fclose($handle);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://axiomus.ru/test/api_xml_test.php"); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, "data=".urlencode($xml)); // add POST fields
        $result = curl_exec($ch); // run the whole process
        echo $result; //show result on screen
        curl_close($ch);
    }

// Функция отправки запроса на API (возвращает ответ от API)
    public function send_data($url, $data){

        $data['token'] = $this->token;
        $opts = array('http' =>	array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($data)));

        $context = stream_context_create($opts);

        if($response = file_get_contents($url, false, $context)){
            return json_decode($response,true); // Возвращаем ассоциативный массив
        }else{
            return false; // Ошибка запроса
        }
    }

// Функция для определения по адресу информации о регионе и определения координат через Яндекс.Геокодер. Возвращает массив с координатами и информацией о городе, регионе и области
    public function ya_geocoder($address){

        $url = 'https://geocode-maps.yandex.ru/1.x/?geocode='.urlencode($this->mb_trim($address)).'&amp;format=json&amp;results=1';
        //+ '&key=' + urlencode('API-ключ если у Вас он есть, без него разрешено делать 25000 запросов в день');

        // Получаем ответ от Яндекс.Геокодера и преобразуем в ассоциативный массив
        $response = file_get_contents($url);
        $resp = json_decode($response, true);
        $ya_data = $resp['response']['GeoObjectCollection'];

        // Если удалось определить координаты, составляем выходной массив $g, иначе возвращаем false (Ошибка геокодирования)
        if($ya_data['metaDataProperty']['GeocoderResponseMetaData']['results'] == 1){
            if(isset($ya_data['featureMember'][0]['GeoObject']['Point']['pos']) AND $this->mb_trim($ya_data['featureMember'][0]['GeoObject']['Point']['pos'])!=''){
                $tmp = explode(' ', $ya_data['featureMember'][0]['GeoObject']['Point']['pos']);
                $g['lon'] = $tmp[0]; // Долгота
                $g['lat'] = $tmp[1]; // Широта
                if(isset($ya_data['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName'])){
                    $g['area'] = $ya_data['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName']; // Область
                }else{
                    $g['area'] = '';
                }
                if(isset($ya_data['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName'])){
                    $g['region'] = $ya_data['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName']; // Регион
                }else{
                    $g['region'] = '';
                }
                if(isset($g['region']) AND $g['region']!=='' AND isset($ya_data['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName'])){
                    $g['city'] = $ya_data['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName']; // Город
                }else{
                    if(isset($ya_data['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName'])){
                        $g['city'] = $ya_data['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName']; // Город
                    }
                }
                if(!isset($g['city']) OR $g['city'] === ''){
                    $g['city'] = NULL; // Если город неопределен возвращаем city = NULL
                }
                unset($tmp);
                return $g;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

// Функция которая корректно убирает пробелы в начале и конце строки
    public static function mb_trim($str) {
        return preg_replace("/(^\s+)|(\s+$)/us", "", $str);
    }

}