<?php
	function add_menu_setting(){
		add_menu_page('註冊系統','註冊系統','manage_options','register-setting','show_setting','',81);
	}
	add_action('admin_menu','add_menu_setting');
	
	function show_setting(){
		$url=get_site_url() . '/index.php';
		$key=wp_nonce_field('reg-setting','key-field');
		echo <<<page
		
		
		<h2>註冊系統相關設定</h2>
		<p><form action="$url" method="post">$key <input type="hidden" name="cmd" value="export" /><input type="submit" value="匯出" class="button" /></form></p>
		
		<p><form action="$url" method="post">$key <input type="hidden" name="cmd" value="import" /><input type="submit" value="匯入" class="button" /></form></p>
page;
	}
?>