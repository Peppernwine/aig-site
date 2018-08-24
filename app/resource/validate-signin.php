<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/20/2018
 * Time: 10:17 AM
 */

require_once RESOURCE_PATH . "/user-session.php";
require_once RESOURCE_PATH . "/signin-helper.php";
require_once RESOURCE_PATH . "/info-page-helper.php";
require_once RESOURCE_PATH . "/configuration.class.php";
require_once RESOURCE_PATH . "/database.php";


if (isset($security) && isset($security['minUserType'])) {
    if (!isSignedIn()) {
        $urlLink = generateSigninLink(null, null, "Sign in",Configuration::instance()->currentUrl());
        $message = "You are not authorized to access this page.  Please " . $urlLink . " to access this page";
        displayInfoPage(ERROR_MESSAGE, $message);
    } else {
        $currentUserType = getSignedInUserType();

        if (!isset($currentUserType) || ($currentUserType < $security['minUserType'])) {
            $message = "You are not authorized to access this page.";
            displayInfoPage(ERROR_MESSAGE, $message);
        }
    }

}