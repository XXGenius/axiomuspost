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

class axiomuspostcarrierKadModuleFrontController extends ModuleFrontController
{
    public function init()
    {
        parent::init();
        if($_POST['data_id'] == 0) { //city[0]-Москва, [1]-Питер,[2]- Регионы
            $sql = ("SELECT `id`, `name` FROM ps_axiomus_kad_type WHERE city='Москва'");
            $city = Db::getInstance()->executeS($sql);
            echo json_encode($city);
            exit;
        }else if($_POST['data_id'] == 1){
            $sql = ("SELECT `id`, `name` FROM ps_axiomus_kad_type WHERE city='Санкт-Петербург'");
            $city = Db::getInstance()->executeS($sql);
            echo json_encode($city);
            exit;
        }else if($_POST['data_id'] == 3){
            exit;
        }
    }

};
