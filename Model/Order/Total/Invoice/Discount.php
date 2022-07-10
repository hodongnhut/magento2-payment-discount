<?php declare(strict_types=1);

namespace Lg\PaymentDiscount\Model\Order\Total\Invoice;

use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;
use Psr\Log\LoggerInterface;

class Discount extends AbstractTotal
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Invoice Fee constructor.
     * @param LoggerInterface $loggerInterface
     */
    public function __construct(
        LoggerInterface $loggerInterface
    ) {
        $this->logger = $loggerInterface;
    }

    /**
     * Collect invoice subtotal
     *
     * @param Invoice $invoice
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function collect(Invoice $invoice)
    {
        $order = $invoice->getOrder();
        $this->logger->debug('PaymentDiscountInvoice', [
            'orderId' => $order->getId()
        ]);
        $discount = $order->getDiscountPaymentAmount();
        $baseDiscount = $order->getBaseDiscountPaymentAmount();

        $invoice->setDiscountPaymentAmount($discount);
        $invoice->setBaseDiscountPaymentAmount($baseDiscount);
        $invoice->setGrandTotal($invoice->getGrandTotal() - $discount);
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() - $baseDiscount);
        $invoice->setSubtotalInclTax($invoice->getSubtotalInclTax() - $discount);
        $invoice->setBaseSubtotalInclTax($invoice->getBaseSubtotalInclTax() - $baseDiscount);

        //discount_payment_amount_invoiced
        $order->setDiscountPaymentAmountInvoiced($discount);
        $order->setBaseDiscountPaymentAmountInvoiced($baseDiscount);

        return $this;
    }
}
