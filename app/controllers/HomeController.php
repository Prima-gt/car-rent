<?php

class HomeController extends Controller
{
    public function index(): string
    {
        return $this->view('home/index');
    }
}

?>

