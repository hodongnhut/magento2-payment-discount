<?php declare(strict_types=1);

namespace Lg\PaymentDiscount\Model\Quote\Address\Total;

use Lg\PaymentDiscount\Helper\Data;
use Magento\Checkout\Model\Session;
use Magento\Framework\Phrase;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Quote\Model\QuoteValidator;
use Psr\Log\LoggerInterface;

class Discount extends AbstractTotal
{
    /**
     * @var string
     */
    protected $_code = 'discount';
    /**
     * @var Data
     */
    protected $_helperData;
    /**
     * @var Session
     */
    protected $_checkoutSession;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Collect grand total address amount
     *
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return $this
     */
    protected $_quoteValidator = null;

    /**
     * Payment Fee constructor.
     * @param QuoteValidator $quoteValidator
     * @param Session $checkoutSession
     * @param PaymentInterface $payment
     * @param Data $helperData
     * @param LoggerInterface $loggerInterface
     */
    public function __construct(
        QuoteValidator $quoteValidator,
        Session $checkoutSession,
        PaymentInterface $payment,
        Data $helperData,
        LoggerInterface $loggerInterface
    ) {
        $this->_quoteValidator = $quoteValidator;
        $this->_helperData = $helperData;
        $this->_checkoutSession = $checkoutSession;
        $this->logger = $loggerInterface;
    }

    /**
     * Collect totals process.
     *
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return $this
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

        $discount = 0;
        if ($this->_helperData->canApply($quote)) {
            $discount = $this->_helperData->getDiscount($quote);
        }
        $total->setTotalAmount('discount_payment', $discount);
        $total->setBaseTotalAmount('discount_payment', $discount);

        $total->setGrandTotal($total->getGrandTotal() - $discount);
        $total->setBaseGrandTotal($total->getBaseGrandTotal() - $discount);

        // Make sure that quote is also updated
        $quote->setDiscountPaymentAmount($discount);
        $quote->setBaseDiscountPaymentAmount($discount);
        $quote->setGrandTotal($total->getGrandTotal() - $discount);
        $quote->setBaseGrandTotal($total->getBaseGrandTotal() - $discount);
        
        return $this;
    }

    /**
     * Assign subtotal amount and label to address object
     *
     * @param Quote $quote
     * @param Total $total
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fetch(
        Quote $quote,
        Total $total
    ) {
        $result = [
            'code'  => $this->getCode(),
            'title' => $this->_helperData->getDiscountDescription($quote),
            'value' => -$quote->getDiscountPaymentAmount()
        ];
        return $result;
    }

    /** 
     * Get Subtotal label
     *
     * @return Phrase
     */
    public function getLabel()
    {
        return __('Payment Discount');
    }
}
