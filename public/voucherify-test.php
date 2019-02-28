<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 1/7/2019
 * Time: 4:27 PM
 */

require_once "bootstrap.php";
require_once RESOURCE_PATH . "/SignupCouponDistribution.class.php";
require_once RESOURCE_PATH . "/CouponRedemption.class.php";
require_once RESOURCE_PATH . "/CouponDistributionDAO.class.php";
require_once RESOURCE_PATH . "/user-session.php";
require_once RESOURCE_PATH . "/database.php";

use Voucherify\VoucherifyClient;
use Voucherify\ClientException;


if (isset($_GET['test']) &&  $_GET['test'] == 'get') {
    $params = [];
    $couponDistDAO = new CouponDistributionDAO();
    $coupons = $couponDistDAO->getCoupons($db, $params);
    var_dump($coupons);
} else {
    $signUpCoupon = new SignupCouponDistribution($db);

    $result = $signUpCoupon->distribute(2);

    var_dump($result);

    $couponRedemption = new CouponRedemption($db);

    $userInfo = getSignedinUserInfo($db);

    $result = $couponRedemption->redeem($userInfo,$result->voucher->code,1000);

    var_dump($result);

    $couponRedemption->redeem($userInfo,$result->voucher->code,1000);

}