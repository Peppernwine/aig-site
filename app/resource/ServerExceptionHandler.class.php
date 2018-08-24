<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 7/12/2018
 * Time: 10:07 AM
 */

require_once RESOURCE_PATH . "/MethodNotAllowedException.class.php";
require_once RESOURCE_PATH . "/ValidationException.class.php";
require_once RESOURCE_PATH . "/ServerException.class.php";

class ServerExceptionHandler
{
    public function HandleException($ex,&$newEx)
    {
        $newEx = $ex;
        if (!($ex instanceof ServerException)) {
            $newEx = new ServerException('Oops! Something went wrong. We are looking into it. Please come back later.');
        }
        }
}


