<?php

namespace Boolfly\PaymentFee\Model\Config;

/**
 * Class ConfigProvider
 *
 * @package Boolfly\PaymentFee\Model\Config
 */
class ConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    /**
     * @var \Boolfly\PaymentFee\Helper\Data
     */
    private $configHelper;

    /**
     * ConfigProvider constructor.
     *
     * @param \Boolfly\PaymentFee\Helper\Data $configHelper
     */
    public function __construct(\Boolfly\PaymentFee\Helper\Data $configHelper)
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