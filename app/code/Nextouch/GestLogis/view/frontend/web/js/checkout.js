require([
    'jquery',
], function ($) {
    'use strict';

    const readonlyPostcode = function () {
        var postcodeState = localStorage.getItem("postcodeState");
        if (postcodeState == "shown") {
            var intervalForReadonlyPostcode = setInterval(() => {
                var shippingPostcode = $('#checkout-step-shipping div[name="shippingAddress.postcode"] :input[name="postcode"]')
                if (shippingPostcode.length > 0) {
                    clearInterval(intervalForReadonlyPostcode)
                    shippingPostcode.attr('disabled', true)
                    shippingPostcode.after('<span class="zipcode-comment">' + window.checkoutConfig.zipcomment + '</span>');
                    var _shippingPostcode = $('.modal-content div[name="shippingAddress.postcode"] :input[name="postcode"]')
                    _shippingPostcode.attr('disabled', false)
                }
            }, 1)

            if (window.isCustomerLoggedIn != false) {
                var intervalForcustomerEamil = setInterval(() => {
                    var customerEmail = $('#shipping .form.form-login #customer-email');
                    if (customerEmail.length > 0) {
                        clearInterval(intervalForcustomerEamil)
                        $('#shipping .form.form-login #customer-email').on("change", function () {
                            var shippingPostcode = $('#checkout-step-shipping div[name="shippingAddress.postcode"] :input[name="postcode"]')
                            shippingPostcode.attr('disabled', false)
                        });
                    }
                }, 10)

                
            }

            var intervalForShippingMethodButton = setInterval(() => {
                var buttonsContainer = $('#checkout-step-shipping_method #shipping-method-buttons-container')
                if (buttonsContainer.length > 0) {
                    clearInterval(intervalForShippingMethodButton)
                    $('#checkout-step-shipping_method #shipping-method-buttons-container button').on('click', function () {
                        setTimeout(() => {
                            //let addressFormError = $('#shipping-new-address-form div._error');
                            //if (addressFormError.length > 0) {
                                var shippingPostcode = $('#checkout-step-shipping div[name="shippingAddress.postcode"] :input[name="postcode"]')
                                shippingPostcode.attr('disabled', true)
                           // }
                        }, 10);
                    });
                }
            }, 1)

            


        }

        var shippingMethodRadioInterval = setInterval(() => {
            if ($('.table-checkout-shipping-method .radio').length > 0) {
                clearInterval(shippingMethodRadioInterval)
                $('.table-checkout-shipping-method .row label').on('click', function () {
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                });
            }
        });
    }

    readonlyPostcode()
});
