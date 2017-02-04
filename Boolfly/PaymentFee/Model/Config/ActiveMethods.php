<?php

namespace Boolfly\PaymentFee\Model\Config;

class ActiveMethods
{
    /**
     * @var \Magento\Payment\Model\Config
     */
    protected $paymentConfig;

    protected $scopeConfig;

    public function __construct(
        \Magento\Payment\Model\Config $config,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->paymentConfig = $config;
        $this->scopeConfig = $scopeConfig;
    }

    protected function _getPaymentMethods()
    {
        return $this->paymentConfig->getActiveMethods();
    }

    public function toOptionArray()
    {
        $methods = [['value'=>'', 'label'=>'']];
        $payments = $this->_getPaymentMethods();

        foreach ($payments as $paymentCode => $paymentModel) {
            $paymentTitle = $this->scopeConfig->getValue('payment/'.$paymentCode.'/title');
            $methods[$paymentCode] = [
                'label'   => $paymentTitle,
                'value' => $paymentCode
            ];
        }
        return $methods;
    }
}