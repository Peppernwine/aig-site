<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/17/2018
 * Time: 8:13 PM
 */

include_once "path.php";
include_once VENDOR_LIB_PATH . "/autoload.php";
include_once RESOURCE_PATH . "/appconfig.php";
include_once RESOURCE_PATH . "/AIGLogger.class.php";
include_once RESOURCE_PATH . "/ErrorHandler.class.php";
include_once RESOURCE_PATH . "/configuration.class.php";
include_once RESOURCE_PATH . "/ExceptionManager.class.php";
include_once RESOURCE_PATH . "/ServerException.class.php";
include_once RESOURCE_PATH . "/LogExceptionHandler.class.php";
include_once RESOURCE_PATH . "/info-page-helper.php";


date_default_timezone_set('US/Eastern');
ErrorHandler::instance()->initialize();

ExceptionManager::setDefaultExceptionHandlers( [new LogExceptionHandler()]);