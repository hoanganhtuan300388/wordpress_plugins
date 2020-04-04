<?php
  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/config/constant.php'); 
  require_once(__ROOT__.'/controller/memberController.php'); 
  $members = new memberController();
  $getCategoryMember = isset($_GET['zipcode'])?($_GET['zipcode']):"";
  $post_id = ($_GET['post_id'])?($_GET['post_id']):"";
  $category_code = '';
	$category_name = '';
	$category_type = 1;
	$category_type = $members->GetCategoryType($post_id);
  $per_page = !empty(get_option('nakama-member-general-per-page')) ? get_option('nakama-member-general-per-page'): 100;
  $current_page = !empty($_POST['current_page_list'])?$_POST['current_page_list']:"1";
  $page_no = $current_page - 1;
  $category_code_sort = "▲";
  $order_by = '';
  $order_old = '';
  $collum_order = '';
	$category_name_sort = '';
	$selected_major_code = '';
  $selected_middle_code = '';
  $selected_minor_code = '';
  $m_CType = '';
  if($_POST){
    
    $collum_order = $_POST['collum_order'];
    $category_code_sort = $_POST['category_code_sort'];
    $category_name_sort = $_POST['category_name_sort'];
    $order_by = $_POST['order_by'];
		$order_old = $_POST['order_old'];
		if($category_type == 0){
			$selected_major_code = $_POST['major_code'];
			$selected_middle_code = $_POST['middle_code'];
			$selected_minor_code = $_POST['minor_code'];
			$m_CType = $_POST['C_Type'];
		}else {
			$category_code = trim($_POST['category_code']);
    	$category_name = $_POST['category_name'];
		}
	}
	if($category_type == 1){
		$rs = $members->getCategoryMember($post_id, $category_code, $category_name, $per_page, $page_no, $collum_order, $order_by);
	}else{
		$rs = $members->SearchCategory($post_id, $selected_major_code, $selected_middle_code, $selected_minor_code,$m_CType, $per_page, $page_no, $collum_order, $order_by);
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
				  	if(order_column == "INDUSTRY_CD"){
				  		document.mainForm.category_code_sort.value = "▲";
				  		document.mainForm.category_name_sort.value = "";
				  	}else{
				  		document.mainForm.category_name_sort.value = "▲";
				  		document.mainForm.category_code_sort.value = "";
				  	}
				}else{
				  	document.mainForm.order_by.value = "DESC";
				  	if(order_column == "INDUSTRY_CD"){
				  		document.mainForm.category_code_sort.value = "▼";
				  		document.mainForm.category_name_sort.value = "";
				  	}else{
				  		document.mainForm.category_name_sort.value = "▼";
				  		document.mainForm.category_code_sort.value = "";
				  	}
				}
			}else{
				document.mainForm.order_by.value = "ASC";
			  	if(order_column == "INDUSTRY_CD"){
			  		document.mainForm.category_code_sort.value = "▲";
			  		document.mainForm.category_name_sort.value = "";
			  	}else{
			  		document.mainForm.category_name_sort.value = "▲";
			  		document.mainForm.category_code_sort.value = "";
			  	}
			}
		  	document.mainForm.order_old.value = order_column;
		  	document.mainForm.submit();
		}
		function OnSelectMajor(){
		  document.mainForm.middle_code.value = "";
		  document.mainForm.minor_code.value  = "";
		  document.mainForm.mode.value        = "select";
		  document.mainForm.page_no.value     = 1;
		  document.mainForm.submit();
		}
		function OnSelectMiddle(){
		  document.mainForm.minor_code.value  = "";
		  document.mainForm.mode.value        = "select";
		  document.mainForm.page_no.value     = 1;
		  document.mainForm.submit();
		}
		function OnSearch(value){
		  document.mainForm.mode.value    = "select";
		  document.mainForm.page_no.value = 1;
		  document.mainForm.submit();
		}
		function OnLoad(){
		var minorLen = 2;
		var middleLen = 0;
		  if(document.mainForm.mode.value == ""){
		    var category = window.opener.document.mainForm.G_INDUSTRY_CD.value;
		    document.mainForm.category_code.value = category;

		    switch(category.length){
		    case 5:
		      minorLen = 3;
		      middleLen = 1;
		    case 4:
		    case 3:
		      if(document.mainForm.minor_code != undefined){
		        document.mainForm.minor_code.options[0].value = category.substr(minorLen, 1);
		      }
		    case 2:
		      if(document.mainForm.middle_code != undefined){
		        document.mainForm.middle_code.options[0].value = category.substr(middleLen, 2);
		      }
		      break;
		    default:
		      break;
		    }

		    document.mainForm.mode.value = "select";
		    document.mainForm.submit();
		  }
		}
		function OnSel(code, name, major, middle, minor){
		  if (window.opener.document.mainForm.G_INDUSTRY_CD != undefined){
		    window.opener.document.mainForm.G_INDUSTRY_CD.value = code;
		  }
		  if (window.opener.document.mainForm.G_INDUSTRY_NM != undefined){
		    window.opener.document.mainForm.G_INDUSTRY_NM.value = name;
		  }
		  window.close();
		}
		function selectData(){
		  mainForm.mode.value = "select";
		  mainForm.submit();
		}
		function selectData2(val){
		  switch(val){
		   case 3:
		    document.mainForm.middle_code.value = "";
		    document.mainForm.minor_code.value = "";
		    break;
		   case 2:
		    document.mainForm.minor_code.value = "";
		    break;
		   case 1:
		    break;
		   case 0:
		    break;
		  }
		  mainForm.mode.value = "select";
		  document.mainForm.page_no.value = 1;
		  mainForm.submit();
		}
		function selectAllData1(){
		  document.mainForm.major_code.value = "";
		  document.mainForm.middle_code.value = "";
		  document.mainForm.minor_code.value = "";
		  document.mainForm.mode.value    = "select";
		  document.mainForm.page_no.value = 1;
		  document.mainForm.submit();
		}
		function selectAllData2(){
		  document.mainForm.category_code.value = "";
		  document.mainForm.category_name.value = "";
		  document.mainForm.mode.value = "select";
		  document.mainForm.submit();
		}
		//-->
	</script>
   </head>
   <body onload="OnLoad();">
      <form id="mainForm" name="mainForm" method="post" action="">
		   <table width="100%" border="0" cellspacing="0" cellpadding="0">
		      <tbody>
		         <tr bgcolor="#CACEF9">
		            <td>&nbsp;&nbsp;&nbsp;&nbsp;<b>業種コード検索</b></td>
		         </tr>
		         <tr>
		            <td align="right"><input type="button" value="閉じる" onclick="JavaScript:window.close();"></td>
		         </tr>
		      </tbody>
		   </table>
		   <table width="100%" border="0" cellspacing="0" cellpadding="0">
		      <tbody>
						<?php if($category_type == 1): ?>
		         <tr>
		            <td width="60">業種コード</td>
		            <td>
		               <input type="text" name="category_code" maxlength="6" size="6" value="<?php echo $category_code; ?>">
		            </td>
		         </tr>
		         <tr>
		            <td>業種名</td>
		            <td>
		               <input type="text" name="category_name" value="<?php echo $category_name; ?>">
		               &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="検索" onclick="selectData();">
		               &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value=" 全業種表示 " onclick="selectAllData2();">
		            </td>
						 </tr>
						<?php else: ?>
							<tr>
		            <td width="60">大分類</td>
		            <td>
		               <select name="major_code" onChange="OnSelectMajor();" style="width: 50%" class="select_category">
										 <option value=""></option>
										 <?php echo $members->SetCategoryCode($post_id,1,"","",$selected_major_code); ?>
									 </select>
		            </td>
		         	</tr>
							<tr>
		            <td width="60">中分類</td>
		            <td>
		               <select name="middle_code" onChange="OnSelectMiddle();" style="width: 50%" class="select_category">
										 <option value=""></option>
										 <?php echo $members->SetCategoryCode($post_id,2,$selected_major_code,"",$selected_middle_code); ?>
									 </select>
		            </td>
		         	</tr>
							<tr>
		            <td width="60">小分類</td>
		            <td>
		               <select name="minor_code" onChange="OnSearch(this.value);" style="width: 50%" class="select_category">
										 <option value=""></option>
										 <?php echo $members->SetCategoryCode($post_id,3,$selected_major_code,$selected_middle_code,$selected_minor_code); ?>
									 </select>
		            </td>
							 </tr>
							 <tr>
								<td></td>
								<td>
									<label><input type="radio" name="C_Type" value="3" onClick="Javascript: selectData2(3);" <?php echo ($m_CType == 3)?"checked":""; ?>>大分類</label>
									<label><input type="radio" name="C_Type" value="2" onClick="Javascript: selectData2(2);" <?php echo ($m_CType == 2)?"checked":""; ?>>中分類</label>
									<label><input type="radio" name="C_Type" value="1" onClick="Javascript: selectData2(1);" <?php echo ($m_CType == 1)?"checked":""; ?>>小分類</label>
									<label><input type="radio" name="C_Type" value="0" onClick="Javascript: selectData2(0);" <?php echo ($m_CType == 0)?"checked":""; ?>>細目</label>
									&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value=" 全業種表示 " onClick="selectAllData1();">
								</td>
							</tr>
						<?php endif;?>
		      </tbody>
		   </table>
		   <br>
		   <br>
		   <table border="0" width="100%">
		      <tbody>
		         <tr class="ListOperation">
		            <td>【該当件数：<?php echo $rs->COUNT; ?>件】</td>
		            <td align="right"><?php if ($pagination['total_page'] > 1 && $pagination['current_page'] != 1){ ?> 
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
		               <div style="white-space: nowrap;"><a href="JavaScript:orderProc('INDUSTRY_CD')"><span id="category_code"><?php echo $category_code_sort; ?></span>業種コード</a></div>
		            </td>
		            <td align="center">
		               <div style="white-space: nowrap;"><a href="JavaScript:orderProc('INDUSTRY_NM')"><span id="category_name"><?php echo $category_name_sort; ?></span>業種</a></div>
		            </td>
		         </tr>
		         <?php
		         	echo '<pre>';
		         	foreach ($rs->DATA as $key => $item) { 
		         		$key = $key+1;
		         		?>
		         		<tr class="ListRow<?php echo ($key % 2 == 0)?'2':'1';?>">
		         			<td align="center"><a href="JavaScript:OnSel('<?php echo $item->INDUSTRY_CD; ?>','<?php echo $item->INDUSTRY_NM; ?>','','','');"><?php echo $item->INDUSTRY_CD; ?></a></td>
		            		<td><?php echo $item->INDUSTRY_NM; ?></td>
		            	</tr>
		         	<?php }?>
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
		   <input type="hidden" name="formName" value="mainForm.G_INDUSTRY_NM">
		   <input type="hidden" name="page_no" value="1">
           <input type="hidden" name="current_page_list" value="<?php echo $current_page; ?>">
           <input type="hidden" name="collum_order" value="<?php echo $collum_order; ?>">
           <input type="hidden" name="order_by" value="<?php echo $order_by; ?>">
           <input type="hidden" name="category_code_sort" value="<?php echo $category_code_sort; ?>">
           <input type="hidden" name="category_name_sort" value="<?php echo $category_name_sort; ?>">
           <input type="hidden" name="order_old" value="<?php echo $order_old; ?>">
		</form>
   </body>
</html>
