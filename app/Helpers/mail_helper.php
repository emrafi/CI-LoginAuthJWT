<?php
function mailing($email, $message)
{
    //set up email
    $emailSender = \Config\Services::email();

    $config['protocol'] = "smtp";
    $config['mailPath'] = "/usr/sbin/sendmail";
    $config['charset'] = "utf-8";
    $config['wordWrap'] = true;
    $config['SMTPHost'] = "smtp.gmail.com";
    $config['SMTPPort'] = '587';
    $config['SMTPUser'] = "mr.firmanssyah@gmail.com";
    $config['SMTPPass'] = "fidy@LOVEYOU28";
    $config['mailType'] = "html";
    $config['newline'] = "\r\n";
    $emailSender->initialize($config);
    $emailSender->setFrom("mr.firmanssyah@gmail.com", "rav");
    $emailSender->setTo($email);
    // $email->setCC('another@another-example.com');
    // $email->setBCC('them@their-example.com');
    $emailSender->setSubject("Information");
    $emailSender->setMessage($message);
    return $emailSender->send();
}
