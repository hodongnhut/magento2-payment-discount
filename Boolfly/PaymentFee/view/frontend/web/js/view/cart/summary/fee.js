/*global define*/
define(
    [
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/totals'
    ],
    function (Component, quote, totals) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Boolfly_PaymentFee/cart/summary/fee'
            },
            totals: quote.getTotals(),
            isDisplayed: function() {
                return this.getPureValue() != 0;
            },
            getPaymentFee: function() {
                if (!this.totals()) {
                    return null;
                }
                return totals.getSegment('fee_amount').value;
            },
            getPureValue: function() {
                var price = 0;
                if (this.totals() && totals.getSegment('fee_amount').value) {
                    price = parseFloat(totals.getSegment('fee_amount').value);
                }
                return price;
            },
            getValue: function() {
                return this.getFormattedPrice(this.getPureValue());
            }
        });
    }
);