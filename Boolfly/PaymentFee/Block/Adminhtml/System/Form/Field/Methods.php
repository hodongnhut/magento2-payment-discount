<?php

namespace Boolfly\PaymentFee\Block\Adminhtml\System\Form\Field;

class Methods extends \Magento\Framework\View\Element\Html\Select
{
    /**
     * Payment methods cache
     *
     * @var array
     */
    private $methods;

    /**
     * @var \Magento\Payment\Model\Config
     */
    protected $paymentConfig;

    /**
     * Methods constructor.
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Payment\Model\Config $config
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Payment\Model\Config $config,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->paymentConfig = $config;

    }

    protected function _getPaymentMethods()
    {
        if($this->methods === null) {
            $this->methods = $this->paymentConfig->getActiveMethods();
        }
        return $this->methods;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }


//    public function toOptionArray()
//    {
//        $methods = [['value'=>'', 'label'=>'']];
//        $payments = $this->_getPaymentMethods();
//
//        foreach ($payments as $paymentCode => $paymentModel) {
//            $paymentTitle = $this->scopeConfig->getValue('payment/'.$paymentCode.'/title');
//            $methods[$paymentCode] = [
//                'label'   => $paymentTitle,
//                'value' => $paymentCode
//            ];
//        }
//        return $methods;
//    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            foreach ($this->_getPaymentMethods() as $paymentCode => $paymentModel) {
                $paymentTitle = $this->_scopeConfig->getValue('payment/'.$paymentCode.'/title');
                $this->addOption($paymentCode, addslashes($paymentTitle));
            }
        }
        return parent::_toHtml();
    }
}
