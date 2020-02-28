<?php declare(strict_types=1);

namespace Boolfly\PaymentFee\Plugin;

use Magento\Paypal\Model\Api\Nvp;
use Psr\Log\LoggerInterface;

class LogPayPalNVPRequest
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function beforeCall(Nvp $nvp, $methodName, array $request)
    {
        $this->logger->debug(__METHOD__ . " methodName $methodName ");
        $this->logger->debug(__METHOD__ . " request " . print_r($request, true));
    }
}
