<?php
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\HeaderBag;

class Redirect {
    public static function to($url = '')
    {
        return new RedirectResponse( siteUrl($url) );
    }

    public static function back($validator = false)
    {
        if ($validator != false) {
            $messages = $validator->messages();
            App::instance()->flash()->set('errors', $messages);
            App::instance()->flash()->set('input_old', App::instance()->post()->all());
        }
        $previous_url = App::instance()->request()->headers->get('referer');
        return new RedirectResponse($previous_url);
    }

    public static function url($url = '')
    {
        return new RedirectResponse($url);
    }

}