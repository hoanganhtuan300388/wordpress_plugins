<?php
   define('__ROOT__', dirname(dirname(__FILE__))); 
   require_once(__ROOT__.'/config/constant.php'); 
   require_once(__ROOT__.'/controller/memberController.php'); 
   $members = new memberController();
   $zipcode = ($_GET['zipcode'])?($_GET['zipcode']):"";
   $post_id = ($_GET['post_id'])?($_GET['post_id']):"";
   $reZipcode = $members->zipCodeMember($post_id, $zipcode);
	$reZipcode = $reZipcode->data;
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
		function OnSel(index){
		 var val = new Array(4);
		 if (document.mainForm.zipcode.length == undefined){
		  val[0] = document.mainForm.zipcode.value;
		 }else{
		  val[0] = document.mainForm.zipcode[index].value;
		 }
		 if(document.mainForm.sta.length == undefined){
		  val[1] = document.mainForm.sta.value;
		 }else{
		  val[1] = document.mainForm.sta[index].value;
		 }
		 if(document.mainForm.addr.length == undefined){
		  val[2] = document.mainForm.addr.value;
		 }else{
		  val[2] = document.mainForm.addr[index].value;
		 }
		 if(document.mainForm.addr2.length == undefined){
		  val[3] = document.mainForm.addr2.value;
		 }else{
		  val[3] = document.mainForm.addr2[index].value;
		 }
		 var flag = "<?php echo $_GET['flag']?>";
		 if(flag != 0){
		 	if(flag == 1){
		 		window.opener.OnZipCallbackP(val[0], val[1], val[2], val[3]);
		 	}else if(flag == 2){
		 		window.opener.OnZipCallbackG(val[0], val[1], val[2], val[3]);
		 	}else if(flag == 3){
		 		window.opener.OnZipCallbackPP(val[0], val[1], val[2], val[3]);
		 	}else if(flag == 4){
		 		window.opener.OnZipCallbackCl(val[0], val[1], val[2], val[3]);
		 	}else if(flag == 5){
		 		window.opener.OnZipCallbackCo(val[0], val[1], val[2], val[3]);
		 	}
		 }
		 window.close();
		}
		function OnLoad(){
		 window.focus();
		}
		</script>
   </head>
   <body onload="OnLoad();">
      <form id="mainForm" name="mainForm" method="post" action="SelAddr.asp">
         <center>
         	<?php $zipcodesub = substr($zipcode, 0, 3)."-".substr($zipcode, 3, 4); ?>
            <?php echo $zipcodesub; ?> - の登録住所は <?php echo count($reZipcode); ?> 件あります。<br>
            使用する住所を選択してください。<br>
         </center>
         <br>
         <center><input type="button" value="閉じる" onclick="window.close();"></center>
         <br>
         <table border="0" width="100%" cellspacing="2" cellpadding="2">
            <tbody>
               	<tr class="ListHeader">
                  <td width="40"><br></td>
                  <td width="40">No</td>
                  <td>郵便番号</td>
                  <td>住所</td>
               	</tr>

               	<?php $i = 0; foreach ($reZipcode as $k => $item) { ?>
               		<tr class="ListRow1">
	                  <td nowrap=""><a href="javascript:OnSel(<?php echo $i; ?>)">選択</a>
	                  </td>
	                  <td nowrap="">
	                    <?php $i++; echo $i; ?>
	                  </td>
	                  <td nowrap="">
	                    <?php 
	                    $zipcode = substr($item->ZIP_CD, 0, 3)."-".substr($item->ZIP_CD, 3, 4);
	                    echo ($item->ZIP_CD)?$zipcode:""; ?>
	                  </td>
	                  <td nowrap="">
	                    <?php echo $item->STA.$item->ADDRESS.$item->ADDRESS2; ?>
	                  </td>
	                  <input type="hidden" name="zipcode" value="<?php echo $zipcode; ?>">
	                  <input type="hidden" name="sta" value="<?php echo $item->STA; ?>">
	                  <input type="hidden" name="addr" value="<?php echo $item->ADDRESS; ?>">
	                  <input type="hidden" name="addr2" value="<?php echo $item->ADDRESS2; ?>">
	               	</tr>
               	<?php } ?>
               	
            </tbody>
         </table>
         <br>
         <center>
            <input type="button" value="閉じる" onclick="window.close();">
         </center>
      </form>
   </body>
</html>