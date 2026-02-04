<?php

declare(strict_types=1);

use \App\System\App;

session_start();

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/helpers.php';

$app = App::instance();
echo $app->run();
