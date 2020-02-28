<?php declare(strict_types=1);

namespace Boolfly\PaymentFee\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Payment\Model\Config;

class ActiveMethods
{
    /**
     * @var Config
     */
    protected $paymentConfig;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * ActiveMethods constructor.
     * @param Config $config
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Config $config,
        ScopeConfigInterface $scopeConfig
    ) {
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
            $paymentTitle = $this->scopeConfig->getValue('payment/' . $paymentCode . '/title');
            $methods[$paymentCode] = [
                'label'   => $paymentTitle,
                'value' => $paymentCode
            ];
        }
        return $methods;
    }
}
