<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Exception;

use RuntimeException;

class PaginatorException extends RuntimeException
{
    public static function noRootEntity()
    {
        return new self('QueryBuilder does not have any root entity');
    }

    public static function noOrders()
    {
        return new self('Query for Cursor pagination MUST be ordered');
    }

    public static function badOrderPart($part)
    {
        return new self(sprintf('bad ordering part `%s`', $part));
    }

    public static function pagerNotSupported($pager)
    {
        return new self(sprintf('pager `%s` not supported', get_class($pager)));
    }
}