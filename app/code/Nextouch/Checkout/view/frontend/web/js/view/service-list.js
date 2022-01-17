define([
    'ko',
    'uiComponent',
    'underscore',
    'Magento_Checkout/js/model/step-navigator',
    'mage/translate',
], function (ko, Component, _, stepNavigator, $t) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Nextouch_Checkout/service-list'
        },

        isVisible: ko.observable(false),

        initialize: function () {
            this._super();

            stepNavigator.registerStep(
                'service-list',
                null,
                $t('Services'),
                this.isVisible,
                _.bind(this.navigate, this),
                15
            );

            return this;
        },

        /**
         * The navigate() method is responsible for navigation between checkout steps
         * during checkout. You can add custom logic, for example some conditions
         * for switching to your custom step
         * When the user navigates to the custom step via url anchor or back button we_must show step manually here
         */
        navigate: function () {
            this.isVisible(this.hasShippingMethod());
            stepNavigator.setHash('shipping');
        },

        hasShippingMethod: function () {
            return window.checkoutConfig.selectedShippingMethod !== null;
        },

        navigateToNextStep: function () {
            stepNavigator.next();
        }
    });
});
