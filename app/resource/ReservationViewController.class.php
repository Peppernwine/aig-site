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
require_once RESOURCE_PATH."/ReservationDAO.class.php";
require_once RESOURCE_PATH."/Reservation.class.php";
require_once RESOURCE_PATH ."/ReservationView.class.php";
require_once RESOURCE_PATH."/http-helper.php";

class ReservationViewController
{
    private $db;
    private $reservationId;
    private $reservation;
    private $order;
    private $occasion;

    const CUSTOMER_VIEW = 1;
    const RESTAURANT_VIEW = 2;

    public function __construct($db,$reservationId){
        $this->db = $db;
        if (empty($reservationId))
            $this->reservationId = -1;
        else
            $this->reservationId = $reservationId;
    }

    public function getReservation() {

        if(!empty($this->reservation)) {
            return $this->reservation;
        }

        $reservationDAO = new ReservationDAO();
        $searchParams['reservationId'] = $this->reservationId;

        $this->reservation = null;
        $reservations = $reservationDAO->getReservations($this->db,$searchParams);

        if (sizeof($reservations) > 0)
            $this->reservation = $reservations[0];
        else
            throw new ValidationException('Invalid Reservation Number');

        return $this->reservation ;
    }

    public function getOccasion() {

        if(!empty($this->occasion)) {
            return $this->occasion;
        }

        $this->getReservation();

        $this->occasion = null;
        if (!empty($reservation) && !empty($reservation->getOccasionId())) {
            $reserveOccasionDAO =  new ReserveOccasionDAO();
            $this->occasion = $reserveOccasionDAO->getReserveOccasion($this->db,$reservation->getOccasionId());
        }
    }

    public function getOrder() {

        $this->getReservation();

        if(!empty($this->order)) {
            return $this->order;
        }

        $this->order = null;

        if (empty($this->reservation) || empty($this->reservation->getOrderHeaderId())) {
            return null;
        }

        $orderDAO = new OrderDAO();
        $searchParams['orderId'] = $this->reservation->getOrderHeaderId();


        $orders = $orderDAO->getOrders($this->db,$searchParams);
        if (sizeof($orders) > 0)
            $this->order = $orders[0];
        else
            throw new ValidationException('Invalid Order Number');

        return $this->order ;
    }


    public function render($mode) {
        try {
            $this->getReservation();
        } catch(Exception $ex) {};


        try {
            $this->getOccasion();
        } catch(Exception $ex) {};


        try {
            $this->getOrder();
        } catch(Exception $ex) {};


        $reservationView = new ReservationView($this->reservation,$this->occasion,$this->order,$mode,$mode == self::CUSTOMER_VIEW ? false : true );

        $reservationView->render();
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

    public function generateRestaurantLink(){
        return Configuration::instance()->getServerURL("reservationview?reservationId=$this->reservationId&mode=" . self::RESTAURANT_VIEW);
    }

    public function generateCustomerLink(){
        return Configuration::instance()->getServerURL("reservationview?reservationId=$this->reservationId");
    }

    public function notify() {

        $msg = null;
        $em = new ExceptionManager([new LogExceptionHandler()]);

        $em->execute( function($controller) use(&$msg) {
                                              $msg = "Your Reservation was successfully saved but we could NOT send notification to the Restaurant. Please contact restaurant with your Reservation Number." ;
                                              $controller->notifyRestaurant() ;
                                              $msg = "Your Reservation was successfully saved and sent to the Restaurant but we could NOT notify you with the confirmation email/text." ;
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
        $sm->sendToRestaurant('New Avon Indian Grill Reservation - #' . $this->reservationId,$html,null);

        $sms = new SendSMS();
        $sms->sendToRestaurant('New Avon Indian Grill Reservation - #' .
                                $this->reservationId .
                               '.Please click on the link to see Reservation Details - ' .
                               createTinyUrl($this->generateRestaurantLink()));
    }

    public function notifyCustomer() {

        $html = $this->getCustomerViewContents();

        $sm = new SendEmail();
        $sm->sendTo($this->reservation->getCustomerEmailId() ,'Your Avon Indian Grill Reservation - #' .
                                                                $this->reservationId,$html,null);

        $sms = new SendSMS();
        $sms->sendTo($this->reservation->getCustomerCellPhone(),'Your Avon Indian Grill Reservation - #' .
                                                          $this->reservationId .
                                                          '. Please click on the link to see Reservation Details - ' .
                                                          createTinyUrl($this->generateCustomerLink()));
    }


}