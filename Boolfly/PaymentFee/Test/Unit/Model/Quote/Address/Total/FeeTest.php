<?php

namespace Boolfly\PaymentFee\Test\Unit\Model\Quote\Address\Total;

class FeeTest extends \PHPUnit_Framework_TestCase
{

    /** @var  \Boolfly\PaymentFee\Model\Quote\Address\Total */

    protected $model;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->model = $objectManager->getObject('Boolfly\PaymentFee\Model\Quote\Address\Total');
    }

}
