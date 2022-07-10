<?php declare(strict_types=1);

namespace Lg\PaymentDiscount\Block\Adminhtml\System\Form\Field;

use Lg\PaymentDiscount\Model\Config\ActiveMethods;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class PaymentDiscount extends AbstractFieldArray
{
    protected $_columns = [];

    /**
     * @var Methods
     */
    protected $_typeRenderer;

    protected $_typeRendererCal;

    protected $_searchFieldRenderer;

    /**
     * @var ActiveMethods
     */
    protected $activeMethods;

    public function __construct(
        Context $context,
        ActiveMethods $activeMethods,
        array $data = []
    ) {
        $this->activeMethods = $activeMethods;
        parent::__construct($context, $data);
    }

    /**
     * Prepare to render
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->_typeRenderer        = null;
        $this->_searchFieldRenderer = null;

        $this->addColumn(
            'payment_method',
            ['label' => __('Payment Method'), 'renderer' => $this->_getPaymentRenderer()]
        );
        $this->addColumn(
            'discount', 
            ['label' => __('Discount')]
        );
        $this->addColumn(
            'calculate',
            ['label' => __('Calculate'), 'renderer' => $this->_getCalculateRenderer()]
        );
        $this->addColumn(
            'label', 
            ['label' => __('Label')]
        );
        $this->_addAfter       = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Retrieve active payment methods renderer
     *
     * @return Methods
     * @throws LocalizedException
     */
    protected function _getPaymentRenderer()
    {
        if (!$this->_typeRenderer) {
            $this->_typeRenderer = $this->getLayout()->createBlock(
                'Lg\PaymentDiscount\Block\Adminhtml\System\Form\Field\Methods',
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_typeRenderer->setClass('payment_fee_select');
        }
        return $this->_typeRenderer;
    }

     /**
     * Retrieve active payment methods renderer
     *
     * @return Methods
     * @throws LocalizedException
     */
    protected function _getCalculateRenderer()
    {
        if (!$this->_typeRendererCal) {
            $this->_typeRendererCal = $this->getLayout()->createBlock(
                'Lg\PaymentDiscount\Block\Adminhtml\System\Form\Field\Calculate',
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_typeRendererCal->setClass('payment_calculate_select');
        }
        return $this->_typeRendererCal;
    }

    /**
     * Prepare existing row data object
     *
     * @param DataObject $row
     * @return void
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $optionExtraAttr = [];
        $optionExtraAttr['option_' . $this->_getPaymentRenderer()->calcOptionHash($row->getData('payment_method'))] =
            'selected="selected"';
        $optionExtraAttr['option_' . $this->_getCalculateRenderer()->calcOptionHash($row->getData('calculate'))] =
            'selected="selected"';
        $row->setData(
            'option_extra_attrs',
            $optionExtraAttr
        );
    }
}
