<?php
require dirname(__DIR__) . '/vendor/autoload.php';

spl_autoload_register(function($class) {
    require __DIR__ . "/libraries/{$class}.php";
});

use EasyWeChat\Foundation\Application;

$options = include(__DIR__ . '/config.php');

$app = new Application($options);

$app->server->setMessageHandler(function($msg) {
    if (strstr($msg, '天气')) {
        // 如果用户查询天气时指定了具体的地区，则查询用户给定地区的天气
        // 否则查询该用户个人信息中填写的地区的天气（或者在没有查询到用户指定地区的天气时）
        // return "亲，过几天（或者几十天）就可以查询天气了哦~";
        $city = trim(str_replace('天气', '', $msg));
        $w = new Weather($city);
        $weather = $w->getCityWeather();
        if (empty($weather) /*|| !strstr($weather, $city)*/) {
            return "sorry, 没有\"{$city}\"这个地区的天气信息~~";
        } else {
            $a = explode(' ', $weather, 3);
            return $a[0] . ' ' . $a[1] . "\n\n" . str_replace(";", "\n\n", $a[2]);
        }
    } else {
        return "急事请联系新浪微博 @面条布丁，或者QQ 545827465，不急的话就耐心等等吧，会回复的。。。\n\n"
                . "另外，发\"xx天气\"给我，就可以立刻收到xx地区的天气预报哦\n"
                . "比如发\"北京天气\"或者\"天津天气\"，等等\n"
                . "是不是很方便？快来试试吧～";
    }
});

$response = $app->server->serve();

$response->send();
