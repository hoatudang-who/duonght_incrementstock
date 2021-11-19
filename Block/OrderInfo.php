<?php

namespace Duonght\IncrementStock\Block;

class OrderInfo extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var \Magento\Framework\View\Element\Template\Context
     */
    protected $context;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Sales\Model\Order $lastOrder
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\Order $lastOrder,
        array $data = []
    ) {
        $this->orderRepository = $orderRepository;
        $this->checkoutSession = $checkoutSession;
        $this->lastOrder = $lastOrder;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Sales\Model\Order
     */
    public function getlastOrder()
    {
        $order = $this->checkoutSession->getLastRealOrder();
        $orderIncrementId = $order->getIncrementId();

        return $this->lastOrder->loadByIncrementId($orderIncrementId);
    }

    /**
     * @return mixed
     */
    public function getOrderPayment()
    {
        $order = $this->orderRepository->get($this->getOrderId());
        $paymentMethod = $order->getPayment()->getMethodInstance();

        return $paymentMethod->getTitle();
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->getlastOrder()->getEntityId();
    }

    /**
     * @param $orderId
     * @return string|null
     */
    public function getOrderStatus($orderId)
    {
        $order = $this->orderRepository->get($orderId);

        return $order->getStatus();

    }

    /**
     * @param $orderId
     * @return float|void|null
     */
    public function getOrderQuantity($orderId)
    {
        $order = $this->orderRepository->get($orderId);
        $orderItems = $order->getAllItems();
        foreach ($orderItems as $item) {
            return $item->getQtyOrdered();
        }
    }
}
