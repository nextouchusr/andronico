define([
    'jquery',
    'mage/translate',
    'Magento_Ui/js/model/messageList',
    'Magento_Checkout/js/model/quote',
], function ($, $t, messageList, quote) {
    'use strict';

    return {
        validate: function () {
            var fiscalCode = this.getFiscalCode();
            var isValid = !this.shouldCheckFiscalCode() || CodiceFiscale.check(fiscalCode);

            if (!isValid) {
                var message = $t('Invalid fiscal code. The field does not respect the correct format.');
                messageList.addErrorMessage({message: message});
            }

            return isValid;
        },

        getFiscalCode: function () {
            var attribute = quote.shippingAddress().customAttributes.find(function (item) {
                return item.attribute_code === 'fiscal_code';
            });

            return attribute.value;
        },

        shouldCheckFiscalCode: function () {
            var fiscalCode = this.getFiscalCode();
            var attribute = quote.shippingAddress().customAttributes.find(function (item) {
                return item.attribute_code === 'invoice_type';
            });

            return fiscalCode.length > 0 || attribute.value === 'invoice';
        },
    };
});
