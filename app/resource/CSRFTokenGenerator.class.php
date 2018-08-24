<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/25/2018
 * Time: 12:51 PM
 */

include_once "user-session.php";

class CSRFTokenGenerator
{
    private $_current ;

    public static function current() {
        if (!isset($_current))
            $_current = new CSRFTokenGenerator();

        return $_current ;
    }

    private function generateRandomBytes($length)
    {
        try {
            $rand = random_bytes($length);

            return base64_encode($rand);
        } catch (Exception $e) {
            throw new Exception("Could not generate a random string.");
        }
    }

    public function getCurrentToken() {
        if ((isset($_SESSION['csrf-token'])))
            return $_SESSION['csrf-token'] ;
        else
            return "";
    }

    public function generateToken() {
        if (!(isset($_SESSION['csrf-token'])))
            $_SESSION['csrf-token'] = $this->generateRandomBytes(32);
        return $_SESSION['csrf-token'] ;
    }


    public function validateToken($token) {
        if  (isset($_SESSION['csrf-token']) &&  $_SESSION['csrf-token'] === $token) {
            return true;
        }

        throw new Exception("This request originates from unknown source, possible attack");
    }
}