<?php

namespace Raven\AtlasPay\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;


class ObserverAfterPaymentVerify implements ObserverInterface
{
    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderSender
     */
    protected $orderSender;

     /**
     * @var LoggerInterface
     */
    protected $_logger;

    public function __construct(
        \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender,
        LoggerInterface $logger

    ) {
        $this->orderSender = $orderSender;
        $this->_logger = $logger;

    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //Observer execution code...
        /** @var \Magento\Sales\Model\Order $order **/
        $order = $observer->getEvent()->getData('atlaspay_order');
        $this->_logger->info("ObserverAfterPaymentVerify has been fired for order #" . $order->getIncrementId());

        if ($order && $order->getStatus() == "pending") {
            // sets the status to processing since payment has been received
            $order->setState(Order::STATE_PROCESSING)
                    ->addStatusToHistory(Order::STATE_PROCESSING, __("AtlasPay Payment Verified and Order is being processed"), true)
                    ->setCanSendNewEmailFlag(true)
                    ->setCustomerNoteNotify(true);
            $order->save();

            $this->orderSender->send($order, true);
        }
    }
}
