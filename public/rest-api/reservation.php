<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 5/31/2018
 * Time: 8:31 AM
 */


require_once dirname(__FILE__) . "/../bootstrap.php";
require_once RESOURCE_PATH . "/Reservation.class.php";
require_once RESOURCE_PATH . "/ReservationDAO.class.php";
require_once RESOURCE_PATH . "/session.php";
require_once RESOURCE_PATH . "/database.php";
require_once RESOURCE_PATH . "/user-session.php";


header("Content-Type: application/json;charset=UTF-8");

$request_method=$_SERVER["REQUEST_METHOD"];
switch($request_method)
{
    case 'GET':
        $response = null;
        $reservationDAO = new ReservationDAO();

        $searchParams = [];

        $headers = apache_request_headers();

        if (!empty($headers['paginationToken'])) {
            $paginationState = $reservationDAO->getPaginationState($headers['paginationToken']);
            $searchParams = $paginationState->getSearchParams();
        } else {
            if (isSignedIn()) {
                if (!hasStaffAccess())
                    $searchParams['customerId'] = getSignedInUserId();
            } else
                $searchParams['customerId'] = -1;

            $searchParams['reservationId'] = null;
            if (isset($_GET['reservationId']))
                $searchParams['reservationId'] = $_GET['reservationId'];

            $searchParams['startDate'] = null;
            if (isset($_GET['startDate']))
                $searchParams['startDate'] = $_GET['startDate'];

            $searchParams['endDate'] = null;
            if (isset($_GET['endDate']))
                $searchParams['endDate'] = $_GET['endDate'];

            $paginationState = $reservationDAO->getNewPaginationState($searchParams);
        }

        $lastPage = $paginationState->getLastPage();
        $searchParams['lastPage']  = $lastPage;
        $searchParams['batchSize'] = $paginationState->getBatchSize();

        $response = $reservationDAO->getReservations($db,$searchParams);

        if (sizeof($response) == $paginationState->getBatchSize()) {
            header("paginationToken: ". $paginationState->getToken());
            header("paginationPage: ". $lastPage);
        }

        echo json_encode($response);

        $paginationState->setLastPage($lastPage + 1);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}