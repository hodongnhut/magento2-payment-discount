define(
    [
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils',
        'Magento_Checkout/js/model/totals',
        'ko'
    ],
    function (Component, quote, priceUtils, totals, ko) {
        "use strict";
        return Component.extend({
            defaults: {
                isFullTaxSummaryDisplayed: window.checkoutConfig.isFullTaxSummaryDisplayed || false,
                template: 'Lg_PaymentDiscount/checkout/summary/discount'
            },
            isLoading: ko.observable(false),
            initObservable: function () {
                this._super();
                this.isLoading(true);
            },
            totals: quote.getTotals(),
            isTaxDisplayedInGrandTotal: window.checkoutConfig.includeTaxInGrandTotal || false,
            isDisplayed: function() {
                return this.isFullMode();
            },
            getValue: function() {
                var price = 0;
                if (this.totals()) {
                    price = totals.getSegment('discount_payment_amount').value;
                }
                return this.getFormattedPrice(price);
            },
            getTitle: function() {
                var title = '';
                if (this.totals()) {
                    title = totals.getSegment('discount_payment_amount').title;
                }
                return title;
            },
            getBaseValue: function() {
                var price = 0;
                if (this.totals()) {
                    price = this.totals().base_payment_charge;
                }
                return priceUtils.formatPrice(price, quote.getBasePriceFormat());
            }
        });
    }
);