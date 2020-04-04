<?php
   define('__ROOT__', dirname(dirname(__FILE__))); 
   require_once(__ROOT__.'/config/constant.php'); 
   require_once(__ROOT__.'/controller/memberController.php');
   $tg_id = $_GET['tg_id'];
   $p_id = $_GET['p_id'];
   $post_id = $_GET['post_id'];
   $c_name = $_GET['c_name'];
   $members = new memberController();
   $arTitle = $members->SelectTitleN($post_id, $tg_id);
   $feeStatus = $members->feeStatus($post_id, $tg_id, $p_id);
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
   </head>
   <body onload="OnLoad();">
      <form id="mainForm" name="mainForm" method="post" action="">
			<table width="100%">
			  <tbody><tr>
			    <td><font size="4" color="#FF9900"><b>■請求状況</b></font></td>
			    <td align="right">
			      <input type="button" value="閉じる" onclick="window.close();">&nbsp;&nbsp;&nbsp;&nbsp;
			    </td>
			  </tr>
			  <tr>
			    <td>
			      <b><?php echo $c_name; ?>さん</b>
			    </td>
			  </tr>
			</tbody></table>
			<br>
			<div align="left">
			過去５年間の請求、入金状況です。
			（※未納分がある場合は全て表示されます。）
			</div>
			<br>
			<center>
			<?php 
				$listData = $feeStatus->LIST_DATA; 
				if(count($listData > 0)){
					$zanFee = !empty($listData[0]->ZAN_FEE)?$listData[0]->ZAN_FEE:"";
					$zanReceipt = !empty($listData[0]->ZAN_RECEIPT)?$listData[0]->ZAN_RECEIPT:"";
				}
				$sumFee = ($feeStatus->SUM_FEE != NULL)?$feeStatus->SUM_FEE:"0";
				$sumReceipt = ($feeStatus->SUM_RECEIPT != NULL)?$feeStatus->SUM_RECEIPT:"0";
				$zanFee = ($zanFee)?$zanFee:"0";
				$zanReceipt = ($zanReceipt)?$zanReceipt:"0";
			?>
			<table border="0" width="100%" cellspacing="0" cellpadding="1">
			  <tbody>
			  	<tr>
				    <td></td>
				    <td align="right" width="300">
				      	<table class="ListCategory" border="0" width="300" cellspacing="1" cellpadding="3">
					        <tbody>
					        	<tr class="ListHeader">
						          	<td align="left" width="100" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitleEnd($arTitle, "fee_d請求額")), "請求額");?></td>
						          	<td align="left" width="100" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitleEnd($arTitle, "fee_d入金額")), "入金額");?></td>
						          	<td align="left" width="100" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitleEnd($arTitle, "fee_d過不足金額")), "過不足額");?></td>
					        	</tr>
					        	<tr class="ListRow2">
						          	<td align="right"><?php echo number_format($sumFee, 0); ?></td>
							        <td align="right"><?php echo number_format($sumReceipt, 0); ?></td>
							        <td align="right"><?php echo number_format(($sumReceipt - $sumFee + $zanReceipt - $zanFee), 0); ?></td>
					        	</tr>
						    </tbody>
						</table>
				    </td>
			  	</tr>
			</tbody></table>
			<br>
			<div class="main-list main-table-scroll">
				<table id="list">
				  <thead>
				  	<tr class="ListHeaderMemberList">
					    <td class="first">No</td>
					    <td class="second" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitleEnd($arTitle, "fee_d会費名")), "会費名");?></td>
					    <td nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitleEnd($arTitle, "下部組織名")), "グループ名");?></td>
					    <td nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitleEnd($arTitle, "組織名")), "組織名");?></td>
					    <td align="center" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitleEnd($arTitle, "fee_d請求期間")), "請求期間");?></td>
					    <td align="center" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitleEnd($arTitle, "fee_d請求区分")), "区分");?></td>
					    <td nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitleEnd($arTitle, "fee_d請求コード")), "請求コード");?></td>
					    <td align="center" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitleEnd($arTitle, "fee_d請求年月日")), "請求日");?></td>
					    <td align="right" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitleEnd($arTitle, "fee_d請求額")), "請求額");?></td>
					    <td align="center" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitleEnd($arTitle, "fee_d入金年月日")), "入金日");?></td>
					    <td align="right" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitleEnd($arTitle, "fee_d入金額")), "入金額");?></td>
					    <td align="right" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitleEnd($arTitle, "fee_d過不足金額")), "過不足額");?></td>
					    <td align="right" nowrap=""><?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitleEnd($arTitle, "fee_d充当額")), "充当額");?></td>
				  	</tr>
				  	<?php $lngCnt = 1; ?>
					<?php
					if($feeStatus->LIST_DATA):
						foreach ($feeStatus->LIST_DATA as $key => $item) { 
							if($item->receipt_status == 0){
								$strFont1 = '';
								$strFont2 = '';
							}else{
								$strFont1 = '<font color="red">';
								$strFont2 = '</font>';
							}
						?>
								<tr class="<?php echo (($lngCnt/2)!= 0)?"ListRow1":"ListRow2"; ?>">
								    <td><?php echo $strFont1.$lngCnt.$strFont2; ?></td>
								    <td><?php echo $strFont1.$item->FEE_NM.$strFont2; ?></td>
								    <?php $lg_name = ($item->LG_NAME)?$item->LG_NAME:""; ?>
								    <td><?php echo $strFont1.$LG_NAME.$strFont2 ?></td>
								    <?php $g_name = ($item->G_NAME)?$item->G_NAME:""; ?>
								    <td><?php echo $strFont1.$g_name.$strFont2; ?></td>
								    <td align="center"><?php echo $strFont1.$item->CLAIM_START."～".$item->CLAIM_END.$strFont2; ?></td>
								    <?php if($feeStatus->STATUS == '1' && $item->CLAIM_CLS == '銀振'): ?>
								    <td align="center"><?php echo $strFont1."郵振".$strFont2; ?></td>
									<?php else: ?>
								    <td align="center"><?php echo $strFont1.$item->CLAIM_CLS.$strFont2 ?></td>
									<?php endif; ?>
								    <td><?php echo $strFont1.$item->CLAIM_CD.$strFont2; ?></td>
								    <td align="center"><?php echo $strFont1.$item->CLAIM_DATE.$strFont2; ?></td>
								    <td align="right"><?php echo $strFont1.number_format($item->FEE,0).$strFont2; ?></td>
								    <td align="center"></td>
								    <td align="right"><?php echo $strFont1.number_format($item->RECEIPT,0).$strFont2; ?></td>
								    <td align="right" nowrap><?php echo $strFont1.number_format(($item->RECEIPT - $item->FEE),0).$strFont2; ?></td>
								    <td align="right"><?php echo $strFont1.number_format(($item->APPROPRIATION),0).$strFont2; ?></td>
								</tr>
						<?php $lngCnt = $lngCnt + 1; 
						}
					endif;
					?>
				</thead>
				<tbody>
					<tr class="Position">
					    <td></td>
					    <td></td>
					    <td></td>
					    <td></td>
					    <td></td>
					    <td></td>
					    <td align="right">　</td>
					    <td align="right"><?php echo $members->convertNvl(str_replace("<br>", "",$members->renderTitleEnd($arTitle, "fee_d前回残高")), "前回残");?></td> 
					    <td align="right">　</td>
					    <td align="right">　</td>
					    <td align="right">　</td>
					    <td align="right"><?php echo number_format(($zanReceipt - $zanFee),0); ?></td>
					    <td>　</td>
					</tr>
				</tbody>
				</table>
			</div>

			</center>
			</form>
   </body>
</html>
