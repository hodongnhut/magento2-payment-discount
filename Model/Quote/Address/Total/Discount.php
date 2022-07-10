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
use Magento\Framework\App\RequestInterface;

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
    protected $request;
    protected $payment;

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
        LoggerInterface $loggerInterface,
        RequestInterface $request
    ) {
        $this->_quoteValidator = $quoteValidator;
        $this->_helperData = $helperData;
        $this->_checkoutSession = $checkoutSession;
        $this->logger = $loggerInterface;
        $this->request = $request;
        $this->payment = $payment;
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
        if (!$this->_helperData->isEnabled()) {
            return parent::collect($quote, $shippingAssignment, $total);
        }

        if (!count($shippingAssignment->getItems())) {
            return $this;
        }
        $discount = $this->_helperData->getDiscountPaymentDefault();
        $address = $shippingAssignment->getShipping()->getAddress();
        if($this->request->getContent()) {
            $paymentMethod = $this->_helperData->arraySearchKey('payment_method', json_decode($this->request->getContent(), true));
            if ($paymentMethod) {
                if ($this->_helperData->canApply($quote, $paymentMethod)) {
                    $discount = $this->_helperData->getDiscount($quote, $paymentMethod);
                }
                $quote->getPayment()->setMethod($paymentMethod);
            }
            $shippingPage = $this->_helperData->checkRequestShipping($this->request->getPathInfo());
            if ($shippingPage) {
                $paymentMethod = $quote->getPayment()->getMethod();
                if ($this->_helperData->canApply($quote, $paymentMethod)) {
                    $discount = $this->_helperData->getDiscount($quote, $paymentMethod);
                }
            }
        }
        $total->setDiscountPaymentAmount($discount['discountTotal']);
        $total->setBaseDiscountPaymentAmount($discount['discountTotal']);

        $quote->setDiscountPaymentAmount($discount['discountTotal']);
        $quote->setBaseDiscountPaymentAmount($discount['discountTotal']);
        
        $quote->setDiscountPaymentType($discount['discountType']);
        $quote->setDiscountPaymentValue($discount['discountValue']);

        $address->setDiscountPaymentAmount($discount['discountTotal']);
        $address->setBaseDiscountPaymentAmount($discount['discountTotal']);
       
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
            'title' => $this->_helperData->getDiscountLabel($quote),
            'value' => - $quote->getDiscountPaymentAmount()
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
