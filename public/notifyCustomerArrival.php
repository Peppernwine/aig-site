<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/21/2018
 * Time: 8:26 PM
 */
require_once "bootstrap.php";
require_once RESOURCE_PATH . '/OrderViewController.class.php';

$orderId = -1;

if (!empty($_GET['orderId'])) {
    $orderId = $_GET['orderId'] ;
}
$msg = '';
$type = ERROR_MESSAGE;

try {
    $ovc = new OrderViewController($db, $orderId);
    $ovc->notifyCustomerArrival();
    $type = INFORMATION_MESSAGE;
    $msg = "Thank you for your message. Front office has been notified of your arrival";
} catch (Exception $ex) {
    $newEx = $ex;
    $em = new ExceptionManager([new LogExceptionHandler()]);
    $em->handleException($ex,$newEx);
    $msg = $newEx->getMessage();
    $type = ERROR_MESSAGE;
}

displayInfoPage($type,$msg);
