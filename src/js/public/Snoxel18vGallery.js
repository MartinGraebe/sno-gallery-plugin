class Snoxel8vGallery {
    constructor(galleryID){
        this.galleries = document.querySelectorAll(`${galleryID}`); // get all elements with the partial id
     
     
        if (this.galleries.length > 0){
            Array.prototype.forEach.call(this.galleries, this.loop); // for each of these element execute loop function callback
        }
        
    }
    
    loop(element, iterator){
        
        const cutId = element.id.split('-'); // split post id from #id selector
        const id = cutId[cutId.length-1];
      
      
        const main_image_id = 'snoxel8v-gallery-main-image-'+id;  // recreate #ids to be looked for for this gallery post instance
        const active_link_id = 'snoxel8v-gallery-active-link-'+id;
        
        const gallery = document.getElementById(`${element.id}`);  // find gallery div
        const mainImage = document.getElementById(`${main_image_id}`); // find main image
        let links;
        if (gallery !== null){
            links = gallery.getElementsByTagName('a'); // if there is a gallery find the links of the gallery images
           }
        let activeLink = document.getElementById(`${active_link_id}`); // find a element with active  link #id
       
        if (links.length > 0 && mainImage != null && activeLink != null ) { // if there is links and a  main image exists and an active link exists
           
            Array.from(links).forEach((el) => {          // add event listener to each link 
      
                el.addEventListener('click', (ev) => {
                   
                    ev.preventDefault();  // prevent default behaviour
                 
                    if(el.href !== activeLink.href){  // if the link that is clicked on is not the active link 
                        mainImage.src = el.href; // set the clicked on thumbnail link to be the main image and active link 
                        el.setAttribute( 'id', `${active_link_id}`);
                        activeLink.removeAttribute('id');
                        activeLink = el;
                        mainImage.scrollIntoView(); // focus view on the new main image

                    } 
                });
        })
          }
    }

    
  
   
    
    
 

}

export default Snoxel8vGallery;