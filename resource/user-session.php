<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/16/2018
 * Time: 7:51 PM
 */

require_once "session.php";
require_once "user-helper.php";
require_once "RememberMeToken.class.php";

/*
function generateClientId () {
    if (isset($_COOKIE['clientId'])) {
        $clientId = $_COOKIE['clientId'];
    } elseif ((isset($_SESSION['clientId']))) {
        $clientId = $_SESSION['clientId'];
    } else {
        $clientId = random_bytes(62);
        $_SESSION['clientId'] = $clientId;
    }
    return $clientId;
}
*/


function isSignedIn() {
    return isset($_SESSION['userId']);

}
function getSignedinUser($dbConnection) {
    return getUserById($dbConnection,getSignedInUserId());
}

function getSignedinUserInfo($dbConnection) {
    $user = getUserById($dbConnection,getSignedInUserId());
    $userInfo = [];
    if (isset($user)) {
        $userInfo['userId']      = $user['user_id'];
        $userInfo['emailId']     = $user['email_id'] ;
        $userInfo['userTypeId']  = $user['user_type_id'];
        $userInfo['firstName']   = $user['first_name'];
        $userInfo['lastName']    = $user['last_name'];
        $userInfo['cellPhone']   = $user['cell_phone'];
    }
    return $userInfo;
}

function getSignedInUserId() {
    $userId = null;
    if (isset($_SESSION['userId']))
        $userId = $_SESSION['userId'];

    return $userId ;
}

function hasSuperUserAccess() {
    $userType = getSignedInUserType();
    return ($userType === 5);
}

function hasAdminAccess() {
    $userType = getSignedInUserType();
    return ($userType >= 4);
}

function hasStaffAccess() {
    $userType = getSignedInUserType();
    return ($userType >= 3);
}

function getSignedInUserType() {
    $userType = null;
    if (isset($_SESSION['userTypeId']))
        $userType = $_SESSION['userTypeId'];

    return $userType ;
}

function getSignedInFirstMame() {
    $firstName = null;
    if (isset($_SESSION['firstName']))
        $firstName = $_SESSION['firstName'];
    return $firstName ;
}

function getSignedInLastMame() {
    $lastName = null;
    if (isset($_SESSION['lastName']))
        $lastName = $_SESSION['lastName'];
    return $lastName ;
}

function getSignedInEmailId() {
    $emailId = null;
    if (isset($_SESSION['emailId']))
        $emailId = $_SESSION['emailId'];
    else if (isset($_COOKIE['emailId']))
        $emailId = $_COOKIE['emailId'];

    return $emailId ;
}

function getSignedInPasswordHash() {
    $passwordHash = null;
    if (isset($_SESSION['passwordHash']))
        $passwordHash = $_SESSION['passwordHash'];

    return $passwordHash ;
}

function getLastRememberMeFlag() {
    $rememberMeFlag = null;
    if (isset($_COOKIE['rememberMe']))
        $rememberMeFlag = $_COOKIE['rememberMe'];

    return $rememberMeFlag;
}

function getLastSignInToken() {
    $signInToken = null;
    if (isset($_COOKIE['signinToken']))
        $signInToken = $_COOKIE['signinToken'];

    return $signInToken;
}

function clearSessionUserInfo() {
    unset($_SESSION['userId']);
    unset($_SESSION['userTypeId']);
    unset($_SESSION['emailId']);
    unset($_SESSION['firstName']);
    unset($_SESSION['lastName']);
    unset($_SESSION['passwordHash']);
}

function persistUserInfoInSession($user) {
    $_SESSION['userId']        = $user['user_id'];
    $_SESSION['userTypeId']   = $user['user_type_id'];
    $_SESSION['emailId']       = $user['email_id'];
    $_SESSION['firstName']     = $user['first_name'];
    $_SESSION['lastName']      = $user['last_name'];
    $_SESSION['passwordHash']  = $user['password_hash'];
}

function persistUserInfo($dbConnection,$user,$rememberMeFlag) {
    if (isset($user)) {
        persistUserInfoInSession($user);
        if  (isset($rememberMeFlag) && ($rememberMeFlag == '1')) {
            rememberSignin($dbConnection, $user);
        } else {
            forgetSignin($dbConnection);
        }
    }
}

function deleteLastUserToken($dbConnection,$rememberMe = null) {
    if (!isset($rememberMe)) $rememberMe = new RememberMeUserToken();

    $oldUserToken = getLastSignInToken();
    if (isset($oldUserToken)) {
        $rememberMe->deleteToken($dbConnection,$oldUserToken);
    }
}

function rememberSignin($dbConnection, $user) {
    if (isset($user)) {
        setcookie("emailId",$user['email_id'],time()+10*365*24*60*60,"/");//expire in 10 years
        setcookie("rememberMe","1",time()+10*365*24*60*60,"/");//expire in 10 years

        $rememberMe = new RememberMeUserToken();

        deleteLastUserToken($dbConnection,$rememberMe);

        $encodedToken = $rememberMe->generateToken($dbConnection,$user['user_id'],time()+2*24*60*60);
        setcookie('signinToken',$encodedToken,time()+2*24*60*60,'/');//expire in 2 days
    };
}

function forgetSignin($dbConnection) {
    deleteLastUserToken($dbConnection);

    unset($_COOKIE['rememberMe']);
    setcookie("rememberMe","",time()-3600,"/");

    unset($_COOKIE['emailId']);
    setcookie("emailId",'',time()-3600,"/");

    unset($_COOKIE['signinToken']);
    setcookie("signinToken", "", time()-3600,"/");

}

function forgetSigninToken($dbConnection) {
    deleteLastUserToken($dbConnection);
    unset($_COOKIE['signinToken']);
    setcookie("signinToken", "", time()-3600,"/");
}
