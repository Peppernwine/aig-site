<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 5/30/2018
 * Time: 4:56 PM
 */

require_once RESOURCE_PATH . "/MandatoryFieldValidation.php";
require_once RESOURCE_PATH . "/ValidationException.class.php";


class OrderDetail implements \JsonSerializable{
    private $order;
    private $orderDetailId;
    private $uniqueId;
    private $menuItemId;
    private $menuItemCode;
    private $price;
    private $qty;
    private $instructions;
    private $options;
    private $optionsText;

    public function getOrderHeaderId () {
        return $this->order->getOrderHeaderId();
    }

    public function clearOrderDetailId () {
        $this->orderDetailId = null;
    }

    public function getOrderDetailId () {
        return $this->orderDetailId;
    }

    public function setNewUniqueId() {
        $this->uniqueId = uniqid();
    }

    public function getUniqueId () {
        return $this->uniqueId;
    }

    public function getMenuItemId (){
        return $this->menuItemId ;
    }

    public function getMenuItemCode (){
        return $this->menuItemCode ;
    }

    public function getPrice (){
        return $this->price ;
    }

    public function getDisplayPrice (){
        return '$' . number_format($this->getPrice(),2) ;
    }


    public function getQty (){
        return $this->qty ;
    }

    public function getInstructions(){
        return $this->instructions ;
    }

    public function &getOptions(){
        return $this->options ;
    }

    public function setOptions($options) {
        $this->options = [];
        foreach ($options as $option) {
            $option["optionId"] = intval($option["optionId"]);
            $option["optionChoiceId"] = intval($option["optionChoiceId"]);
            $this->options[] = $option;
        }
    }

    public function getOptionsText(){
        return $this->optionsText ;
    }

    public function getAmount(){
        return round($this->price * $this->qty,2);
    }

    public function getDisplayAmount (){
        return '$' . number_format($this->getAmount(),2) ;
    }

    function __construct($order,$data){
        $this->order = $order;
        if (array_key_exists('orderDetailId',$data))
            $this->orderDetailId = intval($data['orderDetailId']);

        if (array_key_exists('uniqueId',$data))
            $this->uniqueId = $data['uniqueId'];
        $this->menuItemId = intval($data['menuItemId']);
        $this->menuItemCode = $data['menuItemCode'];
        $this->price = floatval($data['price']);
        $this->qty = intval($data['qty']);
        if (array_key_exists('instructions',$data))
            $this->instructions = $data['instructions'];
        if (array_key_exists('options',$data))
            $this->setOptions($data['options']);

        if (array_key_exists('optionsText',$data))
            $this->optionsText = $data['optionsText'];
    }

    public static function createNewOrderDetail($order) {
        $testData = ['orderDetailId' => -1,'uniqueId'  => uniqid(),'menuItemId'  => 0,
                     'price'  =>  0,'qty'  =>  0,'instructions'  => '','options'  =>  [],
                     'optionsText' => 'Mild/Chicken' ];
        $newOrderDetail = new OrderDetail($order,$testData);
        return $newOrderDetail;
    }

    function copyFrom($sourceData)
    {
        if (array_key_exists('orderDetailId',$sourceData))
            $this->orderDetailId = $sourceData['orderDetailId'];

        if (array_key_exists('uniqueId',$sourceData))
           $this->uniqueId = $sourceData['uniqueId'];
        $this->menuItemId = $sourceData['menuItemId'];
        $this->menuItemCode = $sourceData['menuItemCode'];
        $this->price = $sourceData['price'];
        $this->qty = $sourceData['qty'];

        if (array_key_exists('instructions',$sourceData))
            $this->instructions = $sourceData['instructions'];

        if (array_key_exists('options',$sourceData))
            $this->setOptions($sourceData['options']);

        if (array_key_exists('optionsText',$sourceData))
            $this->optionsText = $sourceData['optionsText'];
    }

