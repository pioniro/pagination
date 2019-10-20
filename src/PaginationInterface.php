<?php
declare(strict_types=1);

namespace Pioniro\Pagination;

interface PaginationInterface extends PaginatorInterface
{
    public function getPaginator(): PaginatorInterface;
}