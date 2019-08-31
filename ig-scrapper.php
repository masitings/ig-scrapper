<?php
/*
Plugin Name: Instagram Scrapper (Developer Version)
Plugin URI: https://semanthic.com/
Description: Plugin for scrape feed from instagram.
Version: 1.0.0
Author: Fixx Digital
Author URI: https://fixxdigital.com
License: Fixx Digital.
*/
include dirname(__FILE__) . '/vendor/autoload.php';
include dirname(__FILE__) . '/init.php';
include dirname(__FILE__) . '/activation.php';


register_activation_hook( __FILE__, 'create_db_igs' );
register_deactivation_hook( __FILE__, 'delete_db_igs' );

add_action('admin_menu', 'ig_scrape_menu');
