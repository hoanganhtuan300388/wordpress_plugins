<?php
  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/config/constant.php'); 
  require_once(__ROOT__.'/controller/memberController.php'); 
  $members = new memberController();
  $sel = $_GET['sel'];
  $bank_code = $_GET['bank_code'];
  $bank_name = $_GET['bank_name'];
  $post_id = $_GET['post_id'];
  $tg_id = !empty(get_post_meta($post_id, 'member_meta_group_id', true)) ? get_post_meta($post_id, 'member_meta_group_id', true) : get_option('nakama-member-group-id');
  $per_page = !empty(get_option('nakama-member-general-per-page')) ? get_option('nakama-member-general-per-page'): 100;
  $current_page = !empty($_POST['current_page_list'])?$_POST['current_page_list']:"1";
  $page_no = $current_page - 1;
  $branch_code_sort = "▲";
  $order_by = '';
  $order_old = '';
  $collum_order = '';
  $branch_code = '';
  $branch_name = '';
  $branch_kana  = '';
  $branch_code_sort = '';
  $branch_name_sort = '';
  $branch_kana_sort = '';
  if($_POST){
    $branch_code = $_POST['branch_code'];
    $branch_name = $_POST['branch_name'];
    $branch_kana = $_POST['branch_kana'];
    $branch_code_sort = $_POST['branch_code_sort'];
    $branch_name_sort = $_POST['branch_name_sort'];
    $branch_kana_sort = $_POST['branch_kana_sort'];
    $order_by = $_POST['order_by'];
    $collum_order = $_POST['collum_order'];
    $order_old = $_POST['order_old'];
  }
  $rs = $members->GetBranchInfo($post_id, $tg_id, $bank_name, $bank_code, $per_page, $page_no, $collum_order, $order_by, $branch_code, $branch_name, $branch_kana);
  $count = isset($rs->COUNT) ? $rs->COUNT : '';
  $pagination = $members->paginatesCategorys($count, $current_page, $per_page);
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
      	jQuery(document).ready(function ($) {
	      	$(".next_list").click(function(){
	      		var num_page = $(this).children('.pagination_page').val();
			    $("input[name='current_page_list']").val(num_page);
			    document.mainForm.submit();
			    return false;
	      	});
	      	$(".prev_list").click(function(){
	      		var num_page = $(this).children('.pagination_page_prev').val();
			    $("input[name='current_page_list']").val(num_page);
			    document.mainForm.submit();
			    return false;
	      	});
	    });
      </script>
      <script type="text/javascript">
  		function movePage(page_no){
		  document.mainForm.page_no.value = page_no;
		  document.mainForm.submit();
		}
		function orderProc(order_column){
		  document.mainForm.collum_order.value = order_column;
		  	var order_by = document.mainForm.order_by.value;
		  	var order_old = document.mainForm.order_old.value;
		  	if(order_column == order_old){
			  	if(order_by == "DESC"){
				  	document.mainForm.order_by.value = "ASC";
				  	if(order_column == "BRANCH_CD"){
				  		document.mainForm.branch_code_sort.value = "▲";
				  		document.mainForm.branch_name_sort.value = "";
				  		document.mainForm.branch_kana_sort.value = "";
				  	}else if(order_column == "BRANCH_NM"){
				  		document.mainForm.branch_code_sort.value = "";
				  		document.mainForm.branch_name_sort.value = "▲";
				  		document.mainForm.branch_kana_sort.value = "";
				  	}else{
				  		document.mainForm.branch_code_sort.value = "";
				  		document.mainForm.branch_name_sort.value = "";
				  		document.mainForm.branch_kana_sort.value = "▲";
				  	}
				}else{
				  	document.mainForm.order_by.value = "DESC";
				  	if(order_column == "BRANCH_CD"){
				  		document.mainForm.branch_code_sort.value = "▼";
				  		document.mainForm.branch_name_sort.value = "";
				  		document.mainForm.branch_kana_sort.value = "";
				  	}else if(order_column == "BRANCH_NM"){
				  		document.mainForm.branch_code_sort.value = "";
				  		document.mainForm.branch_name_sort.value = "▼";
				  		document.mainForm.branch_kana_sort.value = "";
				  	}else{
				  		document.mainForm.branch_code_sort.value = "";
				  		document.mainForm.branch_name_sort.value = "";
				  		document.mainForm.branch_kana_sort.value = "▼";
				  	}
				}
			}else{
				document.mainForm.order_by.value = "ASC";
			  	if(order_column == "BRANCH_CD"){
			  		document.mainForm.branch_code_sort.value = "▲";
			  		document.mainForm.branch_name_sort.value = "";
			  		document.mainForm.branch_kana_sort.value = "";
			  	}else if(order_column == "BRANCH_NM"){
			  		document.mainForm.branch_code_sort.value = "";
			  		document.mainForm.branch_name_sort.value = "▲";
			  		document.mainForm.branch_kana_sort.value = "";
			  	}else{
			  		document.mainForm.branch_code_sort.value = "";
			  		document.mainForm.branch_name_sort.value = "";
			  		document.mainForm.branch_kana_sort.value = "▲";
			  	}
			}
		  	document.mainForm.order_old.value = order_column;
		  	document.mainForm.submit();
		}


		function OnSearch(value){
		  document.mainForm.mode.value    = "select";
		  document.mainForm.page_no.value = 1;
		  document.mainForm.submit();
		}


		function OnSel(code){
		  	var sel = document.mainForm.sel_action.value;
		  	if(sel == "G_BANK_CD"){
			  	if (window.opener.document.mainForm.G_BRANCH_CD != undefined){
				    window.opener.document.mainForm.G_BRANCH_CD.value = code;
				}
		  	}
		  	else if(sel == "P_BANK_CD"){
			  	if (window.opener.document.mainForm.P_BRANCH_CD != undefined){
				    window.opener.document.mainForm.P_BRANCH_CD.value = code;
				}
			}else if(sel == "M_BANK_CD"){
				if (window.opener.document.mainForm.M_BRANCH_CD != undefined){
				    window.opener.document.mainForm.M_BRANCH_CD.value = code;
				}
			}
		  window.close();
		}
		function selectData(){

		  
		  if(!IsNarrowKn(document.mainForm.branch_kana.value, "支店名カナ")){
		  }else{
		    return;
		  }
		  mainForm.page_no.value = 1;
		  mainForm.mode.value = "select";
		  mainForm.submit();
		}
		function OnClear(){
		  mainForm.branch_code.value = '';
		  mainForm.branch_name.value = '';
		  mainForm.branch_kana.value = '';
		}

		function OnMouseOver(buttonObj){
		  buttonObj.style.backgroundColor = "#abcdef";
		  buttonObj.style.color = "blue";
		}

		function OnMouseOut(buttonObj){
		  buttonObj.style.backgroundColor = "buttonface";
		  buttonObj.style.color = "buttontext";
		}
	  </script>
   </head>
   <body onload="OnLoad();">
      <form id="mainForm" name="mainForm" method="post" action="">
      		<input type="hidden" class="sel_action" name="sel_action" value="<?php echo $sel; ?>">
		    <table width="100%" border="0" cellspacing="0" cellpadding="0">
		      <tbody>
		         <tr bgcolor="#CACEF9">
		            <td>&nbsp;&nbsp;&nbsp;&nbsp;<b>支店検索</b></td>
		         </tr>
		         <tr>
		            <td align="right"><input type="button" value="閉じる" onclick="JavaScript:window.close();"></td>
		         </tr>
		      </tbody>
		   </table>
		   <table width="100%" border="0" cellspacing="0" cellpadding="0">
		      <tbody>
		         <tr>
		            <td width="60" height="26"><font size="2">銀行名</font></td>
		            <td>
		               ：&nbsp;<b><font size="2"><?php echo $bank_name; ?></font></b>
		            </td>
		         </tr>
		         <tr>
		            <td width="60"><font size="2">支店コード</font></td>
		            <td>
		               <input type="text" style="ime-mode: disabled; margin-bottom: 10px;" name="branch_code" maxlength="3" size="3" value="<?php echo $branch_code; ?>">
		            </td>
		         </tr>
		         <tr>
		            <td><font size="2">支店名</font></td>
		            <td>
		               <input type="text" style="ime-mode: active; margin-bottom: 10px;" name="branch_name" size="60" maxlength="32" value="<?php echo $branch_name; ?>">
		            </td>
		         </tr>
		         <tr>
		            <td><font size="2">支店名カナ</font></td>
		            <td>
		               <input type="text" style="ime-mode: active; margin-bottom: 10px;" name="branch_kana" size="60" maxlength="32" value="<?php echo $branch_kana; ?>">
		               <font size="2">
		               ※半角カナ入力&nbsp;&nbsp;&nbsp;&nbsp;</font>
		               &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value=" 検索 " onclick="selectData();" onmouseover="OnMouseOver(this);" onmouseout="OnMouseOut(this);">
		               &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value=" クリア " onclick="OnClear();" onmouseover="OnMouseOver(this);" onmouseout="OnMouseOut(this);">
		            </td>
		         </tr>
		      </tbody>
		   </table>
		   <br>
		   <table border="0" width="100%">
		      <tbody>
		         <tr class="ListOperation">
		            <td>【該当件数：<?php echo $count; ?>件】</td>
		            <td align="right">
		            	<?php if ($pagination['total_page'] > 1 && $pagination['current_page'] != 1){ ?> 
				         			<a class="prev_list" href="#"> <?php } ?>
				         			<input type="hidden" value="<?php echo $pagination['current_page']-1; ?>" class="pagination_page_prev">
			            			<span>&lt;&lt;前ページ</span>
			            			<?php if ($pagination['total_page'] > 1 && $pagination['current_page'] != 1){ 
			            				?></a><?php 
			            			} ?>
			            			&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $pagination['current_page']; ?> / <?php echo $pagination['total_page']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($pagination['total_page'] > 1 && $pagination['current_page'] < $pagination['total_page']){ ?> 
			            				<a class="next_list" href="#"> <?php } ?>
					                  	<input type="hidden" value="<?php echo $pagination['current_page']+1; ?>" class="pagination_page">
					                  	<span>次ページ&gt;&gt;</span>
					               	<?php if ($pagination['total_page'] > 1  && $pagination['current_page'] < $pagination['total_page']){ ?></a> <?php } ?>
		            </td>
		         </tr>
		      </tbody>
		   </table>
		   <table width="100%" border="0" cellspacing="1" celpadding="0" bordercolor="#C0C0C0">
		      <tbody>
		         <tr class="ListHeader">
		            <td align="center" width="90">
		               <nobr><a href="JavaScript:orderProc('BRANCH_CD')"><span id="category_code"><?php echo $branch_code_sort; ?>支店コード</a></nobr>
		            </td>
		            <td align="center" width="270">
		               <nobr><a href="JavaScript:orderProc('BRANCH_NM')"><span id="category_code"><?php echo $branch_name_sort; ?>支店名</a></nobr>
		            </td>
		            <td align="center">
		               <nobr><a href="JavaScript:orderProc('BRANCH_KN')"><span id="category_code"><?php echo $branch_kana_sort; ?>支店名カナ</a></nobr>
		            </td>
		         </tr>
		         <?php $data = isset($rs->DATA) ? $rs->DATA : "";
		         	if(!empty($data)):
		         		foreach ($data as $key => $item) { ?>
			         		<tr class="ListRow1">
					            <td align="center"><a href="JavaScript:OnSel('<?php echo ($item->BRANCH_CD)?$item->BRANCH_CD:""; ?>');"><?php echo ($item->BRANCH_CD)?$item->BRANCH_CD:""; ?></a></td>
					            <td><?php echo ($item->BRANCH_NM)?$item->BRANCH_NM:""; ?></td>
					            <td><?php echo ($item->BRANCH_KN)?$item->BRANCH_KN:""; ?></td>
					        </tr>
		         <?php 
		         		}
		         	endif;
		         ?>
		         
		      </tbody>
		   </table>
		   <table border="0" width="100%">
		      <tbody>
		         <tr class="ListOperation">
		            <td align="right">
		            	<?php if ($pagination['total_page'] > 1 && $pagination['current_page'] != 1){ ?> 
		         			<a class="prev_list" href="#"> <?php } ?>
		         			<input type="hidden" value="<?php echo $pagination['current_page']-1; ?>" class="pagination_page_prev">
	            			<span>&lt;&lt;前ページ</span>
	            			<?php if ($pagination['total_page'] > 1 && $pagination['current_page'] != 1){ 
	            				?></a><?php 
	            			} ?>
	            			&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $pagination['current_page']; ?> / <?php echo $pagination['total_page']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($pagination['total_page'] > 1 && $pagination['current_page'] < $pagination['total_page']){ ?> 
	            				<a class="next_list" href="#"> <?php } ?>
			                  	<input type="hidden" value="<?php echo $pagination['current_page']+1; ?>" class="pagination_page">
			                  	<span>次ページ&gt;&gt;</span>
			               	<?php if ($pagination['total_page'] > 1  && $pagination['current_page'] < $pagination['total_page']){ ?></a> <?php } ?>
		            </td>
		         </tr>
		      </tbody>
		   </table>
		   <input type="hidden" name="mode" value="select">
		   <input type="hidden" name="formName" value="mainForm.G_BRANCH_CD">
		   <input type="hidden" name="page_no" value="1">
		   <input type="hidden" name="branch_code_sort" value="<?php echo $branch_code_sort; ?>">
		   <input type="hidden" name="branch_name_sort" value="<?php echo $branch_name_sort; ?>">
		   <input type="hidden" name="branch_kana_sort" value="<?php echo $branch_kana_sort; ?>">
		   <input type="hidden" name="current_page_list" value="<?php echo $current_page; ?>">
		   <input type="hidden" name="order_by" value="<?php echo $order_by; ?>">
		   <input type="hidden" name="collum_order" value="<?php echo $collum_order; ?>">
		   <input type="hidden" name="order_old" value="<?php echo $order_old; ?>">
		</form>
   </body>
</html>