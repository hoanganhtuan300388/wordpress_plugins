<?php
  $group_id_check = get_post_meta($post->ID, 'member_meta_group_id', true);
  if($group_id_check == ""){
    update_post_meta($post->ID, "member_meta_group_id", get_option('nakama-member-group-id'));
    update_post_meta($post->ID, "member_meta_p_id", get_option('nakama-member-personal-id'));
    update_post_meta($post->ID, "member_meta_api_key", get_option('nakama-member-api-key'));
  }
  $group_id_check = get_post_meta($post->ID, 'member_meta_group_id', true);
  update_post_meta($post->ID, "top_g_id", $group_id_check);
?>
<div class="wrap cpt_member">
  <div id="">
      <div class="panel_event">
        <div class="col-md-12 col-sm-12">
        <div id="shortcode">
          <p style="font-weight: bold;color: #0f6ab4;">ショートコード</p>
          <?php
          $short_code_empty = '[member-setting id="'.get_the_ID().'"]';
          $short_code = ($short_code)?$short_code:$short_code_empty; ?>
          <input id="html" value='[member-setting id="<?php echo get_the_ID(); ?>"]' readonly="" name="member_meta_short_code">
        </div>  
        <div class="setting_view_list setting_params_call_api">
          <h1 class="setting_title">API 連携設定</h1>
          <table class="setting-table-param w-100">
            <tr>
              <td>団体ID</td>
              <td>
                <?php $group_id_custom = ($group_id == '')?esc_attr( get_option('nakama-member-group-id') ):$group_id; ?>
                <input type="text" class="member-setting-group-id" id="member-setting-group-id" name="member_meta_group_id" value="<?php echo $group_id_custom; ?>"/>
              </td>
            </tr>
            <?php $p_id_custom = ($p_id == '')?esc_attr( get_option('nakama-member-personal-id') ):$p_id; ?>
            <input type="hidden" name="member_meta_p_id" id="member_meta_p_id" value="<?php echo $p_id_custom; ?>" />
            <tr>
              <td>APIキー</td>
              <td>
                <?php $api_key_custom = ($api_key == '')?esc_attr( get_option('nakama-member-api-key') ):$api_key; ?>
                <textarea rows="3" cols="80" name="member_meta_api_key" ><?php echo $api_key_custom; ?></textarea>
              </td>
            </tr>
            </table>
        </div>
        <input type="hidden" name="postID" value="<?php echo $post->ID; ?>">
          <p class="label_post">
            種類：
          </p>
          <?php
              $search_options_list = array(
                ""=>'種類を選択してください',
                "list_member"=>'会員一覧',
                "div_regist"=>'新規会員登録',
                "div_confirm"=>'内容確認・更新',
                "div_inquery"=>'ＩＤ・パスワード問合せ',
                "div_mail"=>'メールアドレス登録・変更',
                "div_card"=>'会員証発行',
                "setting_magazine"=>'メルマガ登録・ 解除',
                "copy_member"=>'グループ複写登録',
                "multiple_update"=>'担当者別内容確認・変更',
              );
          ?>
          <input type="hidden" name="pattern_no_post_type" value="<?php echo MemberCrSet::getPatternNoPosttype($post->ID);?>">
          <select id="type_setting_member" name="member_meta_box_type" class="type_post">
            <?php
            foreach ($search_options_list as $search_value => $search_name ) {
                ?>
                <option value="<?php echo $search_value ?>" <?php if ($search_value == $type_setting) echo 'selected' ?>><?php echo $search_name ?></option>
                <?php
            }
            ?>
          </select>
          <span id="img_loading_content" style="display: none;">
            <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) .'assets/img/loadingAnimation.gif' ?>" alt="">
          </span>
          <div id="content_option">
            <?php
              switch ($type_setting) {
                case 'list_member':
                  include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_list_member.php');
                  break;
                case 'div_login':
                  echo "<input type='hidden' name='type_setting_logined' value='".$type_setting."'>";
                  include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_member_logined.php');
                  break;
                case 'div_regist':
                  echo "<input type='hidden' name='type_setting_logined' value='".$type_setting."'>";
                  include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_member_logined.php');
                  break;
                case 'div_confirm':
                  echo "<input type='hidden' name='type_setting_logined' value='".$type_setting."'>";
                  include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_member_logined.php');
                  break;
                case 'div_inquery':
                  echo "<input type='hidden' name='type_setting_logined' value='".$type_setting."'>";
                  include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_member_logined.php');
                  break;
                case 'div_mail':
                  echo "<input type='hidden' name='type_setting_logined' value='".$type_setting."'>";
                  include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_member_logined.php');
                  break;
                case 'div_card':
                  echo "<input type='hidden' name='type_setting_logined' value='".$type_setting."'>";
                  include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_member_logined.php');
                  break;
                case 'setting_magazine':
                  include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_magazine.php');
                  break;
                case 'copy_member':
                  echo "<input type='hidden' name='type_setting_logined' value='".$type_setting."'>";
                  include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_member_logined.php');
                  break;
                case 'multiple_update':
                  echo "<input type='hidden' name='type_setting_logined' value='".$type_setting."'>";
                  include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_member_logined.php');
                  break;
                default:
                  break;
              }
            ?>
          </div>
        </div>
      </div>
  </div>
</div>
