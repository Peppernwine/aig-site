<?php
include_once "bootstrap.php";
require_once RESOURCE_PATH . "/user-session.php";

if (isSignedIn()) {
    $signedin = true;
    $signinLabel = "Hi " . getSignedInFirstMame();
} else {
    $signedin = false;
    $signinLabel = "SIGN IN";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo isset($title) ?  "$title-" : "" ; ?>Avon Indian Grill</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">

        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=EB+Garamond:400,400i" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat|Old+Standard+TT:700|Open+Sans|Passion+One|Philosopher:700|Volkhov" rel="stylesheet">

        <?php echo Configuration::instance()->getInternalVendorCSSTag("vendor/bootstrap/css/bootstrap.min.css?v2")?>
        <?php echo Configuration::instance()->getInternalVendorCSSTag("vendor/font-awesome/css/fontawesome-all.css")?>
        <?php echo Configuration::instance()->getInternalVendorCSSTag("vendor/flickity/flickity.css")?>
        <?php echo Configuration::instance()->getInternalVendorCSSTag("vendor/datetimepicker/jquery.datetimepicker.min.css?v2")?>

        <?php echo Configuration::instance()->getInternalCSSTag("css/font.css?v14")?>
        <?php echo Configuration::instance()->getInternalCSSTag("css/base.css?v207")?>
        <?php echo Configuration::instance()->getInternalCSSTag("css/header.css?v92")?>
        <?php echo Configuration::instance()->getInternalCSSTag("css/style.css?v26")?>
        <?php echo Configuration::instance()->getInternalCSSTag("css/footer.css?v33")?>

        <?php
            if (isset($stylesheets)) {
                foreach ($stylesheets as $stylesheet) {
                    echo "<link rel='stylesheet' href='$stylesheet'/>";
                }
            }
        ?>
        <?php echo Configuration::instance()->getInternalVendorJSTag("vendor/jquery/jquery-3.3.1.min.js")?>
        <?php echo Configuration::instance()->getInternalVendorJSTag("vendor/blast/jquery.blast.min.js?v2")?>
        <?php echo Configuration::instance()->getInternalVendorJSTag("vendor/blast/velocity.min.js?v2")?>
        <?php echo Configuration::instance()->getInternalVendorJSTag("vendor/knockout/knockout-3.4.2.js")?>
        <?php echo Configuration::instance()->getInternalVendorJSTag("vendor/knockout/knockout.mapping.js?v4")?>
        <?php echo Configuration::instance()->getInternalVendorJSTag("vendor/bootstrap/js/bootstrap.min.js")?>
        <?php echo Configuration::instance()->getInternalVendorJSTag("vendor/bootbox/bootbox.min.js")?>
        <?php echo Configuration::instance()->getInternalVendorJSTag("vendor/flickity/flickity.pkgd.min.js")?>
        <?php echo Configuration::instance()->getInternalVendorJSTag("vendor/datetimepicker/jquery.datetimepicker.full.min.js?v2")?>
        <?php echo Configuration::instance()->getInternalJSTag("js/main.js?v41")?>
        <?php echo Configuration::instance()->getInternalJSTag("js/referencedata.js")?>

    </head>

    <body>
        <header>
            <div class="top-bar">
                <ul class="top-bar-contact">
                    <li style="display: none" class="address"><a  href="tel:8602844466"><i style="color:  darkorange" class="fa fa-map-marker fa-fw"></i>320 West Main Street, Avon, CT 06001</a></li>
                  <!--  <li class="phone"><a  href="tel:8602844466">(860)284-4466</a><i style="color: darkorange" class="fa fa-phone"></i></li>-->
                    <li style="display: none"><a class="phone" href="tel:8602844466">(860) 284-4466</a></li>
                    <li style="display: none"><a class="email" href="email:info@avonindiangrill.com">info@avonindiangrill.com</a></li>
                </ul>

                <ul class="bar-nav" >

                            <?php
                                if ($signedin) {
                                    echo "<li class='nav-item dropdown'>";
                                    echo "<i style='margin:0 1px;padding:0px 5px 0px 0px;color:darkorange' class='fa fa-unlock'></i>";
                                    echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>";
                                    echo "MY ACCOUNT";
                                    echo "<span class='caret'></span>";
                                    echo "</a>";
                                } else {
                                    echo "<li>";
                                    echo "<i style='margin:0 1px;padding:0px 5px 0px 0px;color:darkorange' class='fa fa-lock'></i> ";
                                    echo "<a href='signin' >";
                                    echo "SIGN IN";
                                    echo "</a>";
                                }
                             ?>

                        

                        <?php
                            if ($signedin) {
                                $welcomeMsg = "Welcome back " . getSignedInFirstMame();
                                echo "<ul class='dropdown-menu' aria-labelledby='navbarDropdown'>
                                        <li><a href='#'>$welcomeMsg</a></li>
                                        <li role='separator' class='divider'></li>";


                                        if (getSignedInUserType() == 5)
                                        {
                                          echo "  <li><a class='dropdown-item' href='reserve-occasion'>Occasions</a></li>
                                                  <li><a class='dropdown-item' href='menu-type'>Menu Type</a></li>
                                                  <li><a class='dropdown-item' href='menu-type-hour'>Menu Hours</a></li>
                                                  <li><a class='dropdown-item' href='menu-category'>Menu Category</a></li>
                                                  <li><a class='dropdown-item' href='menu-option'>Menu Options</a></li>
                                                  <li><a class='dropdown-item' href='menu-item-profile'>Menu Item Profile</a></li>
                                                  <li><a class='dropdown-item' href='menu-item-option'>Menu Profile - Options & Choices</a></li>
                                                  <li><a class='dropdown-item' href='menu-item'>Menu</a></li>
                                                  <li role='separator' class='divider'></li>";
        
                                        }

                                      echo "<li><a class='dropdown-item' href='profile-edit'>Edit Profile</a></li>
                                            <li><a class='dropdown-item' href='profile-edit'>Change Password</a></li>
                                            <li><a class='dropdown-item' href='signout'>Sign out</a></li>";

                                echo "</ul>";
                            }
                            echo "</li>";
                        ?>
                        
                        
                   <!--
                       <div  class="dropdown-menu">

                           <a href="#" class="dialog-close-btn"></a>
                           <iframe id="signin-frame" src="signin"></iframe>
                        </div>


-->
                   <?php
                        echo "<li class='nav-item dropdown'>";
                        echo "<i style='margin:0 1px;padding:0px 5px 0px 0px;color:darkorange' class='fas fa-shopping-cart'></i>";
                        echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>";
                        echo "Order Online";
                        echo "<span class='caret'></span>";
                        echo "</a>";
                        echo "<ul class='dropdown-menu' aria-labelledby='navbarDropdown'>";
                             echo "<li><a class='dropdown-item' href='menu.php'>Pickup Order</a></li>";
                             echo "<li><a class='dropdown-item' href='https://www.dineinct.com/single/order/restaurant/avon-indian-grill/27'>Delivery Order</a></li>";
                             if ($signedin) {
                                 echo "<li><a class='dropdown-item' href='my-orders'>Past Orders</a></li>";
                             }
                        echo "</ul>";
                        echo "</li>";
                    ?>

                    <!--  <li class="header-btn" ><a onclick="showOrderOptions()" href="#"><i style="margin:0 1px;padding:0px 5px 0px 0px;color:darkorange" class="fas fa-shopping-cart"></i>Order Online</a></li>
                     <li><a href="tel:8602844466"><i style="margin:0 1px;padding:0px 5px 0px 0px;color:darkorange" class="fa fa-gift fa-fw"></i>Gift cards</a></li>


                        <li class="signup" style="background:#b70038"><a target="_blank" href="https://avonindiangrill.us17.list-manage.com/subscribe?u=255b1766fbb1be53b3005e40f&amp;id=dbae3c56e7" target="_blank"><i style="margin:0 0px;padding:0px 5px 0px 0px;color:darkorange;" class="fas fa-bullhorn"></i>Email Sign up</a></li>
                        -->
                    <li class="nav-item dropdown">
                        <i style='margin:0 1px;padding:0px 5px 0px 0px;color:darkorange' class='fas fa-shopping-cart'></i>
                        <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>
                            Reservation
                            <span class='caret'></span>
                        </a>

                        <ul class='dropdown-menu' aria-labelledby='navbarDropdown'>
                            <li><a class='dropdown-item' href='reservation'>New Reservation</a></li>
                            <?php
                            if ($signedin) {
                                echo "<li><a class='dropdown-item' href='my-reservations'>Past Reservations</a></li>";
                            }
                            ?>
                        </ul>
                    </li>
                </ul>
            </div>

            <nav class="navbar transparent-navbar" role="navigation">
                <div class="navbar-header">
                    <a class="navbar-brand">
                        <img src="images/logo.png" >
                    </a>
                </div>

                <ul class="nav navbar-nav navbar-nav-primary">
                    <li><a class="nav-link" href="index"><i class="fa fa-home fa-fw " ></i>HOME</a></li>

                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-clipboard-list"></i>
                            MENU
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu primary-dropdown-menu">
                            <li><a href="menu?typeId=1">Lunch & Dinner</a></li>
                            <li><a href="#">Chef's Table</a></li>
                            <li><a href="catering-menu">Catering</a></li>
                            <li><a href="#">Home Meals</a></li>
                            <li><a href="menu?typeId=2">Bar</a></li>
                        </ul>
                    </li>

                    <!--
                    <li><a href="#"><i class="fa fa-shopping-cart"></i>MY CART</a></li>
                    -->
                    <li class="secondary-link"><a href="#"><i class="fa fa-utensils fa-fw"></i>CATERING</a></li>
                    <li class="secondary-link"><a href="#"><i class="fas fa-fire"></i>CUISINE</a></li>
                 <!--
                    <li class="secondary-link"><a href="#"><i class="fas fa-images"></i>GALLERY</a></li>
                   -->
                    <li class="secondary-link"><a target="_blank" href="https://avonindiangrill.vouchercart.com/"><i class="fa fa-gift fa-fw"></i>GIFT CARD</a></li>
                    <li class="secondary-link"><a href="contact"><i class="fas fa-location-arrow"></i>CONTACT</a></li>


                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#navbar-nav-secondary" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar" style="color: white"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </ul>

                <div id="navbar-nav-secondary" class="navbar-nav-secondary navbar-collapse collapse ">
                    <ul class="nav navbar-nav">
                        <!-- -->
                        <li><a href="#"><i class="fa fa-utensils fa-fw"></i>CATERING</a></li>
                        <li><a href="#"><i class="fas fa-fire"></i>CUISINE</a></li>
                        <li ><a target="_blank" href="https://avonindiangrill.vouchercart.com/"><i class="fa fa-gift fa-fw"></i>GIFT CARD</a></li>
                  <!--
                        <li><a href="#"><i class="fas fa-images"></i>GALLERY</a></li>
                      -->
                        <li><a href="contact"><i class="fas fa-location-arrow"></i>CONTACT & HOURS</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </nav>
        </header>
