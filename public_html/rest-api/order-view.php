<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/28/2018
 * Time: 8:04 PM
 */

require_once dirname(__FILE__) . "/../bootstrap.php";
require_once RESOURCE_PATH . "/OrderViewController.class.php";

$orderId = -1;
if (!empty($_GET['orderId']))
    $orderId = intval($_GET['orderId']);

(new OrderViewController($db,$orderId,OrderViewController::CUSTOMER_VIEW,false))->render();