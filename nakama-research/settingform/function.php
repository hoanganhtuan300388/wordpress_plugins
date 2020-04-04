<?php
class researchCrSet {
  public static function getPatternNoPosttype($post_id){
    $numberCountPost = 0;
    $countPost = (array)wp_count_posts('setting_research', '');
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

  public static function research_GetWhereLists($post_id, $param_tg_id,$pattern_no){
    $tg_id = $param_tg_id;
    $p_id = research_PREFIX_P_ID.FUNC_LIST_research.$tg_id."_".$pattern_no;
    $url = research_URL_API_KAIZEN."Setting/GetWhereList?tg_id=$tg_id&fun_id=".FUNC_LIST_research."&p_id=".$p_id."&pattern_no=".$pattern_no;
    $getSetting = research_get_api_common($post_id, $url, array(), "GET");
    return $getSetting;
  }

  public static function research_postSettingWhere($post_id, $param){
    $url = research_URL_API_KAIZEN."Setting/SetListWhere";
    $post = get_post();
    $tg_id = get_post_meta($post->ID, 'top_g_id', true);
    $pattern_no = get_post_meta($post->ID, 'pattern_no_post_type', true);
    $p_id = research_PREFIX_P_ID.FUNC_LIST_research.$tg_id."_".$pattern_no;
    $arrBody = array(
      "TG_ID" => $tg_id,
      "P_ID" => $p_id,
      "FUNC_ID" => FUNC_LIST_research,
      "REG_NAME" => FUNC_LIST_research."-".$pattern_no."-".$tg_id,
      "DETAIL_REQ" => $param,
      "PATTERN_NO" => $pattern_no
    );
    $postSetting = research_get_api_common($post_id, $url, $arrBody , "POST");
    return $postSetting;
  }

  public static function research_GetSettingLists($post_id, $param_tg_id,$pattern_no){
    $tg_id = $param_tg_id;
    $p_id = research_PREFIX_P_ID.FUNC_LIST_research.$tg_id."_".$pattern_no;
    $url = research_URL_API_KAIZEN."Setting/GetSettingList?tg_id=$tg_id&fun_id=".FUNC_LIST_research."&p_id=".$p_id."&pattern_no=".$pattern_no;
    $getSetting = research_get_api_common($post_id, $url, array(), "GET");
    return $getSetting;
  }

  public static function research_postSettingLists($post_id, $arrItem){
    $post = get_post();
    $tg_id = get_post_meta($post->ID, 'top_g_id', true);
    $pattern_no = get_post_meta($post->ID, 'pattern_no_post_type', true);
    $url = research_URL_API_KAIZEN."Setting/SetListDisPlay";
    $p_id = research_PREFIX_P_ID.FUNC_LIST_research.$tg_id."_".$pattern_no;
    $param = array(
      "TG_ID"=> $tg_id,
      "P_ID"=> $p_id,
      "FUNC_ID" => FUNC_LIST_research,
      "item_id_list" => $arrItem,
      "PATTERN_NO" => $pattern_no
    );
    $postSetting = research_get_api_common($post_id, $url, $param, "POST");
    return $postSetting;
  }

  public static function research_GetSettingSort($post_id, $param_tg_id,$pattern_no){
    $tg_id = $param_tg_id;
    $p_id = research_PREFIX_P_ID.FUNC_LIST_research.$tg_id."_".$pattern_no;
    $url = research_URL_API_KAIZEN."/Setting/GetSortList?tg_id=$tg_id&fun_id=".FUNC_LIST_research."&p_id=".$p_id."&pattern_no=".$pattern_no;
    $getSettingSort = research_get_api_common($post_id, $url, array(), "GET");
    return $getSettingSort;
  }

  public static function research_postSettingSort($post_id, $arrItem){
    $url = research_URL_API_KAIZEN."Setting/SetListDisOrder";
    $tg_id = get_post_meta($post_id, 'top_g_id', true);
    $pattern_no = get_post_meta($post_id, 'pattern_no_post_type', true);
    $p_id = research_PREFIX_P_ID.FUNC_LIST_research.$tg_id."_".$pattern_no;
    $param = array(
      "TG_ID"=> $tg_id,
      "P_ID"=> $p_id,
      "FUNC_ID" => FUNC_LIST_research,
      "item_value_sort"=> $arrItem,
      "PATTERN_NO" => $pattern_no
    );
    $postSetting = research_get_api_common($post_id, $url, $param, "POST");
    return $postSetting;
  }

  public static function research_renderSelectField($postid,$index, $name,$arrAllItem){
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

 	public static function research_renderSelectEqua($postid,$name, $before, $after){
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
    public static function research_renderAddWhere($postid,$name){
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

  public static function renderTopStation($top_sta){
    $strHtml = "";
    $arrSta = ["--","北海道","青森県","岩手県","宮城県","秋田県","山形県","福島県","埼玉県","千葉県","東京都","神奈川県","茨城県","栃木県","群馬県","長野県","山梨県","新潟県","富山県",
    "石川県","福井県","岐阜県","静岡県","愛知県","三重県","滋賀県","京都府","大阪府","兵庫県","奈良県","和歌山県","鳥取県","島根県","岡山県","広島県","山口県","徳島県","香川県","愛媛県","高知県","福岡県",
    "佐賀県","長崎県","熊本県","大分県","宮崎県","鹿児島県","沖縄県"];
    $strHtml .= "<select name='top_sta' onChange='OnCommand()'>";
    foreach ($arrSta as $item) {
      $selected = ($top_sta == $item)?"selected":"";
      $strHtml .= "<option value='".$item."' ".$selected.">".$item."</option>";
    }
    $strHtml .= "</select>";
    return $strHtml;
  }

  public static function research_get_Sta($post_id, $tg_id) {
    $url = research_URL_API_KAIZEN."Member/GetSta?tg_id=".$tg_id;
    $rs = research_get_api_common($post_id, $url, "", 'GET');
    return $rs->sta;
  }
    public static function research_update_key($tg_id, $api_key)
    {
        $url = research_URL_API_KAIZEN . "UserAuthentication/UpdateKey";
        $arrBody = array(
            "tg_id" => $tg_id,
            "api_key" => $api_key
        );
        $headers = array(
            'Content-Type' => 'application/json; charset=utf-8',
            'NAKAMA-KEY' => $api_key,
            'TG_ID'=> $tg_id
        );
        $request = wp_remote_post($url, array(
            'headers'     => $headers,
            'body'        => ($arrBody)?json_encode($arrBody):"",
            'method'      => 'POST',
            'data_format' => 'body',
            'timeout'     => 45,
        ));
        if( is_wp_error( $request ) ) {
            return false;
        }
        return json_decode(wp_remote_retrieve_body( $request ));
    }
}
?>