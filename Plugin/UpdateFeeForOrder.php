<?php

namespace Boolfly\PaymentFee\Plugin;

class UpdateFeeForOrder
{
    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    protected $quote;
    protected $logger;
    protected $_checkoutSession;
    protected $_registry;

    public function __construct(
        \Magento\Quote\Model\Quote $quote,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Registry $registry
    ) {
        $this->quote = $quote;
        $this->logger = $logger;
        $this->_checkoutSession = $checkoutSession;
        $this->_registry = $registry;
    }

    /**
     * Get shipping, tax, subtotal and discount amounts all together
     *
     * @return array
     */
    public function beforeGetAllItems($cart)
    {
        // $paypalTest = $this->_registry->registry('is_paypal_items')? $this->_registry->registry('is_paypal_items') : 0;
        // $quote = $this->_checkoutSession->getQuote();
        // $paymentMethod = $quote->getPayment()->getMethod();
        
        // $paypalMehodList = ['payflowpro','payflow_link','payflow_advanced','braintree_paypal','paypal_express_bml','payflow_express_bml','payflow_express','paypal_express'];
        // if($paypalTest < 3 && in_array($paymentMethod,$paypalMehodList)){
        //     if(method_exists($cart , 'addCustomItem' )) {
        //         $cart->addCustomItem(__("Payment Fee"), 1 ,$quote->getFeeAmount());
        //         $reg = $this->_registry->registry('is_paypal_items');
        //         $current = $reg + 1 ;
        //         $this->_registry->unregister('is_paypal_items');
        //         $this->_registry->register('is_paypal_items', $current);
        //     }
        // }
    }
}
 