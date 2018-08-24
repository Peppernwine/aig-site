<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 7/12/2018
 * Time: 10:07 AM
 */

require_once RESOURCE_PATH . "/MethodNotAllowedException.class.php";
require_once RESOURCE_PATH . "/ValidationException.class.php";

class RESTExceptionResponseGenerator
{
    public function getResponse($ex)
    {
        $error['status'] = 500;
        $error['message'] = $ex->getMessage();

        if (($ex instanceof MandatoryFieldException) or ($ex instanceof ValidationException)
                                                     or ($ex instanceof CreditCardException)) {
            $error['status'] = 400;

        } else if ($ex instanceof MethodNotAllowedException) {
            $error['status'] = 405;
        }

        return $error;
    }
}


