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
				die('您輸入的email不符 合格式');
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
			wp_mail($_POST['prefix_email'],'報名成功通知信',$msg,'Content-type: text/html');
			die('建立成功');
			
		}
		
	}
	add_action('init','create_user_post',0);
	
	function setting_cmd(){
		//error_reporting(-1);
		//ini_set('display_errors', 'On');
		set_time_limit(0);
		
		if(wp_verify_nonce($_POST['key-field'],'reg-setting')){
			
			include_once(plugin_dir_path(__FILE__) . 'simple.php');	//載入我自定的Excel Class
			global $wpdb;
			$table_name = $wpdb->prefix . 'odie_registers';
			$x=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P');
			$excel = new excel();
			switch($_POST['cmd']){
				case 'export':
					
					$datas = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_A);
					
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
					break;
				case 'import':
					$path = upload_file($_FILES['import_file']);
					
					$excel->load($path);
					$start_time = time();
					for($localy=2;$localy<$excel->getHeight();$localy++){
						if($excel->read(11 , $localy)=='通過'){
							$usernm = $excel->read(12 , $localy);
							$passwd = wp_generate_password();
							$email = $excel->read(10 , $localy);
							if(is_email($email)){
								$user = wp_create_user($usernm,$passwd,$email);
								if(!is_wp_error($user)){
									wp_update_user(array(
										'ID'=>$user,
										'last_name'=>$excel->read(1 , $localy),
										'nickname'=>$excel->read(1 , $localy),
										'display_name'=>$excel->read(1 , $localy),
										'role'=>'author'
									));
									$content = <<<text
		<h2>審核通過</h2>
		<p>以下是您的帳戶資訊</p>
		<ul>
			<li>帳號：$usernm</li>
			<li>密碼：$passwd</li>
		</ul>
text;
									wp_mail($email,'審核通過',$content,'Content-type: text/html');
									echo '#' . $excel->read(0 , $localy) . '.新增成功<br />';
								}else{
									echo '#' . $excel->read(0 , $localy) . '.新增失敗，錯誤訊息：' . $user->get_error_message() . '<br />';									
								}								
							}else{
								echo '#' . $excel->read(0 , $localy) . '.新增失敗，信箱格式不正確<br />';
							}				
						}else{
							wp_mail($email,'審核未通過','很抱歉在此通知您審核未通過');
							echo '#' . $excel->read(0 , $localy) . '審核未通過<br />';
						}
						
					}
					unlink($path);
					
					echo '全部新增完畢，共' . $excel->getHeight() . '筆資料，耗時' . (time() - $start_time) . '秒<br />';
					break;
			}
			die();
		}
	}
	add_action('init','setting_cmd');
	
	
	function upload_file($file){
		if($file['error'] OR empty($file)){
			throw new Exception($file['error']);
		}else{
			$path=ABSPATH . 'wp-content/' . $file['name'];
			if(move_uploaded_file($file['tmp_name'],$path)){
				return $path;
			}else{
				throw new Exception('Moving file occur error');
			}
		}
	}
	
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