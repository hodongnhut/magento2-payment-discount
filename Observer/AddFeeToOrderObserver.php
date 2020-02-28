<?php declare(strict_types=1);

namespace Boolfly\PaymentFee\Observer;

use Boolfly\PaymentFee\Helper\Data;
use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class AddFeeToOrderObserver implements ObserverInterface
{
    /**
     * @var Session
     */
    protected $_checkoutSession;

    /** @var Data  */
    protected $_helper;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AddFeeToOrderObserver constructor.
     * @param Session $checkoutSession
     */
    public function __construct(
        Session $checkoutSession,
        Data $helper,
        LoggerInterface $loggerInterface
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_helper = $helper;
        $this->logger = $loggerInterface;
    }

    /**
     * Set payment fee to order
     *
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        $quote = $observer->getQuote();
        if ($this->_helper->canApply($quote)) {
            $feeAmount = $this->_helper->getFee($quote);

            //Set fee data to order
            $order = $observer->getOrder();
            $order->setData('fee_amount', $feeAmount);
            $order->setData('base_fee_amount', $feeAmount);
        }

        return $this;
    }
}
