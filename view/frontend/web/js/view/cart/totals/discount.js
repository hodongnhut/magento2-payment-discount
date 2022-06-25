
/*global define*/
define(
    [
        'Lg_PaymentDiscount/js/view/cart/summary/discount'
    ],
    function (Component) {
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
                var title = 'Payment Discount dasdasd';
                return title;
            }
        });
    }
);
