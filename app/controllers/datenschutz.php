<?php

class datenschutz extends Controller
{


	public function index()
	{

		$this->f3->set('isHomePage',false);
		$this->f3->set('title', "Datenschutzerklärung");
		$this->f3->set('view', 'datenschutz.html');
		$this->f3->set('classfoot', 'datenschutz');

	}
}
