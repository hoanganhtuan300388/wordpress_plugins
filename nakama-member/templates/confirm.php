<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/config/constant.php');
require_once(__ROOT__.'/controller/memberController.php');
$members = new memberController();
$errorMessage = "";
if(!$_SESSION['arrRegist']){
	?>
	<script>window.location = "<?php echo get_permalink(get_page_by_path('nakama-member-regist')->ID); ?>"; </script>
	<?php
}
else {
	$dataConfirm = $_SESSION['arrRegist'];
  $arrElement = isset($dataConfirm['arrElement']) ? json_decode(stripslashes($dataConfirm['arrElement']), true) : array();
  $check_input_open = isset($dataConfirm['check_input_open']) ? $dataConfirm['check_input_open'] : null;
}
if($_POST){
	$flg_shortcode = $_SESSION['flg_shortcode'];
	if(!$flg_shortcode)
		$postid = '';
	else 
		$postid = $_SESSION['post_id'];

	$rsConfirm = $members->registMember($dataConfirm,$flg_shortcode,$postid);
	if($rsConfirm->status == "Success"){
		$SendMailMember = $members->SendMailMember($postid, $rsConfirm->TG_ID, $rsConfirm->P_ID, $rsConfirm->LG_ID, $rsConfirm->G_ID,false);
		$mailTo = get_post_meta($postid, 'mail_address', true);
		if(!empty($mailTo)){
			$SendMailAdmin = $members->SendMailAdmin($postid, $rsConfirm->TG_ID, $rsConfirm->P_ID,  $rsConfirm->LG_ID, $rsConfirm->G_ID, 0, $mailTo,false);
		}
		unset($_SESSION['arrRegist']);
		// Move File upload
		if(isset($_SESSION['temp_path'])) {
			$source = $_SESSION['temp_path'];
			$root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
			$destination = MEMBER_ROOT_FILE."\\data\\imgs\\";
			if (!file_exists($destination)) {
				mkdir($destination, 0777, true);
			}
			moveFile($source , $destination );
			unset($_SESSION['temp_path']);
		}
		?>
		<script>window.location = "<?php echo get_permalink(get_page_by_path('nakama-member-complete')->ID); ?>"; </script>
		<?php
	}
	else 
		if($rsConfirm->status == "Fail"){
			$errorMessage = $rsConfirm->message;
		}
		else
			if($rsConfirm->Message){
				$errorMessage = $rsConfirm->Message;
			}
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
	<title>内容確認</title>
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/smart.css">
	<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/jquery-1.6.3.min.js"></script>
	<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/common.js"></script>
	<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/sedai_link.js"></script>
	<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/confirm.js"></script>
</head>
<body>
	<div class="container" style="max-width: 1140px;">
	<div align="center">
		<h1 class="page_title">入力内容の確認</h1>
	</div>
	<br><br>
  <form name="mainForm" id="mainForm" method="post">
		<input type="hidden" value="true" name="reg">
  	<table align="center" border="0" style="width: 100%;">
  		<tbody>
  			<tr>
  				<td align="center">
  					<input type="button" class="base_button" value="戻　る" onclick="OnReinput();">
  				</td>
  			</tr>
  		</tbody>
  	</table>
	<div style="clear: both;"></div>
	<table align="center" border="0" style="width: 100%;">
		<tbody>
			<tr>
				<td align="center">
					<input type="button" class="base_button" name="reg_submit" value="送信する" onclick="OnReg();">
				</td>
			</tr>
		</tbody>
	</table>
 	<br>
	<p class="red"><?php echo $errorMessage; ?></p>
 	<table class="input_table" align="center" cellspacing="0" cellpadding="3">
 		<tbody>
 			<tr>
 				<td class="RegField" colspan="2">項目名</td>
				<td class="RegField">記入内容</td>
				<?php if($check_input_open == 1) : ?>
				<td class="RegField" nowrap="">　公開設定　</td>
				<?php endif; ?>
 			</tr>
      <?php
      if(!empty($arrElement)) :
        $count = count($arrElement);
        for ($i=1; $i < $count; $i++) :
			?>
			<?php if(empty($arrElement[$i]['new'])) : ?>
 			<tr>
        <?php if(!empty($arrElement[$i]['group']) || !empty($arrElement[$i]['rowspan'])) : ?>
 				<td class="RegGroup" rowspan="<?php echo $arrElement[$i]['rowspan']; ?>" width="100"><?php echo $arrElement[$i]['group']; ?></td>
        <?php endif; ?>
 				<td colspan="1" class="<?php echo !empty($arrElement[$i]['value'])?"RegItem_add":"RegItem"?>" nowrap=""><?php echo $arrElement[$i]['label']; ?></td>
				<td class="RegValue "><?php echo $arrElement[$i]['value']; ?></td>
				<?php if($check_input_open == 1) : ?>
				<td class="<?php echo !empty($arrElement[$i]['selected'])?"RegValue_add":"RegValue"?>"><?php echo $arrElement[$i]['selected']; ?></td>
				<?php endif; ?>
			</tr>
			<?php endif; ?>
      <?php
        endfor;
      endif;
      ?>
 		</tbody>
	 </table>
	 <?php if(!empty($arrElement[count($arrElement)-1]['new'])) : ?>
		<br>
		<table border="0" cellspacing="0" cellpadding="0">
			<tbody>
				<tr>
					<td align="left" class="font90b">通信欄（事務局行き）</td>
				</tr>
			</tbody>
		</table>
		<table class="m_connect_end" align="center" border="0" cellspacing="0" cellpadding="5">
			<tbody>
				<tr>
					<td class="<?php echo (!empty($arrElement[count($arrElement)-1]['value']))?'RegItem_add':'RegValue'; ?>" colspan="3">
						<textarea style="ime-mode:active; width:100%; line-height:150%;" cols="98" rows="8" name="M_CONNECTION" readonly><?php echo $arrElement[count($arrElement)-1]['value']; ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<?php endif ?>
 	<br>
 	<center>
 		<input type="button" class="base_button" name="reg_submit" value="送信する" onclick="OnReg();">
 	</center>
  <input type="hidden" name="mode" value="1">
 </form>
 </div>
 <div class="preLoading confirm">
    <div class="img_loading">
      <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/img/member/Spinner.gif" alt="">
      <span>処理中です...</span>
    </div>
  </div>
</body>
</html>
