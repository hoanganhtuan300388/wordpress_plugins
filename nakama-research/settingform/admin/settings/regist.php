<?php
  $group_id_check = get_post_meta($post->ID, 'research_meta_group_id', true);
  if($group_id_check == ""){
    update_post_meta($post->ID, "research_meta_group_id", get_option('nakama-research-group-id'));
    update_post_meta($post->ID, "research_meta_p_id", get_option('nakama-research-personal-id'));
    update_post_meta($post->ID, "research_meta_api_key", get_option('nakama-research-api-key'));
  }
  $group_id_check = get_post_meta($post->ID, 'research_meta_group_id', true);
  update_post_meta($post->ID, "top_g_id", $group_id_check);
?>
<div class="wrap cpt-research">
  <div id="">
      <div class="panel_event">
        <div class="col-md-12 col-sm-12">
        <div id="shortcode">
          <p style="font-weight: bold;color: #0f6ab4;">ショートコード</p>
          <input id="html" value='[research-setting id="<?php echo get_the_ID(); ?>"]' readonly="" name="research_meta_short_code">
        </div>  
        <div class="setting_view_list setting_params_call_api">
          <h1 class="setting_title">API 連携設定</h1>
          <table class="setting-table-param w-100">
            <tr>
              <td>団体ID</td>
              <td>
                <?php $group_id_custom = ($group_id == '')?esc_attr( get_option('nakama-research-group-id') ):$group_id; ?>
                <input type="text" class="research-setting-group-id" id="research_meta_group_id" name="research_meta_group_id" value="<?php echo $group_id_custom; ?>"/>
              </td>
            </tr>
            <?php $p_id_custom = ($p_id == '')?esc_attr( get_option('nakama-research-personal-id') ):$p_id; ?>
            <input type="hidden" name="research_meta_p_id" id="research_meta_p_id" value="<?php echo $p_id_custom; ?>" />
            <tr>
              <td>APIキー</td>
              <td>
                <?php $api_key_custom = ($api_key == '')?esc_attr( get_option('nakama-research-api-key') ):$api_key; ?>
                <textarea rows="3" cols="80" name="research_meta_api_key" ><?php echo $api_key_custom; ?></textarea>
              </td>
            </tr>
            </table>
        </div>
        <input type="hidden" name="postID" value="<?php echo $post->ID; ?>">
        <input type="hidden" name="pattern_no_post_type" value="<?php echo researchCrSet::getPatternNoPosttype($post->ID);?>">
          <div id="content_option">
            <?php
              include(PLUGIN_research_PATH_SETTING . 'admin/settings/setting_list_research.php');
            ?>
          </div>
        </div>
      </div>
  </div>
</div>
