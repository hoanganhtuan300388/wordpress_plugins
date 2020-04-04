<?php
function create_shortcode_login() {
  ob_start();
  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/config/constant.php');
  require_once(__ROOT__.'/controller/authenticationController.php');
  $authentications = new authenticationController();
  $page_redirect = (isset($_GET['page_redirect']))?$_GET['page_redirect']:"";
  $page_redirect = $authentications->getPageSlug($page_redirect);
  if(empty($_SESSION['arrSession'])){
    
    if(isset($_COOKIE['userId']) && !is_null($_COOKIE['userId']) && !is_null($_COOKIE['password']) && isset($_COOKIE['password'])){
       $arrBody = array(
          "tgId" => "",
          "tgNameAn" => "",
          "userId" => $_COOKIE['userId'],
          "password" => $_COOKIE['password'],
          "rememberMe" => true,
          "indexUrl" => "",
          "id" => "",
          "startUrl" => "",
          "loginStyle" => 1,
          "chkPasswordExpired" => true,
          "loginCandidates" => null
       );
       $login = $authentications->logins($arrBody);
       if($login){
          if(isset($login->Message)){
             $errMsg = $login->Message;
          }else{
             $_SESSION['arrSession'] = $login;
             header('Location: '.$page_redirect.'');
          }
       }
    }else{
       if($_POST){
          $regcoockie = false;
          $b_user_id = (isset($_POST['b_user_id']))?$_POST['b_user_id']:"";
          $b_password = (isset($_POST['b_password']))?$_POST['b_password']:"";
          if(isset($_POST['regcoockie']) && $_POST['regcoockie']  == 1){
             $regcoockie = true;
             setcookie("userId", $b_user_id, time() + (60), "/");
             setcookie("password", $b_password, time() + (60), "/");
          }
          $arrBody = array(
             "tgId" => "",
             "tgNameAn" => "",
             "userId" => $b_user_id,
             "password" => $b_password,
             "rememberMe" => (isset($regcoockie))?$regcoockie:false,
             "indexUrl" => "",
             "id" => "",
             "startUrl" => "",
             "loginStyle" => 1,
             "chkPasswordExpired" => true,
             "loginCandidates" => null
          );

          $login = $authentications->logins($arrBody);
          if($login){
             if(isset($login->Message)){
                $errMsg = $login->Message;
             }else{
                $_SESSION['arrSession'] = $login;
                header('Location: '.$page_redirect.'');
             }
          }
       }
    }
?>
<div class="list_page">
   <form name="mainForm" method="post" action="" id="mainForm">
      <div align="center">
          <h1 class="page_title">会員管理&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ログイン</h1>
        </div>
      <table class="login" align="center">
         <tr>
            <td>
               <table align="center" border="0">
                  <tr>
                     <td align="left">
                        <font size="2">ログインＩＤとパスワードを入力してください。(Enter Your Login ID/Password)</font>
                     </td>
                  <tr>
                  </tr>
                  <tr>
                     <!-- <td align="right" valign="top">
                        <a style="font-size: 10pt;" href="" onclick="window.open('/nakama-forgot', '_blank', 'width=1020,height=750,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');">
                        パスワード設定はこちら<br>(Forgot Your Password ?)
                        </a><br><br>
                     </td> -->
                  </tr>
               </table>
               <table class="login_table" align="center" border="0" cellspacing="0" cellpadding="3">
                  <tr>
                     <td class="login_title" width="150" align="center" nowrap>ログインＩＤ<br>(Login ID)</td>
                     <td class="login_value"><input type="text" class="alphameric login_input"name="b_user_id" maxlength="100" value=""></td>
                  </tr>
                  <tr>
                     <td class="login_title" width="150" align="center">パスワード<br>(Password)</td>
                     <td class="login_value"><input type="password" class="alphameric login_input" name="b_password" maxlength="32" value=""></td>
                  </tr>
               </table>
               <table align="center">
                  <tr>
                     <td align="left" valign="top" colspan="2">
                        <font size="2">
                        <label>
                           <input type="checkbox" name="regcoockie" value="1"  >
                           <b>次回からログインID、パスワードの入力を省略<br></b>
                        </label>
                        <input type="hidden" name="dispLginWin" value="1">
                        最長で2週間ログインID、パスワードを保存しておけます。<br>
                        共用のパソコンではチェックを外してください。
                        </font>
                     </td>

                  </tr>
                  <tr>
                     <td align="center" class="errMsg" colspan="2">
                        <?php echo (isset($errMsg))?$errMsg:""; ?>
                     </td>
                  </tr>
               </table>
               <br>
               <div align="center" class="list-button-login">
                  <input type="reset" class="base_button"  value="キャンセル（Cancel)">
                  <input type="button" class="base_button" value="ログイン（Login )" onClick="return chkinput();">
               </div>
               <br>
            </td>
         </tr>
      </table>
      <table width="100%" align="center">
         <tr>
            <td colspan="2" align="center">
               <a href=""><< 閉じる >></a>
            </td>
         </tr>
      </table>
   </form>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/login.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/smart.css">
<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/common.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/login/login.js"></script>
<?php
}
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
add_shortcode('nakama_login', 'create_shortcode_login');
?>
