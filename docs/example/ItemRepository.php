<?php
declare(strict_types=1);

use Doctrine\ORM\QueryBuilder;
use Pioniro\Pagination\Pager\CursorPager;
use Pioniro\Pagination\Pagination\QueryBuilderPagination;
use Pioniro\Pagination\PaginatorInterface;

class ItemRepository
{
    public function getQBOnlyAwesome(): QueryBuilder
    {
        return $this->createQueryBuilder()
            ->orderBy('t.id', 'DESC');
    }

    public function getPaginatorOnlyAwesome(CursorPager $pager): PaginatorInterface
    {
        return (new QueryBuilderPagination($this->getQBOnlyAwesome(), $pager))->getPaginator();
    }
}