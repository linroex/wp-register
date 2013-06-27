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
				'階段'=>$_POST['prefix_stage'],
				'生日'=>$_POST['bir_year'] . '/' . $_POST['bir_month'] . '/' . $_POST['bir_day'],
				'館別'=>$_POST['prefix_department'],
				'性別'=>$_POST['prefix_gender'],
				'地址'=>$_POST['prefix_address'],
				'學生電話'=>$_POST['prefix_stu-phone'],
				'家長姓名'=>$_POST['prefix_parent-name'],
				'家長電話'=>$_POST['prefix_parent-phone'],
				'電子信箱'=>$_POST['prefix_email'],
				'審核結果'=>'未審核',
				'布卡蛙編號'=>'A_XXXXXXXXXXXX',
				'分派地點'=>'',
				'年齡'=>date('Y',time())-date('Y',strtotime($_POST['bir_year']))
			);
			foreach($metas as $key=>$val){
				add_post_meta($id,$key,esc_html($val));
			}
			$msg=<<<MSG
	報名成功，請敬帶審核通知
MSG;
			wp_mail($_POST['prefix_email'],'報名成功通知信',$msg);
			return  '建立成功';
			
		}
		
	}
	add_action('init','create_user_post',0);
	
?>