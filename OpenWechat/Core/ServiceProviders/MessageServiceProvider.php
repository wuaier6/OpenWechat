<?php
/**
 * Created by PhpStorm.
 * User: songzhen
 * Date: 2016/8/8
 * Time: 11:32
 */
namespace App\Common\OpenWechat\Core\ServiceProviders;

use App\Common\OpenWechat\Message\Message;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class MessageServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['message'] = function() use ($pimple){
            return new Message($pimple['wxcrypt'], $pimple['request'], $pimple['xml']);
        };
    }

}