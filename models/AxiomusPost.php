<?php

class AxiomusPost extends ObjectModel {

    public $id;
    public $id_state;
    public $id_post_zone;
    public $active;
    public $tableWithPrefix;
    public $tableCacheWithPrefix; //ToDo а нужен ли public?
    protected $dbconn;
    public static $definition = array(
        'table' => 'axiomus_post',
        'tableCache' => 'axiomus_cache',
        'primary' => 'id',
        'multilang' => false,
        'fields' => array(
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
    }

    public function createTable() {
        //ToDo добавить функцию добавления базы $this->tableCache

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableWithPrefix}` (" .
                '`id` INT(11) NOT NULL AUTO_INCREMENT,' .
                '`id_state` INT(11) NOT NULL,' .
                '`id_post_zone` INT(11) NOT NULL,' .
                '`active` INT(11) NOT NULL,' .
                'PRIMARY KEY (`id`)' .
                ') DEFAULT CHARSET=utf8;';

        if (!Db::getInstance()->execute($sql, false)) {
            return false;
        }

        return true;
    }

    public function dropTable() {

        $sql = "DROP TABLE IF EXISTS `".$this->tableWithPrefix."`;";

        var_dump($sql);
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

    public function getRowInCache($id_addr, $id_product){
        $sql = "SELECT * FROM `{$this->tableCacheWithPrefix}` WHERE `id_addr` = {$id_addr} AND `id_product` = {$id_product}";

        $row = Db::getInstance()->getRow($sql);
        if (isset($row['create_datetime'])){
            $date = new DateTime();
            $date->format('Y-m-d H:i:s');
            $date->add(DateInterval::createfromdatestring('+1 day'));

            $dateInBase = new DateTime($row['create_datetime']);

            if (date_timestamp_get($date) < date_timestamp_get($dateInBase)){
                $sql = "DELETE FROM `{$this->tableCacheWithPrefix}` WHERE `id` = {$row['id']}";
                Db::getInstance()->execute($sql);
                return false;
            }else{
                return $row;
            }
        }
        return false;
    }

    public function insertRowInCache($id_addr, $id_product, $price){
        Db::getInstance()->autoExecuteWithNullValues($this->tableCacheWithPrefix, ['id_addr' => $id_addr, 'id_product' => $id_product, 'price' => $price],'INSERT'); //ToDo добавить исключения
        //Записать в таблицу новую запись
        return true;
    }

    // Пришлось перегрузить этот метод. Колонка в таблице
    // у нас не по правилам называется.
    // Заодно довавил LIMIT на всякий случай
    // FIXME: Поле в таблице потом переименовать! И этот метод убрать!
    public static function existsInDatabase($id_entity, $table) {

        $row = Db::getInstance()->getRow(
                "SELECT `id` FROM `" . _DB_PREFIX_ . $table . "` e " .
                "WHERE `e`.`id` = " . (int) $id_entity . " " .
                "LIMIT 1"
        );

        return isset($row['id']);
    }

}