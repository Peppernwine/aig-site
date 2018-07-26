
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

echo "generating test PDF - $fileName...";

$orderId = -1;
if (!empty($_GET['orderId']))
    $orderId = intval($_GET['orderId']);

$orderViewController = new OrderViewController($db,$orderId,false);
$html = $orderViewController->getCustomerViewContents();

$ch = curl_init();

(new PDFGenerator())->generate($html,$fileName);

echo "COMPLETE!";
