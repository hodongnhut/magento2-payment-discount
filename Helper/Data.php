<?php declare(strict_types=1);

namespace Lg\PaymentDiscount\Helper;

use InvalidArgumentException;
use Magento\Directory\Model\PriceCurrency;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Unserialize\Unserialize;
use Magento\Quote\Model\Quote;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * Recipient fixed amount of custom payment config path
     */
    const CONFIG_PAYMENT_DISCOUNT = 'paymentdiscount/config/';
    /**
     * Total Code
     */
    const TOTAL_CODE = 'discount_amount';
    /**
     * @var array
     */
    public $methodDiscount = null;
    /**
     * Constructor
     */

    /**
     * @var SerializerInterface
     */
    protected $serializer;
    /**
     * @var Data
     */
    protected $pricingHelper;
    /**
     * @var PriceCurrency
     */
    protected $priceCurrency;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Data constructor.
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Pricing\Helper\Data $pricingHelper
     * @param PriceCurrency $priceCurrency
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        PriceCurrency $priceCurrency
    ) {
        parent::__construct($context);
        if (interface_exists(SerializerInterface::class)) {
            // >= Magento 2.2
            $this->serializer = $objectManager->get(SerializerInterface::class);
        } else {
            // < Magento 2.2
            $this->serializer = $objectManager->get(Unserialize::class);
        }
        $this->_getMethodDiscount();
        $this->pricingHelper = $pricingHelper;
        $this->priceCurrency = $priceCurrency;
        $this->logger = $context->getLogger();
    }

    /**
     * Retrieve Payment Method Fees from Store Config
     * @return array
     */
    protected function _getMethodDiscount()
    {
        if (is_null($this->methodDiscount)) {
            try {
                $initialFees = $this->getConfig('discount');
                $fees        = is_array($initialFees) ? $initialFees : $this->serializer->unserialize($initialFees);
            } catch (InvalidArgumentException $e) {
                $fees = [];
            }

            if (is_array($fees)) {
                foreach ($fees as $fee) {
                    $this->methodDiscount[$fee['payment_method']] = [
                        'discount'         => $fee['fee'],
                        'description' => $fee['description']
                    ];
                }
            }
        }
        return $this->methodDiscount;
    }

    /**
     * Retrieve Store Config
     * @param string $field
     * @return mixed|null
     */
    public function getConfig($field = '')
    {
        if ($field) {
            $storeScope = ScopeInterface::SCOPE_STORE;
            return $this->scopeConfig->getValue(self::CONFIG_PAYMENT_DISCOUNT . $field, $storeScope);
        }
        return null;
    }

    /**
     * Check if Extension is Enabled config
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getConfig('enabled');
    }
    /**
     * @param Quote $quote
     * @return bool
     */
    public function canApply(Quote $quote)
    {

        /**@TODO check module or config**/
        if ($this->isEnabled()) {
            if ($method = $quote->getPayment()->getMethod()) {
                if (isset($this->methodDiscount[$method])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param Quote $quote
     * @return float|int
     */
    public function getDiscount(Quote $quote)
    {
        $method = $quote->getPayment()->getMethod();
        $discount = 0;
        if(!empty($this->methodDiscount[$method]['discount'])) {
            $discount = $this->methodDiscount[$method]['discount'];
            $discountType = $this->getDiscountType();
            if ($discountType != AbstractCarrier::HANDLING_TYPE_FIXED) {
                $subTotal = $quote->getSubtotal();
                $discount = $subTotal *  ($discount / 100);
            }
        }
        return $discount;
    }

    /**
     * @param Quote $quote
     * @return float|int
     */
    public function getDiscountDescription(Quote $quote)
    {
        $description = null;
        $method = $quote->getPayment()->getMethod();
        if (!empty($this->methodDiscount[$method]['description'])) {
            $description = $this->methodDiscount[$method]['description'];
        }
        return __('Payment Discount %1', $description);
    }

    /**
     * Retrieve Fee type from Store config (Percent or Fixed)
     * @return string
     */
    public function getDiscountType()
    {
        return $this->getConfig('discount_type');
    }
}
