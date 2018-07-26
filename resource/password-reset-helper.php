<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/19/2018
 * Time: 12:17 AM
 */

include_once "form-validation-helper.php";
include_once "user-helper.php";

function validatePasswordResetFields () {
    $fieldLabels = array('user-id' => 'User Id','password'=> 'Password','confirm-password' => 'Confirm Password');
    $requiredFields = array('user-id','password','confirm-password');
    $fieldLengths = array('password'=> 8);

    $errors = array();
    $errors = array_merge($errors,validateRequiredFields($_POST,$requiredFields,$fieldLabels));
    $errors = array_merge($errors,validateFieldLengths($_POST,$fieldLengths,$fieldLabels));

    if(empty($errors) && !empty($_POST['password'])) {
        $errors = array_merge($errors,validatePassword($_POST['password'],$_POST['confirm-password']));
    };

    return $errors;
}

function resetPassword($dbConnection) {
    $result = null;
    try {
        $errors = validatePasswordResetFields();

        if (empty($errors)) {
            $userId = $_POST['user-id'];
            $password = $_POST['password'];
            $user = getUserById($dbConnection,$userId);
            $passwordHash = hashPassword($password);
            updatePassword($dbConnection,$userId, $passwordHash);
            displayInfoPage(SUCCESS_MESSAGE,"Password for {$user['email_id']} has been reset." .
                                                 "Please " . generateSigninLink($dbConnection,$userId,"sign in"));
        } else
            $result = formatError($errors,"Password reset failed:");
    } catch (Exception $ex) {
        $result = "<p class='error-message'>There was an error resetting password - {$ex->getMessage()}</p>";
    }
    return $result;
}