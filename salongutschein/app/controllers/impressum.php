<?php

class impressum extends Controller
{


	public function index()
	{

		$this->f3->set('isHomePage',false);
		$this->f3->set('title', "Impressum");
		$this->f3->set('view', 'impressum.html');
		$this->f3->set('classfoot', 'impressum');
	}
}
