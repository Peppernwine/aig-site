<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 1/13/2019
 * Time: 3:43 PM
 */

require_once RESOURCE_PATH . "/user-helper.php";
require_once RESOURCE_PATH . "/VoucherifyClientExt.class.php";

class CouponRedemption
{
    private $dbConnection;

    public function _construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function getQRUrl($code) {

        //$client->vouchers->get($code);

        // https://docs.voucheri fy.io/v2018-08-01/reference#vouchers-get
    }

    public function redeem($code,$userId) {
        $user = getUserById($this->dbConnection, $userId) ;

        //https://docs.voucherify.io/v2018-08-01/reference#redeem-voucher
        //$client->redemptions->redeem($code);
        //$client->redemptions->redeem($code, $params);
    }

}
