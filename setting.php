<?php
    public function add_menu_setting()
    {
        add_menu_page('註冊系統','註冊系統','manage_options','register-setting','show_setting','',81);
    }
    add_action('admin_menu','add_menu_setting');

    public function show_setting()
    {
        $url=get_site_url() . '/index.php';
        $key=wp_nonce_field('reg-setting','key-field');
        echo <<<page


        <h2>註冊系統相關設定</h2>
        <p><form action="$url" method="post">$key <input type="hidden" name="cmd" value="export" /><input type="submit" value="匯出" class="button" /></form></p>

        <p><form action="$url" method="post" enctype="multipart/form-data">$key <input type="hidden" name="cmd" value="import" /><input type="file" name="import_file"/><input type="submit" value="匯入" class="button" /></form></p>

        <table>
            <tr>
                <td>姓名</td>

                <td>館別</td>
                <td>性別</td>
                <td>地址</td>
                <td>學員電話</td>
                <td>家長姓名</td>
                <td>家長電話</td>
                <td>信箱</td>
                <td>年齡</td>

            </tr>

page;
        global $wpdb;
        $table_name = $wpdb->prefix . 'odie_registers';
        $datas = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_A);
        foreach ($datas as $data) {
            echo <<<txt
<tr>
    <td>{$data["name"]}</td>
    <td>{$data["department"]}</td>
    <td>{$data["gender"]}</td>
    <td>{$data["address"]}</td>
    <td>{$data["stu-phone"]}</td>
    <td>{$data["parent-name"]}</td>
    <td>{$data["parent-phone"]}</td>
    <td>{$data["email"]}</td>
    <td>{$data["age"]}</td>
</tr>

txt;
        }
        echo '</table>';
    }
