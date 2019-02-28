<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 5/31/2018
 * Time: 8:31 AM
 */


require_once dirname(__FILE__) . "/../bootstrap.php";
require_once RESOURCE_PATH . "/CouponDistributionDAO.class.php";
require_once RESOURCE_PATH . "/session.php";
require_once RESOURCE_PATH . "/database.php";
require_once RESOURCE_PATH . "/user-session.php";
require_once RESOURCE_PATH . "/user-helper.php";


header("Content-Type: application/json;charset=UTF-8");

$request_method=$_SERVER["REQUEST_METHOD"];
switch($request_method)
{
    case 'GET':
        $searchParams = [];

        $response = null;
        $couponDAO = new CouponDistributionDAO();

        $headers = apache_request_headers();

        if (!empty($headers['paginationToken'])) {
            $paginationState = $couponDAO->getPaginationState($headers['paginationToken']);
            $searchParams = $paginationState->getSearchParams();
        } else {
            if (isset($_GET['startDate']))
                $searchParams['startDate'] = $_GET['startDate'];
            else
                $searchParams['startDate'] = null;

            if (isset($_GET['expirationDate']))
                $searchParams['expirationDate'] = $_GET['expirationDate'];
            else
                $searchParams['expirationDate'] = null;

            if (isSignedIn()) {
                if (!hasStaffAccess()) {
                    $now = new DateTime('NOW');
                    $searchParams['customerId'] = getSignedInUserId();
                    $searchParams['startDate'] = $now->format("Y-m-d");
                    $searchParams['expirationDate'] = $now->format("Y-m-d");
                }
            } else
                $searchParams['customerId'] = -1;

            $paginationState = $couponDAO->getNewPaginationState($searchParams);
        }

        $lastPage = $paginationState->getLastPage();
        $searchParams['lastPage']  = $lastPage;
        $searchParams['batchSize'] = $paginationState->getBatchSize();

        $response = $couponDAO->getCoupons($db,$searchParams);

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