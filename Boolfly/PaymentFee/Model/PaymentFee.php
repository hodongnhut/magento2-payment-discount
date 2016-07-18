<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Boolfly\PaymentFee\Model;



/**
 * Pay In Store payment method model
 */
class PaymentFee extends \Magento\Payment\Model\Method\AbstractMethod
{

    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = 'paymentfee';

    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = true;


  

}
