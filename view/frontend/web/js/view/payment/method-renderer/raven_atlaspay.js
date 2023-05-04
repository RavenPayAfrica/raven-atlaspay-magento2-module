define(
    [
        "jquery",
        'mage/url',
        "Magento_Checkout/js/view/payment/default",
        "Magento_Checkout/js/action/place-order",
        "Magento_Checkout/js/model/payment/additional-validators",
        "Magento_Checkout/js/model/quote",
        "Magento_Checkout/js/model/full-screen-loader",
        "Magento_Checkout/js/action/redirect-on-success",
        'mage/storage'
    ],
    function (
            $,
            mageUrl,
            Component,
            placeOrderAction,
            additionalValidators,
            quote,
            fullScreenLoader,
            redirectOnSuccessAction,
            storage
            ) {
        'use strict';

        let secret_key;
        let _this = this;

        return Component.extend({
            defaults: {
                template: 'Raven_AtlasPay/payment/raven-form',
            },

            redirectAfterPlaceOrder: false,

            isActive: function () {
                return true;
            },

            getCode: function() {

                // retrieve the secret key from the server
                storage.get(
                    '/rest/all/V1/atlaspay/live-secret-key/'
                ).done(function (response) {
                    // handle success response
                    secret_key = response[0];
                }).fail(function (response) {
                    // handle error response
                    console.log(response);
                });

                return 'raven_atlaspay';
            },

            /**
             * @override
             */

             placeOrder: function (data, event) {
                let checkoutConfig = window.checkoutConfig;
                let paymentData = quote.billingAddress();
                let product = checkoutConfig.quoteItemData
                var orderId = checkoutConfig.quoteData.entity_id;
                var quoteId = window.checkoutConfig.quoteItemData[0].quote_id;
                let productName = '';
                const Atlas = window.AtlasPaySdk
                let trx_ref;

                // Disable the placeOrder button whilst this is processing
                this.isPlaceOrderActionAllowed(false);
                placeOrderAction(this.getData(), false, this.messageContainer);

                console.log(_this, 'entire entity');

                // get the customer email
                if (checkoutConfig.isCustomerLoggedIn) {
                    var customerData = checkoutConfig.customerData;
                    paymentData.email = customerData.email;
                } else {
                    paymentData.email = quote.guestEmail;
                }

                // collate the product name
                product.forEach(element => {
                    productName = `${productName + ',' + element.name}`
                    productName = productName.replace(/^,\s*/, '')
                });


                let config = {
                    "customer_email": paymentData.email,
                    "description" : `Store payment for ${productName}`,
                    "merchant_ref": orderId + '-' + Math.random(),
                    "amount": Math.ceil(quote.totals().grand_total), // get accurate order amount
                    "redirect_url": "",
                    "payment_methods" : "card,bank_transfer,ussd,raven",
                    "secret_key" : `${secret_key}`
                }

                if (event) {
                    event.preventDefault();
                }
                    fullScreenLoader.startLoader()

                        console.log('its loaded', quoteId)

                        if (!trx_ref){
                            Atlas.generate(config)
                        }

                        window.AtlasPaySdk.onResponse = function(data) {
                            trx_ref = data.data.trx_ref;

                            if (trx_ref.length > 0){
                                Atlas.init(trx_ref)
                                fullScreenLoader.stopLoader()

                            }
                            /**
                          * handle generate response, this triggers when you try generating a new ref via AtlasPay.generate(), you catch ther response here
                          * (required) : you are to retrieve the response via the data returned
                         **/
                           console.log('We got a response:', data); // or do your stuff here
                    }


                // trigger the afterpay events
                this.afterPlaceOrder()

                },

            afterPlaceOrder: function (data, event) {
                var quoteId = window.checkoutConfig.quoteItemData[0].quote_id;
                var orderId = checkoutConfig.quoteData.entity_id;
                let atlasConfig = checkoutConfig.payment.raven_atlaspay;

                console.log(orderId, 'quote_id:', atlasConfig , 'after placeholder')

                window.AtlasPaySdk.onLoad = function(data){
                    console.log('loaded')
                }
                window.AtlasPaySdk.onClose = function(data){
                    if (data.message === 'transaction_success'){
                        console.log('success close', data)

                        fetch(atlasConfig.api_url + 'rest/all/V1/atlaspay/verify/' + quoteId)
                        .then(d => {
                            console.log(d)
                            window.AtlasPaySdk.shutdown()
                            redirectOnSuccessAction.execute();
                        })

                            } else {
                            window.AtlasPaySdk.shutdown()
                           alert('Your payment was cancelled and unsuccesful')
                           _this.isPlaceOrderActionAllowed(true);
                           _this.messageContainer.addErrorMessage({
                               message: "Error, please try again"
                           });

                           return;
                        }

                }

                window.AtlasPaySdk.onSuccess = function(data){
                     // retrieve the secret key from the server
                     console.log(data, 'onSuccess');

                     fetch(atlasConfig.api_url + 'rest/all/V1/atlaspay/verify/' + quoteId)

                    // this.confirmTransaction(quoteId);
                    return

                }

            },


            });
    }
   );
