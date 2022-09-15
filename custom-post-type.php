<?php
// Register Custom Post Type - Case Studies
function ttbc_case_studies_custom_post_type(){
      
    // Set labels for the custom post type
  
    $labels = array(
                     'name' => 'Case Studies',
                     'singular_name' => 'Case Study',
                     'add_new'    => 'Add Case Study',
                     'add_new_item' => 'Enter Case Study Details',
                     'all_items' => 'All Case Studies',
                     'featured_image' => 'Add Poster Image',
                     'set_featured_image' => 'Set Poster Image',
                     'remove_featured_image' => 'Remove Poster Image'
  
                   );
  
  
    // Set Options for this custom post type;
  
    $args = array(    
                    'public' => true,
                    'label'       => 'Case Studies',
                    'labels'      => $labels,
                    'description' => 'Case Studies is a collection of Case Study and their info',
                    'menu_icon'      => 'dashicons-format-aside',    
                    'supports'   => array( 'title', 'editor', 'thumbnail','excerpt'),
                    'capability_type' => 'page',
                      
                 );
  
    register_post_type('casestudy', $args);
  
}
  
add_action( 'init', 'ttbc_case_studies_custom_post_type' );
  
// Custom Post Type ends here.


  
function ttbc_create_shortcode_case_studies_post_type(){
  
    $args = array(
                    'post_type'      => 'casestudy',
                    'posts_per_page' => '10',
                    'publish_status' => 'published',
                 );
  
    $query = new WP_Query($args);
  
    if($query->have_posts()) :
		$result='';
  		$result .= '<div class="casestudy-container"><div class="content-box-wrapper">';	
        while($query->have_posts()) :
  
            $query->the_post() ;
        
	    $content = wp_strip_all_tags( get_the_excerpt(), true );
		$custom_attach = get_post_meta( get_the_ID(), 'wp_custom_attachment', true );
		$path = removeBasePath($custom_attach['url']);
		
		$result .= '<div class="casestudy-item">';
        $result .= '<div class="casestudy-poster">' . get_the_post_thumbnail() . '</div>';
        $result .= '<div class="casestudy-name"><h2>' . get_the_title() . '</h2></div>';
        $result .= '<div class="casestudy-desc"><p>' . wp_trim_words(get_the_content(),17,'...') . '</p></div>'; 

		$result .= '<a class="btn btn-color-primary btn-style-default btn-style-rectangle btn-size-default"  href="'.get_post_permalink(get_the_ID()).'" target="_blank"> Read more</a>';
		
		$result .= '<a class="btn btn-color-default btn-style-bordered btn-style-rectangle btn-size-default" href="'.get_template_directory_uri().'/inc/downloadpdf.php?pdfpath='. $path .'" target="_blank"> Download</a>';

		
        $result .= '</div>';
		
        endwhile;
  		$result .= '</div></div>';
        wp_reset_postdata();

    endif;    
  
    return $result;            
}
  
add_shortcode( 'case-studies-list', 'ttbc_create_shortcode_case_studies_post_type' ); 

// shortcode code ends here - Case Studies
function removeBasePath($url){
	//$urlParts = parse_url($url);
    return $urlParts['path'];
	//return $url;
}
