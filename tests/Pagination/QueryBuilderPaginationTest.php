<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Test\Pagination;

use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Pioniro\Pagination\Exception\PaginatorException;
use Pioniro\Pagination\Pager\CursorPager;
use Pioniro\Pagination\PagerInterface;
use Pioniro\Pagination\Pagination\QueryBuilderPagination;
use Pioniro\Test\DummyPager;
use Pioniro\Test\Test;
use Pioniro\Test\Test0;
use Pioniro\Test\Test2;
use function Pioniro\Pagination\Test\initDoctrine;

class QueryBuilderPaginationTest extends TestCase
{

    /**
     * @param PagerInterface $pagination
     * @param string $entity
     * @param $ids
     * @param PagerInterface $outPagination
     * @dataProvider getPaginationDataProvider
     */
    public function testGetItems(PagerInterface $pagination, string $entity, $ids, PagerInterface $outPagination)
    {
        $q = $this->getQueryBuilder($entity);

        $pagination = new QueryBuilderPagination($q, $pagination);
        $this->assertEquals($ids, array_map(function ($t) {
            return $t->getId();
        }, $pagination->getItems()));
    }

    /**
     * @param PagerInterface $pagination
     * @param string $entity
     * @param $ids
     * @param PagerInterface $outPagination
     * @dataProvider getPaginationDataProvider
     */
    public function testGetPagination(PagerInterface $pagination, string $entity, $ids, PagerInterface $outPagination)
    {
        $q = $this->getQueryBuilder($entity);
        $pagination = new QueryBuilderPagination($q, $pagination);
        $this->assertEquals($outPagination, $pagination->getPager());
    }

    public function testNoOrder()
    {
        $q = $this->getQueryBuilder(Test::class);
        $q->resetDQLPart('orderBy');
        $q = new QueryBuilderPagination($q, new CursorPager(2, null, null));

        $this->expectExceptionObject(PaginatorException::noOrders());
        $q->getItems();
    }

    public function testBadOrder()
    {
        $q = $this->getQueryBuilder(Test::class);
        $q->resetDQLPart('orderBy');
        $q->add('orderBy', new OrderBy('t.id', 'bad_order'));
        $q = new QueryBuilderPagination($q, new CursorPager(2, 1, null));

        $this->expectException(PaginatorException::class);
        $q->getItems();
    }

    public function testNoRootEntity()
    {
        $q = $this->getQueryBuilder(Test::class);
        $q->resetDQLPart('from');
        $q = new QueryBuilderPagination($q, new CursorPager(2, null, null));

        $this->expectExceptionObject(PaginatorException::noRootEntity());
        $q->getItems();
    }

    public function testPaginationNotImplemented()
    {
        $q = $this->getQueryBuilder(Test::class);
        $q = new QueryBuilderPagination($q, new DummyPager());

        $this->expectException(PaginatorException::class);
        $q->getItems();
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
                'pagination' => new CursorPager(2, 1, null),
                'entity' => Test::class,
                'ids' => [],
                'outPagination' => new CursorPager(2, null, null)
            ],
            '2. начало, не конец, вправо, стало концом' => [
                'pagination' => new CursorPager(2, null, null),
                'entity' => Test2::class,
                'ids' => [1, 2],
                'outPagination' => new CursorPager(2, null, null)
            ],
            '3. начало, не конец, вправо, не стало концом' => [
                'pagination' => new CursorPager(2, null, null),
                'entity' => Test::class,
                'ids' => [1, 2],
                'outPagination' => new CursorPager(2, null, 2)
            ],
            '4. начало, конец, влево, стало началом' => [
                'pagination' => new CursorPager(2, 1, null),
                'entity' => Test0::class,
                'ids' => [],
                'outPagination' => new CursorPager(2, null, null)
            ],
            '5. начало, конец, вправо, стало концом' => [
                'pagination' => new CursorPager(2, null, 2),
                'entity' => Test0::class,
                'ids' => [],
                'outPagination' => new CursorPager(2, null, null)
            ],
            '6. не начало, не конец, влево, стало началом' => [
                'pagination' => new CursorPager(2, 3, null),
                'entity' => Test::class,
                'ids' => [1, 2],
                'outPagination' => new CursorPager(2, null, 2)
            ],
            '7. не начало, не конец, влево, не стало началом' => [
                'pagination' => new CursorPager(2, 7, null),
                'entity' => Test::class,
                'ids' => [5, 6],
                'outPagination' => new CursorPager(2, 5, 6)
            ],
            '8. не начало, не конец, вправо, стало концом' => [
                'pagination' => new CursorPager(2, null, 98),
                'entity' => Test::class,
                'ids' => [99, 100],
                'outPagination' => new CursorPager(2, 99, null)
            ],
            '9. не начало, не конец, вправо, не стало концом' => [
                'pagination' => new CursorPager(2, null, 3),
                'entity' => Test::class,
                'ids' => [4, 5],
                'outPagination' => new CursorPager(2, 4, 5)
            ],
            '10. не начало, конец, влево, стало началом' => [
                'pagination' => new CursorPager(2, 3, null),
                'entity' => Test2::class,
                'ids' => [1, 2],
                'outPagination' => new CursorPager(2, null, 2)
            ],
            '11. не начало, конец, влево, не стало началом' => [
                'pagination' => new CursorPager(2, 101, null),
                'entity' => Test::class,
                'ids' => [99, 100],
                'outPagination' => new CursorPager(2, 99, 100)
            ],
            '12. не начало, конец, вправо, стало концом' => [
                'pagination' => new CursorPager(2, null, 100),
                'entity' => Test::class,
                'ids' => [],
                'outPagination' => new CursorPager(2, null, null)
            ],
        ];
    }

    protected function getQueryBuilder(string $entity): QueryBuilder
    {
        $em = initDoctrine();
        return $em->createQueryBuilder()
            ->select('t')
            ->from($entity, 't')
            ->orderBy('t.id', 'ASC');
    }
}
