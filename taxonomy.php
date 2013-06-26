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
			
			case 'num':
				get_post_custom_values('num',$post_id);
			case 'age':
				get_post_custom_values('age',$post_id);
			case 'gender':
				get_post_custom_values('gender',$post_id);
			case 'department':
				get_post_custom_values('department',$post_id);
			case 'where':
				get_post_custom_values('where',$post_id);
			case 'result':
				get_post_custom_values('result',$post_id);
			case 'count':
				get_post_custom_values('count',$post_id);
			
		}
	}
	
	add_action('manage_users_posts_custom_column','custom_user_columns_data',10,2);
?>