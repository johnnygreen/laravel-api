<?php namespace Johnnygreen\LaravelApi;

class LaravelApi {

	private $version = '1.0.0';

	public function __construct()
	{
		
	}
	
	public function getVersion()
	{
		return $this->version;
	}

}