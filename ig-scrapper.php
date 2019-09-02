<?php
/*
Plugin Name: Instagram Scrapper (Developer Version)
Plugin URI: https://semanthic.com/
Description: Plugin for scrape feed from instagram.
Version: 1.0.1
Author: Semanthic
Author URI: https://semanthic.com/
License: Semanthic.
*/

//Updater
require 'updater/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/masitings/ig-scrapper/',
	__FILE__,
	'ig-scrapper'
);
$myUpdateChecker->setBranch('master');

include dirname(__FILE__) . '/vendor/autoload.php';
include dirname(__FILE__) . '/controller/IG_Scrapper.php';
include dirname(__FILE__) . '/init.php';
include dirname(__FILE__) . '/activation.php';


register_activation_hook( __FILE__, 'create_db_igs' );
register_deactivation_hook( __FILE__, 'delete_db_igs' );

add_action('admin_menu', 'ig_scrape_menu');
