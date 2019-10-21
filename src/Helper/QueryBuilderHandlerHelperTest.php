<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Helper;

use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Pioniro\Pagination\Pager\CursorPager;
use Pioniro\Test\Test;
use function Pioniro\Pagination\Test\initDoctrine;

class QueryBuilderHandlerHelperTest extends TestCase
{

    public function testIdField()
    {
        $qb = $this->getQueryBuilder(Test::class);
        QueryBuilderHandlerHelper::handle($qb, new CursorPager(2, 4, null), 't.alt');

        $this->assertEquals('SELECT t FROM Pioniro\Test\Test t WHERE t.alt < 4 ORDER BY t.id DESC', $qb->getDQL());
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
