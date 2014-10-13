<?php


class Home extends Controller{

    function getIndex()
    {
        return View::make('home.twig');
    }
}
