<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 7/18/2018
 * Time: 10:43 AM
 */

require_once RESOURCE_PATH."/ValidationException.class.php";
require_once RESOURCE_PATH."/PDFGenerator.class.php";
require_once RESOURCE_PATH."/SendFax.class.php";
require_once RESOURCE_PATH."/SendSMS.class.php";
require_once RESOURCE_PATH."/SendEmail.class.php";
require_once RESOURCE_PATH."/OrderDAO.class.php";
require_once RESOURCE_PATH."/OrderHeader.class.php";
require_once RESOURCE_PATH . "/OrderView.class.php";

class OrderViewController
{
    private $db;
    private $orderId;
    private $order;

    const CUSTOMER_VIEW = 1;
    const RESTAURANT_VIEW = 2;

    public function __construct($db,$orderId){
        $this->db = $db;
        if (empty($orderId))
            $this->orderId = -1;
        else
            $this->orderId = $orderId;
    }

    public function getOrder() {

        if(!empty($this->order)) {
            return $this->order;
        }

        $orderDAO = new OrderDAO();
        $searchParams['orderId'] = $this->orderId;

        $this->order = null;
        $orders = $orderDAO->getOrders($this->db,$searchParams);
        if (sizeof($orders) > 0)
            $this->order = $orders[0];
        else
            throw new ValidationException('Invalid Order Number');

        return $this->order ;
    }

    public function render($mode) {
        try {
            $this->getOrder();
        } catch(Exception $ex) {};

        $orderView = new OrderView($this->order,$mode,$mode == self::CUSTOMER_VIEW ? false : true );

        $orderView->render();
    }

    public function getContents($mode) {
        ob_start();
        $this->render($mode);
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function renderCustomerView() {
        $this->render(self::CUSTOMER_VIEW);
    }

    public function renderRestaurantView() {
        $this->render(self::RESTAURANT_VIEW);
    }


    public function getRestaurantViewContents() {
        ob_start();
        $this->renderRestaurantView();
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function getCustomerViewContents() {
        ob_start();
        $this->renderCustomerView();
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    private function generatePDF($html) {
        $pg = new PDFGenerator();
        return $pg->generate($html);
    }

    public function generateRestaurantOrderLink(){
        return Configuration::instance()->getServerURL("orderview?orderId=$this->orderId&mode=" . self::RESTAURANT_VIEW);
    }

    public function generateCustomerOrderLink(){
        return Configuration::instance()->getServerURL("orderview?orderId=$this->orderId");
    }

    public function notify() {

        $msg = '';
        $em = new ExceptionManager([new LogExceptionHandler()]);

        $em->execute( function($controller) use(&$msg) {
                                              $msg = "Your order was successfully saved but we could NOT send notification to the Restaurant. Please contact restaurant with your Order Number." ;
                                              $controller->notifyRestaurant() ;
                                              $msg = "Your order was successfully saved and sent to the Restaurant but we could NOT notify you with the confirmation email/text." ;
                                              $controller->notifyCustomer() ;
                                              $msg = '';
                                            },[$this,$msg]);
        return $msg;
    }

    public function notifyRestaurant() {

        $html = $this->getRestaurantViewContents();

        //$sf = new SendFax();
        //$sf->sendToRestaurant($this->generatePDF($html));

        $sm = new SendEmail();
        $sm->sendToRestaurant('New Avon Indian Grill Order - #' . $this->orderId,$html,null);

        $sms = new SendSMS();
        $sms->sendToRestaurant('New Avon Indian Grill Order - #' .
                                $this->orderId .
                               '.Please click on the link to see Order Details - ' .
                                createTinyUrl($this->generateRestaurantOrderLink()));
    }




    public function notifyCustomer() {

        $html = $this->getCustomerViewContents();

        $sm = new SendEmail();
        $sm->sendTo($this->order->getCustomerEmailId() ,'Your Avon Indian Grill Order - #' .
                                                                $this->orderId,$html,null);

        $sms = new SendSMS();
        $sms->sendTo($this->order->getCustomerCellPhone(),'Your Avon Indian Grill Order - #' .
                                                          $this->orderId .
                                                          '. Please click on the link to see Order Details - ' .
                                                          createTinyUrl($this->generateCustomerOrderLink()));


    }


    public function notifyCustomerArrival() {
        $this->getOrder();

        $sms = new SendSMS();

        $msg = "Customer " . $this->order->getCustomerFirstName() . " " .
        $this->order->getCustomerLastName() . " has arrived to pickup Order#".
        $this->orderId . " on " .date("F j, Y, g:i a").
        '. Please click on the link to see Order Details - ' .
        $this->generateRestaurantOrderLink();

        $sms->sendToRestaurant($msg);

    }
}