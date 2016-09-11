<?php

namespace Boolfly\PaymentFee\Helper;



class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * Recipient fixed amount of custom payment config path
     */
    const CONFIG_PAYMENT_FEE = 'payment/paymentfee/amount';
    /**
     * Total Code
     */
    const TOTAL_CODE = 'fee';
    /**
     * @var array
     */
    public $methodFee = NULL;
    /**
     * Constructor
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    )
    {
        parent::__construct($context);
    }

    /**
     * Retrieve Payment Method Fees from Store Config
     * @return array
     */
    protected function _getMethodFee() {
    if (is_null($this->methodFee)) {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $this->methodFee = $this->scopeConfig->getValue(self::CONFIG_PAYMENT_FEE, $storeScope);
    }
    return $this->methodFee;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Address\Total $address
     * @return bool
     */
    public function canApply(\Magento\Quote\Model\Quote\Address\Total $address) {

        $quote = $address->getQuote();
        /**@TODO check module or config**/
        if ($this->isModuleOutputEnabled()) {
            if ($method = $quote->getPayment()->getMethod()) {
                if (isset($this->methodFee[$method])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Address\Total $address
     * @return float|int
     */
    public function getFee(\Magento\Quote\Model\Quote\Address\Total $address) {
        /* @var $quote \Magento\Quote\Model\Quote */
        $quote   = $address->getQuote();
        $method  = $quote->getPayment()->getMethod();
        $fee     = $this->methodFee[$method]['fee'];
        $feeType = $this->getFeeType();
        if ($feeType == \Magento\Shipping\Model\Carrier\AbstractCarrier::HANDLING_TYPE_FIXED) {
            return $fee;
        } else {
            $totals = $quote->getTotals();
            $sum    = 0;
            foreach ($totals as $total) {
                if ($total->getCode() != self::TOTAL_CODE) {
                    $sum += (float)$total->getValue();
                }
            }
            return ($sum * ($fee / 100));
        }
    }

    /**
     * @TODO retrieve config
     * @return string
     */
    public function getFeeType()
    {
        return 'F';
    }
}