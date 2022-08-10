define([], function () {
        'use strict';

        return function (target) {
            return target.extend({
                isStorePickupEnable: function () {
                    var hasInStoreShippingRate = this.rates().find(function (el) {
                        return el['carrier_code'] === 'instore';
                    });

                    return !!hasInStoreShippingRate;
                }
            });
        };
    }
);
