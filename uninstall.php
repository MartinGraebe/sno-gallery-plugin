<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$galleries = get_posts( array('post_type' => 'snoxel8v_cpt', 'numberposts' => -1 ) );
foreach ( $galleries as $gallery ) {
    wp_delete_post( $gallery->ID, true );
}
/* $option_name = 'wporg_option';
 
delete_option($option_name);
 
// for site options in Multisite
delete_site_option($option_name);
 
// drop a custom database table
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}mytable"); */