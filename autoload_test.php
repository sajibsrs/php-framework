<?php

use Application\Autoload\Loader;
use Application\Test\BlaClass;
use Application\Test\TestClass;

require __DIR__ . '/Application/Autoload/Loader.php';

Loader::init(__DIR__ . '/');

$test = new TestClass();
// echo $test->getTest();

$bla = new BlaClass();

// $test = new \Application\Test\FakeClass();
// echo $test->getTest();