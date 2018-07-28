<?php
    $stylesheets = ['css/home.css?v34','css/form-container.css?v3'];
    $title = "Edit Profile";
    $security = ['minUserType' => 1 ];
    require_once "bootstrap.php";
    require_once RESOURCE_PATH . "/validate-signin.php";
?>

<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/20/2018
 * Time: 9:54 AM
 */
require_once RESOURCE_PATH . "/configuration.class.php";
require_once RESOURCE_PATH . "/database.php";
require_once RESOURCE_PATH . "/http-helper.php";
require_once RESOURCE_PATH . "/user-session.php";
require_once RESOURCE_PATH . "/profile-edit-helper.php";
include_once RESOURCE_PATH . "/CSRFTokenGenerator.class.php";
include_once RESOURCE_PATH . "/alertmessage.php";

$userId = null;
$firstName =  null;
$lastName = null;
$emailId = null;
$cellPhone = null;
$profileUpdateSuccess = false;
$passwordUpdateSuccess = false;
$profileUpdateErrors = null;
$passwordUpdateErrors = null;

$csrfToken = null;

$months = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9 =>'Sep',10 => 'Oct',11=>'Nov',12=>'Dec'];
$days = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31];
$birthMonth = 0;
$birthDay = 0;

if (isGet())
    gatherCurrentProfileFields($db, $userId,$emailId, $firstName,$lastName,$cellPhone,$birthMonth,$birthDay);
elseif (isPost('btn-update-profile')) {
    gatherProfileFields($userId, $emailId, $firstName, $lastName, $cellPhone,$birthMonth,$birthDay,$csrfToken);
    $profileUpdateSuccess = updateProfile($db, $userId, $emailId, $firstName, $lastName, $cellPhone,$birthMonth,$birthDay,$csrfToken,$profileUpdateErrors);
} elseif (isPost('btn-change-password')) {
    gatherCurrentProfileFields($db, $userId,$emailId, $firstName,$lastName,$cellPhone,$birthMonth,$birthDay);
    gatherChangePasswordFields($userId,$oldPassword, $newPassword,$confirmPassword,$csrfToken);
    $passwordUpdateSuccess = ChangeProfilePassword($db,$userId,$newPassword,$csrfToken,$passwordUpdateErrors);
}

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

?>

<?php
    require_once "header-html.php";
?>

<div class="container-flex form-container">

    <?php
        require_once "popup-header.html.php";
    ?>

    <article class= "group form-section">
        <h2 class="header-underline group-title">Edit Profile</h2>

        <ul class="nav nav-pills nav-justified">
            <li <?php echo 'class="'.getActiveTabHeaderClass('#personal-info-section'). '"'?> ><a href="#personal-info-section" data-toggle="tab">Personal</a></li>
            <li <?php echo 'class="'.getActiveTabHeaderClass('#change-password-section'). '"' ?> ><a href="#change-password-section" data-toggle="tab">Password</a></li>
           <!-- <li><a href="#preferences-section" data-toggle="tab">Preferences</a></li>-->
        </ul>

        <div class="tab-content">
            <section id="personal-info-section" class="sub-group tab-pane fade<?php echo ' ' . getActiveTabClass('#personal-info-section') .'"'?>  >
                <h3>Personal</h3>

                <?php

                    if($profileUpdateSuccess)
                        echo formatSuccessMessage("Personal information was successfully updated.");
                    elseif (!empty($profileUpdateErrors))
                        echo formatErrorMessages("Personal information could not be updated.", $profileUpdateErrors);
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
                                <input type="text" class="form-control" id="first-name" name="first-name" aria-describedby="firstNameHelp" placeholder="First Name"
                                       value="<?php echo isset($firstName) ? $firstName : '' ?>">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="last-name">Last Name</label>
                                <input type="text" class="form-control" id="last-name" name="last-name" aria-describedby="lastnameHelp" placeholder="Last Name"
                                       value="<?php echo isset($lastName) ? $lastName : '' ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="cell-phone">Cell Phone#</label>
                                <input type="text" class="form-control" id="cell-phone" name="cell-phone" aria-describedby="cellphoneHelp" placeholder="Cell phone#"
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


                    <input type="hidden" id=user-id2" name="user-id"
                                   value="<?php echo isset($userId) ? $userId : '' ?>">

                    <input type="hidden" id="csrf-token" name="csrf-token" value="<?php echo CSRFTokenGenerator::current()->generateToken() ; ?>" >

                    <button type="submit" class="btn btn-primary pull-right" id="btn-update-profile" name="btn-update-profile" value="Update">Update</button>
                </form>

            </section>


            <section id="change-password-section" class="sub-group tab-pane fade <?php echo ' ' . getActiveTabClass('#change-password-section') .'"'?>  >
                <h3>Change Password</h3>

                <?php
                    if($passwordUpdateSuccess)
                        echo formatSuccessMessage("Password was successfully updated.");
                    elseif (!empty($passwordUpdateErrors))
                        echo formatErrorMessages("Password could not be updated.", $passwordUpdateErrors);
                ?>

                <form class = "clear-fix" method="post" action="">
                    <div class="form-group">
                        <label for="current-password">Current Password</label>
                        <input type="password" class="form-control" id="current-password" name="current-password" value ""
                        aria-describedby="current-passwordHelp" >
                    </div>

                    <div class="form-group">
                        <label for="new-password">New Password</label>
                        <input type="password" class="form-control" id="new-password" name="new-password" value ""
                        aria-describedby="newpasswordHelp" >
                    </div>

                    <div class="form-group">
                        <label for="confirm-password">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm-password" name="confirm-password" value ""
                        aria-describedby="confirmpasswordHelp" >
                    </div>

                    <input type="hidden" id=user-id" name="user-id"
                           value="<?php echo isset($userId) ? $userId : '' ?>">
                    <input type="hidden" id="csrf-token2" name="csrf-token" value="<?php echo CSRFTokenGenerator::current()->generateToken() ; ?>" >
                    <button type="submit" class="btn btn-primary pull-right" id="btn-change-password" name="btn-change-password" value="Change Password">Change Password</button>
                </form>

            </section>

            <!--
            <section id="preferences-section" class="sub-group tab-pane fade">
                <h3>Preferences</h3>
            </section>

            -->
         </div>
    </article>
</div>


<?php
require_once "footer-html.php";
?>