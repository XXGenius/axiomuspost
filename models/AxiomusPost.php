<?php

class AxiomusPost extends ObjectModel {

    public $id;
    public $track_number;
    public $id_state;
    public $id_post_zone;
    public $active;

    public $table;
    public $tableOrderWithPrefix;
    public $tableCacheWithPrefix; //ToDo а нужен ли public?
    public $tableWeightPriceWithPrefix;
    public $tableWeightTypeWithPrefix;
    public $tableTimeTypeWithPrefix;
    public $tableConditionPriceWithPrefix;
    protected $dbconn;
    public $priceTypes = [0, 1, 2, 3, 4, 5, 6];
    protected $cities = ['Москва', 'Санкт-Петербург'];
    protected $deliveries = ['axiomus', 'topdelivery', 'dpd', 'boxberry', 'axiomus-carry', 'topdelivery-carry', 'dpd-carry', 'boxberry-carry', 'russianpost-carry'];

    public static $definition = array(
        'table' => 'axiomus',
        'tableOrder' => 'axiomus_order',
        'tableConditionPrice' => 'axiomus_condition_price',
        'tableWeightPrice' => 'axiomus_weight_price',
        'tableCache' => 'axiomus_cache',
        'tableWeightType' => 'axiomus_weight_type',
        'tableTimeType' => 'axiomus_time_type',

        'primary' => 'id',//Это нужно для работы формы добавления
        'multilang' => false,
        'fields' => array( //ToDo нужно ли это все
            'id' => array(
                'type' => ObjectModel::TYPE_INT,
                'required' => false
            ),
            'track_number' => array(
                'type' => ObjectModel::TYPE_STRING,
                'required' => false
            ),
        ),
    );

    public function __construct($id = null, $id_lang = null, $id_shop = NULL) {

        parent::__construct($id, $id_lang, $id_shop);

        $this->tableCacheWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableCache'];
        $this->tableOrderWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableOrder'];
        $this->tableWeightPriceWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableWeightPrice']; //ToDo может както попроще, зачем столько параметров
        $this->tableConditionPriceWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableConditionPrice'];
        $this->tableWeightTypeWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableWeightType'];
        $this->tableTimeTypeWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableTimeType'];
    }

