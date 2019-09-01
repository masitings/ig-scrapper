<?php 
	function get_account_medias($username)
	{
		$init = new IGScrapper();
		$ig = $init->get_account_by_username($username);
		if ($ig != false) {
			if ($ig['isPrivate']) {
				$result = [
					'status'	=> 500,
					'msg'		=> 'The account was private! Cannot do the scrape'
				];
			} else {
				$result = [
					'status'	=> 200,
					'msg'		=> 'Success',
					// 'data'		=> $ig['medias']
					'total'		=> count(parsing_media($ig['medias'])),
					'data'		=> parsing_media($ig['medias'])
				];
			}
		} else {
			$result = [
				'status'	=> 500,
				'msg'		=> 'Cannot do login things'
			];
		}
		return $result;
	}

	function tests()
	{
		global $wpdb;
		return $wpdb->print_error();
	}

	function process_scrape()
	{
		$username = get_option('ig_username');
		$scrape = get_account_medias($username);
		$arr = [];
		if ($scrape['status'] == 200) {
			delete_data_on_db();
			foreach ($scrape['data'] as $key => $value) {
				$arr[$key] = save_to_db($value, $username);
			}
			if ($scrape['total'] == count($arr)) {
				update_option('last_updates_ig', date('F jS, Y H:i:s'));
			}
			$result = true;	
		} else {
			$result = $scrape;
		}
		return $result;
	}

	function delete_data_on_db()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'ig_scrape';
		$check = $wpdb->get_results("SELECT COUNT(*) AS total FROM $table_name");
		if ($check[0]->total > 0) {
			$data = $wpdb->get_results("DELETE FROM $table_name");
		} else {
			$data = true;
		}
		return $data;
	}

	function save_to_db($post, $username)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'ig_scrape';
		$args = [
			'type'			=> $post['type'],
			'image_url'		=> $post['image'],
			'image_link'	=> $post['link'],
			'comment'		=> (int)$post['total_comment'],
			// 'caption'		=> $post['caption'],
			'username'		=> $username
		];

		if ($wpdb->insert($table_name, $args)) {
			return true;
		} else {
			return false;
		}

	}

	function serve_ig_scrape()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'ig_scrape';
		$check = $wpdb->get_results("SELECT COUNT(*) AS total FROM $table_name");
		if ($check[0]->total > 0) {
			$data = $wpdb->get_results("SELECT * FROM $table_name");
			$result = $data;
		} else {
			$result = false;
		}
		return $result;
	}

	function parsing_media($media)
	{
		$arr = [];
		$i = 1;
		$limit = get_option('ig_limit', 10);
		foreach ($media as $key => $value) {
			$arr[$key]['type'] = $value['type'];
			$arr[$key]['link'] = $value['link'];
			$arr[$key]['image'] = $value['imageHighResolutionUrl'];
			$arr[$key]['caption'] = $value['caption'];
			$arr[$key]['total_comment'] = $value['commentsCount'];
			if ($i == $limit) { break; }
			$i++;
		}
		return $arr;
	}