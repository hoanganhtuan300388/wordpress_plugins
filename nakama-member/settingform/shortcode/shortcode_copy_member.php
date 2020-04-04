<?php
define('__ROOT__', dirname(dirname(dirname(__FILE__))));
require_once(__ROOT__.'/config/constant.php');
require_once(__ROOT__.'/controller/memberController.php');
$members = new memberController();
$header_file = $dataSetting['disp_header_file_reg'][0];
$MemberCrSet = new MemberCrSet();
$path_page = get_page_uri();
$page_link = $members->getPageSlug('nakama-login')."page_request=update_member&post_id=".$postid;
$_SESSION['path_page_edit'] = $path_page;
$set_lg_g_id = get_post_meta($postid, 'set_lg_g_id', true);
if(!isset($_SESSION['arrSession'])){
  ?>
  <script>window.location = "<?php echo $page_link.'&page_redirect='.$path_page; ?>"; </script>
  <?php
} else {
  $UpLgId_flg = get_post_meta($postid, 'lg_login', true);
  if(!empty($set_lg_g_id)){
    //ログイン情報
    $arrBodyLogin = array(
      "tgId" => $_SESSION['arrSession']->TG_ID,
      "userId" => $_SESSION['arrSession']->USERID,
      "password" => $_SESSION['arrSession']->PASSWORD,
      "rememberMe" => false,
      "loginStyle" => 1,
      "lg_id" => $set_lg_g_id,
      "lg_login" => 1,
      "UpLgId_flg" => $UpLgId_flg
    );
    $login = $MemberCrSet->logins($postid, $arrBodyLogin);
    if(isset($login->Message)){
        unset($_SESSION['arrSession']);
        //ログインしていない場合、ログイン画面に遷移する
        ?>
        <script>window.location = "<?php echo $page_link.'&page_redirect='.$path_page; ?>"; </script>
        <?php
    }
  }
  
  $p_id = $_SESSION['arrSession']->P_ID;  //⇒エンコードを行いパラメータを渡す
  $tg_id = $_SESSION['arrSession']->TG_ID;//⇒エンコードを行いパラメータを渡す

  $merumaga_flg = $dataSetting['merumaga_flg'][0];	//同意確認の表示
  $merumaga_file = $dataSetting['disp_merumaga_file'][0];	//同意確認の設定ファイル

  if ($merumaga_flg != 1) {//同意確認を表示しない場合
  ?>
    <script>
      NextPage();
    </script>
  <?php
  }

  ?>
  <script>
</script>
  <?php
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
  <meta name="robots" content="none">
  <meta name="robots" content="noindex,nofollow">
  <meta name="robots" content="noarchive">
  <title><?php echo get_the_title($args['id']); ?></title>
  <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/style.css">
  <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/edit_general.css">
  <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/smart.css">
  <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/regist/common.js"></script>
  <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/regist/sedai_link.js"></script>
  <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/regist/input_check.js"></script>
  <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/regist/jquery-1.6.3.min.js"></script>
  <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/regist_confirm.js"></script>
  <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/regist/autoKana.js"></script>
  <script src="https://unpkg.com/wanakana"></script>
  <script type="text/javascript">
         var isIE = /*@cc_on!@*/false || !!document.documentMode;
         if(isIE){
           $('.input_table_noline').css('table-layout', 'fixed');
           $('.input_table_noline .RegValue_noline:first').attr('colspan', '2');
         }
         function OnRelease(post_id){
          var form = document.mainForm;
          form.release.value = "1";
          form.method = "post";
          form.submit();
         }

         function OnLoad(){

         }

          function OnConfirm(){
            var form = document.mainForm;
              if($('input[name=agree_flg]:checked').val() == "1"){
                //OK
                NextPage();
            	return false;
              }
            //NG
            alert("同意確認を「する」にしてください。");
            return false;
          }

          function NextPage(){
            //なかま２の画面に遷移する
            window.location = "https://dev.nakamacloud.com/nakama2/ApplyMember/ApplyCommittee?tgId=<?php echo base64_encode($tg_id) ?>&pId=<?php echo base64_encode($p_id) ?>"
          }

         </script>
</head>
<body onunload="OnUnload();" onload="OnLoad();">
  <div class="container update_page">
      <div class="content_header_file">
        <?php 
        $class = array();
        $class['table'] = "input_table";
        $class['tdHead'] = "RegField";
        $class['tdOne'] = "RegGroup";
        $class['tdSecond'] = "RegItem_add";
        $class['tdThird'] = "RegValue";
        $class['tdFour'] = "RegItem"; ?>
        <?php
        if(empty($header_file)){
          echo MemberCrSet::convertFile(dirname(dirname(dirname(__FILE__)))."/settingform/inc/csaj_entry_header_committee.inc");
        } else {
          echo MemberCrSet::convertFile(dirname(dirname(dirname(__FILE__)))."/settingform/inc/".$header_file);
        }

        if(empty($merumaga_file)){
          echo MemberCrSet::convertFile(dirname(dirname(dirname(__FILE__)))."/settingform/inc/csaj_assent_committee.inc");
        } else {
          echo MemberCrSet::convertFile(dirname(dirname(dirname(__FILE__)))."/settingform/inc/".$merumaga_file);
        }
        ?>
      </div>

      <form name="mainForm" id="mainForm" enctype="multipart/form-data" method="POST" class="mt-15" autocomplete="off">
      <?php
        //※同意確認を表示して、「次へ進む」をクリックした場合に遷移する
        //if(file_exists(__ROOT__."/settingform/inc/".$merumaga_file)): ?>
          <center>
            <div><b><font size="4">
            <label><input type="radio" name="agree_flg" id="agree_flg01" value="1">同意する</label>&nbsp;&nbsp;&nbsp;&nbsp;
            <label><input type="radio" name="agree_flg" id="agree_flg02" value="2">同意しない</label>
            </font size="5"></b></div>

            <input class="base_button regist_confirm_btn" onclick="OnConfirm();" type="button" value="次へ進む">
          </center>
        <?php 
        //endif;
      ?>
      </form>
  </div>

<div class="preLoading">
  <div class="img_loading">
    <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/img/member/Spinner.gif" alt="">
    <span>処理中です...</span>
  </div>
</div>
</body>
</html>
