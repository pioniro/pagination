<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Test\Pagination;

use Pioniro\Pagination\Pager\OffsetPager;
use Pioniro\Pagination\Pagination\ArrayPagination;
use PHPUnit\Framework\TestCase;

class ArrayPaginationTest extends TestCase
{

    public function testGetIterator()
    {
        $pagination = new ArrayPagination([1,2,3], new OffsetPager(3), null, 10);
        $i = 0;
        foreach ($pagination as $item) {
            $i ++;
        }
        $this->assertEquals(3, $i);
    }
}
