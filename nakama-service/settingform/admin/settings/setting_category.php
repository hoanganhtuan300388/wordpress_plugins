<?php
$service = new serviceController();
$tg_id = get_post_meta($postid, 'nakama_service_param_tg_id', true);
$dis_id = get_post_meta($postid, 'nakama_service_param_m_bbsid', true);
$list_category = $service->GetListSettingDiscussionCategory($postid, $tg_id, $dis_id);
?>
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname(dirname(dirname( __FILE__ ))) ); ?>assets/css/setting_category.css">
<script type="text/javascript" src="<?php echo plugin_dir_url( dirname(dirname(dirname( __FILE__ ))) ); ?>assets/js/setting_category.js"></script>
<div class="setting_view_list setting_category">
  <table width="100%" align="center" border="0" cellspacing="0">
      <tbody>
        <tr>
          <td class="" width="450" align="center">
            <h1 class="setting_title">会議室－使用テーマ設定</h1>
          </td>
        </tr>
      </tbody>
    </table>
    <hr class="line" width="100%">
    <br>
    <form id="mainForm" name="mainForm" method="post">
      <div align="right" width="100%">
        <input type="button" class="button button-primary button-large" id="reg_category" name="update" onclick="OpenRegCategory('<?php echo $postid; ?>')" value="新規登録">
      </div>
      <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td>
              <div class="default">
                ※会議室で使用するテーマを選択してください。<br>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td class="RegHead">使用テーマ</td>
          </tr>
          <tr>
            <td align="center">
              <table align="center" width="100%" class="list_category_tbl">
                <tbody>
                  <tr class="ListHeader">
                    <td width="30" align="center">使用</td>
                    <td width="200">テーマ</td>
                    <td width="80" align="center">記事<br>書込み制限</td>
                    <td width="80" align="center">コメント<br>書込み可否</td>
                    <td width="80" align="center">コメント<br>書込み制限</td>
                    <td width="70" align="center">公開制限</td>
                    <td width="90" align="center">実名ﾆｯｸﾈｰﾑ<br>制限</td>
                    <td width="150">通知先グループ</td>
                    <td width="100">管理者名</td>
                    <td width="180">メールアドレス</td>
                    <td width="80" align="center">投稿者への<br>通知</td>
                    <td width="90" align="center">コメント<br>投稿時に<br>通知先<br>グループ<br>へ通知</td>
                  </tr>
                  <?php
                  if(!empty($list_category) && !isset($list_category->Message)) :
                    foreach ($list_category as $key => $item) :
                      if($service->checkEvenNumber($key) == 1){
                        $tr_class = "Row2";
                      }else{
                        $tr_class = "Row1";
                      }
                      $category_no = $key + 1;
                  ?>
                  <tr class="<?php echo $tr_class; ?>">
                    <input type="hidden" name="category<?php echo $category_no; ?>" value="<?php echo $item->CATEGORY; ?>">
                    <td align="center">
                      <input type="checkbox" name="check[]" class="check" value="<?php echo $category_no; ?>" <?php echo (!empty($item->CHK))?"checked":""; ?> onclick="useClick(<?php echo $category_no; ?>);">
                    </td>
                    <td>
                      <?php echo $item->CATEGORY; ?>
                    </td>
                    <td align="center">
                      <select name="wt_open<?php echo $category_no; ?>" >
                        <option value="0" <?php echo ($item->WRITE_THREAD_OPEN == 0)?"selected":""; ?>>一般</option>
                        <option value="1" <?php echo ($item->WRITE_THREAD_OPEN == 1)?"selected":""; ?>>会員</option>
                      </select>
                    </td>
                    <td align="center">
                      <select name="wc_flg<?php echo $category_no; ?>" >
                        <option value="0" <?php echo ($item->WRITE_CMT_FLG == 0)?"selected":""; ?>>可</option>
                        <option value="1" <?php echo ($item->WRITE_CMT_FLG == 1)?"selected":""; ?>>不可</option>
                      </select>
                    </td>
                    <td align="center">
                      <select name="wc_open<?php echo $category_no; ?>" >
                        <option value="0" <?php echo ($item->WRITE_CMT_OPEN == 0)?"selected":""; ?>>一般</option>
                        <option value="1" <?php echo ($item->WRITE_CMT_OPEN == 1)?"selected":""; ?>>会員</option>
                      </select>
                    </td>
                    <td align="center">
                      <select name="openkbn<?php echo $category_no; ?>" >
                        <option value="1" <?php echo ($item->OPEN_CLS == 1)?"selected":""; ?>>一般</option>
                        <option value="2" <?php echo ($item->OPEN_CLS == 2)?"selected":""; ?>>会員</option>
                      </select>
                    </td>
                    <td align="center">
                      <select name="use_name<?php echo $category_no; ?>" >
                        <option value="0" <?php echo ($item->USE_NAME_CLS == 0)?"selected":""; ?>>ﾆｯｸﾈｰﾑ可</option>
                        <option value="1" <?php echo ($item->USE_NAME_CLS == 1)?"selected":""; ?>>実名のみ</option>
                      </select>
                    </td>
                    <td><?php echo $item->TG_NAME; ?></td>
                    <td><?php echo $item->ADMIN_NM; ?></td>
                    <td><?php echo $item->ADMIN_MAIL; ?></td>
                    <td>
                      <?php
                      if($item->EXECUTER_MAIL_FLG == 0){
                        echo "しない";
                      }elseif($item->EXECUTER_MAIL_FLG == 1){
                        echo "する";
                      }
                      ?>
                    </td>
                    <td>
                      <?php
                      if($item->RES_NOTICE_MAIL_FLG == 0){
                        echo "しない";
                      }elseif($item->RES_NOTICE_MAIL_FLG == 1){
                        echo "する";
                      }
                      ?>
                    </td>
                  </tr>
                  <?php
                    endforeach;
                  endif;
                  ?>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center" colspan="5">
              <br>
              <input type="button" value="登　録" class="button button-primary button-large" onclick="javascript:OnOk();">
            </td>
          </tr>
        </tbody>
      </table>
      <input type="hidden" name="m_dispItem" value="">
      <input type="hidden" name="mode" value="">
      <input type="hidden" name="page_no" value="">
      <input type="hidden" name="patten_cd" value="">
      <input type="hidden" name="top_g_id" value="">
      <input type="hidden" name="bbs_id" value="">
      <input type="hidden" name="setting_category" value="1">
    </form>
    <script>
      function OpenRegCategory(postid){

        gToolWnd = window.open('<?php echo get_permalink(get_page_by_path('nakama-discussion-add-category')->ID).getAliasService(); ?>postid='+postid,
        'SearchWnd',
        'width=1300,height=800,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes')
      }
    </script>
</div>

