<?php
	function create_user_post(){
		if(!empty($_POST['key'])){
			$post=array(
				'comment_status'=>false,
				'post_author'=>0,
				'post_content'=>'',
				'post_status'=>'private',
				'post_title'=>$_POST['name'],
				'post_type'=>'users'
			);
			wp_insert_post($post);
		
		}
	}
	add_action('wp_loaded','create_user_post',90);
?>