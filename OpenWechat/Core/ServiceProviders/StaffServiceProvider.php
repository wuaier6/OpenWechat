<?php
/**
 * Created by PhpStorm.
 * User: songzhen
 * Date: 2016/8/11
 * Time: 17:47
 */
namespace App\Common\OpenWechat\Core\ServiceProviders;
use App\Common\OpenWechat\Staff\Staff;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class StaffServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['staff'] = function() use ($pimple){
            return new Staff($pimple['auth']);
        };
    }

}