<?php

namespace Edisk\Common\Pagination;

use InvalidArgumentException;

class Pagination
{
    private string $basePath = '';

    public function __construct(
        private int $total,
        private int $limitPerPage,
        private int $currentPage = 1,
    ) {
    }

    public function getPageCount(): int
    {
        $pagesCount = floor($this->getTotal() / $this->getLimitPerPage());
        if ($pagesCount * $this->getLimitPerPage() < $this->getTotal()) {
            $pagesCount++;
        }

        return $pagesCount;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    public function getLimitPerPage(): int
    {
        return $this->limitPerPage;
    }

    public function setLimitPerPage(int $limitPerPage): void
    {
        $this->limitPerPage = $limitPerPage;
    }


    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $currentPage): void
    {
        if ($currentPage < 1) {
            throw new InvalidArgumentException('invalid page');
        }
        // check validity as 0 based
        if (($currentPage - 1) * $this->getLimitPerPage() > $this->getTotal()) {
            throw new InvalidArgumentException('invalid page');
        }
        $this->currentPage = $currentPage;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function setBasePath(string $basePath): void
    {
        $this->basePath = $basePath;
    }
}
