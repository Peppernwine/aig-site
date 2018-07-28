<?php
    $stylesheets = ['css/home.css?v34','css/form-container.css'];
    $title = "Reservation";
    require_once "path.php";
?>

<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/20/2018
 * Time: 9:54 AM
 */
require_once "bootstrap.php";
require_once RESOURCE_PATH . "/configuration.class.php";
require_once RESOURCE_PATH . "/database.php";
require_once RESOURCE_PATH . "/http-helper.php";
require_once RESOURCE_PATH . "/user-session.php";
require_once  RESOURCE_PATH . "/ReserveOccasionDAO.class.php";
require_once RESOURCE_PATH . "/reservation-helper.php";

include_once RESOURCE_PATH . "/CSRFTokenGenerator.class.php";
include_once RESOURCE_PATH . "/alertmessage.php";

$errors = null;
$reservationId = null;

$occasionOpts = '';
if (isGet())
    gatherReservationDefaults($db, $customerId, $customerEmailId, $customerFirstName,
                            $customerLastName, $customerCellPhone,$reservationName,
                            $requestDate,$requestTime,$guestCount,$occasionId,
                            $instructions,$csrfToken);
elseif (isPost('btn-reserve')) {
    gatherReservationFields($customerId, $customerEmailId, $customerFirstName, $customerLastName, $customerCellPhone,$reservationName,$requestDate,$requestTime,$guestCount,$occasionId,$instructions,$csrfToken);

    $reservationId = createReservation($db, $customerId, $customerEmailId, $customerFirstName, $customerLastName,
                                       $customerCellPhone,$reservationName,$requestDate,$requestTime,$guestCount,
                                       $occasionId,$instructions,$csrfToken,$reservationUrl,$errors);
    if ($reservationId > 0) gatherReservationDefaults($db, $customerId, $customerEmailId, $customerFirstName,
        $customerLastName, $customerCellPhone,$reservationName,
        $requestDate,$requestTime,$guestCount,$occasionId,
        $instructions,$csrfToken);
}

$occasionDAO = new ReserveOccasionDAO();

$occasions = $occasionDAO->getAllReserveOccasions($db);

$occasionOpts = "<option value='0'>--Select Occasion--</option>";
foreach($occasions as $occasion) {
    $listOccasionId = $occasion->getOccasionId();
    $listOccasionCode = $occasion->getOccasionCode();

    $selected = $occasionId === $listOccasionId ? 'selected' : '';
    $occasionOpts .= "<option $selected value='$listOccasionId'>$listOccasionCode</option>";
}


$requestTimeOpts = "<option value='0'>--Select Time--</option>";
$allowedTimes = ['11:30 AM','12:00 PM','12:30 PM','1:00 PM','1:30 PM','4:30 PM','5:00 PM','5:30 PM','6:00 PM','6:30 PM','7:00 PM','7:30 PM','8:00 PM'];
foreach($allowedTimes as $allowedTime) {
    $selected = $requestTime === $allowedTime ? 'selected' : '';
    $requestTimeOpts .= "<option $selected value='$allowedTime'>$allowedTime</option>";
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
        <h2 class="header-underline group-title">Reservation</h2>

        <section id="reservation-section" class="sub-group">

            <div class="container-flex">
                <div class="row">
                    <div class="col-xs-12">
                        <?php
                            if($reservationId > 0) {
                                echo formatSuccessMessageWithLink("Reservation#".$reservationId ." was successfully created.",
                                                            "Reservation#".$reservationId,
                                                            $reservationUrl);

                                if (!empty($errors))
                                    echo formatErrorMessage($errors[0]);
                            }
                            elseif (!empty($errors))
                                echo formatErrorMessages("Reservation could not be created.",$errors);
                        ?>
                     </div>
                </div>

                <form autocomplete="off" class = "clear-fix" method="post" action="">

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="customer-email-id">Email address</label>
                                <input type="email" class="form-control" id="customer-email-id" name="customer-email-id" aria-describedby="emailHelp"
                                       value="<?php echo isset($customerEmailId) ? $customerEmailId : '' ?>">

                                <small id="emailHelp" class="form-text text-muted privacy-text">* We'll never share your email with anyone else.</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="customer-first-name">First Name</label>
                                <input type="text" class="form-control" id="customer-first-name" name="customer-first-name" aria-describedby="firstNameHelp"
                                       value="<?php echo isset($customerFirstName) ? $customerFirstName : '' ?>">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="customer-last-name">Last Name</label>
                                <input type="text" class="form-control" id="customer-last-name" name="customer-last-name" aria-describedby="lastnameHelp"
                                       value="<?php echo isset($customerLastName) ? $customerLastName : '' ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="customer-cell-phone">Cell Phone#</label>
                                <input type="text" class="form-control" id="customer-cell-phone" name="customer-cell-phone" aria-describedby="cellphoneHelp"
                                       value="<?php echo isset($customerCellPhone) ? $customerCellPhone : '' ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="request-date">Request Date</label>
                                <input type="text" class="form-control" id="request-date" name="request-date"
                                       aria-describedby="requestDateHelp"
                                       value="<?php echo isset($requestDate) ? $requestDate : '' ?>">
                            </div>
                        </div>

                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="request-time">Request Time</label>
                                <select type="text" class="form-control" id="request-time" name="request-time"
                                       aria-describedby="requestTimeHelp">
                                        <?php echo $requestTimeOpts?>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="guest-count">Guest Count</label>
                                <input type="text" class="form-control" id="guest-count" name="guest-count"
                                       aria-describedby="guestCountHelp"
                                       value="<?php echo isset($guestCount) ? $guestCount : '' ?>">
                            </div>

                        </div>

                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="reservation-name">Reservation Name</label>
                                <input type="text" class="form-control" id="reservation-name" name="reservation-name"
                                       aria-describedby="reservationNameHelp"
                                       value="<?php echo isset($reservationName) ? $reservationName : '' ?>">
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="occasion-id">Occasion</label>
                                <select id="occasion-id" class="form-control" name="occasion-id">
                                    <?php echo $occasionOpts?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="instructions">Special Instructions</label>
                                <textarea class="form-control" rows="5" name="instructions" id="instructions"><?php echo isset($instructions) ? htmlspecialchars($instructions) : '' ?></textarea>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id=customer-id2" name="customer-id"value="<?php echo isset($customerId) ? $customerId : '' ?>">

                    <input type="hidden" id="csrf-token" name="csrf-token" value="<?php echo $csrfToken ?>" >

                    <button type="submit" class="btn btn-primary pull-right" id="btn-reserve" name="btn-reserve" value="Reserve">Reserve</button>
                </form>
            </div>

        </section>

    </article>
</div>

<script>
    $('#request-date').datetimepicker(   {
        timepicker:false,
        format:'m/d/Y',
        minDate:0,
        maxDate:'+1970/01/07',
        disabledWeekDays:[1]
    });

    allowNumbersOnly('#guest-count',false);
</script>

<?php
require_once "footer-html.php";
?>