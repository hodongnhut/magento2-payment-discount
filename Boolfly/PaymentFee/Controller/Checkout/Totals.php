<?php

namespace Boolfly\PaymentFee\Controller\Checkout;

use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Session;

class Totals extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJson;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    public function __construct(
        Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Json\Helper\Data $helper,
        \Boolfly\PaymentFee\Logger\Logger $logger,
        \Magento\Framework\Controller\Result\JsonFactory $resultJson
    )
    {
        parent::__construct($context);
        $this->_checkoutSession = $checkoutSession;
        $this->_helper = $helper;
        $this->_logger = $logger;
        $this->_resultJson = $resultJson;
    }

    /**
     * Trigger to re-calculate the collect Totals
     *
     * @return bool
     */
    public function execute()
    {
        $response = [
            'errors' => false,
            'message' => 'Re-calculate successful.'
        ];
        try {
            //Trigger to re-calculate totals
            $payment = $this->_helper->jsonDecode($this->getRequest()->getContent());
            $this->_checkoutSession->getQuote()->getPayment()->setMethod('');
            if($payment['payment'] == \Boolfly\PaymentFee\Model\PaymentFee::PAYMENT_METHOD_FEE_CODE) {

                $this->_checkoutSession->getQuote()->getPayment()
                    ->setMethod(\Boolfly\PaymentFee\Model\PaymentFee::PAYMENT_METHOD_FEE_CODE);
            }
            $this->_checkoutSession->getQuote()->collectTotals()->save();

        } catch (\Exception $e) {
            $response = [
                'errors' => true,
                'message' => $e->getMessage()
            ];
        }

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultJson = $this->_resultJson->create();
        $this->_logger->info(
            $this->_checkoutSession->getQuote()->getId() . $response["message"]
        );
        return $resultJson->setData($response);
    }
}