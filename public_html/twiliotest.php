<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 7/5/2018
 * Time: 9:27 AM
 */

require_once "bootstrap.php";

use Twilio\Twiml;

$response = new Twiml;
$response->say("Hello World!");

header("content-type: text/xml");
echo $response;