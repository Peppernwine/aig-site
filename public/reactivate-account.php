
<?php
include_once "bootstrap.php";
include_once RESOURCE_PATH . "/http-helper.php";
include_once RESOURCE_PATH . "/form-validation-helper.php";
include_once RESOURCE_PATH . "/database.php";
include_once RESOURCE_PATH . "/user-helper.php";
include_once RESOURCE_PATH . "/account-activation-helper.php";
include_once RESOURCE_PATH . "/CSRFTokenGenerator.class.php";
include_once RESOURCE_PATH . "/alertmessage.php";

function validateFields($dbConnection,&$errors){
    $fieldLabels = array('email-id'=> 'Email');
    $fieldLengths = array('email-id'=> 6);
    $requiredFields = array('email-id');

    $user = null;
    $errors = array();
    $errors = array_merge($errors,validateRequiredFields($_POST,$requiredFields,$fieldLabels));
    $errors = array_merge($errors,validateFieldLengths($_POST,$fieldLengths,$fieldLabels));

    if(empty($errors) && !empty($_POST['email-id'])) {
        $errors = array_merge($errors,validateEmailFormat($_POST['email-id']));
        if (empty($errors)) {
            $user = getUserByEmailId($dbConnection,$_POST['email-id']);

            $active = $user['active'];
            if ( $active == 1) {
                throw new Exception("Account is already activated. Please sign in or use the Forgot password option to reset your password.");
            }
        }
    };
    return $user;
}

function reactivateAccount($dbConnection,$csrfToken,$emailId,&$errors) {
    $errors = [];
    $success = false;
    try {

        CSRFTokenGenerator::current()->validateToken($csrfToken) ;

        $user = validateFields($dbConnection,$errors );
        if (!empty($user)) {
            //send Activation text/email..
            $fullUrl = generateActivationUrl($dbConnection,$user['user_id']);
            notifyActivationLink($emailId ,$user['cell_phone'],$user['first_name'],$fullUrl);
            $success = true;
        }
    } catch (Exception $ex) {
        $errors[] = $ex->getMessage();
    }

    return $success;
}

$success = false;
if (isPost('btn-activate-account')) {
    $emailId = null;
   if (!empty($_POST['email-id'])) $emailId = $_POST['email-id'];
    if (isset($_POST['csrf-token']))
        $csrfToken = $_POST['csrf-token'];
    else
        $csrfToken = '';
   $success = reactivateAccount($db,$csrfToken,$emailId,$errors);
}
?>


<?php
    $title = "Activate Account";
    require_once "header-html.php";
?>

<div class="container-flex form-container">

    <?php
        require_once "multiparallax.php";
    ?>

    <article class= "group form-section">
        <h2 class="header-underline group-title">Account Activation</h2>

        <?php
            if($success)
                echo formatSuccessMessage("We have emailed you a link. Please click the link in your email to confirm your email id and activate your account.");
            elseif (!empty($errors))
                echo formatErrorMessages("Account activation failed.", $errors);

        ?>

        <p>Please enter your email address and click the Activate Account button. We will re-send you an email with the Account activation link</p>
        <form class="clear-fix" method="post" action="">

            <div class="form-group">
                <label class="sr-only" for="email-id">Email address</label>
                <input type="email" class="form-control" id="email-id" name="email-id" aria-describedby="emailHelp" placeholder="Enter email"
                       value="<?php echo isset($emailId) ? $emailId : '' ?>">
            </div>

            <input type="hidden" id="csrf-token" name="csrf-token" value="<?php echo CSRFTokenGenerator::current()->generateToken() ; ?>" >

            <button type="submit" class="btn btn-primary pull-right" id="btn-activate-account" name="btn-activate-account" value="Activate Account">Activate Account</button>
        </form>

        <ul class="nav-bar">
            <li> <a href="signin.php">Sign in</a></li>
            <li> <a href="signup.php">Not a member?</a></li>
            <li> <a href="password-recovery.php">Forgot password?</a></li>
        </ul>


    </article>

</div>


<?php
require_once "footer-html.php";
?>