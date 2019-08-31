<?php 
//Register Menu
function create_db_igs()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'ig_scrape';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE " . $table_name . "(
                id INT unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
                image_url varchar(255) NOT NULL,
                image_link TEXT NULL,
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
    update_option('ig_type', $_POST['ig_type']);
    update_option('ig_username', $_POST['ig_username']);
    update_option('ig_password', $_POST['ig_password']);
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
    add_submenu_page('ig-scrapper', 'Test Mode', 'Test Menu', 'manage_options', 'test', 'test_ig_view');
}

function LoadViews($name, $data = array()) 
{
    extract($data);
    include "view/$name.php";
}
function test_ig_view()
{
	LoadViews('Test');
}
function main_ig_view()
{
	LoadViews('main');
}