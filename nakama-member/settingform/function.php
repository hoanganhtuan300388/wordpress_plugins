<?php

class MemberCrSet
{
	public static function Member_postSettingLists($post_id, $arrItem){
		$post = get_post();
		$tg_id = get_post_meta($post->ID, 'top_g_id', true);
		$pattern_no = get_post_meta($post->ID, 'pattern_no_post_type', true);
		$url = NAK_MEMBER_URL."Setting/SetListDisPlay";
		$p_id = MEMBER_PREFIX_P_ID.MEMBER_FUNC_ID_LIST.$tg_id."_".$pattern_no;
		$param = array(
			"TG_ID"=> $tg_id,
			"P_ID"=> $p_id,
			"FUNC_ID" => MEMBER_FUNC_ID_LIST,
			"item_id_list" => $arrItem,
			"PATTERN_NO" => $pattern_no
		);
		$postSetting = member_get_api_common($post_id, $url, $param, "POST");
		return $postSetting;
	}

	public static function logins($post_id, $arrBody) {
		$url = NAK_MEMBER_URL.'Personal/LogIn';
		$login = member_get_api_common($post_id, $url, $arrBody, "POST");
		return $login;
	 }

	public static function Member_GetSettingLists($post_id, $param_tg_id,$pattern_no){
		$tg_id = $param_tg_id;
		$p_id = MEMBER_PREFIX_P_ID.MEMBER_FUNC_ID_LIST.$tg_id."_".$pattern_no;
		$url = NAK_MEMBER_URL."Setting/GetSettingList?tg_id=$tg_id&fun_id=".MEMBER_FUNC_ID_LIST."&p_id=".$p_id."&pattern_no=".$pattern_no;
		$getSetting = member_get_api_common($post_id, $url, array(), "GET");
		return $getSetting;
	}

	public static function Member_GetSettingSort($post_id, $param_tg_id,$pattern_no){
		$tg_id = $param_tg_id;
		$p_id = MEMBER_PREFIX_P_ID.MEMBER_FUNC_ID_LIST.$tg_id."_".$pattern_no;
		$url = NAK_MEMBER_URL."/Setting/GetSortList?tg_id=$tg_id&fun_id=".MEMBER_FUNC_ID_LIST."&p_id=".$p_id."&pattern_no=".$pattern_no;
		$getSettingSort = member_get_api_common($post_id, $url, array(), "GET");
		return $getSettingSort;
	}

	public static function Member_GetSettingSortUser($post_id, $param_tg_id,$p_id){
		$tg_id = $param_tg_id;
		$url = NAK_MEMBER_URL."/Setting/GetSortList?tg_id=$tg_id&fun_id=".MEMBER_FUNC_ID_LIST."&p_id=".$p_id."&pattern_no=0";
		$getSettingSort = member_get_api_common($post_id, $url, array(), "GET");
		return $getSettingSort;
	}

	public static function Member_postSettingSort($post_id, $arrItem){
		$url = NAK_MEMBER_URL."Setting/SetListDisOrder";
		$post = get_post();
		$tg_id = get_post_meta($post->ID, 'top_g_id', true);
		$pattern_no = get_post_meta($post->ID, 'pattern_no_post_type', true);
		$p_id = MEMBER_PREFIX_P_ID.MEMBER_FUNC_ID_LIST.$tg_id."_".$pattern_no;
		$param = array(
		"TG_ID"=> $tg_id,
		"P_ID"=> $p_id,
		"FUNC_ID" => MEMBER_FUNC_ID_LIST,
		"item_value_sort"=> $arrItem,
		"PATTERN_NO" => $pattern_no
		);
		$postSetting = member_get_api_common($post_id, $url, $param, "POST");
		return $postSetting;
	}

	public static function Member_postSettingSortUser($post_id, $arrItem, $p_id = ''){
		$url = NAK_MEMBER_URL."Setting/SetListDisOrder";
		$tg_id = get_post_meta($post_id, 'top_g_id', true);
		$param = array(
		"TG_ID"=> $tg_id,
		"P_ID"=> $p_id,
		"FUNC_ID" => MEMBER_FUNC_ID_LIST,
		"item_value_sort"=> $arrItem,
		"PATTERN_NO" => 0
		);
		$postSetting = member_get_api_common($post_id, $url, $param, "POST");
		return $postSetting;
	}

	public static function Member_GetWhereLists($post_id, $param_tg_id,$pattern_no){
		$tg_id = $param_tg_id;
		$p_id = MEMBER_PREFIX_P_ID.MEMBER_FUNC_ID_LIST.$tg_id."_".$pattern_no;
		$url = NAK_MEMBER_URL."Setting/GetWhereList?tg_id=$tg_id&fun_id=".MEMBER_FUNC_ID_LIST."&p_id=".$p_id."&pattern_no=".$pattern_no;
		$getSetting = member_get_api_common($post_id, $url, array(), "GET");
		return $getSetting;
	}

