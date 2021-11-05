<?php

use Application\Database\Connection;
use Application\Database\Pagination;
use Application\Database\QueryBuilder;

require_once __DIR__ . '/bootstrap.php';

$con = new Connection($dbconfig);
$sql = QueryBuilder::select('users');

$pagination = new Pagination($sql::getSql(), 0, 10);

foreach ($pagination->paginate($con, PDO::FETCH_ASSOC) as $row) {
    echo $row['id'] . ' ' . $row['name'] . PHP_EOL;
}


// --- Test autoloader
// $test = new TestClass();
// echo $test->getTest();

// $test = new \Application\Test\FakeClass();
// echo $test->getTest();
// --- Test autoloader

// --- Test query builder
// $sql = QB::select('project')
//     ->where()
//     ->like('name', '%secret%')
//     ->and('priority > 9')
//     ->or('code')
//     ->not('bla')
//     ->in(['one', 'two'])
//     ->limit(10)
//     ->offset(10);

// echo QB::getSql();
// --- Test query builder
