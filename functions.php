<?php
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

// function to display number of posts.
function pwcgk_getPostViews($postID){
    $count_key = 'pwcgk_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count;
}

// function to count views.
function pwcgk_setPostViews($postID) {
	$post_type=get_post_type($postID);
    $count_key = 'pwcgk_post_views_count';
	$cookie_value=json_decode($_COOKIE['pwcgk_post_view_count']);
    $count = get_post_meta($postID, $count_key, true);
	//print_r($cookie_value);
    if(!empty($cookie_value))
	{
		if(!in_array($postID,$cookie_value))
		{
			if($count==''){ $count=1;} else { $count++;}
			update_post_meta($postID, $count_key, $count);
			array_push($cookie_value,$postID);
			setcookie('pwcgk_post_view_count', json_encode($cookie_value), time() + (86400 * 30), "/"); // 86400 = 1 day
		}
    }
	else
	{
		$cookie_value=json_encode(array($postID));
		if($count==''){ $count=1;} else { $count++;}
		update_post_meta($postID, $count_key, $count);
		setcookie('pwcgk_post_view_count', $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
	}
}

// Get post type
function pwcgk_page_view_counter_post_types()
{
	return get_option('pwcgk_post_view_count_post_type');
}

// Get role
function pwcgk_page_view_counter_role_types()
{
	return get_option('pwcgk_post_view_count_role_type');
}

// error message
function pwcgk_failure_option_msg($msg)
{	
	echo  '<div class="notice notice-error pwcgk-error-msg is-dismissible"><p>' . $msg . '</p></div>';			
}


// Success message
function  pwcgk_success_option_msg($msg)
{
	
	echo ' <div class="notice notice-success pwcgk-success-msg is-dismissible"><p>'. $msg . '</p></div>';		
	
}
?>