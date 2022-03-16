define([
        'jquery',
        'mage/storage',
        'Magento_Checkout/js/model/url-builder',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/quote',
    ], function ($, storage, urlBuilder, errorProcessor, quote) {
        'use strict';

        var availableSlots = [];

        return function (target) {
            return target.extend({
                initConfig: function () {
                    this._super();

                    quote.shippingAddress.subscribe(this.fetchAvailableSlots, this);
                },

                fetchAvailableSlots: function () {
                    if (!this.canFetchAvailableSlots()) {
                        return;
                    }

                    storage.post(
                        urlBuilder.createUrl('/carriers/fast_est/appointments/available-slots', {}),
                        JSON.stringify({
                            cart: {
                                customerPostCode: quote.shippingAddress().postcode,
                                quantity: parseInt(window.checkoutConfig.quoteData.items_qty, 10)
                            }
                        })
                    ).done(function (result) {
                        availableSlots = result['slot_response'];
                    }).fail(function (response) {
                        errorProcessor.process(response);
                    });
                },

                canFetchAvailableSlots: function () {
                    return quote.shippingAddress() && quote.shippingAddress().postcode;
                },

                restrictDates: function (d) {
                    var restrictDatesResult = this._super(d);

                    if (this.isFastEstSelected() && this.disableNotAvailableDates(d)) {
                        return [false, ''];
                    }

                    return restrictDatesResult;
                },

                disableNotAvailableDates: function (d) {
                    var isAvailable = availableSlots.some(function (item) {
                        var availableDate = new Date(item['date']);

                        return availableDate.getYear() === d.getYear()
                            && availableDate.getMonth() === d.getMonth()
                            && availableDate.getDate() === d.getDate()
                            && item['slots_number'] > 0;
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
