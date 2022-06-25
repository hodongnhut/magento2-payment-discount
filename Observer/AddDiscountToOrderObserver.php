<?php declare(strict_types=1);

namespace Lg\PaymentDiscount\Observer;

use Lg\PaymentDiscount\Helper\Data;
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
            $discount = $this->_helper->getDiscount($quote);
            $order = $observer->getOrder();
            $order->setData('discount_payment_amount', $discount);
            $order->setData('base_discount_payment_amount', $discount);
        }
        return $this;
    }
}
