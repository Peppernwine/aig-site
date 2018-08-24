<?php

require_once "user-session.php";
require_once "user-helper.php";
require_once "string-utility.php";
require_once "authentication-helper.php";

function decodeSigninIdentityParam(){
    $emailId = null;
    if (isset($_GET['identity']))
        $emailId = $_GET['identity'];
    return $emailId;
}

function generateSigninURL($dbConnection,$userId,$redirectPage=null)
{
    $emailId = null;

    if (isset($dbConnection) && isset($userId)) {
        $user = getUserById($dbConnection,$userId);
        if (isset($user))
            $emailId = $user['email_id'];
    }

    $params = [];
    if (!empty($redirectPage))
        $params['location'] = $redirectPage;

    if (isset($emailId))
        $params['identity'] = $emailId;

/*
    if (isset($emailId)) {
        $params['identity'] = $emailId;
        $identityParam = encodeSigninIdentityParam($emailId);
        $url = getServerURL("/signin.php?$identityParam");
    } else
        $url = getServerURL("/signin.php");
*/

    return Configuration::instance()->getServerURL("signin") . "?" . http_build_query($params);
}

function generateSigninLink($dbConnection,$userId,$linkText,$redirectPage=null) {
    $url = generateSigninURL($dbConnection,$userId,$redirectPage);
    return "<a href='{$url}'>{$linkText}</a>";

}

function redirectToSigninPage() {
    redirectTo("signin");
}

function redirectToHomePage() {
    redirectTo("index");
}

function signIn($dbConnection,$emailId,$password,$rememberMeFlag,$csrfToken,$redirectLocation,&$errors) {
    $success = false;
    $errors = [];

    if (isset($password)) {
        try {
            CSRFTokenGenerator::current()->validateToken($csrfToken) ;
            $user = tryManualAuthenticate($dbConnection,$emailId,$password,$rememberMeFlag,$errors);
        } catch(Exception $ex) {
            $errors[] = $ex->getMessage();
        }
    }

    if (isset($user)) {
        if (!empty($redirectLocation))
            redirectTo($redirectLocation);
        else
            redirectToHomePage();
        $success = true;
    };

    return $success;
}


function gatherSigninDefault(&$emailId,&$rememberMeFlag,&$redirectLocation) {

    $emailId =  decodeSigninIdentityParam();

    if (empty($emailId))
        $emailId = getSignedInEmailId();

    $rememberMeFlag = getLastRememberMeFlag();

    if (isset($_GET['location']))
        $redirectLocation = urldecode($_GET['location']);
    else
        $redirectLocation = '';
}


function gatherSignInFields(&$emailId, &$password, &$rememberMeFlag,&$csrfToken,&$redirectLocation) {
    $emailId = null;
    $password = null;
    $rememberMeFlag = 0;

    if (isset($_POST['email-id']))
        $emailId = $_POST['email-id'];

    if (isset($_POST['password']))
        $password = $_POST['password'];

    if (isset($_POST['cb-remember-me']))
        $rememberMeFlag = $_POST['cb-remember-me'];

    if (isset($_POST['csrf-token']))
        $csrfToken = $_POST['csrf-token'];
    else
        $csrfToken = '';

    if (isset($_POST['location']))
        $redirectLocation = urldecode($_POST['location']);
    else
        $redirectLocation = '';
}

function tryManualAuthenticate($dbConnection, $emailId,$password,$rememberMeFlag, &$errors) {

    forgetSigninToken($dbConnection);

    $fieldLabels = array('email-id' => 'Email', 'password' => 'Password');
    $requiredFields = array('email-id', 'password');

    $errors = array();
    $errors = array_merge($errors, validateRequiredFields($_POST, $requiredFields, $fieldLabels));
    if (!empty($emailId)) {
        $errors = array_merge($errors, validateEmailFormat($emailId));
    }

    if (empty($errors)) {
        try {
            $user = validateCredentials($dbConnection, $emailId, $password);
            persistUserInfo($dbConnection,$user,$rememberMeFlag);
            return $user;
        } catch (Exception $ex) {
            $errors[] = $ex->getMessage();
        }
    }
}

function validatePasswordById($dbConnection, $userId, $password) {
    try {
        $user = getUserById($dbConnection, $userId);
    } catch(Exception $ex) {
        throw new Exception("Invalid User");
    }

    if (!password_verify($password, $user['password_hash']))
        throw new Exception("Invalid Password");

    return $user;
}

function tryAutoSignIn($dbConnection) {

    $user = null;
    try {
        $user = tryAutoAuthenticateFromSession($dbConnection);
        if (!isset($user)) {
            $user = tryAutoAuthenticateFromSigninToken($dbConnection);
        }
    } catch (Exception $ex)
    {
        //do nothing;
    }

    if(isset($user)) {
        persistUserInfo($dbConnection,$user,1);
        redirectToHomePage();
    }

    return $user;
}

function tryAutoAuthenticateFromSigninToken($dbConnection) {
    $user = null;
    $signInToken = getLastSignInToken();

    try {
        if(isset($signInToken)) {
            $rememberMe = new RememberMeUserToken();
            $userId = $rememberMe->validateToken($dbConnection,$signInToken);

            if (!isset($userId))
                throw new Exception("Invalid token");

            $user = getUserById($dbConnection, $userId);

            return $user;
        }
    } catch(Exception $ex) {
        throw new Exception("Invalid Email or Password");
    }
    return $user;
}

function tryAutoAuthenticateFromSession($dbConnection) {
    $emailId = getSignedInEmailId();
    $passwordHash = getSignedInPasswordHash();
    $user = null;

    AIGLogger::instance()->addInfo($emailId . ":" .$passwordHash );
    try {
        if(isset($emailId) && isset($passwordHash)) {
            $user = getUserByEmailId($dbConnection, $emailId);

            if (!hash_equals($passwordHash,$user['password_hash']))
                throw new Exception("Invalid Email or Password");
        }
    } catch(Exception $ex) {
        throw new Exception("Invalid Email or Password");
    }
    return $user;
}
