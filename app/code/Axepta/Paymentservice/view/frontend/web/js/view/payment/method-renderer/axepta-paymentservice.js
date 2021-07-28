/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */


define([
        'ko',
        'jquery',
        'Axepta_Paymentservice/js/view/payment/default',
        'mage/translate',
        'Axepta_Paymentservice/js/action/set-payment',
        'Axepta_Paymentservice/js/action/place-order',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Axepta_Paymentservice/js/model/agreement-validator',
        'mage/validation',
        'mage/storage',
        'Magento_CheckoutAgreements/js/model/agreements-assigner',
        'Magento_Checkout/js/model/full-screen-loader',
        'https://pay-sandbox.axepta.it/sdk/axepta-pg-sdk.js',
        'https://pay.axepta.it/sdk/axepta-pg-sdk.js',
        'Magento_Checkout/js/action/select-payment-method',
        'mage/url',
        'Magento_Checkout/js/model/quote',
        'Magento_Ui/js/modal/alert',
        'Magento_Customer/js/customer-data'
    ],
    function (
      ko,
      $,
      Component,
      $t,
      setPaymentMethodAction,
      placeOrderAction,
      additionalValidators,
      agreementValidator,
      mageValidator,
      storage,
      assignerHelper,
      fullScreenLoader,
      AxeptaTestSdkClient,
      AxeptaSdkClient,
      selectPaymentMethodAction,
      url,
      quote,
      malert,
      customerData
    ) {
        'use strict';
        return Component.extend({
            defaults: {
                active: false,
                selectedCardType: null,
                selectedScheme: null,
                selectedBic: null,
                selectedIban: null,
                redirectAfterPlaceOrder: false,
                formid: 'co-transparent-form-axepta',
                template: 'Axepta_Paymentservice/payment/axepta-paymentservice',
                isAxeptaCardFormValid: ko.observable(false),
            },
            /** @inheritdoc */
            initObservable: function () {
                this._super()
                    .observe(['active', 'selectedCardType', 'selectedScheme', 'selectedBic', 'selectedIban']);

                window.addEventListener("message", (event)=>{
                    if(event.data === "AxeptaCardFormValid"){
                        this.isAxeptaCardFormValid(true);
                    } else if(event.data === "AxeptaCardFormInvalid"){
                        this.isAxeptaCardFormValid(false);
                    } else if(event.data === "Axepta3dsClosed"){
                        window.location.reload();
                    }
                });

                return this;
            },
            /**
             * @return {Boolean}
             */
            selectPaymentMethod: function () {
                selectPaymentMethodAction(this.getData());
                let axeptaSmartToken = this.getAxeptaSmartLicenseToken();
                //TODO Find a better way to manage the following line....
                window.sdk = (this.getSdkUrl() == "https://pay.axepta.it/sdk/axepta-pg-sdk.js" ) ? new AxeptaSdkClient(this.getApiUrl(), axeptaSmartToken) : new AxeptaTestSdkClient(this.getApiUrl(), axeptaSmartToken);
                if (this.getConfigValue('checkout_type') == 3){
                  let paymentID = '';
                  window.sdk.preparePayment(paymentID,'inline','CREDITCARD');
                }
                return true;
            },
            placeOrderHandler: null,
            afterPlaceOrder: function () {
                let axeptaEasyToken = this.getAxeptaEasyLicenseToken();
                let apiURL = this.getApiUrl();
                if(this.isIGFSAxepta() && this.getCheckoutType() == 1){
                     $.post(this.getRedirectUrl()).done(function (data) {
                        var paymentID = data; //todo pass to form submission later
                        window.location.href = apiURL + '/circuits/' + paymentID + '/' + axeptaEasyToken
                     }).fail(function (xhr, status, error){
                       malert({
                            title: 'Error',
                            content: 'Some error occured with the payment gateway. Please try again later.',
                            clickableOverlay: false,
                            actions: {
                                always: function () {}
                            }
                        });
                     });
                } else if (this.isIGFSAxepta() && this.getCheckoutType() == 3) {
                  $.post(this.getRedirectUrl()).done(function (data) {
                      var paymentID = data;
                      window.sdk.setPaymentID(paymentID);
                      window.sdk.submit();
                      $(window).on("message onmessage", function(e) {
                          if(e.originalEvent.data == 'axepta_SUCCESS_message')
                          {
                            customerData.invalidate(['cart']);

                            window.location.href = url.build('/axepta/payment/success/orderid/' + quote.getQuoteId());
                          }
                          else if(e.originalEvent.data == 'axepta_FAILURE_message')
                          {
                            window.location.href = url.build('/axepta/payment/error/orderid/' + quote.getQuoteId());
                          }
                        return true;
                      });
                  }).fail(function (xhr, status, error){
                    malert({
                         title: 'Error',
                         content: 'Some error occured with the payment gateway. Please try again later.',
                         clickableOverlay: false,
                         actions: {
                             always: function () {}
                         }
                     });
                  });
                }
                return false;
            },
            /**
             * @return {*}
             */
            getPlaceOrderDeferredObject: function () {
               var data = this.getData();
               assignerHelper(data);
               return $.when(
                      placeOrderAction(data, this.messageContainer)
               );
            },
            /**
             * @returns {Object}
             */
            context: function () {
                return this;
            },

            /**
             * @returns {String}
             */
            getTitle: function() {
                return 'Paga con carte di credito';
            },

            /**
             * @returns {String}
             */
            getCode: function () {
                return 'axepta_paymentservice';
            },

            /**
             * @returns {Boolean}
             */
            isActive: function () {
                return true;
            },
            /**
             * Get payment method data
             */
            getData: function () {
                return {
                    'method': this.item.method,
                    'po_number': null,
                    'additional_data': null
                };
            },
            // getData: function () {
            //     var returndata = {
            //         'method': this.item.method,
            //         'po_number': null,
            //         'additional_data': {}
            //     };
            //     returndata.additional_data[this.getCode() + '_payment_instrument'] = this.selectedCardType();
            //     returndata.additional_data[this.getCode() + 's_payment_scheme'] = this.selectedScheme();
            //     returndata.additional_data[this.getCode() + 's_payment_bic'] = this.selectedBic();
            //     returndata.additional_data[this.getCode() + 's_payment_iban'] = this.selectedIban();
            //     returndata.additional_data[this.getCode() + 's_checkout_type'] = this.getCheckoutType();
            //
            //     return returndata;
            // },

            /**
             * Get redirect url for pay
             * @returns {String}
             */
            getRedirectUrl: function () {
                return this.getConfigValue('redirecturl');
            },

            /**
             * @returns {String}
             */
            getCheckoutType: function () {
                return this.getConfigValue('checkout_type');
            },

            /**
             * @returns {String}
             */
            getDescription: function () {
                return this.getConfigValue('description');
            },

            /**
             * @returns {boolean}
             */
            isComputop: function () {
                return this.getConfigValue('method') === 'computop';
            },

            /**
             * @returns {boolean}
             */
            isIGFSAxepta: function () {
                return this.getConfigValue('method') === 'axepta';
            },

            /** License Token for Easy integration (hosted fields)
             * @returns {String}
             */
            getAxeptaEasyLicenseToken : function () {
                return this.getConfigValue('axepta_easy_license_token');
            },

            /** API endpoint for Smart integration
             * @returns {String}
             */
            getApiUrl : function () {
                return this.getConfigValue('axepta_api_url');
            },

            /** Sdk Javascript for Smart integration
             * @returns {String}
             */
            getSdkUrl : function () {
                return this.getConfigValue('axepta_sdk_url');
            },

            /** License Token for Easy integration (hosted fields)
             * @returns {String}
             */
            getAxeptaSmartLicenseToken : function () {
                return this.getConfigValue('axepta_smart_license_token');
            },
            /**
             * @param {String} key
             * @returns {String}
             */
            getConfigValue: function (key) {
                return window.checkoutConfig.payment[this.getCode()][key];
            },
            /**
             * @returns {Array}
             */
            getCcAvailableTypes: function () {
                return window.checkoutConfig.payment[this.getCode()]['creditcards'];
            },
            /**
             * @returns {boolean}
             */
            hasCcAvailable: function () {
                var pm = window.checkoutConfig.payment[this.getCode()];
                return pm && pm.creditcards && (pm.creditcards.length > 0);
            },
            /**
             * @returns {Array}
             */
            getCcAlternativeTypes: function () {
                return window.checkoutConfig.payment[this.getCode()]['alternative'];
            },

            /**
             * @returns {boolean}
             */
            hasCcAlternativeAvailable: function () {
                var pm = window.checkoutConfig.payment[this.getCode()];
                return pm && pm.alternative && (pm.alternative.length > 0);
            },
            /**
             * @returns {Array}
             */
            groupedBy: function (collection, mod) {
                var grouped = [], tl = collection.length;
                for (var i = 0; i < tl; i += mod) {
                    var row = [];
                    grouped.push(row);
                    for (var j = 0; j < mod && (i + j) < tl; ++j) {
                        row.push(collection[i + j]);
                    }
                }
                return grouped;
            },
            selectCC: function (cctype,) {
                this.selectedCardType((typeof cctype === 'string') ? cctype : 'cc');
            },
            debugEvent: function (data, source) {
                console.log('debugEvent:', data, source);
            }

        });
    });
