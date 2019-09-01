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

	function process_scrape()
	{
		$username = get_option('ig_username');
		$scrape = get_account_medias($username);
		$arr = [];
		if ($scrape['status'] == 200) {
			if (delete_data_on_db()) {
				foreach ($scrape['data'] as $key => $value) {
					$arr[$key] = save_to_db($value);
				}
				$result = $arr;	
			} else {
				$result = [
					'status'	=> 500,
					'msg'		=> 'Fail to delete the old data'
				];
			}
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
			$data = $wpdb->get_results("DELETE * FROM $table_name");
		} else {
			$data = true;
		}
		return $data;
	}

	function save_to_db($post)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'ig_scrape';
		$args = [
			'image_url'		=> $post['image'],
			'image_link'	=> $post['link'],
			'comment'		=> $post['total_comment'],
			'caption'		=> $post['caption']
		];
		if ($wpdb->insert($table_name, $args)) {
			return true;
		} else {
			return false;
		}

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