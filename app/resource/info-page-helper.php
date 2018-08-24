<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/19/2018
 * Time: 2:19 PM
 */


DEFINE ('INFORMATION_MESSAGE','1');
DEFINE ('SUCCESS_MESSAGE','2');
DEFINE ('WARNING_MESSAGE','3');
DEFINE ('ERROR_MESSAGE','4');

function displayInfoPage($type, $message) {
    $params =  ['data' => base64_encode($type. '|:|'. $message)];
    header("location: info" . "?" . http_build_query($params));
}

function decodeInfoPageParams(&$type, &$message) {
    $type = null;
    $message = null;
    if (!empty($_GET['data'])) {
        $decodedData = base64_decode($_GET['data']);
        list ($type, $message) = explode('|:|', $decodedData);
    }
}


