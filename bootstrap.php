<?php

use Application\Autoload\Loader;

require_once __DIR__ . '/Application/Autoload/Loader.php';
require_once __DIR__ . '/config.php';

define('ROOT_DIR', __DIR__ . '/');

Loader::init(ROOT_DIR);
