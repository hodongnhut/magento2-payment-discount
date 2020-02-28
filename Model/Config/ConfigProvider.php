<?php declare(strict_types=1);

namespace Boolfly\PaymentFee\Model\Config;

use Boolfly\PaymentFee\Helper\Data;
use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Class ConfigProvider
 *
 * @package Boolfly\PaymentFee\Model\Config
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
            'boolfly_payment_fee' => ['is_active' => $this->configHelper->isEnabled()],
        ];
    }
}