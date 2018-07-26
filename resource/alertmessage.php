<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 5/3/2018
 * Time: 10:10 PM
 */


function formatSuccessMessage($successMessage) {
    return
        "<div class='alert alert-success' role='alert'>
         <span class='glyphicon glyphicon-ok'></span>
            $successMessage

        </div>";
}

function formatSuccessMessageWithLink($successMessage,$linkText,$url) {
    $anchor = "<a href='" . $url ."' class='alert-link'>$linkText</a>";
    $messageWithLink = str_replace($linkText,$anchor,$successMessage);

    return
        "<div class='alert alert-success' role='alert'> 
            <span class='glyphicon glyphicon-ok'></span>
            $messageWithLink
        </div>";
}

function formatErrorMessage($errorMessage) {
    return
        "<div class='alert alert-danger' role='alert'>
             <span class='glyphicon glyphicon-exclamation-sign'></span>
             $errorMessage
        </div>";
}

function formatErrorMessageWithLink($errorMessage,$linkText,$url) {

    $anchor = "<a href='" . $url ." class='alert-link'>$linkText</a>";
    $messageWithLink = str_replace($linkText,$anchor,$errorMessage);

    return "<div class='alert alert-danger' role='alert'> 
                <span class='glyphicon glyphicon-exclamation-sign'></span>
                $messageWithLink
            </div>";
}

function formatErrorMessages($messageHeader, $messages) {
/*
    if (count($messages) <= 1) {
        return formatErrorMessage($messageHeader . $messages[0]);
    } else {
  */
        $items = "";
        foreach($messages as $msg) {
            $items .= "<li>$msg</li>";
        }
        $ul = "<ul>$items</ul>";
        return
            "<div class='alert alert-danger' role='alert'>
               <span class='glyphicon glyphicon-exclamation-sign'></span>
                $messageHeader 
                $ul
            </div>";
    //}

}