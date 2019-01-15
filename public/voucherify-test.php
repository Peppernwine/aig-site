<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 1/7/2019
 * Time: 4:27 PM
 */

require_once "bootstrap.php";
require_once RESOURCE_PATH . "/SignupCouponDistribution.class.php";
require_once RESOURCE_PATH . "/database.php";

use Voucherify\VoucherifyClient;
use Voucherify\ClientException;

$signUpCoupon = new SignupCouponDistribution($db);

$result = $signUpCoupon->distribute(2);
var_dump($result->voucher->assets->qr->id);

var_dump($result);
