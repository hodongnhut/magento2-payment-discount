<?php declare(strict_types=1);

namespace Lg\PaymentDiscount\Block\Sales;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\Order;

class Totals extends Template
{
    /**
     * @var Order
     */
    protected $_order;

    /**
     * @var DataObject
     */
    protected $_source;

    /**
     * Check if we nedd display full tax total info
     *
     * @return bool
     */
    public function displayFullSummary()
    {
        return true;
    }

    /**
     * Get data (totals) source model
     *
     * @return DataObject
     */
    public function getSource()
    {
        return $this->_source;
    }
    public function getStore()
    {
        return $this->_order->getStore();
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->_order;
    }
    /**
     * Initialize payment fee totals
     *
     * @return $this
     */
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->_order = $parent->getOrder();
        $this->_source = $parent->getSource();

        if (!$this->_source->getDiscountPaymentAmount()) {
            return $this;
        }

        $discount = new DataObject(
            [
                'code' => 'payment_discount',
                'strong' => false,
                'value' => $this->_source->getDiscountPaymentAmount(),
                'label' => __('Payment Discount'),
            ]
        );

        $parent->addTotal($discount, 'payment_discount');

        return $this;
    }
}
