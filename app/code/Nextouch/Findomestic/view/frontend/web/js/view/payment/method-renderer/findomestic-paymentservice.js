define([
    'ko',
    'jquery',
    'Magento_Checkout/js/view/payment/default',
    'Magento_Checkout/js/action/place-order',
    'Magento_Checkout/js/model/payment/additional-validators',
    'Magento_Ui/js/modal/alert',
], function (
    ko,
    $,
    Component,
    placeOrderAction,
    additionalValidators,
    malert
) {
    'use strict';

    return Component.extend({
        defaults: {
            active: false,
            isPlaceOrderButtonEnabled: ko.observable(true),
            template: 'Nextouch_Findomestic/payment/findomestic-paymentservice',
        },

        initObservable: function () {
            this._super().observe(['active']);

            return this;
        },

        placeOrder: function (data, event) {
            var self = this;

            if (event) {
                event.preventDefault();
            }

            if (this.validate() && additionalValidators.validate()) {
                this.isPlaceOrderActionAllowed(false);
                this.isPlaceOrderButtonEnabled(false);

                $.post(this.getRedirectUrl()).done(function (res) {
                    if (!res.data.statusReturn.isOk) {
                        self.isPlaceOrderActionAllowed(true);
                        self.isPlaceOrderButtonEnabled(true);

                        return malert({
                            title: 'Error',
                            content: 'Some error occurred with the payment gateway. Please try again later.',
                            clickableOverlay: false
                        });
                    }

                    window.location.href = res.data.applyURL;
                });

                return true;
            }

            return false;
        },

        getConfigValue: function (key) {
            return window.checkoutConfig.payment[this.getCode()][key];
        },

        getRedirectUrl: function () {
            return this.getConfigValue('redirect_url');
        },

        getInstructions: function () {
            return this.getConfigValue('instructions');
        },
    });
});
