define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/additional-validators',
    'Nextouch_Checkout/js/model/fiscal-code-validator'
], function (Component, additionalValidators, fiscalCodeValidator) {
    'use strict';

    additionalValidators.registerValidator(fiscalCodeValidator);

    return Component.extend({});
});
