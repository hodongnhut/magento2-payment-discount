
/*global define*/
define(
    [
        'Boolfly_PaymentFee/js/view/cart/summary/fee'
    ],
    function (Component) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Boolfly_PaymentFee/cart/totals/fee'
            },
            /**
             * @override
             *
             * @returns {boolean}
             */
            isDisplayed: function () {
                return this.getPureValue() != 0;
            }
        });
    }
);
