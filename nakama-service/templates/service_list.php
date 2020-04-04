<?php
$service = new serviceController();
$tg_id = isset($_GET['top_g_id']) ? $_GET['top_g_id'] : "";
$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : "";
$svc_id = (int) isset($_GET['svc_id']) ? $_GET['svc_id'] : 0;
$category = isset($_GET['category']) ? $_GET['category'] : "";
$keyword = "";
//$dataSetting = get_post_meta($post_id);

//サービス一覧情報取得
$arrBody = array();
$arrBody["TG_ID"] = $tg_id;
$arrBody["SVC_ID"] = $svc_id;
$arrBody["Category"] = $category;
$arrBody["Keyword"] = $keyword;
$service_list = $service->getServiceList($post_id, $arrBody);

/*print "掲載一覧　tmp";
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/config/constant.php');
require_once(__ROOT__.'/controller/serviceController.php');

$service = new serviceController();
$post_id = isset($_REQUEST['post_id'])?$_REQUEST['post_id']: '';
$dataSetting = get_post_meta( $post_id );
$arrBody = array();
$arrBody['TG_ID'] = (!empty($_SESSION['arrSession'])) ? $_SESSION['arrSession']->TG_ID : $dataSetting['nakama_service_param_tg_id'][0];
$arrBody['P_ID'] = (!empty($_SESSION['arrSession'])) ? $_SESSION['arrSession']->P_ID : '';
$arrBody['REPORT_FLG'] = isset($_POST['sch_select']) ? $_POST['sch_select'] : 0;
$arrBody['DIS_ID'] = $dataSetting['nakama_service_param_m_bbsid'][0];
$arrBody['PATTERN_NO'] = 0;
$arrBody['KEYWORD'] = isset($_POST['ForumSearchText']) ? $_POST['ForumSearchText'] : '';
$arrBody['CATEGORY'] = isset($_REQUEST['category']) ? $_REQUEST['category'] : '';
$arrBody['COLLUM_ORDER'] = isset($_POST['f_sort'])?$_POST['f_sort']:"";
$arrBody['ORDER_BY'] = isset($_POST['f_sortorder'])?$_POST['f_sortorder']:"";

$list_article = $service->getListArticles($post_id, $arrBody);

$data = isset($list_article->data) ? $list_article->data : array();
$count = isset($list_article->count) ? $list_article->count : 0;

$lg_type = (!empty($_SESSION['arrSession'])) ? $_SESSION['arrSession']->LG_TYPE : 0;
$category_response = $service->getListCategories($post_id, $arrBody['TG_ID'], $arrBody['P_ID'], $lg_type, $arrBody['DIS_ID'], $arrBody['PATTERN_NO'], '', '', '');
$category_data = isset($category_response->data) ? $category_response->data : array();
$category_count = isset($category_response->count) ? $category_response->count : 0;
$write_thread_open = '';
if(!empty($category_data)) {
	 foreach ($category_data as $key => $item) {
			if($item->CATEGORY == $arrBody['CATEGORY']){
				 $write_thread_open = $item->WRITE_THREAD_OPEN;
			}
	 }
}*/
?>
<title>掲載一覧</title>
<?php get_header(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
<meta name="robots" content="index,follow">
<meta name="Description" content="">
<meta name="KeyWords" content="">
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(__FILE__)) ?>assets/css/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(__FILE__)) ?>assets/css/issues_list/issue_list.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(__FILE__)) ?>assets/css/responsive.css">
<script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__)) ?>assets/js/jquery-1.6.3.min.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__)) ?>assets/js/common.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__)) ?>assets/js/list.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__)) ?>assets/js/MemberList.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__)) ?>assets/js/issues_list/issue_list.js"></script>

