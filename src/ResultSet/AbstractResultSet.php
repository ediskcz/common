<?php

namespace Edisk\Common\ResultSet;

abstract class AbstractResultSet
{
    /** @var object[] */
    private array $items = [];

    /** @var object[] */
    private array $indexedItems;
    private int $total;

    public function __construct(array $items)
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }

        $indexedItems = [];
        foreach ($this->getItems() as $item) {
            $indexedItems[$item->getId()] = $item;
        }
        $this->indexedItems = $indexedItems;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array|object[] $items
     */
    public function add(array $items): void
    {
        foreach ($items as $item) {
            $this->items[] = $item;
            $this->indexedItems[$item->getId()] = $item;
        }
    }

    public function addItem(object $item): void
    {
        $this->items[] = $item;
        $this->indexedItems[$item->getId()] = $item;
    }

    public function getTotalCount(): int
    {
        if (!isset($this->total)) {
            $this->total = count($this->items);
        }

        return $this->total;
    }

    public function setTotalCount(int $total): void
    {
        $this->total = $total;
    }

    public function getIndexedItems(): array
    {
        return $this->indexedItems;
    }

    public function getIndices(): array
    {
        return array_keys($this->indexedItems);
    }

    abstract public function toArray(): array;
}
