<?php
   define('__ROOT__', dirname(dirname(__FILE__))); 
   require_once(__ROOT__.'/config/constant.php'); 
   require_once(__ROOT__.'/controller/memberController.php'); 
	 $members = new memberController();
   $postid = ($_GET['post_id'])?($_GET['post_id']):"";
   $dicName = ($_GET['dicName'])?($_GET['dicName']):"";
   $tg_id = !empty(get_post_meta($postid, 'member_meta_group_id', true)) ? get_post_meta($postid, 'member_meta_group_id', true) : get_option('nakama-member-group-id');
   $eleName = ($_GET['eleName'])?($_GET['eleName']):"";
   
   $per_page = !empty(get_option('nakama-member-general-per-page')) ? get_option('nakama-member-general-per-page'): 100;
   $current_page = !empty($_POST['current_page_list'])?$_POST['current_page_list']:"1";
   $page_no = $current_page - 1;
   if($_POST){
   	$category_code = $_POST['category_code'];
   	$category_name = $_POST['category_name'];
   }
	 $rs = $members->getSlectDic($postid, $dicName, $tg_id, $per_page, $page_no);
?>
<html lang="ja">
   <head>
      	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
      	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
      	<title>新規会員登録</title>
      	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/style.css">
      	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/smart.css">
      	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/regist.css">
      	<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/common.js"></script>
      	<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/sedai_link.js"></script>
      	<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/inputcheck.js"></script>
      	<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/jquery-1.6.3.min.js"></script>
      	<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/autoKana.js"></script>
      	<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/regist_new.js"></script>
      
      	<script type="text/javascript">
			<!--
			function OnChange(strBuff){
			  var txtname;
			  txtname = document.frmDicShow.text.value;
			  window.opener.document.mainForm.elements[txtname].value = strBuff;
			}
			function OnLoad(){
			  window.focus();
			}
			//-->
		</script>
   </head>
   <body onload="OnLoad();">
      <form method="post" name="frmDicShow" action="">
		  <input type="button" value="閉じる" onclick="window.close();">
		  <input type="hidden" name="text" value="<?php echo $eleName; ?>">
		  <br>
		  <table cols="2" rows="1" cellpadding="2" cellspacing="1">
		    <tbody><tr>
		      <td align="left" class="RegFeeTitle" nowrap=""><b>対象辞書</b></td>
		      <td align="left" nowrap=""><b>：役職名</b></td>
		    </tr>
		  </tbody></table>
		<br>
		  	<select name="dictionary" onchange="javascript:OnChange(frmDicShow.dictionary.value);">
		      <option value=""></option>
		      <?php
		      	if($rs){
	      		foreach ($rs as $key => $item) {?>
	      			<option value="<?php echo ($item->value)?$item->value:''; ?>"><?php echo ($item->value)?$item->value:''; ?></option>
	      		<?php } 
		      }?>
		    </select><table width="100%" border="0" cellpadding="2" cellspacing="1">
		    
		  </table>
		  </form>
   </body>
</html>