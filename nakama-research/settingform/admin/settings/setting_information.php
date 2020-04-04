<div class="wrap setting_view_list window_close_class content-in content-out-first">
  <p><strong style="font-size: 9pt; color: #FF0000;">※最初にこの設定を行わないと「初期表示条件設定」を行うことはできません。</strong></p>
  <table class="setting-table-param">
    <tr>
      <td class="title_table" colspan="2">
        なかま連携情報
        <input type="hidden" id="top_g_id" name="top_g_id" value="<?php echo get_post_meta($post->ID,"top_g_id", true); ?>" />
      </td>
    </tr>
    <tr>
      <td>全国／単会切替表示</td>
      <td>
        <label><input type="radio" name="top_type_visible" value="1" <?php echo ($top_type_visible == 1)?"checked":"";?>>する</label>
        <label><input type="radio" name="top_type_visible" value="0" <?php echo ($top_type_visible == 0)?"checked":"";?>>しない</label>
      </td>
    </tr>
    <tr>
      <td>全国／単会デフォルト表示</td>
      <td>
        <label><input type="radio" name="top_type" value="1" <?php echo ($top_type == 1)?"checked":"";?>>単会</label>
        <label><input type="radio" name="top_type" value="2" <?php echo ($top_type == 2)?"checked":"";?>>全国</label>
      </td>
    </tr>
    <tr>
      <td>背景<font color="#CCFF99">■</font>の説明文</td>
      <td><input type="text" name="word_back_color" value="<?php echo $word_back_color; ?>" /></td>
    </tr>
    <tr>
      <td>問合せ先メールアドレス</td>
      <td><input type="text" name="mail_address" value="<?php echo $mail_address; ?>" /></td>
    </tr>
  </table>
</div>
