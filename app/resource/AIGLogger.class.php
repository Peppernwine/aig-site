<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 7/12/2018
 * Time: 8:42 PM
 */

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SwiftMailerHandler;
use Monolog\Formatter\HtmlFormatter;

class AIGLogger {
    // Hold an instance of the class
    private static $_instance;

    private static function createLogger() {

        $logger = new Logger('aig');
        $logger->pushHandler(new StreamHandler(LOG_PATH.'/aig.log', LOG_LEVEL));

        /*
        $transport = (new Swift_SmtpTransport(SMTP_HOST, SMTP_PORT))
            ->setUsername(SMTP_USER_NAME)
            ->setPassword(SMTP_PASSWORD);
        $message = new Swift_Message('A CRITICAL problem was encountered on Avon Indian Grill website');
        $message->setFrom(FROM_EMAIL_ID);
        $message->setTo(FROM_EMAIL_ID);
        $message->setContentType("text/html");
        $mailer = Swift_Mailer::newInstance($transport);

        $mailerHandler = new SwiftMailerHandler($mailer, $message, Logger::CRITICAL);
        $mailerHandler->setFormatter(new HtmlFormatter());
        $logger->pushHandler($mailerHandler);

        */

        return $logger;
    }

    // The singleton method
    public static function instance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = self::createLogger();
        }
        return self::$_instance;
    }
}

