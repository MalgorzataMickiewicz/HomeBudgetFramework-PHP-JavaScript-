<?php

namespace App;

use App\Config;
use Mailgun\Mailgun;

/**
 * Mail
 *
 * PHP version 7.0
 */
class Mail
{

    /**
     * Send a message
     *
     * @param string $to Recipient
     * @param string $subject Subject
     * @param string $text Text-only content of the message
     * @param string $html HTML content of the message
     *
     * @return mixed
     */
    public static function send($to, $subject, $text, $html)
    {
        $mgClient = Mailgun::create('key-423b6af955098f687beb44004e167cdc');
        $domain = "sandbox2a1058c28a3a4403b553f30e180a7f7f.mailgun.org";
        $result = $mgClient->messages()->send($domain, array(
            'from'    => 'mailgun@sandbox2a1058c28a3a4403b553f30e180a7f7f.mailgun.org',
            'to'      => $to,
            'subject' => $subject,
            'text'    => $text,
            'html'    => $html));
    }
}