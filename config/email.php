<?php

namespace Config;

use Illuminate\Config\Repository;

class Email extends Repository
{
    public $fromEmail = 'support@myshoman.com';
    public $fromName = 'Airtaska';
    public $recipients = 'recipient@example.com';
    public $userAgent = 'CodeIgniter';
    public $protocol = 'smtp';
    public $mailPath = '/usr/sbin/sendmail';
    public $SMTPHost = 'mail.myshoman.com';
    public $SMTPUser = 'support@myshoman.com';
    public $SMTPPass = 'shomanSupport!@';
    public $SMTPPort = 587;
    public $SMTPTimeout = 15;
    public $SMTPKeepAlive = false;
    public $SMTPCrypto = 'tls';
    public $wordWrap = true;
    public $wrapChars = 76;
    public $mailType = 'html';
    public $charset = 'UTF-8';
    public $validate = false;
    public $priority = 3;
    public $CRLF = "\r\n";
    public $newline = "\r\n";
    public $BCCBatchMode = false;
    public $BCCBatchSize = 200;
    public $DSN = false;
}
