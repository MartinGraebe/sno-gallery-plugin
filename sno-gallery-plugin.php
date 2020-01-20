<?php
/**
 * Plugin Name: Sno Gallery Plugin
 * Description: A very basic gallery plugin for wordpress
 * Version: 0.1
 * Author: Martin Graebe
 * Licence: GPL v2 or later
 */


if (!defined('ABSPATH')){
    die();
}
 



function snoxel8v_setup_gallery_options(){
    
    register_post_type( 'snoxel8v_cpt', 
                            array(
                                'labels' => array(
                                        'name'         => __('Galleries'),
                                        'single_name'  => __('Gallery'),
                                ),
                                'public' => true,
                                'show_in_menu' => true,
                                'has_archive' => false,
                            ) 
                        );
  
}
add_action( 'init', 'snoxel8v_setup_gallery_options' );
function snoxel8v_unsetup_gallery_options(){
    unregister_post_type( 'snoxel8v_cpt' );
    flush_rewrite_rules();
}

function snoxel8v_install(){
    snoxel8v_setup_gallery_options();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'snoxel8v_install' );

function snoxel8v_uninstall(){
    snoxel8v_unsetup_gallery_options();
}
register_deactivation_hook( __FILE__, 'snoxel8v_uninstall' );