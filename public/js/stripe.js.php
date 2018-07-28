<?php require_once "../bootstrap.php" ?>

'use strict';

function StripePayment(params) {
    var self = this;
    var params = params;

    var elements,ccSection,form,error,errorMessage ;

    <?php echo "var stripe = Stripe('".STRIPE_API_KEY."');" ?>

    function triggerBrowserValidation () {
        // The only way to trigger HTML5 form validation UI is to fake a user submit.
        var submit = document.createElement('input');
        submit.type = 'submit';
        submit.style.display = 'none';
        form.appendChild(submit);
        submit.click();
        submit.remove();
    }

    function generateSuccessResponse(stripeResult) {
        var response = {success:true,token:stripeResult.token.id,message:'Card was successfully authorized'};
        return response;
    }

    /*

       error.type = api_connection_error(retry),api_error (retry),
                    card_error(show error.message), validation_error (show error.message)

       error.message = show this if error.type= = 'card_error'
       error.code = card_declined,
                    Client side Lib Validation Errors -> incomplete_cvc,incomplete_expiry,incomplete_number
                    server side input error -> incorrect_address,incorrect_cvc,incorrect_number,
                    incorrect_zip,invalid_cvc,invalid_expiry_month,
                    invalid_number,invalid_expiry_month,postal_code_invalid,
                    invalid_expiry_year
    */

    function generateErrorResponse(stripeResult) {
        var response = {success:false,message:''};
        var error = stripeResult.error;

        switch (error.type) {
            case    'card_error',
                    'validation_error' : response.message = error.message;
                                         break;
            default                    : response.message = "System is experiencing technical difficulties. Please retry";
                                         break;
        }

        return response;
    }

    function generateResponse(stripeResult) {
        if (stripeResult.error)
            return generateErrorResponse(stripeResult);
        else
            return generateSuccessResponse(stripeResult);
    }

    this.processPayment = function(responseHandler,params) {

        var responseHandler = responseHandler;
        var params = params;

        // Gather additional customer data we may have collected in our form.
        var name = ccSection.querySelector('#' + 'name-on-card');
        var street = ccSection.querySelector('#' + 'street');
        var zipcode = ccSection.querySelector('#' + 'zipcode');
        var additionalData = {
            name: name ? name.value : undefined,
            address_line1: street ? street.value : undefined,
            address_zip: zipcode ? zipcode.value : undefined,
        };

        // Use Stripe.js to create a token. We only need to pass in one Element
        // from the Element group in order to create a token. We can also pass
        // in the additional customer data we collected in our form.
        stripe.createToken(elements[0], additionalData).then(function (result) {
            responseHandler(generateResponse(result),params);
        });
    }

    function initialize() {
        ccSection = document.querySelector(params.ccSection);

        form = ccSection.querySelector(params.form);

        error = ccSection.querySelector('.cc-error');
        errorMessage = error.querySelector('.message')

        var stripeElements = stripe.elements({
            fonts:params.fonts,
            // Stripe's examples are localized to specific languages, but if
            // you wish to have Elements automatically detect your user's locale,
            // use `locale: 'auto'` instead.
            locale: 'auto'
        });

        var cardNumber = stripeElements.create('cardNumber', {
            style: params.elementStyles,
            classes: params.elementClasses,
            placeholder:"Card Number"
        });
        cardNumber.mount('#card-number');

        var cardExpiry = stripeElements.create('cardExpiry', {
            style: params.elementStyles,
            classes: params.elementClasses,
            placeholder:"Expiration"
        });
        cardExpiry.mount('#card-expiration');

        var cardCvc = stripeElements.create('cardCvc', {
            style: params.elementStyles,
            classes: params.elementClasses,
            placeholder:"CVC"
        });
        cardCvc.mount('#card-cvc');

        elements = [cardNumber,cardExpiry,cardCvc];

        // Listen for errors from each Element, and show error messages in the UI.
        var savedErrors = {};
        elements.forEach(function(element, idx) {
            element.on('change', function(event) {
                if (event.error) {
                    savedErrors[idx] = event.error.message;
                    errorMessage.innerText = event.error.message;
                } else {
                    savedErrors[idx] = null;

                    // Loop over the saved errors and find the first one, if any.
                    var nextError = Object.keys(savedErrors)
                        .sort()
                        .reduce(function(maybeFoundError, key) {
                            return maybeFoundError || savedErrors[key];
                        }, null);

                    if (nextError) {
                        // Now that they've fixed the current error, show another one.
                        errorMessage.innerText = nextError;
                    } else {
                        // The user fixed the last error; no more errors.
                        errorMessage.innerText = "";
                    }
                }
            });
        });
    }

    $(function() {
        initialize();
    });


}
