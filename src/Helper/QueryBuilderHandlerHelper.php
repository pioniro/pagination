<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Helper;

use Doctrine\ORM\Query\Expr\From;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\QueryBuilder;
use Pioniro\Pagination\Exception\PaginatorException;
use Pioniro\Pagination\Pager\CursorPager;

class QueryBuilderHandlerHelper
{
    public static function handle(QueryBuilder $qb, CursorPager $pager, $idField)
    {
        self::assertRootEntity($qb);
        self::assertOrder($qb);

        $id = self::getIdField($idField, $qb);
        if ($pager->getLast()) {
            $qb->andWhere(
                $qb->expr()->gt($id, $pager->getLast())
            );
        } elseif ($pager->getFirst()) {
            $qb->andWhere($qb->expr()->lt($id, $pager->getFirst()));
            $qb->add('orderBy', self::reversOrders($qb));
        }
        $qb->setMaxResults($pager->getLimit() + 1);
    }

    protected static function assertRootEntity(QueryBuilder $qb)
    {
        /** @var From[] $froms */
        $froms = $qb->getDQLPart('from');
        if (count($froms) === 0)
            throw PaginatorException::noRootEntity();
    }

    protected static function getIdField($id, QueryBuilder $qb)
    {
        if($id) {
            return $id;
        }
        /** @var From[] $froms */
        $froms = $qb->getDQLPart('from');
        $from = $froms[0];
        return $from->getAlias() . '.' . 'id';
    }

    protected static function assertOrder(QueryBuilder $qb)
    {
        /** @var OrderBy[] $orders */
        $orders = $qb->getDQLPart('orderBy');
        if(count($orders) === 0)
            throw PaginatorException::noOrders();
    }

    /**
     * @param QueryBuilder $qb
     * @return string[]
     */
    protected static function reversOrders(QueryBuilder $qb): array
    {
        self::assertOrder($qb);
        /** @var OrderBy[] $orders */
        $orders = $qb->getDQLPart('orderBy');
        $newOrders = [];
        foreach ($orders as $order) {
            $reversOrder = new OrderBy();
            foreach ($order->getParts() as $part) {
                $reversOrder->add(...self::reverseOrderPart($part));
            }
            $newOrders[] = $reversOrder;
        }
        return $newOrders;
    }

    protected static function reverseOrderPart(string $part): array
    {
        preg_match('/^(?\'sort\'[\s\S]+) (?\'order\'desc|asc)$/i', $part, $matches);
        if (!isset($matches['sort'], $matches['order'])) {
            throw PaginatorException::badOrderPart($part);
        }
        return [$matches['sort'], mb_strlen($matches['order']) === 3 ? 'DESC' : 'ASC'];
    }
}