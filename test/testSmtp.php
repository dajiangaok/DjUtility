<?php
use Dj\Utility\Smtp\SmtpDriver;
 
$config = [
 'host' => '127.0.0.1',
 'port' => 443,
 'user' => 'user',
 'pass' => 'pass',
 'from' => '来信地址',
 'sender' => '发件人'
];

$smtp = new SmtpDriver(SmtpDriver::ALIYUN_SMTP, $config);
$ret = $smtp->sendMail('1500461361@qq.com', '这是邮件标题', '<div>这是邮件内容</div>');
print_r($ret);

?>
