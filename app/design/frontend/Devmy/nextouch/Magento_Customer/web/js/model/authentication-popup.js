/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/url'
], function ($, modal,url) {
    'use strict';

    return {
        modalWindow: null,

        /**
         * Create popUp window for provided element
         *
         * @param {HTMLElement} element
         */
        createPopUp: function (element) {
            var options = {
                'type': 'popup',
                'modalClass': 'popup-authentication',
                'focus': '[name=username]',
                'responsive': true,
                'innerScroll': true,
                'trigger': '.proceed-to-checkout',
                'buttons': []
            };

            this.modalWindow = element;
            modal(options, $(this.modalWindow));
        },

        getBaseUrl: function(u = '') {
            return url.build(u);
        },

        /** Show login popup window */
        showModal: function () {
            $(this.modalWindow).modal('openModal').trigger('contentUpdated');
        },
        /** Show login popup window */
        hideModal: function () {
            $(this.modalWindow).modal('closeModal').trigger('contentUpdated');
        }

    };
});
