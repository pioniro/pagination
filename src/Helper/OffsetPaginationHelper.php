<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Helper;

use Pioniro\Pagination\Pager\OffsetPager;
use Pioniro\Pagination\Paginator\Paginator;
use Pioniro\Pagination\PaginatorInterface;

class OffsetPaginationHelper
{
    public static function create($items, OffsetPager $pager, $totalRows): PaginatorInterface
    {
        $offset = $pager->getOffset() + count($items);
        return new Paginator(
            $items,
            new OffsetPager($pager->getLimit(), $offset, $totalRows)
        );
    }
}