<?php


class home extends Controller{
    function __construct()
    {
        parent::__construct();
    }
    function index()
    {
        $data = array(
            'name' => 'hello'
        );
        return View::make('home.twig', $data);
    }
}

