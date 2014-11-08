<?php

use Illuminate\Database\Eloquent\Model as Model;

class EloquentModel extends Model{
    public $last_email_html = '';
    function sendEmail($email_template, $subject= '', $additional_data = array(), $from_email = '', $from_name ='')
    {
        $config = require APPDIR . 'config/email.php';
        $app = App::instance();
        $data = array(
            'user' => $this,
        );
        $data = array_merge($data, $additional_data);
        $message = $app->twig->render('emails/' . $email_template, $data);
        $this->last_email_html = $message;

        $mail = $app->email;
        foreach ($config as $key => $value) {
            $mail->$key = $value;
        }

        if ($from_email) {
            $mail->From = $from_email;
        }

        if ($from_name) {
            $mail->FromName = $from_name;
        }

        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->isHTML($config['isHTML']);
        $mail->addAddress($this->email);
        $mail->send();


        return $this;
    }
}

$models_dir = APPDIR . 'eloquent/';
$models = scandir($models_dir);
unset($models[0], $models[1]);
foreach ($models as $model) {
    require $models_dir . $model;
}