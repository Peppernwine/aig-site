<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 1/13/2019
 * Time: 3:43 PM
 */

require_once RESOURCE_PATH . "/user-helper.php";
require_once RESOURCE_PATH . "/VoucherifyClientExt.class.php";

abstract class CouponDistribution
{
    private $dbConnection;

    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    protected abstract function getCampaignCode();

    public function distribute($userId) {

        $userInfo = getUserInfo($this->dbConnection, $userId) ;

        $client = new VoucherifyClientExt(VOUCHERIFY_API_ID, VOUCHERIFY_API_KEY);

        $result = $client->distributions->createPublications([
            "campaign" =>[
                "name" =>  $this->getCampaignCode(),
                "count" => 1
            ],
            "customer" => [
                "source_id" => $userId,
                "email" => $userInfo['emailId'] ,
                "name"  => $userInfo['firstName']  . ' ' . $userInfo['lastName']
            ],
            "metadata" => [
                "emailId" => $userInfo['emailId']
            ],

        ]);
        //save userId, code,type,start_date,expiration_date in database
        //Notify via email/SMS with link to the coupon
        return $result;//->voucher->code;
    }

    public function assignCoupon($coupon) {



    }

}
