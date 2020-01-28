

jQuery(document).ready(function() {
    var image_modal;
    // Jquery on image button click
    jQuery('#snoxel8v_main_image_button').click(function(e){
       
        e.preventDefault();

        // check if media modal already  exists if yes re open it
        if (image_modal){
            image_modal.open();
            return;
        }
        // setup media modal
        image_modal = wp.media({
            title: snoxel8v_main_image.title,
            button: { text: snoxel8v_main_image.button },
            library: { type: 'image'},
        });

        // on select
        image_modal.on('select', function(){
            //  Creates JSON of selection
            var attachment = image_modal.state().get('selection').first().toJSON();
            
            // add attachment url to custom hidden image input field
            jQuery('#snoxel8v_main_image').val(attachment.url);
            // add closing / deleting x to image
            jQuery('.snoxel8v-main-image-container').append('<span class="snoxel8v-delete-button"></span>');

            //if the image is smaller than the thumbnail size use the main URL otherwise attach the thumbnail URL
            if (typeof attachment.sizes.thumbnail === 'undefined'){
                jQuery('#snoxel8v_main_image_src').attr('src', attachment.url);
            }
            else{
                jQuery('#snoxel8v_main_image_src').attr('src', attachment.sizes.thumbnail.url);
            }
        });

        // open modal
        image_modal.open();
        
    });
    var gallery_modal;
    jQuery('#snoxel8v_gallery_gallery_button').click(function(e){
        e.preventDefault();

        if (gallery_modal){
            gallery_modal.open();
            return;
        }

        // setup gallery modal 
        gallery_modal =  wp.media({
            title: 'Media Library',
            button: { text: 'SELECT' },
            library: { type: 'image'},
            multiple: 'add' // 'add' instead of true actually works
         

        })

        // add gallery state
   /*      gallery_modal.states.add([
            new wp.media.controller.Library ({
                id: 'snoxel8v-gallery',
                title: 'Select your Images',
                priority: 200,
                toolbar: 'main-gallery',
                filterable: false,
                library: wp.media.query(gallery_modal.options.library),
                multiple: 'add',
                editable: true,
                allowLocalEdits: true,
                displaySettings: false,
                displayUserSettings: true,
                syncSelection: true

            }),
           
        ]); */

        gallery_modal.on('open', function(){
            var images = gallery_modal.state().get('selection');
            var lib = gallery_modal.state('gallery-edit').get('library');
            var ids = jQuery('#snoxel8v_gallery_gallery').val();
            var idArray;
            if(ids){
                idArray = ids.split(',');
                idArray.forEach(function(id){
                    var attachment = wp.media.attachment(id);
                    attachment.fetch();
                    images.add(attachment ? [ attachment ] : []);
                });
            }
        });
        gallery_modal.on('ready', function(){
            jQuery('.media-modal').addClass('no-sidebar');
        });

        // callback when image is selected 
        gallery_modal.on('select', function(){
            var imageIDs = [];
            var imageHtml = '';
            var metaDataString = '';

            var images = gallery_modal.state().get('selection');
            imageHtml += '<div class="snoxel8v_gallery_array">' ;
            images.each(function(attachment){
                
                imageIDs.push(attachment.attributes.id);
                if (typeof attachment.attributes.sizes.thumbnail === 'undefined'){
                    imageHtml += '<div class="snoxel8v-gallery-item"><span class="snoxel8v-gallery-delete-button"><img id="'+attachment.attributes.id+'" src="'+attachment.attributes.url+'"></span></div>';
                } else {
                    imageHtml += '<div class="snoxel8v-gallery-item"><span class="snoxel8v-gallery-delete-button"><img id="'+attachment.attributes.id+'" src="'+attachment.attributes.sizes.thumbnail.url+'"></span></div>';
                }

            });
            
            imageHtml+= '</div>';
            metaDataString = imageIDs.join(',');
           
            if(metaDataString) {
                jQuery('#snoxel8v_gallery_gallery').val(metaDataString);
                jQuery('#snoxel8v_gallery_gallery_src').html(imageHtml);
             
            }
        });
        gallery_modal.open();
    });

    
    // delete main image selection
    jQuery(document).on('click', '.snoxel8v-delete-button', function(e){
      
        e.preventDefault();
        
        if (confirm('Are you sure you want to unselect the main image?')){
            jQuery('.snoxel8v-delete-button').remove();
            jQuery('#snoxel8v_main_image_src').attr('src', '');
            jQuery('#snoxel8v_main_image').val('');
        }
    });

    jQuery(document).on('click', '.snoxel8v-gallery-delete-button', function(e){
        e.preventDefault();

        if (confirm('Are you sure you want to unselect this image?')){

            var remove = jQuery(this).children('img').attr('id');
            var oldGal = jQuery('#snoxel8v_gallery_gallery').val();
            var newGal = oldGal.replace(','+remove,'').replace(remove+',', '').replace(remove,'');
            jQuery(this).remove();
            jQuery('#snoxel8v_gallery_gallery').val(newGal);
        }
    });

});
