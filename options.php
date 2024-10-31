<?php
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;
if(isset($_POST['Save_Options']))
{ 	$post_type="";
	$role_type="";
	if(isset($_POST['post_type'])) $post_type=$_POST['post_type'];
	if(isset($_POST['role_type'])) $role_type=$_POST['role_type'];
	//Sanitizing here:
	if(!empty($role_type)){
		array_walk($role_type, function(&$value, &$key) {
		$value[$key] = sanitize_text_field($value[$key]);
		});	
	}
	
	if(!empty($post_type)){
		array_walk($post_type, function(&$value, &$key) {
		$value[$key] = sanitize_text_field($value[$key]);
		});	
	}
	$nonce=$_POST['_wpnonce'];
	if(wp_verify_nonce( $nonce, 'post_view_option_nounce' ))
	{
		update_option("pwcgk_post_view_count_post_type",$post_type);	
		update_option("pwcgk_post_view_count_role_type",$role_type);
		pwcgk_success_option_msg('Settting Saved!');		
	}
	else
	{
        pwcgk_failure_option_msg('Unable to save data!');
    }
}
$selected_post_type=pwcgk_page_view_counter_post_types();
$selected_role_type=pwcgk_page_view_counter_role_types();
$post_types = get_post_types(array('public' => true )); 
global $wp_roles;
$roles=$wp_roles->role_names;

?>

<div class="wrap">

	<h2>Post View Counter</h2>
	<div class='pwcgk_inner'>


	<form method="POST">

		<table class="form-table">



			<tbody>


				<tr><th scope="row">Post types</th></tr>
					<tr><td>
					<?php if(count($post_types)>0 && !empty($post_types)){
					 
						foreach($post_types as $post_type)
						{							
							?>
							<input type="checkbox" name="post_type[]" value="<?php echo $post_type; ?>" <?php if(!empty($selected_post_type)){if(in_array($post_type,$selected_post_type)){ echo "checked";}} ?>><?php echo ucfirst($post_type); ?><br>
							<?php
						}
					
					}?>

					</td>
				</tr>
				

				<tr><th scope="row">Do not count hit for these user roles</th></tr>

				<tr><td>
					<?php if(count($roles)>0 && !empty($roles)){
					 
						foreach($roles as $key =>$value)
						{							
							?>
							<input type="checkbox" name="role_type[]" value="<?php echo $key; ?>" <?php if(!empty($selected_role_type)){if(in_array($key,$selected_role_type)){ echo "checked";}} ?>><?php echo $value; ?><br>
							<?php
						}
					
					}?>

				</td></tr>


			</tbody>

		</table><br/>
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo $nonce = wp_create_nonce('post_view_option_nounce'); ?>" />
		<input  class="button-primary" type="submit" value="Update" name="Save_Options">

	</form>  

	</div>

</div>