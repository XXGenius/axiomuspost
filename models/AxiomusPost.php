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
    /**
     * GENIUS EDIT
     * @var
     */
    public $tableCityPecom = 'ps_axiomus_city_pecom';
    public $tableUpdatePecom = 'ps_axiomus_pecom_carry';
    public $tableUpdatePecomDelivery = 'ps_axiomus_pecom_delivery';
    public static $tableCarryPriceWithPrefix;
    public $tableCacheCarryPecomWithPrefix;
    /**
     * @var
     *
     */

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
        'tableCacheCarrypecom' => 'axiomus_cache_carry_pecom',
        'tableCarryPrice' => 'axiomus_carry_price',

        'primary' => 'id',//Это нужно для работы формы добавления
        'multilang' => false,
    );

    public function __construct($id = null, $id_lang = null, $id_shop = NULL) {

        parent::__construct($id, $id_lang, $id_shop);


        $this->tableCacheWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableCache'];
        $this->tableOrderWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableOrder'];
        $this->tableWeightPriceWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableWeightPrice'];
        $this->tableConditionPriceWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableConditionPrice'];
        $this->tableWeightTypeWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableWeightType'];
        $this->tableTimeTypeWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableTimeType'];
        $this->tableKadTypeWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableKadType'];
        $this->tableCacheCarryAxiomusWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableCacheCarryAxiomus'];
        $this->tableCacheCarryDPDWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableCacheCarryDPD'];
        $this->tableCacheCarryBoxBerryWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableCacheCarryBoxBerry'];
        $this->tableCacheCarryPecomWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableCacheCarrypecom'];
        self::$tableCarryPriceWithPrefix = _DB_PREFIX_. AxiomusPost::$definition['tableCarryPrice'];
    }

    public function createTabless() {

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableConditionPriceWithPrefix}` (" .
            '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
            '`city` VARCHAR(255) NOT NULL,' .
            '`delivery` VARCHAR(255) NOT NULL,' .
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
            '`delivery_id` INT(11) NULL,' .
            '`date` DATETIME NULL,' .
            '`carry` BOOLEAN NOT NULL,' .
            '`kadtype` INT(11) NULL,'.
            '`timetype` INT(11) NULL,'.
            '`price_weight` INT(11),' .
            '`price_condition` INT(11),' .
            '`carry_code` VARCHAR(255),' .
            '`position_count` INT(11),' .
            '`oid` INT(11),' .
            '`okey` VARCHAR(255),' .
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
            '`code` VARCHAR(255) NOT NULL,' .
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
            '`code` VARCHAR(255) NOT NULL,' .
            '`type` VARCHAR(11) NOT NULL,' .
            '`name` VARCHAR(255) NOT NULL,' .
            '`address` VARCHAR(255) NOT NULL,' .
            '`region` VARCHAR(255) NOT NULL,' .
            '`city_name` VARCHAR(255) NOT NULL,' .
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
            '`code` VARCHAR(255) NOT NULL,' .
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

        $table = self::$tableCarryPriceWithPrefix;
        $sql = "CREATE TABLE IF NOT EXISTS `{$table}` (" .
            '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
            '`city` VARCHAR(255) NOT NULL,' .
            '`delivery` VARCHAR(50) NOT NULL,' .
            '`sum` INT(11),' .
            'PRIMARY KEY (`id`)' .
            ') DEFAULT CHARSET=utf8;';

        if (!Db::getInstance()->execute($sql, false)) {
            return false;
        }


        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableCacheCarryPecomWithPrefix}` (" .
            '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
            '`datetime` DATETIME DEFAULT NOW() NOT NULL,' .
            '`warehouseId` VARCHAR(255) NOT NULL,' .
            '`code` VARCHAR(255) NOT NULL,' .
            '`divisionId` VARCHAR(255) NOT NULL,' .
            '`name` VARCHAR(255),' .
            '`divisionName` VARCHAR(255),' .
            '`city_name` VARCHAR(255),'.
            '`address` VARCHAR(255),' .
            '`addressDivision` VARCHAR(255),' .
            '`isAcceptanceOnly` BOOLEAN,' .
            '`isFreightSurcharge` BOOLEAN,' .
            '`coordinates` VARCHAR(255),' .
            '`email` VARCHAR(255),' .
            '`phone` VARCHAR(255),' .
            '`isRestrictions` BOOLEAN,' .
            '`maxWeight` INT(11),' .
            '`maxVolume` INT(11),' .
            '`maxWeightPerPlace` INT(11),' .
            '`maxDimention` INT(11),' .
            '`work_schedule` VARCHAR(255),' .

            'PRIMARY KEY (`id`)' .
            ') DEFAULT CHARSET=utf8;';

        if (!Db::getInstance()->execute($sql, false)) {
            return false;
        }

        /**
         * GENIUS EDIT
         */
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableUpdatePecom}` (".
            '`id` INT(11) AUTO_INCREMENT,' .
            '`datetime` DATETIME DEFAULT NOW() ,' .
            '`is_avia` BOOLEAN,' .  //тип перевозки
            '`city` VARCHAR(30) NOT NULL,' . //город получатель
            '`costTotal` INT(11) NOT NULL,' . //общая стоимость перевозки
            '`receiverCityId` INT(11) NOT NULL,' . //код города получателя
            'PRIMARY KEY (`id`)' .
            ') DEFAULT CHARSET=utf8;';

        if (!Db::getInstance()->execute($sql, false)) {
            return false;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableUpdatePecomDelivery}` (".
            '`id` INT(11) AUTO_INCREMENT,' .
            '`datetime` DATETIME DEFAULT NOW() ,' .
            '`is_avia` BOOLEAN,' .  //тип перевозки
            '`city` VARCHAR(30) NOT NULL,' . //город получатель
            '`costTotal` INT(11) NOT NULL,' . //общая стоимость перевозки
            '`receiverCityId` INT(11) NOT NULL,' . //код города получателя
            'PRIMARY KEY (`id`)' .
            ') DEFAULT CHARSET=utf8;';

        if (!Db::getInstance()->execute($sql, false)) {
            return false;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableCityPecom}` (".
            '`id` INT(11) AUTO_INCREMENT,' .
            '`title` VARCHAR(30) NOT NULL,'.
            '`bitrixId` VARCHAR(15) NOT NULL,'.
            '`cityID` VARCHAR(50),'.
            '`cityStatus` INT(11),'.
            '`dvisions` VARCHAR(50),'.
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
        return true;
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
        $sum = 10;
        foreach ($this->cities as $city){
            foreach ($this->getAllWeightType($city) as $type) {
                $id++;
                $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightPriceWithPrefix, ['id' => 0, 'city' => $city, 'type' => (int)$type['id'],'sum' => $sum],'INSERT');
                $sum += 10;
                if (!$res){
                    return false;
                }
            }
        }

        return true;
    }

    private function _insertStartConditionPrice(){
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 1, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 1, 'kadtype' => 2, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 1, 'kadtype' => 3,  'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 1, 'kadtype' => 4,  'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 1, 'kadtype' => 5,  'sum' => 830],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 2, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 2, 'kadtype' => 2, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 2, 'kadtype' => 3,  'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 2, 'kadtype' => 4,  'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 2, 'kadtype' => 5,  'sum' => 830],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 3, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 3, 'kadtype' => 2, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 3, 'kadtype' => 3,  'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 3, 'kadtype' => 4,  'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 3, 'kadtype' => 5,  'sum' => 830],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 4, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 4, 'kadtype' => 2, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 4, 'kadtype' => 3,  'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 4, 'kadtype' => 4,  'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 4, 'kadtype' => 5,  'sum' => 830],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 5, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 5, 'kadtype' => 2, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 5, 'kadtype' => 3,  'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 5, 'kadtype' => 4,  'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 5100, 'sumto' => 99999, 'timetype' => 5, 'kadtype' => 5,  'sum' => 830],'INSERT');

        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 1, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 1, 'kadtype' => 2, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 1, 'kadtype' => 3,  'sum' => 550],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 1, 'kadtype' => 4,  'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 1, 'kadtype' => 5,  'sum' => 830],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 2, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 2, 'kadtype' => 2, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 2, 'kadtype' => 3,  'sum' => 550],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 2, 'kadtype' => 4,  'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 2, 'kadtype' => 5,  'sum' => 830],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 3, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 3, 'kadtype' => 2, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 3, 'kadtype' => 3,  'sum' => 550],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 3, 'kadtype' => 4,  'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 3, 'kadtype' => 5,  'sum' => 830],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 4, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 4, 'kadtype' => 2, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 4, 'kadtype' => 3,  'sum' => 550],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 4, 'kadtype' => 4,  'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 4, 'kadtype' => 5,  'sum' => 830],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 5, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 5, 'kadtype' => 2, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 5, 'kadtype' => 3,  'sum' => 550],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 5, 'kadtype' => 4,  'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 4200, 'sumto' => 5100, 'timetype' => 5, 'kadtype' => 5,  'sum' => 830],'INSERT');

        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 1, 'kadtype' => 1, 'sum' => 350],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 1, 'kadtype' => 2, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 1, 'kadtype' => 3, 'sum' => 550],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 1, 'kadtype' => 4, 'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 5100, 'timetype' => 1, 'kadtype' => 5, 'sum' => 830],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 2, 'kadtype' => 1, 'sum' => 350],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 2, 'kadtype' => 2, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 2, 'kadtype' => 3, 'sum' => 550],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 2, 'kadtype' => 4, 'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 5100, 'timetype' => 2, 'kadtype' => 5, 'sum' => 830],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 3, 'kadtype' => 1, 'sum' => 350],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 3, 'kadtype' => 2, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 3, 'kadtype' => 3, 'sum' => 550],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 3, 'kadtype' => 4, 'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 5100, 'timetype' => 3, 'kadtype' => 5, 'sum' => 830],'INSERT');

        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 4, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 4, 'kadtype' => 2, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 4, 'kadtype' => 3, 'sum' => 550],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 4, 'kadtype' => 4, 'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 4, 'kadtype' => 5, 'sum' => 830],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 5, 'kadtype' => 1, 'sum' => 0],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 5, 'kadtype' => 2, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 5, 'kadtype' => 3, 'sum' => 550],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 4200, 'timetype' => 5, 'kadtype' => 4, 'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 2100, 'sumto' => 5100, 'timetype' => 5, 'kadtype' => 5, 'sum' => 830],'INSERT');

        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 1, 'kadtype' => 1, 'sum' => 350],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 1, 'kadtype' => 2, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 1, 'kadtype' => 3, 'sum' => 550],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 1, 'kadtype' => 4, 'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 1, 'kadtype' => 5, 'sum' => 830],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 2, 'kadtype' => 1, 'sum' => 350],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 2, 'kadtype' => 2, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 2, 'kadtype' => 3, 'sum' => 550],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 2, 'kadtype' => 4, 'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 2, 'kadtype' => 5, 'sum' => 830],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 3, 'kadtype' => 1, 'sum' => 350],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 3, 'kadtype' => 2, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 3, 'kadtype' => 3, 'sum' => 550],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 3, 'kadtype' => 4, 'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 3, 'kadtype' => 5, 'sum' => 830],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 4, 'kadtype' => 1, 'sum' => 350],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 4, 'kadtype' => 2, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 4, 'kadtype' => 3, 'sum' => 550],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 4, 'kadtype' => 4, 'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 4, 'kadtype' => 5, 'sum' => 830],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 5, 'kadtype' => 1, 'sum' => 350],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 5, 'kadtype' => 2, 'sum' => 400],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 5, 'kadtype' => 3, 'sum' => 550],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 5, 'kadtype' => 4, 'sum' => 750],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sumfrom' => 0, 'sumto' => 2100, 'timetype' => 5, 'kadtype' => 5, 'sum' => 830],'INSERT');

        return true;
    }

    private function _insertStartCarryPrice(){

        $res = Db::getInstance()->autoExecuteWithNullValues(self::$tableCarryPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'axiomus', 'sum' => 22],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues(self::$tableCarryPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'dpd', 'sum' => 33],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues(self::$tableCarryPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'boxberry', 'sum' => 44],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues(self::$tableCarryPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'russianpost', 'sum' => 77],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues(self::$tableCarryPriceWithPrefix, ['id' => 0, 'city' => 'Москва', 'delivery' => 'pecom', 'sum' => 77],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues(self::$tableCarryPriceWithPrefix, ['id' => 0, 'city' => 'Санкт-Петербург', 'delivery' => 'axiomus', 'sum' => 55],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues(self::$tableCarryPriceWithPrefix, ['id' => 0, 'city' => 'Санкт-Петербург', 'delivery' => 'dpd', 'sum' => 66],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues(self::$tableCarryPriceWithPrefix, ['id' => 0, 'city' => 'Санкт-Петербург', 'delivery' => 'boxberry', 'sum' => 77],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues(self::$tableCarryPriceWithPrefix, ['id' => 0, 'city' => 'Санкт-Петербург', 'delivery' => 'russianpost', 'sum' => 88],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues(self::$tableCarryPriceWithPrefix, ['id' => 0, 'city' => 'Санкт-Петербург', 'delivery' => 'pecom', 'sum' => 88],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues(self::$tableCarryPriceWithPrefix, ['id' => 0, 'city' => 'регионы', 'delivery' => 'axiomus', 'sum' => 55],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues(self::$tableCarryPriceWithPrefix, ['id' => 0, 'city' => 'регионы', 'delivery' => 'dpd', 'sum' => 66],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues(self::$tableCarryPriceWithPrefix, ['id' => 0, 'city' => 'регионы', 'delivery' => 'boxberry', 'sum' => 77],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues(self::$tableCarryPriceWithPrefix, ['id' => 0, 'city' => 'регионы', 'delivery' => 'russianpost', 'sum' => 77],'INSERT');
        $res = Db::getInstance()->autoExecuteWithNullValues(self::$tableCarryPriceWithPrefix, ['id' => 0, 'city' => 'регионы', 'delivery' => 'pecom', 'sum' => 88],'INSERT');
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

        $sql = "DROP TABLE IF EXISTS `".self::$tableCarryPriceWithPrefix."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $sql = "DROP TABLE IF EXISTS `".$this->tableCacheCarryPecomWithPrefix."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        /**
         * GENIUS EDIT
         */

        $sql = "DROP TABLE IF EXISTS `".$this->tableUpdatePecom."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $sql = "DROP TABLE IF EXISTS `".$this->tableUpdatePecomDelivery."`;";
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $sql = "DROP TABLE IF EXISTS `".$this->tableCityPecom."`;";
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

    public function getPrice($cart_id, $city, $carry, $weight, $price, $carrytype, $kad, $time){

        //определение типа веса
        if ($carry){
            $delivery = $this->getActiveCarry($city)[$carrytype];
            return self::getCarryPriceByName($city,$delivery,$price,$cart_id);
        }else {
            $weighttype = $this->getWeightTypeId($weight);
            //По весу

            $sumWeight = $this->getWeightPrice($city,$weighttype);

            //Надбавка
            $sumCondition = $this->getConditionPrice($city, $price, $kad, $time);

            return $sumWeight + $sumCondition;
        }
    }

    //WeightType
    public function getAllWeightType($city){
        $data = Db::getInstance()->ExecuteS("SELECT * FROM `{$this->tableWeightTypeWithPrefix}` WHERE `city` = '{$city}';");
        return $data;
    }

    public function getWeightTypeId($city, $weight){
        $sql = "SELECT * FROM {$this->tableWeightTypeWithPrefix} WHERE ";
        $sql .= "(`weightfrom` <= '{$weight}' AND `weightto` > '{$weight}')";
        $sql .= " AND `city` = '{$city}'";
        $res = Db::getInstance()->getRow($sql);
        return (int)$res['id'];
    }

    public function getWeightNameById($id)
    {
        $sql = "SELECT * FROM {$this->tableWeightTypeWithPrefix} WHERE `id` = '{$id}'";
        $res = Db::getInstance()->getRow($sql);
        return $res['name'];
    }

    public function insertWeightType($city, $name, $weightfrom, $weightto){
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightTypeWithPrefix, ['city' => $city, 'name' => $name, 'weightfrom' => $weightfrom, 'weightto' => $weightto],'INSERT');
        if (!$res){
            return false;
        }else{
            return true;
        }
    }

    public function updateWeightType($id, $name, $weightfrom, $weightto){
        $res = Db::getInstance()->update(AxiomusPost::$definition['tableWeightType'], ['name' => $name, 'weightfrom' => $weightfrom, 'weightto' => $weightto]," `id` = {$id}");
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

    public function insertTimeType($city, $name, $timefrom, $timeto){
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableTimeTypeWithPrefix, ['city' => $city, 'name' => $name, 'timefrom' => $timefrom, 'timeto' => $timeto],'INSERT');
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

    public function getWeightPrice($city, $weighttype){
        $sql = "SELECT * FROM {$this->tableWeightPriceWithPrefix} WHERE ";
        $sql .= "(`city` = '{$city}' AND `type` = {$weighttype})";
        $res = Db::getInstance()->getRow($sql);
        return $res['sum'];
    }

    public function insertWeightPrice($city, $type, $sum){
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableWeightPriceWithPrefix, ['city' => $city, 'type' => $type, 'sum' => $sum],'INSERT');
        if (!$res){
            return false;
        }else{
            return true;
        }
    }

    public function updateWeightPrice($id, $city, $type, $sum){
        $res = Db::getInstance()->update(AxiomusPost::$definition['tableWeightPrice'], ['city' => $city, 'type' => $type, 'sum' => $sum]," `id` = {$id})");
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
    public function getConditionPrice($city, $price, $kad, $time){
        $sql = "SELECT * FROM `{$this->tableConditionPriceWithPrefix}` WHERE ";
        $sql .= "(`city` = '{$city}')";
        $sql .= " AND (`sumfrom` <= '{$price}' AND `sumto` > '{$price}')";
        $sql .= " AND (`kadtype` = {$kad})";
        $sql .= " AND (`timetype` = {$time})";
        $res = Db::getInstance()->getRow($sql);
        return $res['sum'];
    }

    public function getAllConditionPrice($city){
        $data = Db::getInstance()->ExecuteS("SELECT * FROM `{$this->tableConditionPriceWithPrefix}` WHERE `city` = '{$city}';");
        return $data;
    }

    public function insertConditionPrice($city, $delivery, $sumfrom, $sumto, $timetype, $kadtype, $sum){
        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableConditionPriceWithPrefix, ['city' => $city, 'delivery' => $delivery, 'sumfrom' => $sumfrom, 'sumto' => $sumto, 'timetype' => $timetype, 'kadtype' => $kadtype, 'sum' => $sum],'INSERT');
        if ($res) {
            return true;
        }else{
            return false;
        }
    }

    public function updateConditionPrice($id, $city, $delivery, $sumfrom, $sumto, $timetype, $kadtype, $sum){
        $res = Db::getInstance()->update(AxiomusPost::$definition['tableConditionPrice'], ['city' => $city, 'delivery' => $delivery, 'sumfrom' => $sumfrom, 'sumto' => $sumto, 'timetype' => $timetype, 'kadtype' => $kadtype, 'sum' => $sum]," `id` = {$id}");
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
    public function setOrder($id_cart, $carrier_id, $date = null, $kad = null, $time = null, $carry, $carry_address_id = null){


        if ($carry == 0) {
            $date = new DateTime($date);
            $date = $date->format('Y-m-d');
        }elseif($carry == 1){

        }else{
            return false;
        }

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
        $price_condition = $this->getConditionPrice($city, $price, $kad, $time);

        $carryCode = $this->getCarryAddresses((int)$carrier_id, null, $carry_address_id)['code'];

        if ($this->issetOrder($cart->id)){
            $res = Db::getInstance()->update(AxiomusPost::$definition['tableOrder'], ['delivery_id' => $carrier_id,'date' => $date, 'carry' => (boolean)$carry, 'kadtype' => $kad, 'timetype' => $time, 'price_weight' => (int)$price_weight, 'price_condition' => (int)$price_condition, 'carry_code' => $carryCode],"`id_cart` = {$id_cart}");
            if (!$res){
                return false;
            }else{
                return true;
            }
        }else {
            $res = Db::getInstance()->autoExecuteWithNullValues($this->tableOrderWithPrefix, ['id_cart' => $id_cart, 'delivery_id' => $carrier_id,'date' => $date, 'carry' => $carry, 'kadtype' => $kad, 'timetype' => $time, 'price_weight' => $price_weight, 'price_condition' => $price_condition, 'carry_code' => $carryCode], 'INSERT');
            if ($res) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function setOrderResponse($id_cart, $oid, $okey = null, $position_count){
        if ($this->issetOrder($id_cart)){
            $res = Db::getInstance()->update(AxiomusPost::$definition['tableOrder'], ['oid' => $oid, 'okey' => $okey, 'position_count' => (int)$position_count],"`id_cart` = {$id_cart}");
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
    public function getActiveCarry($city){
        $arr = [];
        if ($city=='Москва') {
            if (Configuration::get('RS_AXIOMUS_MSCW_USE_AXIOMUS_CARRY')) {
                $arr[Configuration::get('RS_AXIOMUS_ID_AXIOMUS_CARRY')] = 'axiomus';
            }
            if (Configuration::get('RS_AXIOMUS_MSCW_USE_DPD_CARRY')) {
                $arr[Configuration::get('RS_AXIOMUS_ID_DPD_CARRY')] = 'dpd';
            }
            if (Configuration::get('RS_AXIOMUS_MSCW_USE_BOXBERRY_CARRY')) {
                $arr[Configuration::get('RS_AXIOMUS_ID_BOXBERRY_CARRY')] = 'boxberry';
            }
            if (Configuration::get('RS_AXIOMUS_MSCW_USE_RUSSIANPOST_CARRY')) {
                $arr[Configuration::get('RS_AXIOMUS_ID_RUSSIANPOST_CARRY')] = 'russianpost';
            }
            if (Configuration::get('RS_AXIOMUS_MSCW_USE_PECOM_CARRY')) {
                $arr[Configuration::get('RS_AXIOMUS_ID_PECOM_CARRY')] = 'pecom';
            }
        }elseif ($city=='Санкт-Петербург'){
            if (Configuration::get('RS_AXIOMUS_PTR_USE_AXIOMUS_CARRY')) {
                $arr[Configuration::get('RS_AXIOMUS_oid_CARRY')] = 'axiomus';
            }
            if (Configuration::get('RS_AXIOMUS_PTR_USE_DPD_CARRY')) {
                $arr[Configuration::get('RS_AXIOMUS_ID_DPD_CARRY')] = 'dpd';
            }
            if (Configuration::get('RS_AXIOMUS_PTR_USE_BOXBERRY_CARRY')) {
                $arr[Configuration::get('RS_AXIOMUS_ID_BOXBERRY_CARRY')] = 'boxberry';
            }
            if (Configuration::get('RS_AXIOMUS_PTR_USE_RUSSIANPOST_CARRY')) {
                $arr[Configuration::get('RS_AXIOMUS_ID_RUSSIANPOST_CARRY')] = 'russianpost';
            }
            if (Configuration::get('RS_AXIOMUS_PTR_USE_PECOM_CARRY')) {
                $arr[Configuration::get('RS_AXIOMUS_ID_PECOM_CARRY')] = 'pecom';
            }
        }else{
            if (Configuration::get('RS_AXIOMUS_REGION_USE_AXIOMUS_CARRY')) {
                if ($this->issetCityInCache($city, 'axiomus')) {
                    $arr[Configuration::get('RS_AXIOMUS_ID_CARRY')] = 'axiomus';
                }
            }
            if (Configuration::get('RS_AXIOMUS_REGION_USE_DPD_CARRY')) {
                if ($this->issetCityInCache($city, 'dpd')) {
                    $arr[Configuration::get('RS_AXIOMUS_ID_DPD_CARRY')] = 'dpd';
                }
            }
            if (Configuration::get('RS_AXIOMUS_REGION_USE_BOXBERRY_CARRY')) {
                if ($this->issetCityInCache($city, 'boxberry')) {
                    $arr[Configuration::get('RS_AXIOMUS_ID_BOXBERRY_CARRY')] = 'boxberry';
                }
            }
            if (Configuration::get('RS_AXIOMUS_REGION_USE_RUSSIANPOST_CARRY')) {
                if ($this->issetCityInCache($city, 'russianpost')) {
                    $arr[Configuration::get('RS_AXIOMUS_ID_RUSSIANPOST_CARRY')] = 'russianpost';
                }
            }
            if (Configuration::get('RS_AXIOMUS_REGION_USE_PECOM_CARRY')) {
                if ($this->issetCityInCache($city, 'pecom')) {
                    $arr[Configuration::get('RS_AXIOMUS_ID_PECOM_CARRY')] = 'pecom';
                }
            }
        }
        return $arr;
    }

    public function getCarryAddresses($carry_id, $city = null, $id = null, $code = null){
        if ($carry_id == (int)Configuration::get('RS_AXIOMUS_ID_AXIOMUS_CARRY')) { //axiomus
            $activeTable = $this->tableCacheCarryAxiomusWithPrefix;
            $sql = "SELECT * FROM `{$activeTable}`";
            if (!empty($id)){
                $sql .= " WHERE `id` = '{$id}'";
                $data = Db::getInstance()->getRow($sql);
            }elseif(!empty($city)){
                $sql .= " WHERE `city_name` = '{$city}'";
                $data = Db::getInstance()->ExecuteS($sql);
            }elseif(!empty($code)){
                $sql .= " WHERE `code` = '{$code}'";
                $data = Db::getInstance()->ExecuteS($sql);
            }
            return $data;
        }elseif ($carry_id == (int)Configuration::get('RS_AXIOMUS_ID_DPD_CARRY')){ //dpd
            $activeTable = $this->tableCacheCarryDPDWithPrefix;
            $sql = "SELECT * FROM `{$activeTable}`";
            if (!empty($id)){
                $sql .= " WHERE `id` = '{$id}'";
                $data = Db::getInstance()->getRow($sql);
            }elseif(!empty($city)){
                $sql .= " WHERE `city_name` = '{$city}'";
                $data = Db::getInstance()->ExecuteS($sql);
            }elseif(!empty($code)){
            $sql .= " WHERE `code` = '{$code}'";
            $data = Db::getInstance()->ExecuteS($sql);
        }
            return $data;
        }elseif ($carry_id == (int)Configuration::get('RS_AXIOMUS_ID_BOXBERRY_CARRY')){ //boxberry
            $activeTable = $this->tableCacheCarryBoxBerryWithPrefix;
            $sql = "SELECT * FROM `{$activeTable}`";
            if (!empty($id)){
                $sql .= " WHERE `id` = '{$id}'";
                $data = Db::getInstance()->getRow($sql);
            }elseif(!empty($city)){
                $sql .= " WHERE `city_name` = '{$city}'";
                $data = Db::getInstance()->ExecuteS($sql);
            }elseif(!empty($code)){
            $sql .= " WHERE `code` = '{$code}'";
            $data = Db::getInstance()->ExecuteS($sql);
        }
            return $data;
        }elseif ($carry_id == (int)Configuration::get('RS_AXIOMUS_ID_RUSSIANPOST_CARRY')){ //boxberry
//            $activeTable = $this->tableCacheCarryBoxBerryWithPrefix;
//            $data = Db::getInstance()->ExecuteS("SELECT * FROM `{$activeTable}` WHERE `city_name` = '{$city}'");
//            return $data;
        }elseif ($carry_id == (int)Configuration::get('RS_AXIOMUS_ID_PECOM_CARRY')){ //boxberry
            $activeTable = $this->tableCacheCarryPecomWithPrefix;
            $sql = "SELECT * FROM `{$activeTable}`";
            if (!empty($id)){
                $sql .= " WHERE `id` = '{$id}'";
                $data = Db::getInstance()->getRow($sql);
            }elseif(!empty($city)){
                $sql .= " WHERE `city_name` = '{$city}'";
                $data = Db::getInstance()->ExecuteS($sql);
            }elseif(!empty($code)){
            $sql .= " WHERE `code` = '{$code}'";
            $data = Db::getInstance()->ExecuteS($sql);
        }
            return $data;
        }else{
            return false;
        }

    }

    //cache carry

    public function refreshCarryAddressCacheAxiomus(){
        $error = [];
        $results = AxiomusXml::getCarryAddressesAxiomus('get_carry')->carry_list;
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

    private function getCarryCodeByIdAxiomus($id){
        $sql = "SELECT * FROM {$this->tableCacheCarryAxiomusWithPrefix} WHERE `id` = '{$id}'";
        $res = Db::getInstance()->getRow($sql);
        return $res;
    }

    public function refreshCarryAddressCacheDPD(){
        $error = [];
        $results = AxiomusXml::getCarryAddressesAxiomus('get_dpd_pickup')->pickup_list;
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
                        'city_name' => (string)$row['city'],
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
        $results = AxiomusXml::getCarryAddressesAxiomus('get_boxberry_pickup')->pickup_list;
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

    public function refreshCarryAddressCachePecom(){
        $daysNames = array("Пн","Вт","Ср","Чт","Пт","Сб","Вс");
        $error = [];
        $results = AxiomusXml::getCarryAddressesPecom()->branches;
        if(!empty($results)){
            $resTruncute = Db::getInstance()->execute("TRUNCATE TABLE `{$this->tableCacheCarryPecomWithPrefix}`");
            $resTruncute1 = Db::getInstance()->execute("TRUNCATE TABLE `{$this->tableCityPecom}`");
            if ($resTruncute && $resTruncute1 ) {
                foreach ($results as $key => $filial) { //ToDo тут все не верно
                    foreach ($filial->divisions as $keyDivision => $division) {
                        foreach ($division->warehouses as $keyWarehouse => $warehouse) {
                            if (!$this->issetCarryAddressCachepecomByWarehouseId($warehouse->id)) {
                                $timeOfWork = '';
                                if (!empty($warehouse->timeOfWork)) {
                                    foreach ($warehouse->timeOfWork as $day) {
                                        if (!empty($day->dayOfWeek)) {
                                            $timeStr = ((string)$day->workFrom == '') ? 'вых.;' : ($day->workFrom . ', ' . $day->workTo . '; ');
                                            $timeOfWork .= $daysNames[$day->dayOfWeek - 1] . ': ' . $timeStr;
                                        }
                                    }
                                }

                                $cityname = '';
                                $cityname = explode(" ", $warehouse->divisionName);
                                $cityname = $cityname[0];

                                $res = Db::getInstance()->autoExecuteWithNullValues($this->tableCacheCarryPecomWithPrefix, [
                                    'warehouseId' => (string)$warehouse->id,
                                    'code' => (string)$warehouse->id,
                                    'divisionId' => (string)$warehouse->divisionId,
                                    'name' => (string)$warehouse->name,
                                    'divisionName' => (string)$warehouse->divisionName,
                                    'city_name' => (string)$cityname,
                                    'address' => (string)(empty($warehouse->address))?$warehouse->addressDivision:$warehouse->address ,
                                    'addressDivision' => (string)(empty($warehouse->addressDivision))?$warehouse->address:$warehouse->addressDivision,
                                    'isAcceptanceOnly' => (boolean)$warehouse->isAcceptanceOnly,
                                    'isFreightSurcharge' => (boolean)$warehouse->isFreightSurcharge,
                                    'coordinates' => (string)$warehouse->coordinates,
                                    'email' => (string)$warehouse->email,
                                    'phone' => (string)$warehouse->telephone,
                                    'isRestrictions' => (boolean)$warehouse->isRestrictions,
                                    'maxWeight' => (int)$warehouse->maxWeight,
                                    'maxVolume' => (int)$warehouse->maxVolume,
                                    'maxWeightPerPlace' => (int)$warehouse->maxWeightPerPlace,
                                    'maxDimention' => (int)$warehouse->maxDimention,
                                    'work_schedule' => (string)$timeOfWork,

                                ], 'INSERT');
                                if ($res) {
                                    continue;
                                } else {
                                    $error[] = $key;
                                }

                            }
                        }
                    }
                    foreach ($filial->cities as $keyCity => $city) {

                        $res = Db::getInstance()->autoExecuteWithNullValues($this->tableCityPecom, [

                            'title' => (string)$city->title,
                            'bitrixId' => (string)$city->bitrixId,
                            'cityID' =>(string)$city->cityId,
                            'cityStatus' =>$city->cityStatus,
                            'dvisions' => $city->divisions

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
    }

    public function issetCarryAddressCachepecomByWarehouseId($warehouseId){
        $sql = "Select * FROM {$this->tableCacheCarryPecomWithPrefix} WHERE `warehouseId` = '{$warehouseId}'";
        $res = Db::getInstance()->getRow($sql);
        if ($res) {
            return true;
        }else{
            return false;
        }
    }

    public function getLastUpdateCacheCarry($type){
        if ($type=='axiomus'){
            $table = $this->tableCacheCarryAxiomusWithPrefix;
        }elseif ($type == 'dpd'){
            $table = $this->tableCacheCarryDPDWithPrefix;
        }elseif ($type == 'boxberry'){
            $table = $this->tableCacheCarryBoxBerryWithPrefix;
        }elseif ($type == 'pecom'){
            $table = $this->tableCacheCarryPecomWithPrefix;
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

    public static function getDeliveryPrice($region, $cart_id, $kad_id, $time_id)
    {
        if ($region == 0){ //Москва
            $city = 'Москва';

        }elseif($region == 1){ //Питер
            $city = 'Санкт-Петербург';
        }else{ //неизвестно что
            return false;
        }

        $axiomusPost = new AxiomusPost(); //Костыль чтобы не делать все методы статиками как полагается
        $cart = new Cart($cart_id);
        $weight = ($cart->getTotalWeight() == 0) ? 0.5 : 0;
        $price = $cart->getordertotal();

        $weighttype = $axiomusPost->getWeightTypeId($city ,$weight);
        //По весу

        $sumWeight = $axiomusPost->getWeightPrice($city,$weighttype);

        //Надбавка
        $sumCondition = $axiomusPost->getConditionPrice($city, $price, $kad_id, $time_id);

        return round($sumWeight + $sumCondition);
    }

    public static function getDeliveryPriceRegion($region, $cart_id, $city_id)
    {
        $tab = self::$tableCarryPriceWithPrefix;
        $cities = Db::getInstance()->getRow("SELECT city_name FROM ps_axiomus_cache_carry_pecom WHERE id = '{$city_id}'");
        if (!$cities) return false;

        $city = $cities['city_name'];

        $regionForRow = $region;
        if ($region != 0 && $region != 1){

            $static_price_row = Db::getInstance()->getRow("SELECT * FROM `{$tab}` WHERE `delivery` = 'pecom' AND `city` = '{$city}';");
            $pecom_price_row = Db::getInstance()->getRow("SELECT * FROM ps_axiomus_pecom_delivery WHERE `city` = '{$region} ' AND `datetime`> (now()-INTERVAL 1 DAY);");
            $delete_old_cache = "DELETE FROM ps_axiomus_pecom_delivery WHERE `datetime` < (now() - INTERVAL 1 DAY)";
            $sql = Db::getInstance()->execute($delete_old_cache);

            $pecom_price = $pecom_price_row['costTotal'] ?? 0;

            if (empty($pecom_price_row) && !empty($cart_id)) {
                $pecom_price = AxiomusXml::GetPricePecom($city, $cart_id, false);
                if (!$pecom_price) return false;
            }
        }else{
            $static_price_row = Db::getInstance()->getRow("SELECT * FROM `{$tab}` WHERE `delivery` = 'pecom' AND `city` = '{$regionForRow}';");
            $pecom_price = 0;
        }
        $static_price = $static_price_row['sum'] ?? 0;

        return round($pecom_price + $static_price);
    }

    //carry

    public static function getCarryPrice($region, $cart_id, $point_id){
        $tab = self::$tableCarryPriceWithPrefix;
        $cities =Db::getInstance()->getRow("SELECT city_name FROM ps_axiomus_cache_carry_pecom WHERE id = '{$point_id}'");
        $region= $cities['city_name'];
        $regionForRow=$region;
        if ($regionForRow!= 'Москва' && $regionForRow!= 'Санкт-Петербург'){
            $regionForRow = 'Регионы';

            $static_price_row = Db::getInstance()->getRow("SELECT * FROM `{$tab}` WHERE `delivery` = 'pecom' AND `city` = '{$regionForRow}';");
            $pecom_price_row = Db::getInstance()->getRow("SELECT * FROM ps_axiomus_pecom_carry WHERE `city` = '{$region} ' AND `datetime`> (now()-INTERVAL 1 DAY);");
            $delete_old_cache = "DELETE FROM ps_axiomus_pecom_carry WHERE `datetime` < (now() - INTERVAL 1 DAY)";
            $sql = Db::getInstance()->execute($delete_old_cache);

            $pecom_price = $pecom_price_row['costTotal'] ?? 0;

            if (empty($pecom_price_row) && !empty($cart_id)) {
                $pecom_price = AxiomusXml::GetPricePecom($region, $cart_id, true);
                if (!$pecom_price) return false;
            }
        }else{
            $static_price_row = Db::getInstance()->getRow("SELECT * FROM `{$tab}` WHERE `delivery` = 'pecom' AND `city` = '{$regionForRow}';");
        }
        $static_price = $static_price_row['sum'] ?? 0;


        return round($pecom_price + $static_price);
    }
    
    
    public static function getCarryPriceByName($city, $name, $price = null, $cart_id = null){
        $tab = self::$tableCarryPriceWithPrefix;
        $cityForStaticPrice = $city;
        if ($cityForStaticPrice!= 'Москва' && $cityForStaticPrice!= 'Санкт-Петербург'){
            $cityForStaticPrice = 'Регионы';}
            $static_price_row = Db::getInstance()->getRow("SELECT * FROM `{$tab}` WHERE `delivery` = '{$name}' AND `city` = '{$cityForStaticPrice}';");
            $pecom_price_row = Db::getInstance()->getRow("SELECT * FROM ps_axiomus_pecom_carry WHERE `city` = '{$city} ' AND `datetime`> (now()-INTERVAL 1 DAY);");
            $delete_old_cache =  "DELETE FROM ps_axiomus_pecom_carry WHERE `datetime` < (now() - INTERVAL 1 DAY)";
            $sql = Db::getInstance()->execute($delete_old_cache);
            $static_price = $static_price_row['sum'] ?? 0;
            $pecom_price = $pecom_price_row['costTotal'] ?? 0;
            if(empty($pecom_price_row) && (!empty($price) && !empty($cart_id))){
                $pecom_price = AxiomusXml::GetPricePecom($city,$price,$cart_id);
            }

        return $pecom_price + $static_price;
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

    public function issetCityInCache($city, $deliveryname){
        if ($deliveryname == 'axiomus'){
            $table = $this->tableCacheCarryAxiomusWithPrefix;
        }elseif ($deliveryname == 'dpd'){
            $table = $this->tableCacheCarryDPDWithPrefix;
        }elseif ($deliveryname == 'boxberry'){
            $table = $this->tableCacheCarryBoxBerryWithPrefix;
        }elseif ($deliveryname == 'russianpost'){
            return;
        }elseif ($deliveryname == 'pecom'){
            $table = $this->tableCacheCarryPecomWithPrefix;
        }else{
            return false;
        }

        $sql = "Select * FROM {$table} WHERE `city_name` = '{$city}'";
        $res = Db::getInstance()->getRow($sql);
        if ($res) {
            return true;
        }else{
            return false;
        }
    }

    public static function getSettingsArray($getvalue){
        return [
            //CPU

            //Moscow
            'use_mscw_axiomus'              => Configuration::get('RS_AXIOMUS_MSCW_USE_AXIOMUS'),
            'use_mscw_strizh'               => Configuration::get('RS_AXIOMUS_MSCW_USE_STRIZH'),
            'use_mscw_pecom'                => Configuration::get('RS_AXIOMUS_MSCW_USE_PECOM'),
            'use_mscw_axiomus_carry'        => Configuration::get('RS_AXIOMUS_MSCW_USE_AXIOMUS_CARRY'),
            'use_mscw_dpd_carry'            => Configuration::get('RS_AXIOMUS_MSCW_USE_DPD_CARRY'),
            'use_mscw_boxberry_carry'       => Configuration::get('RS_AXIOMUS_MSCW_USE_BOXBERRY_CARRY'),
            'use_mscw_russianpost_carry'    => Configuration::get('RS_AXIOMUS_MSCW_USE_RUSSIANPOST_CARRY'),
            'use_mscw_pecom_carry'          => Configuration::get('RS_AXIOMUS_MSCW_USE_PECOM_CARRY'),
            //Piter
            'use_ptr_axiomus'              => Configuration::get('RS_AXIOMUS_PTR_USE_AXIOMUS'),
            'use_ptr_strizh'               => Configuration::get('RS_AXIOMUS_PTR_USE_STRIZH'),
            'use_ptr_pecom'                => Configuration::get('RS_AXIOMUS_PTR_USE_PECOM'),
            'use_ptr_axiomus_carry'        => Configuration::get('RS_AXIOMUS_PTR_USE_AXIOMUS_CARRY'),
            'use_ptr_dpd_carry'            => Configuration::get('RS_AXIOMUS_PTR_USE_DPD_CARRY'),
            'use_ptr_boxberry_carry'       => Configuration::get('RS_AXIOMUS_PTR_USE_BOXBERRY_CARRY'),
            'use_ptr_russianpost_carry'    => Configuration::get('RS_AXIOMUS_PTR_USE_RUSSIANPOST_CARRY'),
            'use_ptr_pecom_carry'          => Configuration::get('RS_AXIOMUS_PTR_USE_PECOM_CARRY'),
            //region
            'use_region_axiomus_carry'        => Configuration::get('RS_AXIOMUS_REGION_USE_AXIOMUS_CARRY'),
            'use_region_dpd_carry'            => Configuration::get('RS_AXIOMUS_REGION_USE_DPD_CARRY'),
            'use_region_boxberry_carry'       => Configuration::get('RS_AXIOMUS_REGION_USE_BOXBERRY_CARRY'),
            'use_region_russianpost_carry'    => Configuration::get('RS_AXIOMUS_REGION_USE_RUSSIANPOST_CARRY'),
            'use_region_pecom_carry'          => Configuration::get('RS_AXIOMUS_REGION_USE_PECOM_CARRY'),
            //Settings
            'axiomus_ukey'                => Configuration::get('RS_AXIOMUS_UKEY'),
            'axiomus_uid'       => Configuration::get('RS_AXIOMUS_UID'),
            'axiomus_url'       => Configuration::get('RS_AXIOMUS_URL'),
            //Settings-pecom
            'pecom_sender' => [
                'pecom_sender_city'                     => ($getvalue)?Configuration::get('RS_PECOM_SENDER_CITY'):'RS_PECOM_SENDER_CITY',
                'pecom_sender_title'                    => ($getvalue)?Configuration::get('RS_PECOM_SENDER_TITLE'):'RS_PECOM_SENDER_TITLE',
                'pecom_sender_person'                   => ($getvalue)?Configuration::get('RS_PECOM_SENDER_PERSON'):'RS_PECOM_SENDER_PERSON',
                'pecom_sender_phone'                    => ($getvalue)?Configuration::get('RS_PECOM_SENDER_PHONE'):'RS_PECOM_SENDER_PHONE',
                'pecom_sender_email'                    => ($getvalue)?Configuration::get('RS_PECOM_SENDER_EMAIL'):'RS_PECOM_SENDER_EMAIL',
                'pecom_sender_address_office'           => ($getvalue)?Configuration::get('RS_PECOM_SENDER_ADDRESS_OFFICE'):'RS_PECOM_SENDER_ADDRESS_OFFICE',
                'pecom_sender_address_office_comment'   => ($getvalue)?Configuration::get('RS_PECOM_SENDER_ADDRESS_OFFICE_COOMENT'):'RS_PECOM_SENDER_ADDRESS_OFFICE_COOMENT',
                'pecom_sender_address_stock'            => ($getvalue)?Configuration::get('RS_PECOM_SENDER_ADDRESS_STOCK'):'RS_PECOM_SENDER_ADDRESS_STOCK',
                'pecom_sender_address_stock_comment'    => ($getvalue)?Configuration::get('RS_PECOM_SENDER_ADDRESS_STOCK_COMMENT'):'RS_PECOM_SENDER_ADDRESS_STOCK_COMMENT',
                'pecom_sender_work_time_from'           => ($getvalue)?Configuration::get('RS_PECOM_SENDER_WORK_TIME_FROM'):'RS_PECOM_SENDER_WORK_TIME_FROM',
                'pecom_sender_work_time_to'             => ($getvalue)?Configuration::get('RS_PECOM_SENDER_WORK_TIME_TO'):'RS_PECOM_SENDER_WORK_TIME_TO',
                'pecom_sender_lunch_break_from'         => ($getvalue)?Configuration::get('RS_PECOM_SENDER_LUNCH_BREAK_FROM'):'RS_PECOM_SENDER_LUNCH_BREAK_FROM',
                'pecom_sender_lunch_break_to'           => ($getvalue)?Configuration::get('RS_PECOM_SENDER_LUNCH_BREAK_TO'):'RS_PECOM_SENDER_LUNCH_BREAK_TO',
                'pecom_sender_is_auth_needed'           => ($getvalue)?Configuration::get('RS_PECOM_SENDER_IS_AUTH_NEEDED'):'RS_PECOM_SENDER_IS_AUTH_NEEDED',
                'pecom_sender_identity_type'            => ($getvalue)?Configuration::get('RS_PECOM_SENDER_IDENTITY_TYPE'):'RS_PECOM_SENDER_IDENTITY_TYPE',
                'pecom_sender_identity_series'          => ($getvalue)?Configuration::get('RS_PECOM_SENDER_IDENTITY_SERIES'):'RS_PECOM_SENDER_IDENTITY_SERIES',
                'pecom_sender_identity_number'          => ($getvalue)?Configuration::get('RS_PECOM_SENDER_NUMBER'):'RS_PECOM_SENDER_NUMBER',
                'pecom_sender_identity_date'            => ($getvalue)?Configuration::get('RS_PECOM_SENDER_DATE'):'RS_PECOM_SENDER_DATE',
            ],
            'pecom_default' => [
                'pecom_volume_one'                      => ($getvalue)?Configuration::get('RS_PECOM_VOLUME_ONE'):'RS_PECOM_VOLUME_ONE',
                'pecom_is_fragile'                      => ($getvalue)?Configuration::get('RS_PECOM_IS_FLAGILE'):'RS_PECOM_IS_FLAGILE',
                'pecom_is_glass'                        => ($getvalue)?Configuration::get('RS_PECOM_IS_GLASS'):'RS_PECOM_IS_GLASS',
                'pecom_is_liquid'                       => ($getvalue)?Configuration::get('RS_PECOM_IS_LIQUID'):'RS_PECOM_IS_LIQUID',
                'pecom_is_othertype'                    => ($getvalue)?Configuration::get('RS_PECOM_IS_OTHERTYPE'):'RS_PECOM_IS_OTHERTYPE',
                'pecom_othertype_description'           => ($getvalue)?Configuration::get('RS_PECOM_OTHERTYPE_DESCRIPTION'):'RS_PECOM_OTHERTYPE_DESCRIPTION',
                'pecom_is_opencar'                      => ($getvalue)?Configuration::get('RS_PECOM_IS_OPENCAR'):'RS_PECOM_IS_OPENCAR',
                'pecom_is_sideload'                     => ($getvalue)?Configuration::get('RS_PECOM_IS_SIDELOAD'):'RS_PECOM_IS_SIDELOAD',
                'pecom_is_special_eq'                   => ($getvalue)?Configuration::get('RS_PECOM_IS_SPECIAL_EQ'):'RS_PECOM_IS_SPECIAL_EQ',
                'pecom_is_uncovered'                    => ($getvalue)?Configuration::get('RS_PECOM_IS_UNCOVERED'):'RS_PECOM_IS_UNCOVERED',
                'pecom_is_daybyday'                     => ($getvalue)?Configuration::get('RS_PECOM_IS_DAYBYDAY'):'RS_PECOM_IS_DAYBYDAY',
                'pecom_register_type'                   => ($getvalue)?Configuration::get('RS_PECOM_REGISTER_TYPE'):'RS_PECOM_REGISTER_TYPE',
                'pecom_responsible_person'              => ($getvalue)?Configuration::get('RS_PECOM_RESPONSIBLE'):'RS_PECOM_RESPONSIBLE',
                'pecom_is_hp'                           => ($getvalue)?Configuration::get('RS_PECOM_IS_HP'):'RS_PECOM_IS_HP',
                'pecom_hp_position_count'               => ($getvalue)?Configuration::get('RS_PECOM_HP_POSITION_COUNT'):'RS_PECOM_HP_POSITION_COUNT',
                'pecom_is_insurance'                    => ($getvalue)?Configuration::get('RS_PECOM_IS_INSURANCE'):'RS_PECOM_IS_INSURANCE',
                'pecom_insurance_price'                 => ($getvalue)?Configuration::get('RS_PECOM_INSURANCE_PRICE'):'RS_PECOM_INSURANCE_PRICE',
                'pecom_is_sealing'                      => ($getvalue)?Configuration::get('RS_PECOM_IS_SEALING'):'RS_PECOM_IS_SEALING',
                'pecom_sealing_position_count'          => ($getvalue)?Configuration::get('RS_PECOM_SEALING_POSITION_COUNT'):'RS_PECOM_SEALING_POSITION_COUNT',
                'pecom_is_strapping'                    => ($getvalue)?Configuration::get('RS_PECOM_IS_STRAPPING'):'RS_PECOM_IS_STRAPPING',
                'pecom_is_documents_return'             => ($getvalue)?Configuration::get('RS_PECOM_IS_DOCUMENTS_RETURN'):'RS_PECOM_IS_DOCUMENTS_RETURN',
                'pecom_is_loading'                      => ($getvalue)?Configuration::get('RS_PECOM_IS_LOADING'):'RS_PECOM_IS_LOADING',
            ],
            'pecom_settings' => [
                'pecom_nickname'                      => ($getvalue)?Configuration::get('RS_PECOM_NICKNAME'):'RS_PECOM_NICKNAME',
                'pecom_api'                      => ($getvalue)?Configuration::get('RS_PECOM_API'):'RS_PECOM_API',
            ],

            'mscw_carry_axiomus' => [
                'daycount'                              => ($getvalue)?Configuration::get('RS_AXIOMUS_MSCW_DAYCOUNT'):'RS_AXIOMUS_MSCW_DAYCOUNT', //ToDo в данный момент не используется в isSubmit
            ],
            'ptr_carry_axiomus' => [
                'daycount'                              => ($getvalue)?Configuration::get('RS_AXIOMUS_MSCW_DAYCOUNT'):'RS_AXIOMUS_MSCW_DAYCOUNT', //ToDo в данный момент не используется в isSubmit
            ],
            //Moscow
            'mscw_axiomus_manual'          => Configuration::get('RS_AXIOMUS_MSCW_AXIOMUS_MANUAL'),
            'mscw_axiomus_increment'       => Configuration::get('RS_AXIOMUS_MSCW_AXIOMUS_INCREMENT'),
//            'mscw_axiomus_weight'          => Configuration::getMultiple('RS_AXIOMUS_MSCW_AXIOMUS_PRICE'),
        ];
    }



}