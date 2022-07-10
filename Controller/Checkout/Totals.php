<?php declare(strict_types=1);

namespace Lg\PaymentDiscount\Controller\Checkout;

use Exception;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Json\Helper\Data;
use Magento\Quote\Api\CartRepositoryInterface;
use Psr\Log\LoggerInterface;

class Totals extends Action
{
    /**
     * @var Session
     */
    protected $_checkoutSession;

    /**
     * @var JsonFactory
     */
    protected $_resultJson;

    /**
     * @var Data
     */
    protected $_helper;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    public function __construct(
        Context $context,
        Session $checkoutSession,
        Data $helper,
        JsonFactory $resultJson,
        CartRepositoryInterface $quoteRepository,
        LoggerInterface $loggerInterface
    ) {
        parent::__construct($context);
        $this->_checkoutSession = $checkoutSession;
        $this->_helper = $helper;
        $this->_resultJson = $resultJson;
        $this->quoteRepository = $quoteRepository;
        $this->logger = $loggerInterface;
    }

    /**
     * Trigger to re-calculate the collect Totals
     *
     * @return Json
     */
    public function execute()
    {
        $response = [
            'errors' => false,
            'message' => 'Re-calculate successful.'
        ];
        try {
            $this->quoteRepository->get($this->_checkoutSession->getQuoteId());
            $payment = $this->_helper->jsonDecode($this->getRequest()->getContent());
            $this->_checkoutSession->getQuote()->getPayment()->setMethod($payment['payment']);
            $this->quoteRepository->save($this->_checkoutSession->getQuote()->collectTotals());
        } catch (Exception $e) {
            $response = [
                'errors' => true,
                'message' => $e->getMessage()
            ];
        }

        /** @var Raw $resultRaw */
        $resultJson = $this->_resultJson->create();
        return $resultJson->setData($response);
    }
}
