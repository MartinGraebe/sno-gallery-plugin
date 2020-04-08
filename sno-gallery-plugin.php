<?php
/**
 * Plugin Name: Sno Gallery Plugin
 * Description: A very basic gallery plugin for wordpress
 * Version: 1.0
 * Author: Martin Graebe
 * Licence: GPL v2 or later
 */


if (!defined('ABSPATH')){
    die();
}

if (file_exists( dirname(__FILE__). '/vendor/autoload.php')) {
    require_once dirname(__FILE__). '/vendor/autoload.php';
}

use Includes\AdminView\GALLERY_ADMIN_VIEW;
use Includes\PublicView\GALLERY_PUBLIC_VIEW;

define( 'SNO_GALLERY_PLUGIN_VERSION', '1.0.0' );

class SNO_GALLERY_PLUGIN_XEL18V {
    function __construct () {
        add_action( 'init', array($this, 'setup_gallery_cpt') );
        
    }
    function register(){
        add_action( 'admin_enqueue_scripts', array($this, 'admin_enqueue') );
        add_action( 'wp_enqueue_scripts', array($this, 'public_enqueue') );
    }

   function admin_enqueue(){
       wp_enqueue_media();
       wp_enqueue_script('media-upload');
       wp_enqueue_style( 'sno-gallery-plugin-admin-style', plugins_url( '/admin/css/admin.css', __FILE__ ) );
       wp_enqueue_script( 'sno-gallery-plugin-admin-script', plugins_url( '/admin/js/admin.min.js', __FILE__ ) );
      
   }
   function public_enqueue(){
    wp_register_style( 'sno-gallery-plugin-public-style', plugins_url( '/public/css/public.css', __FILE__ ) );
    wp_register_script( 'sno-gallery-plugin-public-script', plugins_url( '/public/js/public.min.js', __FILE__ ) , array(), '1.0.0', true);
   }
   function activate(){
    
     flush_rewrite_rules();


 }
 function deactivate(){
    
     flush_rewrite_rules();
 }

    function setup_gallery_cpt(){
    
        register_post_type( 'snoxel8v_cpt', 
                                array(
                                    'labels' => array(
                                            'name'         => __('Galleries'),
                                            'single_name'  => __('Gallery'),
                                    ),
                                    'public' => true,
                                    'supports' => array('title'),
                                    'show_in_menu' => true,
                                    'has_archive' => false,
                                ) 
                            );
      
    }
}

if (class_exists('SNO_GALLERY_PLUGIN_XEL18V')){
    $sno_gallery_plugin = new SNO_GALLERY_PLUGIN_XEL18V();
    $sno_gallery_plugin->register();

    // ADD CPT META BOXES
    $adminview = new GALLERY_ADMIN_VIEW();
    $adminview->register();
    // ADD Shortcode
    $publicview = new GALLERY_PUBLIC_VIEW();
    $publicview->register();
}


// ACTIVATION
register_activation_hook( __FILE__, array($sno_gallery_plugin, 'activate' ));

// DEACTIVATION
register_deactivation_hook( __FILE__, array($sno_gallery_plugin, 'deactivate' ) );


// add uninstall


