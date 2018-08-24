<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/20/2018
 * Time: 6:19 PM
 */

include_once "user-helper.php";
include_once "form-validation-helper.php";
require_once "reservationDAO.class.php";
require_once "ReservationViewController.class.php";


function gatherReservationFields(&$customerId, &$customerEmailId, &$customerFirstName, &$customerLastName, &$customerCellPhone,
                                 &$reservationName,&$requestDate,&$requestTime,&$guestCount,&$occasionId,&$instructions,&$csrfToken){

    if (!isPost()) return;

    if (!empty($_POST['customer-id']))
        $customerId  = $_POST['customer-id'];
    if (!empty($_POST['customer-email-id']))
        $customerEmailId  = $_POST['customer-email-id'] ;
    if (!empty($_POST['customer-first-name']))
        $customerFirstName = $_POST['customer-first-name'];
    if (!empty($_POST['customer-last-name']))
        $customerLastName  = $_POST['customer-last-name'];
    if (!empty($_POST['customer-cell-phone']))
        $customerCellPhone = $_POST['customer-cell-phone'];
    if (!empty($_POST['reservation-name']))
        $reservationName  = $_POST['reservation-name'];
    if (!empty($_POST['request-date']))
        $requestDate  = $_POST['request-date'] ;
    if (!empty($_POST['request-time']))
        $requestTime = $_POST['request-time'];
    if (!empty($_POST['guest-count']))
        $guestCount  = $_POST['guest-count'];
    if (!empty($_POST['occasion-id']))
        $occasionId = $_POST['occasion-id'];
    if (!empty($_POST['instructions']))
        $instructions = $_POST['instructions'];

    if (!empty($_POST['csrf-token']))
        $csrfToken = $_POST['csrf-token'];
    else
        $csrfToken = '';
}



function gatherReservationDefaults($dbConnection,&$customerId, &$customerEmailId, &$customerFirstName,
                                   &$customerLastName, &$customerCellPhone,&$reservationName,
                                   &$requestDate,&$requestTime,&$guestCount,&$occasionId,
                                   &$instructions,&$csrfToken){

    $customerId = null;
    $customerEmailId = null;
    $customerFirstName = null;
    $customerLastName = null;
    $customerCellPhone = null;
    $reservationName = null;
    $requestDate = null;
    $requestTime= null;
    $guestCount = null;
    $occasionId = null;
    $instructions = null;

    $csrfToken = CSRFTokenGenerator::current()->generateToken() ;

    if (!isSignedIn()) return;

    $user = getSignedinUser($dbConnection);

    if (isset($user)) {
        $customerId          = $user['user_id'];
        $customerEmailId     = $user['email_id'] ;
        $customerFirstName   = $user['first_name'];
        $customerLastName    = $user['last_name'];
        $customerCellPhone   = $user['cell_phone'];
    }
}

function validateReservationFields(){
    $fieldLabels = array('request-date'=> 'Request Date','request-time' => 'Request Time',
                         'customer-first-name'=> 'First Name','customer-last-name'=> 'Last Name',
                         'customer-email-id'=> 'Email Id','customer-cell-phone'=> 'Cell Phone',
                         'guest-count' => 'Guest Count');
    $requiredFields = array('request-date','request-time','guest-count',
                            'customer-email-id','customer-cell-phone','customer-first-name','customer-last-name');
    $fieldLengths = array('customer-email-id'=> 6,'customer-first-name' => 1,'customer-last-name'=> 1,'customer-cell-phone'=> 10);

    $errors = array();

    $errors = array_merge($errors,validateRequiredFields($_POST,$requiredFields,$fieldLabels));
    $errors = array_merge($errors,validateFieldLengths($_POST,$fieldLengths,$fieldLabels));

    if(empty($errors) && !empty($_POST['customer-email-id'])) {
        $errors = array_merge($errors,validateEmailFormat($_POST['customer-email-id']));
    };

    return $errors;
}

function createReservation($dbConnection,$customerId, $customerEmailId, $customerFirstName, $customerLastName,
                           $customerCellPhone,$reservationName,$requestDate,$requestTime,$guestCount,$occasionId,
                           $instructions,$csrfToken,&$reservationUrl,&$messages) {
    $messages = [];
    try {
        CSRFTokenGenerator::current()->validateToken($csrfToken) ;
    } catch(Exception $ex) {
        $messages[] = $ex->getMessage();
    }

    if (empty($messages))
        $messages = validateReservationFields();

    if (empty($messages)) {
        try {
            //create reservation object and update with DAO
            $currDate = date("Y/m/d");
            $resDAO = new ReservationDAO();
            $data = ["reserveDate" => $currDate,
                     "occasionId" =>  $occasionId,
                     "reservationName" => $reservationName,
                     "requestDate"  =>  date("Y-m-d H:i:s", strtotime($requestDate)) ,
                     "requestTime"  => $requestTime,
                     "guestCount"  => $guestCount,
                     "instructions"  => $instructions,
                     "customerId"  => $customerId,
                     "customerFirstName"  => $customerFirstName ,
                     "customerLastName"  => $customerLastName,
                     "customerEmailId"  => $customerEmailId,
                     "customerCellPhone"  =>$customerCellPhone];

            $reservation = new Reservation([]);
            $reservation->reserve($data);
            $reservationId = $resDAO->createReservation($dbConnection,$reservation);

            $rvc = new ReservationViewController($dbConnection,$reservationId);
            $reservationUrl = $rvc->generateCustomerLink();
            $notifyMsg = $rvc->notify();
            if (!empty($notifyMsg))
                $messages[] = $notifyMsg;

            return  $reservationId;

        } catch (Exception $ex) {
            $messages[] = $ex->getMessage();
            return -1;
        }
    }

}

