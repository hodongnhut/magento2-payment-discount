<?php

namespace Boolfly\PaymentFee\Model\Order\Total\Invoice;

use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;

class Fee extends AbstractTotal
{

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Invoice Fee constructor.
     * @param \Psr\Log\LoggerInterface $loggerInterface
     */
    public function __construct(
        \Psr\Log\LoggerInterface $loggerInterface
    )
    {
        $this->logger = $loggerInterface;
    }

    /**
     * Collect invoice subtotal
     *
     * @param \Magento\Sales\Model\Order\Invoice $invoice
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function collect(\Magento\Sales\Model\Order\Invoice $invoice)
    {
        $order = $invoice->getOrder();
        $feeAmount = $order->getFeeAmount();
        $baseFeeAmount = $order->getBaseFeeAmount();

        $invoice->setFeeAmount($feeAmount);
        $invoice->setBaseFeeAmount($baseFeeAmount);
        $invoice->setGrandTotal($invoice->getGrandTotal() + $feeAmount);
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseFeeAmount);

        $order->setFeeAmountInvoiced($feeAmount);
        $order->setBaseFeeAmountInvoiced($baseFeeAmount);

        return $this;
    }
}