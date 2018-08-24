<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 2:07 PM
 */

require_once RESOURCE_PATH . "/BaseDAO.class.php";
require_once RESOURCE_PATH . "/OrderHeader.class.php";
require_once RESOURCE_PATH . "/database.php";

class OrderDAO extends BaseDAO
{
    private $entityTableMap = ['OrderHeader' => 'order_header',
                               'OrderDetail' => 'order_detail',
                               'OrderOption' => 'order_option'];

    private $idMap = ['OrderHeader' => 'orderHeaderId',
                      'OrderDetail' => 'orderDetailId',
                      'OrderOption' => 'orderOptionId'];

    //private $calculatedFields = ['OrderHeader' => ['subtotal','tips','salesTax','total']];

    private $calculatedFields = [] ;

    private $orderHeaderMap = [
        'orderHeaderId' => 'order_header_id',
        'orderDate' => 'order_date',
        'orderTypeId' => 'order_type_id',
        'paymentTypeId' => 'payment_type_id',
        'requestDate' => 'request_date',
        'requestTime' => 'request_time',
        'customerId' => 'customer_id',
        'customerFirstName' => 'customer_fname',
        'customerLastName' => 'customer_lname',
        'customerEmailId' => 'customer_email_id',
        'customerCellPhone' => 'customer_cell_phone',
        'instructions' => 'instructions',
        'subTotal' => 'subtotal',
        'tips' => 'tips',
        'salesTax' => 'salestax',
        'total' => 'total'
    ];

    private $orderDetailMap = [
        'orderDetailId' => 'order_detail_id',
        'uniqueId' => 'unique_id',
        'orderHeaderId' => 'order_header_id',
        'menuItemId' => 'menu_item_id',
        'menuItemCode' => 'menu_item_code',
        'qty' => 'qty',
        'price' => 'price',
        'amount' => 'amount',
        'optionsText' => 'options_text',
        'instructions' => 'instructions'
    ];

    private $orderOptionMap = [
        'orderOptionId' => 'order_option_id',
        'orderDetailId' => 'order_detail_id',
        'optionId' => 'option_id',
        'option_choice_id' => 'optionChoiceId',
    ];

    private $fieldMap;

    public function __construct()
    {
        $this->fieldMap = ['OrderHeader' => $this->orderHeaderMap,
                           'OrderDetail' => $this->orderDetailMap,
                           'OrderOption' => $this->orderOptionMap
        ];
        parent::__construct();
    }



    protected function getEntityTableMap() {
        return $this->entityTableMap;
    }

    protected function getIdMap() {
        return $this->idMap;
    }

    protected function getCalculatedFields() {
        return $this->calculatedFields;
    }

    protected function getFieldMap() {
        return $this->fieldMap;
    }

    public function getOrderOptionInsertSQL() {
        $insertOrderOptionSQL = "INSERT INTO order_option
                                     (order_detail_id,option_id,option_choice_id)
                                 VALUES
                                     (:orderDetailId,:optionId,:optionChoiceId)";
        return $insertOrderOptionSQL;
    }

    public function getOrderOptionInsertParameters($orderDetailId,$optionId,$optionChoiceId) {
        return array(
            ':orderDetailId' => $orderDetailId,
            ':optionId' => $optionId,
            ':optionChoiceId' => $optionChoiceId
        );
    }

    private function gatherOrderHeaderFields($row,&$order) {
        $this->gatherEntityFields($row,'OrderHeader',$order);
    }

    private function gatherOrderDetailFields($row,&$orderDetail) {
        $this->gatherEntityFields($row,'OrderDetail',$orderDetail);
    }

    private function gatherOrderOptionFields($row,&$orderOption) {
        if (!empty($row['option_id']) && !empty($row['option_choice_id']))
            $orderOption = ['optionId' => $row['option_id'],'optionChoiceId' => $row['option_choice_id']];
        else
            $orderOption = [];
    }

    private function getOrdersFromSQLRow($rows) {
        $idx = 0;

        $orders = [];

        while($idx <= count($rows) - 1) {

            $orderData = [];
            $orderId = $rows[$idx]['order_header_id'];
            $this->gatherOrderHeaderFields($rows[$idx],$orderData);
            $items = [];
            while (($idx <= count($rows) - 1) && $orderId === $rows[$idx]['order_header_id']) {
                $orderDetailId = $rows[$idx]['order_detail_id'];
                $orderDetailData = [];
                $this->gatherOrderDetailFields($rows[$idx],$orderDetailData);
                $options = [];
                while (($idx <= count($rows) - 1) && ($orderDetailId === $rows[$idx]['order_detail_id'])) {
                    $optionData = [];
                    $this->gatherOrderOptionFields($rows[$idx],$optionData);
                    if (!empty($optionData))
                        $options[] = $optionData;
                    $idx++;
                }
                $orderDetailData['options'] = $options;
                $items[] = $orderDetailData;
            }
            $orderData['items'] = $items;

            $orderHeader = new OrderHeader($orderData);
            $orders[] = $orderHeader;

        }
        return $orders;
    }

    public function getOrder($dbConnection, $orderHeaderId)
    {
        $selectOrderSQL =
           'SELECT h.*, d.*, o.option_id, o.option_choice_id
            FROM order_header h LEFT OUTER JOIN 
                 order_detail d ON h.order_header_id = d.order_header_id LEFT OUTER JOIN 
                order_option o on d.order_detail_id = o.order_detail_id   
             WHERE
                h.order_header_id = :orderHeaderId
                ORDER BY h.order_header_id,d.order_detail_id,option_id DESC 
                ';

        $statement = $dbConnection->prepare($selectOrderSQL);
        $statement->execute(array(':orderHeaderId' => $orderHeaderId));
        $rows = $statement->fetchAll();

        $orders = $this->getOrdersFromSQLRow($rows);
        if (sizeof($orders)  > 0)
            return $orders[0];
        else
            return [];
    }


