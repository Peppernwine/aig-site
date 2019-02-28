<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/15/2018
 * Time: 2:26 PM
 */

class VoucherifyVoucherPublishedDataAdaptor
{
     function convertToAIG($data) {
        $voucherData['emailId'] = $data["data"]["related_object"]["metadata"]["emailId"];
        $voucherData['userId'] = $data["data"]["related_object"]["tracking_id"];
        $voucherData['couponCode'] = $data["data"]["object"]["code"];
        $voucherData['campaignCode'] = $data["data"]["object"]["campaign"];
        $voucherData['campaignId'] = $data["data"]["object"]["campaign_id"];
        $voucherData['couponType'] = $data["data"]["object"]["type"];
        $voucherData['discountType'] = $data["data"]["object"]["discount"]["type"];
        $voucherData['discountValue'] = $data["data"]["object"]["discount"]["amount_off"];
        $dt1 = new DateTime($data["data"]["object"]["start_date"],new DateTimezone('GMT'));
        $voucherData['startDate'] =  $dt1->format('Y-m-d H:i:s');

        $dt2 = new DateTime($data["data"]["object"]["expiration_date"],new DateTimezone('GMT'));
        $voucherData['expirationDate'] = $dt2->format('Y-m-d H:i:s');
        $voucherData['qrCode'] = $data["data"]["object"]["assets"]["qr"]["id"];
        $voucherData['qrUrl'] = $data["data"]["object"]["assets"]["qr"]["url"];
        
        return $voucherData;
     }
}