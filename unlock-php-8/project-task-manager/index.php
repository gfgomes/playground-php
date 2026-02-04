<?php

declare(strict_types=1);

use \App\System\App;

session_start();

require __DIR__ . '/vendor/autoload.php';
$app = App::instance();
echo $app->run();
