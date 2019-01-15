<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 7/5/2018
 * Time: 11:33 AM
 */

use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__, 'appconfig.env');
$dotenv->overload();

define('SERVER_URL',getenv('SERVER_URL'));

define('DB_HOST',getenv('DB_HOST'));
define('DB_NAME',getenv('DB_NAME'));
define('DB_USER',getenv('DB_USER'));
define('DB_PASSWORD',getenv('DB_PASSWORD'));

define('PHP_DISPLAY_ERRORS',getenv('PHP_DISPLAY_ERRORS'));

define('LOG_LEVEL',getenv('LOG_LEVEL'));

define('TINY_URL_HOST',getenv('TINY_URL_HOST'));

define('SMTP_HOST',getenv('SMTP_HOST'));
define('SMTP_PORT',getenv('SMTP_PORT'));
define('SMTP_SECURE',getenv('SMTP_SECURE'));
define('SMTP_USER_NAME',getenv('SMTP_USER_NAME'));
define('SMTP_PASSWORD',getenv('SMTP_PASSWORD'));
define('SMTP_DEBUG',getenv('SMTP_DEBUG'));

define('FROM_EMAIL_ID',getenv('FROM_EMAIL_ID'));
define('FROM_EMAIL_NAME',getenv('FROM_EMAIL_NAME'));
define('REPLY_TO_EMAIL_ID',getenv('REPLY_TO_EMAIL_ID'));
define('REPLY_TO_EMAIL_NAME',getenv('REPLY_TO_EMAIL_NAME'));
define('ADMIN_EMAIL_IDS',getenv('ADMIN_EMAIL_IDS'));
define('FRONT_OFFICE_EMAIL_IDS',getenv('FRONT_OFFICE_EMAIL_IDS'));
define('SMS_FROM_PHONE',getenv('SMS_FROM_PHONE'));
define('SMS_TO_ADMIN_PHONES',getenv('SMS_TO_ADMIN_PHONES'));
define('SMS_TO_FRONT_OFFICE_PHONES',getenv('SMS_TO_FRONT_OFFICE_PHONES'));
define('FAX_TO',getenv('FAX_TO'));

define('STRIPE_API_KEY',getenv('STRIPE_API_KEY'));
define('STRIPE_API_SECRET',getenv('STRIPE_API_SECRET'));

define('PDFSHIFT_API_HOST',getenv('PDFSHIFT_API_HOST'));
define('PDFSHIFT_API_KEY',getenv('PDFSHIFT_API_KEY'));
define('PDFSHIFT_SANDBOX',getenv('PDFSHIFT_SANDBOX'));

define('TWILIO_ACCOUNT_SID',getenv('TWILIO_ACCOUNT_SID'));
define('TWILIO_AUTH_TOKEN',getenv('TWILIO_AUTH_TOKEN'));

define('PHAXIO_API_HOST',getenv('PHAXIO_API_HOST'));
define('PHAXIO_API_BATCH_DELAY',getenv('PHAXIO_API_BATCH_DELAY'));
define('PHAXIO_API_KEY_LIVE',getenv('PHAXIO_API_KEY_LIVE'));
define('PHAXIO_API_SECRET_LIVE',getenv('PHAXIO_API_SECRET_LIVE'));
define('PHAXIO_API_KEY_TEST',getenv('PHAXIO_API_KEY_TEST'));
define('PHAXIO_API_SECRET_TEST',getenv('PHAXIO_API_SECRET_TEST'));

define('VOUCHERIFY_API_ID',getenv('VOUCHERIFY_API_ID'));
define('VOUCHERIFY_API_KEY',getenv('VOUCHERIFY_API_KEY'));
