<?php
class Weather {

    private $city;

    function __construct($city = '北京') {
        $this->city = $city;
    }

    function getCityWeather() {
        $city = urlencode(iconv('UTF-8', 'GBK', $this->city));
        $url = "http://php.weather.sina.com.cn/xml.php?city={$city}&password=DJOYnieT8234jlsK&day=0";
        $xml = '';
        for ($c = 2; $c > 0 && empty($xml); $c--) {
            $xml = file_get_contents($url);
        }
        // echo $xml; exit;
        $domObj = new XmlToArrayParser($xml);
        $wObj = $domObj->array['Profiles']['Weather'];

        return $this->_weatherObjToStr($wObj);
    }

    private function _weatherObjToStr($wObj) {
        if (empty($wObj)) {
            return '';
        }
        return "{$wObj['city']}：\n"
            . ($wObj['status1']==$wObj['status2'] ? $wObj['status2'] : "{$wObj['status1']}转{$wObj['status2']}") . "\n"
            . "{$wObj['direction2']}{$wObj['power2']}级\n"
            . "{$wObj['temperature1']}到{$wObj['temperature2']}摄氏度\n"
            . trim($wObj['yd_s'], '；')
        ;
    }

}

/*
API:
    http://www.360doc.com/content/14/0421/17/2036337_370903721.shtml
*/
