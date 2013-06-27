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
				echo get_post_meta($post_id,'布卡蛙編號',1);
				break;
			case 'age':
				echo get_post_meta($post_id,'年齡',1);
				break;
			case 'gender':
				echo get_post_meta($post_id,'性別',1);
				break;
			case 'department':
				echo get_post_meta($post_id,'館別',1);
				break;
			case 'where':
				echo get_post_meta($post_id,'分派地點',1);
				break;
			case 'result':
				echo get_post_meta($post_id,'審核結果',1);
				break;
			case 'count':
				echo get_post_meta($post_id,'count',1);
				break;
			
		}
	}
	
	add_action('manage_users_posts_custom_column','custom_user_columns_data',10,2);
	
	function user_quick_editor($a,$b){
		if($b=='users'){
			
			switch($a){
				case 'num':
			
					break;
				case 'where':
					
					break;
				case 'result':
					
					break;
			}
			
		}
		
	}
	add_action('quick_edit_custom_box','user_quick_editor',10,2);
	
	
	function register_user($id){
		
		//add_post_meta($id,'debug',$_POST['post_type']);
		if($_POST['post_type']=='users'){
			if(get_post_meta($id,'審核結果',1)=='通過'){
				$passwd=wp_generate_password();
				$usernm=get_post_meta($id,'布卡蛙編號',1);
				$message=<<< MSG
	恭喜您通過審核，以下是您的登入資訊
	帳號：$usernm
	密碼：$passwd
MSG;
				
				$userid = wp_create_user(get_post_meta($id,'布卡蛙編號',1),wp_generate_password(),get_post_meta($id,'電子信箱',1));
				if(is_wp_error($userid)){
					add_post_meta($id,'error',$userid->get_error_message());
					
					
				}else{
					
						wp_mail(get_post_meta($id,'電子信箱',1),'審核通過通知信',$message);
						wp_update_user(array(
							'ID'=>$userid,
							'display_name'=>get_the_title($id),
							'last_name'=>get_the_title($id)
							
						));
						
						add_post_meta($id,'user_id',$userid);
						$usermeta=array(
							'階段'=>get_post_meta($id,'階段',1),
							'生日'=>get_post_meta($id,'生日',1),
							'館別'=>get_post_meta($id,'館別',1),
							'性別'=>get_post_meta($id,'性別',1),
							'地址'=>get_post_meta($id,'地址',1),
							'學生電話'=>get_post_meta($id,'學生電話',1),
							'家長姓名'=>get_post_meta($id,'家長姓名',1),
							'家長電話'=>get_post_meta($id,'家長電話',1),
							'電子信箱'=>get_post_meta($id,'電子信箱',1),
							'分派地點'=>get_post_meta($id,'分派地點',1),
							'年齡'=>get_post_meta($id,'年齡',1)
						);
						foreach($usermeta as $key=>$value){
							add_user_meta($userid,$key,$value);
						}
						wp_trash_post($id);
						wp_redirect(get_site_url() . '/wp-admin/edit.php?post_type=users');
						exit();
						
				}
				
				
			}
			
		}
	}
	add_action('save_post','register_user');
	
?>
