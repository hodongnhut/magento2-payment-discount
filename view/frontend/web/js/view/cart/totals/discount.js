
/*global define*/
define(
    [
        'Lg_PaymentDiscount/js/view/cart/summary/discount',
        'Magento_Checkout/js/model/totals'
    ],
    function (Component, totals) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Lg_PaymentDiscount/cart/totals/discount'
            },
            /**
             * @override
             *
             * @returns {boolean}
             */
            isDisplayed: function () {
                return this.getPureValue() != 0;
            },
            getTitle: function() {
                var title = '';
                if (this.totals()) {
                    title = totals.getSegment('discount_payment_amount').title;
                }
                return title;
            }
        });
    }
);
