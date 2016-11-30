# [Open WeChat]

微信开放平台 。没有全部测试，所以没有做成包,有些地方还有bug,现在通过管道和服务开放出来了.
现在已经用于个人的开放平台学习项目中。


## 环境要求

1. PHP >= 5.5.9
2. **[composer](https://getcomposer.org/)**
3. openssl 拓展

## 安装

```
		//通知
		$wechat = app('openwechat');
        $message = $wechat->event->notice();
        Log::info("component_verify_ticket" . json_encode($message));
        switch ($message['MsgType']) {
            case 'component_verify_ticket':
                Log::info('component_verify_ticket:' . $message['ComponentVerifyTicket']);
                break;
            case 'authorized':
                Log::info('authorized:');
                # 授权
                break;
            case 'unauthorized':
                Log::info('unauthorized:');
                # 取消授权
                break;
            default:
                # code...
                break;
        }

		//创造menu
		$wechat = app('openwechat');
        $menu = $wechat->menu;
        $menu1234="{\"menu\":{\"button\":[{\"name\":\"\u8840\u538b\u7ba1\u7406\",\"sub_button\":[{\"type\":\"view\",\"name\":\"\u8840\u538b\u8bb0\u5f55\",\"url\":\"http:\/\/rzwtzsbpf0.proxy.qqbrowser.cc\/User\/DailyInput\",\"sub_button\":[]},{\"type\":\"view\",\"name\":\"\u62a5\u544a\u67e5\u8be2\",\"url\":\"http:\/\/rzwtzsbpf0.proxy.qqbrowser.cc\/User\/RiskReport\",\"sub_button\":[]},{\"type\":\"view\",\"name\":\"V\u6392\u884c\u699c\",\"url\":\"http:\/\/rzwtzsbpf0.proxy.qqbrowser.cc\/User\/Rank\",\"sub_button\":[]}]},{\"type\":\"view\",\"name\":\"\u75be\u75c5\u77e5\u8bc6\",\"url\":\"http:\/\/htnhub.flzhan.com\",\"sub_button\":[]}],\"menuid\":403867448},\"conditionalmenu\":[{\"button\":[{\"type\":\"view\",\"name\":\"\u8840\u538b\u62a5\u544a\",\"url\":\"http:\/\/bzfsg8lrtg.proxy.qqbrowser.cc\/Doc\/Info\",\"sub_button\":[]},{\"type\":\"view\",\"name\":\"\u75be\u75c5\u77e5\u8bc6\",\"url\":\"http:\/\/bzfsg8lrtg.proxy.qqbrowser.cc\/Health\/Knowledge\",\"sub_button\":[]}],\"matchrule\":{\"group_id\":\"100\"},\"menuid\":403059584}]}";
        $menu_button=json_decode($menu1234,true);
        unset($menu_button['menu']['menuid']);
        print_r($menu_button['menu']);
        $menu_button['menu']['button'][0]['name']="st-test";
        $data= $menu->add($appid,$menu_button['menu']['button']);

		//获取授权用户列表
        $appid='XXX';
        $wechat = app('openwechat');
        $user = $wechat->user;

        $user_list=   $user->lists($appid);

        $user_info= $user->batchGet($appid,$user_list['data']['openid']);
```

有什么问题 可以联系 411080515@qq.com

MIT
