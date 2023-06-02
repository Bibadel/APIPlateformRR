<?php

$_SERVER['HTTPS'] = 'on';
$_SERVER['SERVER_PORT'] = 443;

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
