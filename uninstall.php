<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
  exit();

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "flexible_ab_results_campaign");
$wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "flexible_ab_results_page");
$wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "flexible_ab_results_page_display");
