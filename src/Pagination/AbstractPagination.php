<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Pagination;
use Pioniro\Pagination\PaginationInterface;
use Pioniro\Pagination\PaginatorInterface;
use Pioniro\Pagination\PagerInterface;

abstract class AbstractPagination implements PaginationInterface
{
    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    public function getItems()
    {
        $this->execute();
        return $this->paginator->getItems();
    }

    public function getPaginator(): PaginatorInterface
    {
        $this->execute();
        return $this->paginator;
    }

    public function getPager(): PagerInterface
    {
        $this->execute();
        return $this->paginator->getPager();
    }

//    abstract public function execute();
}