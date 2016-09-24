<?php

namespace App\Tools\SMS;


class SendTemplateSMS
{
    //主帐号
    private $accountSid = '8aaf070857514dd701575a5c3a5e04a1';

    //主帐号Token
    private $accountToken = 'cb1ccaf32ce3487c8e0410c02507da79';

    //应用Id
    private $appId = '8aaf070857514dd701575a5c3c0a04a8';

    //请求地址，格式如下，不需要写https://
    private $serverIP = 'sandboxapp.cloopen.com';

    //请求端口
    private $serverPort = '8883';

    //REST版本号
    private $softVersion = '2013-12-26';

    /**
     * 发送模板短信
     * @param to 手机号码集合,用英文逗号分开
     * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
     * @param $tempId 模板Id
     */
    public function sendTemplateSMS($to, $datas, $tempId)
    {

        // 初始化REST SDK
        $rest = new CCPRestSDK($this->serverIP, $this->serverPort, $this->softVersion);
        $rest->setAccount($this->accountSid, $this->accountToken);
        $rest->setAppId($this->appId);

        // 发送模板短信
        $result = $rest->sendTemplateSMS($to, $datas, $tempId);

        return $result;
    }
}

//sendTemplateSMS("18576437523", array(1234, 5), 1);
