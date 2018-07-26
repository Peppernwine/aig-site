<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 7/5/2018
 * Time: 12:36 PM
 */

require_once "bootstrap.php";
require_once RESOURCE_PATH."/PDFGenerator.class.php";
require_once RESOURCE_PATH . "/OrderViewController.class.php";

$fileName = PUBLIC_TEMP_PATH . '/order.pdf';


$orderId = -1;
if (!empty($_GET['orderId']))
    $orderId = intval($_GET['orderId']);

echo "Notifying Restaurant & Customer of the Order - $orderId";

try {
    $orderViewController = new OrderViewController($db, $orderId, false);
    $html = $orderViewController->notify();

    echo "NOTIFICATION COMPLETE!";
} catch (Exception $ex) {
    echo "NOTIFICATION FAILED WITH ERROR - " . $ex->getMessage();
}
