
<?php
include_once "bootstrap.php";
include_once RESOURCE_PATH . "/http-helper.php";
include_once RESOURCE_PATH . "/database.php";
include_once RESOURCE_PATH . "/user-helper.php";
include_once RESOURCE_PATH . "/password-reset-helper.php";
include_once RESOURCE_PATH . "/password-recovery-helper.php";
require_once RESOURCE_PATH . "/info-page-helper.php";

$userId = null;
$emailId = null;
$user = ValidateRecoveryToken($db,$token);
if ($user !== false) {
    $userId = $user['user_id'];
    $emailId = $user['email_id'];
}
if (isPost('btn-change-password')) {
    resetPassword($db);
}

?>

<?php
$title = "Reset Password";
require_once "header-html.php";
?>


<div class="container-flex form-container">

    <?php
    require_once "multiparallax.php";
    ?>

    <article class= "group form-section">
        <h2 class="header-underline group-title">Reset Password</h2>


    <p>Please enter new password for <?php echo $emailId ?></p>
    <form class="clear-fix" method="post" action="">
        <input type="hidden" id="user-id" name="user-id" value="<?php echo isset($userId) ? $userId : '' ?>">
        <input type="hidden" id="token" name="token" value="<?php echo isset($token) ? $token : '' ?>">


        <div class="form-group">
            <label class="sr-only" for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" value ""
            aria-describedby="passwordHelp" placeholder="Password">
        </div>

        <div class="form-group">
            <label class="sr-only" for="confirm-password">Confirm Password</label>
            <input type="password" class="form-control" id="confirm-password" name="confirm-password" value ""
            aria-describedby="confirmpasswordHelp" placeholder="Confirm Password">
        </div>

        <button type="submit" class="btn btn-primary pull-right" id="btn-change-password" name="btn-change-password" value="Change Password">Change Password</button>
    </form>
    </article>

</div>


<?php
require_once "footer-html.php";
?>