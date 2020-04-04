<!DOCTYPE html>
<html lang="ja">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
      <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
      <title>「アンケート一覧」</title>
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/style.css">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/login.css">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/smart.css">
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/common.js"></script>
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/login/login.js"></script>
   </head>
   <body>
      <div class="container list_page">
         <form name="mainForm" method="post">
            <div align="center">
               <h1 class="page_title" style="width:100%">
                  個人ID・パスワードのお問い合わせ
               </h1>
            </div>
            <br><br>
            <table border="0" align="center" cellpadding="0" cellspacing="0" class="login">
               <tbody>
                  <tr>
                     <td valign="top">
                        申込の際にご登録されたメールアドレスを下欄に入力し、<br>
                        「問合せＩＤ発行」ボタンをクリックしてください。<br>
                        お客様の個人ID・パスワードを確認する為の問合せＩＤを発行します。<br><br>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <table class="input_table" align="center" border="0" cellspacing="0" cellpadding="3">
                           <tbody>
                              <tr>
                                 <td class="input_title" width="150" align="center" nowrap="">メールアドレス</td>
                                 <td class="input_value"><input type="text" name="email" value="" class="login_input" maxlength="100"></td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td align="center">
                        <br><font size="2">※携帯メールアドレスご入力の場合は、<br>ログインページのＵＲＬがメール送信されます。</font>
                        <br><br>
                     </td>
                  </tr>
                  <tr>
                     <td align="center">
                        <input type="button" class="base_button" value="問合せＩＤ発行" onclick="javascript:return send_mail()">
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="button" class="base_button" value="閉じる" onclick="javascript:clo()">
                     </td>
                  </tr>
               </tbody>
            </table>
            <input type="hidden" name="mode" value="">
            <input type="hidden" name="top_g_id" value="dmshibuya">
            <input type="hidden" name="lg_g_id" value="">
            <input type="hidden" name="lg_name" value="">
            <input type="hidden" name="lg_login" value="1">
            <input type="hidden" name="forward_mail" value="demo@dynax.co.jp">
            <input type="hidden" name="mail_flg" value="1">
            <input type="hidden" name="site_id" value="83">
            <input type="hidden" name="page_no" value="50">
            <input type="hidden" name="linkage" value="1">
         </form>
      </div>
   </body>
</html>
