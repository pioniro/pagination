<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Pagination;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Pioniro\Pagination\Exception\PaginatorException;
use Pioniro\Pagination\Helper\CursorPaginationHelper;
use Pioniro\Pagination\Helper\OffsetPaginationHelper;
use Pioniro\Pagination\Helper\QueryBuilderHandlerHelper;
use Pioniro\Pagination\Pager\CursorPager;
use Pioniro\Pagination\Pager\OffsetPager;
use Pioniro\Pagination\PagerInterface;
use Pioniro\Pagination\PaginatorInterface;

class QueryBuilderPagination extends AbstractPagination
{
    /**
     * @var QueryBuilder
     */
    protected $qb;

    /**
     * @var PagerInterface
     */
    protected $pager;

    /**
     * @var string
     */
    protected $idField;

    /**
     * @var array
     */
    protected $items;

    /**
     * @var callable
     */
    protected $id;

    /**
     * @var int
     */
    protected $totalRows;

    /**
     * Pagination constructor.
     * @param QueryBuilder $qb
     * @param PagerInterface $pagination
     * @param string $idField
     * @param callable|null $id
     */
    public function __construct(QueryBuilder $qb, PagerInterface $pagination, ?string $idField = null, callable $id = null)
    {
        $this->qb = clone $qb;
        $this->pager = $pagination;
        $this->idField = $idField;
        $this->items = null;
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

    protected function handleQueryPagination()
    {
        if ($this->pager instanceof CursorPager) {
            $this->handleCursorPagination();
        } elseif ($this->pager instanceof OffsetPager) {
            $this->handleOffsetPagination();
        }
    }

    protected function handleCursorPagination()
    {
        QueryBuilderHandlerHelper::handle($this->qb, $this->pager, $this->idField);
    }

    protected function handleOffsetPagination()
    {
        $paginator = new Paginator($this->qb);
        $this->totalRows = $paginator->count();
        $this->items = iterator_to_array($paginator->getIterator());
    }

    protected function createPaginator(): PaginatorInterface
    {
        if ($this->pager instanceof CursorPager) {
            $this->handleQueryPagination();
            $this->items = $this->qb->getQuery()->execute();
            return $this->createCursorPaginator();
        } elseif ($this->pager instanceof OffsetPager) {
            $this->handleQueryPagination();
            return $this->createOffsetPaginator();
        }
        throw PaginatorException::pagerNotSupported($this->pager);
    }


    protected function createCursorPaginator(): PaginatorInterface
    {
        return CursorPaginationHelper::create($this->items, $this->pager, $this->id);
    }

    protected function createOffsetPaginator(): PaginatorInterface
    {
        return OffsetPaginationHelper::create($this->items, $this->pager, $this->totalRows);
    }
}