<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 5/31/2018
 * Time: 8:31 AM
 */


require_once dirname(__FILE__) . "/../bootstrap.php";
require_once RESOURCE_PATH . "/OrderHeader.class.php";
require_once RESOURCE_PATH . "/session.php";
require_once RESOURCE_PATH . "/database.php";
require_once RESOURCE_PATH . "/user-session.php";
require_once RESOURCE_PATH . "/OrderBagSession.class.php";
require_once RESOURCE_PATH . "/OrderViewController.class.php";
require_once RESOURCE_PATH . "/ExceptionManager.class.php";
require_once RESOURCE_PATH . "/LogExceptionHandler.class.php";
require_once RESOURCE_PATH . "/ServerExceptionHandler.class.php";
require_once RESOURCE_PATH . "/RESTExceptionResponseGenerator.class.php";

/*
shoppingbag
        --POST add item to bag
        --GET return bag items
        --DELETE = clear

*/

function addItem($currentOrder,$item) {
    $newOrderItem = $currentOrder->addItem($item);
    $response = ['item' => $newOrderItem, 'order_summary' => ['count' => count($currentOrder->getItems()),
        'subTotal' => $currentOrder->getSubTotal(),
        'salesTax' => $currentOrder->getSalesTax(),
        'tips' => $currentOrder->getTips(),
        'total' => $currentOrder->getTotal()]];

    return $response;
}

function reOrder($db,$currentOrder,$oldOrderId) {
    $ovc = new OrderViewController($db,$oldOrderId);
    $oldOrder = $ovc->getOrder();

    OrderBagSession::reOrder($oldOrder);

    $response = ['order_summary' => ['subTotal'=>$currentOrder->getSubTotal(),
        'salesTax'=>$currentOrder->getSalesTax(),
        'tips'=>$currentOrder->getTips(),
        'tips'=>$currentOrder->getTotal()]];

    return $response;
}

$response = '';
$request_method=$_SERVER["REQUEST_METHOD"];
try {

    switch($request_method) {
        case 'GET':
            $currentOrder = OrderBagSession::getCurrentOrder();

            $response = ['items' => $currentOrder->getItems() , 'order_summary' => ['count' => count($currentOrder->getItems()),
                'subTotal'=>$currentOrder->getSubTotal(),
                'salesTax'=>$currentOrder->getSalesTax(),
                'tips'=>$currentOrder->getTips(),
                'total'=>$currentOrder->getTotal()]];

            $response['status'] = 200;

            break;

        case 'POST':
            $currentOrder = OrderBagSession::getCurrentOrder();

            $dataJSON = file_get_contents("php://input");
            $orderData = json_decode($dataJSON,true);

            if (empty($orderData['orderHeaderId']))
                $response = addItem($currentOrder,$orderData);
            else {
                $response = reOrder($db,$currentOrder,$orderData['orderHeaderId']);
            }
            $response['status'] = 200;

            break;
        case 'DELETE':
            // CLEAR BAG...

            $currentOrder = OrderBagSession::getCurrentOrder();
            $currentOrder->clear();
            $response = ['order_summary' => ['subTotal'=>$currentOrder->getSubTotal(),
                'salesTax'=>$currentOrder->getSalesTax(),
                'tips'=>$currentOrder->getTips(),
                'tips'=>$currentOrder->getTotal()]];

            $response['status'] = 200;

            break;
        default:
            // Invalid Request Method
            throw new MethodNotAllowedException();
    }


} catch (Exception $e) {
    $newEx = $e;
    $em = new ExceptionManager([new LogExceptionHandler(), new ServerExceptionHandler()]);
    $em->handleException($e,$newEx);
    $responseGenerator = new RESTExceptionResponseGenerator();
    $response = $responseGenerator->getResponse($newEx);
}

header("Content-Type: application/json;charset=UTF-8");
http_response_code($response['status']);
echo json_encode($response);
