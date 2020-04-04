<div class="wrap setting_view_list window_close_class content-in content-out-first">
  <p><strong style="font-size: 9pt; color: #FF0000;">※最初にこの設定を行わないと「初期表示条件設定」を行うことはできません。</strong></p>
  <table class="setting-table-param">
  <tr>
    <td class="title_table" colspan="2">
      なかま連携情報
      <input type="hidden" id="top_g_id" name="top_g_id" value="<?php echo get_option('nakama-member-group-id'); ?>" />
    </td>
  </tr>
 <!--  <tr>
    <td>団体ID</td>
    <td><input type="text" id="top_g_id" name="top_g_id" value="<?php echo $top_g_id; ?>" /></td>
  </tr> -->
  <tr>
    <td>グループID</td>
    <td><input type="text" name="set_lg_g_id" value="<?php echo $set_lg_g_id; ?>" /></td>
  </tr>
  <tr>
    <td>組織ID</td>
    <td><input type="text" name="set_g_id" value="<?php echo $set_g_id; ?>" /></td>
  </tr>
  <tr>
    <td>管理者への<br>通知先メールアドレス</td>
    <td><input type="text" name="mail_address" value="<?php echo $mail_address; ?>" /></td>
  </tr>
  <tr>
    <td>下位グループの<br>ログイン可否</td>
    <td>
      <label class="block-5"><input type="radio" name="lg_login" value="0" <?php echo ($lg_login == 0)?"checked":"";?>>指定グループ以下のログインを不可能とする</label><br>
      <label><input type="radio" name="lg_login" value="1" <?php echo ($lg_login == 1)?"checked":"";?>>指定グループ以下のログインを可能とする</label>
    </td>
  </tr>
  <tr>
    <td>グループの表示設定</td>
    <td>
      <label class="block-5"><input type="radio" name="lg_disp" value="0" <?php echo ($lg_disp == 0)?"checked":"";?>>所属グループのみ表示する</label><br>
      <label><input type="radio" name="lg_disp" value="1" <?php echo ($lg_disp == 1)?"checked":"";?>>指定グループ以下を表示する</label>
    </td>
  </tr>
  </table>
  <br>
  <table class="setting-table-param member_posttype">
    <tr>
      <td class="title_table" colspan="2">『会員一覧』設定</td>
    </tr>
    <tr>
      <td>説明</td>
      <td><input type="text" name="login_caption" value="<?php echo $login_caption; ?>" /></td>
    </tr>
    <tr>
      <td>ログイン制限</td>
      <td>
        <label><input type="radio" name="group_leader" class="showtr" value="1" <?php echo ($group_leader == 1)?"checked":"";?>>する</label>
        <label><input type="radio" name="group_leader" class="showtr inline" value="0" <?php echo ($group_leader == 0)?"checked":"";?>>しない</label>
      </td>
    </tr>
    <tr>
      <td>全国／単会切替表示</td>
      <td>
        <label><input type="radio" name="top_type_visible" value="1" <?php echo ($top_type_visible == 1)?"checked":"";?>>する</label>
        <label><input type="radio" name="top_type_visible" class="inline" value="0" <?php echo ($top_type_visible == 0)?"checked":"";?>>しない</label>
      </td>
    </tr>
    <tr>
      <td>全国／単会デフォルト表示</td>
      <td>
        <label><input type="radio" name="top_type" value="1" <?php echo ($top_type == 1)?"checked":"";?>>単会</label>
        <label><input type="radio" name="top_type" value="2" class="inline" <?php echo ($top_type == 2)?"checked":"";?>>全国</label>
      </td>
    </tr>
    <tr class="show_tr" <?php if($group_leader == 0 || $group_leader == ''): ?> style="display: none;" <?php endif; ?>>
      <td>マーケティング表示</td>
      <td>
        <label><input type="radio" name="marketing_visible" value="1" <?php echo ($marketing_visible == 1)?"checked":"";?>>する</label>
        <label><input type="radio" name="marketing_visible" value="0" class="inline" <?php echo ($marketing_visible == 0)?"checked":"";?>>しない</label>
      </td>
    </tr>
    <tr class="show_tr" <?php if($group_leader == 0 || $group_leader == ''): ?> style="display: none;" <?php endif; ?>>
      <td>マーケティング一覧<br>申込先メールアドレス</td>
      <td><input type="text" name="marketing_mail" value="<?php echo $marketing_mail; ?>" /></td>
    </tr>
    <tr class="show_tr" <?php if($group_leader == 0 || $group_leader == ''): ?> style="display: none;" <?php endif; ?>>
      <td>マーケティング一覧<br>業種プルダウン表示</td>
      <td>
        <label><input type="radio" name="category_visible" value="1" <?php echo ($category_visible == 1)?"checked":"";?>>する</label>
        <label><input type="radio" name="category_visible" value="0" class="inline" <?php echo ($category_visible == 0)?"checked":"";?>>しない</label>
      </td>
    </tr>
  </table>
</div>
