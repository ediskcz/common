<?php

namespace Edisk\Common\Search;

use InvalidArgumentException;

abstract class AbstractSearchRequest
{
    private const HARD_LIMIT = 100;
    private const RESULT_WINDOW = 10000;

    protected array $options = [
        'collectionId' => null,
        'limit' => 50,
        'offset' => 0,
        'query' => null,
        'sort' => 'relevance',
    ];

    private mixed $user = null;

    abstract public function getCollectionIds(): array;

    public function setCollectionId(?string $collectionId): void
    {
        if ($collectionId && !in_array($collectionId, $this->getCollectionIds(), true)) {
            throw new InvalidArgumentException('Invalid collection');
        }

        $this->options['collectionId'] = $collectionId;
    }

    public function getCollectionId(): ?string
    {
        return $this->options['collectionId'];
    }

    public function setLimit(int $limit): void
    {
        if ($limit < 1) {
            $limit = 1;
        }
        if ($limit > self::HARD_LIMIT) {
            $limit = self::HARD_LIMIT;
        }
        $this->options['limit'] = $limit;
    }

    public function getLimit(): int
    {
        return $this->options['limit'];
    }

    public function setOffset(int $offset): void
    {
        if ($offset < 0) {
            $offset = 0;
        }
        if ($offset >= self::RESULT_WINDOW) {
            $offset = 0;
        }
        $this->options['offset'] = $offset;
    }

    public function getOffset(): int
    {
        return $this->options['offset'];
    }

    public function setQuery(?string $query): void
    {
        $this->options['query'] = $query;
    }

    public function getQuery(): ?string
    {
        return $this->options['query'];
    }

    abstract public function getModel(): string;

    abstract public function getSortingTypes(): array;

    public function setSort(string $sort): void
    {
        if (!in_array($sort, $this->getSortingTypes(), true)) {
            throw new InvalidArgumentException('Invalid sort');
        }

        $this->options['sort'] = $sort;
    }

    public function getSort(): string
    {
        return $this->options['sort'];
    }

    public function toArray(): array
    {
        $options = $this->options;

        // temp, backward compatibility
        $options['per_page'] = $options['limit'];
        // temp, backward compatibility
        $options['search'] = $options['query'];

        // p = page, for route pagination links, 0-based to 1-based
        $options['p'] = ((int) floor($options['offset'] / $options['limit'])) + 1;

        return $options;
    }

    public function setUser(mixed $user): void
    {
        $this->user = $user;
    }

    public function getUser(): mixed
    {
        return $this->user;
    }

    public function getLayouts(): array
    {
        return ['grid', 'list'];
    }

    public function setLayout(string $layout): void
    {
        if (!in_array($layout, $this->getLayouts(), true)) {
            throw new InvalidArgumentException('Invalid layout');
        }

        $this->options['layout'] = $layout;
    }

    public function getLayout(): string
    {
        return $this->options['layout'];
    }
}
