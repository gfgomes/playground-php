<?php

declare(strict_types=1);

use \App\System\App;

require '../vendor/autoload.php';
$app = App::instance();
echo $app->run();
