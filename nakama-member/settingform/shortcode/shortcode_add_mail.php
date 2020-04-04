<?php
$members = new memberController();
$dataSetting = get_post_meta( $args['id'] );
$tg_id = $dataSetting['top_g_id'][0];
$auto_reg_flg = $dataSetting['auto_reg_flg'][0];
$rsaddmail = '';
$path_page = get_page_uri();
$page_link = $members->getPageSlug('nakama-login');
if($_SESSION['arrSession']){
  $datamember = $members->memberDetails($postid,$_SESSION['arrSession']->M_ID,$_SESSION['arrSession']->TG_ID);
}
if($_POST) {
  $arrValue = array();
  $arrValue['TG_ID'] = (isset($_SESSION['arrSession']->TG_ID))?$_SESSION['arrSession']->TG_ID:$tg_id;
  $arrValue['EMAIL'] = isset($_POST['p_c_email'.$args['id']]) ? $_POST['p_c_email'.$args['id']] : '';
  $arrValue['RE_EMAIL'] = isset($_POST['p_c_email2'.$args['id']]) ? $_POST['p_c_email2'.$args['id']] : '';
  $arrValue['PMAIL'] = isset($_POST['p_c_pmail'.$args['id']]) ? $_POST['p_c_pmail'.$args['id']] : '';
  $arrValue['C_NAME'] = isset($_POST['p_c_name'.$args['id']]) ? $_POST['p_c_name'.$args['id']] : '';
  $arrValue['G_NAME'] = isset($_POST['g_name'.$args['id']]) ? $_POST['g_name'.$args['id']] : '';
  $arrValue['representative'] = isset($_POST['representative'.$args['id']]) ? $_POST['representative'.$args['id']] : '';
  $arrValue['involved'] = isset($_POST['involved'.$args['id']]) ? $_POST['involved'.$args['id']] : '';
  if($auto_reg_flg == 1){
    $rsaddmail = $members->addEmail($postid, $arrValue);
    $msg2 = ($rsaddmail->msg2)?$rsaddmail->msg2:"";
    $msg3 = ($rsaddmail->msg3)?$rsaddmail->msg3:"";
    $SendMailAfterEntryMail = $members->SendMailAfterEntryMail($postid,1,$msg2,$msg3,$arrValue);
    if($rsaddmail->msg2 == ""){
      $SendMailMemberEntryMail = $members->SendMailMemberEntryMail($postid,$rsaddmail->P_ID,$arrValue['EMAIL'],$arrValue['PMAIL']);
    }
  }else {
    $rsaddmail = new stdClass();
    $rsaddmail->status = "Success";
    $SendMailAfterEntryMail = $members->SendMailAfterEntryMail($postid,0,"","",$arrValue);
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
    <title><?php echo get_the_title($args['id']); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/add_email.css">
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/smart.css">
    <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/jquery-1.6.3.min.js"></script>
    <script type="text/JavaScript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/MakeQRCode.js"></script>
    <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/common.js"></script>
    <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/inputcheck.js"></script>
  </head>
  <body>
    <div class="container">
      <form id="mainForm<?php echo $args['id']; ?>" name="mainForm<?php echo $args['id']; ?>" method="post"  autocomplete="off">
        <div align="center">
          <h1 class="page_title">個人E-MAIL登録・変更</h1>
        </div>
        <p class="text-left">
          入会の際に連絡先となるメールアドレスのご登録がお済でない方、または連絡先メールアドレスを変更したい方は、
          下欄に会員を特定する為の項目にご記入の上、最後に「送信」ボタンをクリックしてください。<br>
          お客様のID・パスワードをご登録の連絡先メールアドレス宛に後ほど(約１日)、事務局より送信します。
        </p>
        <p class="mg-none">
          ※メールアドレス登録をして頂きますと、次の会からのサービスが可能となります。
        </p>
        <ul class="list-number">
          <li>
            ①会からのご案内や通知・連絡をメールでタイムリーにお送りします。
          </li>
          <li>②会員様ご自身の登録情報の確認・変更が可能となります。</li>
          <li>③パスワードを忘れた場合の自動応答による取得が可能となります。</li>
          <li>④受信したイベントや行事案内に対してその場で参加回答が可能となります。</li>
          <li>⑤会員様向けの様々なサービスがメールを介して受けることができます。</li>
        </ul>
        <table class="full-w">
          <tbody>
            <tr>
              <td>
                <span style="color:red;">※マークの項目は必須入力です。</span>
              </td>
            </tr>
            <tr>
              <td>
                <table class="input_table">
                  <tbody>
                    <tr class="first">
                      <td class="input_title" nowrap="">入力項目</td>
                      <td class="input_title" nowrap="">記入欄</td>
                    </tr>
                    <tr>
                      <td class="input_item_Need" nowrap="">※個人E-MAIL</td>
                      <td class="input_value">
                        <input type="text" name="p_c_email<?php echo $args['id']; ?>" value="<?php echo isset($_POST['p_c_email'.$args['id']]) ? $_POST['p_c_email'.$args['id']] : (($_SESSION['arrSession'])?$datamember->D_PERSONAL->C_EMAIL:""); ?>">
                        <span>（入力例：ytaro@dynax.co.jp）</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="input_item_Need" nowrap="">※個人E-MAIL再入力</td>
                      <td class="input_value">
                        <input type="text" name="p_c_email2<?php echo $args['id']; ?>" value="<?php echo isset($_POST['p_c_email2'.$args['id']]) ? $_POST['p_c_email2'.$args['id']] : (($_SESSION['arrSession'])?$datamember->D_PERSONAL->C_EMAIL:""); ?>" autocomplete="nope">
                        <span>（入力例：ytaro@dynax.co.jp）</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="input_item" nowrap="">携帯E-MAIL</td>
                      <td class="input_value">
                        <input type="text" name="p_c_pmail<?php echo $args['id']; ?>" value="<?php echo isset($_POST['p_c_pmail'.$args['id']]) ? $_POST['p_c_pmail'.$args['id']] : (($_SESSION['arrSession'])?$datamember->D_PERSONAL->C_PMAIL:""); ?>">
                        <span>（入力例：ytaro@ezweb.ne.jp）</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="input_item_Need" nowrap="">※氏名</td>
                      <td class="input_value">
                        <input type="text" name="p_c_name<?php echo $args['id']; ?>" value="<?php echo isset($_POST['p_c_name'.$args['id']]) ? $_POST['p_c_name'.$args['id']] : (($_SESSION['arrSession'])?$datamember->D_PERSONAL->C_FNAME." ".$datamember->D_PERSONAL->C_LNAME:""); ?>">
                        <span>（入力例：山田太郎）</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="input_item_Need" nowrap="">※組織名</td>
                      <td class="input_value">
                        <input type="text" name="g_name<?php echo $args['id']; ?>" value="<?php echo isset($_POST['g_name'.$args['id']]) ? $_POST['g_name'.$args['id']] : (($_SESSION['arrSession'])?$datamember->D_ORGANIZATION->G_NAME:""); ?>">
                        <span>（入力例：(株)ダイナックス）</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="input_item" nowrap="">代表者氏名</td>
                      <td class="input_value">
                        <input type="text" name="representative<?php echo $args['id']; ?>" value="<?php echo isset($_POST['representative'.$args['id']]) ? $_POST['representative'.$args['id']] : ''; ?>">
                        <span>（入力例：山田太郎）</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="input_item" nowrap="">会員と異なる場合の<br>会員との関係</td>
                      <td class="input_value">
                        <input type="text" name="involved<?php echo $args['id']; ?>" value="<?php echo isset($_POST['involved'.$args['id']]) ? $_POST['involved'.$args['id']] : ''; ?>">
                        <span>（入力例：上司・総務担当・秘書・家族など）</span>
                      </td>
                    </tr>
                </tbody>
              </table>
              </td>
            </tr>
            
          </tbody>
        </table>
        <ul class="ul-button">
          <li>
            <input type="button" class="base_button" value="送　信" onclick="javascript:onSendMail<?php echo $args['id']; ?>();" name="send<?php echo $args['id']; ?>">
          </li>
        </ul>
        <input type="hidden" name="mode<?php echo $args['id']; ?>" value="">
          <?php
            if(isset($SendMailAfterEntryMail->status)) {
            ?>
            <table class="full-w">
              <tr>
                <td align="center">
                  <?php echo $SendMailAfterEntryMail->message; ?>
                </td>
              </tr>
            </table>
            <?php 
            }
        ?>
      </form>
    </div>
  </body>
</html>
<script>
function onSendMail<?php echo $args['id']; ?>(){
  var form = document.mainForm<?php echo $args['id']; ?>;

  if(form.p_c_email<?php echo $args['id']; ?>.value == ""){
    alert("個人E-MAILを入力して下さい。");
    form.p_c_email<?php echo $args['id']; ?>.focus();
    return false;
  }
  if(getByte(form.p_c_email<?php echo $args['id']; ?>.value) > 100){
    alert("個人E-MAILは半角１００文字以内で入力して下さい。");
    form.p_c_email<?php echo $args['id']; ?>.focus();
    return false;
  }
  if(isMail(form.p_c_email<?php echo $args['id']; ?>.value, '個人E-MAIL')){
    form.p_c_email<?php echo $args['id']; ?>.focus();
    return false;
  }
  if(IsNull(form.p_c_email2<?php echo $args['id']; ?>.value, '個人E-MAIL再入力')) return errProc(form.p_c_email2<?php echo $args['id']; ?>);
  if(form.p_c_email<?php echo $args['id']; ?>.value != form.p_c_email2<?php echo $args['id']; ?>.value){
    alert("個人E-MAILの内容と確認入力の内容が一致しません。\n個人E-MAILをもう一度確認して下さい");
    form.p_c_email2<?php echo $args['id']; ?>.value = "";
    form.p_c_email2<?php echo $args['id']; ?>.focus();
    return false;
  }
  if(IsNarrowPlus3(form.p_c_email<?php echo $args['id']; ?>.value, '個人E-MAIL')){
    form.p_c_email<?php echo $args['id']; ?>.focus();
    return false;
  }
  if(getByte(form.p_c_pmail<?php echo $args['id']; ?>.value) > 100){
    alert("携帯E-MAILは半角１００文字以内で入力して下さい。");
    form.p_c_pmail<?php echo $args['id']; ?>.focus();
    return false;
  }
  if(isMail(form.p_c_pmail<?php echo $args['id']; ?>.value, '携帯E-MAIL')){
    form.p_c_pmail<?php echo $args['id']; ?>.focus();
    return false;
  }
  if(IsNarrowPlus3(form.p_c_pmail<?php echo $args['id']; ?>.value, '携帯E-MAIL')){
    form.p_c_pmail<?php echo $args['id']; ?>.focus();
    return false;
  }
  if(form.p_c_name<?php echo $args['id']; ?>.value == ""){
    alert("氏名を入力して下さい。");
    form.p_c_name<?php echo $args['id']; ?>.focus();
    return false;
  }
  if(getByte(form.p_c_name<?php echo $args['id']; ?>.value) > 200){
    alert("氏名は半角200文字／全角１００以内で入力して下さい。");
    form.p_c_name<?php echo $args['id']; ?>.focus();
    return false;
  }
  if(form.g_name<?php echo $args['id']; ?>.value == ""){
    alert("組織名を入力して下さい。");
    form.g_name<?php echo $args['id']; ?>.focus();
    return false;
  }
  if(getByte(form.g_name<?php echo $args['id']; ?>.value) > 1000){
    alert("組織名は半角1000文字／全角５００以内で入力して下さい。");
    form.g_name<?php echo $args['id']; ?>.focus();
    return false;
  }
  if(getByte(form.representative<?php echo $args['id']; ?>.value) > 100){
    alert("代表者氏名は半角100文字／全角５０以内で入力して下さい。");
    form.representative<?php echo $args['id']; ?>.focus();
    return false;
  }
  if(getByte(form.involved<?php echo $args['id']; ?>.value) > 200){
    alert("会員と異なる場合の会員との関係は半角200文字／全角１００以内で入力して下さい。");
    form.involved<?php echo $args['id']; ?>.focus();
    return false;
  }

  if(window.confirm("入力された内容で送信しますが、よろしいですか？")){
    form.send<?php echo $args['id']; ?>.disabled = true;
    form.mode<?php echo $args['id']; ?>.value = "send";
    form.submit();
  }else{
    return false;
  }
}
</script>
