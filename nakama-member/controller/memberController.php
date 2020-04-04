<?php
  final class memberController{

      public static function lists() {
        $keyword = isset($_POST['search_select'])?$_POST['search_select']:"";
        $columnSort = isset($_POST['sort'])?$_POST['sort']:"";
        $orderBy = isset($_POST['order'])?$_POST['order']:"";
        $current_page = (get_query_var('page') == 0)?1:get_query_var('page');
        $page_no = $current_page - 1;
        $LG_ID = isset($_POST['LG_ID'])?$_POST['LG_ID']:"co_lg000000000001027";

        if(isset($_SESSION['arrSession'])) {
          $LG_ID = $_SESSION['arrSession']->LG_ID;

          if(isset($_POST['LG_ID']))
            $LG_ID = $_POST['LG_ID'];
        }
        else {
          $LG_ID = "";
        }
        if(!isset($_SESSION['arrSession'])){
          $per_page = get_option('nakama-member-list-per-page');
        } else {
          $per_page = get_option('nakama-member-list-logined-per-page');
        }
        $arrBody = array(
          "TG_ID"=> (isset($_SESSION['arrSession']->TG_ID))?$_SESSION['arrSession']->TG_ID:get_option('nak-member-list-tg-id'),
          "P_ID"=> (isset($_SESSION['arrSession']->P_ID))?$_SESSION['arrSession']->P_ID:MEMBER_PREFIX_P_ID.MEMBER_FUNC_ID_LIST.get_option('nak-member-list-tg-id')."_0",
          "LG_TYPE"=> (isset($_SESSION['arrSession']->LG_TYPE))?$_SESSION['arrSession']->LG_TYPE:0,
          "LG_ID"=> $LG_ID,
          "KEYWORD" => $keyword,
          "COLLUM_ORDER" => $columnSort,
          "ORDER_BY" => $orderBy,
          "PUBLIC"=> "1",
          "LG_FOLLOW" => (isset($_SESSION['incLower']))?$_SESSION['incLower']:"0",
          "PATTEN_NO" => 0,
          "FUNC_ID" => (isset($_SESSION['arrSession']->TG_ID))?MEMBER_FUNC_ID_LIST_LOGINED:MEMBER_FUNC_ID_LIST,
        );
        $urlMemberList = URL_API.'Member/GetListMember?page_no='.$page_no.'&per_page='.$per_page;
        $listMembers = get_api_common($post_id, $urlMemberList, $arrBody, "POST");

        return $listMembers;
      }

      /*
      * GMO Z.Com Runsystem
      * 2019/01/03
      * FUNC: Paginate page list member
      */

      public function paginates(){
         $current_page = (get_query_var('page') == 0)?1:get_query_var('page');
         $data_list_member = array();
         $event_count = '';
         $list_member_response = $this->lists();
         if (!empty($list_member_response->data)) {
           $data_list_member = $list_member_response->data;
         }
         if (!empty($list_member_response->count)) {
            $event_count = $list_member_response->count;
         }
         $pagination = pagination($event_count,$current_page);
         return $pagination;
      }

      public function formatDate($datetime, $format = "Y/m/d"){
         $date = date_create($datetime);
         return date_format($date,$format);
      }

      public function getKeyBasedOnName($arr,$name){
         foreach($arr as $key => $object) {
            if($object->column_id==$name) return $object->column_data;
         }
         return false;
      }

      public function memberDetails($post_id, $M_ID, $TG_ID){
         $urlmemberDetail = URL_API."Member/getMember";
         $arrBody = array(
            "M_ID"=> $M_ID,
            "TG_ID"=> $TG_ID,
         );
         $detailMember = get_api_common($post_id, $urlmemberDetail, $arrBody, "POST");
         return $detailMember;
      }

      public function getMemberOrganization($post_id){
         $gid = ($_SESSION['arrSession']->G_ID)?$_SESSION['arrSession']->G_ID:"";
         $tg_id = ($_SESSION['arrSession']->TG_ID)?$_SESSION['arrSession']->TG_ID:"";
         $urlMemberOrganization = URL_API."Member/GetMemberOrganization?g_id=".$gid."&tg_id=".$tg_id;
         $MemberOrganization = get_api_common($post_id, $urlMemberOrganization, array(), "GET");
         return $MemberOrganization;
      }

      // public function getGroupTree(){
      //    $lg_type = (isset($_SESSION['arrSession']->LG_TYPE))?$_SESSION['arrSession']->LG_TYPE:"";
      //    $tg_id = (isset($_SESSION['arrSession']->TG_ID))?$_SESSION['arrSession']->TG_ID:get_option('nakama-member-group-id');
      //    $urlgetGroupTree = URL_API."/Member/getGroupTree";
      //    $arrBody = array(
      //       "TG_ID" => $tg_id,
      //       "LG_TYPE" => $lg_type
      //    );
      //    $getGroupTree = get_api_common($post_id, $urlgetGroupTree, $arrBody, "POST");
      //    return $getGroupTree;
      // }

      /**
       * get group ogranization of user
       *
       * @author nguyentc
       * @return array
       */
      public function getGroupsOfUser($post_id)
      {
        $url = URL_API. "/Member/getGroupList ";
        $arrBody = array(
           "P_ID" => ($_SESSION['arrSession']->P_ID)?$_SESSION['arrSession']->P_ID:"",
           "TG_ID" => ($_SESSION['arrSession']->TG_ID)?$_SESSION['arrSession']->TG_ID:get_option('nakama-member-group-id'),
           "LG_TYPE" => $_SESSION['arrSession']->LG_TYPE,
           "LG_ID" => get_post_meta($post_id,"set_lg_g_id",true)
		   // "LG_ID" => ($_SESSION['arrSession']->LG_ID)?$_SESSION['arrSession']->LG_ID:""
        );

        echo "url = " . $url . "<br>";

		echo "arrBody = ";
		echo "<br>";
		var_dump($arrBody);
		echo "<br>";

        $data = get_api_common($post_id, $url, $arrBody, "POST");

		echo "data = ";
		echo "<br>";
		var_dump($data);
		echo "<br>";

        return $data;
      }

      public function renderRegistMember($class){
        $render = '';
        $ini_array = URL_FILE_REGIST.'/'.'disp_entry.ini';
        $ini_array = file_get_contents($ini_array, true);
        $ini_array = explode("\n", $ini_array);
        $rs = array();
        $i = 0;
        foreach ($ini_array as $key => $item) {
          if(strpos($item, "#") !== false){
            $item = explode(",", $item);
            $rs[$i]['group'] = $item;
            $j = 0;
            $i++;
            $b = $i;
          }else{
            if($b == $i){
              $b = $b - 1;
            }
            $item = explode(",", $item);
            $rs[$b]['item'][$j] = $item;
            $j++;
          }
        }
        $render .= "<table class='".$class['table']."'>";
        $render .= "<tr>";
        $render .= "<td colspan='2' class='".$class['tdHead']."'>項目名</td>";
        $render .= "<td class='".$class['tdHead']."'>記入欄</td>";
        $render .= "<td class='".$class['tdHead']."'>公開設定</td>";
        $render .= "</tr>";

        foreach ($rs as $k => $val) {
          if(trim($val['group'][0]) !== "#HIDDEN"){
            $render .= "<tr>";
            $render .= "<td rowspan =".$val['group'][1]." class='".$class['tdOne']."' style='font-weight:bold;font-size:12pt;'>";
            $render .= substr($val['group'][0], 1);
            $render .= "</td>";
            $classdefault = ($val['item'][0][2] == 3 && $val['item'][0][1] == 1)?$class['tdSecond']:"" ;
            $render .= "<td class='".$classdefault." ".$class['tdFour']."' colspan='1'>";
            $render .= ($val['item'][0][1] == 1)?"<span class='red'>※&nbsp</span>".$val['item'][0][0]:$val['item'][0][0];
            $render .= "</td>";
            $render .= "<td class='".$class['tdThird']."'>";
            // View element
            $element = '';
            $type = '';
            $element = $this->renderElement($val['item'][0]);
            $render .= $element;
            $render .= "</td>";
            $render .= "<td class='".$class['tdThird']."'>";
            $render .= $this->renderElementSelectRight($val['item'][0]);
            $render .= "</td>";
            $render .= "</tr>";
            $elementChild = '';
            for($i = 1; $i <= $val['group'][1]-1; $i++){
              $render .= "<tr>";
              $classChild = ($val['item'][$i][2] == 3 && $val['item'][$i][1] == 1)?$class['tdSecond']:$class['tdFour'];
              $render .= "<td class='".$classChild."'>";
              $render .= ($val['item'][$i][1] == 1)?"<span class='red'>※&nbsp</span>".$val['item'][$i][0]:$val['item'][$i][0];
              $render .= "</td>";
              $yellow = ($val['item'][$i][2] == NULL)?'ReadOnly ':'';
              $render .= "<td class='RegValue ".$yellow."'>";
              $elementChild = $this->renderElement($val['item'][$i]);
              $render .= $elementChild;
              $render .= "</td>";
              $render .= "<td class='RegValue'>";
              $render .= $this->renderElementSelectRight($val['item'][$i]);
              $render .= "</td>";
              $render .= "</tr>";
              $elementChild = '';
            }
          }else{
            $count = count($val['item']);
            for($i = 0; $i <= $count; $i++){
              $render .= $this->renderElementHidden($val['item'][$i][0], $val['item'][$i][1]);
            }

          }
        }
        $render .= "</table>";
        return $render;
      }
      public function renderElement($item){
        $element = '';
        $name = trim($item[8]);
        $typeElement = trim($item[2]);
        $arrValue = explode("|", $item[3]);
        $checked = trim($item[5]);
        $note = trim($item[6]);
        $label = trim($item[10]);
        $filenote = trim($item[9]);

        if($typeElement == '0'){
          $element .= $this->renderInput($name);
        }
        elseif($typeElement == '1'){
          $element .= $this->renderRadio($arrValue, $name, $cheked);
        }elseif($typeElement == '2'){
          $element .= $this->renderCheckbox($arrValue, $name, $cheked);
        }elseif($typeElement == '3'){
          $element .= $this->renderSelect($label, $name, $arrValue, $checked);
        }
        elseif($typeElement == ""){
          $element .= '<font color="red"></font>';
        }
        elseif($typeElement == "4"){
          $element .= $this->renderTextarea($name);
        }
        if($filenote){
          if($filenote == "住所検索"){
            $arKey = implode(",", explode("|", $name));

            $element .= '&nbsp<input type="button" name="explanation" value="住所検索" onclick=OnZipCode('.$arKey.')>';
          }
          else{
            $element .= '&nbsp&nbsp <input type="button" name="explanation" value="説明" onclick=OnExplanationFile("/dantai/nakama/html/一般公開会員リスト表示説明.html");>';
          }
        }
        if(!empty($note)){
          $element .= '<font style="margin:3px 10px 0 0; display: block;" class="red">'.$note.'</font>';
        }
        return $element;
      }

      // Render Input
      public function renderInput($name){
        $element = '';
        $arKey = explode("|", $name);
        if(count($arKey) == 1){
          if($name == "P_PASSWORD" || $name == "P_PASSWORD2"){
            $width = "120px";
            $text = "半角英数字4文字以上20文字まで&nbsp&nbsp";
            if($name == "P_PASSWORD"){
              $text .= '<input type="button" name="dispPPassBtn" style="width:130px;" value="パスワード表示" onclick="chgMastPPassword();">';
            }
            $type = "password";
          }else{
            $width = "97%";
            $text = "";
            $type ="text";
          }
          $element .= "<input style='margin-right:10px; width:".$width.";' name='".$name."' type='".$type."' value=''>";
          $element .= $text;
        }else{
          foreach ($arKey as $k => $v) {
            $element .= "<input style='margin-right:10px;' name='".$v."' type='text' value='' class='w_60px'>";
          }
        }
        return $element;
      }

      // Render Radio
      public function renderRadio($arrValue, $name, $checked){
        $element = '';
        if(!empty($arrValue)){
          foreach ($arrValue as $key => $value) {
            $checked = ($checked == $value)?"checked":"";
            $element .= "<label><input name='".$name."' ".$checked." type='radio' value='".$key."'>".$value."</label>";
          }
        }
        return $element;
      }

      // Render checkbox
      public function renderCheckbox($arrValue, $name, $checked){
        $element = '';
        if(!empty($arrValue)){
          foreach ($arrValue as $key => $value) {
            $checked = ($checked == $value)?"checked":"";
            $element .= "<label><input name='".$name."' ".$checked." type='checkbox' value='".$key."'>".$value."</label>";
          }
        }
        return $element;
      }

      // Render select
      public function renderSelect($label, $name, $arrValue, $checked){
        $element = '';
        if($label){
           $element .= $label."&nbsp&nbsp";
        }
        $element .= "<select name='".$name."'>";
        if(!empty($arrValue)){
          foreach ($arrValue as $key => $value) {
            $checked = ($checked == $value)?"selected":"";
            $element .= "<option ".$checked." value='".$value."'>".$value."</option>";
          }
        }
        $element .= "</select>";
        return $element;
      }

      // Render select right
      public function renderElementSelectRight($item){
        $element = '';
        $name = trim($item[11]);
        $checked = trim($item[13]);
        $arrValue = explode("|", trim($item[12]));
        if(trim($name) != ''){
          $element .= "<select name='".$name."'>";
          foreach ($arrValue as $k => $v) {
            $selected = (trim($v) == $checked)?:"selected";
            $element .= "<option ".$selected." value=".$k.">".$v."</option>";
          }
          $element .= "</select>";
        }
        return $element;
      }

      // Render Textarea
      public function renderTextarea($name){
        return '<textarea style="ime-mode:active; width:97%; line-height:150%;" cols="64" rows="17" name="'.$name.'"></textarea>';
      }

      // Render input hidden
      public function renderElementHidden($item, $val){
        return "<input type='hidden' name='".$item."' value='".$val."'>";
      }
      // Render zipcode
      public function zipCodeMember($post_id, $zipcode){
        $current_page = (get_query_var('page') == 0)?1:get_query_var('page');
        $page_no = $current_page - 1;

        $per_page = get_option('nakama-member-list-per-page');
        $urlZipCode = URL_API.'Member/searchZip/?page_no='.$page_no.'&per_page='.$per_page;
        $arrBody = array(
            "ZIP_CD"=> $zipcode,
        );
        $listZipcode = get_api_common($post_id, $urlZipCode, $arrBody, "POST");
        return $listZipcode;
      }
      public function getCategoryMember($post_id, $industry_cd, $industry_nm, $per_page, $page_no, $collum_order, $order_by){
        $tg_id = get_post_meta($post_id, "member_meta_group_id", true);
        $url = URL_API.'Setting/SearchCategoryGroup?tg_id='.$tg_id.'&industry_cd='.$industry_cd.'&industry_nm='.$industry_nm.'&page_no='.$page_no.'&per_page='.$per_page.'&collum_order='.$collum_order.'&order_by='.$order_by;
        $arrBody = array();
        $getCategoryMember = get_api_common($post_id, $url, $arrBody, "GET");
        return $getCategoryMember;
      }

      public function SearchCategory($post_id, $major_code, $middle_code, $minor_code,$m_CType, $per_page, $page_no, $collum_order, $order_by){
        $tg_id = get_post_meta($post_id, "member_meta_group_id", true);
        $url = URL_API.'Setting/SearchCategory?category_type=0&major_code='.$major_code.'&middle_code='.$middle_code.'&minor_code='.$minor_code.'&m_CType='.$m_CType.'&industry_cd=&industry_nm=&page_no='.$page_no.'&per_page='.$per_page.'&collum_order='.$collum_order.'&order_by='.$order_by;
        $arrBody = array();
        $getCategoryMember = get_api_common($post_id, $url, $arrBody, "GET");
        return $getCategoryMember;
      }
      public function getSearchLg($post_id, $keyword, $per_page, $page_no, $collum_order, $order_by, $tg_id){
        $url = URL_API.'Setting/getGroupTreePaging?page_no='.$page_no.'&per_page='.$per_page;
        $arrBody = array(
          'TG_ID' => $tg_id,
          'LG_TYPE' => '2',
          'KEYWORD' => $keyword,
          'COLLUM_ORDER' => $collum_order,
          'ORDER_BY' => $order_by,
        );
        $getSearchLg = get_api_common($post_id, $url, $arrBody, "POST");
        return $getSearchLg;
      }
      public function GetBranchInfo($post_id, $tg_id, $bank_nm, $bank_cd, $per_page, $page_no, $collum_order, $order_by, $branch_cd, $branch_nm, $branch_kn){
        $url = URL_API."Setting/GetBranchInfo?tg_id=".$tg_id."&bank_nm=".$bank_nm."&bank_cd=".$bank_cd.'&page_no='.$page_no.'&per_page='.$per_page.'&order_by='.$order_by.'&collum_order='.$collum_order.'&branch_cd='.$branch_cd.'&branch_nm='.$branch_nm.'&branch_kn'.$branch_kn;
        $arrBody = array();
        $GetBranchInfo = get_api_common($post_id, $url, $arrBody, "GET");
        return $GetBranchInfo;
      }
      public function getSlectDic($post_id, $name = '', $tg_id = ''){
        $url = URL_API."Setting/Select_Dic?tg_id=".$tg_id."&dic_cls=".$name;
        $arrBody = array();
        $getSlectDic = get_api_common($post_id, $url, $arrBody, "GET");
        return $getSlectDic;
      }
      public function getSearchDictionary($post_id, $name = '', $tg_id = ''){
        $url = URL_API."Member/SearchDictionary?tg_id=".$tg_id."&dic_cls=".$name;
        $arrBody = array();
        $SearchDictionary = get_api_common($post_id, $url, $arrBody, "GET");
        return $SearchDictionary;
      }
     
     public function insertData($post_id,$arrValue){

        $url = URL_API.'Member/InsertData';
        $arrBody = array(
            "EMail" => $arrValue['email'],
            "TG_ID" => $arrValue['TG_ID'],
            "LG_ID" => $arrValue['LG_ID'],
            "C_FNAME" => $arrValue['C_FNAME'],
            "C_LNAME" => $arrValue['C_LNAME'],
            "C_FNAME_KN" => $arrValue['C_FNAME_KN'],
            "C_LNAME_KN" => $arrValue['C_LNAME_KN'],
            "USER_P_ID" => $arrValue['USER_P_ID']
        );
        $insertData = get_api_common($post_id, $url, $arrBody, "POST");
        return $insertData;
     }

     // Func check mail magazine
     public function checkMailMagazine($post_id, $tg_id, $email, $g_id){
        $url = URL_API.'Member/CheckMailMagazine';
        $arrBody = array(
            "P_ID" => (isset($_SESSION['arrSession']->P_ID))?$_SESSION['arrSession']->P_ID:"",
            "TG_ID"=> $tg_id,
            "Email" => $email,
            "G_ID" => $g_id
        );
        $checkMailMagazine = get_api_common($post_id, $url, $arrBody, "POST");
        return $checkMailMagazine;
     }

     // Func check mail magazine Delete
     public function checkMailMagazineDelete($post_id, $tg_id, $email, $g_id,$p_id){
        $url = URL_API.'Member/CheckMailMagazineDel';
        $arrBody = array(
            "TG_ID"=> $tg_id,
            "Email" => $email,
            "G_ID" => $g_id,
            "P_ID" => $p_id
        );
        $checkMailMagazineDelete = get_api_common($post_id, $url, $arrBody, "POST");
        return $checkMailMagazineDelete;
     }

    // get data Member/getGroupTree
    // public function memberGetGroupTree($tgId, $lgType)
    // {
    //     $url = URL_API. "Member/getGroupTree";

    //     $body = [
    //        "TG_ID"=> $tgId,
    //        "LG_TYPE"=> $lgType,
    //     ];

    //     $data = get_api_common($post_id, $url, $body, "POST");

    //     return $data;
    // }
    // Func delete mail magazine
    public function deleteMailMagazine($post_id, $p_id, $tg_id, $g_chg, $relmail, $pgh_relmail, $b_user_id){
      $url = URL_API.'Member/DeleteData';
      $arrBody = array(
          "P_ID"=> $p_id,
          "TG_ID" => $tg_id,
          "G_Chg" => $g_chg,
          "Relmail" => $relmail,
          "Pgh_Relmail" => $pgh_relmail,
          "B_User_Id" => $b_user_id
      );
      $deleteMailMagazine = get_api_common($post_id, $url, $arrBody, "POST");
      return $deleteMailMagazine;
    }

    // Func get recent group
    public function getRecentGroup($p_id, $tg_id, $func_id){
      $url = URL_API.'Member/getRecentGroup';
      $arrBody = array(
          "P_ID"=> $p_id,
          "TG_ID" => $tg_id,
          "FUNC_ID" => $func_id
      );
      $getRecentGroup = get_api_common($post_id, $url, $arrBody, "POST");
      return $getRecentGroup;
    }

    // Func SetMemberShipCard
    public function setMemberShipCard($post_id,$copy_url){
      $url = URL_API.'Member/SetMemberShipCard';
      $G_ID = ($_SESSION['arrSession']->G_ID)?$_SESSION['arrSession']->G_ID:"";
      $TG_ID = ($_SESSION['arrSession']->TG_ID)?$_SESSION['arrSession']->TG_ID:get_option('nakama-member-group-id');
      $P_ID = ($_SESSION['arrSession']->P_ID)?$_SESSION['arrSession']->P_ID:"";
      $arrBody = array(
          "g_id"=> $G_ID,
          "tg_id" => $TG_ID,
          "p_id" => $P_ID,
          "copy_url" => $copy_url
      );
      $setMemberShipCard = get_api_common($post_id, $url, $arrBody, "POST");
      return $setMemberShipCard;
    }

    // Func GetMemberShipCard
    public function getMemberShipCard($post_id,$tgid, $gid, $pid){
      $url = URL_API.'Member/GetMemberShipCard?tgid='.$tgid.'&gId='.$gid.'&pId='.$pid;
      $getMemberShipCard = get_api_common($post_id, $url, array(), "GET");
      return $getMemberShipCard;
    }

    /*
    * GMO Z.Com Runsystem
    * 2019/01/04
    * FUNC: convert Date
    */
    public function convertDates($date, $time, $label){
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

    public function registMember($bodyArr = array(),$flg_shortcode = true, $postid = null){
        $url = URL_API.'Member/RegisterMember/';
        $arrBody = array(
          "TG_ID" => ($flg_shortcode)?get_post_meta($postid,"member_meta_group_id",true):get_option('nakama-member-group-id'),
          "LTG_ID" => ($flg_shortcode)?get_post_meta($postid,"member_meta_group_id",true):get_option('nakama-member-group-id'),
          "LG_TYPE" => 0,
          "LG_ID" => !empty($bodyArr['M_LG_ID'])?$bodyArr['M_LG_ID']:get_post_meta($postid,"set_lg_g_id",true),
          "P_PASSWORD" => $bodyArr['P_PASSWORD'],
          "STATUS" => "",
          "CONTRACT_TYPE" => $bodyArr['M_CONTRACT_TYPE'],
          "GROUP_ADMIN" => 9,
          "M_USER_ID" => $bodyArr['M_USER_ID'],
          "RECOMMEND_P_ID" => $bodyArr['M_RECOMMEND_P_ID'],
          "RECOMMEND_P_ID2" => $bodyArr['M_RECOMMEND_P_ID2'],
          "RECOMMEND_P_ID3" => $bodyArr['M_RECOMMEND_P_ID3'],
          "RECOMMEND_P_ID4" => $bodyArr['M_RECOMMEND_P_ID4'],
          "RECOMMEND_P_ID5" => $bodyArr['M_RECOMMEND_P_ID5'],
          "X_COMMENT" => $bodyArr['M_X_COMMENT'],
          "RECEIVE_INFO_MAIL" => $bodyArr['M_RECEIVE_INFO_MAIL'],
          "RECEIVE_INFO_PMAIL" => 1,
          "RECEIVE_INFO_FAX" => $bodyArr['M_RECEIVE_INFO_FAX'],
          "FAX_TIMEZONE" => $bodyArr['M_FAX_TIMEZONE'],
          "FAX_TIME_FROM" => $bodyArr['FAX_TIME_FROM'],
          "FAX_TIME_TO" => $bodyArr['FAX_TIME_TO'],
          "MLMAGA_FLG" => $bodyArr['M_MLMAGA_FLG'],
          "DISP_LIST" => $bodyArr['M_DISP_LIST'],
          "DISP_DETAIL" => $bodyArr['M_DISP_DETAIL'],
          "DISP_MARKETING" => $bodyArr['M_DISP_MARKETING'],
          "AFFILIATION_NAME" => "",
          "OFFICIAL_POSITION" => "",
          // "CONTACT_ID" => $bodyArr['M_CONTACT_ID'],
          "CONTACT_ID" => ($bodyArr['M_CONTACT_ID'] == '@N')?'':$bodyArr['M_CONTACT_ID'],
          "CONTACT_LNG_MODE" => $bodyArr['M_CONTACT_LNG_MODE'],
          "CONTACT_G_NAME" => $bodyArr['M_CONTACT_G_NAME'],
          "CONTACT_G_NAME_KN" => $bodyArr['M_CONTACT_G_NAME_KN'],
          "CONTACT_G_NAME_EN" => $bodyArr['M_CONTACT_G_NAME_EN'],
          "CONTACT_C_FNAME" => explode(" ",$bodyArr['M_CONTACT_C_NAME'])[0],
          "CONTACT_C_LNAME" => (!empty(explode(" ",$bodyArr['M_CONTACT_C_NAME'])[1]))?explode(" ",$bodyArr['M_CONTACT_C_NAME'])[1]:"",
          "CONTACT_C_FNAME_KN" => explode(" ",$bodyArr['M_CONTACT_C_NAME_KN'])[0],
          "CONTACT_C_LNAME_KN" => (!empty(explode(" ",$bodyArr['M_CONTACT_C_NAME_KN'])[1]))?explode(" ",$bodyArr['M_CONTACT_C_NAME_KN'])[1]:"",
          "CONTACT_C_NAME_EN" => "",
          "CONTACT_EMAIL" => $bodyArr['M_CONTACT_EMAIL'],
          "CONTACT_CC_EMAIL" => $bodyArr['M_CONTACT_CC_EMAIL'],
          "CONTACT_TEL" => $bodyArr['M_CONTACT_TEL'],
          "CONTACT_FAX" => $bodyArr['M_CONTACT_FAX'],
          "CONTACT_POST" => $bodyArr['M_CONTACT_POST'],
          "CONTACT_STA" => $bodyArr['M_CONTACT_STA'],
          "CONTACT_ADR" => $bodyArr['M_CONTACT_ADR'],
          "CONTACT_ADR2" => $bodyArr['M_CONTACT_ADR2'],
          "CONTACT_ADR3" => $bodyArr['M_CONTACT_ADR3'],
          "CONTACT_ADR_EN" => $bodyArr['M_CONTACT_ADR_EN'],
          "CONTACT_AFFILIATION" => $bodyArr['M_CONTACT_AFFILIATION'],
          "CONTACT_POSITION" => $bodyArr['M_CONTACT_POSITION'],
          "ATENA_SUU" => $bodyArr['ATENA_SUU'],
          // "BILLING_ID" => $bodyArr['M_BILLING_ID'],
          "BILLING_ID" => ($bodyArr['M_BILLING_ID'] == '@N')?'':$bodyArr['M_BILLING_ID'],
          "BILLING_LNG_MODE" => $bodyArr['M_BILLING_LNG_MODE'],
          "BILLING_G_NAME" => $bodyArr['M_BILLING_G_NAME'],
          "BILLING_G_KANA" => $bodyArr['M_BILLING_G_KANA'],
          "BILLING_G_NAME_EN" => $bodyArr['M_BILLING_G_NAME_EN'],
          "BILLING_C_FNAME" => explode(" ",$bodyArr['M_BILLING_C_NAME'])[0],
          "BILLING_C_LNAME" => (!empty(explode(" ",$bodyArr['M_BILLING_C_NAME'])[1]))?explode(" ",$bodyArr['M_BILLING_C_NAME'])[1]:"",
          // "BILLING_C_FNAME_KN" => explode(" ",$bodyArr['M_CONTACT_C_NAME_KN'])[0],
          // "BILLING_C_LNAME_KN" => (!empty(explode(" ",$bodyArr['M_CONTACT_C_NAME_KN'])[1]))?explode(" ",$bodyArr['M_CONTACT_C_NAME_KN'])[1]:"",
          "BILLING_C_FNAME_KN" => explode(" ",$bodyArr['M_BILLING_C_NAME_KN'])[0],
          "BILLING_C_LNAME_KN" => (!empty(explode(" ",$bodyArr['M_BILLING_C_NAME_KN'])[1]))?explode(" ",$bodyArr['M_BILLING_C_NAME_KN'])[1]:"",
          "BILLING_C_NAME_EN" => "",
          "BILLING_EMAIL" => $bodyArr['M_BILLING_EMAIL'],
          "BILLING_CC_EMAIL" => $bodyArr['M_BILLING_CC_EMAIL'],
          "BILLING_TEL" => $bodyArr['M_BILLING_TEL'],
          "BILLING_FAX" => $bodyArr['M_BILLING_FAX'],
          "BILLING_POST" => $bodyArr['M_BILLING_POST'],
          "BILLING_STA" => $bodyArr['M_BILLING_STA'],
          "BILLING_ADR" => $bodyArr['M_BILLING_ADR'],
          "BILLING_ADR2" => $bodyArr['M_BILLING_ADR2'],
          "BILLING_ADR3" => $bodyArr['M_BILLING_ADR3'],
          "BILLING_ADR_EN" => $bodyArr['M_BILLING_ADR_EN'],
          "BILLING_AFFILIATION" => $bodyArr['M_BILLING_AFFILIATION'],
          "BILLING_POSITION" => $bodyArr['M_BILLING_POSITION'],
          "BANK_CD" => $bodyArr['M_BANK_CD'],
          "BRANCH_CD" => $bodyArr['M_BRANCH_CD'],
          "ACCOUNT_TYPE" => $bodyArr['M_ACCAUNT_TYPE'],
          "ACCOUNT_NO" => $bodyArr['M_ACCOUNT_NO'],
          "ACCOUNT_NM" => $bodyArr['M_ACCOUNT_NM'],
          "CUST_NO" => $bodyArr['M_CUST_NO'],
          "SAVINGS_CD" => $bodyArr['M_SAVINGS_CD'],
          "SAVINGS_NO" => $bodyArr['M_SAVINGS_NO'],
          "SETTLE_MONTH" => 70,
          "SHIPPING_ID" => "",
          "SHIPPING_LNG_MODE" => 1,
          "SHIPPING_G_NAME" => "",
          "SHIPPING_G_NAME_KN" => "",
          "SHIPPING_G_NAME_EN" => "",
          "SHIPPING_C_FNAME" => "",
          "SHIPPING_C_LNAME" => "",
          "SHIPPING_C_FNAME_KN" => "",
          "SHIPPING_C_LNAME_KN" => "",
          "SHIPPING_C_NAME_EN" => "",
          "SHIPPING_EMAIL" => "",
          "SHIPPING_CC_EMAIL" => "",
          "SHIPPING_TEL" => "",
          "SHIPPING_FAX" => "",
          "SHIPPING_POST" => "",
          "SHIPPING_STA" => "",
          "SHIPPING_ADR" => "",
          "SHIPPING_ADR2" => "",
          "SHIPPING_ADR3" => "",
          "SHIPPING_ADR_EN" => "",
          "SHIPPING_AFFILIATION" => "",
          "SHIPPING_POSITION" => "",
          "ADMISSION_DATE" => $bodyArr['M_ADMISSION_DATE'],
          "WITHDRAWAL_DATE" => $bodyArr['M_WITHDRAWAL_DATE'],
          "CHANGE_DATE" => $bodyArr['M_CHANGE_DATE'],
          "ADMISSION_REASON" => $bodyArr['M_ADMISSION_REASON'],
          "WITHDRAWAL_REASON" => $bodyArr['M_WITHDRAWAL_REASON'],
          "CHANGE_REASON" => $bodyArr['M_CHANGE_REASON'],
          "MOVEOUT_DATE" => $bodyArr['M_MOVEOUT_DATE'],
          "MOVEOUT_NOTE" => $bodyArr['M_MOVEOUT_NOTE'],
          "MOVEIN_DATE" => $bodyArr['M_MOVEIN_DATE'],
          "MOVEIN_NOTE" => $bodyArr['M_MOVEIN_NOTE'],
          "CLAIM_CLS" => ($bodyArr['M_CLAIM_CLS'] == "selected")?null:$bodyArr['M_CLAIM_CLS'],
          "FEE_RANK" => $bodyArr['M_FEE_RANK'],
          "CLAIM_CYCLE" => $bodyArr['M_CLAIM_CYCLE'],
          "FEE_MEMO" => $bodyArr['M_FEE_MEMO'],
          "ENTRUST_CD" => $bodyArr['M_ENTRUST_CD'],
          "FEE_UNIT" => 1.0,
          "MONTH_FEE" => 1.0,
          "TAX_ACCOUNTANT01" => $bodyArr['M_TAX_ACCOUNTANT1'],
          "TAX_ACCOUNTANT02" => $bodyArr['M_TAX_ACCOUNTANT2'],
          "TAX_ACCOUNTANT03" => $bodyArr['M_TAX_ACCOUNTANT3'],
          "TAX_ACCOUNTANT04" => $bodyArr['M_TAX_ACCOUNTANT4'],
          "TAX_ACCOUNTANT05" => $bodyArr['M_TAX_ACCOUNTANT5'],
          "TAX_ACCOUNTANT06" => $bodyArr['M_TAX_ACCOUNTANT6'],
          "TAX_ACCOUNTANT07" => $bodyArr['M_TAX_ACCOUNTANT7'],
          "TAX_ACCOUNTANT08" => $bodyArr['M_TAX_ACCOUNTANT8'],
          "TAX_ACCOUNTANT09" => $bodyArr['M_TAX_ACCOUNTANT9'],
          "TAX_ACCOUNTANT10" => $bodyArr['M_TAX_ACCOUNTANT10'],
          "M_FREE01" => implode("|",(array)$bodyArr['M_FREE1']),
          "M_FREE02" => implode("|",(array)$bodyArr['M_FREE2']),
          "M_FREE03" => implode("|",(array)$bodyArr['M_FREE3']),
          "M_FREE04" => implode("|",(array)$bodyArr['M_FREE4']),
          "M_FREE05" => implode("|",(array)$bodyArr['M_FREE5']),
          "M_FREE06" => implode("|",(array)$bodyArr['M_FREE6']),
          "M_FREE07" => implode("|",(array)$bodyArr['M_FREE7']),
          "M_FREE08" => implode("|",(array)$bodyArr['M_FREE8']),
          "M_FREE09" => implode("|",(array)$bodyArr['M_FREE9']),
          "M_FREE10" => implode("|",(array)$bodyArr['M_FREE10']),
          "M_FREE11" => implode("|",(array)$bodyArr['M_FREE11']),
          "M_FREE12" => implode("|",(array)$bodyArr['M_FREE12']),
          "M_FREE13" => implode("|",(array)$bodyArr['M_FREE13']),
          "M_FREE14" => implode("|",(array)$bodyArr['M_FREE14']),
          "M_FREE15" => implode("|",(array)$bodyArr['M_FREE15']),
          "M_FREE16" => implode("|",(array)$bodyArr['M_FREE16']),
          "M_FREE17" => implode("|",(array)$bodyArr['M_FREE17']),
          "M_FREE18" => implode("|",(array)$bodyArr['M_FREE18']),
          "M_FREE19" => implode("|",(array)$bodyArr['M_FREE19']),
          "M_FREE20" => implode("|",(array)$bodyArr['M_FREE20']),
          "M_FREE21" => implode("|",(array)$bodyArr['M_FREE21']),
          "M_FREE22" => implode("|",(array)$bodyArr['M_FREE22']),
          "M_FREE23" => implode("|",(array)$bodyArr['M_FREE23']),
          "M_FREE24" => implode("|",(array)$bodyArr['M_FREE24']),
          "M_FREE25" => implode("|",(array)$bodyArr['M_FREE25']),
          "M_FREE26" => implode("|",(array)$bodyArr['M_FREE26']),
          "M_FREE27" => implode("|",(array)$bodyArr['M_FREE27']),
          "M_FREE28" => implode("|",(array)$bodyArr['M_FREE28']),
          "M_FREE29" => implode("|",(array)$bodyArr['M_FREE29']),
          "M_FREE30" => implode("|",(array)$bodyArr['M_FREE30']),
          "M_FREE31" => implode("|",(array)$bodyArr['M_FREE31']),
          "M_FREE32" => implode("|",(array)$bodyArr['M_FREE32']),
          "M_FREE33" => implode("|",(array)$bodyArr['M_FREE33']),
          "M_FREE34" => implode("|",(array)$bodyArr['M_FREE34']),
          "M_FREE35" => implode("|",(array)$bodyArr['M_FREE35']),
          "M_FREE36" => implode("|",(array)$bodyArr['M_FREE36']),
          "M_FREE37" => implode("|",(array)$bodyArr['M_FREE37']),
          "M_FREE38" => implode("|",(array)$bodyArr['M_FREE38']),
          "M_FREE39" => implode("|",(array)$bodyArr['M_FREE39']),
          "M_FREE40" => implode("|",(array)$bodyArr['M_FREE40']),
          "M_FREE41" => implode("|",(array)$bodyArr['M_FREE41']),
          "M_FREE42" => implode("|",(array)$bodyArr['M_FREE42']),
          "M_FREE43" => implode("|",(array)$bodyArr['M_FREE43']),
          "M_FREE44" => implode("|",(array)$bodyArr['M_FREE44']),
          "M_FREE45" => implode("|",(array)$bodyArr['M_FREE45']),
          "M_FREE46" => implode("|",(array)$bodyArr['M_FREE46']),
          "M_FREE47" => implode("|",(array)$bodyArr['M_FREE47']),
          "M_FREE48" => implode("|",(array)$bodyArr['M_FREE48']),
          "M_FREE49" => implode("|",(array)$bodyArr['M_FREE49']),
          "M_FREE50" => implode("|",(array)$bodyArr['M_FREE50']),
          "O_M_FREE01" => 1,
          "O_M_FREE02" => 1,
          "O_M_FREE03" => 1,
          "O_M_FREE04" => 1,
          "O_M_FREE05" => 1,
          "O_M_FREE06" => 1,
          "O_M_FREE07" => 1,
          "O_M_FREE08" => 1,
          "O_M_FREE09" => 1,
          "O_M_FREE10" => 1,
          "O_M_FREE11" => 1,
          "O_M_FREE12" => 1,
          "O_M_FREE13" => 1,
          "O_M_FREE14" => 1,
          "O_M_FREE15" => 1,
          "O_M_FREE16" => 1,
          "O_M_FREE17" => 1,
          "O_M_FREE18" => 1,
          "O_M_FREE19" => 1,
          "O_M_FREE20" => 1,
          "O_M_FREE21" => 1,
          "O_M_FREE22" => 1,
          "O_M_FREE23" => 1,
          "O_M_FREE24" => 1,
          "O_M_FREE25" => 1,
          "O_M_FREE26" => 1,
          "O_M_FREE27" => 1,
          "O_M_FREE28" => 1,
          "O_M_FREE29" => 1,
          "O_M_FREE30" => 1,
          "O_M_FREE31" => 1,
          "O_M_FREE32" => 1,
          "O_M_FREE33" => 1,
          "O_M_FREE34" => 1,
          "O_M_FREE35" => 1,
          "O_M_FREE36" => 1,
          "O_M_FREE37" => 1,
          "O_M_FREE38" => 1,
          "O_M_FREE39" => 1,
          "O_M_FREE40" => 1,
          "O_M_FREE41" => 1,
          "O_M_FREE42" => 1,
          "O_M_FREE43" => 1,
          "O_M_FREE44" => 1,
          "O_M_FREE45" => 1,
          "O_M_FREE46" => 1,
          "O_M_FREE47" => 1,
          "O_M_FREE48" => 1,
          "O_M_FREE49" => 1,
          "O_M_FREE50" => 1,
          "G_M_KEYWORD" => $bodyArr['M_CONNECTION'],
          "G_M_FREE01" => "",
          "G_M_FREE02" => "",
          "G_M_FREE03" => "",
          "G_M_FREE04" => "",
          "G_M_FREE05" => "",
          "G_M_FREE06" => "",
          "G_M_FREE07" => "",
          "G_M_FREE08" => "",
          "G_M_FREE09" => "",
          "G_M_FREE10" => "",
          "G_M_FREE11" => "",
          "G_M_FREE12" => "",
          "G_M_FREE13" => "",
          "G_M_FREE14" => "",
          "G_M_FREE15" => "",
          "G_M_FREE16" => "",
          "G_M_FREE17" => "",
          "G_M_FREE18" => "",
          "G_M_FREE19" => "",
          "G_M_FREE20" => "",
          "G_M_FREE21" => "",
          "G_M_FREE22" => "",
          "G_M_FREE23" => "",
          "G_M_FREE24" => "",
          "G_M_FREE25" => "",
          "G_M_FREE26" => "",
          "G_M_FREE27" => "",
          "G_M_FREE28" => "",
          "G_M_FREE29" => "",
          "G_M_FREE30" => "",
          "G_M_FREE31" => "",
          "G_M_FREE32" => "",
          "G_M_FREE33" => "",
          "G_M_FREE34" => "",
          "G_M_FREE35" => "",
          "G_M_FREE36" => "",
          "G_M_FREE37" => "",
          "G_M_FREE38" => "",
          "G_M_FREE39" => "",
          "G_M_FREE40" => "",
          "G_M_FREE41" => "",
          "G_M_FREE42" => "",
          "G_M_FREE43" => "",
          "G_M_FREE44" => "",
          "G_M_FREE45" => "",
          "G_M_FREE46" => "",
          "G_M_FREE47" => "",
          "G_M_FREE48" => "",
          "G_M_FREE49" => "",
          "G_M_FREE50" => "",
          "S_CONTACT_TEL" => "",
          "S_CONTACT_FAX" => "",
          "S_BILLING_TEL" => "",
          "S_BILLING_FAX" => "",
          "S_SHIPPING_TEL" => "",
          "S_SHIPPING_FAX" => "",
          "AUTHORIZATION_FLG" => 1,
          "POINT_RESET_DATE" => "",
          "ACTIVE_ORG_TOPGID" => "",
          "CLAIM_STS" => 1,
          "UNPAID_STS" => 1,
          "EVENT_STS" => 1,
          "PRIZE_STS" => 1,
          "POST_STS" => 1,
          "M_CLASS" => "",
          "BOUNCE_MAIL_FLG" => 1,
          "OWNER" => ($flg_shortcode)?get_post_meta($postid,"member_meta_group_id",true):get_option('nakama-member-group-id'),
          "G_USER_ID" => $bodyArr['G_USER_ID'],
          "G_NAME" => $bodyArr['G_NAME'],
          "G_NAME_EN" => $bodyArr['G_NAME_EN'],
          "G_NAME_KN" => $bodyArr['G_NAME_KN'],
          "G_NAME_AN" => $bodyArr['G_NAME_AN'],
          "G_URL" => $bodyArr['G_URL'],
          "G_P_URL" => $bodyArr['G_P_URL'],
          "G_EMAIL" => $bodyArr['G_EMAIL'],
          "G_CC_EMAIL" => $bodyArr['G_CC_EMAIL'],
          "G_TEL" => $bodyArr['G_TEL'],
          "G_FAX" => $bodyArr['G_FAX'],
          "G_POST" => $bodyArr['G_POST'],
          "G_STA" => $bodyArr['G_STA'],
          "G_ADR" => $bodyArr['G_ADR'],
          "G_ADR2" => $bodyArr['G_ADR2'],
          "G_ADR3" => $bodyArr['G_ADR3'],
          "G_ADR_EN" => $bodyArr['G_ADR_EN'],
          "LNG_MODE" => $bodyArr['G_LNG_MODE'],
          "INDUSTRY_NM" => $bodyArr['G_INDUSTRY_NM'],
          "INDUSTRY_CD" => $bodyArr['G_INDUSTRY_CD'],
          "ORG_BANK_CD" => $bodyArr['G_BANK_CD'],
          "ORG_BRANCH_CD" => $bodyArr['G_BRANCH_CD'],
          "ORG_ACCOUNT_TYPE" => $bodyArr['G_ACCAUNT_TYPE'],
          "ORG_ACCOUNT_NO" => $bodyArr['G_ACCOUNT_NO'],
          "ORG_ACCOUNT_NM" => $bodyArr['G_ACCAUNT_NM'],
          "ORG_CUST_NO" => $bodyArr['G_CUST_NO'],
          "ORG_SAVINGS_CD" => $bodyArr['G_SAVINGS_CD'],
          "ORG_SAVINGS_NO" => $bodyArr['G_SAVINGS_NO'],
          "ORG_SETTLE_MONTH" => 1,
          "FOUND_DATE" => $bodyArr['G_FOUND_DATE'],
          "CAPITAL" => $bodyArr['G_CAPITAL'],
          "REPRESENTATIVE_NM" => $bodyArr['G_REPRESENTATIVE_NM'],
          "REPRESENTATIVE_KN" => $bodyArr['G_REPRESENTATIVE_KN'],
          "REPRESENTATIVE_OP" => $bodyArr['G_REPRESENTATIVE_OP'],
          "REPRESENTATIVE_DATE" => "",
          "REPRESENTATIVE_ID" => "",
          "G_LOGO" => $bodyArr['G_LOGO'],
          "G_IMG" => $bodyArr['G_IMG'],
          "G_APPEAL" => $bodyArr['G_APPEAL'],
          "O_G_NAME" => $bodyArr['G_O_NAME'],
          "O_G_NAME_KN" => $bodyArr['G_O_KANA'],
          "O_G_NAME_AN" => $bodyArr['G_O_SNAME'],
          "O_G_URL" => $bodyArr['G_O_URL'],
          "O_G_P_URL" => $bodyArr['G_O_P_URL'],
          "O_G_EMAIL" => $bodyArr['G_O_EMAIL'],
          "O_G_CC_EMAIL" => $bodyArr['G_O_CC_EMAIL'],
          "O_G_TEL" => $bodyArr['G_O_TEL'],
          "O_G_FAX" => $bodyArr['G_O_FAX'],
          "O_G_POST" => $bodyArr['G_O_POST'],
          "O_G_STA" => $bodyArr['G_O_STA'],
          "O_G_ADR" => $bodyArr['G_O_ADDRESS'],
          "O_INDUSTRY_NM" => $bodyArr['G_O_CATEGORY'],
          "O_INDUSTRY_CD" => $bodyArr['G_O_CATEGORY_CODE'],
          "O_FOUND_DATE" => $bodyArr['G_O_FOUND_DATE'],
          "O_SETTLE_MONTH" => $bodyArr['G_O_SETTLE_MONTH'],
          "O_CAPITAL" => $bodyArr['G_O_CAPITAL'],
          "O_REPRESENTATIVE_NM" => $bodyArr['G_O_REPRESENTATIVE'],
          "O_REPRESENTATIVE_KN" => $bodyArr['G_O_REPRESENTATIVE_KANA'],
          "O_REPRESENTATIVE_OP" => 1,
          "O_REPRESENTATIVE_DATE" => 1,
          "O_G_LOGO" => $bodyArr['G_O_LOGO'],
          "O_G_IMG" => $bodyArr['G_O_IMG'],
          "O_G_APPEAL" => $bodyArr['G_O_APPEAL'],
          "S_G_TEL" => "",
          "S_G_FAX" => "",
          "G_FREE01" => implode("|",(array)$bodyArr['G_FREE1']),
          "G_FREE02" => implode("|",(array)$bodyArr['G_FREE2']),
          "G_FREE03" => implode("|",(array)$bodyArr['G_FREE3']),
          "G_FREE04" => implode("|",(array)$bodyArr['G_FREE4']),
          "G_FREE05" => implode("|",(array)$bodyArr['G_FREE5']),
          "G_FREE06" => implode("|",(array)$bodyArr['G_FREE6']),
          "G_FREE07" => implode("|",(array)$bodyArr['G_FREE7']),
          "G_FREE08" => implode("|",(array)$bodyArr['G_FREE8']),
          "G_FREE09" => implode("|",(array)$bodyArr['G_FREE9']),
          "G_FREE10" => implode("|",(array)$bodyArr['G_FREE10']),
          "G_FREE11" => implode("|",(array)$bodyArr['G_FREE11']),
          "G_FREE12" => implode("|",(array)$bodyArr['G_FREE12']),
          "G_FREE13" => implode("|",(array)$bodyArr['G_FREE13']),
          "G_FREE14" => implode("|",(array)$bodyArr['G_FREE14']),
          "G_FREE15" => implode("|",(array)$bodyArr['G_FREE15']),
          "G_FREE16" => implode("|",(array)$bodyArr['G_FREE16']),
          "G_FREE17" => implode("|",(array)$bodyArr['G_FREE17']),
          "G_FREE18" => implode("|",(array)$bodyArr['G_FREE18']),
          "G_FREE19" => implode("|",(array)$bodyArr['G_FREE19']),
          "G_FREE20" => implode("|",(array)$bodyArr['G_FREE20']),
          "G_FREE21" => implode("|",(array)$bodyArr['G_FREE21']),
          "G_FREE22" => implode("|",(array)$bodyArr['G_FREE22']),
          "G_FREE23" => implode("|",(array)$bodyArr['G_FREE23']),
          "G_FREE24" => implode("|",(array)$bodyArr['G_FREE24']),
          "G_FREE25" => implode("|",(array)$bodyArr['G_FREE25']),
          "G_FREE26" => implode("|",(array)$bodyArr['G_FREE26']),
          "G_FREE27" => implode("|",(array)$bodyArr['G_FREE27']),
          "G_FREE28" => implode("|",(array)$bodyArr['G_FREE28']),
          "G_FREE29" => implode("|",(array)$bodyArr['G_FREE29']),
          "G_FREE30" => implode("|",(array)$bodyArr['G_FREE30']),
          "G_FREE31" => implode("|",(array)$bodyArr['G_FREE31']),
          "G_FREE32" => implode("|",(array)$bodyArr['G_FREE32']),
          "G_FREE33" => implode("|",(array)$bodyArr['G_FREE33']),
          "G_FREE34" => implode("|",(array)$bodyArr['G_FREE34']),
          "G_FREE35" => implode("|",(array)$bodyArr['G_FREE35']),
          "G_FREE36" => implode("|",(array)$bodyArr['G_FREE36']),
          "G_FREE37" => implode("|",(array)$bodyArr['G_FREE37']),
          "G_FREE38" => implode("|",(array)$bodyArr['G_FREE38']),
          "G_FREE39" => implode("|",(array)$bodyArr['G_FREE39']),
          "G_FREE40" => implode("|",(array)$bodyArr['G_FREE40']),
          "G_FREE41" => implode("|",(array)$bodyArr['G_FREE41']),
          "G_FREE42" => implode("|",(array)$bodyArr['G_FREE42']),
          "G_FREE43" => implode("|",(array)$bodyArr['G_FREE43']),
          "G_FREE44" => implode("|",(array)$bodyArr['G_FREE44']),
          "G_FREE45" => implode("|",(array)$bodyArr['G_FREE45']),
          "G_FREE46" => implode("|",(array)$bodyArr['G_FREE46']),
          "G_FREE47" => implode("|",(array)$bodyArr['G_FREE47']),
          "G_FREE48" => implode("|",(array)$bodyArr['G_FREE48']),
          "G_FREE49" => implode("|",(array)$bodyArr['G_FREE49']),
          "G_FREE50" => implode("|",(array)$bodyArr['G_FREE50']),
          "O_G_FREE01" => $bodyArr['G_O_BIKOU1'],
          "O_G_FREE02" => $bodyArr['G_O_BIKOU2'],
          "O_G_FREE03" => $bodyArr['G_O_BIKOU3'],
          "O_G_FREE04" => $bodyArr['G_O_BIKOU4'],
          "O_G_FREE05" => $bodyArr['G_O_BIKOU5'],
          "O_G_FREE06" => $bodyArr['G_O_BIKOU6'],
          "O_G_FREE07" => $bodyArr['G_O_BIKOU7'],
          "O_G_FREE08" => $bodyArr['G_O_BIKOU8'],
          "O_G_FREE09" => $bodyArr['G_O_BIKOU9'],
          "O_G_FREE10" => $bodyArr['G_O_BIKOU10'],
          "O_G_FREE11" => $bodyArr['G_O_BIKOU11'],
          "O_G_FREE12" => $bodyArr['G_O_BIKOU12'],
          "O_G_FREE13" => $bodyArr['G_O_BIKOU13'],
          "O_G_FREE14" => $bodyArr['G_O_BIKOU14'],
          "O_G_FREE15" => $bodyArr['G_O_BIKOU15'],
          "O_G_FREE16" => $bodyArr['G_O_BIKOU16'],
          "O_G_FREE17" => $bodyArr['G_O_BIKOU17'],
          "O_G_FREE18" => $bodyArr['G_O_BIKOU18'],
          "O_G_FREE19" => $bodyArr['G_O_BIKOU19'],
          "O_G_FREE20" => $bodyArr['G_O_BIKOU20'],
          "O_G_FREE21" => $bodyArr['G_O_BIKOU21'],
          "O_G_FREE22" => $bodyArr['G_O_BIKOU22'],
          "O_G_FREE23" => $bodyArr['G_O_BIKOU23'],
          "O_G_FREE24" => $bodyArr['G_O_BIKOU24'],
          "O_G_FREE25" => $bodyArr['G_O_BIKOU25'],
          "O_G_FREE26" => $bodyArr['G_O_BIKOU26'],
          "O_G_FREE27" => $bodyArr['G_O_BIKOU27'],
          "O_G_FREE28" => $bodyArr['G_O_BIKOU28'],
          "O_G_FREE29" => $bodyArr['G_O_BIKOU29'],
          "O_G_FREE30" => $bodyArr['G_O_BIKOU30'],
          "O_G_FREE31" => $bodyArr['G_O_BIKOU31'],
          "O_G_FREE32" => $bodyArr['G_O_BIKOU32'],
          "O_G_FREE33" => $bodyArr['G_O_BIKOU33'],
          "O_G_FREE34" => $bodyArr['G_O_BIKOU34'],
          "O_G_FREE35" => $bodyArr['G_O_BIKOU35'],
          "O_G_FREE36" => $bodyArr['G_O_BIKOU36'],
          "O_G_FREE37" => $bodyArr['G_O_BIKOU37'],
          "O_G_FREE38" => $bodyArr['G_O_BIKOU38'],
          "O_G_FREE39" => $bodyArr['G_O_BIKOU39'],
          "O_G_FREE40" => $bodyArr['G_O_BIKOU40'],
          "O_G_FREE41" => $bodyArr['G_O_BIKOU41'],
          "O_G_FREE42" => $bodyArr['G_O_BIKOU42'],
          "O_G_FREE43" => $bodyArr['G_O_BIKOU43'],
          "O_G_FREE44" => $bodyArr['G_O_BIKOU44'],
          "O_G_FREE45" => $bodyArr['G_O_BIKOU45'],
          "O_G_FREE46" => $bodyArr['G_O_BIKOU46'],
          "O_G_FREE47" => $bodyArr['G_O_BIKOU47'],
          "O_G_FREE48" => $bodyArr['G_O_BIKOU48'],
          "O_G_FREE49" => $bodyArr['G_O_BIKOU49'],
          "O_G_FREE50" => $bodyArr['G_O_BIKOU50'],
          "G_MARKETING01" => $bodyArr['G_MARKETING01'],
          "G_MARKETING02" => $bodyArr['G_MARKETING02'],
          "G_MARKETING03" => $bodyArr['G_MARKETING03'],
          "G_MARKETING04" => $bodyArr['G_MARKETING04'],
          "G_MARKETING05" => $bodyArr['G_MARKETING05'],
          "G_MARKETING06" => $bodyArr['G_MARKETING06'],
          "G_MARKETING07" => $bodyArr['G_MARKETING07'],
          "G_MARKETING08" => $bodyArr['G_MARKETING08'],
          "G_MARKETING09" => $bodyArr['G_MARKETING09'],
          "G_MARKETING10" => $bodyArr['G_MARKETING10'],
          "G_MARKETING11" => $bodyArr['G_MARKETING11'],
          "G_MARKETING12" => $bodyArr['G_MARKETING12'],
          "G_MARKETING13" => $bodyArr['G_MARKETING13'],
          "G_MARKETING14" => $bodyArr['G_MARKETING14'],
          "G_MARKETING15" => $bodyArr['G_MARKETING15'],
          "G_MARKETING16" => $bodyArr['G_ADD_MARKETING16'],
          "G_MARKETING17" => $bodyArr['G_ADD_MARKETING17'],
          "G_MARKETING18" => $bodyArr['G_ADD_MARKETING18'],
          "G_MARKETING19" => $bodyArr['G_ADD_MARKETING19'],
          "G_MARKETING20" => $bodyArr['G_ADD_MARKETING20'],
          "G_KEYWORD" => $bodyArr['G_KEYWORD'],
          "G_G_FREE01" => "",
          "G_G_FREE02" => "",
          "G_G_FREE03" => "",
          "G_G_FREE04" => "",
          "G_G_FREE05" => "",
          "G_G_FREE06" => "",
          "G_G_FREE07" => "",
          "G_G_FREE08" => "",
          "G_G_FREE09" => "",
          "G_G_FREE10" => "",
          "G_G_FREE11" => "",
          "G_G_FREE12" => "",
          "G_G_FREE13" => "",
          "G_G_FREE14" => "",
          "G_G_FREE15" => "",
          "G_G_FREE16" => "",
          "G_G_FREE17" => "",
          "G_G_FREE18" => "",
          "G_G_FREE19" => "",
          "G_G_FREE20" => "",
          "G_G_FREE21" => "",
          "G_G_FREE22" => "",
          "G_G_FREE23" => "",
          "G_G_FREE24" => "",
          "G_G_FREE25" => "",
          "G_G_FREE26" => "",
          "G_G_FREE27" => "",
          "G_G_FREE28" => "",
          "G_G_FREE29" => "",
          "G_G_FREE30" => "",
          "G_G_FREE31" => "",
          "G_G_FREE32" => "",
          "G_G_FREE33" => "",
          "G_G_FREE34" => "",
          "G_G_FREE35" => "",
          "G_G_FREE36" => "",
          "G_G_FREE37" => "",
          "G_G_FREE38" => "",
          "G_G_FREE39" => "",
          "G_G_FREE40" => "",
          "G_G_FREE41" => "",
          "G_G_FREE42" => "",
          "G_G_FREE43" => "",
          "G_G_FREE44" => "",
          "G_G_FREE45" => "",
          "G_G_FREE46" => "",
          "G_G_FREE47" => "",
          "G_G_FREE48" => "",
          "G_G_FREE49" => "",
          "G_G_FREE50" => "",
          "NO_ORGANIZATION_FLG" => "",
          "USER_P_ID2" => "",
          "PER_BANK_CD" => $bodyArr['P_BANK_CD'],
          "PER_BRANCH_CD" => $bodyArr['P_BRANCH_CD'],
          "PER_ACCOUNT_TYPE" => $bodyArr['P_ACCAUNT_TYPE'],
          "PER_ACCOUNT_NO" => $bodyArr['P_ACCOUNT_NO'],
          "PER_ACCOUNT_NM" => $bodyArr['P_ACCOUNT_NM'],
          "PER_CUST_NO" => $bodyArr['P_CUST_NO'],
          "PER_SAVINGS_CD" => $bodyArr['P_SAVINGS_CD'],
          "PER_SAVINGS_NO" => $bodyArr['P_SAVINGS_NO'],
          "P_PASSWORD2" => $bodyArr['P_PASSWORD2'],
          "C_FNAME" => explode(" ",$bodyArr['P_C_NAME'])[0],
          "C_LNAME" => (!empty(explode(" ",$bodyArr['P_C_NAME'])[1]))?explode(" ",$bodyArr['P_C_NAME'])[1]:"",
          "C_FNAME_KN" => explode(" ",$bodyArr['P_C_KANA'])[0],
          "C_LNAME_KN" => (!empty(explode(" ",$bodyArr['P_C_KANA'])[1]))?explode(" ",$bodyArr['P_C_KANA'])[1]:"",
          "C_NAME_EN" => $bodyArr['P_C_NAME_EN'],
          "C_SEX" => $bodyArr['P_C_SEX'],
          "C_URL" => $bodyArr['P_C_URL'],
          "C_EMAIL" => $bodyArr['P_C_EMAIL'],
          "C_CC_EMAIL" => $bodyArr['P_C_CC_EMAIL'],
          "C_PMAIL" => $bodyArr['P_C_PMAIL'],
          "C_TEL" => $bodyArr['P_C_TEL'],
          "C_FAX" => $bodyArr['P_C_FAX'],
          "C_PTEL" => $bodyArr['P_C_PTEL'],
          "C_POST" => $bodyArr['P_C_POST'],
          "C_STA" => $bodyArr['P_C_STA'],
          "C_ADR" => $bodyArr['P_C_ADR'],
          "C_ADR2" => $bodyArr['P_C_ADR2'],
          "C_ADR3" => $bodyArr['P_C_ADR3'],
          "C_ADR_EN" => $bodyArr['P_C_ADR_EN'],
          "SP_FLG" => 1,
          "LOGIN_LOCK_FLG" => $bodyArr['P_LOGIN_LOCK_FLG'],
          "GROUP_ENABLE_FLG" => $bodyArr['P_GROUP_ENABLE_FLG'],
          "MEETING_NM_DISP" => $bodyArr['P_MEETING_NM_DISP'],
          "HANDLE_NM" => $bodyArr['P_HANDLE_NM'],
          "MEETING_NM_MK" => $bodyArr['P_MEETING_NM_MK'],
          "C_IMG" => $bodyArr['P_C_IMG'],
          "C_IMG2" => $bodyArr['P_C_IMG2'],
          "C_IMG3" => $bodyArr['P_C_IMG3'],
          "C_APPEAL" => $bodyArr['P_C_APPEAL'],
          "P_URL" => $bodyArr['P_P_URL'],
          "P_EMAIL" => $bodyArr['P_P_EMAIL'],
          "P_CC_EMAIL" => $bodyArr['P_P_CC_EMAIL'],
          "P_PMAIL" => $bodyArr['P_P_PMAIL'],
          "P_TEL" => $bodyArr['P_P_TEL'],
          "P_FAX" => $bodyArr['P_P_FAX'],
          "P_PTEL" => $bodyArr['P_P_PTEL'],
          "P_POST" => $bodyArr['P_P_POST'],
          "P_STA" => $bodyArr['P_P_STA'],
          "P_ADR" => $bodyArr['P_P_ADR'],
          "P_ADR2" => $bodyArr['P_P_ADR2'],
          "P_ADR3" => $bodyArr['P_P_ADR3'],
          "P_ADR_EN" => $bodyArr['P_P_ADR_EN'],
          "P_BIRTH" => $bodyArr['P_P_BIRTH'],
          "P_CREDITCARD_TYPE" => "",
          "P_CREDITCARD_NO" => "",
          "P_CREDITCARD_NM" => "",
          "P_CREDITCARD_GT" => "",
          "CARD_OPEN" => $bodyArr['P_CARD_OPEN'],
          "O_C_FNAME" => $bodyArr['P_O_NAME'],
          "O_C_LNAME" => $bodyArr['P_O_KANA'],
          "O_C_SEX" => $bodyArr['P_O_SEX'],
          "O_C_URL" => $bodyArr['P_O_URL'],
          "O_C_EMAIL" => $bodyArr['P_O_EMAIL'],
          "O_C_CC_EMAIL" => $bodyArr['P_O_C_CC_EMAIL'],
          "O_C_PMAIL" => $bodyArr['P_O_PMAIL'],
          "O_C_TEL" => $bodyArr['P_O_TEL'],
          "O_C_FAX" => $bodyArr['P_O_FAX'],
          "O_C_PTEL" => $bodyArr['P_O_PTEL'],
          "O_C_POST" => $bodyArr['P_O_POST'],
          "O_C_STA" => $bodyArr['P_O_STA'],
          "O_C_ADR" => $bodyArr['O_C_ADR'],
          "O_G_ID" => $bodyArr['P_O_G_ID'],
          "O_C_IMG" => $bodyArr['P_O_IMG'],
          "O_C_IMG2" => $bodyArr['P_O_IMG2'],
          "O_C_IMG3" => $bodyArr['P_O_IMG3'],
          "O_C_APPEAL" => $bodyArr['P_O_APPEAL'],
          "PRIVATE_OPEN" => $bodyArr['P_PRIVATE_OPEN'],
          "O_P_URL" => 1,
          "O_P_EMAIL" => 1,
          "O_P_CC_EMAIL" => 1,
          "O_P_PMAIL" => 1,
          "O_P_TEL" => 1,
          "O_P_FAX" => 1,
          "O_P_PTEL" => 1,
          "O_P_POST" => 1,
          "O_P_STA" => 1,
          "O_P_ADR" => 1,
          "O_P_BIRTH" => 1,
          "O_OFFICIAL" => 1,
          "O_AFFILIATION" => 1,
          "S_C_TEL" => "",
          "S_C_FAX" => "",
          "S_C_PTEL" => "",
          "S_P_TEL" => "",
          "S_P_FAX" => "",
          "S_P_PTEL" => "",
          "MOBILE_TERM_ID" => "",
          "MAIL_NOTICE_DATE" => "2018-12-19T13:57:25.2297493+07:00",
          "URL_NOTICE_DATE" => "2018-12-19T13:57:25.2297493+07:00",
          "FELICA_ID" => "",
          "FAMILY_MEMBER_PASSWORD" => "",
          "NO_PERSONAL_FLG" => "",
          "C_FREE01" => $bodyArr['P_C_FREE1'],
          "C_FREE02" => $bodyArr['P_C_FREE2'],
          "C_FREE03" => $bodyArr['P_C_FREE3'],
          "C_FREE04" => $bodyArr['P_C_FREE4'],
          "C_FREE05" => $bodyArr['P_C_FREE5'],
          "C_FREE06" => $bodyArr['P_C_FREE6'],
          "C_FREE07" => $bodyArr['P_C_FREE7'],
          "C_FREE08" => $bodyArr['P_C_FREE8'],
          "C_FREE09" => $bodyArr['P_C_FREE9'],
          "C_FREE10" => $bodyArr['P_C_FREE10'],
          "C_FREE11" => $bodyArr['P_C_FREE11'],
          "C_FREE12" => $bodyArr['P_C_FREE12'],
          "C_FREE13" => $bodyArr['P_C_FREE13'],
          "C_FREE14" => $bodyArr['P_C_FREE14'],
          "C_FREE15" => $bodyArr['P_C_FREE15'],
          "C_FREE16" => $bodyArr['P_C_FREE16'],
          "C_FREE17" => $bodyArr['P_C_FREE17'],
          "C_FREE18" => $bodyArr['P_C_FREE18'],
          "C_FREE19" => $bodyArr['P_C_FREE19'],
          "C_FREE20" => $bodyArr['P_C_FREE20'],
          "C_FREE21" => $bodyArr['P_C_FREE21'],
          "C_FREE22" => $bodyArr['P_C_FREE22'],
          "C_FREE23" => $bodyArr['P_C_FREE23'],
          "C_FREE24" => $bodyArr['P_C_FREE24'],
          "C_FREE25" => $bodyArr['P_C_FREE25'],
          "C_FREE26" => $bodyArr['P_C_FREE26'],
          "C_FREE27" => $bodyArr['P_C_FREE27'],
          "C_FREE28" => $bodyArr['P_C_FREE28'],
          "C_FREE29" => $bodyArr['P_C_FREE29'],
          "C_FREE30" => $bodyArr['P_C_FREE30'],
          "C_FREE31" => $bodyArr['P_C_FREE31'],
          "C_FREE32" => $bodyArr['P_C_FREE32'],
          "C_FREE33" => $bodyArr['P_C_FREE33'],
          "C_FREE34" => $bodyArr['P_C_FREE34'],
          "C_FREE35" => $bodyArr['P_C_FREE35'],
          "C_FREE36" => $bodyArr['P_C_FREE36'],
          "C_FREE37" => $bodyArr['P_C_FREE37'],
          "C_FREE38" => $bodyArr['P_C_FREE38'],
          "C_FREE39" => $bodyArr['P_C_FREE39'],
          "C_FREE40" => $bodyArr['P_C_FREE40'],
          "C_FREE41" => $bodyArr['P_C_FREE41'],
          "C_FREE42" => $bodyArr['P_C_FREE42'],
          "C_FREE43" => $bodyArr['P_C_FREE43'],
          "C_FREE44" => $bodyArr['P_C_FREE44'],
          "C_FREE45" => $bodyArr['P_C_FREE45'],
          "C_FREE46" => $bodyArr['P_C_FREE46'],
          "C_FREE47" => $bodyArr['P_C_FREE47'],
          "C_FREE48" => $bodyArr['P_C_FREE48'],
          "C_FREE49" => $bodyArr['P_C_FREE49'],
          "C_FREE50" => $bodyArr['P_C_FREE50'],
          "O_FREE01" => $bodyArr['P_O_BIKOU1'],
          "O_FREE02" => $bodyArr['P_O_BIKOU2'],
          "O_FREE03" => $bodyArr['P_O_BIKOU3'],
          "O_FREE04" => $bodyArr['P_O_BIKOU4'],
          "O_FREE05" => $bodyArr['P_O_BIKOU5'],
          "O_FREE06" => $bodyArr['P_O_BIKOU6'],
          "O_FREE07" => $bodyArr['P_O_BIKOU7'],
          "O_FREE08" => $bodyArr['P_O_BIKOU8'],
          "O_FREE09" => $bodyArr['P_O_BIKOU9'],
          "O_FREE10" => $bodyArr['P_O_BIKOU10'],
          "O_FREE11" => $bodyArr['P_O_BIKOU11'],
          "O_FREE12" => $bodyArr['P_O_BIKOU12'],
          "O_FREE13" => $bodyArr['P_O_BIKOU13'],
          "O_FREE14" => $bodyArr['P_O_BIKOU14'],
          "O_FREE15" => $bodyArr['P_O_BIKOU15'],
          "O_FREE16" => $bodyArr['P_O_BIKOU16'],
          "O_FREE17" => $bodyArr['P_O_BIKOU17'],
          "O_FREE18" => $bodyArr['P_O_BIKOU18'],
          "O_FREE19" => $bodyArr['P_O_BIKOU19'],
          "O_FREE20" => $bodyArr['P_O_BIKOU20'],
          "O_FREE21" => $bodyArr['P_O_BIKOU21'],
          "O_FREE22" => $bodyArr['P_O_BIKOU22'],
          "O_FREE23" => $bodyArr['P_O_BIKOU23'],
          "O_FREE24" => $bodyArr['P_O_BIKOU24'],
          "O_FREE25" => $bodyArr['P_O_BIKOU25'],
          "O_FREE26" => $bodyArr['P_O_BIKOU26'],
          "O_FREE27" => $bodyArr['P_O_BIKOU27'],
          "O_FREE28" => $bodyArr['P_O_BIKOU28'],
          "O_FREE29" => $bodyArr['P_O_BIKOU29'],
          "O_FREE30" => $bodyArr['P_O_BIKOU30'],
          "O_FREE31" => $bodyArr['P_O_BIKOU31'],
          "O_FREE32" => $bodyArr['P_O_BIKOU32'],
          "O_FREE33" => $bodyArr['P_O_BIKOU33'],
          "O_FREE34" => $bodyArr['P_O_BIKOU34'],
          "O_FREE35" => $bodyArr['P_O_BIKOU35'],
          "O_FREE36" => $bodyArr['P_O_BIKOU36'],
          "O_FREE37" => $bodyArr['P_O_BIKOU37'],
          "O_FREE38" => $bodyArr['P_O_BIKOU38'],
          "O_FREE39" => $bodyArr['P_O_BIKOU39'],
          "O_FREE40" => $bodyArr['P_O_BIKOU40'],
          "O_FREE41" => $bodyArr['P_O_BIKOU41'],
          "O_FREE42" => $bodyArr['P_O_BIKOU42'],
          "O_FREE43" => $bodyArr['P_O_BIKOU43'],
          "O_FREE44" => $bodyArr['P_O_BIKOU44'],
          "O_FREE45" => $bodyArr['P_O_BIKOU45'],
          "O_FREE46" => $bodyArr['P_O_BIKOU46'],
          "O_FREE47" => $bodyArr['P_O_BIKOU47'],
          "O_FREE48" => $bodyArr['P_O_BIKOU48'],
          "O_FREE49" => $bodyArr['P_O_BIKOU49'],
          "O_FREE50" => $bodyArr['P_O_BIKOU50'],
          "P_FREE01" => $bodyArr['P_P_FREE1'],
          "P_FREE02" => $bodyArr['P_P_FREE2'],
          "P_FREE03" => $bodyArr['P_P_FREE3'],
          "P_FREE04" => $bodyArr['P_P_FREE4'],
          "P_FREE05" => $bodyArr['P_P_FREE5'],
          "P_FREE06" => $bodyArr['P_P_FREE6'],
          "P_FREE07" => $bodyArr['P_P_FREE7'],
          "P_FREE08" => $bodyArr['P_P_FREE8'],
          "P_FREE09" => $bodyArr['P_P_FREE9'],
          "P_FREE10" => $bodyArr['P_P_FREE10'],
          "P_FREE11" => $bodyArr['P_P_FREE11'],
          "P_FREE12" => $bodyArr['P_P_FREE12'],
          "P_FREE13" => $bodyArr['P_P_FREE13'],
          "P_FREE14" => $bodyArr['P_P_FREE14'],
          "P_FREE15" => $bodyArr['P_P_FREE15'],
          "P_FREE16" => $bodyArr['P_P_FREE16'],
          "P_FREE17" => $bodyArr['P_P_FREE17'],
          "P_FREE18" => $bodyArr['P_P_FREE18'],
          "P_FREE19" => $bodyArr['P_P_FREE19'],
          "P_FREE20" => $bodyArr['P_P_FREE20'],
          "P_FREE21" => $bodyArr['P_P_FREE21'],
          "P_FREE22" => $bodyArr['P_P_FREE22'],
          "P_FREE23" => $bodyArr['P_P_FREE23'],
          "P_FREE24" => $bodyArr['P_P_FREE24'],
          "P_FREE25" => $bodyArr['P_P_FREE25'],
          "P_FREE26" => $bodyArr['P_P_FREE26'],
          "P_FREE27" => $bodyArr['P_P_FREE27'],
          "P_FREE28" => $bodyArr['P_P_FREE28'],
          "P_FREE29" => $bodyArr['P_P_FREE29'],
          "P_FREE30" => $bodyArr['P_P_FREE30'],
          "P_FREE31" => $bodyArr['P_P_FREE31'],
          "P_FREE32" => $bodyArr['P_P_FREE32'],
          "P_FREE33" => $bodyArr['P_P_FREE33'],
          "P_FREE34" => $bodyArr['P_P_FREE34'],
          "P_FREE35" => $bodyArr['P_P_FREE35'],
          "P_FREE36" => $bodyArr['P_P_FREE36'],
          "P_FREE37" => $bodyArr['P_P_FREE37'],
          "P_FREE38" => $bodyArr['P_P_FREE38'],
          "P_FREE39" => $bodyArr['P_P_FREE39'],
          "P_FREE40" => $bodyArr['P_P_FREE40'],
          "P_FREE41" => $bodyArr['P_P_FREE41'],
          "P_FREE42" => $bodyArr['P_P_FREE42'],
          "P_FREE43" => $bodyArr['P_P_FREE43'],
          "P_FREE44" => $bodyArr['P_P_FREE44'],
          "P_FREE45" => $bodyArr['P_P_FREE45'],
          "P_FREE46" => $bodyArr['P_P_FREE46'],
          "P_FREE47" => $bodyArr['P_P_FREE47'],
          "P_FREE48" => $bodyArr['P_P_FREE48'],
          "P_FREE49" => $bodyArr['P_P_FREE49'],
          "P_FREE50" => $bodyArr['P_P_FREE50'],
          "O_P_FREE01" => $bodyArr['O_P_FREE1'],
          "O_P_FREE02" => $bodyArr['O_P_FREE2'],
          "O_P_FREE03" => $bodyArr['O_P_FREE3'],
          "O_P_FREE04" => $bodyArr['O_P_FREE4'],
          "O_P_FREE05" => $bodyArr['O_P_FREE5'],
          "O_P_FREE06" => $bodyArr['O_P_FREE6'],
          "O_P_FREE07" => $bodyArr['O_P_FREE7'],
          "O_P_FREE08" => $bodyArr['O_P_FREE8'],
          "O_P_FREE09" => $bodyArr['O_P_FREE9'],
          "O_P_FREE10" => $bodyArr['O_P_FREE10'],
          "O_P_FREE11" => $bodyArr['O_P_FREE11'],
          "O_P_FREE12" => $bodyArr['O_P_FREE12'],
          "O_P_FREE13" => $bodyArr['O_P_FREE13'],
          "O_P_FREE14" => $bodyArr['O_P_FREE14'],
          "O_P_FREE15" => $bodyArr['O_P_FREE15'],
          "O_P_FREE16" => $bodyArr['O_P_FREE16'],
          "O_P_FREE17" => $bodyArr['O_P_FREE17'],
          "O_P_FREE18" => $bodyArr['O_P_FREE18'],
          "O_P_FREE19" => $bodyArr['O_P_FREE19'],
          "O_P_FREE20" => $bodyArr['O_P_FREE20'],
          "O_P_FREE21" => $bodyArr['O_P_FREE21'],
          "O_P_FREE22" => $bodyArr['O_P_FREE22'],
          "O_P_FREE23" => $bodyArr['O_P_FREE23'],
          "O_P_FREE24" => $bodyArr['O_P_FREE24'],
          "O_P_FREE25" => $bodyArr['O_P_FREE25'],
          "O_P_FREE26" => $bodyArr['O_P_FREE26'],
          "O_P_FREE27" => $bodyArr['O_P_FREE27'],
          "O_P_FREE28" => $bodyArr['O_P_FREE28'],
          "O_P_FREE29" => $bodyArr['O_P_FREE29'],
          "O_P_FREE30" => $bodyArr['O_P_FREE30'],
          "O_P_FREE31" => $bodyArr['O_P_FREE31'],
          "O_P_FREE32" => $bodyArr['O_P_FREE32'],
          "O_P_FREE33" => $bodyArr['O_P_FREE33'],
          "O_P_FREE34" => $bodyArr['O_P_FREE34'],
          "O_P_FREE35" => $bodyArr['O_P_FREE35'],
          "O_P_FREE36" => $bodyArr['O_P_FREE36'],
          "O_P_FREE37" => $bodyArr['O_P_FREE37'],
          "O_P_FREE38" => $bodyArr['O_P_FREE38'],
          "O_P_FREE39" => $bodyArr['O_P_FREE39'],
          "O_P_FREE40" => $bodyArr['O_P_FREE40'],
          "O_P_FREE41" => $bodyArr['O_P_FREE41'],
          "O_P_FREE42" => $bodyArr['O_P_FREE42'],
          "O_P_FREE43" => $bodyArr['O_P_FREE43'],
          "O_P_FREE44" => $bodyArr['O_P_FREE44'],
          "O_P_FREE45" => $bodyArr['O_P_FREE45'],
          "O_P_FREE46" => $bodyArr['O_P_FREE46'],
          "O_P_FREE47" => $bodyArr['O_P_FREE47'],
          "O_P_FREE48" => $bodyArr['O_P_FREE48'],
          "O_P_FREE49" => $bodyArr['O_P_FREE49'],
          "O_P_FREE50" => $bodyArr['O_P_FREE50'],
          "C_KEYWORD" => $bodyArr['P_C_KEYWORD'],
          "C_G_FREE01" => "",
          "C_G_FREE02" => "",
          "C_G_FREE03" => "",
          "C_G_FREE04" => "",
          "C_G_FREE05" => "",
          "C_G_FREE06" => "",
          "C_G_FREE07" => "",
          "C_G_FREE08" => "",
          "C_G_FREE09" => "",
          "C_G_FREE10" => "",
          "C_G_FREE11" => "",
          "C_G_FREE12" => "",
          "C_G_FREE13" => "",
          "C_G_FREE14" => "",
          "C_G_FREE15" => "",
          "C_G_FREE16" => "",
          "C_G_FREE17" => "",
          "C_G_FREE18" => "",
          "C_G_FREE19" => "",
          "C_G_FREE20" => "",
          "C_G_FREE21" => "",
          "C_G_FREE22" => "",
          "C_G_FREE23" => "",
          "C_G_FREE24" => "",
          "C_G_FREE25" => "",
          "C_G_FREE26" => "",
          "C_G_FREE27" => "",
          "C_G_FREE28" => "",
          "C_G_FREE29" => "",
          "C_G_FREE30" => "",
          "C_G_FREE31" => "",
          "C_G_FREE32" => "",
          "C_G_FREE33" => "",
          "C_G_FREE34" => "",
          "C_G_FREE35" => "",
          "C_G_FREE36" => "",
          "C_G_FREE37" => "",
          "C_G_FREE38" => "",
          "C_G_FREE39" => "",
          "C_G_FREE40" => "",
          "C_G_FREE41" => "",
          "C_G_FREE42" => "",
          "C_G_FREE43" => "",
          "C_G_FREE44" => "",
          "C_G_FREE45" => "",
          "C_G_FREE46" => "",
          "C_G_FREE47" => "",
          "C_G_FREE48" => "",
          "C_G_FREE49" => "",
          "C_G_FREE50" => "",
          "UN_SUBSCRIBE_FLG" => 1,
          "SKYPE_NAME" => "",
          "OUTSOURCE_TYPE" => 1,
          "AFF_AFFILIATION_NAME" => $bodyArr['S_AFFILIATION_NAME'],
          "AFF_OFFICIAL_POSITION" => $bodyArr['S_OFFICIAL_POSITION']
        );  
        $listZipcode = get_api_common($postid, $url, $arrBody, "POST");
        return $listZipcode;
    }

    public function updateMember($bodyArr = array(), $postid){
        $url = URL_API.'Member/UpdateMember';
        $arrBody = array(
          "P_ID" => ($_SESSION['arrSession']->P_ID)?$_SESSION['arrSession']->P_ID:"",
          "TG_ID" => ($_SESSION['arrSession']->TG_ID)?$_SESSION['arrSession']->TG_ID:get_option('nakama-member-group-id'),
          "LTG_ID" => ($_SESSION['arrSession']->LTG_ID)?$_SESSION['arrSession']->LTG_ID:"",
          "LG_TYPE" => $_SESSION['arrSession']->LG_TYPE,
          "LG_ID" => $_SESSION['arrSession']->LG_ID,
          "M_ID" => $_SESSION['arrSession']->M_ID,
          "STATUS" => ($_SESSION['arrSession']->STATUS)?$_SESSION['arrSession']->STATUS:"",
          "CONTRACT_TYPE" => $bodyArr['M_CONTRACT_TYPE'],
          "GROUP_ADMIN" => 9,
          "M_USER_ID" => $bodyArr['M_USER_ID'],
          "RECOMMEND_P_ID" => $bodyArr['M_RECOMMEND_P_ID'],
          "RECOMMEND_P_ID2" => $bodyArr['M_RECOMMEND_P_ID2'],
          "RECOMMEND_P_ID3" => $bodyArr['M_RECOMMEND_P_ID3'],
          "RECOMMEND_P_ID4" => $bodyArr['M_RECOMMEND_P_ID4'],
          "RECOMMEND_P_ID5" => $bodyArr['M_RECOMMEND_P_ID5'],
          "X_COMMENT" => $bodyArr['M_X_COMMENT'],
          "RECEIVE_INFO_MAIL" => $bodyArr['M_RECEIVE_INFO_MAIL'],
          "RECEIVE_INFO_PMAIL" => 1,
          "RECEIVE_INFO_FAX" => $bodyArr['M_RECEIVE_INFO_FAX'],
          "FAX_TIMEZONE" => $bodyArr['M_FAX_TIMEZONE'],
          "FAX_TIME_FROM" => $bodyArr['FAX_TIME_FROM'],
          "FAX_TIME_TO" => $bodyArr['FAX_TIME_TO'],
          "MLMAGA_FLG" => $bodyArr['M_MLMAGA_FLG'],
          "DISP_LIST" => $bodyArr['M_DISP_LIST'],
          "DISP_DETAIL" => $bodyArr['M_DISP_DETAIL'],
          "DISP_MARKETING" => $bodyArr['M_DISP_MARKETING'],
          "AFFILIATION_NAME" => "",
          "OFFICIAL_POSITION" => "",
          // "CONTACT_ID" => $bodyArr['M_CONTACT_ID'],
          "CONTACT_ID" => ($bodyArr['M_CONTACT_ID'] == '@N')?'':$bodyArr['M_CONTACT_ID'],
          "CONTACT_LNG_MODE" => $bodyArr['M_CONTACT_LNG_MODE'],
          "CONTACT_G_NAME" => $bodyArr['M_CONTACT_G_NAME'],
          "CONTACT_G_NAME_KN" => $bodyArr['M_CONTACT_G_NAME_KN'],
          "CONTACT_G_NAME_EN" => $bodyArr['M_CONTACT_G_NAME_EN'],
          "CONTACT_C_FNAME" => explode(" ",$bodyArr['M_CONTACT_C_NAME'])[0],
          "CONTACT_C_LNAME" => (!empty(explode(" ",$bodyArr['M_CONTACT_C_NAME'])[1]))?explode(" ",$bodyArr['M_CONTACT_C_NAME'])[1]:"",
          "CONTACT_C_FNAME_KN" => explode(" ",$bodyArr['M_CONTACT_C_NAME_KN'])[0],
          "CONTACT_C_LNAME_KN" => (!empty(explode(" ",$bodyArr['M_CONTACT_C_NAME_KN'])[1]))?explode(" ",$bodyArr['M_CONTACT_C_NAME_KN'])[1]:"",
          "CONTACT_C_NAME_EN" => "",
          "CONTACT_EMAIL" => $bodyArr['M_CONTACT_EMAIL'],
          "CONTACT_CC_EMAIL" => $bodyArr['M_CONTACT_CC_EMAIL'],
          "CONTACT_TEL" => $bodyArr['M_CONTACT_TEL'],
          "CONTACT_FAX" => $bodyArr['M_CONTACT_FAX'],
          "CONTACT_POST" => $bodyArr['M_CONTACT_POST'],
          "CONTACT_STA" => $bodyArr['M_CONTACT_STA'],
          "CONTACT_ADR" => $bodyArr['M_CONTACT_ADR'],
          "CONTACT_ADR2" => $bodyArr['M_CONTACT_ADR2'],
          "CONTACT_ADR3" => $bodyArr['M_CONTACT_ADR3'],
          "CONTACT_ADR_EN" => $bodyArr['M_CONTACT_ADR_EN'],
          "CONTACT_AFFILIATION" => $bodyArr['M_CONTACT_AFFILIATION'],
          "CONTACT_POSITION" => $bodyArr['M_CONTACT_POSITION'],
          "ATENA_SUU" => $bodyArr['ATENA_SUU'],
          // "BILLING_ID" => $bodyArr['M_BILLING_ID'],
          "BILLING_ID" => ($bodyArr['M_BILLING_ID'] == '@N')?'':$bodyArr['M_BILLING_ID'],
          "BILLING_LNG_MODE" => $bodyArr['M_BILLING_LNG_MODE'],
          "BILLING_G_NAME" => $bodyArr['M_BILLING_G_NAME'],
          "BILLING_G_KANA" => $bodyArr['M_BILLING_G_KANA'],
          "BILLING_G_NAME_EN" => $bodyArr['M_BILLING_G_NAME_EN'],
          "BILLING_C_FNAME" => explode(" ",$bodyArr['M_BILLING_C_NAME'])[0],
          "BILLING_C_LNAME" => (!empty(explode(" ",$bodyArr['M_BILLING_C_NAME'])[1]))?explode(" ",$bodyArr['M_BILLING_C_NAME'])[1]:"",
          // "BILLING_C_FNAME_KN" => explode(" ",$bodyArr['M_CONTACT_C_NAME_KN'])[0],
          // "BILLING_C_LNAME_KN" => (!empty(explode(" ",$bodyArr['M_CONTACT_C_NAME_KN'])[1]))?explode(" ",$bodyArr['M_CONTACT_C_NAME_KN'])[1]:"",
          "BILLING_C_FNAME_KN" => explode(" ",$bodyArr['M_BILLING_C_NAME_KN'])[0],
          "BILLING_C_LNAME_KN" => (!empty(explode(" ",$bodyArr['M_BILLING_C_NAME_KN'])[1]))?explode(" ",$bodyArr['M_BILLING_C_NAME_KN'])[1]:"",
          "BILLING_C_NAME_EN" => "",
          "BILLING_EMAIL" => $bodyArr['M_BILLING_EMAIL'],
          "BILLING_CC_EMAIL" => $bodyArr['M_BILLING_CC_EMAIL'],
          "BILLING_TEL" => $bodyArr['M_BILLING_TEL'],
          "BILLING_FAX" => $bodyArr['M_BILLING_FAX'],
          "BILLING_POST" => $bodyArr['M_BILLING_POST'],
          "BILLING_STA" => $bodyArr['M_BILLING_STA'],
          "BILLING_ADR" => $bodyArr['M_BILLING_ADR'],
          "BILLING_ADR2" => $bodyArr['M_BILLING_ADR2'],
          "BILLING_ADR3" => $bodyArr['M_BILLING_ADR3'],
          "BILLING_ADR_EN" => $bodyArr['M_BILLING_ADR_EN'],
          "BILLING_AFFILIATION" => $bodyArr['M_BILLING_AFFILIATION'],
          "BILLING_POSITION" => $bodyArr['M_BILLING_POSITION'],
          "BANK_CD" => $bodyArr['M_BANK_CD'],
          "BRANCH_CD" => $bodyArr['M_BRANCH_CD'],
          "ACCOUNT_TYPE" => $bodyArr['M_ACCAUNT_TYPE'],
          "ACCOUNT_NO" => $bodyArr['M_ACCOUNT_NO'],
          "ACCOUNT_NM" => $bodyArr['M_ACCOUNT_NM'],
          "CUST_NO" => $bodyArr['M_CUST_NO'],
          "SAVINGS_CD" => $bodyArr['M_SAVINGS_CD'],
          "SAVINGS_NO" => $bodyArr['M_SAVINGS_NO'],
          "SETTLE_MONTH" => 70,
          "SHIPPING_ID" => "",
          "SHIPPING_LNG_MODE" => 1,
          "SHIPPING_G_NAME" => "",
          "SHIPPING_G_NAME_KN" => "",
          "SHIPPING_G_NAME_EN" => "",
          "SHIPPING_C_FNAME" => "",
          "SHIPPING_C_LNAME" => "",
          "SHIPPING_C_FNAME_KN" => "",
          "SHIPPING_C_LNAME_KN" => "",
          "SHIPPING_C_NAME_EN" => "",
          "SHIPPING_EMAIL" => "",
          "SHIPPING_CC_EMAIL" => "",
          "SHIPPING_TEL" => "",
          "SHIPPING_FAX" => "",
          "SHIPPING_POST" => "",
          "SHIPPING_STA" => "",
          "SHIPPING_ADR" => "",
          "SHIPPING_ADR2" => "",
          "SHIPPING_ADR3" => "",
          "SHIPPING_ADR_EN" => "",
          "SHIPPING_AFFILIATION" => "",
          "SHIPPING_POSITION" => "",
          "ADMISSION_DATE" => $bodyArr['M_ADMISSION_DATE'],
          "WITHDRAWAL_DATE" => $bodyArr['M_WITHDRAWAL_DATE'],
          "CHANGE_DATE" => $bodyArr['M_CHANGE_DATE'],
          "ADMISSION_REASON" => $bodyArr['M_ADMISSION_REASON'],
          "WITHDRAWAL_REASON" => $bodyArr['M_WITHDRAWAL_REASON'],
          "CHANGE_REASON" => $bodyArr['M_CHANGE_REASON'],
          "MOVEOUT_DATE" => $bodyArr['M_MOVEOUT_DATE'],
          "MOVEOUT_NOTE" => $bodyArr['M_MOVEOUT_NOTE'],
          "MOVEIN_DATE" => $bodyArr['M_MOVEIN_DATE'],
          "MOVEIN_NOTE" => $bodyArr['M_MOVEIN_NOTE'],
          "CLAIM_CLS" => ($bodyArr['M_CLAIM_CLS'] == "selected")?null:$bodyArr['M_CLAIM_CLS'],
          "FEE_RANK" => $bodyArr['M_FEE_RANK'],
          "CLAIM_CYCLE" => $bodyArr['M_CLAIM_CYCLE'],
          "FEE_MEMO" => $bodyArr['M_FEE_MEMO'],
          "ENTRUST_CD" => $bodyArr['M_ENTRUST_CD'],
          "FEE_UNIT" => 1.0,
          "MONTH_FEE" => 1.0,
          "TAX_ACCOUNTANT01" => $bodyArr['M_TAX_ACCOUNTANT1'],
          "TAX_ACCOUNTANT02" => $bodyArr['M_TAX_ACCOUNTANT2'],
          "TAX_ACCOUNTANT03" => $bodyArr['M_TAX_ACCOUNTANT3'],
          "TAX_ACCOUNTANT04" => $bodyArr['M_TAX_ACCOUNTANT4'],
          "TAX_ACCOUNTANT05" => $bodyArr['M_TAX_ACCOUNTANT5'],
          "TAX_ACCOUNTANT06" => $bodyArr['M_TAX_ACCOUNTANT6'],
          "TAX_ACCOUNTANT07" => $bodyArr['M_TAX_ACCOUNTANT7'],
          "TAX_ACCOUNTANT08" => $bodyArr['M_TAX_ACCOUNTANT8'],
          "TAX_ACCOUNTANT09" => $bodyArr['M_TAX_ACCOUNTANT9'],
          "TAX_ACCOUNTANT10" => $bodyArr['M_TAX_ACCOUNTANT10'],
          "M_FREE01" => implode("|",(array)$bodyArr['M_FREE1']),
          "M_FREE02" => implode("|",(array)$bodyArr['M_FREE2']),
          "M_FREE03" => implode("|",(array)$bodyArr['M_FREE3']),
          "M_FREE04" => implode("|",(array)$bodyArr['M_FREE4']),
          "M_FREE05" => implode("|",(array)$bodyArr['M_FREE5']),
          "M_FREE06" => implode("|",(array)$bodyArr['M_FREE6']),
          "M_FREE07" => implode("|",(array)$bodyArr['M_FREE7']),
          "M_FREE08" => implode("|",(array)$bodyArr['M_FREE8']),
          "M_FREE09" => implode("|",(array)$bodyArr['M_FREE9']),
          "M_FREE10" => implode("|",(array)$bodyArr['M_FREE10']),
          "M_FREE11" => implode("|",(array)$bodyArr['M_FREE11']),
          "M_FREE12" => implode("|",(array)$bodyArr['M_FREE12']),
          "M_FREE13" => implode("|",(array)$bodyArr['M_FREE13']),
          "M_FREE14" => implode("|",(array)$bodyArr['M_FREE14']),
          "M_FREE15" => implode("|",(array)$bodyArr['M_FREE15']),
          "M_FREE16" => implode("|",(array)$bodyArr['M_FREE16']),
          "M_FREE17" => implode("|",(array)$bodyArr['M_FREE17']),
          "M_FREE18" => implode("|",(array)$bodyArr['M_FREE18']),
          "M_FREE19" => implode("|",(array)$bodyArr['M_FREE19']),
          "M_FREE20" => implode("|",(array)$bodyArr['M_FREE20']),
          "M_FREE21" => implode("|",(array)$bodyArr['M_FREE21']),
          "M_FREE22" => implode("|",(array)$bodyArr['M_FREE22']),
          "M_FREE23" => implode("|",(array)$bodyArr['M_FREE23']),
          "M_FREE24" => implode("|",(array)$bodyArr['M_FREE24']),
          "M_FREE25" => implode("|",(array)$bodyArr['M_FREE25']),
          "M_FREE26" => implode("|",(array)$bodyArr['M_FREE26']),
          "M_FREE27" => implode("|",(array)$bodyArr['M_FREE27']),
          "M_FREE28" => implode("|",(array)$bodyArr['M_FREE28']),
          "M_FREE29" => implode("|",(array)$bodyArr['M_FREE29']),
          "M_FREE30" => implode("|",(array)$bodyArr['M_FREE30']),
          "M_FREE31" => implode("|",(array)$bodyArr['M_FREE31']),
          "M_FREE32" => implode("|",(array)$bodyArr['M_FREE32']),
          "M_FREE33" => implode("|",(array)$bodyArr['M_FREE33']),
          "M_FREE34" => implode("|",(array)$bodyArr['M_FREE34']),
          "M_FREE35" => implode("|",(array)$bodyArr['M_FREE35']),
          "M_FREE36" => implode("|",(array)$bodyArr['M_FREE36']),
          "M_FREE37" => implode("|",(array)$bodyArr['M_FREE37']),
          "M_FREE38" => implode("|",(array)$bodyArr['M_FREE38']),
          "M_FREE39" => implode("|",(array)$bodyArr['M_FREE39']),
          "M_FREE40" => implode("|",(array)$bodyArr['M_FREE40']),
          "M_FREE41" => implode("|",(array)$bodyArr['M_FREE41']),
          "M_FREE42" => implode("|",(array)$bodyArr['M_FREE42']),
          "M_FREE43" => implode("|",(array)$bodyArr['M_FREE43']),
          "M_FREE44" => implode("|",(array)$bodyArr['M_FREE44']),
          "M_FREE45" => implode("|",(array)$bodyArr['M_FREE45']),
          "M_FREE46" => implode("|",(array)$bodyArr['M_FREE46']),
          "M_FREE47" => implode("|",(array)$bodyArr['M_FREE47']),
          "M_FREE48" => implode("|",(array)$bodyArr['M_FREE48']),
          "M_FREE49" => implode("|",(array)$bodyArr['M_FREE49']),
          "M_FREE50" => implode("|",(array)$bodyArr['M_FREE50']),
          "O_M_FREE01" => 1,
          "O_M_FREE02" => 1,
          "O_M_FREE03" => 1,
          "O_M_FREE04" => 1,
          "O_M_FREE05" => 1,
          "O_M_FREE06" => 1,
          "O_M_FREE07" => 1,
          "O_M_FREE08" => 1,
          "O_M_FREE09" => 1,
          "O_M_FREE10" => 1,
          "O_M_FREE11" => 1,
          "O_M_FREE12" => 1,
          "O_M_FREE13" => 1,
          "O_M_FREE14" => 1,
          "O_M_FREE15" => 1,
          "O_M_FREE16" => 1,
          "O_M_FREE17" => 1,
          "O_M_FREE18" => 1,
          "O_M_FREE19" => 1,
          "O_M_FREE20" => 1,
          "O_M_FREE21" => 1,
          "O_M_FREE22" => 1,
          "O_M_FREE23" => 1,
          "O_M_FREE24" => 1,
          "O_M_FREE25" => 1,
          "O_M_FREE26" => 1,
          "O_M_FREE27" => 1,
          "O_M_FREE28" => 1,
          "O_M_FREE29" => 1,
          "O_M_FREE30" => 1,
          "O_M_FREE31" => 1,
          "O_M_FREE32" => 1,
          "O_M_FREE33" => 1,
          "O_M_FREE34" => 1,
          "O_M_FREE35" => 1,
          "O_M_FREE36" => 1,
          "O_M_FREE37" => 1,
          "O_M_FREE38" => 1,
          "O_M_FREE39" => 1,
          "O_M_FREE40" => 1,
          "O_M_FREE41" => 1,
          "O_M_FREE42" => 1,
          "O_M_FREE43" => 1,
          "O_M_FREE44" => 1,
          "O_M_FREE45" => 1,
          "O_M_FREE46" => 1,
          "O_M_FREE47" => 1,
          "O_M_FREE48" => 1,
          "O_M_FREE49" => 1,
          "O_M_FREE50" => 1,
          "G_M_KEYWORD" => $bodyArr['M_CONNECTION'],
          "G_M_FREE01" => "",
          "G_M_FREE02" => "",
          "G_M_FREE03" => "",
          "G_M_FREE04" => "",
          "G_M_FREE05" => "",
          "G_M_FREE06" => "",
          "G_M_FREE07" => "",
          "G_M_FREE08" => "",
          "G_M_FREE09" => "",
          "G_M_FREE10" => "",
          "G_M_FREE11" => "",
          "G_M_FREE12" => "",
          "G_M_FREE13" => "",
          "G_M_FREE14" => "",
          "G_M_FREE15" => "",
          "G_M_FREE16" => "",
          "G_M_FREE17" => "",
          "G_M_FREE18" => "",
          "G_M_FREE19" => "",
          "G_M_FREE20" => "",
          "G_M_FREE21" => "",
          "G_M_FREE22" => "",
          "G_M_FREE23" => "",
          "G_M_FREE24" => "",
          "G_M_FREE25" => "",
          "G_M_FREE26" => "",
          "G_M_FREE27" => "",
          "G_M_FREE28" => "",
          "G_M_FREE29" => "",
          "G_M_FREE30" => "",
          "G_M_FREE31" => "",
          "G_M_FREE32" => "",
          "G_M_FREE33" => "",
          "G_M_FREE34" => "",
          "G_M_FREE35" => "",
          "G_M_FREE36" => "",
          "G_M_FREE37" => "",
          "G_M_FREE38" => "",
          "G_M_FREE39" => "",
          "G_M_FREE40" => "",
          "G_M_FREE41" => "",
          "G_M_FREE42" => "",
          "G_M_FREE43" => "",
          "G_M_FREE44" => "",
          "G_M_FREE45" => "",
          "G_M_FREE46" => "",
          "G_M_FREE47" => "",
          "G_M_FREE48" => "",
          "G_M_FREE49" => "",
          "G_M_FREE50" => "",
          "S_CONTACT_TEL" => "",
          "S_CONTACT_FAX" => "",
          "S_BILLING_TEL" => "",
          "S_BILLING_FAX" => "",
          "S_SHIPPING_TEL" => "",
          "S_SHIPPING_FAX" => "",
          "AUTHORIZATION_FLG" => 1,
          "POINT_RESET_DATE" => "",
          "ACTIVE_ORG_TOPGID" => "",
          "CLAIM_STS" => 1,
          "UNPAID_STS" => 1,
          "EVENT_STS" => 1,
          "PRIZE_STS" => 1,
          "POST_STS" => 1,
          "M_CLASS" => "",
          "BOUNCE_MAIL_FLG" => 1,
          // "OWNER" => ($flg_shortcode)?get_post_meta($postid,"member_meta_group_id",true):get_option('nakama-member-group-id'),
          "G_USER_ID" => $bodyArr['G_USER_ID'],
          "G_NAME" => $bodyArr['G_NAME'],
          "G_NAME_KN" => $bodyArr['G_NAME_KN'],
          "G_NAME_EN" => $bodyArr['G_NAME_EN'],
          "G_NAME_AN" => $bodyArr['G_NAME_AN'],
          "G_URL" => $bodyArr['G_URL'],
          "G_P_URL" => $bodyArr['G_P_URL'],
          "G_EMAIL" => $bodyArr['G_EMAIL'],
          "G_CC_EMAIL" => $bodyArr['G_CC_EMAIL'],
          "G_TEL" => $bodyArr['G_TEL'],
          "G_FAX" => $bodyArr['G_FAX'],
          "G_POST" => $bodyArr['G_POST'],
          "G_STA" => $bodyArr['G_STA'],
          "G_ADR" => $bodyArr['G_ADR'],
          "G_ADR2" => $bodyArr['G_ADR2'],
          "G_ADR3" => $bodyArr['G_ADR3'],
          "G_ADR_EN" => $bodyArr['G_ADR_EN'],
          "LNG_MODE" => $bodyArr['G_LNG_MODE'],
          "INDUSTRY_NM" => $bodyArr['G_INDUSTRY_NM'],
          "INDUSTRY_CD" => $bodyArr['G_INDUSTRY_CD'],
          "ORG_BANK_CD" => $bodyArr['G_BANK_CD'],
          "ORG_BRANCH_CD" => $bodyArr['G_BRANCH_CD'],
          "ORG_ACCOUNT_TYPE" => $bodyArr['G_ACCAUNT_TYPE'],
          "ORG_ACCOUNT_NO" => $bodyArr['G_ACCOUNT_NO'],
          "ORG_ACCOUNT_NM" => $bodyArr['G_ACCAUNT_NM'],
          "ORG_CUST_NO" => $bodyArr['G_CUST_NO'],
          "ORG_SAVINGS_CD" => $bodyArr['G_SAVINGS_CD'],
          "ORG_SAVINGS_NO" => $bodyArr['G_SAVINGS_NO'],
          "ORG_SETTLE_MONTH" => 1,
          "FOUND_DATE" => $bodyArr['G_FOUND_DATE'],
          "CAPITAL" => $bodyArr['G_CAPITAL'],
          "REPRESENTATIVE_NM" => $bodyArr['G_REPRESENTATIVE_NM'],
          "REPRESENTATIVE_KN" => $bodyArr['G_REPRESENTATIVE_KN'],
          "REPRESENTATIVE_OP" => $bodyArr['G_REPRESENTATIVE_OP'],
          "REPRESENTATIVE_DATE" => "",
          "REPRESENTATIVE_ID" => "",
          "G_LOGO" => $bodyArr['G_LOGO'],
          "G_IMG" => $bodyArr['G_IMG'],
          "G_APPEAL" => $bodyArr['G_APPEAL'],
          "O_G_NAME" => $bodyArr['G_O_NAME'],
          "O_G_NAME_KN" => $bodyArr['G_O_KANA'],
          "O_G_NAME_AN" => $bodyArr['G_O_SNAME'],
          "O_G_URL" => $bodyArr['G_O_URL'],
          "O_G_P_URL" => $bodyArr['G_O_P_URL'],
          "O_G_EMAIL" => $bodyArr['G_O_EMAIL'],
          "O_G_CC_EMAIL" => $bodyArr['G_O_CC_EMAIL'],
          "O_G_TEL" => $bodyArr['G_O_TEL'],
          "O_G_FAX" => $bodyArr['G_O_FAX'],
          "O_G_POST" => $bodyArr['G_O_POST'],
          "O_G_STA" => $bodyArr['G_O_STA'],
          "O_G_ADR" => $bodyArr['G_O_ADDRESS'],
          "O_INDUSTRY_NM" => $bodyArr['G_O_CATEGORY'],
          "O_INDUSTRY_CD" => $bodyArr['G_O_CATEGORY_CODE'],
          "O_FOUND_DATE" => $bodyArr['G_O_FOUND_DATE'],
          "O_SETTLE_MONTH" => $bodyArr['G_O_SETTLE_MONTH'],
          "O_CAPITAL" => $bodyArr['G_O_CAPITAL'],
          "O_REPRESENTATIVE_NM" => $bodyArr['G_O_REPRESENTATIVE'],
          "O_REPRESENTATIVE_KN" => $bodyArr['G_O_REPRESENTATIVE_KANA'],
          "O_REPRESENTATIVE_OP" => 1,
          "O_REPRESENTATIVE_DATE" => 1,
          "O_G_LOGO" => $bodyArr['G_O_LOGO'],
          "O_G_IMG" => $bodyArr['G_O_IMG'],
          "O_G_APPEAL" => $bodyArr['G_O_APPEAL'],
          "S_G_TEL" => "",
          "S_G_FAX" => "",
          "G_FREE01" => implode("|",(array)$bodyArr['G_FREE1']),
          "G_FREE02" => implode("|",(array)$bodyArr['G_FREE2']),
          "G_FREE03" => implode("|",(array)$bodyArr['G_FREE3']),
          "G_FREE04" => implode("|",(array)$bodyArr['G_FREE4']),
          "G_FREE05" => implode("|",(array)$bodyArr['G_FREE5']),
          "G_FREE06" => implode("|",(array)$bodyArr['G_FREE6']),
          "G_FREE07" => implode("|",(array)$bodyArr['G_FREE7']),
          "G_FREE08" => implode("|",(array)$bodyArr['G_FREE8']),
          "G_FREE09" => implode("|",(array)$bodyArr['G_FREE9']),
          "G_FREE10" => implode("|",(array)$bodyArr['G_FREE10']),
          "G_FREE11" => implode("|",(array)$bodyArr['G_FREE11']),
          "G_FREE12" => implode("|",(array)$bodyArr['G_FREE12']),
          "G_FREE13" => implode("|",(array)$bodyArr['G_FREE13']),
          "G_FREE14" => implode("|",(array)$bodyArr['G_FREE14']),
          "G_FREE15" => implode("|",(array)$bodyArr['G_FREE15']),
          "G_FREE16" => implode("|",(array)$bodyArr['G_FREE16']),
          "G_FREE17" => implode("|",(array)$bodyArr['G_FREE17']),
          "G_FREE18" => implode("|",(array)$bodyArr['G_FREE18']),
          "G_FREE19" => implode("|",(array)$bodyArr['G_FREE19']),
          "G_FREE20" => implode("|",(array)$bodyArr['G_FREE20']),
          "G_FREE21" => implode("|",(array)$bodyArr['G_FREE21']),
          "G_FREE22" => implode("|",(array)$bodyArr['G_FREE22']),
          "G_FREE23" => implode("|",(array)$bodyArr['G_FREE23']),
          "G_FREE24" => implode("|",(array)$bodyArr['G_FREE24']),
          "G_FREE25" => implode("|",(array)$bodyArr['G_FREE25']),
          "G_FREE26" => implode("|",(array)$bodyArr['G_FREE26']),
          "G_FREE27" => implode("|",(array)$bodyArr['G_FREE27']),
          "G_FREE28" => implode("|",(array)$bodyArr['G_FREE28']),
          "G_FREE29" => implode("|",(array)$bodyArr['G_FREE29']),
          "G_FREE30" => implode("|",(array)$bodyArr['G_FREE30']),
          "G_FREE31" => implode("|",(array)$bodyArr['G_FREE31']),
          "G_FREE32" => implode("|",(array)$bodyArr['G_FREE32']),
          "G_FREE33" => implode("|",(array)$bodyArr['G_FREE33']),
          "G_FREE34" => implode("|",(array)$bodyArr['G_FREE34']),
          "G_FREE35" => implode("|",(array)$bodyArr['G_FREE35']),
          "G_FREE36" => implode("|",(array)$bodyArr['G_FREE36']),
          "G_FREE37" => implode("|",(array)$bodyArr['G_FREE37']),
          "G_FREE38" => implode("|",(array)$bodyArr['G_FREE38']),
          "G_FREE39" => implode("|",(array)$bodyArr['G_FREE39']),
          "G_FREE40" => implode("|",(array)$bodyArr['G_FREE40']),
          "G_FREE41" => implode("|",(array)$bodyArr['G_FREE41']),
          "G_FREE42" => implode("|",(array)$bodyArr['G_FREE42']),
          "G_FREE43" => implode("|",(array)$bodyArr['G_FREE43']),
          "G_FREE44" => implode("|",(array)$bodyArr['G_FREE44']),
          "G_FREE45" => implode("|",(array)$bodyArr['G_FREE45']),
          "G_FREE46" => implode("|",(array)$bodyArr['G_FREE46']),
          "G_FREE47" => implode("|",(array)$bodyArr['G_FREE47']),
          "G_FREE48" => implode("|",(array)$bodyArr['G_FREE48']),
          "G_FREE49" => implode("|",(array)$bodyArr['G_FREE49']),
          "G_FREE50" => implode("|",(array)$bodyArr['G_FREE50']),
          "O_G_FREE01" => $bodyArr['G_O_BIKOU1'],
          "O_G_FREE02" => $bodyArr['G_O_BIKOU2'],
          "O_G_FREE03" => $bodyArr['G_O_BIKOU3'],
          "O_G_FREE04" => $bodyArr['G_O_BIKOU4'],
          "O_G_FREE05" => $bodyArr['G_O_BIKOU5'],
          "O_G_FREE06" => $bodyArr['G_O_BIKOU6'],
          "O_G_FREE07" => $bodyArr['G_O_BIKOU7'],
          "O_G_FREE08" => $bodyArr['G_O_BIKOU8'],
          "O_G_FREE09" => $bodyArr['G_O_BIKOU9'],
          "O_G_FREE10" => $bodyArr['G_O_BIKOU10'],
          "O_G_FREE11" => $bodyArr['G_O_BIKOU11'],
          "O_G_FREE12" => $bodyArr['G_O_BIKOU12'],
          "O_G_FREE13" => $bodyArr['G_O_BIKOU13'],
          "O_G_FREE14" => $bodyArr['G_O_BIKOU14'],
          "O_G_FREE15" => $bodyArr['G_O_BIKOU15'],
          "O_G_FREE16" => $bodyArr['G_O_BIKOU16'],
          "O_G_FREE17" => $bodyArr['G_O_BIKOU17'],
          "O_G_FREE18" => $bodyArr['G_O_BIKOU18'],
          "O_G_FREE19" => $bodyArr['G_O_BIKOU19'],
          "O_G_FREE20" => $bodyArr['G_O_BIKOU20'],
          "O_G_FREE21" => $bodyArr['G_O_BIKOU21'],
          "O_G_FREE22" => $bodyArr['G_O_BIKOU22'],
          "O_G_FREE23" => $bodyArr['G_O_BIKOU23'],
          "O_G_FREE24" => $bodyArr['G_O_BIKOU24'],
          "O_G_FREE25" => $bodyArr['G_O_BIKOU25'],
          "O_G_FREE26" => $bodyArr['G_O_BIKOU26'],
          "O_G_FREE27" => $bodyArr['G_O_BIKOU27'],
          "O_G_FREE28" => $bodyArr['G_O_BIKOU28'],
          "O_G_FREE29" => $bodyArr['G_O_BIKOU29'],
          "O_G_FREE30" => $bodyArr['G_O_BIKOU30'],
          "O_G_FREE31" => $bodyArr['G_O_BIKOU31'],
          "O_G_FREE32" => $bodyArr['G_O_BIKOU32'],
          "O_G_FREE33" => $bodyArr['G_O_BIKOU33'],
          "O_G_FREE34" => $bodyArr['G_O_BIKOU34'],
          "O_G_FREE35" => $bodyArr['G_O_BIKOU35'],
          "O_G_FREE36" => $bodyArr['G_O_BIKOU36'],
          "O_G_FREE37" => $bodyArr['G_O_BIKOU37'],
          "O_G_FREE38" => $bodyArr['G_O_BIKOU38'],
          "O_G_FREE39" => $bodyArr['G_O_BIKOU39'],
          "O_G_FREE40" => $bodyArr['G_O_BIKOU40'],
          "O_G_FREE41" => $bodyArr['G_O_BIKOU41'],
          "O_G_FREE42" => $bodyArr['G_O_BIKOU42'],
          "O_G_FREE43" => $bodyArr['G_O_BIKOU43'],
          "O_G_FREE44" => $bodyArr['G_O_BIKOU44'],
          "O_G_FREE45" => $bodyArr['G_O_BIKOU45'],
          "O_G_FREE46" => $bodyArr['G_O_BIKOU46'],
          "O_G_FREE47" => $bodyArr['G_O_BIKOU47'],
          "O_G_FREE48" => $bodyArr['G_O_BIKOU48'],
          "O_G_FREE49" => $bodyArr['G_O_BIKOU49'],
          "O_G_FREE50" => $bodyArr['G_O_BIKOU50'],
          "G_MARKETING01" => $bodyArr['G_MARKETING01'],
          "G_MARKETING02" => $bodyArr['G_MARKETING02'],
          "G_MARKETING03" => $bodyArr['G_MARKETING03'],
          "G_MARKETING04" => $bodyArr['G_MARKETING04'],
          "G_MARKETING05" => $bodyArr['G_MARKETING05'],
          "G_MARKETING06" => $bodyArr['G_MARKETING06'],
          "G_MARKETING07" => $bodyArr['G_MARKETING07'],
          "G_MARKETING08" => $bodyArr['G_MARKETING08'],
          "G_MARKETING09" => $bodyArr['G_MARKETING09'],
          "G_MARKETING10" => $bodyArr['G_MARKETING10'],
          "G_MARKETING11" => $bodyArr['G_MARKETING11'],
          "G_MARKETING12" => $bodyArr['G_MARKETING12'],
          "G_MARKETING13" => $bodyArr['G_MARKETING13'],
          "G_MARKETING14" => $bodyArr['G_MARKETING14'],
          "G_MARKETING15" => $bodyArr['G_MARKETING15'],
          "G_MARKETING16" => $bodyArr['G_ADD_MARKETING16'],
          "G_MARKETING17" => $bodyArr['G_ADD_MARKETING17'],
          "G_MARKETING18" => $bodyArr['G_ADD_MARKETING18'],
          "G_MARKETING19" => $bodyArr['G_ADD_MARKETING19'],
          "G_MARKETING20" => $bodyArr['G_ADD_MARKETING20'],
          "G_KEYWORD" => $bodyArr['G_KEYWORD'],
          "G_G_FREE01" => "",
          "G_G_FREE02" => "",
          "G_G_FREE03" => "",
          "G_G_FREE04" => "",
          "G_G_FREE05" => "",
          "G_G_FREE06" => "",
          "G_G_FREE07" => "",
          "G_G_FREE08" => "",
          "G_G_FREE09" => "",
          "G_G_FREE10" => "",
          "G_G_FREE11" => "",
          "G_G_FREE12" => "",
          "G_G_FREE13" => "",
          "G_G_FREE14" => "",
          "G_G_FREE15" => "",
          "G_G_FREE16" => "",
          "G_G_FREE17" => "",
          "G_G_FREE18" => "",
          "G_G_FREE19" => "",
          "G_G_FREE20" => "",
          "G_G_FREE21" => "",
          "G_G_FREE22" => "",
          "G_G_FREE23" => "",
          "G_G_FREE24" => "",
          "G_G_FREE25" => "",
          "G_G_FREE26" => "",
          "G_G_FREE27" => "",
          "G_G_FREE28" => "",
          "G_G_FREE29" => "",
          "G_G_FREE30" => "",
          "G_G_FREE31" => "",
          "G_G_FREE32" => "",
          "G_G_FREE33" => "",
          "G_G_FREE34" => "",
          "G_G_FREE35" => "",
          "G_G_FREE36" => "",
          "G_G_FREE37" => "",
          "G_G_FREE38" => "",
          "G_G_FREE39" => "",
          "G_G_FREE40" => "",
          "G_G_FREE41" => "",
          "G_G_FREE42" => "",
          "G_G_FREE43" => "",
          "G_G_FREE44" => "",
          "G_G_FREE45" => "",
          "G_G_FREE46" => "",
          "G_G_FREE47" => "",
          "G_G_FREE48" => "",
          "G_G_FREE49" => "",
          "G_G_FREE50" => "",
          "NO_ORGANIZATION_FLG" => "",
          "USER_P_ID2" => "",
          "PER_BANK_CD" => $bodyArr['P_BANK_CD'],
          "PER_BRANCH_CD" => $bodyArr['P_BRANCH_CD'],
          "PER_ACCOUNT_TYPE" => $bodyArr['P_ACCAUNT_TYPE'],
          "PER_ACCOUNT_NO" => $bodyArr['P_ACCOUNT_NO'],
          "PER_ACCOUNT_NM" => $bodyArr['P_ACCOUNT_NM'],
          "PER_CUST_NO" => $bodyArr['P_CUST_NO'],
          "PER_SAVINGS_CD" => $bodyArr['P_SAVINGS_CD'],
          "PER_SAVINGS_NO" => $bodyArr['P_SAVINGS_NO'],
          "P_PASSWORD" => $bodyArr['P_PASSWORD'],
          "P_PASSWORD2" => $bodyArr['P_PASSWORD2'],
          "C_FNAME" => explode(" ",$bodyArr['P_C_NAME'])[0],
          "C_LNAME" => (!empty(explode(" ",$bodyArr['P_C_NAME'])[1]))?explode(" ",$bodyArr['P_C_NAME'])[1]:"",
          "C_FNAME_KN" => explode(" ",$bodyArr['P_C_KANA'])[0],
          "C_LNAME_KN" => (!empty(explode(" ",$bodyArr['P_C_KANA'])[1]))?explode(" ",$bodyArr['P_C_KANA'])[1]:"",
          "C_NAME_EN" => $bodyArr['P_C_NAME_EN'],
          "C_SEX" => $bodyArr['P_C_SEX'],
          "C_URL" => $bodyArr['P_C_URL'],
          "C_EMAIL" => $bodyArr['P_C_EMAIL'],
          "C_CC_EMAIL" => $bodyArr['P_C_CC_EMAIL'],
          "C_PMAIL" => $bodyArr['P_C_PMAIL'],
          "C_TEL" => $bodyArr['P_C_TEL'],
          "C_FAX" => $bodyArr['P_C_FAX'],
          "C_PTEL" => $bodyArr['P_C_PTEL'],
          "C_POST" => $bodyArr['P_C_POST'],
          "C_STA" => $bodyArr['P_C_STA'],
          "C_ADR" => $bodyArr['P_C_ADR'],
          "C_ADR2" => $bodyArr['P_C_ADR2'],
          "C_ADR3" => $bodyArr['P_C_ADR3'],
          "C_ADR_EN" => $bodyArr['P_C_ADR_EN'],
          "SP_FLG" => 1,
          "LOGIN_LOCK_FLG" => $bodyArr['P_LOGIN_LOCK_FLG'],
          "GROUP_ENABLE_FLG" => $bodyArr['P_GROUP_ENABLE_FLG'],
          "MEETING_NM_DISP" => $bodyArr['P_MEETING_NM_DISP'],
          "HANDLE_NM" => $bodyArr['P_HANDLE_NM'],
          "MEETING_NM_MK" => $bodyArr['P_MEETING_NM_MK'],
          "C_IMG" => $bodyArr['P_C_IMG'],
          "C_IMG2" => $bodyArr['P_C_IMG2'],
          "C_IMG3" => $bodyArr['P_C_IMG3'],
          "C_APPEAL" => $bodyArr['P_C_APPEAL'],
          "P_URL" => $bodyArr['P_P_URL'],
          "P_EMAIL" => $bodyArr['P_P_EMAIL'],
          "P_CC_EMAIL" => $bodyArr['P_P_CC_EMAIL'],
          "P_PMAIL" => $bodyArr['P_P_PMAIL'],
          "P_TEL" => $bodyArr['P_P_TEL'],
          "P_FAX" => $bodyArr['P_P_FAX'],
          "P_PTEL" => $bodyArr['P_P_PTEL'],
          "P_POST" => $bodyArr['P_P_POST'],
          "P_STA" => $bodyArr['P_P_STA'],
          "P_ADR" => $bodyArr['P_P_ADR'],
          "P_ADR2" => $bodyArr['P_P_ADR2'],
          "P_ADR3" => $bodyArr['P_P_ADR3'],
          "P_ADR_EN" => $bodyArr['P_P_ADR_EN'],
          "P_BIRTH" => $bodyArr['P_P_BIRTH'],
          "P_CREDITCARD_TYPE" => "",
          "P_CREDITCARD_NO" => "",
          "P_CREDITCARD_NM" => "",
          "P_CREDITCARD_GT" => "",
          "CARD_OPEN" => $bodyArr['P_CARD_OPEN'],
          "O_C_FNAME" => $bodyArr['P_O_NAME'],
          "O_C_LNAME" => $bodyArr['P_O_KANA'],
          "O_C_SEX" => $bodyArr['P_O_SEX'],
          "O_C_URL" => $bodyArr['P_O_URL'],
          "O_C_EMAIL" => $bodyArr['P_O_EMAIL'],
          "O_C_CC_EMAIL" => $bodyArr['P_O_C_CC_EMAIL'],
          "O_C_PMAIL" => $bodyArr['P_O_PMAIL'],
          "O_C_TEL" => $bodyArr['P_O_TEL'],
          "O_C_FAX" => $bodyArr['P_O_FAX'],
          "O_C_PTEL" => $bodyArr['P_O_PTEL'],
          "O_C_POST" => $bodyArr['P_O_POST'],
          "O_C_STA" => $bodyArr['P_O_STA'],
          "O_C_ADR" => $bodyArr['O_C_ADR'],
          "O_G_ID" => $bodyArr['P_O_G_ID'],
          "O_C_IMG" => $bodyArr['P_O_IMG'],
          "O_C_IMG2" => $bodyArr['P_O_IMG2'],
          "O_C_IMG3" => $bodyArr['P_O_IMG3'],
          "O_C_APPEAL" => $bodyArr['P_O_APPEAL'],
          "PRIVATE_OPEN" => $bodyArr['P_PRIVATE_OPEN'],
          "O_P_URL" => 1,
          "O_P_EMAIL" => 1,
          "O_P_CC_EMAIL" => 1,
          "O_P_PMAIL" => 1,
          "O_P_TEL" => 1,
          "O_P_FAX" => 1,
          "O_P_PTEL" => 1,
          "O_P_POST" => 1,
          "O_P_STA" => 1,
          "O_P_ADR" => 1,
          "O_P_BIRTH" => 1,
          "O_OFFICIAL" => 1,
          "O_AFFILIATION" => 1,
          "S_C_TEL" => "",
          "S_C_FAX" => "",
          "S_C_PTEL" => "",
          "S_P_TEL" => "",
          "S_P_FAX" => "",
          "S_P_PTEL" => "",
          "MOBILE_TERM_ID" => "",
          "MAIL_NOTICE_DATE" => "2018-12-19T13:57:25.2297493+07:00",
          "URL_NOTICE_DATE" => "2018-12-19T13:57:25.2297493+07:00",
          "FELICA_ID" => "",
          "FAMILY_MEMBER_PASSWORD" => "",
          "NO_PERSONAL_FLG" => "",
          "C_FREE01" => $bodyArr['P_C_FREE1'],
          "C_FREE02" => $bodyArr['P_C_FREE2'],
          "C_FREE03" => $bodyArr['P_C_FREE3'],
          "C_FREE04" => $bodyArr['P_C_FREE4'],
          "C_FREE05" => $bodyArr['P_C_FREE5'],
          "C_FREE06" => $bodyArr['P_C_FREE6'],
          "C_FREE07" => $bodyArr['P_C_FREE7'],
          "C_FREE08" => $bodyArr['P_C_FREE8'],
          "C_FREE09" => $bodyArr['P_C_FREE9'],
          "C_FREE10" => $bodyArr['P_C_FREE10'],
          "C_FREE11" => $bodyArr['P_C_FREE11'],
          "C_FREE12" => $bodyArr['P_C_FREE12'],
          "C_FREE13" => $bodyArr['P_C_FREE13'],
          "C_FREE14" => $bodyArr['P_C_FREE14'],
          "C_FREE15" => $bodyArr['P_C_FREE15'],
          "C_FREE16" => $bodyArr['P_C_FREE16'],
          "C_FREE17" => $bodyArr['P_C_FREE17'],
          "C_FREE18" => $bodyArr['P_C_FREE18'],
          "C_FREE19" => $bodyArr['P_C_FREE19'],
          "C_FREE20" => $bodyArr['P_C_FREE20'],
          "C_FREE21" => $bodyArr['P_C_FREE21'],
          "C_FREE22" => $bodyArr['P_C_FREE22'],
          "C_FREE23" => $bodyArr['P_C_FREE23'],
          "C_FREE24" => $bodyArr['P_C_FREE24'],
          "C_FREE25" => $bodyArr['P_C_FREE25'],
          "C_FREE26" => $bodyArr['P_C_FREE26'],
          "C_FREE27" => $bodyArr['P_C_FREE27'],
          "C_FREE28" => $bodyArr['P_C_FREE28'],
          "C_FREE29" => $bodyArr['P_C_FREE29'],
          "C_FREE30" => $bodyArr['P_C_FREE30'],
          "C_FREE30" => $bodyArr['P_C_FREE30'],
          "C_FREE31" => $bodyArr['P_C_FREE31'],
          "C_FREE32" => $bodyArr['P_C_FREE32'],
          "C_FREE33" => $bodyArr['P_C_FREE33'],
          "C_FREE34" => $bodyArr['P_C_FREE34'],
          "C_FREE35" => $bodyArr['P_C_FREE35'],
          "C_FREE36" => $bodyArr['P_C_FREE36'],
          "C_FREE37" => $bodyArr['P_C_FREE37'],
          "C_FREE38" => $bodyArr['P_C_FREE38'],
          "C_FREE39" => $bodyArr['P_C_FREE39'],
          "C_FREE40" => $bodyArr['P_C_FREE40'],
          "C_FREE41" => $bodyArr['P_C_FREE41'],
          "C_FREE42" => $bodyArr['P_C_FREE42'],
          "C_FREE43" => $bodyArr['P_C_FREE43'],
          "C_FREE44" => $bodyArr['P_C_FREE44'],
          "C_FREE45" => $bodyArr['P_C_FREE45'],
          "C_FREE46" => $bodyArr['P_C_FREE46'],
          "C_FREE47" => $bodyArr['P_C_FREE47'],
          "C_FREE48" => $bodyArr['P_C_FREE48'],
          "C_FREE49" => $bodyArr['P_C_FREE49'],
          "C_FREE50" => $bodyArr['P_C_FREE50'],
          "O_FREE01" => $bodyArr['P_O_BIKOU1'],
          "O_FREE02" => $bodyArr['P_O_BIKOU2'],
          "O_FREE03" => $bodyArr['P_O_BIKOU3'],
          "O_FREE04" => $bodyArr['P_O_BIKOU4'],
          "O_FREE05" => $bodyArr['P_O_BIKOU5'],
          "O_FREE06" => $bodyArr['P_O_BIKOU6'],
          "O_FREE07" => $bodyArr['P_O_BIKOU7'],
          "O_FREE08" => $bodyArr['P_O_BIKOU8'],
          "O_FREE09" => $bodyArr['P_O_BIKOU9'],
          "O_FREE10" => $bodyArr['P_O_BIKOU10'],
          "O_FREE11" => $bodyArr['P_O_BIKOU11'],
          "O_FREE12" => $bodyArr['P_O_BIKOU12'],
          "O_FREE13" => $bodyArr['P_O_BIKOU13'],
          "O_FREE14" => $bodyArr['P_O_BIKOU14'],
          "O_FREE15" => $bodyArr['P_O_BIKOU15'],
          "O_FREE16" => $bodyArr['P_O_BIKOU16'],
          "O_FREE17" => $bodyArr['P_O_BIKOU17'],
          "O_FREE18" => $bodyArr['P_O_BIKOU18'],
          "O_FREE19" => $bodyArr['P_O_BIKOU19'],
          "O_FREE20" => $bodyArr['P_O_BIKOU20'],
          "O_FREE21" => $bodyArr['P_O_BIKOU21'],
          "O_FREE22" => $bodyArr['P_O_BIKOU22'],
          "O_FREE23" => $bodyArr['P_O_BIKOU23'],
          "O_FREE24" => $bodyArr['P_O_BIKOU24'],
          "O_FREE25" => $bodyArr['P_O_BIKOU25'],
          "O_FREE26" => $bodyArr['P_O_BIKOU26'],
          "O_FREE27" => $bodyArr['P_O_BIKOU27'],
          "O_FREE28" => $bodyArr['P_O_BIKOU28'],
          "O_FREE29" => $bodyArr['P_O_BIKOU29'],
          "O_FREE30" => $bodyArr['P_O_BIKOU30'],
          "O_FREE31" => $bodyArr['P_O_BIKOU31'],
          "O_FREE32" => $bodyArr['P_O_BIKOU32'],
          "O_FREE33" => $bodyArr['P_O_BIKOU33'],
          "O_FREE34" => $bodyArr['P_O_BIKOU34'],
          "O_FREE35" => $bodyArr['P_O_BIKOU35'],
          "O_FREE36" => $bodyArr['P_O_BIKOU36'],
          "O_FREE37" => $bodyArr['P_O_BIKOU37'],
          "O_FREE38" => $bodyArr['P_O_BIKOU38'],
          "O_FREE39" => $bodyArr['P_O_BIKOU39'],
          "O_FREE40" => $bodyArr['P_O_BIKOU40'],
          "O_FREE41" => $bodyArr['P_O_BIKOU41'],
          "O_FREE42" => $bodyArr['P_O_BIKOU42'],
          "O_FREE43" => $bodyArr['P_O_BIKOU43'],
          "O_FREE44" => $bodyArr['P_O_BIKOU44'],
          "O_FREE45" => $bodyArr['P_O_BIKOU45'],
          "O_FREE46" => $bodyArr['P_O_BIKOU46'],
          "O_FREE47" => $bodyArr['P_O_BIKOU47'],
          "O_FREE48" => $bodyArr['P_O_BIKOU48'],
          "O_FREE49" => $bodyArr['P_O_BIKOU49'],
          "O_FREE50" => $bodyArr['P_O_BIKOU50'],
          "P_FREE01" => $bodyArr['P_P_FREE1'],
          "P_FREE02" => $bodyArr['P_P_FREE2'],
          "P_FREE03" => $bodyArr['P_P_FREE3'],
          "P_FREE04" => $bodyArr['P_P_FREE4'],
          "P_FREE05" => $bodyArr['P_P_FREE5'],
          "P_FREE06" => $bodyArr['P_P_FREE6'],
          "P_FREE07" => $bodyArr['P_P_FREE7'],
          "P_FREE08" => $bodyArr['P_P_FREE8'],
          "P_FREE09" => $bodyArr['P_P_FREE9'],
          "P_FREE10" => $bodyArr['P_P_FREE10'],
          "P_FREE11" => $bodyArr['P_P_FREE11'],
          "P_FREE12" => $bodyArr['P_P_FREE12'],
          "P_FREE13" => $bodyArr['P_P_FREE13'],
          "P_FREE14" => $bodyArr['P_P_FREE14'],
          "P_FREE15" => $bodyArr['P_P_FREE15'],
          "P_FREE16" => $bodyArr['P_P_FREE16'],
          "P_FREE17" => $bodyArr['P_P_FREE17'],
          "P_FREE18" => $bodyArr['P_P_FREE18'],
          "P_FREE19" => $bodyArr['P_P_FREE19'],
          "P_FREE20" => $bodyArr['P_P_FREE20'],
          "P_FREE21" => $bodyArr['P_P_FREE21'],
          "P_FREE22" => $bodyArr['P_P_FREE22'],
          "P_FREE23" => $bodyArr['P_P_FREE23'],
          "P_FREE24" => $bodyArr['P_P_FREE24'],
          "P_FREE25" => $bodyArr['P_P_FREE25'],
          "P_FREE26" => $bodyArr['P_P_FREE26'],
          "P_FREE27" => $bodyArr['P_P_FREE27'],
          "P_FREE28" => $bodyArr['P_P_FREE28'],
          "P_FREE29" => $bodyArr['P_P_FREE29'],
          "P_FREE30" => $bodyArr['P_P_FREE30'],
          "P_FREE31" => $bodyArr['P_P_FREE31'],
          "P_FREE32" => $bodyArr['P_P_FREE32'],
          "P_FREE33" => $bodyArr['P_P_FREE33'],
          "P_FREE34" => $bodyArr['P_P_FREE34'],
          "P_FREE35" => $bodyArr['P_P_FREE35'],
          "P_FREE36" => $bodyArr['P_P_FREE36'],
          "P_FREE37" => $bodyArr['P_P_FREE37'],
          "P_FREE38" => $bodyArr['P_P_FREE38'],
          "P_FREE39" => $bodyArr['P_P_FREE39'],
          "P_FREE40" => $bodyArr['P_P_FREE40'],
          "P_FREE41" => $bodyArr['P_P_FREE41'],
          "P_FREE42" => $bodyArr['P_P_FREE42'],
          "P_FREE43" => $bodyArr['P_P_FREE43'],
          "P_FREE44" => $bodyArr['P_P_FREE44'],
          "P_FREE45" => $bodyArr['P_P_FREE45'],
          "P_FREE46" => $bodyArr['P_P_FREE46'],
          "P_FREE47" => $bodyArr['P_P_FREE47'],
          "P_FREE48" => $bodyArr['P_P_FREE48'],
          "P_FREE49" => $bodyArr['P_P_FREE49'],
          "P_FREE50" => $bodyArr['P_P_FREE50'],
          "O_P_FREE01" => $bodyArr['O_P_FREE1'],
          "O_P_FREE02" => $bodyArr['O_P_FREE2'],
          "O_P_FREE03" => $bodyArr['O_P_FREE3'],
          "O_P_FREE04" => $bodyArr['O_P_FREE4'],
          "O_P_FREE05" => $bodyArr['O_P_FREE5'],
          "O_P_FREE06" => $bodyArr['O_P_FREE6'],
          "O_P_FREE07" => $bodyArr['O_P_FREE7'],
          "O_P_FREE08" => $bodyArr['O_P_FREE8'],
          "O_P_FREE09" => $bodyArr['O_P_FREE9'],
          "O_P_FREE10" => $bodyArr['O_P_FREE10'],
          "O_P_FREE11" => $bodyArr['O_P_FREE11'],
          "O_P_FREE12" => $bodyArr['O_P_FREE12'],
          "O_P_FREE13" => $bodyArr['O_P_FREE13'],
          "O_P_FREE14" => $bodyArr['O_P_FREE14'],
          "O_P_FREE15" => $bodyArr['O_P_FREE15'],
          "O_P_FREE16" => $bodyArr['O_P_FREE16'],
          "O_P_FREE17" => $bodyArr['O_P_FREE17'],
          "O_P_FREE18" => $bodyArr['O_P_FREE18'],
          "O_P_FREE19" => $bodyArr['O_P_FREE19'],
          "O_P_FREE20" => $bodyArr['O_P_FREE20'],
          "O_P_FREE21" => $bodyArr['O_P_FREE21'],
          "O_P_FREE22" => $bodyArr['O_P_FREE22'],
          "O_P_FREE23" => $bodyArr['O_P_FREE23'],
          "O_P_FREE24" => $bodyArr['O_P_FREE24'],
          "O_P_FREE25" => $bodyArr['O_P_FREE25'],
          "O_P_FREE26" => $bodyArr['O_P_FREE26'],
          "O_P_FREE27" => $bodyArr['O_P_FREE27'],
          "O_P_FREE28" => $bodyArr['O_P_FREE28'],
          "O_P_FREE29" => $bodyArr['O_P_FREE29'],
          "O_P_FREE30" => $bodyArr['O_P_FREE30'],
          "O_P_FREE31" => $bodyArr['O_P_FREE31'],
          "O_P_FREE32" => $bodyArr['O_P_FREE32'],
          "O_P_FREE33" => $bodyArr['O_P_FREE33'],
          "O_P_FREE34" => $bodyArr['O_P_FREE34'],
          "O_P_FREE35" => $bodyArr['O_P_FREE35'],
          "O_P_FREE36" => $bodyArr['O_P_FREE36'],
          "O_P_FREE37" => $bodyArr['O_P_FREE37'],
          "O_P_FREE38" => $bodyArr['O_P_FREE38'],
          "O_P_FREE39" => $bodyArr['O_P_FREE39'],
          "O_P_FREE40" => $bodyArr['O_P_FREE40'],
          "O_P_FREE41" => $bodyArr['O_P_FREE41'],
          "O_P_FREE42" => $bodyArr['O_P_FREE42'],
          "O_P_FREE43" => $bodyArr['O_P_FREE43'],
          "O_P_FREE44" => $bodyArr['O_P_FREE44'],
          "O_P_FREE45" => $bodyArr['O_P_FREE45'],
          "O_P_FREE46" => $bodyArr['O_P_FREE46'],
          "O_P_FREE47" => $bodyArr['O_P_FREE47'],
          "O_P_FREE48" => $bodyArr['O_P_FREE48'],
          "O_P_FREE49" => $bodyArr['O_P_FREE49'],
          "O_P_FREE50" => $bodyArr['O_P_FREE50'],
          "C_KEYWORD" => $bodyArr['P_C_KEYWORD'],
          "C_G_FREE01" => "",
          "C_G_FREE02" => "",
          "C_G_FREE03" => "",
          "C_G_FREE04" => "",
          "C_G_FREE05" => "",
          "C_G_FREE06" => "",
          "C_G_FREE07" => "",
          "C_G_FREE08" => "",
          "C_G_FREE09" => "",
          "C_G_FREE10" => "",
          "C_G_FREE11" => "",
          "C_G_FREE12" => "",
          "C_G_FREE13" => "",
          "C_G_FREE14" => "",
          "C_G_FREE15" => "",
          "C_G_FREE16" => "",
          "C_G_FREE17" => "",
          "C_G_FREE18" => "",
          "C_G_FREE19" => "",
          "C_G_FREE20" => "",
          "C_G_FREE21" => "",
          "C_G_FREE22" => "",
          "C_G_FREE23" => "",
          "C_G_FREE24" => "",
          "C_G_FREE25" => "",
          "C_G_FREE26" => "",
          "C_G_FREE27" => "",
          "C_G_FREE28" => "",
          "C_G_FREE29" => "",
          "C_G_FREE30" => "",
          "C_G_FREE31" => "",
          "C_G_FREE32" => "",
          "C_G_FREE33" => "",
          "C_G_FREE34" => "",
          "C_G_FREE35" => "",
          "C_G_FREE36" => "",
          "C_G_FREE37" => "",
          "C_G_FREE38" => "",
          "C_G_FREE39" => "",
          "C_G_FREE40" => "",
          "C_G_FREE41" => "",
          "C_G_FREE42" => "",
          "C_G_FREE43" => "",
          "C_G_FREE44" => "",
          "C_G_FREE45" => "",
          "C_G_FREE46" => "",
          "C_G_FREE47" => "",
          "C_G_FREE48" => "",
          "C_G_FREE49" => "",
          "C_G_FREE50" => "",
          "UN_SUBSCRIBE_FLG" => 1,
          "SKYPE_NAME" => "",
          "OUTSOURCE_TYPE" => 1,
          "AFF_AFFILIATION_NAME" => $bodyArr['S_AFFILIATION_NAME'],
          "AFF_OFFICIAL_POSITION" => $bodyArr['S_OFFICIAL_POSITION']
        );
        $rsUpdateMember = get_api_common($postid, $url, $arrBody, "POST");
        return $rsUpdateMember;
    }
    
    public function addEmail($post_id,$arrValue){
      $url = URL_API.'Personal/UpdateMail';
      $arrBody = array(
          "TG_ID"=> (isset($_SESSION['arrSession']->TG_ID))?$_SESSION['arrSession']->TG_ID:get_post_meta($post_id,'member_meta_group_id',true),
          "EMAIL" => $arrValue['EMAIL'],
          "RE_EMAIL" => $arrValue['RE_EMAIL'],
          "PMAIL" => $arrValue['PMAIL'],
          "C_NAME" => $arrValue['C_NAME'],
          "G_NAME" => $arrValue['G_NAME']
      );
      $memberAddEmail = get_api_common($post_id, $url, $arrBody, "POST");
      return $memberAddEmail;
    }

    public function SendMailAfterEntryMail($post_id,$auto_reg_flg,$msg2,$msg3,$arrValue){
      $entry_mail_file = dirname(dirname(__FILE__))."/settingform/ini/entry_mail.ini";
      if(file_exists($entry_mail_file)){
        $objText = $this->convertFile($entry_mail_file);
        $objText = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $objText);
      }else {
        $objText = "";
      }
      $tg_id = get_post_meta($post_id,"member_meta_group_id",true);
      $mail_address = get_post_meta($post_id,"mail_address",true);
      $arrBody = array(
        "tg_id" => $tg_id,
        "mailTo" => $mail_address,
        "auto_reg_flg" => $auto_reg_flg,
        "msg2" => $msg2,
        "msg3" => $msg3,
        "dataIniFile" => $objText,
        "p_c_name" => $arrValue['C_NAME'],
        "g_name" => $arrValue['G_NAME'],
        "p_c_email" => $arrValue['EMAIL'],
        "p_c_pmail" => $arrValue['PMAIL'],
        "representative" => $arrValue['representative'],
        "involved" => $arrValue['involved'],
      );
      $url = URL_API.'Member/SendMailAfterEntryMail';
      $SendMailAfterEntryMail = get_api_common($post_id, $url, $arrBody , "POST");
      return $SendMailAfterEntryMail;
    }

    public function SendMailMemberEntryMail($post_id,$member_pid,$p_c_email,$p_c_pmail){
      $tg_id = get_post_meta($post_id,"member_meta_group_id",true);
      $url = URL_API.'Member/SendMailMemberEntryMail?tg_id='.$tg_id.'&member_pid='.$member_pid.'&p_c_email='.$p_c_email.'&p_c_pmail='.$p_c_pmail;
      $SendMailMemberEntryMail = get_api_common($post_id, $url, array() , "GET");
      return $SendMailMemberEntryMail;
    }

    public function selectData($arrValue){
      $url = URL_API.'Member/SelectData ';
      $arrBody = array(
          "P_ID"=> ($_SESSION['arrSession']->P_ID)?$_SESSION['arrSession']->P_ID:"",
          "TG_ID" => ($_SESSION['arrSession']->TG_ID)?$_SESSION['arrSession']->TG_ID:get_option('nakama-member-group-id'),
          "G_ID" => ($_SESSION['arrSession']->G_ID)?$_SESSION['arrSession']->G_ID:"",
          "G_Chg" => $arrValue['G_Chg'],
          "Relmail" => $arrValue['Relmail'],
          "Pgh_Relmail" => $arrValue['Pgh_Relmail'],
          "B_User_Id" => $arrValue['B_User_Id']
      );
      $selectData = get_api_common($post_id, $url, $arrBody, "POST");
      return $selectData;
    }

    public function gdMonthText($val){
      $buf = array("", "１月", "２月", "３月", "４月", "５月", "６月", "７月", "８月", "９月", "１０月", "１１月", "１２月");
      return $buf[$val];
    }

    public function getFaxTimezone($timezone,$fax_time_from, $fax_time_to){
      if($timezone == "1"){
        return "日中(07:00～17:00)";
      }
      else
        if($timezone == "2"){
          return "夜間(17:00～24:00)";
        }
        else
          if($timezone == "3"){
            return "深夜(24:00～07:00)";
          }
        else
          if($timezone == "4"){
            $str_timezone = "指定　".substr($fax_time_from,1,2)."時".substr($fax_time_from,4,2)."分～".substr($fax_time_to,1,2)."時".substr($fax_time_to,4,2)."分";
            return $str_timezone;
          }
          else return "指定なし";
    }

    public function MakeSmallImageName($id, $fileName){
      $pos = strpos($fileName, ".");
      if($pos != 0)
        return $id.".".substr($fileName,1, $pos - 1)."_S.gif";
      else
        return $id.".".$fileName."_S.gif";
    }

    public function MakeGroupImageUrl($fileName){
      $NAK_NAKAMA_PATH = "https://wn.cococica.com/nakama";
      $NAK_GROUP_IMAGE_PATH = "group/img";
      return $NAK_NAKAMA_PATH."/imgs/".$NAK_GROUP_IMAGE_PATH."/".$fileName;
    }

    public function MakeLogoImageUrl($fileName){
      $NAK_NAKAMA_PATH = "https://wn.cococica.com/nakama";
      $NAK_GROUP_LOGO_PATH = "group/logo";
      return $NAK_NAKAMA_PATH."/imgs/".$NAK_GROUP_LOGO_PATH."/".$fileName;
    }

    public function AccountTypeText($account_type){
      $NAK_ACCOUNT_TYPE_SAVINGS = 1;
      $NAK_ACCOUNT_TYPE_CHECKING = 2;
      switch ($account_type) {
        case $NAK_ACCOUNT_TYPE_SAVINGS:
          return "普通";
          break;
        case $NAK_ACCOUNT_TYPE_CHECKING:
          return "当座";
          break;
      }
    }

    public function getGroupname($post_id,$TG_ID,$LG_ID,$LG_TYPE){
      $url = URL_API.'Member/getGroupname';
      $arrBody = array(
          "TG_ID" => $TG_ID,
          "LG_ID" => $LG_ID,
          "LG_TYPE" => $LG_TYPE,
      );
      $getGroupname = get_api_common($post_id, $url, $arrBody, "POST");
      return !empty($getGroupname->TG_NAME)?$getGroupname->TG_NAME:"";
    }
    function Member_logined_GetSetting($post_id = '', $type){
      $tg_id = (!empty($_SESSION['arrSession'])) ? $_SESSION['arrSession']->TG_ID:"";
      $p_id = (!empty($_SESSION['arrSession'])) ? $_SESSION['arrSession']->P_ID :"";
      $url = URL_API."Setting/$type?tg_id=$tg_id&fun_id=".MEMBER_FUNC_ID_LIST_LOGINED."&p_id=".$p_id."&pattern_no=0";
      $getSetting = get_api_common($post_id, $url, array(), "GET");
      return $getSetting;
    }
    function Member_logined_PostSettingList($arrItem){
      $path_page = "nakama-logined-setting-view";
      if(!isset($_SESSION['arrSession'])){
        wp_redirect($this->getPageSlug("nakama-login").'page_redirect='.$path_page);
        exit();
      }
      $url = URL_API."Setting/SetListDisPlay";
      $param = array(
      "TG_ID"=> (!empty($_SESSION['arrSession'])) ? $_SESSION['arrSession']->TG_ID :"",
      "P_ID"=> (!empty($_SESSION['arrSession'])) ? $_SESSION['arrSession']->P_ID :"",
      "FUNC_ID" => MEMBER_FUNC_ID_LIST_LOGINED,
      "item_id_list"=> $arrItem,
      "PATTERN_NO" => 0
      );
      $postSetting = get_api_common($post_id, $url, $param, "POST");
      return $postSetting;
    }
    function Member_logined_PostSettingSort($arrItem){
      $path_page = "nakama-logined-setting-sort";
      if(!isset($_SESSION['arrSession'])){
        wp_redirect($this->getPageSlug("nakama-login").'page_redirect='.$path_page);
        exit();
      }
      $url = URL_API."Setting/SetListDisOrder";
      $tg_id = (!empty($_SESSION['arrSession'])) ? $_SESSION['arrSession']->TG_ID : "";
      $p_id = (!empty($_SESSION['arrSession'])) ? $_SESSION['arrSession']->P_ID : "";
      $param = array(
        "TG_ID"=> $tg_id,
        "P_ID"=> $p_id,
        "FUNC_ID" => MEMBER_FUNC_ID_LIST_LOGINED,
        "item_value_sort"=> $arrItem,
        "PATTERN_NO" => 0
      );
      $postSetting = get_api_common($post_id, $url, $param, "POST");
      return $postSetting;
    }
    function readFileCSV(){
      $name_file = get_option('regist-file-hidden');
      $file_path = URL_FILE_REGIST.'/'.$name_file;
      $arrCustom = array();
      if (($handle = fopen($file_path, "r")) !== false) {
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            array_push($arrCustom, $data);
            $row++;
        }
        fclose($handle);
      }
      return $arrCustom;
    }
    function SetConnection(){
      $md = $GLOBALS['md'];
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == 1):
        
        echo '<textarea style="ime-mode:active; width:100% !important; line-height:150%; max-width: 100%;" rows="8" name="M_CONNECTION">'.(!empty($md->G_M_KEYWORD)?$md->G_M_KEYWORD:"").'</textarea>';
      else:
        echo '<textarea style="ime-mode:active; width:100% !important; line-height:150%; max-width: 100%;" rows="8" name="M_CONNECTION">'.(!empty($md->G_M_KEYWORD)?$md->G_M_KEYWORD:"").'</textarea>';
      endif;
    }
    public function getDispIniValue($value){
      switch ($value) {
        case '公開しない':
          $getDispIniValue = "0";
          break;
        case '一般公開':
          $getDispIniValue = "1";
          break;
        case '会員にのみ公開':
          $getDispIniValue = "2";
          break;
        default:
          $getDispIniValue = "";
          break;
      }
      return $getDispIniValue;
    }
    public function IsGrouping($filename){
      $item;
      $IsGrouping = false;
      $redirectFile = $filename;
      $ini_array = file_get_contents($redirectFile, true);
      $ini_array = explode("\n", $ini_array);
      foreach ($ini_array as $k => $item) {
        if(substr($item, 0, 1) == "#"):
          if($item == "#HIDDEN"):
          elseif($item == "#通信欄"):
          elseif($item == "#CONNECTION"):
          else:
            $item = explode(",", $item);
            if($item['0'] != "#"):
              $IsGrouping = true;
              break;
            endif;
          endif;
        endif;
      }
      return $IsGrouping;
    }
  public function SelectTitleN($post_id, $tg_id){
    $SelectTitleN = array();
    if($tg_id){
      $url = URL_API.'Setting/SelectTitleN?tg_id='.$tg_id;
      $arrBody = array();
      $SelectTitleN = get_api_common($post_id, $url, $arrBody, "GET");
    }
    return $SelectTitleN;
  }
  function ShowAllData($ini_file = '', $flg_disp_menu, $flg_is_shortcode, $postid = null){
      $chg = '';
      $regist_name   = "";
      $confirm_name  = "";
      $tg_id      = "";
      $forward_mail  = "";
      $set_lg_g_id   = "";
      $set_g_id      = "";
      $merumaga_flg = "";
      $merumaga_file = "";
      $entry_setting3 = '';
      $input_table = '';
      $disp_menu = $flg_disp_menu;
      $ReleaseDisp = '';
      $YearsSelect = '';
      $pd = array();
      $sd = array();
      $md = array();
      $gd = array();
      $mdh = array();
      $gdh = array();
      $pdh = array();
      $p_id = '';
      $g_id = '';
      $DefContactBilllig = $this->SetDefContactBilllig($postid);
      $GLOBALS['DefContact'] = $DefContactBilllig->DEF_CONTACT;
      switch ($disp_menu) {
        case 'div_regist':
          $regist_name   = ($flg_is_shortcode)?get_post_meta($postid,"regist_name",true):get_option('regist_name');
          $confirm_name  = "";
          $tg_id      = ($flg_is_shortcode)?get_post_meta($postid,"member_meta_group_id",true):get_option('nakama-member-group-id');
          $forward_mail  = ($flg_is_shortcode)?get_post_meta($postid,"mail_address",true):get_option('mail_address');
          $set_lg_g_id   = ($flg_is_shortcode)?get_post_meta($postid,"set_lg_g_id",true):get_option('set_lg_g_id');
          $set_g_id      = ($flg_is_shortcode)?get_post_meta($postid,"set_g_id",true):get_option('set_g_id');
          $merumaga_flg = ($flg_is_shortcode)?get_post_meta($postid,"merumaga_flg",true):get_option('merumaga_flg');
          $merumaga_file = ($flg_is_shortcode)?get_post_meta($postid,"",true):get_option('');
          $gInputOpen = ($flg_is_shortcode)?get_post_meta($postid,"input_open",true):get_option("input_open");
          $entry_setting3 = ($flg_is_shortcode)?get_post_meta($postid,"entry_setting3",true):get_option("entry_setting3");
          $YearsSelect = ($flg_is_shortcode)?get_post_meta($postid,"entry_setting2",true):get_option("entry_setting2");
          $chg = 0;

          $arrGetSysConfigData = $this->GetSysConfigData($postid, $tg_id);
          $p_id = $this->GetMakePid($postid, $tg_id);
          $g_id = $this->GetMakeGid($postid, $tg_id);
          // regist render
          if($set_lg_g_id != ""):
            $md["G_ID"] = $set_lg_g_id;
            $mdh["G_ID"] = $set_lg_g_id;
          endif;
          $pd["P_ID"] = $p_id;
          if($pd["P_ID"] == "Err"):
            echo "IDの採番に失敗しました、登録することができません。";
          endif;
          $pdh["P_ID"] = $pd["P_ID"];
          $md["TG_ID"] = $tg_id;
          $gd["G_ID"] = $g_id;
          if($gd["G_ID"] == "Err"):
            echo "IDの採番に失敗しました、登録することができません。";
          endif;
          $gdh["G_ID"] = $gd["G_ID"];
          // $md["M_CLAIMDEST"]  = DEST_CURRENT;
          // $mdh["M_CLAIMDEST"]  = DEST_CURRENT;
          $md["M_BILLING_ID"]  ="@C";
          $mdh["M_BILLING_ID"]  = "@C";
          // $md["M_CONTACT_ID"] = DEST_CURRENT;
          // $mdh["M_CONTACT_ID"] = DEST_CURRENT;
          $md["M_CONTACT_ID"] = "@C";
          $mdh["M_CONTACT_ID"] = "@C";

          $md['M_MLMAGA_FLG'] = "1";
          $mdh['M_MLMAGA_FLG'] = "1";

          $md["M_RECEIVE_INFO_FAX"] = "1";
          $mdh["M_RECEIVE_INFO_FAX"] = "1";
          $md["M_RECEIVE_INFO_MAIL"] = "1";
          $mdh["M_RECEIVE_INFO_MAIL"] = "1";

          $md["M_ADMISSION_DATE_Y"] = date("Y");
          $md["M_ADMISSION_DATE_M"] = date("m");
          $md["M_ADMISSION_DATE_D"] = date("d");

          if($arrGetSysConfigData['def_list_nodisp'] == "1"):
            $gd["O_G_NAME"]       = "1";
            $gd["O_G_KANA"]       = "1";
            $gd["O_G_NAME_AN"]       = "1";
          else:
            $gd["O_G_NAME"]       = "2";
            $gd["O_G_KANA"]       = "2";
            $gd["O_G_NAME_AN"]       = "2";
          endif;
          $pd["GROUP_ENABLE_FLG"]   = "1";
          $pd["HANDLE_NM"]       = "";
          $pd["MEETING_NM_DISP"] = "";
          $pd["O_C_FNAME"]         = "2";
          $pd["O_C_LNAME"]         = "2";
          $pd["O_G_ID"]         = "0";
          $pd["O_C_SEX"]          = "0";
          $pd["O_C_URL"]          = "0";
          $pd["O_C_EMAIL"]        = "0";
          $pd["O_C_CC_EMAIL"]   = "0";
          $pd["O_C_TEL"]          = "0";
          $pd["O_C_FAX"]          = "0";
          $pd["O_C_PTEL"]         = "0";
          $pd["O_C_PMAIL"]        = "0";
          $pd["O_C_POST"]         = "0";
          $pd["O_C_STA"]          = "0";
          $pd["O_C_ADR"]      = "0";
          $pd["O_C_IMG"]          = "0";
          $pd["O_C_IMG2"]         = "0";
          $pd["O_C_IMG3"]         = "0";
          $pd["O_C_APPEAL"]       = "0";
          $pd["O_OFFICIAL"]     = "0";
          $pd["O_AFFILIATION"]  = "0";
          $pd["O_FREE01"]       = "0";
          $pd["O_FREE02"]       = "0";
          $pd["O_FREE03"]       = "0";
          $pd["O_FREE04"]       = "0";
          $pd["O_FREE05"]       = "0";
          $pd["O_FREE06"]       = "0";
          $pd["O_FREE07"]       = "0";
          $pd["O_FREE08"]       = "0";
          $pd["O_FREE09"]       = "0";
          $pd["O_FREE10"]      = "0";
          $pd["O_FREE11"]      = "0";
          $pd["O_FREE12"]      = "0";
          $pd["O_FREE13"]      = "0";
          $pd["O_FREE14"]      = "0";
          $pd["O_FREE15"]      = "0";
          $pd["O_FREE16"]      = "0";
          $pd["O_FREE17"]      = "0";
          $pd["O_FREE18"]      = "0";
          $pd["O_FREE19"]      = "0";
          $pd["O_FREE20"]      = "0";
          $pd["O_FREE21"]      = "0";
          $pd["O_FREE22"]      = "0";
          $pd["O_FREE23"]      = "0";
          $pd["O_FREE24"]      = "0";
          $pd["O_FREE25"]      = "0";
          $pd["O_FREE26"]      = "0";
          $pd["O_FREE27"]      = "0";
          $pd["O_FREE28"]      = "0";
          $pd["O_FREE29"]      = "0";
          $pd["O_FREE30"]      = "0";
          $pd["O_FREE31"]     = "0";
          $pd["O_FREE32"]     = "0";
          $pd["O_FREE33"]     = "0";
          $pd["O_FREE34"]     = "0";
          $pd["O_FREE35"]     = "0";
          $pd["O_FREE36"]     = "0";
          $pd["O_FREE37"]     = "0";
          $pd["O_FREE38"]     = "0";
          $pd["O_FREE39"]     = "0";
          $pd["O_FREE40"]     = "0";
          $pd["O_FREE41"]     = "0";
          $pd["O_FREE42"]     = "0";
          $pd["O_FREE43"]     = "0";
          $pd["O_FREE44"]     = "0";
          $pd["O_FREE45"]     = "0";
          $pd["O_FREE46"]     = "0";
          $pd["O_FREE47"]     = "0";
          $pd["O_FREE48"]     = "0";
          $pd["O_FREE49"]     = "0";
          $pd["O_FREE50"]     = "0";

          $pd["O_P_FREE01"]       = "0";
          $pd["O_P_FREE02"]       = "0";
          $pd["O_P_FREE03"]       = "0";
          $pd["O_P_FREE04"]       = "0";
          $pd["O_P_FREE05"]       = "0";
          $pd["O_P_FREE06"]       = "0";
          $pd["O_P_FREE07"]       = "0";
          $pd["O_P_FREE08"]       = "0";
          $pd["O_P_FREE09"]       = "0";
          $pd["O_P_FREE10"]      = "0";
          $pd["O_P_FREE11"]      = "0";
          $pd["O_P_FREE12"]      = "0";
          $pd["O_P_FREE13"]      = "0";
          $pd["O_P_FREE14"]      = "0";
          $pd["O_P_FREE15"]      = "0";
          $pd["O_P_FREE16"]      = "0";
          $pd["O_P_FREE17"]      = "0";
          $pd["O_P_FREE18"]      = "0";
          $pd["O_P_FREE19"]      = "0";
          $pd["O_P_FREE20"]      = "0";
          $pd["O_P_FREE21"]      = "0";
          $pd["O_P_FREE22"]      = "0";
          $pd["O_P_FREE23"]      = "0";
          $pd["O_P_FREE24"]      = "0";
          $pd["O_P_FREE25"]      = "0";
          $pd["O_P_FREE26"]      = "0";
          $pd["O_P_FREE27"]      = "0";
          $pd["O_P_FREE28"]      = "0";
          $pd["O_P_FREE29"]      = "0";
          $pd["O_P_FREE30"]      = "0";
          $pd["O_P_FREE31"]     = "0";
          $pd["O_P_FREE32"]     = "0";
          $pd["O_P_FREE33"]     = "0";
          $pd["O_P_FREE34"]     = "0";
          $pd["O_P_FREE35"]     = "0";
          $pd["O_P_FREE36"]     = "0";
          $pd["O_P_FREE37"]     = "0";
          $pd["O_P_FREE38"]     = "0";
          $pd["O_P_FREE39"]     = "0";
          $pd["O_P_FREE40"]     = "0";
          $pd["O_P_FREE41"]     = "0";
          $pd["O_P_FREE42"]     = "0";
          $pd["O_P_FREE43"]     = "0";
          $pd["O_P_FREE44"]     = "0";
          $pd["O_P_FREE45"]     = "0";
          $pd["O_P_FREE46"]     = "0";
          $pd["O_P_FREE47"]     = "0";
          $pd["O_P_FREE48"]     = "0";
          $pd["O_P_FREE49"]     = "0";
          $pd["O_P_FREE50"]     = "0";

          if($arrGetSysConfigData['def_list_nodisp'] == "1"):
            $gdh["O_G_NAME"]      = "1";
            $gdh["O_G_KANA"]      = "1";
          else:
            $gdh["O_G_NAME"]      = "2";
            $gdh["O_G_KANA"]      = "2";
          endif;

          $pdh["GROUP_ENABLE_FLG"]  = "1";
          $pdh["HANDLE_NM"]       = "";
          $pdh["MEETING_NM_DISP"] = "";
          $pdh["O_G_NAME"]        = "2";
          $pdh["O_G_KANA"]        = "2";
          $pdh["O_G_ID"]        = "0";
          $pdh["O_C_SEX"]         = "0";
          $pdh["O_G_URL"]         = "0";
          $pdh["O_C_EMAIL"]       = "0";
          $pdh["O_C_CC_EMAIL"]  = "0";
          $pdh["O_C_TEL"]         = "0";
          $pdh["O_C_FAX"]         = "0";
          $pdh["O_C_PTEL"]        = "0";
          $pdh["O_C_PMAIL"]       = "0";
          $pdh["O_C_POST"]        = "0";
          $pdh["O_C_STA"]         = "0";
          $pdh["O_C_ADR"]     = "0";
          $pdh["O_C_IMG"]         = "0";
          $pdh["O_C_IMG2"]        = "0";
          $pdh["O_C_IMG3"]        = "0";
          $pdh["O_C_APPEAL"]      = "0";
          $pdh["O_OFFICIAL"]    = "0";
          $pdh["O_AFFILIATION"] = "0";
          $pdh["O_FREE01"]      = "0";
          $pdh["O_FREE02"]      = "0";
          $pdh["O_FREE03"]      = "0";
          $pdh["O_FREE04"]      = "0";
          $pdh["O_FREE05"]      = "0";
          $pdh["O_FREE06"]      = "0";
          $pdh["O_FREE07"]      = "0";
          $pdh["O_FREE08"]      = "0";
          $pdh["O_FREE09"]      = "0";
          $pdh["O_FREE10"]     = "0";
          $pdh["O_FREE11"]     = "0";
          $pdh["O_FREE12"]     = "0";
          $pdh["O_FREE13"]     = "0";
          $pdh["O_FREE14"]     = "0";
          $pdh["O_FREE15"]     = "0";
          $pdh["O_FREE16"]     = "0";
          $pdh["O_FREE17"]     = "0";
          $pdh["O_FREE18"]     = "0";
          $pdh["O_FREE19"]     = "0";
          $pdh["O_FREE20"]     = "0";
          $pdh["O_FREE21"]     = "0";
          $pdh["O_FREE22"]     = "0";
          $pdh["O_FREE23"]     = "0";
          $pdh["O_FREE24"]     = "0";
          $pdh["O_FREE25"]     = "0";
          $pdh["O_FREE26"]     = "0";
          $pdh["O_FREE27"]     = "0";
          $pdh["O_FREE28"]     = "0";
          $pdh["O_FREE29"]     = "0";
          $pdh["O_FREE30"]     = "0";
          $pdh["O_FREE31"]     = "0";
          $pdh["O_FREE32"]     = "0";
          $pdh["O_FREE33"]     = "0";
          $pdh["O_FREE34"]     = "0";
          $pdh["O_FREE35"]     = "0";
          $pdh["O_FREE36"]     = "0";
          $pdh["O_FREE37"]     = "0";
          $pdh["O_FREE38"]     = "0";
          $pdh["O_FREE39"]     = "0";
          $pdh["O_FREE40"]     = "0";
          $pdh["O_FREE41"]     = "0";
          $pdh["O_FREE42"]     = "0";
          $pdh["O_FREE43"]     = "0";
          $pdh["O_FREE44"]     = "0";
          $pdh["O_FREE45"]     = "0";
          $pdh["O_FREE46"]     = "0";
          $pdh["O_FREE47"]     = "0";
          $pdh["O_FREE48"]     = "0";
          $pdh["O_FREE49"]     = "0";
          $pdh["O_FREE50"]     = "0";
          $pdh["O_P_FREE01"]      = "0";
          $pdh["O_P_FREE02"]      = "0";
          $pdh["O_P_FREE03"]      = "0";
          $pdh["O_P_FREE04"]      = "0";
          $pdh["O_P_FREE05"]      = "0";
          $pdh["O_P_FREE06"]      = "0";
          $pdh["O_P_FREE07"]      = "0";
          $pdh["O_P_FREE08"]      = "0";
          $pdh["O_P_FREE09"]      = "0";
          $pdh["O_P_FREE10"]     = "0";
          $pdh["O_P_FREE11"]     = "0";
          $pdh["O_P_FREE12"]     = "0";
          $pdh["O_P_FREE13"]     = "0";
          $pdh["O_P_FREE14"]     = "0";
          $pdh["O_P_FREE15"]     = "0";
          $pdh["O_P_FREE16"]     = "0";
          $pdh["O_P_FREE17"]     = "0";
          $pdh["O_P_FREE18"]     = "0";
          $pdh["O_P_FREE19"]     = "0";
          $pdh["O_P_FREE20"]     = "0";
          $pdh["O_P_FREE21"]     = "0";
          $pdh["O_P_FREE22"]     = "0";
          $pdh["O_P_FREE23"]     = "0";
          $pdh["O_P_FREE24"]     = "0";
          $pdh["O_P_FREE25"]     = "0";
          $pdh["O_P_FREE26"]     = "0";
          $pdh["O_P_FREE27"]     = "0";
          $pdh["O_P_FREE28"]     = "0";
          $pdh["O_P_FREE29"]     = "0";
          $pdh["O_P_FREE30"]     = "0";
          $pdh["O_P_FREE31"]     = "0";
          $pdh["O_P_FREE32"]     = "0";
          $pdh["O_P_FREE33"]     = "0";
          $pdh["O_P_FREE34"]     = "0";
          $pdh["O_P_FREE35"]     = "0";
          $pdh["O_P_FREE36"]     = "0";
          $pdh["O_P_FREE37"]     = "0";
          $pdh["O_P_FREE38"]     = "0";
          $pdh["O_P_FREE39"]     = "0";
          $pdh["O_P_FREE40"]     = "0";
          $pdh["O_P_FREE41"]     = "0";
          $pdh["O_P_FREE42"]     = "0";
          $pdh["O_P_FREE43"]     = "0";
          $pdh["O_P_FREE44"]     = "0";
          $pdh["O_P_FREE45"]     = "0";
          $pdh["O_P_FREE46"]     = "0";
          $pdh["O_P_FREE47"]     = "0";
          $pdh["O_P_FREE48"]     = "0";
          $pdh["O_P_FREE49"]     = "0";
          $pdh["O_P_FREE50"]     = "0";
          $pd["PRIVATE_OPEN"]   = "0";
          $pdh["PRIVATE_OPEN"]  = "0";
          $gd["DISP_LIST"]    = $arrGetSysConfigData['def_list_nodisp'];
          $gd["DISP_DETAIL"]    = $arrGetSysConfigData['def_list_nolink'];
          $gd["O_INDUSTRY_CD"]         = "0";
          $gd["O_INDUSTRY_NM"]              = "0";
          $gd["O_G_URL"]                   = "0";
          $gd["O_P_URL"]                 = "0";
          $gd["O_G_EMAIL"]                 = "0";
          $gd["O_G_CC_EMAIL"]              = "0";
          $gd["O_G_TEL"]                   = "0";
          $gd["O_G_FAX"]                   = "0";
          $gd["O_FOUND_DATE"]            = "0";
          $gd["O_SETTLE_MONTH"]          = "0";
          $gd["O_CAPITAL"]               = "0";
          $gd["O_REPRESENTATIVE_NM"]        = "0";
          $gd["O_REPRESENTATIVE_KN"]   = "0";
          $gd["O_G_POST"]                  = "0";
          $gd["O_G_STA"]                   = "0";
          $gd["O_G_ADR"]               = "0";
          $gd["O_G_IMG"]                   = "0";
          $gd["O_G_APPEAL"]                = "0";
          //$gd["o_keyword"]               = "0";
          $gd["O_G_LOGO"]                  = "0";
          $gd["O_G_FREE01"]                = "0";
          $gd["O_G_FREE02"]                = "0";
          $gd["O_G_FREE03"]                = "0";
          $gd["O_G_FREE04"]                = "0";
          $gd["O_G_FREE05"]                = "0";
          $gd["O_G_FREE06"]               = "0";
          $gd["O_G_FREE07"]                = "0";
          $gd["O_G_FREE08"]                = "0";
          $gd["O_G_FREE09"]                = "0";
          $gd["O_G_FREE10"]               = "0";
          $gd["O_G_FREE11"]               = "0";
          $gd["O_G_FREE12"]               = "0";
          $gd["O_G_FREE13"]               = "0";
          $gd["O_G_FREE14"]               = "0";
          $gd["O_G_FREE15"]               = "0";
          $gd["O_G_FREE16"]               = "0";
          $gd["O_G_FREE17"]               = "0";
          $gd["O_G_FREE18"]              = "0";
          $gd["O_G_FREE19"]               = "0";
          $gd["O_G_FREE20"]               = "0";
          $gd["O_G_FREE21"]               = "0";
          $gd["O_G_FREE22"]               = "0";
          $gd["O_G_FREE23"]               = "0";
          $gd["O_G_FREE24"]               = "0";
          $gd["O_G_FREE25"]               = "0";
          $gd["O_G_FREE26"]               = "0";
          $gd["O_G_FREE27"]               = "0";
          $gd["O_G_FREE28"]               = "0";
          $gd["O_G_FREE29"]               = "0";
          $gd["O_G_FREE30"]               = "0";
          $gd["O_G_FREE31"]              = "0";
          $gd["O_G_FREE32"]              = "0";
          $gd["O_G_FREE33"]              = "0";
          $gd["O_G_FREE34"]              = "0";
          $gd["O_G_FREE35"]              = "0";
          $gd["O_G_FREE36"]              = "0";
          $gd["O_G_FREE37"]              = "0";
          $gd["O_G_FREE38"]              = "0";
          $gd["O_G_FREE39"]              = "0";
          $gd["O_G_FREE40"]              = "0";
          $gd["O_G_FREE41"]              = "0";
          $gd["O_G_FREE42"]              = "0";
          $gd["O_G_FREE43"]              = "0";
          $gd["O_G_FREE44"]              = "0";
          $gd["O_G_FREE45"]              = "0";
          $gd["O_G_FREE46"]              = "0";
          $gd["O_G_FREE47"]              = "0";
          $gd["O_G_FREE48"]              = "0";
          $gd["O_G_FREE49"]              = "0";
          $gd["O_G_FREE50"]              = "0";
          $gdh["O_INDUSTRY_CD"]        = "0";
          $gdh["O_INDUSTRY_NM"]             = "0";
          $gdh["O_G_URL"]                  = "0";
          $gdh["O_G_P_URL"]                = "0";
          $gdh["O_G_EMAIL"]               = "0";
          $gdh["O_G_CC_EMAIL"]            = "0";
          $gdh["O_G_TEL"]                  = "0";
          $gdh["O_G_FAX"]                  = "0";
          $gdh["O_FOUND_DATE"]          = "0";
          $gdh["O_SETTLE_MONTH"]         = "0";
          $gdh["O_CAPITAL"]              = "0";
          $gdh["O_REPRESENTATIVE_NM"]       = "0";
          $gdh["O_REPRESENTATIVE_KN"]  = "0";
          $gdh["O_G_POST"]                 = "0";
          $gdh["O_G_STA"]                  = "0";
          $gdh["O_G_ADR"]              = "0";
          $gdh["O_G_IMG"]                  = "0";
          $gdh["O_G_APPEAL"]               = "0";
          //$gdh["o_keyword"]              = "0";
          $gdh["O_G_LOGO"]                 = "0";
          $gdh["O_G_FREE01"]               = "0";
          $gdh["O_G_FREE02"]               = "0";
          $gdh["O_G_FREE03"]               = "0";
          $gdh["O_G_FREE04"]               = "0";
          $gdh["O_G_FREE05"]               = "0";
          $gdh["O_G_FREE06"]               = "0";
          $gdh["O_G_FREE07"]               = "0";
          $gdh["O_G_FREE08"]               = "0";
          $gdh["O_G_FREE09"]               = "0";
          $gdh["O_G_FREE10"]              = "0";
          $gdh["O_G_FREE11"]              = "0";
          $gdh["O_G_FREE12"]              = "0";
          $gdh["O_G_FREE13"]              = "0";
          $gdh["O_G_FREE14"]              = "0";
          $gdh["O_G_FREE15"]              = "0";
          $gdh["O_G_FREE16"]              = "0";
          $gdh["O_G_FREE17"]              = "0";
          $gdh["O_G_FREE18"]              = "0";
          $gdh["O_G_FREE19"]              = "0";
          $gdh["O_G_FREE20"]              = "0";
          $gdh["O_G_FREE21"]              = "0";
          $gdh["O_G_FREE22"]              = "0";
          $gdh["O_G_FREE23"]              = "0";
          $gdh["O_G_FREE24"]              = "0";
          $gdh["O_G_FREE25"]              = "0";
          $gdh["O_G_FREE26"]              = "0";
          $gdh["O_G_FREE27"]              = "0";
          $gdh["O_G_FREE28"]              = "0";
          $gdh["O_G_FREE29"]              = "0";
          $gdh["O_G_FREE30"]              = "0";
          $gdh["O_G_FREE31"]              = "0";
          $gdh["O_G_FREE32"]              = "0";
          $gdh["O_G_FREE33"]              = "0";
          $gdh["O_G_FREE34"]              = "0";
          $gdh["O_G_FREE35"]              = "0";
          $gdh["O_G_FREE36"]              = "0";
          $gdh["O_G_FREE37"]              = "0";
          $gdh["O_G_FREE38"]              = "0";
          $gdh["O_G_FREE39"]              = "0";
          $gdh["O_G_FREE40"]              = "0";
          $gdh["O_G_FREE41"]              = "0";
          $gdh["O_G_FREE42"]              = "0";
          $gdh["O_G_FREE43"]              = "0";
          $gdh["O_G_FREE44"]              = "0";
          $gdh["O_G_FREE45"]              = "0";
          $gdh["O_G_FREE46"]              = "0";
          $gdh["O_G_FREE47"]              = "0";
          $gdh["O_G_FREE48"]              = "0";
          $gdh["O_G_FREE49"]              = "0";
          $gdh["O_G_FREE50"]              = "0";

          if($tg_id == "kekkannikusyu"):
            $pd["CARD_OPEN"] = "1";
            $pdh["CARD_OPEN"] = "1";
          endif;
          $gd = (object)$gd;
          $gdh = (object)$gdh;
          $md = (object)$md;
          $mdh = (object)$mdh;
          $pd = (object)$pd;
          $pdh = (object)$pdh;
          break;
        case 'div_confirm':
          $p_id = $_SESSION['arrSession']->P_ID;
          $tg_id = $_SESSION['arrSession']->TG_ID;
          $m_id = $_SESSION['arrSession']->M_ID;
          $dataMember = $this->memberDetails($postid, $m_id,$tg_id);
          $_SESSION['dataBeforeUpdate'] = $dataMember;
          $pd = $dataMember->D_PERSONAL;
          $sd = $dataMember->D_AFFILIATION;
          $md = $dataMember->D_MEMBER;
          $gd = $dataMember->D_ORGANIZATION;
          $regist_name   = "";
          $confirm_name  = ($flg_is_shortcode)?get_post_meta($postid,"confirm_name",true):get_option('confirm_name');
          $tg_id      = ($flg_is_shortcode)?get_post_meta($postid,"member_meta_group_id",true):get_option('nakama-member-group-id');
          $forward_mail  = ($flg_is_shortcode)?get_post_meta($postid,"mail_address",true):get_option('mail_address');
          $set_lg_g_id   = ($flg_is_shortcode)?get_post_meta($postid,"set_lg_g_id",true):get_option('set_lg_g_id');
          $set_g_id      = ($flg_is_shortcode)?get_post_meta($postid,"set_g_id",true):get_option('set_g_id');
          $merumaga_flg = ($flg_is_shortcode)?get_post_meta($postid,"merumaga_flg",true):get_option('merumaga_flg');
          $merumaga_file = ($flg_is_shortcode)?get_post_meta($postid,"",true):get_option('');
          $gInputOpen = ($flg_is_shortcode)?get_post_meta($postid,"input_open_end",true):get_option('input_open_end');
          $ReleaseDisp = ($flg_is_shortcode)?get_post_meta($postid,"ReleaseDisp_end",true):get_option('ReleaseDisp_end');
          $entry_setting3 = ($flg_is_shortcode)?get_post_meta($postid,"entry_setting3_end",true):get_option("entry_setting3_end");
          $YearsSelect = ($flg_is_shortcode)?get_post_meta($postid,"entry_setting2_end",true):get_option("entry_setting2_end");
          $chg = 1;
          break;
        default:
          break;
      }
      $GLOBALS['gInputOpen'] = $gInputOpen;
      $GLOBALS['regist_name'] = $regist_name;
      $GLOBALS['confirm_name'] = $confirm_name;
      $GLOBALS['forward_mail'] = $forward_mail;
      $GLOBALS['set_lg_g_id'] = $set_lg_g_id;
      $GLOBALS['set_g_id'] = $set_g_id;
      $GLOBALS['merumaga_flg'] = $merumaga_flg;
      $GLOBALS['merumaga_file'] = $merumaga_file;
      $GLOBALS['gd'] = $gd;
      $GLOBALS['pd'] = $pd;
      $GLOBALS['md'] = $md;
      $GLOBALS['sd'] = $sd;
      $GLOBALS['gdh'] = array();
      $GLOBALS['pdh'] = array();
      $GLOBALS['mdh'] = array();
      $GLOBALS['sdh'] = array();
      $GLOBALS['g_chg'] = $chg;
      $GLOBALS['ReleaseDisp'] = $ReleaseDisp;
      $GLOBALS['chg'] = $chg;
      $GLOBALS['tg_id'] = $tg_id;
      $arTitle = $this->SelectTitleN($postid, $tg_id);
      $GLOBALS['arTitle'] = $arTitle;
      $GLOBALS['entry_setting3'] = $entry_setting3;
      $GLOBALS['YearsSelect'] = $YearsSelect;
      $GLOBALS['p_id'] = $p_id;
      $GLOBALS['g_id'] = $g_id;
      $GLOBALS['postid'] = $postid;
      $disp_entry_filename;
      $objText;

      $itemArray;
      $itemArrayIndex;
      $default;
      $explanation;
      $explanationFile;

      $dispIni;
      $radioValue;
      $readOnlyFlg;

      $connectionFlg = '';
      $GroupingFlg;

      if(empty($ini_file))
        $disp_entry_filename = __ROOT__ . "/settingform/ini/disp_entry.ini";
      else 
        $disp_entry_filename = __ROOT__ . "/settingform/ini/".$ini_file;
      $GroupingFlg = true;
      // $objText = file_get_contents($disp_entry_filename, true);
      $objText = $this->convertFile($disp_entry_filename);
      $objText = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $objText);
      $input_table = ($entry_setting3 == 1)?"input_table":"input_table_noline";
      if($gInputOpen == "1"):
        echo "<table id='table_setting' class='".$input_table."' align='center' cellspacing='0' cellpadding='3'>";
      else:
        echo "<table id='table_setting' class='".$input_table."' align='center' cellspacing='0' cellpadding='3'>";
      endif;
      if($entry_setting3 == 1):
        echo "<tr>";
        $colspan = ($GroupingFlg)?'colspan = 2':'';
        echo "<td class='RegField'".$colspan.">項目名</td>";

        echo "<td class='RegField'>記入欄</td>";

        if($gInputOpen == "1"):
          echo "<td class='RegField'>公開設定</td>";
        endif;
        echo "</tr>";
      endif;

      $ItemRowCnt = 0;
      $ini_array = preg_split('/\r\n|\r|\n/', $objText);
      $radioValue = '';
      $readOnlyFlg = false;
      $default = '';
      $explanation = '';
      $dispIni = '';
      $explanationFile = '';
      $disp = false;
      foreach ($ini_array as $key => $item) {
        $explanation = '';
        $readOnlyFlg = false;
        if($item != ""):
          $ItemRowCnt = $ItemRowCnt + 1;
        endif;
        // ■空白の場合は何も行わない
        if($item == ''):
        // ■HIDDENの場合
        elseif(trim($item) == "#HIDDEN"):
          $disp = false;
          $item = explode(",", $item);
        elseif(trim($item) == "#通信欄"):
          $disp = false;
          $connectionFlg = 1;
          $widthTd = ($gInputOpen == "1")?'900':'800';
          echo "</table><br>";
          echo "<table width='".$widthTd."' border='0' cellspacing='0' cellpadding='0' style='border: none!important;'><tr>";
          echo "<td align='left' class='font90b' style='border: none!important;'>通信欄（事務局行き）</td>";
          echo "</tr></table>";
          echo "<table class='m_connect_end' width='".$widthTd."' align='center' border='0' cellspacing='0' cellpadding='5'>";
          echo "<tr>";
          echo '<td class="RegValue" colspan="3">';
          $item = explode(",", $item);
        elseif(trim($item) == "CONNECTION"):
          if($connectionFlg == 1):
            $this->SetConnection();
            echo "</td></tr></table><br>";
          endif;
          $item = explode(",", $item);
        // ■タイトルの場合
        elseif(substr($item, 0, 1) == "#"):
          $gColSpan = 1;
          $disp = true;
          if($disp):
            $itemname;
            $itemname = explode(",", $item);
            $itemname = $itemname['0'];
            if(($chg == 1) || (isset($_SESSION["LOGIN_STATUS"])?$_SESSION["LOGIN_STATUS"]:"" == NAK_LOGIN_MEMBER)):
            else:
              echo "<tr>";
            endif;
          endif;
          $item = explode(",", $item);
          if(!empty($item['1']) && $item['1'] != ""):
            $regGroupName = (!empty(str_replace("#", "", $item['0']))) ? str_replace("#", "", $item['0']) : "&nbsp;";
            if($regGroupName !== ""):
              $gColSpan = 1;
              $colspan = ($gInputOpen == 1)?"3":"2";
              if($entry_setting3 == 1):
                echo "<td class='RegGroup' rowspan='".($item['1'])."' width='100'>".$regGroupName."</td>";
              else:

                echo "<td class='RegGroup_noline' colspan='".$colspan."' style='font-weight:bold;font-size:16pt;'>".$regGroupName."</td>";
                echo "<input type='hidden' class='rowspanNoline' value='".($item['1'])."'>";
                echo '</tr>';
              endif;
              $item['1'] = "";
            else:
              if($GroupingFlg == true):
                $gColSpan = 2;
              else:
                $gColSpan = 1;
              endif;
            endif;
          endif;
        // ■上記以外は画面表示を行う
        else:
          $item = explode(",", $item);
          $itemArray = $item;
          $itemArrayIndex = count($itemArray);
          $GLOBALS['flag_checkbox'] = false;
          if($itemArrayIndex < 2)
            $default = '';
          if($itemArrayIndex > 2):
            if($itemArrayIndex > 2):
              if($itemArray['2'] == "1"):
                $radioValue = $itemArray['3'];
              elseif($itemArray['2'] == "2"):
                $GLOBALS['flag_checkbox'] = true;
                $radioValue = "[@@@]" . $itemArray['3'];
              else:
                $radioValue = '';
              endif;
            endif;
            if($itemArrayIndex > 3):
              if(!empty($itemArray['4']) && $itemArray['4'] == "1"):
                $readOnlyFlg = true;
              else:
                $readOnlyFlg = false;
              endif;
            endif;

            if($itemArrayIndex > 4):
              $default = !empty($itemArray['5'])?$itemArray['5']:"";
            endif;

            if($itemArrayIndex > 5):
              $explanation = !empty($itemArray['6'])?$itemArray['6']:"";
            endif;

            if($itemArrayIndex > 6):
              if(empty($itemArray['7']))
                $itemArray['7']  = "";
              $dispIni = !empty($this->getdispIniValue($itemArray['7']))?$this->getdispIniValue($itemArray['7']):"";
            endif;

            if($itemArrayIndex > 7):
              $explanationFile = !empty($itemArray['8'])?$itemArray['8']:"";
              if($explanationFile !== ''):
                $explanationFile = ROOT_PATH . "/nakama/html/" . $explanationFile;
              endif;
            endif;
          endif;
          $field_name_setting = "";
          if($itemArrayIndex > 8):
            if(!empty($itemArray['9'])){
              $field_name_setting = $itemArray['9'];
            }
          endif;
          $GLOBALS['field_name_setting'] = $field_name_setting;
          $GLOBALS['gColSpan'] = $gColSpan;

          if($disp):
            $must = (isset($itemArray[1]) && trim($itemArray[1]) == "1")?true:false;
            $this->SelectItemName($tg_id, trim($itemArray[0]), $disp, $must, $radioValue, $readOnlyFlg, $default, $explanation, $dispIni, $explanationFile);
            echo '</tr>';
          else:
            $this->SelectItemName($tg_id, trim($item[0]), $disp, false, $radioValue, $readOnlyFlg, $default, $explanation, $dispIni, $explanationFile);
          endif;
        endif;
      }
      if($ItemRowCnt == 0):
        echo "<center>";
        echo "<font color='red' class='w_60px'>■設定ERROR■</font><br>";
        echo "<font color='red'>設定ファイルが存在しません。</font>";
        echo "</center>";
        echo "<script type='text/javascript'>". "\n";
        echo "alert('■設定エラー\n設定ファイルが存在しません。\n「ＯＫ」ボタンを押すとウィンドウを閉じます。');". "\n";
        echo "window.close();". "\n";
        echo "</script>". "\n";
      endif;
      $this->SetHiddenMemberData();
      $this->EndAllData($ReleaseDisp);
      $this->SetchkMustInput($disp_entry_filename); ?>
<?php
  }
  public function SetchkMustInput($disp_entry_filename){
    $objText = file_get_contents($disp_entry_filename, true);
    $objText = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $objText);
    $ini_array = preg_split('/\r\n|\r|\n/', $objText);
    echo "<script type='text/javascript'>";
    echo "function chkMustInput(){";
    echo "var i;";

    foreach ($ini_array as $key => $item) {
      if($item == ""):
      elseif($item == "#HIDDEN"):
      elseif(substr($item, 0, 1) == "#"):
      elseif($item == "CONNECTION"):
      else:
        $item = explode(",", $item);
        if(count($item) > 2):
          if($item[1] == "1" && $item[2] == "2" && substr($item[0], 0, 9) == "P_C_FREE"):
            echo " if(mainForm.".$item[0]." != undefined){";
            echo "   if(mainForm.".$item[0].".length != undefined){";
            echo "     i = 0;";
            echo "     for(var count = 0; count < mainForm.".$item[0].".length; count++){";
            echo "       if(mainForm.".$item[0]."[count].type == 'checkbox'){";
            echo "         if((mainForm.".$item[0]."[count].checked == true){";
            echo "           i = i + 1;";
            echo "         }";
            echo "       }";
            echo "     }";
            echo "     if(i == 0){";
            echo "       alert('".$this->SelectDispName($item[0])."を１つ以上選択してください。');";
            echo "       mainForm.".$item[0]."[0].focus();";
            echo "       return false;";
            echo "     }";
            echo "   }else if(mainForm.".$item[0].".type == 'checkbox'){";
            echo "     if((mainForm.".$item[0].".checked == false){";
            echo "       alert('".$this->SelectDispName($item[0])."を選択してください。');";
            echo "       mainForm.".$item[0].".focus();";
            echo "       return false;";
            echo "     }";
            echo "   }";
            echo " }";
          endif;
        endif;

        if(!empty($item[1]) && $item[1] == "1" && !($item[0] == "P_O_AFFILIATION" || $item[0] == "P_O_OFFICIAL" ||
              $item[0] == "S_AFFILIATION_NAME2" || $item[0] == "S_OFFICIAL_POSITION2" ||
              $item[0] == "P_P_BLOOD" || $item[0] == "M_CLAIM_CLS" ||
              $item[0] == "G_IMG" || $item[0] == "P_C_IMG" ||
              $item[0] == "P_C_IMG2" || $item[0] == "P_C_IMG3" )):
          echo "if(mainForm.".$item[0]." !== undefined){";
          echo " if(mainForm.".$item[0].".length == undefined){";
          echo "  if(mainForm.".$item[0].".type !== 'hidden'){";
          echo "   if(mainForm.".$item[0].".type !== 'select-one'){";
          echo "    if(IsNull(mainForm.".$item[0].".value, ".$this->SelectDispName($item[0]).")){";
          echo "     mainForm.".$item[0].".select();";
          echo "     mainForm.".$item[0].".focus();";
          echo "     return false;";
          echo "    }";
          echo "   }else{";
          echo "    if(IsNull(mainForm.".$item[0].".value, ".$this->SelectDispName($item[0]).")){";
          echo "     mainForm.".$item[0].".focus();";
          echo "     return false;";
          echo "    }";
          echo "   }";
          echo "  }else{";
          echo "   if(mainForm.".$item[0]."_1 !== undefined){";
          echo "    if(mainForm.".$item[0]."_1.type !== 'hidden'){";
          echo "     if(IsNull(mainForm.".$item[0]."_1.value, ".$this->SelectDispName($item[0]).")){";
          echo "      mainForm.".$item[0]."_1.select();";
          echo "      mainForm.".$item[0]."_1.focus();";
          echo "      return false;";
          echo "     }";
          echo "    }";
          echo "   }else{";
          echo "    if(mainForm.".$item[0]."_u !== undefined){";
          echo "     if(mainForm.".$item[0]."_u.type !== 'hidden'){";
          echo "      if(IsNull(mainForm.".$item[0]."_u.value, ".$this->SelectDispName($item[0]).")){";
          echo "       mainForm.".$item[0]."_u.select();";
          echo "       mainForm.".$item[0]."_u.focus();";
          echo "       return false;";
          echo "      }";
          echo "     }";
          echo "    }else{";
          echo "     if(mainForm.".$item[0]."_YEAR !== undefined){";
          echo "      if(mainForm.".$item[0]."_YEAR.type != 'hidden'){";
          echo "       if(IsNull(mainForm.".$item[0]."_YEAR.value, ".$this->SelectDispName($item[0]).")){";
          echo "        mainForm.".$item[0]."_YEAR.select();";
          echo "        mainForm.".$item[0]."_YEAR.focus();";
          echo "        return false;";
          echo "       }";
          echo "      }";
          echo "     }else{";
          echo "      if(mainForm.".str_replace("_DATE", "", $item[0])."_YEAR !== undefined){";
          echo "       if(mainForm.".str_replace("_DATE", "", $item[0])."_YEAR.type !== 'hidden'){";
          echo "        if(IsNull(mainForm.".str_replace("_DATE", "", $item[0])."_YEAR.value, ".$this->SelectDispName($item[0]).")){";
          echo "         mainForm.".str_replace("_DATE", "", $item[0])."_YEAR.select();";
          echo "         mainForm.".str_replace("_DATE", "", $item[0])."_YEAR.focus();";
          echo "         return false;";
          echo "        }";
          echo "       }";
          echo "      }else{";
          echo "       if(mainForm.".$item[0]."_SEL !== undefined){";
          echo "        if(mainForm.".$item[0]."_SEL.type != 'hidden'){";
          echo "         if(IsNull(mainForm.".$item[0]."_SEL.value, ".$this->SelectDispName($item[0]).")){";
          echo "          mainForm.".$item[0]."_SEL.focus();";
          echo "          return false;";
          echo "         }";
          echo "        }";
          echo "       }";
          echo "      }";
          echo "     }";
          echo "    }";
          echo "   }";
          echo "  }";
          echo " }";
          echo "}";
        endif;
      endif;
    }
    echo "  return true;";
    echo "}";
    echo "</script>";
}
  function SetHiddenMemberData(){
    $md = $GLOBALS['md'];
    $pd = $GLOBALS['pd'];

    echo '
      <input type="hidden" name="M_P_ID" value="'.(!empty($md->P_ID)?$md->P_ID:"").'">
      <input type="hidden" name="M_A_ID" value="'.(!empty($md->A_ID)?$md->A_ID:"").'">
      <input type="hidden" name="M_C_ID" value="'.(!empty($md->C_ID)?$md->C_ID:"").'">
      <input type="hidden" name="feestatus_tg_id" value="'.(!empty($md->TG_ID)?$md->TG_ID:"").'">
      <input type="hidden" name="feestatus_p_id" value="'.(!empty($pd->P_ID)?$pd->P_ID:"").'">
      <input type="hidden" name="feestatus_c_name" value="'.(!empty($pd->C_FNAME)?$pd->C_FNAME:"").' '.(!empty($pd->C_LNAME)?$pd->C_LNAME:"").'">
      <table style="display:none;">
        <tr>
          <td id="m_claimNewNeed"></td>
          <td id="m_contactNewNeed"></td>
          <td id="m_mainFeeInfo"></td>
          <td id="m_claimClassNeed"></td>
        </tr>
      </table>
    ';
  }
  function EndAllData($ReleaseDisp){
    $chg = $GLOBALS['chg'];
    $postid = $GLOBALS['postid'];
    echo '<br>
      <center>';
          if($chg == "1"):
            
              echo '<input type="button" class="base_button regist_confirm_btn" value="更　新" onClick="OnConfirm();">&nbsp;&nbsp;';
          else:
                echo '<input type="button" class="base_button regist_confirm_btn" value="登　録" onClick="OnConfirm();">&nbsp;&nbsp;';
          endif;
          
          if($chg == "1"):
              if($ReleaseDisp == 1):
                echo '<input type="button" class="base_button" value="登録解除" onClick="OnRelease();">';
              endif;
                  echo '<input type="checkbox" name="m_saveHist" style="display: none;" value="1" checked>';
          endif;
      echo '</center>';

      echo '<input type="checkbox" name="useRegisteredGid" style="display:none;">
      <input type="checkbox" name="useRegisteredPid" style="display:none;">
      <input type="button"   name="useGroupInfo"     style="display:none;">';
  }

    function SelectItemName($tgid, $name, $disp, $must, $radioValue, $readOnlyFlg, $default, $explanation, $dispIni, $explanationFile){
	$entry_setting3 = $GLOBALS['entry_setting3'];
  	$RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  	$RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
      $_SESSION['LOGIN_TOPGID'] = $tgid;
      $pd = $GLOBALS['pd'];
      $gd = $GLOBALS['gd'];
      $md = $GLOBALS['md'];
      $tg_id = $GLOBALS['tg_id'];
      switch($name){
          case "G_G_ID": $this->SetG_G_ID($disp, $must, $explanation); break;
          case "G_NAME": $this->SetG_NAME($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_NAME_KN": $this->SetG_NAME_KN($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_NAME_EN": $this->SetG_NAME_EN($disp, $must, $explanation); break;
          case "G_ADR_EN": $this->SetG_ADR_EN($disp, $must, $explanation); break;
          case "G_LNG_MODE": $this->SetG_LNG_MODE($disp, $must, $explanation); break;
          case "P_LNG_MODE": $this->SetG_LNG_MODE($disp, $must, $explanation); break;
          case "M_CONTACT_LNG_MODE": $this->SetM_CONTACT_LNG_MODE($disp, $must, $explanation); break;
          case "M_BILLING_LNG_MODE": $this->SetM_BILLING_LNG_MODE($disp, $must, $explanation); break;
          case "G_USER_ID": $this->SetG_USER_ID($disp, $must, $explanation); break;
          case "G_NAME_AN": $this->SetG_NAME_AN($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_INDUSTRY_CD": $this->SetG_INDUSTRY_CD($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_INDUSTRY_NM": $this->SetG_INDUSTRY_NM($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_URL": $this->SetG_URL($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_P_URL": $this->SetG_P_URL($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_EMAIL": $this->SetG_EMAIL($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_EMAIL2": $this->SetG_EMAIL2($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_CC_EMAIL": $this->SetG_CC_EMAIL($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_TEL": $this->SetG_TEL($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_FAX": $this->SetG_FAX($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_FOUND_DATE": $this->SetG_FOUND_DATE($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_SETTLE_MONTH": $this->SetG_SETTLE_MONTH($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_CAPITAL": $this->SetG_CAPITAL($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_REPRESENTATIVE_NM": $this->SetG_REPRESENTATIVE_NM($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_REPRESENTATIVE_KN": $this->SetG_REPRESENTATIVE_KN($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_POST": $this->SetG_POST($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_STA": $this->SetG_STA($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_ADR": $this->SetG_ADR($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_ADR2": $this->SetG_ADR2($disp, $must, $readOnlyFlg, $explanation); break;
          case "G_ADR3": $this->SetG_ADR3($disp, $must, $readOnlyFlg, $explanation); break;
          case "G_IMG": $this->SetG_IMG($disp, $must, $readOnlyFlg, $explanation); break;
          case "G_APPEAL": $this->SetG_APPEAL($disp, $must, $readOnlyFlg, $explanation); break;
          case "G_LOGO": $this->SetG_LOGO($disp, $must, $readOnlyFlg, $explanation); break;
          case "G_FREE01": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "1" , "１"  , !empty($gd->G_FREE01)?$gd->G_FREE01:"" , $gd->O_G_FREE01 , !empty($gd->G_FREE01)?$gd->G_FREE01:"" , $gd->O_G_FREE01 , $explanation, $dispIni); break;
          case "G_FREE02":
            if($tg_id == 'TOPGID_NITIGANKYO'){
              $this->SetG_KEYWORD_Optician($disp, $must, $radioValue, $readOnlyFlg, $default, "2" , "２", !empty($gd->G_FREE02)?$gd->G_FREE02:"" , $gd->O_G_FREE02 , !empty($gd->G_FREE02)?$gd->G_FREE02:"" , $gd->O_G_FREE02 , $explanation, $dispIni);
            }else{
              $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "2" , "２", !empty($gd->G_FREE02)?$gd->G_FREE02:"" , $gd->O_G_FREE02 , !empty($gd->G_FREE02)?$gd->G_FREE02:"" , $gd->O_G_FREE02 , $explanation, $dispIni);
            }
            break;
          case "G_FREE03":
            if($tg_id == 'TOPGID_NITIGANKYO'){
              $this->SetG_KEYWORD_Ages($disp, $must, $radioValue, $readOnlyFlg, $default, "3" , "３", !empty($gd->G_FREE03)?$gd->G_FREE03:"" , $gd->O_G_FREE03 , !empty($gd->G_FREE03)?$gd->G_FREE03:"" , $gd->O_G_FREE03 , $explanation, $dispIni);
            }else{
              $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "3" , "３", !empty($gd->G_FREE03)?$gd->G_FREE03:"" , $gd->O_G_FREE03 , !empty($gd->G_FREE03)?$gd->G_FREE03:"", $gd->O_G_FREE03 , $explanation, $dispIni);
            }
            break;
          case "G_FREE04":
            if($tg_id == 'TOPGID_NITIGANKYO'){
              $this->SetG_KEYWORD_Holiday($disp, $must, $radioValue, $readOnlyFlg, $default, "4" , "４", !empty($gd->G_FREE04)?$gd->G_FREE04:"" , $gd->O_G_FREE04 , !empty($gd->G_FREE04)?$gd->G_FREE04:"" , $gd->O_G_FREE04 , $explanation, $dispIni);
            }else{
              $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "4" , "４", !empty($gd->G_FREE04)?$gd->G_FREE04:"" , $gd->O_G_FREE04 , !empty($gd->G_FREE04)?$gd->G_FREE04:"" , $gd->O_G_FREE04 , $explanation, $dispIni);
            }
            break;
          case "G_FREE05":
            if($tg_id == 'TOPGID_NITIGANKYO'){
              $this->SetG_KEYWORD_ShopHour($disp, $must, $readOnlyFlg, $default, "5" , "５", !empty($gd->G_FREE05)?$gd->G_FREE05:"" , $gd->O_G_FREE05 , !empty($gd->G_FREE05)?$gd->G_FREE05:"" , $gd->O_G_FREE05 , $explanation, $dispIni);
            }else{
              $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "5" , "５", !empty($gd->G_FREE05)?$gd->G_FREE05:"" , $gd->O_G_FREE05 , !empty($gd->G_FREE05)?$gd->G_FREE05:"" , $gd->O_G_FREE05 , $explanation, $dispIni);
            }
            break;
          case "G_FREE06":
            if($tg_id == "TOPGID_NITIGANKYO"){
              $this->SetG_KEYWORD_Parking($disp, $must, $radioValue, $readOnlyFlg, $default, "6" , "６", !empty($gd->G_FREE06)?$gd->G_FREE06:"" , $gd->O_G_FREE06 , !empty($gd->G_FREE06)?$gd->G_FREE06:"" , $gd->O_G_FREE06 , $explanation, $dispIni);
            }else{
              $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "6" , "６", !empty($gd->G_FREE06)?$gd->G_FREE06:"" , $gd->O_G_FREE06 , !empty($gd->G_FREE06)?$gd->G_FREE06:"" , $gd->O_G_FREE06 , $explanation, $dispIni);
            }
            break;
          case "G_FREE07":
            if($tg_id == 'TOPGID_NITIGANKYO'){
              $this->SetG_KEYWORD_Frame_Home($disp, $must, $radioValue, $readOnlyFlg, $default, "7" , "７", !empty($gd->G_FREE07)?$gd->G_FREE07:"" , $gd->O_G_FREE07 , !empty($gd->G_FREE07)?$gd->G_FREE07:"" , $gd->O_G_FREE07 , $explanation, $dispIni);
            }else{
              $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "7" , "７", !empty($gd->G_FREE07)?$gd->G_FREE07:"" , $gd->O_G_FREE07 , !empty($gd->G_FREE07)?$gd->G_FREE07:"" , $gd->O_G_FREE07 , $explanation, $dispIni);
            }
            break;
          case "G_FREE08":
            if($tg_id == 'TOPGID_NITIGANKYO'){
              $this->SetG_KEYWORD_Frame_Abroad($disp, $must, $radioValue, $readOnlyFlg, $default, "8" , "８", !empty($gd->G_FREE08)?$gd->G_FREE08:"" , $gd->O_G_FREE08 , !empty($gd->G_FREE08)?$gd->G_FREE08:"" , $gd->O_G_FREE08 , $explanation, $dispIni);
            }else{
              $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "8" , "８", !empty($gd->G_FREE08)?$gd->G_FREE08:"" , $gd->O_G_FREE08 , !empty($gd->G_FREE08)?$gd->G_FREE08:"" , $gd->O_G_FREE08 , $explanation, $dispIni);
            }
            break;
          case "G_FREE09":
            if($tg_id == 'TOPGID_NITIGANKYO'){
              //得意な取扱いレンズ
              $this->SetG_KEYWORD_Lens($disp, $must, $radioValue, $readOnlyFlg, $default, "9" , "９", !empty($gd->G_FREE09)?$gd->G_FREE09:"" , $gd->O_G_FREE09 , !empty($gd->G_FREE09)?$gd->G_FREE09:"" , $gd->O_G_FREE09 , $explanation, $dispIni);
            }else{
              $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "9" , "９", !empty($gd->G_FREE09)?$gd->G_FREE09:"" , $gd->O_G_FREE09 , !empty($gd->G_FREE09)?$gd->G_FREE09:"" , $gd->O_G_FREE09 , $explanation, $dispIni);
            }
            break;
          case "G_FREE10":
            if($tg_id == 'TOPGID_NITIGANKYO'){
              //お店の特徴
              $this->SetG_KEYWORD_Distinct($disp, $must, $radioValue, $readOnlyFlg, $default, "10", "１０" , !empty($gd->G_FREE10)?$gd->G_FREE10:"" , $gd->O_G_FREE10 , !empty($gd->G_FREE10)?$gd->G_FREE10:"" , $gd->O_G_FREE10, $explanation, $dispIni);
            }else{
              $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "10", "１０" , !empty($gd->G_FREE10)?$gd->G_FREE10:"" , $gd->O_G_FREE10 , !empty($gd->G_FREE10)?$gd->G_FREE10:"" , $gd->O_G_FREE10, $explanation, $dispIni);
            }
            break;
          case "G_FREE11":
            if($tg_id == 'TOPGID_NITIGANKYO'){
              //平均予算（通常）
              $this->SetG_KEYWORD_BudgetNormal($disp, $must, $radioValue, $readOnlyFlg, $default, "11", "１１" ,!empty($gd->G_FREE11)?$gd->G_FREE11:"" , $gd->O_G_FREE11 , !empty($gd->G_FREE11)?$gd->G_FREE11:"" , $gd->O_G_FREE11, $explanation, $dispIni);
            }else{
              $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "11", "１１" , !empty($gd->G_FREE11)?$gd->G_FREE11:"" , $gd->O_G_FREE11 , !empty($gd->G_FREE11)?$gd->G_FREE11:"" , $gd->O_G_FREE11, $explanation, $dispIni);
            }
            break;
          case "G_FREE12":
            if($tg_id == 'TOPGID_NITIGANKYO'){
              //平均予算（遠近両用）
              $this->SetG_KEYWORD_BudgetDual($disp, $must, $radioValue, $readOnlyFlg, $default, "12", "１２" , !empty($gd->G_FREE12)?$gd->G_FREE12:"" , $gd->O_G_FREE12 , !empty($gd->G_FREE12)?$gd->G_FREE12:"" , $gd->O_G_FREE12, $explanation, $dispIni);
            }else{
              $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "12", "１２" , !empty($gd->G_FREE12)?$gd->G_FREE12:"" , $gd->O_G_FREE12 , !empty($gd->G_FREE12)?$gd->G_FREE12:"" , $gd->O_G_FREE12, $explanation, $dispIni);
            }
            break;
          case "G_FREE13":
            if($tg_id == 'TOPGID_NITIGANKYO'){
              //平均予算（その他）
              $this->SetG_KEYWORD_BudgetOthers($disp, $must, $radioValue, $readOnlyFlg, $default, "13", "１３" , !empty($gd->G_FREE13)?$gd->G_FREE13:"" , $gd->O_G_FREE13 , !empty($gd->G_FREE13)?$gd->G_FREE13:"" , $gd->O_G_FREE13, $explanation, $dispIni);
            }else{
              $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "13", "１３" , !empty($gd->G_FREE13)?$gd->G_FREE13:"" , $gd->O_G_FREE13 , !empty($gd->G_FREE13)?$gd->G_FREE13:"" , $gd->O_G_FREE13, $explanation, $dispIni);
            }
           break;
          case "G_FREE14":
            if($tg_id == 'TOPGID_NITIGANKYO'){
              //立地
              $this->SetG_KEYWORD_Location($disp, $must, $radioValue, $readOnlyFlg, $default, "14", "１４" , !empty($gd->G_FREE14)?$gd->G_FREE14:"" , $gd->O_G_FREE14 , !empty($gd->G_FREE14)?$gd->G_FREE14:"" , $gd->O_G_FREE14, $explanation, $dispIni);
            }else{
              $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "14", "１４" , !empty($gd->G_FREE14)?$gd->G_FREE14:"" , $gd->O_G_FREE14 , !empty($gd->G_FREE14)?$gd->G_FREE14:"" , $gd->O_G_FREE14, $explanation, $dispIni);
            }
            break;

          case "G_FREE15":
            if($tg_id == 'TOPGID_NITIGANKYO'){
             $this->SetG_KEYWORD_Keyword($disp, $must, $radioValue, $readOnlyFlg, $default, "15", "１５" , !empty($gd->G_FREE15)?$gd->G_FREE15:"" , $gd->O_G_FREE15 , !empty($gd->G_FREE15)?$gd->G_FREE15:"" , $gd->O_G_FREE15, $explanation, $dispIni);
            }
            else{
             $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "15", "１５", !empty($gd->G_FREE15)?$gd->G_FREE15:"" , $gd->O_G_FREE15 , !empty($gd->G_FREE15)?$gd->G_FREE15:"" , $gd->O_G_FREE15, $explanation, $dispIni);
            }
            break;
          case "G_FREE16": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "16", "１６", !empty($gd->G_FREE16)?$gd->G_FREE16:"" , $gd->O_G_FREE16 , !empty($gd->G_FREE16)?$gd->G_FREE16:"" , $gd->O_G_FREE16, $explanation, $dispIni); break;
          case "G_FREE17": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "17", "１７", !empty($gd->G_FREE17)?$gd->G_FREE17:"" , $gd->O_G_FREE17 , !empty($gd->G_FREE17)?$gd->G_FREE17:"" , $gd->O_G_FREE17, $explanation, $dispIni); break;
          case "G_FREE18": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "18", "１８", !empty($gd->G_FREE18)?$gd->G_FREE18:"" , $gd->O_G_FREE18 , !empty($gd->G_FREE18)?$gd->G_FREE18:"" , $gd->O_G_FREE18, $explanation, $dispIni); break;
          case "G_FREE19": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "19", "１９", !empty($gd->G_FREE19)?$gd->G_FREE19:"" , $gd->O_G_FREE19 , !empty($gd->G_FREE19)?$gd->G_FREE19:"" , $gd->O_G_FREE19, $explanation, $dispIni); break;
          case "G_FREE20": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "20", "２０", !empty($gd->G_FREE20)?$gd->G_FREE20:"" , $gd->O_G_FREE20 , !empty($gd->G_FREE20)?$gd->G_FREE20:"" , $gd->O_G_FREE20, $explanation, $dispIni); break;
          case "G_FREE21": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "21", "２１", !empty($gd->G_FREE21)?$gd->G_FREE21:"" , $gd->O_G_FREE21 , !empty($gd->G_FREE21)?$gd->G_FREE21:"" , $gd->O_G_FREE21, $explanation, $dispIni); break;
          case "G_FREE22": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "22", "２２", !empty($gd->G_FREE22)?$gd->G_FREE22:"" , $gd->O_G_FREE22 , !empty($gd->G_FREE22)?$gd->G_FREE22:"" , $gd->O_G_FREE22, $explanation, $dispIni); break;
          case "G_FREE23": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "23", "２３", !empty($gd->G_FREE23)?$gd->G_FREE23:"" , $gd->O_G_FREE23 , !empty($gd->G_FREE23)?$gd->G_FREE23:"" , $gd->O_G_FREE23, $explanation, $dispIni); break;
          case "G_FREE24": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "24", "２４", !empty($gd->G_FREE24)?$gd->G_FREE24:"" , $gd->O_G_FREE24 , !empty($gd->G_FREE24)?$gd->G_FREE24:"" , $gd->O_G_FREE24, $explanation, $dispIni); break;
          case "G_FREE25": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "25", "２５", !empty($gd->G_FREE25)?$gd->G_FREE25:"" , $gd->O_G_FREE25 , !empty($gd->G_FREE25)?$gd->G_FREE25:"" , $gd->O_G_FREE25, $explanation, $dispIni); break;
          case "G_FREE26": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "26", "２６", !empty($gd->G_FREE26)?$gd->G_FREE26:"" , $gd->O_G_FREE26 , !empty($gd->G_FREE26)?$gd->G_FREE26:"" , $gd->O_G_FREE26, $explanation, $dispIni); break;
          case "G_FREE27": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "27", "２７", !empty($gd->G_FREE27)?$gd->G_FREE27:"" , $gd->O_G_FREE27 , !empty($gd->G_FREE27)?$gd->G_FREE27:"" , $gd->O_G_FREE27, $explanation, $dispIni); break;
          case "G_FREE28": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "28", "２８", !empty($gd->G_FREE28)?$gd->G_FREE28:"" , $gd->O_G_FREE28 , !empty($gd->G_FREE28)?$gd->G_FREE28:"" , $gd->O_G_FREE28, $explanation, $dispIni); break;
          case "G_FREE29": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "29", "２９", !empty($gd->G_FREE29)?$gd->G_FREE29:"" , $gd->O_G_FREE29 , !empty($gd->G_FREE29)?$gd->G_FREE29:"" , $gd->O_G_FREE29, $explanation, $dispIni); break;
          case "G_FREE30": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "30", "３０", !empty($gd->G_FREE30)?$gd->G_FREE30:"" , $gd->O_G_FREE30 , !empty($gd->G_FREE30)?$gd->G_FREE30:"" , $gd->O_G_FREE30, $explanation, $dispIni); break;
          case "G_FREE31": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "31", "３１", !empty($gd->G_FREE31)?$gd->G_FREE31:"" , $gd->O_G_FREE31 , !empty($gd->G_FREE31)?$gd->G_FREE31:"" , $gd->O_G_FREE31, $explanation, $dispIni); break;
          case "G_FREE32": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "32", "３２", !empty($gd->G_FREE32)?$gd->G_FREE32:"" , $gd->O_G_FREE32 , !empty($gd->G_FREE32)?$gd->G_FREE32:"" , $gd->O_G_FREE32, $explanation, $dispIni); break;
          case "G_FREE33": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "33", "３３", !empty($gd->G_FREE33)?$gd->G_FREE33:"" , $gd->O_G_FREE33 , !empty($gd->G_FREE33)?$gd->G_FREE33:"" , $gd->O_G_FREE33, $explanation, $dispIni); break;
          case "G_FREE34": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "34", "３４", !empty($gd->G_FREE34)?$gd->G_FREE34:"" , $gd->O_G_FREE34 , !empty($gd->G_FREE34)?$gd->G_FREE34:"" , $gd->O_G_FREE34, $explanation, $dispIni); break;
          case "G_FREE35": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "35", "３５", !empty($gd->G_FREE35)?$gd->G_FREE35:"" , $gd->O_G_FREE35 , !empty($gd->G_FREE35)?$gd->G_FREE35:"" , $gd->O_G_FREE35, $explanation, $dispIni); break;
          case "G_FREE36": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "36", "３６", !empty($gd->G_FREE36)?$gd->G_FREE36:"" , $gd->O_G_FREE36 , !empty($gd->G_FREE36)?$gd->G_FREE36:"" , $gd->O_G_FREE36, $explanation, $dispIni); break;
          case "G_FREE37": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "37", "３７", !empty($gd->G_FREE37)?$gd->G_FREE37:"" , $gd->O_G_FREE37 , !empty($gd->G_FREE37)?$gd->G_FREE37:"" , $gd->O_G_FREE37, $explanation, $dispIni); break;
          case "G_FREE38": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "38", "３８", !empty($gd->G_FREE38)?$gd->G_FREE38:"" , $gd->O_G_FREE38 , !empty($gd->G_FREE38)?$gd->G_FREE38:"" , $gd->O_G_FREE38, $explanation, $dispIni); break;
          case "G_FREE39": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "39", "３９", !empty($gd->G_FREE39)?$gd->G_FREE39:"" , $gd->O_G_FREE39 , !empty($gd->G_FREE39)?$gd->G_FREE39:"" , $gd->O_G_FREE39, $explanation, $dispIni); break;
          case "G_FREE40": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "40", "４０", !empty($gd->G_FREE40)?$gd->G_FREE40:"" , $gd->O_G_FREE40 , !empty($gd->G_FREE40)?$gd->G_FREE40:"" , $gd->O_G_FREE40, $explanation, $dispIni); break;
          case "G_FREE41": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "41", "４１", !empty($gd->G_FREE41)?$gd->G_FREE41:"" , $gd->O_G_FREE41 , !empty($gd->G_FREE41)?$gd->G_FREE41:"" , $gd->O_G_FREE41, $explanation, $dispIni); break;
          case "G_FREE42": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "42", "４２", !empty($gd->G_FREE42)?$gd->G_FREE42:"" , $gd->O_G_FREE42 , !empty($gd->G_FREE42)?$gd->G_FREE42:"" , $gd->O_G_FREE42, $explanation, $dispIni); break;
          case "G_FREE43": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "43", "４３", !empty($gd->G_FREE43)?$gd->G_FREE43:"" , $gd->O_G_FREE43 , !empty($gd->G_FREE43)?$gd->G_FREE43:"" , $gd->O_G_FREE43, $explanation, $dispIni); break;
          case "G_FREE44": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "44", "４４", !empty($gd->G_FREE44)?$gd->G_FREE44:"" , $gd->O_G_FREE44 , !empty($gd->G_FREE44)?$gd->G_FREE44:"" , $gd->O_G_FREE44, $explanation, $dispIni); break;
          case "G_FREE45": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "45", "４５", !empty($gd->G_FREE45)?$gd->G_FREE45:"" , $gd->O_G_FREE45 , !empty($gd->G_FREE45)?$gd->G_FREE45:"" , $gd->O_G_FREE45, $explanation, $dispIni); break;
          case "G_FREE46": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "46", "４６", !empty($gd->G_FREE46)?$gd->G_FREE46:"" , $gd->O_G_FREE46 , !empty($gd->G_FREE46)?$gd->G_FREE46:"" , $gd->O_G_FREE46, $explanation, $dispIni); break;
          case "G_FREE47": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "47", "４７", !empty($gd->G_FREE47)?$gd->G_FREE47:"" , $gd->O_G_FREE47 , !empty($gd->G_FREE47)?$gd->G_FREE47:"" , $gd->O_G_FREE47, $explanation, $dispIni); break;
          case "G_FREE48": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "48", "４８", !empty($gd->G_FREE48)?$gd->G_FREE48:"" , $gd->O_G_FREE48 , !empty($gd->G_FREE48)?$gd->G_FREE48:"" , $gd->O_G_FREE48, $explanation, $dispIni); break;
          case "G_FREE49": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "49", "４９", !empty($gd->G_FREE49)?$gd->G_FREE49:"" , $gd->O_G_FREE49 , !empty($gd->G_FREE49)?$gd->G_FREE49:"" , $gd->O_G_FREE49, $explanation, $dispIni); break;
          case "G_FREE50": $this->SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "50", "５０", !empty($gd->G_FREE50)?$gd->G_FREE50:"" , $gd->O_G_FREE50 , !empty($gd->G_FREE50)?$gd->G_FREE50:"" , $gd->O_G_FREE50, $explanation, $dispIni); break;

          case "G_ADD_MARKETING1": $this->SetG_ADD_MARKETING($disp, $must, "1" , "１", !empty($gd->G_MARKETING01)?$gd->G_MARKETING01:"" , !empty($gd->G_MARKETING01)?$gd->G_MARKETING01:"" , $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING2": $this->SetG_ADD_MARKETING($disp, $must, "2" , "２", !empty($gd->G_MARKETING02)?$gd->G_MARKETING02:"" , !empty($gd->G_MARKETING02)?$gd->G_MARKETING02:"" , $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING3": $this->SetG_ADD_MARKETING($disp, $must, "3" , "３", !empty($gd->G_MARKETING03)?$gd->G_MARKETING03:"" , !empty($gd->G_MARKETING03)?$gd->G_MARKETING03:"" , $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING4": $this->SetG_ADD_MARKETING($disp, $must, "4" , "４", !empty($gd->G_MARKETING04)?$gd->G_MARKETING04:"" , !empty($gd->G_MARKETING04)?$gd->G_MARKETING04:"", $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING5": $this->SetG_ADD_MARKETING($disp, $must, "5" , "５", !empty($gd->G_MARKETING05)?$gd->G_MARKETING05:"" , !empty($gd->G_MARKETING05)?$gd->G_MARKETING05:"", $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING6": $this->SetG_ADD_MARKETING($disp, $must, "6" , "６", !empty($gd->G_MARKETING06)?$gd->G_MARKETING06:"" , !empty($gd->G_MARKETING06)?$gd->G_MARKETING06:"", $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING7": $this->SetG_ADD_MARKETING($disp, $must, "7" , "７", !empty($gd->G_MARKETING07)?$gd->G_MARKETING07:"" , !empty($gd->G_MARKETING07)?$gd->G_MARKETING07:"", $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING8": $this->SetG_ADD_MARKETING($disp, $must, "8" , "８", !empty($gd->G_MARKETING08)?$gd->G_MARKETING08:"" , !empty($gd->G_MARKETING08)?$gd->G_MARKETING08:"", $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING9": $this->SetG_ADD_MARKETING($disp, $must, "9" , "９", !empty($gd->G_MARKETING09)?$gd->G_MARKETING09:"" , !empty($gd->G_MARKETING09)?$gd->G_MARKETING09:"", $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING10": $this->SetG_ADD_MARKETING($disp, $must, "10", "１０", !empty($gd->G_MARKETING10)?$gd->G_MARKETING10:"" , !empty($gd->G_MARKETING10)?$gd->G_MARKETING10:"", $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING11": $this->SetG_ADD_MARKETING($disp, $must, "11", "１１", !empty($gd->G_MARKETING11)?$gd->G_MARKETING11:"" , !empty($gd->G_MARKETING11)?$gd->G_MARKETING11:"", $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING12": $this->SetG_ADD_MARKETING($disp, $must, "12", "１２", !empty($gd->G_MARKETING12)?$gd->G_MARKETING12:"" , !empty($gd->G_MARKETING12)?$gd->G_MARKETING12:"", $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING13": $this->SetG_ADD_MARKETING($disp, $must, "13", "１３", !empty($gd->G_MARKETING13)?$gd->G_MARKETING13:"" , !empty($gd->G_MARKETING13)?$gd->G_MARKETING13:"", $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING14": $this->SetG_ADD_MARKETING($disp, $must, "14", "１４", !empty($gd->G_MARKETING14)?$gd->G_MARKETING14:"" , !empty($gd->G_MARKETING14)?$gd->G_MARKETING14:"", $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING15": $this->SetG_ADD_MARKETING($disp, $must, "15", "１５", !empty($gd->G_MARKETING15)?$gd->G_MARKETING15:"" , !empty($gd->G_MARKETING15)?$gd->G_MARKETING15:"", $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING16": $this->SetG_ADD_MARKETING_TEXT($disp, $must, "16", "１６", !empty($gd->G_MARKETING16)?$gd->G_MARKETING16:"" , !empty($gd->G_MARKETING16)?$gd->G_MARKETING16:"", $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING17": $this->SetG_ADD_MARKETING_TEXT($disp, $must, "17", "１７", !empty($gd->G_MARKETING17)?$gd->G_MARKETING17:"" , !empty($gd->G_MARKETING17)?$gd->G_MARKETING17:"", $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING18": $this->SetG_ADD_MARKETING_TEXT($disp, $must, "18", "１８", !empty($gd->G_MARKETING18)?$gd->G_MARKETING18:"" , !empty($gd->G_MARKETING18)?$gd->G_MARKETING18:"", $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING19": $this->SetG_ADD_MARKETING_TEXT($disp, $must, "19", "１９", !empty($gd->G_MARKETING19)?$gd->G_MARKETING19:"" , !empty($gd->G_MARKETING19)?$gd->G_MARKETING19:"", $readOnlyFlg, $explanation); break;
          case "G_ADD_MARKETING20": $this->SetG_ADD_MARKETING_TEXT($disp, $must, "20", "２０", !empty($gd->G_MARKETING20)?$gd->G_MARKETING20:"" , !empty($gd->G_MARKETING20)?$gd->G_MARKETING20:"", $readOnlyFlg, $explanation); break;

          case "G_BANK_CD": $this->SetG_BANK_CD($disp, $must, $readOnlyFlg, $explanation); break;
          case "G_BRANCH_CD" : $this->SetG_BRANCH_CD($disp, $must, $readOnlyFlg, $explanation); break;
          case "G_ACCAUNT_TYPE": $this->SetG_ACCAUNT_TYPE($disp, $must, $readOnlyFlg, $explanation); break;
          case "G_ACCOUNT_NO": $this->SetG_ACCOUNT_NO($disp, $must, $readOnlyFlg, $explanation); break;
          case "G_ACCAUNT_NM": $this->SetG_ACCAUNT_NM($disp, $must, $readOnlyFlg, $explanation); break;
          case "G_CUST_NO": $this->SetG_CUST_NO($disp, $must, $readOnlyFlg, $explanation); break;
          case "G_SAVINGS_CD": $this->SetG_SAVINGS_CD($disp, $must, $readOnlyFlg, $explanation); break;
          case "G_SAVINGS_NO": $this->SetG_SAVINGS_NO($disp, $must, $readOnlyFlg, $explanation); break;

          case "P_P_ID": $this->SetP_P_ID($disp, $must, $explanation); break;
          case "P_PASSWORD" : $this->SetP_PASSWORD($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_PASSWORD2": $this->SetP_PASSWORD2($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_C_NAME" : $this->SetP_C_NAME($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_KANA" : $this->SetP_C_KANA($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_NAME_EN" : $this->SetP_C_NAME_EN($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_SEX": $this->SetP_C_SEX($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_URL": $this->SetP_C_URL($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_EMAIL": $this->SetP_C_EMAIL($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_EMAIL2": $this->SetP_C_EMAIL2($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_CC_EMAIL": $this->SetP_C_CC_EMAIL($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_TEL": $this->SetP_C_TEL($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_FAX": $this->SetP_C_FAX($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_PTEL" : $this->SetP_C_PTEL($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_PMAIL": $this->SetP_C_PMAIL($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_PMAIL2": $this->SetP_C_PMAIL2($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_POST" : $this->SetP_C_POST($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_STA": $this->SetP_C_STA($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_ADR": $this->SetP_C_ADR($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_ADR2": $this->SetP_C_ADR2($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_C_ADR3": $this->SetP_C_ADR3($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_C_ADR_EN": $this->SetP_C_ADR_EN($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_C_IMG": $this->SetP_C_IMG($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_IMG2" : $this->SetP_C_IMG2($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_IMG3" : $this->SetP_C_IMG3($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_APPEAL" : $this->SetP_C_APPEAL($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_C_FREE01" : $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "1", "１", !empty($pd->C_FREE01)?$pd->C_FREE01:"", $pd->O_FREE01, !empty($pd->C_FREE01)?$pd->C_FREE01:"", $pd->O_FREE01, $explanation, $dispIni); break;
          case "P_C_FREE02" : $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "2", "２", !empty($pd->C_FREE02)?$pd->C_FREE02:"", $pd->O_FREE02, !empty($pd->C_FREE02)?$pd->C_FREE02:"", $pd->O_FREE02, $explanation, $dispIni); break;
          case "P_C_FREE03" : $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "3", "３", !empty($pd->C_FREE03)?$pd->C_FREE03:"", $pd->O_FREE03, !empty($pd->C_FREE03)?$pd->C_FREE03:"", $pd->O_FREE03, $explanation, $dispIni); break;
          case "P_C_FREE04" : $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "4", "４", !empty($pd->C_FREE04)?$pd->C_FREE04:"", $pd->O_FREE04, !empty($pd->C_FREE04)?$pd->C_FREE04:"", $pd->O_FREE04, $explanation, $dispIni); break;
          case "P_C_FREE05" : $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "5", "５", !empty($pd->C_FREE05)?$pd->C_FREE05:"", $pd->O_FREE05, !empty($pd->C_FREE05)?$pd->C_FREE05:"", $pd->O_FREE05, $explanation, $dispIni); break;
          case "P_C_FREE06" : $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "6", "６", !empty($pd->C_FREE06)?$pd->C_FREE06:"", $pd->O_FREE06, !empty($pd->C_FREE06)?$pd->C_FREE06:"", $pd->O_FREE06, $explanation, $dispIni); break;
          case "P_C_FREE07" : $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "7", "７", !empty($pd->C_FREE07)?$pd->C_FREE07:"", $pd->O_FREE07, !empty($pd->C_FREE07)?$pd->C_FREE07:"", $pd->O_FREE07, $explanation, $dispIni); break;
          case "P_C_FREE08" : $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "8", "８", !empty($pd->C_FREE08)?$pd->C_FREE08:"", $pd->O_FREE08, !empty($pd->C_FREE08)?$pd->C_FREE08:"", $pd->O_FREE08, $explanation, $dispIni); break;
          case "P_C_FREE09" : $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "9", "９", !empty($pd->C_FREE09)?$pd->C_FREE09:"", $pd->O_FREE09, !empty($pd->C_FREE09)?$pd->C_FREE09:"", $pd->O_FREE09, $explanation, $dispIni); break;
          case "P_C_FREE10": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "10", "１０", !empty($pd->C_FREE10)?$pd->C_FREE10:"", $pd->O_FREE10, !empty($pd->C_FREE10)?$pd->C_FREE10:"", $pd->O_FREE10, $explanation, $dispIni); break;
          case "P_C_FREE11": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "11", "１１", !empty($pd->C_FREE11)?$pd->C_FREE11:"", $pd->O_FREE11, !empty($pd->C_FREE11)?$pd->C_FREE11:"", $pd->O_FREE11, $explanation, $dispIni); break;
          case "P_C_FREE12": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "12", "１２", !empty($pd->C_FREE12)?$pd->C_FREE12:"", $pd->O_FREE12, !empty($pd->C_FREE12)?$pd->C_FREE12:"", $pd->O_FREE12, $explanation, $dispIni); break;
          case "P_C_FREE13": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "13", "１３", !empty($pd->C_FREE13)?$pd->C_FREE13:"", $pd->O_FREE13, !empty($pd->C_FREE13)?$pd->C_FREE13:"", $pd->O_FREE13, $explanation, $dispIni); break;
          case "P_C_FREE14": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "14", "１４", !empty($pd->C_FREE14)?$pd->C_FREE14:"", $pd->O_FREE14, !empty($pd->C_FREE14)?$pd->C_FREE14:"", $pd->O_FREE14, $explanation, $dispIni); break;
          case "P_C_FREE15": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "15", "１５", !empty($pd->C_FREE15)?$pd->C_FREE15:"", $pd->O_FREE15, !empty($pd->C_FREE15)?$pd->C_FREE15:"", $pd->O_FREE15, $explanation, $dispIni); break;
          case "P_C_FREE16": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "16", "１６", !empty($pd->C_FREE16)?$pd->C_FREE16:"", $pd->O_FREE16, !empty($pd->C_FREE16)?$pd->C_FREE16:"", $pd->O_FREE16, $explanation, $dispIni); break;
          case "P_C_FREE17": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "17", "１７", !empty($pd->C_FREE17)?$pd->C_FREE17:"", $pd->O_FREE17, !empty($pd->C_FREE17)?$pd->C_FREE17:"", $pd->O_FREE17, $explanation, $dispIni); break;
          case "P_C_FREE18": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "18", "１８", !empty($pd->C_FREE18)?$pd->C_FREE18:"", $pd->O_FREE18, !empty($pd->C_FREE18)?$pd->C_FREE18:"", $pd->O_FREE18, $explanation, $dispIni); break;
          case "P_C_FREE19": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "19", "１９", !empty($pd->C_FREE19)?$pd->C_FREE19:"", $pd->O_FREE19, !empty($pd->C_FREE19)?$pd->C_FREE19:"", $pd->O_FREE19, $explanation, $dispIni); break;
          case "P_C_FREE20": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "20", "２０", !empty($pd->C_FREE20)?$pd->C_FREE20:"", $pd->O_FREE20, !empty($pd->C_FREE20)?$pd->C_FREE20:"", $pd->O_FREE20, $explanation, $dispIni); break;
          case "P_C_FREE21": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "21", "２１", !empty($pd->C_FREE21)?$pd->C_FREE21:"", $pd->O_FREE21, !empty($pd->C_FREE21)?$pd->C_FREE21:"", $pd->O_FREE21, $explanation, $dispIni); break;
          case "P_C_FREE22": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "22", "２２", !empty($pd->C_FREE22)?$pd->C_FREE22:"", $pd->O_FREE22, !empty($pd->C_FREE22)?$pd->C_FREE22:"", $pd->O_FREE22, $explanation, $dispIni); break;
          case "P_C_FREE23": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "23", "２３", !empty($pd->C_FREE23)?$pd->C_FREE23:"", $pd->O_FREE23, !empty($pd->C_FREE23)?$pd->C_FREE23:"", $pd->O_FREE23, $explanation, $dispIni); break;
          case "P_C_FREE24": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "24", "２４", !empty($pd->C_FREE24)?$pd->C_FREE24:"", $pd->O_FREE24, !empty($pd->C_FREE24)?$pd->C_FREE24:"", $pd->O_FREE24, $explanation, $dispIni); break;
          case "P_C_FREE25": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "25", "２５", !empty($pd->C_FREE25)?$pd->C_FREE25:"", $pd->O_FREE25, !empty($pd->C_FREE25)?$pd->C_FREE25:"", $pd->O_FREE25, $explanation, $dispIni); break;
          case "P_C_FREE26": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "26", "２６", !empty($pd->C_FREE26)?$pd->C_FREE26:"", $pd->O_FREE26, !empty($pd->C_FREE26)?$pd->C_FREE26:"", $pd->O_FREE26, $explanation, $dispIni); break;
          case "P_C_FREE27": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "27", "２７", !empty($pd->C_FREE27)?$pd->C_FREE27:"", $pd->O_FREE27, !empty($pd->C_FREE27)?$pd->C_FREE27:"", $pd->O_FREE27, $explanation, $dispIni); break;
          case "P_C_FREE28": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "28", "２８", !empty($pd->C_FREE28)?$pd->C_FREE28:"", $pd->O_FREE28, !empty($pd->C_FREE28)?$pd->C_FREE28:"", $pd->O_FREE28, $explanation, $dispIni); break;
          case "P_C_FREE29": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "29", "２９", !empty($pd->C_FREE29)?$pd->C_FREE29:"", $pd->O_FREE29, !empty($pd->C_FREE29)?$pd->C_FREE29:"", $pd->O_FREE29, $explanation, $dispIni); break;
          case "P_C_FREE30": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "30", "３０", !empty($pd->C_FREE30)?$pd->C_FREE30:"", $pd->O_FREE30, !empty($pd->C_FREE30)?$pd->C_FREE30:"", $pd->O_FREE30, $explanation, $dispIni); break;
          case "P_C_FREE31": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "31", "３１", !empty($pd->C_FREE31)?$pd->C_FREE31:"", $pd->O_FREE31, !empty($pd->C_FREE31)?$pd->C_FREE31:"", $pd->O_FREE31, $explanation, $dispIni); break;
          case "P_C_FREE32": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "32", "３２", !empty($pd->C_FREE32)?$pd->C_FREE32:"", $pd->O_FREE32, !empty($pd->C_FREE32)?$pd->C_FREE32:"", $pd->O_FREE32, $explanation, $dispIni); break;
          case "P_C_FREE33": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "33", "３３", !empty($pd->C_FREE33)?$pd->C_FREE33:"", $pd->O_FREE33, !empty($pd->C_FREE33)?$pd->C_FREE33:"", $pd->O_FREE33, $explanation, $dispIni); break;
          case "P_C_FREE34": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "34", "３４", !empty($pd->C_FREE34)?$pd->C_FREE34:"", $pd->O_FREE34, !empty($pd->C_FREE34)?$pd->C_FREE34:"", $pd->O_FREE34, $explanation, $dispIni); break;
          case "P_C_FREE35": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "35", "３５", !empty($pd->C_FREE35)?$pd->C_FREE35:"", $pd->O_FREE35, !empty($pd->C_FREE35)?$pd->C_FREE35:"", $pd->O_FREE35, $explanation, $dispIni); break;
          case "P_C_FREE36": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "36", "３６", !empty($pd->C_FREE36)?$pd->C_FREE36:"", $pd->O_FREE36, !empty($pd->C_FREE36)?$pd->C_FREE36:"", $pd->O_FREE36, $explanation, $dispIni); break;
          case "P_C_FREE37": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "37", "３７", !empty($pd->C_FREE37)?$pd->C_FREE37:"", $pd->O_FREE37, !empty($pd->C_FREE37)?$pd->C_FREE37:"", $pd->O_FREE37, $explanation, $dispIni); break;
          case "P_C_FREE38": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "38", "３８", !empty($pd->C_FREE38)?$pd->C_FREE38:"", $pd->O_FREE38, !empty($pd->C_FREE38)?$pd->C_FREE38:"", $pd->O_FREE38, $explanation, $dispIni); break;
          case "P_C_FREE39": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "39", "３９", !empty($pd->C_FREE39)?$pd->C_FREE39:"", $pd->O_FREE39, !empty($pd->C_FREE39)?$pd->C_FREE39:"", $pd->O_FREE39, $explanation, $dispIni); break;
          case "P_C_FREE40": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "40", "４０", !empty($pd->C_FREE40)?$pd->C_FREE40:"", $pd->O_FREE40, !empty($pd->C_FREE40)?$pd->C_FREE40:"", $pd->O_FREE40, $explanation, $dispIni); break;
          case "P_C_FREE41": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "41", "４１", !empty($pd->C_FREE41)?$pd->C_FREE41:"", $pd->O_FREE41, !empty($pd->C_FREE41)?$pd->C_FREE41:"", $pd->O_FREE41, $explanation, $dispIni); break;
          case "P_C_FREE42": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "42", "４２", !empty($pd->C_FREE42)?$pd->C_FREE42:"", $pd->O_FREE42, !empty($pd->C_FREE42)?$pd->C_FREE42:"", $pd->O_FREE42, $explanation, $dispIni); break;
          case "P_C_FREE43": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "43", "４３", !empty($pd->C_FREE43)?$pd->C_FREE43:"", $pd->O_FREE43, !empty($pd->C_FREE43)?$pd->C_FREE43:"", $pd->O_FREE43, $explanation, $dispIni); break;
          case "P_C_FREE44": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "44", "４４", !empty($pd->C_FREE44)?$pd->C_FREE44:"", $pd->O_FREE44, !empty($pd->C_FREE44)?$pd->C_FREE44:"", $pd->O_FREE44, $explanation, $dispIni); break;
          case "P_C_FREE45": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "45", "４５", !empty($pd->C_FREE45)?$pd->C_FREE45:"", $pd->O_FREE45, !empty($pd->C_FREE45)?$pd->C_FREE45:"", $pd->O_FREE45, $explanation, $dispIni); break;
          case "P_C_FREE46": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "46", "４６", !empty($pd->C_FREE46)?$pd->C_FREE46:"", $pd->O_FREE46, !empty($pd->C_FREE46)?$pd->C_FREE46:"", $pd->O_FREE46, $explanation, $dispIni); break;
          case "P_C_FREE47": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "47", "４７", !empty($pd->C_FREE47)?$pd->C_FREE47:"", $pd->O_FREE47, !empty($pd->C_FREE47)?$pd->C_FREE47:"", $pd->O_FREE47, $explanation, $dispIni); break;
          case "P_C_FREE48": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "48", "４８", !empty($pd->C_FREE48)?$pd->C_FREE48:"", $pd->O_FREE48, !empty($pd->C_FREE48)?$pd->C_FREE48:"", $pd->O_FREE48, $explanation, $dispIni); break;
          case "P_C_FREE49": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "49", "４９", !empty($pd->C_FREE49)?$pd->C_FREE49:"", $pd->O_FREE49, !empty($pd->C_FREE49)?$pd->C_FREE49:"", $pd->O_FREE49, $explanation, $dispIni); break;
          case "P_C_FREE50": $this->SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "50", "５０", !empty($pd->C_FREE50)?$pd->C_FREE50:"", $pd->O_FREE50, !empty($pd->C_FREE50)?$pd->C_FREE50:"", $pd->O_FREE50, $explanation, $dispIni); break;
          case "P_P_FREE01" : $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "1", "１", !empty($pd->P_FREE01)?$pd->P_FREE01:"", $pd->O_P_FREE01, !empty($pd->P_FREE01)?$pd->P_FREE01:"", $pd->O_P_FREE01, $explanation, $dispIni); break;
          case "P_P_FREE02" : $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "2", "２", !empty($pd->P_FREE02)?$pd->P_FREE02:"", $pd->O_P_FREE02, !empty($pd->P_FREE02)?$pd->P_FREE02:"", $pd->O_P_FREE02, $explanation, $dispIni); break;
          case "P_P_FREE03" : $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "3", "３", !empty($pd->P_FREE03)?$pd->P_FREE03:"", $pd->O_P_FREE03, !empty($pd->P_FREE03)?$pd->P_FREE03:"", $pd->O_P_FREE03, $explanation, $dispIni); break;
          case "P_P_FREE04" : $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "4", "４", !empty($pd->P_FREE04)?$pd->P_FREE04:"", $pd->O_P_FREE04, !empty($pd->P_FREE04)?$pd->P_FREE04:"", $pd->O_P_FREE04, $explanation, $dispIni); break;
          case "P_P_FREE05" : $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "5", "５", !empty($pd->P_FREE05)?$pd->P_FREE05:"", $pd->O_P_FREE05, !empty($pd->P_FREE05)?$pd->P_FREE05:"", $pd->O_P_FREE05, $explanation, $dispIni); break;
          case "P_P_FREE06" : $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "6", "６", !empty($pd->P_FREE06)?$pd->P_FREE06:"", $pd->O_P_FREE06, !empty($pd->P_FREE06)?$pd->P_FREE06:"", $pd->O_P_FREE06, $explanation, $dispIni); break;
          case "P_P_FREE07" : $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "7", "７", !empty($pd->P_FREE07)?$pd->P_FREE07:"", $pd->O_P_FREE07, !empty($pd->P_FREE07)?$pd->P_FREE07:"", $pd->O_P_FREE07, $explanation, $dispIni); break;
          case "P_P_FREE08" : $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "8", "８", !empty($pd->P_FREE08)?$pd->P_FREE08:"", $pd->O_P_FREE08, !empty($pd->P_FREE08)?$pd->P_FREE08:"", $pd->O_P_FREE08, $explanation, $dispIni); break;
          case "P_P_FREE09" : $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "9", "９", !empty($pd->P_FREE09)?$pd->P_FREE09:"", $pd->O_P_FREE09, !empty($pd->P_FREE09)?$pd->P_FREE09:"", $pd->O_P_FREE09, $explanation, $dispIni); break;
          case "P_P_FREE10": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "10", "１０", !empty($pd->P_FREE10)?$pd->P_FREE10:"", $pd->O_P_FREE10, !empty($pd->P_FREE10)?$pd->P_FREE10:"", $pd->O_P_FREE10, $explanation, $dispIni); break;
          case "P_P_FREE11": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "11", "１１", !empty($pd->P_FREE11)?$pd->P_FREE11:"", $pd->O_P_FREE11, !empty($pd->P_FREE11)?$pd->P_FREE11:"", $pd->O_P_FREE11, $explanation, $dispIni); break;
          case "P_P_FREE12": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "12", "１２", !empty($pd->P_FREE12)?$pd->P_FREE12:"", $pd->O_P_FREE12, !empty($pd->P_FREE12)?$pd->P_FREE12:"", $pd->O_P_FREE12, $explanation, $dispIni); break;
          case "P_P_FREE13": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "13", "１３", !empty($pd->P_FREE13)?$pd->P_FREE13:"", $pd->O_P_FREE13, !empty($pd->P_FREE13)?$pd->P_FREE13:"", $pd->O_P_FREE13, $explanation, $dispIni); break;
          case "P_P_FREE14": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "14", "１４", !empty($pd->P_FREE14)?$pd->P_FREE14:"", $pd->O_P_FREE14, !empty($pd->P_FREE14)?$pd->P_FREE14:"", $pd->O_P_FREE14, $explanation, $dispIni); break;
          case "P_P_FREE15": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "15", "１５", !empty($pd->P_FREE15)?$pd->P_FREE15:"", $pd->O_P_FREE15, !empty($pd->P_FREE15)?$pd->P_FREE15:"", $pd->O_P_FREE15, $explanation, $dispIni); break;
          case "P_P_FREE16": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "16", "１６", !empty($pd->P_FREE16)?$pd->P_FREE16:"", $pd->O_P_FREE16, !empty($pd->P_FREE16)?$pd->P_FREE16:"", $pd->O_P_FREE16, $explanation, $dispIni); break;
          case "P_P_FREE17": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "17", "１７", !empty($pd->P_FREE17)?$pd->P_FREE17:"", $pd->O_P_FREE17, !empty($pd->P_FREE17)?$pd->P_FREE17:"", $pd->O_P_FREE17, $explanation, $dispIni); break;
          case "P_P_FREE18": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "18", "１８", !empty($pd->P_FREE18)?$pd->P_FREE18:"", $pd->O_P_FREE18, !empty($pd->P_FREE18)?$pd->P_FREE18:"", $pd->O_P_FREE18, $explanation, $dispIni); break;
          case "P_P_FREE19": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "19", "１９", !empty($pd->P_FREE19)?$pd->P_FREE19:"", $pd->O_P_FREE19, !empty($pd->P_FREE19)?$pd->P_FREE19:"", $pd->O_P_FREE19, $explanation, $dispIni); break;
          case "P_P_FREE20": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "20", "２０", !empty($pd->P_FREE20)?$pd->P_FREE20:"", $pd->O_P_FREE20, !empty($pd->P_FREE20)?$pd->P_FREE20:"", $pd->O_P_FREE20, $explanation, $dispIni); break;
          case "P_P_FREE21": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "21", "２１", !empty($pd->P_FREE21)?$pd->P_FREE21:"", $pd->O_P_FREE21, !empty($pd->P_FREE21)?$pd->P_FREE21:"", $pd->O_P_FREE21, $explanation, $dispIni); break;
          case "P_P_FREE22": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "22", "２２", !empty($pd->P_FREE22)?$pd->P_FREE22:"", $pd->O_P_FREE22, !empty($pd->P_FREE22)?$pd->P_FREE22:"", $pd->O_P_FREE22, $explanation, $dispIni); break;
          case "P_P_FREE23": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "23", "２３", !empty($pd->P_FREE23)?$pd->P_FREE23:"", $pd->O_P_FREE23, !empty($pd->P_FREE23)?$pd->P_FREE23:"", $pd->O_P_FREE23, $explanation, $dispIni); break;
          case "P_P_FREE24": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "24", "２４", !empty($pd->P_FREE24)?$pd->P_FREE24:"", $pd->O_P_FREE24, !empty($pd->P_FREE24)?$pd->P_FREE24:"", $pd->O_P_FREE24, $explanation, $dispIni); break;
          case "P_P_FREE25": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "25", "２５", !empty($pd->P_FREE25)?$pd->P_FREE25:"", $pd->O_P_FREE25, !empty($pd->P_FREE25)?$pd->P_FREE25:"", $pd->O_P_FREE25, $explanation, $dispIni); break;
          case "P_P_FREE26": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "26", "２６", !empty($pd->P_FREE26)?$pd->P_FREE26:"", $pd->O_P_FREE26, !empty($pd->P_FREE26)?$pd->P_FREE26:"", $pd->O_P_FREE26, $explanation, $dispIni); break;
          case "P_P_FREE27": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "27", "２７", !empty($pd->P_FREE27)?$pd->P_FREE27:"", $pd->O_P_FREE27, !empty($pd->P_FREE27)?$pd->P_FREE27:"", $pd->O_P_FREE27, $explanation, $dispIni); break;
          case "P_P_FREE28": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "28", "２８", !empty($pd->P_FREE28)?$pd->P_FREE28:"", $pd->O_P_FREE28, !empty($pd->P_FREE28)?$pd->P_FREE28:"", $pd->O_P_FREE28, $explanation, $dispIni); break;
          case "P_P_FREE29": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "29", "２９", !empty($pd->P_FREE29)?$pd->P_FREE29:"", $pd->O_P_FREE29, !empty($pd->P_FREE29)?$pd->P_FREE29:"", $pd->O_P_FREE29, $explanation, $dispIni); break;
          case "P_P_FREE30": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "30", "３０", !empty($pd->P_FREE30)?$pd->P_FREE30:"", $pd->O_P_FREE30, !empty($pd->P_FREE30)?$pd->P_FREE30:"", $pd->O_P_FREE30, $explanation, $dispIni); break;
          case "P_P_FREE31": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "31", "３１", !empty($pd->P_FREE31)?$pd->P_FREE31:"", $pd->O_P_FREE31, !empty($pd->P_FREE31)?$pd->P_FREE31:"", $pd->O_P_FREE31, $explanation, $dispIni); break;
          case "P_P_FREE32": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "32", "３２", !empty($pd->P_FREE32)?$pd->P_FREE32:"", $pd->O_P_FREE32, !empty($pd->P_FREE32)?$pd->P_FREE32:"", $pd->O_P_FREE32, $explanation, $dispIni); break;
          case "P_P_FREE33": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "33", "３３", !empty($pd->P_FREE33)?$pd->P_FREE33:"", $pd->O_P_FREE33, !empty($pd->P_FREE33)?$pd->P_FREE33:"", $pd->O_P_FREE33, $explanation, $dispIni); break;
          case "P_P_FREE34": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "34", "３４", !empty($pd->P_FREE34)?$pd->P_FREE34:"", $pd->O_P_FREE34, !empty($pd->P_FREE34)?$pd->P_FREE34:"", $pd->O_P_FREE34, $explanation, $dispIni); break;
          case "P_P_FREE35": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "35", "３５", !empty($pd->P_FREE35)?$pd->P_FREE35:"", $pd->O_P_FREE35, !empty($pd->P_FREE35)?$pd->P_FREE35:"", $pd->O_P_FREE35, $explanation, $dispIni); break;
          case "P_P_FREE36": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "36", "３６", !empty($pd->P_FREE36)?$pd->P_FREE36:"", $pd->O_P_FREE36, !empty($pd->P_FREE36)?$pd->P_FREE36:"", $pd->O_P_FREE36, $explanation, $dispIni); break;
          case "P_P_FREE37": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "37", "３７", !empty($pd->P_FREE37)?$pd->P_FREE37:"", $pd->O_P_FREE37, !empty($pd->P_FREE37)?$pd->P_FREE37:"", $pd->O_P_FREE37, $explanation, $dispIni); break;
          case "P_P_FREE38": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "38", "３８", !empty($pd->P_FREE38)?$pd->P_FREE38:"", $pd->O_P_FREE38, !empty($pd->P_FREE38)?$pd->P_FREE38:"", $pd->O_P_FREE38, $explanation, $dispIni); break;
          case "P_P_FREE39": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "39", "３９", !empty($pd->P_FREE39)?$pd->P_FREE39:"", $pd->O_P_FREE39, !empty($pd->P_FREE39)?$pd->P_FREE39:"", $pd->O_P_FREE39, $explanation, $dispIni); break;
          case "P_P_FREE40": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "40", "４０", !empty($pd->P_FREE40)?$pd->P_FREE40:"", $pd->O_P_FREE40, !empty($pd->P_FREE40)?$pd->P_FREE40:"", $pd->O_P_FREE40, $explanation, $dispIni); break;
          case "P_P_FREE41": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "41", "４１", !empty($pd->P_FREE41)?$pd->P_FREE41:"", $pd->O_P_FREE41, !empty($pd->P_FREE41)?$pd->P_FREE41:"", $pd->O_P_FREE41, $explanation, $dispIni); break;
          case "P_P_FREE42": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "42", "４２", !empty($pd->P_FREE42)?$pd->P_FREE42:"", $pd->O_P_FREE42, !empty($pd->P_FREE42)?$pd->P_FREE42:"", $pd->O_P_FREE42, $explanation, $dispIni); break;
          case "P_P_FREE43": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "43", "４３", !empty($pd->P_FREE43)?$pd->P_FREE43:"", $pd->O_P_FREE43, !empty($pd->P_FREE43)?$pd->P_FREE43:"", $pd->O_P_FREE43, $explanation, $dispIni); break;
          case "P_P_FREE44": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "44", "４４", !empty($pd->P_FREE44)?$pd->P_FREE44:"", $pd->O_P_FREE44, !empty($pd->P_FREE44)?$pd->P_FREE44:"", $pd->O_P_FREE44, $explanation, $dispIni); break;
          case "P_P_FREE45": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "45", "４５", !empty($pd->P_FREE45)?$pd->P_FREE45:"", $pd->O_P_FREE45, !empty($pd->P_FREE45)?$pd->P_FREE45:"", $pd->O_P_FREE45, $explanation, $dispIni); break;
          case "P_P_FREE46": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "46", "４６", !empty($pd->P_FREE46)?$pd->P_FREE46:"", $pd->O_P_FREE46, !empty($pd->P_FREE46)?$pd->P_FREE46:"", $pd->O_P_FREE46, $explanation, $dispIni); break;
          case "P_P_FREE47": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "47", "４７", !empty($pd->P_FREE47)?$pd->P_FREE47:"", $pd->O_P_FREE47, !empty($pd->P_FREE47)?$pd->P_FREE47:"", $pd->O_P_FREE47, $explanation, $dispIni); break;
          case "P_P_FREE48": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "48", "４８", !empty($pd->P_FREE48)?$pd->P_FREE48:"", $pd->O_P_FREE48, !empty($pd->P_FREE48)?$pd->P_FREE48:"", $pd->O_P_FREE48, $explanation, $dispIni); break;
          case "P_P_FREE49": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "49", "４９", !empty($pd->P_FREE49)?$pd->P_FREE49:"", $pd->O_P_FREE49, !empty($pd->P_FREE49)?$pd->P_FREE49:"", $pd->O_P_FREE49, $explanation, $dispIni); break;
          case "P_P_FREE50": $this->SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "50", "５０", !empty($pd->P_FREE50)?$pd->P_FREE50:"", $pd->O_P_FREE50, !empty($pd->P_FREE50)?$pd->P_FREE50:"", $pd->O_P_FREE50, $explanation, $dispIni); break;
          case "P_GROUP_ENABLE_FLG": $this->SetP_GROUP_ENABLE_FLG($disp, $must, $readOnlyFlg, $default, $explanation); break;
          case "P_MEETING_NM_DISP": $this->SetP_MEETING_NM_DISP($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_HANDLE_NM": $this->SetP_HANDLE_NM($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_MEETING_NM_MK": $this->SetP_MEETING_NM_MK($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_G_ID": $this->SetP_G_ID($disp, $must, $explanation, $dispIni); break;
          case "S_AFFILIATION_NAME": $this->SetS_AFFILIATION_NAME($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          /* next */
          case "S_OFFICIAL_POSITION": $this->SetS_OFFICIAL_POSITION($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "G_REPRESENTATIVE_OP": $this->SetG_REPRESENTATIVE_OP($disp, $must, $readOnlyFlg, $explanation, $dispIni); break;
          case "P_CARD_OPEN": $this->SetP_CARD_OPEN($disp, $must, $readOnlyFlg, $default, $explanation, $dispIni); break;
          case "P_LOGIN_LOCK_FLG": $this->SetP_LOGIN_LOCK_FLG($disp, $must, $readOnlyFlg, $default, $explanation); break;

          case "P_P_URL": $this->SetP_P_URL($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_P_EMAIL": $this->SetP_P_EMAIL($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_P_EMAIL2": $this->SetP_P_EMAIL2($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_P_CC_EMAIL": $this->SetP_P_CC_EMAIL($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_P_TEL": $this->SetP_P_TEL($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_P_FAX": $this->SetP_P_FAX($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_P_PTEL" : $this->SetP_P_PTEL($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_P_PMAIL": $this->SetP_P_PMAIL($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_P_PMAIL2": $this->SetP_P_PMAIL2($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_P_POST": $this->SetP_P_POST($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_P_STA": $this->SetP_P_STA($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_P_ADR": $this->SetP_P_ADR($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_P_ADR2": $this->SetP_P_ADR2($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_P_ADR3": $this->SetP_P_ADR3($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_P_ADR_EN": $this->SetP_P_ADR_EN($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_P_BIRTH": $this->SetP_P_BIRTH($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_PRIVATE_OPEN": $this->SetP_PRIVATE_OPEN($disp, $must, $readOnlyFlg, $default, $explanation); break;
          case "P_BANK_CD": $this->SetP_BANK_CD($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_BRANCH_CD" : $this->SetP_BRANCH_CD($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_ACCAUNT_TYPE": $this->SetP_ACCAUNT_TYPE($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_ACCOUNT_NO": $this->SetP_ACCOUNT_NO($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_ACCOUNT_NM": $this->SetP_ACCOUNT_NM($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_CUST_NO": $this->SetP_CUST_NO($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_SAVINGS_CD": $this->SetP_SAVINGS_CD($disp, $must, $readOnlyFlg, $explanation); break;
          case "P_SAVINGS_NO": $this->SetP_SAVINGS_NO($disp, $must, $readOnlyFlg, $explanation); break;

          case "M_LG_ID": $this->SetM_LG_ID($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTRACT_TYPE" : $this->SetM_CONTRACT_TYPE($disp, $must, $readOnlyFlg, $default, $explanation); break;
          case "M_FAX_TIMEZONE": $this->SetM_FAX_TIMEZONE($disp, $must, $readOnlyFlg, $default, $explanation); break;
          case "M_USER_ID": $this->SetM_USER_ID($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_RECOMMEND_P_ID": $this->SetM_RECOMMEND_P_ID($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_RECOMMEND_P_ID2": $this->SetM_RECOMMEND_P_ID2($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_RECOMMEND_P_ID3": $this->SetM_RECOMMEND_P_ID3($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_RECOMMEND_P_ID4": $this->SetM_RECOMMEND_P_ID4($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_RECOMMEND_P_ID5": $this->SetM_RECOMMEND_P_ID5($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_DISP_LIST": $this->SetM_DISP_LIST($disp, $must, $explanation); break;
          case "M_DISP_DETAIL": $this->SetM_DISP_DETAIL($disp, $must, $explanation); break;
          case "M_DISP_MARKETING": $this->SetM_DISP_MARKETING($disp, $must, $explanation); break;
          case "M_X_COMMENT": $this->SetM_X_COMMENT($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_TAX_ACCOUNTANT01": $this->SetM_TAX_ACCOUNTANT($disp, $must, $readOnlyFlg, $explanation, "1", "１",!empty($md->TAX_ACCOUNTANT01)?$md->TAX_ACCOUNTANT01:""); break;
          case "M_TAX_ACCOUNTANT02": $this->SetM_TAX_ACCOUNTANT($disp, $must, $readOnlyFlg, $explanation, "2", "２",!empty($md->TAX_ACCOUNTANT02)?$md->TAX_ACCOUNTANT02:""); break;
          case "M_TAX_ACCOUNTANT03": $this->SetM_TAX_ACCOUNTANT($disp, $must, $readOnlyFlg, $explanation, "3", "３",!empty($md->TAX_ACCOUNTANT03)?$md->TAX_ACCOUNTANT03:""); break;
          case "M_TAX_ACCOUNTANT04": $this->SetM_TAX_ACCOUNTANT($disp, $must, $readOnlyFlg, $explanation, "4", "４",!empty($md->TAX_ACCOUNTANT04)?$md->TAX_ACCOUNTANT04:""); break;
          case "M_TAX_ACCOUNTANT05": $this->SetM_TAX_ACCOUNTANT($disp, $must, $readOnlyFlg, $explanation, "5", "５",!empty($md->TAX_ACCOUNTANT05)?$md->TAX_ACCOUNTANT05:""); break;
          case "M_TAX_ACCOUNTANT06": $this->SetM_TAX_ACCOUNTANT($disp, $must, $readOnlyFlg, $explanation, "6", "６",!empty($md->TAX_ACCOUNTANT06)?$md->TAX_ACCOUNTANT06:""); break;
          case "M_TAX_ACCOUNTANT07": $this->SetM_TAX_ACCOUNTANT($disp, $must, $readOnlyFlg, $explanation, "7", "７",!empty($md->TAX_ACCOUNTANT07)?$md->TAX_ACCOUNTANT07:""); break;
          case "M_TAX_ACCOUNTANT08": $this->SetM_TAX_ACCOUNTANT($disp, $must, $readOnlyFlg, $explanation, "8", "８",!empty($md->TAX_ACCOUNTANT08)?$md->TAX_ACCOUNTANT08:""); break;
          case "M_TAX_ACCOUNTANT09": $this->SetM_TAX_ACCOUNTANT($disp, $must, $readOnlyFlg, $explanation, "9", "９",!empty($md->TAX_ACCOUNTANT09)?$md->TAX_ACCOUNTANT09:""); break;
          case "M_TAX_ACCOUNTANT10": $this->SetM_TAX_ACCOUNTANT($disp, $must, $readOnlyFlg, $explanation, "10", "１０",!empty($md->TAX_ACCOUNTANT10)?$md->TAX_ACCOUNTANT10:""); break;
          case "M_FREE01": $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "1", "１", !empty($md->M_FREE01)?$md->M_FREE01:"", !empty($md->M_FREE01)?$md->M_FREE01:"", $explanation); break;
          case "M_FREE02": $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "2", "２", !empty($md->M_FREE02)?$md->M_FREE02:"", !empty($md->M_FREE02)?$md->M_FREE02:"", $explanation); break;
          case "M_FREE03": $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "3", "３", !empty($md->M_FREE03)?$md->M_FREE03:"", !empty($md->M_FREE03)?$md->M_FREE03:"", $explanation); break;
          case "M_FREE04": $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "4", "４", !empty($md->M_FREE04)?$md->M_FREE04:"", !empty($md->M_FREE04)?$md->M_FREE04:"", $explanation); break;
          case "M_FREE05": $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "5", "５", !empty($md->M_FREE05)?$md->M_FREE05:"", !empty($md->M_FREE05)?$md->M_FREE05:"", $explanation); break;
          case "M_FREE06": $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "6", "６", !empty($md->M_FREE06)?$md->M_FREE06:"", !empty($md->M_FREE06)?$md->M_FREE06:"", $explanation); break;
          case "M_FREE07": $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "7", "７", !empty($md->M_FREE07)?$md->M_FREE07:"", !empty($md->M_FREE07)?$md->M_FREE07:"", $explanation); break;
          case "M_FREE08": $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "8", "８", !empty($md->M_FREE08)?$md->M_FREE08:"", !empty($md->M_FREE08)?$md->M_FREE08:"", $explanation); break;
          case "M_FREE09": $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "9", "９", !empty($md->M_FREE09)?$md->M_FREE09:"", !empty($md->M_FREE09)?$md->M_FREE09:"", $explanation); break;
          case "M_FREE10" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "10", "１０", !empty($md->M_FREE10)?$md->M_FREE10:"", !empty($md->M_FREE10)?$md->M_FREE10:"", $explanation); break;
          case "M_FREE11" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "11", "１１", !empty($md->M_FREE11)?$md->M_FREE11:"", !empty($md->M_FREE11)?$md->M_FREE11:"", $explanation); break;
          case "M_FREE12" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "12", "１２", !empty($md->M_FREE12)?$md->M_FREE12:"", !empty($md->M_FREE12)?$md->M_FREE12:"", $explanation); break;
          case "M_FREE13" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "13", "１３", !empty($md->M_FREE13)?$md->M_FREE13:"", !empty($md->M_FREE13)?$md->M_FREE13:"", $explanation); break;
          case "M_FREE14" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "14", "１４", !empty($md->M_FREE14)?$md->M_FREE14:"", !empty($md->M_FREE14)?$md->M_FREE14:"", $explanation); break;
          case "M_FREE15" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "15", "１５", !empty($md->M_FREE15)?$md->M_FREE15:"", !empty($md->M_FREE15)?$md->M_FREE15:"", $explanation); break;
          case "M_FREE16" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "16", "１６", !empty($md->M_FREE16)?$md->M_FREE16:"", !empty($md->M_FREE16)?$md->M_FREE16:"", $explanation); break;
          case "M_FREE17" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "17", "１７", !empty($md->M_FREE17)?$md->M_FREE17:"", !empty($md->M_FREE17)?$md->M_FREE17:"", $explanation); break;
          case "M_FREE18" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "18", "１８", !empty($md->M_FREE18)?$md->M_FREE18:"", !empty($md->M_FREE18)?$md->M_FREE18:"", $explanation); break;
          case "M_FREE19" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "19", "１９", !empty($md->M_FREE19)?$md->M_FREE19:"", !empty($md->M_FREE19)?$md->M_FREE19:"", $explanation); break;
          case "M_FREE20" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "20", "２０", !empty($md->M_FREE20)?$md->M_FREE20:"", !empty($md->M_FREE20)?$md->M_FREE20:"", $explanation); break;
          case "M_FREE21" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "21", "２１", !empty($md->M_FREE21)?$md->M_FREE21:"", !empty($md->M_FREE21)?$md->M_FREE21:"", $explanation); break;
          case "M_FREE22" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "22", "２２", !empty($md->M_FREE22)?$md->M_FREE22:"", !empty($md->M_FREE22)?$md->M_FREE22:"", $explanation); break;
          case "M_FREE23" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "23", "２３", !empty($md->M_FREE23)?$md->M_FREE23:"", !empty($md->M_FREE23)?$md->M_FREE23:"", $explanation); break;
          case "M_FREE24" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "24", "２４", !empty($md->M_FREE24)?$md->M_FREE24:"", !empty($md->M_FREE24)?$md->M_FREE24:"", $explanation); break;
          case "M_FREE25" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "25", "２５", !empty($md->M_FREE25)?$md->M_FREE25:"", !empty($md->M_FREE25)?$md->M_FREE25:"", $explanation); break;
          case "M_FREE26" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "26", "２６", !empty($md->M_FREE26)?$md->M_FREE26:"", !empty($md->M_FREE26)?$md->M_FREE26:"", $explanation); break;
          case "M_FREE27" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "27", "２７", !empty($md->M_FREE27)?$md->M_FREE27:"", !empty($md->M_FREE27)?$md->M_FREE27:"", $explanation); break;
          case "M_FREE28" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "28", "２８", !empty($md->M_FREE28)?$md->M_FREE28:"", !empty($md->M_FREE28)?$md->M_FREE28:"", $explanation); break;
          case "M_FREE29" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "29", "２９", !empty($md->M_FREE29)?$md->M_FREE29:"", !empty($md->M_FREE29)?$md->M_FREE29:"", $explanation); break;
          case "M_FREE30" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "30", "３０", !empty($md->M_FREE30)?$md->M_FREE30:"", !empty($md->M_FREE30)?$md->M_FREE30:"", $explanation); break;
          case "M_FREE31" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "31", "３１", !empty($md->M_FREE31)?$md->M_FREE31:"", !empty($md->M_FREE31)?$md->M_FREE31:"", $explanation); break;
          case "M_FREE32" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "32", "３２", !empty($md->M_FREE32)?$md->M_FREE32:"", !empty($md->M_FREE32)?$md->M_FREE32:"", $explanation); break;
          case "M_FREE33" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "33", "３３", !empty($md->M_FREE33)?$md->M_FREE33:"", !empty($md->M_FREE33)?$md->M_FREE33:"", $explanation); break;
          case "M_FREE34" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "34", "３４", !empty($md->M_FREE34)?$md->M_FREE34:"", !empty($md->M_FREE34)?$md->M_FREE34:"", $explanation); break;
          case "M_FREE35" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "35", "３５", !empty($md->M_FREE35)?$md->M_FREE35:"", !empty($md->M_FREE35)?$md->M_FREE35:"", $explanation); break;
          case "M_FREE36" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "36", "３６", !empty($md->M_FREE36)?$md->M_FREE36:"", !empty($md->M_FREE36)?$md->M_FREE36:"", $explanation); break;
          case "M_FREE37" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "37", "３７", !empty($md->M_FREE37)?$md->M_FREE37:"", !empty($md->M_FREE37)?$md->M_FREE37:"", $explanation); break;
          case "M_FREE38" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "38", "３８", !empty($md->M_FREE38)?$md->M_FREE38:"", !empty($md->M_FREE38)?$md->M_FREE38:"", $explanation); break;
          case "M_FREE39" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "39", "３９", !empty($md->M_FREE39)?$md->M_FREE39:"", !empty($md->M_FREE39)?$md->M_FREE39:"", $explanation); break;
          case "M_FREE40" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "40", "４０", !empty($md->M_FREE40)?$md->M_FREE40:"", !empty($md->M_FREE40)?$md->M_FREE40:"", $explanation); break;
          case "M_FREE41" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "41", "４１", !empty($md->M_FREE41)?$md->M_FREE41:"", !empty($md->M_FREE41)?$md->M_FREE41:"", $explanation); break;
          case "M_FREE42" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "42", "４２", !empty($md->M_FREE42)?$md->M_FREE42:"", !empty($md->M_FREE42)?$md->M_FREE42:"", $explanation); break;
          case "M_FREE43" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "43", "４３", !empty($md->M_FREE43)?$md->M_FREE43:"", !empty($md->M_FREE43)?$md->M_FREE43:"", $explanation); break;
          case "M_FREE44" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "44", "４４", !empty($md->M_FREE44)?$md->M_FREE44:"", !empty($md->M_FREE44)?$md->M_FREE44:"", $explanation); break;
          case "M_FREE45" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "45", "４５", !empty($md->M_FREE45)?$md->M_FREE45:"", !empty($md->M_FREE45)?$md->M_FREE45:"", $explanation); break;
          case "M_FREE46" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "46", "４６", !empty($md->M_FREE46)?$md->M_FREE46:"", !empty($md->M_FREE46)?$md->M_FREE46:"", $explanation); break;
          case "M_FREE47" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "47", "４７", !empty($md->M_FREE47)?$md->M_FREE47:"", !empty($md->M_FREE47)?$md->M_FREE47:"", $explanation); break;
          case "M_FREE48" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "48", "４８", !empty($md->M_FREE48)?$md->M_FREE48:"", !empty($md->M_FREE48)?$md->M_FREE48:"", $explanation); break;
          case "M_FREE49" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "49", "４９", !empty($md->M_FREE49)?$md->M_FREE49:"", !empty($md->M_FREE49)?$md->M_FREE49:"", $explanation); break;
          case "M_FREE50" : $this->SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, "50", "５０", !empty($md->M_FREE50)?$md->M_FREE50:"", !empty($md->M_FREE50)?$md->M_FREE50:"", $explanation); break;
          case "M_STATUS" : $this->SetM_STATUS($disp, $must, $readOnlyFlg, $default, $explanation); break;
          case "M_ADMISSION_DATE": $this->SetM_ADMISSION_DATE($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_WITHDRAWAL_DATE": $this->SetM_WITHDRAWAL_DATE($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CHANGE_DATE" : $this->SetM_CHANGE_DATE($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CHANGE_REASON" : $this->SetM_CHANGE_REASON($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_MOVEOUT_DATE" : $this->SetM_MOVEOUT_DATE($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_MOVEOUT_NOTE" : $this->SetM_MOVEOUT_NOTE($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_MOVEIN_DATE" : $this->SetM_MOVEIN_DATE($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_MOVEIN_NOTE" : $this->SetM_MOVEIN_NOTE($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_ADMISSION_REASON" : $this->SetM_ADMISSION_REASON($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_WITHDRAWAL_REASON" : $this->SetM_WITHDRAWAL_REASON($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CLAIM_CLS" : $this->SetM_CLAIM_CLS($disp, $must, $readOnlyFlg, $default, $explanation); break;
          case "M_FEE_RANK" : $this->SetM_FEE_RANK($disp, $must, $readOnlyFlg, $default, $explanation); break;
          case "M_CLAIM_CYCLE" : $this->SetM_CLAIM_CYCLE($disp, $must, $readOnlyFlg, $default, $explanation); break;
          case "M_SETTLE_MONTH": $this->SetM_SETTLE_MONTH($disp, $must, $readOnlyFlg, $default, $explanation); break;
          case "M_FEE_MEMO" : $this->SetM_FEE_MEMO($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_ENTRUST_CD": $this->SetM_ENTRUST_CD($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BANK_CD": $this->SetM_BANK_CD($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BRANCH_CD" : $this->SetM_BRANCH_CD($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_ACCAUNT_TYPE": $this->SetM_ACCAUNT_TYPE($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_ACCOUNT_NO": $this->SetM_ACCOUNT_NO($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_ACCOUNT_NM": $this->SetM_ACCOUNT_NM($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CUST_NO": $this->SetM_CUST_NO($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_ID": $this->SetM_BILLING_ID($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_G_NAME": $this->SetM_BILLING_G_NAME($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_G_KANA": $this->SetM_BILLING_G_KANA($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_G_NAME_EN": $this->SetM_BILLING_G_NAME_EN($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_C_NAME": $this->SetM_BILLING_C_NAME($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_C_NAME_KN": $this->SetM_BILLING_C_NAME_KN($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_SAVINGS_CD": $this->SetM_SAVINGS_CD($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_SAVINGS_NO": $this->SetM_SAVINGS_NO($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_AFFILIATION" : $this->SetM_BILLING_AFFILIATION($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_POSITION" : $this->SetM_BILLING_POSITION($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_EMAIL": $this->SetM_BILLING_EMAIL($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_EMAIL2": $this->SetM_BILLING_EMAIL2($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_CC_EMAIL" : $this->SetM_BILLING_CC_EMAIL($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_TEL" : $this->SetM_BILLING_TEL($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_FAX" : $this->SetM_BILLING_FAX($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_POST": $this->SetM_BILLING_POST($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_STA" : $this->SetM_BILLING_STA($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_ADR": $this->SetM_BILLING_ADR($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_ADR2" : $this->SetM_BILLING_ADR2($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_ADR3" : $this->SetM_BILLING_ADR3($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_BILLING_ADR_EN" : $this->SetM_BILLING_ADR_EN($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_ID" : $this->SetM_CONTACT_ID($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_G_NAME": $this->SetM_CONTACT_G_NAME($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_G_NAME_KN": $this->SetM_CONTACT_G_NAME_KN($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_G_NAME_EN": $this->SetM_CONTACT_G_NAME_EN($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_C_NAME": $this->SetM_CONTACT_C_NAME($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_C_NAME_KN": $this->SetM_CONTACT_C_NAME_KN($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_AFFILIATION" : $this->SetM_CONTACT_AFFILIATION($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_POSITION" : $this->SetM_CONTACT_POSITION($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_EMAIL": $this->SetM_CONTACT_EMAIL($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_EMAIL2": $this->SetM_CONTACT_EMAIL2($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_CC_EMAIL" : $this->SetM_CONTACT_CC_EMAIL($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_TEL" : $this->SetM_CONTACT_TEL($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_FAX" : $this->SetM_CONTACT_FAX($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_POST": $this->SetM_CONTACT_POST($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_STA" : $this->SetM_CONTACT_STA($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_ADR": $this->SetM_CONTACT_ADR($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_ADR2" : $this->SetM_CONTACT_ADR2($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_ADR3" : $this->SetM_CONTACT_ADR3($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_CONTACT_ADR_EN" : $this->SetM_CONTACT_ADR_EN($disp, $must, $readOnlyFlg, $explanation); break;
          case "ATENA_SUU": $this->SetATENA_SUU($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_RECEIVE_INFO_MAIL": $this->SetInfoMail($disp, $must, $readOnlyFlg, $default, $explanation); break;
          case "M_RECEIVE_INFO_FAX" : $this->SetInfoFax($disp, $must, $readOnlyFlg, $explanation); break;
          case "M_MLMAGA_FLG" : $this->SetM_MLMAGA_FLG($disp, $must, $readOnlyFlg, $default, $explanation); break;
      }
    }
    function SetDummyInputOpen(){
        $entry_setting3 = $GLOBALS['entry_setting3'];
        $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
        echo "<td class='".$RegValue."'>&nbsp;</td>";
    }
    function StartGroupData(){
      echo '
        <table width="900" align="center" border="0" cellspacing="1" cellpadding="3">
         <tr>
          <td width="120">■組織データ</td>
          <td width="4"><br></td>
          <td>
           <input type="checkbox" name="useRegisteredGid" style="display:none;">
          </td>
         </tr>
        </table>';
    }
    function SetGroupData(){
      if($chg !== "1"){
        echo
        '<div id="ginput1" style="display:none;">
            <table width="900" align="center" border="0" cellspacing="1" cellpadding="3">
            <tr>
              <td class="RegItem" width="120" name="td1">組織ID</td>
              <td class="RegValue">
                <input size="30" style="ime-mode: disabled;" type="text" name="G_G_ID2" value="<?php=fpgh("G_G_ID") ?>">
                <input type="button" value="検索" onClick="OnSearchGroup();">
              </td>
            </tr>
            </table>
        </div>
        <div id="ginput2">
            <table>
              <tr>
              </tr>
            </table>
        </div>
        <div id="pinput1" style="display:none;">
          <table width="900" align="center" border="0" cellspacing="1" cellpadding="3">
           <tr>
            <td class="RegItem" width="120" name="td1">個人ID
            </td>
            <td class="RegValue">
             <input size="30" style="ime-mode: inactive;" type="text" name="P_P_ID2" value="<?php=fpgh("P_P_ID") ?>">
             <input type="button" value="検索" onClick="OnSearchPersonal();">
            </td>
           </tr>
           <tr>
            <td class="RegItem">所属組織</td>
            <td class="RegValue">今回登録の組織になります</td>
           </tr>
           <tr>
            <td class="RegItem">所属</td>
            <td class="RegValue">
             <input style="ime-mode: active;" type="text" name="S_AFFILIATION_NAME2" value="'.fpgh("S_AFFILIATION_NAME").'">
            </td>
           </tr>
           <tr>
            <td class="RegItem">役職</td>
            <td class="RegValue">
             <input style="ime-mode: active;" type="text" name="S_OFFICIAL_POSITION2" value="'.fpgh("S_OFFICIAL_POSITION").'">
             <input type="button" value="辞書" onClick=ShowDictionaryWnd("mainForm", "S_OFFICIAL_POSITION2", "役職名"")>
            </td>
           </tr>
          </table>
        </div>
        <div id="pinput2" style="display:none;">
          <table width="900" align="center" border="0" cellspacing="1" cellpadding="3">
              <tr>
              </tr>
          </table>
        </div>';
      }
    }
    function SetG_G_ID($disp, $must, $explanation){
      $chg = $GLOBALS['chg'];
      $set_g_id = $GLOBALS['set_g_id'];
      $gd = $GLOBALS['gd'];
      $entry_setting3 = $GLOBALS['entry_setting3'];
      $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
      $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
      $ReadOnly = ($entry_setting3 == 1)?' ReadOnly':'';
      if(empty($gd->G_ID))
        $gd->G_ID = "";
      if(!$disp){
        $_SESSION['disp_JS_G_G_ID'] = false;
        if($chg == "1"){
           echo '<input type="hidden" name="G_G_ID2" value="'.$gd->G_ID.'">
            <input type="hidden" name="G_G_ID"  value="'.$gd->G_ID.'">';
        }else{
          echo '<input type="hidden" name="G_G_ID2" value="'.(!empty($_POST["G_G_ID"])?$_POST["G_G_ID"]:"").'">
            <input type="hidden" name="G_G_ID"  value='.$gd->G_ID.'">';
        }
      }
      else{
        $_SESSION['disp_JS_G_G_ID'] = true;
        echo '<td class="'.$RegItem.'" width="120" name="td1" nowrap>';
        echo ($must)?MUST_START_TAG:"";
        echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織ＩＤ")), "組織ＩＤ");
        echo ($must)?MUST_END_TAG:"";
        echo '</td>';
        echo '<td class="'.$RegValue.$ReadOnly.'">';
        if($chg == "1"){
          echo $gd->G_ID;
          echo '<input type="hidden" name="G_G_ID2" value="'.$gd->G_ID.'">
          <input type="hidden" name="G_G_ID" value="'.$gd->G_ID.'">';
        }
        else{
          if($set_g_id == ''){
            echo $gd->G_ID;
            echo '<input type="hidden" name="G_G_ID" value="'.$gd->G_ID.'">
            <input type="hidden" name="G_G_ID2" value="'.$gd->G_ID.'">';
          }
          else{
            echo $gd->G_ID;
            echo '<input type="hidden" name="G_G_ID2" value="'.$gd->G_ID.'">
            <input type="hidden" name="G_G_ID" value="'.$gd->G_ID.'">';
          }
        }
        if($explanation != ""){
          echo '<br><font color="red">'.$explanation.'</font>';
        }
        $gInputOpen = $GLOBALS['gInputOpen'];
        if($gInputOpen == "1"){
           $this->SetDummyInputOpen();
        }
      }
    }
    function SetG_NAME($disp, $must, $readOnlyFlg, $explanation, $dispIni){
      $gd = $GLOBALS['gd'];
      $gdh = $GLOBALS['gdh'];
      $chg = $GLOBALS['chg'];
      $gInputOpen = $GLOBALS['gInputOpen'];
      $entry_setting3 = $GLOBALS['entry_setting3'];
      $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
      $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
      $ReadOnly = ($entry_setting3 == 1)?' ReadOnly':'';
      if(empty($gd->G_NAME))
        $gd->G_NAME = "";
      if(!$disp):
        $_SESSION['PUTOUT_JS_G_NAME'] = false;
        echo '<input type="hidden" name="G_NAME" value="'.$gd->G_NAME.'">
        <input type="hidden" name="G_NAME_HIDDEN" value="1">
        <input type="hidden" name="G_O_NAME" value="'.$gd->O_G_NAME.'">
        <input type="hidden" name="G_O_NAME" value="'.$gd->O_G_NAME.'">';
      else:
        $_SESSION['PUTOUT_JS_G_NAME'] = true;
        if($_SESSION['LOGIN_TOPGID'] == NAK_DENKOU_ID):
          if($chg == "1"):
            echo '<td class="'.$RegItem .'" nowrap>';
            echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織名")), "組織名");
            echo '</td>';
            echo '<td class="'.$RegValue.$ReadOnly.'">';
            echo $gd->G_NAME;
            echo '<input type="hidden" name="G_NAME" value="'.$gd->G_NAME.'">';
          else:
            echo '<td class="'.$RegItem .''.$this->comp($gd->G_NAME, $gd->G_NAME).'" nowrap>';
            echo ($must)?MUST_START_TAG:"";
            echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織名")), "組織名");
            echo ($must)?MUST_END_TAG:"";
            echo '</td>';
            echo '<td class="'.$RegValue.'">
            <input style="ime-mode: active;" type="text" size="60" maxlength="80" name="G_NAME" autocomplete="nope" value="'.$gd->G_NAME.'" onChange="changeContactForm();changeClaimForm();">';
          endif;
        else:
          echo '<td class="'.$RegItem.$this->comp(isset($gd->G_NAME)?$gd->G_NAME:"",isset($gd->G_NAME)?$gd->G_NAME:"").'" nowrap>';
          echo ($must)?MUST_START_TAG:"";
          echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織名")), "組織名");
          echo ($must)?MUST_END_TAG:"";
          echo '</td>';
          if(!$readOnlyFlg || $chg != "1"):
            $vgName = (empty($gd->G_NAME)?"":$gd->G_NAME);
            echo '<td class="'.$RegValue.'">';
            echo '<input style="ime-mode: active;" type="text" size="60" maxlength="80" name="G_NAME"  autocomplete="nope" value="'.$vgName.'">';
          else:
            echo '<td class="'.$RegValue.$ReadOnly.'">';
            echo isset($gd->G_NAME)?$gd->G_NAME:"";
            echo '<input type="hidden" name="G_NAME" value="'.$gd->G_NAME.'">';
          endif;
        endif;
        if($explanation != ""):
          echo '<br><font color="red">'.$explanation.'</font>';
        endif;
        echo '<input type="hidden" name="G_NAME_HIDDEN" value="0">';
        echo '</td>';
        if($gInputOpen == "1"): ?>
          <td class="<?php echo $RegValue.$this->comp($gd->O_G_NAME, $gd->O_G_NAME); ?>">
            <select name="G_O_NAME" style="max-width: 100%; width: 100%; padding: 0px;">
            <option value="0" <?php echo ($gd->O_G_NAME == "0")?"selected":""; ?>>公開しない</option>
            <option value="1" <?php echo ($gd->O_G_NAME == "1")?"selected":""; ?>>一般公開</option>
            <option value="2" <?php echo ($gd->O_G_NAME == "2")?"selected":""; ?>>会員にのみ公開</option>
            </select>
          </td>
        <?php
        else:
          echo '<input type="hidden" name="G_O_NAME" value="'.(empty($gd))?"":$gd->G_NAME.'">';
          echo '<input type="hidden" name="G_O_NAME" value="'.$this->getdispIni((empty($gd->G_NAME))?"":$gd->G_NAME, $dispIni).'">';
        endif;
      endif;
    }
    function SetG_NAME_KN($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $chg = $GLOBALS['chg'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  if(empty($gd->G_NAME_KN))
    $gd->G_NAME_KN = "";
  if(empty($gd->O_G_NAME_KN))
    $gd->O_G_NAME_KN = "0";
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->O_G_NAME_KN)){
    $gd->O_G_NAME_KN = "";
  }
  if(empty($gd->G_NAME_KN)){
    $gd->G_NAME_KN = '';
  }
  if(!$disp):
    $_SESSION['PUTOUT_JS_G_KANA'] = false;?>
    <input type="hidden" name="G_NAME_KN" value="<?php echo ($gd->G_NAME_KN == "")?"　":$gd->G_NAME_KN; ?>">
    <input type="hidden" name="G_O_KANA" value="<?php echo $gd->O_G_NAME_KN ?>">
  <?php
    else:
      $_SESSION['PUTOUT_JS_G_KANA'] = true; ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->G_NAME_KN)?$gd->G_NAME_KN:"",!empty($gd->G_NAME_KN)?$gd->G_NAME_KN:"") ?>" nowrap>
        <?php
          echo ($must)?MUST_START_TAG:"";
          echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織フリガナ")), "組織フリガナ");
          echo ($must)?MUST_END_TAG:"";
        ?>
      </td>
      <?php
        if(($set_g_id == "" && !$readOnlyFlg) || $chg !== "1"): ?>
        <td class="<?php echo $RegValue; ?>">
           <input style="ime-mode: active;" type="text" size="60" maxlength="80" name="G_NAME_KN" value="<?php echo (empty($gd->G_NAME_KN))?"":$gd->G_NAME_KN; ?>" onChange="Javascript:funcHiraganaToKatakana(this);">
      <?php else: ?>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $gd->G_NAME_KN ?>
          <input type="hidden" name="G_NAME_KN" value="<?php echo $gd->G_NAME_KN; ?>">
      <?php endif; ?>
      <?php if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
      <?php endif; ?>
      </td>
      <?php if($gInputOpen == "1"): ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_NAME_KN, $gd->O_G_NAME_KN); ?>">
          <select name="G_O_KANA" style="max-width: 100%; width: 100%; padding: 0px;">
          <option value="0" <?php echo ($gd->O_G_NAME_KN == "0")?"selected":""; ?>>公開しない</option>
          <option value="1" <?php echo ($gd->O_G_NAME_KN == "1")?"selected":""; ?>>一般公開</option>
          <option value="2" <?php echo ($gd->O_G_NAME_KN == "2")?"selected":""; ?>>会員にのみ公開</option>
          </select>
        </td>
      <?php else: ?>
        <input type="hidden" name="G_O_KANA" value="<?php echo $this->getdispIni(!empty($gd->O_G_NAME_KN)?$gd->O_G_NAME_KN:"", $dispIni) ?>">
    <?php endif;
    endif;
}

function SetG_NAME_EN($disp, $must, $explanation){
  $chg = $GLOBALS['chg'];
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $ReadOnly = ($entry_setting3 == 1)?' ReadOnly':'';
  if(!$disp){
    if($chg == "1"){
     echo '<input type="hidden" name="G_NAME_EN" value="'.$gd->G_NAME_EN.'">';
    }else{
      echo '<input type="hidden" name="G_NAME_EN" value="">';
    }
  }
  else{
    echo '<td class="'.$RegItem.'" width="120" name="td1" nowrap>';
    echo ($must)?MUST_START_TAG:"";
    echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織名英語表記")), "組織名英語表記");
    echo ($must)?MUST_END_TAG:"";
    echo '</td>';
    echo '<td class="'.$RegValue.$ReadOnly.'">';
      if($chg == "1"){
        echo '<input type="text" name="G_NAME_EN" value="'.$gd->G_NAME_EN.'">';
      }
      else{
        echo '<input type="text" name="G_NAME_EN" value="">';
      }
      if($explanation != ""){
        echo '<br><font color="red">'.$explanation.'</font>';
      }
    echo '</td>';
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  }
}

function SetG_ADR_EN($disp, $must, $explanation){
  $chg = $GLOBALS['chg'];
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $ReadOnly = ($entry_setting3 == 1)?' ReadOnly':'';
  if(!$disp){
    if($chg == "1"){
     echo '<input type="hidden" name="G_ADR_EN" value="'.$gd->G_ADR_EN.'">';
    }else{
      echo '<input type="hidden" name="G_ADR_EN" value="">';
    }
  }
  else{
    echo '<td class="'.$RegItem.'" width="120" name="td1" nowrap>';
    echo ($must)?MUST_START_TAG:"";
    echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織住所　英語表記")), "組織住所　英語表記");
    echo ($must)?MUST_END_TAG:"";
    echo '</td>';
    echo '<td class="'.$RegValue.$ReadOnly.'">';
      if($chg == "1"){
        echo '<input type="text" name="G_ADR_EN" value="'.$gd->G_ADR_EN.'">';
      }
      else{
        echo '<input type="text" name="G_ADR_EN" value="">';
      }
      if($explanation != ""){
        echo '<br><font color="red">'.$explanation.'</font>';
      }
    echo '</td>';
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  }
}

function SetG_LNG_MODE($disp, $must, $explanation){
  $chg = $GLOBALS['chg'];
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $ReadOnly = ($entry_setting3 == 1)?' ReadOnly':'';
  if(!$disp){
    if($chg == "1"){
     echo '<input type="hidden" name="G_LNG_MODE" value="'.$gd->LNG_MODE.'">';
    }else{
      echo '<input type="hidden" name="G_LNG_MODE" value="">';
    }
  }
  else{
    echo '<td class="'.$RegItem.'" width="120" name="td1" nowrap>';
    echo ($must)?MUST_START_TAG:"";
    echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織住所　英語表記")), "組織住所　英語表記");
    echo ($must)?MUST_END_TAG:"";
    echo '</td>';
    echo '<td class="'.$RegValue.$ReadOnly.'">';
      if($chg == "1"){
        ?>
        <input type="radio" name="G_LNG_MODE" value="0" <?php echo ((($gd->LNG_MODE)?$gd->LNG_MODE:0) == "0")?"checked":""; ?>><label>日本語</label>
        <input type="radio" name="G_LNG_MODE" value="1" <?php echo ($gd->LNG_MODE == "1")?"checked":""; ?>><label>英語</label>
        <?php
      }
      else{
        ?>
        <input type="radio" name="G_LNG_MODE" value="0" checked><label>日本語</label>
        <input type="radio" name="G_LNG_MODE" value="1"><label>英語</label>
        <?php
      }
      if($explanation != ""){
        echo '<br><font color="red">'.$explanation.'</font>';
      }
    echo '</td>';
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  }
}

function SetM_CONTACT_LNG_MODE($disp, $must, $explanation){
  $chg = $GLOBALS['chg'];
  $set_g_id = $GLOBALS['set_g_id'];
  $md = $GLOBALS['md'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $ReadOnly = ($entry_setting3 == 1)?' ReadOnly':'';
  if(!$disp){
    if($chg == "1"){
     echo '<input type="hidden" name="M_CONTACT_LNG_MODE" value="'.$md->CONTACT_LNG_MODE.'">';
    }else{
      echo '<input type="hidden" name="M_CONTACT_LNG_MODE" value="">';
    }
  }
  else{
    echo '<td class="'.$RegItem.'" width="120" name="td1" nowrap>';
    echo ($must)?MUST_START_TAG:"";
    echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先氏名・住所の表記")), "連絡先氏名・住所の表記");
    echo ($must)?MUST_END_TAG:"";
    echo '</td>';
    echo '<td class="'.$RegValue.$ReadOnly.'">';
      if($chg == "1"){
        ?>
        <input type="radio" name="M_CONTACT_LNG_MODE" value="0" <?php echo ((($md->CONTACT_LNG_MODE)?$md->CONTACT_LNG_MODE:0) == "0")?"checked":""; ?>><label>日本語</label>
        <input type="radio" name="M_CONTACT_LNG_MODE" value="1" <?php echo ($md->CONTACT_LNG_MODE == "1")?"checked":""; ?>><label>英語</label>
        <?php
      }
      else{
        ?>
        <input type="radio" name="M_CONTACT_LNG_MODE" value="0" checked><label>日本語</label>
        <input type="radio" name="M_CONTACT_LNG_MODE" value="1"><label>英語</label>
        <?php
      }
      if($explanation != ""){
        echo '<br><font color="red">'.$explanation.'</font>';
      }
    echo '</td>';
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  }
}

function SetM_BILLING_LNG_MODE($disp, $must, $explanation){
  $chg = $GLOBALS['chg'];
  $set_g_id = $GLOBALS['set_g_id'];
  $md = $GLOBALS['md'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $ReadOnly = ($entry_setting3 == 1)?' ReadOnly':'';
  if(!$disp){
    if($chg == "1"){
     echo '<input type="hidden" name="M_BILLING_LNG_MODE" value="'.$md->BILLING_LNG_MODE.'">';
    }else{
      echo '<input type="hidden" name="M_BILLING_LNG_MODE" value="">';
    }
  }
  else{
    echo '<td class="'.$RegItem.'" width="120" name="td1" nowrap>';
    echo ($must)?MUST_START_TAG:"";
    echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先氏名・住所の表記")), "請求先氏名・住所の表記");
    echo ($must)?MUST_END_TAG:"";
    echo '</td>';
    echo '<td class="'.$RegValue.$ReadOnly.'">';
      if($chg == "1"){
        ?>
        <input type="radio" name="M_BILLING_LNG_MODE" value="0" <?php echo ((($md->BILLING_LNG_MODE)?$md->BILLING_LNG_MODE:0) == "0")?"checked":""; ?>><label>日本語</label>
        <input type="radio" name="M_BILLING_LNG_MODE" value="1" <?php echo ($md->BILLING_LNG_MODE == "1")?"checked":""; ?>><label>英語</label>
        <?php
      }
      else{
        ?>
        <input type="radio" name="M_BILLING_LNG_MODE" value="0" checked><label>日本語</label>
        <input type="radio" name="M_BILLING_LNG_MODE" value="1"><label>英語</label>
        <?php
      }
      if($explanation != ""){
        echo '<br><font color="red">'.$explanation.'</font>';
      }
    echo '</td>';
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  }
}

function SetG_USER_ID($disp, $must, $explanation){
  $chg = $GLOBALS['chg'];
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $ReadOnly = ($entry_setting3 == 1)?' ReadOnly':'';
  if(!$disp){
    if($chg == "1"){
     echo '<input type="hidden" name="G_USER_ID" value="'.$gd->G_ID.'">';
    }else{
      echo '<input type="hidden" name="G_USER_ID" value="">';
    }
  }
  else{
    $required = ($must)?"required":"";
    echo '<td class="'.$RegItem.'" width="120" name="td1" nowrap>';
    echo ($must)?MUST_START_TAG:"";
    echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("団体管理組織ＩＤ")), "団体管理組織ＩＤ");
    echo ($must)?MUST_END_TAG:"";
    echo '</td>';
    echo '<td class="'.$RegValue.$ReadOnly.'">';
      if($chg == "1"){
        echo '<input type="text" name="G_USER_ID" '.$required.' value="'.$gd->G_USER_ID.'">';
      }
      else{
        echo '<input type="text" name="G_USER_ID" '.$required.' value="">';
      }
      if($explanation != ""){
        echo '<br><font color="red">'.$explanation.'</font>';
      }
    echo '</td>';
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  }
}

function SetG_REPRESENTATIVE_NM($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->REPRESENTATIVE_NM))
    $gd->REPRESENTATIVE_NM = "";
  if(!$disp):
    $_SESSION["PUTOUT_JS_G_REPRESENTATIVE_NM"] = false; ?>
    <input type="hidden" name="G_REPRESENTATIVE_NM"  value="<?php echo $gd->REPRESENTATIVE_NM; ?>">
    <input type="hidden" name="G_O_REPRESENTATIVE" value="<?php echo $gd->O_REPRESENTATIVE_NM; ?>">
  <?php
  else:
    $_SESSION["PUTOUT_JS_G_REPRESENTATIVE_NM"] = true; ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->REPRESENTATIVE_NM)?$gd->REPRESENTATIVE_NM:"", !empty($gd->REPRESENTATIVE_NM)?$gd->REPRESENTATIVE_NM:""); ?>" nowrap>
      <?php
        echo ($must)?MUST_START_TAG:"";
        echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("代表者")), "代表者");
        echo ($must)?MUST_END_TAG:"";
      ?>
    </td>
    <?php
    if($set_g_id == "" && !$readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?>">
        <input size="50" style="ime-mode: active;" type="text" name="G_REPRESENTATIVE_NM" value="<?php echo !empty($gd->REPRESENTATIVE_NM)?$gd->REPRESENTATIVE_NM:""; ?>">
    <?php
    else:
    ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $gd->REPRESENTATIVE_NM; ?>
        <input type="hidden" name="G_REPRESENTATIVE_NM"  value="<?php $gd->REPRESENTATIVE_NM; ?>">
    <?php
    endif;
    ?>
    <?php if($explanation !== ""):?>
    <br><font color="red"><?php echo $explanation ?></font>
    <?php endif; ?>
    </td>
    <?php
    if($gInputOpen == "1"):
    ?>
    <td class="<?php echo $RegValue; ?><?php $this->comp($gd->O_REPRESENTATIVE_NM, $gd->O_REPRESENTATIVE_NM); ?>">
      <select name="G_O_REPRESENTATIVE" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($gd->O_REPRESENTATIVE_NM) == "0"?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($gd->O_REPRESENTATIVE_NM) == "1"?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($gd->O_REPRESENTATIVE_NM) == "2"?"selected":""; ?>>会員にのみ公開</option>
      </select>
    </td>
    <?php
    else:
    ?>
      <input type="hidden" name="G_O_REPRESENTATIVE" value="<?php echo $gd->O_REPRESENTATIVE_NM; ?>">
    <?php

    endif;
  endif;
} //End Sub

function SetG_REPRESENTATIVE_KN($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->REPRESENTATIVE_KN))
    $gd->REPRESENTATIVE_KN = "";
  if(!$disp):
    $_SESSION["PUTOUT_JS_G_REPRESENTATIVE_KN"] = False; ?>
    <input type="hidden" name="G_REPRESENTATIVE_KN"  value="<?php echo $gd->REPRESENTATIVE_KN ?>">
    <input type="hidden" name="G_O_REPRESENTATIVE_KANA" value="<?php echo $gd->O_REPRESENTATIVE_KN;?>">
    <?php
  else:
    $_SESSION["PUTOUT_JS_G_REPRESENTATIVE_KN"] = true;
        ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->REPRESENTATIVE_KN)?$gd->REPRESENTATIVE_KN:"", !empty($gd->REPRESENTATIVE_KN)?$gd->REPRESENTATIVE_KN:"") ?>" nowrap>
          <?php
            echo ($must)?MUST_START_TAG:"";
            echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("代表者フリガナ")), "代表者フリガナ");
            echo ($must)?MUST_END_TAG:"";
          ?>
        </td>
        <?php
          if($set_g_id == "" && !$readOnlyFlg): ?>
            <td class="<?php echo $RegValue; ?>">
              <input size="50" style="ime-mode: active;" type="text" name="G_REPRESENTATIVE_KN" value="<?php echo !empty($gd->REPRESENTATIVE_KN)?$gd->REPRESENTATIVE_KN:""; ?>" onChange="Javascript:funcHiraganaToKatakana(this);">
            <?php
          else:
            ?>
            <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
              <?php echo $gd->REPRESENTATIVE_KN ?>
              <input type="hidden" name="G_REPRESENTATIVE_KN"  value="<?php echo $gd->REPRESENTATIVE_KN ?>">
            <?php
          endif; ?>
        <?php if($explanation !== ""): ?>
          <br><font color="red"><?php echo $explanation; ?></font>
      <?php endif; ?>
      </td>
      <?php
          if($gInputOpen == "1"):
      ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_REPRESENTATIVE_KN, $gd->O_REPRESENTATIVE_KN) ?>">
        <select name="G_O_REPRESENTATIVE_KANA" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($gd->O_REPRESENTATIVE_KN == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($gd->O_REPRESENTATIVE_KN == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($gd->O_REPRESENTATIVE_KN == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
      <?php
          else:
      ?>
        <input type="hidden" name="G_O_REPRESENTATIVE_KANA" value="<?php echo $gd->O_REPRESENTATIVE_KN; ?>">
      <?php
        endif;
  endif;
} //End Sub


function SetG_NAME_AN($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->G_NAME_AN))
    $gd->G_NAME_AN = "";
  if(!$disp): ?>
    <input type="hidden" name="G_NAME_AN" value="<?php $gd->G_NAME_AN; ?>">
    <input type="hidden" name="G_O_SNAME" value="<?php $gd->O_G_NAME_AN; ?>">
    <input type="hidden" name="G_O_SNAME" value="<?php $this->getdispIni($gd->O_G_NAME_AN, $dispIni); ?>">
  <?php else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($gd->G_NAME_AN, $gd->G_NAME_AN) ?>" nowrap>
      <?php
        echo ($must)?MUST_START_TAG:"";
        echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織名略称")), "組織名略称");
        echo ($must)?MUST_END_TAG:"";
      ?>
    </td>
    <?php
  if($set_g_id == "" && !$readOnlyFlg):?>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: active;" type="text" size="60" maxlength="80" name="G_NAME_AN" value="<?php echo $gd->G_NAME_AN; ?>">
  <?php else: ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $gd->G_NAME_AN; ?>
      <input type="hidden" name="G_NAME_AN" value="<?php echo $gd->G_NAME_AN; ?>">
  <?php
  endif;
  if($explanation !== ""):?>
    <br><font col||="red"><?php echo $explanation ?></font>
  <?php endif; ?>
    </td>
  <?php
    if($gInputOpen == "1"):
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_NAME_AN, $gd->O_G_NAME_AN); ?>">
          <?php echo $this->openLevelText($gd->O_G_NAME_AN) ?>
          <input type="hidden" name="G_O_SNAME" value="<?php echo $gd->O_G_NAME_AN; ?>">
          <input type="hidden" name="G_O_SNAME" value="<?php echo $this->getdispIni($gd->O_G_NAME_AN, $dispIni); ?>">
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_NAME_AN, $gd->O_G_NAME_AN) ?>">
          <select name="G_O_SNAME" style="max-width: 100%; width: 100%; padding: 0px;">
          <option value="0" <?php ($this->getdispIni($gd->O_G_NAME_AN, $dispIni) == "0")?"selected":""; ?>>公開しない</option>
          <option value="1" <?php ($this->getdispIni($gd->O_G_NAME_AN, $dispIni) == "1")?"selected":""; ?>>一般公開</option>
          <option value="2" <?php ($this->getdispIni($gd->O_G_NAME_AN, $dispIni) == "2")?"selected":""; ?>>会員にのみ公開</option>
          </select>
        </td>
    <?php
      endif;
    else: ?>
        <input type="hidden" name="G_O_SNAME" value="<?php echo $gd->O_G_NAME_AN ?>">
        <input type="hidden" name="G_O_SNAME" value="<?php echo $this->getdispIni($gd->O_G_NAME_AN, $dispIni) ?>">
    <?php
    endif;
  endif;
}

function SetG_POST($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $page_link = $this->getPageSlug('nakama-member-zipcode');
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $postid = $GLOBALS['postid'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  $g_post_l = $g_post_u = "";
  if(!empty($gd->G_POST)){
    $g_post_u = explode("-",$gd->G_POST)[0];
    $g_post_l = explode("-",$gd->G_POST)[1];
  }else {
    $gd->G_POST = "";
  }
  if(!$disp):
      $_SESSION["PUTOUT_JS_G_POST"] = false; ?>
      <input type="hidden" name="G_POST_u" value="<?php echo $g_post_u; ?>">
      <input type="hidden" name="G_POST_l" value="<?php echo $g_post_l; ?>">
      <input type="hidden" name="G_POST"   value="<?php echo !empty($gd->G_POST)?$gd->G_POST:""; ?>">
      <input type="hidden" name="G_O_POST" value="<?php echo $gd->O_G_POST; ?>">
  <?php
  else:
    $_SESSION["PUTOUT_JS_G_POST"] = true; ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->G_POST)?$gd->G_POST:"", !empty($gd->G_POST)?$gd->G_POST:""); ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織〒")), "組織〒"); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php if($set_g_id == "" && !$readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?>">
        <input style="ime-mode: disabled;" type="text" class="w_60px" name="G_POST_u" maxlength="3" value="<?php echo $g_post_u; ?>" onChange="changeContactForm();changeClaimForm();">&nbsp;-
        <input style="ime-mode: disabled;" type="text" class="w_60px" name="G_POST_l" maxlength="4" value="<?php echo $g_post_l; ?>" onChange="changeContactForm();changeClaimForm();">
        <input type="hidden" name="G_POST" value="<?php echo !empty($gd->G_POST)?$gd->G_POST:""; ?>">
        <input type="button" value="住所検索" onClick="OnZipCode('G_POST_u','G_POST_l', <?php echo $postid; ?>, '<?php echo $page_link; ?>');">
    <?php else: ?>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $gd->G_POST; ?>
          <input type="hidden" name="G_POST_u" value="<?php echo $g_post_u; ?>">
          <input type="hidden" name="G_POST_l" value="<?php echo $g_post_l; ?>">
          <input type="hidden" name="G_POST"   value="<?php echo !empty($gd->G_POST)?$gd->G_POST:""; ?>">
        <?php
        endif;
    ?>

    <?php if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation; ?></font>
    <?php endif; ?>
    </td>
    <?php
        if($gInputOpen == "1"):
          if($readOnlyFlg): ?>
            <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_POST, $gd->O_G_POST) ?>">
              <?php echo $this->openLevelText($gd->O_G_POST) ?>
              <input type="hidden" name="G_O_POST" value="<?php echo $gd->O_G_POST; ?>">
            </td>
        <?php
          else:
        ?>
          <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_POST, $gd->O_G_POST) ?>">
            <select name="G_O_POST" style="max-width: 100%; width: 100%; padding: 0px;">
            <option value="0" <?php echo ($gd->O_G_POST == "0")?"selected":""; ?>>公開しない</option>
            <option value="1" <?php echo ($gd->O_G_POST == "1")?"selected":""; ?>>一般公開</option>
            <option value="2" <?php echo ($gd->O_G_POST == "2")?"selected":""; ?>>会員にのみ公開</option>
            </select>
          </td>
        <?php
        endif;
      else:
      ?>
        <input type="hidden" name="G_O_POST" value="<?php echo $gd->O_G_POST; ?>">
      <?php
      endif;
  endif;
} //End Sub

function SetG_STA($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $tg_id = $GLOBALS['tg_id'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->G_STA))
    $gd->G_STA = "";
  if(!$disp):
    $_SESSION["PUTOUT_JS_G_STA"] = false; ?>
    <input type="hidden" name="G_STA" value="<?php echo $gd->G_STA; ?>">
    <input type="hidden" name="G_STA_HIDDEN" value="1">
    <input type="hidden" name="G_O_STA" value="<?php echo $gd->O_G_STA; ?>">
    <?php
  else:
    $_SESSION["PUTOUT_JS_G_STA"] = true;
    if($tg_id == NAK_DENKOU_ID):
      if(pgh("$chg") == "1"): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
            <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織都道府県")), "組織都道府県"); ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $gd->G_STA; ?>
          <input type="hidden" name="G_STA" value="<?php echo !empty($gd->G_STA)?$gd->G_STA:""; ?>">
        <?php
      else: ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp($gd->G_STA, $gd->G_STA) ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織都道府県")), "組織都道府県"); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?>">
      <select name="G_STA" onChange="changeContactForm();changeClaimForm();">
        <script type="text/javascript">StateSelectOptions2("<?php echo !empty($gd->G_STA)?$gd->G_STA:""; ?>");</script>
      </select>
    <?php
      endif;
    else: ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->G_STA)?$gd->G_STA:"", !empty($gd->G_STA)?$gd->G_STA:"") ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織都道府県")), "組織都道府県"); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
    <?php
      if($set_g_id == "" && !$readOnlyFlg):
      ?>
      <td class="<?php echo $RegValue; ?>">
        <select name="G_STA" onChange="changeContactForm();changeClaimForm();">
          <script type="text/javascript">StateSelectOptions2("<?php echo !empty($gd->G_STA)?$gd->G_STA:""; ?>");</script>
        </select>
      <?php
      else:
      ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $gd->G_STA; ?>
        <input type="hidden" name="G_STA" value="<?php echo !empty($gd->G_STA)?$gd->G_STA:""; ?>">
      <?php
      endif;
    endif;
    ?>
  <input type="hidden" name="G_STA_HIDDEN" value="0">
  <?php if($explanation !== ""): ?>
      <br><font color="red"><?php echo $explanation ?></font>
  <?php endif; ?>
  </td>
  <?php
    if($gInputOpen == "1"):
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_STA, $gd->O_G_STA); ?>">
          <?php echo $this->openLevelText($gd->O_G_STA); ?>
          <input type="hidden" name="G_O_STA" value="<?php echo $gd->O_G_STA; ?>">
        </td>
    <?php
      else:
    ?>
    <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_STA, $gd->O_G_STA); ?>">
      <select name="G_O_STA" style="max-width: 100%; width: 100%; padding: 0px;">
      <option value="0" <?php echo ($gd->O_G_STA == "0")?"selected":""; ?>>公開しない</option>
      <option value="1" <?php echo ($gd->O_G_STA == "1")?"selected":""; ?>>一般公開</option>
      <option value="2" <?php echo ($gd->O_G_STA == "2")?"selected":""; ?>>会員にのみ公開</option>
      </select>
    </td>
    <?php
      endif;
    else: ?>
        <input type="hidden" name="G_O_STA" value="<?php echo $gd->O_G_STA; ?>">
    <?php
    endif;
  endif;
} //End Sub

function SetG_ADR($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->G_ADR))
    $gd->G_ADR = "";
  if(!$disp):
    $_SESSION["PUTOUT_JS_G_ADR"] = false; ?>
    <input type="hidden" name="G_ADR" value="<?php echo $gd->G_ADR; ?>">
    <input type="hidden" name="G_ADR_HIDDEN" value="1">
    <input type="hidden" name="G_O_ADDRESS" value="<?php echo $gd->O_G_ADR; ?>">
    <?php
  else:
    $_SESSION["PUTOUT_JS_G_ADR"] = true; ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->G_ADR)?$gd->G_ADR:"", !empty($gd->G_ADR)?$gd->G_ADR:"") ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織住所１")), "組織住所１"); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php
    if($set_g_id == "" && !$readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?>">
      <?php if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) && $_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
        <input style="ime-mode: active;" type="text" size="60" maxlength="250" name="G_ADR" value="<?php echo !empty($gd->G_ADR)?$gd->G_ADR:""; ?>" onChange="Javascript:funcHanToZen(this);">
      <?php else: ?>
        <input style="ime-mode: active;" type="text" size="60" maxlength="250" name="G_ADR" value="<?php echo !empty($gd->G_ADR)?$gd->G_ADR:""; ?>" onChange="changeContactForm();changeClaimForm();">
      <?php endif; ?>
        <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $gd->G_ADR; ?>
        <input type="hidden" name="G_ADR" value="<?php echo $gd->G_ADR; ?>">
      <?php
    endif; ?>
    <input type="hidden" name="G_ADR_HIDDEN" value="0">
    <?php if($explanation !== ""):  ?>
    <br><font color="red"><?php echo $explanation ?></font>
    <?php endif; ?>
    </td>
    <?php
    if($gInputOpen == "1"):
      if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_ADR, $gd->O_G_ADR); ?>" rowspan="2">
        <?php echo $this->openLevelText($gd->O_G_ADR); ?>
        <input type="hidden" name="G_O_ADDRESS" value="<?php echo $gd->O_G_ADR; ?>">
      </td>
      <?php else:?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_ADR,$gd->O_G_ADR); ?>" rowspan="2">
          <select name="G_O_ADDRESS" style="max-width: 100%; width: 100%; padding: 0px;">
          <option value="0" <?php echo ($gd->O_G_ADR == "0")?"selected":""; ?>>公開しない</option>
          <option value="1" <?php echo ($gd->O_G_ADR == "1")?"selected":""; ?>>一般公開</option>
          <option value="2" <?php echo ($gd->O_G_ADR == "2")?"selected":""; ?>>会員にのみ公開</option>
          </select>
        </td>
    <?php
      endif;
    else: ?>
        <input type="hidden" name="G_O_ADDRESS" value="<?php echo $gd->O_G_ADR; ?>">
  <?php endif;
  endif;
} //End Sub

function SetG_ADR2($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->G_ADR2))
    $gd->G_ADR2 = "";
  if(!$disp):
    $_SESSION["PUTOUT_JS_G_ADR2"] = false; ?>
    <input type="hidden" name="G_ADR2" value="<?php echo $gd->G_ADR2; ?>">
    <input type="hidden" name="G_ADR2_HIDDEN" value="1">
    <?php
  else:
    $_SESSION["PUTOUT_JS_G_ADR2"] = true; ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->G_ADR2)?$gd->G_ADR2:"", !empty($gd->G_ADR2)?$gd->G_ADR2:"") ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織住所２")), "組織住所２"); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php if($set_g_id == "" && !$readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?>">
        <?php if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) &&$_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
          <input style="ime-mode: active;" type="text" size="60" maxlength="50" name="G_ADR2" value="<?php echo !empty($gd->G_ADR2)?$gd->G_ADR2:""; ?>" onChange="Javascript:funcHanToZen(this);">
        <?php else: ?>
           <input style="ime-mode: active;" type="text" size="60" maxlength="50" name="G_ADR2" value="<?php echo !empty($gd->G_ADR2)?$gd->G_ADR2:""; ?>" onChange="changeContactForm();changeClaimForm();">
        <?php endif; ?>
    <?php else: ?>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $gd->G_ADR2; ?>
          <input type="hidden" name="G_ADR2" value="<?php echo $gd->G_ADR2; ?>">
    <?php endif; ?>
    <input type="hidden" name="G_ADR2_HIDDEN" value="0">
    <?php if($explanation !== ""):  ?>
    <br>
      <font color="red">
        <?php echo $explanation ?>
      </font>
    <?php endif; ?>
    </td>
 <?php endif;
} //End Sub

function SetG_ADR3($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->G_ADR3))
    $gd->G_ADR3 = "";
  if(!$disp): ?>
    <input type="hidden" name="G_ADR3" value="<?php echo $gd->G_ADR3; ?>">
  <?php
  else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->G_ADR3)?$gd->G_ADR3:"", !empty($gd->G_ADR3)?$gd->G_ADR3:"") ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織住所３")), "組織住所３"); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php if($set_g_id == "" && !$readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?>">
        <?php if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) &&$_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
          <input style="ime-mode: active;" type="text" size="60" maxlength="50" name="G_ADR3" value="<?php echo !empty($gd->G_ADR3)?$gd->G_ADR3:""; ?>" onChange="Javascript:funcHanToZen(this);">
        <?php else: ?>
           <input style="ime-mode: active;" type="text" size="60" maxlength="50" name="G_ADR3" value="<?php echo !empty($gd->G_ADR3)?$gd->G_ADR3:""; ?>" onChange="changeContactForm();changeClaimForm();">
        <?php endif; ?>
    <?php else: ?>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $gd->G_ADR3; ?>
          <input type="hidden" name="G_ADR3" value="<?php echo $gd->G_ADR3; ?>">
    <?php endif; ?>
    <?php if($explanation !== ""):  ?>
    <br>
      <font color="red">
        <?php echo $explanation ?>
      </font>
    <?php endif; ?>
    </td>
    <?php 
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
        $this->SetDummyInputOpen();
      }
endif;
 
} //End Sub

function SetG_IMG($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->G_IMG))
    $gd->G_IMG = "";
  if(!$disp): ?>
    <input type="hidden" name="G_IMG">
    <input type="hidden" name="m_delImgG" value="">
    <input type="hidden" name="m_curImgG" value="<?php echo $gd->G_IMG; ?>">
    <input type="hidden" name="G_O_IMG" value="<?php echo $gd->O_G_IMG; ?>">
  <?php
  else: ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->G_IMG)?$gd->G_IMG:"",!empty($gd->G_IMG)?$gd->G_IMG:"") ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("掲載画像")), "掲載画像"); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <?php if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?>">
          <input type="file" size="60" maxlength="80" name="G_IMG"><br>
            
              <input type="checkbox" name="m_delImgG" value="1" <?php echo (!empty($_POST["m_delImgG"]) && $_POST["m_delImgG"] == "1")?"checked":""; ?>>削除
              <input type="hidden" name="m_curImgG" value="<?php echo !empty($_POST["m_curImgG"])?$_POST["m_curImgG"]:(!empty($gd->G_IMG)?$gd->G_IMG:""); ?>">
              <?php if(!empty($gd->G_IMG) && $gd->G_IMG != ""): ?>
                  <?php echo $gd->G_IMG; ?><br>
                  <a href="javascript:ShowImage('<?php echo $gd->G_IMG; ?>');">
                    <?php echo $this->dispImgSrc($gd->G_IMG); ?>
                    </a>
              <?php endif; ?>
              
            <?php 
      else: ?>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php if($gd->G_IMG !== ""): ?>
          <?php echo $gd->G_IMG; ?><br>
        <?php echo $this->dispImgSrc($gd->G_IMG); ?>
        <?php endif; ?>
        <input type="hidden" name="G_IMG">
        <input type="hidden" name="m_delImgG" value="">
        <input type="hidden" name="m_curImgG" value="<?php echo $gd->G_IMG; ?>">
      <?php
      endif;?>
      <?php if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation; ?></font>
      <?php endif; ?>
    </td>
    <input type="hidden" name="G_O_IMG" value="<?php echo $gd->O_G_IMG; ?>">
      <?php
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
  endif;
} //End Sub

function SetG_APPEAL($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->G_APPEAL))
    $gd->G_APPEAL = "";
  if(!$disp):
    $_SESSION["PUTOUT_JS_G_APPEAL"] = false; ?>
    <input type="hidden" name="G_APPEAL" value="<?php echo htmlspecialchars($gd->G_APPEAL) ?>">
    <input type="hidden" name="G_O_APPEAL" value="<?php echo $gd->O_G_APPEAL; ?>">
    <?php
  else:
        $_SESSION["PUTOUT_JS_G_APPEAL"] = true;
    ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->G_APPEAL)?$gd->G_APPEAL:"", !empty($gd->G_APPEAL)?$gd->G_APPEAL:"") ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織アピール")), "組織アピール"); ?><br>（５００文字以内）
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php
        if($set_g_id == "" && !$readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?>">
      <textarea style="ime-mode:active; width:100%; line-height:150%; max-width: 100%" cols="58" rows="17" name="G_APPEAL"><?php echo htmlspecialchars(!empty($gd->G_APPEAL)?$gd->G_APPEAL:"") ?></textarea>
    <?php
        else:
    ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <textarea class="ReadOnly" style="ime-mode:active; width:100%; line-height:150%; max-width: 100%" cols="58" rows="17" name="G_APPEAL" readonly><?php echo htmlspecialchars(!empty($gd->G_APPEAL)?$gd->G_APPEAL:""); ?></textarea>
    <?php
    endif;
    ?>

    <?php if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>
    <input type="hidden" name="G_O_APPEAL" value="<?php echo $gd->O_G_APPEAL; ?>">
    <?php
        if($gInputOpen == "1"){
           $this->SetDummyInputOpen();
        }
 endif;
} //End Sub

function SetG_LOGO($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->G_LOGO))
    $gd->G_LOGO = "";
  if(!$disp): ?>
    <input type="hidden" name="G_LOGO">
    <input type="hidden" name="m_delLogoG" value="">
    <input type="hidden" name="m_curLogoG" value="<?php echo $gd->G_LOGO; ?>">
    <input type="hidden" name="G_O_LOGO" value="<?php echo $gd->O_G_LOGO; ?>">
  <?php
  else:?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->G_LOGO)?$gd->G_LOGO:"", !empty($gd->G_LOGO)?$gd->G_LOGO:"") ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織ロゴ画像")), "組織ロゴ画像"); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php
      if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?>">
        <input type="file" size="60" maxlength="80" name="G_LOGO"><br>
              <input type="checkbox" name="m_delLogoG" value="1">削除
              <input type="hidden" name="m_curLogoG" value="<?php echo (empty($_POST["m_curLogoG"]) == false)?$_POST["m_curLogoG"]:(!empty($gd->G_LOGO)?$gd->G_LOGO:"") ?>">
              <?php if(!empty($gd->G_LOGO) && $gd->G_LOGO != ""): ?>
                <?php echo !empty($gd->G_LOGO)?$gd->G_LOGO:""; ?><br>
                <a href="javascript:ShowImage('<?php echo $gd->G_LOGO; ?>');">
                  <?php echo $this->dispImgSrc($gd->G_LOGO) ?>
                </a>
            <?php
            endif;
      else: ?>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php if($gd->G_LOGO !== ""): ?>
          <?php echo $gd->G_LOGO; ?><br>
          <?php echo $this->dispImgSrc($gd->G_LOGO); ?>
          <?php endif; ?>
      <input type="hidden" name="G_LOGO">
      <input type="hidden" name="m_delLogoG" value="">
      <input type="hidden" name="m_curLogoG" value="<?php echo $gd->G_LOGO; ?>">
      <?php endif;?>
  <?php if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
  <?php endif; ?>
  </td>
  <input type="hidden" name="G_O_LOGO" value="<?php echo $gd->O_G_LOGO; ?>">
<?php
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
endif;
} //End Sub

function SetG_URL($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$set_g_id = $GLOBALS['set_g_id'];
$gd = $GLOBALS['gd'];
$gdh = $GLOBALS['gdh'];
$gInputOpen = $GLOBALS['gInputOpen'];
if(empty($gd->G_URL))
  $gd->G_URL ="";
if(!$disp):
    $_SESSION["PUTOUT_JS_G_URL"] = false; ?>
      <input type="hidden" name="G_URL" value="<?php echo $gd->G_URL ?>">
      <input type="hidden" name="G_O_URL" value="<?php echo $gd->O_G_URL ?>">
      <input type="hidden" name="G_O_URL" value="<?php echo $gd->O_G_URL ?>">
  <?php
  else:
    $_SESSION["PUTOUT_JS_G_URL"] = true;
  ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->G_URL)?$gd->G_URL:"",!empty($gd->G_URL)?$gd->G_URL:""); ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織ＵＲＬ")), "組織ＵＲＬ"); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
  <?php
    if($set_g_id == "" && !$readOnlyFlg):
  ?>
      <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" size="60" maxlength="80" name="G_URL" value="<?php echo !empty($gd->G_URL)?$gd->G_URL:""; ?>">
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $gd->G_URL ?>
        <input type="hidden" name="G_URL" value="<?php echo $gd->G_URL ?>">
  <?php
    endif;
    if( $explanation !== ""): ?>
      <br><font color="red"><?php echo $explanation ?></font>
  <?php
    endif; ?>
      </td>
  <?php if($gInputOpen == "1"):
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_URL,$gd->O_G_URL); ?>">
        <?php echo $this->openLevelText($gd->O_G_URL); ?>
        <input type="hidden" name="G_O_URL" value="<?php echo $gd->O_G_URL; ?>">
        <input type="hidden" name="G_O_URL" value="<?php echo $gd->O_G_URL; ?>">
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_URL,$gd->O_G_URL); ?>">
        <select name="G_O_URL" style="max-width: 100%; width: 100%; padding: 0px;">
          <option value="0" <?php echo ($gd->O_G_URL == "0")?"selected":""; ?>>公開しない</option>
          <option value="1" <?php echo ($gd->O_G_URL == "1")?"selected":""; ?>>一般公開</option>
          <option value="2" <?php echo ($gd->O_G_URL == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
  <?php
    endif;
    else: ?>
        <input type="hidden" name="G_O_URL" value="<?php echo $gd->O_G_URL; ?>">
        <input type="hidden" name="G_O_URL" value="<?php echo $gd->O_G_URL; ?>">
<?php
    endif;
  endif;
}

function SetG_P_URL($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->G_P_URL))
    $gd->G_P_URL = "";
  if(empty($gd->O_G_P_URL))
    $gd->O_G_P_URL = "0";
  if(!$disp):
    $_SESSION["PUTOUT_JS_G_P_URL"] = false; ?>
      <input type="hidden" name="G_P_URL" value="<?php echo $gd->G_P_URL ?>">
      <input type="hidden" name="G_O_P_URL" value="<?php echo $gd->O_G_P_URL ?>">
      <input type="hidden" name="G_O_P_URL" value="<?php echo $gd->O_G_P_URL; ?>">
  <?php
  else:
    $_SESSION["PUTOUT_JS_G_P_URL"] = true; ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->G_P_URL)?$gd->G_P_URL:"",!empty($gd->G_P_URL)?$gd->G_P_URL:""); ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織携帯ＵＲＬ")), "組織携帯ＵＲＬ"); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
  <?php
    if($set_g_id == "" && !$readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" size="60" maxlength="80" name="G_P_URL" value="<?php echo !empty($gd->G_P_URL)?$gd->G_P_URL:"" ?>">
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $gd->G_P_URL ?>
        <input type="hidden" name="G_P_URL" value="<?php echo $gd->G_P_URL; ?>">
  <?php
    endif;
    if( $explanation !== ""): ?>
      <br><font color="red"><?php echo $explanation ?></font>
  <?php
    endif; ?>
      </td>
  <?php
    if( $gInputOpen == "1"):
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_P_URL,$gd->O_G_P_URL); ?>">
        <?php echo $this->openLevelText($gd->O_G_P_URL); ?>
        <input type="hidden" name="G_O_P_URL" value="<?php echo $gd->O_G_P_URL ?>">
        <input type="hidden" name="G_O_P_URL" value="<?php echo $gd->O_G_P_URL; ?>">
        </td>
  <?php
      else: ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_P_URL,$gd->O_G_P_URL); ?>">
          <select name="G_O_P_URL" style="max-width: 100%; width: 100%; padding: 0px;">
            <option value="0" <?php echo ($gd->O_G_P_URL == "0")?"selected":""; ?>>公開しない</option>
            <option value="1" <?php echo ($gd->O_G_P_URL == "1")?"selected":""; ?>>一般公開</option>
            <option value="2" <?php echo ($gd->O_G_P_URL == "2")?"selected":""; ?>>会員にのみ公開</option>
          </select>
        </td>
  <?php
      endif;
    else: ?>
        <input type="hidden" name="G_O_P_URL" value="<?php echo !empty($gd->O_G_P_URL)?$gd->O_G_P_URL:""; ?>">
        <input type="hidden" name="G_O_P_URL" value="<?php echo !empty($gd->O_G_P_URL)?$gd->O_G_P_URL:""; ?>">
  <?php
    endif;
  endif;
}

function SetG_EMAIL($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->G_EMAIL))
    $gd->G_EMAIL = "";
  if(!$disp): ?>
    <input type="hidden" name="G_EMAIL" value="<?php echo $gd->G_EMAIL ?>">
    <input type="hidden" name="G_O_EMAIL" value="<?php echo $gd->O_G_EMAIL ?>">
    <input type="hidden" name="G_O_EMAIL" value="<?php echo $gd->O_G_EMAIL ?>">
  <?php
  else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->G_EMAIL)?$gd->G_EMAIL:"",!empty($gd->G_EMAIL)?$gd->G_EMAIL:""); ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織Ｅ－ＭＡＩＬ")), "組織Ｅ－ＭＡＩＬ"); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php
    if( $set_g_id == "" && !$readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?>">
        <input style="ime-mode: disabled;" type="text" size="60" maxlength="80" name="G_EMAIL" value="<?php echo !empty($gd->G_EMAIL)?$gd->G_EMAIL:""; ?>" onChange="changeContactForm();changeClaimForm();">
    <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $gd->G_EMAIL; ?>
        <input type="hidden" name="G_EMAIL" value="<?php echo $gd->G_EMAIL ?>">
    <?php
    endif; ?>
    <br><span style="color:red;">※このメールアドレスではログインすることはできません。</span>
    <?php
      if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
    <?php endif; ?>
      </td>
    <?php
      if( $gInputOpen == "1"):
        if($readOnlyFlg): ?>
          <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_EMAIL,$gd->O_G_EMAIL) ?>">
            <?php echo $this->openLevelText($gd->O_G_EMAIL) ?>
            <input type="hidden" name="G_O_EMAIL" value="<?php echo $gd->O_G_EMAIL ?>">
            <input type="hidden" name="G_O_EMAIL" value="<?php echo $gd->O_G_EMAIL ?>">
          </td>
        <?php
        else: ?>
          <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_EMAIL,$gd->O_G_EMAIL) ?>">
            <select name="G_O_EMAIL" style="max-width: 100%; width: 100%; padding: 0px;">
            <option value="0" <?php echo ($gd->O_G_EMAIL == "0")?"selected":""; ?>>公開しない</option>
            <option value="1" <?php echo ($gd->O_G_EMAIL == "1")?"selected":""; ?>>一般公開</option>
            <option value="2" <?php echo ($gd->O_G_EMAIL == "2")?"selected":""; ?>>会員にのみ公開</option>
            </select>
          </td>
          <?php
        endif;
      else:?>
            <input type="hidden" name="G_O_EMAIL" value="<?php echo $gd->O_G_EMAIL ?>">
            <input type="hidden" name="G_O_EMAIL" value="<?php echo $gd->O_G_EMAIL; ?>">
        <?php
      endif;
  endif;
}

function SetG_EMAIL2($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->G_EMAIL))
    $gd->G_EMAIL = "";
  if(!$disp): ?>
    <input type="hidden" name="G_EMAIL2" value="<?php echo $gd->G_EMAIL ?>">
    <input type="hidden" name="G_O_EMAIL2" value="<?php echo $gd->O_G_EMAIL ?>">
    <input type="hidden" name="G_O_EMAIL2" value="<?php echo $gd->O_G_EMAIL ?>">
  <?php
  else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->G_EMAIL)?$gd->G_EMAIL:"",!empty($gd->G_EMAIL)?$gd->G_EMAIL:""); ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織Ｅ－ＭＡＩＬ再入力")), "組織Ｅ－ＭＡＩＬ再入力"); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php
    if( $set_g_id == "" && !$readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?>">
        <input style="ime-mode: disabled;" type="text" size="60" maxlength="80" name="G_EMAIL2" value="<?php echo !empty($gd->G_EMAIL)?$gd->G_EMAIL:""; ?>" onChange="changeContactForm();changeClaimForm();"  autocomplete="nope">
    <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $gd->G_EMAIL; ?>
        <input type="hidden" name="G_EMAIL2" value="<?php echo $gd->G_EMAIL ?>">
    <?php
    endif; ?>
    <br><span style="color:red;">※このメールアドレスではログインすることはできません。</span>
    <?php
      if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
    <?php endif; ?>
      </td>
    <?php
      if( $gInputOpen == "1"):
        if($readOnlyFlg): ?>
          <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_EMAIL,$gd->O_G_EMAIL) ?>">
            <?php echo $this->openLevelText($gd->O_G_EMAIL) ?>
            <input type="hidden" name="G_O_EMAIL2" value="<?php echo $gd->O_G_EMAIL ?>">
            <input type="hidden" name="G_O_EMAIL2" value="<?php echo $gd->O_G_EMAIL ?>">
          </td>
        <?php
        else: ?>
          <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_EMAIL,$gd->O_G_EMAIL) ?>">
            <select name="G_O_EMAIL2" style="max-width: 100%; width: 100%; padding: 0px;">
            <option value="0" <?php echo ($gd->O_G_EMAIL == "0")?"selected":""; ?>>公開しない</option>
            <option value="1" <?php echo ($gd->O_G_EMAIL == "1")?"selected":""; ?>>一般公開</option>
            <option value="2" <?php echo ($gd->O_G_EMAIL == "2")?"selected":""; ?>>会員にのみ公開</option>
            </select>
          </td>
          <?php
        endif;
      else:?>
            <input type="hidden" name="G_O_EMAIL2" value="<?php echo $gd->O_G_EMAIL ?>">
            <input type="hidden" name="G_O_EMAIL2" value="<?php echo $gd->O_G_EMAIL; ?>">
        <?php
      endif;
  endif;
}
function SetG_CC_EMAIL($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->G_CC_EMAIL))
    $gd->G_CC_EMAIL = ""; 
  if(!$disp): ?>
    <input type="hidden" name="G_CC_EMAIL" value="<?php echo $gd->G_CC_EMAIL; ?>">
    <input type="hidden" name="G_O_CC_EMAIL" value="<?php echo $gd->O_G_CC_EMAIL ?>">
    <input type="hidden" name="G_O_CC_EMAIL" value="<?php echo $gd->O_G_CC_EMAIL; ?>">
  <?php
  else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->G_CC_EMAIL)?$gd->G_CC_EMAIL:"",!empty($gd->G_CC_EMAIL)?$gd->G_CC_EMAIL:""); ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織追加送信先Ｅ－ＭＡＩＬ")), "組織追加送信先Ｅ－ＭＡＩＬ"); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
  <?php
  if($set_g_id == "" && !$readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" size="60" maxlength="80" name="G_CC_EMAIL" value="<?php echo !empty($gd->G_CC_EMAIL)?$gd->G_CC_EMAIL:""; ?>" onChange="changeContactForm();changeClaimForm();">
  <?php
  else: ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $gd->G_CC_EMAIL ?>
      <input type="hidden" name="G_CC_EMAIL" value="<?php echo $gd->G_CC_EMAIL ?>">
  <?php
  endif;
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation ?></font>
  <?php
  endif; ?>
    </td>
  <?php
  if($gInputOpen == "1"):
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_CC_EMAIL,$gd->O_G_CC_EMAIL); ?>">
        <?php echo $this->openLevelText($gd->O_G_CC_EMAIL) ?>
        <input type="hidden" name="G_O_CC_EMAIL" value="<?php echo $gd->O_G_CC_EMAIL ?>">
        <input type="hidden" name="G_O_CC_EMAIL" value="<?php echo $gd->O_G_CC_EMAIL ?>">
      </td>
    <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_CC_EMAIL,$gd->O_G_CC_EMAIL) ?>">
        <select name="G_O_CC_EMAIL" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($gd->O_G_CC_EMAIL == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($gd->O_G_CC_EMAIL == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($gd->O_G_CC_EMAIL == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
    <?php
    endif;
  else: ?>
      <input type="hidden" name="G_O_CC_EMAIL" value="<?php echo $gd->O_G_CC_EMAIL ?>">
      <input type="hidden" name="G_O_CC_EMAIL" value="<?php echo $gd->O_G_CC_EMAIL; ?>">
  <?php
  endif;
  endif;
}
function SetG_TEL($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $g_tel1 = $g_tel2 = $g_tel3 = "";
  if(!empty($gd->G_TEL)){
    $g_tel1 = explode("-",$gd->G_TEL)[0];
    $g_tel2 = explode("-",$gd->G_TEL)[1];
    $g_tel3 = explode("-",$gd->G_TEL)[2];
  }else {
    $gd->G_TEL = "";
  }
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(!$disp):
    $_SESSION["PUTOUT_JS_G_TEL"] = false; ?>
      <input type="hidden" name="G_TEL_1" value="<?php echo $g_tel1 ?>">
      <input type="hidden" name="G_TEL_2" value="<?php echo $g_tel2 ?>">
      <input type="hidden" name="G_TEL_3" value="<?php echo $g_tel3 ?>">
      <input type="hidden" name="G_TEL"   value="<?php echo $gd->G_TEL ?>">
      <input type="hidden" name="G_O_TEL" value="<?php echo $gd->O_G_TEL ?>">
  <?php
  else:
    $_SESSION["PUTOUT_JS_G_TEL"] = true; ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->G_TEL)?$gd->G_TEL:"",!empty($gd->G_TEL)?$gd->G_TEL:""); ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織ＴＥＬ")), "組織ＴＥＬ"); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
    <?php
      if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="G_TEL_1" value="<?php echo $g_tel1 ?>" onChange="changeContactForm();changeClaimForm();">&nbsp;-
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="G_TEL_2" value="<?php echo $g_tel2 ?>" onChange="changeContactForm();changeClaimForm();">&nbsp;-
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="G_TEL_3" value="<?php echo $g_tel3 ?>" onChange="changeContactForm();changeClaimForm();">
          <input type="hidden" name="G_TEL" value="<?php echo !empty($gd->G_TEL)?$gd->G_TEL:"" ?>">
      <?php
      else: ?>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $gd->G_TEL ?>
          <input type="hidden" name="G_TEL_1" value="<?php echo $g_tel1 ?>">
          <input type="hidden" name="G_TEL_2" value="<?php echo $g_tel2 ?>">
          <input type="hidden" name="G_TEL_3" value="<?php echo $g_tel3 ?>">
          <input type="hidden" name="G_TEL"   value="<?php echo !empty($gd->G_TEL)?$gd->G_TEL:"" ?>">
      <?php
      endif; ?>
      <?php
      if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
      <?php
      endif; ?>
        </td>
      <?php
      if($gInputOpen == "1"):
        if($readOnlyFlg): ?>
          <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_TEL,$gd->O_G_TEL) ?>">
            <?php echo $this->openLevelText($gd->O_G_TEL) ?>
            <input type="hidden" name="G_O_TEL" value="<?php echo $gd->O_G_TEL ?>">
            <input type="hidden" name="G_O_TEL" value="<?php echo $gd->O_G_TEL ?>">
          </td>
        <?php
        else: ?>
          <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_TEL,$gd->O_G_TEL) ?>">
            <select name="G_O_TEL" style="max-width: 100%; width: 100%; padding: 0px;">
            <option value="0" <?php echo ($gd->O_G_TEL == "0")?"selected":""; ?>>公開しない</option>
            <option value="1" <?php echo ($gd->O_G_TEL == "1")?"selected":""; ?>>一般公開</option>
            <option value="2" <?php echo ($gd->O_G_TEL == "2")?"selected":""; ?>>会員にのみ公開</option>
            </select>
          </td>
        <?php
        endif;
      else: ?>
          <input type="hidden" name="G_O_TEL" value="<?php echo $gd->O_G_TEL ?>">
          <input type="hidden" name="G_O_TEL" value="<?php echo $gd->O_G_TEL ?>">
      <?php
      endif;
  endif;
}

function SetG_FAX($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $g_fax1 = $g_fax2 = $g_fax3 = "";
  if(!empty($gd->G_FAX)){
    $g_fax1 = explode("-",$gd->G_FAX)[0];
    $g_fax2 = explode("-",$gd->G_FAX)[1];
    $g_fax3 = explode("-",$gd->G_FAX)[2];
  }else {
    $gd->G_FAX = "";  
  }
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(!$disp): ?>
    <input type="hidden" name="G_FAX_1" value="<?php echo $g_fax1 ?>">
    <input type="hidden" name="G_FAX_2" value="<?php echo $g_fax2 ?>">
    <input type="hidden" name="G_FAX_3" value="<?php echo $g_fax3 ?>">
    <input type="hidden" name="G_FAX"   value="<?php echo $gd->G_FAX ?>">
    <input type="hidden" name="G_O_FAX" value="<?php echo $gd->O_G_FAX ?>">
  <?php
  else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->G_FAX)?$gd->G_FAX:"",!empty($gd->G_FAX)?$gd->G_FAX:"") ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織ＦＡＸ")), "組織ＦＡＸ"); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
  <?php
    if($set_g_id == "" && !$readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?>">
        <input style="ime-mode: disabled;" type="text" class='w_60px' name="G_FAX_1" value="<?php echo $g_fax1 ?>" onChange="changeContactForm();changeClaimForm();">&nbsp;-
        <input style="ime-mode: disabled;" type="text" class='w_60px' name="G_FAX_2" value="<?php echo $g_fax2 ?>" onChange="changeContactForm();changeClaimForm();">&nbsp;-
        <input style="ime-mode: disabled;" type="text" class='w_60px' name="G_FAX_3" value="<?php echo $g_fax3 ?>" onChange="changeContactForm();changeClaimForm();">
        <input type="hidden" name="G_FAX" value="<?php echo !empty($gd->G_FAX)?$gd->G_FAX:"" ?>">
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $gd->G_FAX ?>
        <input type="hidden" name="G_FAX_1" value="<?php echo $g_fax1 ?>">
        <input type="hidden" name="G_FAX_2" value="<?php echo $g_fax2 ?>">
        <input type="hidden" name="G_FAX_3" value="<?php echo $g_fax3 ?>">
        <input type="hidden" name="G_FAX"   value="<?php echo !empty($gd->G_FAX)?$gd->G_FAX:"" ?>">
  <?php
    endif; ?>
  <?php
    if($explanation !== ""): ?>
      <br><font color="red"><?php echo $explanation ?></font>
  <?php
    endif; ?>
    </td>
  <?php
    if($gInputOpen == "1"):
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_FAX,$gd->O_G_FAX) ?>">
          <?php echo $this->openLevelText($gd->O_G_FAX) ?>
          <input type="hidden" name="G_O_FAX" value="<?php echo $gd->O_G_FAX ?>">
          <input type="hidden" name="G_O_FAX" value="<?php echo $gd->O_G_FAX; ?>">
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_G_FAX,$gd->O_G_FAX) ?>">
          <select name="G_O_FAX" style="max-width: 100%; width: 100%; padding: 0px;">
          <option value="0" <?php echo ($gd->O_G_FAX == "0")?"selected":""; ?>>公開しない</option>
          <option value="1" <?php echo ($gd->O_G_FAX == "1")?"selected":""; ?>>一般公開</option>
          <option value="2" <?php echo ($gd->O_G_FAX == "2")?"selected":""; ?>>会員にのみ公開</option>
          </select>
        </td>
    <?php
      endif;
    else: ?>
        <input type="hidden" name="G_O_FAX" value="<?php echo $gd->O_G_FAX ?>">
        <input type="hidden" name="G_O_FAX" value="<?php echo $gd->O_G_FAX ?>">
  <?php
    endif;
  endif;
}

function SetG_FOUND_DATE($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  $YearsSelect = $GLOBALS['YearsSelect'];
  $format_date = '';
  $found_dateY = $found_dateM = $found_dateD = "";
  if(!empty($gd->FOUND_DATE) && $gd->FOUND_DATE != ''){
    $format_date = date('Y/m/d', strtotime($gd->FOUND_DATE));
    $found_dateY = explode("/",$format_date)[0];
    $found_dateM = explode("/",$format_date)[1];
    $found_dateD = explode("/",$format_date)[2];
  }
    if(!$disp): ?>
      <input type="hidden" name="m_foundImperialG">
      <input type="hidden" name="G_FOUND_YEAR"  value="<?php echo $found_dateY; ?>">
      <input type="hidden" name="G_FOUND_MONTH" value="<?php echo $found_dateM; ?>">
      <input type="hidden" name="G_FOUND_DAY"   value="<?php echo $found_dateD; ?>">
      <input type="hidden" name="G_FOUND_DATE"  value="<?php echo $format_date ?>">
      <input type="hidden" name="G_O_FOUND_DATE" value="<?php echo $gd->O_FOUND_DATE ?>">
    <?php
    else: ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp($format_date,$format_date) ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("設立年月日")), "設立年月日"); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <?php
      if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?>">
          <?php
            if($YearsSelect == "0"): ?>
              <input type="hidden" name="m_foundImperialG" value="AD">
              <?php
            else: ?>
              <select name="m_foundImperialG">
              <?php  $this->ShowImperialOption(!empty($_POST["m_foundImperialG"])?$_POST["m_foundImperialG"]:"") ?>
              </select>
              <?php
            endif; ?>
            <input style="ime-mode: disabled;" type="text" class="w_60px" maxlength="4" name="G_FOUND_YEAR"  value="<?php echo $found_dateY ?>">年
            <input style="ime-mode: disabled;" type="text" class="w_60px" maxlength="2" name="G_FOUND_MONTH" value="<?php echo $found_dateM ?>">月
            <input style="ime-mode: disabled;" type="text" class="w_60px" maxlength="2" name="G_FOUND_DAY"   value="<?php echo $found_dateD ?>">日
            <input type="hidden" name="G_FOUND_DATE">
      <?php
      else: ?>
          <?php echo $format_date ?>
          <input type="hidden" name="m_foundImperialG">
          <input type="hidden" name="G_FOUND_YEAR"  value="<?php echo $found_dateY ?>">
          <input type="hidden" name="G_FOUND_MONTH" value="<?php echo $found_dateM ?>">
          <input type="hidden" name="G_FOUND_DAY"   value="<?php echo $found_dateD ?>">
          <input type="hidden" name="G_FOUND_DATE"  value="<?php echo $format_date ?>">
          <?php
      endif; ?>
      <?php
    if($explanation !== ""): ?>
      <br><font color="red"><?php echo $explanation ?></font>
      <?php
    endif; ?>
    </td>
  <?php
    if($gInputOpen == "1"):
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_FOUND_DATE,$gd->O_FOUND_DATE) ?>">
          <?php echo $this->openLevelText($gd->O_FOUND_DATE) ?>
          <input type="hidden" name="G_O_FOUND_DATE" value="<?php echo $gd->O_FOUND_DATE ?>">
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_FOUND_DATE,$gd->O_FOUND_DATE); ?>">
          <select name="G_O_FOUND_DATE" style="max-width: 100%; width: 100%; padding: 0px;">
          <option value="0" <?php echo ($gd->O_FOUND_DATE == "0")?"selected":""; ?>>公開しない</option>
          <option value="1" <?php echo ($gd->O_FOUND_DATE == "1")?"selected":""; ?>>一般公開</option>
          <option value="2" <?php echo ($gd->O_FOUND_DATE == "2")?"selected":""; ?>>会員にのみ公開</option>
          </select>
        </td>
    <?php
      endif;
    else: ?>
        <input type="hidden" name="G_O_FOUND_DATE" value="<?php echo $gd->O_FOUND_DATE ?>">
  <?php
    endif;
  endif;
}

function SetG_SETTLE_MONTH($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->SETTLE_MONTH))
    $gd->SETTLE_MONTH = "";
  if(!$disp): ?>
      <input type="hidden" name="G_SETTLE_MONTH"  value="<?php echo $gd->SETTLE_MONTH ?>">
      <input type="hidden" name="G_O_SETTLE_MONTH" value="<?php echo $gd->O_SETTLE_MONTH ?>">
  <?php
    else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->SETTLE_MONTH)?$gd->SETTLE_MONTH:"",!empty($gd->SETTLE_MONTH)?$gd->SETTLE_MONTH:""); ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("決算月")), "決算月"); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <?php
      if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?>">
          <select name="G_SETTLE_MONTH">
            <option value="">----</option>
            <?php
              $buf = array("", "１月", "２月", "３月", "４月", "５月", "６月", "７月", "８月", "９月", "１０月", "１１月", "１２月");
              for($i=1 ; $i<=12; $i++) {
            ?>
                <option value="<?php echo $i; ?>" <?php echo ($i == !empty($gd->SETTLE_MONTH)?$gd->SETTLE_MONTH:"")?"selected":""; ?>>
                  <?php echo $buf[$i]; ?>
                </option>
            <?php } ?>
          </select>
        <?php
      else: ?>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $this->gdMonthText($gd->SETTLE_MONTH) ?>
          <input type="hidden" name="G_SETTLE_MONTH"  value="<?php echo $gd->SETTLE_MONTH ?>">
        <?php
      endif;
      if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
        <?php
      endif; ?>
      </td>
    <?php
      if($gInputOpen == "1"):
        if($readOnlyFlg): ?>
          <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_SETTLE_MONTH,$gd->O_SETTLE_MONTH) ?>">
            <?php echo $this->openLevelText($gd->O_SETTLE_MONTH) ?>
            <input type="hidden" name="G_O_SETTLE_MONTH" value="<?php echo $gd->O_SETTLE_MONTH ?>">
          </td>
      <?php
        else: ?>
          <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_SETTLE_MONTH,$gd->O_SETTLE_MONTH) ?>">
            <select name="G_O_SETTLE_MONTH" style="max-width: 100%; width: 100%; padding: 0px;">
            <option value="0" <?php echo ($gd->O_SETTLE_MONTH == "0")?"selected":""; ?>>公開しない</option>
            <option value="1" <?php echo ($gd->O_SETTLE_MONTH == "1")?"selected":""; ?>>一般公開</option>
            <option value="2" <?php echo ($gd->O_SETTLE_MONTH == "2")?"selected":""; ?>>会員にのみ公開</option>
            </select>
          </td>
      <?php
        endif;
      else: ?>
          <input type="hidden" name="G_O_SETTLE_MONTH" value="<?php echo $gd->O_SETTLE_MONTH ?>">
    <?php
      endif;
  endif;
}

function SetG_CAPITAL($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->CAPITAL))
    $gd->CAPITAL = "";
  if(!$disp):
    $_SESSION["PUTOUT_JS_G_CAPITAL"] = false; ?>
      <input type="hidden" name="G_CAPITAL"  value="<?php echo $gd->CAPITAL ?>">
      <input type="hidden" name="G_O_CAPITAL" value="<?php echo $gd->O_CAPITAL ?>">
<?php
  else:
    $_SESSION["PUTOUT_JS_G_CAPITAL"] = true; ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->CAPITAL)?$gd->CAPITAL:"",!empty($gd->CAPITAL)?$gd->CAPITAL:"") ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("資本金")), "資本金"); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
  <?php
    if($set_g_id == "" && !$readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?>">
        <input style="ime-mode: disabled; text-align: right;" type="text" name="G_CAPITAL"  maxlength="13" value="<?php echo !empty($gd->CAPITAL)?$gd->CAPITAL:""; ?>">
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $gd->CAPITAL; ?>
        <input type="hidden" name="G_CAPITAL"  value="<?php echo $gd->CAPITAL ?>">
  <?php
    endif;
    if($explanation !== ""): ?>
      <br><font color="red"><?php echo $explanation ?></font>
  <?php
    endif; ?>
      </td>
  <?php
    if($gInputOpen == "1"): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_CAPITAL,$gd->O_CAPITAL) ?>">
        <select name="G_O_CAPITAL" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($gd->O_CAPITAL == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($gd->O_CAPITAL == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($gd->O_CAPITAL == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
  <?php
    else: ?>
        <input type="hidden" name="G_O_CAPITAL" value="<?php echo $gd->O_CAPITAL ?>">
  <?php
    endif;
  endif;
}

function SetG_INDUSTRY_CD($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $page_link = $this->getPageSlug('nakama-member-category');
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $postid = $GLOBALS['postid'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(!$disp):
    $_SESSION["PUTOUT_JS_G_CATEGORY"] = false; ?>
      <input type="hidden" name="G_INDUSTRY_CD" value="<?php echo !empty($gd->INDUSTRY_CD)?$gd->INDUSTRY_CD:"" ?>">
      <input type="hidden" name="G_O_CATEGORY_CODE" value="<?php echo $gd->O_INDUSTRY_CD ?>">
  <?php
  else:
    $_SESSION["PUTOUT_JS_G_CATEGORY"] = true; ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->INDUSTRY_CD)?$gd->INDUSTRY_CD:"",!empty($gd->INDUSTRY_CD)?$gd->INDUSTRY_CD:"") ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("業種コード")), "業種コード"); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
  <?php
  if($set_g_id == "" && !$readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" class="w_60px" maxlength="6" name="G_INDUSTRY_CD" value="<?php echo !empty($gd->INDUSTRY_CD)?$gd->INDUSTRY_CD:""; ?>">
      <input type="button" value="検索" onClick="OnSearchCategory(<?php echo $postid; ?>, '<?php echo $page_link; ?>');">
  <?php
  else: ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $gd->INDUSTRY_CD ?>
    <input type="hidden" name="G_INDUSTRY_CD" value="<?php echo !empty($gd->INDUSTRY_CD)?$gd->INDUSTRY_CD:"" ?>">
  <?php
  endif; ?>
  <?php
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation ?></font>
  <?php
  endif; ?>
    </td>
  <?php
    if($gInputOpen == "1"):
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_INDUSTRY_CD,$gd->O_INDUSTRY_CD) ?>">
          <?php echo $this->openLevelText($gd->O_INDUSTRY_CD) ?>
          <input type="hidden" name="G_O_CATEGORY_CODE" value="<?php echo $gd->O_INDUSTRY_CD ?>">
          <input type="hidden" name="G_O_CATEGORY_CODE" value="<?php echo $gd->O_INDUSTRY_CD ?>">
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_INDUSTRY_CD,$gd->O_INDUSTRY_CD) ?>">
          <select name="G_O_CATEGORY_CODE" style="max-width: 100%; width: 100%; padding: 0px;">
          <option value="0" <?php echo ($gd->O_INDUSTRY_CD == "0")?"selected":""; ?>>公開しない</option>
          <option value="1" <?php echo ($gd->O_INDUSTRY_CD == "1")?"selected":""; ?>>一般公開</option>
          <option value="2" <?php echo ($gd->O_INDUSTRY_CD == "2")?"selected":""; ?>>会員にのみ公開</option>
          </select>
        </td>
    <?php
      endif;
    else: ?>
        <input type="hidden" name="G_O_CATEGORY_CODE" value="<?php echo $gd->O_INDUSTRY_CD ?>">
        <input type="hidden" name="G_O_CATEGORY_CODE" value="<?php echo $gd->O_INDUSTRY_CD ?>">
<?php
    endif;
  endif;
}


function SetG_INDUSTRY_NM($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($gd->INDUSTRY_NM))
    $gd->INDUSTRY_NM = "";
  if(!$disp): ?>
    <input type="hidden" name="G_INDUSTRY_NM" value="<?php echo $gd->INDUSTRY_NM ?>">
    <input type="hidden" name="G_O_CATEGORY" value="<?php echo $gd->O_INDUSTRY_NM ?>">
    <input type="hidden" name="G_O_CATEGORY" value="<?php echo $gd->O_INDUSTRY_NM  ?>">
  <?php
  else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->INDUSTRY_NM)?$gd->INDUSTRY_NM:"",!empty($gd->INDUSTRY_NM)?$gd->INDUSTRY_NM:"") ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("業種")), "業種"); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
  <?php
  if($set_g_id == "" && !$readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: active;" type="text" size="60" maxlength="500" name="G_INDUSTRY_NM" value="<?php echo !empty($gd->INDUSTRY_NM)?$gd->INDUSTRY_NM:""; ?>">
  <?php
  else: ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $gd->INDUSTRY_NM ?>
      <input type="hidden" name="G_INDUSTRY_NM" value="<?php echo $gd->INDUSTRY_NM ?>">
  <?php
  endif; ?>
  <?php
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation ?></font>
  <?php
  endif; ?>
    </td>
  <?php
  if($gInputOpen == "1"):
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_INDUSTRY_NM,$gd->O_INDUSTRY_NM); ?>">
        <?php echo $this->openLevelText($gd->O_INDUSTRY_NM) ?>
        <input type="hidden" name="G_O_CATEGORY" value="<?php echo $gd->O_INDUSTRY_NM ?>">
        <input type="hidden" name="G_O_CATEGORY" value="<?php echo $gd->O_INDUSTRY_NM ?>">
      </td>
    <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($gd->O_INDUSTRY_NM,$gd->O_INDUSTRY_NM) ?>">
        <select name="G_O_CATEGORY" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($gd->O_INDUSTRY_NM == "0")?"selected":"" ?>>公開しない</option>
        <option value="1" <?php echo ($gd->O_INDUSTRY_NM == "1")?"selected":"" ?>>一般公開</option>
        <option value="2" <?php echo ($gd->O_INDUSTRY_NM == "2")?"selected":"" ?>>会員にのみ公開</option>
        </select>
      </td>
    <?php
    endif;
  else: ?>
    <input type="hidden" name="G_O_CATEGORY" value="<?php echo $gd->O_INDUSTRY_NM ?>">
    <input type="hidden" name="G_O_CATEGORY" value="<?php echo $gd->O_INDUSTRY_NM ?>">
<?php
  endif;
endif;
}

function SetG_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, $no, $no2, $bikou, $o_bikou, $h_bikou, $ho_bikou, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $set_g_id = $GLOBALS['set_g_id'];
    $gd = $GLOBALS['gd'];
    $gdh = $GLOBALS['gdh'];
    $gInputOpen = $GLOBALS['gInputOpen'];
    if(!$disp):
      $_SESSION["PUTOUT_JS_G_FREE".$no] = false; ?>
      <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo $o_bikou ?>">
    <?php
    else:
      $_SESSION["PUTOUT_JS_G_FREE".$no] = true;
      if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($bikou, $h_bikou); ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
        <?php
        if($radioValue == ""):?>
        <input style="ime-mode: active;" type="text" size="60" maxlength="80" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
        <?php
        else:
          if($GLOBALS['flag_checkbox']):
            $radioValue = substr($radioValue,5);
          endif;
          $radioAray = explode("|", $radioValue);
          $checkedValue = explode("|", $bikou);
          for ($i = 0; $i < count($radioAray); $i++) {
            $checked = "";
            $editValue = str_replace("<br>", "", $radioAray[$i]);
            $editValue = str_replace("<BR>", "", $editValue);

            if(in_array($editValue,$checkedValue)):
              $checked = "checked";
            elseif(($bikou)?$bikou:"" == ""):
              if($default !== ""):
                if($default == $editValue):
                  $checked = "checked";
                endif;
              endif;
            endif;
            ?>
            <input type="<?php echo (!$GLOBALS['flag_checkbox'])?"radio":"checkbox"; ?>" name="G_FREE<?php echo $no; ?><?php echo ($GLOBALS['flag_checkbox'])?"[]":""; ?>" value="<?php echo $editValue; ?>" <?php echo $checked; ?>>
            <?php
            echo $radioAray[$i];
          }
        endif;
      else: ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $bikou ?>
          <input type="hidden" name="G_FREE<?php echo $no; ?>" value="<?php echo $bikou; ?>">
        <?php
      endif;?>
      <?php if($explanation !== ""):   ?>
          <br><font color="red"><?php echo $explanation ?></font>
      <?php endif; ?>
    </td>
    <?php
    if($gInputOpen == "1"):
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($o_bikou, $ho_bikou) ?>">
          <?php echo $this->openLevelText($o_bikou); ?>
          <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo $o_bikou; ?>">
        </td>
    <?php
      else:?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($o_bikou, $ho_bikou) ?>">
          <select name="G_O_BIKOU<?php echo $no; ?>" style="max-width: 100%; width: 100%; padding: 0px;">
            <option value="0" <?php echo ($o_bikou == "0")?"selected":""; ?>>公開しない</option>
            <option value="1" <?php echo ($o_bikou == "1")?"selected":""; ?>>一般公開</option>
            <option value="2" <?php echo ($o_bikou == "2")?"selected":""; ?>>会員にのみ公開</option>
          </select>
        </td>
    <?php endif;
    else: ?>
        <input type="hidden" name="G_O_BIKOU<?php echo $no; ?>" value="<?php echo $o_bikou?>">
    <?php
    endif;
  endif;
} //End Sub

function SetG_KEYWORD_Optician($disp, $must, $itemValue, $readOnlyFlg, $default, $no, $no2, $bikou, $o_bikou, $h_bikou, $ho_bikou, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $set_g_id = $GLOBALS['set_g_id'];
    $gd = $GLOBALS['gd'];
    $gdh = $GLOBALS['gdh'];
    $gInputOpen = $GLOBALS['gInputOpen'];

    if(!$disp): ?>
      <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni); ?>">
  <?php
    else:
      if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($bikou, $h_bikou) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>" colspan="2">
          <?php
          if($itemValue == ""): ?>
            <input style="ime-mode: active;" type="text" size="60" maxlength="15" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
          <?php
          else:
            $radioAray = explode(KEYWORD_DELIMITER_ITEM,$itemValue);
            for($i = 0; $i < count($radioAray); $i++){
              $checked = "";
              $editValue = str_replace("<br>","",$radioAray[$i]);
              $editValue = str_replace("<BR>","",$editValue);

              $regex = "(^|\"".KEYWORD_DELIMITER_ITEM.")".$editValue."(\"".KEYWORD_DELIMITER_ITEM."|$)";
              if(preg_match($regex,$bikou)):
                $checked = "checked";
              else:
                if(empty($bikou)?"":$bikou == ""):
                  if($default !== ""):
                    if($default == $editValue):
                      $checked = "checked";
                    endif;
                  else:
                    if($i == 0):
                      $checked = "checked";
                    endif;
                  endif;
                endif;
              endif;
              ?>
              <input type="radio" name="G_FREE<?php echo $no ?>" value="<?php echo $editValue ?>" <?php echo $checked; ?>>
              <?php echo $radioAray[$i];
            }
          endif;
      else: ?>
        <td class="<?php echo $RegItem; ?>" <?php echo $rowSpan?> nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $bikou ?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
    <?php
      endif; ?>
    <?php
    if($explanation != ""): ?>
      <br><font color="red"><?php echo $explanation ?></font>
  <?php
    endif; ?>
  </td>
    <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    endif;
  }



  function SetG_KEYWORD_Ages($disp, $must, $itemValue, $readOnlyFlg, $default, $no, $no2, $bikou, $o_bikou, $h_bikou, $ho_bikou, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $set_g_id = $GLOBALS['set_g_id'];
    if(!$disp): ?>
      <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni); ?>">
  <?php
    else:
      if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($bikou, $h_bikou) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>" colspan="2">
          <?php
          if($itemValue == ""): ?>
            <input style="ime-mode: active;" type="text" size="60" maxlength="15" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
          <?php
          else:
            $checkAray = explode(KEYWORD_DELIMITER_ITEM,$itemValue);
            for($i = 0; $i < count($checkAray); $i++){
              $checked = "";
              $editValue = str_replace("<br>","",$checkAray[$i]);
              $editValue = str_replace("<BR>","",$editValue);

              $regex = "(^|\"".KEYWORD_DELIMITER_ITEM.")".$editValue."(\"".KEYWORD_DELIMITER_ITEM & "|$)";
              if(preg_match($regex,$bikou)):
                $checked = "checked";
              else:
                if(empty($bikou)?"":$bikou == ""):
                  if($default !== ""):
                    if($default == $editValue):
                      $checked = "checked";
                    endif;
                  endif;
                endif;
              endif; ?>
            <input type="checkbox" name="G_KEY_Ages" value="<?php echo $editValue ?>" <?php echo $checked ?>>
            <?php echo $checkAray[$i];
          }
          endif; ?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?>" <?php echo $rowSpan?> nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $bikou ?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
    <?php
      endif;
      if($explanation != ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
    <?php
      endif; ?>
      </td>
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    endif;
  }


  function SetG_KEYWORD_Holiday($disp, $must, $itemValue, $readOnlyFlg, $default, $no, $no2, $bikou, $o_bikou, $h_bikou, $ho_bikou, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $set_g_id = $GLOBALS['set_g_id'];
    if(!$disp): ?>
      <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    else:
      if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($bikou, $h_bikou) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>" colspan="2">
          <?php
          if($itemValue == ""): ?>
            <input style="ime-mode: active;" type="text" size="60" maxlength="15" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
          <?php
          else:
            $checkAray = explode(KEYWORD_DELIMITER_ITEM,$itemValue);
            for($i = 0; $i < count($checkAray); $i++){
              $checked = "";
              $editValue = str_replace("<br>","",$checkAray[$i]);
              $editValue = str_replace("<BR>","",$editValue);

              $regex = "(^|\"".KEYWORD_DELIMITER_ITEM.")".$editValue."(\"".KEYWORD_DELIMITER_ITEM & "|$)";
              if(preg_match($regex,$bikou)):
                $checked = "checked";
              else:
                if(empty($bikou)?"":$bikou == ""):
                  if($default != ""):
                    if($default == $editValue):
                      $checked = "checked";
                    endif;
                  endif;
                endif;
              endif;
          ?>
          <input type="checkbox" name="G_KEY_Holiday" value="<?php echo $editValue ?>" <?php echo $checked ?>><?php echo $checkAray[$i] ?>
          <?php
          }
          endif; ?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
    <?php
      else:?>
        <td class="<?php echo $RegItem; ?>" <?php echo $rowSpan?> nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?$MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $bikou ?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
    <?php
      endif;
      if($explanation != ""):?>
        <br><font color="red"><?php echo $explanation ?></font>
      <?php
      endif; ?>
      </td>
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    endif;
  }




  function SetG_KEYWORD_ShopHour($disp, $must, $readOnlyFlg, $default, $no, $no2, $bikou, $o_bikou, $h_bikou, $ho_bikou, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $set_g_id = $GLOBALS['set_g_id'];
    if(!$disp): ?>
      <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    else:
      if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($bikou, $h_bikou) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>" colspan="2">
          <?php
          $checkAray = explode("～", $bikou);
          if(count($checkAray) > 0):

            $startH = explode("時",trim($checkAray[0]))[0];
            $startM = explode("分",explode("時",trim($checkAray[0]))[1])[0];
            $endH   = explode("時",trim($checkAray[1]))[0];
            $endM   = explode("分",explode("時",trim($checkAray[1]))[1])[0];
          endif;
          ?>
            <select name="G_KEY_StartH">
              <?php
                for($i = 1; $i<=23; $i++){ ?>
                <option value="<?php echo ($i < 10)?"0".$i:$i; ?>" <?php echo ($i == $startH)?"selected":""; ?>><?php echo ($i < 10)?"0".$i:$i; ?></option>;
              <?php } ?>
            </select>
            時&nbsp;&nbsp;
            <select name="G_KEY_StartM">
              <?php
                for($i = 0; $i<=45; $i+=15){ ?>
                <option value="<?php echo ($i < 10)?"0".$i:$i; ?>" <?php echo ($i == $startM)?"selected":""; ?>><?php echo ($i < 10)?"0".$i:$i; ?></option>;
              <?php } ?>
            </select>
            分&nbsp;&nbsp;～&nbsp;&nbsp;
            <select name="G_KEY_EndH">
              <?php
                for($i = 1; $i<=23; $i++){ ?>
                <option value="<?php echo ($i < 10)?"0".$i:$i; ?>" <?php echo ($i == $endH)?"selected":""; ?>><?php echo ($i < 10)?"0".$i:$i; ?></option>;
              <?php } ?>
            </select>
            時&nbsp;&nbsp;
            <select name="G_KEY_EndM">
              <?php
                for($i = 0; $i<=45; $i+=15){ ?>
                <option value="<?php echo ($i < 10)?"0".$i:$i; ?>" <?php echo ($i == $endM)?"selected":""; ?>><?php echo ($i < 10)?"0".$i:$i; ?></option>;
              <?php } ?>
            </select>
            分
            <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $editValue ?>">
          <?php
          else:?>
            <td class="<?php echo $RegItem; ?>" <?php echo $rowSpan?> nowrap>
              <?php echo ($must)?MUST_START_TAG:""; ?>
              <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
              <?php echo ($must)?MUST_END_TAG:""; ?>
            </td>
            <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
              <?php echo $bikou ?>
              <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
          <?php
          endif;
          if($explanation != ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    endif;
  }



  function SetG_KEYWORD_Parking($disp, $must, $itemValue, $readOnlyFlg, $default, $no, $no2, $bikou, $o_bikou, $h_bikou, $ho_bikou, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $set_g_id = $GLOBALS['set_g_id'];
    if(!$disp): ?>
      <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    else:
      if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($bikou, $h_bikou) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>" colspan="2">
          <?php
          if($itemValue == ""): ?>
            <input style="ime-mode: active;" type="text" size="60" maxlength="15" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
          <?php
          else:
            $radioAray = explode(KEYWORD_DELIMITER_ITEM, $itemValue);
            for($i = 0; $i < count($radioAray); $i++){
              $checked = "";
              $editValue = str_replace("<br>","",$radioAray[$i]);
              $editValue = str_replace("<BR>","",$editValue);

              $regex = "(^|\"".KEYWORD_DELIMITER_ITEM.")".$editValue."(\"".KEYWORD_DELIMITER_ITEM & "|$)";
              if(preg_match($regex, $bikou)):
                $checked = "checked";
              else:
                if(empty($bikou)?"":$bikou == ""):
                  if($default != ""):
                    if($default == $editValue):
                      $checked = "checked";
                    endif;
                  else:
                    if($i == 0):
                      $checked = "checked";
                    endif;
                  endif;
                endif;
              endif;
          ?>
            <input type="radio" name="G_FREE<?php echo $no ?>" value="<?php echo $editValue ?>" <?php echo $checked ?>><?php echo $radioAray[$i] ?>
          <?php
            }
        endif;
      else: ?>
        <td class="<?php echo $RegItem; ?>" <?php echo $rowSpan?> nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $bikou ?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
    <?php
      endif;
      if($explanation != ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
      <?php
      endif; ?>
      </td>
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    endif;
  }


  function SetG_KEYWORD_Frame_Home($disp, $must, $itemValue, $readOnlyFlg, $default, $no, $no2, $bikou, $o_bikou, $h_bikou, $ho_bikou, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $set_g_id = $GLOBALS['set_g_id'];
    if(!$disp): ?>
      <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    else:
      if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($bikou, $h_bikou) ?>" rowspan="2" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo empty(str_replace("<br>", "","得意なフレーム"))?"組織自由項目".$no2:str_replace("<br>", "","得意なフレーム"); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>" colspan="2">
        <?php
        if($itemValue == ""): ?>
          <input style="ime-mode: active;" type="text" size="60" maxlength="15" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
      <?php
        else:
          $checkAray = explode(KEYWORD_DELIMITER_ITEM,$itemValue);
          for($i = 0; $i < count($checkAray); $i++){
            $checked = "";
            $editValue = str_replace("<br>","",$checkAray[$i]);
            $editValue = str_replace("<BR>","",$editValue);

            $regex = "(^|\"".KEYWORD_DELIMITER_ITEM.")".$editValue."(\"".KEYWORD_DELIMITER_ITEM & "|$)";
            if(preg_match($regex, $bikou)):
              $checked = "checked";
            else:
              if(empty($bikou)?"":$bikou == ""):
                if($default !== ""):
                  if($default == $editValue):
                    $checked = "checked";
                  endif;
                endif;
              endif;
            endif;
            if($i == 0):
            ?>
            <b><input type="checkbox" name="G_KEY_Frame_Home" value="<?php echo $editValue ?>" OnClick="JavaScript:CheckFrameParent(this.name);" <?php echo $checked ?>><?php echo $checkAray[$i] ?></b>：<br>
            <?php
            else: ?>
              <input type="checkbox" name="G_KEY_Frame_Home" value="<?php echo $editValue ?>" OnClick="JavaScript:CheckFrameChild(this.name);" <?php echo $checked ?>><?php echo $checkAray[$i] ?>
            <?php
            endif;
          }
        endif;?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $editValue ?>">
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?>" <?php echo $rowSpan?> nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo  empty(str_replace("<br>", "","得意なフレーム"))?"組織自由項目".$no2:str_replace("<br>", "","得意なフレーム"); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td <?php echo $rowSpan?> class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $bikou ?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
    <?php
      endif;
      if($explanation != ""):?>
        <br><font color="red"><?php echo $explanation ?></font>
      <?php
      endif; ?>
    </td>
    <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    endif;
  }


  function SetG_KEYWORD_Frame_Abroad($disp, $must, $itemValue, $readOnlyFlg, $default, $no, $no2, $bikou, $o_bikou, $h_bikou, $ho_bikou, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $set_g_id = $GLOBALS['set_g_id'];
    if(!$disp): ?>
      <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    else:
      if($set_g_id = "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?>" colspan="2">
        <?php
          if($itemValue = ""): ?>
            <input style="ime-mode: active;" type="text" size="60" maxlength="15" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
          <?php
          else:
            $checkAray = explode(KEYWORD_DELIMITER_ITEM,$itemValue);
            for($i = 0; $i < count($checkAray); $i++){
              $checked = "";
              $editValue = str_replace("<br>","",$checkAray[$i]);
              $editValue = str_replace("<BR>","",$editValue);

              $regex = "(^|\"".KEYWORD_DELIMITER_ITEM.")".$editValue."(\"".KEYWORD_DELIMITER_ITEM & "|$)";
              if(preg_match($regex,$bikou)):
                $checked = "checked";
              else:
                if(empty($bikou)?"":$bikou == ""):
                  if($default !== ""):
                    if($default == $editValue):
                      $checked = "checked";
                    endif;
                  endif;
                endif;
              endif;
              if($i == 0): ?>
                <b><input type="checkbox" name="G_KEY_Frame_Abroad" value="<?php echo $editValue ?>" OnClick="JavaScript:CheckFrameParent(this.name);" <?php echo $checked ?>><?php echo $checkAray[$i]; ?></b>：<br>
              <?php
              else: ?>
                <input type="checkbox" name="G_KEY_Frame_Abroad" value="<?php echo $editValue ?>" OnClick="JavaScript:CheckFrameChild(this.name);" <?php echo $checked ?>><?php echo $checkAray[$i]; ?>
              <?php
              endif;
            }
          endif; ?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $editValue ?>">
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?>" <?php echo $rowSpan?> nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td <?php echo $rowSpan?> class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $bikou ?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
    <?php
      endif;
      if($explanation != ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
    <?php
      endif; ?>
      </td>
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    endif;
  }


  function SetG_KEYWORD_Lens($disp, $must, $itemValue, $readOnlyFlg, $default, $no, $no2, $bikou, $o_bikou, $h_bikou, $ho_bikou, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $set_g_id = $GLOBALS['set_g_id'];
    if(!$disp): ?>
      <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    else:
      if($set_g_id == "" && !$readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($bikou, $h_bikou) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>" colspan="2">
          <?php
          if($itemValue == ""): ?>
            <input style="ime-mode: active;" type="text" size="60" maxlength="15" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
          <?php
          else:
            $checkAray = explode(KEYWORD_DELIMITER_ITEM,$itemValue);
            for($i = 0; $i < count($checkAray); $i++){
              $checked = "";
              $editValue = str_replace("<br>","",$checkAray[$i]);
              $editValue = str_replace("<BR>","",$editValue);

              $regex = "(^|\"".KEYWORD_DELIMITER_ITEM.")".$editValue."(\"".KEYWORD_DELIMITER_ITEM & "|$)";
              if(preg_match($regex,$bikou)):
                $checked = "checked";
              else:
                if(empty($bikou)?"":$bikou == ""):
                  if($default != ""):
                    if($default == $editValue):
                      $checked = "checked";
                    endif;
                  endif;
                endif; ?>
                <input type="checkbox" name="G_KEY_Lens" value="<?php echo $editValue ?>" <?php echo $checked ?>><?php echo $checkAray[$i]; ?>
                <?php
                if($i == 5):
                  echo "<br>";
                endif;
              endif;
            }
          endif; ?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $editValue ?>">
        <?php
        else: ?>
          <td class="<?php echo $RegItem; ?>" <?php echo $rowSpan?> nowrap>
            <?php echo ($must)?MUST_START_TAG:""; ?>
            <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
            <?php echo ($must)?MUST_END_TAG:""; ?>
          </td>
          <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
            <?php echo $bikou ?>
            <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
        <?php
        endif; ?>
        <?php
        if($explanation != ""): ?>
          <br><font color="red"><?php echo $explanation ?></font>
        <?php
        endif; ?>
        </td>
        <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    endif;
  }



  function SetG_KEYWORD_Distinct($disp, $must, $itemValue, $readOnlyFlg, $default, $no, $no2, $bikou, $o_bikou, $h_bikou, $ho_bikou, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $set_g_id = $GLOBALS['set_g_id'];
    if(!$disp): ?>
      <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
    <?php
    else:
      if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($bikou, $h_bikou) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>" colspan="2">
          <?php
          if($itemValue == ""): ?>
            <input style="ime-mode: active;" type="text" size="60" maxlength="15" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
          <?php
          else:
            $checkAray = explode(KEYWORD_DELIMITER_ITEM,$itemValue);
            for($i = 0; $i < count($checkAray); $i++){
              $checked = "";
              $editValue = str_replace("<br>","",$checkAray[$i]);
              $editValue = str_replace("<BR>","",$editValue);

              $regex = "(^|\"".KEYWORD_DELIMITER_ITEM.")".$editValue."(\"".KEYWORD_DELIMITER_ITEM & "|$)";
              if(preg_match($regex,$bikou)):
                $checked = "checked";
              else:
                if(empty($bikou)?"":$bikou == ""):
                  if($default != ""):
                    if($default == $editValue):
                      $checked = "checked";
                    endif;
                  endif;
                endif;
              endif; ?>
              <input type="checkbox" name="G_KEY_Distinct" value="<?php echo $editValue ?>" <?php echo $checked ?>><?php echo $checkAray[$i] ?>
              <?php
              if($i == 2):
                echo "<br>";
              endif;
            }
          endif; ?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $editValue ?>">
          <?php
      else: ?>
        <td class="<?php echo $RegItem; ?>" <?php echo $rowSpan?> nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $bikou ?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
    <?php
      endif;
      if($explanation != ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
      <?php
      endif; ?>
      </td>
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni)?>">
  <?php
    endif;
  }


  function SetG_KEYWORD_BudgetNormal($disp, $must, $itemValue, $readOnlyFlg, $default, $no, $no2, $bikou, $o_bikou, $h_bikou, $ho_bikou, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $set_g_id = $GLOBALS['set_g_id'];
    if(!$disp): ?>
      <input type="hidden" name="G_FREE<?php echo $no;?>" value="<?php echo $bikou ?>">
      <input type="hidden" name="G_O_BIKOU<?php echo $no; ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
    <?php
    else:
      if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($bikou, $h_bikou) ?>" rowspan="3" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo (str_replace("<br>", "", "平均予算"))?str_replace("<br>", "", "平均予算"):"組織自由項目".$no2;?>
          <br>(一式価格)
          <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?>" colspan="2">
        <?php
        if($itemValue == ""): ?>
          <input style="ime-mode: active;" type="text" size="60" maxlength="15" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
        <?php
        else:
          $checkAray = explode(KEYWORD_DELIMITER_ITEM,$itemValue);
          for($i = 0; $i < count($checkAray); $i++){
            $checked = "";
            $editValue = str_replace("<br>","",$checkAray[$i]);
            $editValue = str_replace("<BR>","",$editValue);

            $regex = "(^|\"".KEYWORD_DELIMITER_ITEM.")".$editValue."(\"".KEYWORD_DELIMITER_ITEM & "|$)";
            if(preg_match($regex,$bikou)):
              $checked = "checked";
            else:
              if(empty($bikou)?"":$bikou == ""):
                if($default != ""):
                  if($default == $editValue):
                    $checked = "checked";
                  endif;
                endif;
              endif;
            endif;
            if($i == 0): ?>
              <div style="height:40px;">
              <div style="float:left; height:40px; width:150px; line-height:40px;">
                通常の近視・遠視用
              </div>
              <div style="float:left;">
            <?php
            endif; ?>
            <input type="radio" name="G_FREE<?php echo $no ?>" value="<?php echo $editValue ?>" <?php echo $checked ?>><?php echo $checkAray[$i]; ?>
            <?php
            if($i == 2):
              echo "<br>";
            endif;
          }
        endif; ?>
    </div>
    </div>
  <?php
      else: ?>
        <td class="<?php echo $RegItem; ?>" <?php echo $rowSpan?> nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo (str_replace("<br>", "", "平均予算"))?str_replace("<br>", "", "平均予算"):"組織自由項目".$no2;?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td <?php echo $rowSpan?> class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $bikou ?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
    <?php
      endif;
      if($explanation != ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
      <?php
      endif; ?>
    </td>
    <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    endif;
  }


  function SetG_KEYWORD_BudgetDual($disp, $must, $itemValue, $readOnlyFlg, $default, $no, $no2, $bikou, $o_bikou, $h_bikou, $ho_bikou, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $set_g_id = $GLOBALS['set_g_id'];
    if(!$disp): ?>
      <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    else:
      if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?>" colspan="2">
        <?php
        if($itemValue == ""): ?>
          <input style="ime-mode: active;" type="text" size="60" maxlength="15" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
        <?php
        else:
          $checkAray = explode(KEYWORD_DELIMITER_ITEM,$itemValue);
          for($i = 0; $i < count($checkAray); $i++){
            $checked = "";
            $editValue = str_replace("<br>","",$checkAray[$i]);
            $editValue = str_replace("<BR>","",$editValue);

            $regex = "(^|\"".KEYWORD_DELIMITER_ITEM.")".$editValue."(\"".KEYWORD_DELIMITER_ITEM & "|$)";
            if(preg_match($regex,$bikou)):
              $checked = "checked";
            else:
              if(empty($bikou)?"":$bikou == ""):
                if($default != ""):
                  if($default == $editValue):
                    $checked = "checked";
                  endif;
                endif;
              endif;
              if($i == 0): ?>
                <div style="height:40px;">
                <div style="float:left; height:40px; width:150px; line-height:40px;">
                遠近両用
                </div>
                <div style="float:left;">
              <?php
              endif; ?>
              <input type="radio" name="G_FREE<?php echo $no ?>" value="<?php echo $editValue ?>" <?php echo $checked ?>><?php echo $checkAray[$i] ?>
              <?php
              if($i == 2):
                echo "<br>";
              endif;
            endif;
          }
        endif;?>
    </div>
    </div>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?>" <?php echo $rowSpan?> nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td <?php echo $rowSpan?> class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $bikou ?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
    <?php
      endif;
      if($explanation != ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
    <?php
      endif; ?>
      </td>
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    endif;
  }


  function SetG_KEYWORD_BudgetOthers($disp, $must, $itemValue, $readOnlyFlg, $default, $no, $no2, $bikou, $o_bikou, $h_bikou, $ho_bikou, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $set_g_id = $GLOBALS['set_g_id'];
    if(!$disp): ?>
      <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    else:
      if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?>" colspan="2">
        <?php
        if($itemValue == ""): ?>
          <input style="ime-mode: active;" type="text" size="60" maxlength="15" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
        <?php
        else:
          $checkAray = explode(KEYWORD_DELIMITER_ITEM,$itemValue);
          for($i = 0; $i < count($checkAray); $i++){
            $checked = "";
            $editValue = str_replace("<br>","",$checkAray[$i]);
            $editValue = str_replace("<BR>","",$editValue);

            $regex = "(^|\"".KEYWORD_DELIMITER_ITEM.")".$editValue."(\"".KEYWORD_DELIMITER_ITEM & "|$)";
            if(preg_match($regex,$bikou)):
              $checked = "checked";
            else:
              if(empty($bikou)?"":$bikou == ""):
                if($default != ""):
                  if($default == $editValue):
                    $checked = "checked";
                  endif;
                endif;
              endif;
              if($i == 0): ?>
                <div style="height:40px;">
                <div style="float:left; height:40px; width:150px; line-height:40px;">
                  その他のメガネ
                </div>
                <div style="float:left;">
            <?php
              endif; ?>
              <input type="radio" name="G_FREE<?php echo $no ?>" value="<?php echo $editValue ?>" <?php echo $checked ?>><?php echo $checkAray[$i]; ?>
            <?php
              if($i == 2):
                echo "<br>";
              endif;
            endif;
          }
        endif;?>
    </div>
    </div>
  <?php
      else: ?>
        <td class="<?php echo $RegItem; ?>" <?php echo $rowSpan?> nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td <?php echo $rowSpan?> class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $bikou ?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
    <?php
      endif;
      if($explanation != ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
      <?php
      endif; ?>
    </td>
    <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    endif;
  }



  function SetG_KEYWORD_Location($disp, $must, $itemValue, $readOnlyFlg, $default, $no, $no2, $bikou, $o_bikou, $h_bikou, $ho_bikou, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $set_g_id = $GLOBALS['set_g_id'];
    if(!$disp): ?>
      <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    else:
      if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($bikou, $h_bikou) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>" colspan="2">
          <?php
          if($itemValue == ""): ?>
            <input style="ime-mode: active;" type="text" size="60" maxlength="15" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">　
            <br>例：東京駅より徒歩５分／恵比寿・代官山
          <?php
          else:
            $checkAray = explode(KEYWORD_DELIMITER_ITEM,$itemValue);
            for($i = 0; $i < count($checkAray); $i++){
              $checked = "";
              $editValue = str_replace("<br>","",$checkAray[$i]);
              $editValue = str_replace("<BR>","",$editValue);

              $regex = "(^|\"".KEYWORD_DELIMITER_ITEM.")".$editValue."(\"".KEYWORD_DELIMITER_ITEM & "|$)";
              if(preg_match($regex,$bikou)):
                $checked = "checked";
              else:
                if(empty($bikou)?"":$bikou == ""):
                  if($default != ""):
                    if($default == $editValue):
                      $checked = "checked";
                    endif;
                  endif;
                endif;
              endif; ?>
                <input type="checkbox" name="G_FREE<?php echo $no ?>" value="<?php echo $editValue ?>" <?php echo $checked ?>><?php echo $checkAray[$i];
            }
          endif;
      else: ?>
        <td class="<?php echo $RegItem; ?>" <?php echo $rowSpan?> nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $bikou ?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
    <?php
      endif;
      if($explanation != ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
    <?php
      endif; ?>
    </td>
    <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    endif;
  }


  function SetG_KEYWORD_Keyword($disp, $must, $itemValue, $readOnlyFlg, $default, $no, $no2, $bikou, $o_bikou, $h_bikou, $ho_bikou, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $set_g_id = $GLOBALS['set_g_id'];
    if(!$disp): ?>
      <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
      <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    else:
      if($set_g_id == "" && !$readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($bikou, $h_bikou) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <br>(90文字まで・改行なし)
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>" colspan="2">
        <?php
        if($itemValue == ""): ?>
          <textarea style="ime-mode:active; overflow:hidden; width:100%; line-height:150%;" rows="5" name="G_FREE<?php echo $no ?>"><?php echo $bikou ?></textarea>
        <?php
        else:
          $checkAray = explode(KEYWORD_DELIMITER_ITEM,$itemValue);
            for($i = 0; $i < count($checkAray); $i++){
              $checked = "";
              $editValue = str_replace("<br>","",$checkAray[$i]);
              $editValue = str_replace("<BR>","",$editValue);

              $regex = "(^|\"".KEYWORD_DELIMITER_ITEM.")".$editValue."(\"".KEYWORD_DELIMITER_ITEM & "|$)";
              if(preg_match($regex,$bikou)):
                $checked = "checked";
              else:
                if(empty($bikou)?"":$bikou == ""):
                  if($default != ""):
                    if($default == $editValue):
                      $checked = "checked";
                    endif;
                  endif;
                endif;
              endif;?>
              <input type="checkbox" name="G_FREE<?php echo $no ?>" value="<?php echo $editValue ?>" <?php echo $checked ?>><?php echo $checkAray[$i]; ?>
          <?php
            }
        endif;
      else: ?>
        <td class="<?php echo $RegItem; ?>" <?php echo $rowSpan?> nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織自由項目'.$no2)), '組織自由項目'.$no2); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $bikou ?>
          <input type="hidden" name="G_FREE<?php echo $no ?>" value="<?php echo $bikou ?>">
    <?php
      endif;
      if($explanation != ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
    <?php
      endif; ?>
      </td>
    <input type="hidden" name="G_O_BIKOU<?php echo $no ?>" value="<?php echo getDispIni($o_bikou, $dispIni) ?>">
  <?php
    endif;
  }

function SetG_ADD_MARKETING($disp, $must, $no, $no2, $data, $h_data, $readOnlyFlg, $explanation){
  $postid = $GLOBALS['postid'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  if($no < 10){
    $no = "0".$no;
  }
  $set_g_id = $GLOBALS['set_g_id'];
  $tg_id = $GLOBALS['tg_id'];
  if(!$disp): ?>
    <input type="hidden" name="G_ADD_MARKETING<?php echo $no ?>" value="<?php echo $data ?>">
  <?php
  else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($data, $h_data) ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('マーケティング自由項目'.$no2)), 'マーケティング自由項目'.$no2); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php
      if($set_g_id == "" && !$readOnlyFlg):?>
      <td class="<?php echo $RegValue; ?>">
        <?php $this->GetAddMarketingItem($postid, "G_MARKETING".$no, $data, $tg_id) ?>
      <?php
      else:?>

        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $data ?>
          <input type="hidden" name="G_ADD_MARKETING<?php echo $no ?>" value="<?php echo $data ?>">
        <?php
      endif;
        ?>

      <?php if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation; ?></font>
      <?php endif; ?>
      </td>
    <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

  endif;
} //End Sub

function SetG_ADD_MARKETING_TEXT($disp, $must, $no, $no2, $data, $h_data, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $set_g_id = $GLOBALS['set_g_id'];
  if($no < 10){
    $no = "0".$no;
  }
  if(!$disp): ?>
    <input type="hidden" name="G_MARKETING<?php echo $no ?>" value="<?php echo $data; ?>">
  <?php
  else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($data, $h_data) ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('マーケティング自由項目'.$no2)), 'マーケティング自由項目'.$no2); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php
        if($set_g_id == "" && !$readOnlyFlg):?>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: active;" type="text" size="60" maxlength="80" name="G_ADD_MARKETING<?php echo $no ?>" value="<?php echo $data ?>">
        <?php
        else:
          ?>
          <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
            <?php echo $data ?>
            <input type="hidden" name="G_MARKETING<?php echo $no ?>" value="<?php echo $data ?>">
          <?php
        endif; ?>
        <?php if($explanation !== ""): ?>
          <br><font color="red"><?php echo $explanation; ?></font>
        <?php endif; ?>
        </td>
    <?php
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  endif;
} //End Sub

function SetG_BANK_CD($disp, $must, $readOnlyFlg, $explanation){
  $postid = $GLOBALS['postid'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  if(empty($gd->BANK_CD))
    $gd->BANK_CD = "";
  if(!$disp):
    ?>
    <input type="hidden" name="G_BANK_CD" value="<?php $gd->BANK_CD ?>">
    <?php
  else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($gd->BANK_CD)?$gd->BANK_CD:"", !empty($gd->BANK_CD)?$gd->BANK_CD:""); ?>" width="120" id="m_claimClassNeed" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('銀行コード')), '銀行コード'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php
      if($readOnlyFlg): ?>
          <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
            <?php echo $gd->BANK_CD ?>
            <input type="hidden" name="G_BANK_CD" value="<?php echo $gd->BANK_CD; ?>">
          <?php
      else: ?>
          <td class="<?php echo $RegValue; ?>">
            <select name="G_BANK_CD" onChange="OnBankCodeChange(this, G_BRANCH_CD, '3', <?php echo $postid; ?>);">
            <?php $this->GetBankInfo(!empty($gd->BANK_CD)?$gd->BANK_CD:"") ?>
            </select>
          <?php
      endif; ?>
      <?php if($explanation !== ""):  ?>
          <br><font color="red"><?php echo $explanation; ?></font>
      <?php endif; ?>
    </td>
    <?php
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }

  endif;
} //End Sub
function SetG_BRANCH_CD($disp, $must, $readOnlyFlg, $explanation){
  $page_link = $this->getPageSlug('nakama-search-bank');
  $postid = $GLOBALS['postid'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  if(empty($gd->BRANCH_CD))
    $gd->BRANCH_CD = "";
  if(!$disp): ?>
      <input type="hidden" name="G_BRANCH_CD" value="<?php $gd->BRANCH_CD ?>">
      <?php
  else: ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($gd->BRANCH_CD, $gd->BRANCH_CD); ?>" id="m_claimClassNeed" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('支店コード')), '支店コード'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
        ?>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php $$gd->BRANCH_CD ?>
          <input type="hidden" name="G_BRANCH_CD" value="<?php echo $gd->BRANCH_CD ?>">
        </td>
        <?php
      else:
        ?>
        <td class="<?php echo $RegValue; ?>">
          <select name="G_BRANCH_CD" onChange="changeContactForm();changeClaimForm();"></select>
          <input type="button" name="search_button_g" value="検索" onClick="OnSearchBankG(<?php echo $postid; ?>, '<?php echo $page_link; ?>');">
      <?php if($explanation !== ""): ?>
          <br><font color="red"><?php echo $explanation; ?></font>
        <?php endif; ?>
    </td>
  <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
  endif;
} //End Sub

function SetG_ACCAUNT_TYPE($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  if(empty($gd->ACCOUNT_TYPE))
    $gd->ACCOUNT_TYPE = "";
  if(!$disp):
    ?>
    <input type="hidden" name="G_ACCAUNT_TYPE" value="<?php $gd->ACCOUNT_TYPE ?>">
    <?php
  else:
    ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($gd->ACCOUNT_TYPE, $gd->ACCOUNT_TYPE) ?>" id="m_claimClassNeed" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('科目')), '科目'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php
    if($readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php
              if($gd->ACCOUNT_TYPE == NAK_ACCOUNT_TYPE_CHECKING):
                echo $this->AccauntTypeText(NAK_ACCOUNT_TYPE_CHECKING);
              else:
                echo $this->AccauntTypeText(NAK_ACCOUNT_TYPE_SAVINGS);
              endif;
        ?>
          <input type="hidden" name="G_ACCAUNT_TYPE" value="<?php echo $gd->ACCOUNT_TYPE; ?>">
        <?php
    else:
        ?>
        <td class="<?php echo $RegValue; ?>">
          <select name="G_ACCAUNT_TYPE" onChange="changeContactForm();changeClaimForm();">
          <option value="">
          <option value="<?php echo NAK_ACCOUNT_TYPE_SAVINGS; ?>" <?php echo (!empty($gd->ACCOUNT_TYPE) && $gd->ACCOUNT_TYPE == NAK_ACCOUNT_TYPE_SAVINGS)?"selected":""; ?>>
            <?php echo $this->AccauntTypeText(NAK_ACCOUNT_TYPE_SAVINGS); ?>
          <option value="<?php echo NAK_ACCOUNT_TYPE_CHECKING; ?>" <?php echo (!empty($gd->ACCOUNT_TYPE) && $gd->ACCOUNT_TYPE == NAK_ACCOUNT_TYPE_CHECKING)?"selected":""; ?>>
            <?php echo $this->AccauntTypeText(NAK_ACCOUNT_TYPE_CHECKING); ?>
          </select>
        <?php
    endif;
    ?>
    <?php   if($explanation !== ""):?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>
    <?php
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }

  endif;
} //End Sub


function SetG_ACCOUNT_NO($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  if(empty($gd->ACCOUNT_NO))
    $gd->ACCOUNT_NO = "";
  if(!$disp): ?>
    <input type="hidden" name="G_ACCOUNT_NO" value="<?php echo $gd->ACCOUNT_NO; ?>">
    <?php
  else:
    ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($gd->ACCOUNT_NO, $gd->ACCOUNT_NO); ?>" id="m_claimClassNeed" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('口座番号')), '口座番号'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
<?php
    if($readOnlyFlg):
      ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $gd->ACCOUNT_NO; ?>
        <input type="hidden" name="G_ACCOUNT_NO" value="<?php echo $gd->ACCOUNT_NO ?>">
      </td>
      <?php
    else:
      ?>
      <td class="<?php echo $RegValue; ?>">
        <input style="ime-mode: disabled;" type="text" maxlength="7" size="60" name="G_ACCOUNT_NO" value="<?php echo !empty($gd->ACCOUNT_NO)?$gd->ACCOUNT_NO:"" ?>" onChange="changeContactForm();changeClaimForm();">

        <?php if($explanation !== ""):  ?>
            <br><font color="red"><?php echo $explanation ?></font>
        <?php endif; ?>
      </td>
    <?php
    endif;
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  endif;
} //End Sub


function SetG_ACCAUNT_NM($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  if(empty($gd->ACCOUNT_NM))
    $gd->ACCOUNT_NM = "";
  if(!$disp):
      ?>
      <input type="hidden" name="G_ACCAUNT_NM" value="<?php echo $gd->ACCOUNT_NM ?>">
      <?php
  else: ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp($gd->ACCOUNT_NM,$gd->ACCOUNT_NM) ?>" id="m_claimClassNeed" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('口座名義')), '口座名義'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <?php
          if($readOnlyFlg): ?>
              <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
                <?php $gd->ACCOUNT_NM ?>
                <input type="hidden" name="G_ACCAUNT_NM" value="<?php echo $gd->ACCOUNT_NM ?>">
              <?php
          else: ?>
              <td class="<?php echo $RegValue; ?>">
                <input style="ime-mode: active;" type="text" name="G_ACCAUNT_NM" maxlength="30" size="60" value="<?php echo !empty($gd->ACCOUNT_NM)?$gd->ACCOUNT_NM:""; ?>" onChange="Javascript:funcZenToHan(this);"><br>(半角ｶﾅ　半角英字　｢･｣　｢.｣のみ)
          <?php endif; ?>

          <?php if($explanation !== ""):  ?>
            <br><font color="red"><?php echo $explanation; ?></font>
          <?php   endif; ?>
          </td>
          <?php
          $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
        $this->SetDummyInputOpen();
      }

  endif;
} //End Sub

function SetG_CUST_NO($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  if(empty($gd->CUST_NO))
    $gd->CUST_NO = "";
  if(!$disp): ?>
    <input type="hidden" name="G_CUST_NO" value="<?php echo !empty($gd->CUST_NO)?$gd->CUST_NO:""; ?>">
    <?php
      else:
    ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($gd->CUST_NO, $gd->CUST_NO); ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('顧客番号')), '顧客番号'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php $gd->CUST_NO ?>
        <input type="hidden" name="G_CUST_NO" value="<?php echo !empty($gd->CUST_NO)?$gd->CUST_NO:""; ?>">
    <?php
    else:
      ?>
      <td class="<?php echo $RegValue; ?>">
        <input style="ime-mode: inactive;" type="text" name="G_CUST_NO" maxlength="12" size="60" value="<?php echo !empty($gd->CUST_NO)?$gd->CUST_NO:""; ?>" onChange="changeContactForm();changeClaimForm();">
    <?php
    endif;
    ?>
    <?php if($explanation !== ""):?>
      <br>
      <font color="red">
        <?php echo $explanation; ?>
      </font>
    <?php endif; ?>
    </td>
    <?php
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
        $this->SetDummyInputOpen();
    }
  endif;
} //End Sub
function SetG_SAVINGS_CD($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  if(empty($gd->SAVINGS_CD))
    $gd->SAVINGS_CD = "";
  if(!$disp):
  ?>
  <input type="hidden" name="G_SAVINGS_CD" value="<?php echo $gd->SAVINGS_CD ?>">
  <?php
    else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($gd->SAVINGS_CD,$gd->SAVINGS_CD); ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('貯金記号')), '貯金記号'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php $gd->SAVINGS_CD ?>
    <input type="hidden" name="G_SAVINGS_CD" value="<?php echo $gd->SAVINGS_CD ?>">
  <?php
      else:
  ?>
  <td class="<?php echo $RegValue; ?>">
    <input style="ime-mode: inactive;" type="text" name="G_SAVINGS_CD" maxlength="5" size="60" value="<?php echo !empty($gd->SAVINGS_CD)?$gd->SAVINGS_CD:"" ?>" onChange="changeContactForm();changeClaimForm();">
  <?php
    endif;
  ?>
  <?php if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>
  </td>
  <?php
  $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
$this->SetDummyInputOpen();
}
    endif;
} //End Sub
function SetG_SAVINGS_NO($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $gd = $GLOBALS['gd'];
  $gdh = $GLOBALS['gdh'];
  if(empty($gd->SAVINGS_NO))
    $gd->SAVINGS_NO = "";
  if(!$disp):
      ?>
      <input type="hidden" name="G_SAVINGS_NO" value="<?php echo $gd->SAVINGS_NO ?>">
      <?php
  else:
      ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp($gd->SAVINGS_NO,$gd->SAVINGS_NO) ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('貯金番号')), '貯金番号'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <?php
      if($readOnlyFlg):
        ?>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php $gd->SAVINGS_NO ?>
          <input type="hidden" name="G_SAVINGS_NO" value="<?php echo $gd->SAVINGS_NO ?>">
        <?php
      else:
        ?>
        <td class="<?php echo $RegValue; ?>">
           <input style="ime-mode: inactive;" type="text" name="G_SAVINGS_NO"  maxlength="8" size="60" value="<?php echo !empty($gd->SAVINGS_NO)?$gd->SAVINGS_NO:"" ?>" onChange="changeContactForm();changeClaimForm();">
        <?php
      endif;
      ?>
     <?php if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation; ?></font>
      <?php endif; ?>
      </td>
  <?php
  $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
      $this->SetDummyInputOpen();
    }

    endif;
} //End Sub

function SetP_P_ID($disp, $must, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$gdh = $GLOBALS['gdh'];
$chg = $GLOBALS['chg'];
if(!$disp): ?>
    <input type="hidden" name="P_P_ID" value="<?php echo $pd->P_ID; ?>">
    <?php
  else:
      ?>
      <td class="<?php echo $RegItem; ?>" width="120" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人ID')), '個人ID'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php
      if($chg == "1"):
        ?>
          <?php echo $pd->P_ID ?>
          <input type="hidden" name="P_P_ID" value="<?php echo $pd->P_ID; ?>">
        <?php
      else:
          ?>
            <?php echo $pd->P_ID ?>
            <input type="hidden" name="P_P_ID" value="<?php echo $pd->P_ID; ?>">
          <?php
      endif;
    ?>
  <?php if($explanation !== ""): ?>
      <br><font color="red"><?php echo $explanation; ?></font>
    <?php endif; ?>
</td>
<?php
  $gInputOpen = $GLOBALS['gInputOpen'];
  if($gInputOpen == "1"){
     $this->SetDummyInputOpen();
  }
endif;
} //End Sub

function SetP_PASSWORD($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$chg = $GLOBALS['chg'];
if(empty($pd->P_PASSWORD))
  $pd->P_PASSWORD = "";
if(!$disp):
$_SESSION["disp_JS_P_PASSWORD"] = false; ?>
<input type="hidden" name="P_PASSWORD" value="<?php echo $pd->P_PASSWORD; ?>">
<?php
else:
  $_SESSION["disp_JS_P_PASSWORD"] = true; ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($pd->P_PASSWORD)?$pd->P_PASSWORD:"",!empty($pd->P_PASSWORD)?$pd->P_PASSWORD:"") ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('パスワード')), 'パスワード'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php

  if(!$readOnlyFlg || $chg !== "1"): ?>
    <td class="<?php echo $RegValue; ?>">
      <div class="left">
        <input style="ime-mode: disabled; width:120px;" type="password" name="P_PASSWORD" value="<?php echo !empty($pd->P_PASSWORD)?$pd->P_PASSWORD:"" ?>">&nbsp;&nbsp;
        <span class="label_max_char">
          半角英数字４文字以上２０文字まで
        </span>
      </div>
      <div class="right">
        <input type="button" name="dispPPassBtn" style="width:150px;" value="パスワード表示" onClick="Javascript:chgMastPPassword();">
      </div>
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo !empty($pd->P_PASSWORD)?$pd->P_PASSWORD:"" ?>
    <input type="hidden" name="P_PASSWORD" value="<?php echo !empty($pd->P_PASSWORD)?$pd->P_PASSWORD:"" ?>">
    <?php

  endif;
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
  <?php
  endif; ?>
    </td>
<?php
  $gInputOpen = $GLOBALS['gInputOpen'];
  if($gInputOpen == "1"){
     $this->SetDummyInputOpen();
  }

endif;
}

function SetP_PASSWORD2($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$chg = $GLOBALS['chg'];

if(!$disp): ?>
  <input type="hidden" name="P_PASSWORD2" value="<?php echo !empty($pd->P_PASSWORD2)?$pd->P_PASSWORD2:"" ?>">
  <?php
else: ?>
    <td class="<?php echo $RegItem; ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('パスワード再入力')), 'パスワード再入力'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
  <?php
    if(!$readOnlyFlg || $chg !== "1"): ?>
      <td class="<?php echo $RegValue; ?>">
        <div class="left">
          <input style="ime-mode: disabled; width:120px;" type="password" name="P_PASSWORD2" value="<?php echo !empty($pd->P_PASSWORD2)?$pd->P_PASSWORD2:"" ?>">&nbsp;&nbsp;
          <span style="display:none;">
            <input type="password" name="P_PASSWORD_DUMMY" value="<?php echo !empty($pd->P_PASSWORD2)?$pd->P_PASSWORD2:"" ?>">
          </span>
          <span class="label_max_char">
            半角英数字４文字以上２０文字まで
          </span>
        </div>
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo !empty($pd->P_PASSWORD2)?$pd->P_PASSWORD2:"" ?>
        <input type="hidden" name="P_PASSWORD2" value="<?php echo !empty($pd->P_PASSWORD2)?$pd->P_PASSWORD2:"" ?>">
  <?php
    endif;
    if($explanation !== ""): ?>
      <br><font color="red"><?php echo $explanation ?></font>
  <?php
    endif; ?>
      </td>
  <?php
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
    endif;
}


function SetP_C_NAME($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $pd = $GLOBALS['pd'];
  $pdh = $GLOBALS['pdh'];
  $chg = $GLOBALS['chg'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  $P_C_NAME = '';
  if($pd)
    $P_C_NAME = (!empty($pd->C_FNAME)?$pd->C_FNAME:"")." ".(!empty($pd->C_LNAME)?$pd->C_LNAME:"");
  $P_C_NAME = ltrim($P_C_NAME);
  if(!$disp):
    $_SESSION["PUTOUT_JS_P_C_NAME"] = false; ?>
    <input type="hidden" name="P_C_NAME" value="<?php echo $P_C_NAME ?>">
    <input type="hidden" name="P_O_NAME" value="<?php echo $pd->O_C_FNAME ?>">
<?php
  else:
    $_SESSION["PUTOUT_JS_P_C_NAME"] = true; ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($P_C_NAME,$P_C_NAME) ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('氏名')), '氏名'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
  <?php
    if(!$readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?>">
        <input size="50" style="ime-mode: active;" type="text" name="P_C_NAME" value="<?php echo $P_C_NAME ?>" onChange="changeContactForm(); changeClaimForm();">
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $pd->C_FNAME." ".$pd->C_LNAME ?>
        <input type="hidden" name="P_C_NAME" value="<?php echo $P_C_NAME ?>">
  <?php
    endif;
    if($explanation !== ""): ?>
      <br><font color="red"><?php echo $explanation; ?></font>
  <?php
    endif; ?>
      </td>
  <?php
    if($gInputOpen == "1"): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_FNAME,$pd->O_C_FNAME) ?>">
        <select name="P_O_NAME" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_C_FNAME == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_C_FNAME == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_C_FNAME == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
  <?php
    else: ?>
      <input type="hidden" name="P_O_NAME" value="<?php echo $pd->O_C_FNAME ?>">
  <?php
    endif;
  endif;
}



function SetP_C_KANA($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $pd = $GLOBALS['pd'];
  $pdh = $GLOBALS['pdh'];
  $chg = $GLOBALS['chg'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  $P_C_NAME_KN = '';
  if($pd)
    $P_C_NAME_KN = (!empty($pd->C_FNAME_KN)?$pd->C_FNAME_KN:"")." ".(!empty($pd->C_LNAME_KN)?$pd->C_LNAME_KN:"");
  $P_C_NAME_KN = ltrim($P_C_NAME_KN);
  if(!$disp):
    $_SESSION["PUTOUT_JS_P_C_KANA"] = false; ?>
    <input type="hidden" name="P_C_KANA" value="<?php echo $P_C_NAME_KN ?>">
    <input type="hidden" name="P_O_KANA" value="<?php echo $pd->O_C_LNAME ?>">
<?php
  else:
    $_SESSION["PUTOUT_JS_P_C_KANA"] = true; ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($P_C_NAME_KN,$P_C_NAME_KN) ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('氏名フリガナ')), '氏名フリガナ'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php
    if(!$readOnlyFlg || $chg !== "1"): ?>
      <td class="<?php echo $RegValue; ?>">
        <input size="50" style="ime-mode: active;" type="text" name="P_C_KANA" value="<?php echo $P_C_NAME_KN ?>" onChange="Javascript:funcHiraganaToKatakana(this);">
      <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $P_C_NAME_KN ?>
        <input type="hidden" name="P_C_KANA" value="<?php echo $P_C_NAME_KN ?>">
      <?php

    endif;
    if($explanation !== ""): ?>
      <br>
      <font color="red"><?php echo $explanation; ?></font>
      <?php
    endif; ?>
    </td>
  <?php

    if($gInputOpen == "1"): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_LNAME,$pd->O_C_LNAME) ?>">
        <select name="P_O_KANA" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_C_LNAME == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_C_LNAME == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_C_LNAME == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
  <?php
    else: ?>
      <input type="hidden" name="P_O_KANA" value="<?php echo $pd->O_C_LNAME ?>">
  <?php

    endif;
  endif;
}

function SetP_C_NAME_EN($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $pd = $GLOBALS['pd'];
  $pdh = $GLOBALS['pdh'];
  $chg = $GLOBALS['chg'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  $P_C_NAME_EN = '';
  if($pd)
    $P_C_NAME_EN = (!empty($pd->C_NAME_EN)?$pd->C_NAME_EN:"");
  if(!$disp):
    $_SESSION["PUTOUT_JS_P_C_NAME_EN"] = false; ?>
    <input type="hidden" name="P_C_NAME_EN" value="<?php echo $P_C_NAME_EN; ?>">
  <?php
  else:
    $_SESSION["PUTOUT_JS_P_C_NAME_EN"] = true; ?>
    <td class="<?php echo $RegItem; ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('氏名　英語表記')), '氏名　英語表記'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php
    if(!$readOnlyFlg || $chg !== "1"): ?>
      <td class="<?php echo $RegValue; ?>">
        <input type="text" name="P_C_NAME_EN" value="<?php echo $P_C_NAME_EN; ?>">
      <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $P_C_NAME_EN ?>
        <input type="text" name="P_C_NAME_EN" value="<?php echo $P_C_NAME_EN; ?>">
      <?php

    endif;
    if($explanation !== ""): ?>
      <br>
      <font color="red"><?php echo $explanation; ?></font>
      <?php
    endif; ?>
    </td>
  <?php
    if($gInputOpen == "1"): 
      $this->SetDummyInputOpen();
    endif;
  endif;
}


function SetP_C_SEX($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $pd = $GLOBALS['pd'];
  $pdh = $GLOBALS['pdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($pd->C_SEX))
    $pd->C_SEX = "";
  if(!$disp): ?>
    <input type="hidden" name="P_C_SEX" value="<?php echo $pd->C_SEX ?>">
    <input type="hidden" name="P_O_SEX" value="<?php echo $pd->O_C_SEX; ?>">
<?php
  else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->C_SEX,$pd->C_SEX) ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('性別')), '性別'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
  <?php
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $pd->C_SEX ?>
        <input type="hidden" name="P_C_SEX" value="<?php echo $pd->C_SEX ?>">
  <?php
    else: ?>
    <td class="<?php echo $RegValue; ?>">
      <select name="P_C_SEX" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="">----</option>
        <option value="M" <?php echo (!empty($pd->C_SEX) && $pd->C_SEX == "M")?"selected":""; ?>>男</option>
        <option value="F" <?php echo (!empty($pd->C_SEX) && $pd->C_SEX == "F")?"selected":""; ?>>女</option>
      </select>
  <?php
    endif;
    if($explanation !== ""): ?>
      <br><font color="red"><?php echo $explanation ?></font>
  <?php
    endif; ?>
    </td>
  <?php
    if($gInputOpen == "1"):
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_SEX,$pd->O_C_SEX) ?>">
          <?php echo $this->openLevelText($pd->O_C_SEX) ?>
          <input type="hidden" name="P_O_SEX" value="<?php echo $pd->O_C_SEX; ?>">
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_SEX,$pd->O_C_SEX) ?>">
          <select name="P_O_SEX" style="max-width: 100%; width: 100%; padding: 0px;">
          <option value="0" <?php echo ($pd->O_C_SEX == "0")?"selected":""; ?>>公開しない</option>
          <option value="1" <?php echo ($pd->O_C_SEX == "1")?"selected":""; ?>>一般公開</option>
          <option value="2" <?php echo ($pd->O_C_SEX == "2")?"selected":""; ?>>会員にのみ公開</option>
          </select>
        </td>
    <?php
      endif;
    else: ?>
      <input type="hidden" name="P_O_SEX" value="<?php echo $pd->O_C_SEX; ?>">
  <?php

    endif;
  endif;
}


function SetP_C_URL($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$gInputOpen = $GLOBALS['gInputOpen'];
if(empty($pd->C_URL))
  $pd->C_URL = "";
if(!$disp): ?>
  <input type="hidden" name="P_C_URL" value="<?php echo $pd->C_URL ?>">
  <input type="hidden" name="P_O_URL" value="<?php echo $pd->O_C_URL; ?>">
<?php
else: ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->C_URL,$pd->C_URL) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人ＵＲＬ')), '個人ＵＲＬ'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
<?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $pd->C_URL ?>
      <input type="hidden" name="P_C_URL" value="<?php echo $pd->C_URL ?>">
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" size="60" maxlength="80" name="P_C_URL" value="<?php echo !empty($pd->C_URL)?$pd->C_URL:"" ?>">
<?php
  endif;
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation ?></font>
<?php
  endif; ?>
    </td>
<?php
  if($gInputOpen == "1"):
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_URL,$pd->O_C_URL) ?>">
        <?php echo $this->openLevelText($pd->O_C_URL) ?>
        <input type="hidden" name="P_O_URL" value="<?php echo $pd->O_C_URL; ?>">
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_URL,$pd->O_C_URL) ?>">
        <select name="P_O_URL" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_C_URL == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_C_URL == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_C_URL == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
  <?php
    endif;
  else: ?>
      <input type="hidden" name="P_O_URL" value="<?php echo $pd->O_C_URL; ?>">
<?php
  endif;
endif;
}


function SetP_C_EMAIL($disp, $must,$readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$gInputOpen = $GLOBALS['gInputOpen'];
if(empty($pd->C_EMAIL))
  $pd->C_EMAIL = "";
if(!$disp):
  $_SESSION["PUTOUT_JS_P_C_EMAIL"] = false; ?>
  <input type="hidden" name="P_C_EMAIL" value="<?php echo $pd->C_EMAIL; ?>">
  <input type="hidden" name="P_O_EMAIL" value="<?php echo $pd->O_C_EMAIL;?>">
<?php
else:
  $_SESSION["PUTOUT_JS_P_C_EMAIL"] = true; ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->C_EMAIL,$pd->C_EMAIL) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人Ｅ－ＭＡＩＬ')), '個人Ｅ－ＭＡＩＬ'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
<?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $pd->C_EMAIL ?>
      <input type="hidden" name="P_C_EMAIL" value="<?php echo $pd->C_EMAIL ?>">
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" size="60" maxlength="80" name="P_C_EMAIL" value="<?php echo !empty($pd->C_EMAIL)?$pd->C_EMAIL:""; ?>" onChange="changeContactForm();changeClaimForm();">
<?php
  endif;
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation ?></font>
<?php
  endif; ?>
    </td>
<?php
  if($gInputOpen == "1"):
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_EMAIL,$pd->O_C_EMAIL) ?>">
        <?php echo $this->openLevelText($pd->O_C_EMAIL) ?>
        <input type="hidden" name="P_O_EMAIL" value="<?php echo $pd->O_C_EMAIL; ?>">
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_EMAIL,$pd->O_C_EMAIL) ?>">
        <select name="P_O_EMAIL" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_C_EMAIL == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_C_EMAIL == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_C_EMAIL == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
  <?php
    endif;
  else: ?>
      <input type="hidden" name="P_O_EMAIL" value="<?php echo $pd->O_C_EMAIL ?>">
<?php
  endif;
endif;
}

function SetP_C_EMAIL2($disp, $must,$readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$gInputOpen = $GLOBALS['gInputOpen'];
if(empty($pd->C_EMAIL))
  $pd->C_EMAIL = "";
if(!$disp):
  $_SESSION["PUTOUT_JS_P_C_EMAIL2"] = false; ?>
  <input type="hidden" name="P_C_EMAIL2" value="<?php echo $pd->C_EMAIL; ?>">
  <input type="hidden" name="P_O_EMAIL2" value="<?php echo $pd->O_C_EMAIL;?>">
<?php
else:
  $_SESSION["PUTOUT_JS_P_C_EMAIL2"] = true; ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->C_EMAIL,$pd->C_EMAIL) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人Ｅ－ＭＡＩＬ再入力')), '個人Ｅ－ＭＡＩＬ再入力'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
<?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $pd->C_EMAIL ?>
      <input type="hidden" name="P_C_EMAIL2" value="<?php echo $pd->C_EMAIL ?>">
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" size="60" maxlength="80" name="P_C_EMAIL2" value="<?php echo !empty($pd->C_EMAIL)?$pd->C_EMAIL:""; ?>" onChange="changeContactForm();changeClaimForm();"  autocomplete="nope">
<?php
  endif;
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation ?></font>
<?php
  endif; ?>
    </td>
<?php
  if($gInputOpen == "1"):
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_EMAIL,$pd->O_C_EMAIL) ?>">
        <?php echo $this->openLevelText($pd->O_C_EMAIL) ?>
        <input type="hidden" name="P_O_EMAIL2" value="<?php echo $pd->O_C_EMAIL; ?>">
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_EMAIL,$pd->O_C_EMAIL) ?>">
        <select name="P_O_EMAIL2" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_C_EMAIL == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_C_EMAIL == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_C_EMAIL == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
  <?php
    endif;
  else: ?>
      <input type="hidden" name="P_O_EMAIL2" value="<?php echo $pd->O_C_EMAIL ?>">
<?php
  endif;
endif;
}



function SetP_C_CC_EMAIL($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$gInputOpen = $GLOBALS['gInputOpen'];
if(empty($pd->C_CC_EMAIL))
  $pd->C_CC_EMAIL = "";
if(!$disp):
  $_SESSION["PUTOUT_JS_P_C_CC_EMAIL"] = false; ?>
  <input type="hidden" name="P_C_CC_EMAIL" value="<?php echo $pd->C_CC_EMAIL ?>">
  <input type="hidden" name="P_O_C_CC_EMAIL" value="<?php echo $pd->O_C_CC_EMAIL; ?>">
<?php
else:
  $_SESSION["PUTOUT_JS_P_C_CC_EMAIL"] = true; ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->C_CC_EMAIL,$pd->C_CC_EMAIL) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人追加送信先Ｅ－ＭＡＩＬ')), '個人追加送信先Ｅ－ＭＡＩＬ'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
<?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $pd->C_CC_EMAIL ?>
      <input type="hidden" name="P_C_CC_EMAIL" value="<?php echo $pd->C_CC_EMAIL ?>">
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" size="60" maxlength="80" name="P_C_CC_EMAIL" value="<?php echo !empty($pd->C_CC_EMAIL)?$pd->C_CC_EMAIL:"" ?>" onChange="changeContactForm();changeClaimForm();">
<?php
  endif;
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation ?></font>
<?php
  endif; ?>
</td>
<?php
  if($gInputOpen == "1"):
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_CC_EMAIL,$pd->O_C_CC_EMAIL) ?>">
        <?php echo $this->openLevelText($pd->O_C_CC_EMAIL) ?>
        <input type="hidden" name="P_O_C_CC_EMAIL" value="<?php echo $pd->O_C_CC_EMAIL ?>">
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_CC_EMAIL,$pd->O_C_CC_EMAIL) ?>">
        <select name="P_O_C_CC_EMAIL" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_C_CC_EMAIL == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_C_CC_EMAIL == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_C_CC_EMAIL == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
  <?php
    endif;
  else: ?>
      <input type="hidden" name="P_O_C_CC_EMAIL" value="<?php echo $pd->O_C_CC_EMAIL ?>">
<?php

  endif;
endif;
}


function SetP_C_TEL($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$gInputOpen = $GLOBALS['gInputOpen'];
if(empty($pd->C_TEL))
  $pd->C_TEL = "";
$c_tel1 = $c_tel2 = $c_tel3 = "";
if(!empty($pd->C_TEL)){
  $c_tel1 = explode("-",$pd->C_TEL)[0];
  $c_tel2 = explode("-",$pd->C_TEL)[1];
  $c_tel3 = explode("-",$pd->C_TEL)[2];
}
if(!$disp):
  $_SESSION["PUTOUT_JS_P_C_TEL"] = false; ?>
  <input type="hidden" name="P_C_TEL_1" value="<?php echo $c_tel1; ?>">
  <input type="hidden" name="P_C_TEL_2" value="<?php echo $c_tel2; ?>">
  <input type="hidden" name="P_C_TEL_3" value="<?php echo $c_tel3; ?>">
  <input type="hidden" name="P_C_TEL"   value="<?php echo $pd->C_TEL ?>">
  <input type="hidden" name="P_O_TEL"   value="<?php echo $pd->O_C_TEL; ?>">
<?php
else:
  $_SESSION["PUTOUT_JS_P_C_TEL"] = true; ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->C_TEL,$pd->C_TEL) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人ＴＥＬ')), '個人ＴＥＬ'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
<?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $pd->C_TEL ?>
      <input type="hidden" name="P_C_TEL_1" value="<?php echo $c_tel1 ?>">
      <input type="hidden" name="P_C_TEL_2" value="<?php echo $c_tel2 ?>">
      <input type="hidden" name="P_C_TEL_3" value="<?php echo $c_tel3 ?>">
      <input type="hidden" name="P_C_TEL"   value="<?php echo $pd->C_TEL ?>">
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_C_TEL_1" value="<?php echo $c_tel1 ?>" onChange="changeContactForm();changeClaimForm();">&nbsp;-
      <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_C_TEL_2" value="<?php echo $c_tel2 ?>" onChange="changeContactForm();changeClaimForm();">&nbsp;-
      <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_C_TEL_3" value="<?php echo $c_tel3 ?>" onChange="changeContactForm();changeClaimForm();">
      <input type="hidden" name="P_C_TEL" value="<?php echo $pd->C_TEL ?>">
<?php
  endif;
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation ?></font>
<?php
  endif; ?>
    </td>
<?php
  if($gInputOpen == "1"):
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_TEL,$pd->O_C_TEL) ?>">
        <?php echo $this->openLevelText($pd->O_C_TEL) ?>
        <input type="hidden" name="P_O_TEL" value="<?php echo $pd->O_C_TEL ?>">
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_TEL,$pd->O_C_TEL) ?>">
        <select name="P_O_TEL" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_C_TEL == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_C_TEL == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_C_TEL == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
  <?php
    endif;
  else: ?>
      <input type="hidden" name="P_O_TEL" value="<?php echo $pd->O_C_TEL?>">
<?php

  endif;
endif;
}



function SetP_C_FAX($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$gInputOpen = $GLOBALS['gInputOpen'];
$c_fax1 = $c_fax2 = $c_fax3 = "";
if(!empty($pd->C_FAX)){
  $c_fax1 = explode("-",$pd->C_FAX)[0];
  $c_fax2 = explode("-",$pd->C_FAX)[1];
  $c_fax3 = explode("-",$pd->C_FAX)[2];
}else {
  $pd->C_FAX = "";
}
if(!$disp): ?>
<input type="hidden" name="P_C_FAX_1" value="<?php echo $c_fax1 ?>">
<input type="hidden" name="P_C_FAX_2" value="<?php echo $c_fax2 ?>">
<input type="hidden" name="P_C_FAX_3" value="<?php echo $c_fax3 ?>">
<input type="hidden" name="P_C_FAX"   value="<?php echo $pd->C_FAX ?>">
<input type="hidden" name="P_O_FAX"   value="<?php echo $pd->O_C_FAX ?>">
<?php
else: ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->C_FAX,$pd->C_FAX) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人ＦＡＸ')), '個人ＦＡＸ'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
<?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $pd->C_FAX ?>
      <input type="hidden" name="P_C_FAX_1" value="<?php echo $c_fax1 ?>">
      <input type="hidden" name="P_C_FAX_2" value="<?php echo $c_fax2 ?>">
      <input type="hidden" name="P_C_FAX_3" value="<?php echo $c_fax3 ?>">
      <input type="hidden" name="P_C_FAX"   value="<?php echo $pd->C_FAX ?>">
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_C_FAX_1" value="<?php echo $c_fax1 ?>" onChange="changeContactForm();changeClaimForm();">&nbsp;-
      <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_C_FAX_2" value="<?php echo $c_fax2 ?>" onChange="changeContactForm();changeClaimForm();">&nbsp;-
      <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_C_FAX_3" value="<?php echo $c_fax3 ?>" onChange="changeContactForm();changeClaimForm();">
      <input type="hidden" name="P_C_FAX" value="<?php echo $pd->C_FAX ?>">
<?php
  endif;
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation ?></font>
<?php
  endif; ?>
    </td>
<?php
  if($gInputOpen == "1"):
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_FAX,$pd->O_C_FAX) ?>">
        <?php echo $this->openLevelText($pd->O_C_FAX) ?>
        <input type="hidden" name="P_O_FAX" value="<?php echo $pd->O_C_FAX; ?>">
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_FAX,$pd->O_C_FAX) ?>">
        <select name="P_O_FAX" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_C_FAX == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_C_FAX == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_C_FAX == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
  <?php
    endif;
  else: ?>
      <input type="hidden" name="P_O_FAX" value="<?php echo $pd->O_C_FAX ?>">
<?php

  endif;
endif;
}


function SetP_C_PTEL($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$gInputOpen = $GLOBALS['gInputOpen'];
$c_ptel1 = $c_ptel2 = $c_ptel3 = "";
if(!empty($pd->C_PTEL)){
  $c_ptel1 = explode("-",$pd->C_PTEL)[0];
  $c_ptel2 = explode("-",$pd->C_PTEL)[1];
  $c_ptel3 = explode("-",$pd->C_PTEL)[2];
}else {
  $pd->C_PTEL = "";
}

if(!$disp): ?>
  <input type="hidden" name="P_C_PTEL_1" value="<?php echo $c_ptel1 ?>">
  <input type="hidden" name="P_C_PTEL_2" value="<?php echo $c_ptel2 ?>">
  <input type="hidden" name="P_C_PTEL_3" value="<?php echo $c_ptel3 ?>">
  <input type="hidden" name="P_C_PTEL"   value="<?php echo $pd->C_PTEL ?>">
  <input type="hidden" name="P_O_PTEL"   value="<?php echo $pd->O_C_PTEL ?>">
<?php
else: ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->C_PTEL,$pd->C_PTEL) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人携帯ＴＥＬ')), '個人携帯ＴＥＬ'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
<?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $pd->C_PTEL ?>
      <input type="hidden" name="P_C_PTEL_1" value="<?php echo $c_ptel1 ?>">
      <input type="hidden" name="P_C_PTEL_2" value="<?php echo $c_ptel2 ?>">
      <input type="hidden" name="P_C_PTEL_3" value="<?php echo $c_ptel3 ?>">
      <input type="hidden" name="P_C_PTEL"   value="<?php echo $pd->C_PTEL ?>">
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_C_PTEL_1" value="<?php echo $c_ptel1 ?>">&nbsp;-
      <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_C_PTEL_2" value="<?php echo $c_ptel2 ?>">&nbsp;-
      <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_C_PTEL_3" value="<?php echo $c_ptel3 ?>">
      <input type="hidden" name="P_C_PTEL" value="<?php echo $pd->C_PTEL ?>">
<?php
  endif;
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation ?></font>
<?php
  endif; ?>
</td>
<?php
  if($gInputOpen == "1"):
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_PTEL,$pd->O_C_PTEL) ?>">
        <?php echo $this->openLevelText($pd->O_C_PTEL) ?>
        <input type="hidden" name="P_O_PTEL" value="<?php echo $pd->O_C_PTEL; ?>">
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_PTEL,$pd->O_C_PTEL) ?>">
        <select name="P_O_PTEL" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_C_PTEL == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_C_PTEL == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_C_PTEL == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
  <?php
    endif;
  else: ?>
      <input type="hidden" name="P_O_PTEL" value="<?php echo $pd->O_C_PTEL ?>">
<?php
  endif;
endif;
}


function SetP_C_PMAIL($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$gInputOpen = $GLOBALS['gInputOpen'];
if(empty($pd->C_PMAIL))
  $pd->C_PMAIL = "";
if(!$disp):
  $_SESSION["PUTOUT_JS_P_C_PMAIL"] = false; ?>
  <input type="hidden" name="P_C_PMAIL" value="<?php echo $pd->C_PMAIL ?>">
  <input type="hidden" name="P_O_PMAIL" value="<?php echo $pd->O_C_PMAIL ?>">
  <input type="hidden" name="P_C_PMAIL_URL" value="">
<?php
else:
  $_SESSION["PUTOUT_JS_P_C_PMAIL"] = true; ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->C_PMAIL,$pd->C_PMAIL) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('携帯メール')), '携帯メール'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
<?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $pd->C_PMAIL ?>
      <input type="hidden" name="P_C_PMAIL" value="<?php echo $pd->C_PMAIL ?>">
      <input type="hidden" name="P_C_PMAIL_URL" value="">
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" size="60" maxlength="80" name="P_C_PMAIL" value="<?php echo !empty($pd->C_PMAIL)?$pd->C_PMAIL:"" ?>">
    <?php
      if(!empty($_SESSION["M_CONF.OP2"]) && $_SESSION["M_CONF.OP2"] == true): ?>
        <input type="button" value="携帯簡易ログインURL通知" style="width:160px;" onClick="Javascript:OnCheck('<?php echo $pd->P_ID ?>');">
    <?php
      else: ?>
        <input type="hidden" name="P_C_PMAIL_URL" value="">
    <?php
      endif;
  endif;
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation ?></font>
<?php
  endif; ?>
    </td>
<?php
  if($gInputOpen == "1"):
    if($readOnlyFlg):?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_PMAIL,$pd->O_C_PMAIL) ?>">
        <?php echo $this->openLevelText($pd->O_C_PMAIL) ?>
        <input type="hidden" name="P_O_PMAIL" value="<?php echo $pd->O_C_PMAIL ?>">
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_PMAIL,$pd->O_C_PMAIL) ?>">
        <select name="P_O_PMAIL" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_C_PMAIL == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_C_PMAIL == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_C_PMAIL == "2")?"selected":""; ?> >会員にのみ公開</option>
        </select>
      </td>
  <?php
    endif;
  else: ?>
      <input type="hidden" name="P_O_PMAIL" value="<?php echo $pd->O_C_PMAIL ?>">
<?php

  endif;
endif;
}

function SetP_C_PMAIL2($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $pd = $GLOBALS['pd'];
  $pdh = $GLOBALS['pdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
if(empty($pd->C_PMAIL))
  $pd->C_PMAIL = "";
if(!$disp):
  $_SESSION["PUTOUT_JS_P_C_PMAIL2"] = false; ?>
  <input type="hidden" name="P_C_PMAIL2" value="<?php echo $pd->C_PMAIL ?>">
  <input type="hidden" name="P_O_PMAIL2" value="<?php echo $pd->O_C_PMAIL ?>">
  <input type="hidden" name="P_C_PMAIL2_URL" value="">
<?php
else:
  $_SESSION["PUTOUT_JS_P_C_PMAIL2"] = true; ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->C_PMAIL,$pd->C_PMAIL) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('携帯メール再入力')), '携帯メール再入力'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
<?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $pd->C_PMAIL ?>
      <input type="hidden" name="P_C_PMAIL2" value="<?php echo $pd->C_PMAIL; ?>">
      <input type="hidden" name="P_C_PMAIL2_URL" value="">
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" size="60" maxlength="80" name="P_C_PMAIL2" value="<?php echo !empty($pd->C_PMAIL)?$pd->C_PMAIL:"" ?>">
    <?php
      if(!empty($_SESSION["M_CONF.OP2"]) && $_SESSION["M_CONF.OP2"] == true): ?>
        <input type="button" value="携帯簡易ログインURL通知" style="width:160px;" onClick="Javascript:OnCheck('<?php echo $pd->P_ID ?>');">
    <?php
      else: ?>
        <input type="hidden" name="P_C_PMAIL2_URL" value="">
    <?php
      endif;
  endif;
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation ?></font>
<?php
  endif; ?>
    </td>
<?php
  if($gInputOpen == "1"):
    if($readOnlyFlg):?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_PMAIL,$pd->O_C_PMAIL) ?>">
        <?php echo $this->openLevelText($pd->O_C_PMAIL) ?>
        <input type="hidden" name="P_O_PMAIL2" value="<?php echo $pd->O_C_PMAIL ?>">
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_PMAIL,$pd->O_C_PMAIL) ?>">
        <select name="P_O_PMAIL2" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_C_PMAIL == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_C_PMAIL == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_C_PMAIL == "2")?"selected":""; ?> >会員にのみ公開</option>
        </select>
      </td>
  <?php
    endif;
  else: ?>
      <input type="hidden" name="P_O_PMAIL" value="<?php echo $pd->O_C_PMAIL ?>">
<?php

  endif;
endif;
}

function SetP_C_POST($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $page_link = $this->getPageSlug('nakama-member-zipcode');
  $postid = $GLOBALS['postid'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$gInputOpen = $GLOBALS['gInputOpen'];
$c_postU = $c_postL = "";
if(!empty($pd->C_POST)){
  $c_postU = explode("-",$pd->C_POST)[0];
  $c_postL = explode("-",$pd->C_POST)[1];
}else {
  $pd->C_POST = "";
}

if(!$disp):
  $_SESSION["PUTOUT_JS_P_C_POST"] = false; ?>
    <input type="hidden" name="P_C_POST_u" value="<?php echo $c_postU ?>">
    <input type="hidden" name="P_C_POST_l" value="<?php echo $c_postL ?>">
    <input type="hidden" name="P_C_POST"   value="<?php echo $pd->C_POST ?>">
    <input type="hidden" name="P_O_POST"   value="<?php echo $pd->O_C_POST; ?>">
<?php
else:
  $_SESSION["PUTOUT_JS_P_C_POST"] = true; ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->C_POST,$pd->C_POST) ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人〒')), '個人〒'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
<?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $pd->C_POST ?>
      <input type="hidden" name="P_C_POST_u" value="<?php echo $c_postU ?>">
      <input type="hidden" name="P_C_POST_l" value="<?php echo $c_postL ?>">
      <input type="hidden" name="P_C_POST"   value="<?php echo $pd->C_POST ?>">
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" class="w_60px" maxlength="3" name="P_C_POST_u" value="<?php echo $c_postU ?>" onChange="changeContactForm();changeClaimForm();">&nbsp;-
      <input style="ime-mode: disabled;" type="text" class="w_60px" maxlength="4" name="P_C_POST_l" value="<?php echo $c_postL ?>" onChange="changeContactForm();changeClaimForm();">
      <input type="hidden" name="P_C_POST" value="<?php echo $pd->C_POST ?>">
      <input type="button" value="住所検索" onClick="OnZipCode('P_C_POST_u', 'P_C_POST_l', <?php echo $postid; ?>, '<?php echo $page_link; ?>');">

<?php
  endif;
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation ?></font>
<?php
  endif; ?>
    </td>
<?php
  if($gInputOpen == "1"):
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_POST,$pd->O_C_POST) ?>">
        <?php echo $this->openLevelText($pd->O_C_POST) ?>
        <input type="hidden" name="P_O_POST" value="<?php echo $pd->O_C_POST; ?>">
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_POST,$pd->O_C_POST) ?>">
        <select name="P_O_POST" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_C_POST == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_C_POST == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_C_POST == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
  <?php
    endif;
  else: ?>
      <input type="hidden" name="P_O_POST" value="<?php echo $pd->O_C_POST; ?>">
<?php
  endif;
endif;
}

function SetP_C_STA($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$gInputOpen = $GLOBALS['gInputOpen'];
if(empty($pd->C_STA))
  $pd->C_STA = "";
if(!$disp):
  $_SESSION["PUTOUT_JS_P_C_STA"] = false; ?>
    <input type="hidden" name="P_C_STA" value="<?php echo $pd->C_STA ?>">
    <input type="hidden" name="P_O_STA" value="<?php echo $pd->O_C_STA; ?>">
<?php
else:
  $_SESSION["PUTOUT_JS_P_C_STA"] = true; ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->C_STA,$pd->C_STA) ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人都道府県')), '個人都道府県'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
<?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $pd->C_STA ?>
      <input type="hidden" name="P_C_STA" value="<?php echo $pd->C_STA ?>">
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?>">
      <select name="P_C_STA" onChange="changeContactForm();changeClaimForm();">
      <script type="text/javascript">StateSelectOptions2("<?php echo $pd->C_STA ?>");</script>
      </select>
<?php
  endif;
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation ?></font>
<?php
  endif; ?>
    </td>
<?php
  if($gInputOpen == "1"):
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_STA,$pd->O_C_STA) ?>">
        <?php echo $this->openLevelText($pd->O_C_STA) ?>
       <input type="hidden" name="P_O_STA" value="<?php echo $pd->O_C_STA; ?>">
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_STA,$pd->O_C_STA) ?>">
        <select name="P_O_STA" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_C_STA == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_C_STA == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_C_STA == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
  <?php
    endif;
  else: ?>
      <input type="hidden" name="P_O_STA" value="<?php echo $pd->O_C_STA; ?>">
<?php
  endif;
endif;
}



function SetP_C_ADR($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$gInputOpen = $GLOBALS['gInputOpen'];
if(empty($pd->C_ADR))
  $pd->C_ADR = "";
if(!$disp):?>
    <input type="hidden" name="P_C_ADR" value="<?php echo $pd->C_ADR ?>">
    <input type="hidden" name="P_C_ADR" value="<?php echo $pd->O_C_ADR; ?>">
<?php
else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($pd->C_ADR)?$pd->C_ADR:"",!empty($pd->C_ADR)?$pd->C_ADR:"") ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人住所１')), '個人住所１'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
<?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $pd->C_ADR ?>
      <input type="hidden" name="P_C_ADR" value="<?php echo !empty($pd->C_ADR)?$pd->C_ADR:"" ?>">
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?>">
  <?php
    if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) && $_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
      <input style="ime-mode: active;" type="text" size="60" maxlength="250" name="P_C_ADR" value="<?php echo !empty($pd->C_ADR)?$pd->C_ADR:"" ?>" onChange="Javascript:funcHanToZen(this);">
  <?php
    else: ?>
      <input style="ime-mode: active;" type="text" size="60" maxlength="250" name="P_C_ADR" value="<?php echo !empty($pd->C_ADR)?$pd->C_ADR:""; ?>" onChange="changeContactForm();changeClaimForm();">
  <?php
    endif; ?>
<?php
  endif; ?>
<?php
if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation ?></font>
<?php
endif; ?>
    </td>
<?php
if($gInputOpen == "1"):
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_ADR,$pd->O_C_ADR) ?>">
      <?php echo $this->openLevelText($pd->O_C_ADR) ?>
      <input type="hidden" name="O_C_ADR" value="<?php echo $pd->O_C_ADR ?>">
    </td>
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_ADR,$pd->O_C_ADR) ?>">
      <select name="O_C_ADR" style="max-width: 100%; width: 100%; padding: 0px;">
      <option value="0" <?php echo ($pd->O_C_ADR == "0")?"selected":""; ?>>公開しない</option>
      <option value="1" <?php echo ($pd->O_C_ADR == "1")?"selected":""; ?>>一般公開</option>
      <option value="2" <?php echo ($pd->O_C_ADR == "2")?"selected":""; ?>>会員にのみ公開</option>
      </select>
    </td>
<?php
  endif;
else: ?>
    <input type="hidden" name="O_C_ADR" value="<?php echo $pd->O_C_ADR; ?>">
<?php

  endif;
endif;
}


function SetP_C_ADR2($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $pd = $GLOBALS['pd'];
  $pdh = $GLOBALS['pdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($pd->C_ADR2 ))
    $pd->C_ADR2 = "";
  if(!$disp):?>
    <input type="hidden" name="P_C_ADR2" value="<?php echo !empty($pd->C_ADR2)?$pd->C_ADR2:"" ?>">
<?php
else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($pd->C_ADR2)?$pd->C_ADR2:"",!empty($pd->C_ADR2)?$pd->C_ADR2:"") ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人住所２')), '個人住所２'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
<?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $pd->C_ADR2 ?>
      <input type="hidden" name="P_C_ADR2" value="<?php echo !empty($pd->C_ADR2)?$pd->C_ADR2:"" ?>">
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?>">
    <?php
      if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) && $_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
        <input style="ime-mode: active;" type="text" size="60" maxlength="80" name="P_C_ADR2" value="<?php echo !empty($pd->C_ADR2)?$pd->C_ADR2:"" ?>" onChange="Javascript:funcHanToZen(this);">
        <?php
      else: ?>
        <input style="ime-mode: active;" type="text" size="60" maxlength="80" name="P_C_ADR2" value="<?php echo !empty($pd->C_ADR2)?$pd->C_ADR2:"" ?>" onChange="changeContactForm();changeClaimForm();">
        <?php
      endif;
      endif;
      if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
    <?php
      endif; ?>
    </td>
    <?php if($gInputOpen == 1): ?>
    <td class="<?php echo $RegValue; ?>"></td>
    <?php endif; ?>
<?php

endif;
}

function SetP_C_ADR3($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $pd = $GLOBALS['pd'];
  $pdh = $GLOBALS['pdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($pd->C_ADR3 ))
    $pd->C_ADR3 = "";
  if(!$disp): ?>
      <input type="hidden" name="P_C_ADR3" value="<?php echo !empty($pd->C_ADR3)?$pd->C_ADR3:"" ?>">
  <?php
  else: ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($pd->C_ADR3)?$pd->C_ADR3:"",!empty($pd->C_ADR3)?$pd->C_ADR3:"") ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人住所３')), '個人住所３'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
  <?php
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $pd->C_ADR3 ?>
        <input type="hidden" name="P_C_ADR3" value="<?php echo !empty($pd->C_ADR3)?$pd->C_ADR3:"" ?>">
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?>">
      <?php
        if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) && $_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
          <input style="ime-mode: active;" type="text" size="60" maxlength="80" name="P_C_ADR3" value="<?php echo !empty($pd->C_ADR3)?$pd->C_ADR3:"" ?>" onChange="Javascript:funcHanToZen(this);">
          <?php
        else: ?>
          <input style="ime-mode: active;" type="text" size="60" maxlength="80" name="P_C_ADR3" value="<?php echo !empty($pd->C_ADR3)?$pd->C_ADR3:"" ?>" onChange="changeContactForm();changeClaimForm();">
          <?php
        endif;
        endif;
        if($explanation !== ""): ?>
          <br><font color="red"><?php echo $explanation ?></font>
      <?php
        endif; ?>
      </td>
      <?php if($gInputOpen == 1): ?>
      <td class="<?php echo $RegValue; ?>"></td>
      <?php endif; ?>
  <?php

  endif;
}

function SetP_C_ADR_EN($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $pd = $GLOBALS['pd'];
  $pdh = $GLOBALS['pdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($pd->C_ADR_EN ))
    $pd->C_ADR_EN = "";
  if(!$disp):
    $_SESSION["PUTOUT_JS_P_C_ADR_EN"] = false; ?>
      <input type="hidden" name="P_C_ADR_EN" value="<?php echo !empty($pd->C_ADR_EN)?$pd->C_ADR_EN:"" ?>">
  <?php
  else:
    $_SESSION["PUTOUT_JS_P_C_ADR_EN"] = true; ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($pd->C_ADR_EN)?$pd->C_ADR_EN:"",!empty($pd->C_ADR_EN)?$pd->C_ADR_EN:"") ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人住所　英語表記')), '個人住所　英語表記'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
  <?php
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $pd->C_ADR_EN ?>
        <input type="hidden" name="P_C_ADR_EN" value="<?php echo !empty($pd->C_ADR_EN)?$pd->C_ADR_EN:"" ?>">
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?>">
      <?php
        if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) && $_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
          <input style="ime-mode: active;" type="text" size="60" maxlength="80" name="P_C_ADR_EN" value="<?php echo !empty($pd->C_ADR_EN)?$pd->C_ADR_EN:"" ?>">
          <?php
        else: ?>
          <input style="ime-mode: active;" type="text" size="60" maxlength="80" name="P_C_ADR_EN" value="<?php echo !empty($pd->C_ADR_EN)?$pd->C_ADR_EN:"" ?>">
          <?php
        endif;
        endif;
        if($explanation !== ""): ?>
          <br><font color="red"><?php echo $explanation ?></font>
      <?php
        endif; ?>
      </td>
      <?php if($gInputOpen == 1): ?>
      <td class="<?php echo $RegValue; ?>"></td>
      <?php endif; ?>
  <?php

  endif;
}


function SetP_C_IMG($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$gInputOpen = $GLOBALS['gInputOpen'];
if(empty($pd->C_IMG))
  $pd->C_IMG = "";
if(!$disp): ?>
  <input type="hidden" name="P_C_IMG">
  <input type="hidden" name="m_delImgP" value="">
  <input type="hidden" name="m_curImgP" value="<?php echo $pd->C_IMG ?>">
  <input type="hidden" name="P_O_IMG"   value="<?php echo $pd->O_C_IMG; ?>">
<?php
else: ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->C_IMG,$pd->C_IMG) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人掲載画像')), '個人掲載画像'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
    <?php
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php
        if($pd->C_IMG !== ""): ?>
          &nbsp;&nbsp;<?php echo $pd->C_IMG ?><br>
          <img src="<?php echo NAK_PERSONAL_IMAGE_PATH, $pd->P_ID.".".$pd->C_IMG; ?>">
        <?php
        endif; ?>
        <input type="hidden" name="P_C_IMG">
        <input type="hidden" name="m_delImgP" value="">
        <input type="hidden" name="m_curImgP" value="<?php echo $pd->C_IMG ?>">
        <?php
      else: ?>
        <td class="<?php echo $RegValue; ?>">
          <input type="file" size="60" maxlength="80" name="P_C_IMG" value="<?php echo $pd->C_IMG ?>"><br>
          <input type="checkbox" name="m_delImgP" value="1" <?php echo (!empty($_POST["m_delImgP"]) && $_POST["m_delImgP"] == "1")?"checked":""; ?>>削除
          <input type="hidden" name="m_curImgP" value="<?php echo (empty($_POST["m_curImgP"]) == false)?$_POST["m_curImgP"]:$pd->C_IMG ?>">
            <?php
              if(!empty($pd->C_IMG) && $pd->C_IMG != ""): ?>
                &nbsp;&nbsp;
                <?php echo $pd->C_IMG ?><br>
                <a href="javascript:ShowImage('<?php echo $pd->C_IMG; ?>');">
                  <?php echo $this->dispImgSrc($pd->C_IMG) ?>
                </a>
            <?php
              endif;
      endif;
      if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
        <?php
      endif; ?>
      </td>
  <?php
  if($gInputOpen == "1"):
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_IMG,$pd->O_C_IMG) ?>">
        <?php echo $this->openLevelText($pd->O_C_IMG) ?>
        <input type="hidden" name="P_O_IMG" value="<?php echo $pd->O_C_IMG; ?>">
      </td>
    <?php
    else:?>

      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_IMG,$pd->O_C_IMG) ?>">
        <select name="P_O_IMG" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_C_IMG == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_C_IMG == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_C_IMG == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
  <?php
    endif;
  else: ?>
      <input type="hidden" name="P_O_IMG"   value="<?php echo $pd->O_C_IMG; ?>">
<?php
  endif;
endif;
}


function SetP_C_IMG2($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$gInputOpen = $GLOBALS['gInputOpen'];
if(empty($pd->C_IMG2))
  $pd->C_IMG2 = "";
if(!$disp): ?>
  <input type="hidden" name="P_C_IMG2">
  <input type="hidden" name="m_delImgP2" value="">
  <input type="hidden" name="m_curImgP2" value="<?php echo $pd->C_IMG2 ?>">
  <input type="hidden" name="P_O_IMG2"   value="<?php echo $pd->O_C_IMG2; ?>">
<?php
else: ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->C_IMG2,$pd->C_IMG2) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人掲載画像２')), '個人掲載画像２'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
<?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
  <?php
    if($pd->C_IMG2 !== NULL): ?>
      &nbsp;&nbsp;<?php echo $pd->C_IMG2 ?><br>
      <?php echo $this->dispImgSrc($pd->C_IMG2);
    endif; ?>
    <input type="hidden" name="P_C_IMG2">
    <input type="hidden" name="m_delImgP2" value="">
    <input type="hidden" name="m_curImgP2" value="<?php echo $pd->C_IMG2 ?>">
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?>">
      <input type="file" size="60" maxlength="80" name="P_C_IMG2" value="<?php echo $pd->C_IMG2 ?>"><br>
      <input type="checkbox" name="m_delImgP2" value="1" <?php echo (!empty($_POST["m_delImgP2"]) && $_POST["m_delImgP2"] == "1")?"checked":""; ?>>削除
      <input type="hidden" name="m_curImgP2" value="<?php echo (empty($_POST["m_curImgP2"]) == False)?$_POST["m_curImgP2"]:$pd->C_IMG2 ?>">
  <?php
    if(!empty($pd->C_IMG2) && $pd->C_IMG2 != NULL): ?>
      &nbsp;&nbsp;<?php echo $pd->C_IMG2 ?><br>
      <a href="javascript:ShowImage('<?php echo $pd->C_IMG2; ?>');">
      
      <?php echo $this->dispImgSrc($pd->C_IMG2) ?>
      </a>
  <?php
    endif; ?>
<?php
  endif;
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation ?></font>
<?php
  endif; ?>
    </td>
<?php
  if($gInputOpen == "1"):
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_IMG2,$pd->O_C_IMG2) ?>">
        <?php echo $this->openLevelText($pd->O_C_IMG2) ?>
        <input type="hidden" name="P_O_IMG2" value="<?php echo $pd->O_C_IMG2; ?>">
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_IMG2,$pd->O_C_IMG2) ?>">
        <select name="P_O_IMG2" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_C_IMG2 == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_C_IMG2 == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_C_IMG2 == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
  <?php
    endif;
  else: ?>
      <input type="hidden" name="P_O_IMG2"   value="<?php echo $pd->O_C_IMG2; ?>">
<?php

  endif;
endif;
}

function SetP_C_IMG3($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$gInputOpen = $GLOBALS['gInputOpen'];
if(empty($pd->C_IMG3))
$pd->C_IMG3 = "";
if(!$disp): ?>
  <input type="hidden" name="P_C_IMG3">
  <input type="hidden" name="m_delImgP3" value="">
  <input type="hidden" name="m_curImgP3" value="<?php echo $pd->C_IMG3 ?>">
  <input type="hidden" name="P_O_IMG3"   value="<?php echo $pd->O_C_IMG3; ?>">
<?php
else: ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->C_IMG3,$pd->C_IMG3) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人掲載画像３')), '個人掲載画像３'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
<?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
  <?php
    if($pd->C_IMG3 !== NULL): ?>
      &nbsp;&nbsp;<?php echo $pd->C_IMG3 ?><br>
      <?php echo $this->dispImgSrc($pd->C_IMG3) ?>
  <?php
    endif; ?>
    <input type="hidden" name="P_C_IMG3">
    <input type="hidden" name="m_delImgP3" value="">
    <input type="hidden" name="m_curImgP3" value="<?php echo $pd->C_IMG3 ?>">
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?>">
      <input type="file" size="60" maxlength="80" name="P_C_IMG3" value="<?php echo $pd->C_IMG3 ?>"><br>
      <input type="checkbox" name="m_delImgP3" value="1" <?php echo (!empty($_POST["m_delImgP3"]) && $_POST["m_delImgP3"] == "1")?"checked":""; ?>>削除
      <input type="hidden" name="m_curImgP3" value="<?php echo (empty($_POST["m_curImgP3"]) == False)?$_POST["m_curImgP3"]:$pd->C_IMG3 ?>">
  <?php
    if(!empty($pd->C_IMG3) && $pd->C_IMG3 != NULL): ?>
      &nbsp;&nbsp;<?php echo $pd->C_IMG3 ?><br>
      <a href="javascript:ShowImage('<?php echo $pd->C_IMG3; ?>');">
      <?php echo $this->dispImgSrc($pd->C_IMG3); ?>
      </a>
  <?php
    endif; ?>
<?php
  endif;
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation ?></font>
<?php
  endif; ?>
    </td>
<?php
  if($gInputOpen == "1"):
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_IMG3,$pd->O_C_IMG3) ?>">
        <?php echo $this->openLevelText($pd->O_C_IMG3) ?>
        <input type="hidden" name="P_O_IMG3" value="<?php echo $pd->O_C_IMG3; ?>">
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_IMG3,$pd->O_C_IMG3) ?>">
        <select name="P_O_IMG3" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_C_IMG3 == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_C_IMG3 == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_C_IMG3 == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
  <?php
    endif;
  else: ?>
      <input type="hidden" name="P_O_IMG3"   value="<?php echo $pd->O_C_IMG3; ?>">
<?php

  endif;
endif;
}



function SetP_C_APPEAL($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$gInputOpen = $GLOBALS['gInputOpen'];
if(empty($pd->C_APPEAL))
  $pd->C_APPEAL = "";
if(!$disp): ?>
  <input type="hidden" name="P_C_APPEAL" value="<?php echo htmlspecialchars($pd->C_APPEAL) ?>">
  <input type="hidden" name="P_O_APPEAL" value="<?php echo $pd->O_C_APPEAL; ?>">
<?php
else: ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->C_APPEAL,$pd->C_APPEAL) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人アピール')), '個人アピール'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
<?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <textarea class="ReadOnly" style="ime-mode:active; width:100%; line-height:150%;" cols="58" rows="17" name="P_C_APPEAL" readonly><?php echo htmlspecialchars($pd->C_APPEAL) ?></textarea>
<?php
  else: ?>
    <td class="<?php echo $RegValue; ?>">
      <textarea style="ime-mode:active; width:100%; line-height:150%;" cols="58" rows="17" name="P_C_APPEAL"><?php echo htmlspecialchars(!empty($pd->C_APPEAL)?$pd->C_APPEAL:"") ?></textarea>
<?php
  endif;
  if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation ?></font>
<?php
  endif; ?>
    </td>
<?php
  if($gInputOpen == "1"):
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_APPEAL,$pd->O_C_APPEAL) ?>">
        <?php echo $this->openLevelText($pd->O_C_APPEAL) ?>
        <input type="hidden" name="P_O_APPEAL" value="<?php echo $pd->O_C_APPEAL; ?>">
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_C_APPEAL,$pd->O_C_APPEAL) ?>">
        <select name="P_O_APPEAL" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_C_APPEAL == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_C_APPEAL == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_C_APPEAL == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
  <?php
    endif;
  else: ?>
      <input type="hidden" name="P_O_APPEAL" value="<?php echo $pd->O_C_APPEAL ?>">
<?php

  endif;
endif;
}

function SetP_C_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, $no, $no2, $bikou, $o_bikou, $h_bikou, $ho_bikou, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$checked;
$i;
$radioAray;
$editValue;
$checkboxValue;
$checkboxAray;
$gInputOpen = $GLOBALS['gInputOpen'];
$gd = $GLOBALS['gd'];
if(!$disp): ?>
  <input type="hidden" name="P_C_FREE<?php echo $no; ?>" value="<?php echo $bikou; ?>">
  <input type="hidden" name="P_O_BIKOU<?php echo $no; ?>" value="<?php echo $o_bikou; ?>">
<?php
else:
  if($readOnlyFlg): ?>
      <td class="<?php echo $RegItem; ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人自由項目'.$no2)), '個人自由項目'.$no2); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php
        if(str_replace("[textarea]", "", $radioValue) == "" && $radioValue == "[textarea]"): ?>
          <textarea class="ReadOnly" style="ime-mode:active; width:100%; line-height:150%;" cols="58" rows="17" name="P_C_FREE<?php echo $no; ?>" readonly>
            <?php echo htmlspecialchars($bikou); ?></textarea>
          <?php
        else:
          ?>
          <?php echo $bikou; ?>
          <input type="hidden" name="P_C_FREE<?php echo $no; ?>" value="<?php echo $bikou; ?>">
          <?php
        endif; ?>
  <?php
  else:
  ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($bikou, $h_bikou); ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人自由項目'.$no2)), '個人自由項目'.$no2); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
    <?php
        if(str_replace("[textarea]","", $radioValue) == "" && $radioValue == "[textarea]"): ?>
          <textarea style="ime-mode:active; width:100%; line-height:150%;" cols="58" rows="17" name="P_C_FREE<?php echo $no; ?>"><?php echo htmlspecialchars($bikou) ?></textarea>
          <?php
        elseif(str_replace("[textarea]","", $radioValue) == ""): ?>
            <input style="ime-mode: active;" type="text" size="60" maxlength="80" name="P_C_FREE<?php echo $no; ?>" value="<?php echo $bikou; ?>">
            <?php
        elseif(substr($radioValue, 0, 5) == "[@@@]"):
            $checkboxValue = substr($radioValue, 6);
            $checkboxAray = explode("|", $checkboxValue);
            for($i = 0; $i <count($checkboxAray); $i++) {
              $editValue = str_replace("<br>", "", $checkboxAray[$i]);
              $editValue = str_replace("<BR>","", $editValue);
              $checked = "";
              if($this->Check_Entry_CheckBox($bikou, $editValue)):
                $checked = "checked";
              elseif(($bikou)?$bikou:"" == ""):
                if($default !== ""):
                  if($this->Check_Entry_CheckBox($default, $editValue)):
                    $checked = "checked";
                  endif;
                endif;
              endif;?>
              <input type="checkbox" name="P_C_FREE<?php echo $no ?>" value="<?php echo $editValue; ?>" <?php echo $checked ?> onclick="p_c_biou()">
              <?php echo $checkboxAray[$i];
            }

        else:

          $radioAray = explode("|", $radioValue);
          for ($i = 0; $i <count($radioAray); $i++) {
            $checked = "";
            $editValue = str_replace("<br>", "", $radioAray[$i]);
            $editValue = str_replace("<BR>","", $editValue);
            if($bikou == $editValue):
              $checked = "checked";
            elseif(((empty($bikou))?$bikou:"") == ""):
              if($default !== ""):
                if($default == $editValue):
                  $checked = "checked";
                endif;
              else:
                if($i == 0):
                  $checked = "checked";
                endif;
              endif;
            endif; ?>
            <input type="radio" name="P_C_FREE<?php echo $no; ?>" value="<?php echo $editValue; ?>" <?php echo $checked ?>>
            <?php echo $radioAray[$i]; ?>
            <?php
          }
        endif;
    endif;
  ?>
  <?php if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php endif; ?>
  </td>
<?php
    if($gInputOpen == "1"):
      if($readOnlyFlg):
        ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($o_bikou, $ho_bikou) ?>">
          <?php echo $this->openLevelText($o_bikou) ?>
          <input type="hidden" name="P_O_BIKOU<?php echo $no; ?>" value="<?php echo $o_bikou; ?>">
        </td>
        <?php
      else:
        ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($o_bikou, $ho_bikou); ?>">
          <select name="P_O_BIKOU<?php echo $no; ?>" style="max-width: 100%; width: 100%; padding: 0px;">
            <option value="0" <?php echo ($o_bikou == "0")?"selected":""; ?>>公開しない</option>
            <option value="1" <?php echo ($o_bikou == "1")?"selected":""; ?>>一般公開</option>
            <option value="2" <?php echo ($o_bikou == "2")?"selected":""; ?>>会員にのみ公開</option>
          </select>
        </td>
        <?php
      endif;
    else: ?>
        <input type="hidden" name="P_O_BIKOU<?php echo $no; ?>" value="<?php echo $o_bikou; ?>">
      <?php

    endif;
  endif;
} //End Sub
function SetP_P_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, $no, $no2, $bikou, $o_bikou, $h_bikou, $ho_bikou, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$checked;
$i;
$radioAray;
$editValue;
$checkboxValue;
$checkboxAray;
$gInputOpen = $GLOBALS['gInputOpen'];
$gd = $GLOBALS['gd'];
if(!$disp): ?>
  <input type="hidden" name="P_P_FREE<?php echo $no; ?>" value="<?php echo $bikou; ?>">
  <input type="hidden" name="O_P_FREE<?php echo $no; ?>" value="<?php echo $o_bikou; ?>">
<?php
else:
  if($readOnlyFlg): ?>
      <td class="<?php echo $RegItem; ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('プライベート自由項目'.$no2)), 'プライベート自由項目'.$no2); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php
        if(str_replace("[textarea]", "", $radioValue) == "" && $radioValue == "[textarea]"): ?>
          <textarea class="ReadOnly" style="ime-mode:active; width:100%; line-height:150%;" cols="58" rows="17" name="P_C_FREE<?php echo $no; ?>" readonly>
            <?php echo htmlspecialchars($bikou); ?></textarea>
          <?php
        else:
          ?>
          <?php echo $bikou; ?>
          <input type="hidden" name="P_P_FREE<?php echo $no; ?>" value="<?php echo $bikou; ?>">
          <?php
        endif; ?>
  <?php
  else:
  ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($bikou, $h_bikou); ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('プライベート自由項目'.$no2)), 'プライベート自由項目'.$no2); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
    <?php
        if(str_replace("[textarea]","", $radioValue) == "" && $radioValue == "[textarea]"): ?>
          <textarea style="ime-mode:active; width:100%; line-height:150%;" cols="58" rows="17" name="P_C_FREE<?php echo $no; ?>"><?php echo htmlspecialchars($bikou) ?></textarea>
          <?php
        elseif(str_replace("[textarea]","", $radioValue) == ""): ?>
            <input style="ime-mode: active;" type="text" size="60" maxlength="80" name="P_C_FREE<?php echo $no; ?>" value="<?php echo $bikou; ?>">
            <?php
        elseif(substr($radioValue, 0, 5) == "[@@@]"):
            $checkboxValue = substr($radioValue, 6);
            $checkboxAray = explode("|", $checkboxValue);
            for($i = 0; $i <count($checkboxAray); $i++) {
              $editValue = str_replace("<br>", "", $checkboxAray[$i]);
              $editValue = str_replace("<BR>","", $editValue);
              $checked = "";
              if($this->Check_Entry_CheckBox($bikou, $editValue)):
                $checked = "checked";
              elseif(($bikou)?$bikou:"" == ""):
                if($default !== ""):
                  if($this->Check_Entry_CheckBox($default, $editValue)):
                    $checked = "checked";
                  endif;
                endif;
              endif;?>
              <input type="checkbox" name="P_P_FREE<?php echo $no ?>" value="<?php echo $editValue; ?>" <?php echo $checked ?> onclick="p_c_biou()">
              <?php echo $checkboxAray[$i];
            }

        else:

          $radioAray = explode("|", $radioValue);
          for ($i = 0; $i <count($radioAray); $i++) {
            $checked = "";
            $editValue = str_replace("<br>", "", $radioAray[$i]);
            $editValue = str_replace("<BR>","", $editValue);
            if($bikou == $editValue):
              $checked = "checked";
            elseif(((empty($bikou))?$bikou:"") == ""):
              if($default !== ""):
                if($default == $editValue):
                  $checked = "checked";
                endif;
              else:
                if($i == 0):
                  $checked = "checked";
                endif;
              endif;
            endif; ?>
            <input type="radio" name="P_P_FREE<?php echo $no; ?>" value="<?php echo $editValue; ?>" <?php echo $checked ?>>
            <?php echo $radioAray[$i]; ?>
            <?php
          }
        endif;
    endif;
  ?>
  <?php if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php endif; ?>
  </td>
<?php
    if($gInputOpen == "1"):
      if($readOnlyFlg):
        ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($o_bikou, $ho_bikou) ?>">
          <?php echo $this->openLevelText($o_bikou) ?>
          <input type="hidden" name="O_P_FREE<?php echo $no; ?>" value="<?php echo $o_bikou; ?>">
        </td>
        <?php
      else:
        ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($o_bikou, $ho_bikou); ?>">
          <select name="O_P_FREE<?php echo $no; ?>" style="max-width: 100%; width: 100%; padding: 0px;">
            <option value="0" <?php echo ($o_bikou == "0")?"selected":""; ?>>公開しない</option>
            <option value="1" <?php echo ($o_bikou == "1")?"selected":""; ?>>一般公開</option>
            <option value="2" <?php echo ($o_bikou == "2")?"selected":""; ?>>会員にのみ公開</option>
          </select>
        </td>
        <?php
      endif;
    else: ?>
        <input type="hidden" name="O_P_FREE<?php echo $no; ?>" value="<?php echo $o_bikou; ?>">
      <?php

    endif;
  endif;
} //End Sub

function SetP_GROUP_ENABLE_FLG($disp, $must, $readOnlyFlg, $default, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(!$disp): ?>
  <input type="hidden" name="P_GROUP_ENABLE_FLG" value="<?php echo $pd->GROUP_ENABLE_FLG ?>">
  <?php
else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp(($pd->GROUP_ENABLE_FLG)?$pd->GROUP_ENABLE_FLG:0,($pd->GROUP_ENABLE_FLG)?$pd->GROUP_ENABLE_FLG:0); ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('組織変更許可フラグ')), '本人が所属組織の<br>変更可能'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php
          if(($pd->GROUP_ENABLE_FLG)?$pd->GROUP_ENABLE_FLG:"0" == "0"):
            echo "不可<br>";
          else:
            echo "可<br>";
          endif;
    ?>
    <input type="hidden" name="P_GROUP_ENABLE_FLG" value="<?php echo $pd->GROUP_ENABLE_FLG; ?>">
  <?php
  else:
    ?>
    <td class="<?php echo $RegValue; ?>">
      <select name="P_GROUP_ENABLE_FLG">
        <option value="0" <?php echo (($pd->GROUP_ENABLE_FLG)?$pd->GROUP_ENABLE_FLG:$default == "0")?"selected":""; ?>>不可
        <option value="1" <?php echo (($pd->GROUP_ENABLE_FLG)?$pd->GROUP_ENABLE_FLG:$default == "1")?"selected":""; ?>>可
      </select>
  <?php endif; ?>
  <?php if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>
  </td>
  <?php
  $gInputOpen = $GLOBALS['gInputOpen'];
  if($gInputOpen == "1"){
     $this->SetDummyInputOpen();
  }

endif;
} //End Sub
function SetP_MEETING_NM_DISP($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(!$disp): ?>
  <input type="hidden" name="P_MEETING_NM_DISP" value="<?php echo $pd->MEETING_NM_DISP ?>">
  <?php
else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->MEETING_NM_DISP,$pd->MEETING_NM_DISP) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('会議室氏名表示区分')), '会議室氏名表示区分'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
  if($readOnlyFlg): ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php
      if((($pd->MEETING_NM_DISP)?$pd->MEETING_NM_DISP:"0") == "0"):
        echo "ニックネーム<br>";
      else:
        echo "氏名<br>";
      endif; ?>
    <input type="hidden" name="P_MEETING_NM_DISP" value="<?php echo $pd->MEETING_NM_DISP; ?>">
  <?php else: ?>
    <td class="<?php echo $RegValue; ?>">
      <input type="radio" name="P_MEETING_NM_DISP" value="0" <?php echo ((($pd->MEETING_NM_DISP)?$pd->MEETING_NM_DISP:0) == "0")?"checked":""; ?>><label>ニックネーム</label>
      <input type="radio" name="P_MEETING_NM_DISP" value="1" <?php echo ($pd->MEETING_NM_DISP == "1")?"checked":""; ?>><label>氏名</label>
  <?php endif; ?>
  <?php if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
  <?php endif; ?>
  </td>
  <?php
  $gInputOpen = $GLOBALS['gInputOpen'];
  if($gInputOpen == "1"){
     $this->SetDummyInputOpen();
  }

endif;
} //End Sub

function SetP_HANDLE_NM($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(!$disp): ?>
  <input type="hidden" name="P_HANDLE_NM" value="<?php echo $pd->HANDLE_NM ?>">
  <?php
else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->HANDLE_NM, $pd->HANDLE_NM) ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('会議室ニックネーム')), '会議室ニックネーム'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php
    if($readOnlyFlg):
      ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $pd->HANDLE_NM ?>
        <input type="hidden" name="P_HANDLE_NM" value="<?php echo $pd->HANDLE_NM ?>">
  <?php else: ?>
    <td class="<?php echo $RegValue; ?>">
      <input size="50" style="ime-mode: active;" type="text" name="P_HANDLE_NM" value="<?php echo $pd->HANDLE_NM; ?>">
  <?php endif; ?>
  <?php if($explanation !== ""):  ?>
  <br>
  <font color="red"><?php echo $explanation; ?></font>
<?php endif; ?>

</td>

<?php
$gInputOpen = $GLOBALS['gInputOpen'];
if($gInputOpen == "1"){
   $this->SetDummyInputOpen();
}
endif;
} //End Sub
function SetP_MEETING_NM_MK($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $pd = $GLOBALS['pd'];
  $pdh = $GLOBALS['pdh'];
  if(!$disp): ?>
     <input type="hidden" name="P_MEETING_NM_MK" value="<?php !empty($pd->MEETING_NM_MK)?$pd->MEETING_NM_MK:"" ?>">
  <?php
  else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($pd->MEETING_NM_MK)?$pd->MEETING_NM_MK:"", !empty($pd->MEETING_NM_MK)?$pd->MEETING_NM_MK:"") ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('会議室公開ネーム表示マーク')), '会議室公開ネーム表示マーク'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php
        if($readOnlyFlg):?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $pd->MEETING_NM_MK ?>
      <input type="hidden" name="P_MEETING_NM_MK" value="<?php echo !empty($pd->MEETING_NM_MK)?$pd->MEETING_NM_MK:"" ?>">
    <?php   else: ?>
    <td class="<?php echo $RegValue; ?>">
      <input class="w_60px" style="ime-mode: active;" type="text" name="P_MEETING_NM_MK" value="<?php echo !empty($pd->MEETING_NM_MK)?$pd->MEETING_NM_MK:"" ?>">
    <?php   endif; ?>

    <?php   if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

      </td>
    <?php
  
  $gInputOpen = $GLOBALS['gInputOpen'];
  if($gInputOpen == "1"){
      $this->SetDummyInputOpen();
  }
  endif;
} //End Sub
function SetP_G_ID($disp, $must, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$sd = $GLOBALS['sd'];
$md = $GLOBALS['md'];
$pdh = $GLOBALS['pdh'];
$chg = $GLOBALS['chg'];
$postid = $GLOBALS['postid'];
$gInputOpen = $GLOBALS['gInputOpen'];
  if(!$disp):
    if(!is_null($sd)):
      if($chg == "1"):
        ?>
        <input type="hidden" name="P_G_ID" value="<?php echo $pd->P_ID; ?>">
        <input type="hidden" name="P_O_G_ID" value="<?php echo $pd->O_G_ID; ?>">
        <?php
      else:
        ?>
        <input type="hidden" name="P_G_ID" value="<?php echo $this->GetGroupName($postid,!empty($md->TG_ID)?$md->TG_ID:"",!empty($md->LG_ID)?$md->LG_ID:"",!empty($md->LG_TYPE)?$md->LG_TYPE:"") ?>">
        <input type="hidden" name="P_O_G_ID" value="<?php echo $pd->O_G_ID; ?>">
        <?php
      endif;
   endif;

  else:
    if(!is_null($sd)):
        $affName;
        $offPos;
        $affName = !empty($sd->AFFILIATION_NAME)?$sd->AFFILIATION_NAME:"";
        $offPos = !empty($sd->OFFICIAL_POSITION)?$sd->OFFICIAL_POSITION:""; ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('所属組織')), '所属組織'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <?php
          if($chg == "1"): ?>
            <td class="<?php echo $RegValue; ?>">
              <select name="P_G_ID" onChange="OnChange_GetAffInfo(this.value, '<?php echo $pd->P_ID; ?>');">
                <?php echo $this->pdFillGroup($postid,$pd, $affName, $offPos); ?>
              </select>
              <?php
                else:
              ?>
              <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
              <?php
            echo ($this->GetGroupName($postid,!empty($md->TG_ID)?$md->TG_ID:"",!empty($md->LG_ID)?$md->LG_ID:"",!empty($md->LG_TYPE)?$md->LG_TYPE:""))?$this->GetGroupName($postid,!empty($md->TG_ID)?$md->TG_ID:"",!empty($md->LG_ID)?$md->LG_ID:"",!empty($md->LG_TYPE)?$md->LG_TYPE:""):"&nbsp;";
          endif; ?>
        <?php if($explanation !== ""):  ?>
          <br>
            <font color="red"><?php echo $explanation; ?></font>
        <?php endif; ?>
      </td>
  <?php
      else:
  ?>
  <td class="<?php echo $RegItem; ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('所属組織')), '所属組織'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <td class="<?php echo $RegValue; ?>">組織のメンバーに登録されると表示されます
  <?php if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php endif; ?>
  </td>
  <?php
      endif;
      if($gInputOpen == "1"):
        ?>
        <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_G_ID, $pd->O_G_ID) ?>">
          <select name="P_O_G_ID" style="max-width: 100%; width: 100%; padding: 0px;">
            <option value="0" <?php echo ($pd->O_G_ID == "0")?"selected":""; ?>>公開しない</option>
            <option value="1" <?php echo ($pd->O_G_ID == "1")?"selected":""; ?>>一般公開</option>
            <option value="2" <?php echo ($pd->O_G_ID == "2")?"selected":""; ?>>会員にのみ公開</option>
          </select>
        </td>
          <?php
      else:?>
        <input type="hidden" name="P_O_G_ID" value="<?php echo $pd->O_G_ID; ?>">
        <?php
      endif;
    endif;
} //End Sub

function SetS_AFFILIATION_NAME($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$sd = $GLOBALS['sd'];
$sdh = $GLOBALS['sdh'];
$chg = $GLOBALS['chg'];
$gInputOpen = $GLOBALS['gInputOpen'];
$chg = $GLOBALS['chg'];
if(!$disp):
  if(!is_null($sd)):
    if($chg == "1"):
      ?>
      <input type="hidden" name="S_AFFILIATION_NAME" value="<?php $sd->AFFILIATION_NAME ?>">
      <input type="hidden" name="P_O_AFFILIATION"    value="<?php $pd->O_AFFILIATION ?>">
      <?php
    else:
      ?>
      <input type="hidden" name="S_AFFILIATION_NAME" value="">
      <input type="hidden" name="P_O_AFFILIATION"    value="<?php echo $pd->O_AFFILIATION; ?>">
      <?php
    endif;
  endif;
else:
  if(!is_null($sd)):
      $affName = !empty($sd->AFFILIATION_NAME)?$sd->AFFILIATION_NAME:"";
      $offPos = !empty($sd->OFFICIAL_POSITION)?$sd->OFFICIAL_POSITION:""; ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($sd->AFFILIATION_NAME)?$sd->AFFILIATION_NAME:"", !empty($sd->AFFILIATION_NAME)?$sd->AFFILIATION_NAME:"") ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('所属')), '所属'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
    <?php
      if($readOnlyFlg):?>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $affName; ?>
          <input type="hidden" size="50" name="S_AFFILIATION_NAME" value="<?php echo $affName; ?>">
        </td>
        <?php
      else: ?>
        <td class="<?php echo $RegValue; ?>">
          <input type="text" size="50" name="S_AFFILIATION_NAME" value="<?php echo $affName; ?>" onChange="changeContactForm();changeClaimForm();">
            <?php if($explanation !== ""):  ?>
              <br><font color="red">
                <?php echo $explanation; ?></font>
            <?php endif; ?>
        </td>
          <?php
      endif;
  else:?>
        <td class="<?php echo $RegItem; ?>">
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('所属')), '所属'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">組織のメンバーに登録されると表示されます
          <input type="hidden" name="S_AFFILIATION_NAME" value="">

          <?php if($explanation !== ""):  ?>
              <br><font color="red"><?php echo $explanation ?></font>
        <?php endif; ?>
        </td>
    <?php
  endif;
  if($gInputOpen == "1"):
    if($readOnlyFlg):  ?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_AFFILIATION, $pd->O_AFFILIATION) ?>">
        <?php echo $this->openLevelText($pd->O_AFFILIATION) ?>
        <input type="hidden" name="P_O_AFFILIATION" value="<?php echo $pd->O_AFFILIATION; ?>">
      </td>
      <?php
    else:?>
      <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_AFFILIATION,$pd->O_AFFILIATION) ?>">
        <select name="P_O_AFFILIATION" style="max-width: 100%; width: 100%; padding: 0px;">
        <option value="0" <?php echo ($pd->O_AFFILIATION == "0")?"selected":""; ?>>公開しない</option>
        <option value="1" <?php echo ($pd->O_AFFILIATION == "1")?"selected":""; ?>>一般公開</option>
        <option value="2" <?php echo ($pd->O_AFFILIATION == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
      </td>
    <?php
    endif;
  else:?>
      <input type="hidden" name="P_O_AFFILIATION" value="<?php echo $pd->O_AFFILIATION; ?>">
    <?php

  endif;
endif;
} //End Sub
function SetS_OFFICIAL_POSITION($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $page_link = $this->getPageSlug('nakama-select-dictionary');
  $postid = $GLOBALS['postid'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $pd = $GLOBALS['pd'];
  $pdh = $GLOBALS['pdh'];
  $sd = $GLOBALS['sd'];
  $sdh = $GLOBALS['sdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(!$disp):
    if($chg = "1"):
    ?>
    <input type="hidden" name="S_OFFICIAL_POSITION" value="<?php echo !empty($sd->OFFICIAL_POSITION)?$sd->OFFICIAL_POSITION:""; ?>">
    <input type="hidden" name="P_O_OFFICIAL"    value="<?php echo $pd->O_OFFICIAL; ?>">
    <?php
        else:
    ?>
    <input type="hidden" name="S_OFFICIAL_POSITION" value="">
    <input type="hidden" name="P_O_OFFICIAL"    value="<?php echo $pd->O_AFFILIATION; ?>">
    <?php
        endif;
      else:
        if(!is_null($sd)):
          $affName = !empty($sd->AFFILIATION_NAME)?$sd->AFFILIATION_NAME:"";
          $offPos = !empty($sd->OFFICIAL_POSITION)?$sd->OFFICIAL_POSITION:"";
    ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($sd->OFFICIAL_POSITION)?$sd->OFFICIAL_POSITION:"", !empty($sd->OFFICIAL_POSITION)?$sd->OFFICIAL_POSITION:"") ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('役職')), '役職'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php
          if($readOnlyFlg):
    ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $offPos ?>
      <input type="hidden" size="50" name="S_OFFICIAL_POSITION" value="<?php echo $offPos ?>">
    <?php
          else:
    ?>
    <td class="<?php echo $RegValue; ?>">
      <input type="text" size="50" name="S_OFFICIAL_POSITION" value="<?php echo $offPos ?>" onfocus="SetDicItem(3);" onChange="changeContactForm();changeClaimForm();">
      <input type="button" value="辞書" onClick="OnDic('役職名', 'S_OFFICIAL_POSITION', <?php echo $postid; ?>, '<?php echo $page_link; ?>');">
    <?php
          endif;
    ?>

    <?php   if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>
    <?php
      else:
    ?>
    <td class="<?php echo $RegItem; ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('役職')), '役職'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">組織のメンバーに登録されると表示されます
      <input type="hidden" name="S_OFFICIAL_POSITION" value="">

    <?php   if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>
    <?php
        endif;

        if($gInputOpen == "1"):
          if($readOnlyFlg):
    ?>
    <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_OFFICIAL, $pd->O_OFFICIAL); ?>">
      <?php echo $this->openLevelText($pd->O_OFFICIAL); ?>
      <input type="hidden" name="P_O_OFFICIAL" value="<?php echo $pd->O_OFFICIAL; ?>">
    </td>
    <?php
          else:
    ?>
    <td class="<?php echo $RegValue; ?><?php echo $this->comp($pd->O_OFFICIAL, $pd->O_OFFICIAL) ?>">
        <select name="P_O_OFFICIAL" style="max-width: 100%; width: 100%; padding: 0px;">
          <option value="0" <?php echo ($pd->O_OFFICIAL == "0")?"selected":""; ?>>公開しない</option>
          <option value="1" <?php echo ($pd->O_OFFICIAL == "1")?"selected":""; ?>>一般公開</option>
          <option value="2" <?php echo ($pd->O_OFFICIAL == "2")?"selected":""; ?>>会員にのみ公開</option>
        </select>
    </td>
    <?php
          endif;
        else:
    ?>
    <input type="hidden" name="P_O_OFFICIAL" value="<?php echo $this->getdispIni($pd->O_OFFICIAL, $dispIni); ?>">
    <?php

      endif;
    endif;
} //End Sub

function SetG_REPRESENTATIVE_OP($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $page_link = $this->getPageSlug('nakama-select-dictionary');
  $postid = $GLOBALS['postid'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $pd = $GLOBALS['pd'];
  $pdh = $GLOBALS['pdh'];
  $sd = $GLOBALS['sd'];
  $sdh = $GLOBALS['sdh'];
  $chg = $GLOBALS['chg'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(!$disp):
    if($chg == "1"):?>
      <input type="hidden" name="G_REPRESENTATIVE_OP" value="<?php echo !empty($sd->REPRESENTATIVE_OP)?$sd->REPRESENTATIVE_OP:""; ?>">
      <?php
    else: ?>
      <input type="hidden" name="G_REPRESENTATIVE_OP" value="">
      <?php
    endif;
  else:
    if(!is_null($sd)):
      $offPos = !empty($sd->REPRESENTATIVE_OP)?$sd->REPRESENTATIVE_OP:""; ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($sd->REPRESENTATIVE_OP)?$sd->REPRESENTATIVE_OP:"", !empty($sd->REPRESENTATIVE_OP)?$sd->REPRESENTATIVE_OP:"") ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('代表者役職')), '代表者役職'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <?php
          if($readOnlyFlg): ?>
            <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
              <?php echo $offPos ?>
              <input type="hidden" size="50" name="G_REPRESENTATIVE_OP" value="<?php echo $offPos ?>">
      <?php
          else:
            ?>
            <td class="<?php echo $RegValue; ?>">
              <input type="text" size="50" name="G_REPRESENTATIVE_OP" value="<?php echo $offPos ?>" onfocus="SetDicItem(3);" onChange="changeContactForm();changeClaimForm();">
              <input type="button" value="辞書" onClick="OnDic('役職名', 'G_REPRESENTATIVE_OP', <?php echo $postid; ?>, '<?php echo $page_link; ?>');">
            <?php
          endif;
      ?>
      <?php if($explanation !== ""): ?>
          <br><font color="red"><?php echo $explanation; ?></font>
        <?php endif; ?>
      </td>
      <?php
    else:
      ?>
      <td class="<?php echo $RegItem; ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('代表者役職')), '代表者役職'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?>">組織のメンバーに登録されると表示されます
        <input type="hidden" name="G_REPRESENTATIVE_OP" value="">
        <?php if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation; ?></font>
        <?php endif; ?>
      </td>
    <?php
    endif;
    if($gInputOpen == "1"):
      $this->SetDummyInputOpen();
    endif;
 endif;
} //End Sub

function SetP_CARD_OPEN($disp, $must, $readOnlyFlg, $default, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$tg_id = $GLOBALS['tg_id'];
$gInputOpen = $GLOBALS['gInputOpen'];
if(empty($pd->CARD_OPEN))
  $pd->CARD_OPEN = "";
if(!$disp):
  if($chg = "1"): ?>
  <input type="hidden" name="P_CARD_OPEN" value="<?php echo $pd->CARD_OPEN; ?>">
  <?php
      else:
  ?>
  <input type="hidden" name="P_CARD_OPEN" value="0">
  <?php
      endif;

    else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->CARD_OPEN, $pd->CARD_OPEN) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人情報公開設定')), '個人情報公開設定'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $this->openLevelText(($pd->CARD_OPEN)?$pd->CARD_OPEN:2); ?>
    <input type="hidden" name="P_CARD_OPEN" value="<?php echo ($pd->CARD_OPEN)?$pd->CARD_OPEN:2; ?>">
  </td>
  <?php
      else:
  ?>
  <td class="<?php echo $RegValue; ?>">
    <select name="P_CARD_OPEN" style="max-width: 100%; width: 100%; padding: 0px;">
      <option value="0" <?php echo (!empty($pd->CARD_OPEN) && $pd->CARD_OPEN == "0")?"selected":""; ?>>公開しない</option>
      <option value="1" <?php echo (!empty($pd->CARD_OPEN) && $pd->CARD_OPEN == "1")?"selected":""; ?>>一般公開</option>
      <option value="2" <?php echo (!empty($pd->CARD_OPEN) && $pd->CARD_OPEN == "2")?"selected":""; ?>>会員にのみ公開</option>
    </select>
  <?php
      endif;
  ?>
  <?php  if($tg_id !== "zsjapan" && $tg_id !== "kekkannikusyu"): ?>
  <br>

  <font color="red">
  <?php     if($explanation !== ""): ?>
  <?php echo $explanation; ?>
  <?php     else: ?>
  この設定は、組織情報の「※一般公開会員リスト表示設定」または「※会員間の企業情報公開の設定」で公開するとした場合に有効となる設定です。<br>
  従って、個別項目の設定で公開するを選択した場合であっても、この設定で公開しないとした場合は企業情報だけが公開されることになります。<br>
  しかし、最終的に優先するのは個別項目ごとの公開指定です。
  <?php     endif; ?>
  </font>

  <?php   endif; ?>
  </td>
  <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

  endif;
} //End Sub

function SetP_LOGIN_LOCK_FLG($disp, $must, $readOnlyFlg, $default, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$gInputOpen = $GLOBALS['gInputOpen'];
if(empty($pd->LOGIN_LOCK_FLG))
  $pd->LOGIN_LOCK_FLG = "";
if(!$disp):
  if($chg = "1"): ?>
    <input type="hidden" name="P_LOGIN_LOCK_FLG" value="<?php echo $pd->LOGIN_LOCK_FLG; ?>">
    <?php
  else: ?>
    <input type="hidden" name="P_LOGIN_LOCK_FLG" value="0">
    <?php
  endif;

else:  ?>
<td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->LOGIN_LOCK_FLG, $pd->LOGIN_LOCK_FLG) ?>" nowrap>
  <?php echo ($must)?MUST_START_TAG:""; ?>
  <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('携帯自動ログイン')), '携帯自動ログインを<br>停止する'); ?>
  <?php echo ($must)?MUST_END_TAG:""; ?>
</td>
<?php
    if($readOnlyFlg):
?>
<td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
<?php
      if(($pd->LOGIN_LOCK_FLG)?$pd->LOGIN_LOCK_FLG:"0" == "0"):
        echo "停止しない<br>";
      else:
        echo "停止する<br>";
      endif;
?>
<input type="hidden" name="P_LOGIN_LOCK_FLG" value="<?php echo $pd->LOGIN_LOCK_FLG ?>">
<?php
    else:
?>
<td class="<?php echo $RegValue; ?>">
  <select name="P_LOGIN_LOCK_FLG" style="max-width: 100%; width: 100%; padding: 0px;">
  <option value="0" <?php echo ((!empty($pd->LOGIN_LOCK_FLG) && $pd->LOGIN_LOCK_FLG != '')?$pd->LOGIN_LOCK_FLG:$default == "0")?"selected":""; ?>>停止しない</option>
  <option value="1" <?php echo ((!empty($pd->LOGIN_LOCK_FLG) && $pd->LOGIN_LOCK_FLG != '')?$pd->LOGIN_LOCK_FLG:$default == "1")?"selected":""; ?>>停止する</option>
  </select>
<?php
    endif;
?>

<?php   if($explanation !== ""):  ?>
<br><font color="red"><?php echo $explanation; ?></font>
<?php   endif; ?>

  </td>
<?php
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }

  endif;
} //End Sub

function SetP_P_URL($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $pd = $GLOBALS['pd'];
  $pdh = $GLOBALS['pdh'];
  $gInputOpen = $GLOBALS['gInputOpen'];
  if(empty($pd->P_URL))
    $pd->P_URL = "";
  if(!$disp):
    ?>
    <input type="hidden" name="P_P_URL" value="<?php echo $pd->P_URL ?>">
    <?php
      else:
    ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->P_URL, $pd->P_URL) ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('プライベート　ＵＲＬ')), 'プライベート　ＵＲＬ'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <?php
        if($readOnlyFlg):
    ?>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $p['p_url']; ?>
      <input type="hidden" name="P_P_URL" value="<?php echo $pd->P_URL ?>">
    <?php
        else:
    ?>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" name="P_P_URL" size="60" maxlength="80" value="<?php echo !empty($pd->P_URL)?$pd->P_URL:""; ?>">
    <?php
        endif;
    ?>

    <?php   if($explanation !== ""):  ?>
    <br><font color="red"><?php echo $explanation ?></font>
    <?php   endif; ?>

      </td>
    <?php
        if($gInputOpen == "1"){
           $this->SetDummyInputOpen();
        }

      endif;
}

function SetP_P_EMAIL($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(empty($pd->P_EMAIL))
  $pd->P_EMAIL = "";
if(!$disp):
  ?>
  <input type="hidden" name="P_P_EMAIL" value="<?php echo $pd->P_EMAIL; ?>">
  <?php
    else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->P_EMAIL,$pd->P_EMAIL) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('プライベート　Ｅ－ＭＡＩＬ')), 'プライベート　Ｅ－ＭＡＩＬ'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $pd->P_EMAIL ?>
    <input type="hidden" name="P_P_EMAIL" value="<?php echo $pd->P_EMAIL ?>">
  <?php
      else:
  ?>
  <td class="<?php echo $RegValue; ?>">
    <input style="ime-mode: disabled;" type="text" name="P_P_EMAIL" size="60" maxlength="80"  value="<?php echo !empty($pd->P_EMAIL)?$pd->P_EMAIL:"" ?>" onChange="changeContactForm();changeClaimForm();">
  <?php
      endif;
  ?>

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation ?></font>
  <?php   endif; ?>

    </td>
  <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

    endif;
} //End Sub

function SetP_P_EMAIL2($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(empty($pd->P_EMAIL))
  $pd->P_EMAIL = "";
if(!$disp):
  ?>
  <input type="hidden" name="P_P_EMAIL2" value="<?php echo $pd->P_EMAIL; ?>">
  <?php
    else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->P_EMAIL,$pd->P_EMAIL) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('プE-Mail再入力')), 'E-Mail再入力'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $pd->P_EMAIL ?>
    <input type="hidden" name="P_P_EMAIL2" value="<?php echo $pd->P_EMAIL ?>">
  <?php
      else:
  ?>
  <td class="<?php echo $RegValue; ?>">
    <input style="ime-mode: disabled;" type="text" name="P_P_EMAIL2" size="60" maxlength="80"  value="<?php echo !empty($pd->P_EMAIL)?$pd->P_EMAIL:"" ?>" onChange="changeContactForm();changeClaimForm();"  autocomplete="nope">
  <?php
      endif;
  ?>

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation ?></font>
  <?php   endif; ?>

    </td>
  <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

    endif;
} //End Sub

function SetP_P_CC_EMAIL($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(empty($pd->P_CC_EMAIL))
  $pd->P_CC_EMAIL = "";
if(!$disp):
?>
<input type="hidden" name="P_P_CC_EMAIL" value="<?php echo $pd->P_CC_EMAIL ?>">
<?php
  else:
?>
<td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->P_CC_EMAIL, $pd->P_CC_EMAIL) ?>" nowrap>
  <?php echo ($must)?MUST_START_TAG:""; ?>
  <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('プライベート　追加送信先Ｅ－ＭＡＩＬ')), 'プライベート　追加送信先Ｅ－ＭＡＩＬ'); ?>
  <?php echo ($must)?MUST_END_TAG:""; ?>
</td>
<?php
    if($readOnlyFlg):
?>
<td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
  <?php echo $pd->P_CC_EMAIL ?>
  <input type="hidden" name="P_P_CC_EMAIL" value="<?php echo $pd->P_CC_EMAIL; ?>">
<?php
    else:
?>
<td class="<?php echo $RegValue; ?>">
  <input style="ime-mode: disabled;" type="text" name="P_P_CC_EMAIL" size="60" maxlength="80"  value="<?php echo !empty($pd->P_CC_EMAIL)?$pd->P_CC_EMAIL:""; ?>" onChange="changeContactForm();changeClaimForm();">
<?php
    endif;
?>

<?php   if($explanation !== ""): ?>
<br><font color="red"><?php echo $explanation; ?></font>
<?php   endif; ?>

</td>
<?php
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }

  endif;
} //End Sub


function SetP_P_TEL($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$p_tel1 = $p_tel2 = $p_tel3 = "";
if(!empty($pd->P_TEL)){
  $p_tel1 = explode("-",$pd->P_TEL)[0];
  $p_tel2 = explode("-",$pd->P_TEL)[1];
  $p_tel3 = explode("-",$pd->P_TEL)[2];
}else {
  $pd->P_TEL = "";
}

if(!$disp):
?>
<input type="hidden" name="P_P_TEL_1" value="<?php echo $p_tel1 ?>">
<input type="hidden" name="P_P_TEL_2" value="<?php echo $p_tel2 ?>">
<input type="hidden" name="P_P_TEL_3" value="<?php echo $p_tel3 ?>">
<input type="hidden" name="P_P_TEL"   value="<?php echo $pd->P_TEL ?>">
<?php
  else:
?>
<td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->P_TEL, $pd->P_TEL) ?>" nowrap>
  <?php echo ($must)?MUST_START_TAG:""; ?>
  <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('プライベート　ＴＥＬ')), 'プライベート　ＴＥＬ'); ?>
  <?php echo ($must)?MUST_END_TAG:""; ?>
</td>
<?php
    if($readOnlyFlg):
?>
<td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
  <?php echo $pd->P_TEL; ?>
  <input type="hidden" name="P_P_TEL_1" value="<?php echo $p_tel1; ?>">
  <input type="hidden" name="P_P_TEL_2" value="<?php echo $p_tel2 ?>">
  <input type="hidden" name="P_P_TEL_3" value="<?php echo $p_tel3 ?>">
  <input type="hidden" name="P_P_TEL"   value="<?php echo $pd->P_TEL ?>">
<?php
    else:
?>
<td class="<?php echo $RegValue; ?>">
  <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_P_TEL_1" value="<?php echo $p_tel1 ?>" onChange="changeContactForm();changeClaimForm();">&nbsp;-
  <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_P_TEL_2" value="<?php echo $p_tel2 ?>" onChange="changeContactForm();changeClaimForm();">&nbsp;-
  <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_P_TEL_3" value="<?php echo $p_tel3 ?>" onChange="changeContactForm();changeClaimForm();">
  <input type="hidden" name="P_P_TEL" value="<?php echo $pd->P_TEL ?>">
<?php
    endif;
?>

<?php   if($explanation !== ""): ?>
<br><font color="red"><?php echo $explanation; ?></font>
<?php   endif; ?>

</td>
<?php
  $gInputOpen = $GLOBALS['gInputOpen'];
  if($gInputOpen == "1"){
     $this->SetDummyInputOpen();
  }

endif;
} //End Sub

function SetP_P_FAX($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$p_fax1 = $p_fax2 = $p_fax3 = "";
if(!empty($pd->P_FAX)){
  $p_fax1 = explode("-",$pd->P_FAX)[0];
  $p_fax2 = explode("-",$pd->P_FAX)[1];
  $p_fax3 = explode("-",$pd->P_FAX)[2];
}else { 
  $pd->P_FAX = "";
 }

if(!$disp): ?>
  <input type="hidden" name="P_P_FAX_1" value="<?php echo $p_fax1; ?>">
  <input type="hidden" name="P_P_FAX_2" value="<?php echo $p_fax2; ?>">
  <input type="hidden" name="P_P_FAX_3" value="<?php echo $p_fax3; ?>">
  <input type="hidden" name="P_P_FAX"   value="<?php echo $pd->P_FAX; ?>">
  <?php
else:?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->P_FAX, $pd->P_FAX); ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('プライベート　ＦＡＸ')), 'プライベート　ＦＡＸ'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg): ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $pd->P_FAX; ?>
        <input type="hidden" name="P_P_FAX_1" value="<?php echo $p_fax1; ?>">
        <input type="hidden" name="P_P_FAX_2" value="<?php echo $p_fax2; ?>">
        <input type="hidden" name="P_P_FAX_3" value="<?php echo $p_fax3; ?>">
        <input type="hidden" name="P_P_FAX"   value="<?php echo $pd->P_FAX; ?>">
  <?php
      else:
        ?>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_P_FAX_1" value="<?php echo $p_fax1; ?>" onChange="changeContactForm();changeClaimForm();">&nbsp;-
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_P_FAX_2" value="<?php echo $p_fax2; ?>" onChange="changeContactForm();changeClaimForm();">&nbsp;-
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_P_FAX_3" value="<?php echo $p_fax3; ?>" onChange="changeContactForm();changeClaimForm();">
          <input type="hidden" name="P_P_FAX" value="<?php echo $pd->P_FAX; ?>">
        <?php
      endif;
  ?>

  <?php   if($explanation !== ""):  ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

  </td>
  <?php
  $gInputOpen = $GLOBALS['gInputOpen'];
  if($gInputOpen == "1"){
     $this->SetDummyInputOpen();
  }
    endif;
} //End Sub

function SetP_P_PTEL($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$p_ptel1 = $p_ptel2 = $p_ptel3 = "";
if(!empty($pd->P_PTEL)){
  $p_ptel1 = explode("-",$pd->P_PTEL)[0];
  $p_ptel2 = explode("-",$pd->P_PTEL)[1];
  $p_ptel3 = explode("-",$pd->P_PTEL)[2];
}else { 
  $pd->P_PTEL = "";
 }

if(!$disp): ?>
  <input type="hidden" name="P_P_PTEL_1" value="<?php echo $p_ptel1; ?>">
  <input type="hidden" name="P_P_PTEL_2" value="<?php echo $p_ptel2; ?>">
  <input type="hidden" name="P_P_PTEL_3" value="<?php echo $p_ptel3; ?>">
  <input type="hidden" name="P_P_PTEL"   value="<?php echo $pd->P_PTEL; ?>">
  <?php
    else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->P_PTEL,$pd->P_PTEL) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('プライベート　ＴＥＬ')), 'プライベート　ＴＥＬ'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>

  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $pd->P_PTEL; ?>
    <input type="hidden" name="P_P_PTEL_1" value="<?php echo $p_ptel1; ?>">
    <input type="hidden" name="P_P_PTEL_2" value="<?php echo $p_ptel2; ?>">
    <input type="hidden" name="P_P_PTEL_3" value="<?php echo $p_ptel3; ?>">
    <input type="hidden" name="P_P_PTEL"   value="<?php echo $pd->P_PTEL; ?>">
  <?php
      else:
  ?>
  <td class="<?php echo $RegValue; ?>">
    <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_P_PTEL_1" value="<?php echo $p_ptel1; ?>">&nbsp;-
    <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_P_PTEL_2" value="<?php echo $p_ptel2; ?>">&nbsp;-
    <input style="ime-mode: disabled;" type="text" class='w_60px' name="P_P_PTEL_3" value="<?php echo $p_ptel3; ?>">
    <input type="hidden" name="P_P_PTEL" value="<?php echo $pd->P_PTEL; ?>">
  <?php
      endif;
  ?>

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

  </td>
  <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

    endif;
} //End Sub

function SetP_P_PMAIL($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(empty($pd->P_PMAIL))
  $pd->P_PMAIL = "";
if(!$disp): ?>
  <input type="hidden" name="P_P_PMAIL" value="<?php echo $pd->P_PMAIL ?>">
  <?php
else: ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->P_PMAIL, $pd->P_PMAIL); ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('プライベート　携帯メール')), 'プライベート　携帯メール'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $pd->P_PMAIL; ?>
  <input type="hidden" name="P_P_PMAIL" value="<?php echo $pd->P_PMAIL; ?>">
  <?php
      else:
  ?>
  <td class="<?php echo $RegValue; ?>">
    <input style="ime-mode: disabled;" type="text" name="P_P_PMAIL" size="60" maxlength="80" value="<?php echo !empty($pd->P_PMAIL)?$pd->P_PMAIL:""; ?>">
  <?php
      endif;
  ?>

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

  </td>
  <?php
  $gInputOpen = $GLOBALS['gInputOpen'];
  if($gInputOpen == "1"){
     $this->SetDummyInputOpen();
  }

endif;
} //End Sub

function SetP_P_PMAIL2($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(empty($pd->P_PMAIL))
  $pd->P_PMAIL = "";
if(!$disp): ?>
  <input type="hidden" name="P_P_PMAIL2" value="<?php echo $pd->P_PMAIL ?>">
  <?php
else: ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->P_PMAIL, $pd->P_PMAIL); ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('携帯メール再入力')), '携帯メール再入力'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $pd->P_PMAIL; ?>
  <input type="hidden" name="P_P_PMAIL2" value="<?php echo $pd->P_PMAIL; ?>">
  <?php
      else:
  ?>
  <td class="<?php echo $RegValue; ?>">
    <input style="ime-mode: disabled;" type="text" name="P_P_PMAIL2" size="60" maxlength="80" value="<?php echo !empty($pd->P_PMAIL)?$pd->P_PMAIL:""; ?>">
  <?php
      endif;
  ?>

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

  </td>
  <?php
  $gInputOpen = $GLOBALS['gInputOpen'];
  if($gInputOpen == "1"){
     $this->SetDummyInputOpen();
  }

endif;
} //End Sub

function SetP_P_POST($disp, $must, $readOnlyFlg, $explanation){
  $page_link = $this->getPageSlug('nakama-member-zipcode');
  $postid = $GLOBALS['postid'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
$p_post_l = $p_post_u = "";
if(!empty($pd->P_POST)){
  $p_post_u = explode("-",$pd->P_POST)[0];
  $p_post_l = explode("-",$pd->P_POST)[1];
}else{ 
  $pd->P_POST = "";
}

if(!$disp):
  ?>
  <input type="hidden" name="P_P_POST_u" value="<?php echo $p_post_u; ?>">
  <input type="hidden" name="P_P_POST_l" value="<?php echo $p_post_l; ?>">
  <input type="hidden" name="P_P_POST"   value="<?php echo $pd->P_POST; ?>">
  <?php
else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->P_POST, $pd->P_POST) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('プライベート　〒')), 'プライベート　〒'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $pd->P_POST; ?>
    <input type="hidden" name="P_P_POST_u" value="<?php echo $p_post_u; ?>">
    <input type="hidden" name="P_P_POST_l" value="<?php echo $p_post_l; ?>">
    <input type="hidden" name="P_P_POST"   value="<?php echo $pd->P_POST; ?>">
  <?php
      else:
  ?>
  <td class="<?php echo $RegValue; ?>">
    <input style="ime-mode: disabled;" type="text" class="w_60px" name="P_P_POST_u" maxlength="3" value="<?php echo $p_post_u; ?>" onChange="changeContactForm();changeClaimForm();">&nbsp;-
    <input style="ime-mode: disabled;" type="text" class="w_60px" name="P_P_POST_l" maxlength="4" value="<?php echo $p_post_l ?>" onChange="changeContactForm();changeClaimForm();">
    <input type="hidden" name="P_P_POST" value="<?php echo $pd->P_POST ?>">
    <input type="button" value="住所検索" onClick="OnZipCode('P_P_POST_u', 'P_P_POST_l', <?php echo $postid; ?>, '<?php echo $page_link; ?>');">

  <?php
      endif;
  ?>
  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

  </td>
  <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

endif;
} //End Sub
function SetP_P_STA($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(empty($pd->P_STA))
  $pd->P_STA = "";
if(!$disp): ?>
  <input type="hidden" name="P_P_STA" value="<?php echo $pd->P_STA; ?>">
  <?php
    else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->P_STA, $pd->P_STA); ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('プライベート　都道府県')), 'プライベート　都道府県'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $pd->P_STA; ?>
    <input type="hidden" name="P_P_STA" value="<?php echo $pd->P_STA; ?>">
  <?php
      else:
  ?>
  <td class="<?php echo $RegValue; ?>">
    <select name="P_P_STA" onChange="changeContactForm();changeClaimForm();">
    <script type="text/javascript">StateSelectOptions2("<?php echo $pd->P_STA; ?>");</script>
    </select>
  <?php
      endif;
  ?>

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

  </td>
  <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

endif;
} //End Sub

function SetP_P_ADR($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(empty($pd->P_ADR))
  $pd->P_ADR = "";
if(!$disp): ?>
  <input type="hidden" name="P_P_ADR" value="<?php echo $pd->P_ADR; ?>">
  <?php
    else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->P_ADR, $pd->P_ADR); ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('プライベート　住所１')), 'プライベート　住所１'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $pd->P_ADR; ?>
    <input type="hidden" name="P_P_ADR" value="<?php echo $pd->P_ADR; ?>">
  <?php
      else:
  ?>
  <td class="<?php echo $RegValue; ?>">
  <?php if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) && $_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
    <input style="ime-mode: active;" type="text" name="P_P_ADR" size="60" maxlength="80" value="<?php echo !empty($pd->P_ADR)?$pd->P_ADR:""; ?>" onChange="Javascript:funcHanToZen(this);">
  <?php     else: ?>
    <input style="ime-mode: active;" type="text" name="P_P_ADR" size="60" maxlength="80" value="<?php echo !empty($pd->P_ADR)?$pd->P_ADR:""; ?>" onChange="changeContactForm();changeClaimForm();">
  <?php     endif; ?>
  <?php
      endif;
  ?>

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

  </td>
  <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

endif;
} //End Sub

function SetP_P_ADR2($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(empty($pd->P_ADR2))
  $pd->P_ADR2 = "";
if(!$disp): ?>
<input type="hidden" name="P_P_ADR2" value="<?php echo $pd->P_ADR2; ?>">
<?php
  else:
?>
<td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->P_ADR2, $pd->P_ADR2) ?>" nowrap>
  <?php echo ($must)?MUST_START_TAG:""; ?>
  <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('プライベート　住所２')), 'プライベート　住所２'); ?>
  <?php echo ($must)?MUST_END_TAG:""; ?>
</td>
<?php
    if($readOnlyFlg):
?>
<td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
  <?php echo $pd->P_ADR2; ?>
  <input type="hidden" name="P_P_ADR2" value="<?php echo $pd->P_ADR2 ?>">
<?php
    else:
?>
<td class="<?php echo $RegValue; ?>">
<?php if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) && $_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
  <input style="ime-mode: active;" type="text" name="P_P_ADR2" size="60" maxlength="80" value="<?php echo !empty($pd->P_ADR2)?$pd->P_ADR2:"" ?>" onChange="Javascript:funcHanToZen(this);">
<?php     else: ?>
  <input style="ime-mode: active;" type="text" name="P_P_ADR2" size="60" maxlength="80" value="<?php echo !empty($pd->P_ADR2)?$pd->P_ADR2:"" ?>" onChange="changeContactForm();changeClaimForm();">
<?php     endif; ?>
<?php
    endif;
?>

  <?php  if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>
  </td>
  <?php
  $gInputOpen = $GLOBALS['gInputOpen'];
  if($gInputOpen == "1"){
    $this->SetDummyInputOpen();
  }

  endif;
} //End Sub

function SetP_P_ADR3($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $pd = $GLOBALS['pd'];
  $pdh = $GLOBALS['pdh'];
  if(empty($pd->P_ADR3))
    $pd->P_ADR3 = "";
  if(!$disp): ?>
  <input type="hidden" name="P_P_ADR3" value="<?php echo $pd->P_ADR3; ?>">
  <?php
    else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->P_ADR3, $pd->P_ADR3) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('住所３')), '住所３'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $pd->P_ADR3; ?>
    <input type="hidden" name="P_P_ADR3" value="<?php echo $pd->P_ADR3 ?>">
  <?php
      else:
  ?>
  <td class="<?php echo $RegValue; ?>">
  <?php if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) && $_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
    <input style="ime-mode: active;" type="text" name="P_P_ADR3" size="60" maxlength="80" value="<?php echo !empty($pd->P_ADR3)?$pd->P_ADR3:"" ?>">
  <?php     else: ?>
    <input style="ime-mode: active;" type="text" name="P_P_ADR3" size="60" maxlength="80" value="<?php echo !empty($pd->P_ADR3)?$pd->P_ADR3:"" ?>">
  <?php     endif; ?>
  <?php
      endif;
  ?>

  <?php  if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>
  </td>
  <?php
  $gInputOpen = $GLOBALS['gInputOpen'];
  if($gInputOpen == "1"){
    $this->SetDummyInputOpen();
  }

  endif;
} //End Sub

function SetP_P_ADR_EN($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $pd = $GLOBALS['pd'];
  $pdh = $GLOBALS['pdh'];
  if(empty($pd->P_ADR_EN))
    $pd->P_ADR_EN = "";
  if(!$disp): ?>
  <input type="hidden" name="P_P_ADR_EN" value="<?php echo $pd->P_ADR_EN; ?>">
  <?php
    else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->P_ADR_EN, $pd->P_ADR_EN) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('プライベート　住所　英語表記')), 'プライベート　住所　英語表記'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $pd->P_ADR_EN; ?>
    <input type="hidden" name="P_P_ADR_EN" value="<?php echo $pd->P_ADR_EN ?>">
  <?php
      else:
  ?>
  <td class="<?php echo $RegValue; ?>">
  <?php if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) && $_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
    <input style="ime-mode: active;" type="text" name="P_P_ADR_EN" size="60" maxlength="80" value="<?php echo !empty($pd->P_ADR_EN)?$pd->P_ADR_EN:"" ?>">
  <?php     else: ?>
    <input style="ime-mode: active;" type="text" name="P_P_ADR_EN" size="60" maxlength="80" value="<?php echo !empty($pd->P_ADR_EN)?$pd->P_ADR_EN:"" ?>">
  <?php     endif; ?>
  <?php
      endif;
  ?>

  <?php  if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>
  </td>
  <?php
  $gInputOpen = $GLOBALS['gInputOpen'];
  if($gInputOpen == "1"){
    $this->SetDummyInputOpen();
  }

  endif;
} //End Sub


function SetP_P_MAP($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $pd = $GLOBALS['pd'];
  $pdh = $GLOBALS['pdh'];
  if(!$disp):
?>
<input type="hidden" name="P_P_MAP" value="<?php echo $pd['p_map']; ?>">
<?php
  else:
?>
<td class="<?php echo $RegItem; ?><?php echo $this->comp($pd['p_map'], $pdh['p_map']) ?>" nowrap>
  <?php echo ($must)?MUST_START_TAG:""; ?>
  <?php echo 'マップコード'; ?>
  <?php echo ($must)?MUST_END_TAG:""; ?>
</td>
<?php
    if($readOnlyFlg):
?>
<td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
  <?php echo $pd['p_map']; ?>
  <input type="hidden" name="P_P_MAP" value="<?php echo $pd['p_map']; ?>">
<?php
    else:
?>
<td class="<?php echo $RegValue; ?>">
  <input style="ime-mode: disabled;" type="text" name="P_P_MAP" size="60" maxlength="80" value="<?php echo $pd['p_map']; ?>">
<?php
    endif;
?>

<?php   if($explanation !== ""): ?>
<br><font color="red"><?php echo $explanation; ?></font>
<?php   endif; ?>
</td>
<?php
  endif;
} //End Sub

function SetP_P_NAVI($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];

  if(!$disp):
?>
<input type="hidden" name="P_P_NAVI" value="<?php echo $pd['p_navi']; ?>">
<?php
  else:
?>
<td class="<?php echo $RegItem; ?><?php echo $this->comp($pd['p_navi'], $pdh['p_navi']) ?>" nowrap>
  <?php echo ($must)?MUST_START_TAG:""; ?>
  <?php echo 'カーナビ座標'; ?>
  <?php echo ($must)?MUST_END_TAG:""; ?>
</td>
<?php
    if($readOnlyFlg):
?>
<td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
  <?php echo $pd['p_navi']; ?>
  <input type="hidden" name="P_P_NAVI" value="<?php echo $pd['p_navi']; ?>">
<?php
    else:
?>
<td class="<?php echo $RegValue; ?>">
  <input style="ime-mode: disabled;" type="text" name="P_P_NAVI" size="60" maxlength="80" value="<?php echo $pd['p_navi']; ?>">
<?php
    endif;
?>

<?php   if($explanation !== ""): ?>
<br><font color="red"><?php echo $explanation; ?></font>
<?php   endif; ?>

</td>
<?php
  endif;
} //End Sub

function SetP_P_BIRTH($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $pd = $GLOBALS['pd'];
  $pdh = $GLOBALS['pdh'];
  $YearsSelect = $GLOBALS['YearsSelect'];
  $format_date = '';
  $p_birthD = $p_birthM = $p_birthY = "";
  if(!empty($pd->P_BIRTH) && $pd->P_BIRTH != ''){
    $format_date = date('Y/m/d', strtotime($pd->P_BIRTH));
    $p_birthY = explode("/",$format_date)[0];
    $p_birthM = explode("/",$format_date)[1];
    $p_birthD = explode("/",$format_date)[2];
  }
    
    if(!$disp):?>
      <input type="hidden" name="m_birthImperialP">
      <input type="hidden" name="P_P_BIRTH_YEAR"  value="<?php echo $p_birthY; ?>">
      <input type="hidden" name="P_P_BIRTH_MONTH" value="<?php echo $p_birthM; ?>">
      <input type="hidden" name="P_P_BIRTH_DAY"   value="<?php echo $p_birthD; ?>">
      <input type="hidden" name="P_P_BIRTH"       value="<?php echo $format_date; ?>">
      <?php
    else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($format_date,$format_date) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('生年月日')), '生年月日'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
    if($readOnlyFlg):
      ?>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $format_date;?>
        <input type="hidden" name="m_birthImperialP">
        <input type="hidden" name="P_P_BIRTH_YEAR"  value="<?php echo $p_birthY; ?>">
        <input type="hidden" name="P_P_BIRTH_MONTH" value="<?php echo $p_birthM; ?>">
        <input type="hidden" name="P_P_BIRTH_DAY"   value="<?php echo $p_birthD; ?>">
        <input type="hidden" name="P_P_BIRTH"       value="<?php echo $format_date; ?>">
      <?php
    else:
      ?>
      <td class="<?php echo $RegValue; ?>">
        <?php  if($YearsSelect == "0"): ?>
          <input type="hidden" name="m_birthImperialP" value="AD">
        <?php  else: ?>
          <select name="m_birthImperialP">
          <?php  $this->ShowImperialOption(!empty($_POST["m_birthImperialP"])?$_POST["m_birthImperialP"]:"") ?>
          </select>
        <?php  endif; ?>
        <input style="ime-mode: disabled;" type="text" class="w_60px" maxlength="4" name="P_P_BIRTH_YEAR"  value="<?php echo $p_birthY; ?>">年
        <input style="ime-mode: disabled;" type="text" class="w_60px" maxlength="2" name="P_P_BIRTH_MONTH" value="<?php echo $p_birthM;?>">月
        <input style="ime-mode: disabled;" type="text" class="w_60px" maxlength="2" name="P_P_BIRTH_DAY"   value="<?php echo $p_birthD;?>">日
        <input type="hidden" name="P_P_BIRTH">
      <?php
    endif; ?>
      <?php if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation; ?></font>
      <?php endif; ?>
  </td>
  <?php
  $gInputOpen = $GLOBALS['gInputOpen'];
  if($gInputOpen == "1"){
     $this->SetDummyInputOpen();
  }

endif;
} //End Sub

function SetP_PRIVATE_OPEN($disp, $must, $readOnlyFlg, $default, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(!$disp): ?>
  <input type="hidden" name="P_PRIVATE_OPEN" value="<?php echo $pd->PRIVATE_OPEN; ?>">
  <?php
    else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->PRIVATE_OPEN,$pd->PRIVATE_OPEN) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('プライベート情報公開設定')), 'プライベート情報公開'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
  <?php
        if((($pd->PRIVATE_OPEN)?$pd->PRIVATE_OPEN:"0") == "0"):
          echo "しない<br>";
        else:
          echo "する<br>";
        endif;
  ?>
  <input type="hidden" name="P_PRIVATE_OPEN" value="<?php echo $pd->PRIVATE_OPEN; ?>">
  <?php
      else:
  ?>
  <td class="<?php echo $RegValue; ?>">
    <select name="P_PRIVATE_OPEN" style="max-width: 100%; width: 100%; padding: 0px;">
    <option value="0" <?php (($pd->PRIVATE_OPEN)?$pd->PRIVATE_OPEN:$default == "0")?"selected":""; ?>>しない</option>
    <option value="1" <?php (($pd->PRIVATE_OPEN)?$pd->PRIVATE_OPEN:$default == "1")?"selected":""; ?>>する</option>
    </select>
  <?php
      endif;
  ?>

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

  </td>
  <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

endif;
} //End Sub

function SetP_BANK_CD($disp, $must, $readOnlyFlg, $explanation){
  $postid = $GLOBALS['postid'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(empty($pd->BANK_CD))
  $pd->BANK_CD = "";
if(!$disp): ?>
  <input type="hidden" name="P_BANK_CD" value="<?php echo $pd->BANK_CD; ?>">
  <?php
    else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->BANK_CD,$pd->BANK_CD) ?>" width="120" id="m_claimClassNeed" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('銀行コード')), '銀行コード'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $pd->BANK_CD; ?>
    <input type="hidden" name="P_BANK_CD" value="<?php echo $pd->BANK_CD; ?>">
  <?php
      else:
  ?>
  <td class="<?php echo $RegValue; ?>">
    <select name="P_BANK_CD" onChange="OnBankCodeChange(this, P_BRANCH_CD, '2', <?php echo $postid; ?>);">
    <?php  $this->GetBankInfo($pd->BANK_CD) ?>
    </select>
  <?php
      endif;
  ?>

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

  </td>
  <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

endif;
} //End Sub

function SetP_BRANCH_CD($disp, $must, $readOnlyFlg, $explanation)
{
  $page_link = $this->getPageSlug('nakama-search-bank');
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $postid = $GLOBALS['postid'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $pd = $GLOBALS['pd'];
  $pdh = $GLOBALS['pdh'];
  if(empty($pd->BRANCH_CD))
    $pd->BRANCH_CD = "";
  if(!$disp):
    ?>
    <input type="hidden" name="P_BRANCH_CD" value="<?php echo $pd->BRANCH_CD; ?>">
    <?php
  else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->BRANCH_CD,$pd->BRANCH_CD) ?>" id="m_claimClassNeed" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('支店コード')), '支店コード'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $pd->BRANCH_CD; ?>
    <input type="hidden" name="P_BRANCH_CD" value="<?php echo $pd->BRANCH_CD; ?>">
  <?php
      else:
  ?>
  <td class="<?php echo $RegValue; ?>">
    <select name="P_BRANCH_CD" onChange="changeContactForm();changeClaimForm();"></select>
    <input type="button" value="検索" name="search_button_p" onClick="OnSearchBankP(<?php echo $postid; ?>, '<?php echo $page_link; ?>');">
  <?php
      endif;
  ?>

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

  </td>
  <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
endif;
}

function SetP_ACCAUNT_TYPE($disp, $must, $readOnlyFlg, $explanation)
{
$entry_setting3 = $GLOBALS['entry_setting3'];
$RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
$RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(empty($pd->ACCOUNT_TYPE))
  $pd->ACCOUNT_TYPE = "";
if(!$disp): ?>
  <input type="hidden" name="P_ACCAUNT_TYPE" value="<?php echo $pd->ACCOUNT_TYPE; ?>">
  <?php
    else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->ACCOUNT_TYPE,$pd->ACCOUNT_TYPE) ?>" id="m_claimClassNeed" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('科目')), '科目'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
  <?php
        if($pd->ACCOUNT_TYPE == NAK_ACCOUNT_TYPE_CHECKING):
          echo $this->AccauntTypeText(NAK_ACCOUNT_TYPE_CHECKING);
        else:
          echo $this->AccauntTypeText(NAK_ACCOUNT_TYPE_SAVINGS);
        endif;
  ?>
    <input type="hidden" name="P_ACCAUNT_TYPE" value="<?php echo $pd->ACCOUNT_TYPE; ?>">
  <?php
      else:
  ?>

  <td class="<?php echo $RegValue; ?>">
    <select name="P_ACCAUNT_TYPE" onChange="changeContactForm();changeClaimForm();">
    <option value="">
    <option value="<?php echo NAK_ACCOUNT_TYPE_SAVINGS;  ?>" <?php echo (!empty($pd->ACCOUNT_TYPE) && $pd->ACCOUNT_TYPE == NAK_ACCOUNT_TYPE_SAVINGS)?"selected":""; ?>><?php echo $this->AccauntTypeText(NAK_ACCOUNT_TYPE_SAVINGS); ?>
    <option value="<?php echo NAK_ACCOUNT_TYPE_CHECKING; ?>" <?php echo (!empty($pd->ACCOUNT_TYPE) && $pd->ACCOUNT_TYPE == NAK_ACCOUNT_TYPE_CHECKING)?"selected":""; ?>><?php echo $this->AccauntTypeText(NAK_ACCOUNT_TYPE_CHECKING); ?>
    </select>
  <?php
      endif;
  ?>

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

  </td>
  <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

  endif;
} //End Sub

function SetP_ACCOUNT_NO($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(empty($pd->ACCOUNT_NO))
  $pd->ACCOUNT_NO = "";
if(!$disp):?>
<input type="hidden" name="P_ACCOUNT_NO" value="<?php echo $pd->ACCOUNT_NO; ?>">
<?php
  else:
?>
<td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->ACCOUNT_NO,$pd->ACCOUNT_NO); ?>" id="m_claimClassNeed" nowrap>
  <?php echo ($must)?MUST_START_TAG:""; ?>
  <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('口座番号')), '口座番号'); ?>
  <?php echo ($must)?MUST_END_TAG:""; ?>
</td>
<?php
    if($readOnlyFlg):
?>
<td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
  <?php echo $pd->ACCOUNT_NO; ?>
  <input type="hidden" name="P_ACCOUNT_NO" value="<?php echo $pd->ACCOUNT_NO; ?>">
<?php
    else:
?>
<td class="<?php echo $RegValue; ?>">
  <input style="ime-mode: disabled;" type="text" name="P_ACCOUNT_NO"  maxlength="7" size="60" value="<?php echo !empty($pd->ACCOUNT_NO)?$pd->ACCOUNT_NO:""; ?>" onChange="changeContactForm();changeClaimForm();">
<?php
    endif;
?>
<?php   if($explanation !== ""): ?>
<br><font color="red"><?php echo $explanation; ?></font>
<?php   endif; ?>

</td>
<?php
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }

  endif;
} //End Sub

function SetP_ACCOUNT_NM($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(empty($pd->ACCOUNT_NM))
  $pd->ACCOUNT_NM = "";
if(!$disp): ?>
  <input type="hidden" name="P_ACCOUNT_NM" value="<?php echo $pd->ACCOUNT_NM; ?>">
<?php
  else:
?>
<td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->ACCOUNT_NM, $pd->ACCOUNT_NM); ?>" id="m_claimClassNeed" nowrap>
  <?php echo ($must)?MUST_START_TAG:""; ?>
  <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('口座名義')), '口座名義'); ?>
  <?php echo ($must)?MUST_END_TAG:""; ?>
</td>
<?php
    if($readOnlyFlg):
?>
<td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
  <?php echo $pd->ACCOUNT_NM; ?>
  <input type="hidden" name="P_ACCOUNT_NM" value="<?php echo $pd->ACCOUNT_NM; ?>">
<?php
    else:
?>
<td class="<?php echo $RegValue; ?>">
  <input style="ime-mode: active;" type="text" name="P_ACCOUNT_NM"  maxlength="30" size="60" value="<?php echo !empty($pd->ACCOUNT_NM)?$pd->ACCOUNT_NM:""; ?>" onChange="Javascript:funcZenToHan(this);"><br>(半角ｶﾅ　半角英字　｢･｣　｢.｣のみ)
<?php
    endif;
?>
<?php   if($explanation !== ""): ?>
<br><font color="red"><?php echo $explanation; ?></font>
<?php   endif; ?>

</td>
<?php
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }

  endif;
} //End Sub

function SetP_CUST_NO($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(empty($pd->CUST_NO))
  $pd->CUST_NO = "";
if(!$disp):
?>
<input type="hidden" name="P_CUST_NO" value="<?php echo $pd->CUST_NO; ?>">
<?php
else:
?>
<td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->CUST_NO, $pd->CUST_NO) ?>" nowrap>
<?php echo ($must)?MUST_START_TAG:""; ?>
<?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('顧客番号')), '顧客番号'); ?>
<?php echo ($must)?MUST_END_TAG:""; ?>
</td>
<?php
  if($readOnlyFlg):
?>
<td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
<?php echo $pd->CUST_NO; ?>
<input type="hidden" name="P_CUST_NO" value="<?php echo $pd->CUST_NO; ?>">
<?php
  else:
?>
<td class="<?php echo $RegValue; ?>">
<input style="ime-mode: inactive;" type="text" name="P_CUST_NO" maxlength="12" size="60" value="<?php echo !empty($pd->CUST_NO)?$pd->CUST_NO:""; ?>" onChange="changeContactForm();changeClaimForm();">
<?php
  endif;
?>

<?php   if($explanation !== ""):?>
<br><font color="red"><?php echo $explanation; ?></font>
<?php   endif; ?>

</td>
<?php
  $gInputOpen = $GLOBALS['gInputOpen'];
  if($gInputOpen == "1"){
     $this->SetDummyInputOpen();
  }

endif;
} //End Sub

function SetP_SAVINGS_CD($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(empty($pd->SAVINGS_CD))
  $pd->SAVINGS_CD = "";
if(!$disp):?>
  <input type="hidden" name="P_SAVP_SAVINGS_CDINGS_CODE" value="<?php echo $pd->SAVINGS_CD; ?>">
  <?php
    else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->SAVINGS_CD, $pd->SAVINGS_CD); ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('貯金記号')), '貯金記号'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>

  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $pd->SAVINGS_CD; ?>
    <input type="hidden" name="P_SAVINGS_CD" value="<?php echo $pd->SAVINGS_CD; ?>">
  <?php
      else:
  ?>
  <td class="<?php echo $RegValue; ?>">
    <input style="ime-mode: inactive;" type="text" name="P_SAVINGS_CD" maxlength="5" size="60" value="<?php echo !empty($pd->SAVINGS_CD)?$pd->SAVINGS_CD:""; ?>" onChange="changeContactForm();changeClaimForm();">
  <?php
      endif;
  ?>

  <?php   if($explanation !== ""):?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

  </td>
  <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

    endif;
} //End Sub

function SetP_SAVINGS_NO($disp, $must, $readOnlyFlg, $explanation)
{
$entry_setting3 = $GLOBALS['entry_setting3'];
$RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
$RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$pd = $GLOBALS['pd'];
$pdh = $GLOBALS['pdh'];
if(empty($pd->SAVINGS_NO))
  $pd->SAVINGS_NO = "";
if(!$disp):
  ?>
  <input type="hidden" name="P_SAVINGS_NO" value="<?php echo $pd->SAVINGS_NO; ?>">
  <?php
    else:
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($pd->SAVINGS_NO, $pd->SAVINGS_NO) ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('貯金番号')), '貯金番号'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <?php
      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $pd->SAVINGS_NO; ?>
    <input type="hidden" name="P_SAVINGS_NO" value="<?php echo $pd->SAVINGS_NO; ?>">
  <?php
      else:
  ?>
  <td class="<?php echo $RegValue; ?>">
    <input style="ime-mode: inactive;" type="text" name="P_SAVINGS_NO"  maxlength="8" size="60" value="<?php echo !empty($pd->SAVINGS_NO)?$pd->SAVINGS_NO:""; ?>" onChange="changeContactForm();changeClaimForm();">
  <?php
      endif;
  ?>

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

  </td>
  <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

endif;
} //End Sub

function SetM_LG_ID($disp, $must, $readOnlyFlg, $explanation){
  $page_link = $this->getPageSlug('nakama-search-lg');
  $postid = $GLOBALS['postid'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$md = $GLOBALS['md'];
$mdh = $GLOBALS['mdh'];
$tg_id = $GLOBALS['tg_id'];
$set_lg_g_id = $GLOBALS['set_lg_g_id'];
$chg = $GLOBALS['chg'];
if(empty($md->LG_ID))
  $md->LG_ID = "";
if(!$disp):
  $_SESSION["PUTOUT_JS_M_LG_G_ID"] = false;
  ?>
  <input type="hidden" name="M_LG_G_ID_SEL" value="<?php echo $md->LG_ID; ?>">
  <input type="hidden" name="M_LG_ID"     value="<?php echo $md->LG_ID; ?>">
  <input type="hidden" name="M_LG_NAME"     value="">
  <?php
else:
  $_SESSION["PUTOUT_JS_M_LG_G_ID"] = true;
    if($set_lg_g_id == "" && !$readOnlyFlg):
      if($chg !== "1"): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('グループＩＤ')), 'グループＩＤ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <select name="M_LG_G_ID_SEL" onChange="OnChangeLowerGroup();">
          <?php $this->FillLowerGroup($tg_id,$md->LG_ID); ?>
          </select>
          <input size="25" style="ime-mode: disabled;" type="text" name="M_LG_ID"  value="<?php echo $md->LG_ID; ?>">
          <input type="button" name="okButton" value="　OK　" onClick="ShowLowerGroupName(document.mainForm.M_LG_ID.value);">
          <input type="button" name="SearchButton" value="検索" onClick="OnSearchLowerGroup(<?php echo $postid; ?>, '<?php echo $page_link; ?>');">
          <input type="hidden" name="M_LG_NAME" value="">
          <?php if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation; ?></font>
  <?php endif; ?>

  </td>
  <?php

        elseif($chg == "1"):
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->LG_ID,$md->LG_ID) ?>" nowrap>
   <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('グループＩＤ')), 'グループＩＤ'); ?>
   <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <td class="<?php echo $RegValue; ?>">
    <select name="M_LG_G_ID_SEL" onChange="OnChangeLowerGroup();">
    <?php  $this->FillLowerGroupSel($tg_id, $md->LG_ID); ?>
    </select>
    <input size="30" style="ime-mode: disabled;" type="text" name="M_LG_ID" onkeydown="OnKeyDownLgGid();" value="<?php echo $md->LG_ID; ?>">
    <input type="button" value="　OK　" onClick="ShowLowerGroupName(document.mainForm.M_LG_ID.value);">
    <input type="button" value="検索" onClick="OnSearchLowerGroup();">
    <input type="hidden" name="M_LG_NAME" value="">

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

      </td>
  <?php
        endif;
      else:
  ?>
  <td class="<?php echo $RegItem; ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('グループＩＤ')), 'グループＩＤ'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $this->GetGroupName($postid,!empty($md->TG_ID)?$md->TG_ID:"",!empty($md->LG_ID)?$md->LG_ID:"",!empty($md->LG_TYPE)?$md->LG_TYPE:""); ?>
    <input type="hidden" name="M_LG_G_ID_SEL" value="<?php echo $md->LG_ID; ?>">
    <input type="hidden" name="M_LG_ID"     value="<?php echo $md->LG_ID; ?>">
    <input type="hidden" name="M_LG_NAME"     value="">

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

  </td>
  <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
  endif;
} //End Sub


function SetM_CONTRACT_TYPE($disp, $must, $readOnlyFlg, $default, $explanation)
{
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  $chg = $GLOBALS['chg'];
 if(!$disp):
    ?>
    <input type="hidden" name="M_CONTRACT_TYPE" value="1">
    <?php
      else:

        if($readOnlyFlg):
    ?>
    <td class="<?php echo $RegItem; ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡手段')), '連絡手段'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php
          if($md->CONTRACT_TYPE == "1"):
            echo 'E-MAIL会員<br>';
          elseif($md->CONTRACT_TYPE == "2"):
            echo "FAX会員<br>";
          elseif($md->CONTRACT_TYPE == "3"):
            echo "郵送会員<br>";
          else:
            echo "不明<br>";
          endif;
    ?>
      <input type="hidden" name="M_CONTRACT_TYPE" value="1">
    </td>
    <?php
        else:
          // if($chg !== "1"):
		  if($chg != "1"):
    ?>
    <td class="<?php echo $RegItem; ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡手段')), '連絡手段'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
    <?php

          if(($default)?$default:"" == "1"):
            echo "現在の連絡手段は「E-MAIL会員」です。<br>";
          elseif(($default)?$default:"" == "2"):
            echo "現在の連絡手段は「FAX会員」です。<br>";
          elseif(($default)?$default:"" == "3"):
            echo "現在の連絡手段は「郵送会員」です。<br>";
          else:
            echo "現在連絡手段は設定されていません。<br>";
          endif;
    ?>
      <input type="hidden" name="M_CONTRACT_TYPE" value="<?php echo ($default)?$default:''; ?>">

    <font color="red">
    <?php  if($explanation !== ""):?>
    <?php echo $explanation ?>
    <?php  else: ?>
    メールアドレス登録をして頂くと自動で連絡手段がE-MAIL会員に変わります。<br>
    （ご登録のご協力をお願いします）<br>
    <?php     endif; ?>
    </font>
    </td>
    <?php
          elseif($chg == "1"):
    ?>
    <td class="<?php echo $RegItem; ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡手段')), '連絡手段'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
    <?php
          $member_type = ($md->CONTRACT_TYPE)?$md->CONTRACT_TYPE:"";
            if($member_type == "1"):
              echo "現在の連絡手段は「E-MAIL会員」です。<br>";
            elseif($member_type == "2"):
              echo "現在の連絡手段は「FAX会員」です。<br>";
            elseif($member_type == "3"):
              echo "現在の連絡手段は「郵送会員」です。<br>";
            else:
              echo "現在の連絡手段は「不明」です。<br>";
            endif;
    ?>
      <input type="hidden" name="M_CONTRACT_TYPE" value="<?php echo ($md->CONTRACT_TYPE)?$md->CONTRACT_TYPE:""; ?>">

    <font color="red">
    <?php  if($explanation !== ""): ?>
    <?php echo $explanation ?>
    <?php  else: ?>
    メールアドレス登録をして頂くと自動で連絡手段がE-MAIL会員に変わります。<br>
    （ご登録のご協力をお願いします）<br>
    <?php endif; ?>
    </font>
    </td>
    <?php
    endif;
  endif;
  $gInputOpen = $GLOBALS['gInputOpen'];
  if($gInputOpen == "1"){
     $this->SetDummyInputOpen();
  }

endif;
} //End Sub
public function getTime(){
$md = $GLOBALS['md'];
$mdh = $GLOBALS['mdh'];
$time = array();
for ($i=0; $i < 24 ; $i++) {
  if($i < 10){
    $i = "0".$i;
  }
  $time[$i] = $i;
}
return $time;
}
public function getHour(){
$hour = array();
$hour['00'] = '00';
$hour['15'] = '15';
$hour['30'] = '30';
$hour['45'] = '45';
return $hour;
}
public function createList($objList, $default){
$list = '';
foreach ($objList as $key => $value) {
  $selected = ($default == $key)?'selected':'';
  $list .=  "<option value='".$key."' ".$selected.">".$value."</option>";
}
return $list;
}
function SetM_FAX_TIMEZONE($disp, $must, $readOnlyFlg, $default, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$dispclass = '';
$dispclass2 = '';
$time_list = $this->getTime();
$hour_list = $this->getHour();
$gColSpan = $GLOBALS['gColSpan'];
$md = $GLOBALS['md'];
if(empty($md->FAX_TIMEZONE))
  $md->FAX_TIMEZONE = "";
if(empty($md->FAX_TIME_FROM))
  $md->FAX_TIME_FROM = "";
if(empty($md->FAX_TIME_TO))
  $md->FAX_TIME_TO = "";
if($gColSpan !== 1):
  if($this->comp($md->FAX_TIMEZONE, $md->FAX_TIMEZONE) == ""):
    $dispclass = "ReleaseSetting";
  else:
    $dispclass = $RegItem.$this->comp($md->FAX_TIMEZONE, $md->FAX_TIMEZONE);
  endif;
    $dispclass2 = "ReleaseSetting";
  else:
    $dispclass = $RegItem.$this->comp(!empty($md->FAX_TIMEZONE)?$md->FAX_TIMEZONE:"", !empty($md->FAX_TIMEZONE)?$md->FAX_TIMEZONE:"");
    $dispclass2 = $RegValue;
  endif;


if(!$disp):
  ?>
  <input type="hidden" name="M_FAX_TIMEZONE" value="<?php echo ($md->FAX_TIMEZONE)?$md->FAX_TIMEZONE:"0"; ?>">
  <input type="hidden" name="FAX_TIME_FROM_H" value="">
  <input type="hidden" name="FAX_TIME_FROM_N" value="">
  <input type="hidden" name="FAX_TIME_TO_H" value="">
  <input type="hidden" name="FAX_TIME_TO_N" value="">
  <input type="hidden" name="FAX_TIME_FROM" value="<?php echo $md->FAX_TIME_FROM; ?>">
  <input type="hidden" name="FAX_TIME_TO" value="<?php echo $md->FAX_TIME_TO; ?>">
  <?php
    else:

      if($readOnlyFlg):
  ?>
  <td colspan=<?php echo $gColSpan ?> class="<?php echo $dispclass ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('ＦＡＸ送信時間帯')), 'ＦＡＸ送信時間帯'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <td class="<?php echo $dispclass2; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
  <?php
        if($md->FAX_TIMEZONE == "1"):
          echo "日中(07:00～17:00)<br>";
  ?>  <input type="hidden" name="FAX_TIMEZONE" value="1" ><?php
        elseif($md->FAX_TIMEZONE == "2"):
          echo "夜間(17:00～24:00)";
  ?>  <input type="hidden" name="FAX_TIMEZONE" value="2" ><?php
        elseif($md->FAX_TIMEZONE == "3"):
          echo "深夜(24:00～07:00<br>";
  ?>  <input type="hidden" name="FAX_TIMEZONE" value="3" ><?php
        elseif($md->FAX_TIMEZONE == "4"):
          echo "指定　";
          echo mid($md->FAX_TIME_FROM,1,2)."時";
          echo mid($md->FAX_TIME_FROM,4,2)."分～";
          echo mid($md->FAX_TIME_TO,1,2)."時";
          echo mid($md->FAX_TIME_TO,4,2)."分<br>";
  ?>  <input type="hidden" name="M_FAX_TIMEZONE" value="4" ><?php
        else:
          echo "指定なし<br>";
  ?>  <input type="hidden" name="M_FAX_TIMEZONE" value="0" ><?php
        endif;
  ?>

    <input type="hidden" name="FAX_TIME_FROM" value="<?php echo $md->FAX_TIME_FROM; ?>">
    <input type="hidden" name="FAX_TIME_TO" value="<?php echo $md->FAX_TIME_TO; ?>">

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

  </td>
  <?php
      else:
  ?>
  <td colspan=<?php echo $gColSpan; ?> class="<?php echo $dispclass; ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('ＦＡＸ送信時間帯')), 'ＦＡＸ送信時間帯'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <td class="<?php echo $dispclass2; ?>">
    <?php
      $checked = (!empty($md->FAX_TIMEZONE) && $md->FAX_TIMEZONE != '')?$md->FAX_TIMEZONE:$default;
      $checkedfirst =  (!empty($md->FAX_TIMEZONE) && $md->FAX_TIMEZONE != '')?$md->FAX_TIMEZONE:($default)?$default:"0";
      // echo $checked;
    ?>
    <input type="radio" name="M_FAX_TIMEZONE" value="0" onClick="dispSwitch('off')" <?php echo ($checkedfirst == "0")?"checked":""; ?>><label>指定なし</label><br>
    <input type="radio" name="M_FAX_TIMEZONE" value="1" onClick="dispSwitch('off')"  <?php echo ($checked == "1")?"checked":""; ?>><label>日中(07:00～17:00)</label><br>
    <input type="radio" name="M_FAX_TIMEZONE" value="2" onClick="dispSwitch('off')"  <?php echo ($checked == "2")?"checked":""; ?>><label>夜間(17:00～24:00)</label><br>
    <input type="radio" name="M_FAX_TIMEZONE" value="3" onClick="dispSwitch('off')"  <?php echo ($checked == "3")?"checked":""; ?>><label>深夜(24:00～07:00)</label><br>
    <input type="radio" name="M_FAX_TIMEZONE" value="4" onClick="dispSwitch('on')"   <?php echo ($checked == "4")?"checked":""; ?>>指定&nbsp;
    <select name="FAX_TIME_FROM_H">
    <option value=""></option>
    <?php echo $this->createList($time_list, substr($md->FAX_TIME_FROM,0,2)); ?>
    </select>時
    <select name="FAX_TIME_FROM_N">
    <option value="">  </option>
    <?php echo $this->createList($hour_list, substr($md->FAX_TIME_FROM,2,2)); ?>
    </select>分～
    <select name="FAX_TIME_TO_H">
    <option value="">  </option>
    <?php echo $this->createList($time_list, substr($md->FAX_TIME_TO,0,2)); ?>
    </select>時
    <select name="FAX_TIME_TO_N">
    <option value="">  </option>
    <?php echo $this->createList($hour_list, substr($md->FAX_TIME_TO,2,2)) ?>
    </select>分
    <input type="hidden" name="FAX_TIME_FROM" value="<?php echo $md->FAX_TIME_FROM; ?>">
    <input type="hidden" name="FAX_TIME_TO" value="<?php echo $md->FAX_TIME_TO; ?>">

  <?php   if($explanation !== ""):?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

  </td>
  <?php
      endif;
        $gInputOpen = $GLOBALS['gInputOpen'];
        if($gInputOpen == "1"){
           $this->SetDummyInputOpen();
        }
    endif;
} //End Sub

function SetM_USER_ID($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  $chg = $GLOBALS['chg'];
  if(empty($md->M_USER_ID))
    $md->M_USER_ID = "";
  if(!$disp):
    ?>
    <input type="hidden" name="M_USER_ID" value="<?php echo $md->M_USER_ID; ?>">
    <?php
      else:
        if($readOnlyFlg):
    ?>
    <td class="<?php echo $RegItem; ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('管理番号')), '管理番号'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $md->M_USER_ID; ?>
      <input type="hidden" name="M_USER_ID" value="<?php echo $md->M_USER_ID; ?>">
    <?php   if($explanation != ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>
    <?php
        else:
          if($chg != "1"):
    ?>
    <td class="<?php echo $RegItem; ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('管理番号')), '管理番号'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
      <input type="text" name="M_USER_ID" value="">
    <?php   if($explanation != ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>

    <?php
          elseif($chg == "1"):
    ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->M_USER_ID, $md->M_USER_ID) ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('管理番号')), '管理番号'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" name="M_USER_ID" value="<?php echo $md->M_USER_ID; ?>">

    <?php   if($explanation != ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>
    <?php
          endif;
        endif;
        $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
          $this->SetDummyInputOpen();
        }
      endif;
} //End Sub

function SetM_RECOMMEND_P_ID($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$md = $GLOBALS['md'];
$mdh = $GLOBALS['mdh'];
$chg = $GLOBALS['chg'];
if(empty($md->RECOMMEND_P_ID))
  $md->RECOMMEND_P_ID = "";
if(!$disp):
  ?>
  <input type="hidden" name="M_RECOMMEND_P_ID" value="<?php echo $md->RECOMMEND_P_ID; ?>">
  <?php
    else:
      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegItem; ?>" width="120" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('紹介者')), '紹介者'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
    <?php echo $md->RECOMMEND_P_ID; ?>
    <input type="hidden" name="M_RECOMMEND_P_ID" value="<?php echo $md->RECOMMEND_P_ID; ?>">
  <?php   if($explanation != ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>

    </td>
    <?php
        else:
          if($chg != "1"):
    ?>
    <td class="<?php echo $RegItem; ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('紹介者')), '紹介者'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
      <?php echo !empty($_SESSION["b_user_id"])?$_SESSION["b_user_id"]:""; ?>　　
      <?php echo !empty($_SESSION["b_user_nm"])?$_SESSION["b_user_nm"]:""; ?>
      <input type="hidden" name="M_RECOMMEND_P_ID" value="<?php echo !empty($_SESSION["b_user_id"])?$_SESSION["b_user_id"]:""; ?>">

    <?php   if($explanation != ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>

    <?php
          elseif($chg == "1"):
    ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->RECOMMEND_P_ID, $md->RECOMMEND_P_ID) ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('紹介者')), '紹介者'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" name="M_RECOMMEND_P_ID" value="<?php echo $md->RECOMMEND_P_ID; ?>">

    <?php   if($explanation != ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>
    <?php
          endif;
        endif;
        $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
          $this->SetDummyInputOpen();
        }
      endif;
} //End Sub

function SetM_RECOMMEND_P_ID2($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  $chg = $GLOBALS['chg'];
  if(empty($md->RECOMMEND_P_ID2))
    $md->RECOMMEND_P_ID2 = "";
  if(!$disp):
    ?>
    <input type="hidden" name="M_RECOMMEND_P_ID2" value="<?php echo $md->RECOMMEND_P_ID2; ?>">
    <?php
      else:
        if($readOnlyFlg):
    ?>
    <td class="<?php echo $RegItem; ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('紹介者２')), '紹介者２'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $md->RECOMMEND_P_ID2; ?>
      <input type="hidden" name="M_RECOMMEND_P_ID2" value="<?php echo $md->RECOMMEND_P_ID2; ?>">
    <?php   if($explanation != ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>
    <?php
        else:
          if($chg != "1"):
    ?>
    <td class="<?php echo $RegItem; ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('紹介者２')), '紹介者２'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
      <input type="text" name="M_RECOMMEND_P_ID2" value="">
    <?php   if($explanation != ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>

    <?php
          elseif($chg == "1"):
    ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->RECOMMEND_P_ID2, $md->RECOMMEND_P_ID2) ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('紹介者２')), '紹介者２'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" name="M_RECOMMEND_P_ID2" value="<?php echo $md->RECOMMEND_P_ID2; ?>">

    <?php   if($explanation != ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>
    <?php
          endif;
        endif;
        $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
          $this->SetDummyInputOpen();
        }
      endif;
} //End Sub

function SetM_RECOMMEND_P_ID3($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  $chg = $GLOBALS['chg'];
  if(empty($md->RECOMMEND_P_ID3))
    $md->RECOMMEND_P_ID3 = "";
  if(!$disp):
    ?>
    <input type="hidden" name="M_RECOMMEND_P_ID3" value="<?php echo $md->RECOMMEND_P_ID3; ?>">
    <?php
      else:
        if($readOnlyFlg):
    ?>
    <td class="<?php echo $RegItem; ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('紹介者３')), '紹介者３'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $md->RECOMMEND_P_ID3; ?>
      <input type="hidden" name="M_RECOMMEND_P_ID3" value="<?php echo $md->RECOMMEND_P_ID3; ?>">
    <?php   if($explanation != ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>
    <?php
        else:
          if($chg != "1"):
    ?>
    <td class="<?php echo $RegItem; ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('紹介者３')), '紹介者３'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
      <input type="text" name="M_RECOMMEND_P_ID3" value="">
    <?php   if($explanation != ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>

    <?php
          elseif($chg == "1"):
    ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->RECOMMEND_P_ID3, $md->RECOMMEND_P_ID3) ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('紹介者３')), '紹介者３'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" name="M_RECOMMEND_P_ID3" value="<?php echo $md->RECOMMEND_P_ID3; ?>">

    <?php   if($explanation != ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>
    <?php
          endif;
        endif;
        $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
          $this->SetDummyInputOpen();
        }
      endif;
} //End Sub

function SetM_RECOMMEND_P_ID4($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  $chg = $GLOBALS['chg'];
  if(empty($md->RECOMMEND_P_ID4))
    $md->RECOMMEND_P_ID4 = "";
  if(!$disp):
    ?>
    <input type="hidden" name="M_RECOMMEND_P_ID4" value="<?php echo $md->RECOMMEND_P_ID4; ?>">
    <?php
      else:
        if($readOnlyFlg):
    ?>
    <td class="<?php echo $RegItem; ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('紹介者４')), '紹介者４'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $md->RECOMMEND_P_ID4; ?>
      <input type="hidden" name="M_RECOMMEND_P_ID4" value="<?php echo $md->RECOMMEND_P_ID4; ?>">
    <?php   if($explanation != ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>
    <?php
        else:
          if($chg != "1"):
    ?>
    <td class="<?php echo $RegItem; ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('紹介者４')), '紹介者４'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
      <input type="text" name="M_RECOMMEND_P_ID4" value="">
    <?php   if($explanation != ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>

    <?php
          elseif($chg == "1"):
    ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->RECOMMEND_P_ID4, $md->RECOMMEND_P_ID4) ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('紹介者４')), '紹介者４'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" name="M_RECOMMEND_P_ID4" value="<?php echo $md->RECOMMEND_P_ID4; ?>">

    <?php   if($explanation != ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>
    <?php
          endif;
        endif;
        $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
          $this->SetDummyInputOpen();
        }
      endif;
} //End Sub

function SetM_RECOMMEND_P_ID5($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  $chg = $GLOBALS['chg'];
  if(empty($md->RECOMMEND_P_ID5))
    $md->RECOMMEND_P_ID5 = "";
  if(!$disp):
    ?>
    <input type="hidden" name="M_RECOMMEND_P_ID5" value="<?php echo $md->RECOMMEND_P_ID5; ?>">
    <?php
      else:
        if($readOnlyFlg):
    ?>
    <td class="<?php echo $RegItem; ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('紹介者５')), '紹介者５'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $md->RECOMMEND_P_ID5; ?>
      <input type="hidden" name="M_RECOMMEND_P_ID5" value="<?php echo $md->RECOMMEND_P_ID5; ?>">
    <?php   if($explanation != ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>
    <?php
        else:
          if($chg != "1"):
    ?>
    <td class="<?php echo $RegItem; ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('紹介者５')), '紹介者５'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
      <input type="text" name="M_RECOMMEND_P_ID5" value="">
    <?php   if($explanation != ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>

    <?php
          elseif($chg == "1"):
    ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->RECOMMEND_P_ID5, $md->RECOMMEND_P_ID5) ?>" width="120" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('紹介者５')), '紹介者５'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
      <input style="ime-mode: disabled;" type="text" name="M_RECOMMEND_P_ID5" value="<?php echo $md->RECOMMEND_P_ID5; ?>">

    <?php   if($explanation != ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>

    </td>
    <?php
          endif;
        endif;
        $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
          $this->SetDummyInputOpen();
        }
      endif;
} //End Sub

function SetM_DISP_LIST($disp, $must, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  $chg = $GLOBALS['chg'];
  if(empty($md->DISP_LIST))
    $md->DISP_LIST = 1;
  if(!$disp):
    ?>
    <input type="hidden" name="M_DISP_LIST" value="<?php echo $md->DISP_LIST; ?>">
    <?php
  else:
      echo '<td class="'.$RegItem.'" width="120" name="td1" nowrap>';
      echo ($must)?MUST_START_TAG:"";
      echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("会員一覧表示設定")), "会員一覧表示設定");
      echo ($must)?MUST_END_TAG:"";
      echo '</td>';
      echo '<td class="'.$RegValue.'">';
        if($chg == "1"){
          $selected = (!empty($md->DISP_LIST))?$md->DISP_LIST:1; ?>
          <select name="M_DISP_LIST" style="max-width: 100%; width: 100%; padding: 0px;">
            <option value="0" <?php echo ($selected == "0")?"selected":""; ?>>表示しない</option>
            <option value="1" <?php echo ($selected == "1")?"selected":""; ?>>一般公開</option>
            <option value="2" <?php echo ($selected == "2")?"selected":""; ?>>会員にのみ公開</option>
          </select>
          <?php
        }
        else{
          ?>
          <select name="M_DISP_LIST" style="max-width: 100%; width: 100%; padding: 0px;">
            <option value="0" >表示しない</option>
            <option value="1" selected>一般公開</option>
            <option value="2" >会員にのみ公開</option>
          </select>
          <?php
        }
        if($explanation != ""){
          echo '<br><font color="red">'.$explanation.'</font>';
        }
      echo '</td>';
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
        $this->SetDummyInputOpen();
      }
  endif;
} //End Sub

function SetM_DISP_DETAIL($disp, $must, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  $chg = $GLOBALS['chg'];
  if(empty($md->DISP_DETAIL))
    $md->DISP_DETAIL = 1;
  if(!$disp):
    ?>
    <input type="hidden" name="M_DISP_DETAIL" value="<?php echo $md->DISP_DETAIL; ?>">
    <?php
  else:
      echo '<td class="'.$RegItem.'" width="120" name="td1" nowrap>';
      echo ($must)?MUST_START_TAG:"";
      echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("会員一覧からの詳細画面表示設定")), "会員一覧からの詳細画面表示設定");
      echo ($must)?MUST_END_TAG:"";
      echo '</td>';
      echo '<td class="'.$RegValue.'">';
        if($chg == "1"){
          $selected = (!empty($md->DISP_DETAIL))?$md->DISP_DETAIL:1; ?>
          <select name="M_DISP_DETAIL" style="max-width: 100%; width: 100%; padding: 0px;">
            <option value="0" <?php echo ($selected == "0")?"selected":""; ?>>表示しない</option>
            <option value="1" <?php echo ($selected == "1")?"selected":""; ?>>一般公開</option>
            <option value="2" <?php echo ($selected == "2")?"selected":""; ?>>会員にのみ公開</option>
          </select>
          <?php
        }
        else{
          ?>
          <select name="M_DISP_DETAIL" style="max-width: 100%; width: 100%; padding: 0px;">
            <option value="0" >表示しない</option>
            <option value="1" selected>一般公開</option>
            <option value="2" >会員にのみ公開</option>
          </select>
          <?php
        }
        if($explanation != ""){
          echo '<br><font color="red">'.$explanation.'</font>';
        }
      echo '</td>';
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
        $this->SetDummyInputOpen();
      }
  endif;
} //End Sub

function SetM_DISP_MARKETING($disp, $must, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  $chg = $GLOBALS['chg'];
  if(empty($md->DISP_MARKETING))
    $md->DISP_MARKETING = 1;
  if(!$disp):
    ?>
    <input type="hidden" name="M_DISP_MARKETING" value="<?php echo $md->DISP_MARKETING; ?>">
    <?php
  else:
      echo '<td class="'.$RegItem.'" width="120" name="td1" nowrap>';
      echo ($must)?MUST_START_TAG:"";
      echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle("マーケティング情報表示設定")), "マーケティング情報表示設定");
      echo ($must)?MUST_END_TAG:"";
      echo '</td>';
      echo '<td class="'.$RegValue.'">';
        if($chg == "1"){
          $selected = (!empty($md->DISP_MARKETING))?$md->DISP_MARKETING:1; ?>
          <select name="M_DISP_MARKETING" style="max-width: 100%; width: 100%; padding: 0px;">
            <option value="0" <?php echo ($selected == "0")?"selected":""; ?>>表示しない</option>
            <option value="1" <?php echo ($selected == "1")?"selected":""; ?>>表示する</option>
          </select>
          <?php
        }
        else{
          ?>
          <select name="M_DISP_MARKETING" style="max-width: 100%; width: 100%; padding: 0px;">
            <option value="0" >表示しない</option>
            <option value="1" selected>表示する</option>
          </select>
          <?php
        }
        if($explanation != ""){
          echo '<br><font color="red">'.$explanation.'</font>';
        }
      echo '</td>';
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
        $this->SetDummyInputOpen();
      }
  endif;
} //End Sub

function SetM_X_COMMENT($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
$md = $GLOBALS['md'];
$mdh = $GLOBALS['mdh'];
$chg = $GLOBALS['chg'];
if(empty($md->X_COMMENT))
  $md->X_COMMENT = "";
if(!$disp):
  ?>
  <input type="hidden" name="M_X_COMMENT" value="<?php echo htmlspecialchars($md->X_COMMENT); ?>">
  <?php
    else:

      if($readOnlyFlg):
  ?>
  <td class="<?php echo $RegItem; ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('コメント')), 'コメント'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>

  <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
     <textarea class="ReadOnly" style="ime-mode:active; width:100%; line-height:150%;" cols="58" rows="17" name="M_X_COMMENT" readonly><?php echo htmlspecialchars($md->X_COMMENT) ?></textarea>

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation ?></font>
  <?php   endif; ?>

  </td>
  <?php
      else:
        if($chg !== "1"):
  ?>
  <td class="<?php echo $RegItem; ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('コメント')), 'コメント'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <td class="<?php echo $RegValue; ?>">
    <textarea style="ime-mode:active; width:100%; line-height:150%;" cols="58" rows="17" name="M_X_COMMENT"><?php echo htmlspecialchars(!empty($md->X_COMMENT)?$md->X_COMMENT:"") ?></textarea>

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation ?></font>
  <?php   endif; ?>
  </td>
  <?php
        elseif($chg == "1"):
  ?>
  <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->X_COMMENT, $md->X_COMMENT); ?>" nowrap>
    <?php echo ($must)?MUST_START_TAG:""; ?>
    <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('コメント')), 'コメント'); ?>
    <?php echo ($must)?MUST_END_TAG:""; ?>
  </td>
  <td class="<?php echo $RegValue; ?>">
    <textarea style="ime-mode:active; width:100%; line-height:150%;" cols="58" rows="17" name="M_X_COMMENT"><?php echo htmlspecialchars($md->X_COMMENT) ?></textarea>

  <?php   if($explanation !== ""): ?>
  <br><font color="red"><?php echo $explanation; ?></font>
  <?php   endif; ?>
  </td>
  <?php
  endif;
  endif;
$gInputOpen = $GLOBALS['gInputOpen'];
if($gInputOpen == "1"){
   $this->SetDummyInputOpen();
}
endif;
} //End Sub

function SetM_TAX_ACCOUNTANT($disp, $must, $readOnlyFlg, $explanation, $no, $no2, $tax_accountant){
  $page_link = $this->getPageSlug('nakama-select-dictionary-list');
  $postid = $GLOBALS['postid'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(!$disp): ?>
    <input type="hidden" name="M_TAX_ACCOUNTANT<?php echo $no; ?>" value="<?php echo $tax_accountant; ?>">
  <?php
  else:
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegItem; ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('税理士'.$no2)), '税理士'.$no2); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $tax_accountant; ?>
        <input type="hidden" name="M_TAX_ACCOUNTANT<?php echo $no; ?>" value="<?php echo $tax_accountant; ?>">
    <?php
    else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($tax_accountant,$tax_accountant) ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('税理士'.$no2)), '税理士'.$no2); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
      <input type="text" name="M_TAX_ACCOUNTANT<?php echo $no; ?>" size="30" value="<?php echo htmlspecialchars(!empty($tax_accountant)?$tax_accountant:"") ?>">
      <input type="button" value="辞書" onclick="OnDic('税理士', 'M_TAX_ACCOUNTANT<?php echo $no; ?>', <?php echo $postid; ?>, '<?php echo $page_link; ?>');">
    <?php
    endif; ?>
    <?php
    if($explanation !== ""): ?>
      <br><font color="red"><?php echo $explanation ?></font>
    <?php
    endif; ?>
    </td>
  <?php
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  endif;
}

function SetM_FREE($disp, $must, $radioValue, $readOnlyFlg, $default, $no, $no2, $bikou, $h_bikou, $explanation)
{
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $checked;
  $i;
  $radioAray;
  $editValue;
  if(!$disp): ?>
    <input type="hidden" name="M_FREE<?php echo $no; ?>" value="<?php echo $bikou; ?>">
    <?php
      else:
        if($readOnlyFlg):
    ?>
    <td class="<?php echo $RegItem; ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('管理自由項目'.$no2)), '管理自由項目'.$no2); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
      <?php echo $bikou; ?>
      <input type="hidden" name="M_FREE<?php echo $no; ?>" value="<?php echo $bikou; ?>">
    <?php
        else:
    ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($bikou, $h_bikou) ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('管理自由項目'.$no2)), '管理自由項目'.$no2); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
    <?php
          if($radioValue == ""):
    ?>
      <input style="ime-mode: active;" type="text" size="60" maxlength="80" name="M_FREE<?php echo $no; ?>" value="<?php echo $bikou ?>">
    <?php
          else:
            if($GLOBALS['flag_checkbox']):
              $radioValue = substr($radioValue,5);
            endif;
            $radioAray = explode("|", $radioValue);
            $checkedValue = explode("|", $bikou);
            for ($i=0; $i <count($radioAray) ; $i++) {
              $checked = "";
              $editValue = str_replace("<br>", "", $radioAray[$i]);
              $editValue = str_replace("<BR>", "", $editValue);
              if(in_array($editValue,$checkedValue)):
                $checked = "checked";
              elseif(($bikou)?$bikou:"" == ""):
                if($default !== ""):
                  if($default == $editValue):
                    $checked = "checked";
                  endif;
                endif;
              endif; ?>
              <input type="<?php echo (!$GLOBALS['flag_checkbox'])?"radio":"checkbox"; ?>" name="M_FREE<?php echo $no ?><?php echo ($GLOBALS['flag_checkbox'])?"[]":""; ?>" value="<?php echo $editValue ?>" <?php echo $checked; ?>>
              <?php echo $radioAray[$i];
            }
          endif;
        endif;
    ?>
    <?php   if($explanation !== ""): ?>
    <br><font color="red"><?php echo $explanation; ?></font>
    <?php   endif; ?>
    </td>
    <?php
        $gInputOpen = $GLOBALS['gInputOpen'];
        if($gInputOpen == "1"){
           $this->SetDummyInputOpen();
        }

  endif;
} //End Sub

function SetM_STATUS($disp, $must, $readOnlyFlg, $default, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  if(empty($md->STATUS))
    $md->STATUS = "";
  if(!$disp): ?>
    <input type="hidden" name="M_STATUS" value="<?php echo $md->STATUS ?>">
  <?php
  else:
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->STATUS,$mdh['status']) ?>" width="120" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('状態')), '状態'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
  <?php
    if($md->CONTRACT_TYPE = "0"):
      echo "非会員<br>";
    elseif($md->CONTRACT_TYPE == "1"):
      echo "会員<br>";
    elseif($md->CONTRACT_TYPE == "2"):
      echo "保留<br>";
    elseif($md->CONTRACT_TYPE == "3"):
      echo "削除状態<br>";
    else:
      echo "一般<br>";
    endif; ?>
  <input type="hidden" name="M_STATUS" value="<?php echo $md->STATUS ?>">
  <?php
    if($explanation !== ""): ?>
      <br><font color="red"><?php echo $explanation ?></font>
  <?php
    endif; ?>
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->STATUS,$md->STATUS) ?>" width="120" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('状態')), '状態'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?>">
      <?php
        if(!empty($md->STATUS) && $md->STATUS == "5"): ?>
        一般
        <input type="hidden" name="M_STATUS" value="<?php echo $md->STATUS ?>">
      <?php
      else: ?>
        <?php $slected = (!empty($md->STATUS))?$md->STATUS:$default; ?>
        <select name="M_STATUS" style="max-width: 100%; width: 100%; padding: 0px;">
          <option value="0" <?php echo ($slected == "0")?"selected":""; ?>>非会員</option>
          <option value="1" <?php echo ($slected == "1")?"selected":""; ?>>会員</option>
          <option value="2" <?php echo ($slected == "2")?"selected":""; ?>>保留</option>
          <option value="3" <?php echo ($slected == "3")?"selected":""; ?>>削除状態</option>
        </select>
      <?php
      endif; ?>

  <?php
    if($explanation !== ""): ?>
      <br><font color="red"><?php echo $explanation; ?></font>
  <?php
    endif; ?>
    </td>
  <?php
    endif;
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  endif;
}

function SetM_ADMISSION_DATE($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $YearsSelect = $GLOBALS['YearsSelect'];
  $format_date = '';
  $admission_dateD = $admission_dateM = $admission_dateY = "";
  if(!empty($md->ADMISSION_DATE) && $md->ADMISSION_DATE != ''){
    $format_date = date('Y/m/d', strtotime($md->ADMISSION_DATE));
    $admission_dateY = explode("/",$format_date)[0];
    $admission_dateM = explode("/",$format_date)[1];
    $admission_dateD = explode("/",$format_date)[2];
  }
  if(!$disp): ?>
    <input type="hidden" name="m_admImperialM" value="">
    <input type="hidden" name="M_ADMISSION_DATE_Y" value="<?php echo $admission_dateY; ?>">
    <input type="hidden" name="M_ADMISSION_DATE_M" value="<?php echo $admission_dateM; ?>">
    <input type="hidden" name="M_ADMISSION_DATE_D" value="<?php echo $admission_dateD; ?>">
    <input type="hidden" name="M_ADMISSION_DATE"   value="<?php echo $format_date; ?>">
    <input type="hidden" name="HDN_ADMISSION_DATE_Y" value="<?php echo $admission_dateY; ?>">
    <input type="hidden" name="HDN_ADMISSION_DATE_M" value="<?php echo $admission_dateM; ?>">
    <input type="hidden" name="HDN_ADMISSION_DATE_D" value="<?php echo $admission_dateD; ?>">
    <input type="hidden" name="HDN_ADMISSION_NAME" value="<?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('入会年月日')), '入会年月日'); ?>">
  <?php
  else:
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegItem; ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('入会年月日')), '入会年月日'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $format_date ?>
        <input type="hidden" name="m_admImperialM"     value="">
        <input type="hidden" name="M_ADMISSION_DATE_Y" value="<?php echo $admission_dateY; ?>">
        <input type="hidden" name="M_ADMISSION_DATE_M" value="<?php echo $admission_dateM; ?>">
        <input type="hidden" name="M_ADMISSION_DATE_D" value="<?php echo $admission_dateD; ?>">
        <input type="hidden" name="M_ADMISSION_DATE"   value="<?php echo $format_date; ?>">
      <?php
        if($explanation !== ""): ?>
          <br><font color="red"><?php echo $explanation; ?></font>
      <?php
        endif; ?>
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp($format_date,$format_date) ?>" width="120" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('入会年月日')), '入会年月日'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?>">
      <?php
        if($YearsSelect == "0"): ?>
          <input type="hidden" name="m_admImperialM" value="AD">
      <?php
        else: ?>
          <select name="m_admImperialM">
          <?php  $this->ShowImperialOption(!empty($_POST["m_admImperialM"])?$_POST["m_admImperialM"]:""); ?>
          </select>
      <?php
        endif; ?>
        <input type="text" style="ime-mode: disabled;" class="w_60px" maxlength="4" name="M_ADMISSION_DATE_Y" value="<?php echo $admission_dateY; ?>">年
        <input type="text" style="ime-mode: disabled;" class="w_60px" maxlength="2" name="M_ADMISSION_DATE_M" value="<?php echo $admission_dateM; ?>">月
        <input type="text" style="ime-mode: disabled;" class="w_60px" maxlength="2" name="M_ADMISSION_DATE_D" value="<?php echo $admission_dateD; ?>">日
        <input type="button" value="今日の日付" onclick="SetToday(document.mainForm.M_ADMISSION_DATE_Y, document.mainForm.M_ADMISSION_DATE_M, document.mainForm.M_ADMISSION_DATE_D); document.mainForm.m_admImperialM.value='AD';">
        <input type="hidden" name="M_ADMISSION_DATE" value="<?php echo $format_date; ?>">
        <input type="hidden" name="HDN_ADMISSION_DATE_Y" value="<?php echo $admission_dateY; ?>">
        <input type="hidden" name="HDN_ADMISSION_DATE_M" value="<?php echo $admission_dateM; ?>">
        <input type="hidden" name="HDN_ADMISSION_DATE_D" value="<?php echo $admission_dateD; ?>">
        <input type="hidden" name="HDN_ADMISSION_NAME" value="<?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('入会年月日')), '入会年月日'); ?>">
      <?php
        if($explanation !== ""): ?>
          <br><font color="red"><?php echo $explanation; ?></font>
      <?php
        endif; ?>
    </td>
  <?php
    endif;
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  endif;
}



function SetM_WITHDRAWAL_DATE($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  $YearsSelect = $GLOBALS['YearsSelect'];
  $format_date = '';
  $withdrawal_dateD = $withdrawal_dateM = $withdrawal_dateY = "";
  if(!empty($md->WITHDRAWAL_DATE) && $md->WITHDRAWAL_DATE != ''){
    $format_date = date('Y/m/d', strtotime($md->WITHDRAWAL_DATE));
    $withdrawal_dateY = explode("/",$format_date)[0];
    $withdrawal_dateM = explode("/",$format_date)[1];
    $withdrawal_dateD = explode("/",$format_date)[2];
  }
  if(!$disp): ?>
    <input type="hidden" name="m_witImperialM"      value="">
    <input type="hidden" name="M_WITHDRAWAL_DATE_Y" value="<?php echo $withdrawal_dateY; ?>">
    <input type="hidden" name="M_WITHDRAWAL_DATE_M" value="<?php echo $withdrawal_dateM; ?>">
    <input type="hidden" name="M_WITHDRAWAL_DATE_D" value="<?php echo $withdrawal_dateD; ?>">
    <input type="hidden" name="M_WITHDRAWAL_DATE"   value="<?php echo $format_date; ?>">
    <input type="hidden" name="HDN_WITHDRAWAL_DATE_Y" value="<?php echo $withdrawal_dateY; ?>">
    <input type="hidden" name="HDN_WITHDRAWAL_DATE_M" value="<?php echo $withdrawal_dateM; ?>">
    <input type="hidden" name="HDN_WITHDRAWAL_DATE_D" value="<?php echo $withdrawal_dateD; ?>">
    <input type="hidden" name="HDN_WITHDRAWAL_NAME" value="<?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('退会年月日')), '退会年月日'); ?>">
    <?php
  else:
        if($readOnlyFlg): ?>
          <td class="<?php echo $RegItem; ?>" nowrap>
            <?php echo ($must)?MUST_START_TAG:""; ?>
            <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('退会年月日')), '退会年月日'); ?>
            <?php echo ($must)?MUST_END_TAG:""; ?>
          </td>
          <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
            <?php echo $format_date; ?>
            <input type="hidden" name="m_witImperialM"      value="">
            <input type="hidden" name="M_WITHDRAWAL_DATE_Y" value="<?php echo $withdrawal_dateY; ?>">
            <input type="hidden" name="M_WITHDRAWAL_DATE_M" value="<?php echo $withdrawal_dateM; ?>">
            <input type="hidden" name="M_WITHDRAWAL_DATE_D" value="<?php echo $withdrawal_dateD; ?>">
            <input type="hidden" name="M_WITHDRAWAL_DATE"   value="<?php echo $format_date; ?>">
            <input type="hidden" name="HDN_WITHDRAWAL_DATE_Y" value="<?php echo $withdrawal_dateY; ?>">
            <input type="hidden" name="HDN_WITHDRAWAL_DATE_M" value="<?php echo $withdrawal_dateM; ?>">
            <input type="hidden" name="HDN_WITHDRAWAL_DATE_D" value="<?php echo $withdrawal_dateD; ?>">
            <input type="hidden" name="HDN_WITHDRAWAL_NAME" value="<?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('退会年月日')), '退会年月日'); ?>">
            <?php
            if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation; ?></font>
            <?php
            endif; ?>
          </td>
      <?php
        else: ?>
          <td class="<?php echo $RegItem; ?><?php echo $this->comp($format_date,$format_date) ?>" width="120" nowrap>
            <?php echo ($must)?MUST_START_TAG:""; ?>
            <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('退会年月日')), '退会年月日'); ?>
            <?php echo ($must)?MUST_END_TAG:""; ?>
          </td>
          <td class="<?php echo $RegValue; ?>">
            <?php
            if($YearsSelect == "0"): ?>
              <input type="hidden" name="m_witImperialM" value="AD">
            <?php
            else: ?>
              <select name="m_witImperialM">
              <?php  $this->ShowImperialOption(!empty($_POST["m_witImperialM"])?$_POST["m_witImperialM"]:""); ?>
              </select>
            <?php
              endif; ?>
            <input type="text" style="ime-mode: disabled;" class="w_60px" maxlength="4" name="M_WITHDRAWAL_DATE_Y" value="<?php echo $withdrawal_dateY; ?>">年
            <input type="text" style="ime-mode: disabled;" class="w_60px" maxlength="2" name="M_WITHDRAWAL_DATE_M" value="<?php echo $withdrawal_dateM; ?>">月
            <input type="text" style="ime-mode: disabled;" class="w_60px" maxlength="2" name="M_WITHDRAWAL_DATE_D" value="<?php echo $withdrawal_dateD; ?>">日
            <input type="button" value="今日の日付" onclick="SetToday(document.mainForm.M_WITHDRAWAL_DATE_Y, document.mainForm.M_WITHDRAWAL_DATE_M, document.mainForm.M_WITHDRAWAL_DATE_D); document.mainForm.m_witImperialM.value='AD';">
            <input type="hidden" name="M_WITHDRAWAL_DATE" value="<?php echo $format_date; ?>">
            <input type="hidden" name="HDN_WITHDRAWAL_DATE_Y" value="<?php echo $withdrawal_dateY; ?>">
            <input type="hidden" name="HDN_WITHDRAWAL_DATE_M" value="<?php echo $withdrawal_dateM;?>">
            <input type="hidden" name="HDN_WITHDRAWAL_DATE_D" value="<?php echo $withdrawal_dateD; ?>">
            <input type="hidden" name="HDN_WITHDRAWAL_NAME" value="<?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('退会年月日')), '退会年月日'); ?>">
            <?php
            if($explanation !== ""): ?>
              <br><font color="red"><?php echo $explanation; ?></font>
            <?php
            endif; ?>
          </td>
      <?php
    endif;
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  endif;
}



function SetM_CHANGE_DATE($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  $YearsSelect = $GLOBALS['YearsSelect'];
  $format_date = '';
  $change_dateD = $change_dateM = $change_dateY = "";
  if(!empty($md->CHANGE_DATE) && $md->CHANGE_DATE != ''){
    $format_date = date('Y/m/d', strtotime($md->CHANGE_DATE));
    $change_dateY = explode("/",$format_date)[0];
    $change_dateM = explode("/",$format_date)[1];
    $change_dateD = explode("/",$format_date)[2];
  }
  if(!$disp):?>
    <input type="hidden" name="m_chaImperialM"  value="">
    <input type="hidden" name="M_CHANGE_DATE_Y" value="<?php echo $change_dateY; ?>">
    <input type="hidden" name="M_CHANGE_DATE_M" value="<?php echo $change_dateM; ?>">
    <input type="hidden" name="M_CHANGE_DATE_D" value="<?php echo $change_dateD; ?>">
    <input type="hidden" name="M_CHANGE_DATE"   value="<?php echo $format_date; ?>">
    <?php
  else:
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegItem; ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('異動年月日')), '異動年月日'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $format_date ?>
        <input type="hidden" name="m_chaImperialM"  value="">
        <input type="hidden" name="M_CHANGE_DATE_Y" value="<?php echo $change_dateY ?>">
        <input type="hidden" name="M_CHANGE_DATE_M" value="<?php echo $change_dateM ?>">
        <input type="hidden" name="M_CHANGE_DATE_D" value="<?php echo $change_dateD ?>">
        <input type="hidden" name="M_CHANGE_DATE"   value="<?php echo $format_date ?>">
        <?php
        if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
        <?php
        endif; ?>
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp($format_date,$format_date) ?>" width="120" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('異動年月日')), '異動年月日'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?>">
        <?php
        if($YearsSelect == "0"): ?>
        <input type="hidden" name="m_chaImperialM" value="AD">
        <?php
        else: ?>
        <select name="m_chaImperialM">
        <?php  $this->ShowImperialOption(!empty($_POST["m_chaImperialM"])?$_POST["m_chaImperialM"]:"") ?>
        </select>
        <?php
        endif; ?>
        <input type="text" style="ime-mode: disabled;" class="w_60px" maxlength="4" name="M_CHANGE_DATE_Y" value="<?php echo $change_dateY ?>">年
        <input type="text" style="ime-mode: disabled;" class="w_60px" maxlength="2" name="M_CHANGE_DATE_M" value="<?php echo $change_dateM ?>">月
        <input type="text" style="ime-mode: disabled;" class="w_60px" maxlength="2" name="M_CHANGE_DATE_D" value="<?php echo $change_dateD ?>">日
        <input type="button" value="今日の日付" onclick="SetToday(document.mainForm.M_CHANGE_DATE_Y, document.mainForm.M_CHANGE_DATE_M, document.mainForm.M_CHANGE_DATE_D); document.mainForm.m_chaImperialM.value='AD';">
        <input type="hidden" name="M_CHANGE_DATE" value="<?php echo $format_date ?>">
        <?php
        if($explanation !== ""):?>
        <br><font color="red"><?php echo $explanation ?></font>
        <?php
        endif; ?>
      </td>
  <?php
    endif;
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  endif;
}

function SetM_CHANGE_REASON($disp, $must, $readOnlyFlg, $explanation){
  $page_link = $this->getPageSlug('nakama-select-dictionary');
  $postid = $GLOBALS['postid'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->CHANGE_REASON))
    $md->CHANGE_REASON = "";
  if(!$disp): ?>
    <input type="hidden" name="M_CHANGE_REASON" value="<?php echo htmlspecialchars($md->CHANGE_REASON) ?>">
  <?php
  else:
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegItem; ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('異動理由')), '異動理由'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo htmlspecialchars($md->CHANGE_REASON); ?>
        <input type="hidden" name="M_CHANGE_REASON" value="<?php echo htmlspecialchars($md->CHANGE_REASON) ?>">
    <?php
    else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CHANGE_REASON,$md->CHANGE_REASON) ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('異動理由')), '異動理由'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
      <input type="text" name="M_CHANGE_REASON" size="30" value="<?php echo htmlspecialchars(!empty($md->CHANGE_REASON)?$md->CHANGE_REASON:"") ?>">
      <input type="button" value="辞書" onclick="OnDic('異動理由', 'M_CHANGE_REASON', <?php echo $postid; ?>, '<?php echo $page_link; ?>');">
    <?php
    endif; ?>
    <?php
    if($explanation !== ""): ?>
      <br><font color="red"><?php echo $explanation ?></font>
    <?php
    endif; ?>
    </td>
  <?php
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  endif;
}

function SetM_MOVEOUT_DATE($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  $YearsSelect = $GLOBALS['YearsSelect'];
  $format_date = '';
  $moveout_dateD = $moveout_dateM = $moveout_dateY = "";
  if(!empty($md->MOVEOUT_DATE) && $md->MOVEOUT_DATE != ''){
    $format_date = date('Y/m/d', strtotime($md->MOVEOUT_DATE));
    $moveout_dateY = explode("/",$format_date)[0];
    $moveout_dateM = explode("/",$format_date)[1];
    $moveout_dateD = explode("/",$format_date)[2];
  }
  if(!$disp):?>
    <input type="hidden" name="m_chaImperialM"  value="">
    <input type="hidden" name="M_MOVEOUT_DATE_Y" value="<?php echo $moveout_dateY; ?>">
    <input type="hidden" name="M_MOVEOUT_DATE_M" value="<?php echo $moveout_dateM; ?>">
    <input type="hidden" name="M_MOVEOUT_DATE_D" value="<?php echo $moveout_dateD; ?>">
    <input type="hidden" name="M_MOVEOUT_DATE"   value="<?php echo $format_date; ?>">
    <?php
  else:
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegItem; ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('転出年月日')), '転出年月日'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $format_date ?>
        <input type="hidden" name="m_chaImperialM"  value="">
        <input type="hidden" name="M_MOVEOUT_DATE_Y" value="<?php echo $moveout_dateY ?>">
        <input type="hidden" name="M_MOVEOUT_DATE_M" value="<?php echo $moveout_dateM ?>">
        <input type="hidden" name="M_MOVEOUT_DATE_D" value="<?php echo $moveout_dateD ?>">
        <input type="hidden" name="M_MOVEOUT_DATE"   value="<?php echo $format_date ?>">
        <?php
        if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
        <?php
        endif; ?>
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp($format_date,$format_date) ?>" width="120" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('転出年月日')), '転出年月日'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?>">
        <?php
        if($YearsSelect == "0"): ?>
        <input type="hidden" name="m_chaImperialM" value="AD">
        <?php
        else: ?>
        <select name="m_chaImperialM">
        <?php  $this->ShowImperialOption(!empty($_POST["m_chaImperialM"])?$_POST["m_chaImperialM"]:"") ?>
        </select>
        <?php
        endif; ?>
        <input type="text" style="ime-mode: disabled;" class="w_60px" maxlength="4" name="M_MOVEOUT_DATE_Y" value="<?php echo $moveout_dateY ?>">年
        <input type="text" style="ime-mode: disabled;" class="w_60px" maxlength="2" name="M_MOVEOUT_DATE_M" value="<?php echo $moveout_dateM ?>">月
        <input type="text" style="ime-mode: disabled;" class="w_60px" maxlength="2" name="M_MOVEOUT_DATE_D" value="<?php echo $moveout_dateD ?>">日
        <input type="button" value="今日の日付" onclick="SetToday(document.mainForm.M_MOVEOUT_DATE_Y, document.mainForm.M_MOVEOUT_DATE_M, document.mainForm.M_MOVEOUT_DATE_D); document.mainForm.m_chaImperialM.value='AD';">
        <input type="hidden" name="M_MOVEOUT_DATE" value="<?php echo $format_date ?>">
        <?php
        if($explanation !== ""):?>
        <br><font color="red"><?php echo $explanation ?></font>
        <?php
        endif; ?>
      </td>
  <?php
    endif;
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  endif;
}

function SetM_MOVEIN_DATE($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  $YearsSelect = $GLOBALS['YearsSelect'];
  $format_date = '';
  $movein_dateD = $movein_dateM = $movein_dateY = "";
  if(!empty($md->MOVEIN_DATE) && $md->MOVEIN_DATE != ''){
    $format_date = date('Y/m/d', strtotime($md->MOVEIN_DATE));
    $movein_dateY = explode("/",$format_date)[0];
    $movein_dateM = explode("/",$format_date)[1];
    $movein_dateD = explode("/",$format_date)[2];
  }
  if(!$disp):?>
    <input type="hidden" name="m_chaImperialM"  value="">
    <input type="hidden" name="M_MOVEIN_DATE_Y" value="<?php echo $movein_dateY; ?>">
    <input type="hidden" name="M_MOVEIN_DATE_M" value="<?php echo $movein_dateM; ?>">
    <input type="hidden" name="M_MOVEIN_DATE_D" value="<?php echo $movein_dateD; ?>">
    <input type="hidden" name="M_MOVEIN_DATE"   value="<?php echo $format_date; ?>">
    <?php
  else:
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegItem; ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('転入年月日')), '転入年月日'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $format_date ?>
        <input type="hidden" name="m_chaImperialM"  value="">
        <input type="hidden" name="M_MOVEIN_DATE_Y" value="<?php echo $movein_dateY ?>">
        <input type="hidden" name="M_MOVEIN_DATE_M" value="<?php echo $movein_dateM ?>">
        <input type="hidden" name="M_MOVEIN_DATE_D" value="<?php echo $movein_dateD ?>">
        <input type="hidden" name="M_MOVEIN_DATE"   value="<?php echo $format_date ?>">
        <?php
        if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
        <?php
        endif; ?>
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp($format_date,$format_date) ?>" width="120" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('転入年月日')), '転入年月日'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?>">
        <?php
        if($YearsSelect == "0"): ?>
        <input type="hidden" name="m_chaImperialM" value="AD">
        <?php
        else: ?>
        <select name="m_chaImperialM">
        <?php  $this->ShowImperialOption(!empty($_POST["m_chaImperialM"])?$_POST["m_chaImperialM"]:"") ?>
        </select>
        <?php
        endif; ?>
        <input type="text" style="ime-mode: disabled;" class="w_60px" maxlength="4" name="M_MOVEIN_DATE_Y" value="<?php echo $movein_dateY ?>">年
        <input type="text" style="ime-mode: disabled;" class="w_60px" maxlength="2" name="M_MOVEIN_DATE_M" value="<?php echo $movein_dateM ?>">月
        <input type="text" style="ime-mode: disabled;" class="w_60px" maxlength="2" name="M_MOVEIN_DATE_D" value="<?php echo $movein_dateD ?>">日
        <input type="button" value="今日の日付" onclick="SetToday(document.mainForm.M_MOVEIN_DATE_Y, document.mainForm.M_MOVEIN_DATE_M, document.mainForm.M_MOVEIN_DATE_D); document.mainForm.m_chaImperialM.value='AD';">
        <input type="hidden" name="M_MOVEIN_DATE" value="<?php echo $format_date ?>">
        <?php
        if($explanation !== ""):?>
        <br><font color="red"><?php echo $explanation ?></font>
        <?php
        endif; ?>
      </td>
  <?php
    endif;
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  endif;
}

function SetM_MOVEOUT_NOTE($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->MOVEOUT_NOTE))
    $md->MOVEOUT_NOTE = "";
    if(!$disp): ?>
      <input type="hidden" name="M_MOVEOUT_NOTE" value="<?php echo $md->MOVEOUT_NOTE ?>">
  <?php
    else:
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('転出コメント')), '転出コメント'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->MOVEOUT_NOTE ?>
          <input type="hidden" name="M_MOVEOUT_NOTE" value="<?php echo $md->MOVEOUT_NOTE ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->MOVEOUT_NOTE,$md->MOVEOUT_NOTE) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('転出コメント')), '転出コメント'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input type="text" name="M_MOVEOUT_NOTE" class="w-100" style="ime-mode: active;" value="<?php echo !empty($md->MOVEOUT_NOTE)?$md->MOVEOUT_NOTE:"" ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

function SetM_MOVEIN_NOTE($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->MOVEIN_NOTE))
    $md->MOVEIN_NOTE = "";
    if(!$disp): ?>
      <input type="hidden" name="M_MOVEIN_NOTE" value="<?php echo $md->MOVEIN_NOTE ?>">
  <?php
    else:
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('転入コメント')), '転入コメント'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->MOVEIN_NOTE ?>
          <input type="hidden" name="M_MOVEIN_NOTE" value="<?php echo $md->MOVEIN_NOTE ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->MOVEIN_NOTE,$md->MOVEIN_NOTE) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('転入コメント')), '転入コメント'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input type="text" name="M_MOVEIN_NOTE" class="w-100" style="ime-mode: active;" value="<?php echo !empty($md->MOVEIN_NOTE)?$md->MOVEIN_NOTE:"" ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

function SetM_ADMISSION_REASON($disp, $must, $readOnlyFlg, $explanation){
  $page_link = $this->getPageSlug('nakama-select-dictionary-list');
  $postid = $GLOBALS['postid'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->ADMISSION_REASON))
    $md->ADMISSION_REASON = "";
  if(!$disp): ?>
    <input type="hidden" name="M_ADMISSION_REASON" value="<?php echo htmlspecialchars($md->ADMISSION_REASON) ?>">
  <?php
  else:
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegItem; ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('入会理由')), '入会理由'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo htmlspecialchars($md->ADMISSION_REASON); ?>
        <input type="hidden" name="M_ADMISSION_REASON" value="<?php echo htmlspecialchars($md->ADMISSION_REASON) ?>">
    <?php
    else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->ADMISSION_REASON,$md->ADMISSION_REASON) ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('入会理由')), '入会理由'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
      <input type="text" name="M_ADMISSION_REASON" size="30" value="<?php echo htmlspecialchars(!empty($md->ADMISSION_REASON)?$md->ADMISSION_REASON:"") ?>">
      <input type="button" value="辞書" onclick="OnDic('入会理由', 'M_ADMISSION_REASON', <?php echo $postid; ?>, '<?php echo $page_link; ?>');">
    <?php
    endif; ?>
    <?php
    if($explanation !== ""): ?>
      <br><font color="red"><?php echo $explanation ?></font>
    <?php
    endif; ?>
    </td>
  <?php
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  endif;
}

function SetM_WITHDRAWAL_REASON($disp, $must, $readOnlyFlg, $explanation){
  $page_link = $this->getPageSlug('nakama-select-dictionary-list');
  $postid = $GLOBALS['postid'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->WITHDRAWAL_REASON))
    $md->WITHDRAWAL_REASON = "";
  if(!$disp): ?>
    <input type="hidden" name="M_WITHDRAWAL_REASON" value="<?php echo htmlspecialchars($md->WITHDRAWAL_REASON) ?>">
  <?php
  else:
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegItem; ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('退会理由')), '退会理由'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo htmlspecialchars($md->WITHDRAWAL_REASON); ?>
        <input type="hidden" name="M_WITHDRAWAL_REASON" value="<?php echo htmlspecialchars($md->WITHDRAWAL_REASON) ?>">
    <?php
    else: ?>
    <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->WITHDRAWAL_REASON,$md->WITHDRAWAL_REASON) ?>" nowrap>
      <?php echo ($must)?MUST_START_TAG:""; ?>
      <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('退会理由')), '退会理由'); ?>
      <?php echo ($must)?MUST_END_TAG:""; ?>
    </td>
    <td class="<?php echo $RegValue; ?>">
      <input type="text" name="M_WITHDRAWAL_REASON" size="30" value="<?php echo htmlspecialchars(!empty($md->WITHDRAWAL_REASON)?$md->WITHDRAWAL_REASON:"") ?>">
      <input type="button" value="辞書" onclick="OnDic('退会理由', 'M_WITHDRAWAL_REASON', <?php echo $postid; ?>, '<?php echo $page_link; ?>');">
    <?php
    endif; ?>
    <?php
    if($explanation !== ""): ?>
      <br><font color="red"><?php echo $explanation ?></font>
    <?php
    endif; ?>
    </td>
  <?php
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  endif;
}


function SetM_CLAIM_CLS($disp, $must, $readOnlyFlg, $default, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->CLAIM_CLS))
    $md->CLAIM_CLS = "";
  if(!$disp): ?>
    <input type="hidden" name="M_CLAIM_CLS" value="<?php echo $md->CLAIM_CLS; ?>">
  <?php
  else:
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegItem; ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求区分')), '請求区分'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $md->CLAIM_CLS ?>
        <input type="hidden" name="M_CLAIM_CLS" value="<?php echo $md->CLAIM_CLS ?>">
        <?php
        if($explanation !== ""): ?>
          <br><font color="red"><?php echo $explanation ?></font>
        <?php
        endif; ?>
      </td>
  <?php
    else:?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CLAIM_CLS,$md->CLAIM_CLS) ?>" width="120" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求区分')), '請求区分'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?>">
        <?php $ls = (!empty($md->CLAIM_CLS))?$md->CLAIM_CLS:$default; ?>
        <select name="M_CLAIM_CLS" onChange="OnClaimClassChange(this.value);">
        <option value="<?php echo ($md->CLAIM_CLS == '')?'selected':''; ?>"><?php echo $this->ClaimClassText('') ?></option>
        <option value="<?php echo NAK_CLAIM_CLASS_AUTO?>" <?php echo ($ls == NAK_CLAIM_CLASS_AUTO)?"selected":""; ?>><?php echo $this->ClaimClassText(NAK_CLAIM_CLASS_AUTO) ?></option>
        <option value="<?php echo NAK_CLAIM_CLASS_BANK?>" <?php echo ($ls == NAK_CLAIM_CLASS_BANK)?"selected":""; ?>><?php echo $this->ClaimClassText(NAK_CLAIM_CLASS_BANK) ?></option>
        <option value="<?php echo NAK_CLAIM_CLASS_CASH?>" <?php echo ($ls == NAK_CLAIM_CLASS_CASH)?"selected":""; ?>><?php echo $this->ClaimClassText(NAK_CLAIM_CLASS_CASH) ?></option>
        </select>
        <?php
        if($explanation !== ""): ?>
          <br><font color="red"><?php echo $explanation ?></font>
        <?php
        endif; ?>
      </td>
  <?php
    endif;
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  endif;
}


function SetM_FEE_RANK($disp, $must, $readOnlyFlg, $default, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->FEE_RANK))
    $md->FEE_RANK = "";
  if(!$disp): ?>
    <input type="hidden" name="M_FEE_RANK" value="<?php echo $md->FEE_RANK ?>">
  <?php
  else:
    if($readOnlyFlg): ?>
      <td class="<?php echo $RegItem; ?>" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('会費ランク')), '会費ランク'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
        <?php echo $md->FEE_RANK ?>
        <input type="hidden" name="M_FEE_RANK" value="<?php echo $md->FEE_RANK ?>">
        <?php
        if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
        <?php
        endif; ?>
      </td>
  <?php
    else: ?>
      <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->FEE_RANK,$md->FEE_RANK) ?>" id="m_mainFeeInfo" nowrap>
        <?php echo ($must)?MUST_START_TAG:""; ?>
        <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('会費ランク')), '会費ランク'); ?>
        <?php echo ($must)?MUST_END_TAG:""; ?>
      </td>
      <td class="<?php echo $RegValue; ?>">
        <select name="M_FEE_RANK">
        <option value="">
        <?php
        $buf = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
        for($i = 0; $i < count($buf); $i++){
        ?>
          <option value="<?php echo $buf[$i]; ?>" <?php echo ($buf[$i] == ((!empty($md->FEE_RANK))?$md->FEE_RANK:$default))?"selected":""; ?>><?php echo $buf[$i]; ?></option>
        <?php
        }?>
        </select>
        <?php
        if($explanation !== ""): ?>
          <br><font color="red"><?php echo $explanation ?></font>
        <?php
        endif; ?>
      </td>
  <?php
    endif;
    $gInputOpen = $GLOBALS['gInputOpen'];
    if($gInputOpen == "1"){
       $this->SetDummyInputOpen();
    }
  endif;
}
function SetM_CLAIM_CYCLE($disp, $must, $readOnlyFlg, $default, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->CLAIM_CYCLE))
    $md->CLAIM_CYCLE = "";
  if(!$disp): ?>
      <input type="hidden" name="M_CLAIM_CYCLE" value="<?php echo $md->CLAIM_CYCLE ?>">
  <?php
  else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求サイクル')), '請求サイクル'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CLAIM_CYCLE ?>
          <input type="hidden" name="M_CLAIM_CYCLE" value="<?php echo $md->CLAIM_CYCLE ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CLAIM_CYCLE,$md->CLAIM_CYCLE) ?>" id="m_mainFeeInfo" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求サイクル')), '請求サイクル'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <select name="M_CLAIM_CYCLE">
            <option value=""   <?php echo (empty($md->CLAIM_CYCLE)?$default:$md->CLAIM_CYCLE == "" )?"selected":""; ?>>
            <option value="1"  <?php echo (empty($md->CLAIM_CYCLE)?$default:$md->CLAIM_CYCLE == "1" )?"selected":""; ?>>1
            <option value="3"  <?php echo (empty($md->CLAIM_CYCLE)?$default:$md->CLAIM_CYCLE == "3" )?"selected":""; ?>>3
            <option value="6"  <?php echo (empty($md->CLAIM_CYCLE)?$default:$md->CLAIM_CYCLE == "6" )?"selected":""; ?>>6
            <option value="12" <?php echo (empty($md->CLAIM_CYCLE)?$default:$md->CLAIM_CYCLE == "12" )?"selected":""; ?>>12
          </select>
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetM_SETTLE_MONTH($disp, $must, $readOnlyFlg, $explanation, $dispIni){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    if(!$disp): ?>
      <input type="hidden" name="M_SETTLE_MONTH"  value="<?php echo $md->SETTLE_MONTH ?>">
  <?php
    else:
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('会員決算月')), '会員決算月'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->SETTLE_MONTH ?>
          <input type="hidden" name="M_SETTLE_MONTH"  value="<?php echo $md->SETTLE_MONTH ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->SETTLE_MONTH,$md->SETTLE_MONTH) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('会員決算月')), '会員決算月'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <select name="M_SETTLE_MONTH">
          <option value="">----</option>
          <?php
            $buf = array("", "１月", "２月", "３月", "４月", "５月", "６月", "７月", "８月", "９月", "１０月", "１１月", "１２月");
            for($i = 1; $i<=12; $i++){
            ?>
              <option value="<?php echo $i; ?>" <?php echo ($i == CLngEx($md->SETTLE_MONTH))?"selected":""; ?>><?php echo $buf[$i]; ?></option>
            <?php } ?>
          </select>
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation; ?></font>
          <?php
          endif; ?>
        </td>
  <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetM_FEE_MEMO($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->FEE_MEMO))
    $md->FEE_MEMO = "";
    if(!$disp): ?>
      <input type="hidden" name="M_FEE_MEMO" value="<?php echo $md->FEE_MEMO ?>">
  <?php
    else:
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('会費メモ')), '会費メモ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->FEE_MEMO ?>
          <input type="hidden" name="M_FEE_MEMO" value="<?php echo $md->FEE_MEMO ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->FEE_MEMO,$md->FEE_MEMO) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('会費メモ')), '会費メモ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input type="text" name="M_FEE_MEMO" class="w-100" style="ime-mode: active;" value="<?php echo !empty($md->FEE_MEMO)?$md->FEE_MEMO:"" ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }



  function SetM_ENTRUST_CD($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->ENTRUST_CD))
    $md->ENTRUST_CD = "";
    if(!$disp): ?>
      <input type="hidden" name="M_ENTRUST_CD" value="<?php echo $md->ENTRUST_CD ?>">
  <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('委託者コード')), '委託者コード'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->ENTRUST_CD ?>
          <input type="hidden" name="M_ENTRUST_CD" value="<?php echo $md->ENTRUST_CD ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->ENTRUST_CD,$md->ENTRUST_CD) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('委託者コード')), '委託者コード'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <select name="M_ENTRUST_CD">
          <?php  $this->FillEntrustList($md->ENTRUST_CD); ?>
          </select>
          <br><font color="red">※請求区分「自動振替」で会員個別に設定する場合の委託者コード</font>
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_BANK_CD($disp, $must, $readOnlyFlg, $explanation){
  $postid = $GLOBALS['postid'];
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  if(empty($md->BANK_CD))
    $md->BANK_CD = "";
    if(!$disp): ?>
      <input type="hidden" name="M_BANK_CD" value="<?php echo $md->BANK_CD ?>">
  <?php
    else:
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('銀行コード')), '銀行コード'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BANK_CD ?>
          <input type="hidden" name="M_BANK_CD" value="<?php echo $md->BANK_CD ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BANK_CD,$md->BANK_CD) ?>" width="120" id="m_claimClassNeed" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('銀行コード')), '銀行コード'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <select name="M_BANK_CD" onChange="OnBankCodeChange(this, M_BRANCH_CD, '2', <?php echo $postid; ?>);">
          <?php  $this->GetBankInfo($md->BANK_CD) ?>
          </select>
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }



  function SetM_BRANCH_CD($disp, $must, $readOnlyFlg, $explanation){
    $page_link = $this->getPageSlug('nakama-search-bank');
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $tg_id = $GLOBALS['tg_id'];
  $postid = $GLOBALS['postid'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->BRANCH_CD))
    $md->BRANCH_CD = "";
    if(!$disp): ?>
      <input type="hidden" name="M_BRANCH_CD" value="<?php echo $md->BRANCH_CD ?>">
  <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('支店コード')), '支店コード'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BRANCH_CD ?>
          <input type="hidden" name="M_BRANCH_CD" value="<?php echo $md->BRANCH_CD ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BRANCH_CD,$md->BRANCH_CD) ?>" id="m_claimClassNeed" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('支店コード')), '支店コード'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <select name="M_BRANCH_CD" onChange="changeClaimFormValue();"></select>
          <input type="button" value="検索" name="search_button_m" onClick="OnSearchBankM(<?php echo $postid; ?>, '<?php echo $page_link; ?>');">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_ACCAUNT_TYPE($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->ACCOUNT_TYPE))
    $md->ACCOUNT_TYPE = "";
    if(!$disp): ?>
      <input type="hidden" name="M_ACCAUNT_TYPE" value="<?php echo $md->ACCOUNT_TYPE ?>">
  <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('科目')), '科目'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php
          if($md->ACCOUNT_TYPE == NAK_ACCOUNT_TYPE_CHECKING):
            echo NAK_ACCOUNT_TYPE_CHECKING;
          else:
            echo NAK_ACCOUNT_TYPE_SAVINGS;
          endif; ?>
          <input type="hidden" name="M_ACCAUNT_TYPE" value="<?php echo $md->ACCOUNT_TYPE ?>">
          <?php else: ?>
          <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->ACCOUNT_TYPE,$md->ACCOUNT_TYPE) ?>" id="m_claimClassNeed" nowrap>
            <?php echo ($must)?MUST_START_TAG:""; ?>
            <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('科目')), '科目'); ?>
            <?php echo ($must)?MUST_END_TAG:""; ?>
          </td>
          <td class="<?php echo $RegValue; ?>">
            <select name="M_ACCAUNT_TYPE" onChange="changeClaimFormValue();">
              <option value=""></option>
              <option value="<?php echo NAK_ACCOUNT_TYPE_SAVINGS ?>" <?php echo (!empty($md->ACCOUNT_TYPE) && $md->ACCOUNT_TYPE == NAK_ACCOUNT_TYPE_SAVINGS)?"selected":""; ?>><?php echo $this->AccauntTypeText(NAK_ACCOUNT_TYPE_SAVINGS); ?>
              <option value="<?php echo NAK_ACCOUNT_TYPE_CHECKING ?>" <?php echo (!empty($md->ACCOUNT_TYPE) && $md->ACCOUNT_TYPE == NAK_ACCOUNT_TYPE_CHECKING)?"selected":""; ?>><?php echo $this->AccauntTypeText(NAK_ACCOUNT_TYPE_CHECKING) ?>
            </select>
            <?php
              endif; ?>
            <?php
            if($explanation !== ""): ?>
              <br><font color="red"><?php echo $explanation ?></font>
            <?php
            endif; ?>
          </td>
    <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

    endif;
  }



  function SetM_ACCOUNT_NO($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    if(!$disp): ?>
      <input type="hidden" name="M_ACCOUNT_NO" value="<?php echo !empty($md->ACCOUNT_NO)?$md->ACCOUNT_NO:"" ?>">
  <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('口座番号')), '口座番号'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo !empty($md->ACCOUNT_NO)?$md->ACCOUNT_NO:"" ?>
          <input type="hidden" name="M_ACCOUNT_NO" value="<?php echo !empty(!empty($md->ACCOUNT_NO)?$md->ACCOUNT_NO:"")?!empty($md->ACCOUNT_NO)?$md->ACCOUNT_NO:"":"" ?>">
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp(!empty($md->ACCOUNT_NO)?$md->ACCOUNT_NO:"",!empty($md->ACCOUNT_NO)?$md->ACCOUNT_NO:"") ?>" id="m_claimClassNeed" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('口座番号')), '口座番号'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: disabled;" type="text" name="M_ACCOUNT_NO" maxlength="7" size="60" value="<?php echo !empty($md->ACCOUNT_NO)?$md->ACCOUNT_NO:"" ?>" onChange="changeClaimFormValue()">
          <?php
            endif; ?>
          <?php
            if($explanation !== ""):  ?>
              <br><font color="red"><?php echo $explanation ?></font>
          <?php
            endif; ?>
        </td>
  <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

    endif;
  }


  function SetM_ACCOUNT_NM($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->ACCOUNT_NM))
    $md->ACCOUNT_NM = "";
    if(!$disp): ?>
      <input type="hidden" name="M_ACCOUNT_NM" value="<?php echo $md->ACCOUNT_NM ?>">
  <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('口座名義')), '口座名義'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->ACCOUNT_NM ?>
          <input type="hidden" name="M_ACCOUNT_NM" value="<?php echo $md->ACCOUNT_NM ?>">
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->ACCOUNT_NM,$md->ACCOUNT_NM) ?>" id="m_claimClassNeed" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('口座名義')), '口座名義'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: active;" type="text" name="M_ACCOUNT_NM"  maxlength="30" size="60" value="<?php echo !empty($md->ACCOUNT_NM)?$md->ACCOUNT_NM:"" ?>" onChange="Javascript:funcZenToHan(this);"><br>(半角ｶﾅ　半角英字　｢･｣　｢.｣のみ)
      <?php
      endif; ?>
    <?php
    if($explanation !== ""): ?>
      <br><font color="red"><?php echo $explanation ?></font>
    <?php
    endif; ?>
  </td>
  <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

    endif;
  }


  function SetM_CUST_NO($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->CUST_NO))
    $md->CUST_NO = "";
    if(!$disp): ?>
      <input type="hidden" name="M_CUST_NO" value="<?php echo $md->CUST_NO ?>">
  <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('顧客番号')), '顧客番号'); ?>
          （全銀）
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CUST_NO ?>
          <input type="hidden" name="M_CUST_NO" value="<?php echo $md->CUST_NO ?>">
      <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CUST_NO,$md->CUST_NO) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('顧客番号')), '顧客番号'); ?>
          （全銀）
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
           <input style="ime-mode: inactive;" type="text" name="M_CUST_NO" maxlength="12" size="60" value="<?php echo !empty($md->CUST_NO)?$md->CUST_NO:"" ?>" onChange="changeClaimFormValue()">
      <?php
      endif; ?>
      <?php
      if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
      <?php
      endif; ?>
      </td>
  <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

    endif;
  }


  function SetM_BILLING_ID($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  $g_chg = $GLOBALS['g_chg'];
  if(empty($md->BILLING_ID))
    $md->BILLING_ID = "";
    if(!$disp): ?>
      <?php
        if($g_chg == 1): ?>
          <input type="hidden" name="M_BILLING_ID" id="M_BILLING_ID" value="<?php echo $md->BILLING_ID ?>">
        <?php
        else: ?>
          <input type="hidden" name="M_BILLING_ID" id="M_BILLING_ID" value="<?php echo $gBilling_def ?>">
        <?php
        endif; ?>
  <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先指定')), '請求先指定'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php
          if($g_chg == 1): ?>
            <?php echo $this->DestinationText($md->BILLING_ID) ?>
            <input type="hidden" name="M_BILLING_ID" id="M_BILLING_ID" value="<?php echo $md->BILLING_ID ?>">
          <?php
          else: ?>
            <?php echo $this->DestinationText($gBilling_def) ?>
            <input type="hidden" name="M_BILLING_ID" id="M_BILLING_ID" value="<?php echo $gBilling_def ?>">
          <?php
          endif; ?>
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else:
        if($_SESSION["LOGIN_TOPGID"] == TOPGID_JPCSED): ?>
          <td class="<?php echo $RegItem; ?><?php echo $this->comp(empty($md->BILLING_ID)?0:$md->BILLING_ID,empty($md->BILLING_ID)?0:$md->BILLING_ID) ?>" width="120" id="m_mainFeeInfo" nowrap>
            <?php echo ($must)?MUST_START_TAG:""; ?>
            <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先指定')), '請求先指定'); ?>
            <?php echo ($must)?MUST_END_TAG:""; ?>
          </td>
          <td class="<?php echo $RegValue; ?>">
            <input type="radio" name="M_CLAIMDEST_RADIO" value="<?php echo DEST_PRIVATE  ?>" onClick="OnClaimDestChangeRadio(this.value,true);" <?php echo ($md->BILLING_ID == DEST_PRIVATE)?"checked":""; ?>><?php echo $this->DestinationText(DEST_PRIVATE)   ?>
            <input type="radio" name="M_CLAIMDEST_RADIO" value="<?php echo DEST_AFF_GROUP ?>" onClick="OnClaimDestChangeRadio(this.value,true);" <?php echo ($md->BILLING_ID == DEST_AFF_GROUP)?"checked":""; ?>><?php echo $this->DestinationText(DEST_AFF_GROUP) ?>
            <span style="display:none;">
            <select name="M_BILLING_ID" id="M_BILLING_ID" onChange="changeClaimForm(true);">
              <option value="@G" <?php echo ($md->BILLING_ID == "@G")?"selected":""; ?>><?php echo $this->DestinationText(DEST_AFF_GROUP)     ?></option>
              <option value="@C" <?php echo ($md->BILLING_ID == "@C")?"selected":""; ?>><?php echo $this->DestinationText(DEST_CURRENT)       ?></option>
              <option value="@N" <?php echo ($md->BILLING_ID == "")?"selected":""; ?>><?php echo $this->DestinationText(DEST_NEW)           ?></option>
            </select>
            </span>
            <?php
            if($explanation !== ""):?>
              <br><font color="red"><?php echo $explanation ?></font>
            <?php
            endif; ?>
          </td>
      <?php
        else: ?>
          <td class="<?php echo $RegItem; ?><?php echo $this->comp(empty($md->BILLING_ID)?0:$md->BILLING_ID,empty($md->BILLING_ID)?0:$md->BILLING_ID) ?>" width="120" id="m_mainFeeInfo" nowrap>
            <?php echo ($must)?MUST_START_TAG:""; ?>
            <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先指定')), '請求先指定'); ?>
            <?php echo ($must)?MUST_END_TAG:""; ?>
          </td>
          <td class="<?php echo $RegValue; ?>">
            <select name="M_BILLING_ID" id="M_BILLING_ID" onChange="changeClaimForm(true);">
            <option value="@G" <?php echo ($md->BILLING_ID == "@G")?"selected":""; ?>><?php echo $this->DestinationText(DEST_AFF_GROUP)     ?></option>
            <option value="@C" <?php echo ($md->BILLING_ID == "@C")?"selected":""; ?>><?php echo $this->DestinationText(DEST_CURRENT)       ?></option>
            <option value="@N" <?php echo ($md->BILLING_ID == "")?"selected":""; ?>><?php echo $this->DestinationText(DEST_NEW)           ?></option>
            </select>
            <?php
            if($explanation !== ""): ?>
              <br><font color="red"><?php echo $explanation ?></font>
            <?php
            endif; ?>
          </td>
      <?php
        endif;
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_BILLING_G_NAME($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->BILLING_G_NAME))
    $md->BILLING_G_NAME = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CL_G_NAME"] = false; ?>
      <input type="hidden" name="M_BILLING_G_NAME" value="<?php echo $md->BILLING_G_NAME ?>">
    <?php
    else:
      $_SESSION["PUTOUT_JS_M_CL_G_NAME"] = true;
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先組織名')), '請求先組織名'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_G_NAME ?>
          <input type="hidden" name="M_BILLING_G_NAME" value="<?php echo $md->BILLING_G_NAME ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_G_NAME,$md->BILLING_G_NAME) ?>" width="120" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先組織名')), '請求先組織名'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: active;" type="text" class="w-100" name="M_BILLING_G_NAME" value="<?php echo !empty($md->BILLING_G_NAME)?$md->BILLING_G_NAME:"" ?>" onChange="changeClaimFormValue();changeContactForm();">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetM_BILLING_G_KANA($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->BILLING_G_KANA))
    $md->BILLING_G_KANA = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CL_G_KANA"] = false; ?>
      <input type="hidden" name="M_BILLING_G_KANA" value="<?php echo $md->BILLING_G_KANA ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CL_G_KANA"] = true;
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先組織名フリガナ')), '請求先組織名フリガナ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_G_KANA ?>
          <input type="hidden" name="M_BILLING_G_KANA" value="<?php echo $md->BILLING_G_KANA ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_G_KANA,$md->BILLING_G_KANA) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先組織名フリガナ')), '請求先組織名フリガナ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: active;" type="text" class="w-100" name="M_BILLING_G_KANA" value="<?php echo !empty($md->BILLING_G_KANA)?$md->BILLING_G_KANA:"" ?>" onChange="Javascript:funcHiraganaToKatakana(this);">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetM_BILLING_G_NAME_EN($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->BILLING_G_NAME_EN))
    $md->BILLING_G_NAME_EN = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CL_G_NAME_EN"] = false; ?>
      <input type="hidden" name="M_BILLING_G_NAME_EN" value="<?php echo $md->BILLING_G_NAME_EN ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CL_G_NAME_EN"] = true;
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先組織名英語表記')), '連絡先組織名英語表記'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_G_NAME_EN ?>
          <input type="hidden" name="M_BILLING_G_NAME_EN" value="<?php echo $md->BILLING_G_NAME_EN ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_G_NAME_EN,$md->BILLING_G_NAME_EN) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先組織名英語表記')), '連絡先組織名英語表記'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: active;" type="text" class="w-100" name="M_BILLING_G_NAME_EN" value="<?php echo !empty($md->BILLING_G_NAME_EN)?$md->BILLING_G_NAME_EN:"" ?>" onChange="Javascript:funcHiraganaToKatakana(this);">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_BILLING_C_NAME($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->BILLING_C_FNAME))
    $md->BILLING_C_FNAME = "";
  if(empty($md->BILLING_C_LNAME))
    $md->BILLING_C_LNAME = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CL_C_NAME"] = false; ?>
        <input type="hidden" name="M_BILLING_C_NAME" value="<?php echo $md->BILLING_C_FNAME." ".$md->BILLING_C_LNAME ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CL_C_NAME"] = true;
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先氏名')), '請求先氏名'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_C_FNAME." ".$md->BILLING_C_LNAME ?>
          <input type="hidden" name="M_BILLING_C_NAME" value="<?php echo $md->BILLING_C_FNAME." ".$md->BILLING_C_LNAME ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_C_FNAME." ".$md->BILLING_C_LNAME,$md->BILLING_C_FNAME." ".$md->BILLING_C_LNAME) ?>" width="120" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先氏名')), '請求先氏名'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: active;" type="text" class="w-100" name="M_BILLING_C_NAME" value="<?php echo (!empty($md->BILLING_C_FNAME)?$md->BILLING_C_FNAME:"")." ".(!empty($md->BILLING_C_LNAME)?$md->BILLING_C_LNAME:"") ?>" onChange="changeClaimFormValue();changeContactForm();">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }
  function SetM_BILLING_C_NAME_KN($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->BILLING_C_FNAME_KN))
    $md->BILLING_C_FNAME_KN = "";
  if(empty($md->BILLING_C_LNAME_KN))
    $md->BILLING_C_LNAME_KN = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CL_C_KANA"] = false; ?>
      <input type="hidden" name="M_BILLING_C_NAME_KN" value="<?php echo $md->BILLING_C_FNAME_KN." ".$md->BILLING_C_LNAME_KN ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CL_C_KANA"] = true;
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先氏名フリガナ')), '請求先氏名フリガナ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_C_FNAME_KN." ".$md->BILLING_C_LNAME_KN ?>
          <input type="hidden" name="M_BILLING_C_NAME_KN" value="<?php echo $md->BILLING_C_FNAME_KN." ".$md->BILLING_C_LNAME_KN ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_C_FNAME_KN." ".$md->BILLING_C_LNAME_KN,$md->BILLING_C_FNAME_KN." ".$md->BILLING_C_LNAME_KN) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先氏名フリガナ')), '請求先氏名フリガナ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: active;" type="text" class="w-100" name="M_BILLING_C_NAME_KN" value="<?php echo (!empty($md->BILLING_C_FNAME_KN)?$md->BILLING_C_FNAME_KN:"")." ".(!empty($md->BILLING_C_LNAME_KN)?$md->BILLING_C_LNAME_KN:"") ?>" onChange="Javascript:funcHiraganaToKatakana(this);">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_SAVINGS_CD($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->SAVINGS_CD))
    $md->SAVINGS_CD = "";
    if(!$disp): ?>
      <input type="hidden" name="M_SAVINGS_CD" value="<?php echo $md->SAVINGS_CD ?>">
  <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('貯金記号')), '貯金記号'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->SAVINGS_CD ?>
          <input type="hidden" name="M_SAVINGS_CD" value="<?php echo $md->SAVINGS_CD ?>">
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->SAVINGS_CD,$md->SAVINGS_CD) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('貯金記号')), '貯金記号'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: inactive;" type="text" name="M_SAVINGS_CD" maxlength="5" size="60" value="<?php echo !empty($md->SAVINGS_CD)?$md->SAVINGS_CD:"" ?>" onChange="changeClaimFormValue()"><br>
          <font color="red">※「ゆうちょ銀行」の場合のみ入力（数値5桁）</font>
    <?php
      endif;?>
      <?php
      if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
      <?php
      endif; ?>
      </td>
  <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_SAVINGS_NO($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->SAVINGS_NO))
    $md->SAVINGS_NO = "";
    if(!$disp): ?>
      <input type="hidden" name="M_SAVINGS_NO" value="<?php echo $md->SAVINGS_NO ?>">
  <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo '貯金番号'; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('貯金番号')), '貯金番号'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->SAVINGS_NO ?>
          <input type="hidden" name="M_SAVINGS_NO" value="<?php echo $md->SAVINGS_NO ?>">
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->SAVINGS_NO,$md->SAVINGS_NO) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('貯金番号')), '貯金番号'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: inactive;" type="text" name="M_SAVINGS_NO"  maxlength="8" size="60" value="<?php echo !empty($md->SAVINGS_NO)?$md->SAVINGS_NO:"" ?>" onChange="changeClaimFormValue()"><br>
          <font color="red">※「ゆうちょ銀行」の場合のみ入力（数値8桁）</font>
    <?php
      endif; ?>
      <?php
      if($explanation !== ""): ?>
        <br><font color="red"><?php echo $explanation ?></font>
      <?php
      endif; ?>
    </td>
    <?php
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }

    endif;
  }

  function SetM_BILLING_AFFILIATION($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->BILLING_AFFILIATION))
    $md->BILLING_AFFILIATION = "";
    if(!$disp): ?>
      <input type="hidden" name="M_BILLING_AFFILIATION" value="<?php echo $md->BILLING_AFFILIATION ?>">
  <?php
    else:
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先所属')), '請求先所属'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_AFFILIATION ?>
          <input type="hidden" name="M_BILLING_AFFILIATION" value="<?php echo $md->BILLING_AFFILIATION ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_AFFILIATION,$md->BILLING_AFFILIATION) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先所属')), '請求先所属'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input class="w-100" style="ime-mode: active;" type="text" name="M_BILLING_AFFILIATION" value="<?php echo $md->BILLING_AFFILIATION ?>" onChange="changeClaimFormValue();changeContactForm();">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_BILLING_POSITION($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->BILLING_POSITION))
    $md->BILLING_POSITION = "";
    if(!$disp): ?>
      <input type="hidden" name="M_BILLING_POSITION" value="<?php echo $md->BILLING_POSITION ?>">
  <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先役職')), '請求先役職'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_POSITION ?>
          <input type="hidden" name="M_BILLING_POSITION" value="<?php echo $md->BILLING_POSITION ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_POSITION,$md->BILLING_POSITION) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先役職')), '請求先役職'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input class="w-100" style="ime-mode: active;" type="text" name="M_BILLING_POSITION" value="<?php echo $md->BILLING_POSITION ?>" onChange="changeClaimFormValue();changeContactForm();">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }



  function SetM_BILLING_EMAIL($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->BILLING_EMAIL))
    $md->BILLING_EMAIL = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CL_C_EMAIL"] = false; ?>
      <input type="hidden" name="M_BILLING_EMAIL" value="<?php echo $md->BILLING_EMAIL ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CL_C_EMAIL"] = true;
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先Ｅ－ＭＡＩＬ')), '請求先Ｅ－ＭＡＩＬ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_EMAIL ?>
          <input type="hidden" name="M_BILLING_EMAIL" value="<?php echo $md->BILLING_EMAIL ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_EMAIL,$md->BILLING_EMAIL) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先Ｅ－ＭＡＩＬ')), '請求先Ｅ－ＭＡＩＬ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input class="w-100" style="ime-mode: disabled;" type="text" name="M_BILLING_EMAIL" value="<?php echo $md->BILLING_EMAIL ?>" onChange="changeClaimFormValue();changeContactForm();">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
  <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetM_BILLING_EMAIL2($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->BILLING_EMAIL))
    $md->BILLING_EMAIL = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CL_C_EMAIL2"] = false; ?>
      <input type="hidden" name="M_BILLING_EMAIL2" value="<?php echo $md->BILLING_EMAIL ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CL_C_EMAIL2"] = true;
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先E-MAIL再入力')), '請求先E-MAIL再入力'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_EMAIL ?>
          <input type="hidden" name="M_BILLING_EMAIL2" value="<?php echo $md->BILLING_EMAIL ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_EMAIL,$md->BILLING_EMAIL) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先E-MAIL再入力')), '請求先E-MAIL再入力'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input class="w-100" style="ime-mode: disabled;" type="text" name="M_BILLING_EMAIL2" value="<?php echo $md->BILLING_EMAIL ?>" onChange="changeClaimFormValue();changeContactForm();"  autocomplete="nope">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
  <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_BILLING_CC_EMAIL($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->BILLING_CC_EMAIL))
    $md->BILLING_CC_EMAIL = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CL_C_CC_EMAIL"] = false; ?>
      <input type="hidden" name="M_BILLING_CC_EMAIL" value="<?php echo $md->BILLING_CC_EMAIL ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CL_C_CC_EMAIL"] = true;
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先追加送信先Ｅ－ＭＡＩＬ')), '請求先追加送信先Ｅ－ＭＡＩＬ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_CC_EMAIL ?>
          <input type="hidden" name="M_BILLING_CC_EMAIL" value="<?php echo $md->BILLING_CC_EMAIL ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation; ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_CC_EMAIL,$md->BILLING_CC_EMAIL) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先追加送信先Ｅ－ＭＡＩＬ')), '請求先追加送信先Ｅ－ＭＡＩＬ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input class="w-100" style="ime-mode: disabled;" type="text" name="M_BILLING_CC_EMAIL" value="<?php echo $md->BILLING_CC_EMAIL ?>" onChange="changeClaimFormValue();changeContactForm();">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_BILLING_TEL($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
    $clTel1 = $clTel2 = $clTel3 = "";
    if(!empty($md->BILLING_TEL)){
      $clTel1 = explode("-",$md->BILLING_TEL)[0];
      $clTel2 = explode("-",$md->BILLING_TEL)[1];
      $clTel3 = explode("-",$md->BILLING_TEL)[2];
    }else { 
      $md->BILLING_TEL = "";
    }
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CL_C_TEL"] = false; ?>
      <input type="hidden" name="M_CL_C_TEL_1" value="<?php echo $clTel1 ?>">
      <input type="hidden" name="M_CL_C_TEL_2" value="<?php echo $clTel2 ?>">
      <input type="hidden" name="M_CL_C_TEL_3" value="<?php echo $clTel3 ?>">
      <input type="hidden" name="M_BILLING_TEL"   value="<?php echo $md->BILLING_TEL ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CL_C_TEL"] = true;
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先ＴＥＬ')), '請求先ＴＥＬ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_TEL ?>
          <input type="hidden" name="M_CL_C_TEL_1" value="<?php echo $clTel1 ?>">
          <input type="hidden" name="M_CL_C_TEL_2" value="<?php echo $clTel2 ?>">
          <input type="hidden" name="M_CL_C_TEL_3" value="<?php echo $clTel3 ?>">
          <input type="hidden" name="M_BILLING_TEL"   value="<?php echo $md->BILLING_TEL ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
  <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_TEL,$md->BILLING_TEL) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先ＴＥＬ')), '請求先ＴＥＬ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="M_CL_C_TEL_1" value="<?php echo $clTel1 ?>" onChange="changeClaimFormValue();changeContactForm();">&nbsp;-
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="M_CL_C_TEL_2" value="<?php echo $clTel2 ?>" onChange="changeClaimFormValue();changeContactForm();">&nbsp;-
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="M_CL_C_TEL_3" value="<?php echo $clTel3 ?>" onChange="changeClaimFormValue();changeContactForm();">
          <input type="hidden" name="M_BILLING_TEL" value="<?php echo $md->BILLING_TEL ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
  <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }



  function SetM_BILLING_FAX($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    $clFax1 = $clFax2 = $clFax3 = "";
    if(!empty($md->BILLING_FAX)){
      $clFax1 = explode("-",$md->BILLING_FAX)[0];
      $clFax2 = explode("-",$md->BILLING_FAX)[1];
      $clFax3 = explode("-",$md->BILLING_FAX)[2];
    }else { 
      $md->BILLING_FAX = "";
    }
    
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CL_C_FAX"] = false; ?>
      <input type="hidden" name="M_CL_C_FAX_1" value="<?php echo $clFax1 ?>">
      <input type="hidden" name="M_CL_C_FAX_2" value="<?php echo $clFax2 ?>">
      <input type="hidden" name="M_CL_C_FAX_3" value="<?php echo $clFax3 ?>">
      <input type="hidden" name="M_BILLING_FAX"   value="<?php echo $md->BILLING_FAX ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CL_C_FAX"] = true;
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先ＦＡＸ')), '請求先ＦＡＸ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_FAX ?>
          <input type="hidden" name="M_CL_C_FAX_1" value="<?php echo $clFax1 ?>">
          <input type="hidden" name="M_CL_C_FAX_2" value="<?php echo $clFax2 ?>">
          <input type="hidden" name="M_CL_C_FAX_3" value="<?php echo $clFax3 ?>">
          <input type="hidden" name="M_BILLING_FAX"   value="<?php echo $md->BILLING_FAX ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_FAX,$md->BILLING_FAX) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先ＦＡＸ')), '請求先ＦＡＸ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="M_CL_C_FAX_1" value="<?php echo $clFax1 ?>" onChange="changeClaimFormValue();changeContactForm();">&nbsp;-
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="M_CL_C_FAX_2" value="<?php echo $clFax2 ?>" onChange="changeClaimFormValue();changeContactForm();">&nbsp;-
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="M_CL_C_FAX_3" value="<?php echo $clFax3 ?>" onChange="changeClaimFormValue();changeContactForm();">
          <input type="hidden" name="M_BILLING_FAX" value="<?php echo $md->BILLING_FAX ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
  <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_BILLING_POST($disp, $must, $readOnlyFlg, $explanation){
    $page_link = $this->getPageSlug('nakama-member-zipcode');
    $postid = $GLOBALS['postid'];
    $entry_setting3 = $GLOBALS['entry_setting3'];
    $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
    $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    $clPostU = $clPostL = "";
    if(!empty($md->BILLING_POST)){
      $clPostU = explode("-",$md->BILLING_POST)[0];
      $clPostL = explode("-",$md->BILLING_POST)[1];
    }else {
      $md->BILLING_POST = "";
    } 
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CL_C_POST"] = false; ?>
      <input type="hidden" name="M_CL_C_POST_u" value="<?php echo $clPostU ?>">
      <input type="hidden" name="M_CL_C_POST_l" value="<?php echo $clPostL ?>">
      <input type="hidden" name="M_BILLING_POST"   value="<?php echo $md->BILLING_POST?>">
      <input type="hidden" name="search_button2">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CL_C_POST"] = true;
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先〒')), '請求先〒'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_POST ?>
          <input type="hidden" name="M_CL_C_POST_u" value="<?php echo $clPostU ?>">
          <input type="hidden" name="M_CL_C_POST_l" value="<?php echo $clPostL ?>">
          <input type="hidden" name="M_BILLING_POST"   value="<?php echo $md->BILLING_POST ?>">
          <input type="hidden" name="search_button2">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_POST,$md->BILLING_POST) ?>" id="m_claimNewNeed" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先〒')), '請求先〒'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: disabled;" type="text" class="w_60px" name="M_CL_C_POST_u" value="<?php echo $clPostU ?>" onChange="changeClaimFormValue();changeContactForm();">&nbsp;-
          <input style="ime-mode: disabled;" type="text" class="w_60px" name="M_CL_C_POST_l" value="<?php echo $clPostL ?>" onChange="changeClaimFormValue();changeContactForm();">
          <input type="hidden" name="M_BILLING_POST" value="<?php echo $md->BILLING_POST ?>">
          <input type="button" value="住所検索" name="search_button2" onClick="OnZipCode('M_CL_C_POST_u', 'M_CL_C_POST_l', <?php echo $postid; ?>, '<?php echo $page_link; ?>');">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_BILLING_STA($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->BILLING_STA))
    $md->BILLING_STA = "";
    if(!$disp): ?>
      <input type="hidden" name="M_BILLING_STA" value="<?php echo $md->BILLING_STA ?>">
  <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先都道府県')), '請求先都道府県'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_STA ?>
          <input type="hidden" name="M_BILLING_STA" value="<?php echo $md->BILLING_STA ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_STA,$md->BILLING_STA) ?>" id="m_claimNewNeed" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先都道府県')), '請求先都道府県'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <select name="M_BILLING_STA" onChange="changeClaimFormValue();changeContactForm();">
          <script type="text/javascript">StateSelectOptions2("<?php echo $md->BILLING_STA ?>");</script>
          </select>
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_BILLING_ADR($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->BILLING_ADR))
    $md->BILLING_ADR = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CL_C_ADDRESS"] = false; ?>
      <input type="hidden" name="M_BILLING_ADR" value="<?php echo $md->BILLING_ADR ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CL_C_ADDRESS"] = true;
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先住所１')), '請求先住所１'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_ADR ?>
          <input type="hidden" name="M_BILLING_ADR" value="<?php echo $md->BILLING_ADR ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_ADR,$md->BILLING_ADR) ?>" id="m_claimNewNeed" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先住所１')), '請求先住所１'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <?php
          if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) && $_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
            <input style="ime-mode: active;" type="text" class="w-100" name="M_BILLING_ADR" value="<?php echo $md->BILLING_ADR ?>" onChange="Javascript:funcHanToZen(this);">
          <?php
          else: ?>
            <input style="ime-mode: active;" type="text" class="w-100" name="M_BILLING_ADR" value="<?php echo $md->BILLING_ADR ?>" onChange="changeClaimFormValue();changeContactForm();">
          <?php
          endif; ?>
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_BILLING_ADR2($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->BILLING_ADR2))
    $md->BILLING_ADR2 = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CL_C_ADDRESS2"] = false; ?>
      <input type="hidden" name="M_BILLING_ADR2" value="<?php echo $md->BILLING_ADR2 ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CL_C_ADDRESS2"] = true;
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先住所２')), '請求先住所２'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_ADR2 ?>
          <input type="hidden" name="M_BILLING_ADR2" value="<?php echo $md->BILLING_ADR2 ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_ADR2,$md->BILLING_ADR2) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先住所２')), '請求先住所２'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <?php
          if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) && $_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
            <input style="ime-mode: active;" type="text" class="w-100" name="M_BILLING_ADR2" value="<?php echo $md->BILLING_ADR2 ?>" onChange="Javascript:funcHanToZen(this);">
          <?php
          else: ?>
            <input style="ime-mode: active;" type="text" class="w-100" name="M_BILLING_ADR2" value="<?php echo $md->BILLING_ADR2 ?>" onChange="changeClaimFormValue();changeContactForm();">
          <?php
          endif; ?>
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetM_BILLING_ADR3($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->BILLING_ADR3))
    $md->BILLING_ADR3 = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CL_C_ADDRESS3"] = false; ?>
      <input type="hidden" name="M_BILLING_ADR3" value="<?php echo $md->BILLING_ADR3 ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CL_C_ADDRESS3"] = true;
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先住所３')), '請求先住所３'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_ADR3 ?>
          <input type="hidden" name="M_BILLING_ADR3" value="<?php echo $md->BILLING_ADR3 ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_ADR3,$md->BILLING_ADR3) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先住所３')), '請求先住所３'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <?php
          if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) && $_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
            <input style="ime-mode: active;" type="text" class="w-100" name="M_BILLING_ADR3" value="<?php echo $md->BILLING_ADR3 ?>" onChange="Javascript:funcHanToZen(this);">
          <?php
          else: ?>
            <input style="ime-mode: active;" type="text" class="w-100" name="M_BILLING_ADR3" value="<?php echo $md->BILLING_ADR3 ?>" onChange="changeClaimFormValue();changeContactForm();">
          <?php
          endif; ?>
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetM_BILLING_ADR_EN($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->BILLING_ADR_EN))
    $md->BILLING_ADR_EN = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CL_C_ADDRESS_EN"] = false; ?>
      <input type="hidden" name="M_BILLING_ADR_EN" value="<?php echo $md->BILLING_ADR_EN ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CL_C_ADDRESS_EN"] = true;
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先住所　英語表記')), '請求先住所　英語表記'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->BILLING_ADR_EN ?>
          <input type="hidden" name="M_BILLING_ADR_EN" value="<?php echo $md->BILLING_ADR_EN ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->BILLING_ADR_EN,$md->BILLING_ADR_EN) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('請求先住所　英語表記')), '請求先住所　英語表記'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <?php
          if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) && $_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
            <input style="ime-mode: active;" type="text" class="w-100" name="M_BILLING_ADR_EN" value="<?php echo $md->BILLING_ADR_EN ?>" onChange="Javascript:funcHanToZen(this);">
          <?php
          else: ?>
            <input style="ime-mode: active;" type="text" class="w-100" name="M_BILLING_ADR_EN" value="<?php echo $md->BILLING_ADR_EN ?>" onChange="changeClaimFormValue();changeContactForm();">
          <?php
          endif; ?>
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }



  function SetM_CONTACT_ID($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  $g_chg = $GLOBALS['g_chg'];
  $gContact_def = $GLOBALS['DefContact'];
  if(empty($md->CONTACT_ID))
    $md->CONTACT_ID = "";
    if(!$disp):
      if($g_chg == 1): ?>
        <input type="hidden" name="M_CONTACT_ID" id="M_CONTACT_ID" value="<?php echo $md->CONTACT_ID ?>">
      <?php
      else: ?>
        <input type="hidden" name="M_CONTACT_ID" id="M_CONTACT_ID" value="<?php echo $gContact_def ?>">
      <?php
      endif; ?>
  <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先指定')), '連絡先指定'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php  if($g_chg == 1): ?>
            <?php echo $this->DestinationText($md->CONTACT_ID) ?>
            <input type="hidden" name="M_CONTACT_ID" id="M_CONTACT_ID" value="<?php echo $md->CONTACT_ID ?>">
          <?php
          else: ?>
            <?php echo $this->DestinationText($gContact_def) ?>
            <input type="hidden" name="M_CONTACT_ID" id="M_CONTACT_ID" value="<?php echo $gContact_def ?>">
          <?php
          endif; ?>
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else:
        if($_SESSION["LOGIN_TOPGID"] == TOPGID_JPCSED): ?>
          <td class="<?php echo $RegItem; ?><?php echo $this->comp(empty($md->CONTACT_ID)?0:$md->CONTACT_ID,empty($md->CONTACT_ID)?0:$md->CONTACT_ID) ?>" nowrap>
            <?php echo ($must)?MUST_START_TAG:""; ?>
            <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先指定')), '連絡先指定'); ?>
            <?php echo ($must)?MUST_END_TAG:""; ?>
          </td>
          <td class="<?php echo $RegValue; ?>">
            <input type="radio" name="M_CONTACTDEST_RADIO" value="<?php echo DEST_PRIVATE  ?>" onClick="OnContactDestChangeRadio(this.value,true);" <?php echo ($md->CONTACT_ID == DEST_PRIVATE)?"checked":""; ?>><?php echo $this->DestinationText(DEST_PRIVATE)   ?>
            <input type="radio" name="M_CONTACTDEST_RADIO" value="<?php echo DEST_AFF_GROUP ?>" onClick="OnContactDestChangeRadio(this.value,true);" <?php echo ($md->CONTACT_ID == DEST_AFF_GROUP)?"checked":""; ?>><?php echo $this->DestinationText(DEST_AFF_GROUP) ?>
            <span style="display:none;">
            <select name="M_CONTACT_ID" id="M_CONTACT_ID" onChange="changeContactForm(true);">
              <option value="@G" <?php echo ($md->CONTACT_ID == "@G")?"selected":""; ?>><?php echo $this->DestinationText(DEST_AFF_GROUP)    ?></option>
              <option value="@C" <?php echo ($md->CONTACT_ID == "@C")?"selected":""; ?>><?php echo $this->DestinationText(DEST_CURRENT)      ?></option>
              <option value="@N" <?php echo ($md->CONTACT_ID == "")?"selected":""; ?>><?php echo $this->DestinationText(DEST_NEW)          ?></option>
              </select><font color="red">※異なる場合のみ入力してください。</red>
              </span>
              <?php
              if($explanation !== ""): ?>
                <br><font color="red"><?php echo $explanation ?></font>
              <?php
              endif; ?>
          </td>
      <?php
        else: ?>
          <td class="<?php echo $RegItem; ?><?php echo $this->comp(empty($md->CONTACT_ID)?0:$md->CONTACT_ID,empty($md->CONTACT_ID)?0:$md->CONTACT_ID); ?>" nowrap>
            <?php echo ($must)?MUST_START_TAG:""; ?>
            <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先指定')), '連絡先指定'); ?>
            <?php echo ($must)?MUST_END_TAG:""; ?>
          </td>
          <td class="<?php echo $RegValue;?>">
            <select name="M_CONTACT_ID" id="M_CONTACT_ID" onChange="changeContactForm(true);">
              <option value="@G" <?php echo ($md->CONTACT_ID == "@G")?"selected":""; ?>><?php echo $this->DestinationText(DEST_AFF_GROUP)    ?></option>
              <option value="@C" <?php echo ($md->CONTACT_ID == "@C")?"selected":""; ?>><?php echo $this->DestinationText(DEST_CURRENT)      ?></option>
              <option value="@N" <?php echo ($md->CONTACT_ID == "")?"selected":""; ?>><?php echo $this->DestinationText(DEST_NEW)          ?></option>
            </select>
            <font color="red">※異なる場合のみ入力してください。</red>
            <?php
            if($explanation !== ""): ?>
                <br><font color="red"><?php echo $explanation ?></font>
            <?php
            endif; ?>
          </td>
      <?php
        endif;
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_CONTACT_G_NAME($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->CONTACT_G_NAME))
    $md->CONTACT_G_NAME = "";
    if(!$disp): ?>
      <input type="hidden" name="M_CONTACT_G_NAME" value="<?php echo $md->CONTACT_G_NAME ?>">
  <?php
    else:
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先組織名')), '連絡先組織名'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CONTACT_G_NAME ?>
          <input type="hidden" name="M_CONTACT_G_NAME" value="<?php echo $md->CONTACT_G_NAME ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_G_NAME,$md->CONTACT_G_NAME) ?>" width="120" id="m_contactNewNeed" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先組織名')), '連絡先組織名'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: active;" type="text" class="w-100" name="M_CONTACT_G_NAME" value="<?php echo $md->CONTACT_G_NAME ?>" onChange="changeContactFormValue();changeClaimForm();">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


function SetM_CONTACT_G_NAME_KN($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->CONTACT_G_NAME_KN))
    $md->CONTACT_G_NAME_KN = "";
    if(!$disp): ?>
      <input type="hidden" name="M_CONTACT_G_NAME_KN" value="<?php echo $md->CONTACT_G_NAME_KN ?>">
  <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先組織名フリガナ')), '連絡先組織名フリガナ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CONTACT_G_NAME_KN ?>
          <input type="hidden" name="M_CONTACT_G_NAME_KN" value="<?php echo $md->CONTACT_G_NAME_KN ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_G_NAME_KN,$md->CONTACT_G_NAME_KN) ?>" id="m_contactNewNeed" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先組織名フリガナ')), '連絡先組織名フリガナ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: active;" type="text" class="w-100" name="M_CONTACT_G_NAME_KN" value="<?php echo $md->CONTACT_G_NAME_KN ?>" onChange="Javascript:funcHiraganaToKatakana(this);">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetM_CONTACT_G_NAME_EN($disp, $must, $readOnlyFlg, $explanation){
    $entry_setting3 = $GLOBALS['entry_setting3'];
    $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
    $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    if(empty($md->CONTACT_G_NAME_EN))
      $md->CONTACT_G_NAME_EN = "";
      if(!$disp): ?>
        <input type="hidden" name="M_CONTACT_G_NAME_EN" value="<?php echo $md->CONTACT_G_NAME_EN ?>">
    <?php
      else:
        if($readOnlyFlg): ?>
          <td class="<?php echo $RegItem; ?>" nowrap>
            <?php echo ($must)?MUST_START_TAG:""; ?>
            <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先組織名英語表記')), '連絡先組織名英語表記'); ?>
            <?php echo ($must)?MUST_END_TAG:""; ?>
          </td>
          <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
            <?php echo $md->CONTACT_G_NAME_EN ?>
            <input type="hidden" name="M_CONTACT_G_NAME_EN" value="<?php echo $md->CONTACT_G_NAME_EN ?>">
            <?php
            if($explanation !== ""):?>
              <br><font color="red"><?php echo $explanation ?></font>
            <?php
            endif; ?>
          </td>
      <?php
        else: ?>
          <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_G_NAME_EN,$md->CONTACT_G_NAME_EN) ?>" id="m_contactNewNeed" nowrap>
            <?php echo ($must)?MUST_START_TAG:""; ?>
            <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先組織名英語表記')), '連絡先組織名英語表記'); ?>
            <?php echo ($must)?MUST_END_TAG:""; ?>
          </td>
          <td class="<?php echo $RegValue; ?>">
            <input style="ime-mode: active;" type="text" class="w-100" name="M_CONTACT_G_NAME_EN" value="<?php echo $md->CONTACT_G_NAME_EN ?>">
            <?php
            if($explanation !== ""):?>
              <br><font color="red"><?php echo $explanation ?></font>
            <?php
            endif; ?>
          </td>
      <?php
        endif;
        $gInputOpen = $GLOBALS['gInputOpen'];
        if($gInputOpen == "1"){
           $this->SetDummyInputOpen();
        }
      endif;
  }


  function SetM_CONTACT_C_NAME($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
  if(empty($md->CONTACT_C_FNAME))
    $md->CONTACT_C_FNAME = "";
  if(empty($md->CONTACT_C_LNAME))
    $md->CONTACT_C_LNAME = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CO_C_NAME"] = false; ?>
      <input type="hidden" name="M_CONTACT_C_NAME" value="<?php echo $md->CONTACT_C_FNAME." ".$md->CONTACT_C_LNAME ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CO_C_NAME"] = true;
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先氏名　姓')), '連絡先氏名　姓'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CONTACT_C_FNAME." ".$md->CONTACT_C_LNAME ?>
          <input type="hidden" name="M_CONTACT_C_NAME" value="<?php echo $md->CONTACT_C_FNAME." ".$md->CONTACT_C_LNAME ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_C_FNAME." ".$md->CONTACT_C_LNAME,$md->CONTACT_C_FNAME." ".$md->CONTACT_C_LNAME) ?>" width="120" id="m_contactNewNeed" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先氏名　姓')), '連絡先氏名　姓'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: active;" type="text" class="w-100" name="M_CONTACT_C_NAME" value="<?php echo !empty($md->CONTACT_C_FNAME) ? $md->CONTACT_C_FNAME." ".$md->CONTACT_C_LNAME : ""; ?>" onChange="changeContactFormValue();changeClaimForm();">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetM_CONTACT_C_NAME_KN($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->CONTACT_C_FNAME_KN))
    $md->CONTACT_C_FNAME_KN = "";
  if(empty($md->CONTACT_C_LNAME_KN))
    $md->CONTACT_C_LNAME_KN = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CO_C_KANA"] = false; ?>
      <input type="hidden" name="M_CONTACT_C_NAME_KN" value="<?php echo $md->CONTACT_C_FNAME_KN." ".$md->CONTACT_C_LNAME_KN ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CO_C_KANA"] = true;
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先氏名フリガナ　姓')), '連絡先氏名フリガナ　姓'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CONTACT_C_FNAME_KN." ".$md->CONTACT_C_LNAME_KN ?>
          <input type="hidden" name="M_CONTACT_C_NAME_KN" value="<?php echo $md->CONTACT_C_FNAME_KN." ".$md->CONTACT_C_LNAME_KN ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_C_FNAME_KN." ".$md->CONTACT_C_LNAME_KN,$md->CONTACT_C_FNAME_KN." ".$md->CONTACT_C_LNAME_KN) ?>" id="m_contactNewNeed" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先氏名フリガナ　姓')), '連絡先氏名フリガナ　姓'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: active;" type="text" class="w-100" name="M_CONTACT_C_NAME_KN" value="<?php echo !empty($md->CONTACT_C_FNAME_KN) ? $md->CONTACT_C_FNAME_KN." ".$md->CONTACT_C_LNAME_KN : ""; ?>" onChange="Javascript:funcHiraganaToKatakana(this);">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_CONTACT_AFFILIATION($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->CONTACT_AFFILIATION))
    $md->CONTACT_AFFILIATION = "";  
    if(!$disp): ?>
      <input type="hidden" name="M_CONTACT_AFFILIATION" value="<?php echo $md->CONTACT_AFFILIATION ?>">
  <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先所属')), '連絡先所属'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CONTACT_AFFILIATION ?>
          <input type="hidden" name="M_CONTACT_AFFILIATION" value="<?php echo $md->CONTACT_AFFILIATION ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_AFFILIATION,$md->CONTACT_AFFILIATION) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先所属')), '連絡先所属'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input class="w-100" style="ime-mode: active;" type="text" name="M_CONTACT_AFFILIATION" value="<?php echo $md->CONTACT_AFFILIATION ?>" onChange="changeContactFormValue();changeClaimForm();">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_CONTACT_POSITION($disp, $must, $readOnlyFlg, $explanation){
  $entry_setting3 = $GLOBALS['entry_setting3'];
  $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
  $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
  $md = $GLOBALS['md'];
  $mdh = $GLOBALS['mdh'];
  if(empty($md->CONTACT_POSITION))
    $md->CONTACT_POSITION = "";
    if(!$disp): ?>
      <input type="hidden" name="M_CONTACT_POSITION" value="<?php echo $md->CONTACT_POSITION ?>">
  <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先役職')), '連絡先役職'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CONTACT_POSITION ?>
          <input type="hidden" name="M_CONTACT_POSITION" value="<?php echo $md->CONTACT_POSITION ?>">
          <?php
          if($explanation !== ""):  ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else:?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_POSITION,$md->CONTACT_POSITION) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先役職')), '連絡先役職'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input class="w-100" style="ime-mode: active;" type="text" name="M_CONTACT_POSITION" value="<?php echo $md->CONTACT_POSITION ?>" onChange="changeContactFormValue();changeClaimForm();">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_CONTACT_EMAIL($disp, $must, $readOnlyFlg, $explanation){
    $entry_setting3 = $GLOBALS['entry_setting3'];
    $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
    $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    if(empty($md->CONTACT_EMAIL))
      $md->CONTACT_EMAIL = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CO_C_EMAIL"] = false; ?>
      <input type="hidden" name="M_CONTACT_EMAIL" value="<?php echo $md->CONTACT_EMAIL ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CO_C_EMAIL"] = true;
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先Ｅ－ＭＡＩＬ')), '連絡先Ｅ－ＭＡＩＬ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CONTACT_EMAIL ?>
          <input type="hidden" name="M_CONTACT_EMAIL" value="<?php echo $md->CONTACT_EMAIL ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_EMAIL,$md->CONTACT_EMAIL) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先Ｅ－ＭＡＩＬ')), '連絡先Ｅ－ＭＡＩＬ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input class="w-100" style="ime-mode: disabled;" type="text" name="M_CONTACT_EMAIL" value="<?php echo $md->CONTACT_EMAIL ?>" onChange="changeContactFormValue();changeClaimForm();">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetM_CONTACT_EMAIL2($disp, $must, $readOnlyFlg, $explanation){
    $entry_setting3 = $GLOBALS['entry_setting3'];
    $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
    $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    if(empty($md->CONTACT_EMAIL))
      $md->CONTACT_EMAIL = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CO_C_EMAIL2"] = false; ?>
      <input type="hidden" name="M_CONTACT_EMAIL2" value="<?php echo $md->CONTACT_EMAIL ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CO_C_EMAIL2"] = true;
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先Ｅ－ＭＡＩＬ')), '連絡先Ｅ－ＭＡＩＬ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CONTACT_EMAIL ?>
          <input type="hidden" name="M_CONTACT_EMAIL2" value="<?php echo $md->CONTACT_EMAIL ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_EMAIL,$md->CONTACT_EMAIL) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先Ｅ－ＭＡＩＬ')), '連絡先Ｅ－ＭＡＩＬ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input class="w-100" style="ime-mode: disabled;" type="text" name="M_CONTACT_EMAIL2" value="<?php echo $md->CONTACT_EMAIL ?>" onChange="changeContactFormValue();changeClaimForm();"  autocomplete="nope">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetM_CONTACT_CC_EMAIL($disp, $must, $readOnlyFlg, $explanation){
    $entry_setting3 = $GLOBALS['entry_setting3'];
    $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
    $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    if(empty($md->CONTACT_CC_EMAIL))
      $md->CONTACT_CC_EMAIL = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CO_C_CC_EMAIL"] = false; ?>
      <input type="hidden" name="M_CONTACT_CC_EMAIL" value="<?php echo $md->CONTACT_CC_EMAIL ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CO_C_CC_EMAIL"] = true;
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先追加送信先Ｅ－ＭＡＩＬ')), '連絡先追加送信先Ｅ－ＭＡＩＬ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CONTACT_CC_EMAIL ?>
          <input type="hidden" name="M_CONTACT_CC_EMAIL" value="<?php echo $md->CONTACT_CC_EMAIL ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_CC_EMAIL,$md->CONTACT_CC_EMAIL) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先追加送信先Ｅ－ＭＡＩＬ')), '連絡先追加送信先Ｅ－ＭＡＩＬ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input class="w-100" style="ime-mode: disabled;" type="text" name="M_CONTACT_CC_EMAIL" value="<?php echo $md->CONTACT_CC_EMAIL ?>" onChange="changeContactFormValue();changeClaimForm();">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetM_CONTACT_TEL($disp, $must, $readOnlyFlg, $explanation){
    $entry_setting3 = $GLOBALS['entry_setting3'];
    $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
    $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    $coTel1 = $coTel2 = $coTel3 = "";
    if(!empty($md->CONTACT_TEL)){
      $coTel1 = explode("-",$md->CONTACT_TEL)[0];
      $coTel2 = explode("-",$md->CONTACT_TEL)[1];
      $coTel3 = explode("-",$md->CONTACT_TEL)[2];
    }else {
      $md->CONTACT_TEL = "";
    }
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CO_C_TEL"] = false; ?>
      <input type="hidden" name="M_CO_C_TEL_1" value="<?php echo $coTel1 ?>">
      <input type="hidden" name="M_CO_C_TEL_2" value="<?php echo $coTel2 ?>">
      <input type="hidden" name="M_CO_C_TEL_3" value="<?php echo $coTel3 ?>">
      <input type="hidden" name="M_CONTACT_TEL"   value="<?php echo $md->CONTACT_TEL ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CO_C_TEL"] = true;
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先ＴＥＬ')), '連絡先ＴＥＬ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CONTACT_TEL ?>
          <input type="hidden" name="M_CO_C_TEL_1" value="<?php echo $coTel1 ?>">
          <input type="hidden" name="M_CO_C_TEL_2" value="<?php echo $coTel2 ?>">
          <input type="hidden" name="M_CO_C_TEL_3" value="<?php echo $coTel3 ?>">
          <input type="hidden" name="M_CONTACT_TEL"   value="<?php echo $md->CONTACT_TEL ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_TEL,$md->CONTACT_TEL) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先ＴＥＬ')), '連絡先ＴＥＬ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="M_CO_C_TEL_1" value="<?php echo $coTel1 ?>" onChange="changeContactFormValue();changeClaimForm();">&nbsp;-
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="M_CO_C_TEL_2" value="<?php echo $coTel2 ?>" onChange="changeContactFormValue();changeClaimForm();">&nbsp;-
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="M_CO_C_TEL_3" value="<?php echo $coTel3 ?>" onChange="changeContactFormValue();changeClaimForm();">
          <input type="hidden" name="M_CONTACT_TEL" value="<?php echo $md->CONTACT_TEL ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_CONTACT_FAX($disp, $must, $readOnlyFlg, $explanation){
    $entry_setting3 = $GLOBALS['entry_setting3'];
    $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
    $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    $coFax1 = $coFax2 = $coFax3 = "";
    if(!empty($md->CONTACT_FAX)){
      $coFax1 = explode("-",$md->CONTACT_FAX)[0];
      $coFax2 = explode("-",$md->CONTACT_FAX)[1];
      $coFax3 = explode("-",$md->CONTACT_FAX)[2];
    }else {
      $md->CONTACT_FAX = "";
    }
    
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CO_C_FAX"] = false; ?>
      <input type="hidden" name="M_CO_C_FAX_1" value="<?php echo $coFax1 ?>">
      <input type="hidden" name="M_CO_C_FAX_2" value="<?php echo $coFax2 ?>">
      <input type="hidden" name="M_CO_C_FAX_3" value="<?php echo $coFax3 ?>">
      <input type="hidden" name="M_CONTACT_FAX"   value="<?php echo $md->CONTACT_FAX?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CO_C_FAX"] = true;
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先ＦＡＸ')), '連絡先ＦＡＸ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CONTACT_FAX ?>
          <input type="hidden" name="M_CO_C_FAX_1" value="<?php echo $coFax1 ?>">
          <input type="hidden" name="M_CO_C_FAX_2" value="<?php echo $coFax2 ?>">
          <input type="hidden" name="M_CO_C_FAX_3" value="<?php echo $coFax3 ?>">
          <input type="hidden" name="M_CONTACT_FAX"   value="<?php echo $md->CONTACT_FAX ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_FAX,$md->CONTACT_FAX) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先ＦＡＸ')), '連絡先ＦＡＸ'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="M_CO_C_FAX_1" value="<?php echo $coFax1 ?>" onChange="changeContactFormValue();changeClaimForm();">&nbsp;-
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="M_CO_C_FAX_2" value="<?php echo $coFax2 ?>" onChange="changeContactFormValue();changeClaimForm();">&nbsp;-
          <input style="ime-mode: disabled;" type="text" class='w_60px' name="M_CO_C_FAX_3" value="<?php echo $coFax3 ?>" onChange="changeContactFormValue();changeClaimForm();">
          <input type="hidden" name="M_CONTACT_FAX" value="<?php echo $md->CONTACT_FAX ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_CONTACT_POST($disp, $must, $readOnlyFlg, $explanation){
    $page_link = $this->getPageSlug('nakama-member-zipcode');
    $postid = $GLOBALS['postid'];
    $entry_setting3 = $GLOBALS['entry_setting3'];
    $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
    $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    $coPostU = $coPostL  = "";
    if(!empty($md->CONTACT_POST)){
      $coPostU = explode("-",$md->CONTACT_POST)[0];
      $coPostL = explode("-",$md->CONTACT_POST)[1];
    }else {
      $md->CONTACT_POST = "";
    }
    
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CO_C_POST"] = false; ?>
      <input type="hidden" name="M_CO_C_POST_u" value="<?php echo $coPostU ?>">
      <input type="hidden" name="M_CO_C_POST_l" value="<?php echo $coPostL ?>">
      <input type="hidden" name="M_CONTACT_POST"   value="<?php echo $md->CONTACT_POST ?>">
      <input type="hidden" name="search_button1">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CO_C_POST"] = true;
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先〒')), '連絡先〒'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CONTACT_POST ?>
          <input type="hidden" name="M_CO_C_POST_u" value="<?php echo $coPostU ?>">
          <input type="hidden" name="M_CO_C_POST_l" value="<?php echo $coPostL ?>">
          <input type="hidden" name="M_CONTACT_POST"   value="<?php echo $md->CONTACT_POST ?>">
          <input type="hidden" name="search_button1">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_POST,$md->CONTACT_POST) ?>" id="m_contactNewNeed" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先〒')), '連絡先〒'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: disabled;" type="text" class="w_60px" name="M_CO_C_POST_u" value="<?php echo $coPostU ?>" onChange="changeContactFormValue();changeClaimForm();">&nbsp;-
          <input style="ime-mode: disabled;" type="text" class="w_60px" name="M_CO_C_POST_l" value="<?php echo $coPostL ?>" onChange="changeContactFormValue();changeClaimForm();">
          <input type="hidden" name="M_CONTACT_POST" value="<?php echo $md->CONTACT_POST ?>">
          <input type="button" value="住所検索" name="search_button1" onClick="OnZipCode('M_CO_C_POST_u', 'M_CO_C_POST_l', <?php echo $postid; ?>, '<?php echo $page_link; ?>');">

          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetM_CONTACT_STA($disp, $must, $readOnlyFlg, $explanation){
    $entry_setting3 = $GLOBALS['entry_setting3'];
    $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
    $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    if(empty($md->CONTACT_STA))
      $md->CONTACT_STA = "";
    if(!$disp): ?>
      <input type="hidden" name="M_CONTACT_STA" value="<?php echo $md->CONTACT_STA ?>">
  <?php
    else:
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先都道府県')), '連絡先都道府県'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CONTACT_STA ?>
          <input type="hidden" name="M_CONTACT_STA" value="<?php echo $md->CONTACT_STA ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_STA,$md->CONTACT_STA) ?>" id="m_contactNewNeed" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先都道府県')), '連絡先都道府県'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <select name="M_CONTACT_STA" onChange="changeContactFormValue();changeClaimForm();">
          <script type="text/javascript">StateSelectOptions2("<?php echo $md->CONTACT_STA ?>");</script>
          </select>
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetM_CONTACT_ADR($disp, $must, $readOnlyFlg, $explanation){
    $entry_setting3 = $GLOBALS['entry_setting3'];
    $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
    $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    if(empty($md->CONTACT_ADR))
      $md->CONTACT_ADR = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CO_C_ADDRESS"] = false; ?>
      <input type="hidden" name="M_CONTACT_ADR" value="<?php echo $md->CONTACT_ADR; ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CO_C_ADDRESS"] = true;
      if($readOnlyFlg):?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先住所１')), '連絡先住所１'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CONTACT_ADR ?>
          <input type="hidden" name="M_CONTACT_ADR" value="<?php echo $md->CONTACT_ADR ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_ADR,$md->CONTACT_ADR) ?>" id="m_contactNewNeed" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先住所１')), '連絡先住所１'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <?php
          if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) && $_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
            <input style="ime-mode: active;" type="text" class="w-100" name="M_CONTACT_ADR" value="<?php echo $md->CONTACT_ADR ?>" onChange="Javascript:funcHanToZen(this);">
          <?php
          else: ?>
          <input style="ime-mode: active;" type="text" class="w-100" name="M_CONTACT_ADR" value="<?php echo $md->CONTACT_ADR ?>" onChange="changeContactFormValue();changeClaimForm();">
          <?php
          endif; ?>
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
  <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetM_CONTACT_ADR2($disp, $must, $readOnlyFlg, $explanation){
    $entry_setting3 = $GLOBALS['entry_setting3'];
    $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
    $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    if(empty($md->CONTACT_ADR2))
      $md->CONTACT_ADR2 = "";
    if(!$disp):
      $_SESSION["PUTOUT_JS_M_CO_C_ADDRESS2"] = false; ?>
      <input type="hidden" name="M_CONTACT_ADR2" value="<?php echo $md->CONTACT_ADR2 ?>">
  <?php
    else:
      $_SESSION["PUTOUT_JS_M_CO_C_ADDRESS2"] = true;
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先住所２')), '連絡先住所２'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CONTACT_ADR2 ?>
          <input type="hidden" name="M_CONTACT_ADR2" value="<?php echo $md->CONTACT_ADR2 ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_ADR2,$md->CONTACT_ADR2) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先住所２')), '連絡先住所２'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <?php
          if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) && $_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
            <input style="ime-mode: active;" type="text" class="w-100" name="M_CONTACT_ADR2" value="<?php echo $md->CONTACT_ADR2 ?>" onChange="Javascript:funcHanToZen(this);">
          <?php
          else: ?>
            <input style="ime-mode: active;" type="text" class="w-100" name="M_CONTACT_ADR2" value="<?php echo $md->CONTACT_ADR2 ?>" onChange="changeContactFormValue();changeClaimForm();">
          <?php
          endif; ?>
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
  <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetM_CONTACT_ADR3($disp, $must, $readOnlyFlg, $explanation){
    $entry_setting3 = $GLOBALS['entry_setting3'];
    $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
    $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    if(empty($md->CONTACT_ADR3))
      $md->CONTACT_ADR3 = "";
    if(!$disp):
      ?>
      <input type="hidden" name="M_CONTACT_ADR3" value="<?php echo $md->CONTACT_ADR3 ?>">
  <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先住所３')), '連絡先住所３'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CONTACT_ADR3 ?>
          <input type="hidden" name="M_CONTACT_ADR3" value="<?php echo $md->CONTACT_ADR3 ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_ADR3,$md->CONTACT_ADR3) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先住所３')), '連絡先住所３'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <?php
          if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) && $_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
            <input style="ime-mode: active;" type="text" class="w-100" name="M_CONTACT_ADR3" value="<?php echo $md->CONTACT_ADR3 ?>" >
          <?php
          else: ?>
            <input style="ime-mode: active;" type="text" class="w-100" name="M_CONTACT_ADR3" value="<?php echo $md->CONTACT_ADR3 ?>" >
          <?php
          endif; ?>
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
  <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetM_CONTACT_ADR_EN($disp, $must, $readOnlyFlg, $explanation){
    $entry_setting3 = $GLOBALS['entry_setting3'];
    $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
    $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    if(empty($md->CONTACT_ADR_EN))
      $md->CONTACT_ADR_EN = "";
    if(!$disp):
      ?>
      <input type="hidden" name="M_CONTACT_ADR_EN" value="<?php echo $md->CONTACT_ADR_EN ?>">
  <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先住所英語表記')), '連絡先住所英語表記'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->CONTACT_ADR_EN ?>
          <input type="hidden" name="M_CONTACT_ADR_EN" value="<?php echo $md->CONTACT_ADR_EN ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->CONTACT_ADR_EN,$md->CONTACT_ADR_EN) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('連絡先住所英語表記')), '連絡先住所英語表記'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <?php
          if(!empty($_SESSION["NAKAMA_TRANSFER_FORMAT"]) && $_SESSION["NAKAMA_TRANSFER_FORMAT"] == "1"): ?>
            <input style="ime-mode: active;" type="text" class="w-100" name="M_CONTACT_ADR_EN" value="<?php echo $md->CONTACT_ADR_EN ?>" >
          <?php
          else: ?>
            <input style="ime-mode: active;" type="text" class="w-100" name="M_CONTACT_ADR_EN" value="<?php echo $md->CONTACT_ADR_EN ?>" >
          <?php
          endif; ?>
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
  <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetATENA_SUU($disp, $must, $readOnlyFlg, $explanation){
    $entry_setting3 = $GLOBALS['entry_setting3'];
    $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
    $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    if(empty($md->ATENA_SUU))
      $md->ATENA_SUU = "";
    if(!$disp): ?>
      <input type="hidden" name="ATENA_SUU" value="<?php echo $md->ATENA_SUU ?>">
      <?php
    else:
      if($readOnlyFlg): ?>
        <td class="<?php echo $RegItem; ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('発行部数')), '発行部数'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php echo $md->ATENA_SUU?>
          <input type="hidden" name="ATENA_SUU" value="<?php echo $md->ATENA_SUU ?>">
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td class="<?php echo $RegItem; ?><?php echo $this->comp($md->ATENA_SUU,$md->ATENA_SUU) ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('発行部数')), '発行部数'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?>">
          <input style="ime-mode: inactive;" type="text" maxlength="2" class="w_60px" name="ATENA_SUU" value="<?php echo !empty($md->ATENA_SUU)?$md->ATENA_SUU:""; ?>">
           宛名ラベル、封筒、はがきの印刷部数を指定してください。
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
        <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;

  }

  function SetInfoMail($disp, $must, $readOnlyFlg, $default, $explanation){
    $entry_setting3 = $GLOBALS['entry_setting3'];
    $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
    $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    $dispclass = '';
    $dispclass2 = '';
    $gColSpan = $GLOBALS['gColSpan'];
    if(empty($md->RECEIVE_INFO_MAIL))
      $md->RECEIVE_INFO_MAIL = "";
    if($gColSpan !== 1):
      if($this->comp($md->RECEIVE_INFO_MAIL, $md->RECEIVE_INFO_MAIL) == ""):
        $dispclass = "ReleaseSetting";
      else:
        $dispclass = $RegItem.$this->comp($md->RECEIVE_INFO_MAIL,$md->RECEIVE_INFO_MAIL);
      endif;
      $dispclass2 = "ReleaseSetting";
    else:
      $dispclass = $RegItem.$this->comp(!empty($md->RECEIVE_INFO_MAIL)?$md->RECEIVE_INFO_MAIL:"",!empty($md->RECEIVE_INFO_MAIL)?$md->RECEIVE_INFO_MAIL:"");
      $dispclass2 = $RegValue;
    endif;
    if(!$disp): ?>
      <input type="hidden" name="M_RECEIVE_INFO_MAIL" value="<?php echo $md->RECEIVE_INFO_MAIL ?>">
  <?php
    else:
      if($readOnlyFlg): ?>
        <td colspan="<?php echo $gColSpan ?>" class="<?php echo $dispclass ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('メールによる案内受取設定')), 'メールによる会からの案内受取設定'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php
          if(empty($md->RECEIVE_INFO_MAIL)?"1":$md->RECEIVE_INFO_MAIL == "1"):
            echo "Ｅメールでの案内を受け取る<br>";
          else:
          echo "Ｅメールでの案内を受け取らない<br>";
          endif; ?>
          <input type="hidden" name="M_RECEIVE_INFO_MAIL" value="<?php echo $md->RECEIVE_INFO_MAIL ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td colspan="<?php echo $gColSpan ?>" class="<?php echo $dispclass ?>">
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('メールによる案内受取設定')), 'メールによる会からの案内受取設定'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $dispclass2 ?>">Ｅメールでの案内を
          <select name="M_RECEIVE_INFO_MAIL">
            <option value="1" <?php echo (!empty($md->RECEIVE_INFO_MAIL) && $md->RECEIVE_INFO_MAIL ==  "1")?"selected":""; ?>>受け取る</option>
            <option value="0" <?php echo (!empty($md->RECEIVE_INFO_MAIL) && $md->RECEIVE_INFO_MAIL == "0")?"selected":""; ?>>受け取らない</option>
          </select>
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }

  function SetInfoFax($disp, $must, $readOnlyFlg, $explanation){
    $entry_setting3 = $GLOBALS['entry_setting3'];
    $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
    $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    $dispclass = '';
    $dispclass2 = '';
    $gColSpan = $GLOBALS['gColSpan'];
    if(empty($md->RECEIVE_INFO_PMAIL))
      $md->RECEIVE_INFO_PMAIL = "";
    if($gColSpan != 1):
      if($this->comp($md->RECEIVE_INFO_PMAIL,$md->RECEIVE_INFO_PMAIL) == ""):
        $dispclass = "ReleaseSetting";
      else:
        $dispclass = $RegItem.$this->comp($md->RECEIVE_INFO_PMAIL,$md->RECEIVE_INFO_PMAIL);
      endif;
      $dispclass2 = "ReleaseSetting";
    else:
      $dispclass = $RegItem.$this->comp(!empty($md->RECEIVE_INFO_PMAIL)?$md->RECEIVE_INFO_PMAIL:"",!empty($md->RECEIVE_INFO_PMAIL)?$md->RECEIVE_INFO_PMAIL:"");
      $dispclass2 = $RegValue;
    endif;
    if(!$disp): ?>
      <input type="hidden" name="M_RECEIVE_INFO_FAX" value="<?php echo $md->RECEIVE_INFO_PMAIL ?>">
  <?php
    else:
      if($readOnlyFlg): ?>
        <td colspan='<?php echo $gColSpan ?>' class="<?php echo $dispclass ?>" nowrap>
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('ＦＡＸによる案内受取設定')), 'ＦＡＸによる会からの案内受取設定'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
          <?php
            if(empty($md->RECEIVE_INFO_PMAIL)?"1":$md->RECEIVE_INFO_PMAIL = "1"):
              echo "ＦＡＸでの案内を受け取る<br>";
            else:
              echo "ＦＡＸでの案内を受け取らない<br>";
            endif;
          ?>
          <input type="hidden" name="M_RECEIVE_INFO_FAX" value="<?php echo $md->RECEIVE_INFO_PMAIL ?>">
          <?php
          if($explanation !== ""):?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
          endif; ?>
        </td>
    <?php
      else: ?>
        <td colspan='<?php echo $gColSpan ?>' class="<?php echo $dispclass ?>">
          <?php echo ($must)?MUST_START_TAG:""; ?>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('ＦＡＸによる案内受取設定')), 'ＦＡＸによる会からの案内受取設定'); ?>
          <?php echo ($must)?MUST_END_TAG:""; ?>
        </td>
        <td class="<?php echo $dispclass2 ?>">ＦＡＸでの案内を
          <select name="M_RECEIVE_INFO_FAX">
            <option value="1" <?php echo (!empty($md->RECEIVE_INFO_PMAIL) && $md->RECEIVE_INFO_PMAIL == "1")?"selected":""; ?>>受け取る</option>
            <option value="0" <?php echo (!empty($md->RECEIVE_INFO_PMAIL) && $md->RECEIVE_INFO_PMAIL == "0")?"selected":""; ?>>受け取らない</option>
          </select>
          <?php
          if($explanation !== ""): ?>
            <br><font color="red"><?php echo $explanation ?></font>
          <?php
            endif; ?>
        </td>
    <?php
      endif;
      $gInputOpen = $GLOBALS['gInputOpen'];
      if($gInputOpen == "1"){
         $this->SetDummyInputOpen();
      }
    endif;
  }


  function SetM_MLMAGA_FLG($disp, $must, $readOnlyFlg, $default, $explanation){
    $entry_setting3 = $GLOBALS['entry_setting3'];
    $postid = $GLOBALS['postid'];
    $RegItem = ($entry_setting3 == 1)?"RegItem":"RegItem_noline";
    $RegValue = ($entry_setting3 == 1)?"RegValue":"RegValue_noline";
    $md = $GLOBALS['md'];
    $mdh = $GLOBALS['mdh'];
    $chg = $GLOBALS['chg'];
    $tg_id = $GLOBALS['tg_id'];
    $dispclass = '';
    $dispclass2 = '';
    $gColSpan = $GLOBALS['gColSpan'];
    if($gColSpan != 1):
      if($this->comp($md->MLMAGA_FLG,$md->MLMAGA_FLG) == ""):
        $dispclass = "ReleaseSetting";
      else:
        $dispclass = $RegItem.$this->comp($md->MLMAGA_FLG,$md->MLMAGA_FLG);
      endif;
      $dispclass2 = "ReleaseSetting";
    else:
      $dispclass = $RegItem.$this->comp(!empty($md->MLMAGA_FLG)?$md->MLMAGA_FLG:"",!empty($md->MLMAGA_FLG)?$md->MLMAGA_FLG:"");
      $dispclass2 = $RegValue;
    endif;
    if(!$disp):
      if($chg != "1"): ?>
        <input type="hidden" name="M_MLMAGA_FLG" value="<?php echo empty($default)?empty($md->MLMAGA_FLG)?"0":$md->MLMAGA_FLG:$default; ?>">
      <?php
      else: ?>
        <input type="hidden" name="M_MLMAGA_FLG" value="<?php echo empty($md->MLMAGA_FLG)?"0":$md->MLMAGA_FLG; ?>">
    <?php
      endif;
    else:
      if(!empty($md->MLMAGA_FLG) && $md->MLMAGA_FLG == "9"): ?>
        <td colspan='<?php echo $gColSpan ?>' class="<?php echo $dispclass ?>" nowrap>
          <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('メルマガ登録設定')), 'メルマガ登録設定'); ?>
        </td>
        <td class="<?php echo $dispclass2 ?>">
          メルマガグループＩＤが設定されていません。
          <input type="hidden" name="M_MLMAGA_FLG" value="<?php echo empty($md->MLMAGA_FLG)?"0":$md->MLMAGA_FLG; ?>">
        </td>
    <?php
      else:
        if($readOnlyFlg): ?>
          <td colspan='<?php echo $gColSpan ?>' class="<?php echo $dispclass ?>" nowrap>
            <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('メルマガ登録設定')), 'メルマガ登録設定'); ?>
          </td>
          <td class="<?php echo $RegValue; ?><?php echo ($entry_setting3 == 1)?' ReadOnly':''; ?>">
              <?php 
                $eofBefore = $this->CheckRegistMlmagaGId($postid, $tg_id, $md->P_ID); 
                if($eofBefore == 1){
                  echo '現在 登録済';
                }else{
                  echo '現在 未登録';
                }
              ?>
              <input type="hidden" name="M_MLMAGA_FLG" value="<?php echo empty($md->MLMAGA_FLG)?"0":$md->MLMAGA_FLG; ?>">
              <?php
              if($explanation !== ""): ?>
                <br><font color="red"><?php echo $explanation ?></font>
            <?php
            endif; ?>
          </td>
      <?php
        else: ?>
          <td colspan='<?php echo $gColSpan ?>' class="<?php echo $dispclass ?>" nowrap>
            <?php echo $this->convertNvl(str_replace("<br>", "",$this->renderTitle('メルマガ登録設定')), 'メルマガ登録設定'); ?>
          </td>
          <td class="<?php echo $dispclass2 ?>">
          <?php
            $mFlg = 0;
            $eofAfter = $this->CheckRegistMlmagaGId($postid, $tg_id, !empty($md->P_ID)?$md->P_ID:""); 
            if(empty($eofAfter->Message)){
              if( isset($eofAfter->IS_REGIST_MLMANGA) && $eofAfter->IS_REGIST_MLMANGA == 1){
                $mFlg = 1;
              }else{
                $mFlg = 0;
              }
            }
            //($chg != "1" || $mFlg == 0)
          if($chg != "1"): ?>  
              <input type="radio" name="M_MLMAGA_FLG" value="1" <?php echo (empty($default)?"1":$default == "1")?"checked":""; ?>> <label>する</label> 
              <input type="radio" name="M_MLMAGA_FLG" value="0" <?php echo (empty($default)?"0":$default == "0")?"checked":""; ?>> <label>しない</label>
            <?php
            else: ?>
              <input type="radio" name="M_MLMAGA_FLG" value="1" <?php echo is_numeric($md->MLMAGA_FLG == '')?$default:$md->MLMAGA_FLG == "1"?"checked":""; ?>> <label>する</label> 
              <input type="radio" name="M_MLMAGA_FLG" value="0" <?php echo is_numeric($md->MLMAGA_FLG == '')?$default:$md->MLMAGA_FLG == "0"?"checked":""; ?>> <label>しない</label> 
            <?php
            endif;
            if($mFlg == 1):
              echo "現在 登録済";
            else:
              echo "現在 未登録";
            endif; ?>
            <?php
            if($explanation != ""):?>
              <br><font color="red"><?php echo $explanation ?></font>
            <?php
            endif; ?>
          </td>
      <?php
        endif;
        $gInputOpen = $GLOBALS['gInputOpen'];
        if($gInputOpen == "1"){
           $this->SetDummyInputOpen();
        }
      endif;
    endif;
}
    public function getdispIni($name, $dispIni){
      $g_chg = $GLOBALS['g_chg'];
      if($g_chg == "1"):
        $getDispIni = ($name)?$name:$dispIni;
      else:
        $getDispIni = ($dispIni)?$dispIni:$name;
      endif;
    }
    public function comp($cur, $old){
      $cur = ($cur)?$cur:"";
      $old = ($old)?$old:"";
      $comp = "";
      if($cur == ""):
        if($old != ""):
          $comp = "_del";
        endif;
      else:
        if($old == ""):
          $comp = "_add";
        else:
          if($cur !== $old):
            $comp = "_chg";
          endif;
        endif;
      endif;
      return $comp;
    }

    public function DestinationText($dest){
      $DestinationText  = '';
      switch ($dest) {
        case DEST_CURRENT:
          $DestinationText = "個人データ登録先";
          break;
        case DEST_REGISTERED:
          $DestinationText = "別の個人ID";
          break;
        case DEST_NEW:
          $DestinationText = "新たに設定";
          break;
        case DEST_AFF_GROUP:
            if($_SESSION["LOGIN_TOPGID"] == "jpcsed"):
              $DestinationText = "勤務先";
            else:
              $DestinationText = "所属組織データ登録先";
            endif;
          break;
        case DEST_PRIVATE:
            if($_SESSION["LOGIN_TOPGID"] == "jpcsed"):
              $DestinationText = "個人先";
            else:
              $DestinationText = "プライベート情報登録先";
            endif;
          break;
        default:
          break;
      }
      return $DestinationText;
    }


    public function Check_Entry_CheckBox($val, $target){
      $i;
      $buf;
      $Check_Entry_CheckBox = false;
      if(is_array(explode("|", $val))):
        $buf = explode("|", $val);
        for ($i=0; $i < count($buf); $i++) {
          if($target == $buf[$i]):
            $Check_Entry_CheckBox = true;
          endif;
        }
      else:
        if($target == $val):
          $Check_Entry_CheckBox = true;
        endif;
      endif;
      return $Check_Entry_CheckBox;
    }
    public function ShowImperialOption($val){ ?>
      <option value="AD" <?php echo ($val == "AD")?"selected":""; ?>>西暦</option>
      <option value="M" <?php echo ($val == "M")?"selected":""; ?>>明治</option>
      <option value="T" <?php echo ($val == "T")?"selected":""; ?>>大正</option>
      <option value="S" <?php echo ($val == "S")?"selected":""; ?>>昭和</option>
      <option value="H" <?php echo ($val == "H")?"selected":""; ?>>平成</option>
    <?php
    }
    public function openLevelText($val){
      $rs = '';
      switch ($val) {
        case '0':
          $rs = "公開しない";
          break;
        case '1':
          $rs = "一般公開";
          break;
        case '2':
          $rs = "会員にのみ公開";
          break;
        default:
          # code...
          break;
      }
    }
    public function dispImgSrc($filename){
      $dispImgSrc = '';
      if($filename == ''):
        $dispImgSrc = "<img src='data/imgs/".$filename."'><br>不明な画像";
      endif;
      $extension = pathinfo($filename, PATHINFO_EXTENSION);
      switch ($extension) {
        case "gif":
          $sfile = strtolower($filename);
          $sfile = str_replace(".gif", "_S.gif", $sfile);
          $buf = "<img src='".ROOT_IMG."/data/imgs/".$filename."' title='画像' width='80' border='0'>";
          break;
        case "jpg":
        case "bmp":
        case "png":
        case "PNG":
          $buf = "<img src='".ROOT_IMG."/data/imgs/".$filename."' title='画像' width='80' border='0'>";
          break;
        case "wma":
        case "wax":
        case "wav":
        case "mp3":
        case "mp1":
        case "mp2":
        case "ra":
        case "ram":
        case "mid":
        case "midi":
        case "rmi":
        case "aif":
        case "aiff":
        case "aifc":
        case "au":
        case "mpga":
        case "cda":
          $buf = "<img src='".ROOT_IMG."/data/imgs/".$filename."' border='0' vspace='0' hspace='10'>";
          break;
        case "asx":
        case "asf":
        case "wpl":
        case "wm":
        case "wmx":
        case "wmd":
        case "wmz":
        case "wmv":
        case "avi":
        case "mpg":
        case "mpeg":
        case "mpe":
        case "m1v":
        case "m2v":
        case "mpv2":
        case "mpv":
        case "mps":
        case "mpa":
        case "mp4":
        case "m4e":
        case "rm":
        case "rv":
        case "smi":
        case "mov":
        case "qt":
        case "vdo":
        case "3gp":
        case "3gp2":
        case "amv":
        case "amc":
        case "dcr":
        case "dir":
        case "dxr":
        case "spl":
          $buf = "<img src='../img/composition/movie.gif' border='0' vspace='0' hspace='10'>";
          break;
        case "swf":
          $buf = "<embed src='../img/".$imgpath."/".$filename."'>";
          break;
        case "pdf":
          $buf = "<img src='../img/composition/pdf.gif' border='0' vspace='0' hspace='10' title='PDFファイル(*.pdf)'>";
          break;
        case "xls":
        case "xlsx":
          $buf = "<img src='../img/composition/excel.gif' border='0' vspace='0' hspace='10' title='Microsoft Excelファイル(*.xls | *.xlsx)'>";
          break;
        case "doc":
        case "docx":
          $buf = "<img src='../img/composition/word.gif' border='0' vspace='0' hspace='10' title='Microsoft Wordファイル(*.doc | *.docx)'>";
          break;
        case "ppt":
        case "pptx":
          $buf = "<img src='../img/composition/ppt.gif' border='0' vspace='0' hspace='10' title='Microsoft PowerPointファイル(*.ppt | *.pptx)'>";
          break;
        case "csv":
          $buf = "<img src='../img/composition/csv.gif' border='0' vspace='0' hspace='10' title='CSVファイル(カンマ区切りテキストファイル)'>";
          break;
        case "rtf":
          $buf = "<img src='../img/composition/rtf.gif' border='0' vspace='0' hspace='10' title='リッチテキスト(*.rtf)'>";
          break;
        case "mht":
          $buf = "<img src='../img/composition/mht.gif' border='0' vspace='0' hspace='10' title='ＷＥＢアーカイブ、単一のファイル(*.mht)'>";
          break;
        case "zip":
          $buf = "<img src='../img/composition/zip.s.GIF' border='0' vspace='0' hspace='10' title='ZIP圧縮ファイル(*.zip)'>";
          break;
        case "lzh":
          $buf = "<img src='../img/composition/lzh.s.GIF' border='0' vspace='0' hspace='10' title='LZH圧縮ファイル(*.lzh)'>";
          break;
        case "tif":
        case "tiff":
        case "qti":
        case "pict":
        case "pic":
        case "pct":
          $buf = "<img src='../img/composition/img.gif' border='0' vspace='0' hspace='10'>";
          break;
        case "xps":
          $buf = "<img src='../img/composition/xps.png' border='0' vspace='0' hspace='10' title='Microsoft XPS ドキュメント(*.xps)'>";
          break;
      }
      $dispImgSrc = $buf;
      return $dispImgSrc;
    }
    public function AccauntTypeText($accaunt_type){
      $accauntTypeText = '';
      switch ($accaunt_type) {
        case NAK_ACCOUNT_TYPE_SAVINGS:
          $accauntTypeText = "普通";
          break;
        case NAK_ACCOUNT_TYPE_CHECKING:
          $accauntTypeText = "当座";
          break;
        default:
          break;
      }
      return $accauntTypeText;
    }
    public function GetBankInfo($v){
      $tg_id = $GLOBALS['tg_id'];
      $post_id = $GLOBALS['postid'];
      $url = URL_API.'Setting/GetBankInfo?tg_id='.$tg_id;
      $arrBody = array();
      $GetBankInfo = get_api_common($post_id, $url, $arrBody, "GET");
      echo '<option value=""></option>';
      foreach ($GetBankInfo as $key => $value) {
        $selected = ($v == $value->BANK_CD)?"selected":"";
        echo '<option '.$selected.' value='.$value->BANK_CD.'>'.$value->BANK_CD." ".$value->BANK_NM.'</option>';
      }
    }
    public function FillLowerGroup($tg_id,$lg_id){
      $post_id = $GLOBALS['postid'];
      $url = URL_API.'Member/getGroupTree';
      $arrBody = array(
        "TG_ID" => $tg_id,
        "LG_TYPE" => "0"
      );
      $FillLowerGroup = get_api_common($post_id, $url, $arrBody, "POST");
      echo '<option value="">選択してください</option>';
      foreach ($FillLowerGroup->data as $key => $value) {
        $selected = ($lg_id == $value->LG_ID)?"selected":"";
        echo '<option '.$selected.' value='.$value->LG_ID.'>'.$value->LG_NAME.'</option>';
      }
    }
    public function FillLowerGroupSel($tg_id, $lg_id){
      $post_id = $GLOBALS['postid'];
      $url = URL_API.'Member/getGroupTree';
      $arrBody = array(
        "TG_ID" => $tg_id,
        "LG_TYPE" => "0"
      );
      $FillLowerGroup = get_api_common($post_id, $url, $arrBody, "POST");
      foreach ($FillLowerGroup->data as $key => $value) {
        $selected = ($lg_id == $value->LG_ID)?"selected":"";
        echo '<option '.$selected.' value='.$value->LG_ID.'>'.$value->LG_NAME.'</option>';
      }
    }
    public function GetClaimClassText($val){
      switch ($val) {
        case '1':
          $GetClaimClassText = "当日";
          break;
        case '2':
          $GetClaimClassText = "自動引落";
          break;
        case '3':
          $GetClaimClassText = "事前振込";
          break;
        default:
          break;
      }
      return $GetClaimClassText;
    }
    public function ClaimClassText($claim_class){
      switch ($claim_class) {
        case NAK_CLAIM_CLASS_AUTO:
          $ClaimClassText = "自動振替";
          break;
        case NAK_CLAIM_CLASS_BANK:
          $ClaimClassText = "振込";
          break;
        case NAK_CLAIM_CLASS_POSTAL:
          $ClaimClassText = "振込";
          break;
        case NAK_CLAIM_CLASS_CASH:
          $ClaimClassText = "集金";
          break;
        default:
          $ClaimClassText = "請求なし";
          break;
      }
      return $ClaimClassText;
    }
    public function GroupClassText($val){
      switch ($val) {
        case '0':
          $GroupClassText = "団体組織";
          break;
        case '1':
          $GroupClassText = "一般組織";
          break;
        case '2':
          $GroupClassText = "グループ";
          break;
        case '3':
          $GroupClassText = "チーム";
          break;
        default:
          # code...
          break;
      }
      return $GroupClassText;
    }
    public function FillEntrustList($ENTRUST_CD){
      $tg_id = $GLOBALS['tg_id'];
      $post_id = $GLOBALS['postid'];
      $url = URL_API.'Setting/FillEntrustList?tg_id='.$tg_id;
      $FillEntrustList = get_api_common($post_id, $url, "", "GET");
      foreach ($FillEntrustList as $key => $value) {
        $selected = ($ENTRUST_CD == $value->ENTRUST_CD)?"selected":"";
        echo '<option '.$selected.' value='.$value->ENTRUST_CD.'>'.$value->ENTRUST_NM.'</option>';
      }
    }
    public function GetAddMarketingItem($post_id, $AddNo, $val, $tg_id){
      $post_id = $GLOBALS['postid'];
      $url = URL_API.'Setting/GetAddMarketingItem?tg_id='.$tg_id;
      $GetAddMarketingItem = get_api_common($post_id, $url, "", "GET");
      $arrField = array("text", "checkbox", "radio");
      if($GetAddMarketingItem):
        foreach ($GetAddMarketingItem as $key => $item) {
          if($item->FIELD_NM == $AddNo){
            if(in_array($item->INPUT_TYPE, $arrField)){
              if($item->INPUT_TYPE == "text"){
                echo "<input size='60' type='".$item->INPUT_TYPE."' name='".$item->FIELD_NM."' value='".$val."' maxlength='".$item->MAX_LENGTH."'>";
              }elseif($item->INPUT_TYPE == "radio" || $item->INPUT_TYPE == "checkbox"){
                $arrValue = $item->OPTION_NAME;
                $arrValue = explode("|", $arrValue);
                foreach ($arrValue as $k => $v) {
                  echo '<input name="'.$item->FIELD_NM.'" type="'.$item->INPUT_TYPE.'" value="'.$k.'">'.$v;
                }
              }
            }
            elseif($item->INPUT_TYPE == "select"){
              echo '<select name="'.$item->FIELD_NM.'">';
                $arrValue = $item->OPTION_NAME;
                $arrValue = explode("|", $arrValue);
                foreach ($arrValue as $key => $value) {
                  echo "<option value='".$key."'>".$value."</option>";
                }
              echo '</select>';
            }
            elseif($item->INPUT_TYPE == "textarea"){
              echo "<textarea cols='58' rows='17' name='".$item->FIELD_NM."' maxlength='".$item->MAX_LENGTH."'>".$val."</textarea>";
            }
          }
        }
      endif;
    }
    public function CheckRegistMlmagaGId($post_id, $tg_id, $p_id){
      $url = URL_API.'Setting/CheckRegistMlmagaGId?tg_id='.$tg_id.'&p_id='.$p_id;
      $CheckRegistMlmagaGId = get_api_common($post_id, $url, "", "GET");
      return $CheckRegistMlmagaGId;  
    }
    public function GetMakePid($post_id, $tg_id){
      $url = URL_API.'Setting/GetMakePid?tg_id='.$tg_id;
      $GetMakePid = get_api_common($post_id, $url, "", "GET");
      $pId = '';
      if($GetMakePid){
        $pId = $GetMakePid->P_ID;
      }
      return $pId;
    }
    public function GetMakeGid($post_id, $tg_id){
      $url = URL_API.'Setting/GetMakeGid?tg_id='.$tg_id;
      $GetMakeGid = get_api_common($post_id, $url, "", "GET");
      $gId = '';
      if($GetMakeGid){
        $gId = $GetMakeGid->G_ID;
      }
      return $gId;
    }
    public function SelectDispName($name){
      switch ($name) {
        case "G_G_ID": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織ＩＤ")), "組織ＩＤ");break;
        case "G_NAME": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織名")), "組織名");break;
        case "G_KANA": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織フリガナ")), "組織フリガナ");break;
        case "G_NAME_EN": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織名英語表記")), "組織名英語表記");break;
        case "G_ADR_EN": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織住所　英語表記")), "組織住所　英語表記");break;
        case "G_USER_ID": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("団体管理組織ＩＤ")), "団体管理組織ＩＤ"); break;
        case "G_SNAME": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織名略称")), "組織名略称");break;
        case "G_CATEGORY_CODE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("業種コード")), "業種コード");break;
        case "G_CATEGORY": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("業種")), "業種");break;
        case "G_NAME_KN": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織フリガナ")), "組織フリガナ");break;
        case "G_NAME_AN": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織名略称")), "組織名略称");break;
        case "G_INDUSTRY_CD": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("業種コード")), "業種コード");break;
        case "G_INDUSTRY_NM": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("業種")), "業種");break;
        case "G_URL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織ＵＲＬ")), "組織ＵＲＬ");break;
        case "G_P_URL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織携帯ＵＲＬ")), "組織携帯ＵＲＬ");break;
        case "G_EMAIL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織Ｅ－ＭＡＩＬ")), "組織Ｅ－ＭＡＩＬ");break;
        case "G_EMAIL2": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織Ｅ－ＭＡＩＬ再入力")), "組織Ｅ－ＭＡＩＬ再入力");break;
        case "G_CC_EMAIL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織追加送信先Ｅ－ＭＡＩＬ")), "組織追加送信先Ｅ－ＭＡＩＬ");break;
        case "G_TEL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織ＴＥＬ")), "組織ＴＥＬ");break;
        case "G_FAX": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織ＦＡＸ")), "組織ＦＡＸ");break;
        case "G_FOUND_DATE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("設立年月日")), "設立年月日");break;
        case "G_SETTLE_MONTH": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("決算月")), "決算月");break;
        case "G_CAPITAL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("資本金")), "資本金");break;
        case "G_REPRESENTATIVE_NM": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("代表者")), "代表者");break;
        case "G_REPRESENTATIVE_KN": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("代表者フリガナ")), "代表者フリガナ");break;
        case "G_POST": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織〒")), "組織〒");break;
        case "G_STA": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織都道府県")), "組織都道府県");break;
        case "G_ADR": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織住所１")), "組織住所１");break;
        case "G_ADR2": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織住所２")), "組織住所２");break;
        case "G_ADR3": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織住所３")), "組織住所３");break;
        case "G_IMG": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("画像ファイル名")), "画像ファイル名");break;
        case "G_APPEAL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織アピール")), "組織アピール");break;
        case "G_LOGO": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("ロゴ画像ファイル名")), "ロゴ画像ファイル名");break;
        case "G_FREE01": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目１")), "組織自由項目１");break;
        case "G_FREE02": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目２")), "組織自由項目２");break;
        case "G_FREE03": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目３")), "組織自由項目３");break;
        case "G_FREE04": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目４")), "組織自由項目４");break;
        case "G_FREE05": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目５")), "組織自由項目５");break;
        case "G_FREE06": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目６")), "組織自由項目６");break;
        case "G_FREE07": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目７")), "組織自由項目７");break;
        case "G_FREE08": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目８")), "組織自由項目８");break;
        case "G_FREE09": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目９")), "組織自由項目９");break;
        case "G_FREE10": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目１０")), "組織自由項目１０");break;
        case "G_FREE11": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目１１")), "組織自由項目１１");break;
        case "G_FREE12": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目１２")), "組織自由項目１２");break;
        case "G_FREE13": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目１３")), "組織自由項目１３");break;
        case "G_FREE14": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目１４")), "組織自由項目１４");break;
        case "G_FREE15": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目１５")), "組織自由項目１５");break;
        case "G_FREE16": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目１６")), "組織自由項目１６");break;
        case "G_FREE17": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目１７")), "組織自由項目１７");break;
        case "G_FREE18": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目１８")), "組織自由項目１８");break;
        case "G_FREE19": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目１９")), "組織自由項目１９");break;
        case "G_FREE20": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目２０")), "組織自由項目２０");break;
        case "G_FREE21": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目２１")), "組織自由項目２１");break;
        case "G_FREE22": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目２２")), "組織自由項目２２");break;
        case "G_FREE23": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目２３")), "組織自由項目２３");break;
        case "G_FREE24": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目２４")), "組織自由項目２４");break;
        case "G_FREE25": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目２５")), "組織自由項目２５");break;
        case "G_FREE26": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目２６")), "組織自由項目２６");break;
        case "G_FREE27": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目２７")), "組織自由項目２７");break;
        case "G_FREE28": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目２８")), "組織自由項目２８");break;
        case "G_FREE29": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目２９")), "組織自由項目２９");break;
        case "G_FREE30": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織自由項目３０")), "組織自由項目３０");break;
        case "G_BANK_CD": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織銀行コード")), "組織銀行コード");break;
        case "G_BRANCH_CD": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織支店コード")), "組織支店コード");break;break;
        case "G_ACCAUNT_TYPE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織科目")), "組織科目");break;
        case "G_ACCOUNT_NO": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織口座番号")), "組織口座番号");break;
        case "G_ACCAUNT_NM": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織口座名義")), "組織口座名義");break;
        case "G_CUST_NO": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織顧客番号")), "組織顧客番号");break;
        case "G_SAVINGS_CD": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織貯金記号")), "組織貯金記号");break;
        case "G_SAVINGS_NO": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織貯金番号")), "組織貯金番号");break;
        case "P_P_ID": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人ID")), "個人ID");break;
        case "P_PASSWORD": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人パスワード")), "個人パスワード");break;
        case "P_PASSWORD2": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人パスワード再入力")), "個人パスワード再入力");break;
        case "P_C_NAME": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("氏名")), "氏名");break;
        case "P_C_NAME_EN": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle('氏名　英語表記')), '氏名　英語表記'); break;
        case "P_C_KANA": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("氏名フリガナ")), "氏名フリガナ");break;
        case "P_LNG_MODE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人住所　英語表記")), "個人住所　英語表記");break;
        case "G_LNG_MODE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織名・住所の表記")), "組織名・住所の表記");break;
        case "M_CONTACT_LNG_MODE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先氏名・住所の表記")), "連絡先氏名・住所の表記");break;
        case "M_BILLING_LNG_MODE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先氏名・住所の表記")), "請求先氏名・住所の表記");break;
        case "P_C_SEX": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("性別")), "性別");break;
        case "P_C_URL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人ＵＲＬ")), "個人ＵＲＬ");break;
        case "P_C_EMAIL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人Ｅ－ＭＡＩＬ")), "個人Ｅ－ＭＡＩＬ");break;
        case "P_C_CC_EMAIL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人追加送信先Ｅ－ＭＡＩＬ")), "個人追加送信先Ｅ－ＭＡＩＬ");break;
        case "P_C_TEL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人ＴＥＬ")), "個人ＴＥＬ");break;
        case "P_C_FAX": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人ＦＡＸ")), "個人ＦＡＸ");break;
        case "P_C_PTEL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人携帯ＴＥＬ")), "個人携帯ＴＥＬ");break;
        case "P_C_PMAIL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("携帯メール")), "携帯メール");break;
        case "P_C_PMAIL2": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("携帯メール再入力")), "携帯メール再入力");break;
        case "P_C_POST": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人〒")), "個人〒");break;
        case "P_C_STA": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人都道府県")), "個人都道府県");break;
        case "P_C_ADDRESS": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人住所１")), "個人住所１");break;
        case "P_C_ADDRESS2": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人住所２")), "個人住所２");break;
        case "P_C_ADDRESS3": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人住所３")), "個人住所３");break;
        case "P_C_ADR_EN": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle('個人住所　英語表記')), '個人住所　英語表記');break;
        case "P_C_IMG": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("画像ファイル名")), "画像ファイル名");break;
        case "P_C_IMG2": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("画像ファイル名２")), "画像ファイル名２");break;
        case "P_C_IMG3": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("画像ファイル名３")), "画像ファイル名３");break;
        case "P_C_APPEAL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人アピール")), "個人アピール");break;
        case "P_C_FREE1": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目１")), "個人自由項目１");break;
        case "P_C_FREE2": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目２")), "個人自由項目２");break;
        case "P_C_FREE3": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目３")), "個人自由項目３");break;
        case "P_C_FREE4": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目４")), "個人自由項目４");break;
        case "P_C_FREE5": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目５")), "個人自由項目５");break;
        case "P_C_FREE6": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目６")), "個人自由項目６");break;
        case "P_C_FREE7": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目７")), "個人自由項目７");break;
        case "P_C_FREE8": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目８")), "個人自由項目８");break;
        case "P_C_FREE9": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目９")), "個人自由項目９");break;
        case "P_C_FREE10": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目１０")), "個人自由項目１０");break;
        case "P_C_FREE11": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目１１")), "個人自由項目１１");break;
        case "P_C_FREE12": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目１２")), "個人自由項目１２");break;
        case "P_C_FREE13": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目１３")), "個人自由項目１３");break;
        case "P_C_FREE14": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目１４")), "個人自由項目１４");break;
        case "P_C_FREE15": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目１５")), "個人自由項目１５");break;
        case "P_C_FREE16": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目１６")), "個人自由項目１６");break;
        case "P_C_FREE17": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目１７")), "個人自由項目１７");break;
        case "P_C_FREE18": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目１８")), "個人自由項目１８");break;
        case "P_C_FREE19": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目１９")), "個人自由項目１９");break;
        case "P_C_FREE20": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目２０")), "個人自由項目２０");break;
        case "P_C_FREE21": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目２１")), "個人自由項目２１");break;
        case "P_C_FREE22": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目２２")), "個人自由項目２２");break;
        case "P_C_FREE23": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目２３")), "個人自由項目２３");break;
        case "P_C_FREE24": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目２４")), "個人自由項目２４");break;
        case "P_C_FREE25": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目２５")), "個人自由項目２５");break;
        case "P_C_FREE26": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目２６")), "個人自由項目２６");break;
        case "P_C_FREE27": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目２７")), "個人自由項目２７");break;
        case "P_C_FREE28": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目２８")), "個人自由項目２８");break;
        case "P_C_FREE29": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目２９")), "個人自由項目２９");break;
        case "P_C_FREE30": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目３０")), "個人自由項目３０");break;
        case "P_C_FREE31": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目３１")), "個人自由項目３１");break;
        case "P_C_FREE32": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目３２")), "個人自由項目３２");break;
        case "P_C_FREE33": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目３３")), "個人自由項目３３");break;
        case "P_C_FREE34": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目３４")), "個人自由項目３４");break;
        case "P_C_FREE35": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目３５")), "個人自由項目３５");break;
        case "P_C_FREE36": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目３６")), "個人自由項目３６");break;
        case "P_C_FREE37": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目３７")), "個人自由項目３７");break;
        case "P_C_FREE38": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目３８")), "個人自由項目３８");break;
        case "P_C_FREE39": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目３９")), "個人自由項目３９");break;
        case "P_C_FREE40": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目４０")), "個人自由項目４０");break;
        case "P_C_FREE41": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目４１")), "個人自由項目４１");break;
        case "P_C_FREE42": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目４２")), "個人自由項目４２");break;
        case "P_C_FREE43": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目４３")), "個人自由項目４３");break;
        case "P_C_FREE44": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目４４")), "個人自由項目４４");break;
        case "P_C_FREE45": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目４５")), "個人自由項目４５");break;
        case "P_C_FREE46": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目４６")), "個人自由項目４６");break;
        case "P_C_FREE47": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目４７")), "個人自由項目４７");break;
        case "P_C_FREE48": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目４８")), "個人自由項目４８");break;
        case "P_C_FREE49": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目４９")), "個人自由項目４９");break;
        case "P_C_FREE50": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人自由項目５０")), "個人自由項目５０");break;
        case "P_GROUP_ENABLE_FLG": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("組織変更許可フラグ")), "組織変更許可フラグ");break;
        case "P_MEETING_NM_DISP": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("会議室氏名表示区分")), "会議室氏名表示区分");break;
        case "P_HANDLE_NM": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("会議室ニックネーム")), "会議室ニックネーム");break;
        case "P_MEETING_NM_MK": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("会議室公開ネーム表示マーク")), "会議室公開ネーム表示マーク");break;
        case "P_G_ID": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("所属組織")), "所属組織");break;
        case "S_AFFILIATION_NAME": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("所属")), "所属");break;
        case "P_O_AFFILIATION": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("所属")), "所属");break;
        case "S_OFFICIAL_POSITION": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("役職")), "役職");break;
        case "G_REPRESENTATIVE_OP": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("代表者役職")), "代表者役職");break;
        case "P_O_OFFICIAL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("役職")), "役職");break;
        case "P_CARD_OPEN": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("公開設定")), "公開設定");break;
        case "P_LOGIN_LOCK_FLG": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("携帯自動ログイン")), "携帯自動ログイン");break;
        case "P_P_URL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("プライベート　ＵＲＬ")), "プライベート　ＵＲＬ");break;
        case "P_P_EMAIL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("プライベート　Ｅ－ＭＡＩＬ")), "プライベート　Ｅ－ＭＡＩＬ");break;
        case "P_P_EMAIL2": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("E-Mail再入力")), "E-Mail再入力");break;
        case "P_P_CC_EMAIL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("プライベート　追加送信先Ｅ－ＭＡＩＬ")), "プライベート　追加送信先Ｅ－ＭＡＩＬ");break;
        case "P_P_TEL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("プライベート　ＴＥＬ")), "プライベート　ＴＥＬ");break;
        case "P_P_FAX": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("プライベート　ＦＡＸ")), "プライベート　ＦＡＸ");break;
        case "P_P_PTEL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("プライベート　ＴＥＬ")), "プライベート　ＴＥＬ");break;
        case "P_P_PMAIL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("プライベート　携帯メール")), "プライベート　携帯メール");break;
        case "P_P_PMAIL2": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("携帯メール再入力")), "携帯メール再入力");break;
        case "P_P_POST": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("プライベート　〒")), "プライベート　〒");break;
        case "P_P_STA": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("プライベート　都道府県")), "プライベート　都道府県");break;
        case "P_P_ADDRESS": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("プライベート　住所１")), "プライベート　住所１");break;
        case "P_P_ADDRESS2": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("プライベート　住所２")), "プライベート　住所２");break;
        case "P_P_ADR3": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("住所３")), "住所３");break;
        case "P_P_ADR_EN": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("プライベート　住所　英語表記")), "プライベート　住所　英語表記");break;
        case "P_P_ADR": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("プライベート住所１")), "住所１");break;
        case "P_P_ADR2": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("プライベート　住所２")), "プライベート　住所２");break;
        case "P_P_BIRTH": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("生年月日")), "生年月日");break;
        case "P_PRIVATE_OPEN": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("プライベート情報公開")), "プライベート情報公開");break;
        case "P_BANK_CD": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人銀行コード")), "個人銀行コード");break;
        case "P_BRANCH_CD": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人支店コード")), "個人支店コード");break;
        case "P_ACCAUNT_TYPE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人科目")), "個人科目");break;
        case "P_ACCOUNT_NO": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人口座番号")), "個人口座番号");break;
        case "P_ACCOUNT_NM": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人口座名義")), "個人口座名義");break;
        case "P_CUST_NO": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人顧客番号")), "個人顧客番号");break;
        case "P_SAVINGS_CODE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人貯金記号")), "個人貯金記号");break;
        case "P_SAVINGS_NUMBER": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人貯金番号")), "個人貯金番号");break;
        case "M_LG_G_ID": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("グループＩＤ")), "グループＩＤ");break;
        case "M_MEMBER_TYPE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡手段")), "連絡手段");break;
        case "FAX_TIMEZONE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("ＦＡＸ送信時間帯")), "ＦＡＸ送信時間帯");break;
        case "M_USER_ID": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理番号")), "管理番号");break;
        case "M_RECOMMEND_P_ID": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("紹介者")), "紹介者");break;
        case "M_RECOMMEND_P_ID2": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("紹介者２")), "紹介者２");break;
        case "M_RECOMMEND_P_ID3": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("紹介者３")), "紹介者３");break;
        case "M_RECOMMEND_P_ID4": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("紹介者４")), "紹介者４");break;
        case "M_RECOMMEND_P_ID5": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("紹介者５")), "紹介者５");break;
        case "M_DISP_LIST": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("会員一覧表示設定")), "会員一覧表示設定");break;
        case "M_DISP_DETAIL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("会員一覧からの詳細画面表示設定")), "会員一覧からの詳細画面表示設定");break;
        case "M_DISP_MARKETING": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("マーケティング情報表示設定")), "マーケティング情報表示設定");break;
        case "P_SAVINGS_CD": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人貯金記号")), "個人貯金記号");break;
        case "P_SAVINGS_NO": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("個人貯金番号")), "個人貯金番号");break;
        case "M_LG_ID": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("グループＩＤ")), "グループＩＤ");break;
        case "M_CONTRACT_TYPE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡手段")), "連絡手段");break;
        case "M_FAX_TIMEZONE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("ＦＡＸ送信時間帯")), "ＦＡＸ送信時間帯");break;
        case "M_RECOMMEND_P_ID": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("紹介者")), "紹介者");break;
        case "M_X_COMMENT": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("コメント")), "コメント");break;
        case "M_TAX_ACCOUNTANT01": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("税理士１")), "税理士１");break;
        case "M_TAX_ACCOUNTANT02": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("税理士２")), "税理士２");break;
        case "M_TAX_ACCOUNTANT03": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("税理士３")), "税理士３");break;
        case "M_TAX_ACCOUNTANT04": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("税理士４")), "税理士４");break;
        case "M_TAX_ACCOUNTANT05": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("税理士５")), "税理士５");break;
        case "M_TAX_ACCOUNTANT06": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("税理士６")), "税理士６");break;
        case "M_TAX_ACCOUNTANT07": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("税理士７")), "税理士７");break;
        case "M_TAX_ACCOUNTANT08": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("税理士８")), "税理士８");break;
        case "M_TAX_ACCOUNTANT09": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("税理士９")), "税理士９");break;
        case "M_TAX_ACCOUNTANT10": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("税理士１０")), "税理士１０");break;
        case "M_FREE1": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目１")), "管理自由項目１");break;
        case "M_FREE2": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目２")), "管理自由項目２");break;
        case "M_FREE3": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目３")), "管理自由項目３");break;
        case "M_FREE4": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目４")), "管理自由項目４");break;
        case "M_FREE5": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目５")), "管理自由項目５");break;
        case "M_FREE6": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目６")), "管理自由項目６");break;
        case "M_FREE7": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目７")), "管理自由項目７");break;
        case "M_FREE8": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目８")), "管理自由項目８");break;
        case "M_FREE9": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目９")), "管理自由項目９");break;
        case "M_FREE10": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目１０")), "管理自由項目１０");break;
        case "M_FREE11": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目１１")), "管理自由項目１１");break;
        case "M_FREE12": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目１２")), "管理自由項目１２");break;
        case "M_FREE13": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目１３")), "管理自由項目１３");break;
        case "M_FREE14": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目１４")), "管理自由項目１４");break;
        case "M_FREE15": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目１５")), "管理自由項目１５");break;
        case "M_FREE16": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目１６")), "管理自由項目１６");break;
        case "M_FREE17": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目１７")), "管理自由項目１７");break;
        case "M_FREE18": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目１８")), "管理自由項目１８");break;
        case "M_FREE19": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目１９")), "管理自由項目１９");break;
        case "M_FREE20": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目２０")), "管理自由項目２０");break;
        case "M_FREE21": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目２１")), "管理自由項目２１");break;
        case "M_FREE22": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目２２")), "管理自由項目２２");break;
        case "M_FREE23": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目２３")), "管理自由項目２３");break;
        case "M_FREE24": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目２４")), "管理自由項目２４");break;
        case "M_FREE25": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目２５")), "管理自由項目２５");break;
        case "M_FREE26": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目２６")), "管理自由項目２６");break;
        case "M_FREE27": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目２７")), "管理自由項目２７");break;
        case "M_FREE28": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目２８")), "管理自由項目２８");break;
        case "M_FREE29": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目２９")), "管理自由項目２９");break;
        case "M_FREE30": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目３０")), "管理自由項目３０");break;
        case "M_FREE31": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目３１")), "管理自由項目３１");break;
        case "M_FREE32": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目３２")), "管理自由項目３２");break;
        case "M_FREE33": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目３３")), "管理自由項目３３");break;
        case "M_FREE34": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目３４")), "管理自由項目３４");break;
        case "M_FREE35": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目３５")), "管理自由項目３５");break;
        case "M_FREE36": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目３６")), "管理自由項目３６");break;
        case "M_FREE37": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目３７")), "管理自由項目３７");break;
        case "M_FREE38": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目３８")), "管理自由項目３８");break;
        case "M_FREE39": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目３９")), "管理自由項目３９");break;
        case "M_FREE40": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目４０")), "管理自由項目４０");break;
        case "M_FREE41": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目４１")), "管理自由項目４１");break;
        case "M_FREE42": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目４２")), "管理自由項目４２");break;
        case "M_FREE43": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目４３")), "管理自由項目４３");break;
        case "M_FREE44": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目４４")), "管理自由項目４４");break;
        case "M_FREE45": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目４５")), "管理自由項目４５");break;
        case "M_FREE46": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目４６")), "管理自由項目４６");break;
        case "M_FREE47": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目４７")), "管理自由項目４７");break;
        case "M_FREE48": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目４８")), "管理自由項目４８");break;
        case "M_FREE49": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目４９")), "管理自由項目４９");break;
        case "M_FREE50": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("管理自由項目５０")), "管理自由項目５０");break;
        case "M_STATUS": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("状態")), "状態");break;
        case "M_ADMISSION_DATE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("入会年月日")), "入会年月日");break;
        case "M_WITHDRAWAL_DATE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("退会年月日")), "退会年月日");break;
        case "M_CHANGE_DATE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("異動年月日")), "異動年月日");break;
        case "M_CHANGE_REASON": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("異動理由")), "異動理由");break;
        case "M_MOVEOUT_DATE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("転出年月日")), "転出年月日");break;
        case "M_MOVEOUT_NOTE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("転出コメント")), "転出コメント");break;
        case "M_MOVEIN_DATE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("転入年月日")), "転入年月日");break;
        case "M_MOVEIN_NOTE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("転入コメント")), "転入コメント");break;
        case "M_ADMISSION_REASON": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("入会理由")), "入会理由");break;
        case "M_WITHDRAWAL_REASON": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("退会理由")), "退会理由");break;
        case "M_CLAIM_CLS": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求区分")), "請求区分");break;
        case "M_FEE_RANK": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("会費ランク")), "会費ランク");break;
        case "M_CLAIM_CYCLE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求サイクル")), "請求サイクル");break;
        case "M_SETTLE_MONTH": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("会員決算月")), "会員決算月");break;
        case "M_FEE_MEMO": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("会費メモ")), "会費メモ");break;
        case "M_ENTRUST_CD": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("委託者コード")), "委託者コード");break;
        case "M_BANK_CD": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("銀行コード")), "銀行コード");break;
        case "M_BRANCH_CD": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("支店コード")), "支店コード");break;
        case "M_ACCAUNT_TYPE": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("科目")), "科目");break;
        case "M_ACCOUNT_NO": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("口座番号")), "口座番号");break;
        case "M_ACCOUNT_NM": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("口座名義")), "口座名義");break;
        case "M_CUST_NO": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("顧客番号")), "顧客番号");break;
        case "M_SAVINGS_CD": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("貯金記号")), "貯金記号");break;
        case "M_SAVINGS_NO": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("貯金番号")), "貯金番号");break;
        // case "M_CLAIMDEST": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先指定")), "請求先指定");break;
        case "M_BILLING_ID": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先指定")), "請求先指定");break;
        case "M_BILLING_G_NAME": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先組織名")), "請求先組織名");break;
        case "M_BILLING_G_KANA": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先組織名フリガナ")), "請求先組織名フリガナ");break;
        case "M_BILLING_G_NAME_EN": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先組織名英語表記")), "連絡先組織名英語表記");break;
        case "M_BILLING_C_NAME": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先氏名")), "請求先氏名");break;
        case "M_BILLING_C_NAME_KN": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先氏名フリガナ")), "請求先氏名フリガナ");break;
        case "M_BILLING_AFFILIATION": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先所属")), "請求先所属");break;
        case "M_BILLING_POSITION": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先役職")), "請求先役職");break;
        case "M_BILLING_EMAIL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先Ｅ－ＭＡＩＬ")), "請求先Ｅ－ＭＡＩＬ");break;
        case "M_BILLING_EMAIL2": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先E-MAIL再入力")), "請求先E-MAIL再入力");break;
        case "M_BILLING_CC_EMAIL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先追加送信先Ｅ－ＭＡＩＬ")), "請求先追加送信先Ｅ－ＭＡＩＬ");break;
        case "M_BILLING_TEL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先ＴＥＬ")), "請求先ＴＥＬ");break;
        case "M_BILLING_FAX": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先ＦＡＸ")), "請求先ＦＡＸ");break;
        case "M_BILLING_POST": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先〒")), "請求先〒");break;
        case "M_BILLING_STA": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先都道府県")), "請求先都道府県");break;
        case "M_BILLING_ADR": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先住所１")), "請求先住所１");break;
        case "M_BILLING_ADR2": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先住所２")), "請求先住所２");break;
        case "M_BILLING_ADR3": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先住所３")), "請求先住所３");break;
        case "M_BILLING_ADR_EN": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("請求先住所　英語表記")), "請求先住所　英語表記");break;
        case "M_CONTACT_ID": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先指定")), "連絡先指定");break;
        case "M_CONTACT_G_NAME": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先組織名")), "連絡先組織名");break;
        case "M_CONTACT_G_NAME_KN": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先組織名フリガナ")), "連絡先組織名フリガナ");break;
        case "M_CONTACT_G_NAME_EN": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先組織名英語表記")), "連絡先組織名英語表記");break;
        case "M_CONTACT_C_NAME": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先氏名　姓")), "連絡先氏名　姓");break;
        case "M_CO_C_KANA": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先氏名フリガナ　姓")), "連絡先氏名フリガナ　姓");break;
        case "M_CONTACT_AFFILIATION": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先所属")), "連絡先所属");break;
        case "M_CONTACT_POSITION": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先役職")), "連絡先役職");break;
        case "M_CONTACT_EMAIL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先Ｅ－ＭＡＩＬ")), "連絡先Ｅ－ＭＡＩＬ");break;
        case "M_CONTACT_EMAIL2": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先Ｅ－ＭＡＩＬ")), "連絡先Ｅ－ＭＡＩＬ");break;
        case "M_CONTACT_CC_EMAIL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先追加送信先Ｅ－ＭＡＩＬ")), "連絡先追加送信先Ｅ－ＭＡＩＬ");break;
        case "M_CONTACT_TEL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先ＴＥＬ")), "連絡先ＴＥＬ");break;
        case "M_CONTACT_FAX": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先ＦＡＸ")), "連絡先ＦＡＸ");break;
        case "M_CONTACT_POST": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先〒")), "連絡先〒");break;
        case "M_CONTACT_STA": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先都道府県")), "連絡先都道府県");break;
        case "M_CONTACT_ADR": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先住所１")), "連絡先住所１");break;
        case "M_CONTACT_ADR2": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先住所２")), "連絡先住所２");break;
        case "M_CONTACT_ADR3": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先住所３")), "連絡先住所３");break;
        case "M_CONTACT_ADR_EN": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("連絡先住所英語表記")), "連絡先住所英語表記");break;
        case "ATENA_SUU": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("発行部数")), "発行部数");break;
        case "M_RECEIVE_INFO_FAX": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("ＦＡＸによる会からの<br>案内を受け取り設定")), "ＦＡＸによる会からの<br>案内を受け取り設定");break;
        case "M_RECEIVE_INFO_MAIL": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("メールによる会からの<br>案内を受け取り設定")), "メールによる会からの<br>案内を受け取り設定");break;
        case "MLMAGA_FLG": $SelectDispName = $this->convertNvl(str_replace("<br>", "",$this->renderTitle("メルマガ登録設定")), "メルマガ登録設定");break;
        default:
         break;
      }
      return '"'.$SelectDispName.'"';
    }
    public function convertNvl($before, $after){
      $rs = '';
      if($before != NULL || $before != ''){
        $rs = $before;
      }else{
        $rs = $after;
      }
      if(!empty($GLOBALS['field_name_setting'])){
        $rs = $GLOBALS['field_name_setting'];
      }
      return $rs;
    }
    public function paginatesCategorys($event_count, $current_page, $per_page){
      $pagination = common_pagination($event_count,$current_page, $per_page);
      return $pagination;
    }
    public function renderTitle($name = ''){
      $arTitle = $GLOBALS['arTitle'];
      foreach ($arTitle as $key => $item) {
        if($item->BEFORE_NAME == $name){
          return $item->AFTER_NAME;
        }
      }
    }
    public function renderTitleEnd($arTitle, $name = ''){
      foreach ($arTitle as $key => $item) {
        if($item->BEFORE_NAME == $name){
          return $item->AFTER_NAME;
        }
      }
    }
    public function GetSysConfigData($post_id, $tg_id){
      $arr = array();
      $arr['def_list_nodisp']      = "1";
      $arr['def_list_nolink']      = "1";
      $arr['def_biz_info']         = "1";
      $arr['def_marketing_flg']    = "1";      
      $url = URL_API.'Setting/GetSysConfigData?tg_id='.$tg_id;
      $GetSysConfigData = get_api_common($post_id, $url, "", "GET");
      if($GetSysConfigData){
        $GetSysConfigData = $GetSysConfigData;
        if(!empty($GetSysConfigData->DEF_LIST_NODISP))
          $arr['def_list_nodisp']      = $GetSysConfigData->DEF_LIST_NODISP;
        if(!empty($GetSysConfigData->DEF_LIST_NOLINK))
          $arr['def_list_nolink']      = $GetSysConfigData->DEF_LIST_NOLINK;
        if(!empty($GetSysConfigData->DEF_BIZ_INFO))
          $arr['def_biz_info']         = $GetSysConfigData->DEF_BIZ_INFO;
        if(!empty($GetSysConfigData->DEF_MARKETING_FLG))
          $arr['def_marketing_flg']    = $GetSysConfigData->DEF_MARKETING_FLG;
      }
      return $arr;
    }
    public function fGetFeeData($post_id, $tg_id){
      $p_id = $GLOBALS['p_id'];
      $g_id = $GLOBALS['g_id'];
      $url = URL_API.'Setting/fGetFeeData?tg_id='.$tg_id.'&g_id='.$g_id.'&p_id='.$p_id;
      $fGetFeeData = get_api_common($post_id, $url, "", "GET");
      return $fGetFeeData->value;
    }
    public function editDate($theDate,$mode){
      $theYear = '';
      $theMonth = '';
      $editDate = '';
      $h = '';
      $m = '';
      if( trim($theDate) == "" ){
        $editDate = "";
        return $editDate;
      }
    
      if( is_null($theDate) ){
        $editDate = "";
        return $editDate;
      }
    
      if( DateTime::createFromFormat('Y-m-d H:i:s', $theDate) == FALSE ){
        if( strpos($theDate, "/") > 0) {
          $theYear  = explode($theDate,"/")[0];
          $theMonth = explode($theDate,"/")[1];
          $theDay   = explode($theDate,"/")[2];
        }else {
          $theYear  = substr($theDate, 0, 4);
          $theMonth = substr($theDate, 5, 2);
          $theDay   = substr($theDate,-2);
        }
      }
      else{
          $theDate_temp = DateTime::createFromFormat("Y-m-d H:i:s", $theDate);
          $theYear  = $theDate_temp->format("Y");
          $theMonth = $theDate_temp->format("m");
          $theDay   = $theDate_temp->format("d");
          $h = $theDate_temp->format("H");
          $m = $theDate_temp->format("i");
      }
    
      if($mode == 3 && strlen($theDate) < 14) {
        $mode = 4;
      }
      switch ($mode) {
        case 0:
          $editDate = substr("0000".$theYear, -4)."/".substr("0".$theMonth,-2)."/".substr("0".$theDay,-2);
          break;
        case 1:
          $editDate = substr("0000".$theYear, -4).substr("0".$theMonth,-2).substr("0".$theDay,-2);
          break;
        case 2:
          $editDate = substr("0000".$theYear, -4)."/".substr("0".$theMonth,-2)."/".substr("0".$theDay,-2)." ".substr("00".$h, -2).":".substr("00".$m, -2);
          break;
        case 3:
          $editDate = substr("0000".$theYear, -4)."年".substr("0".$theMonth,-2)."月".substr("0".$theDay,-2)."日";
          break;
        default:
          $editDate = "";
          break;
      }
      return $editDate;
    }
    public function feeStatus($post_id, $tg_id, $p_id){
      $url = URL_API.'Setting/GetFeeData?tg_id='.$tg_id.'&p_id='.$p_id;
      $fGetFeeData = get_api_common($post_id, $url, "", "GET");
      return $fGetFeeData;
    }

    public function DeleteDataMember($post_id){
      $url = URL_API.'Member/DeleteDataMember';
      $arrBody = array(
        "TG_ID"=> (isset($_SESSION['arrSession']->TG_ID))?$_SESSION['arrSession']->TG_ID:"",
        "P_ID"=> (isset($_SESSION['arrSession']->P_ID))?$_SESSION['arrSession']->P_ID:"",
      );
      $DeleteDataMember = get_api_common($post_id, $url,$arrBody, "POST");
      return $DeleteDataMember;
    }

    public function rs_get_branch_list($post_id, $tg_id, $brank_code) {
        $url = URL_API."Setting/GetBranchInfo?tg_id=".$tg_id."&bank_nm=&bank_cd=".$brank_code;
        $rs = get_api_common($post_id, $url, "", 'GET');
        $rs = $rs->DATA;
        return $rs;
    }

    public function pdFillGroup($post_id,$pd, $affName, $offPos) {
      $url = URL_API."Member/FillGroup?p_id=".$pd->P_ID;
      $rs = get_api_common($post_id, $url, "", 'GET');
      $temp = "";
      if(!isset($rs->result)){
        if(!empty($rs)){
          foreach ($rs as $item) {
            $selected = ($pd->G_ID == $item->G_ID)?"selected":"";
            $temp .= "<option value='".$item->G_ID."' ".$selected.">".$item->TG_NAME."</option>";
          }
        }
      }
      return $temp;
    }

    public function getPageSlug($slug){
      $page_id = get_page_by_path($slug);
      $page_link = get_page_link($page_id); 
      $permalink_structure = get_option('permalink_structure');
      $alias = ($permalink_structure == NULL)?'&':"?";
      return $page_link.$alias;
    }
    public function convertFile($filePath)
    {
      $content = file_get_contents($filePath);
      return mb_convert_encoding($content, "UTF-8", "ASCII,JIS,UTF-8,eucJP-win,SJIS-win");
    }
    public function getNewTitle($arrTitle,$name = ''){
      $arTitle = $arrTitle;
      foreach ($arTitle as $key => $item) {
        if($item->BEFORE_NAME == $name){
          return $item->AFTER_NAME;
        }
      }
    }

    public function SendDeleteConfirmMail($post_id,$emailfrom,$emailto,$pcname, $pckana) {
      $url = URL_API."Member/SendDeleteConfirmMail";
      $arrBody = [
        "tg_id" => get_post_meta($post_id, "member_meta_group_id", true),
        "p_id" => (isset($_SESSION['arrSession']->P_ID))?$_SESSION['arrSession']->P_ID:"",
        "mailFrom" => $emailfrom,
        "pass_from" => "9wk5vGbLDr",
        "mailTo" => $emailto,
        "bcc" => "",
        "cc" => "",
        "pc_name" => $pcname,
        "pc_kana" => $pckana
      ];
      $rs = get_api_common($post_id, $url, $arrBody, 'POST');
      return $rs;
  }
  
  public function SendRegistMail($post_id,$tg_id,$p_id, $pcName, $pcEMail) {
    $url = URL_API."/Member/SendRegistMail?tg_id=$tg_id&pcName=$pcName&pcEMail=$pcEMail";
    $rs = get_api_common($post_id, $url, array(), 'GET');
    return $rs;
  }

  public function SendDeleteMail($post_id,$tg_id,$p_id, $pcName, $pcEMail) {
    $url = URL_API."/Member/SendDeleteMail?tg_id=$tg_id&p_id=$p_id&pcName=$pcName&pcEMail=$pcEMail";
    $rs = get_api_common($post_id, $url, array(), 'GET');
    return $rs;
  }

  public function SendMailMember($post_id,$tg_id,$p_id,$lg_id, $g_id,$flag_update) {
    $url = URL_API."Member/SendMailMember";
    if($flag_update){
      $ini_file = get_post_meta($post_id, "disp_merumaga_file_end", true);
    }else {
      $ini_file = get_post_meta($post_id, "disp_entry_file", true);
    }
    if(empty($ini_file))
      $disp_entry_filename = __ROOT__ . "/settingform/ini/disp_entry.ini";
    else 
      $disp_entry_filename = __ROOT__ . "/settingform/ini/".$ini_file;
    $objText = $this->convertFile($disp_entry_filename);
    $objText = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $objText);

    $arrBody = [
      "tg_id" => $tg_id,
      "p_id" => $p_id,
      "lg_id" => $lg_id,
      "g_id" => $g_id,
      "mail_subject" => ($flag_update)?get_post_meta($post_id, "mail_subject_end", true):get_post_meta($post_id, "mail_subject", true),
      "mail_body" => ($flag_update)?get_post_meta($post_id, "mail_body_end", true):get_post_meta($post_id, "mail_body", true),
      "dataIniFile" => $objText,
    ];
    $rs = get_api_common($post_id, $url, $arrBody, 'POST');
    return $rs;
  }

  public function SendMailAdmin($post_id,$tg_id,$p_id,$lg_id, $g_id, $g_chg, $mailTo,$flag_update) {
    $url = URL_API."Member/SendMailAdmin?tg_id=$tg_id&p_id=$p_id&lg_id=$lg_id&g_id=$g_id&g_chg=$g_chg&mailTo=$mailTo";
    if($flag_update){
      $ini_file = get_post_meta($post_id, "disp_merumaga_file_end", true);
    }else {
      $ini_file = get_post_meta($post_id, "disp_entry_file", true);
    }
    if(empty($ini_file))
      $disp_entry_filename = __ROOT__ . "/settingform/ini/disp_entry.ini";
    else 
      $disp_entry_filename = __ROOT__ . "/settingform/ini/".$ini_file;
    $objText = $this->convertFile($disp_entry_filename);
    $objText = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $objText);
    $arrBody = [
      "tg_id" => $tg_id,
      "p_id" => $p_id,
      "lg_id" => $lg_id,
      "g_id" => $g_id,
      "g_chg" => $g_chg,
      "mailTo" => $mailTo,
      "dataIniFile" => $objText,
      "dataBeforeUpdate" => ($flag_update)?$_SESSION['dataBeforeUpdate']:"",
    ];
    $rs = get_api_common($post_id, $url, $arrBody, 'POST');
    return $rs;
  }

  public function SendReleaseMail($post_id,$tg_id,$p_id,$lg_id, $g_id, $mailTo) {
    $url = URL_API."Member/SendReleaseMail?tg_id=$tg_id&p_id=$p_id&lg_id=$lg_id&g_id=$g_id&mailTo=$mailTo";
    $rs = get_api_common($post_id, $url, array(), 'GET');
    return $rs;
  }

  public static function logins($post_id, $arrBody) {
		$url = URL_API.'Personal/LogIn';
		$login = get_api_common($post_id, $url, $arrBody, "POST");
		return $login;
	}

  public function getItemColumn($item, $arr){
    foreach ($arr as $key => $v) {
      if($item == $v['column_id']){
        return $v['column_name'];
      }
    }
  }

  public function GetCategoryType($post_id) {
    $tg_id = get_post_meta($post_id, "member_meta_group_id", true);
    $url = URL_API."Setting/GetCategoryType?tg_id=$tg_id";
    $rs = get_api_common($post_id, $url, array(), 'GET');
    return $rs;
  }

  public function SetCategoryCode($post_id,$type,$major_code = "",$middle_code = "",$selected) {
    $tg_id = get_post_meta($post_id, "member_meta_group_id", true);
    $url = URL_API."Setting/SetCategoryCode?type=$type&major_code=$major_code&middle_code=$middle_code";
    $datas = get_api_common($post_id, $url, array(), 'GET');
    $rs = "";
    if(!empty($datas)){
      foreach($datas as $data){
        $rs .= '<option value="'.$data->CD.'" '.(($selected == $data->CD)?"selected":"").'>'.$data->CD.' : '.$data->NM.'</option>';
      }
    }
    return $rs;
  }

  public function SetDefContactBilllig($post_id) {
    $tg_id = get_post_meta($post_id, "member_meta_group_id", true);
    $url = URL_API."Member/SetDefContactBilllig?tg_id=".$tg_id;
    $datas = get_api_common($post_id, $url, array(), 'GET');
    return $datas;
  }
  
}
?>
