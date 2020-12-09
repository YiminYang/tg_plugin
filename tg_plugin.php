<?php
/**
 * Plugin Name: TG_Plugin
 * Description: Add Custom Post Type.
 * Version: 1.0
 * Author: Yimin Yang
 */
 
//Add Post Type
function add_post_type() {
	$labels = array(
            'labels' => array(
                'name' => __( 'Reviews' ),
				'all_items' => 'All Reviews',
                'singular_name' => __( 'Edit Reviews' )
            ),
            'has_archive' => true,
            'public' => true,
            'show_in_rest' => true,
			'publicly_queryable' => true,
            'supports' => array( 'title', 'editor' )
        );
	
    register_post_type( 'reviews', $labels );
}
add_action( 'init', 'add_post_type' );

//Add Custom Meta
function reviews_add_custom_box() {
	add_meta_box(
		'reviews_title',                
		'Add reviews title',      
		'get_reviews_title', 
		'reviews'                           
	);
}
add_action( 'add_meta_boxes', 'reviews_add_custom_box' );

//Show Meta On Edit Page
function get_reviews_title( $post ) {
    $value = get_post_meta( $post->ID, 'review_title_key', true);
    ?>
    <label for="review_title">Add reviews Title</label>
	<input type="text" name="review_title" id="review_title" value="<?php echo $value; ?>">
    <?php
}

//Update Meta Value
function reviews_title_save_postdata( $post_id ) {
    if ( array_key_exists( 'review_title', $_POST ) ) {
        update_post_meta(
            $post_id,
            'review_title_key',
            $_POST['review_title']
        );
    }
}
add_action( 'save_post', 'reviews_title_save_postdata' );


//Register Meta Field To API
add_action( 'rest_api_init', 'create_api_posts_meta_field' );

function create_api_posts_meta_field() {
    register_rest_field( 'reviews', 'title_of_reviews', array(
           'get_callback' => 'slug_get_field'
        )
    );
}

function slug_get_field($object, $field_name, $request) {
  return get_post_meta( $object['id'], 'review_title_key', true);
}
