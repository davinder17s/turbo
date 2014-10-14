<?php
/**
 * Default emailing parameters
 *
 * PHPMailer makes use of this config
 */

return array(
    'isSMTP' => false, // set true to use smtp settings

    // SMTP Settings
    'Host' => 'smtp1.example.com',
    'SMTPAuth' => true,
    'Username' => 'user@example.com',
    'Password' => '',
    'SMTPSecure' => 'tls',
    'Port' => 587,

    // Default Settings
    'From' => 'no-reply@domain.com',
    'FromName' => 'mailer',
    'WordWrap' => 100,
    'isHTML' => true
);