<?php
require_once "bootstrap.php";
require_once RESOURCE_PATH . "/SendSMS.class.php";
use Twilio\Rest\Client;


// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]

// A Twilio number you own with SMS capabilities
$fromNumber = TWILIO_FROM_PHONE;
$toNumber = '+12033006833';

$sendSMS = new SendSMS();

try {
    $sendSMS->send($fromNumber,$toNumber,'Thanks for your order. Link to view your order-http://de47c7a1.ngrok.io/aig-customer-site/public_html/orderview?orderId=113');

    echo "SMS sent to $toNumber from $fromNumber";

} catch (Twilio\Exceptions\RestException $ex) {
    echo ' Exception Class       : ' . get_class($ex) ;
    echo ' Exception Code        : ' . $ex->getCode() ;
    echo ' Exception Status Code : ' . $ex->getStatusCode();
    echo ' Exception Message     : ' . $ex->getMessage();

}

