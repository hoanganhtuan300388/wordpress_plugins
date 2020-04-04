<?php
$list_file_inc = scandir(dirname(dirname(dirname(__FILE__)))."/inc");
?>
<div class="setting_view_list">
  <h1 class="setting_title">メルマガ登録・解除</h1>
  <table class="setting-table-param member_posttype">
    <tr>
      <td class="title_table" colspan="2">なかま連携情報 <input type="hidden" name="nak_member_magazine_tg_id" value="<?php echo get_option('nakama-member-group-id'); ?>" /></td>
    </tr>
    <!-- <tr>
      <td>団体ID</td>
      <td><input type="text" name="nak_member_magazine_tg_id" value="<?php echo get_option('nakama-member-group-id'); ?>" /></td>
    </tr> -->
    <tr>
      <td>通知先メールアドレス</td>
      <td><input type="text" name="nak_member_magazine_mail_address" value="<?php echo $nak_member_magazine_mail_address; ?>" /></td>
    </tr>
    <tr>
      <td>組織ID</td>
      <td><input type="text" name="nak_member_magazine_g_id" value="<?php echo $nak_member_magazine_g_id; ?>" /></td>
    </tr>
  </table>
  <p>※画面に表示するメニューの名称および説明文を入力して下さい。</p>
  <table class="setting-table-param setting_magazine member_posttype">
    <tr>
      <td class="title_table" colspan="2">画面表示設定</td>
    </tr>
    <tr>
      <td>表示画面</td>
      <td>
        <label><input type="radio" name="type_magazine" class="showtr" value="1" <?php echo ($nakama_member_type_magazine == 1)?"checked":"";?>>メルマガ登録</label>
        <label><input type="radio" name="type_magazine" class="showtr inline" value="0" <?php echo ($nakama_member_type_magazine == 0)?"checked":"";?>>メルマガ解除</label>
      </td>
    </tr>
    <tr>
      <td>説明</td>
      <td><input type="text" name="nak_member_magazine_caption" value="<?php echo $nak_member_magazine_caption; ?>" /></td>
    </tr>
    <tr>
      <td>ヘッダファイル</td>
      <td>
        <select name="nak_member_magazine_disp_header_file_reg">
          <option value="">未設定</option>
          <?php
            for($i = 2; $i< count($list_file_inc); $i++){ 
              $f_name = MemberCrSet::convert_file_r($list_file_inc[$i]);
              ?>
              <option value="<?php echo $f_name; ?>" <?php echo ($f_name == $nak_member_magazine_disp_header_file_reg)?"selected":""; ?>><?php echo $f_name; ?></option>';
          <?php } ?>
        </select>
      </td>
    </tr>
  </table>
</div>
