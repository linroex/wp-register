<?php
	function create_user_post(){
		global $wpdb;
		if(get_option('odie_register_installed')!='true'){
			include_once( ABSPATH . 'wp-admin/includes/upgrade.php');
			
			$table_name = $wpdb->prefix . 'odie_registers';
			
			$sql="CREATE TABLE $table_name(
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				name text NOT NULL,
				stage text NOT NULL,
				birthday text NOT NULL,
				department text NOT NULL,
				gender text NOT NULL,
				address text NOT NULL,
				stu_phone text NOT NULL,
				parent_name text NOT NULL,
				parent_phone text NOT NULL,
				email text NOT NULL,
				result text NOT NULL,
				frog_id text NOT NULL,
				local text NOT NULL,
				age text NOT NULL,
				note text NOT NULL,
				UNIQUE KEY id (id)
			)";
			dbDelta($sql);
			add_option('odie_register_installed','true');
		}
		
		
		if(wp_verify_nonce($_POST['key-field'],'odie_register')){
			if(!is_email($_POST['prefix_email'])){
				die('您輸入的email不符合格式');
			}
			$data=array(
				'name'=>$_POST['prefix_stuname'],
				'stage'=>$_POST['prefix_stage'],
				'birthday'=>$_POST['bir_year'] . '/' . $_POST['bir_month'] . '/' . $_POST['bir_day'],
				'department'=>$_POST['prefix_department'],
				'gender'=>$_POST['prefix_gender'],
				'address'=>$_POST['prefix_address'],
				'stu_phone'=>$_POST['prefix_stu-phone'],
				'parent_name'=>$_POST['prefix_parent-name'],
				'parent_phone'=>$_POST['prefix_parent-phone'],
				'email'=>$_POST['prefix_email'],
				'result'=>'未審核',
				'frog_id'=>'A_XXXXXXXXXXXX',
				'local'=>'',
				'age'=>date('Y',time())-date('Y',strtotime($_POST['bir_year'])),
				'note'=>esc_textarea($_POST['prefix_note'])
				);
			$wpdb->insert($wpdb->prefix . 'odie_registers',$data);
			
			$msg=<<<MSG
	報名成功，請敬帶審核通知
MSG;
			wp_mail($_POST['prefix_email'],'報名成功通知信',$msg);
			die('建立成功');
			
		}
		
	}
	add_action('init','create_user_post',0);
	
	function setting_cmd(){
		
		include_once('simple.php');	//載入我自定的Excel Class
		global $wpdb;
		$table_name = $wpdb->prefix . 'odie_registers';
		$x=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		if(wp_verify_nonce($_POST['key-field'],'reg-setting')){
			
			switch($_POST['cmd']){
				case 'export':
					
					
					$datas = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_A);
					$excel = new excel();
					$flag = false;
					$row_count = 2;
					foreach($datas as $data){
						
						$c = count($data);
						if(!$flag){
							$i=0;
							foreach($data as $key=>$value){
								$excel->write($x[$i] . '1',$key);
								$i++;
							}
							$flag=true;
						}
						$i=0;
						foreach($data as $key=>$value){
							
							
							$excel->write($x[$i] . $row_count,$value);
							$i++;
							
						}
						$row_count++;
					}
					$fname='wp-content/register-' . time() . '.xls';
					$excel->save($fname);
					safe_download($fname);	
					die();
					break;
					
				case 'import':
				
					break;
			}
		}
	}
	add_action('init','setting_cmd');
	
	function safe_download($path){
		//download file and delete it.
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($path));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($path));
		ob_clean();
		flush();
		readfile($path);
		unlink($path); //delete file
	}
?>