	public static function Member_postSettingWhere($post_id, $param){
		$url = NAK_MEMBER_URL."Setting/SetListWhere";
		$post = get_post();
		$tg_id = get_post_meta($post->ID, 'top_g_id', true);
		$pattern_no = get_post_meta($post->ID, 'pattern_no_post_type', true);
		$p_id = MEMBER_PREFIX_P_ID.MEMBER_FUNC_ID_LIST.$tg_id."_".$pattern_no;
		$arrBody = array(
			"TG_ID" => $tg_id,
			"P_ID" => $p_id,
			"FUNC_ID" => MEMBER_FUNC_ID_LIST,
			"REG_NAME" => MEMBER_FUNC_ID_LIST."-".$pattern_no."-".$tg_id,
			"DETAIL_REQ" => $param,
			"PATTERN_NO" => $pattern_no
		);
		$postSetting = member_get_api_common($post_id, $url, $arrBody , "POST");
		return $postSetting;
	}

	public static function member_renderSelectField($postid,$index, $name,$arrAllItem){
		$value = get_post_meta($postid, $name, true);
		$rs = '';
		$rs .= "<select class='choise_item' name='".$name."' onchange='OnSelChange(".$index.")'>";
		$rs .= "<option value=>----</option>";
		$GetSettingLists = $arrAllItem;
		if(!empty($GetSettingLists)) {
			 foreach ($GetSettingLists as $key => $item) {
				 $selected = ($value == $item->ITEM_ID)?'selected':'';
				 $rs .= "<option ".$selected." value=".$item->ITEM_ID.">".$item->ITEM_NAME."</option>";
				}
		}
		$rs .= "</select>";
		return $rs;
 	}

 	public static function member_renderSelectEqua($postid,$name, $before, $after){
		$post = get_post();
		$arrValue = array(
			'' => '----',
			'1' => "含む",
			'0' => "除く",
			'2' => "等しい",
			'3' => "範囲",
			'4' => "データあり",
			'5' => "データなし",
			'6' => "で始まる"
	 	);
		$value = '';
		$value = get_post_meta($postid, $name, true);
		$rs = '';
		$rs .= "<select class='choise_codition' name='".$name."'>";
		foreach ($arrValue as $key => $item) {
			 $selected = ($value == $key && $value !== '')?'selected':'';
			 $rs .= "<option ".$selected." value='".$key."'>".$item."</option>";
		}
		$rs .= "</select>";

		return $rs;
 	}
 	public static function member_renderAddWhere($postid,$name){
		$post = get_post();
		$arrValue = array(
			'' => '----',
			'1' => "かつ",
			'2' => "または");
		$value = get_post_meta($postid, $name, true);
		$rs = '';
		$rs .= "<select class='add_where' name='".$name."'>";
		foreach ($arrValue as $key => $item) {
			$selected = ($value == $key)?'selected':'';
			$rs .= "<option ".$selected." value='".$key."'>".$item."</option>";
		}
		$rs .= "</select>";
	 	return $rs;
 	}

	public static function renderListMember($post_id) {
		$shortcode = do_shortcode("[nakama_member_list_setting id='".$post_id."']");
    	update_post_meta($post_id, 'render_html', $shortcode);
    }
	public static function shortcode_Getlists($post_id, $tg_id,$LG_ID,$keyword,$columnSort,$orderBy,$LG_FOLLOW,$page_no,$per_page,$PattenNo,$top_sta) {
		$arrBody = array(
			"TG_ID"=> (isset($_SESSION['arrSession']))?$_SESSION['arrSession']->TG_ID:$tg_id,
			"P_ID"=> (isset($_SESSION['arrSession']))?$_SESSION['arrSession']->P_ID:MEMBER_PREFIX_P_ID.MEMBER_FUNC_ID_LIST.$tg_id."_".$PattenNo,
			"LG_TYPE"=> 0,
			"LG_ID"=> $LG_ID,
			"KEYWORD" => $keyword,
			"COLLUM_ORDER" => $columnSort,
			"ORDER_BY" => $orderBy,
			"PUBLIC"=> "1",
			"LG_FOLLOW" => (isset($_SESSION['incLower']))?$_SESSION['incLower']:"0",
			"PATTEN_NO" => (isset($_SESSION['arrSession']))?0:$PattenNo,
			"FUNC_ID" => MEMBER_FUNC_ID_LIST,
			"Sta" => ($top_sta == "--")?"":$top_sta
		);
		$urlMemberList = URL_API.'Member/GetListMember?page_no='.$page_no.'&per_page='.$per_page;
		$listMembers = get_api_common($post_id, $urlMemberList, $arrBody, "POST");
		return $listMembers;
	}

