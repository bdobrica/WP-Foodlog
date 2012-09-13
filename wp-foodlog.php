<?php
/*
Plugin Name: WP FoodLog
Plugin URI: http://foodlog.ro/
Description: Food Log Plugin
Author: Bogdan Dobrica
Version: 0.1
Author URI: http://ublo.ro/
*/

function wp_foodlog () {
	}

function wp_foodlog_cookie () {
	}

function wp_foodlog_firstrun () {
	wp_insert_post ( array (
			'post_title' => 'FoodLog Profile',
			'post_content' => '[fdl-profile]',
			'post_date' => date('Y-m-d H:i:s'),
			'post_status' => 'published',
			'post_type' => 'page',
			));
	wp_insert_post ( array (
			'post_title' => 'Log a Meal',
			'post_content' => '[fdl-log-meal]',
			'post_date' => date('Y-m-d H:i:s'),
			'post_status' => 'published',
			'post_type' => 'page',
			));
	wp_insert_post ( array (
			'post_title' => 'Meals\' Stats',
			'post_content' => '[fdl-meals-stats]',
			'post_date' => date('Y-m-d H:i:s'),
			'post_status' => 'published',
			'post_type' => 'page',
			));
	$iobjs = array (
		new WP_FDL_Nutrient(),
		new WP_FDL_Food(),
		new WP_FDL_Group(),
		new WP_FDL_Meal(),
		new WP_FDL_User());
	foreach ($iobjs as $iobj) $iobj->init();
	}

function wp_foodlog_lastrun () {
	$iobjs = array (
		new WP_FDL_Nutrient(),
		new WP_FDL_Food(),
		new WP_FDL_Group(),
		new WP_FDL_Meal(),
		new WP_FDL_User());
	foreach ($iobjs as $iobj) $iobj->init(FALSE);
	}

function wp_foodlog_admin () {
	add_menu_page ('', '', 'publish_posts', 'wp-foodlog', 'wp-foodlog');
	add_submenu_page ('wp-foodlog', '', '', '', '', '');
	}
function wp_foodlog_scripts () {
	wp_enqueue_script ('wp-foodlog', WP_CRM_URL . '/scripts/wp-foodlog.js', array('jquery'), '0.1');
	wp_enqueue_style  ('wp-foodlog', WP_CRM_URL . '/style/wp-foodlog.css', '0.1');
	}

register_activation_hook (__FILE__, 'wp_foodlog_firstrun');
register_deactivation_hook (__FILE__, 'wp_foodlog_lastrun');

add_action ('get_header', 'wp_foodlog_cookie');

add_action ('admin_enqueue_scripts', 'wp_foodlog_scripts');
add_action ('admin_menu', 'wp_foodlog_admin');
?>
