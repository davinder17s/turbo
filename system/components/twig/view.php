<?php

use Symfony\Component\HttpFoundation\Response as Response;

class View {
    public static function make($file_path, $data = array(), $response_code = 200, $headers = array())
    {
        $app = App::instance();
        $rendered_view = $app->twig->render($file_path, $data);

        $response = new Response;
        $response->setContent($rendered_view);
        $response->setStatusCode($response_code);
        $response->headers->set('Content-Type', 'text/html');

        foreach ($headers as $header_name => $header_value) {
            $response->headers->set($header_name, $header_value);
        }

        return $response;
    }
}