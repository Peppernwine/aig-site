<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 7/6/2018
 * Time: 3:42 PM
 */

require_once "bootstrap.php";
require_once RESOURCE_PATH . "/StripePayment.class.php";
require_once RESOURCE_PATH . "/alertmessage.php";

if (isset($_POST['stripeToken'])) {
    $sp = new StripePayment();

    $chargeResponse = $sp->charge($_POST['stripeToken'],999,'usd',
             'Avon Indian Grill Charge','Avon Indian Grill',
                'order_id','1001');


    echo '<br>' . "<b>CHARGE:</b>" . '<br>';
    echo 'Charge ID:' . $chargeResponse->id . '<br>';
    echo 'Charge Object:' . $chargeResponse;

    $refundResponse = $sp->refund($chargeResponse->id);
    echo '<br>' . "<b>REFUND:</b>" . '<br>';
    echo 'Refund Id' . $refundResponse->id;
    echo 'Refund Object' . $refundResponse;
}


?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title data-tid="elements_examples.meta.title">Stripe Test</title>

        <script src="vendor/jquery/jquery-3.3.1.min.js"></script>
        <script src="https://js.stripe.com/v3/"></script>
        <script src="js/stripe.js.php?v11" data-rel-js></script>
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="vendor/bootbox/bootbox.min.js"></script>
        <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css?v2">
        <link rel="stylesheet" href="vendor/font-awesome/css/fontawesome-all.css">
        <link rel="stylesheet" href="css/stripe.css">

    </head>

    <body>
        <section id="credit-card-section" class="container">
            <form class="clear-fix" method="POST" action="">
                <section>

                    <div class="row">
                        <div class="col-xs-12 col-sm-4">
                            <div class="form-group">
                                <label for="name-on-card">Name on Card</label>
                                <input type="text" data-value-missing=”xxxx” required autocomplete="name"
                                       value="John Doe"
                                       class="form-control" id="name-on-card" name="name-on-card" aria-describedby="name-on-cardHelp">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4">
                            <div class="form-group">
                                <label for="street">Street</label>
                                <input type="text" required autocomplete="address-line1"
                                       value="221B Baker Street"
                                       class="form-control" id="street" name="street" aria-describedby="streetHelp">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4">
                            <div class="form-group">
                                <label for="zipcode">Zip Code</label>
                                <input type="text" required autocomplete="postal-code"
                                       value="08452"
                                       class="form-control" id="zipcode" name="zipcode" aria-describedby="zipcodeHelp">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="card-number">Card Number</label>
                                <div class='cc-field' id="card-number"></div>
                            </div>
                        </div>

                        <div class="col-sm-3 col-xs-6">
                            <div class="form-group">
                                <label for="card-expiration">Expiration</label>
                                <div class='cc-field' id="card-expiration"></div>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <div class="form-group">
                                <label for="card-cvc">CVC</label>
                                <div class='cc-field' id="card-cvc"></div>
                            </div>
                        </div>
                    </div>

                    <div class="cc-error" role="alert">
                        <span class="message"></span>
                    </div>

                </section>

                <input id="btn-pay" type="submit" value="Pay & Refund"/>

            </form>

            <script>
                 (function () {
                    'use strict';

                    var elementStyles = {
                        base: {
                            color: '#32325D',
                            fontWeight: 400,
                            fontFamily: 'lato-reg-webfont,Lato, sans-serif',
                            fontSize: '16px',
                            fontSmoothing: 'antialiased',
                            '::placeholder': {
                                color: '#CFD7DF',
                            },
                            ':-webkit-autofill': {
                                color: '#e39f48',
                            },
                        },
                        invalid: {
                            color: '#E25950',

                            '::placeholder': {
                                color: '#FFCCA5',
                            },
                        },
                    };

                    var elementClasses = {
                        focus: 'focused',
                        empty: 'empty',
                        invalid: 'invalid',
                    };

                    var sp = new StripePayment({
                                       fonts : [{cssSrc:"css/font.css"}],
                                       elementStyles:elementStyles,
                                       elementClasses:elementClasses,
                                       ccSection: "#credit-card-section",
                                       form:"form"});

                    document.querySelector("#btn-pay").addEventListener('click', function(e) {
                        e.preventDefault();

                        var popup = bootbox.dialog({
                            title: 'Submitting Order',
                            closeButton:false,
                            message: '<p><i class="fa fa-spin fa-spinner"></i>Placing Order</p>'
                        });
                        popup.init(function(){
                            popup.find('.bootbox-body').html('<p><i class="fa fa-spin fa-spinner"></i> Testing - Charge & Refund Credit card </p>');
                            sp.processPayment(responseHandler,{dialog:popup});
                        });
                    });


                    function responseHandler(response,params) {

                        if (!response.success) {
                            params.dialog.modal('hide');

                            var msg = 'There was an error processing your order - ' + response.message;

                            var errorContent = "<div style='margin:10px' class='alert alert-danger' role='alert'> " +
                                               "<span class='glyphicon glyphicon-exclamation-sign'></span> " + msg
                                               "</div>";
/* TEST */
                            bootbox.alert(errorContent);
                            return;
                        }

                        var form = document.querySelector('#credit-card-section form');
                        var hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'stripeToken');
                        hiddenInput.setAttribute('value', response.token);
                        form.appendChild(hiddenInput);

                        // Submit the form
                        form.submit();

                    }
                })();

            </script>
         </section>
    </body>
</html>
