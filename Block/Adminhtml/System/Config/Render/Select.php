<?php

namespace Boolfly\PaymentFee\Block\Adminhtml\System\Config\Render;

class Select extends \Magento\Framework\View\Element\Html\Select
{
    public function _toHtml() {
        return trim(preg_replace('/\s+/', ' ', parent::_toHtml()));
    }
}