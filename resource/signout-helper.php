<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/16/2018
 * Time: 7:42 PM
 */

require_once "user-session.php";
require_once "database.php";
require_once "http-helper.php";
require_once "signin-helper.php";

function signOut($dbConnection) {
    clearSessionUserInfo();
    forgetSigninToken($dbConnection);

    session_destroy();
    session_regenerate_id();

    session_start();
    $_SESSION['do-sign-out-refresh'] = "1";

    redirectToHomePage();
}

