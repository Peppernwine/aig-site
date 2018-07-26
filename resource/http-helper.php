<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/16/2018
 * Time: 5:30 PM
 */

require_once "ExceptionManager.class.php";

function isPost($buttonName = null) {
    $isPost = false;
    if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'POST'))
        $isPost = true;
    if (is_null($buttonName))
        return $isPost;

    $isPost = isset($_POST["$buttonName"]);

    return $isPost;
}


function isGet($paramName = null) {
    $isGet = false;
    if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'GET'))
        $isGet = true;

    if (is_null($paramName))
        return $isGet;

    $isGet = isset($_GET["$paramName"]);

    return $isGet;
}

function redirectTo($page) {
    header("location: {$page}");
}

function redirectWithParamTo($page,$params) {
    header("location: {$page}" . "?" . http_build_query($params));
}

function createTinyUrl($strUrl) {
    $tinyUrl = '';
    $em = new ExceptionManager([]);
    $success = $em->execute( function($strUrl) use(&$tinyUrl){
            $tinyUrl = file_get_contents(TINY_URL_HOST."?url=" . $strUrl);
            return $tinyUrl;
        },[$strUrl,$tinyUrl]
    );

    if ($success)
        return $tinyUrl;
    else
        return $strUrl;
}