    public function getData() {
        $data =
            ['orderDetailId' => $this->orderDetailId,
            'uniqueId' => $this->uniqueId,
            'menuItemId' => $this->menuItemId,
            'menuItemCode' => $this->menuItemCode,
            'price' => $this->price,
            'qty' => $this->qty,
            'amount' => $this->getAmount(),
            'instructions'=> $this->instructions ,
            'options' => $this->options,
            'optionsText' => $this->optionsText
            ];

        return $data;
    }

    public function jsonSerialize(){
        return $this->getData();
    }

}


class OrderHeader implements \JsonSerializable
{
    const SALES_TAX_PC = 6.35;

    private $orderHeaderId;
    private $orderDate;
    private $orderTypeId;
    private $paymentTypeId;
    private $customerId;
    private $customerFirstName;
    private $customerLastName;
    private $customerEmailId;
    private $customerCellPhone;
    private $requestDate;
    private $requestTime;
    private $instructions;

    private $items = [];

    private $subTotal = 0;
    private $tips = 0;
    private $total = 0;
    private $salesTax = 0;

    public function __construct($data){
        if (!empty($data)) {
            if (array_key_exists('orderHeaderId',$data))
                $this->orderHeaderId = intval($data['orderHeaderId']);

            $this->orderDate = $data['orderDate'];
            $this->orderTypeId = intval($data['orderTypeId']);
            $this->paymentTypeId = intval($data['paymentTypeId']);
            $this->customerId = intval($data['customerId']);
            $this->customerFirstName = $data['customerFirstName'];
            $this->customerLastName = $data['customerLastName'];
            $this->customerEmailId = $data['customerEmailId'];
            $this->customerCellPhone = $data['customerCellPhone'];
            $this->requestDate = $data['requestDate'];
            $this->requestTime = $data['requestTime'];
            if (array_key_exists('instructions',$data))
                $this->instructions = $data['instructions'];

            $this->subTotal  = $data['subTotal'];
            $this->salesTax  = $data['salesTax'];
            $this->tips  = $data['tips'];
            $this->total  = $data['total'];

            $this->items = [];
            if (isset($data['items'])) {
                foreach ($data['items'] as $orderDetailData) {
                    $this->addItem($orderDetailData);
                }
            }
        }
        $this->calculate();
    }

        public static function createNewTestOrder(){

        $itemUniqueId1= uniqid();
        $itemUniqueId2= uniqid();

        $testData = '{
                            "orderHeaderId":999,"orderDate":"2018-1-1","orderTypeId":1,"paymentTypeId":1,
                            "customerId":1,"customerFirstName":"John","customerLastName":"Doe",
                            "customerEmailId":"john.doeglobalnet.com","customerCellPhone":"111-111-1111",
                            "requestDate":"2018-1-1","requestTime":"11:50 AM","instructions": "ASAP/Mild/no nuts",
                            "items" :
                                [
                                 {
                                    "orderDetailId":1,"uniqueId":"'.$itemUniqueId1.'","menuItemId":1,"menuItemName":"Samosa",
                                    "price":10.45,"qty":10,"amount":104.5,"instructions":"spicy/no nuts","optionsText":"mild/chicken",
                                    "options" : [{"optionId":1,"optionChoiceId":1},{"optionId":2,"optionChoiceId":2}]
                                 },
                                 {
                                    "orderDetailId":2,"uniqueId":"'.$itemUniqueId2.'","menuItemId":2,"menuItemName":"Chicken Tikka Masala",
                                    "price":10,"qty":12,"amount":120,"instructions":"spicy/no nuts","optionsText":"Hot/Lamb",
                                    "options" : [{"optionId":3,"optionChoiceId":9},{"optionId":12,"optionChoiceId":42}]
                                  }
                                ]
                      }';

