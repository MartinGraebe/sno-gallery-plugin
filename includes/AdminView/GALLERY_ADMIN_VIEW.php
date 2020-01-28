<?php


namespace Includes\AdminView; 

class GALLERY_ADMIN_VIEW
{
    // create fields for main gallery image & other gallery images
    const CUSTOM_META_FIELDS =  array(
        array(
            'label' => 'Main Image', 
            'desc' =>  'This is the image that is displayed first /n in focus', 
            'id' =>  'snoxel8v_main_image',
            'type' =>  'media'               
            
        ),
        array(
            'label' =>  'Gallery Images', 
            'desc' =>  'These are the images that appear below the main image', 
            'id' =>  'snoxel8v_gallery_gallery',
            'type' =>  'gallery'               
            
            )


        );
    public function register(){
        add_action( 'add_meta_boxes', array($this, 'create_custom_meta_box'));
        add_action( 'save_post', array($this, 'save'));
    }
    public function create_custom_meta_box(){
        add_meta_box( 
            
            'snoxel8v_cpt_meta_box', //id
            'Sno Gallery Fields', // title
             array($this, 'show_meta_box') , // callback
            'snoxel8v_cpt', // page / post type
            'normal', // context
            'high' // priority

            
        );
    }

    function show_meta_box($post){
          
        
            
        
        
        // create hidden nonce field for verification purposes
            echo '<input type="hidden" name="snoxel8v_cpt_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
            ?>
            <div class="snoxel8v-main">
            <?php
            foreach(self::CUSTOM_META_FIELDS as $field){
                $meta = get_post_meta( $post->ID, $field['id'], true );
                echo '<div class="snoxel8v-gallery-sub">
                    <label for="'.$field['id'].'">'.$field['label'].'</label>';
                switch($field['type']){
                    case 'media':
                        $close_button = null;
                        if ($meta) {
                            $close_button = '<span class="snoxel8v-delete-button"></span>';
                        }
                        echo '<input id="snoxel8v_main_image" type="hidden" name="snoxel8v_main_image" value="'.esc_attr( $meta ).'" >
                        <div class="snoxel8v-main-image-container">'.$close_button.'<img id="snoxel8v_main_image_src" src="'.wp_get_attachment_thumb_url(attachment_url_to_postid($meta)).'"></div>
                        <input id="snoxel8v_main_image_button" type="button" value="Add Image">';
                    break;
                    case 'gallery':
                        $gallery_html = null;
                        if( $meta ) {
                            $gallery_html .= '<div class="snoxel8v_gallery_array">';
                            $gallery_array = explode(',', $meta);
                            foreach ($gallery_array as $gallery_item){
                                $gallery_html .= '<div class="snoxel8v-gallery-item"><span class="snoxel8v-gallery-delete-button"><img id="'. esc_attr( $gallery_item ) .'" src="'.wp_get_attachment_thumb_url( $gallery_item ).'"></span></div>';
                            }
                            $gallery_html .= '</div>';

                        }
                        echo '<input id="snoxel8v_gallery_gallery" type="hidden" name="snoxel8v_gallery_gallery" value="'.esc_attr( $meta ).'" >
                        <span id="snoxel8v_gallery_gallery_src">'. $gallery_html .'</span>
                        <div class="snoxel8v_gallery_gallery_button_container"><input id="snoxel8v_gallery_gallery_button" type="button" value="Add Gallery"></div>'; 
                    break;
                        


                }
                
                
              echo '</div>';  
            }
            ?>
            </div>
            <?php
    }

    function save($post_id){
        
        // verify  nonce
        if( isset($_POST['snoxel8v_cpt_meta_box_nonce']) && !wp_verify_nonce( $_POST['snoxel8v_cpt_meta_box_nonce'], basename(__FILE__) )){
            return $post_id;
        }
        // check if autosaving 
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
            return $post_id;
        }
        // check permissions
        if (isset($_POST['snoxel8v_cpt']) && 'page' == $POST['snoxel8v_cpt'] ){
            if (!current_user_can( 'edit_page', $post_id )){
                return $post_id;
            }
        } elseif(!current_user_can( 'edit_page', $post_id )){
            return $post_id;
        }

        // loop through meta fields
        foreach (self::CUSTOM_META_FIELDS as $field){
            if( isset($_POST[$field['id']])){
                $new_value = esc_attr( $_POST[$field['id']] );
            } else $new_value = null;
           
            $meta_key = $field['id'];
            $old_value = get_post_meta( $post_id, $meta_key, true);

            // if old value is empty add new value
            if ($new_value && $old_value == null){
                add_post_meta( $post_id, $meta_key, $new_value, true );
            } // If old value has different content than new value
            elseif($new_value && $new_value != $old_value){
                update_post_meta( $post_id, $meta_key, $new_value, $old_value );
            } // if new value is empty => delete meta 
            elseif($new_value == null && $old_value ){
                delete_post_meta( $post_id, $meta_key, $old_value );
            }
        }
    }
    // ADD META FIELDS => THEN ADD JS
}
