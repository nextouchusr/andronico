define(['jquery'], function ($) {
        'use strict';

        return function (target) {
            return target.extend({
                restrictDates: function (d) {
                    var restrictDatesResult = this._super(d);

                    if (this.isFastEstSelected() && this.disableNotAvailableDates(d)) {
                        return [false, ''];
                    }

                    return restrictDatesResult;
                },

                disableNotAvailableDates: function (d) {
                    var availableSlots = window.checkoutConfig.amasty.deliverydate.fast_est_available_slots;

                    var availableDates = availableSlots.map(function (item) {
                        return new Date(item['date']);
                    });

                    var isAvailable = availableDates.some(function (item) {
                        return item.getYear() === d.getYear()
                            && item.getMonth() === d.getMonth()
                            && item.getDate() === d.getDate();
                    });

                    return !isAvailable;
                },

                isFastEstSelected: function () {
                    return $('input[type="radio"][name="shipping_method"]:checked').val() === 'fast_est_fast_est';
                }
            });
        };
    }
);