<table class="h_width" width="600" align="center" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="" valign="top">
			<a name="#top"></a>
			<div align="center" id="head_div">
				<table class="h_width" width="600" align="center" cellspacing="0" cellpadding="0" border="0" style="float: none;">
					<tr>
						<td width="50">
							<a href="http://www.nakamacloud.com/demo/index.asp"><img src="<?= plugin_dir_url(dirname(__FILE__)) ?>assets/img/header/r_header120120501144402.png" border="0" vspace="0" hspace="0" title=""></a>
						</td>
					</tr>
				</table>
				<table width="600" class="h_width" align="center" cellspacing="0" cellpadding="0" border="0" style="padding-top: 8px; float: none;">
					<tr>
						<td align="left">
							<font size="2">
								<a href="javascript:jump('0','index.asp?patten_cd=41&page_no=1','17','www.nakamacloud.com','/dantai');">TOP</a>&nbsp;&gt;
								<font color="red"> 会員PR支援サービス</font>
							</font>
						</td>
					</tr>
				</table>
				<table class="h_width" align="center" cellspacing="0" cellpadding="0" border="0" style="float: none;">
					<tr>
						<td class="h_width"><hr size="1"></td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
		<td align="center" valign="top">
			<div id="body_div">
				<form name="mainForm" method="post">
					<script type="text/JavaScript">
						<!--
						function OnMouseOver(buttonObj){
							buttonObj.style.backgroundColor = "#abcdef";
							buttonObj.style.color = "blue";
						}
						function OnMouseOut(buttonObj){
							buttonObj.style.backgroundColor = "buttonface";
							buttonObj.style.color = "buttontext";
						}
						//-->
					</script>

					<table class="page_setup_width" align="left">
						<tr>
							<td>
								<div style="margin-top:0px;margin-left:0px;width:100%;padding:0px"><img src="<?= plugin_dir_url(dirname(__FILE__)) ?>assets/img/f_users/r_831370img0120190808183336.jpg" border=0 vspace=0 hspace="10" align=left title="会員PR支援サービス"></div>
								<div style="clear:both;"></div>
								<div align="left"></div>
							</td>
						</tr>
						<tr>
							<td align="left">&nbsp;会員PR支援サービス</td>
						</tr>
					</table>

					<div style="clear:both;"></div>
					<div class="seminarList">
						<h3 class="yearTitle">2019年</h3>
						<table border="0" cellspacing="0" cellpadding="0" width="95%">
							<tr>
								<td class="dayTitle" width="20%">掲載日</td>
								<td class="nameTitle" width="80%">タイトル</td>
							</tr>
							<?php if ($service_list->Count > 0) { ?>
								<?php foreach($service_list->data as $res_service) { ?>
								<tr>
									<td class="dayList"><?= $res_service->POST_START_DATE ?></td>
									<td class="seminarNameList">
										<a href="javascript:showRequestServiceDetail('<?= $post_id; ?>','<?= $res_service->SVC_ID; ?>','<?= $res_service->SVC_INFO_NO; ?>','<?php echo $page_link; ?>');"><?= $res_service->POST_TITLE ?></a>
										<br><?= $res_service->POST_G_NAME ?>
										<div style="width:100%;margin-bottom:2px;text-align:right;font-size:10pt;font-weight:bold;color:#999999;"><?= $res_service->TG_NAME ?></div>
									</td>
								</tr>
								<?php } ?>
							<?php } ?>
						</table>
					</div>
					<br>
					<table width="250" align="center" cellpadding="7" cellspacing="1" bgcolor="#CCCCCC" class="p8">
					<tr>
						<td style="background-color:#8a96b6;" class="p8">
							<div align="center"><a href="Javascript:svcReceipt('');"><font color="#FFFFFF" size="+1">掲載申込はこちら</font></a></div>
						</td>
					</tr>
					</table>
					<input type="hidden" name="svcinfo_no" value="">
			</div>
		</td>
	</tr>
	<tr>
		<td class="" align="left" valign="top">
			<table class="f_width" cellspacing="0" cellpadding="0" border="0" style="float: none;" align="center">
				<tr>
					<td class="f_width"><hr size="1"></td>
				</tr>
			</table>
			<table class="f_width" align="center" width="600" cellspacing="0" cellpadding="1" style="float: none;">
				<tr class="footer">
					<td align="left"><FONT size=2>〒150-0013　東京都渋谷区恵比寿４－１２－１２　Tel:０３－５４８８－７０３０　Fax:０３－５４８８－７０６３</FONT></td>
				</tr>
			</table>
			<br>
		</td>
	</tr>
	<tr>
		<td class="" align="center" valign="top">
			<div align="center" class="footer">
			</div>
			<div class="asterisk" align="right">
				<br><a href="javascript:pass('41','137','www.nakamacloud.com','/dantai');">*</a>
			</div>
		</td>
	</tr>
