<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/19/2018
 * Time: 12:17 AM
 */

use PHPMailer\PHPMailer\PHPMailer as PHPMailer;


include_once "configuration.class.php";
include_once "form-validation-helper.php";
include_once "user-helper.php";
require_once "PasswordRecoveryToken.class.php";
include_once "SendEmail.class.php";

function encodePasswordRecoveryParam($token) {
    return http_build_query(['token'=>$token]);
}

function decodePasswordRecoveryParam(){
    $token = null;
    if (isset($_GET['token']))
        $token = $_GET['token'];
    return $token;
}

function generatePasswordRecoveryLink($dbConnection,$userId,$linkText) {
    $passwordRecoveryToken = new PasswordRecoveryToken();
    $encodedToken = $passwordRecoveryToken->generateToken($dbConnection,$userId,time() + 24*60*60);

    $tokenParam = encodePasswordRecoveryParam($encodedToken);
    $url = Configuration::instance()->getServerURL("password-reset?$tokenParam");
    return "<a href='{$url}'>$linkText</a>";
}

function validatePasswordRecoveryFields($dbConnection,&$errors) {
    $fieldLabels = array('email-id'=> 'Email');
    $fieldLengths = array('email-id'=> 6);
    $requiredFields = array('email-id');

    $user = null;
    $errors = array();
    $errors = array_merge($errors,validateRequiredFields($_POST,$requiredFields,$fieldLabels));
    $errors = array_merge($errors,validateFieldLengths($_POST,$fieldLengths,$fieldLabels));

    if(empty($errors) && !empty($_POST['email-id'])) {
        $errors = array_merge($errors,validateEmailFormat($_POST['email-id']));
        if (empty($errors)) {
            $user = getUserByEmailId($dbConnection,$_POST['email-id']);
        }
    };
    return $user;
}

function sendPasswordRecoveryEmail($user, $link) {
    $mailer = new SendMail();
    $emailBody = '<html>
                   <body>
                        <h2>Password recovery link</h2>
                        <p>Dear ' . $user['first_name']. ', <br><br> 
                           We have sent you this email because you have requested that your Avon Indian Grill password be reset.
                           Please click on the below link to reset your password:</p>
                        <p>' . $link . '</p>
                   </body>
                   </html>';
    $subject = "Password recovery link from Avon Indian Grill";

    $error = ($mailer->sendTo($user['email_id'],$subject,$emailBody,null));

    if (!is_null($error))
        throw new Exception("Password reset link could not be emailed.");
}

function recoverPassword($dbConnection,$csrfToken,&$errors) {
    $errors = [];
    $success = false;
    try {

        CSRFTokenGenerator::current()->validateToken($csrfToken) ;

        $user = validatePasswordRecoveryFields($dbConnection,$errors );
        if (empty($errors)) {
            //send Activation email..
            sendPasswordRecoveryEmail($user,generatePasswordRecoveryLink($dbConnection,$user['user_id'],'Reset Password'));
            $success = true;
        }
    } catch (Exception $ex) {
        $errors[] = $ex->getMessage();
    }

    return $success;
}

function ValidateRecoveryToken($dbConnection,&$encodedToken) {
    try {
        $encodedToken = null;
        $passwordRecoveryToken = new PasswordRecoveryToken();
        $encodedToken = decodePasswordRecoveryParam();
        $userId = $passwordRecoveryToken->validateToken($dbConnection, $encodedToken);

        return getUserById($dbConnection,$userId);
    } catch (Exception $ex) {
        displayInfoPage(ERROR_MESSAGE,$ex->getMessage() . "Please <a href='password-recovery'> request</a> a new link");
    }
}