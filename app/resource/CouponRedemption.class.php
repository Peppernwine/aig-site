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

    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function getQRUrl($code) {

        $client = new VoucherifyClientExt(VOUCHERIFY_API_ID, VOUCHERIFY_API_KEY);

        $voucher = $client->vouchers->get($code);

        var_dump($voucher);

        // https://docs.voucheri fy.io/v2018-08-01/reference#vouchers-get
    }

    /*

     "customer" => [
                "source_id" => $user['email_id'],
                "email" => $user['email_id'],
                "name"  => $user['first_name'] . ' ' . $user['last_name']
            ]

    {
    "customer": {
      "source_id": "track_+EUcXP8WDKXGf3mYmWxbJvEosmKXi3Aw",
      "name": "Alice Morgan",
      "email": "alice@morgan.com",
      "metadata": {
        "locale": "en-GB",
        "shoeSize": 5,
        "favourite_brands": ["Armani", "L’Autre Chose", "Vicini"]
      }
    },
    "order": {
      "amount": 20050,
      "items": [
        { "product_id": "prod_anJ03RZZq74z4v", "quantity": "2", "price": 10000 },
        { "sku_id": "sku_0KtP4rvwEECQ2U", "quantity": "1" }
      ]
    },
    "metadata": {
      "locale": "en-GB"
    }
  }
    */

    //https://docs.voucherify.io/v2018-08-01/reference#redeem-voucher
    public function redeem($userInfo,$code,$orderTotal) {

        $client = new VoucherifyClientExt(VOUCHERIFY_API_ID, VOUCHERIFY_API_KEY);

        $customer =  ["source_id" => $userInfo['emailId'] ,
                      "email" => $userInfo['emailId'] ,
                      "name"  => $userInfo['firstName']  . ' ' . $userInfo['lastName']];
        $order = [ "amount" => $orderTotal];

        return $client->redemptions->redeem($code, ["customer" => $customer , "order" => $order]);
    }

}
