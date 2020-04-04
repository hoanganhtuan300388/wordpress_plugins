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
         <form name="mainForm" method="post" onsubmit="javascript: return toiawase()">
            <br><br>
            <table width="600" align="center" height="50" border="0" cellpadding="0" cellspacing="0" background="../img/nakama/hlbg1.gif">
               <tbody>
                  <tr>
                     <td width="20">&nbsp;</td>
                     <td valign="bottom">
                        <div class="DispTitle">メールアドレス、パスワード確認</div>
                     </td>
                  </tr>
               </tbody>
            </table>
            <br><br><br>
            <table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
               <tbody>
                  <tr>
                     <td valign="top" align="center">
                        <div>111111 様<br><br></div>
                     </td>
                  </tr>
                  <tr>
                     <td valign="top" align="center">
                        <div>あなたのメールアドレス、パスワードは下記の通りです。<br><br></div>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <table width="500" align="center" border="1" cellspacing="1" cellpadding="3" bordercolor="#45C91B">
                           <tbody>
                              <tr>
                                 <td class="item_title" width="120" align="center" nowrap=""><font>メールアドレス</font></td>
                                 <td>hieudt@runsystem.net</td>
                              </tr>
                              <tr>
                                 <td class="item_title" width="120" align="center" nowrap=""><font>パスワード</font></td>
                                 <td>11111</td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td align="center">
                        <br><a href="../index.asp">ログイン</a>
                        <br>
                        <br><a href="Javascript:openMemberReg();">会員登録内容を確認・変更する</a>
                     </td>
                  </tr>
                  <tr>
                     <td align="center">
                        <br><br>
                        <font>
                           <div align="center"><a href="javascript:clo();">&lt;&lt;　閉じる　&gt;&gt;</a></div>
                        </font>
                     </td>
                  </tr>
               </tbody>
            </table>
            <input type="hidden" name="mode" value="">
            <input type="hidden" name="mail" value="hieudt@runsystem.net">
            <input type="hidden" name="ccd" value="aVUQosuGGklVbxO">
            <input type="hidden" name="top_g_id" value="dmshibuya">
            <input type="hidden" name="forward_mail" value="demo@dynax.co.jp">
            <input type="hidden" name="page_no" value="50">
            <input type="hidden" name="linkage" value="1">
         </form>
      </div>
   </body>
</html>
