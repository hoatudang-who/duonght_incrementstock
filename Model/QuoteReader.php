<?php

namespace Duonght\IncrementStock\Model;

use Magento\Quote\Api\Data\CartInterface;

class QuoteReader
{
    /**
     * @var \Magento\Quote\Model\Quote\Item[]
     */
    private $allSimpleItems;

    /**
     * @return \Magento\Quote\Model\Quote\Item[]
     */
    public function getSimpleProductQuoteItems()
    {
        return $this->allSimpleItems;
    }

    /**
     * @param CartInterface $quote
     * @return bool
     */
    public function hasQuoteItems(CartInterface $quote)
    {
        return count($this->allSimpleItems) > 0;
    }

    /**
     * @param CartInterface $quote
     * @return array|\Magento\Quote\Model\Quote\Item[]
     */
    public function initializeQuote(CartInterface $quote)
    {
        $this->allSimpleItems = [];

        $allItems = $quote->getAllItems();
        foreach ($allItems as $item) {
            if (is_null($item->getParentItemId())) {
                $this->allSimpleItems[] = $item;
            }
        }

        return $this->allSimpleItems;
    }
}
