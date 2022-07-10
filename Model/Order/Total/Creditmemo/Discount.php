<?php declare(strict_types=1);

namespace Lg\PaymentDiscount\Model\Order\Total\Creditmemo;

use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;
use Psr\Log\LoggerInterface;

class Discount extends AbstractTotal
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Credit Memo Fee constructor.
     * @param LoggerInterface $loggerInterface
     */
    public function __construct(
        LoggerInterface $loggerInterface
    ) {
        $this->logger = $loggerInterface;
    }

    /**
     * @param Creditmemo $creditmemo
     * @return $this
     */
    public function collect(Creditmemo $creditmemo)
    {
       
        $order = $creditmemo->getOrder();
        $this->logger->debug('PaymentDiscountCreditmemo', [
            'orderId' => $order->getId()
        ]);
        $discountAmountInvoiced = $order->getDiscountPaymentAmountInvoiced();
        $baseDiscountAmountInvoiced = $order->getBaseDiscountPaymentAmountInvoiced();

        // Nothing to refound
        if ((int)$discountAmountInvoiced === 0) {
            return $this;
        }

        // Check if refound has already been done discount_payment_amount_refunded
        $discountAmountRefunded = $order->getDiscountPaymentAmountRefunded();
        if ((int)$discountAmountRefunded === 0) {
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() - $discountAmountInvoiced);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() - $baseDiscountAmountInvoiced);
            $creditmemo->setDiscountPaymentAmount($discountAmountInvoiced);
            $creditmemo->setBaseDiscountPaymentAmount($baseDiscountAmountInvoiced);

            // Set fee amount refunded into order
            $order->setDiscountPaymentAmountRefunded($discountAmountInvoiced);
            $order->setBaseDiscountPaymentAmountRefunded($baseDiscountAmountInvoiced);
        }

        return $this;
    }
}
