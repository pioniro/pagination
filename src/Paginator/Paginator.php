<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Paginator;

use Pioniro\Pagination\PagerInterface;
use Pioniro\Pagination\PaginatorInterface;

class Paginator implements PaginatorInterface
{
    /**
     * @var iterable|array
     */
    protected $items;

    /**
     * @var PagerInterface
     */
    protected $pagination;

    /**
     * Paginator constructor.
     * @param array|iterable $items
     * @param PagerInterface $pagination
     */
    public function __construct(iterable $items, PagerInterface $pagination)
    {
        $this->items = $items;
        $this->pagination = $pagination;
    }

    /**
     * @return array|iterable
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return PagerInterface
     */
    public function getPager(): PagerInterface
    {
        return $this->pagination;
    }
}