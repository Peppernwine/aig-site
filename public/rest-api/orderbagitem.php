<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/17/2018
 * Time: 5:35 PM
 */

require_once dirname(__FILE__) . "/../bootstrap.php";
require_once RESOURCE_PATH . "/OrderHeader.class.php";
require_once RESOURCE_PATH . "/session.php";
require_once RESOURCE_PATH . "/database.php";
require_once RESOURCE_PATH . "/user-session.php";
require_once RESOURCE_PATH . "/OrderBagSession.class.php";

/*

shoppingbag/123/
        --GET get item from Bag
        --PUT update item in Bag
        --DELETE delete ite from Bag

*/

header("Content-Type: application/json;charset=UTF-8");

$request_method=$_SERVER["REQUEST_METHOD"];
switch($request_method)
{
    case 'GET':
        $currentOrder = OrderBagSession::getCurrentOrder();
        if(!empty($_GET["uniqueId"])){
            $uniqueId = $_GET["uniqueId"];
            if ($_GET["uniqueId"] === 'new')
                echo json_encode($currentOrder->newItem());
            else
                echo json_encode($currentOrder->findItem($uniqueId));
        }
        break;

    case 'PUT':
        // Update Product
        $currentOrder = OrderBagSession::getCurrentOrder();

        $orderItemData = json_decode(file_get_contents("php://input"),true);

        $orderDetail = $currentOrder->updateItem($orderItemData);

        $response = ['item' => $orderDetail , 'order_summary' => ['count' => count($currentOrder->getItems()),
            'subTotal'=>$currentOrder->getSubTotal(),
            'salesTax'=>$currentOrder->getSalesTax(),
            'tips'=>$currentOrder->getTips(),
            'total'=>$currentOrder->getTotal()]];

        echo json_encode($response);
        break;
    case 'DELETE':
        // Delete Product

        $currentOrder = OrderBagSession::getCurrentOrder();
        if(!empty($_GET["uniqueId"])) {
            $uniqueId = $_GET["uniqueId"];
            $currentOrder->deleteItem($uniqueId);
        }

        $response = ['order_summary' => ['subTotal'=>$currentOrder->getSubTotal(),
            'salesTax'=>$currentOrder->getSalesTax(),
            'tips'=>$currentOrder->getTips(),
            'tips'=>$currentOrder->getTotal()]];

        echo json_encode($response);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}