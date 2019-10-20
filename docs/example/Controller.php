<?php
declare(strict_types=1);

use Pioniro\Pagination\Pager\CursorPager;
use Pioniro\Pagination\Pagination\QueryBuilderPagination;

class Controller
{
    public function index(ItemRepository $repository, $first = null, $last = null, $limit = 20)
    {
        $qb = $repository->getQBOnlyAwesome();
        $pagination = new QueryBuilderPagination($qb, new CursorPager($limit, $first, $last));
        return $this->json($pagination->getPaginator());
    }

    public function alter(ItemRepository $repository, $first = null, $last = null, $limit = 20)
    {
        return $this->json($repository->getPaginatorOnlyAwesome(new CursorPager($limit, $first, $last)));
    }
}