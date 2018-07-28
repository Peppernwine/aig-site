<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/15/2018
 * Time: 2:26 PM
 */

require_once realpath(dirname(__FILE__)) . "/../public/bootstrap.php";

class UserToken
{
    const SECRET_KEY = "121^23SD$-AvonIndianGrill=^sdfs3434$*)(^~^";
    const TOKEN_LENGTH = 179;
    const SIGNIN_TOKEN = 1;
    const SIGNUP_ACTIVATION_TOKEN = 2;
    const PASSWORD_RECOVERY_TOKEN = 3;

    private $tokenType;

    private function generateRandomBytes($length)
    {
        try {
            $rand = random_bytes($length);

            return $rand;
        } catch (Exception $e) {
            throw new Exception("Could not generate a random string.");
        }
    }

    private function generateTokenMAC($userId, $token)
    {
        return hash_hmac('sha256', $userId . ':' . $token, SELF::SECRET_KEY);
    }

    private function encodeToken($userId, $token)
    {
        $mac = $this->generateTokenMAC($userId, $token);
        $encodedToken = base64_encode("{$userId}:{$token}:{$mac}");

        return $encodedToken;
    }

    private function decodeToken($encodedToken, &$userId, &$token, &$tokenMAC)
    {
        $encodedToken = base64_decode($encodedToken);

        list ($userId, $token, $tokenMAC) = explode(':', $encodedToken);
    }

    private function timeStampToDate($timeStamp)
    {
        return date("Y-m-d H:i:s", $timeStamp);
    }

    private function insertToken($dbConnection, $userId, $token, $expiry)
    {

        $insertTokenSQL = "INSERT INTO user_token(token_type,user_id,token,expiry,created_date) 
                                           VALUES($this->tokenType,:userId,:token,:expiry,now())";

        $statement = $dbConnection->prepare($insertTokenSQL);
        $statement->execute(array(':userId' => $userId, ':token' => $token, ':expiry' => $this->timeStampToDate($expiry)));

        if ($statement->rowCount() == 0)
            throw new Exception("Could not save user token");
    }

    private function getTokenExpiry($dbConnection, $userId, $token)
    {
        $selectTokenSQL = "SELECT token, expiry FROM user_token WHERE user_id = :userId AND token_type=:tokenType";

        $statement = $dbConnection->prepare($selectTokenSQL);
        $statement->execute(array(':userId' => $userId, ':tokenType' => $this->tokenType));

        while ($row = $statement->Fetch()) {

            if (hash_equals($row['token'], $token)) {
                return $row['expiry'];
            }
        }
        return -1;
    }

    public function __construct($tokenType)
    {
        $this->tokenType = $tokenType;
    }

    public function deleteToken($dbConnection, $encodedToken)
    {
        list($userId, $token, $tokenMAC) = array(null, null, null);
        $this->decodeToken($encodedToken, $userId, $token, $tokenMAC);

        $deleteTokenSQL = "DELETE FROM user_token WHERE user_id = :userId and token = :token";

        $statement = $dbConnection->prepare($deleteTokenSQL);
        $statement->execute(array(':userId' => $userId, ':token' => $token));

        if ($statement->rowCount() == 0)
            throw new Exception("Could not delete user token ");
    }

    function generateToken($dbConnection, $userId, $expiry)
    {
        try {
            $token = $this->generateRandomBytes(SELF::TOKEN_LENGTH);

            $token = str_replace(":", ";", $token);

            $this->InsertToken($dbConnection, $userId, $token, $expiry);
            $encodedToken = $this->encodeToken($userId, $token);

            return $encodedToken;
        } catch (Exception $ex) {
            //silently log & notify ADMIN if this fails
        }
    }

    function validateToken($dbConnection, $encodedToken)
    {
        $expiry = null;
        $userId = null;
        $token = null;
        $tokenMAC = null;
        $isValid = false;

        try {
            $this->decodeToken($encodedToken, $userId, $token, $tokenMAC);

            if (!is_null($tokenMAC) && !is_null($userId)
                                    && !is_null($token)
                                    && hash_equals($this->generateTokenMAC($userId, $token), $tokenMAC)) {
                $expiry = $this->getTokenExpiry($dbConnection, $userId, $token);

                //check if token has expired...
                if (time() < strtotime($expiry))
                    $isValid = true;
            }
        } catch (Exception $ex) {
            //do nothing...
        }

        if ($isValid) {
            return $userId;
        } else {
            throw new Exception("Invalid Token or Token has expired.");

        }


    }
}