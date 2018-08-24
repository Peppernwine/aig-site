<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/17/2018
 * Time: 3:15 AM
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SendEmail
{
   public function sendTo($toEmailId, $subject, $body, $altBody) {
       $this->send(FROM_EMAIL_ID, FROM_EMAIL_NAME,$toEmailId, $subject, $body, $altBody);
   }

    public function sendToAdmin($subject, $body,$altBody) {
        foreach (explode(',',ADMIN_EMAIL_IDS) as $toEmailId) {
            $this->sendTo($toEmailId, $subject, $body,$altBody);
        }
    }

    public function sendToRestaurant($subject, $body, $altBody) {
        foreach (explode(',',FRONT_OFFICE_EMAIL_IDS) as $toEmailId) {
            $this->sendTo($toEmailId, $subject, $body,$altBody);
        }
    }

    public function send($fromEmailId, $fromName, $toEmailId, $subject, $body, $altBody) {
        $mail = new PHPMailer(true); // Passing `true` enables exceptions
        //Server settings
        $mail->SMTPDebug = SMTP_DEBUG; // Enable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP

        $mail->Username = SMTP_USER_NAME; // SMTP username
        $mail->Password = SMTP_PASSWORD;// SMTP password
        if (!empty(SMTP_USER_NAME) && !empty(SMTP_PASSWORD))
            $mail->SMTPAuth = true; // Enable SMTP authentication

        $mail->Host = SMTP_HOST;  // Specify main and backup SMTP servers
        $mail->Port = SMTP_PORT; // TCP port to connect to

        $mail->SMTPSecure = SMTP_SECURE; // Enable TLS encryption, `ssl` also accepted

        //Recipients
        $mail->setFrom($fromEmailId, $fromName);
        $mail->addAddress($toEmailId); // Name is optional
        $mail->addReplyTo(REPLY_TO_EMAIL_ID, REPLY_TO_EMAIL_NAME);

        //Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = $altBody;

        try {
            ob_start();
            if (!$mail->send())
                throw new ServerException('Error Sending email - ' . $mail->ErrorInfo);
        } finally {
            $debug = ob_get_contents();
            ob_end_clean();
            if (!empty($debug))
                AIGLogger::instance()->addDebug($debug);
        }
    }
}