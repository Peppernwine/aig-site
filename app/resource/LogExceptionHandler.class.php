<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 7/12/2018
 * Time: 10:07 AM
 */

require_once RESOURCE_PATH . "/MethodNotAllowedException.class.php";
require_once RESOURCE_PATH . "/ValidationException.class.php";

class LogExceptionHandler
{
    public function HandleException($ex,&$newEx)
    {
        ErrorHandler::instance()->reportException($ex);
        $newEx = $ex;
    }
}