	public static function getGroupTree($post_id){
		$lg_type = (isset($_SESSION['arrSession']->LG_TYPE))?$_SESSION['arrSession']->LG_TYPE:"0";
		$tg_id = (isset($_SESSION['arrSession']->TG_ID))?$_SESSION['arrSession']->TG_ID:get_option('nakama-member-group-id');
		$urlgetGroupTree = URL_API."/Member/getGroupTree";
		$arrBody = array(
			"TG_ID" => $tg_id,
			"LG_TYPE" => $lg_type
		);
		$getGroupTree = get_api_common($post_id, $urlgetGroupTree, $arrBody, "POST");
		return $getGroupTree;
	}

	public static function GetGroupData($post_id){
		$tg_id = get_post_meta($post_id, 'member_meta_group_id', true);
		$urlGetGroupData = URL_API."/Member/GetGroupData?tg_id=".$tg_id;
		$GetGroupData = get_api_common($post_id, $urlGetGroupData, array(), "GET");
		return $GetGroupData;
	}

	public static function getKeyBasedOnName($arr,$name){
		foreach($arr as $key => $object) {
			 if($object->column_id==$name) return $object->column_data;
		}
		return false;
 	}

	public static function member_setting_paginates($member_count, $current_page,$per_page){
		$pagination = paginationForShortcode($member_count,$current_page,$per_page);
		return $pagination;
	}
	public static function convertDates($date, $time, $label){
		$old_date = $date;
		$old_date_timestamp = strtotime($old_date);
		$new_date = date($time, $old_date_timestamp);
		if($time == "l"){
			if($new_date == "Monday"){
				$new_date = "月";
			}else if($new_date == "Tuesday"){
				$new_date = "火";
			}
			else if($new_date == "Wednesday"){
				$new_date = "水";
			}
			else if($new_date == "Thursday"){
				$new_date = "木";
			}
			else if($new_date == "Friday"){
				$new_date = "金";
			}
			else if($new_date == "Saturday"){
				$new_date = "土";
			}else{
				$new_date = "日";
			}
			return $new_date;
		}else{
				return $new_date.$label;
		}
	}
	// Func check mail magazine
	public static function checkMailMagazine($post_id,$tg_id, $email, $g_id){
		$url = URL_API.'Member/CheckMailMagazine';
		$arrBody = array(
			"P_ID" => (isset($_SESSION['arrSession']->P_ID))?$_SESSION['arrSession']->P_ID:"",
			"TG_ID"=> $tg_id,
			"Email" => $email,
			"G_ID" => $g_id
		);
		$checkMailMagazine = get_api_common($post_id,$url, $arrBody, "POST");
		return $checkMailMagazine;
	}
	// Func regist mail magazine
	public static function registMailMagazine($post_id,$tgId, $email, $gId, $lgId){
		$url = URL_API.'Member/UpdateMlMaga';
		$arrBody = array(
			"TG_ID"=> $tgId,
			"Email" => $email,
		);
		$registMailMagazine = get_api_common($post_id,$url, $arrBody, "POST");
		return $registMailMagazine;
	}
	
	public static function selectData($post_id,$arrValue){
		$url = URL_API.'Member/SelectData ';
		$arrBody = array(
			"P_ID"=> isset($_SESSION['arrSession']->P_ID)?$_SESSION['arrSession']->P_ID:"",
			"TG_ID" => isset($_SESSION['arrSession']->TG_ID)?$_SESSION['arrSession']->TG_ID:get_post_meta($post_id, 'member_meta_group_id', true),
			"G_ID" => isset($_SESSION['arrSession']->G_ID)?$_SESSION['arrSession']->G_ID:"",
			"G_Chg" => $arrValue['G_Chg'],
			"Relmail" => $arrValue['Relmail'],
			"Pgh_Relmail" => $arrValue['Pgh_Relmail'],
			"B_User_Id" => $arrValue['B_User_Id']
		);

		$selectData = get_api_common($post_id,$url, $arrBody, "POST");
		return $selectData;
	}

	public static function getPatternNoPosttype($post_id){
		$numberCountPost = 0;
		$countPost = (array)wp_count_posts('setting_member', '');
		if($countPost){
			$numberCountPost = $countPost['publish'] + $countPost['draft'] + $countPost['future'] + $countPost['trash'] + $countPost['pending'] + $countPost['private'] + $countPost['inherit'];
		}
		$pattern_no_post_type = get_post_meta($post_id, 'pattern_no_post_type', true);
		if($pattern_no_post_type){
			return $pattern_no_post_type;
		}
		else
			return $numberCountPost+1;
	}
	public static function getPageSlug($slug){
		$page_id = get_page_by_path($slug);
		$page_link = get_page_link($page_id); 
		$permalink_structure = get_option('permalink_structure');
		$alias = ($permalink_structure == NULL)?'&':"?";
		return $page_link.$alias;
	}

