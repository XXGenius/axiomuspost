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

class axiomuspostcarrierTestModuleFrontController extends ModuleFrontController
{
    public function init()
    {
        parent::init();
        if($_POST == 2) {
                echo json_encode(array('1'=>'Волгоград','2'=> 'Самара','3'=> 'Волгодонск'));
        }
    }

};
