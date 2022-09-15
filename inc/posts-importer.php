<?php
/**
 * Create a ACF options page used for importing Posts from external sites
 **/

if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Posts Puller',
		'menu_title'	=> 'Posts Puller',
		'menu_slug' 	=> 'post-importer',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
}

add_action('admin_head', 'gc_post_importer_styles');
function gc_post_importer_styles () {

    wp_enqueue_style('admin_styles' , get_template_directory_uri().'/inc/assets/css/post-importer.css', '', '2.2' );
    wp_enqueue_script('admin_scripts' , get_template_directory_uri().'/inc/assets/js/post-importer.js', '', '2.2' ); // increment these with new APP.JS
}

add_action('wp_ajax_gc_pull_posts', 'gc_pull_posts');
add_action('wp_ajax_nopriv_gc_pull_posts', 'gc_pull_posts');

function gc_pull_posts() {
    // create posts
    $site = $_POST['site'];
    $type = $_POST['type'];
    $id = $_POST['id'];
    $is_imported = $_POST['is_imported'];

    $apiurl = 'http://' . $site . '/wp-json/wp/v2/' . $type . '/' . $id;
    //echo $apiurl.'DHANOA';exit;
    $request = file_get_contents($apiurl);
    $json_decoded_request = json_decode( $request );

    if(!is_null($json_decoded_request)) {

				if ( $type == 'posts' ) {

					$postarr = array(
	            'post_type'     => 'bulletins',
	            'post_title'    => 'IMPORTED - ' . $json_decoded_request->title->rendered,
	            'post_content'  => $json_decoded_request->content->rendered,
	            'tax_input'     => array('departments' => $json_decoded_request->departments),
	            'featured'      => $json_decoded_request->departments,
	            'post_date' 		=> $json_decoded_request->date,
	            'post_date_gmt' => $json_decoded_request->date_gmt,
	        );

				} else {

					$postarr = array(
	            'post_type'     => $type,
	            'post_title'    => 'IMPORTED - ' . $json_decoded_request->title->rendered,
	            'post_content'  => $json_decoded_request->content->rendered,
	            'tax_input'     => array('departments' => $json_decoded_request->departments),
	            'featured'      => $json_decoded_request->departments,
	            'post_date' 		=> $json_decoded_request->date,
	            'post_date_gmt' => $json_decoded_request->date_gmt,
	        );

				}

        //create the post
        $new_id = wp_insert_post($postarr);

        // add featured image to the newly created post
        $image_api_link = json_decode(json_encode($json_decoded_request), true)['_links']['wp:featuredmedia'][0]['href'];
        if(! $image_api_link == "" ) {
            $image_request = file_get_contents($image_api_link);
            $decoded_image_data = json_decode($image_request, true);
            $image_url = $decoded_image_data['source_url'];
            $upload_dir = wp_upload_dir();
            $image_data = file_get_contents( $image_url );
            $filename = basename( $image_url );

            $file = ( wp_mkdir_p( $upload_dir['path'] ) ) ? $upload_dir['path'] . '/' . $filename : $upload_dir['basedir'] . '/' . $filename;

            file_put_contents( $file, $image_data );
            $wp_filetype = wp_check_filetype( $filename, null );

            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => sanitize_file_name( $filename ),
                'post_content' => '',
                'post_status' => 'inherit'
            );

            $thumbnail_id = wp_insert_attachment( $attachment, $file, $new_id, true );
            $attach_data = wp_generate_attachment_metadata( $thumbnail_id, $image_url );
            wp_update_attachment_metadata( $thumbnail_id,  $attach_data );
            set_post_thumbnail($new_id, $thumbnail_id);
        }

        // after post is created, get it and add in the custom data that can't be added via wp_insert_post
        if($type === 'bulletins') {
            $post_cats = $json_decoded_request->bulletin_category;
            $category_request = file_get_contents('http://' . $site . '/wp-json/wp/v2/bulletin_category/');
            $category_data = json_decode($category_request, true);

            foreach($post_cats as $post_cat) {
                foreach($category_data as $cat) {
                    if($post_cat === $cat['id']) {
                        $category_slugs[] = $cat['slug'];
                    }
                }
            }

            wp_set_object_terms( $new_id, $category_slugs, 'bulletin_category');
        } elseif($type === 'news') {
            $post_cats = $json_decoded_request->news_category;
            $category_request = file_get_contents('http://' . $site . '/wp-json/wp/v2/news_category/');
            $category_data = json_decode($category_request, true);

            foreach($post_cats as $post_cat) {
                foreach($category_data as $cat) {
                    if($post_cat === $cat['id']) {
                        $category_slugs[] = $cat['slug'];
                    }
                }
            }

            wp_set_object_terms( $new_id, $category_slugs, 'news_category');
        } elseif($type === 'events') {
            $post_cats = $json_decoded_request->event_location;
            $category_request = file_get_contents('http://' . $site . '/wp-json/wp/v2/event_location/');
            $category_data = json_decode($category_request, true);

            foreach($post_cats as $post_cat) {
                foreach($category_data as $cat) {
                    if($post_cat === $cat['id']) {
                        $category_slugs[] = $cat['slug'];
                    }
                }
            }

            wp_set_object_terms( $new_id, $category_slugs, 'event_location');

        } elseif($type === 'posts') {
            $post_cats = $json_decoded_request->default_category;
            $category_request = file_get_contents('http://' . $site . '/wp-json/wp/v2/default_category/');
            $category_data = json_decode($category_request, true);

            foreach($post_cats as $post_cat) {
                foreach($category_data as $cat) {
                    if($post_cat === $cat['id']) {
                        $category_slugs[] = $cat['slug'];
                    }
                }
            }

            wp_set_object_terms( $new_id, $category_slugs, 'default_category');
        }


        $acfs = json_decode(json_encode($json_decoded_request), true)['acf'];

					//var_dump($json_decoded_request);
					//var_dump($acfs);

        // check if is internaly or externaly linked
        // the update either all the ACF fields, or just the required ones


        if($is_imported === 'internal') {
            update_field('link_post_to', 1, $new_id);
            foreach($acfs as $key => $value) {
                update_field($key, $value, $new_id);
            }
						update_field('enter_what_is_the_external_url', $json_decoded_request->link, $new_id);
        } else {
            update_field('link_post_to', 0, $new_id);
            update_field('enter_what_is_the_external_url', $json_decoded_request->link, $new_id);
            foreach($acfs as $key => $value) {
                if($key === 'excerpt' || $key === 'banner_heading' || $key === 'heading') {
                    update_field($key, $value, $new_id);
                }
            }
        }

        wp_die();


    } // end create posts

    die();
}



add_action('acf/save_post', 'gc_import_posts', 20);
function gc_import_posts( $post_id ) {
    $screen = get_current_screen();
    if (strpos($screen->id, "post-importer") == false) {
        return;
    }

    $selected_site = get_field('site','options');
    if($selected_site == 'please select a site') {
        return;
    }

    $url = $_SERVER['REQUEST_URI'] . '&site=' . $selected_site;
    wp_redirect($url);
    exit;
}



add_action('admin_menu', 'gc_get_remote_posts');
function gc_get_remote_posts() {
    if(array_key_exists('page',$_GET) && $_GET['page'] !== 'post-importer') {
        return;
    }

    if (array_key_exists('site', $_GET)) {
        $site = $_GET['site'];
        $args = array('site' => $site);
        get_template_part( 'inc/posts-importer-post_loop', '', $args );
    }
}



// post importer ajax request
function function_name () {
    // Ajax request
}
