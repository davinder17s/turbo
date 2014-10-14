<?php

/**
 * Class Home extends Controller
 *
 * Must return \Symfony\Component\HttpFoundation\Response Object
 *
 * This controller is fully restful and will require to specify the access method get/post before name to work.
 */

class Home extends Controller{

    function getIndex()
    {
        /*
         * Render a template and return a new response
         */
        return View::make('home.twig');
    }
}
