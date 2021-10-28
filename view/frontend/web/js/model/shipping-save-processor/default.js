define(
    [
        'jquery',
        'ko',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/resource-url-manager',
        'mage/storage',
        'Magento_Checkout/js/model/payment-service',
        'Magento_Checkout/js/model/payment/method-converter',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/action/select-billing-address'
    ],
    function (
        $,
        ko,
        quote,
        resourceUrlManager,
        storage,
        paymentService,
        methodConverter,
        errorProcessor,
        fullScreenLoader,
        selectBillingAddressAction
    ) {
        'use strict';

        return {
            saveShippingInformation: function () {
	
                var payload;

                if (!quote.billingAddress()) {
                    selectBillingAddressAction(quote.shippingAddress());
                }
				
				//var customNotes = $('[name="custom_attributes[custom_notes]"]').val();
				var unienvios_token = $('[name="custom_attributes[unienvios_token]"]').val();
				var unienvios_document_recipient = $('[name="custom_attributes[unienvios_document_recipient]"]').val();
				var unienvios_number = $('[name="custom_attributes[unienvios_number]"]').val();
				var unienvios_neighbourhood = $('[name="custom_attributes[unienvios_neighbourhood]"]').val();
				var unienvios_complement = $('[name="custom_attributes[unienvios_complement]"]').val();
                payload = {
                    addressInformation: {
                        shipping_address: quote.shippingAddress(),
                        billing_address: quote.billingAddress(),
                        shipping_method_code: quote.shippingMethod().method_code,
                        shipping_carrier_code: quote.shippingMethod().carrier_code,
                        extension_attributes:{
                            unienvios_token: unienvios_token,
		            unienvios_document_recipient: unienvios_document_recipient,
			    unienvios_number: unienvios_number,
			    unienvios_neighbourhood: unienvios_neighbourhood,
			    unienvios_complement: unienvios_complement
                    
                        }
                    }
                };

                fullScreenLoader.startLoader();

                return storage.post(
                    resourceUrlManager.getUrlForSetShippingInformation(quote),
                    JSON.stringify(payload)
                ).done(
                    function (response) {
                        quote.setTotals(response.totals);
                        paymentService.setPaymentMethods(methodConverter(response.payment_methods));
                        fullScreenLoader.stopLoader();
                    }
                ).fail(
                    function (response) {
                        errorProcessor.process(response);
                        fullScreenLoader.stopLoader();
                    }
                );
            }
        };
    }
);
 

