<?php namespace Johnnygreen\LaravelApi;

class Api {

	private $version = '1.0.0';

	public function __construct()
	{
		
	}
	
	public function getVersion()
	{
		return $this->version;
	}

}