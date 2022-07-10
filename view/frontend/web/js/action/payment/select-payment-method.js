/*global define*/
define(
    [
        'jquery',
        'ko',
        'Magento_Checkout/js/model/quote',
        'Lg_PaymentDiscount/js/action/checkout/cart/totals',
        'Magento_Checkout/js/checkout-data',
        'Magento_Checkout/js/model/full-screen-loader',
    ],
    function($, ko ,quote, totals, checkoutData, fullScreenLoader) {
        'use strict';
        var isLoading = ko.observable(false);
        return function(paymentMethod) {
            if (!$(".loader").is(':visible')) {
                fullScreenLoader.startLoader();
            }
            if (checkoutData.getSelectedPaymentMethod() != paymentMethod.method) {
                quote.paymentMethod(paymentMethod);
                totals(isLoading, paymentMethod['method']);
                checkoutData.setSelectedPaymentMethod(paymentMethod);
            }
            if (event.target['tagName'] === undefined) {
                quote.paymentMethod(paymentMethod);
                totals(isLoading, paymentMethod['method']);
                checkoutData.setSelectedPaymentMethod(paymentMethod);
            }
            fullScreenLoader.stopLoader();
        }
    }
);