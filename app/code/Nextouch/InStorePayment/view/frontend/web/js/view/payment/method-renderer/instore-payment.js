define([
    'ko',
    'jquery',
    'Magento_Checkout/js/view/payment/default',
], function (ko, $, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            active: false,
            isPlaceOrderButtonEnabled: ko.observable(true),
            template: 'Nextouch_InStorePayment/payment/instore-payment',
        },

        initObservable: function () {
            this._super().observe(['active']);

            return this;
        },

        getConfigValue: function (key) {
            return window.checkoutConfig.payment[this.getCode()][key];
        },

        getInstructions: function () {
            return this.getConfigValue('instructions');
        },
    });
});
