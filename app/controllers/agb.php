<?php

class agb extends Controller
{
    public function index()
    {
        $this->f3->set('isHomePage', false);
        $this->f3->set('title', 'Agb');
        $this->f3->set('view', 'agb.html');
        $this->f3->set('classfoot', 'agb');
        // ADD JS
        // $addscripts = 'js/layout/agb.js';
        // $this->f3->set('addscripts', array($addscripts));
    }
}
