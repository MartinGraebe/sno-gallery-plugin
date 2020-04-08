<?php


namespace Includes\PublicView; 

class GALLERY_PUBLIC_VIEW
{
    public function register(){
        add_shortcode( 'snoxel8v_gallery', array($this, 'display_gallery') );
    }
    function display_gallery($attr){
           
        
            $atts = shortcode_atts( 
                array(
                    'id' => null
                ),
                $attr, 'snoxel8v_gallery' );
             // if style & script are not enqueued yet do so
            if ( ! wp_style_is( 'sno-gallery-plugin-public-style', $list = 'enqueued' )) { wp_enqueue_style( 'sno-gallery-plugin-public-style'); }
            if ( ! wp_script_is( 'sno-gallery-plugin-public-script', $list = 'enqueued' )) { wp_enqueue_script( 'sno-gallery-plugin-public-script' ); }
            $id = $atts['id'];
            $transient = 'snoxel8v_gallery_transient'.$id;
            // CREATE TRANSIENT WITH POST ID => EMPTY TRANSIENT ON POST UPDATE
            if ( false === ($html = get_transient( $transient ))){ // If there is no transient saved execute code and create transient
                $css_id_main = 'snoxel8v-gallery-main-image-'.$id; // create #id selectors with the post id
                $css_id_active = 'snoxel8v-gallery-active-link-'.$id;
                $css_id_gallery = 'snoxel8v-gallery-gallery-'.$id;
                $js_instance = 'snoxel8v_class_instance_'.$id;
                
                $title = esc_html( get_the_title( $id ) );
                $main_image = get_post_meta( $id, 'snoxel8v_main_image', true );
                if ($main_image == ''){ return;} // if no main image is selected don't display anything 
                $gallery = get_post_meta( $id, 'snoxel8v_gallery_gallery', true );
                $main_image_id = attachment_url_to_postid( $main_image );
                $gallery_id_array = explode(',', $gallery);
                $active_thumb_url = wp_get_attachment_image_src( $main_image_id, 'thumbnail' );
                $active_thumb_html = '<a  id="'.$css_id_active.'" href='.$main_image.'><img src='.$active_thumb_url[0].' alt="'.$title.'-image"></a>';
                $gallery_html = '<div id="'.$css_id_gallery.'" class="snoxel8v-sub-gallery">'.$active_thumb_html;
                
                $main_image_html = '<img id="'.$css_id_main.'" alt="'.$title.'-image" src='.$main_image.' >';
                if ($gallery === ''  ){ return $main_image_html;} // if there are no images in the gallery besides the main image just return the html for the main image
                if (count($gallery_id_array) > 0 && $gallery_id_array[0] != null){
                    foreach ($gallery_id_array as $image){
                        $thumb = wp_get_attachment_image_src( $image, 'thumbnail' );
                        $src = wp_get_attachment_image_src( $image, 'full' );
                        $galleryalt = get_the_title( $image );
                        $gallery_html.='<a href='.$src[0].'><img src='.$thumb[0].' alt="'.$galleryalt.'"></a>';
                    }

                }
                $gallery_html .='</div>';

                $html='<div class="snoxel8v-gallery">';
                $html.=    $main_image_html.$gallery_html;
                $html.='</div>';
                set_transient( $transient, $html, DAY_IN_SECONDS );
                return $html;
            }
            else return $html; // else return html saved in transient
            
           
            
        }
}