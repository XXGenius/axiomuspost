<?php

require_once(_PS_MODULE_DIR_ . 'axiomuspostcarrier/models/AxiomusXml.php');

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
    public $tableKadTypeWithPrefix;
    public $tableCacheCarryAxiomusWithPrefix;
    public $tableCacheCarryDPDWithPrefix;
    public $tableCacheCarryBoxBerryWithPrefix;

    public $tableCarryPriceWithPrefix;

    protected $dbconn;
    public $priceTypes = [1, 2, 3, 4, 5, 6, 7];
    protected $cities = ['Москва', 'Санкт-Петербург'];
    protected $deliveries = ['axiomus', 'topdelivery', 'dpd', 'boxberry', 'axiomus-carry', 'topdelivery-carry', 'dpd-carry', 'boxberry-carry', 'russianpost-carry'];

    public $AxiomusXML;
    public static $definition = array(
        'table' => 'axiomus',
        'tableOrder' => 'axiomus_order',
        'tableConditionPrice' => 'axiomus_condition_price',
        'tableWeightPrice' => 'axiomus_weight_price',
        'tableCache' => 'axiomus_cache',
        'tableWeightType' => 'axiomus_weight_type',
        'tableTimeType' => 'axiomus_time_type',
        'tableKadType' => 'axiomus_kad_type',
        'tableCacheCarryAxiomus' => 'axiomus_cache_carry_axiomus',
        'tableCacheCarryDPD' => 'axiomus_cache_carry_dpd',
        'tableCacheCarryBoxBerry' => 'axiomus_cache_carry_boxberry',
        'tableCarryPrice' => 'axiomus_carry_price',

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

        $this->AxiomusXML = new AxiomusXml();

        $this->tableCacheWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableCache'];
        $this->tableOrderWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableOrder'];
        $this->tableWeightPriceWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableWeightPrice']; //ToDo может както попроще, зачем столько параметров
        $this->tableConditionPriceWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableConditionPrice'];
        $this->tableWeightTypeWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableWeightType'];
        $this->tableTimeTypeWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableTimeType'];
        $this->tableKadTypeWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableKadType'];
        $this->tableCacheCarryAxiomusWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableCacheCarryAxiomus'];
        $this->tableCacheCarryDPDWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableCacheCarryDPD'];
        $this->tableCacheCarryBoxBerryWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableCacheCarryBoxBerry'];
        $this->tableCarryPriceWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableCarryPrice'];
    }

    public function createTables() {

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableConditionPriceWithPrefix}` (" .
            '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
            '`city` VARCHAR(255) NOT NULL,' .
            '`delivery` VARCHAR(255) NOT NULL,' .
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
            '`city` VARCHAR(255) NOT NULL,' .
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
            '`city` VARCHAR(255) NOT NULL,' .
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
            '`city` VARCHAR(255) NOT NULL,' .
            '`name` VARCHAR(50) NOT NULL,' .
            '`timefrom` TIME NOT NULL,' .
            '`timeto` TIME NOT NULL,' .
            'PRIMARY KEY (`id`)' .
            ') DEFAULT CHARSET=utf8;';

        if (!Db::getInstance()->execute($sql, false)) {
            return false;
        }


        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableKadTypeWithPrefix}` (" .
            '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
            '`city` VARCHAR(255) NOT NULL,' .
            '`name` VARCHAR(50) NOT NULL,' .
            '`rangefrom` INT(11) NOT NULL,' .
            '`rangeto` INT(11) NOT NULL,' .
            'PRIMARY KEY (`id`)' .
            ') DEFAULT CHARSET=utf8;';

        if (!Db::getInstance()->execute($sql, false)) {
            return false;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableOrderWithPrefix}` (" .
            '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
            '`id_cart` INT(11) NOT NULL,' .
            '`delivery` VARCHAR(50) NOT NULL,' .
            '`date` DATETIME NOT NULL,' .
            '`carry` BOOLEAN NOT NULL,' .
            '`kadtype` INT(11) NOT NULL,'.
            '`timetype` INT(11) NOT NULL,'.
            '`price_weight` INT(11),' .
            '`price_condition` INT(11),' .
            '`id_axiomus` INT(11),' .
            '`okay` VARCHAR(255),' .
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


        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableCacheCarryAxiomusWithPrefix}` (" .
            '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
            '`datetime` DATETIME DEFAULT NOW() NOT NULL,' .
            '`code` INT(11) NOT NULL,' .
            '`name` VARCHAR(255) NOT NULL,' .
            '`address` VARCHAR(255) NOT NULL,' .
            '`city_code` INT(11) NOT NULL,' .
            '`city_name` VARCHAR(255) NOT NULL,' .
            '`work_schedule` VARCHAR(255) NULL,'.
            '`GPS` VARCHAR(255) NOT NULL,'.
            '`fitting` BOOLEAN,'.
            '`part_return` BOOLEAN,' .
            '`check_before_paying` BOOLEAN,' .
            '`path` VARCHAR(255) NULL,'.

            '`country` VARCHAR(255),' .
            '`region` VARCHAR(255),' .
            '`locality` VARCHAR(255),' .
            '`district` VARCHAR(255),' .
            '`street` VARCHAR(255),' .
            '`house` VARCHAR(255),' .
            '`building` VARCHAR(255),' .
            '`apartament` VARCHAR(255),' .
            '`zip_code` VARCHAR(255),' .

            '`cash` BOOLEAN,'.
            '`cheque` BOOLEAN,' .
            '`card` BOOLEAN,' .
            'PRIMARY KEY (`id`)' .
            ") DEFAULT CHARSET=utf8;".
            "CREATE UNIQUE INDEX {$this->tableOrderWithPrefix}_id_cart_uindex ON ps_axiomus_order (id_cart);";

        if (!Db::getInstance()->execute($sql, false)) {
            return false;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableCacheCarryDPDWithPrefix}` (" .
            '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
            '`datetime` DATETIME DEFAULT NOW() NOT NULL,' .
            '`code` VARCHAR(11) NOT NULL,' .
            '`type` VARCHAR(11) NOT NULL,' .
            '`name` VARCHAR(255) NOT NULL,' .
            '`address` VARCHAR(255) NOT NULL,' .
            '`region` VARCHAR(255) NOT NULL,' .
            '`city` VARCHAR(255) NOT NULL,' .
            '`GPS` VARCHAR(255) NOT NULL,'.
            '`work_schedule` VARCHAR(255) NULL,'.
            '`cash` BOOLEAN,'.
            '`card` BOOLEAN,' .
            '`fitting` BOOLEAN,'.
            '`part_return` BOOLEAN,' .
            '`esCodes` VARCHAR(255) NULL,'.
            '`payment_by_bank_card` BOOLEAN,' .
            'PRIMARY KEY (`id`)' .
            ") DEFAULT CHARSET=utf8;".
            "CREATE UNIQUE INDEX {$this->tableOrderWithPrefix}_id_cart_uindex ON ps_axiomus_order (id_cart);";

        if (!Db::getInstance()->execute($sql, false)) {
            return false;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableCacheCarryBoxBerryWithPrefix}` (" .
            '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
            '`datetime` DATETIME DEFAULT NOW() NOT NULL,' .
            '`code` INT(11) NOT NULL,' .
            '`name` VARCHAR(255) NOT NULL,' .
            '`address` VARCHAR(255) NOT NULL,' .
            '`city_code` INT(11) NOT NULL,' .
            '`city_name` VARCHAR(255) NOT NULL,' .

            '`GPS` VARCHAR(255) NOT NULL,'.
            '`region` VARCHAR(255),' . //Area
            '`work_schedule` VARCHAR(255) NULL,'.

            '`only_prepaid_orders` BOOLEAN,'.
            '`phone` VARCHAR(255),' .
            '`tariff_zone` INT(11),' .
            '`delivery_period` INT(11) NULL,'.
            '`card` BOOLEAN,' .//Acquiring
            '`path` VARCHAR(255) NULL,'.
            '`cash` BOOLEAN,'.

            'PRIMARY KEY (`id`)' .
            ") DEFAULT CHARSET=utf8;".
            "CREATE UNIQUE INDEX {$this->tableOrderWithPrefix}_id_cart_uindex ON ps_axiomus_order (id_cart);";

        if (!Db::getInstance()->execute($sql, false)) {
            return false;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableCarryPriceWithPrefix}` (" .
            '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
            '`city` VARCHAR(255) NOT NULL,' .
            '`delivery` VARCHAR(50) NOT NULL,' .
            '`sum` INT(11),' .
            'PRIMARY KEY (`id`)' .
            ') DEFAULT CHARSET=utf8;';

        if (!Db::getInstance()->execute($sql, false)) {
            return false;
        }

        return true;
    }

    public function insertStartValueDb(){
        $this->_insertStartKadType();
        $this->_insertStartTimeType();
        $this->_insertStartWeightType();
        $this->_insertStartWeightPrice();
        $this->_insertStartConditionPrice();
        $this->_insertStartCarryPrice();
    }

    private function _insertStartKadType(){

        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableKadTypeWithPrefix, ['id' => 1, 'city' => 'Москва', 'name' => 'В пределах МКАД', 'rangefrom' => 0, 'rangeto' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableKadTypeWithPrefix, ['id' => 2, 'city' => 'Москва', 'name' => 'В пределах 5 км от МКАД', 'rangefrom' => 0, 'rangeto' => 5],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableKadTypeWithPrefix, ['id' => 3, 'city' => 'Москва', 'name' => 'От 5 до 10 км от МКАД', 'rangefrom' => 5, 'rangeto' => 10],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableKadTypeWithPrefix, ['id' => 4, 'city' => 'Москва', 'name' => 'От 10 до 25 км от МКАД', 'rangefrom' => 10, 'rangeto' => 25],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableKadTypeWithPrefix, ['id' => 5, 'city' => 'Москва', 'name' => 'От 25 до 40 км от МКАД', 'rangefrom' => 25, 'rangeto' => 40],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableKadTypeWithPrefix, ['id' => 6, 'city' => 'Санкт-Петербург', 'name' => 'В пределах КАД', 'rangefrom' => 0, 'rangeto' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableKadTypeWithPrefix, ['id' => 7, 'city' => 'Санкт-Петербург', 'name' => 'В пределах 5 км от КАД', 'rangefrom' => 0, 'rangeto' => 5],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableKadTypeWithPrefix, ['id' => 8, 'city' => 'Санкт-Петербург', 'name' => 'От 5 до 10 км от КАД', 'rangefrom' => 5, 'rangeto' => 10],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableKadTypeWithPrefix, ['id' => 9, 'city' => 'Санкт-Петербург', 'name' => 'От 10 до 25 км от КАД', 'rangefrom' => 10, 'rangeto' => 25],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableKadTypeWithPrefix, ['id' => 10, 'city' => 'Санкт-Петербург', 'name' => 'От 25 до 40 км от КАД', 'rangefrom' => 25, 'rangeto' => 40],'INSERT');
        if (!$res){
            return false;
        }

        return true;
    }

    private function _insertStartTimeType(){

        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableTimeTypeWithPrefix, ['id' => 1, 'city' => 'Москва', 'name' => 'c 10:00 до 14:00', 'timefrom' => '10:00', 'timeto' => '14:00'],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableTimeTypeWithPrefix, ['id' => 2, 'city' => 'Москва', 'name' => 'c 14:00 до 18:00', 'timefrom' => '14:00', 'timeto' => '18:00'],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableTimeTypeWithPrefix, ['id' => 3, 'city' => 'Москва', 'name' => 'с 18:00 до 22:00', 'timefrom' => '18:00', 'timeto' => '22:00'],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableTimeTypeWithPrefix, ['id' => 4, 'city' => 'Москва', 'name' => 'c 23:00 до 03:00', 'timefrom' => '23:00', 'timeto' => '03:00'],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableTimeTypeWithPrefix, ['id' => 5, 'city' => 'Москва', 'name' => 'c 3:00 до 6:00', 'timefrom' => '03:00', 'timeto' => '06:00'],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableTimeTypeWithPrefix, ['id' => 6, 'city' => 'Санкт-Петербург', 'name' => 'c 10:00 до 14:00', 'timefrom' => '10:00', 'timeto' => '14:00'],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableTimeTypeWithPrefix, ['id' => 7, 'city' => 'Санкт-Петербург', 'name' => 'c 14:00 до 18:00', 'timefrom' => '14:00', 'timeto' => '18:00'],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableTimeTypeWithPrefix, ['id' => 8, 'city' => 'Санкт-Петербург', 'name' => 'с 18:00 до 22:00', 'timefrom' => '18:00', 'timeto' => '22:00'],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableTimeTypeWithPrefix, ['id' => 9, 'city' => 'Санкт-Петербург', 'name' => 'c 23:00 до 03:00', 'timefrom' => '23:00', 'timeto' => '03:00'],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableTimeTypeWithPrefix, ['id' => 10, 'city' => 'Санкт-Петербург', 'name' => 'c 3:00 до 6:00', 'timefrom' => '03:00', 'timeto' => '06:00'],'INSERT');
        if (!$res){
            return false;
        }

        return true;
    }

    private function _insertStartWeightType(){

        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 1, 'city' => 'Москва', 'name' => 'до 1кг.', 'weightfrom' => 0, 'weightto' => 1],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 2, 'city' => 'Москва', 'name' => 'от 1 до 3кг.', 'weightfrom' => 1, 'weightto' => 3],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 3, 'city' => 'Москва', 'name' => 'от 3 до 5кг.', 'weightfrom' => 3, 'weightto' => 5],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 4, 'city' => 'Москва', 'name' => 'от 5 до 10кг', 'weightfrom' => 5, 'weightto' => 10],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 5, 'city' => 'Москва', 'name' => 'от 10 др 15кг.', 'weightfrom' => 10, 'weightto' => 15],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 6, 'city' => 'Москва', 'name' => 'от 15 до 25кг.', 'weightfrom' => 15, 'weightto' => 25],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 7, 'city' => 'Москва', 'name' => 'более 25кг.', 'weightfrom' => 25, 'weightto' => 999],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 8, 'city' => 'Санкт-Петербург', 'name' => 'до 1кг.', 'weightfrom' => 0, 'weightto' => 1],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 9, 'city' => 'Санкт-Петербург', 'name' => 'от 1 до 3кг.', 'weightfrom' => 1, 'weightto' => 3],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 10, 'city' => 'Санкт-Петербург', 'name' => 'от 3 до 5кг.', 'weightfrom' => 3, 'weightto' => 5],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 11, 'city' => 'Санкт-Петербург', 'name' => 'от 5 до 10кг', 'weightfrom' => 5, 'weightto' => 10],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 12, 'city' => 'Санкт-Петербург', 'name' => 'от 10 др 15кг.', 'weightfrom' => 10, 'weightto' => 15],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 13, 'city' => 'Санкт-Петербург', 'name' => 'от 15 до 25кг.', 'weightfrom' => 15, 'weightto' => 25],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['id' => 14, 'city' => 'Санкт-Петербург', 'name' => 'более 25кг.', 'weightfrom' => 25, 'weightto' => 999],'INSERT');

        if (!$res){
            return false;
        }

        return true;
    }

    private function _insertStartWeightPrice(){

        $id = 0;
        foreach ($this->cities as $city){
            foreach ($this->deliveries as $delivery){
                foreach ($this->getAllWeightType($city) as $type) {
                    $id++;
                    $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightPriceWithPrefix, ['id' => 0, 'city' => $city, 'delivery' => $delivery, 'carry' => false, 'type' => (int)$type['id'],'sum' => 0],'INSERT');
                    if (!$res){
                        return false;
                    }
                }
            }
        }

        return true;
    }

    private function _insertStartConditionPrice(){
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 1, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 1, 'kadtype' => 2, 'sum' => 50],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 1, 'kadtype' => 3,  'sum' => 200],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 1, 'kadtype' => 4,  'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 1, 'kadtype' => 5,  'sum' => 480],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 5100, 'timetype' => 1, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 5100, 'timetype' => 1, 'kadtype' => 2, 'sum' => 50],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 5100, 'timetype' => 1, 'kadtype' => 3, 'sum' => 200],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 5100, 'timetype' => 1, 'kadtype' => 4, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 5100, 'timetype' => 1, 'kadtype' => 5, 'sum' => 480],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 999999, 'timetype' => 1, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 999999, 'timetype' => 1, 'kadtype' => 2, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 999999, 'timetype' => 1, 'kadtype' => 3, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 999999, 'timetype' => 1, 'kadtype' => 4, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 999999, 'timetype' => 1, 'kadtype' => 5, 'sum' => 480],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 5, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 5, 'kadtype' => 2, 'sum' => 50],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 5, 'kadtype' => 3, 'sum' => 200],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 5, 'kadtype' => 4, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 5, 'kadtype' => 5, 'sum' => 480],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 2, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 2, 'kadtype' => 2, 'sum' => 50],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 2, 'kadtype' => 3, 'sum' => 200],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 2, 'kadtype' => 4, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 2, 'kadtype' => 5, 'sum' => 480],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 3, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 3, 'kadtype' => 2, 'sum' => 50],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 3, 'kadtype' => 3, 'sum' => 200],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 3, 'kadtype' => 4, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 3, 'kadtype' => 5, 'sum' => 480],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 4, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 4, 'kadtype' => 2, 'sum' => 50],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 4, 'kadtype' => 3, 'sum' => 200],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 4, 'kadtype' => 4, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'carry' => false, 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 999999, 'timetype' => 4, 'kadtype' => 5, 'sum' => 480],'INSERT');
        return true;
    }

    private function _insertStartCarryPrice(){

        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableCarryPriceWithPrefix, ['id' => 1, 'city' => 'Москва', 'delivery' => 'axiomus', 'sum' => 22],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableCarryPriceWithPrefix, ['id' => 2, 'city' => 'Москва', 'delivery' => 'dpd', 'sum' => 33],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableCarryPriceWithPrefix, ['id' => 3, 'city' => 'Москва', 'delivery' => 'boxberry', 'sum' => 44],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableCarryPriceWithPrefix, ['id' => 4, 'city' => 'Санкт-Петербург', 'delivery' => 'axiomus', 'sum' => 55],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableCarryPriceWithPrefix, ['id' => 5, 'city' => 'Санкт-Петербург', 'delivery' => 'dpd', 'sum' => 66],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableCarryPriceWithPrefix, ['id' => 6, 'city' => 'Санкт-Петербург', 'delivery' => 'boxberry', 'sum' => 77],'INSERT');

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

        $sql = "DROP TABLE IF EXISTS `".$this->tableKadTypeWithPrefix."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $sql = "DROP TABLE IF EXISTS `".$this->tableOrderWithPrefix."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $sql = "DROP TABLE IF EXISTS `".$this->tableCacheCarryAxiomusWithPrefix."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $sql = "DROP TABLE IF EXISTS `".$this->tableCacheCarryDPDWithPrefix."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $sql = "DROP TABLE IF EXISTS `".$this->tableCacheCarryBoxBerryWithPrefix."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $sql = "DROP TABLE IF EXISTS `".$this->tableCarryPriceWithPrefix."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        return true;
    }


    //Price ajax
    public function getPriceByCartId($cart_id){
        $sql = "SELECT * FROM {$this->tableOrderWithPrefix} WHERE `id_cart` = '{$cart_id}'";
        $res = Db::getInstance()->getRow($sql);
        return $res;
    }

    public function getPrice($city, $carry, $weight, $price, $carrytype, $kad, $time){

        //определение типа веса
        if ($carry){
            $delivery = $this->getActiveCarry()[$carrytype-1];
            return $this->getCarryPriceByName($city, $delivery);
        }else {
            $weighttype = $this->getWeightTypeId($weight);
            //По весу
            $sumWeight = $this->getWeightPrice($city, $carry, $weighttype);

            //Надбавка
            $sumCondition = $this->getConditionPrice($city, $carry, $price, $kad, $time);

            return $sumWeight + $sumCondition;
        }
    }


    //WeightType
    public function getAllWeightType($city){
        $data = Db::getInstance()->ExecuteS("SELECT * FROM `{$this->tableWeightTypeWithPrefix}` WHERE `city` = '{$city}';");
        return $data;
    }

    public function getWeightTypeId($weight){
        $sql = "SELECT * FROM {$this->tableWeightTypeWithPrefix} WHERE ";
        $sql .= "(`weightfrom` <= '{$weight}' AND `weightto` > '{$weight}')";
        $res = Db::getInstance()->getRow($sql);
        return (int)$res['id'];
    }

    public function getWeightNameById($id)
    {
        $sql = "SELECT * FROM {$this->tableWeightTypeWithPrefix} WHERE `id` = '{$id}'";
        $res = Db::getInstance()->getRow($sql);
        return $res['name'];
    }

    public function insertWeightType( $name, $weightfrom, $weightto){
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['name' => $name, 'weightfrom' => $weightfrom, 'weightto' => $weightto],'INSERT');
        if (!$res){
            return false;
        }else{
            return true;
        }
    }

    public function updateWeightType($id, $name, $weightfrom, $weightto){
        $res = Db::getInstance()->update(AxiomusPost::$definition['tableWeightType'], [ 'name' => $name, 'weightfrom' => $weightfrom, 'weightto' => $weightto]," `id` = {$id}");
        if (!$res){
            return false;
        }else{
            return true;
        }
    }

    public function deleteWeightType($id){
        $res = Db::getInstance()->delete($this->tableWeightTypeWithPrefix,"id = {$id}");
        if ($res) {
            return true;
        }else{
            return false;
        }
    }

    //TimeType
    public function getTimeTypeById($id){
        $sql = "SELECT * FROM {$this->tableTimeTypeWithPrefix} WHERE `id` = '{$id}'";
        $res = Db::getInstance()->getRow($sql);
        return $res;
    }

    public function getAllTimeType($city){
        $data = Db::getInstance()->ExecuteS("SELECT * FROM `{$this->tableTimeTypeWithPrefix}` WHERE `city` = '{$city}';");
        return $data;
    }

    public function insertTimeType($name, $timefrom, $timeto){
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableTimeTypeWithPrefix, ['name' => $name, 'timefrom' => $timefrom, 'timeto' => $timeto],'INSERT');
        if (!$res){
            return false;
        }else{
            return true;
        }
    }

    public function updateTimeType($id, $name, $timefrom, $timeto){
        $res = Db::getInstance()->update(AxiomusPost::$definition['tableTimeType'], ['name' => $name, 'timefrom' => $timefrom, 'timeto' => $timeto]," `id` = {$id}");
        if (!$res){
            return false;
        }else{
            return true;
        }
    }

    public function deleteTimeType($id){
        $res = Db::getInstance()->delete($this->tableTimeTypeWithPrefix,"id = {$id}");
        if ($res) {
            return true;
        }else{
            return false;
        }
    }

    //KadType

    public function getKadTypeById($id){
        $sql = "SELECT * FROM {$this->tableKadTypeWithPrefix} WHERE `id` = '{$id}'";
        $res = Db::getInstance()->getRow($sql);
        return $res;
    }

    public function getAllKadType($city){
        $data = Db::getInstance()->ExecuteS("SELECT * FROM `{$this->tableKadTypeWithPrefix}` WHERE `city` = '{$city}';");
        return $data;
    }

    public function insertKadType($city, $name, $rangefrom, $rangeto){
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableKadTypeWithPrefix, ['city' => $city, 'name' => $name, 'rangefrom' => $rangefrom, 'rangeto' => $rangeto ],'INSERT');
        if (!$res){
            return false;
        }else{
            return true;
        }
    }

    public function updateKadType($id, $city, $name, $rangefrom, $rangeto){
        $res = Db::getInstance()->update(AxiomusPost::$definition['tableKadType'], ['city' => $city, 'name' => $name, 'rangefrom' => $rangefrom, 'rangeto' => $rangeto ]," `id` = {$id}");
        if (!$res){
            return false;
        }else{
            return true;
        }
    }

    public function deleteKadType($id){
        $res = Db::getInstance()->delete($this->tableKadTypeWithPrefix,"id = {$id}");
        if ($res) {
            return true;
        }else{
            return false;
        }
    }

    //WeightPrice
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
    } //ToDo нужен ли этот метод?

    public function getOneRowWeightPrice($city, $delivery, $type)
    {
        $row = Db::getInstance()->getRow("SELECT * FROM `{$this->tableWeightPriceWithPrefix}` WHERE `type` = {$type} AND (`city` = '{$city}' AND `delivery` = '{$delivery}')");
        if (isset($row)) { //ToDo точно ли isset
            return $row['sum'];
        }else{
            return false;
        }
    }

    public function getAllWeightPrice($city){
        $data = Db::getInstance()->ExecuteS("SELECT * FROM `{$this->tableWeightPriceWithPrefix}`  WHERE `city` = '{$city}';");
        return $data;
    }

    public function getWeightPrice($city, $carry, $weighttype){
        $sql = "SELECT * FROM {$this->tableWeightPriceWithPrefix} WHERE ";
        $sql .= "(`city` = '{$city}' AND `delivery` = 'axiomus' AND `carry` = '{$carry}' AND `type` = {$weighttype})";
        $res = Db::getInstance()->getRow($sql);
        return $res['sum'];
    }

    public function insertWeightPrice($city, $delivery, $carry, $type, $sum){
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['city' => $city, 'delivery' => $delivery, 'carry' => $carry, 'type' => $type, 'sum' => $sum],'INSERT');
        if (!$res){
            return false;
        }else{
            return true;
        }
    }

    public function updateWeightPrice($id, $city, $delivery, $carry, $type, $sum){
        $res = Db::getInstance()->update(AxiomusPost::$definition['tableWeightPrice'], ['city' => $city, 'delivery' => $delivery, 'carry' => $carry, 'type' => $type, 'sum' => $sum]," `id` = {$id})");
        if (!$res){
            return false;
        }else{
            return true;
        }
    }

    public function deleteWeightPrice($id){
        $res = Db::getInstance()->delete($this->tableWeightPriceWithPrefix,"id = {$id}");
        if ($res) {
            return true;
        }else{
            return false;
        }
    }

    //ConditionPrice
    public function getConditionPrice($city, $carry, $price, $kad, $time){
        $sql = "SELECT * FROM `{$this->tableConditionPriceWithPrefix}` WHERE ";
        $sql .= "(`city` = '{$city}' AND `carry` = '{$carry}')";
        $sql .= " AND (`sumfrom` < '{$price}' AND `sumto` > '{$price}')";
        $sql .= " AND (`kadtype` = {$kad})";
        $sql .= " AND (`timetype` = {$time})";
        $res = Db::getInstance()->getRow($sql);
        return $res['sum'];
    }

    public function getAllConditionPrice($city){
        $data = Db::getInstance()->ExecuteS("SELECT * FROM `{$this->tableConditionPriceWithPrefix}` WHERE `city` = '{$city}';");
        return $data;
    }

    public function insertConditionPrice($city, $delivery, $carry, $sumfrom, $sumto, $timetype, $kadtype, $sum){
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['city' => $city, 'delivery' => $delivery, 'carry' => $carry, 'sumfrom' => $sumfrom, 'sumto' => $sumto, 'timetype' => $timetype, 'kadtype' => $kadtype, 'sum' => $sum],'INSERT');
        if ($res) {
            return true;
        }else{
            return false;
        }
    }

    public function updateConditionPrice($id, $city, $delivery, $carry, $sumfrom, $sumto, $timetype, $kadtype, $sum){
        $res = Db::getInstance()->update(AxiomusPost::$definition['tableConditionPrice'], ['city' => $city, 'delivery' => $delivery, 'carry' => $carry, 'sumfrom' => $sumfrom, 'sumto' => $sumto, 'timetype' => $timetype, 'kadtype' => $kadtype, 'sum' => $sum]," `id` = {$id}");
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

    //Order
    public function setOrder($id_cart, $delivery, $date, $carry, $kad, $time){
        $date = new DateTime($date);

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
            $res = Db::getInstance()->update(AxiomusPost::$definition['tableOrder'], ['delivery' => $delivery,'date' => $date->format('Y-m-d'), 'carry' => (boolean)$carry, 'kadtype' => $kad, 'timetype' => $time, 'price_weight' => (int)$price_weight, 'price_condition' => (int)$price_condition],"`id_cart` = {$id_cart}");
            if (!$res){
                return false;
            }else{
                return true;
            }
        }else {
            $res = Db::getInstance()->autoExecuteWithNullValues($this->tableOrderWithPrefix, ['id_cart' => $id_cart, 'delivery' => $delivery,'date' => $date->format('Y-m-d'), 'carry' => $carry, 'kadtype' => $kad, 'timetype' => $time, 'price_weight' => $price_weight, 'price_condition' => $price_condition], 'INSERT');
            if ($res) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function setOrderResponse($id_cart, $oid, $okey){
        if ($this->issetOrder($id_cart)){
            $res = Db::getInstance()->update(AxiomusPost::$definition['tableOrder'], ['id_axiomus' => $oid, 'okay' => $okey],"`id_cart` = {$id_cart}");
            if (!$res){
                return false;
            }else{
                return true;
            }
        }else {
            return false;
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

    public function getOrderByIdCart($id_cart){
        $row = Db::getInstance()->getRow("SELECT * FROM `{$this->tableOrderWithPrefix}` WHERE `id_cart` = {$id_cart}");
        if (isset($row)) { //ToDo точно ли isset
            return $row;
        }else{
            return false;
        }
    }
    //Carry
    public function getActiveCarry(){

        return [0 => 'axiomus',1 => 'dpd', 2=>'boxberry'];
    }

    public function getCarryAddressesArray($carry_id, $city){
        if ($carry_id == 0+1) { //axiomus
            $activeTable = $this->tableCacheCarryAxiomusWithPrefix;
            $data = Db::getInstance()->ExecuteS("SELECT * FROM `{$activeTable}` WHERE `city_name` = '{$city}'");
            return $data;
        }elseif ($carry_id == 1+1){ //dpd
            $activeTable = $this->tableCacheCarryDPDWithPrefix;
            $data = Db::getInstance()->ExecuteS("SELECT * FROM `{$activeTable}` WHERE `city` = '{$city}'");
            return $data;
        }elseif ($carry_id == 2+1){ //boxberry
            $activeTable = $this->tableCacheCarryBoxBerryWithPrefix;
            $data = Db::getInstance()->ExecuteS("SELECT * FROM `{$activeTable}` WHERE `city_name` = '{$city}'");
            return $data;
        }else{
            return false;
        }

    }

    //cache carry

    public function refreshCarryAddressCacheAxiomus(){
        $error = [];
        $results = $this->AxiomusXML->getCarryAddresses('get_carry')->carry_list;
        if(!empty($results)){
            $data = Db::getInstance()->execute("TRUNCATE TABLE `{$this->tableCacheCarryAxiomusWithPrefix}`");
            if ($data) {
                foreach ($results->office as $key => $row) {
                    $res = Db::getInstance()->autoExecuteWithNullValues($this->tableCacheCarryAxiomusWithPrefix, [
                        'code' => (int)$row['office_code'],
                        'name' => (string)$row['office_name'],
                        'address' => (string)$row['office_address'],
                        'city_code' => (int)$row['city_code'],
                        'city_name' => (string)$row['city_name'],
                        'GPS' => (string)$row['GPS'],
                        'work_schedule' => (string)$row['WorkSchedule'],
                        'fitting' => (boolean)$row['fitting'],
                        'part_return' => (boolean)$row['part_return'],
                        'check_before_paying' => (boolean)$row['Check_before_paying'],
                        'path' => (string)$row->path,
                        'country' => (!empty($row->address->country)) ? (string)$row->address->country : null,
                        'region' => (!empty($row->address->region)) ? (string)$row->address->region : null,
                        'locality' => (!empty($row->address->locality)) ? (string)$row->address->locality : null,
                        'district' => (!empty($row->address->district)) ? (string)$row->address->district : null,
                        'street' => (!empty($row->address->street)) ? (string)$row->address->street : null,
                        'house' => (!empty($row->address->house)) ? (string)$row->address->house : null,
                        'building' => (!empty($row->address->building)) ? (string)$row->address->building : null,
                        'apartament' => (!empty($row->address->apartament)) ? (string)$row->address->apartament : null,
                        'zip_code' => (!empty($row->address->zip_code)) ? (string)$row->address->zip_code : null,
                        'cash' => ($row->services['cash'] == 'yes') ? true : false,
                        'cheque' => ($row->services['cheque'] == 'yes') ? true : false,
                        'card' => ($row->services['card'] == 'yes') ? true : false,
                    ], 'INSERT');
                    if ($res) {
                        continue;
                    } else {
                        $error[] = $key;
                    }
                }
            }
            return $error;
        }
    }

    public function refreshCarryAddressCacheDPD(){
        $error = [];
        $results = $this->AxiomusXML->getCarryAddresses('get_dpd_pickup')->pickup_list;
        if(!empty($results)){
            $data = Db::getInstance()->execute("TRUNCATE TABLE `{$this->tableCacheCarryDPDWithPrefix}`");
            if ($data) {
                foreach ($results->office as $key => $row) {
                    $res = Db::getInstance()->autoExecuteWithNullValues($this->tableCacheCarryDPDWithPrefix, [
                        'code' => (string)$row['code'],
                        'type' => (string)$row['type'],
                        'name' => (string)$row['name'],
                        'address' => (string)$row['address'],
                        'region' => (string)$row['region'],
                        'city' => (string)$row['city'],
                        'GPS' => (string)$row['GPS'],
                        'work_schedule' => (string)$row['WorkSchedule'],
                        'cash' => (boolean)$row['cash'],
                        'card' => (boolean)$row['card'],
                        'fitting' => (boolean)$row['fitting'],
                        'part_return' => (boolean)$row['Part_return'],
                        'esCodes' => (string)$row['esCodes'],
                        'payment_by_bank_card' => ($row->services['PaymentByBankCard'] == 'yes') ? true : false,

                    ], 'INSERT');
                    if ($res) {
                        continue;
                    } else {
                        $error[] = $key;
                    }
                }
            }
            return $error;
        }
    }

    public function refreshCarryAddressCacheBoxBerry(){
        $error = [];
        $results = $this->AxiomusXML->getCarryAddresses('get_boxberry_pickup')->pickup_list;
        if(!empty($results)){
            $data = Db::getInstance()->execute("TRUNCATE TABLE `{$this->tableCacheCarryBoxBerryWithPrefix}`");
            if ($data) {
                foreach ($results->office as $key => $row) {
                    $res = Db::getInstance()->autoExecuteWithNullValues($this->tableCacheCarryBoxBerryWithPrefix, [
                        'code' => (int)$row['office_code'],
                        'name' => (string)$row['office_name'],
                        'address' => (string)$row['office_address'],
                        'city_code' => (int)$row['city_code'],
                        'city_name' => (string)$row['city_name'],
                        'GPS' => (string)$row['GPS'],
                        'region' => (string)$row['Area'],
                        'work_schedule' => (string)$row['WorkSchedule'],
                        'only_prepaid_orders' => (boolean)$row['OnlyPrepaidOrders'],
                        'phone' => (boolean)$row['Phone'],

                        'tariff_zone' => (int)$row['TariffZone'],
                        'delivery_period' => (int)$row['DeliveryPeriod'],
                        'card' => (boolean)$row->services['Acquiring'],
                        'path' => str_replace('\'','',(string)$row['TripDescription']),
                        'cash' => (boolean)$row->services['cash'],

                    ], 'INSERT');
                    if ($res) {
                        continue;
                    } else {
                        $error[] = $key;
                    }
                }
            }
            return $error;
        }
    }

    public function getLastUpdateCacheCarry($type){
        if ($type=='axiomus'){
            $table = $this->tableCacheCarryAxiomusWithPrefix;
        }elseif ($type == 'dpd'){
            $table = $this->tableCacheCarryDPDWithPrefix;
        }elseif ($type == 'boxberry'){
            $table = $this->tableCacheCarryBoxBerryWithPrefix;
        }else{
            return false;
        }
        $row = Db::getInstance()->getRow("SELECT * FROM `{$table}`");
        if (!empty($row)) {
            return $row['datetime'];
        }else{
            return 'Кэш не создан';
        }
    }

    //carry

    public function getCarryPriceByName($city, $name){

        $row = Db::getInstance()->getRow("SELECT * FROM `{$this->tableCarryPriceWithPrefix}` WHERE `delivery` = '{$name}' AND `city` = '{$city}';");
        if (!empty($row)) {
            return (int)$row['sum'];
        }else{
            return false;
        }
    }

    public function setCarryPrice($city, $name, $sum){
        $res = Db::getInstance()->update(AxiomusPost::$definition['tableCarryPrice'], ['sum' => $sum]," `city` = '{$city}' AND `delivery` = '{$name}';");
        if (!$res){
            return false;
        }else{
            return true;
        }
    }

    //Cache
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