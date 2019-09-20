define(
    [
        'jquery',
        'ko',
        'mage/storage',
        'mage/url',
        'Magento_Checkout/js/action/get-totals',
    ],
    function(
        $,
        ko,
        storage,
        urlBuilder,
        getTotalsAction
    ) {
        'use strict';

        var paymentFeeConfig = $.merge({is_active: false}, window.checkoutConfig.boolfly_payment_fee || {}),
            getTotals = function() {
                var deferred = $.Deferred();
                isLoading(false);
                getTotalsAction([], deferred);
            };

        if (!paymentFeeConfig.is_active) {
            return getTotals;
        }

        return function (isLoading, payment) {
            var serviceUrl = urlBuilder.build('paymentfee/checkout/totals');
            return storage.post(
                serviceUrl,
                JSON.stringify({payment: payment})
            ).done(
                function(response) {
                    if (response) {
                        getTotals();
                    }
                }
            ).fail(
                function (response) {
                    isLoading(false);
                    //var error = JSON.parse(response.responseText);
                }
            );
        }
    }
);