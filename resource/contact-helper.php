<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/26/2018
 * Time: 1:21 AM
 */
require_once realpath(dirname(__FILE__)) . "/../public_html/bootstrap.php";
include_once "user-helper.php";
include_once "form-validation-helper.php";
include_once "SendEmail.class.php";

function getCommentTypes() {
    return ["Compliments or concerns about recent visit","Questions about our Products or Services. Eg Chef's table, Catering etc",
            "Gift card questions or comments","General question"];
}


function getCommentType($commentType) {
    return getCommentTypes()[$commentType];
}

function gatherCommentFields(&$emailId, &$firstName, &$lastName, &$cellPhone, &$commentType, &$comment, &$csrfToken){

    if (!isPost()) return;

    if (isset($_POST['email-id']))
        $emailId  = $_POST['email-id'] ;
    if (isset($_POST['first-name']))
        $firstName = $_POST['first-name'];
    if (isset($_POST['last-name']))
        $lastName  = $_POST['last-name'];
    if (isset($_POST['cell-phone']))
        $cellPhone = $_POST['cell-phone'];
    if (isset($_POST['comment-type']))
        $commentType = $_POST['comment-type'];
    if (isset($_POST['comment']))
        $comment = $_POST['comment'];
    if (isset($_POST['csrf-token']))
        $csrfToken = $_POST['csrf-token'];
    else
        $csrfToken = '';
}

function gatherCurrentCommentFields($dbConnection,&$emailId,&$firstName,&$lastName,&$cellPhone){

    $user = null;

    if(isSignedIn())
        $user = getSignedinUser($dbConnection);

    if (isset($user)) {
        $emailId     = $user['email_id'] ;
        $firstName   = $user['first_name'];
        $lastName    = $user['last_name'];
        $cellPhone   = $user['cell_phone'];
    }
}

function validateCommentFields(){
    $fieldLabels = array('email-id'=> 'Email','first-name' => 'First Name',
        'last-name'=> 'Last Name','cell-phone'=> 'Cell Phone','comment-type'=> 'Topic','comment'=> 'Comment');
    $requiredFields = array('email-id','first-name','last-name','cell-phone','comment-type','comment');
    $fieldLengths = array('email-id'=> 6,'first-name' => 1,'last-name'=> 1,'cell-phone'=> 10);

    $errors = array();

    if(empty($_POST['comment-type']) || ($_POST['comment-type'] == -1)) {
        $errors[] = "Invalid Topic";
    };

    $errors = array_merge($errors,validateRequiredFields($_POST,$requiredFields,$fieldLabels));
    $errors = array_merge($errors,validateFieldLengths($_POST,$fieldLengths,$fieldLabels));


    if(!empty($_POST['email-id'])) {
        $errors = array_merge($errors,validateEmailFormat($_POST['email-id']));
    };




    return $errors;
}


function sendCommentEmail($fromEmailId, $firstName, $lastName, $cellPhone,$commentType,$comment) {
    $mailer = new SendEmail();
    $commentTypeText = getCommentType($commentType);
    $emailBody = "<html>
                   <body>
                        <h2>$commentTypeText</h2>
                        <p>Dear Customer Support, <br><br> You have a comment/question from $lastName,$firstName</p>
                        <table>
                            <tr>
                                <td>Name</td>
                                <td>$lastName,$firstName</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>$fromEmailId</td>
                            </tr>
                             <tr>
                                <td>Cell Phone</td>
                                <td>$cellPhone</td>
                            </tr>
                             <tr>
                                <td>Comment</td>
                                <td>$comment</td>
                            </tr>
                        </table>
                   </body>
                   </html>";

    $subject = "New comment for Avon Indian Grill - $commentTypeText";

    $error = ($mailer->send($fromEmailId,$lastName.",".$firstName,"info@avonindiangrill.com",$subject,$emailBody,null));

    return $error;
}

function addComment($dbConnection, $emailId, $firstName, $lastName, $cellPhone,$commentType,$comment) {
    $insertUserSQL = "INSERT INTO user_comment(email_id,first_name,last_name,cell_phone,comment_type,comment,submit_date) 
                                        VALUES(:emailId,:firstName,:lastName,:cellPhone,:commentType,:comment, now())";
    $statement = $dbConnection->prepare($insertUserSQL);
    $statement->execute(array(':emailId' => $emailId, ':firstName' => $firstName, ':lastName' => $lastName,
                              ':cellPhone' => $cellPhone,':commentType' => $commentType,':comment' => $comment));

}

function submitComment($dbConnection, $emailId, $firstName, $lastName, $cellPhone,$commentType,$comment,$csrfToken,&$errors){
    $result = null;
    $success = false;
    $errors = [];
    try {
        CSRFTokenGenerator::current()->validateToken($csrfToken) ;
    } catch(Exception $ex) {
        $errors[] = $ex->getMessage();
    }

    if (empty($errors))
        $errors = validateCommentFields();

    if (empty($errors)) {
        try {
            $success = true;
            addComment($dbConnection, $emailId, $firstName, $lastName, $cellPhone,$commentType,$comment);
            sendCommentEmail($emailId, $firstName, $lastName, $cellPhone,$commentType,$comment);
        } catch (Exception $ex) {
            $errors[] = $ex->getMessage();
        }
    }
    return $success;
}
