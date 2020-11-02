<?php

namespace App;

use App\Config;
use Mailgun\Mailgun;

/**
 * Mail
 *
 * PHP version 7.0
 */
class Mail {

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
    public static function send($to, $subject, $message, $html) {

        //PHP mail()
        ini_set( 'display_errors', 1 );
        error_reporting( E_ALL );
        $from = "yourbudget@malgorzatamickiewicz.pl";
        $headers = "From:" . $from;
        mail($to,$subject,$message, $headers);
    }
    
}