<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Pagination;

use Doctrine\ORM\QueryBuilder;
use Pioniro\Pagination\Helper\CursorPaginationHelper;
use Pioniro\Pagination\Exception\PaginatorException;
use Pioniro\Pagination\Helper\QueryBuilderHandlerHelper;
use Pioniro\Pagination\Pager\CursorPager;
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
        $this->id = $id ?? function($item) {return $item->getId();};
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
        }
    }

    protected function handleCursorPagination()
    {
        QueryBuilderHandlerHelper::handle($this->qb, $this->pager, $this->idField);
    }

    protected function createPaginator(): PaginatorInterface
    {
        if ($this->pager instanceof CursorPager) {
            $this->handleQueryPagination();
            $this->items = $this->qb->getQuery()->execute();
            return $this->createCursorPager();
        }
        throw PaginatorException::pagerNotSupported($this->pager);
    }


    protected function createCursorPager(): PaginatorInterface
    {
        return CursorPaginationHelper::create($this->items, $this->pager, $this->id);
    }
}