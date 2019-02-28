<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 2:07 PM
 */

require_once RESOURCE_PATH . "/BaseDAO.class.php";
require_once RESOURCE_PATH . "/database.php";

class CouponDistributionDAO extends BaseDAO
{
    private function generateCouponInsertSQL() {
        $insertCouponSQL = "INSERT INTO customer_coupon
                                         (customer_id,email_id,coupon_code,campaign_id,campaign_code,coupon_type,
                                          discount_type,discount_value,start_date,expiration_date,qr_code,qr_url)
                             VALUES
                                     (:customerId,:emailId,:couponCode,:campaignId,:campaignCode,:couponType,
                                      :discountType,:discountValue,:startDate,:expirationDate,:qrCode,:qrUrl)";
        return $insertCouponSQL;
    }

    private function getCouponInsertParameters($coupon) {
        return array(
            ':emailId' => $coupon['emailId'],
            ':customerId' => $coupon['customerId'],
            ':couponCode' => $coupon['couponCode'],
            ':campaignCode' => $coupon['campaignCode'],
            ':campaignId' => $coupon['campaignId'],
            ':couponType' => $coupon['couponType'],
            ':discountType' => $coupon['discountType'],
            ':discountValue' => $coupon['discountValue'],
            ':startDate' => $coupon['startDate'],
            ':expirationDate' => $coupon['expirationDate'],
            ':qrCode' => $coupon['qrCode'],
            ':qrUrl' => $coupon['qrUrl']
        );
    }

    private function gatherCouponFields($row,&$coupon) {
        $coupon['customerCouponId'] = $row['customer_coupon_id'] ;
        $coupon['emailId']          = $row['email_id'] ;
        $coupon['customerId']       = $row['customer_id'] ;
        $coupon['couponCode']       = $row['coupon_code'] ;
        $coupon['campaignCode']     = $row['campaign_code'] ;
        $coupon['campaignId']       = $row['campaign_id'] ;
        //$coupon['couponType']       = $row['coupon_type'] ;
        $coupon['discountType']     = $row['discount_type'] ;
        $coupon['discountValue']    = $row['discount_value'] ;
        $coupon['startDate']        = $row['start_date'] ;
        $coupon['expirationDate']   = $row['expiration_date'] ;
        $coupon['qrCode']           = $row['qr_code'] ;
        $coupon['qrUrl']            = $row['qr_url'] ;
    }

    private function getCouponsFromSQLRow($rows) {
        $idx = 0;

        $coupons = [];

        while($idx <= count($rows) - 1) {
            $coupon = [];
            $this->gatherCouponFields($rows[$idx],$coupon);
            $coupons[] = $coupon;
            $idx++;
        }
        return $coupons;
    }

    private function getSearchSQLParams($searchParams) {

        $params = [];

        if (array_key_exists('customerId',$searchParams) && !empty($searchParams['customerId'])
            && $searchParams['customerId'] != -1) {
            $params['noCustomerIdParam'] = 0;
            $params['customerId'] = $searchParams['customerId'];
        } else {
            $params['noCustomerIdParam'] = 1;
            $params['customerId'] = -1;
        }

        if (array_key_exists('startDate',$searchParams) && !empty($searchParams['startDate'])) {
            $params['noStartDateParam'] = 0;
            $searchParams['startDate'] = trim($searchParams['startDate'],"'");
            $params['startDate'] = date_format(date_create_from_format('Y-m-d',$searchParams['startDate']),"Y-m-d");
        } else {
            $params['noStartDateParam'] = 1;
            $params['startDate'] = 0;
        }

        if (array_key_exists('expirationDate',$searchParams) &&!empty($searchParams['expirationDate'])) {
            $params['noExpirationDateParam'] = 0;
            $searchParams['expirationDate'] = trim($searchParams['expirationDate'],"'");
            $params['expirationDate'] =  date_format(date_create_from_format('Y-m-d',$searchParams['expirationDate']),"Y-m-d");
        } else {
            $params['noExpirationDateParam'] = 1;
            $params['expirationDate'] = 0;
        }

        return $params;
    }

    public function getCoupons($dbConnection, $searchParams)
    {
        $params = $this->getSearchSQLParams($searchParams);

        if (array_key_exists('batchSize',$searchParams) && !empty($searchParams['batchSize'])) {
            $limit = $searchParams['batchSize'];
            $offset = $limit * $searchParams['lastPage'];
        } else {
            $limit = 15;
            $offset = 0;
        }

        //TODO: filter by user_id, expiration_date >= now
        $selectCouponsSQL =
            "SELECT cc.*  
             FROM customer_coupon cc
             WHERE 
             (1=:noStartDateParam OR cc.start_date >= :startDate) AND 
             (1=:noExpirationDateParam OR cc.expiration_date <= :expirationDate) AND
             (1=:noCustomerIdParam OR IFNULL(cc.customer_id,-1) = :customerId) AND 
             coupon_type = \"DISCOUNT_VOUCHER\"              
             ORDER BY cc.expiration_date DESC LIMIT $offset ,$limit";

        $statement = $dbConnection->prepare($selectCouponsSQL);

        $statement->execute($params);

        $rows = $statement->fetchAll();

        $coupons = $this->getCouponsFromSQLRow($rows);

        if (sizeof($coupons)  > 0)
            return $coupons;
        else
            return [];
    }

    public function saveCoupon($dbConnection, $coupon)
    {
        $insertCouponSQL = $this->generateCouponInsertSQL();

        $insertCouponStatement = $dbConnection->prepare($insertCouponSQL);

        $couponParams = $this->getCouponInsertParameters($coupon);
        $insertCouponStatement->execute($couponParams);
    }
}
