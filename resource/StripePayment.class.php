<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 7/11/2018
 * Time: 1:46 PM
 */

require_once RESOURCE_PATH . "/StripeExceptionHandler.class.php";

use \Stripe\Stripe;

class StripePayment
{
    public function charge($token,$chargeAmount,$chargeCCY,$chargeDescription,$companyDetails,$referenceKey,$referenceValue)
    {
        Stripe::setApiKey(STRIPE_API_SECRET);

        $charge = \Stripe\Charge::create([
            'amount' => intval($chargeAmount*100),
            'currency' => $chargeCCY,
            'description' => $chargeDescription,
            'statement_descriptor' => $companyDetails,
            'source' => $token,
            'metadata' => [$referenceKey => $referenceValue],
        ]);

        return $charge;

        //echo formatSuccessMessage(json_encode($charge->outcome, JSON_PRETTY_PRINT));

    }

}