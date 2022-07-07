define([
        'jquery',
        'mage/url',
        'Magento_Ui/js/modal/alert',
    ], function ($, url, malert) {
        'use strict';

        return function (target) {
            return target.extend({
                selectPickupLocation: function (location) {
                    this._super(location);

                    $.post(url.build('wins/quote/validate')).done(function (res) {
                        malert({
                            content: res.message,
                            clickableOverlay: false
                        });
                    });
                },
            });
        };
    }
);
