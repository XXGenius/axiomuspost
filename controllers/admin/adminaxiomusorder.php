<?php
require_once(_PS_MODULE_DIR_ . 'axiomuspostcarrier/models/AxiomusXml.php');

class AdminAxiomusOrderController extends ModuleAdminController
{

    public $toolbar_title;

    protected $statuses_array = array();

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'order';
        $this->className = 'Order';
        $this->lang = false;
        $this->addRowAction('send');
        $this->explicitSelect = true;
        $this->allow_export = true;
        $this->deleted = false;
        $this->context = Context::getContext();

        $this->_select = '
		a.id_currency,
		CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) AS `customer`,
		osl.`name` AS `osname`,
		os.`color`,
		IF((SELECT so.id_order FROM `' . _DB_PREFIX_ . 'orders` so WHERE so.id_customer = a.id_customer AND so.id_order < a.id_order LIMIT 1) > 0, 0, 1) as new,
		carrier.name as cname,
		carrier.id_carrier as cid,
		IF(a.valid, 1, 0) badge_success';

        $this->_join = '
        LEFT JOIN `'._DB_PREFIX_.'order_carrier` oc ON a.`id_order` = oc.`id_order`
		LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON (c.`id_customer` = a.`id_customer`)
		LEFT JOIN `' . _DB_PREFIX_ . 'address` address ON address.id_address = a.id_address_delivery
		LEFT JOIN `' . _DB_PREFIX_ . 'carrier` carrier ON carrier.id_carrier = a.id_carrier
		LEFT JOIN `' . _DB_PREFIX_ . 'country` country ON address.id_country = country.id_country
		LEFT JOIN `' . _DB_PREFIX_ . 'country_lang` country_lang ON (country.`id_country` = country_lang.`id_country` AND country_lang.`id_lang` = ' . (int)$this->context->language->id . ')
		LEFT JOIN `' . _DB_PREFIX_ . 'order_state` os ON (os.`id_order_state` = a.`current_state`)
		LEFT JOIN `' . _DB_PREFIX_ . 'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = ' . (int)$this->context->language->id . ')';
        $this->_orderBy = 'id_order';
        $this->_orderWay = 'DESC';
        $this->_use_found_rows = true;

        $statuses = OrderState::getOrderStates((int)$this->context->language->id);
        foreach ($statuses as $status) {
            $this->statuses_array[$status['id_order_state']] = $status['name'];
        }

        $this->fields_list = array(
            'id_order' => array(
                'title' => $this->l('ID'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs'
            ),
            'reference' => array(
                'title' => $this->l('Reference')
            ),
            'tracking_number' => array(
                'title' => $this->l('tracking number'),
                'havingFilter' => true,
            ),
            'new' => array(
                'title' => $this->l('New client'),
                'align' => 'text-center',
                'type' => 'bool',
                'tmpTableFilter' => true,
                'orderby' => false,
                'callback' => 'printNewCustomer'
            ),
            'customer' => array(
                'title' => $this->l('Customer'),
                'havingFilter' => true,
            ),
        );

        if (Configuration::get('PS_B2B_ENABLE')) {
            $this->fields_list = array_merge($this->fields_list, array(
                'company' => array(
                    'title' => $this->l('Company'),
                    'filter_key' => 'c!company'
                ),
            ));
        }

        $this->fields_list = array_merge($this->fields_list, array(
            'total_paid_tax_incl' => array(
                'title' => $this->l('Total'),
                'align' => 'text-right',
                'type' => 'price',
                'currency' => true,
                'callback' => 'setOrderCurrency',
                'badge_success' => true
            ),
            'payment' => array(
                'title' => $this->l('Payment')
            ),
            'osname' => array(
                'title' => $this->l('Status'),
                'type' => 'select',
                'color' => 'color',
                'list' => $this->statuses_array,
                'filter_key' => 'os!id_order_state',
                'filter_type' => 'int',
                'order_key' => 'osname'
            ),
            'date_add' => array(
                'title' => $this->l('Date'),
                'align' => 'text-right',
                'type' => 'datetime',
                'filter_key' => 'a!date_add'
            ),
//            'id_pdf' => array(
//                'title' => $this->l('PDF'),
//                'align' => 'text-center',
////                'callback' => 'printPDFIcons',
//                'orderby' => false,
//                'search' => false,
//                'remove_onclick' => true
//            )
        ));


        $sql = '
        SELECT c.name, c.id_carrier
        FROM `' . _DB_PREFIX_ . 'orders` o
        ' . Shop::addSqlAssociation('orders', 'o') . '
        INNER JOIN `' . _DB_PREFIX_ . 'carrier` c ON o.id_carrier= c.id_carrier
        ORDER BY c.name ASC';
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);


        $country_array = array();
        foreach ($result as $row) {
            $country_array[$row['id_carrier']] = $row['name'];
        }

        $part1 = array_slice($this->fields_list, 0, 3);
        $part2 = array_slice($this->fields_list, 3);
        $part1['cname'] = array(
            'title' => $this->l('Delivery'),
            'type' => 'select',
            'list' => $country_array,
            'filter_key' => 'carrier!id_carrier',
            'filter_type' => 'int',
            'order_key' => 'cname'
        );
        $this->fields_list = array_merge($part1, $part2);

        //Заполним массив включенными id доставок
        $arr = [];
        if (!empty(Configuration::get('RS_AXIOMUS_ID_TOPDELIVERY_DELIVERY')))
            $arr[] = (int)Configuration::get('RS_AXIOMUS_ID_TOPDELIVERY_DELIVERY');
        if (!empty(Configuration::get('RS_AXIOMUS_ID_DPD_DELIVERY')))
            $arr[] = (int)Configuration::get('RS_AXIOMUS_ID_DPD_DELIVERY');
        if (!empty(Configuration::get('RS_AXIOMUS_ID_BOXBERRY_DELIVERY')))
            $arr[] = (int)Configuration::get('RS_AXIOMUS_ID_BOXBERRY_DELIVERY');
        if (!empty(Configuration::get('RS_AXIOMUS_ID_AXIOMUS_CARRY')))
            $arr[] = (int)Configuration::get('RS_AXIOMUS_ID_AXIOMUS_CARRY');
        if (!empty(Configuration::get('RS_AXIOMUS_ID_TOPDELIVERY_CARRY')))
            $arr[] = (int)Configuration::get('RS_AXIOMUS_ID_TOPDELIVERY_CARRY');
        if (!empty(Configuration::get('RS_AXIOMUS_ID_DPD_CARRY')))
            $arr[] = (int)Configuration::get('RS_AXIOMUS_ID_DPD_CARRY');
        if (!empty(Configuration::get('RS_AXIOMUS_ID_BOXBERRY_CARRY')))
            $arr[] = (int)Configuration::get('RS_AXIOMUS_ID_BOXBERRY_CARRY');
        if (!empty(Configuration::get('RS_AXIOMUS_ID_RUSSIANPOST_CARRY')))
            $arr[] = (int)Configuration::get('RS_AXIOMUS_ID_RUSSIANPOST_CARRY');
        if (!empty(Configuration::get('RS_AXIOMUS_ID_AXIOMUS_DELIVERY')))
            $arr[] = (int)Configuration::get('RS_AXIOMUS_ID_AXIOMUS_DELIVERY');

        //Фильтруем выборку по нашим доставкам
        $count = 0;
        $wherestring = '';
        foreach ($arr as $item){
            if ($count == 0){
                $wherestring = 'AND (carrier.id_carrier = '.$item;
                $count++;
            }else{
                $wherestring .= ' OR carrier.id_carrier = '.$item;
            }
        }
        if (!empty($wherestring)){
            $wherestring .= ')';
            $this->_where = $wherestring;
        }


        $this->shopLinkType = 'shop';
        $this->shopShareDatas = Shop::SHARE_ORDER;

        if (Tools::isSubmit('id_order')) {
            // Save context (in order to apply cart rule)
            $order = new Order((int)Tools::getValue('id_order'));
            $this->context->cart = new Cart($order->id_cart);
            $this->context->customer = new Customer($order->id_customer);
        }

        $this->bulk_actions = array(
            'updateOrderStatus' => array('text' => $this->l('Change Order Status'), 'icon' => 'icon-refresh')
        );

        parent::__construct();
    }

    public function printNewCustomer($id_order, $tr)
    {
        return ($tr['new'] ? $this->l('Yes') : $this->l('No'));
    }
    public static function setOrderCurrency($echo, $tr)
    {
        $order = new Order($tr['id_order']);
        return Tools::displayPrice($echo, (int)$order->id_currency);
    }

    public function postProcess()
    {
        //ToDo добавить везде такую же проверку токена
        if (isset($_GET['token']))
            if ($_GET['token']!=$this->token)
                return;
        if (!($object = $this->loadObject(true)))
            return;

        if (isset($_GET['send_to_axiomus'])){
            //ToDo здесь отправка в axiomus, присвоение кода отслеживания и изменение статуса
            $sendNewAxiomus = new AxiomusXml();
            $oid = $sendNewAxiomus->sendTo((int)$_GET['id_order']);


            $order = new Order((int)$_GET['id_order']);
            $order->setWsShippingNumber($oid); //ToDo а точно ли oid = номер отслеживания?
            $order->shipping_number = $oid;
            $history = new OrderHistory();
            $history->id_order = (int)$order->id;
            $history->changeIdOrderState((int)Configuration::get('RS_AXIOMUS_200_SEND_ORDER_STATUS_ID'), (int)($order->id));
        }
        parent::postProcess();
    }


    public function processSave()
    {
        parent::processSave();
    }

    public function initProcess()
    {
        parent::initProcess();
    }

    protected function processUpdateOptions()
    {
        parent::processUpdateOptions();
    }

    public function renderList($params = null)
    {
        $this->addRowAction('test');
        return parent::renderList();
    }

    public function displayTestLink($token = null, $id, $name = null)
    {
        $tpl = $this->createTemplate('helpers/list/list_action_edit.tpl');
        if (!array_key_exists('Test', self::$cache_lang))
            self::$cache_lang['Test'] = $this->l('Отправить в Axiomus', 'Helper');

        $tpl->assign(array(
           'href' => 'index.php?controller='.$this->controller_name.'&'.$this->identifier.'='.$id.'&send_to_axiomus=true&token='.($token != null ? $token : $this->token),
            'action' => self::$cache_lang['Test'],
            'id' => $id
        ));

        return $tpl->fetch();
    }



//    public function renderList()
//    {
//        $this->tpl_list_vars['postZones'] = array(
//            array(
//                'id_post_zone' => 1,
//                'name' => $this->l('Zone 1'),
//            ),
//            array(
//                'id_post_zone' => 2,
//                'name' => $this->l('Zone 2'),
//            ),
//            array(
//                'id_post_zone' => 3,
//                'name' => $this->l('Zone 3'),
//            ),
//            array(
//                'id_post_zone' => 4,
//                'name' => $this->l('Zone 4'),
//            ),
//            array(
//                'id_post_zone' => 5,
//                'name' => $this->l('Zone 5'),
//            ),
//        );
//
//        return parent::renderList();
//    }

}
