<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/20/2018
 * Time: 6:19 PM
 */

include_once "user-helper.php";
include_once "form-validation-helper.php";

function gatherProfileFields(&$userId,&$emailId,&$firstName,&$lastName,&$cellPhone,&$birthMonth,&$birthDay,&$csrfToken){

    if (!isPost()) return;

    if (isset($_POST['user-id']))
        $userId  = $_POST['user-id'];
    if (isset($_POST['email-id']))
        $emailId  = $_POST['email-id'] ;
    if (isset($_POST['first-name']))
        $firstName = $_POST['first-name'];
    if (isset($_POST['last-name']))
        $lastName  = $_POST['last-name'];
    if (isset($_POST['cell-phone']))
        $cellPhone = $_POST['cell-phone'];
    if (isset($_POST['birth-month']))
        $birthMonth = $_POST['birth-month'];
    if (isset($_POST['birth-day']))
        $birthDay = $_POST['birth-day'];
    if (isset($_POST['csrf-token']))
        $csrfToken = $_POST['csrf-token'];
    else
        $csrfToken = '';
}

function gatherCurrentProfileFields($dbConnection,&$userId,&$emailId,&$firstName,&$lastName,&$cellPhone,&$birthMonth,&$birthDay){
    $user = getSignedinUser($dbConnection);

    if (isset($user)) {
        $userId      = $user['user_id'];
        $emailId     = $user['email_id'] ;
        $firstName   = $user['first_name'];
        $lastName    = $user['last_name'];
        $cellPhone   = $user['cell_phone'];
        $birthMonth  = $user['birth_month'];
        $birthDay    = $user['birth_day'];
    }
}

function validateProfileFields($dbConnection){
    $fieldLabels = array('email-id'=> 'Email','first-name' => 'First Name',
                         'last-name'=> 'Last Name','cell-phone'=> 'Cell Phone');
    $requiredFields = array('email-id','first-name','last-name');
    $fieldLengths = array('email-id'=> 6,'first-name' => 1,'last-name'=> 1,'cell-phone'=> 10);

    $errors = array();
    $errors = array_merge($errors,validateRequiredFields($_POST,$requiredFields,$fieldLabels));
    $errors = array_merge($errors,validateFieldLengths($_POST,$fieldLengths,$fieldLabels));


    if(empty($errors) && !empty($_POST['email-id'])) {
        $errors = array_merge($errors,validateEmailFormat($_POST['email-id']));
        if (empty($errors) && anotherUserExists($dbConnection,$_POST['user-id'],$_POST['email-id'])) {
            $errors[] = "Email already exists. Please use a different email id";
        }
    };

    return $errors;
}

function getActiveTabHeaderClass($tabIdToCheck) {
    $result = "";
    if (!isPost() && ($tabIdToCheck == '#personal-info-section'))
        $result = 'active';
    elseif (($tabIdToCheck == '#personal-info-section') && isPost('btn-update-profile'))
        $result = 'active';
    elseif (($tabIdToCheck == '#change-password-section') && isPost('btn-change-password'))
        $result = 'active';

    return $result;
}

function getActiveTabClass($tabIdToCheck) {
    $result = "";

    if (!isPost() && ($tabIdToCheck == '#personal-info-section'))
        $result = 'in active';
    if (($tabIdToCheck == '#personal-info-section') && isPost('btn-update-profile'))
        $result = ' in active ';
    elseif (($tabIdToCheck == '#change-password-section') && isPost('btn-change-password'))
        $result = ' in active ';

    return $result;
}

function updateProfile($dbConnection,$userId,$emailId,$firstName,$lastName,$cellPhone,$birthMonth,$birthDay,$csrfToken,&$errors) {

    $success = false;
    $errors = [];
    try {
        CSRFTokenGenerator::current()->validateToken($csrfToken) ;
    } catch(Exception $ex) {
        $errors[] = $ex->getMessage();
    }

    if (empty($errors))
        $errors = validateProfileFields($dbConnection);

    if (empty($errors)) {
        try {
            updateUserPersonalInfo($dbConnection,$userId,$emailId,$firstName,$lastName,$cellPhone,$birthMonth,$birthDay);
            $success = true;

        } catch (Exception $ex) {
            $errors[] = $ex->getMessage();
        }
    }
    return $success;
}

function gatherChangePasswordFields(&$userId, &$oldPassword, &$newPassword, &$confirmPassword,&$csrfToken) {
    if (!isPost()) return;

    if (isset($_POST['user-id']))
        $userId  = $_POST['user-id'];

    if (isset($_POST['old-password']))
        $oldPassword  = $_POST['old-password'] ;

    if (isset($_POST['new-password']))
        $newPassword = $_POST['new-password'];

    if (isset($_POST['confirm-password']))
        $confirmPassword  = $_POST['confirm-password'];

    if (isset($_POST['csrf-token']))
        $csrfToken = $_POST['csrf-token'];
    else
        $csrfToken = '';
}

function validateChangePasswordFields($dbConnection){
    $fieldLabels    = array('current-password'=> 'Current Password','new-password'=> 'New Password','confirm-password' => 'Confirm Password');
    $requiredFields = array('current-password','new-password','confirm-password');
    $fieldLengths   = array('new-password'=> 8);

    $errors = array();
    $errors = array_merge($errors,validateRequiredFields($_POST,$requiredFields,$fieldLabels));
    $errors = array_merge($errors,validateFieldLengths($_POST,$fieldLengths,$fieldLabels));

    if(!empty($_POST['new-password'])) {
        $errors = array_merge($errors, validatePassword($_POST['new-password'], $_POST['confirm-password']));
    }

    if(empty($errors)) {
        try {
            validatePasswordById($dbConnection,$_POST['user-id'],$_POST['current-password']);
         } catch(Exception $ex) {
            $errors[] = $ex->getMessage();
        }
    };

    return $errors;
}

function ChangeProfilePassword($dbConnection, $userId, $newPassword,$csrfToken,&$errors) {
    $success = false;

    $errors = [];
    try {
        CSRFTokenGenerator::current()->validateToken($csrfToken) ;
    } catch(Exception $ex) {
        $errors[] = $ex->getMessage();
    }

    if (empty($errors))
        $errors = validateChangePasswordFields($dbConnection);

    if (empty($errors)) {
        try {
            $newPasswordHash = hashPassword($newPassword);
            updatePassword($dbConnection,$userId,$newPasswordHash);
            $success = true;
        } catch (Exception $ex) {
            $errors[] = $ex->getMessage();
        }
    }
    return $success;
}