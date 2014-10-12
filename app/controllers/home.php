<?php


class Home extends Controller{

    function index()
    {
        return View::make('home.twig');
    }
}

