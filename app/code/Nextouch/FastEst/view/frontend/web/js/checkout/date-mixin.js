define([
        'jquery',
        'mage/storage',
        'Magento_Checkout/js/model/url-builder',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/quote',
        'uiRegistry',
    ], function ($, storage, urlBuilder, errorProcessor, quote, registry) {
        'use strict';

        var availableSlots = [];

        return function (target) {
            return target.extend({
                initConfig: function () {
                    this._super();
                    var self = this;

                    registry.async('checkoutProvider')(function (checkoutProvider) {
                        checkoutProvider.on('shippingAddress', function (shippingAddress) {
                            self.fetchAvailableSlots(shippingAddress);
                        });
                    });

                    quote.shippingAddress.subscribe(this.fetchAvailableSlots, this);
                },

                fetchAvailableSlots: function (shippingAddress) {
                    if (!this.canFetchAvailableSlots(shippingAddress)) {
                        return;
                    }

                    storage.post(
                        urlBuilder.createUrl('/carriers/fast_est/appointments/available-slots', {}),
                        JSON.stringify({
                            cart: {
                                customerPostCode: shippingAddress.postcode,
                                quantity: parseInt(window.checkoutConfig.quoteData.items_qty, 10)
                            }
                        })
                    ).done(function (result) {
                        availableSlots = result['slot_response'];
                    }).fail(function (response) {
                        errorProcessor.process(response);
                    });
                },

                canFetchAvailableSlots: function (shippingAddress) {
                    return shippingAddress && shippingAddress.postcode;
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

                onShiftedValueChange: function (value) {
                    this._super(value);

                    var selectedDate = new Date(value);

                    this.disableNotAvailableIntervals(selectedDate);
                },

                disableNotAvailableIntervals: function (d) {
                    $('select[name="amdeliverydate_time"] > option:not(:first)').each(function () {
                        var deliveryInterval = this.text.replace(/\s/g, '');
                        var isAvailable = availableSlots.some(function (item) {
                            var availableDate = new Date(item['date']);

                            return availableDate.getYear() === d.getYear()
                                && availableDate.getMonth() === d.getMonth()
                                && availableDate.getDate() === d.getDate()
                                && item['time_slot'] === deliveryInterval
                                && item['slots_number'] > 0;
                        });

                        $(this).toggle(isAvailable);
                    });
                },

                isFastEstSelected: function () {
                    return $('#checkout-step-shipping_method').find('input[type="radio"]:checked').val() === 'fast_est_fast_est';
                }
            });
        };
    }
);
