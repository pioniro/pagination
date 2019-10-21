<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Pager;
use Pioniro\Pagination\PagerInterface;

/**
 * @codeCoverageIgnore
 */
class OffsetPager implements PagerInterface
{
    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int|null
     */
    protected $offset;

    /**
     * @var int|null
     */
    protected $total;

    /**
     * OffsetPagination constructor.
     * @param int $limit
     * @param int|null $offset
     * @param int|null $total
     */
    public function __construct(int $limit, ?int $offset = null, ?int $total = null)
    {
        $this->limit = $limit;
        $this->offset = $offset;
        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @return int|null
     */
    public function getTotal(): ?int
    {
        return $this->total;
    }
}