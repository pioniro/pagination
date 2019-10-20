<?php
declare(strict_types=1);

namespace Pioniro\Pagination;

interface PaginatorInterface
{
    /**
     * @return array|iterable
     */
    public function getItems();

    /**
     * @return PagerInterface
     */
    public function getPager(): PagerInterface;
}