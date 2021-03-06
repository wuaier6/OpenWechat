<?php
/**
 * Created by PhpStorm.
 * User: songzhen
 * Date: 2016/7/27
 * Time: 14:19
 */
namespace App\Common\OpenWechat\Auth;

use Doctrine\Common\Cache\Cache;
use App\Common\OpenWechat\Core\AbstractAPI;
use App\Models\WechatOfficalAccounts;
use App\Repositories\WechatOfficalAccountsRepositoryEloquent;
class AccessToken extends AbstractAPI
{
    protected $appid;
    protected $accessTokenPrefix;
    protected $refreshTokenPrefix;

    protected $wechatofficalaccounts;
    const AUTHORIZER_TOKEN_URL = 'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token=';


    public function __construct($appid, Cache $cache)
    {
        $this->appid = $appid;
        $this->cache = $cache;
        $this->accessTokenPrefix = 'sinre.openwechat.authorizer_access_token.';
        $this->refreshTokenPrefix = 'sinre.openwechat.authorizer_refresh_token.';

        $this->wechatofficalaccounts = app('App\Repositories\WechatOfficalAccountsRepositoryEloquent');
    }

    /**
     * 缓存token
     * @param string $authorizer_appid 授权方appid
     * @param string|null $authorizer_access_token 授权方令牌
     * @param int|null $expire_in 授权方令牌的过期时间
     * @param string|null $authorizer_refresh_token 刷新令牌
     */
    public function cacheToken($authorizer_appid, $authorizer_access_token = null, $expire_in = null, $authorizer_refresh_token = null)
    {
        if($authorizer_access_token){
            $this->getCacheHandler()->save($this->accessTokenPrefix . $authorizer_appid, $authorizer_access_token, $expire_in - 1500);
        }
        $this->getCacheHandler()->save($this->refreshTokenPrefix . $authorizer_appid, $authorizer_refresh_token, 0);
    }

    /**
     * 获取授权公众号的接口调用凭据
     * @param string $authorizer_appid 授权方appid
     * @param string $access_token 第三方平台component_access_token
     * @param bool $forceRefresh 是否强制刷新token
     * @return false|mixed|null
     */
    public function getToken($authorizer_appid, $access_token, $forceRefresh = true)
    {
        $token = $this->getCacheHandler()->fetch($this->accessTokenPrefix . $authorizer_appid);
        if(!$token || $forceRefresh){
            $token = $this->getAccessToken($authorizer_appid, $access_token);
        }
        return $token;
    }

    /**
     * 获取授权公众号的接口调用凭据
     * @param string $authorizer_appid 授权方appid
     * @param string $access_token 第三方平台component_access_token
     * @return mixed|null
     */
    protected function getAccessToken($authorizer_appid, $access_token)
    {
        $params = [
            'component_appid' => $this->appid,
            'authorizer_appid' => $authorizer_appid,
            'authorizer_refresh_token' => $this->getfreshToken($authorizer_appid)
        ];
        $token = $this->parseJSON('post', [self::AUTHORIZER_TOKEN_URL . $access_token, json_encode($params)]);
        $this->savefreshToken($authorizer_appid,$token);
        $this->cacheToken($authorizer_appid, $token['authorizer_access_token'], $token['expires_in'], $token['authorizer_refresh_token']);
        return $token['authorizer_access_token'];
    }

    private function getfreshToken($authorizer_appid){
        $freshToken=$this->getCacheHandler()->fetch($this->refreshTokenPrefix . $authorizer_appid);
        if(is_null($freshToken)){
            $authorizer_app_info =  $this->wechatofficalaccounts->findWhere(array('authorizer_appid'=>$authorizer_appid))->first();
            if(is_null($authorizer_app_info)){
                return false;
            }
            return $authorizer_app_info->authorizer_refresh_token;
        }
        return $freshToken;
    }

    private function savefreshToken($authorizer_appid,$token){
        $array = array(
         "authorizer_access_token" => $token['authorizer_access_token']
        , "authorizer_refresh_token" => $token['authorizer_refresh_token']
        );
        return $this->wechatofficalaccounts->updateOrCreate(array("authorizer_appid" => $authorizer_appid), $array);
    }

}