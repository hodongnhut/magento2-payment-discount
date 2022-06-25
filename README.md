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

**Admin Config:**
Admin -> Store Config ->Sales -> Payment Discount
![image](https://user-images.githubusercontent.com/8769219/175768768-f3418965-0cf7-4691-96a3-7d2feeb2d30c.png)
Storefont:
![image](https://user-images.githubusercontent.com/8769219/175768891-2cb93efa-0092-46df-b791-e3373a4fda47.png)

**Contact**
If have any Question please contact me on Skype: hodongnhut
