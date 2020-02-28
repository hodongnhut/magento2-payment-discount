<?php declare(strict_types=1);

namespace Boolfly\PaymentFee\Block\Sales;

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
        if (!$this->_source->getFeeAmount()) {
            return $this;
        }

        $fee = new DataObject(
            [
                'code' => 'fee',
                'strong' => false,
                'value' => $this->_source->getFeeAmount(),
                'label' => __('Payment Fee'),
            ]
        );

        $parent->addTotal($fee, 'fee');

        return $this;
    }
}
