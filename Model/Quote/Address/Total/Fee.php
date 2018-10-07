<?php

namespace Boolfly\PaymentFee\Model\Quote\Address\Total;

use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;

class Fee extends AbstractTotal
{
    /**
     * @var string
     */
    protected $_code = 'fee';
    /**
     * @var \Boolfly\PaymentFee\Helper\Data
     */
    protected $_helperData;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Collect grand total address amount
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     */
    protected $_quoteValidator = null;

    /**
     * Payment Fee constructor.
     * @param \Magento\Quote\Model\QuoteValidator $quoteValidator
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Quote\Api\Data\PaymentInterface $payment
     * @param \Boolfly\PaymentFee\Helper\Data $helperData
     * @param \Psr\Log\LoggerInterface $loggerInterface
     */
    public function __construct(
        \Magento\Quote\Model\QuoteValidator $quoteValidator,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Quote\Api\Data\PaymentInterface $payment,
        \Boolfly\PaymentFee\Helper\Data $helperData,
        \Psr\Log\LoggerInterface $loggerInterface
    )
    {
        $this->_quoteValidator = $quoteValidator;
        $this->_helperData = $helperData;
        $this->_checkoutSession = $checkoutSession;
        $this->logger = $loggerInterface;
    }

    /**
     * Collect totals process.
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

        $fee = 0;
        if($this->_helperData->canApply($quote)) {
            $fee = $this->_helperData->getFee($quote);
        }
        
        $total->setFeeAmount($fee);
        $total->setBaseFeeAmount($fee);
        
        $total->setTotalAmount('fee_amount', $fee);
        $total->setBaseTotalAmount('base_fee_amount', $fee);
        
        // // Duplicate fee added when this is added
        // $total->setGrandTotal($total->getGrandTotal() + $total->getFeeAmount());
        // $total->setBaseGrandTotal($total->getBaseGrandTotal() + $total->getBaseFeeAmount());
        $total->setGrandTotal($total->getGrandTotal());
        $total->setBaseGrandTotal($total->getBaseGrandTotal());

        // Make sure that quote is also updated
        $quote->setFeeAmount($fee);
        $quote->setBaseFeeAmount($fee);

        return $this;
    }

    /**
     * Assign subtotal amount and label to address object
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fetch(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        $result = [
            'code' => $this->getCode(),
            'title' => __('Payment Fee'),
            'value' => $total->getFeeAmount()
        ];
        return $result;
    }

    /**
     * Get Subtotal label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Payment Fee');
    }
}