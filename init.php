<?php 
	function login_with_creds($username, $password)
	{
		$ig = \InstagramScraper\Instagram::withCredentials($username, $password, dirname(__FILE__).'/tmp/cache/');
		return $ig->login(true);
	}