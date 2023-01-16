var config = {
    map: {
        '*': {
            'ShipperHQ_AddressAutocomplete/js/autocomplete': 'Nextouch_Checkout/js/autocomplete'
        }
    }, config: {
        mixins: {
            'Magento_Checkout/js/model/checkout-data-resolver': {
                'Nextouch_Checkout/js/model/default-payment-method-resolver': true
            }
        }
    }
};
