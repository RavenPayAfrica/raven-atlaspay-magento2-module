<?php
/**
 * AtlasPay Magento2 Module using \Magento\Payment\Model\Method\AbstractMethod
 * Copyright (C) 2019 AtlasPay.com
 *
 * This file is part of Raven/AtlasPay.
 *
 * Raven/AtlasPay is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Raven\AtlasPay\Model;
use Magento\Sales\Model\Order;
use Magento\Quote\Model\QuoteFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class PaymentManagement implements \Raven\AtlasPay\Api\PaymentManagementInterface
{

    protected $order;

    protected $quoteFactory;

    protected $orderRepository;

    protected $_objectManager;

    protected $searchCriteriaBuilder;


    public function __construct(
        Order $order,
        QuoteFactory $quoteFactory,
        OrderRepositoryInterface $orderRepository,
        \Magento\Framework\Event\Manager $eventManager,
        \Magento\Checkout\Model\Session $checkoutSession,
        SearchCriteriaBuilder $searchCriteriaBuilder

        )
    {
        $this->order = $order;
        $this->eventManager = $eventManager;
        $this->quoteFactory = $quoteFactory;
        $this->orderRepository = $orderRepository;
        $this->checkoutSession = $checkoutSession;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;

    }
    public function orderStatusChange()
    {
        $orderId = 9999;
        $order = $this->order->load($orderId);
        $order->setStatus("Status Code");
        $order->save();
    }

    /**
     * @param string $reference
     * @return array
     */
    public function verifyPayment($reference)
    {

        try {
            $order = $this->getOrder($reference);
            $orderId = $order->getId();
            $quoteId = $order->getQuoteId();

            if ($order && $quoteId === $reference){
                $eventData = ['atlaspay_order' => $order];
                // $this->eventManager->dispatch('atlaspay_payment_verify_after', $eventData);

                 // dispatch the `atlaspay_payment_verify_after` event to update the order status
                 $this->eventManager->dispatch('atlaspay_payment_verify_after', [
                    "atlaspay_order" => $order,
                ]);
                return array([
                    'quoteId' => $quoteId,
                    'orderId' => $orderId,
                    'grandTotal' => $order->getGrandTotal(),
                    'status' => $order->getStatus(),
                    'increment' => $this->getOrder($quoteId),
                ]);

            }

        } catch (\Exception $e) {
            return json_encode([
                'status'=>0,
                'message'=>$e->getMessage(),
                "data"=>$this->checkoutSession

            ]);
        }

    }

    private function getOrder($quoteId)
{
    $this->searchCriteriaBuilder->addFilter('quote_id', $quoteId);
    $searchCriteria = $this->searchCriteriaBuilder->create();
    $orderList = $this->orderRepository->getList($searchCriteria)->getItems();
    // Check if the list is not empty
    if (count($orderList) > 0) {
        // Assuming there is only one order per quote ID
        $order = reset($orderList);
        return $order;
    } else {
        return null;
    }
}

}
