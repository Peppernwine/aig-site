<?php
include_once "bootstrap.php";
include_once RESOURCE_PATH . "/database.php";
include_once RESOURCE_PATH . "/signin-helper.php";
include_once RESOURCE_PATH . "/account-activation-helper.php";
include_once RESOURCE_PATH . "/user-helper.php";
include_once RESOURCE_PATH . "/http-helper.php";
include_once RESOURCE_PATH . "/info-page-helper.php";

$urlLink = null;
$type = ERROR_MESSAGE;
$result = null;
try {
    $useId = activateAccount($db);
    $result = "Your account has been activated, please " . generateSigninLink($db,$useId,"Sign in",Configuration::instance()->getServerURL("index.php"));
    $type = SUCCESS_MESSAGE;
} catch(Exception $ex) {
    $urlLink = generateSigninLink($db,null,"Sign in",Configuration::instance()->getServerURL("index.php"));
    $result = "There was an error activating your account - {$ex->getMessage()}". " ".
              "If you have previously activated your account, please " . $urlLink  ;

}

displayInfoPage($type,$result);

?>

<?php
$stylesheets = ['css/home.css?v35','css/form-container.css'];
$title = "Activate Account";
require_once "header-html.php";
?>


<div class="container-flex form-container">

    <?php
    require_once "multiparallax.php";
    ?>

    <article class= "group form-section">
        <h2 class="header-underline group-title">Activate Account</h2>

    <?php echo $result?>
    </article>

</div>


<?php
require_once "footer-html.php";
?>