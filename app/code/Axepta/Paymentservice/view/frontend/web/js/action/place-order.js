/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */
/*jshint jquery:true*/
define([
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/url-builder',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/place-order'
], function (quote, urlBuilder, customer, placeOrderService) {
    'use strict';


    return function (paymentData, messageContainer) {
        var serviceUrl, payload;

        payload = {
            cartId: quote.getQuoteId(),
            billingAddress: quote.billingAddress(),
            paymentMethod: paymentData
        };
        if (customer.isLoggedIn()) {
            serviceUrl = urlBuilder.createUrl('/carts/mine/set-payment-information', {});
        } else {
            serviceUrl = urlBuilder.createUrl('/guest-carts/:quoteId/set-payment-information', {
                quoteId: quote.getQuoteId()
            });
            payload.email = quote.guestEmail;
        }

        return placeOrderService(serviceUrl, payload, messageContainer);
    };
});
