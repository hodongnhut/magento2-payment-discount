<?php

namespace Boolfly\PaymentFee\Plugin;

class LogPayPalNVPRequest
{

    protected $logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function beforeCall(\Magento\Paypal\Model\Api\Nvp $nvp, $methodName, array $request) {

        $this->logger->debug(__METHOD__ . " methodName $methodName ");
        $this->logger->debug(__METHOD__ . " request " . print_r($request,true));
    }
}