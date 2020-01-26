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
 

define( 'SNO_GALLERY_PLUGIN_VERSION', '1.0.0' );

class SNO_GALLERY_PLUGIN_XEL18V {
    function __construct () {
        add_action( 'init', array($this, 'setup_gallery_cpt') );
    }
    function activate(){
       $this->setup_gallery_cpt();
        flush_rewrite_rules();
  

    }
    function deactivate(){
       
        flush_rewrite_rules();
    }
    function uninstall(){
        
    }


    function setup_gallery_cpt(){
    
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
}

if (class_exists('SNO_GALLERY_PLUGIN_XEL18V')){
    $sno_gallery_plugin = new SNO_GALLERY_PLUGIN_XEL18V();
}

register_activation_hook( __FILE__, array($sno_gallery_plugin, 'activate' ));


register_deactivation_hook( __FILE__, array($sno_gallery_plugin, 'deactivate' ) );


// add uninstall


