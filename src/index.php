<?php
require dirname(__DIR__) . '/vendor/autoload.php';

spl_autoload_register(function($class) {
    require __DIR__ . "/libraries/{$class}.php";
});

use EasyWeChat\Foundation\Application;

$options = include(__DIR__ . '/config.php');

$app = new Application($options);

$app->server->setMessageHandler(function($msg) {
    $msg = json_decode($msg, true);

    /// 事件

    if ($msg['MsgType'] == 'event' && $msg['Event'] == 'CLICK') {
        switch ($msg['EventKey']) {
            case 'MENU_HELP':
                return "发\"xx天气\"，即可立即收到xx地区的天气预报";
            case 'MENU_ME':
                return "新浪微博 @面条布丁\nQQ 545827465";
            default:
                return;
        }
    }

    /// 消息

    $content = $msg['Content'];
    if (strstr($content, '天气')) {
        // 如果用户查询天气时指定了具体的地区，则查询用户给定地区的天气
        // 否则查询该用户个人信息中填写的地区的天气（或者在没有查询到用户指定地区的天气时）
        // return "亲，过几天（或者几十天）就可以查询天气了哦~";
        $city = trim(str_replace('天气', '', $content));
        $w = new Weather($city);
        $weather = $w->getCityWeather();
        if (empty($weather) /*|| !strstr($weather, $city)*/) {
            return "sorry, 没有\"{$city}\"这个地区的天气信息~~";
        } else {
            return $weather;
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
