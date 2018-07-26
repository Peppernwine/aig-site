<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/19/2018
 * Time: 9:39 PM
 */

require_once dirname(__FILE__) . "/../bootstrap.php";
require_once RESOURCE_PATH . "/database.php";
require_once RESOURCE_PATH . "/authentication-helper.php";
require_once RESOURCE_PATH . "/user-session.php";


$username = null;
$password = null;

// mod_php
if (isset($_SERVER['PHP_AUTH_USER'])) {
    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];

// most other servers
} elseif (isset($_SERVER['HTTP_AUTHENTICATION'])) {

    if (strpos(strtolower($_SERVER['HTTP_AUTHENTICATION']),'basic')===0)
        list($username,$password) = explode(':',base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));

}

if (is_null($username)) {

    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';

    die();

} else {
    $user = validateCredentials($db,$username,$password);
    persistUserInfo($db,$user,0);
}

?>