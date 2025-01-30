<?php

class error extends Controller
{
    public function index()
    {
		$this->f3->set('isHomePage',false);
		$this->f3->set( 'title', "Willkommen" );
        $this->f3->set( 'view', 'error.html' );
        $this->f3->set('classfoot', 'error');
    }
}
