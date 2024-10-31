<?php

/*

Plugin Name: Post View Count

Description: allows you to display how many times a post, page or custom post type had been viewed in a simple, fast and reliable way.

Author: Geek Code Lab

Version: 1.4

Author URI: https://geekcodelab.com/

*/

if ( ! defined( 'ABSPATH' ) ) exit;

require_once( plugin_dir_path( __FILE__ ) . 'functions.php' );

add_action('admin_menu', 'pwcgk_admin_menu_post_view');
add_action( 'admin_enqueue_scripts', 'pwcgk_enqueue_styles_scripts_post_views' );
//---------------------------------------------------------------//

function pwcgk_enqueue_styles_scripts_post_views()

{

    if( is_admin() ) {              

        $css= plugins_url() . '/'.  basename(dirname(__FILE__)) . "/css/admin.css";               

        wp_enqueue_style( 'main-post-view-css', $css );

    }

}
//---------------------------------------------------------------



function pwcgk_admin_menu_post_view() {

	add_options_page('Post View Counter', 'Post View Counter', 'manage_options', 'post-view-counter-option', 'pwcgk_options_menu_view'  );

}

//---------------------------------------------------------------//

function pwcgk_options_menu_view() {

	
	if (!current_user_can('manage_options'))  {

			wp_die( __('You do not have sufficient permissions to access this page.') );

		}

		
      include( plugin_dir_path( __FILE__ ) . 'options.php' );

	

}

//---------------------------------------------------------------//

// Add it to a column in WP-Admin
$post_types=pwcgk_page_view_counter_post_types();
if(isset($post_types) && !empty($post_types)){
	foreach($post_types as $post_type)
	{
		
	add_filter('manage_'.$post_type.'_posts_columns', 'pwcgk_posts_column_views');
	add_action('manage_'.$post_type.'_posts_custom_column', 'pwcgk_posts_custom_column_views',5,2);
	if($post_type=='page')
	{
		add_filter( $post_type.'_template', 'pwcgk_get_custom_post_type_template' );		
	}
	else
	{
		add_filter( 'single_template', 'pwcgk_get_custom_post_type_template' );		
	}

	}
}

function pwcgk_posts_column_views($defaults){
    $defaults['post_views_r'] = __('Views');
    return $defaults;
}
function pwcgk_posts_custom_column_views($column_name, $id){
	if($column_name === 'post_views_r'){
        echo pwcgk_getPostViews(get_the_ID());
    }
}

// Update page view
function pwcgk_get_custom_post_type_template($single_template) {
		global $post;
		$roles=pwcgk_page_view_counter_role_types();
		$current_role=wp_get_current_user()->roles[0];
			if(empty($roles))
			  { 	
					pwcgk_setPostViews(get_the_ID());  
			  }
			  else
			  {
				if(!in_array($current_role,$roles))
				 {
					pwcgk_setPostViews(get_the_ID());  
				 }
			  }
}
		    
// Add views to post edit screen
add_action( 'post_submitbox_misc_actions','pwcgk_display_post_views_meta');
function pwcgk_display_post_views_meta(){
	$post_types=pwcgk_page_view_counter_post_types();
	global $post, $pagenow, $typenow;
	if(count($post_types)>0 && !empty($post_types)){
	if(in_array($post->post_type,$post_types))
		{
			?>
			<div class="misc-pub-section curtime misc-pub-curtime">
			<span id="post_views_count">
			<?php _e( 'Views:', 'post-hit-counter' ); ?>
			<strong><?php echo pwcgk_getPostViews(get_the_ID()); ?></strong></span>
			</div>
			<?php
		}
			
	}		
}

function pwcgk_plugin_add_settings_link( $links ) {	
	$settings_link = '<a href="admin.php?page=post-view-counter-option">' . __( 'Settings' ) . '</a>'; 
	array_push( $links, $settings_link );	
	return $links;	
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'pwcgk_plugin_add_settings_link');

?>