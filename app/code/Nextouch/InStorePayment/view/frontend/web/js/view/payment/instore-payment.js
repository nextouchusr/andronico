define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (Component, rendererList) {
    'use strict';

    rendererList.push(
        {
            type: 'instore_payment',
            component: 'Nextouch_InStorePayment/js/view/payment/method-renderer/instore-payment'
        }
    );

    /** Add view logic here if needed */
    return Component.extend({});
});
