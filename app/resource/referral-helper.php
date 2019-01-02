<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/20/2018
 * Time: 6:19 PM
 */

include_once "user-helper.php";


function getCurrentUserId($dbConnection)
{
    $user = getSignedinUser($dbConnection);

    $userId = null;
    if (isset($user)) {
        $userId = $user['user_id'];
    }

    return $userId;
}


