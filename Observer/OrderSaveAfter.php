<?php

namespace Duonght\IncrementStock\Observer;

use Duonght\IncrementStock\Block\OrderInfo;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Api\Data\CartInterface;
use Duonght\IncrementStock\Model\QuoteReader;
use Duonght\IncrementStock\Block\NoValidQuote;
use Duonght\IncrementStock\Block\InvalidQuoteItem;

class OrderSaveAfter implements ObserverInterface
{
    /**
     * @var QuoteReader
     */
    private $quoteReader;

    /**
     * @var \Duonght\IncrementStock\Model\ProductStockHandler
     */
    private $productStockHandler;

    /**
     * @param QuoteReader $quoteReaderProxy
     * @param \Duonght\IncrementStock\Model\ProductStockHandler $productStockHandler
     */
    public function __construct(
        QuoteReader $quoteReaderProxy,
        \Duonght\IncrementStock\Block\OrderInfo $orderInfo,
        \Duonght\IncrementStock\Model\ProductStockHandler $productStockHandler
    ) {
        $this->quoteReader = $quoteReaderProxy;
        $this->orderInfo = $orderInfo;
        $this->productStockHandler = $productStockHandler;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        if ($this->quoteHasItems($observer)) {
            foreach ($this->getSimpleProductQuoteItems() as $item) {
                    try {
                        $this->productStockHandler->incrementStock($item);
                    } catch
                    (InvalidQuoteItem $e) {
                        // possible log
                    }
            }
        }
    }

    /**
     * @return \Magento\Quote\Model\Quote\Item[]
     */
    private function getSimpleProductQuoteItems()
    {
        return $this->quoteReader->getSimpleProductQuoteItems();
    }

    /**
     * @param Observer $observer
     * @return bool
     */
    private function quoteHasItems(Observer $observer)
    {
        try {
            $quote = $this->validateQuote($observer);
            $this->quoteReader->initializeQuote($quote);
            $result = $this->quoteReader->hasQuoteItems($quote);
        } catch (NoValidQuote $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * @param Observer $observer
     * @return mixed
     * @throws NoValidQuote
     */
    private function validateQuote(Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();

        if (!$quote instanceof CartInterface) {
            throw new NoValidQuote();
        }

        return $quote;
    }
}
