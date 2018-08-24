<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 7/12/2018
 * Time: 10:07 AM
 */

require_once RESOURCE_PATH . "/CreditCardException.class.php";

class StripeExceptionHandler
{
    public function HandleException($ex,&$newEx)
    {
        $newEx = $ex;
        $message = null;
        if ($ex instanceof \Stripe\Error\Card) {
            $body = $ex->getJsonBody();
            $err = $body['error'];
            $message = 'Card was declined - ' . $err['message'];
        } else if ($ex instanceof \Stripe\Error\RateLimit) {
                $message = 'System is experiencing high load.';
        } else if (($ex instanceof \Stripe\Error\Authentication) or
                   ($ex instanceof \Stripe\Error\ApiConnection)  or
                   ($ex instanceof \Stripe\Error\Base)) {
            $message = 'System is experiencing technical problems';
        }
        if (!empty($message))
            $newEx = new CreditCardException($message);
    }
}