	public static function convertFile($filePath)
	{
	  $content = file_get_contents($filePath);
    return mb_convert_encoding($content, "UTF-8", "ASCII,JIS,UTF-8,eucJP-win,SJIS-win");
	}

	public static function convert_file_r($file)
	{
		// $detect = mb_detect_encoding($file, "auto");
		$file_utf8 = mb_convert_encoding($file, "UTF-8", "ASCII,JIS,UTF-8,eucJP-win,SJIS-win");
		return $file_utf8;
	}

	public static function renderTopStation($id,$top_sta){
		$strHtml = "";
		$arrSta = ["--","北海道","青森県","岩手県","宮城県","秋田県","山形県","福島県","埼玉県","千葉県","東京都","神奈川県","茨城県","栃木県","群馬県","長野県","山梨県","新潟県","富山県",
			"石川県","福井県","岐阜県","静岡県","愛知県","三重県","滋賀県","京都府","大阪府","兵庫県","奈良県","和歌山県","鳥取県","島根県","岡山県","広島県","山口県","徳島県","香川県","愛媛県","高知県","福岡県",
			"佐賀県","長崎県","熊本県","大分県","宮崎県","鹿児島県","沖縄県"];
		$strHtml .= "<select name='top_sta' class='top_sta' onChange='OnCommand(".$id.")'>";
		foreach ($arrSta as $item) {
			$selected = ($top_sta == $item)?"selected":"";
			$strHtml .= "<option value='".$item."' ".$selected.">".$item."</option>";
		}
		$strHtml .= "</select>";
		return $strHtml;
	}

	public static function member_get_Sta($post_id,$tg_id) {
		$url = URL_API."Member/GetSta?tg_id=".$tg_id;
		$rs = get_api_common($post_id, $url, "", 'GET');
		return $rs->sta;
	}

	public static function member_check_LG_ID($post_id,$TG_ID,$LG_ID) {
		$url = URL_API."Setting/CheckExistGroupID?tg_id=".$TG_ID."&lg_id=".$LG_ID;
		$rs = get_api_common($post_id, $url, "", 'GET');
		return $rs->CheckExistGroupID;
	}

	public function printSpace($num){
		for ($i=1; $i < (int)$num; $i++) {
			echo "&nbsp;&nbsp;&nbsp;";
		}
	}
	public function customHeaderItem($item, $arr = array(), $arrSortShow){
			$column1 = isset($arrSortShow[0]->item_id) ? $arrSortShow[0]->item_id : "";
	  	$column2 =  isset($arrSortShow[1]->item_id) ? $arrSortShow[1]->item_id : "";
	  	$column3 =  isset($arrSortShow[2]->item_id) ? $arrSortShow[2]->item_id : "";

	  	$column1_orderby =  isset($arrSortShow[0]->item_sort) ? $arrSortShow[0]->item_sort : "";
	  	$column2_orderby =  isset($arrSortShow[1]->item_sort) ? $arrSortShow[1]->item_sort : "";
	  	$column3_orderby =  isset($arrSortShow[2]->item_sort) ? $arrSortShow[2]->item_sort : "";

	    $arrCusAdd = [];
	    foreach ($arr as $key => $v) {
	      if($item == $v->column_id){
	        $arrCusAdd['column_id'] = $v->column_id;
	        $arrCusAdd['column_name'] = $v->column_name;
	        $arrCusAdd['column_width'] = $v->column_width;
	        $arrCusAdd['column_type'] = $v->column_type;
	        if($v->column_id == $column1){
	        	$arrCusAdd['column_sort'] = $column1_orderby;
	        }
	        elseif($v->column_id == $column2){
	        	$arrCusAdd['column_sort'] = $column2_orderby;
	        }
	        elseif($v->column_id == $column3){
	        	$arrCusAdd['column_sort'] = $column3_orderby;
	        }else{
	        	$arrCusAdd['column_sort'] = "";
	        }
	        $arrCusAdd['column_sort_index'] = $v->column_sort_index;
	      }
	    }
	    return $arrCusAdd;
	}
	public function customBodyItem($item, $arr = array()){
	    $arrCusAdd = [];
	    foreach ($arr as $key => $v) {
	      if($item == $v->column_id){
	        $arrCusAdd['column_id'] = $v->column_id;
	        $arrCusAdd['column_data'] = $v->column_data;
	        $arrCusAdd['column_format'] = $v->column_format;
	      }
	    }
	    return $arrCusAdd;
	}
}
?>
