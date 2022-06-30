**Magento Payment specific Discount or Fee to the payment in Magento 2

**Install with Composer**
composer require --prefer-source 'hodongnhut/magento2-payment-discount:*'

php bin/magento setup:upgrade

php bin/magento setup:di:compile

php bin/magento cache:flush

**Technical Guide**
- We can follow this guide to add a specific discocunt to order total**:
https://magento.stackexchange.com/questions/204532/magento-2-2-1-how-to-give-discount-on-payment-methods

-Replace a default JS component: http://devdocs.magento.com/guides/v2.0/javascript-dev-guide/javascript/custom_js.html

Magento 2 - How to add custom discount in cart programmatically
https://magento.stackexchange.com/questions/104112/magento-2-how-to-add-custom-discount-in-cart-programmatically

Magento 2 - discount depend on Payment Method does not work
https://magento.stackexchange.com/questions/128580/magento-2-discount-depend-on-payment-method-does-not-work

**Admin Config:**
Admin -> Store Config ->Sales -> Payment Discount
![image](https://user-images.githubusercontent.com/8769219/176577256-1c58109c-3fe2-42d8-aa60-746ada0c6554.png)

Storefont:
![image](https://user-images.githubusercontent.com/8769219/175768891-2cb93efa-0092-46df-b791-e3373a4fda47.png)

**Contact**
If have any Question please contact me on Skype: **hodongnhut**, Website: https://fitgroupco.com/
