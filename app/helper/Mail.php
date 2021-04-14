<?php
namespace App\helper;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Class Mail
 * @package App\helper
 * Mail::send(Mail::getInstance(),"123@qq.com","内容");
 */
class Mail{
    private static $mail = '';
    private function __construct()
    {

    }

    private function __clone(){}

    public static function getInstance()
    {
        if(!self::$mail instanceof PHPMailer){
            self::$mail = new PHPMailer(true);

            try {
                // 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
                self::$mail->SMTPDebug = 1;
                // 使用smtp鉴权方式发送邮件
                self::$mail->isSMTP();
                // smtp需要鉴权 这个必须是true
                self::$mail->SMTPAuth = true;
                // 链接qq域名邮箱的服务器地址
                self::$mail->Host = 'smtp.qq.com';
                // 设置使用ssl加密方式登录鉴权
                self::$mail->SMTPSecure = 'ssl';
                // 设置ssl连接smtp服务器的远程服务器端口号
                self::$mail->Port = 465;
                // 设置发送的邮件的编码
                self::$mail->CharSet = 'UTF-8';
                // 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
                self::$mail->FromName = '通';
                // smtp登录的账号 QQ邮箱即可
                self::$mail->Username = '396595399@qq.com';
                // smtp登录的密码 使用生成的授权码
                self::$mail->Password = 'erbozgnoysmhcbed';
                // 设置发件人邮箱地址 同登录账号
                self::$mail->From = '396595399@qq.com';
                // 邮件正文是否为html编码 注意此处是一个方法
                self::$mail->isHTML(true);



            } catch (Exception $e) {
                var_dump($e);
            }
        }
        return self::$mail;
    }

    public static function send($obj,$receive_email,$body){
        //Recipients
// 设置收件人邮箱地址
        $obj->addAddress($receive_email);
        // 添加多个收件人 则多次调用方法即可
//            $mail->addAddress('87654321@163.com');
        // 添加该邮件的主题
        $obj->Subject = '邮件主题';
        // 添加邮件正文
        $obj->Body = $body;
        // 为该邮件添加附件
//            $mail->addAttachment('./example.pdf');
        // 发送邮件 返回状态
        $status = $obj->send();
        return $status;
    }

}