<?php

include_once "bootstrap.php";
include_once RESOURCE_PATH . "/user-session.php";
include_once RESOURCE_PATH . "/database.php";
include_once RESOURCE_PATH . "/signin-helper.php";
include_once RESOURCE_PATH . "/form-validation-helper.php";
include_once RESOURCE_PATH . "/http-helper.php";
include_once RESOURCE_PATH . "/CSRFTokenGenerator.class.php";
include_once RESOURCE_PATH . "/alertmessage.php";

$errors = null;
$emailId = null;
$password = null;
$rememberMeFlag = 0;
$manualAuthenticate = false;
$success = false;
$redirectLocation = null;


if (isPost('btn-signin')) {
    gatherSignInFields($emailId,$password,$rememberMeFlag,$csrfToken,$redirectLocation);
    $success = signIn($db,$emailId,$password,$rememberMeFlag,$csrfToken,$redirectLocation,$errors);
} else {
    gatherSigninDefault($emailId,$rememberMeFlag,$redirectLocation);
    if($rememberMeFlag)
        tryAutoSignIn($db);
}
?>

<?php
$title = "Sign in";
require_once "header-html.php";
?>

<div class="container-flex form-container">

    <?php
    require_once "popup-header.html.php";
    ?>


    <article class= "group form-section ">
        <h2 class="header-underline group-title">Sign in</h2>

        <?php
            if (!empty($errors))
                echo formatErrorMessages("Sign in failed.", $errors);
        ?>

        <form class = "clear-fix" method="post" action="">
            <div class="form-group">
                <label for="email-id">Email address</label>
                <input type="email" class="form-control" id="email-id" name="email-id" aria-describedby="emailHelp" placeholder="Enter email"
                       value="<?php echo isset($emailId) ? $emailId : '' ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            </div>

            <div class="form-group form-check">
                <input type="hidden" name="cb-remember-me" value= "0">
                <input type="checkbox" class="form-check-input pull-left" id="cb-remember-me" name="cb-remember-me" value= "1"
                <?php echo ($rememberMeFlag == '1' ? 'checked' : '');?> >
                <label class="form-check-label pull-left" for="cb-remember-me" id="lbl-signedin">Keep me signed in</label>
            </div>
            <button type="submit" class="btn btn-primary pull-right" id="btn-signin" name="btn-signin" >Sign in</button>
            <input type="hidden" id="csrf-token" name="csrf-token" value="<?php echo CSRFTokenGenerator::current()->generateToken() ; ?>" >
            <input type="hidden" id="location" name="location" value="<?php echo $redirectLocation ; ?>" >
        </form>

        <ul class="nav-bar">
            <li> <a href="password-recovery.php">Forgot password?</a></li>
            <li> <a href="signup.php">Not a Member?</a></li>
            <li> <a href="reactivate-account.php">Activate Account</a></li>
        </ul>
    </article>

</div>


<?php
require_once "footer-html.php";
?>