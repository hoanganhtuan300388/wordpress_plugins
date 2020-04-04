<?php
$list_file_inc = scandir(dirname(dirname(dirname(__FILE__)))."/inc");
$list_file_ini = scandir(dirname(dirname(dirname(__FILE__)))."/ini");
?>
<div class="setting_view_list">
  <table class="setting-table-param member_posttype" style="margin-bottom:20px;">
      <tr>
        <td class="title_table" colspan="2">なかま連携情報 <input type="hidden" name="top_g_id" value="<?php echo get_option('nakama-member-group-id'); ?>" /></td>
      </tr>
      <tr>
        <td>グループID</td>
        <td>
          <input type="text" name="set_lg_g_id" id="set_lg_g_id" onchange="checkValidLG_ID();" value="<?php echo $set_lg_g_id; ?>"
          <?php echo ($type_setting == "div_regist")?"required":"";?>/>
          <p id="err_lg_id">グループIDが存在していません。</p>
        </td>
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
  <div id="div_login" <?php echo ($disp_menu == "div_login")?"":"style='display: none'"; ?>>
    <table class="setting-table-param member_posttype">
      <tr>
        <td class="title_table" colspan="2">『会員名刺交換リスト』設定</td>
      </tr>
      <tr>
        <td>タイトル</td>
        <td><input type="text" name="login_name" value="<?php echo $login_name; ?>" /></td>
      </tr>
      <tr>
        <td>説明</td>
        <td><input type="text" name="login_caption" value="<?php echo $login_caption; ?>" /></td>
      </tr>
      <tr>
        <td>ログイン制限</td>
        <td>
          <label><input type="radio" name="group_leader" value="1" <?php echo ($group_leader == 1)?"checked":"";?>>グループリーダーのみ</label>
          <label><input type="radio" class="inline" name="group_leader" value="0" <?php echo ($group_leader == 0)?"checked":"";?>>しない</label>
        </td>
      </tr>
      <tr>
        <td>全国／単会切替表示</td>
        <td>
          <label><input type="radio" name="top_type_visible" value="1" <?php echo ($top_type_visible == 1)?"checked":"";?>>する</label>
          <label><input type="radio" class="inline" name="top_type_visible" value="0" <?php echo ($top_type_visible == 0)?"checked":"";?>>しない</label>
        </td>
      </tr>
      <tr>
        <td>全国／単会デフォルト表示</td>
        <td>
          <label><input type="radio" name="top_type" value="1" <?php echo ($top_type == 1)?"checked":"";?>>単会</label>
          <label><input type="radio" class="inline" name="top_type" value="2" <?php echo ($top_type == 2)?"checked":"";?>>全国</label>
        </td>
      </tr>
      <tr>
        <td>マーケティング表示</td>
        <td>
          <label><input type="radio" name="marketing_visible" value="1" <?php echo ($marketing_visible == 1)?"checked":"";?>>する</label>
          <label><input type="radio" class="inline" name="marketing_visible" value="0" <?php echo ($marketing_visible == 0)?"checked":"";?>>しない</label>
        </td>
      </tr>
      <tr>
        <td>マーケティング一覧<br>申込先メールアドレス</td>
        <td><input type="text" name="marketing_mail" value="<?php echo $marketing_mail; ?>" /></td>
      </tr>
      <tr>
        <td>マーケティング一覧<br>業種プルダウン表示</td>
        <td>
          <label><input type="radio" name="category_visible" value="1" <?php echo ($category_visible == 1)?"checked":"";?>>する</label>
          <label><input type="radio" class="inline" name="category_visible" value="0" <?php echo ($category_visible == 0)?"checked":"";?>>しない</label>
        </td>
      </tr>
    </table>
  </div>
  <div id="div_regist" <?php echo ($disp_menu == "div_regist")?"":"style='display: none'"; ?>>
    <table class="setting-table-param member_posttype">
      <tr>
        <td class="title_table" colspan="2">『新規会員登録』設定</td>
      </tr>
      <tr>
        <td>説明</td>
        <td><input type="text" name="regist_caption" value="<?php echo $regist_caption; ?>" /></td>
      </tr>
      <tr>
        <td>ヘッダファイル(新規)</td>
        <?php
        ?>
        <td>
          <select name="disp_header_file_reg">
            <option value="">未設定</option>
            <?php
            for($i = 2; $i< count($list_file_inc); $i++){ 
              $f_name = MemberCrSet::convert_file_r($list_file_inc[$i]);
              ?>
              <option value="<?php echo $f_name; ?>" <?php echo ($f_name == $disp_header_file_reg)?"selected":""; ?>><?php echo $f_name; ?></option>';
            <?php } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>同意確認の表示</td>
        <td>
          <label class="block-5"><input type="radio" name="merumaga_flg" <?php echo ($merumaga_flg == 1)?"checked":"";?> value="1">する（同意確認表示し、登録画面に遷移）</label><br>
          <label class="block-5"><input type="radio" name="merumaga_flg" <?php echo ($merumaga_flg == 2)?"checked":"";?> value="2">する（登録画面に同意確認を表示)</label><br>
          <label><input type="radio" name="merumaga_flg" <?php echo ($merumaga_flg == 0)?"checked":"";?> value="0">しない</label>
        </td>
      </tr>
      <tr>
        <td>同意確認画面の設定ファイル</td>
        <td>
          <select name="disp_merumaga_file">
            <option value="">未設定</option>
            <?php
            for($i = 2; $i< count($list_file_inc); $i++){ 
              $f_name = MemberCrSet::convert_file_r($list_file_inc[$i]);
              ?>
              <option value="<?php echo $f_name; ?>" <?php echo ($f_name == $disp_merumaga_file)?"selected":""; ?>><?php echo $f_name; ?></option>';
            <?php } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>使用設定ファイル</td>
        <td>
          <select name="disp_entry_file">
            <option value="">未設定</option>
            <?php
            for($i = 2; $i< count($list_file_ini); $i++){ 
              $f_name = MemberCrSet::convert_file_r($list_file_ini[$i]);
              ?>  
              <option value="<?php echo $f_name; ?>" <?php echo ($f_name == $disp_entry_file)?"selected":""; ?>><?php echo $f_name; ?></option>';
            <?php } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>公開設定の入力</td>
        <td>
          <label><input type="radio" name="input_open" value="1" <?php echo ($input_open == 1)?"checked":"";?>>する</label>
          <label><input type="radio" class="inline" name="input_open" value="0" <?php echo ($input_open == 0)?"checked":"";?>>しない</label>
        </td>
      </tr>
      <tr>
        <td>西暦・和暦入力プルダウン<br>の切り替え</td>
        <td>
          <label><input type="radio" name="entry_setting2" value="1" <?php echo ($entry_setting2 == 1)?"checked":"";?>>西暦・和暦選択 </label>
          <label><input type="radio" class="inline" name="entry_setting2" value="0" <?php echo ($entry_setting2 == 0)?"checked":"";?>>西暦入力のみ</label>
        </td>
      </tr>
      <tr>
        <td>罫線有無</td>
        <td>
          <label><input type="radio" name="entry_setting3" value="1" <?php echo ($entry_setting3 == 1)?"checked":"";?>>あり </label>
          <label><input type="radio" class="inline" name="entry_setting3" value="0" <?php echo ($entry_setting3 == 0)?"checked":"";?>>なし</label>
        </td>
      </tr>
      <!-- <tr>
        <td>FelicaID登録ボタン<br>の表示有無</td>
        <td>
          <label><input type="radio" name="entry_setting4" value="1" <?php echo ($entry_setting4 == 1)?"checked":"";?>>あり </label>
          <label><input type="radio" name="entry_setting4" value="0" <?php echo ($entry_setting4 == 0)?"checked":"";?>>なし</label>
        </td>
      </tr> -->
      <tr>
        <td>会費支払状況ボタン<br>の表示有無</td>
        <td>
          <label><input type="radio" name="entry_setting5" value="1" <?php echo ($entry_setting5 == 1)?"checked":"";?>>あり </label>
          <label><input type="radio" class="inline" name="entry_setting5" value="0" <?php echo ($entry_setting5 == 0)?"checked":"";?>>なし</label>
        </td>
      </tr>
      <!-- <tr>
        <td>会員登録後の<br>会員への控えメールの送信</td>
        <td>
          <label><input type="radio" name="entry_setting1" value="1" <?php echo ($entry_setting1 == 1)?"checked":"";?>>する </label>
          <label><input type="radio" name="entry_setting1" value="0" <?php echo ($entry_setting1 == 0)?"checked":"";?>>しない</label>
        </td>
      </tr> -->
      <tr>
        <td>会員への控えメールの件名</td>
        <?php $mail_subject_check = !empty($mail_subject) ? $mail_subject : MAIL_SUBJECT_REGIST_DEFAULT; ?>
        <td><input type="text" name="mail_subject" value="<?php echo $mail_subject_check; ?>" placeholder="『[@団体名]』会員登録申し込み完了のお知らせ"/></td>
      </tr>
      <tr>
        <td>会員への控えメールの本文</td>
        <td>
          <?php $mail_body_check = !empty($mail_body) ? $mail_body : MAIL_BODY_REGIST_DEFAULT; ?>
          <textarea name="mail_body" style="width: 100%" rows="20"><?php echo $mail_body_check; ?></textarea>
        </td>
      </tr>
    </table>
  </div>
  <div id="div_confirm" <?php echo ($disp_menu == "div_confirm")?"":"style='display: none'"; ?>>
    <table class="setting-table-param member_posttype">
      <tr>
        <td class="title_table" colspan="2">『内容確認・更新』設定</td>
      </tr>
      <tr>
        <td>説明</td>
        <td><input type="text" name="confirm_caption" value="<?php echo $confirm_caption; ?>" /></td>
      </tr>
      <tr>
        <td>ヘッダファイル(更新)</td>
        <td>

          <select name="disp_header_file_reg_end">
            <option value="">未設定</option>
            <?php
            for($i = 2; $i< count($list_file_inc); $i++){ 
              $f_name = MemberCrSet::convert_file_r($list_file_inc[$i]);
              ?>
              <option value="<?php echo $f_name; ?>" <?php echo ($f_name == $disp_header_file_reg_end)?"selected":""; ?>><?php echo $f_name; ?></option>';
            <?php } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>登録解除ボタンの表示</td>
        <td>
          <label><input type="radio" name="ReleaseDisp_end" value="1" <?php echo ($ReleaseDisp_end == 1)?"checked":"";?>>する</label>
          <label><input type="radio" class="inline" name="ReleaseDisp_end" value="0" <?php echo ($ReleaseDisp_end == 0)?"checked":"";?>>しない</label>
        </td>
      </tr>
      <tr>
        <td>使用設定ファイル</td>
        <td>
          <select name="disp_merumaga_file_end">
            <option value="">未設定</option>
            <?php
              for($i = 2; $i< count($list_file_ini); $i++){
                $l_name = MemberCrSet::convert_file_r($list_file_ini[$i]);
               ?>
                <option value="<?php echo $l_name; ?>" <?php echo ($l_name == $disp_merumaga_file_end)?"selected":""; ?>><?php echo $l_name; ?></option>
            <?php  }
            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>公開設定の入力</td>
        <td>
          <label><input type="radio" name="input_open_end" value="1" <?php echo ($input_open_end == 1)?"checked":"";?>>する</label>
          <label><input type="radio" class="inline" name="input_open_end" value="0" <?php echo ($input_open_end == 0)?"checked":"";?>>しない</label>
        </td>
      </tr>
      <tr>
        <td>西暦・和暦入力プルダウン<br>の切り替え</td>
        <td>
          <label><input type="radio" name="entry_setting2_end" value="1" <?php echo ($entry_setting2_end == 1)?"checked":"";?>>西暦・和暦選択 </label>
          <label><input type="radio" class="inline" name="entry_setting2_end" value="0" <?php echo ($entry_setting2_end == 0)?"checked":"";?>>西暦入力のみ</label>
        </td>
      </tr>
      <tr>
        <td>罫線有無</td>
        <td>
          <label><input type="radio" name="entry_setting3_end" value="1" <?php echo ($entry_setting3_end == 1)?"checked":"";?>>あり</label>
          <label><input type="radio" class="inline" name="entry_setting3_end" value="0" <?php echo ($entry_setting3_end == 0)?"checked":"";?>>なし</label>
        </td>
      </tr>
      <!-- <tr>
        <td>FelicaID登録ボタン<br>の表示有無</td>
        <td>
          <label><input type="radio" name="entry_setting4_end" value="1" <?php echo ($entry_setting4_end == 1)?"checked":"";?>>あり </label>
          <label><input type="radio" name="entry_setting4_end" value="0" <?php echo ($entry_setting4_end == 0)?"checked":"";?>>なし</label>
        </td>
      </tr> -->
      <tr>
        <td>会費支払状況ボタン<br>の表示有無</td>
        <td>
          <label><input type="radio" name="entry_setting5_end" value="1" <?php echo ($entry_setting5_end == 1)?"checked":"";?>>あり </label>
          <label><input type="radio" class="inline" name="entry_setting5_end" value="0" <?php echo ($entry_setting5_end == 0)?"checked":"";?>>なし</label>
        </td>
      </tr>
      <!-- <tr>
        <td>会員登録後の<br>会員への控えメールの送信</td>
        <td>
          <label><input type="radio" name="entry_setting1_end" value="1" <?php echo ($entry_setting1_end == 1)?"checked":"";?>>する  </label>
          <label><input type="radio" name="entry_setting1_end" value="0" <?php echo ($entry_setting1_end == 0)?"checked":"";?>>しない</label>
        </td>
      </tr> -->
      <tr>
        <td>会員への控えメールの件名</td>
        <?php $mail_subject_end_check = !empty($mail_subject_end) ? $mail_subject_end : MAIL_SUBJECT_EDIT_DEFAULT; ?>
        <td><input type="text" name="mail_subject_end" value="<?php echo $mail_subject_end_check; ?>" placeholder="『[@団体名]』会員登録申し込み完了のお知らせ" /></td>
      </tr>
      <tr>
        <td>会員への控えメールの本文</td>
        <td>
          <?php 
          $mail_body_end_check = !empty($mail_body_end) ? $mail_body_end : MAIL_BODY_EDIT_DEFAULT; ?>
          <textarea name="mail_body_end" style="width: 100%" rows="20"><?php echo $mail_body_end_check; ?></textarea>
        </td>
      </tr>
    </table>
  </div>
  <div id="div_inquery" <?php echo ($disp_menu == "div_inquery")?"":"style='display: none'"; ?>>
    <table class="setting-table-param member_posttype">
      <tr>
        <td class="title_table" colspan="2">『ＩＤ・パスワード問合せ』設定</td>
      </tr>
      <tr>
        <td>説明</td>
        <td><input type="text" name="query_caption" value="<?php echo $query_caption; ?>" /></td>
      </tr>
    </table>
  </div>
  <div id="div_mail" <?php echo ($disp_menu == "div_mail")?"":"style='display: none'"; ?>>
    <table class="setting-table-param member_posttype">
      <tr>
        <td class="title_table" colspan="2">『メールアドレス登録・変更』設定</td>
      </tr>
      <tr>
        <td>説明</td>
        <td><input type="text" name="mail_caption" value="<?php echo $mail_caption; ?>" /></td>
      </tr>
      <tr>
        <td>自動的に会員認証までしてメルアド登録</td>
        <td>
          <label><input type="radio" name="auto_reg_flg" value="1" <?php echo ($auto_reg_flg == 1)?"checked":"";?>>する  </label>
          <label><input type="radio" class="inline" name="auto_reg_flg" value="0" <?php echo ($auto_reg_flg == 0)?"checked":"";?>>しない</label>
        </td>
      </tr>
    </table>
  </div>
  <div id="div_card" <?php echo ($disp_menu == "div_card")?"":"style='display: none'"; ?>>
    <table class="setting-table-param member_posttype">
      <tr>
        <td class="title_table" colspan="2">『会員証発行』設定</td>
      </tr>
      <tr>
        <td>説明</td>
        <td><input type="text" name="member_card_caption" value="<?php echo $member_card_caption;?>" /></td>
      </tr>
    </table>
  </div>

  <div id="copy_member" <?php echo ($disp_menu == "copy_member")?"":"style='display: none'"; ?>>
    <table class="setting-table-param member_posttype">
      <tr>
        <td class="title_table" colspan="2">『グループ複写登録』設定</td>
      </tr>
      <tr>
        <td>説明</td>
        <td><input type="text" name="regist_caption" value="<?php echo $regist_caption; ?>" /></td>
      </tr>
      <tr>
        <td>ヘッダファイル(新規)</td>
        <?php
        ?>
        <td>
          <select name="disp_header_file_reg">
            <option value="">未設定</option>
            <?php
            for($i = 2; $i< count($list_file_inc); $i++){ 
              $f_name = MemberCrSet::convert_file_r($list_file_inc[$i]);
              ?>
              <option value="<?php echo $f_name; ?>" <?php echo ($f_name == $disp_header_file_reg)?"selected":""; ?>><?php echo $f_name; ?></option>';
            <?php } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>同意確認の表示</td>
        <td>
          <label class="block-5"><input type="radio" name="merumaga_flg" <?php echo ($merumaga_flg == 1)?"checked":"";?> value="1">する（同意確認表示し、登録画面に遷移）</label><br>
          <label><input type="radio" name="merumaga_flg" <?php echo ($merumaga_flg == 0)?"checked":"";?> value="0">しない</label>
        </td>
      </tr>
      <tr>
        <td>同意確認画面の設定ファイル</td>
        <td>
          <select name="disp_merumaga_file">
            <option value="">未設定</option>
            <?php
            for($i = 2; $i< count($list_file_inc); $i++){ 
              $f_name = MemberCrSet::convert_file_r($list_file_inc[$i]);
              ?>
              <option value="<?php echo $f_name; ?>" <?php echo ($f_name == $disp_merumaga_file)?"selected":""; ?>><?php echo $f_name; ?></option>';
            <?php } ?>
          </select>
        </td>
      </tr>
    </table>
  </div>

  <div id="multiple_update" <?php echo ($disp_menu == "multiple_update")?"":"style='display: none'"; ?>>
    <table class="setting-table-param member_posttype">
      <tr>
        <td class="title_table" colspan="2">『担当者別内容確認・変更』設定</td>
      </tr>
      <tr>
        <td>説明</td>
        <td><input type="text" name="regist_caption" value="<?php echo $regist_caption; ?>" /></td>
      </tr>
      <tr>
        <td>ヘッダファイル(新規)</td>
        <?php
        ?>
        <td>
          <select name="disp_header_file_reg">
            <option value="">未設定</option>
            <?php
            for($i = 2; $i< count($list_file_inc); $i++){ 
              $f_name = MemberCrSet::convert_file_r($list_file_inc[$i]);
              ?>
              <option value="<?php echo $f_name; ?>" <?php echo ($f_name == $disp_header_file_reg)?"selected":""; ?>><?php echo $f_name; ?></option>';
            <?php } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>同意確認の表示</td>
        <td>
          <label class="block-5"><input type="radio" name="merumaga_flg" <?php echo ($merumaga_flg == 1)?"checked":"";?> value="1">する（同意確認表示し、登録画面に遷移）</label><br>
          <label><input type="radio" name="merumaga_flg" <?php echo ($merumaga_flg == 0)?"checked":"";?> value="0">しない</label>
        </td>
      </tr>
      <tr>
        <td>同意確認画面の設定ファイル</td>
        <td>
          <select name="disp_merumaga_file">
            <option value="">未設定</option>
            <?php
            for($i = 2; $i< count($list_file_inc); $i++){ 
              $f_name = MemberCrSet::convert_file_r($list_file_inc[$i]);
              ?>
              <option value="<?php echo $f_name; ?>" <?php echo ($f_name == $disp_merumaga_file)?"selected":""; ?>><?php echo $f_name; ?></option>';
            <?php } ?>
          </select>
        </td>
      </tr>
    </table>
  </div>
  
</div>
<script>
  jQuery(document).ready(function($) {
    var type_setting_logined = $("input[name='type_setting_logined']").val();
    $('#'+type_setting_logined).show();
  });
</script>


