<?php
   define('__ROOT__', dirname(dirname(__FILE__))); 
   require_once(__ROOT__.'/config/constant.php'); 
   require_once(__ROOT__.'/controller/memberController.php'); 
   $members = new memberController();
   $postid = ($_GET['post_id'])?$_GET['post_id']:"";
   $rs = array();
   $tg_id = !empty(get_post_meta($post_id, 'member_meta_group_id', true)) ? get_post_meta($post_id, 'member_meta_group_id', true) : get_option('nakama-member-group-id');
   $per_page = !empty(get_option('nakama-member-general-per-page')) ? get_option('nakama-member-general-per-page'): 100;
   $current_page = !empty($_POST['current_page_list'])?$_POST['current_page_list']:"1";
   $page_no = $current_page - 1;
   $key_work = '';
   $lg_name_sort = "▲";
   $order_by = '';
   $order_old = '';
   if($_POST){
   	$key_work = $_POST['input_keyword'];
   	$lg_name_sort = $_POST['lg_name_sort'];
   	$order_by = $_POST['order_by'];
   	$collum_order = $_POST['collum_order'];
   	$order_old = $_POST['order_old'];
   	$rs = $members->getSearchLg($postid, $key_work, $per_page, $page_no, $collum_order, $order_by, $tg_id);
   }
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
					  	if(order_column == "LG_NAME"){
					  		document.mainForm.lg_name_sort.value = "▲";
					  	}
					}else{
					  	document.mainForm.order_by.value = "DESC";
					  	if(order_column == "LG_NAME"){
					  		document.mainForm.lg_name_sort.value = "▼";
					  	}
					}
				}else{
					document.mainForm.order_by.value = "ASC";
				  	if(order_column == "LG_NAME"){
				  		document.mainForm.lg_name_sort.value = "▲";
				  	}
				}
			  	document.mainForm.order_old.value = order_column;
			  	document.mainForm.submit();
			}
			function OnSearch(){
			  document.mainForm.submit();
			}
			function OnSel(val){
			  if (window.opener.document.mainForm.M_LG_G_ID_SEL != undefined){
			    window.opener.document.mainForm.M_LG_G_ID_SEL.value = val;
			    var obj = window.opener.document.mainForm.M_LG_G_ID_SEL
			    if (window.opener.document.mainForm.M_LG_NAME != undefined){
			      window.opener.document.mainForm.M_LG_NAME.value = obj.options[obj.selectedIndex].text;
			    }
			  }
			  window.opener.document.mainForm.M_LG_G_ID.value = val;
			  window.close();
			}
	  </script>
   </head>
   <body onload="OnLoad();">
      <form id="mainForm" name="mainForm" method="post" action="">
		  	<div align="center">
		    	<h1 class="page_title" style="width:100%">グループ名検索</h1>
		  	</div><br><br>
		  	<table width="70%" align="center" border="1" cellspacing="2" cellpadding="3" bordercolor="#C0C0C0">
		    <tbody><tr>
		      <td class="RegField" colspan="2">
		        <b>検索条件を入力してください。</b>
		      </td>
		    </tr>
		    <tr>
		      <td class="RegField">
		        キーワード
		      </td>
		      <td class="RegGroup">
		        <input type="text" name="input_keyword" value="<?php echo $key_work; ?>" size="80">
		      </td>
		    </tr>
		  	</tbody></table><br>
			<center>
			    <input type="button" value="閉じる" onclick="JavaScript:window.close();">&nbsp;&nbsp;&nbsp;&nbsp;
			    <input type="button" value="検　索" onclick="JavaScript:OnSearch();">
			</center>
			<br>
			<?php if($rs->DATA): ?>
				<table border="0" width="100%">
				   <tbody>
				      <tr class="ListOperation">
				         <td>【該当件数：1件】</td>
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

				<table border="1" width="100%" cellspacing="2" celpadding="3" bordercolor="#C0C0C0">
				   <tbody>
				      	<tr class="RegField">
				         <td width="50">&nbsp;</td>
				         <td align="center" width="50" nowrap="">No</td>
				         <td align="left" valign="top" width="660">
				            <div style="white-space: nowrap;"><a href="JavaScript:orderProc('LG_NAME')"><span id="category_code"><?php echo $lg_name_sort; ?>グループ名</a></div>
				         </td>
				      	</tr>
				    <?php foreach ($rs->DATA as $key => $item) { ?>
				      	<tr class="RegGroup">
					        <td align="center"><a href="JavaScript:OnSel('<?php echo $item->LG_ID; ?>');">選択</a></td>
					        <td align="right"><?php echo $key+1; ?></td>
					        <td align="left"><?php echo $item->LG_NAME; ?></td>
					    </tr>
				     <?php } ?>
				      
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
				<?php endif; ?>
			   	<input type="hidden" name="top_g_id" value="">
			   	<input type="hidden" name="formName" value="mainForm.M_LG_G_ID">
			   	<input type="hidden" name="page_no" value="1">
			   	<input type="hidden" name="lg_name_sort" value="<?php echo $lg_name_sort; ?>">
			   	<input type="hidden" name="current_page_list" value="<?php echo $current_page; ?>">
			   	<input type="hidden" name="order_by" value="<?php echo $order_by; ?>">
			   	<input type="hidden" name="collum_order" value="<?php echo $collum_order; ?>">
			   	<input type="hidden" name="order_old" value="<?php echo $order_old; ?>">
			
		</form>
   </body>
</html>