<?php declare(strict_types=1);

namespace Lg\PaymentDiscount\Block\Adminhtml\Sales\Order\Creditmemo;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;

class Totals extends Template
{

    /**
     * Get data (totals) source model
     *
     * @return DataObject
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function getCreditmemo()
    {
        return $this->getParentBlock()->getCreditmemo();
    }
    /**
     * Initialize payment fee totals
     *
     * @return $this
     */
    public function initTotals()
    {
        $this->getParentBlock();
        $this->getCreditmemo();
        $this->getSource();

        if(!$this->getSource()->getDiscountPaymentAmount()) {
            return $this;
        }
        $fee = new DataObject(
            [
                'code' => 'payment_discount',
                'strong' => false,
                'value' => $this->getSource()->getDiscountPaymentAmount(),
                'label' => __('Payment Discount'),
            ]
        );

        $this->getParentBlock()->addTotalBefore($fee, 'grand_total');

        return $this;
    }
}