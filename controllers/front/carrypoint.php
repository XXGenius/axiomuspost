<?php
/**
 * Created by PhpStorm.
 * User: genius
 * Date: 25.07.17
 * Time: 12:59
 */

/**
 * http://serverpresta16new.com/index.php?fc=module&module=axiomuspostcarrier&controller=getpostaxiomus
 */
require_once(_PS_MODULE_DIR_ . 'axiomuspostcarrier/models/AxiomusPost.php');

class axiomuspostcarrierCarryPointModuleFrontController extends ModuleFrontController
{
    public function init()
    {
        parent::init();
        if($_POST['city'] == 0) { //city[0]-Москва, [1]-Питер,[2]- Регионы
            $sql = ("SELECT address AS 'city' FROM ps_axiomus_cache_carry_pecom WHERE city_name='Москва'");
            $city = Db::getInstance()->executeS($sql);
            echo json_encode($city);
            exit;
        }else if($_POST['city'] == 1){
            $sql = ("SELECT address AS 'city' FROM ps_axiomus_cache_carry_pecom WHERE city_name='Санкт-петербург'");
            $city = Db::getInstance()->executeS($sql);
            echo json_encode($city);
            exit;
        }else if($_POST['city'] == 2){
            $sql = ("SELECT  city_name as 'city' FROM ps_axiomus_cache_carry_pecom GROUP BY city_name");
            $city= Db::getInstance()->executeS($sql);
            echo json_encode($city);
            exit;
        }
    }

};
