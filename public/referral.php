<?php
    $title = "Referral Program";
    $security = ['minUserType' => 1 ];
    require_once "bootstrap.php";
    require_once RESOURCE_PATH . "/validate-signin.php";
    require_once RESOURCE_PATH . "/CSRFTokenGenerator.class.php";
    require_once RESOURCE_PATH . "/database.php";
    require_once RESOURCE_PATH . "/referral-helper.php";
    require_once "header-html.php";
    $userId = getCurrentUserId($db);
?>


<section class="container">
    <div class="sec-page-empty-content">

    </div>

    <div class="sec-page-header">

    </div>

    <div class="sec-page-header-caption">
        <h2>Referral</h2>
    </div>

    <div class="sec-page-header-overlay">

    </div>

    <article id="referral-list" class= "clear-fix group">
            <h2 class="header-underline group-title">Referral Reward Program</h2>
            <header class="sub-group">
                <div>
                    <p>Referring friends and family and get instant rewards and qualify to win exciting prizes like iPhone, XBOX</p>
                </div>
            </header>

            <div style="height:40rem;position:relative">
                <section style="position:absolute;width:100%;height:100%;background-image: url('images/referral-reward-1200.jpg')">

                </section>

                <section class="sub-group" style="position:absolute;width:40%;background:red;right:5rem;top:5rem">
                    <h3 style="margin:0">Refer a Friend</h3>
                    <p>
                       <span style="font-size: 2rem">Get $12 for every friend you refer.</span>
                        Your friends get $12 off their first-time Grubhub app order of $15+ and you get
                        $12 off on the app after their first order!
                    </p>
                    <form id="referral-form" class="clear-fix" style="padding:2rem;box-shadow:0 8px 20px 0 rgba(0,0,0,.2)">
                        <input type="hidden" id="csrf-token" name="csrf-token" value="<?php echo CSRFTokenGenerator::current()->generateToken() ; ?>" >

                        <input type="hidden" id=user-id" name="user-id"
                               value="<?php echo isset($userId) ? $userId : '' ?>">

                        <div id="customer-section" class="container-flex sub-group">

                            <div class="form-group">
                                <label class="sr-only" for="email-id">Email address</label>
                                <input data-bind="value:recipientEmailId"  type="email" class="form-control" id="email-id" name="email-id" aria-describedby="emailHelp" placeholder="Email address" value="">
                            </div>
                            <button data-bind = "enable:isValidRecipientEmail ,click:sendInvite" type="button" class="btn btn-big btn-2-wide btn-primary pull-right" id="btn-send-referrals" name="btn-send-referrals" value="Send">Send</button>
                        </div>
                    </form>
                </section>
            </div>

        </article>
</section>

<?php
    $scripts = ["referral.js.php"];
    require_once "footer-html.php";
?>