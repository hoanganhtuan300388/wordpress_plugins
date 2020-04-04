<?php
function service_management_setting(){ ?>
	<div class="wrap setting_view_list">
			<h1 class="setting_title">API設定</h1>
			<form method="post" action="options.php">
					<?php settings_fields( 'nakama-service-plugin-settings-group' ); ?>
					<?php do_settings_sections( 'nakama-service-plugin-settings-group' ); ?>
					<table class="form-table nakama-setting">
							<tr valign="top">
							<th scope="row">団体ID</th>
							<td>
								<input type="text" onchange="renPublicPID(event)" name="nakama-service-group-id" value="<?php echo esc_attr( get_option('nakama-service-group-id') ); ?>" />
								<span class="exam_label">例： cloudnakama</span></td>
							</td>
							</tr>
							<input type="hidden" id="nakama-service-personal-id" name="nakama-service-personal-id" value="<?php echo esc_attr( get_option('nakama-service-personal-id') ); ?>" />
							<tr valign="top">
							<th scope="row">APIキー</th>
							<td>
								<textarea rows="3" cols="80" name="nakama-service-api-key"><?php echo get_option('nakama-service-api-key'); ?></textarea>
								<span class="exam_label">例： Hgfhbd5tG5zzZfJodjNyDEVDkEcFf9HPj1vptBELi7GFVzcM8aaUf9ocCPFbzTzZYTN+y5+SA4XnqNtOAzeQiQ==</span>
							</td>
							</tr>
                            <tr>
                                <th scope="row">
                                    一覧表示件数 :
                                </th>
                                <td>
                                    <select name="nakama-service-general-per-page" class="">
                                        <?php
                                        $current_per_page = get_option("nakama-service-general-per-page");
                                        for($i = 10; $i <= 100; $i += 10) { ?>
                                            <option value="<?php echo $i; ?>" <?php echo ($i == $current_per_page)?"selected":""?>><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
					</table>
					<?php submit_button('変更保存'); ?>
			</form>
		</div>
		<script>
			function renPublicPID(e){
				var p_id = "public_"+e.target.value;
				jQuery("#nakama-service-personal-id").val(p_id);
			}
		</script>
	<?php
}
?>
