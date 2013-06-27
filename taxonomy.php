<?php
	function create_user_type(){
		$labels=array(
			'name'=>'Users',
			'menu_name'=>'Users',
			'add_new'=>'Add User',
			'add_new_item'=>'Add User',
			'edit_item'=>'Edit User',
			'new_item'=>'Add User',
			'view_item'=>'View User'			
		);
		$args=array(
			'labels'=>$labels,
			'public'=>false,
			'exclude_from_search'=>true,
			'show_ui'=>true,
			'show_in_menu'=>true,
			'menu_position'=>5,
			'supports'=>array('title','custom-fields','editor')
		);
		register_post_type('Users',$args);
	}
	add_action('init','create_user_type');
	
	function custom_user_columns($col){
		
		$cc=array(
			'cb'=>'<input type="checkbox" />',
			'ID'=>'編號',
			'title'=>'學童姓名',
			'num'=>'布卡蛙序號',
			'age'=>'年齡',
			'gender'=>'性別',
			'department'=>'館別',
			'where'=>'派發區域',
			
			'result'=>'審核',
			'count'=>'篇數'
			
		);
		return $cc;
	}	
	add_filter('manage_edit-users_columns','custom_user_columns');
	
	
	function custom_user_columns_data($columns,$post_id){
		
		switch($columns){
			case 'ID':
				echo $post_id;
				break;
			case 'num':
				echo get_post_meta($post_id,'num',1);
				break;
			case 'age':
				echo get_post_meta($post_id,'age',1);
				break;
			case 'gender':
				echo get_post_meta($post_id,'gender',1);
				break;
			case 'department':
				echo get_post_meta($post_id,'department',1);
				break;
			case 'where':
				echo get_post_meta($post_id,'where',1);
				break;
			case 'result':
				echo get_post_meta($post_id,'result',1);
				break;
			case 'count':
				echo get_post_meta($post_id,'count',1);
				break;
			
		}
	}
	
	add_action('manage_users_posts_custom_column','custom_user_columns_data',10,2);
?>