        $testData = json_decode($testData,true);
        return new OrderHeader($testData);
    }

    public function getData() {
        $data = ['orderHeaderId' => $this->orderHeaderId,
            'orderDate' => $this->orderDate,
            'orderTypeId' => $this->orderTypeId,
            'paymentTypeId' => $this->paymentTypeId,
            'customerId' => $this->customerId,
            'customerFirstName' => $this->customerFirstName,
            'customerLastName' => $this->customerLastName,
            'customerEmailId' => $this->customerEmailId,
            'customerCellPhone' => $this->customerCellPhone,
            'requestDate' => $this->requestDate,
            'requestTime' => $this->requestTime,
            'instructions' => $this->instructions,
            'subTotal'  => $this->subTotal,
            'salesTax'  => $this->salesTax,
            'tips'  => $this->tips,
            'total'  => $this->total
        ];

        $itemsData = [];
        foreach($this->items as $item) {
            $itemsData[] = $item->getData();
        }
        $data['items'] = $itemsData;

        return $data;
    }

    public function jsonSerialize(){
        return $this->getData();
    }

    function __clone() {
        foreach($this as $key => $val) {
            if(is_object($val)||(is_array($val))){
                $this->{$key} = unserialize(serialize($val));
            }
        }

        $this->orderHeaderId = null;
        $this->orderDate = null;

        foreach ($this->items as $key => $value) {
            $value->clearOrderDetailId();
            $value->setNewUniqueId();
            $this->items[$value->getUniqueId()] = $value;
            unset($this->items[$key]);
        }


        $this->calculate();

    }

    public function getOrderHeaderId(){
        return $this->orderHeaderId;
    }

    public function getDisplayOrderHeaderId(){
        return '#' . $this->orderHeaderId;
    }

    public function getOrderDate(){
        return $this->orderDate;
    }

    public function getOrderTypeId(){
        return $this->orderTypeId;
    }

    public function getDeliveryLabel(){
        if ($this->orderTypeId == 1)
            return 'Pickup Time';
        else
            return 'Express Dine-in Time';
    }

    public function getDisplayOrderType(){
        if ($this->orderTypeId == 1)
            return 'Pickup';
        else
            return 'Express Dine-in';
    }

    public function getPaymentTypeId(){
        return $this->paymentTypeId;
    }

    public function getDisplayPaymentType(){
        if ($this->paymentTypeId == 1)
            return 'Pay at Restaurant';
        else
            return 'Paid by Credit Card';
    }

    public function getCustomerId(){
        return $this->customerId;
    }

    public function getCustomerFirstName(){
        return $this->customerFirstName;
    }

    public function getCustomerLastName(){
        return $this->customerLastName;
    }

    public function getDisplayCustomerName(){
        return $this->customerLastName . ',' . $this->customerFirstName;
    }

    public function getCustomerEmailId(){
        return $this->customerEmailId;
    }

    public function getCustomerCellPhone(){
        return $this->customerCellPhone;
    }

    public function getRequestDate(){
        return $this->requestDate;
    }

    public function getDisplayDeliveryDateTime(){
        $today = new DateTime('00:00');
        $requestDate = new DateTime($this->requestDate);
        if ($today === $this->requestDate)
            return 'Today,'.$this->requestTime;
        else
            return date_format($requestDate,"m/d/Y") . ','.$this->requestTime;
    }

    public function getRequestTime(){
        return $this->requestTime;
    }


    public function getInstructions(){
        return $this->instructions;
    }

    public function setTips($tips) {
        $this->tips = $tips;
    }

    public function getTips(){
        return $this->tips;
    }

    public function getDisplayTips(){
        return '$' . number_format($this->tips,2) ;
    }

    public function getSubTotal(){
        return $this->subTotal;
    }

    public function getDisplaySubTotal(){
        return '$' . number_format($this->subTotal,2) ;
    }

    public function getSalesTax(){
        return $this->salesTax;
    }

    public function getDisplaySalesTax(){
        return '$' . number_format($this->salesTax,2) ;
    }

    public function getTotal(){
        return $this->total;
    }

    public function getDisplayTotal(){
        return '$' . number_format($this->total,2) ;
    }

    public function getItems() {
        return array_values($this->items);
    }

    public function newItem() {
        return OrderDetail::createNewOrderDetail($this);
    }

    private function checkDuplicate($itemData) {
        $uniqueId = $itemData['uniqueId'];
        $item = $this->finditem($itemData['uniqueId']);
        if (isset($item))
            throw new ValidationException("Cannot add item,item with UniqueID - $uniqueId already exists");
    }

    public function addItem($itemData) {
        if (!array_key_exists('uniqueId',$itemData))
            $itemData['uniqueId'] = uniqid();

        $val = new MandatoryFieldValidation(['uniqueId' => 'Unique Id','menuItemId' => 'Menu Item',
                                             'menuItemCode' => 'Menu Item Code',
                                             'price' => 'Price','qty' => 'Quantity','amount' => 'Amount'],
                                             $itemData);
        $val->validate();

        $this->checkDuplicate($itemData);

        if (!empty($itemData['options']) && empty($itemData['optionsText']) ) {
            throw new ValidationException('Options Text cannot be empty if the Order Detail has options specified');

        }
        $item = new OrderDetail($this,$itemData);
        $this->items[$item->getUniqueId()] = $item;
        $this->calculate();
        return $item;
    }

    public function findItem($itemUniqueId) {
        if (array_key_exists($itemUniqueId,$this->items))
            return $this->items[$itemUniqueId];
    }


    private function validateItemByUniqueId($uniqueId) {
        $item = $this->finditem($uniqueId);
        if (!isset($item))
            throw new ValidationException("Invalid Item-could not located Order Detail with Unique ID : $uniqueId");
        return $item;
    }

    private function validateItemByItemData($itemData) {
        $item = null;
        if (array_key_exists('uniqueId',$itemData))
            $item = $this->validateItemByUniqueId($itemData['uniqueId']);
        else
            throw new ValidationException("Could not locate Order Detai - Invalid Order");

        return $item;
    }

    public function updateItem($itemData) {
        $item = $this->validateItemByItemData($itemData);
        $item->copyFrom($itemData);
        $this->calculate();
        return $item;
    }

    public function deleteItem($itemUniqueId) {
        $this->validateItemByUniqueId($itemUniqueId);
        unset($this->items[$itemUniqueId]);
        $this->calculate();
    }

    public function clear() {
        $this->setTips(0);
        unset($this->items);
        $this->items = [];
        $this->calculate();
    }

    private function setCheckoutInfo($data) {

        $val = new MandatoryFieldValidation(['orderTypeId' => 'Order Type','paymentTypeId' => 'Payment Type',
                                      'requestDate' => 'Request Date','requestTime' => 'Request Time',
                                      'customerFirstName' => 'First Name','customerLastName' => 'Last Name',
                                      'customerEmailId' => 'Email Id','customerCellPhone' => 'Cell Phone'],
                                       $data);
        $val->validate();


        if (array_key_exists('orderDate',$data) && !empty($data['orderDate']))
            $this->orderDate = $data['orderDate'];
        else
            $this->orderDate = date("Y-m-d");

        $this->orderTypeId = $data['orderTypeId'];
        $this->tips = $data['tips'];
        $this->paymentTypeId = $data['paymentTypeId'];
        $this->requestDate = $data['requestDate'];
        $this->requestTime = $data['requestTime'];
        if (array_key_exists('customerId',$data))
            $this->customerId = $data['customerId'];
        $this->customerFirstName = $data['customerFirstName'];
        $this->customerLastName = $data['customerLastName'];
        $this->customerEmailId = $data['customerEmailId'];
        $this->customerCellPhone = $data['customerCellPhone'];
        if (array_key_exists('instructions',$data))
            $this->instructions = $data['instructions'];
    }

    public function checkOut($checkoutInfo) {
        if (sizeof($this->items) === 0)
            throw new ValidationException("Bag is empty, cannot checkout");
        $this->setCheckoutInfo($checkoutInfo);

        $this->calculate();
    }

    public function calculate() {
        $this->subTotal = 0;
        $this->salesTax  = 0;
        $this->total = 0;
        foreach ($this->items as $key => $value) {
            $this->subTotal += $value->getAmount();
        }
        $this->salesTax  = round($this->subTotal * (self::SALES_TAX_PC / 100),2);
        $this->total = $this->subTotal + $this->salesTax + $this->tips;
    }
}