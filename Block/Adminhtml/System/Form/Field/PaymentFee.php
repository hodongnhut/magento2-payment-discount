<?php

namespace Boolfly\PaymentFee\Block\Adminhtml\System\Form\Field;

class PaymentFee extends  \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    protected $_columns = [];

    /**
     * @var Methods
     */
    protected $_typeRenderer;

    protected $_searchFieldRenderer;

    /**
     * @var \Boolfly\PaymentFee\Model\Config\ActiveMethods
     */
    protected $activeMethods;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Boolfly\PaymentFee\Model\Config\ActiveMethods $activeMethods,
        array $data = []
    )
    {
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

        $this->addColumn('fee', ['label' => __('Fee')]);
        $this->addColumn('description', ['label' => __('Description')]);
        $this->_addAfter       = false;
        $this->_addButtonLabel = __('Add Fee');
    }


    /**
     * Retrieve active payment methods renderer
     *
     * @return Methods
     */
    protected function _getPaymentRenderer() {

        if (!$this->_typeRenderer) {
            $this->_typeRenderer = $this->getLayout()->createBlock(
                'Boolfly\PaymentFee\Block\Adminhtml\System\Form\Field\Methods',
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_typeRenderer->setClass('payemtfee_select');
        }
        return $this->_typeRenderer;
    }
    /**
     * Prepare existing row data object
     *
     * @param \Magento\Framework\DataObject $row
     * @return void
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row) {

        $optionExtraAttr = [];
        $optionExtraAttr['option_' . $this->_getPaymentRenderer()->calcOptionHash($row->getData('payment_method'))] =
            'selected="selected"';
        $row->setData(
            'option_extra_attrs',
            $optionExtraAttr
        );
    }
}