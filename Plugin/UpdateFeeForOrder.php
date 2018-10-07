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
     * Add Fee as a custom line item
     *
     * @return array
     */
    public function beforeGetAllItems(\Magento\Paypal\Model\Cart $cart)
    {
        $quote = $this->_checkoutSession->getQuote();
        $paymentMethod = $quote->getPayment()->getMethod();
        
        $paypalMehodList = ['payflowpro','payflow_link','payflow_advanced','braintree_paypal','paypal_express_bml','payflow_express_bml','payflow_express','paypal_express'];
        if(!in_array($paymentMethod,$paypalMehodList)) return;
        
        $feeAmount = $quote->getFeeAmount();
        $cart->addCustomItem(__("Payment Fee"), 1 , $feeAmount, 'payment_method_fee');
        $cart->addSubtotal($feeAmount);
    }

    /**
     * Get shipping, tax, subtotal and discount amounts all together
     * No way to tell if we already added a fee line item in beforeGetAllItems :'(
     * We will filter out any extras
     *
     * @return array
     */
    public function afterGetAllItems(\Magento\Paypal\Model\Cart $cart, $result)
    {

        if (empty($result)) return $result;

        $found = false;
        foreach ($result as $key => $item) {
            
            if ($item->getId() != 'payment_method_fee') continue;

            if ($found) {
                unset($result[$key]);
                continue;
            }

            $found = true;
        }
        
        return $result;
    }
}
 