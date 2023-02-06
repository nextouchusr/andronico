define([
    'jquery',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'Magento_Checkout/js/checkout-data',
], function ($, registry, Select, checkoutData) {
    'use strict';

    return Select.extend({
        defaults: {
            skipValidation: false
        },

        initialize: function () {
            this._super();
            this.toggleShippingAddressVatId();
        },

        onUpdate: function () {
            this._super();
            this.toggleShippingAddressVatId();
        },

        toggleShippingAddressVatId: function () {
            registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.vat_id', function (field) {
                // On first loading this field could be undefined
                const selectedInvoiceType = $("#shipping-new-address-form select[name='custom_attributes[invoice_type]']").val();
                const shippingAddress = checkoutData.getShippingAddressFromData();
                let invoiceType = 'receipt';

                if (selectedInvoiceType) {
                    invoiceType = selectedInvoiceType;
                } else if (shippingAddress) {
                    invoiceType = shippingAddress.custom_attributes.invoice_type;
                }

                if (invoiceType === 'receipt') {
                    field.clear();
                    return field.hide();
                }

                return field.show();
            });
        },
    });
});
