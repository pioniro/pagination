<?php
declare(strict_types=1);

namespace Pioniro\Pagination;

use Countable;
use Serializable;
use ArrayAccess;
use SeekableIterator;

interface PaginatorInterface extends SeekableIterator, ArrayAccess, Serializable, Countable
{
    /**
     * @return array|iterable
     */
    public function getItems();

    /**
     * @return PagerInterface
     */
    public function getPager(): PagerInterface;
}