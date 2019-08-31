<?php 
	

	function login_with_creds($username, $password)
	{
		$website = str_replace(['http://', 'https://', '/'], '', site_url());
		$cache_path = dirname(__FILE__).'/tmp/cache/'.$website.'/';

		$ig = \InstagramScraper\Instagram::withCredentials($username, $password, $cache_path);
		if (!file_exists($cache_path)) {
			return $ig->login(true);
		}
		$account = $ig->getAccountById(1000);
		return $account['profilePicUrl'];
	}