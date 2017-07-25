<?php
/**
 * Created by PhpStorm.
 * User: genius
 * Date: 24.07.17
 * Time: 12:59
 */

/**
 * http://serverpresta16new.com/index.php?fc=module&module=axiomuspostcarrier&controller=getpostaxiomus
 */
require_once(_PS_MODULE_DIR_ . 'axiomuspostcarrier/models/AxiomusPost.php');

class axiomuspostcarrierGetPointCountryModuleFrontController extends ModuleFrontController
{
    public function init()
    {
        $city= $_POST['home'];// home - выбранный город ИЗ РЕГИОНОВ в данный момент
        $sql = ("SELECT  address AS 'city' FROM ps_axiomus_cache_carry_pecom WHERE `city_name`='{$city}'");
        $city= Db::getInstance()->executeS($sql);
        echo json_encode($city);
        exit;

    }

};
