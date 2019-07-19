<?php

namespace Dj\Utility\Sms;

use Dj\Utility\Sms\Aliyun\SignatureHelper;
use Dj\Utility\Sms\Tencent\SmsMultiSender;
use Dj\Utility\Sms\Tencent\SmsSingleSender;


/**
 * Class SmsDriver 邮件短信驱动(只实现阿里云和腾讯模版短信接口)
 * @package App\Utility\Sms
 */
class SmsDriver
{
    const ALIYUN_SMS = 0;  // 阿里云接口
    const TENCENT_SMS = 1;  // 腾讯云接口

    private $config;
    private $type;

    /**
     * 邮件短信驱动
     * SmsDriver constructor.
     * @param $type 发送短信接口类型, ALIYUN_SMS|TENCENT_SMS
     * @param $config 配置, {appId:'', appSecret:''}
     */
    public function __construct($type, $config)
    {
        $this->type = $type;
        $this->config = $config;

    }

    /**
     * 发送单条短信
     * @param $phoneNumber 电话号码
     * @param $templateId  模板ID
     * @param $templateParams  模板参数
     * @param $signName 签名名称
     * @param string $upExtendCode 上行电话号码
     * @return bool|mixed|\stdClass  falsh或返回值
     */
    public function sendSms($phoneNumber, $templateId, $templateParams, $signName, $upExtendCode = '')
    {
        if ($this->type == self::ALIYUN_SMS) {
            $sms = new SignatureHelper();
            $params = [
                'PhoneNumbers' => $phoneNumber,
                'SignName' => $signName,
                'TemplateCode' => $templateId,
                'TemplateParam' => json_encode($templateParams, JSON_UNESCAPED_UNICODE)
            ];
            if (!empty($upExtendCode)) {
                $params['SmsUpExtendCode'] = $upExtendCode;
            }
            $params = array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ));
            $rsp = $sms->request($this->config['appId'], $this->config['appSecret'], 'dysmsapi.aliyuncs.com', $params);
            return $rsp;
        } else if ($this->type == self::TENCENT_SMS) {
            $sms = new SmsSingleSender($this->config['appId'], $this->config['appSecret']);
            $result = $sms->sendWithParam('86', $phoneNumber, $templateId, $templateParams, $signName, $upExtendCode);
            $rsp = json_decode($result);
            return $rsp;
        }
    }

    /**
     * 群发短信
     * @param array $phoneNumbers 手机号码(数组)
     * @param string $templateId  模板ID
     * @param array $templateParams  模板参数,阿里云需要使用二维数组,腾讯云使用一维数组
     * @param $signNames 签名,阿里云使用数组,腾讯云使用字符串
     * @param string $upExtendCodes 上行手机号,阿里云使用数组,腾讯云使用字符串
     * @return bool|mixed|\stdClass
     */
    public function sendMultiSms(array $phoneNumbers, string $templateId, array $templateParams, $signNames, $upExtendCodes = '')
    {
        if ($this->type == self::AliyunSms) {
            $sms = new SignatureHelper();
            $params = [
                'PhoneNumberJson' => json_encode($phoneNumbers, JSON_UNESCAPED_UNICODE),
                'SignNameJson' => json_encode($signNames, JSON_UNESCAPED_UNICODE),
                'TemplateCode' => $templateId,
                'TemplateParamJson' => json_encode($templateParams, JSON_UNESCAPED_UNICODE)
            ];
            if (!empty($upExtendCodes)) {
                $params["SmsUpExtendCodeJson"] = json_encode($upExtendCodes, JSON_UNESCAPED_UNICODE);
            }
            $params = array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendBatchSms",
                "Version" => "2017-05-25",
            ));
            $rsp = $sms->request($this->config['appId'], $this->config['appSecret'], 'dysmsapi.aliyuncs.com', $params);
            return $rsp;
        } else if ($this->type == self::TENCENT_SMS) {
            $sms = new SmsMultiSender($this->config['appId'], $this->config['appSecret']);
            $result = $sms->sendWithParam('86', $phoneNumbers, $templateId, $templateParams, $signNames, $upExtendCodes);
            $rsp = json_decode($result);
            return $rsp;
        }
    }


}