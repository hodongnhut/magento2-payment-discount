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
    const CONFIG_PAYMENT_DISCOUNT = 'payment_discount/config_discount/';
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
                $initialDiscount = $this->getConfig('discount');
                $discounts        = is_array($initialDiscount) ? $initialDiscount : $this->serializer->unserialize($initialDiscount);
            } catch (InvalidArgumentException $e) {
                $discounts = [];
            }

            if (is_array($discounts)) {
                foreach ($discounts as $discount) {
                    $this->methodDiscount[$discount['payment_method']] = [
                        'discount'  => $discount['discount'],
                        'label'     => $discount['label'],
                        'calculate' => $discount['calculate']
                    ];
                }
            }
        }
        return $this->methodDiscount;
    }

    public function getDiscountPaymentDefault()
    {
        $discount = [
            'discountTotal' => 0,
            'discountType' => null,
            'discountValue' => 0,
        ];
        return $discount;
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
    public function canApply(Quote $quote, $method)
    {
        if ($this->isEnabled()) {
            if (!empty($method) && is_string($method)  && isset($this->methodDiscount[$method])) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Quote $quote
     * @return float|int
     */
    public function getDiscount(Quote $quote, $method)
    {
        $discount = $this->getDiscountPaymentDefault();
        if(!empty($this->methodDiscount[$method]['discount'])) {
            $discount['discountValue'] = $this->methodDiscount[$method]['discount'];
            $discount['discountType'] = $this->getDiscountType($quote, $method);
            if ($discount['discountType'] != AbstractCarrier::HANDLING_TYPE_FIXED) {
                $subTotal = $quote->getSubtotalWithDiscount();
                $discount['discountTotal'] = $subTotal *  ($discount['discountValue'] / 100);
            }
        }
        return $discount;
    }

    /**
     * @param Quote $quote
     * @return float|int
     */
    public function getDiscountLabel(Quote $quote)
    {
        $method = $quote->getPayment()->getMethod();
        if (!empty($this->methodDiscount[$method]['label'])) {
            return __($this->methodDiscount[$method]['label']);
        }
        return __('Payment Discount');
    }

    /**
     * Retrieve Fee type from Store config (Percent or Fixed)
     * @return string
     */
    public function getDiscountType(Quote $quote, $method)
    {
        $type = AbstractCarrier::HANDLING_TYPE_FIXED;
        if (!empty($this->methodDiscount[$method]['calculate'])) {
            $type = $this->methodDiscount[$method]['calculate'];
        }
        return $type;
    }


    /*
    * Searches for $needle in the multidimensional array $haystack.
    *
    * @param mixed $needle The item to search for
    * @param array $haystack The array to search
    * @return array|bool The indices of $needle in $haystack across the
    *  various dimensions. FALSE if $needle was not found.
    */
    public function arraySearchKey($needle, $haystack, $currentKey = '') {
        if (is_array($haystack)) {
            foreach ($haystack as $key => $value) {
                if ($key === $needle) {
                    return $value;
                } elseif (is_array($value)) {
                    $check = $this->arraySearchKey($needle, $value);
                    if($check)
                       return $check;
                }
            }
        }
        return false;
    }

    public function checkRequestShipping($url)
    {
        $shippingLink = ['shipping-information', 'estimate-shipping-methods'];
        $aliasUrl = explode("/", $url);
        $aliasUrl = array_values(array_slice($aliasUrl, -1))[0]; 
        if(in_array($aliasUrl, $shippingLink)) {
            return true;
        }
        return false;
    }
}
