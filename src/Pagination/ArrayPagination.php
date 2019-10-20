<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Pagination;

use Pioniro\Pagination\Exception\PaginatorException;
use Pioniro\Pagination\Helper\CursorPaginationHelper;
use Pioniro\Pagination\Pager\CursorPager;
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
     * @var bool
     */
    protected $reversed;

    /**
     * @var callable|\Closure
     */
    protected $id;

    /**
     * ArrayPagination constructor.
     * @param $items
     * @param $pagination
     * @param $reversed
     * @param $id
     */
    public function __construct($items, PagerInterface $pagination, bool $reversed = false, callable $id = null)
    {
        $this->items = $items;
        $this->pager = $pagination;
        $this->reversed = $reversed;
        $this->id = $id ?? function ($item) {
                return $item->getId();
            };
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
        }
        throw new PaginatorException(sprintf('%s pagination not supported', get_class($this->pager)));
    }

    protected function createCursorPaginator(): PaginatorInterface
    {
        return CursorPaginationHelper::create(
            $this->items,
            $this->pager,
            $this->id
        );
    }
}