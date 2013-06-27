<?php
	function create_user_post(){
		if(!is_email($_POST['email'])){
			return '您輸入的email不符合格式';
		}
		if(!empty($_POST['key'])){
			$post=array(
				'comment_status'=>false,
				'post_author'=>0,
				'post_content'=>'',
				'post_status'=>'private',
				'post_title'=>esc_html($_POST['stuname']),
				'post_type'=>'users',
				'post_content'=>esc_textarea($_POST['note'])
			);
			
			$id = wp_insert_post($post);
			if($id==0) return '建立失敗';
			$metas=array(
				'stage'=>$_POST['stage'],
				'birthday'=>$_POST['year'] . '/' . $_POST['month'] . '/' . $_POST['day'],
				'department'=>$_POST['department'],
				'gender'=>$_POST['gender'],
				'address'=>$_POST['address'],
				'stu-phone'=>$_POST['stu-phone'],
				'parent-name'=>$_POST['parent-name'],
				'parent-phone'=>$_POST['parent-phone'],
				'email'=>$_POST['email'],
				'result'=>'未審核',
				'num'=>'A_XXXXXXXXXXXX',
				'where'=>'',
				'age'=>date('Y',time())-date('Y',strtotime($_POST['year']))
				
			);
			foreach($metas as $key=>$val){
				add_post_meta($id,$key,esc_html($val));
			}
			
			echo  '建立成功';
			
		}
		
	}
	add_action('init','create_user_post',0);
	
?>