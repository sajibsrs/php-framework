<?php

use Application\Database\Connection;
use Application\Database\Pagination;
use Application\Database\QueryBuilder;
use Application\Entity\Customer;
use Application\Entity\CustomerService;

require_once __DIR__ . '/bootstrap.php';

$con = new Connection($dbconfig);
$cs = new CustomerService($con);
$customer = $cs->findById(9);

// --- Test update customer
// $customer->setLevel('XLL');
// $update = $cs->update($customer);
// --- Test update customer

// --- Test new customer
// $data = [
//     'name' => 'Mahfuzur rahman',
//     'balance' => 300.50,
//     'email' => 'max.shohag@gmail.com',
//     'password' => '505050k',
//     'status'   => 3,
//     'security_question' => 'Donno yet',
//     'confirm_code' => 'z0z0z0z0',
//     'profile_id' => 3,
//     'level' => 'XOX'
//     ];

// $new = Customer::arrayToEntity($data, new Customer());
// $cs->insert($new);
// --- Test new customer

// --- Test entity
// $builder = QueryBuilder::select('customers')->where('id = 1');

// $stmt = $con->pdo->prepare($builder->getQuery()); 
// $stmt->execute();
// $result = $stmt->fetch(PDO::FETCH_ASSOC);

// $customer = Customer::arrayToEntity($result, new Customer());
// var_dump($customer);
// --- Test entity

// --- Test pagination
// $pagination = new Pagination($builder::getQuery(), 0, 10);

// foreach ($pagination->paginate($con, PDO::FETCH_ASSOC) as $row) {
//     echo $row['id'] . ' ' . $row['name'] . PHP_EOL;
// }
// --- Test pagination

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
