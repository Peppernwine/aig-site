<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 7/13/2018
 * Time: 12:35 PM
 */

class ErrorHandler
{
    private $logger;

    // Hold an instance of the class
    private static $_instance;

    private static function createErrorHandler() {

        $errorHandler = new ErrorHandler(AIGLogger::instance()) ;
        return $errorHandler;
    }

    // The singleton method
    public static function instance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = self::createErrorHandler();
        }
        return self::$_instance;
    }

    private function generateErrorResponse() {
        $error['status'] = 500;
        $error['message'] = 'Oops! Something went wrong. We are looking into it. Please come back later.';
        header("Content-Type: application/json;charset=UTF-8");
        http_response_code($error['status']);
        echo json_encode($error);
    }

    private function reportError($errNo, $errStr, $errFile, $errLine,$errStack ) {
        if (!empty($stack))
            $errData =  ['stacktrace' => $errStack,
                           'File' => $errFile,
                           'Line' =>$errLine];
        else
            $errData =  ['File' => $errFile,'Line' => $errLine];

        if ($errNo == E_USER_ERROR)
            $this->logger->addAlert($errStr,$errData);
        else
            $this->logger->addInfo($errStr,$errData);
        }

    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    public function fatal_handler() {
        $errfile = "unknown file";
        $errstr  = "shutdown";
        $errno   = E_CORE_ERROR;
        $errline = 0;

        $error = error_get_last();

        if( !is_null($error)) {
            $errno   = $error["type"];
            $errfile = $error["file"];
            $errline = $error["line"];
            $errstr  = $error["message"];

            $this->reportError($errno,$errstr,  $errfile, $errline,[]);
            if ($errno == E_USER_ERROR)
                $this->generateErrorResponse();
        }
    }

    public function reportException($exception) {
        $errorStr = 'Uncaught exception ' . get_class($exception) . ' with message ' . $exception->getMessage() ;

        $this->reportError(E_ERROR, $errorStr, $exception->getFile(), $exception->getLine(), $exception->getTraceAsString());
    }

    public function exception_handler($exception) {
        $this->reportException($exception);
    }

    public function error_handler($errNo, $errStr, $errFile, $errLine )
    {
        if (!(error_reporting() & $errNo)) {
            // This error code is not included in error_reporting, so let it fall
            // through to the standard PHP error handler
            return false;
        }

        switch ($errNo) {
            case E_USER_ERROR:
                $this->reportError($errNo, $errStr, $errFile, $errLine,[]);
                $this->generateErrorResponse();
                exit(1);
                break;

            case E_USER_WARNING:
                $this->reportError($errNo, $errStr, $errFile, $errLine,[]);
                break;

            case E_USER_NOTICE:
                $this->reportError($errNo, $errStr, $errFile, $errLine,[]);
                break;

            default:
                $this->reportError($errNo, $errStr, $errFile, $errLine,[]);
                break;
        }

        return false;
    }

    public function initialize() {
        error_reporting(E_ALL);
        ini_set('log_errors','1');
        ini_set('display_errors',PHP_DISPLAY_ERRORS);

        set_exception_handler(array(ErrorHandler::instance(), "exception_handler"));
        set_error_handler(array(ErrorHandler::instance(), "error_handler"));
        register_shutdown_function(array(ErrorHandler::instance(), "fatal_handler"));
    }
}

