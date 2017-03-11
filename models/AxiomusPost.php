<?php

class AxiomusPost extends ObjectModel {

    public $id;
    public $id_state;
    public $id_post_zone;
    public $active;
    public $tableWithPrefix;
    public $tableCacheWithPrefix; //ToDo а нужен ли public?
    public $tableOrderWithPrefix;
    protected $dbconn;
    public static $definition = array(
        'table' => 'axiomus_order',
        'tableCache' => 'axiomus_cache',
        'tableOrder' => 'axiomus_order',
        'primary' => 'id',//ToDo нужно ли это все
        'multilang' => false,
        'fields' => array( //ToDo нужно ли это все
            'id_state' => array(
                'type' => ObjectModel::TYPE_INT,
                'required' => true
            ),
            'id_post_zone' => array(
                'type' => ObjectModel::TYPE_INT,
                'required' => true
            ),
            'active' => array(
                'type' => ObjectModel::TYPE_INT,
                'required' => false
            ),
        ),
    );

    public function __construct($id = null, $id_lang = null, $id_shop = NULL) {

        parent::__construct($id, $id_lang, $id_shop);

        $this->tableWithPrefix = _DB_PREFIX_ . AxiomusPost::$definition['table'];
        $this->tableCacheWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableCache']; //ToDo может както попроще, зачем столько параметров
        $this->tableOrderWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableOrder']; //ToDo может както попроще, зачем столько параметров
    }

    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableOrderWithPrefix}` (" .
            '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
            '`order_id` INT(11) NOT NULL,' .
            '`order_code` VARCHAR(50) NOT NULL,' .
            '`carry` BOOLEAN NOT NULL,' .
            '`company_name` VARCHAR(50) NOT NULL,' .
            '`address` VARCHAR(255) NOT NULL,' .
            '`track_number` VARCHAR(255),' .
            '`status` INT(11),' .
            '`finish_datetime` DATETIME,' .
            '`send_datetime` DATETIME,' .
            '`create_datetime` DATETIME DEFAULT CURRENT_TIMESTAMP NULL,' .
            'PRIMARY KEY (`id`)' .
            ') DEFAULT CHARSET=utf8;';

        if (!Db::getInstance()->execute($sql, false)) {
            return false;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableCacheWithPrefix}` (" .
                '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
                '`id_addr` INT(11) NOT NULL,' .
                '`create_datetime` DATETIME DEFAULT CURRENT_TIMESTAMP NULL,' .
                '`total_weight` FLOAT NOT NULL,' .
                '`price_axiomus_delivery` INT(11),' .
                '`price_topdelivery_delivery` INT(11),' .
                '`price_dpd_delivery` INT(11),' .
                '`price_boxberry_delivery` INT(11),' .
                '`price_axiomus_carry` INT(11),' .
                '`price_topdelivery_carry` INT(11),' .
                '`price_dpd_carry` INT(11),' .
                '`price_boxberry_carry` INT(11),' .
                '`price_russianpost_carry` INT(11),' .
                'PRIMARY KEY (`id`)' .
                ') DEFAULT CHARSET=utf8;';

        if (!Db::getInstance()->execute($sql, false)) {
            return false;
        }
//        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableWithPrefix}` (" .
//            '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
//            '`id_state` INT(11) NOT NULL,' .
//            '`id_post_zone` INT(11) NOT NULL,' .
//            '`active` INT(11) NOT NULL,' .
//            'PRIMARY KEY (`id`)' .
//            ') DEFAULT CHARSET=utf8;';
//
//        if (!Db::getInstance()->execute($sql, false)) {
//            return false;
//        }
        return true;
    }

    public function dropTable() {

        $sql = "DROP TABLE IF EXISTS `".$this->tableWithPrefix."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $sql = "DROP TABLE IF EXISTS `".$this->tableCacheWithPrefix."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $sql = "DROP TABLE IF EXISTS `".$this->tableOrderWithPrefix."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        return true;
    }

    public function getRpZone($addr) {

        if (!Country::containsStates($addr->id_country))
            return 0;

        if (!$addr->id_state)
            return 0;

        $row = AxiomusPost::$db->getRow("SELECT * FROM `{$this->tableWithPrefix}` WHERE `id_state` = {$addr->id_state} AND `active` = 1");

        if (!isset($row['id_post_zone']))
            return 0;

        return $row['id_post_zone'];

        //$row = $this->dbconn->getRow("SELECT ");
    }

    public function getPriceInCache($id_addr, $totalWeight){
        if ($totalWeight <= 1){
            $left = 0;
            $right = 1;
        }elseif ($totalWeight>1 && $totalWeight<=3){
            $left = 1;
            $right = 3;
        }elseif ($totalWeight>3 && $totalWeight<=5){
            $left = 3;
            $right = 5;
        }elseif ($totalWeight>5 && $totalWeight<=10){
            $left = 5;
            $right = 10;
        }elseif ($totalWeight>10 && $totalWeight<=15){
            $left = 10;
            $right = 15;
        }elseif ($totalWeight>15 && $totalWeight<=25){
            $left = 15;
            $right = 25;
        }elseif ($totalWeight>25){
                $left = 0;
                $right = 0;
        }

        $sql = "SELECT * FROM `{$this->tableCacheWithPrefix}` WHERE `id_addr` = {$id_addr} AND (`total_weight` >= {$left} AND `total_weight` < {$right})";

        $rows = Db::getInstance()->executeS($sql);
        if (is_array($rows)) {
            foreach ($rows as $row) {
                {
                    $date = new DateTime();
                    $date->format('Y-m-d H:i:s');

                    $dateInBase = new DateTime($row['create_datetime']);
                    $dateInBase->add(DateInterval::createfromdatestring('+' . (int)Configuration::get('RS_AXIOMUS_CACHE_HOURLIFE') . ' hour'));

                    if (date_timestamp_get($dateInBase) < date_timestamp_get($date)) {
                        $sql = "DELETE FROM `{$this->tableCacheWithPrefix}` WHERE `id` = {$row['id']}";
                        Db::getInstance()->execute($sql);
                    } else {
                        return $row;
                    }
                }
            }
        }
        return false;
    }

    public function insertRowCache($id_addr, $totalWeight, $priceAxiomusDelivery, $priceTopDeliveryDelivery, $priceDPDDelivery, $priceBoxBerryDelivery, $priceAxiomusCarry, $priceTopDeliveryCarry, $priceDPDCarry, $priceBoxBerryCarry, $priceRussianPostCarry){
        Db::getInstance()->autoExecuteWithNullValues($this->tableCacheWithPrefix, ['id_addr' => $id_addr, 'total_weight' => $totalWeight, 'price_axiomus_delivery' => $priceAxiomusDelivery, 'price_topdelivery_delivery' => $priceTopDeliveryDelivery, 'price_dpd_delivery' => $priceDPDDelivery, 'price_boxberry_delivery' => $priceBoxBerryDelivery, 'price_axiomus_carry' => $priceAxiomusCarry, 'price_topdelivery_carry' => $priceTopDeliveryCarry, 'price_dpd_carry' => $priceDPDCarry, 'price_boxberry_carry' => $priceBoxBerryCarry, 'price_russianpost_carry' => $priceRussianPostCarry],'INSERT');
        //ToDo добавить исключения
        return true;
    }

    public function insertRowOrder($orderId, $cartда){
        $orderCode = '';
        $address = 0;
        $status = 0;
        $carry = 0;
        $companyName = '';
//        Db::getInstance()->autoExecuteWithNullValues($this->tableOrderWithPrefix, ['order_id' => $orderId, 'order_code' => $orderCode, 'address' => $address,  'status' => $status, 'carry' => $carry, 'company_name' => $companyName,'INSERT');
        //ToDo добавить исключения
        return true;
    }

}