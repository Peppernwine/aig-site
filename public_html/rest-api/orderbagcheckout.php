<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/17/2018
 * Time: 5:35 PM
 */

require_once dirname(__FILE__) . "/../bootstrap.php";
require_once RESOURCE_PATH . "/OrderHeader.class.php";
require_once RESOURCE_PATH . "/Reservation.class.php";
require_once RESOURCE_PATH . "/OrderDAO.class.php";
require_once RESOURCE_PATH . "/ReservationDAO.class.php";
require_once RESOURCE_PATH . "/session.php";
require_once RESOURCE_PATH . "/database.php";
require_once RESOURCE_PATH . "/user-session.php";
require_once RESOURCE_PATH . "/OrderBagSession.class.php";
require_once RESOURCE_PATH . "/StripePayment.class.php";
require_once RESOURCE_PATH . "/OrderViewController.class.php";
require_once RESOURCE_PATH . "/MethodNotAllowedException.class.php";
require_once RESOURCE_PATH . "/ExceptionManager.class.php";
require_once RESOURCE_PATH . "/LogExceptionHandler.class.php";
require_once RESOURCE_PATH . "/ServerExceptionHandler.class.php";
require_once RESOURCE_PATH . "/StripeExceptionHandler.class.php";
require_once RESOURCE_PATH . "/RESTExceptionResponseGenerator.class.php";

$response = '';
$request_method=$_SERVER["REQUEST_METHOD"];
try {
    switch($request_method) {
        case 'GET':

            $checkoutInfo = null;
            if (isSignedIn()) {
                $userInfo = getSignedinUserInfo($db);
                $checkoutInfo['customerId'] = $userInfo['userId'];
                $checkoutInfo['customerFirstName'] = $userInfo['firstName'];
                $checkoutInfo['customerLastName'] = $userInfo['lastName'];
                $checkoutInfo['customerEmailId'] = $userInfo['emailId'];
                $checkoutInfo['customerCellPhone'] = $userInfo['cellPhone'];
            }

            $response['checkoutDefaults'] = $checkoutInfo;
            $response['status'] = 200;

            break;
        case 'POST':

            $currentOrder = OrderBagSession::getCurrentOrder();
            $data = json_decode(file_get_contents("php://input"), true);

            if (isSignedIn()) {
                $userInfo = getSignedinUserInfo($db);
                if (!empty($userInfo))
                    $data['customerId'] = $userInfo['userId'];

            }
            $currentOrder->checkOut($data);

            $db->beginTransaction();
            try {
                $orderDAO = new OrderDAO();
                $orderId = $orderDAO->createOrder($db, $currentOrder);

                if ($currentOrder->getOrderTypeId() == 3) {
                    $reservation = new Reservation([]);
                    $data['orderHeaderId'] = $currentOrder->getOrderHeaderId();
                    $reservation->reserve($data);
                    $reservationDAO = new ReservationDAO();
                    $reservationDAO->createReservation($db, $reservation);
                }

                $currentOrder->clear();
                OrderBagSession::clearCurrentOrder();

                $order = $orderDAO->getOrder($db, $orderId);

                $response['order'] = $order;
                $response['status'] = 201;

                $db->commit();
            } catch (Exception $e) {
                $db->rollback();
                throw $e;
            }

            try {
                if ($currentOrder->getPaymentTypeId() == 2) {
                    $stripePayment = new StripePayment();

                    $stripeCharge = $stripePayment->charge($data['cctoken'],$order->getTotal(),'USD',
                        'Avon Indian Grill Charge','Avon Indian Grill',
                        'Order ID',$order->getOrderHeaderId());
                }

                $ovc = new OrderViewController($db,$orderId);
                $msg = $ovc->notify();
            } catch(Exception $ex) {
                $newEx = $e;
                $em = new ExceptionManager([new LogExceptionHandler(), new StripeExceptionHandler(), new ServerExceptionHandler()]);
                $em->handleException($e,$newEx);
                $msg = $newEx->getMessage();
            }

            $response['location'] = $ovc->generateCustomerOrderLink();
            $response['message'] = $msg;
            break;
        default:
            // Invalid Request Method
            throw new MethodNotAllowedException();
    }

} catch (Exception $e) {
    $newEx = $e;
    $em = new ExceptionManager([new LogExceptionHandler(), new StripeExceptionHandler(), new ServerExceptionHandler()]);
    $em->handleException($e,$newEx);
    $responseGenerator = new RESTExceptionResponseGenerator();
    $response = $responseGenerator->getResponse($newEx);
}

header("Content-Type: application/json;charset=UTF-8");
http_response_code($response['status']);
echo json_encode($response);