<?php

namespace Duonght\IncrementStock\Block;

use Lof\MarketPlace\Block\Seller\Becomeseller;

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
     * @param \Magento\Catalog\Model\Product $productCollection
     * @param \Magento\Sales\Model\Order $lastOrder
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Model\Product $productCollection,
        \Magento\Sales\Model\Order $lastOrder,
        array $data = []
    ) {
        $this->orderRepository = $orderRepository;
        $this->checkoutSession = $checkoutSession;
        $this->productCollection = $productCollection;
        $this->lastOrder = $lastOrder;
        parent::__construct($context, $data);
    }

    /**
     * @return OrderInfo
     */
    public function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('Order Information'));
        return parent::_prepareLayout();
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
        $id = $this->getlastOrder()->getEntityId();
        if ($id) {
            return $id;
        }
    }

    /**
     * @return string|null
     */
    public function getOrderStatus()
    {
        $order = $this->orderRepository->get($this->getOrderId());

        return $order->getStatus();

    }

    /**
     * @return int|null
     */
    public function getOrderProductId()
    {
        foreach ($this->prepareProductInfo() as $item) {
            $productId = $item->getProductId();
        }

        return $productId;
    }

    public function getProductSku()
    {
        foreach ($this->prepareProductInfo() as $item) {
            $productSku = $item->getSku();
        }

        return $productSku;
    }

    public function getProductQty()
    {
        foreach ($this->prepareProductInfo() as $item) {
            $productQty = $item->getQtyOrdered();
        }

        return $productQty;
    }

    /**
     * @return \Magento\Sales\Model\Order\Item[]
     */
    public function prepareProductInfo()
    {
        $order = $this->orderRepository->get($this->getOrderId());

        return $order->getAllItems();
    }
}
