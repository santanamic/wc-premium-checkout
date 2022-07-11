<?php 

namespace WPC;

final class Main_Plugin 
{
	use Singleton;
	
	public $addons;
	public $controls;
	
	private function __construct()
	{
		$this->addons   = new Load_Addons();
		$this->controls = new Register_Controls();		
	}

}