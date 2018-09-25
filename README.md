# Boolfly Payment Fee
Add a specific fee to the payment in Magento 2

The same license with Magento application: https://opensource.org/licenses/OSL-3.0

Re-construct module to follow the logic of this extension: https://github.com/devromans/payment-fee

Thanks to <a href="https://github.com/devromans">@devromans</a>

<h5>Welcome any feedback and contributor.</h5>

<h2>Install with Composer</h2>

`composer config repositories.mrkhoa99boolfly_payment_fee vcs https://github.com/mrkhoa99/Boolfly_payment_fee.git`

`composer require --prefer-source 'boolfly/payment-fee:*'`

`php bin/magento setup:upgrade`

`php bin/magento setup:di:compile`

`php bin/magento cache:flush`

<h2>Technical Guide</h2>
-We can create an offline payment method here: http://cedcommerce.com/magento-2-module-creator/payment-module

-We can follow this guide to add a specific fee to order total: http://magento.stackexchange.com/questions/92774/how-to-add-fee-to-order-totals-in-magento2

-Replace a default JS component: http://devdocs.magento.com/guides/v2.0/javascript-dev-guide/javascript/custom_js.html 

<h3>Admin Config:</h3> 
**STORES > Configuration > SALES > Payment Fee**

<img src="https://github.com/mrkhoa99/Boolfly_payment_fee/blob/master/screenshots/Payment%20admin%20config.png" alt="Boolfly Payment admin config"/>

<h3>Creating order Admin</h3>

<img src="https://github.com/mrkhoa99/Boolfly_payment_fee/blob/master/screenshots/Create%20order%20admin.png" alt="Boolfly Payment fee for creating order admin"/>

<h3>Checkout Page:</h3>

<img src="https://github.com/mrkhoa99/Boolfly_payment_fee/blob/master/screenshots/One%20Page%20Checkout.png" alt="Boofly Payment fee on checkout Page"/>

**Tested: Magento 2.1.2**
