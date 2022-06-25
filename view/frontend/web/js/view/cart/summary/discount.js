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
                template: 'Lg_PaymentDiscount/cart/summary/discount'
            },
            totals: quote.getTotals(),
            isDisplayed: function() {
                return this.getPureValue() != 0;
            },
            getPaymentDiscount: function() {
                if (!this.totals()) {
                    return null;
                }
                return totals.getSegment('discount_amount').value;
            },
            getPureValue: function() {
                var price = 0;
                if (this.totals() && totals.getSegment('discount_amount').value) {
                    price = parseFloat(totals.getSegment('discount_amount').value);
                }
                return price;
            },
            getValue: function() {
                return this.getFormattedPrice(this.getPureValue());
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