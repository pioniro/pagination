<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Helper;

use Pioniro\Pagination\Pager\CursorPager;
use Pioniro\Pagination\Paginator\Paginator;
use Pioniro\Pagination\PaginatorInterface;

class CursorPaginationHelper
{
    public static function create($items, CursorPager $pager, callable $id): PaginatorInterface
    {
        // если указан last, то это точно не начало списка
        $isStart = !$pager->getLast();
        // если указан first, то это точто не конец списка
        $isEnd = !$pager->getFirst();
        // если есть элемент по направлению выборки, то
        if (count($items) > $pager->getLimit()) {
            // если выборка была в сторону начала списка, то значит мы ещё не достигли начала списка
            if ($pager->getFirst()) {
                $isStart = false;
                // по умолчанию мы выбираем в сторону конца списка, так что лишний элемент означает, что конец ещё не достигнут
            } else {
                $isEnd = false;
            }
            // удаляем проверочный элемент
            array_splice($items, -1);
        }
        // если выборка была в сторону начала списка, то список сейчас в обратном порядке, исправляем это.
        if (!is_null($pager->getFirst())) {
            $items = array_reverse($items);
        }
        $first = null;
        $last = null;
        if (count($items) > 0) {
            if (!$isStart) {
                $first = $id($items[0]);
            }
            if (!$isEnd) {
                $last = $id($items[count($items) - 1]);
            }
        }
        return new Paginator($items, new CursorPager($pager->getLimit(), $first, $last));
    }
}