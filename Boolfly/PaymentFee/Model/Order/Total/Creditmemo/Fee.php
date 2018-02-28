<?php

namespace Boolfly\PaymentFee\Model\Order\Total\Creditmemo;

use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;

class Fee extends AbstractTotal
{
    /**
     * @param \Magento\Sales\Model\Order\Creditmemo $creditmemo
     * @return $this
     */
    public function collect(\Magento\Sales\Model\Order\Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();

        $feeAmountInvoiced = $order->getFeeAmountInvoiced();
        $baseFeeAmountInvoiced = $order->getBaseFeeAmountInvoiced();

        // Nothing to refound
        if((int)$feeAmountInvoiced === 0){
            return $this;
        }

        // Check if refound has already been done
        $feeAmountRefunded = $order->getFeeAmountRefunded();
        if((int)$feeAmountRefunded === 0){
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $feeAmountInvoiced);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseFeeAmountInvoiced);
            $creditmemo->setFeeAmount($feeAmountInvoiced);
            $creditmemo->setBaseFeeAmount($baseFeeAmountInvoiced);

            // Set fee amount refunded into order
            $order->setFeeAmountRefunded($feeAmountInvoiced);
            $order->setBaseFeeAmountRefunded($baseFeeAmountInvoiced);
        }

        return $this;
    }
}