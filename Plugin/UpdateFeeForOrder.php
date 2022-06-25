<?php declare(strict_types=1);

namespace Lg\PaymentDiscount\Plugin;

use Magento\Checkout\Model\Session;
use Magento\Framework\Registry;
use Magento\Paypal\Model\Cart;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteFactory;
use Psr\Log\LoggerInterface;

class UpdateFeeForOrder
{
    /**
     * @var QuoteFactory
     */
    protected $quote;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Session
     */
    protected $_checkoutSession;

    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * UpdateFeeForOrder constructor.
     * @param Quote $quote
     * @param LoggerInterface $logger
     * @param Session $checkoutSession
     * @param Registry $registry
     */
    public function __construct(
        Quote $quote,
        LoggerInterface $logger,
        Session $checkoutSession,
        Registry $registry
    ) {
        $this->quote = $quote;
        $this->logger = $logger;
        $this->_checkoutSession = $checkoutSession;
        $this->_registry = $registry;
    }

    /**
     * Add Fee as a custom line item
     *
     * @param Cart $cart
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeGetAllItems(Cart $cart)
    {
        $quote = $this->_checkoutSession->getQuote();
        $paymentMethod = $quote->getPayment()->getMethod();

        $paypalMehodList = [];
        if (!in_array($paymentMethod, $paypalMehodList)) {
            return;
        }

        $discountAmount = $quote->getDiscountAmount();
        $cart->addCustomItem(__("Payment Discount"), 1, $discountAmount, 'payment_method_discount');
        $cart->addSubtotal($discountAmount);
    }

    /**
     * Get shipping, tax, subtotal and discount amounts all together
     * No way to tell if we already added a fee line item in beforeGetAllItems :'(
     * We will filter out any extras
     *
     * @param Cart $cart
     * @param $result
     * @return array
     */
    public function afterGetAllItems(Cart $cart, $result)
    {
        if (empty($result)) {
            return $result;
        }

        $found = false;
        foreach ($result as $key => $item) {
            if ($item->getId() != 'payment_method_discount') {
                continue;
            }

            if ($found) {
                unset($result[$key]);
                continue;
            }

            $found = true;
        }

        return $result;
    }
}
