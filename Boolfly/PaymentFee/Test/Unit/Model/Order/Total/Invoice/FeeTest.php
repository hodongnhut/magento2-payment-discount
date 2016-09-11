<?php

namespace Boolfly\PaymentFee\Test\Unit\Model\Order\Total\Invoice;

class FeeTest extends \PHPUnit_Framework_TestCase
{

    /** @var  \Boolfly\PaymentFee\Model\Order\Total\Invoice */

    protected $model;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->model = $objectManager->getObject('\Boolfly\PaymentFee\Model\Order\Total\Invoice');
    }

}
