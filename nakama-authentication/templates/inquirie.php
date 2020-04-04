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
            <table width="650" cellspacing="0" cellpadding="0" border="0" align="center" class="login">
               <tbody>
                  <tr>
                     <td valign="top" align="center">
                        <div><font size="3">下記のメールアドレスに問合せ用ＵＲＬを送信しました。</font><br><br><br></div>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <table class="input_table" width="650" cellspacing="0" cellpadding="3" border="0" align="center">
                           <tbody>
                              <tr>
                                 <td class="input_title" width="150" nowrap="" align="center">メールアドレス</td>
                                 <td class="input_value"><input type="text" name="email" value="hieudt@runsystem.net" class="login_input" maxlength="100"></td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td><br></td>
                  </tr>
                  <tr>
                     <td>
                        メールにて送信されたＵＲＬでパスワード確認ができます。<br>
                        その際に下記の問合せＩＤの入力が必要ですので控えて下さい。<br>
                        ※問合せＩＤをコピーしておくと、後の入力で貼り付けることができます。<br>
                     </td>
                  </tr>
                  <tr>
                     <td><br></td>
                  </tr>
                  <tr>
                     <td>
                        <table class="input_table" width="650" cellspacing="0" cellpadding="3" border="0" align="center">
                           <tbody>
                              <tr>
                                 <td class="input_title" width="150" nowrap="" align="center">問合せＩＤ</td>
                                 <td class="input_value"><font size="6">lM3yo</font></td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td><br></td>
                  </tr>
                  <tr>
                     <td>
                        <font size="2" color="red">
                        ※問合せＩＤは、当日のみ有効で、且つ一度しか利用できません。
                        </font>
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
