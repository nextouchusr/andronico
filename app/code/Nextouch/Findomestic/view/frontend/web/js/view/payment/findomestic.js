define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (Component, rendererList) {
    'use strict';

    rendererList.push(
        {
            type: 'findomestic_paymentservice',
            component: 'Nextouch_Findomestic/js/view/payment/method-renderer/findomestic-paymentservice'
        }
    );

    /** Add view logic here if needed */
    return Component.extend({});
});
