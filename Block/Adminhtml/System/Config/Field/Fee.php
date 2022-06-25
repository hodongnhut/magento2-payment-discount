<?php declare(strict_types=1);

namespace Lg\PaymentDiscount\Block\Adminhtml\System\Config\Field;

use Exception;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class Fee extends AbstractFieldArray
{
    protected $_columns = [];

    protected $_typeRenderer;

    protected $_searchFieldRenderer;

    protected function _prepareToRender()
    {
        $this->_typeRenderer        = null;
        $this->_searchFieldRenderer = null;

        $this->addColumn('payment_method', ['label' => __('Payment Method')]);
        $this->addColumn('fee', ['label' => __('Discount')]);
        $this->addColumn('description', ['label' => __('Description')]);

        $this->_addAfter       = false;
        $this->_addButtonLabel = __('Add Discount');
    }

    /**
     * @param string $columnName
     * @return string
     * @throws Exception
     */
    public function renderCellTemplate($columnName)
    {
        $inputName = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';
        if ($columnName == "payment_method") {
            return $this->_getPaymentRenderer()
                ->setName($inputName)
                ->setTitle($columnName)
                ->setExtraParams('style="width:260px"')
                ->setClass('validate-select')
                ->setOptions(Mage::getModel("adminhtml/system_config_source_payment_allowedmethods")->toOptionArray(null))
                ->toHtml();
        } elseif ($columnName == "fee") {
            $this->_columns[$columnName]['class'] = 'input-text required-entry validate-number';
            $this->_columns[$columnName]['style'] = 'width:50px';
        }

        return parent::renderCellTemplate($columnName);
    }

    /**
     * @return mixed
     * @throws LocalizedException
     */
    protected function _getPaymentRenderer()
    {
        if (!$this->_typeRenderer) {
            $this->_typeRenderer = $this->getLayout()
                ->createBlock('Lg\PaymentDiscount\Adminhtml\System\Config\Render\Select')
                ->setIsRenderToJsTemplate(true);
        }
        return $this->_typeRenderer;
    }

    /**
     * @param DataObject $row
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $row->setData('option_extra_attr_' . $this->_getPaymentRenderer()->calcOptionHash($row->getData('payment_method')), 'selected="selected"');
    }
}