    private function getSearchSQLParams($searchParams) {

        $params = [];

        if (array_key_exists('customerId',$searchParams) && !empty($searchParams['customerId'])
                                                              && $searchParams['customerId'] != -1) {
            $params['noCustomerIdParam'] = 0;
            $params['customerId'] = $searchParams['customerId'];
        } else {
            $params['noCustomerIdParam'] = 1;
            $params['customerId'] = -1;
        }

        if (array_key_exists('orderId',$searchParams) && !empty($searchParams['orderId'])) {
            $params['noOrderIdParam'] = 0;
            $params['orderId'] = $searchParams['orderId'];
        } else {
            $params['noOrderIdParam'] = 1;
            $params['orderId'] = -1;
        }

        if (array_key_exists('startDate',$searchParams) && !empty($searchParams['startDate'])) {
            $params['noStartDateParam'] = 0;
            $searchParams['startDate'] = trim($searchParams['startDate'],"'");
            $params['startDate'] = date_format(date_create_from_format('Y-m-d',$searchParams['startDate']),"Y-m-d");
        } else {
            $params['noStartDateParam'] = 1;
            $params['startDate'] = 0;
        }

        if (array_key_exists('endDate',$searchParams) &&!empty($searchParams['endDate'])) {
            $params['noEndDateParam'] = 0;
            $searchParams['endDate'] = trim($searchParams['endDate'],"'");
            $params['endDate'] =  date_format(date_create_from_format('Y-m-d',$searchParams['endDate']),"Y-m-d");
        } else {
            $params['noEndDateParam'] = 1;
            $params['endDate'] = 0;
        }

        return $params;
    }

    public function getOrders($dbConnection, $searchParams)
    {
        $params = $this->getSearchSQLParams($searchParams);

        if (array_key_exists('batchSize',$searchParams) && !empty($searchParams['batchSize'])) {
            $limit = $searchParams['batchSize'];
            $offset = $limit * $searchParams['lastPage'];
        } else {
            $limit = 15;
            $offset = 0;
        }

        $selectIDSQL =
            "SELECT  order_header_id FROM order_header oh WHERE
                   (1=:noStartDateParam OR oh.request_date >= :startDate) AND 
                   (1=:noEndDateParam OR oh.request_date <= :endDate) AND
                   (1=:noCustomerIdParam OR IFNULL(oh.customer_id,-1) = :customerId) AND 
                   (1=:noOrderIdParam OR oh.order_header_id = :orderId) 
                    ORDER BY oh.order_header_id DESC LIMIT $offset ,$limit";

        $headerIds = '-1';
        $statement = $dbConnection->prepare($selectIDSQL);

        $statement->execute($params);
        $rows = $statement->fetchAll();

        foreach($rows as $row) {
            $headerIds .= ',' . $row['order_header_id'];
        }

        $selectOrderSQL =
           "SELECT h.*, d.*, o.option_id, o.option_choice_id 
            FROM order_header h LEFT OUTER JOIN 
                 order_detail d ON h.order_header_id = d.order_header_id LEFT OUTER JOIN 
                order_option o on d.order_detail_id = o.order_detail_id   
             WHERE
                h.order_header_id in (
                                  $headerIds
                               )
                ORDER BY h.order_header_id DESC ,d.order_detail_id,option_id 
                ";

        $statement = $dbConnection->prepare($selectOrderSQL);

        $statement->execute();

        $rows = $statement->fetchAll();

        $orders = $this->getOrdersFromSQLRow($rows);

        if (sizeof($orders)  > 0)
            return $orders;
        else
            return [];
    }

    public function createOrder($dbConnection, $orderHeader)
    {
        $insertOrderHeaderSQL = $this->generateInsertSQL('OrderHeader');
        $insertOrderDetailSQL = $this->generateInsertSQL('OrderDetail');
        $insertOrderOptionSQL = $this->getOrderOptionInsertSQL();


        $orderHeaderStatement = $dbConnection->prepare($insertOrderHeaderSQL);
        $orderDetailStatement = $dbConnection->prepare($insertOrderDetailSQL);
        $orderOptionStatement = $dbConnection->prepare($insertOrderOptionSQL);


        $orderHdrParams = $this->getTableInsertParameters('OrderHeader', $orderHeader,[]);
        $orderHeaderStatement->execute($orderHdrParams);


        $orderHeaderID = $dbConnection->lastInsertId();

        $orderDetails = $orderHeader->getItems();

        foreach ($orderDetails as $orderDetail) {
            $orderDetailStatement->execute($this->getTableInsertParameters(
                'OrderDetail',$orderDetail,
                ['orderHeaderId' => $orderHeaderID]));
                $orderDetailID = $dbConnection->lastInsertId();


                $orderOptions = $orderDetail->getOptions();
                foreach ($orderOptions as $orderOption) {
                    $orderOptionStatement->execute(
                        $this->getOrderOptionInsertParameters($orderDetailID,
                                                              $orderOption['optionId'],
                                                              $orderOption['optionChoiceId']));
                }
        }
        return $orderHeaderID;
    }
}

/*
$x = new OrderDAO();
$orderId = $x->createOrder($db,OrderHeader::createNewTestOrder());
echo 'Created Test Order # ' . $orderId . '<br>';

$order  = $x->getOrder($db,$orderId);
echo json_encode($order);

echo '<br>'.'Listing of my orders# ' . '<br>';

$myOrders  = $x->getOrders($db,1);
echo json_encode($myOrders);
*/