<?php
use Dj\Utility\Sms\SmsDriver;
 
$config = [
  'appId' => 'werwer', 
  'appSecret' => 'werwer'
];
$sms =new SmsDriver(SmsDriver::ALIYUN_SMS, $config);
$sms->sendSms('13510277171', 'tp012c', ['name'=>'名称', 'age'=>'10分'], '签名');

// 群发
$phones = ['13510277171', '13412222123'];
$params = [
  ['name'=>'名称', 'age'=>'10分'],
  ['name'=>'名称', 'age'=>'10分'],
];
$singNames = [
 '签名',
 '签名'
];
$sms->sendMultiSms(phones, 'tp012c', params, singNames); 

// --------------------------------------------------------
// 腾讯短信

$sms =new SmsDriver(SmsDriver::TENCENT_SMS, $config);
$sms->sendSms('13510277171', 'tp012c', ['名称', '10分'], '签名');
// 群发
$phones = ['13510277171', '13412222123'];
$params = [ '名称',  '10分'];
$singNames = '签名';
$sms->sendMultiSms(phones, 'tp012c', params, singNames); 


?>