/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */

define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
],
function (Component, rendererList) {
    'use strict';

    rendererList.push(
        {
            type: 'axepta_paymentservice',
            component: 'Axepta_Paymentservice/js/view/payment/method-renderer/axepta-paymentservice'
        }
    );

    /** Add view logic here if needed */
    return Component.extend({});
});
