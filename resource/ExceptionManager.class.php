<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 7/17/2018
 * Time: 9:03 AM
 */

class ExceptionManager
{
    private $handlers ;
    private static $defaultHandlers = [];

    public function __construct($handlers){
        if(!empty($handlers))
            $this->handlers = $handlers;
        else
            $this->handlers = SELF::$defaultHandlers;
    }

    public static function setDefaultExceptionHandlers($handlers) {
        SELF::$defaultHandlers = $handlers;
    }

    private function _handleException($ex,&$newEx,$handlers) {
        $newEx = $ex;
        foreach ($handlers as $handler) {
            $handler->handleException($ex,$newEx);
            $ex = $newEx;
        }
    }
    public function handleException($ex,&$newEx) {
        $this->_handleException($ex,$newEx,$this->handlers);
    }

     public function execute($fn,$params){
        try {
            call_user_func_array($fn,$params);
            return true;
        } catch (Exception $ex) {
            $newEx = $ex;
            $this->handleException($ex,$newEx);
            return false;
        }
    }
}