<?php
function research_management_plugin_settings_page(){

  ?>
    <div class="wrap setting_view_list">
       <h1 class="setting_title">API設定</h1>
       <form method="post" action="options.php">
        <?php settings_fields( 'nakama-research-plugin-settings-group' ); ?>
        <?php do_settings_sections( 'nakama-research-plugin-settings-group' ); ?>
        <table class="form-table nakama-setting">
          <tr valign="top">
          <th scope="row">団体ID</th>
            <td>
              <input type="text" name="nakama-research-group-id" onchange="renPublicPID(event)" value="<?php echo esc_attr( get_option('nakama-research-group-id') ); ?>" />
              <span class="exam_label">例：cloudnakama</span>
            </td>
          </tr>
          <input type="hidden" name="nakama-research-personal-id" id="nakama-research-personal-id" value="<?php echo esc_attr( get_option('nakama-research-personal-id') ); ?>" />
          <tr valign="top">
          <th scope="row">APIキー</th>
            <td>
              <textarea rows="3" cols="80" name="nakama-research-api-key"><?php echo get_option('nakama-research-api-key'); ?></textarea>
              <span class="exam_label">例：Hgfhbd5tG5zzZfJodjNyDEVDkEcFf9HPj1vptBELi7GFVzcM8aaUf9ocCPFbzTzZYTN+y5+SA4XnqNtOAzeQiQ==</span>
            </td>
          </tr>
          <tr>
            <th scope="row">
              一覧表示件数 :  
            </th>
            <td>
              <select name="nakama-research-general-per-page" class="">
                <?php
                  $current_per_page = get_option("nakama-research-general-per-page");
                  for($i = 10; $i <= 100; $i += 10) { ?>
                    <option value="<?php echo $i; ?>" <?php echo ($i == $current_per_page)?"selected":""?>><?php echo $i; ?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
        </table>
        <?php submit_button("変更保存"); ?>
      </form>
    </div>
    <script>
      function renPublicPID(e){
        var p_id = "public_"+e.target.value;
        jQuery("#nakama-research-personal-id").val(p_id);
      }
      document.getElementById('submit').onclick = function updateKey() {
          <?php
          $tg_id =  get_option('nakama-research-group-id');
          $api_key = get_option('nakama-research-api-key');
          researchCrSet::research_update_key($tg_id,$api_key);
          ?>
      }
    </script>
  <?php
}
?>