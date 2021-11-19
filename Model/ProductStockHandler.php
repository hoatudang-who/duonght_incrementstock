<?php

namespace Duonght\IncrementStock\Model;

use Duonght\IncrementStock\Block\OrderInfo;
use Magento\Quote\Model\Quote\Item;

class ProductStockHandler
{
    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    private $stockRegistry;

    /**
     * @var OrderInfo
     */
    protected $orderInfo;

    /**
     * @param OrderInfo $orderInfo
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     */
    public function __construct(
        \Duonght\IncrementStock\Block\OrderInfo $orderInfo,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
    ) {
        $this->orderInfo = $orderInfo;
        $this->stockRegistry = $stockRegistry;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Item $item
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function incrementStock(Item $item)
    {
        $stockItem = $this->stockRegistry->getStockItemBySku($item->getSku());
        $stockItem->setQty($stockItem->getQty() + $item->getQty());
        if ($this->orderInfo->getOrderPayment() != 'Check / Money order') {
            $this->stockRegistry->updateStockItemBySku($item->getSku(), $stockItem);
        }
    }
}
