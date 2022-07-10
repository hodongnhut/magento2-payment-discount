<?php declare(strict_types=1);

namespace Lg\PaymentDiscount\Block\Adminhtml\Sales\Order\Invoice;

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

    /**
     * @return mixed
     */
    public function getInvoice()
    {
        return $this->getParentBlock()->getInvoice();
    }
    /**
     * Initialize payment fee totals
     *
     * @return $this
     */
    public function initTotals()
    {
        $this->getParentBlock();
        $this->getInvoice();
        $this->getSource();

        if(!$this->getSource()->getDiscountPaymentAmount()) {
            return $this;
        }
        $total = new DataObject(
            [
                'code' => 'payment_discount',
                'value' => - $this->getSource()->getDiscountPaymentAmount(),
                'label' => __('Payment Discount'),
            ]
        );

        $this->getParentBlock()->addTotalBefore($total, 'grand_total');
        return $this;
    }
}