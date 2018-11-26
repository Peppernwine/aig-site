<?php
    include_once "bootstrap.php";
    include_once RESOURCE_PATH . "/database.php";
    include_once RESOURCE_PATH . "/signup-helper.php";
    include_once RESOURCE_PATH . "/user-helper.php";
    include_once RESOURCE_PATH . "/http-helper.php";
    include_once RESOURCE_PATH . "/account-activation-helper.php";
    include_once RESOURCE_PATH . "/CSRFTokenGenerator.class.php";
    include_once RESOURCE_PATH . "/signin-helper.php";
    include_once RESOURCE_PATH . "/alertmessage.php";

    $success = false;
    $errors = null;
    $months = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9 =>'Sep',10 => 'Oct',11=>'Nov',12=>'Dec'];
    $days = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31];
    $birthMonth = 0;
    $birthDay = 0;

    if (isPost('btn-signup')) {
        gatherSignupFields($firstName,$lastName,$emailId,$rawPassword,$confirmPassword,$cellPhone,$birthMonth,$birthDay,$csrfToken);
        $success = signUp($db,$emailId,$rawPassword,$firstName,$lastName,$cellPhone,$birthMonth,$birthDay,$csrfToken,$errors);
        if ($success) {
            $firstName= null;
            $lastName= null;
            $emailId= null;
            $rawPassword= null;
            $confirmPassword= null;
            $cellPhone= null;
            $birthMonth= null;
            $birthDay= null;
        }

    }
?>

<?php
    $title = "Sign up";
    require_once "header-html.php";
?>

<div class="container-flex form-container">

    <?php
    require_once "popup-header.html.php";
    ?>


    <article class= "group form-section">
            <h2 class="header-underline group-title">Sign up</h2>

            <?php
                $dayOpts ="<option value='0'>--Select Day--</option>";
                foreach($days as $day) {
                    $dayOpts .= "<option "
                                . " value='$day'"
                                . ($day == $birthDay ? " selected  " : " ")
                                . " >"
                                . $day
                                . "</option>";
                }

                $monthOpts = "<option value='0'>--Select Month--</option>";
                foreach ($months as $key => $value) {
                    $monthOpts .= "<option "
                                . " value='$key' "
                                . ($key == $birthMonth ? "selected" : " ")
                                . " >"
                                . $value
                                ." </option>";
                 }

                 if($success)
                    echo formatSuccessMessage("We have sent you a link via email/text. Please click the link to confirm and activate your account");
                 elseif (!empty($errors))
                    echo formatErrorMessages("Sign up failed.", $errors);
            ?>

            <form class = "clear-fix" method="post" action="">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="email-id">Email address</label>
                            <input type="email" class="form-control" id="email-id" name="email-id" aria-describedby="emailHelp"
                                   value="<?php echo isset($emailId) ? $emailId : '' ?>">
                            <small id="emailHelp" class="form-text text-muted privacy-text">* We'll never share your email with anyone else.</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="first-name">First Name</label>
                            <input type="text" class="form-control" id="first-name" name="first-name" aria-describedby="firstNameHelp"
                                   value="<?php echo isset($firstName) ? $firstName : '' ?>">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="last-name">Last Name</label>
                            <input type="text" class="form-control" id="last-name" name="last-name" aria-describedby="lastnameHelp"
                                   value="<?php echo isset($lastName) ? $lastName : '' ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="customer-cell-phone">Cell Phone#</label>
                            <input type="text" class="form-control" id="customer-cell-phone" name="customer-cell-phone" aria-describedby="cellphoneHelp"
                                   value="<?php echo isset($cellPhone) ? $cellPhone : '' ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="Birthday">Birthday Month</label>
                            <select class="form-control" id="birth-month" name="birth-month" aria-describedby="birth-monthHelp">
                                <?php
                                     echo $monthOpts ;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="Birthday">Birthday</label>
                            <select class="form-control" id="birth-day" name="birth-day" aria-describedby="birth-dayHelp">
                                <?php
                                    echo $dayOpts;
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" value ""
                            aria-describedby="passwordHelp">
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm-password" name="confirm-password" value ""
                           aria-describedby="confirmpasswordHelp">
                </div>

                <input type="hidden" id="csrf-token" name="csrf-token" value="<?php echo CSRFTokenGenerator::current()->generateToken() ; ?>" >
                <button type="submit" class="btn btn-primary pull-right" id="btn-signup" name="btn-signup" value="Sign up">Sign up</button>
            </form>
            <ul class="nav-bar">
                <li> <a href="signin.php">I have an account</a></li>
                <li> <a href="password-recovery.php">Forgot password?</a></li>
            </ul>
    </article>
</div>


<?php
require_once "footer-html.php";
?>