<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/18/2018
 * Time: 9:21 AM
 */

require_once "configuration.class.php";
require_once "AccountActivationToken.class.php";
require_once "SendEmail.class.php";
require_once "SendSMS.class.php";
require_once "http-helper.php";

function encodeAccountActivationParam($token) {
    return http_build_query(['token'=>$token]);
}

function decodeAccountActivationParam(){
    $token = null;
    if (isset($_GET['token']))
        $token = $_GET['token'];
    return $token;
}


function generateActivationUrl($dbConnection, $userId) {
    $accountActivationToken = new AccountActivationToken();
    $encodedToken = $accountActivationToken->generateToken($dbConnection,$userId,time() + 24*60*60);

    $tokenParam = encodeAccountActivationParam($encodedToken);

    $url = Configuration::instance()->getServerURL("account-activation?$tokenParam");
    return $url;
}

function notifyActivationLink($emailId, $cellPhone,$firstName,$url) {
    $shortUrl = createTinyUrl($url);
    $em = new ExceptionManager([new LogExceptionHandler()]);
    $em->execute(function($emailId, $firstName,$url){sendActivationEmail($emailId, $firstName,"<a href='$url'>Activate Account</a>");},[$emailId, $firstName,$url]);
    $em->execute(function($cellPhone, $firstName,$shortUrl){sendActivationText($cellPhone, $firstName,$shortUrl);},[$cellPhone, $firstName,$shortUrl]);
}

function sendActivationText($cellPhone, $firstName,$url) {
    $sms = new SendSMS();
    $sms->sendTo($cellPhone,'Dear ' . $firstName .
                                    ', Thanks for signing up with Avon Indian Grill. Please click on the following link to verify and activate your Account - ' .
        $url );
}

function sendActivationEmail($emailId, $firstName,$url) {
    $mailer = new SendEmail();
    $emailBody = '<html>
                   <body>
                        <h2>Account Activation link</h2>
                        <p>Dear ' . $firstName. ', <br><br> 
                           Thank you for signing up with Avon Indian Grill. Please click on the below link to confirm your email ID & activate your account:</p>
                        <p>' . $url . '</p>
                   </body>
                   </html>';
    $subject = "Account activation link from Avon Indian Grill";
    $mailer->sendTo($emailId,$subject,$emailBody,null);

    /*
    $error = ($mailer->sendTo($emailId,$subject,$emailBody,null));


    if (is_null($error))
        return "<p class='success-message'>Account activation link has been sent to your email.</p>";
    else
        return  "<p class='error-message'>Account activation could not be emailed.".$error ."</p>";
*/
}

function activateAccount($dbConnection) {
    $encodedToken = null;
    $accountActivationToken = new AccountActivationToken();
    $userId = null;
    $encodedToken = decodeAccountActivationParam();
    $userId = $accountActivationToken->validateToken($dbConnection,$encodedToken);

    $activatedFlag = updateUserStatus($dbConnection,$userId,1);

    if ($activatedFlag)
        $accountActivationToken->deleteToken($dbConnection, $encodedToken);
    else
        throw new Exception("Account is either invalid or has already been activated.");

    return $userId;
}