    public function createTables() {

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableConditionPriceWithPrefix}` (" .
            '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
            '`city` VARCHAR(10) NOT NULL,' .
            '`carry` BOOLEAN NOT NULL,' .
            '`sumfrom` INT(11) NOT NULL,'.
            '`sumto` INT(11) NOT NULL,'.
            '`timetype` INT(11) NOT NULL,' .
            '`kadtype` INT(11) NOT NULL,' .
            '`sum` INT(11),' .
            'PRIMARY KEY (`id`)' .
            ') DEFAULT CHARSET=utf8;';

        if (!Db::getInstance()->execute($sql, false)) {
            return false;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableWeightPriceWithPrefix}` (" .
            '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
            '`city` VARCHAR(10) NOT NULL,' .
            '`delivery` VARCHAR(50) NOT NULL,' .
            '`carry` BOOLEAN NOT NULL,' .
            '`type` INT(11) NOT NULL,' .
            '`sum` INT(11),' .
            'PRIMARY KEY (`id`)' .
            ') DEFAULT CHARSET=utf8;';

        if (!Db::getInstance()->execute($sql, false)) {
            return false;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableWeightTypeWithPrefix}` (" .
            '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
            '`name` VARCHAR(50) NOT NULL,' .
            '`weightfrom` INT(11) NOT NULL,' .
            '`weightto` INT(11) NOT NULL,' .
            'PRIMARY KEY (`id`)' .
            ') DEFAULT CHARSET=utf8;';

        if (!Db::getInstance()->execute($sql, false)) {
            return false;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableTimeTypeWithPrefix}` (" .
            '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
            '`name` VARCHAR(50) NOT NULL,' .
            'PRIMARY KEY (`id`)' .
            ') DEFAULT CHARSET=utf8;';

        if (!Db::getInstance()->execute($sql, false)) {
            return false;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableOrderWithPrefix}` (" .
            '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
            '`id_cart` INT(11) NOT NULL,' .
            '`delivery` VARCHAR(50) NOT NULL,' .
            '`carry` BOOLEAN NOT NULL,' .
            '`price_weight` INT(11),' .
            '`price_condition` INT(11),' .
            'PRIMARY KEY (`id`)' .
            ") DEFAULT CHARSET=utf8;".
            "CREATE UNIQUE INDEX {$this->tableOrderWithPrefix}_id_cart_uindex ON ps_axiomus_order (id_cart);";

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

        return true;
    }

    public function insertStartWeightPrice(){

        $id = 0;
        foreach ($this->cities as $city){
            foreach ($this->deliveries as $delivery){
                foreach ($this->priceTypes as $type) { //ToDo получать из базы
                    $id++;
                    $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightPriceWithPrefix, ['id' => 0, 'city' => $city, 'delivery' => $delivery, 'carry' => false, 'type' => $type,'sum' => 0],'INSERT');
                    if (!$res){
                        return false;
                    }
                }
            }
        }

        return true;
    }

    public function insertStartConditionPrice(){
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 1, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 1, 'kadtype' => 2, 'sum' => 50],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 1, 'kadtype' => 3,  'sum' => 200],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 1, 'kadtype' => 4,  'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 1, 'kadtype' => 5,  'sum' => 480],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 2100, 'sumto' => 5100, 'timetype' => 1, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 2100, 'sumto' => 5100, 'timetype' => 1, 'kadtype' => 2, 'sum' => 50],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 2100, 'sumto' => 5100, 'timetype' => 1, 'kadtype' => 3, 'sum' => 200],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 2100, 'sumto' => 5100, 'timetype' => 1, 'kadtype' => 4, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 2100, 'sumto' => 5100, 'timetype' => 1, 'kadtype' => 5, 'sum' => 480],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 5100, 'sumto' => 999999, 'timetype' => 1, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 5100, 'sumto' => 999999, 'timetype' => 1, 'kadtype' => 2, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 5100, 'sumto' => 999999, 'timetype' => 1, 'kadtype' => 3, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 5100, 'sumto' => 999999, 'timetype' => 1, 'kadtype' => 4, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 5100, 'sumto' => 999999, 'timetype' => 1, 'kadtype' => 5, 'sum' => 480],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 5, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 5, 'kadtype' => 2, 'sum' => 50],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 5, 'kadtype' => 3, 'sum' => 200],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 5, 'kadtype' => 4, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 5, 'kadtype' => 5, 'sum' => 480],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 2, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 2, 'kadtype' => 2, 'sum' => 50],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 2, 'kadtype' => 3, 'sum' => 200],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 2, 'kadtype' => 4, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 2, 'kadtype' => 5, 'sum' => 480],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 3, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 3, 'kadtype' => 2, 'sum' => 50],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 3, 'kadtype' => 3, 'sum' => 200],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 3, 'kadtype' => 4, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 3, 'kadtype' => 5, 'sum' => 480],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 4, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 4, 'kadtype' => 2, 'sum' => 50],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 4, 'kadtype' => 3, 'sum' => 200],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 4, 'kadtype' => 4, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 4, 'kadtype' => 5, 'sum' => 480],'INSERT');
        return true;
    }

    public function insertStartWeightType(){

        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 1, 'name' => 'до 1кг.', 'weightfrom' => 0, 'weightto' => 1],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 2, 'name' => 'от 1 до 3кг.', 'weightfrom' => 1, 'weightto' => 3],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 3, 'name' => 'от 3 до 5кг.', 'weightfrom' => 3, 'weightto' => 5],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 4, 'name' => 'от 5 до 10кг', 'weightfrom' => 5, 'weightto' => 10],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 5, 'name' => 'от 10 др 15кг.', 'weightfrom' => 10, 'weightto' => 15],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 6, 'name' => 'от 15 до 25кг.', 'weightfrom' => 15, 'weightto' => 25],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 7, 'name' => 'более 25кг.', 'weightfrom' => 25, 'weightto' => 999],'INSERT');

        if (!$res){
            return false;
        }

        return true;
    }

    public function insertStartTimeType(){

        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableTimeTypeWithPrefix, ['id' => 1, 'name' => 'c 10:00 до 14:00'],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableTimeTypeWithPrefix, ['id' => 2, 'name' => 'c 14:00 до 18:00'],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableTimeTypeWithPrefix, ['id' => 3, 'name' => 'с 18:00 до 22:00'],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableTimeTypeWithPrefix, ['id' => 4, 'name' => 'c 23:00 до 03:00'],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableTimeTypeWithPrefix, ['id' => 5, 'name' => 'c 3:00 до 6:00'],'INSERT');

        if (!$res){
            return false;
        }

        return true;
    }

    public function dropTables() {

        $sql = "DROP TABLE IF EXISTS `".$this->tableCacheWithPrefix."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $sql = "DROP TABLE IF EXISTS `".$this->tableWeightPriceWithPrefix."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $sql = "DROP TABLE IF EXISTS `".$this->tableConditionPriceWithPrefix."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $sql = "DROP TABLE IF EXISTS `".$this->tableWeightTypeWithPrefix."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $sql = "DROP TABLE IF EXISTS `".$this->tableTimeTypeWithPrefix."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $sql = "DROP TABLE IF EXISTS `".$this->tableOrderWithPrefix."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        return true;
    }


    public function getPriceByCartId($cart_id){
        $sql = "SELECT * FROM {$this->tableOrderWithPrefix} WHERE `id_cart` = '{$cart_id}'";
        $res = Db::getInstance()->getRow($sql);
        return $res;
    }

    public function getPrice($city, $carry, $weight, $price, $kad, $time){

        //определение типа веса

        $weighttype = $this->getWeightTypeId($weight);
        //По весу
        $sumWeight = $this->getWeightPrice($city, $carry, $weighttype);

        //Надбавка
        $sumCondition = $this->getConditionPrice($city, $carry, $price, $kad, $time);

        return $sumWeight+$sumCondition;
    }

    public function getWeightTypeId($weight){
        $sql = "SELECT * FROM {$this->tableWeightTypeWithPrefix} WHERE ";
        $sql .= "(`weightfrom` <= '{$weight}' AND `weightto` > '{$weight}')";
        $res = Db::getInstance()->getRow($sql);
        return (int)$res['id'];
    }

    public function getWeightPrice($city, $carry, $weighttype){
        $sql = "SELECT * FROM {$this->tableWeightPriceWithPrefix} WHERE ";
        $sql .= "(`city` = '{$city}' AND `delivery` = 'axiomus' AND `carry` = '{$carry}' AND `type` = {$weighttype})";
        $res = Db::getInstance()->getRow($sql);
        return $res['sum'];
    }

    public function getTimeNameById($id){
        $sql = "SELECT * FROM {$this->tableTimeTypeWithPrefix} WHERE `id` = '{$id}'";
        $res = Db::getInstance()->getRow($sql);
        return $res['name'];
    }



    public function getWeightPriceArray() {
        $arr = [];
        foreach ($this->cities as $city) {
            foreach ($this->deliveries as $delivery) {
                foreach ($this->priceTypes as $type) {
                    $row = Db::getInstance()->getRow("SELECT * FROM `{$this->tableWeightPriceWithPrefix}` WHERE `type` = {$type} AND (`city` = '{$city}' AND `delivery` = '{$delivery}')");
                    $arr[$row['city'] . '_' . str_replace('-','_',$row['delivery']) . '_price_' . $type] = $row['sum'];
                }
            }
        }

        return $arr;
    }

    public function getOneRowWeightPrice($city, $delivery, $type)
    {
        $row = Db::getInstance()->getRow("SELECT * FROM `{$this->tableWeightPriceWithPrefix}` WHERE `type` = {$type} AND (`city` = '{$city}' AND `delivery` = '{$delivery}')");
        if (isset($row)) { //ToDo точно ли isset
            return $row['sum'];
        }else{
            return false;
        }
    }

    public function setWeightPrice($city, $delivery, $type, $sum){
        $res = Db::getInstance()->update(AxiomusPost::$definition['tableWeightPrice'], ['sum' => $sum],"`type` = {$type} AND (`city` = '{$city}' AND `delivery` = '{$delivery}')");
        if (!$res){
            return false;
        }else{
            return true;
        }
    }

    public function getWeightNameById($id)
    {
        $sql = "SELECT * FROM {$this->tableWeightTypeWithPrefix} WHERE `id` = '{$id}'";
        $res = Db::getInstance()->getRow($sql);
        return $res['name'];
    }

    public function getConditionPrice($city, $carry, $price, $kad, $time){
        $sql = "SELECT * FROM `{$this->tableConditionPriceWithPrefix}` WHERE ";
        $sql .= "(`city` = '{$city}' AND `carry` = '{$carry}')";
        $sql .= " AND (`sumfrom` < '{$price}' AND `sumto` > '{$price}')";
        $sql .= " AND (`kadtype` = {$kad})";
        $sql .= " AND (`timetype` = {$time})";
        $res = Db::getInstance()->getRow($sql);
        return $res['sum'];
    }

    public function getAllConditionPrice(){
        $data = Db::getInstance()->ExecuteS("SELECT * FROM `{$this->tableConditionPriceWithPrefix}`");
        return $data;
    }

    public function getAllTimeType(){
        $data = Db::getInstance()->ExecuteS("SELECT * FROM `{$this->tableTimeTypeWithPrefix}`");
        return $data;
    }

    public function getAllWeightType(){
        $data = Db::getInstance()->ExecuteS("SELECT * FROM `{$this->tableWeightTypeWithPrefix}`");
        return $data;
    }

    public function getAllWeightPrice(){
        $data = Db::getInstance()->ExecuteS("SELECT * FROM `{$this->tableWeightPriceWithPrefix}`");
        return $data;
    }

    public function insertConditionPrice($city, $delivery, $sumfrom, $sumto, $timefrom, $timeto, $kadfrom, $kadto, $sum){
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['city' => $city, 'delivery' => $delivery, 'sumfrom' => $sumfrom, 'sumto' => $sumto, 'timefrom' => $timefrom, 'timeto' => $timeto, 'kadfrom' => $kadfrom, 'kadto' => $kadto, 'sum' => $sum],'INSERT');
        if ($res) {
            return true;
        }else{
            return false;
        }
    }

    public function insertOrder($id_cart, $delivery, $carry, $kad, $time){
        $cart = new Cart($id_cart);
        $totalWeight = $cart->getTotalWeight();
        $weighttype = $this->getWeightTypeId($totalWeight);

        $addr = new Address($cart->id_address_delivery);
        if (!Validate::isLoadedObject($addr))
            return false;
        $city = $addr->city;

        $price = 0;
        $products = $cart->getProducts();
        foreach ($products as $product) {
            $price += (float)$product['total_wt']; //ToDo а точно ли не total?
        }

        $price_weight = $this->getWeightPrice($city, $carry, $weighttype);
        $price_condition = $this->getConditionPrice($city, $carry, $price, $kad, $time);

        if ($this->issetOrder($cart->id)){
            $res = Db::getInstance()->update(AxiomusPost::$definition['tableOrder'], ['delivery' => $delivery, 'carry' => (boolean)$carry, 'price_weight' => (int)$price_weight, 'price_condition' => (int)$price_condition],"`id_cart` = {$id_cart}");
            if (!$res){
                return false;
            }else{
                return true;
            }
        }else {
            $res = Db::getInstance()->autoExecuteWithNullValues($this->tableOrderWithPrefix, ['id_cart' => $id_cart, 'delivery' => $delivery, 'carry' => $carry, 'price_weight' => $price_weight, 'price_condition' => $price_condition], 'INSERT');
            if ($res) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function issetOrder($id_cart){
        $sql = "Select * FROM {$this->tableOrderWithPrefix} WHERE `id_cart` = '{$id_cart}'";
        $res = Db::getInstance()->getRow($sql);
        if ($res) {
            return true;
        }else{
            return false;
        }
    }

    public function deleteConditionPrice($id){
        $res = Db::getInstance()->delete($this->tableConditionPriceWithPrefix,"id = {$id}");
        if ($res) {
            return true;
        }else{
            return false;
        }
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

}