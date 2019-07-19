<?php

namespace Dj\Utility\Smtp;


use Dj\Utility\Smtp\Aliyun\Smtp;

/**
 * Class SmtpDriver 邮件驱动(只实现阿里云接口)
 * @package App\Utility\Smtp
 */
class SmtpDriver
{
    const ALIYUN_SMTP = 0;

    private $config;
    private $type;

    /**
     * SMTP发送邮件
     * SmtpDriver constructor.
     * @param $type 类型  ALIYUN_SMTP
     * @param $config 配置{host:'',port:20,user:'',pass:'',from:'',sender:''}
     */
    public function __construct($type, $config)
    {
        $this->type = $type;
        $this->config = $config;

    }

    public function sendMail($to, $title, $body)
    {
        if ($this->type == self::ALIYUN_SMTP) {
            $smtp = new Smtp($this->config['host'], $this->config['port'], true, $this->config['user'], $this->config['pass']);

            $from = $this->config['from'];  // 发信地址
            $sender = $this->config['sender']; // 发件人
            $mailtype = "HTML";
            $cc = '';
            $bcc = '';
            $additional_headers = '';
            $smtpreplyto = '';
            $title = "=?UTF-8?B?" . base64_encode($title) . "?=";
            return $smtp->sendmail($to, $from, $title, $body, $mailtype, $cc, $bcc, $additional_headers, $sender, $smtpreplyto);
        }
        return false;
    }



}