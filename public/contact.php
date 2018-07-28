<?php
 require_once "bootstrap.php";
require_once RESOURCE_PATH . "/configuration.class.php";
require_once RESOURCE_PATH . "/database.php";
require_once RESOURCE_PATH . "/http-helper.php";
require_once RESOURCE_PATH . "/user-session.php";
require_once RESOURCE_PATH . "/contact-helper.php";
include_once RESOURCE_PATH . "/CSRFTokenGenerator.class.php";
include_once RESOURCE_PATH . "/alertmessage.php";

$firstName =  null;
$lastName = null;
$emailId = null;
$cellPhone = null;
$commentType = null;
$comment = null;
$errors = null;
$success = false;
$csrfToken = null;

if (isGet())
    gatherCurrentCommentFields($db,$emailId,$firstName,$lastName,$cellPhone);
elseif (isPost('btn-submit-comment')) {
    gatherCommentFields($emailId,$firstName,$lastName,$cellPhone,$commentType,$comment,$csrfToken);
    if (submitComment($db, $emailId, $firstName, $lastName, $cellPhone,$commentType,$comment,$csrfToken,$errors)) {
        $success = true;
        $commentType = null;
        $comment = null;
    }
}

?>

<?php
    $stylesheets = ['css/contact.css?v19'];
    $title = "Contact Us";
    require_once "header-html.php";

    if (isPost('btn-submit-comment')) {
        echo "<script>
        $(function() {
            $('html, body').animate({
                scrollTop: $('#contact-feedback').offset().top
            }, 2000);
        });
        </script>";
    };
?>

        <div class="container">

            <div class="sec-page-header">
            </div>      

            <div class="sec-page-header-caption"> 
                <h2>CONTACT</h2>
            </div>


            <div class="sec-page-header-overlay">

            </div>

            <div class="sec-page-empty-content">

            </div>
            
            <article class= "group clear-fix" id="contact-location">
                <h2 class="header-underline group-title">Location</h2>
                <section class="sub-group">
                        <h3 class="no-margin">Address & Hours</h3>
                        <div style="text-align:center" id="contact-address">
                            <p>320 West Main Street<br>Avon, CT 06001</p>
                            <p>Tel: (860) 284-4466<br>Fax:(860) 404-5319</p>
                            <p><em>Lunch Hours:</em><br>Tue - Fri 11:30am - 2:30pm, Sat &amp; Sun 11:30am - 3pm</p><p><em>Dinner Hours:</em><br> Tue - Thu &amp; Sun 4:30pm - 9:30pm, Fri &amp; Sat 4:30pm - 10pm </p>
                            <p><em>Happy Hour:</em><br>Tue - Fri 4:30pm - 6:30pm</p>
                            <p><em>Closed on Monday</em></p>
                        </div>
                    <div class="clear-fix"></div>
                </section>

                <section class="sub-group">
                    <h3 class="no-margin">Map</h3>
                    <div id="contact-map">

                    </div>
                </section>    
                
            </article>

            <article class="group clear-fix" id="contact-feedback">
                <h2 class="header-underline group-title" >Comments or Questions</h2>

                <!-- <?php echo $_SERVER['PHP_SELF']; ?> -->
                <form class="clear-fix" method="post" action="">

                    <p class="center-text">
                        Please submit the below form and a member of our Customer Service Team will respond within 24-48 hours. You may also call (860) 284-4466 Tuesday through Sunday, 11:30 am to 9:00 pm Eastern Time, for immediate assistance from a Customer Service representative.
                    </p>


                    <?php
                        if($success)
                            echo formatSuccessMessage("Thanks for submitting your comment/question. We will review & respond within 24-48 hours");
                        elseif (!empty($errors))
                            echo formatErrorMessages("Your comment could not be submitted.", $errors);
                    ?>

                    <div class="form-group">
                        <label for="email-id">Email address</label>
                        <input type="email" class="form-control" id="email-id" name="email-id" aria-describedby="emailHelp"
                               value="<?php echo isset($emailId) ? $emailId : '' ?>">
                        <small id="emailHelp" class="form-text text-muted privacy-text">* We'll never share your email with anyone else.</small>
                    </div>

                    <div class="form-group">
                        <label for="first-name">First Name</label>
                        <input type="text" class="form-control" id="first-name" name="first-name" aria-describedby="cellphoneHelp"
                               value="<?php echo isset($firstName) ? $firstName : '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="last-name">Last Name</label>
                        <input type="text" class="form-control" id="last-name" name="last-name" aria-describedby="lastnameHelp"
                               value="<?php echo isset($lastName) ? $lastName : '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="cell-phone">Cell Phone#</label>
                        <input type="text" class="form-control" id="cell-phone" name="cell-phone" aria-describedby="cellphoneHelp"
                        value="<?php echo isset($cellPhone) ? $cellPhone : '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="comment-type">Topic</label>
                        <select id="comment-type" class="form-control" name="comment-type">
                            <option <?php echo (is_null($commentType) || ($commentType == -1) ? "selected" : ""); ?> value="-1">--Select Topic--</option>
                            <option <?php echo (($commentType == 1) ? "selected" : ""); ?> value="1">Compliments or concerns about recent visit</option>
                            <option <?php echo (($commentType == 2) ? "selected" : ""); ?> value="2">Questions about our Products or Services. Eg Chef's table, Catering etc </option>
                            <option <?php echo (($commentType == 3) ? "selected" : ""); ?> value="3">Gift card questions or comments</option>
                            <option <?php echo (($commentType == 4) ? "selected" : ""); ?> value="4">General questions</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="xsr-only" for="comment">Comment</label>
                        <textarea class="form-control" name="comment" id="comment" ><?php
                            echo isset($comment) ? htmlspecialchars($comment) : '' ?></textarea>

                    </div>

                    <input type="hidden" id="csrf-token" name="csrf-token" value="<?php echo CSRFTokenGenerator::current()->generateToken() ; ?>" >
                    <button class="btn btn-primary pull-right" type="submit" name="btn-submit-comment" id="btn-submit-comment" value="Submit">Submit</button>
                </form>

            </article>

        </div>

        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAHRnsu9PnHu_VUr0YKRo397PzscGb_py0&callback=initMap">
        </script>
        
        <script>
             function initMap() {
              var uluru = {lat:41.8146857, lng: -72.85806550000001};
              var map = new google.maps.Map(document.getElementById('contact-map'), {
                zoom: 16,
                center: uluru
              });
              var marker = new google.maps.Marker({
                position: uluru,
                map: map
              });
            }
        </script>

<?php
    require_once "footer-html.php";
?>