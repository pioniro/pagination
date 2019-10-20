<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Test;

use PHPUnit\Framework\TestCase;
use Pioniro\Pagination\Helper\CursorPaginationHelper;
use Pioniro\Pagination\Pager\CursorPager;

class CursorPaginationHelperTest extends TestCase
{

    /**
     * @dataProvider getPaginationDataProvider
     */
    public function testCreate(
        $pagination,
        $firstId,
        $entitiesCount,
        $isReversed,
        $outPagination
    ) {
        $data = [];
        if ($entitiesCount > 0) {
            foreach (range($firstId, $firstId + $entitiesCount - 1) as $item) {
                $data[] = ['id' => $item];
            }
        }
        $data = $isReversed ? array_reverse($data) : $data;

        $paginator = CursorPaginationHelper::create($data, $pagination, function ($item) {
            return $item['id'];
        });
        $this->assertEquals($outPagination, $paginator->getPager());
    }

    /**
     *
     */
    public function getPaginationDataProvider()
    {
        return [
            /**
             * cursor pagination provider
             * есть пять переменных:
             * 1. начало списка или нет
             * 2. конец списка или нет
             * 3. двигаемся вправо или влево
             * 4. стал конец списка или нет
             * 5. стало начало списка или нет
             *
             * варанты:
             * 1. начало, не конец, влево, стало началом
             * 2. начало, не конец, вправо, стало концом
             * 3. начало, не конец, вправо, не стало концом
             * 4. начало, конец, влево, стало началом
             * 5. начало, конец, вправо, стало концом
             * 6. не начало, не конец, влево, стало началом
             * 7. не начало, не конец, влево, не стало началом
             * 8. не начало, не конец, вправо, стало концом
             * 9. не начало, не конец, вправо, не стало концом
             * 10. не начало, конец, влево, стало началом
             * 11. не начало, конец, влево, не стало началом
             * 12. не начало, конец, вправо, стало концом
             *
             * ------------------
             * 12 вариантов
             */
            '1. начало, не конец, влево, стало началом' => [
                'pagination' => new CursorPager(2, 1, 1),
                'firstId' => 1,
                'entitiesCount' => 0,
                'isReversed' => true,
                'outPagination' => new CursorPager(2, null, null)
            ],
            '2. начало, не конец, вправо, стало концом' => [
                'pagination' => new CursorPager(2, null, null),
                'firstId' => 1,
                'entitiesCount' => 2,
                'isReversed' => false,
                'outPagination' => new CursorPager(2, null, null)
            ],
            '3. начало, не конец, вправо, не стало концом' => [
                'pagination' => new CursorPager(2, null, null),
                'firstId' => 1,
                'entitiesCount' => 3,
                'isReversed' => false,
                'outPagination' => new CursorPager(2, null, 2)
            ],
            // пока повторяет 1 вариант, но это только из-за мока QueryBuilder
            '4. начало, конец, влево, стало началом' => [
                'pagination' => new CursorPager(2, 1, null),
                'firstId' => 1,
                'entitiesCount' => 0,
                'isReversed' => true,
                'outPagination' => new CursorPager(2, null, null)
            ],
            '5. начало, конец, вправо, стало концом' => [
                'pagination' => new CursorPager(2, null, 2),
                'firstId' => 1,
                'entitiesCount' => 0,
                'isReversed' => false,
                'outPagination' => new CursorPager(2, null, null)
            ],
            '6. не начало, не конец, влево, стало началом' => [
                'pagination' => new CursorPager(2, 3, null),
                'firstId' => 1,
                'entitiesCount' => 2,
                'isReversed' => true,
                'outPagination' => new CursorPager(2, null, 2)
            ],
            '7. не начало, не конец, влево, не стало началом' => [
                'pagination' => new CursorPager(2, 7, null),
                'firstId' => 4,
                'entitiesCount' => 3,
                'isReversed' => true,
                'outPagination' => new CursorPager(2, 5, 6)
            ],
            '8. не начало, не конец, вправо, стало концом' => [
                'pagination' => new CursorPager(2, null, 3),
                'firstId' => 4,
                'entitiesCount' => 2,
                'isReversed' => false,
                'outPagination' => new CursorPager(2, 4, null)
            ],
            '9. не начало, не конец, вправо, не стало концом' => [
                'pagination' => new CursorPager(2, null, 3),
                'firstId' => 4,
                'entitiesCount' => 3,
                'isReversed' => false,
                'outPagination' => new CursorPager(2, 4, 5)
            ],
            '10. не начало, конец, влево, стало началом' => [
                'pagination' => new CursorPager(2, 3, null),
                'firstId' => 1,
                'entitiesCount' => 2,
                'isReversed' => true,
                'outPagination' => new CursorPager(2, null, 2)
            ],
            '11. не начало, конец, влево, не стало началом' => [
                'pagination' => new CursorPager(2, 4, null),
                'firstId' => 1,
                'entitiesCount' => 3,
                'isReversed' => true,
                'outPagination' => new CursorPager(2, 2, 3)
            ],
            '12. не начало, конец, вправо, стало концом' => [
                'pagination' => new CursorPager(2, null, 3),
                'firstId' => 1,
                'entitiesCount' => 0,
                'isReversed' => true,
                'outPagination' => new CursorPager(2, null, null)
            ],
        ];
    }
}