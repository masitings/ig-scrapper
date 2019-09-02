<?php 
//Register Menu
function create_db_igs()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'ig_scrape';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE " . $table_name . "(
                id INT unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
                type varchar(255) NOT NULL,
                image_url varchar(255) NOT NULL,
                image_link TEXT NULL,
                comment INT NULL,
                caption TEXT NULL,
                username varchar(255) NOT NULL
                )";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        add_option('competition_database_version', '1.0');
    }
}

function update_configuration()
{
    foreach ($_POST as $key => $value) {
        update_option( $key, $value );
    }
    
    return true;
}

function delete_db_igs()
{
     global $wpdb;
     $table_name = $wpdb->prefix . "ig_scrape";
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);
}

function ig_scrape_menu()
{
    add_menu_page('IG Scrapper', 'IG Scrapper', 'manage_options', 'ig-scrapper', 'main_ig_view', 'dashicons-instagram');
    // add_submenu_page('ig-scrapper', 'Test Mode', 'Test Menu', 'manage_options', 'test_igs', 'test_ig_view');
}

function LoadViews($name, $data = array()) 
{
    extract($data);
    include "view/$name.php";
}

function test_ig_view()
{
	LoadViews('test');
}

function main_ig_view()
{
	LoadViews('main');
}

// Cron Job
function wp_pinterin_schedules($schedules) {

    $schedules['weekly'] = array(
        'interval' => 10080,
        'display' => __('Once Weekly')
    );

    $schedules['monthly'] = array(
        'interval' => 43800,
        'display' => __('Once Monthly')
    );

    return  $schedules;
}

add_filter ( 'cron_schedules', 'wp_pinterin_schedules' );


// if (! wp_next_scheduled ( 'wp_ig_scrape_hook' )) {
    $interval = get_option('ig_interval', 'daily');
    wp_schedule_event ( time (), $interval, 'wp_ig_scrape_hook' );
// }
add_action ( 'wp_ig_scrape_hook', 'process_scrape' );


