<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/19/2018
 * Time: 1:54 PM
 */

require_once "bootstrap.php";
require_once RESOURCE_PATH . "/info-page-helper.php";

$type = null;
$message = null;
decodeInfoPageParams($type, $message) ;
$color = 'green';
switch ($type) {
    case SUCCESS_MESSAGE:
        $messageType = 'Success';
        $class = 'success-message';
        $iconClass = "success-message fa fa-check";
        break;
    case WARNING_MESSAGE:
        $messageType = 'Warning';
        $class = 'warning-message';
        $iconClass = "warning-message fa fa-exclamation-triangle";
        break;
    case ERROR_MESSAGE:
        $color = 'red';
        $messageType = 'Error';
        $class = 'error-message';
        $iconClass = "error-message fa fa-exclamation-triangle";
        break;
    default:
        $type = INFORMATION_MESSAGE;
        $messageType = 'Information';
        $class = '';
        $iconClass = "fa fa-info" ;
}
?>
<!DOCTYPE html>

<html>

<head lang="en">
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $messageType?></title>
    <?php echo Configuration::instance()->getInternalCSSTag("css/font.css?v14")?>
    <?php echo Configuration::instance()->getInternalCSSTag("css/base.css?v191")?>
    <?php echo Configuration::instance()->getInternalCSSTag("css/style.css?v26")?>
    <?php echo Configuration::instance()->getInternalVendorCSSTag("vendor/font-awesome/css/fontawesome-all.css")?>
</head>

<body>
    <section class="form-section" id="info-section">
        <div style='color:black;background-color:rgba(239,141,36,.8);text-align:center;padding:10px;position:absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);width:600px;max-width:90%;box-shadow: 0px 0px 20px 5px rgba(0, 0, 0, .6)'>
           <div style="margin:0px;color:darkslategray;padding:5px;border-bottom: 1px solid black">
           <img width="100px" src="images/logo.png"></td>
           <!--
           <p style="font-size:.65rem;margin:0px">320 West Main Street, Avon, CT-06001</p>
           <p style="font-size:.65rem;margin:0px">(860)-284-4466</p>
           <p style="font-size:.65rem;margin:0px">www.avonindiangrill.com</p>
           <p style="font-size:.65rem;margin:0px">info@avonindiangrill.com</p>

           -->
           </div>

           <h3 style="margin:5px 10px 5px 5px;color:white;text-align: center">
               <span><i <?php echo "style='color:$color'" . " class='$iconClass'"?> aria-hidden="true"></i> </span>
               <?php echo $messageType ?>
           </h3>
           <p style="margin-top:0px;color:white;text-align: center"> <?php echo $message?></p>
       </div>
    </section>
</body>




</html>