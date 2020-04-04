<?php
   final class researchController{
      /*
      * GMO Z.Com Runsystem
      * 2019/01/03
      * FUNC: List research
      */
      public static function lists($postid,$tg_id,$top_sta,$PattenNo) {
          $now_year = date('Y');
          $now_month = date('m');
          $current_page = (get_query_var('page') == 0)?1:get_query_var('page');
          $page_no = $current_page - 1;
          $keyword = isset($_POST['search_select'])?$_POST['search_select']:"";
          $columnSort = isset($_POST['sort'])?$_POST['sort']:"";
          $orderBy = isset($_POST['order'])?$_POST['order']:"";
          $year_date = isset($_POST['year_date'])?$_POST['year_date']:$now_year;
          $month_date = isset($_POST['month_date'])?$_POST['month_date']:$now_month;
          $p_id = research_PREFIX_P_ID.FUNC_LIST_research.$tg_id."_".$PattenNo;
          $arrBody = array(
            "tg_id" => $tg_id,
            "p_id" => $p_id,
            "g_id" => isset($_SESSION['arrSession']->G_ID)?$_SESSION['arrSession']->G_ID:"",
            "m_id" =>isset($_SESSION['arrSession']->M_ID)?$_SESSION['arrSession']->M_ID:"",
            "year" => $year_date,
            "month" => $month_date,
            "Keyword" => $keyword,
            "CollumOrder" => $columnSort,
            "ORDER_BY" => $orderBy,
            "PattenNo" => $PattenNo,
            "Sta" => $top_sta,
            "FUNC_ID" => FUNC_LIST_research
          );
          $per_page = get_post_meta($postid, "nak-research-per-page",true);
          $urlresearchList = research_URL_API.'Research/ListResearch?page_no='.$page_no.'&per_page='.$per_page;
          $listresearchs = research_get_api_common($postid, $urlresearchList, $arrBody, "POST");
          return $listresearchs;
      }

      public function listGetResStatus($st, $RES_START_DATE, $RES_START_TIME,$RES_END_DATE,$RES_END_TIME){
         $reStatus = "";
         $start_date = date("Y/m/d",strtotime($RES_START_DATE))." ".$RES_START_TIME;
         $end_date = date("Y/m/d",strtotime($RES_END_DATE))." ".$RES_END_TIME;
         
         
         if($st != 2){ 
            if(date("Y/m/d",$start_date) > Date("Y/m/d") )
               $reStatus = "受付前";
            else 
               if( date("Y/m/d",$end_date) < Date("Y/m/d")) 
                  $reStatus = "受付終了";
               else 
                  $reStatus = "受付中";
         }
         else 
            $reStatus = "受付中断";
         return $reStatus;
      }

      public function sedai_disp_umu($RES_SEDAI_DISP_UMU,$RES_MEMBERREG_OPEN){
         if($RES_SEDAI_DISP_UMU == "2" && $RES_MEMBERREG_OPEN == "0")
            return "一般のみ";
         else 
            if($RES_SEDAI_DISP_UMU == "1" && $RES_MEMBERREG_OPEN == "0")
               return "オープン";
            else 
               if($RES_SEDAI_DISP_UMU == "0" && $RES_MEMBERREG_OPEN == "1")
                  return "対象会員のみ";
               else return "会員";
      }

      /*
      * GMO Z.Com Runsystem
      * 2019/01/03
      * FUNC: Paginate page list research
      */

      public function paginates($listresearchs,$per_page){
         $current_page = (get_query_var('page') == 0)?1:get_query_var('page');
         $data_list_research = array();
         $research_count = '';
         $list_research_response = $listresearchs;
         if (!empty($list_research_response->data)) {
           $data_list_research = $list_research_response->data;
         }
         if (!empty($list_research_response->count)) {
            $research_count = $list_research_response->count;
         }
         $pagination = research_pagination($research_count,$current_page,$per_page);
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


      /*
      * GMO Z.Com Runsystem
      * 2019/01/25
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

      /*
      * GMO Z.Com Runsystem
      * 2019/01/25
      * FUNC: detail research
      */
      public static function getDetail($post_id, $tg_id, $research_id) {
         $url = research_URL_API_KAIZEN.'Research/ResearchDetail?tg_id='.$tg_id.'&research_id='.$research_id;
         $detail = research_get_api_common($post_id, $url, array(), "GET");
         return $detail;
      }

      /*
      * GMO Z.Com Runsystem
      * 2019/01/25
      * FUNC: CapacityCheck
      */
      public static function CapacityCheck($post_id, $tg_id, $research_id) {
         $url = research_URL_API_KAIZEN.'Research/CapacityCheck?tg_id='.$tg_id.'&research_id='.$research_id;
         $result = research_get_api_common($post_id, $url, array(), "GET");
         return $result;
      }

      /*
      * GMO Z.Com Runsystem
      * 2019/01/25
      * FUNC: CheckTarget
      */
      public static function CheckTarget($post_id, $tg_id, $research_id, $login_id, $memberreg_open) {
         $url = research_URL_API_KAIZEN.'Research/CheckTarget?tg_id='.$tg_id.'&research_id='.$research_id.'&login_id='.$login_id.'&memberreg_open='.$memberreg_open;
         $result = research_get_api_common($post_id, $url, array(), "GET");
         return $result;
      }

      public static function getResearchAnswerTarget($post_id,$research_id,$m_tno) {
         $tg_id = get_post_meta($post_id, "research_meta_group_id", true);
         $p_id = isset($_SESSION['arrSession']->P_ID)?$_SESSION['arrSession']->P_ID:"";
         $url = research_URL_API_KAIZEN.'Research/ResearchAnswerTarget?tg_id='.$tg_id.'&research_id='.$research_id.'&t_no='.$m_tno.'&p_id='.$p_id;
         $result = research_get_api_common($post_id, $url, array(), "GET");
         return $result;
      }

      public static function getListResearchQuestion($post_id,$research_id) {
        $tg_id = get_post_meta($post_id, "research_meta_group_id", true);
        $p_id = isset($_SESSION['arrSession']->P_ID)?$_SESSION['arrSession']->P_ID:"";
        $url = research_URL_API_KAIZEN.'Research/ResearchQuestion?tg_id='.$tg_id.'&research_id='.$research_id.'&p_id='.$p_id;
        $result = research_get_api_common($post_id, $url, array(), "GET");
        return $result;
      }

      public function getYearRange(){
         $years = range(date('Y')+research_YEAR_RANGE, date('Y')-research_YEAR_RANGE + 1);
         return $years;
      }

      public function research_SelectTitleN($post_id, $tg_id){
        $SelectTitleN = array();
        if($tg_id){
          $url = research_URL_API_KAIZEN.'Setting/SelectTitleN?tg_id='.$tg_id;
          $arrBody = array();
          $SelectTitleN = research_get_api_common($post_id, $url, $arrBody, "GET");
        }
        return $SelectTitleN;
      }

      public function research_renderTitleEnd($arTitle, $name = ''){
        foreach ($arTitle as $key => $item) {
          if($item->BEFORE_NAME == $name){
            return $item->AFTER_NAME;
          }
        }
      }
      
      public function research_convertNvl($before, $after){
        $rs = '';
        if($before != NULL || $before != ''){
          $rs = $before;
        }else{
          $rs = $after;
        }
        return $rs;
      }

      public function DispQuestion($g_q_type,$g_q_value,$g_q_next,$g_q_no,$g_q_disp,$g_q_words){
        $qvalue = '';
        $qnext = '';
        $i = $j = '';
        $sel = '';
        $sel2 = '';
        $maxlength = '';
        $size = '';
        $other_qvalue = '';
        
        $tmp = "";
        $RESERCH_ANS_OTHER = isset($_SESSION["RESERCH_ANS_OTHER"]) ? $_SESSION["RESERCH_ANS_OTHER"] : array();
        $RESERCH_ANS = isset($_SESSION["RESERCH_ANS"]) ? $_SESSION["RESERCH_ANS"] : array();
        switch ($g_q_type) {
          case "S1": 
            $qvalue = explode("|",$g_q_value);
            $qnext = explode("|",$g_q_next);
            $other_qvalue = explode("|",isset($RESERCH_ANS_OTHER[$g_q_no-1]) ? $RESERCH_ANS_OTHER[$g_q_no-1] : '');
          $tmp = $tmp."<tr>"."\r\n";
          $tmp = $tmp."<td>"."\r\n";
        
          for ($i = 0; $i < count($qvalue); $i++){
            $sel = (isset($RESERCH_ANS[$g_q_no-1]) && $RESERCH_ANS[$g_q_no-1] == $qvalue[$i])?"checked":"";
            if ($i != 0 ):
              $tmp = $tmp.(($g_q_disp == "0")?"<br>":"&nbsp;&nbsp;");
            endif;
            $valueFunc = (strpos($qvalue[$i],"[@テキスト]") > 0)?"other".$i:"";
            $tmp = $tmp."<label><input type='radio' name='qvalue' value='".$qvalue[$i]."' onClick='OnOtherRadio(".$valueFunc.")' ".$sel.">".str_replace("[@テキスト]","",$qvalue[$i])."</label>\r\n";
        
            if ( strpos($qvalue[$i],"[@テキスト]") > 0  ) :
              if( $g_q_disp == "0"):
                if( IsSafari() ) :
                  $size = "84";
                else:
                  $size = "100";
                endif;
              else:
                $size = "30";
              endif;
              if( $sel == "checked" ):
                $tmp = $tmp.(($g_q_disp == "0")?"<br>":"")."（<input type='text' style='width: 80%;ime-mode: active; background-color: #FFFFFF;' maxlength='2000' name='other_qvalue' id='other".$i."' value='".$other_qvalue[$i]."' >）"."\r\n";
              else:
                $tmp = $tmp.(($g_q_disp == "0")?"<br>":"")."（<input type='text' style='width: 80%;ime-mode: active; background-color: #C0C0C0;' maxlength='2000' name='other_qvalue' id='other".$i."' value='' readonly='readonly'>）"."\r\n";
              endif;
            else:
              $tmp = $tmp."<input type='hidden' name='other_qvalue' value=''>"."\r\n";
            endif;
          }
        
          $tmp = $tmp."</td>"."\r\n";
          $tmp = $tmp."</tr>"."\r\n";
        
          for( $i = 0; $i < count($qnext); $i++) {
            $tmp = $tmp."<input type='hidden' name='qnext' value='".$qnext[$i]."'>"."\r\n";
          }
        
          if( count($qnext) < count($qvalue) ):
            for($i = count(qnext); $i <= count($qvalue); $i++) {
              $tmp = $tmp."<input type='hidden' name='qnext' value='999'>"."\r\n";
            }
          endif;
          break;
        case "S2": 
        
          $qvalue = explode("|",$g_q_value);
          $qnext = explode("|",$g_q_next);
        
          $tmp = $tmp."<tr>"."\r\n";
          $tmp = $tmp."<td>"."\r\n";
          $tmp = $tmp."<select name='qvalue'>"."\r\n";
          $tmp = $tmp."<option value=''></option>"."\r\n";
          for( $i = 0; $i < count($qvalue); $i++){
            $sel = (isset($RESERCH_ANS[$g_q_no-1]) && $RESERCH_ANS[$g_q_no-1] == $qvalue[$i])?"selected":"";
            $tmp = $tmp."<option value='".$qvalue[$i]."' ".$sel.">".$qvalue[$i]."</option>"."\r\n";
          }
          $tmp = $tmp."</select>"."\r\n";
          $tmp = $tmp."</td>"."\r\n";
          $tmp = $tmp."</tr>"."\r\n";
        
          $tmp = $tmp."<input type='hidden' name='qnext' value='".($g_q_no+1)."'>"."\r\n";
          for( $i = 0; $i < count($qnext); $i++){
            $tmp = $tmp."<input type='hidden' name='qnext' value='".$qnext[$i]."'>"."\r\n";
          }
        
          if( count($qnext) < count($qvalue) ):
            for($i = count($qnext); $i <= count($qvalue); $i++) {
              $tmp = $tmp."<input type='hidden' name='qnext' value='999'>"."\r\n";
            }
          endif;
          break;
        case "M":
          $qvalue = explode("|",$g_q_value);
          $qnext = explode("|",$g_q_next);
          $sel = explode("|",isset($RESERCH_ANS[$g_q_no-1]) ? $RESERCH_ANS[$g_q_no-1] : '');
          $other_qvalue = explode("|",isset($RESERCH_ANS_OTHER[$g_q_no-1]) ? $RESERCH_ANS_OTHER[$g_q_no-1] : '');
          $tmp = $tmp."<tr>"."\r\n";
          $tmp = $tmp."<td>"."\r\n";
          if( $g_q_disp == "0"):
          $tmp = $tmp."<table width='100%' cellspacing='0' cellpadding='0' border='0'>"."\r\n";
          endif;
          for( $i = 0; $i < count($qvalue); $i++) {
            if ($g_q_disp == "0" ):
            $tmp = $tmp."<tr><td valign='top' width='10px'>";
            endif;
            $sel2 = "";
            if ($i != 0 ):
              $tmp = $tmp.(($g_q_disp == "0")?"":"&nbsp;&nbsp;");
            endif;
            for( $j = 0; $j < count($sel); $j++) {
              if ($sel2 == ""):
                $sel2 = ($sel[$j] == $qvalue[$i])?"checked":"";
              endif;
            }
        
            if( $g_q_disp == "0" ):
              $tempFunc = (strpos($qvalue[$i],"[@テキスト]") > 0)?"other".$i:"";
              $tmp = $tmp."<input type='checkbox' name='qvalue[]' value='".$qvalue[$i]."' ".$sel2." onClick='OnOtherCheckBox(".$tempFunc.")'>";
              if( strpos($qvalue[$i],"[@テキスト]") > 0):
                $tmp = $tmp."</td><td valign='top'><table width='100%' cellspacing='2' cellpadding='1'><tr><td nowrap> ".str_replace("[@テキスト]","",$qvalue[$i]);
              else:
                $tmp = $tmp."</td><td valign='top'><table width='100%' cellspacing='2' cellpadding='1'><tr><td> ".str_replace("[@テキスト]","",$qvalue[$i]);
              endif;
            else:
              $tmp = $tmp."<input type='checkbox' name='qvalue[]' value='".$qvalue[$i]."' ".$sel2." onClick='OnOtherCheckBox(".((strpos($qvalue[$i],"[@テキスト]") > 0)?"'other".$i."'":"''").")'>".str_replace("[@テキスト]","",$qvalue[$i])."\r\n";
            endif;
            if( strpos($qvalue[$i],"[@テキスト]") > 0 ):
              if ($g_q_disp == "0"):
                if (IsSafari()):
                  $size = "84";
                else:
                  $size = "100";
                endif;
              else:
                $size = "30";
              endif;
              if( $sel2 == "checked"):
                $tmp = $tmp.(($g_q_disp == "0")?"<br>":"")."（<input type='text' size='".$size."' style='ime-mode: active; background-color: #FFFFFF;' maxlength='2000' name='other_qvalue' id='other".$i."' value='".$other_qvalue[$i]."' >）"."\r\n";
              else:
                $tmp = $tmp.(($g_q_disp == "0")?"<br>":"")."（<input type='text' size='".$size."' style='ime-mode: active; background-color: #C0C0C0;' maxlength='2000' name='other_qvalue' id='other".$i."' value='' readonly='readonly'>）"."\r\n";
              endif;
            else:
                $tmp = $tmp."<input type='hidden' name='other_qvalue' value=''>"."\r\n";
            endif;
          if ($g_q_disp == "0" ):
            $tmp = $tmp."</td></tr></table></td></tr>"."\r\n";
          endif;
          }
          if( $g_q_disp == "0" ):
            $tmp = $tmp."</table>"."\r\n";
          endif;
          $tmp = $tmp."</td>"."\r\n";
          $tmp = $tmp."</tr>"."\r\n";
        
          for( $i = 0; $i < count($qnext); $i++){
            $tmp = $tmp."<input type='hidden' name='qnext' value='".$qnext[$i]."'>"."\r\n";
          }
          break;
        case "T1":  
          $maxlength = ($g_q_words != "" && $g_q_words != "0")?"maxlength='".CLngEx($g_q_words) * 2 ."'":"maxlength='2000'";
          $size      = ($g_q_words != "" && $g_q_words != "0")?"size='".((CLngEx($g_q_words) * 2 > 110)?"110":CLngEx($g_q_words) * 2):"size='110'";
        
          $tmp = $tmp."<tr>"."\r\n";
          $tmp = $tmp."<td>"."\r\n";
          $tmp = $tmp."<input type='text' name='qvalue' ".$size." ".$maxlength." value='".(isset($RESERCH_ANS[$g_q_no-1])?$RESERCH_ANS[$g_q_no-1]:'')."' style='ime-mode: active;width: 100%;'>"."\r\n";
          $tmp = $tmp."</td>"."\r\n";
          $tmp = $tmp."</tr>"."\r\n";
        
          $tmp = $tmp."<input type='hidden' name='qnext' value='".$g_q_next."'>"."\r\n";
          $tmp = $tmp."<input type='hidden' name='qwords' value='".$g_q_words."'>"."\r\n";
          break;
        case "T2":   
          $tmp = $tmp."<tr>"."\r\n";
          $tmp = $tmp."<td>"."\r\n";
          $tmp = $tmp."<textarea name='qvalue' cols='77' rows='5' style='ime-mode: active;width: 100%;'>".(isset($RESERCH_ANS[$g_q_no-1])?$RESERCH_ANS[$g_q_no-1]:'')."</textarea>"."\r\n";
          $tmp = $tmp."</td>"."\r\n";
          $tmp = $tmp."</tr>"."\r\n";
        
          $tmp = $tmp."<input type='hidden' name='qnext' value='".$g_q_next."'>"."\r\n";
          $tmp = $tmp."<input type='hidden' name='qwords' value='".$g_q_words."'>"."\r\n";
          break;
        case "N":    
          $maxlength = ($g_q_words != "" && $g_q_words != "0")?"maxlength='".$g_q_words."'":"maxlength='2000'";
          $size      = ($g_q_words != "" && $g_q_words != "0")?"size='".(($g_q_words > 110)?"110":$g_q_words."''")."'":"size='110'";
        
          $tmp = $tmp."<tr>"."\r\n";
          $tmp = $tmp."<td>"."\r\n";
          $tmp = $tmp."<input type='text' name='qvalue' ".$size." ".$maxlength." value='".(isset($RESERCH_ANS[$g_q_no-1])?$RESERCH_ANS[$g_q_no-1]:'')."' style='ime-mode: disabled;width: 100%;'>"."\r\n";
          $tmp = $tmp."</td>"."\r\n";
          $tmp = $tmp."</tr>"."\r\n";
        
          $tmp = $tmp."<input type='hidden' name='qnext' value='".$g_q_next."'>"."\r\n";
          $tmp = $tmp."<input type='hidden' name='qwords' value='".$g_q_words."'>"."\r\n";
          break;
        case "R": 
          $qnext = explode("|",$g_q_next);
          $qvalue = explode("|",$g_q_value);
          $sel = explode("|", isset($RESERCH_ANS[$g_q_no-1]) ? $RESERCH_ANS[$g_q_no-1] : '');
          $tmp = $tmp."<tr>"."\r\n";
          $tmp = $tmp."<td>"."\r\n";
          for( $i = 1; $i < count($qvalue)+1; $i++) {
            if($i != 1):
              $tmp = $tmp.(($g_q_disp == "0")?"<br>":"&nbsp;&nbsp;");
            endif;
            $tmp = $tmp.$i."位"."\r\n";
            $tmp = $tmp."&nbsp;<select name='qvalue_R'>"."\r\n";
            $tmp = $tmp."<option value=''></option>"."\r\n";
            for( $j = 0; $j < count($qvalue); $j++) {
              $selected = (isset($sel[$i-1]) && $qvalue[$j] == $sel[$i-1])?"selected":"";
              if( count($sel) >= count($qvalue)):
                $tmp = $tmp."<option value='".$qvalue[$j]."' ".$selected.">".$qvalue[$j]."</option>"."\r\n";
              else:
                $tmp = $tmp."<option value='".$qvalue[$j]."'>".$qvalue[$j]."</option>"."\r\n";
              endif;
            }
            $tmp = $tmp."</select>"."\r\n";
          }
          $tmp = $tmp."</td>"."\r\n";
          $tmp = $tmp."</tr>"."\r\n";
        
          for( $i = 0; $i < count($qnext); $i++){
            $tmp = $tmp."<input type='hidden' name='qnext' value='".$qnext[$i]."'>"."\r\n";
          }
          $tmp = $tmp."<div id='arr_qvalue'></div>"."\r\n";
          break;
        default:
          $tmp = "Error";
          $g_err = false;
          break;
        }
        
        return $tmp;
        }

        public function getResearchFile($tg_id, $g_reserch_id,$g_detailFile){
        $wkVal = '';
        $Tag = '';
        $Title = '';
        $filename = '';
        $filesize = '';
        $buf = '';
      
        $filename = $g_detailFile;
        $Title = $filename;
      
        if( $filename == ""){
          return "&nbsp;";
        }
        $info = new SplFileInfo($filename);
        $wkVal = ".".$info->getExtension();
        switch ($wkVal){
        case ".bmp":
        case ".gif":
        case ".grp": 
        case ".icn":
        case ".ico":
        case ".img":
        case ".jpe":
        case ".jpeg":
        case ".jpg":
        case ".pbm":
        case ".pcd":
        case ".pct":
        case ".pic":
        case ".pict":
        case ".png": 
        case ".pntg":
        case ".tif":
        case ".tiff":
          $Tag = research_IMAGE_URL.$tg_id."/".$g_reserch_id ."/".$filename;
          $Tag = "<img src='".$Tag ."' width='120' border='0' scrolling='no' allowtransparency='true' title='".$Title."'></img>";
      
          $buf = "<a href='".research_IMAGE_URL.$tg_id."/".$g_reserch_id ."/".$filename."' download>";
/*
          $buf = "<a href='javascript:DispDetail(&#39;";
          $buf = $buf.$tg_id."&#39;,&#39;";
          $buf = $buf.$g_reserch_id."&#39;,&#39;";
          $buf = $buf.$filename;
          $buf = $buf."&#39;, 1";
          $buf = $buf.");'>";
*/
          $Tag = $buf.$Tag."</a>";
          break;
        case ".txt":
          $Tag = "<img src='".plugin_dir_url(dirname(__FILE__))."assets/img/file/text.gif' width='50' height='50' border='0' scrolling='no' allowtransparency='true' title='".Title."'></img>";
          break;
        case ".csv":
          $Tag = "<img src='".plugin_dir_url(dirname(__FILE__))."assets/img/file/csv.gif' width='50' height='50' border='0' scrolling='no' allowtransparency='true' title='".Title."'></img>";
        case ".rtf":
          $Tag = "<img src='".plugin_dir_url(dirname(__FILE__))."assets/img/file/rtf.gif' width='50' height='50' border='0' scrolling='no' allowtransparency='true' title='".Title."'></img>";
          break;
        case ".lzh":
          $Tag = "<img src='".plugin_dir_url(dirname(__FILE__))."assets/img/file/lzh.s.gif' width='50' height='50' border='0' scrolling='no' allowtransparency='true' title='".Title."'></img>";
          break;
        case ".zip":
          $Tag = "<img src='".plugin_dir_url(dirname(__FILE__))."assets/img/file/zip.s.gif' width='50' height='50' border='0' scrolling='no' allowtransparency='true' title='".Title."'></img>";
          break;
        case ".xls":
          $Tag = "<img src='".plugin_dir_url(dirname(__FILE__))."assets/img/file/excel.gif' width='50' height='50' border='0' scrolling='no' allowtransparency='true' title='".Title."'></img>";
          break;
        case ".doc":
          $Tag = "<img src='".plugin_dir_url(dirname(__FILE__))."assets/img/file/word.gif' width='50' height='50' border='0' scrolling='no' allowtransparency='true' title='".Title."'></img>";
          break;
        case ".ppt":
          $Tag = "<img src='".plugin_dir_url(dirname(__FILE__))."assets/img/file/ppt.gif' width='50' height='50' border='0' scrolling='no' allowtransparency='true' title='".Title."'></img>";
          break;
        case ".pdf":
          $Tag = "<img src='".plugin_dir_url(dirname(__FILE__))."assets/img/file/pdf.gif' width='50' height='50' border='0' scrolling='no' allowtransparency='true' title='".Title."'></img>";
          break;
        default:
          $Tag = "<img src='".plugin_dir_url(dirname(__FILE__))."assets/img/file/file.gif' width='50' height='50' border='0' scrolling='no' allowtransparency='true' title='".Title."'></img>";
          break;
        }
      
        switch ($wkVal){
        case ".bmp": case ".gif": case ".grp": case ".icn": case ".ico": case ".img": case ".jpe": case ".jpeg": case ".jpg": case ".pbm": case ".pcd": case ".pct": case ".pic": case ".pict": case ".png": case ".pntg": case ".tif": case ".tiff":
          break;
        default:
          $Tag = "<a href='".research_IMAGE_URL.$tg_id."/".$g_reserch_id ."/".$filename."' download>".$Tag."<br>".$filename."</a>";
          //$Tag = "<a href='javascript:OnDownloadFile(&#39;".$filename."&#39;);'>".$Tag."<br>".$filename."</a>";
          break;
        }
        return $Tag;
      }
      public static function ResearchAgree($post_id, $tg_id, $research_id) {
         $url = research_URL_API_KAIZEN.'Research/ResearchAgree?tg_id='.$tg_id.'&research_id='.$research_id;
         $result = research_get_api_common($post_id, $url, array(), "GET");
         return $result;
      }

      public static function CheckAnswer($post_id, $tg_id, $research_id, $p_id) {
         $url = research_URL_API_KAIZEN.'Research/CheckAnswer?tg_id='.$tg_id.'&research_id='.$research_id.'&p_id='.$p_id;
         $result = research_get_api_common($post_id, $url, array(), "GET");
         return $result;
      }

      public static function postResearchAnswer($post_id, $arrBody, $ListResearchQuestion, $MemberInfo) {
        $url = research_URL_API_KAIZEN.'Research/ResearchAnswer';
        $arr = array (
          'ListResearchQuestion' => $ListResearchQuestion,
          'MemberInfo' => $MemberInfo,
          'p_id' => $arrBody['p_id'],
          'tg_id' => $arrBody['tg_id'],
          'research_id' => $arrBody['research_id'],
          't_no' => $arrBody['t_no'],
        );
        $result = research_get_api_common($post_id, $url, $arr, "POST");
        return $result;
      }

      public static function SendAnsReturnMail($post_id, $tg_id, $research_id, $mailFrom, $pass_from, $mailTo, $t_no, $env, $mailInfo) {
        $url = research_URL_API_KAIZEN.'Research/SendAnsReturnMail?tg_id='.$tg_id.'&research_id='.$research_id.'&mailFrom='.$mailFrom.'&pass_from='.$pass_from.'&mailTo='.$mailTo.'&t_no='.$t_no.'&env='.$env.'&mailInfo='.$mailInfo;
        $result = research_get_api_common($post_id, $url, array(), "GET");
        return $result;
     }

      public function SelectTitleN($post_id, $tg_id){
         $SelectTitleN = array();
         if($tg_id){
           $url = research_URL_API_KAIZEN.'Setting/SelectTitleN?tg_id='.$tg_id;
           $arrBody = array();
           $SelectTitleN = research_get_api_common($post_id, $url, $arrBody, "GET");
         }
          return $SelectTitleN;
       }

       public function getNewTitle($arrTitle,$name = ''){
         $arTitle = $arrTitle;
         foreach ($arTitle as $key => $item) {
           if($item->BEFORE_NAME == $name){
             return $item->AFTER_NAME;
           }
         }
       }
       public function getPageSlug($slug){
        $page_id = get_page_by_path($slug);
        $page_link = get_page_link($page_id); 
        $permalink_structure = get_option('permalink_structure');
        $alias = ($permalink_structure == NULL)?'&':"?";
        return $page_link.$alias;
      }

      public function getEnv() {
          return strpos(get_site_url(), RESEARCH_TEST_SITE_URL) ? RESEARCH_APP_ENV_TEST : RESEARCH_APP_ENV_PROC;
      }
       
   }
?>
