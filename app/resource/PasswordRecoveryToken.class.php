<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/15/2018
 * Time: 2:26 PM
 */

require_once "UserToken.class.php";

class PasswordRecoveryToken
{
    private $userToken ;

    function __construct() {
        $this->userToken = new UserToken(UserToken::PASSWORD_RECOVERY_TOKEN);
    }

    function generateToken($dbConnection, $userId, $expiry) {
        return $this->userToken->generateToken($dbConnection, $userId, $expiry);
    }

    public function deleteToken($dbConnection, $encodedToken) {
        $this->userToken->deleteToken($dbConnection, $encodedToken);
    }

    function validateToken($dbConnection, $encodedToken) {
        return $this->userToken->validateToken($dbConnection, $encodedToken);
    }
}