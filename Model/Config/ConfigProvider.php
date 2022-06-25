<?php declare(strict_types=1);

namespace Lg\PaymentDiscount\Model\Config;

use Lg\PaymentDiscount\Helper\Data;
use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Class ConfigProvider
 *
 * @package Lg\PaymentDiscount\Model\Config
 */
class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Data
     */
    private $configHelper;

    /**
     * ConfigProvider constructor.
     *
     * @param Data $configHelper
     */
    public function __construct(Data $configHelper)
    {
        $this->configHelper = $configHelper;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return [
            'lg_payment_discount' => ['is_active' => $this->configHelper->isEnabled()],
        ];
    }
}