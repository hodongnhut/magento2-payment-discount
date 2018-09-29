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
        return;

        $paypalTest = $this->_registry->registry('is_paypal_items')? $this->_registry->registry('is_paypal_items') : 0;
        $quote = $this->_checkoutSession->getQuote();
        $paymentMethod = $quote->getPayment()->getMethod();

        $this->logger->debug(__METHOD__ . ' paymentMethod: ' .$paymentMethod);
        $this->logger->debug(__METHOD__ . ' paypalTest: ' .$paypalTest);
        
        $paypalMehodList = ['payflowpro','payflow_link','payflow_advanced','braintree_paypal','paypal_express_bml','payflow_express_bml','payflow_express','paypal_express'];
        if($paypalTest < 3 && in_array($paymentMethod,$paypalMehodList)){
            
            $this->logger->debug(__METHOD__ . ' correct payment theod');
            
            if(method_exists($cart , 'addCustomItem' )) {
                
                $this->logger->debug(__METHOD__ . ' addCustomItem exists');
                
                $feeAmount = $quote->getFeeAmount();
                $cart->addCustomItem(__("Payment Fee"), 1 , $feeAmount);

                $this->logger->debug(__METHOD__ . ' fee ' . $feeAmount);

                $reg = $this->_registry->registry('is_paypal_items');
                $current = $reg + 1 ;

                $items = $quote->getAllItems();

                foreach($items as $item) {
                    $this->logger->debug(
                        __METHOD__ . ' ID: '.$item->getProductId()."\n".
                        'Name: '.$item->getName()."\n".
                        'Sku: '.$item->getSku()."\n".
                        'Quantity: '.$item->getQty()."\n".
                        'Price: '.$item->getPrice()."\n"
                    );            
                }

                $this->logger->debug(__METHOD__ . ' subtotal ' . $quote->getSubtotal());
                $this->logger->debug(__METHOD__ . ' grandtotal ' . $quote->getGrandTotal());
                
                $this->_registry->unregister('is_paypal_items');
                $this->_registry->register('is_paypal_items', $current);
            }
        }
    }
}
 