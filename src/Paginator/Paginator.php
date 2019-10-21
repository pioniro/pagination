<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Paginator;

use ArrayIterator;
use Pioniro\Pagination\PagerInterface;
use Pioniro\Pagination\PaginatorInterface;
use Pioniro\Pagination\Traits\Arrayable;

class Paginator implements PaginatorInterface
{
    use Arrayable;

    /**
     * @var iterable|array
     */
    protected $items;

    /**
     * @var PagerInterface
     */
    protected $pagination;

    /**
     * @var ArrayIterator
     */
    protected $iterator;

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

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        if(!$this->iterator)
            $this->iterator = new ArrayIterator($this->getItems());
        return $this->iterator;
    }
}