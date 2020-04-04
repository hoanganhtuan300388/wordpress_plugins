<?php
   define('__ROOT__', dirname(dirname(__FILE__))); 
   require_once(__ROOT__.'/config/constant.php'); 
   require_once(__ROOT__.'/controller/researchController.php'); 
   $researchs = new researchController();
   $listresearchs = $researchs->lists();
   $pagination = $researchs->paginates();
   $keyword = isset($_POST['search_select'])?$_POST['search_select']:"";
   $columnSort = isset($_POST['sort'])?$_POST['sort']:"";
   $orderBy = isset($_POST['order'])?$_POST['order']:"";
   $current_page = (get_query_var('page') == 0)?1:get_query_var('page');
   $page_no = $current_page - 1;

   $now_year = date('Y');
   $now_month = date('m');
   $year_date = isset($_POST['year_date'])?$_POST['year_date']:$now_year;
   $month_date = isset($_POST['month_date'])?$_POST['month_date']:$now_month;
   $years = $researchs->getYearRange();
?>
<!DOCTYPE html>
<html lang="ja">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
      <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
      <title>アンケート一覧</title>
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/style.css">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/smart.css">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/list.css">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/h_menu.css">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/f_menu.css">
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/common.js"></script>
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/list/list.js"></script>
   </head>
   <body>
      <div class="container list_page">
        <form id="mainForm" name="mainForm" method="post">
         <table width="100%" cellspacing="0" cellpadding="3">
            <tbody>
               <tr>
                  <td width="100%" valign="top" align="right">
                     &nbsp;&nbsp;
                     <a class="btn_color" href="Javascript:window.close();"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/img/close.gif" title="閉じる" border="0"></a>
                  </td>
               </tr>
            </tbody>
         </table>
         <table class="top_logo" width="100%" cellspacing="0" cellpadding="0">
            <tbody>
               <tr>
                  <td align="left">
                     <table width="100%" align="left" cellspacing="0" cellpadding="0" border="0" style="float: none;">
                        <tbody>
                           <tr>
                              <td width="100%" class="center_logo"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/img/r_header120120501144402.png" border="0" vspace="0" hspace="0" title=""></td>
                           </tr>
                        </tbody>
                     </table>
                  </td>
               </tr>
            </tbody>
         </table>
         <table width="100%" align="left" cellspacing="0" cellpadding="0" border="0" style="float: none;">
            <tbody>
               <tr>
                  <td width="100%">
                     <hr size="1">
                  </td>
               </tr>
            </tbody>
         </table>
         <table class="formloading" cellspacing="0" cellpadding="3">
            <tbody>
               <tr>
                  <td>
                     <strong><font size="2"><font color="#808080">■</font>　アンケート</font></strong>
                  </td>
                  <td align="center">
                     <div id="indidiv" style="display:none">
                        <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/img/loading.gif" valign="middle">
                        <font size="2" color="#6699FF"><b>検索中です。しばらくお待ちください・・・</b><font>
                        </font></font>
                     </div>
                  </td>
                  <td align="right">
                     <font size="2"><b>※背景<font color="#CCFF99">■</font>は会員のみの参加アンケートです。</b></font>
                  </td>
               </tr>
            </tbody>
         </table>
         <!-- ■■■ ボタン ■■■ -->
         <table width="100%" class="formSearch" cellspacing="0" cellpadding="3">
            <tbody>
               <tr>
                  <input type="hidden" name="top_type" value="1">
                  <input type="hidden" name="top_sta" value="東京都">
                  <td width="75%" align="right">
                     <select name="year_date" onchange="OnCommand();">
                        <option <?php if ($year_date == "") {echo "selected";} ?> value="">----</option>
                     <?php foreach ($years as $year) { ?>
                        <option <?php if ($year_date == $year) {echo "selected";} ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
                     <?php } ?>
                     </select>
                     年
                     <select name="month_date" onchange="OnCommand();">
                        <option <?php if ($month_date == "") {echo "selected";} ?> value="">--</option>
                        <?php
                           for($month_start = 01; $month_start <=12; $month_start ++)  { ?>
                              <option <?php if ($month_date == $month_start) {echo "selected";} ?> value="<?php echo $month_start?>">
                                 <?php echo ($month_start <10)?"0".$month_start:$month_start?>
                              </option>
                        <?php } ?>
                     </select>
                     月
                     <span>キーワード <input type="text" class="inputSearch" name="search_select" value="<?php echo $keyword; ?>">
                     <input type="button" class="btnSearch" value="　検索　" onclick="OnCommand();"></span>
                  </td>
               </tr>
            </tbody>
         </table>
         <!-- ■■■ 一覧 ■■■ -->
         <b>
         </b>
         <div class="statics">
            <span class="left">【該当件数：<?php if($listresearchs->count) echo $listresearchs->count; else echo 0; ?>件】</span>
            <ul class="right listOpe-right">
               <li class="nolink_startpage <?php if($current_page == 1) echo 'none_event'; ?>">
                  <a href="Javascript:Pagination(1);"><span>先頭ページ</span> <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>/assets/img/prevfirst.png" class="none-view"></a>
               </li>
               <li class="nolink_prevpage <?php if($current_page == 1) echo 'none_event'; ?>">
                  <a href="Javascript:Pagination(<?php echo $current_page-1; ?>);"><span>前ページ</span> <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>/assets/img/prev.png" class="none-view"></a>
               </li>
               <li>
                  &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $pagination['current_page']; ?> / <?php echo $pagination['total_page']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
               </li>
               <li class="link_nextpage <?php if($current_page == $pagination['total_page']) echo 'none_event'; ?>">
                  <a href="Javascript:Pagination(<?php echo $current_page+1; ?>);"><span>次ページ</span> <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>/assets/img/next.png" class="none-view"></a>
               </li>
               <li class="link_lastpage <?php if($current_page == $pagination['total_page']) echo 'none_event'; ?>">
                  <a href="Javascript:Pagination(<?php echo $pagination['total_page']; ?>);"><span>最終ページ</span> <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>/assets/img/nextend.png" class="none-view"></a>
               </li>
            </ul>
         </div>
         <table id="list" border="0" width="910" cellspacing="1" celpadding="0">
            <tbody>
               <tr class="ListHeader">
                  <td align="center" class="stt" style="width: 30px;" nowrap="">
                     No
                  </td>
                  <td width="112" nowrap="">
                     <a id="lh_0" class="LHLink" href="javascript:OnSort('RES_SUB_TITLE','');">
                        サブタイトル
                        <?php
                           echo ($columnSort == 'RES_SUB_TITLE')?($orderBy == "asc")?"▲1":"▼1":"";
                        ?>
                     </a>
                  </td>
                  <td width="192">
                     <a id="lh_1" class="LHLink" href="javascript:OnSort('RES_CONTENTS','');">
                        説明文
                        <?php
                           echo ($columnSort == 'RES_CONTENTS')?($orderBy == "asc")?"▲1":"▼1":"";
                        ?>
                     </a>
                  </td>
                  <td width="112" nowrap="">
                     <a id="lh_2" class="LHLink" href="javascript:OnSort('RES_STATUS','');">
                        状態
                        <?php
                           echo ($columnSort == 'RES_STATUS')?($orderBy == "asc")?"▲1":"▼1":"";
                        ?>
                     </a>
                  </td>
                  <td width="112" nowrap="">
                     <a id="lh_3" class="LHLink" href="javascript:OnSort('RES_START_DATE','');">
                        開始日
                        <?php
                           echo ($columnSort == 'RES_START_DATE')?($orderBy == "asc")?"▲1":"▼1":"";
                        ?>
                     </a>
                  </td>
                  <td width="112" nowrap="">
                     <a id="lh_4" class="LHLink" href="javascript:OnSort('RES_END_DATE','');">
                        終了日
                        <?php
                           echo ($columnSort == 'RES_END_DATE')?($orderBy == "asc")?"▲1":"▼1":"";
                        ?>
                     </a>
                  </td>
                  <td width="126" nowrap="">
                     <a id="lh_5" class="LHLink" href="javascript:OnSort('RES_MEMBERREG_OPEN','');">
                        対象者
                        <?php
                           echo ($columnSort == 'RES_MEMBERREG_OPEN')?($orderBy == "asc")?"▲1":"▼1":"";
                        ?>
                     </a>
                  </td>
                  <td width="144" nowrap="">
                     <a id="lh_6" class="LHLink" href="javascript:OnSort('RES_RESERCH_NAME','');">
                        アンケート名
                        <?php
                           echo ($columnSort == 'RES_RESERCH_NAME')?($orderBy == "asc")?"▲1":"▼1":"";
                        ?>
                     </a>
                  </td>
               </tr>
               <?php
                  if(!empty($listresearchs->data))
                  foreach ($listresearchs->data as $key => $res_research) { 
               ?>
                  <tr class="ListRow4">
                     <td align="center"><?php echo $page_no*get_option('nakama-member-list-per-page')+$key+1; ?></td>
                     <td><?php echo $res_research->RES_SUB_TITLE; ?></td>
                     <td>
                        <?php echo $res_research->RES_CONTENTS; ?>
                     </td>
                     <td>
                        <?php 
                           echo $researchs->listGetResStatus(
                                 $res_research->RES_STATUS,
                                 $res_research->RES_START_DATE,
                                 $res_research->RES_START_TIME,
                                 $res_research->RES_END_DATE,
                                 $res_research->RES_END_TIME
                              ); 
                        ?>
                     </td>
                     <td><?php echo $researchs->convertDates($res_research->RES_START_DATE,"Y","年").$researchs->convertDates($res_research->RES_START_DATE,"m","月").$researchs->convertDates($res_research->RES_START_DATE,"d","日"); ?></td>
                     <td><?php echo $researchs->convertDates($res_research->RES_END_DATE,"Y","年").$researchs->convertDates($res_research->RES_END_DATE,"m","月").$researchs->convertDates($res_research->RES_END_DATE,"d","日"); ?></td>
                     <td><?php echo $researchs->sedai_disp_umu($res_research->RES_SEDAI_DISP_UMU,$res_research->RES_MEMBERREG_OPEN); ?></td>
                     <td><a class="LRLink" href="javascript:ShowResearchDetail('<?php echo $res_research->RES_TG_ID; ?>', '<?php echo $res_research->RES_RESERCH_ID; ?>');"><?php echo $res_research->RES_RESERCH_NAME; ?></a></td>
                  </tr>
               <?php } ?>
               
            </tbody>
         </table>
         <div class="statics">
            <ul class="right listOpe-right">
               <li class="nolink_startpage <?php if($current_page == 1) echo 'none_event'; ?>">
                  <a href="Javascript:Pagination(1);"><span>先頭ページ</span> <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>/assets/img/prevfirst.png" class="none-view"></a>
               </li>
               <li class="nolink_prevpage <?php if($current_page == 1) echo 'none_event'; ?>">
                  <a href="Javascript:Pagination(<?php echo $current_page-1; ?>);"><span>前ページ</span> <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>/assets/img/prev.png" class="none-view"></a>
               </li>
               <li>
                  &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $pagination['current_page']; ?> / <?php echo $pagination['total_page']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
               </li>
               <li class="link_nextpage <?php if($current_page == $pagination['total_page']) echo 'none_event'; ?>">
                  <a href="Javascript:Pagination(<?php echo $current_page+1; ?>);"><span>次ページ</span> <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>/assets/img/next.png" class="none-view"></a>
               </li>
               <li class="link_lastpage <?php if($current_page == $pagination['total_page']) echo 'none_event'; ?>">
                  <a href="Javascript:Pagination(<?php echo $pagination['total_page']; ?>);"><span>最終ページ</span> <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>/assets/img/nextend.png" class="none-view"></a>
               </li>
            </ul>
         </div>
         <br>
         <input type="hidden" name="sort" value="<?php echo $columnSort; ?>">
         <input type="hidden" name="order" value="<?php echo $orderBy; ?>">
         <input type="hidden" name="page" value="1">
         <input type="hidden" name="cmd" value="">
         <input type="hidden" name="max" value="1">
         <input type="hidden" name="top_g_id" value="dmshibuya">
         <input type="hidden" name="other_top_g_id" value="dmtachikawa,dmhino,dmhmurayama,hsdk0000,hkyg0000000000,naka,ryokanlist,dmmeguro,hjodawara">
         <input type="hidden" name="page_no" value="19">
         <input type="hidden" name="search_mode" value="">
         <input type="hidden" name="top_type_visible" value="0">
         <input type="hidden" name="lower_g_id" value="">
      </form>
      </div>
   </body>
</html>