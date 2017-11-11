<?php
defined('BASEPATH') OR exit('No direct script access allowed');
    $config['protocol'] = 'smtp';
    $config['smtp_host'] = 'ssl://smtp.gmail.com';
    $config['smtp_port'] = '465';
    $config['smtp_user'] = 'email_addr';
    $config['smtp_pass'] = 'email_pass';
    $config['smtp_timeout'] = '30';
    // $config['mailpath'] = '/application/logs/';
    // $config['mailpath'] = '/usr/sbin/sendmail';
    $config['charset'] = "utf-8";
    $config['mailtype'] = 'html'; // or text
    $config['newline'] = "\r\n";
    $config['crlf'] = "\r\n";
    $config['wordwrap'] = true;
?>