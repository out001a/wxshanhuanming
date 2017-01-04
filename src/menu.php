<?php
require dirname(__DIR__) . '/vendor/autoload.php';

spl_autoload_register(function($class) {
    require __DIR__ . "/libraries/{$class}.php";
});

use EasyWeChat\Foundation\Application;

$options = include(__DIR__ . '/config.php');

$app = new Application($options);

$app->menu->add([
    [
        'type' => 'click',
        'name' => '帮助',
        'key'  => 'MENU_HELP',
    ],
    [
        'type' => 'click',
        'name' => '关于我',
        'key'  => 'MENU_ME',
    ],
]);
