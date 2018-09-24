<?php

namespace Boolfly\PaymentFee\Helper;

use Magento\Framework\Serialize\SerializerInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * Recipient fixed amount of custom payment config path
     */
    const CONFIG_PAYMENT_FEE = 'paymentfee/config/';
    /**
     * Total Code
     */
    const TOTAL_CODE = 'fee_amount';
    /**
     * @var array
     */
    public $methodFee = NULL;
    /**
     * Constructor
     */

    /**
     * @var SerializerInterface
     */
    protected $serializer;
    /**
     * @var Cart
     */
    protected $cart;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        SerializerInterface $serializer,
        \Magento\Checkout\Model\Cart $cart,
        \Psr\Log\LoggerInterface $loggerInterface
    )
    {
        parent::__construct($context);
        $this->serializer = $serializer;
        $this->_getMethodFee();
        $this->cart = $cart;
        $this->logger = $loggerInterface;
    }

    /**
     * Retrieve Payment Method Fees from Store Config
     * @return array
     */
    protected function _getMethodFee() {

        if (is_null($this->methodFee)) {
            $initialFees = $this->getConfig('fee');
            $fees = is_array($initialFees) ? $initialFees : $this->serializer->unserialize($initialFees);

            if(is_array($fees)) {
                foreach ($fees as $fee) {
                    $this->methodFee[$fee['payment_method']] = array(
                        'fee'         => $fee['fee'],
                        'description' => $fee['description']
                    );
                }
            }

        }
        return $this->methodFee;
    }

    /**
     * Retrieve Store Config
     * @param string $field
     * @return mixed|null
     */
    public function getConfig($field = '') {
        if ($field) {
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            return $this->scopeConfig->getValue(self::CONFIG_PAYMENT_FEE . $field, $storeScope);
        }
        return NULL;
    }

    /**
     * Check if Extension is Enabled config
     * @return bool
     */
    public function isEnabled() {
        return $this->getConfig('enabled');
    }
    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @return bool
     */
    public function canApply(\Magento\Quote\Model\Quote $quote) {

        /**@TODO check module or config**/
        if ($this->isEnabled()) {
            if ($method = $quote->getPayment()->getMethod()) {
                if (isset($this->methodFee[$method])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @return float|int
     */
    public function getFee(\Magento\Quote\Model\Quote $quote) {
        $method  = $quote->getPayment()->getMethod();
        $fee     = $this->methodFee[$method]['fee'];
        $feeType = $this->getFeeType();

        if ($feeType == \Magento\Shipping\Model\Carrier\AbstractCarrier::HANDLING_TYPE_FIXED) {
            return $fee;
        } else {
            $subTotal = $this->cart->getQuote()->getSubtotal();
            return ($subTotal * ($fee / 100));
        }
    }

    /**
     * Retrieve Fee type from Store config (Percent or Fixed)
     * @return string
     */
    public function getFeeType()
    {
        return $this->getConfig('fee_type');
    }
}
