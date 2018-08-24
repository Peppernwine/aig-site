<?php

require_once "session.php";
require_once "string-utility.php";

function validateRequiredFields($data,$requiredFields,$fieldLabels) {
    $errors = array();

    foreach ($requiredFields as $fieldName) {
        if (empty($data[$fieldName])) {
            $errors[] = "{$fieldLabels[$fieldName]} is a required field";
        }
    }
    return $errors;
}

function validateFieldLengths($data,$fieldLengths,$fieldLabels) {
    $errors = array();

    foreach ($fieldLengths as $fieldName => $fieldLength) {
        $len = strlen(trim($data[$fieldName]));
        if ($len > 0 && $len < $fieldLength) { //zero length checks would have been caught by validateRequiredFields...
            $errors[] = "{$fieldLabels[$fieldName]} is too short, must be {$fieldLength} characters long";
        }
    }
    return $errors;
}


function formatError($errors, $errorHeader)
{
    $htmlErrors = array();
    if (!empty($errors)) {
        $htmlErrors = "<p class='error-message'>{$errorHeader}</p>";
        $htmlErrors = $htmlErrors . "<ul class='error-message'>";
        foreach ($errors as $error) {
            $htmlErrors = "{$htmlErrors}<li>{$error}</li>";
        }
        $htmlErrors = $htmlErrors . "</ul>";
    }
    return $htmlErrors;
}

