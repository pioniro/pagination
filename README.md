Simple paginator
================

[![Build Status](https://travis-ci.org/pioniro/pagination.svg?branch=master)](https://travis-ci.org/pioniro/pagination)
[![Coverage Status](https://coveralls.io/repos/github/pioniro/pagination/badge.svg?branch=master)](https://coveralls.io/github/pioniro/pagination?branch=master)

## Install

```bash
composer require pioniro/pagination
```

##Usage

```php
use Pioniro\Pagination\Pagination\QueryBuilderPagination;
use Pioniro\Pagination\Pager\CursorPager;

// give me 10 items after item(id=15)
$pager = new CursorPager(10, null, 15);

$qb = $entityManager
    ->createQueryBuilder('App:Item', 'i')
    ->orderBy('i.id', 'ASC');

$pagination = new QueryBuilderPagination($qb, $pager);
// items can be obtained
foreach ($pagination as $item) {
    // ...
}
// OR
foreach ($pagination->getItems() as $item) {
    // ...
}
// new Pager same type as $pager
$pagination->getPager();
```