<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/16/2018
 * Time: 5:10 PM
 */
include_once "user-helper.php";
include_once "form-validation-helper.php";

function gatherSignupFields(&$firstName,&$lastName,&$emailId,&$rawPassword,&$confirmPassword,&$cellPhone,&$birthMonth,&$birthDay,&$csrfToken) {
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $emailId = $_POST['email-id'];
    $rawPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $cellPhone = $_POST['customer-cell-phone'];
    $birthMonth = $_POST['birth-month'];
    $birthDay = $_POST['birth-day'];
    if (isset($_POST['csrf-token']))
        $csrfToken = $_POST['csrf-token'];
    else
        $csrfToken = '';
}

function validateSignupFields($dbConnection){
    $fieldLabels = array('first-name' => 'First Name','last-name'=> 'Last Name','cell-phone'=> 'Cell Phone',
                         'email-id'=> 'Email   ','password'=> 'Password','confirm-password' => 'Confirm Password');
    $requiredFields = array('first-name','last-name','email-id','password','confirm-password');
    $fieldLengths = array('first-name' => 1,'last-name'=> 1,'customer-cell-phone'=> 10,'email-id'=> 6,'password'=> 8);

    $errors = array();


    $errors = array_merge($errors,validateRequiredFields($_POST,$requiredFields,$fieldLabels));
    $errors = array_merge($errors,validateFieldLengths($_POST,$fieldLengths,$fieldLabels));

    if(!empty($_POST['password'])) {
        $errors = array_merge($errors, validatePassword($_POST['password'], $_POST['confirm-password']));
    }

    if(empty($errors) && !empty($_POST['email-id'])) {
        $errors = array_merge($errors,validateEmailFormat($_POST['email-id']));
        if (empty($errors) && userExists($dbConnection,$_POST['email-id'])) {
            $errors[] = "Email already exists. Please use a different email id";
        }
    };

    return $errors;
}


function signUp($dbConnection,$emailId,$rawPassword,$firstName,$lastName,$cellPhone,$birthMonth,$birthDay,$csrfToken,&$errors) {

    $success = false;
    $errors = [];
    try {
        CSRFTokenGenerator::current()->validateToken($csrfToken) ;
    } catch(Exception $ex) {
        $errors[] = $ex->getMessage();
    }

    if (empty($errors))
        $errors = validateSignupFields($dbConnection);

    if (empty($errors)) {
        try {

            $passwordHash = hashPassword($rawPassword);

            $userId = createUser($dbConnection,$emailId,$passwordHash,$firstName,$lastName,$cellPhone,$birthMonth,$birthDay);

            $fullUrl = generateActivationUrl($dbConnection,$userId);

            notifyActivationLink($emailId,$cellPhone,$firstName,$fullUrl);
            $success = true;
        } catch (Exception $ex) {
            $errors[] = $ex->getMessage();
        }
    }

    return $success;

}
