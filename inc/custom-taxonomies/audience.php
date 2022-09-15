<?php
function wh_audience() {

	$labels = array(
		'name'                       => _x( 'Audiences', 'Taxonomy General Name', 'westernhealth' ),
		'singular_name'              => _x( 'Framework Audience', 'Taxonomy Singular Name', 'westernhealth' ),
		'menu_name'                  => __( 'Audiences', 'westernhealth' ),
		'all_items'                  => __( 'All audiences', 'westernhealth' ),
		'parent_item'                => __( 'Parent Audience', 'westernhealth' ),
		'parent_item_colon'          => __( 'Parent Audience:', 'westernhealth' ),
		'new_item_name'              => __( 'New Audience', 'westernhealth' ),
		'add_new_item'               => __( 'Add New Audience', 'westernhealth' ),
		'edit_item'                  => __( 'Edit Audience', 'westernhealth' ),
		'update_item'                => __( 'Update Audience', 'westernhealth' ),
		'view_item'                  => __( 'View Audience', 'westernhealth' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'westernhealth' ),
		'add_or_remove_items'        => __( 'Add or remove categories', 'westernhealth' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'westernhealth' ),
		'popular_items'              => __( 'Popular Audiences', 'westernhealth' ),
		'search_items'               => __( 'Search Categories', 'westernhealth' ),
		'not_found'                  => __( 'Not Found', 'westernhealth' ),
		'no_terms'                   => __( 'No categories', 'westernhealth' ),
		'items_list'                 => __( 'Audiences list', 'westernhealth' ),
		'items_list_navigation'      => __( 'Audience list navigation', 'westernhealth' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'audience', array('feedback', 'training', 'projects', 'post','news'), $args );

}
add_action( 'init', 'wh_audience', 0 );