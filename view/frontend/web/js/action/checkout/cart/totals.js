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
        return function (isLoading, payment) {
            var serviceUrl = urlBuilder.build('PaymentDiscount/checkout/totals');
            return storage.post(
                serviceUrl,
                JSON.stringify({payment: payment})
            ).done(
                function(response) {
                    if (response) {
                        isLoading(false);
                        getTotalsAction([]);
                    }
                }
            ).fail(
                function (response) {
                    isLoading(false);
                }
            );
        }
    }
);