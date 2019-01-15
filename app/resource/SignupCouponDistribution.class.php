<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 1/13/2019
 * Time: 3:43 PM
 */

require_once RESOURCE_PATH . "/CouponDistribution.class.php";

class SignupCouponDistribution extends CouponDistribution
{
    protected function getCampaignCode() {
        return "Signup";
    }

}
