<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * User.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace  App\Common\OpenWechat\User;

use  App\Common\OpenWechat\Core\AbstractAPI;

/**
 * Class User.
 */
class User extends AbstractAPI
{
    const API_GET = 'https://api.weixin.qq.com/cgi-bin/user/info';
    const API_BATCH_GET = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=';
    const API_LIST = 'https://api.weixin.qq.com/cgi-bin/user/get';
    const API_GROUP = 'https://api.weixin.qq.com/cgi-bin/groups/getid';
    const API_REMARK = 'https://api.weixin.qq.com/cgi-bin/user/info/updateremark';
    const API_OAUTH_GET = 'https://api.weixin.qq.com/sns/userinfo';

    /**
     * Notice constructor.
     *
     * @param \EasyWeChat\Core\AccessToken $accessToken
     */
    public function __construct($auth)
    {
        $this->auth = $auth;
    }
    /**
     * Fetch a user by open id.
     *
     * @param string $openId
     * @param string $lang
     *
     * @return array
     */
    public function get($appid,$openId, $lang = 'zh_CN')
    {
        $access_token = $this->auth->getAuthorizerToken($appid);
        $params = [
                   'access_token'=> $access_token,
                   'openid' => $openId,
                   'lang' => $lang,
                  ];

        return $this->parseJSON('get', [self::API_GET, $params]);
    }

    /**
     * Batch get users.
     *
     * @param array  $openIds
     * @param string $lang
     *
     * @return \EasyWeChat\Support\Collection
     */
    public function batchGet($appid,array $openIds, $lang = 'zh_CN')
    {
        $access_token = $this->auth->getAuthorizerToken($appid);
        $params = [];

        $params['user_list'] = array_map(function ($openId) use ($lang) {
            return [
                    'openid' => $openId,
                    'lang' => $lang,
                    ];
        }, $openIds);
        return $this->parseJSON('json', [self::API_BATCH_GET.$access_token, $params]);
    }

    /**
     * List users.
     *
     * @param string $nextOpenId
     *
     * @return \EasyWeChat\Support\Collection
     */
    public function lists($appid,$nextOpenId = null)
    {
        $access_token = $this->auth->getAuthorizerToken($appid);

        $params = ['access_token'=> $access_token,'next_openid' => $nextOpenId];

        return $this->parseJSON('get', [self::API_LIST, $params]);
    }

    /**
     * Set user remark.
     *
     * @param string $openId
     * @param string $remark
     *
     * @return bool
     */
    public function remark($appid,$openId, $remark)
    {
        $access_token = $this->auth->getAuthorizerToken($appid);
        $params = [
                   'access_token'=> $access_token,
                   'openid' => $openId,
                   'remark' => $remark,
                  ];

        return $this->parseJSON('json', [self::API_REMARK, $params]);
    }

    /**
     * Get user's group id.
     *
     * @param string $openId
     *
     * @return int
     */
    public function group($appid,$openId)
    {
        return $this->getGroup($appid,$openId);
    }

    /**
     * Get user's group.
     *
     * @param string $openId
     *
     * @return array
     */
    public function getGroup($appid,$openId)
    {
        $access_token = $this->auth->getAuthorizerToken($appid);
        $params = [ 'access_token'=> $access_token,'openid' => $openId];

        return $this->parseJSON('json', [self::API_GROUP, $params]);
    }
}
