<?php

namespace Duonght\IncrementStock\Model;

use Magento\InventoryApi\Api\Data\StockInterface;

class ProductStockHandler
{
    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    private $stockRegistry;

    public function __construct(
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
    ) {
        $this->stockRegistry = $stockRegistry;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Item $item
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function incrementStock(\Magento\Quote\Model\Quote\Item $item)
    {
        $stockItem = $this->stockRegistry->getStockItemBySku($item->getSku());
        $stockItem->setQty($stockItem->getQty() + $item->getQty());
        $this->stockRegistry->updateStockItemBySku($item->getSku(), $stockItem);
    }
}
