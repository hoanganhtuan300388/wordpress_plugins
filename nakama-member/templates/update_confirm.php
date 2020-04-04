<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/config/constant.php');
require_once(__ROOT__.'/controller/memberController.php');
$members = new memberController();
$page_link = $members->getPageSlug('nakama-login');
$path_page = "nakama-update-confirm";
$err = '';
if(!isset($_SESSION['arrSession'])){
	 wp_redirect($page_link.'page_redirect='.$path_page);
	 exit();
} else {

	if(!$_SESSION['arrUpdate']){
		?>
		<script>window.location = "<?php echo get_permalink(get_page_by_path('nakama-member-edit')->ID); ?>"; </script>
		<?php
	}
	else {
		$dataUpdate = $_SESSION['arrUpdate'];
		$arrElement = isset($dataUpdate['arrElement']) ? json_decode(stripslashes($dataUpdate['arrElement']), true) : array();
		$arrElementBefore = isset($dataUpdate['arrElementBefore']) ? json_decode(stripslashes($dataUpdate['arrElementBefore']), true) : array();
		$check_input_open = isset($dataConfirm['check_input_open']) ? $dataConfirm['check_input_open'] : null;
		$count = count($arrElement);
		for ($i=1; $i < $count; $i++) {
			$arrElement[$i]['value_color'] = '';
			$arrElement[$i]['select_color'] = '';
			$arrElement[$i]['new_color'] = '';
			if(empty($arrElementBefore[$i]['value'])){
				if(!empty($arrElement[$i]['value'])){
					$arrElement[$i]['value_color'] = 'RegItem_add';
				}
			}else{
				if(empty($arrElement[$i]['value'])){
					$arrElement[$i]['value_color'] = 'RegItem_del';
				}elseif($arrElement[$i]['value'] != $arrElementBefore[$i]['value']){
					$arrElement[$i]['value_color'] = 'RegItem_chg';
				}else{
					$arrElement[$i]['value_color'] = 'RegItem';
				}
			}

			if(empty($arrElementBefore[$i]['selected'])){
				if(!empty($arrElement[$i]['selected'])){
					$arrElement[$i]['select_color'] = 'RegValue_add';
				}
			}else{
				if(empty($arrElement[$i]['selected'])){
					$arrElement[$i]['select_color'] = 'RegValue_del';
				}elseif($arrElement[$i]['selected'] != $arrElementBefore[$i]['selected']){
					$arrElement[$i]['select_color'] = 'RegValue_chg';
				}else{
					$arrElement[$i]['select_color'] = 'RegValue';
				}
			}

			if(empty($arrElementBefore[$i]['new'])){
				if(!empty($arrElement[$i]['new'])){
					$arrElement[$i]['new_color'] = 'RegValue_add';
				}
			}else{
				if(empty($arrElement[$i]['new'])){
					$arrElement[$i]['new_color'] = 'RegValue_del';
				}elseif($arrElement[$i]['new'] != $arrElementBefore[$i]['new']){
					$arrElement[$i]['new_color'] = 'RegValue_chg';
				}else{
					$arrElement[$i]['new_color'] = 'RegValue';
				}
			}
		}
	}
	if($_POST) {
		$post_id = $dataUpdate['post_id'];
		$logins = $_SESSION['arrSession'];
		if($_POST['release'] == '1'){
			$rsDeleteMember = $members->DeleteDataMember($post_id);
			if($rsDeleteMember->status == "Success"){
				$SendReleaseMail = $members->SendReleaseMail($post_id, $logins->TG_ID, $logins->P_ID, $logins->LG_ID, $logins->G_ID,  $logins->C_EMAIL);
				session_unset();
				?>
				<script>window.location = "<?php echo get_permalink(get_page_by_path('nakama-update-complete')->ID); ?>"; </script>
				<?php
			}
		}
		else{
			$post_id = $dataUpdate['post_id'];
			$rsUpdate = $members->updateMember($dataUpdate, $post_id);
			if($rsUpdate->status == "Success"){
				$SendMailMember = $members->SendMailMember($post_id, $logins->TG_ID, $logins->P_ID, $logins->LG_ID, $logins->G_ID,true);
				$mailTo = get_post_meta($post_id, 'mail_address', true);
				if(!empty($mailTo)){
					$SendMailAdmin = $members->SendMailAdmin($post_id, $logins->TG_ID, $logins->P_ID,  $logins->LG_ID, $logins->G_ID, 1, $mailTo,true);
				}
				unset($_SESSION['arrUpdate']);
				unset($_SESSION['flag_Group']);
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
				<script>window.location = "<?php echo get_permalink(get_page_by_path('nakama-update-complete')->ID); ?>"; </script>
				<?php
			}else{
				$err = isset($rsUpdate->Message) ? $rsUpdate->Message : "";
			}
		}
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
	<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/update_confirm.js"></script>
	<style type="text/css">
	.LargeItem {
		font-size: 18pt;
		font-weight: bold;
	}
	.red{
		color : red;
	}
</style>
</head>
<body>
	<div class="container" style="max-width: 1140px;">
		<div align="center">
			<h1 class="page_title">入力内容の確認</h1>
		</div>
		<br>
		<p class="red"><?php echo $err; ?></p>
		<br>
		<form name="mainForm" id="mainForm" method="post" style="width: 100%;">
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
			<?php if($dataUpdate['release'] != '1'): ?>
			<table align="center" border="0" style="width: 100%;">
				<tbody>
					<tr>
						<td align="center">
							<input type="button" class="base_button" value="送信する" onclick="OnReg();">
						</td>
					</tr>
				</tbody>
			</table>
			<?php endif; ?>
			<?php if($dataUpdate['release'] != '1'): ?>
			<div>

			</div>
			<br>
			<p class="text-left">
			入力項目の背景色は前回入力内容との比較です。
		</p>
		<p class="text-left">
			<span style="color:#CCCCFF;">■</span>：追加　<span style="color:#CCFFCC;">■</span>：変更　<span style="color:#FFCCFF;">■</span>：削除
		</p>
			<table class="input_table" align="center" cellspacing="0" cellpadding="3">
				<tbody>
					<tr>
						<td class="RegField" colspan="2">グループ名</td>
						<td colspan="2" class="RegField"><?php echo (isset($_SESSION['LG_NAME']))?$_SESSION['LG_NAME']:""; ?></td>
					</tr>
				</tbody>
			</table><br>
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
						for ($i=1; $i < $count; $i++) :
					?>
					<?php if(empty($arrElement[$i]['new'])) : ?>
					<tr>
						<?php if(!empty($arrElement[$i]['group']) || !empty($arrElement[$i]['rowspan'])) : ?>
						<td class="RegGroup" rowspan="<?php echo $arrElement[$i]['rowspan']; ?>" width="100"><?php echo $arrElement[$i]['group']; ?></td>
						<?php endif; ?>
						<td colspan="1" class="<?php echo !empty($arrElement[$i]['value_color'])? $arrElement[$i]['value_color'] :"RegItem"?>" nowrap=""><?php echo $arrElement[$i]['label']; ?></td>
						<td class="RegValue "><?php echo $arrElement[$i]['value']; ?></td>
						<?php if($check_input_open == 1) : ?>
						<td class="<?php echo !empty($arrElement[$i]['select_color'])?$arrElement[$i]['select_color']:"RegValue"?>"><?php echo $arrElement[$i]['selected']; ?></td>
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
					<td class="<?php echo !empty($arrElement[count($arrElement)-1]['value_color'])?$arrElement[count($arrElement)-1]['value_color']:"RegValue"?>" colspan="3">
						<textarea style="ime-mode:active; width:100%; line-height:150%;" cols="98" rows="8" name="M_CONNECTION" readonly><?php echo $arrElement[count($arrElement)-1]['value']; ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<?php endif ?>
		<?php endif ?>
		<?php if($dataUpdate['release'] == '1'): ?>
		<center><br><br><br><br>
      <font size="3">
        会員解除されますと会からのサービスはすべて停止となりますがよろしいですか？<br><br>
        よろしければ「送信する」をクリックしてください。
      </font>
		</center>
		<?php endif ?>
		<br>
		<center>
			<input type="button" class="base_button" value="送信する" onclick="OnReg();">
		</center>
		<br>
		<input type="hidden" name="useRegisteredGid" value="">
		<input type="hidden" name="G_G_ID" value="">
		<input type="hidden" name="useRegisteredPid" value="">
		<input type="hidden" name="P_P_ID" value="">
		<input type="hidden" name="M_LG_G_ID" value="">
		<input type="hidden" name="m_lg_g_id_old" value="">
		<input type="hidden" name="g_g_id_old" value="">
		<input type="hidden" name="m_reinput" value="1">
		<input type="hidden" name="m_chg" value="1">
		<input type="hidden" name="m_saveHist" value="1">
		<input type="hidden" name="forward_mail" value="">
		<input type="hidden" name="release" value="<?php echo $dataUpdate['release']; ?>">
		<input type="hidden" name="mail_flg" value="0">
		<input type="hidden" name="set_lg_g_id" value="dmshibuya">
		<input type="hidden" name="set_g_id" value="">
		<input type="hidden" name="m_curImgG" value="">
		<input type="hidden" name="m_delImgG" value="">
		<input type="hidden" name="m_curLogoG" value="">
		<input type="hidden" name="m_delLogoG" value="">
		<input type="hidden" name="m_curImgP" value="Masato02.jpg">
		<input type="hidden" name="m_delImgP" value="">
		<input type="hidden" name="m_curImgP2" value="KEDUKA.jpg">
		<input type="hidden" name="m_delImgP2" value="">
		<input type="hidden" name="m_curImgP3" value="igaiga.jpg">
		<input type="hidden" name="m_delImgP3" value="">
		<input type="hidden" name="m_foundImperialG" value="">
		<input type="hidden" name="m_birthImperialP" value="">
		<input type="hidden" name="m_mournStartImperialP" value="">
		<input type="hidden" name="m_mournEndImperialP" value="">
		<input type="hidden" name="m_admImperialM" value="">
		<input type="hidden" name="m_witImperialM" value="">
		<input type="hidden" name="m_chaImperialM" value="">
		<input type="hidden" name="flgFee" value="">
		<input type="hidden" name="k_top_gid" value="dmshibuya">
		<input type="hidden" name="k_gid" value="dmshibuyag0001">
		<input type="hidden" name="k_pid" value="dmshibuyap0001">
		<input type="hidden" name="g_sta" value="東京都">
		<input type="hidden" name="g_name" value="（株）恵比寿フラワーショップ（デモ）">
		<input type="hidden" name="p_name" value="岡野　アイ">
		<input type="hidden" name="useNoneGid" value="">
		<input type="hidden" name="useExistGid" value="1">
		<input type="hidden" name="page_no" value="51">
		<input type="hidden" name="patten_cd" value="">


		<input type="hidden" name="ActiveRf" value="0">
		<input type="hidden" name="NoneRMf" value="0">


		<input type="hidden" name="elist" value="">
		<input type="hidden" name="connection" value="">
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
