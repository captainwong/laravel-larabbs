<?php

namespace App\Handlers;

use GuzzleHttp\Client;
use Overtrue\Pinyin\Pinyin;
use Illuminate\Support\Facades\Log;

class SlugTranslateHandler
{
    public function translate($text){
        $http = new Client;

        $api = 'http://api.fanyi.baidu.com/api/trans/vip/translate?';
        $appid = config('services.baidu_translate.appid');
        $key = config('services.baidu_translate.key');
        $salt = time();

        Log::info("百度翻译".$appid.$key);

        if(empty($appid) || empty($key)){
            return $this->pinyin($text);
        }

        $sign = md5($appid. $text . $salt . $key);
        Log::info("百度翻译 sign:".$sign);

        $query = http_build_query([
            'q' => $text,
            'from' => 'zh',
            'to' => 'en',
            'appid' => $appid,
            'salt' => $salt,
            'sign' => $sign,
        ]);

        $responce = $http->get($api . $query);

        $result = json_decode($responce->getBody(), true);
        Log::info("百度翻译 result:".json_encode($result));

        /**
        获取结果，如果请求成功，dd($result) 结果如下：

        array:3 [▼
            "from" => "zh"
            "to" => "en"
            "trans_result" => array:1 [▼
                0 => array:2 [▼
                    "src" => "XSS 安全漏洞"
                    "dst" => "XSS security vulnerability"
                ]
            ]
        ]

        **/

        if(isset($result['trans_result'][0]['dst'])){
            return 'topic-'.str_slug($result['trans_result'][0]['dst']);
        }else{
            return $this->pinyin($text);
        }
    }

    public function pinyin($text){
        return 'topic-'.str_slug(app(Pinyin::class)->permalink($text));
    }
}