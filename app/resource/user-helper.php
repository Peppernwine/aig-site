<?php

require_once "session.php";
require_once "string-utility.php";


function validateEmailFormat($emailId) {
    $errors = array();
    if (!filter_var($emailId, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email ID is not valid";
    };
    return $errors;
}

function validatePassword($newPassword, $repeatPassword) {
    $errors = array();
    if (strlen($newPassword) <= '8') {
        $errors[] = "Your Password Must Contain At Least 8 Characters!";
    };

    if(!preg_match("#[0-9]+#",$newPassword)) {
        $errors[] = "Your Password Must Contain At Least 1 Number!";
    };

    if(!preg_match("#[A-Z]+#",$newPassword)) {
        $errors[] = "Your Password Must Contain At Least 1 Capital Letter!";
    };

    if(!preg_match("#[a-z]+#",$newPassword)) {
        $errors[]= "Your Password Must Contain At Least 1 Lowercase Letter!";
    };

    if (empty($errors) && ($newPassword !== $repeatPassword) ) {
        $errors[] = "Passwords do not match";
    };
    return $errors;
}

function getUserById($dbConnection, $userId) {
    $selectSQL = "SELECT * FROM user WHERE user_id = :userId";
    $statement = $dbConnection->prepare($selectSQL);
    $statement->execute(array(':userId' => $userId));
    $row = $statement->fetch();
    if ($row === false) {
        throw new Exception("Invalid User ID");
    }
    return $row;
}

function userExists($dbConnection, $emailId) {
    $selectSQL = "SELECT email_id FROM user WHERE email_id = :emailId ";
    $statement = $dbConnection->prepare($selectSQL);
    $statement->execute(array(':emailId' => $emailId));

    $row = $statement->fetch();

    return ($row === false) ? false : true;
}

function anotherUserExists($dbConnection, $userId, $emailId) {
    $selectSQL = "SELECT email_id FROM user WHERE email_id = :emailId AND user_id <> :userId";
    $statement = $dbConnection->prepare($selectSQL);
    $statement->execute(array(':userId' => $userId,':emailId' => $emailId));

    $row = $statement->fetch();

    return ($row === false) ? false : true;
}

function userActivated($dbConnection, $emailId) {
    $selectSQL = "SELECT email_id FROM user WHERE email_id = :emailId AND active = 1";
    $statement = $dbConnection->prepare($selectSQL);
    $statement->execute(array(':emailId' => $emailId));

    $row = $statement->fetch();

    return ($row === false) ? false : true;
}

function getUserByEmailId($dbConnection, $emailId) {
    $selectSQL = "SELECT * FROM user WHERE email_id = :emailId";
    $statement = $dbConnection->prepare($selectSQL);
    $statement->execute(array(':emailId' => $emailId));
    $row = $statement->fetch();
    if ($row === false) {
        throw new Exception("Email ID does not exist in our system");
    }
    return $row;
}

/*
 *
 *
USER_TYPE_ID
1 = customer
2 = partner
3 = staff
4 = admin
5 = superuser
 */
function createUser($dbConnection, $emailId,$passwordHash,$firstName,$lastName,$cellPhone,$birthMonth,$birthDay) {
    $insertUserSQL = "INSERT INTO user(email_id,user_type_id, password_hash,first_name,last_name,cell_phone,birth_month,birth_day,join_date,active) 
                                VALUES(:emailId,1,:passwordHash,:firstName,:lastName,:cellPhone,:birthMonth,:birthDay, now(),0)";
    $statement = $dbConnection->prepare($insertUserSQL);
    $statement->execute(array(':emailId' => $emailId, ':passwordHash' => $passwordHash,
        ':firstName' => $firstName, ':lastName' => $lastName,
        ':cellPhone' => $cellPhone,':birthMonth' => $birthMonth,':birthDay' => $birthDay));

    return $dbConnection->lastInsertId();
}

function updateUserPersonalInfo($dbConnection, $userId, $emailId, $firstName, $lastName, $cellNumber,$birthMonth,$birthDay)
{
    $updateUserSQL = "UPDATE user SET 
                        email_id = :emailId, first_name = :firstName,
                        last_name = :lastName, cell_phone = :cellNumber,
                        birth_month = :birthMonth,birth_day = :birthDay
                      WHERE 
                        user_id = :userId";
    $statement = $dbConnection->prepare($updateUserSQL);
    $statement->execute(array(':emailId' => $emailId, ':firstName' => $firstName,
        ':lastName' => $lastName, ':cellNumber' => $cellNumber,
        ':birthMonth' => $birthMonth,':birthDay' => $birthDay,':userId' => $userId));
}


function updateUserStatus($dbConnection, $userId,$active) {
    $updateUserSQL = "UPDATE user SET active = :active WHERE user_id = :userId AND active = :inActive";
    $statement = $dbConnection->prepare($updateUserSQL);
    $statement->execute(array(':active' => $active,':userId'=> $userId, ':inActive' => 0));
    if ($statement->rowCount() == 0)
        return false;
    else
        return true;
}


function hashPassword($rawPassword) {
    return  password_hash($rawPassword, PASSWORD_DEFAULT);
}

function updatePassword($dbConnection, $userId, $passwordHash) {
    $updatePasswordSQL = "UPDATE user SET password_hash = :passwordHash WHERE user_id = :userId";
    $statement = $dbConnection->prepare($updatePasswordSQL);
    $statement->execute(array(':userId'=> $userId, ':passwordHash' => $passwordHash));
    if ($statement->rowCount() == 0)
        throw new Exception("XXXPassword reset failed" . $userId);
}

