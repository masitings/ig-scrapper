<?php 

class IGScrapper
{
	private function init()
	{
		$type = get_option('ig_type', '');
		$username = get_option('ig_username', '');
		$password = get_option('ig_password', '');
		if ($type === 'private') {
			if ($username !== '' && $password !== '') {
				$website = str_replace(['http://', 'https://', '/'], '', site_url());
				$cache_path = dirname(__FILE__).'/tmp/cache/'.$website.'/';
				$ig = \InstagramScraper\Instagram::withCredentials($username, $password, $cache_path);
				if (!file_exists($cache_path)) {
					$ig->login(true);
				}
				return $ig;
			} else {
				return false;
			}
		} elseif ($type === 'public') {
			$ig = new \InstagramScraper\Instagram();
			return $ig;
		} else {
			return false;
		}
		
	}

	public function get_account_by_username($username)
	{
		$init = $this->init();
		if ($init) {
			return $init->getAccount($username);
		} else {
			return false;
		}
	}
}