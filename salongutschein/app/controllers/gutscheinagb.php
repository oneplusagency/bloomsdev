<?php

class gutscheinagb extends Controller
{
    public function index()
    {
        $this->f3->set('isHomePage', false);
        $this->f3->set('title', 'Gutschein-Agb');
        $this->f3->set('view', 'gutschein-agb.html');
        $this->f3->set('classfoot', 'agb');
        // ADD JS
        // $addscripts = 'js/layout/agb.js';
        // $this->f3->set('addscripts', array($addscripts));
    }
}
