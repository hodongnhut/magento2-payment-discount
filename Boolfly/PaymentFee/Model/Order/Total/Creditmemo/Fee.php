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
        if ($order->getFeeAmountInvoiced() > 0) {
            $feeAmountLeft     = $order->getFeeAmountInvoiced() - $order->getFeeAmountRefunded();
            $basefeeAmountLeft = $order->getBaseFeeAmountInvoiced() - $order->getBaseFeeAmountRefunded();
            if ($basefeeAmountLeft > 0) {
                $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $feeAmountLeft);
                $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $basefeeAmountLeft);
                $creditmemo->setFeeAmount($feeAmountLeft);
                $creditmemo->setBaseFeeAmount($basefeeAmountLeft);
            }
        } else {
            $feeAmount     = $order->getFeeAmountInvoiced();
            $basefeeAmount = $order->getBaseFeeAmountInvoiced();
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $feeAmount);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $basefeeAmount);
            $creditmemo->setFeeAmount($feeAmount);
            $creditmemo->setBaseFeeAmount($basefeeAmount);
        }
        return $this;

        return $this;
    }
}