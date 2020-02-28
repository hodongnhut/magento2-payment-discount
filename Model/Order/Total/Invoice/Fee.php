<?php declare(strict_types=1);

namespace Boolfly\PaymentFee\Model\Order\Total\Invoice;

use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;
use Psr\Log\LoggerInterface;

class Fee extends AbstractTotal
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
