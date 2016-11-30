<?php

return [
    /*
     * Debug 模式，bool 值：true/false
     *
     * 当值为 false 时，所有的日志都不会记录
     */
    'debug'  => true,

    /*
     * 使用 Laravel 的缓存系统
     */
    'use_laravel_cache' => true,

    /*
     * 开放平台获取
     */
    'app_id'  => env('WECHAT_APPID', 'your-app-id'),         // AppID
    'secret'  => env('WECHAT_SECRET', 'your-app-secret'),     // AppSecret
    'token'   => env('WECHAT_TOKEN', 'your-token'),          // Token
    'aes_key' => env('WECHAT_AES_KEY', '')                   // EncodingAESKey

];
