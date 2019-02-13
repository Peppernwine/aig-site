<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 2/12/2019
 * Time: 12:46 AM
 */

require_once dirname(__FILE__) . "/../bootstrap.php";

$data = file_get_contents("php://input");

AIGLogger::instance()->addInfo($data);

http_response_code(200);


/*
 * "type": "voucher.published",
 *  "data": {
        "object": {
          "id": "v_hKb3ySs81IydmRqSEo0slB7Ks8I7wc26",
          "code": "SUcngwBpoO",
          "campaign": "Signup",
          "campaign_id": "camp_Bll33KWhL7eR1zGnh3iql9hI",
          "type": "DISCOUNT_VOUCHER",
          "discount": {
            "type": "AMOUNT",
            "amount_off": 500
          },
          "start_date": "2019-01-13T00:00:00.000Z",
          "expiration_date": "2019-03-21T06:39:29.346Z",


          "assets": {
                "qr": {
                  "id": "U2FsdGVkX19aACS2RbSorz5VxY9eCCvSw3WA03OjkmOxjK3QnNHCAjCimQYqy/sITkgN5DYT/oXw9mpleMHl5Ow20McV5BiALuSVPPQZpL8Sw2N9dDAyzh4srJjAEffu9aoy5YsT6InNy1RQBgDHNA==",
                  "url": "https://dl.voucherify.io/api/v1/assets/qr/U2FsdGVkX19aACS2RbSorz5VxY9eCCvSw3WA03OjkmOxjK3QnNHCAjCimQYqy%2FsITkgN5DYT%2FoXw9mpleMHl5Ow20McV5BiALuSVPPQZpL8Sw2N9dDAyzh4srJjAEffu9aoy5YsT6InNy1RQBgDHNA%3D%3D"
                },

*
 *
 */