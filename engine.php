<?php
	function create_user_post(){
		if(!is_email($_POST['prefix_email'])){
			return '您輸入的email不符合格式';
		}
		if(!empty($_POST['key'])){
			$post=array(
				'comment_status'=>false,
				'post_author'=>0,
				'post_content'=>'',
				'post_status'=>'private',
				'post_title'=>esc_html($_POST['prefix_stuname']),
				'post_type'=>'users',
				'post_content'=>esc_textarea($_POST['prefix_note'])
			);
			
			$id = wp_insert_post($post);
			if($id==0) return '建立失敗';
			$metas=array(
				'stage'=>$_POST['prefix_stage'],
				'birthday'=>$_POST['bir_year'] . '/' . $_POST['bir_month'] . '/' . $_POST['bir_day'],
				'department'=>$_POST['prefix_department'],
				'gender'=>$_POST['prefix_gender'],
				'address'=>$_POST['prefix_address'],
				'stu-phone'=>$_POST['prefix_stu-phone'],
				'parent-name'=>$_POST['prefix_parent-name'],
				'parent-phone'=>$_POST['prefix_parent-phone'],
				'email'=>$_POST['prefix_email'],
				'result'=>'未審核',
				'num'=>'A_XXXXXXXXXXXX',
				'where'=>'',
				'age'=>date('Y',time())-date('Y',strtotime($_POST['bir_year']))
				
			);
			foreach($metas as $key=>$val){
				add_post_meta($id,$key,esc_html($val));
			}
			
			return  '建立成功';
			
		}
		
	}
	add_action('init','create_user_post',0);
	
?>