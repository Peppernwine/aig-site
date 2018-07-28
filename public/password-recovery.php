<?php
include_once "bootstrap.php";
include_once RESOURCE_PATH . "/http-helper.php";
include_once RESOURCE_PATH . "/database.php";
include_once RESOURCE_PATH . "/user-helper.php";
include_once RESOURCE_PATH . "/password-recovery-helper.php";
include_once RESOURCE_PATH . "/CSRFTokenGenerator.class.php";
include_once RESOURCE_PATH . "/alertmessage.php";

$success = false;

if (isPost('btn-recover-password')) {
    $errors = null;
    $emailId = null;
   if (!empty($_POST['email-id'])) $emailId = $_POST['email-id'];
    if (isset($_POST['csrf-token']))
        $csrfToken = $_POST['csrf-token'];
    else
        $csrfToken = '';
    $success = recoverPassword($db,$csrfToken,$errors);
}
?>

<?php
    $stylesheets = ['css/home.css?v35','css/form-container.css'];
    $title = "Password Recovery";
    require_once "header-html.php";
?>

<div class="container-flex form-container">

    <?php
      require_once "multiparallax.php";
    ?>

    <article class= "group form-section">
        <h2 class="header-underline group-title">Password Recovery</h2>

        <?php
            if($success)
                echo formatSuccessMessage("We have emailed you the password reset link. Please click the link in your email to reset your password.");
            elseif (!empty($errors))
                echo formatErrorMessages("Password recovery failed.", $errors);
        ?>

        <p>Please enter your email address and click the Recover Password button. We will send you an email with the password reset link</p>
            <form class="clear-fix" method="post" action="">
                <div class="form-group">
                    <label class="sr-only" for="email-id">Email address</label>
                    <input type="email" class="form-control" id="email-id" name="email-id" aria-describedby="emailHelp" placeholder="Enter email"
                           value="<?php echo isset($emailId) ? $emailId : '' ?>">
                </div>

                <input type="hidden" id="csrf-token" name="csrf-token" value="<?php echo CSRFTokenGenerator::current()->generateToken() ; ?>" >
                <button type="submit" class="btn btn-primary pull-right" id="btn-recover-password" name="btn-recover-password" value="Recover Password">Recover Password</button>

            </form>
            <ul class="nav-bar">
                <li> <a href="signin.php">Sign in</a></li>
                <li> <a href="signup.php">Not a member?</a></li>
            </ul>


    </article>

</div>


<?php
require_once "footer-html.php";
?>