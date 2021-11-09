<?php

use Application\Database\Connection;
use Application\Entity\CustomerService;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';

$con = new Connection($dbconfig);
$cs = new CustomerService($con);
$customer = $cs->findById(9);

var_dump($customer);