</table>

<input type="hidden" name="patten_cd"	 value="41">
<input type="hidden" name="page_no"		 value="137">
<input type="hidden" name="mail_flg"		value="">
<input type="hidden" name="mode"				value="">
<input type="hidden" name="company_nm"	value="">
<input type="hidden" name="cls"				 value="2">
<input type="hidden" name="cmd"				 value="">

<input type="hidden" name="search_mode" value="">

</form>

<form name="pageForm" method="get" action="index.asp">
	<input type="hidden" name="page"				value="">
	<input type="hidden" name="disp_page"	 value="">
	<input type="hidden" name="patten_cd"	 value="41">
	<input type="hidden" name="page_no"		 value="137">
</form>

<form name="dispForm" method="get" action=""></form>


<script type="text/JavaScript">
	<!--
	$(function(){
		$('iframe').each(function(){
			var IframeWidth=$(this).contents().find('body').width();
			var IframeHeight=$(this).contents().find('body').height();
			$(this).css({width:IframeWidth,height:IframeHeight});
		});
	});
	//-->
</script>


<script>
	function OnCommand(cmd){
		var form = document.mainForm;
		var path_category = form.path_page_category.value;
		switch(cmd){
		case "category":
			document.mainForm.action = path_category;
			break;
		case "regthread":
			<?php if($write_thread_open == 1) : ?>
			document.mainForm.mode.value = 1;
				<?php if(!empty($_SESSION['arrSession'])) : ?>
					 document.mainForm.action = '<?php echo get_permalink(get_page_by_path('nakama-discussion-thread-input')->ID); ?>';
				<?php else : ?>
					 document.mainForm.action = '<?php echo get_permalink(get_page_by_path('nakama-login')->ID); ?>';
				<?php endif; ?>
			<?php else : ?>
			document.mainForm.action = '<?php echo get_permalink(get_page_by_path('nakama-discussion-thread-input')->ID); ?>';
			<?php endif; ?>
			break;
		case "view":
			document.mainForm.action = '<?php echo get_permalink(get_page_by_path('nakama-discussion-thread')->ID); ?>';
			break;
		case "login":
			document.mainForm.action = '<?php echo get_permalink(get_page_by_path('nakama-login')->ID); ?>';
			break;
		default:
			document.mainForm.action = '<?php echo get_permalink(get_page_by_path('nakama-discussion-list-forum')->ID); ?>';
			break;
		}

		document.mainForm.cmd.value = cmd;
		document.mainForm.submit();
	}

	function ShowMemberDetail_bbs(post_id, pid, tg_id){
		var url;
		var x,y;
		var wnd;
		x = (screen.width - 700) / 2;
		y = (screen.height - 600) / 2;
		var page_url = '<?php echo get_permalink(get_page_by_path('nakama-discussion-detail-member')->ID).getAliasService(); ?>';
		url = page_url+'pid=' + pid +'tg_id='+tg_id+'post_id='+post_id;

		gToolWnd=open(url, "memberDetail",
			"width=700,height=600,menubar=no,status=no,scrollbars=yes,personalbar=no,left="+x+", top="+y+",resizable=yes");
	}
</script>
