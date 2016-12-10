define(
    [
        'Boolfly_PaymentFee/js/view/checkout/summary/charge'
    ],
    function (Component) {
        'use strict';

        return Component.extend({

            /**
             * @override
             */
            isDisplayed: function () {
                return true;
            }
        });
    }
);