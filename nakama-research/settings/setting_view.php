<?php
function research_setting_list_view(){
?>
	<div class="wrap setting_view_list">
		<form method="post" action="options.php">
			<?php settings_fields( 'nakama-research-plugin-settings-list' ); ?>
			<?php do_settings_sections( 'nakama-research-plugin-settings-list' ); ?>
			<h1>NAKAMA research Setting List View</h1>
			<h1 class="setting_title">表示設定</h1>
			<div class="per_page">
				<label>一覧表示件数 :</label>
				<select name="nakama-research-list-per-page" class="">
					<?php
						$current_per_page = get_option("nakama-research-list-per-page");
						for($i = 10; $i <= 100; $i += 10) { ?>
							<option value="<?php echo $i; ?>" <?php echo ($i == $current_per_page)?"selected":""?>><?php echo $i; ?></option>
					<?php } ?>
				</select>
			</div>
			<div style="clear: both;"></div>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
} 
?>