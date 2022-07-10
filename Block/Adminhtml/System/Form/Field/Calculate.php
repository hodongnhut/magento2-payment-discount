<?php declare(strict_types=1);

namespace Lg\PaymentDiscount\Block\Adminhtml\System\Form\Field;

use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;
use Magento\Payment\Model\Config;

class Calculate extends Select
{
    /**
     * Payment methods cache
     *
     * @var array
     */
    private $methods;

    /**
     * @var Config
     */
    protected $paymentConfig;

    /**
     * Methods constructor.
     * @param Context $context
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->paymentConfigCalculate = [
            [
                'code' => 'F',
                'value'=> 'Fixed'
            ],
            [
                'code' => 'P',
                'value'=> 'Percent'
            ]
        ];
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            foreach ($this->paymentConfigCalculate as $paymentModel) {
                $this->addOption($paymentModel['code'], addslashes($paymentModel['value']));
            }
        }
        return parent::_toHtml();
    }
}
