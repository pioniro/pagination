<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Pagination;

use Pioniro\Pagination\Exception\PaginatorException;
use Pioniro\Pagination\Helper\CursorPaginationHelper;
use Pioniro\Pagination\Helper\OffsetPaginationHelper;
use Pioniro\Pagination\Pager\CursorPager;
use Pioniro\Pagination\Pager\OffsetPager;
use Pioniro\Pagination\PagerInterface;
use Pioniro\Pagination\PaginatorInterface;

class ArrayPagination extends AbstractPagination
{
    protected $items;

    /**
     * @var PagerInterface
     */
    protected $pager;

    /**
     * @var callable|\Closure
     */
    protected $id;

    /**
     * @var int|null
     */
    protected $totalRows;

    /**
     * ArrayPagination constructor.
     * @param $items
     * @param PagerInterface $pagination
     * @param bool $reversed
     * @param callable $id
     * @param null $totalRows
     */
    public function __construct($items, PagerInterface $pagination, callable $id = null, ?int $totalRows = null)
    {
        $this->items = $items;
        $this->pager = $pagination;
        $this->id = $id ?? function ($item) {
                return $item->getId();
            };
        $this->totalRows = $totalRows;
    }

    protected function execute()
    {
        if (is_null($this->paginator)) {
            $this->paginator = $this->createPaginator();
        }
    }

    protected function createPaginator(): PaginatorInterface
    {
        if ($this->pager instanceof CursorPager) {
            return $this->createCursorPaginator();
        } elseif($this->pager instanceof OffsetPager) {
            return $this->createOffsetPaginator();
        }
        throw PaginatorException::pagerNotSupported($this->pager);
    }

    protected function createCursorPaginator(): PaginatorInterface
    {
        return CursorPaginationHelper::create(
            $this->items,
            $this->pager,
            $this->id
        );
    }

    protected function createOffsetPaginator(): PaginatorInterface
    {
        return OffsetPaginationHelper::create($this->items, $this->pager, $this->totalRows);
    }
}