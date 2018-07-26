<?php

include_once "path.php";
include_once RESOURCE_PATH . "/user-session.php";
include_once "bootstrap.php";
include_once RESOURCE_PATH . "/database.php";
include_once RESOURCE_PATH . "/signin-helper.php";
include_once RESOURCE_PATH . "/form-validation-helper.php";
include_once RESOURCE_PATH . "/http-helper.php";
include_once RESOURCE_PATH . "/CSRFTokenGenerator.class.php";

$errors = array();
$emailId = null;
$password = null;
$rememberMeFlag = 0;
$manualAuthenticate = false;
$success = false;

gatherSignInFields($emailId,$password,$rememberMeFlag,$manualAuthenticate,$csrfToken);

$success = signIn($db,$emailId,$password,$rememberMeFlag,$manualAuthenticate,$csrfToken,$result);

?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in - Avon Indian Grill</title>
    <link type="text/css" rel="stylesheet" href="css/style.css?v21"> </link>
    <link type="text/css" rel="stylesheet" href="css/base.css?v3"> </link>
</head>

<body>
    <?php
    if (isset($_SESSION['do-sign-out-refresh']) && ($_SESSION['do-sign-out-refresh'] === '1')) {
        $_SESSION['do-sign-out-refresh'] = "0";
        $cond = "true";
    } else
        $cond = "false";

    echo '<script>
        if (' . $cond . ' ) {
            parent.location.reload();
        }
    </script>';
?>

    <article class="signin-section">

        <h1>Sign in</h1>
        <form method="post" action="">
            <input class="form-control" type="email" id="email-id" name="email-id" placeholder="Email"
                   value="<?php echo isset($emailId) ? $emailId : '' ?>">
            <input class="form-control" type="password" id="password" name="password" placeholder="Password">
            <input type="hidden" name="cb-remember-me" value= "0">
            <input class="single-line-form-control" type="checkbox" id="cb-remember-me" name="cb-remember-me" value= "1"
                <?php echo ($rememberMeFlag == '1' ? 'checked' : '');?> >
            <span id="lbl-signedin" >Keep me signed in</span>

            <input type="hidden" id="csrf-token" name="csrf-token" value="<?php echo CSRFTokenGenerator::current()->generateToken() ; ?>" >
            <input class="form-control submit-button" type="submit" id="btn-signin" name="btn-signin" value="Sign in">
        </form>
        <ul class="nav-bar">
            <li> <a href="reactivate-account.php">Activate Account</a></li>
            <li> <a href="password-recovery.php">Forgot password?</a></li>
            <li> <a href="signup.php">Not a Member?</a></li>
        </ul>
        <?php
        if(isset($result)) {
            echo $result;
        };


        ?>
        <!--    </div>
        </div> -->
    </article>
</body>

</html>