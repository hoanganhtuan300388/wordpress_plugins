<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/config/constant.php');
require_once(__ROOT__.'/controller/authenticationController.php');
$authentications = new authenticationController();
$page_redirect = (isset($_GET['page_redirect']))?$_GET['page_redirect']:"";
$evt_id = (isset($_GET['evt_id']))?$_GET['evt_id']:"";
$post_id = (isset($_GET['post_id']))?$_GET['post_id']:"";
$page_request = isset($_REQUEST['page_request']) ? $_REQUEST['page_request'] : "";
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$page_redirect = $authentications->getPageSlug($page_redirect);
//trunglt update 20112019
$get_meta = get_post_meta($post_id);
if($get_meta){
   foreach($get_meta as $key => $meta_box){
      if((strpos($key,"meta_group_id") !== false)){
         $meta_tg_id = $meta_box[0];
      }
   }
  }else {
   $meta_tg_id = get_option('nakama-authentication-group-id');
  }

if(!empty($_SESSION['checkTargetErrorSession'])) {
    $errMsg = $_SESSION['checkTargetErrorSession'];
    unset($_SESSION['checkTargetErrorSession']);
}
//
if(!empty($evt_id)){
    $page_redirect .= getAliasAuthen()."evt_id=".$evt_id.'&post_id='.$post_id;
}
if(empty($_SESSION['arrSession'])){
    $lg_id = '';
    $lg_login = 0;
    $UpLgId_flg = 0;
    $lg_id_check = get_post_meta($post_id, 'set_lg_g_id', true);
    if(!empty($page_request) && !empty($lg_id_check)){
        $lg_id = get_post_meta($post_id, 'set_lg_g_id', true);
        $UpLgId_flg = get_post_meta($post_id, 'lg_login', true);
        $lg_login = 1;
    }
    // if(isset($_COOKIE['userId']) && !is_null($_COOKIE['userId']) && !is_null($_COOKIE['password']) && isset($_COOKIE['password'])){
    //    $arrBody = array(
    //       "tgId" => $meta_tg_id,
    //       "userId" => $_COOKIE['userId'],
    //       "password" => $_COOKIE['password'],
    //       "rememberMe" => true,
    //       "loginStyle" => 1,
    //       "lg_id" => $lg_id,
    //       "lg_login" => $lg_login,
    //       "UpLgId_flg" => $UpLgId_flg
    //    );
    //    $login = $authentications->logins($arrBody);
    //    if($login){
    //       if(isset($login->Message)){
    //          $errMsg = $login->Message;
    //       }else{
    //          $_SESSION['arrSession'] = $login;
    //          $groupName = $authentications->getGroupname($_SESSION['arrSession']->TG_ID,$_SESSION['arrSession']->LG_ID,$_SESSION['arrSession']->LG_TYPE);
    //          $_SESSION['login_TG_NAME'] = $groupName;
    //          wp_redirect($page_redirect);
    //          exit();
    //       }
    //    }
    // }else{
    if($_POST){
        $regcoockie = false;
        $b_user_id = (isset($_POST['b_user_id']))?$_POST['b_user_id']:"";
        $b_password = (isset($_POST['b_password']))?$_POST['b_password']:"";

        $lg_g_is_root = !empty(get_post_meta($post_id, 'nakama_service_param_lg_g_is_root', true)) ? get_post_meta($post_id, 'nakama_service_param_lg_g_is_root', true) : "";
        if (!empty($lg_g_is_root) && $lg_g_is_root == 'root') {
            $lg_id = "";
            $lg_login = 0;
        }
        if(isset($_POST['regcoockie']) && $_POST['regcoockie']  == 1){
            $regcoockie = true;
            setcookie("userId", $b_user_id, time() + (60), "/");
            setcookie("password", $b_password, time() + (60), "/");
        }
        $arrBody = array(
            "tgId" => $meta_tg_id,
            "userId" => $b_user_id,
            "password" => $b_password,
            "rememberMe" => (isset($regcoockie))?$regcoockie:false,
            "loginStyle" => 1,
            "lg_id" => $lg_id,
            "lg_login" => $lg_login,
            "UpLgId_flg" => $UpLgId_flg
        );
        $login = $authentications->logins($arrBody);
        if($login){
            if(isset($login->Message)){
                $errMsg = $login->Message;
            }else{
                $_SESSION['arrSession'] = $login;
                $groupName = $authentications->getGroupname($_SESSION['arrSession']->TG_ID,$_SESSION['arrSession']->LG_ID,$_SESSION['arrSession']->LG_TYPE);
                $_SESSION['login_TG_NAME'] = $groupName;
                if($_REQUEST['mode'] != ''){
                    $startUrl = "?thread_id=".$_REQUEST['thread_id'];
                    $startUrl = $startUrl."&bbs_id=".$_REQUEST['bbs_id'];
                    $startUrl = $startUrl."&message_id=".$_REQUEST['message_id'];
                    $startUrl = $startUrl."&upper_message_id=".$_REQUEST['upper_message_id'];
                    $startUrl = $startUrl."&category=".$_REQUEST['category'];
                    $startUrl = $startUrl."&cmd=".$_REQUEST['cmd'];
                    $startUrl = $startUrl."&post_id=".$_REQUEST['post_id'];
                    switch ($_REQUEST['mode']) {
                        case '1':
                            $startUrl = get_permalink(get_page_by_path('nakama-discussion-thread-input')->ID).getAliasAuthen().$startUrl;
                            break;
                        case '3':
                            $startUrl = get_permalink(get_page_by_path('nakama-discussion-res-input')->ID).getAliasAuthen().$startUrl;
                            break;
                        default:
                            break;
                    }
                    wp_redirect($startUrl);
                    exit();
                }
                elseif(isset($_REQUEST['research_id']) && !empty($_REQUEST['research_id'])){
                    $researchUrl = "tg_id=".$_REQUEST['tg_id'];
                    $researchUrl .= "&research_id=".$_REQUEST['research_id'];
                    $researchUrl .= "&post_id=".$_REQUEST['post_id'];
                    $researchUrl .= "&ans_type=".$_REQUEST['ans_type'];
                    $researchUrl = get_permalink(get_page_by_path('nakama-research-agreement')->ID).getAliasAuthen().$researchUrl;
                    wp_redirect($researchUrl);
                    exit();
                }
                elseif(isset($_REQUEST['page_request']) && $_REQUEST['page_request'] == 'request_service_select') {
                    $researchUrl = "post_id=".$_REQUEST['post_id'];
                    $researchUrl .= "&top_g_id=".$_REQUEST['top_g_id'];
                    $researchUrl .= "&page_no=".$_REQUEST['page_no'];
                    $researchUrl = get_permalink(get_page_by_path('nakama-request-service-list')->ID).getAliasAuthen().$researchUrl;
                    wp_redirect($researchUrl);
                }elseif($_SESSION['page_meeting']){
	                wp_redirect($_SESSION['page_meeting']);
	                exit();
                }
                else{
                    wp_redirect($page_redirect);
                    exit();
                }
            }
        }
    }
    // }
    ?>
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
        <title>会員ログイン</title>
        <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/style.css">
        <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/login.css">
        <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/smart.css">
        <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/common.js"></script>
        <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/login/login.js"></script>
    </head>
    <body>
    <div class="container list_page">
        <form name="mainForm" method="post" action="" id="mainForm">
            <div align="center">
                <h1 class="page_title">会員ログイン</h1>
            </div>
            <table class="login" align="center">
                <tr>
                    <td>
                        <table align="center" border="0">
                            <tr>
                                <td align="left">
                                    <font size="2" style="display : none;">ログインＩＤとパスワードを入力してください。</font>
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
                                <td class="login_title" width="150" align="center" nowrap>ログインＩＤ</td>
                                <td class="login_value"><input type="text" class="alphameric login_input"name="b_user_id" maxlength="100" value="<?php echo isset($_COOKIE["userId"])?$_COOKIE["userId"]:""; ?>"></td>
                            </tr>
                            <tr>
                                <td class="login_title" width="150" align="center">パスワード</td>
                                <td class="login_value"><input type="password" class="alphameric login_input" name="b_password" maxlength="32" value="<?php echo isset($_COOKIE["password"])?$_COOKIE["password"]:""; ?>"></td>
                            </tr>
                        </table>
                        <table align="center">
                            <tr>
                                <td align="left" valign="top" colspan="2">
                                    <font size="2">
                                        <label>
                                            <input type="checkbox" name="regcoockie" value="1" <?php echo isset($_COOKIE["userId"])?"checked":""; ?>>
                                            <b>次回からログインID、パスワードの入力を省略<br></b>
                                        </label>
                                        <input type="hidden" name="dispLginWin" value="1">
                                        最長で2週間ログインID、パスワードを保存しておけます。<br>
                                        共用のパソコンではチェックを外してください。
                                    </font>
                                </td>

                            </tr>
                            <tr>
                                <?php if(empty($mode)) : ?>
                                    <td align="center" class="errMsg" colspan="2">
                                        <?php echo (isset($errMsg))?$errMsg:""; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        </table>
                        <br>
                        <div align="center" class="list-button-login">
                            <input type="button" class="base_button"  value="キャンセル" onclick="goHome()">
                            <input type="button" class="base_button" value="ログイン" onClick="return chkinput();">
                        </div>
                        <br>
                    </td>
                </tr>
            </table>
            <!-- <table width="100%" align="center">
               <tr>
                  <td colspan="2" align="center">
                     <a href=""><< 閉じる >></a>
                  </td>
               </tr>
            </table> -->
            <input type="hidden" name="top_g_id" value="<?php echo isset($_REQUEST['top_g_id'])?$_REQUEST['top_g_id']:""; ?>">
            <input type="hidden" name="bbs_id" value="<?php echo isset($_REQUEST['bbs_id'])?$_REQUEST['bbs_id']:""; ?>">
            <input type="hidden" name="thread_id" value="<?php echo isset($_REQUEST['thread_id'])?$_REQUEST['thread_id']:""; ?>">
            <input type="hidden" name="category" value="<?php echo isset($_REQUEST['category'])?$_REQUEST['category']:""; ?>">
            <input type="hidden" name="message_id" value="<?php echo isset($_REQUEST['message_id'])?$_REQUEST['message_id']:""; ?>">
            <input type="hidden" name="upper_message_id" value="<?php echo isset($_REQUEST['upper_message_id'])?$_REQUEST['upper_message_id']:""; ?>">
            <input type="hidden" name="path_page_category" value="<?php echo isset($_REQUEST['path_page_category'])?$_REQUEST['path_page_category']:""; ?>">
            <input type="hidden" name="post_id" value="<?php echo isset($_REQUEST['post_id'])?$_REQUEST['post_id']:"" ?>">
            <input type="hidden" name="page_request" value="<?php echo isset($_REQUEST['page_request'])?$_REQUEST['page_request']:"" ?>">
            <input type="hidden" name="cmd" value="<?php echo isset($_REQUEST['cmd'])?$_REQUEST['cmd']:"" ?>">
            <input type="hidden" name="mode" value="<?php echo isset($_REQUEST['mode'])?$_REQUEST['mode']:"" ?>">
            <input type="hidden" name="tg_id" value="<?php echo isset($_REQUEST['tg_id'])?$_REQUEST['tg_id']:"" ?>">
            <input type="hidden" name="research_id" value="<?php echo isset($_REQUEST['research_id'])?$_REQUEST['research_id']:"" ?>">
        </form>
    </div>
    <script>
        function goHome(){
            window.location = '<?php echo empty($_SESSION['url_org']) ? $_SESSION['meeting_back_login']:$_SESSION['url_org']; ?>'
        }
    </script>
    </body>
    </html>
<?php }else{
    if($_REQUEST['mode'] != ''){
        $startUrl = "?thread_id=".$_REQUEST['thread_id'];
        $startUrl = $startUrl."&bbs_id=".$_REQUEST['bbs_id'];
        $startUrl = $startUrl."&message_id=".$_REQUEST['message_id'];
        $startUrl = $startUrl."&upper_message_id=".$_REQUEST['upper_message_id'];
        $startUrl = $startUrl."&category=".$_REQUEST['category'];
        $startUrl = $startUrl."&cmd=".$_REQUEST['cmd'];
        $startUrl = $startUrl."&post_id=".$_REQUEST['post_id'];
        switch ($_REQUEST['mode']) {
            case '1':
                $startUrl = get_permalink(get_page_by_path('nakama-discussion-thread-input')->ID).getAliasAuthen().$startUrl;
                break;
            case '3':
                $startUrl = get_permalink(get_page_by_path('nakama-discussion-res-input')->ID).getAliasAuthen().$startUrl;
                break;
            default:
                break;
        }
        wp_redirect($startUrl);
        exit();
    }
    elseif(isset($_REQUEST['research_id']) && !empty($_REQUEST['research_id'])){
        $researchUrl = "tg_id=".$_REQUEST['tg_id'];
        $researchUrl .= "&research_id=".$_REQUEST['research_id'];
        $researchUrl .= "&post_id=".$_REQUEST['post_id'];
        $researchUrl .= "&ans_type=".$_REQUEST['ans_type'];
        $researchUrl = get_permalink(get_page_by_path('nakama-research-agreement')->ID).getAliasAuthen().$researchUrl;
        wp_redirect($researchUrl);
        exit();
    }else{
        header('Location:'.home_url());
    }
}
?>