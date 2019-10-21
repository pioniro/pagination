<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Pager;
use Pioniro\Pagination\PagerInterface;

/**
 * @codeCoverageIgnore
 */
class CursorPager implements PagerInterface
{
    /**
     * @var null|int|string
     */
    protected $last;

    /**
     * @var null|int|string
     */
    protected $first;

    /**
     * @var int
     */
    protected $limit;

    /**
     * CursorPagination constructor.
     * @param int $limit
     * @param $last
     * @param $first
     */
    public function __construct(int $limit, $first = null, $last = null)
    {
        $this->last = $last;
        $this->first = $first;
        $this->limit = $limit;
    }

    /**
     * @return null
     */
    public function getLast()
    {
        return $this->last;
    }

    /**
     * @return null
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
}