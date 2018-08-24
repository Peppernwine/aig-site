<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/19/2018
 * Time: 9:48 PM
 */

require_once RESOURCE_PATH . "/authentication-helper.php";
require_once RESOURCE_PATH . "/user-session.php";

function validateCredentials($dbConnection, $emailId, $password) {
    try {
        $user = getUserByEmailId($dbConnection, $emailId);
    } catch(Exception $ex) {
        throw new Exception("Invalid Email or Password");
    }

    if ($user['active'] != 1) {
        throw new Exception("Account has not been activated. Please activate your account from the email link.");
    }

    if (!password_verify($password, $user['password_hash']))
        throw new Exception("Invalid Email or Password");

    return $user;
}