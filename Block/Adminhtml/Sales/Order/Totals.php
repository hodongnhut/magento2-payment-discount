<?php

namespace Lg\PaymentDiscount\Block\Adminhtml\Sales\Order;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\Order;

class Totals extends Template
{

    /**
     * Retrieve current order model instance
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * @return $this
     */
    public function initTotals()
    {
        $this->getParentBlock();
        $this->getOrder();
        $this->getSource();

        if(!$this->getSource()->getDiscountPaymentAmount()) {
            return $this;
        }
        $total = new DataObject(
            [
                'code' => 'payment_discount',
                'value' => $this->getSource()->getDiscountPaymentAmount(),
                'label' => __('Payment Discount'),
            ]
        );
        $this->getParentBlock()->addTotalBefore($total, 'grand_total');

        return $this;
    }
}