<?php
  $path_page = get_page_uri();
  $MemberCrSet = new MemberCrSet();
  $page_link = $MemberCrSet->getPageSlug('nakama-login')."page_request=list_member&post_id=".$postid;
  if(!isset($_SESSION['arrSession'])){
    if($group_leader == 1){
      ?>
      <script>window.location = "<?php echo $page_link.'&page_redirect='.$path_page; ?>"; </script>
      <?php
    }
  }else{
      $set_lg_g_id = get_post_meta($postid, 'set_lg_g_id', true);
      $UpLgId_flg = get_post_meta($postid, 'lg_login', true);
      $group_leader = get_post_meta($postid, 'group_leader', true);
      if($group_leader == 1 && !empty($set_lg_g_id)){
         $arrBodyLogin = array(
            "tgId" => $_SESSION['arrSession']->TG_ID,
            "userId" => $_SESSION['arrSession']->USERID,
            "password" => $_SESSION['arrSession']->PASSWORD,
            "rememberMe" => false,
            "loginStyle" => 1,
            "lg_id" => $set_lg_g_id,
            "lg_login" => 1,
            "UpLgId_flg" => $UpLgId_flg
         );
         $login = $MemberCrSet->logins($postid, $arrBodyLogin);
         if(isset($login->Message)){
            unset($_SESSION['arrSession']);
            ?>
            <script>window.location = "<?php echo $page_link.'&page_redirect='.$path_page; ?>"; </script>
            <?php
         }
      }
  }
  if(!isset($_POST["incLower"])){
    $_SESSION['incLower'] = 1;
  } else {
    $_SESSION['incLower'] = (isset($_POST["incLower"]) && ($_POST["incLower"] == "1" || $_POST["incLower"] == NULL))?1:0;
  }
  $group_leader = get_post_meta($postid, 'group_leader', true);
  $per_page = get_post_meta($postid, 'nak-member-per-page', true);
  if(isset($_SESSION['arrSession'])){
      $p_id = $_SESSION['arrSession']->P_ID;
      $user_per_page = get_option($p_id.$postid."nakama-member-list-logined-per-page");
      $per_page = (!empty($user_per_page)) ? $user_per_page : $per_page;
  }
  $set_lg_g_id = get_post_meta($postid, 'set_lg_g_id', true);
  $top_type_visible = get_post_meta($postid, 'top_type_visible', true);
  $top_type = isset($_POST['top_type'])?$_POST['top_type']:get_post_meta($postid, 'top_type', true);
  $gTop_type = ($top_type == '')?1:$top_type;
  if($gTop_type == 2){
    $top_sta = $MemberCrSet->member_get_Sta($postid,$tg_id);
    $top_sta = isset($_POST['top_sta'])?$_POST['top_sta']:$top_sta;
  }else{
    $top_sta = "";
  }
  $LG_Follow = $_SESSION['incLower'];
  $keyword = isset($_POST['search_select'.$args['id']])?$_POST['search_select'.$args['id']]:"";
  $columnSort = isset($_POST['sort'.$args['id']])?$_POST['sort'.$args['id']]:"";
  $orderBy = isset($_POST['order'.$args['id']])?$_POST['order'.$args['id']]:"";
  $current_page = isset($_POST['page'.$args['id']])?$_POST['page'.$args['id']]:1;
  $page_no = $current_page - 1;
  $LG_ID = "";
  $LG_ID = $set_lg_g_id;
   if(isset($_POST['LG_ID'])){
      $LG_ID = $_POST['LG_ID'];
   }
   if($top_type == 2){
      $LG_ID = "";
   }
  $listMembers = $MemberCrSet->shortcode_Getlists($postid, $tg_id,$LG_ID,$keyword,$columnSort,$orderBy,$LG_Follow,$page_no,$per_page,$PattenNo,$top_sta);
  if(!empty($listMembers->header)){
    foreach ($listMembers->header as $col) {
      $arrHeader[] = ["column_id" => $col->column_id, "column_name" => $col->column_name];
    }
    $_SESSION['list_arr_header'] = $arrHeader;
  }
  $list_count = isset($listMembers->count) ? $listMembers->count : 0;
  $pagination = $MemberCrSet->member_setting_paginates($list_count,$current_page,$per_page);
  $getGroupTree = $MemberCrSet->getGroupTree($postid);
  $getGroupName = $MemberCrSet->GetGroupData($postid);
  
   $arrGroup = array();
   if(isset($getGroupTree->data)){
      foreach ($getGroupTree->data as $key => $item) {
         $item->id = $item->LG_ID;
         $item->text = $item->LG_NAME;
         array_push($arrGroup, $item);
      }
   }
   $tree = buildTree($arrGroup);
   $json_tree = json_encode($tree, JSON_UNESCAPED_UNICODE);
   $infoUser = isset($_SESSION['arrSession'])?$_SESSION['arrSession']:array();
   $pIdInfo = isset($infoUser->P_ID)?$infoUser->P_ID:"";
   $arrShow = get_option($pIdInfo.$postid.'_setting_list_show');
?>
<!DOCTYPE html>
<html lang="ja">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
      <title><?php echo get_the_title($args['id']); ?></title>
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/style.css">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/list.css">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/smart.css">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/selectleveljs/easyui.css">
      <script type="text/javascript">
         var urlajax = "<?php echo admin_url("admin-ajax.php"); ?>"
      </script>
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/jquery-1.6.3.min.js"></script>
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/common.js"></script>
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/list.js"></script>
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/MemberList.js"></script>
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/selectleveljs/jquery.easyui.min.js"></script>
   </head>
   <body>

      <div class="container">
         <form id="mainForm<?php echo $args['id']; ?>" name="mainForm<?php echo $args['id']; ?>" method="post" action="" class="mb-100">
            <div id="search_area">
               <table>
                  <tbody>
                     <tr>
                        <td>
                           <span style="font-size: 80%"><strong><font color="#808080">■</font>　<?php echo get_the_title($args['id']); ?></strong></span>
                        </td>
                     </tr>
                     <tr>
                        <td class="no-line">
                           <div id="indidiv<?php echo $args['id']; ?>" style="display:none">
                              <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/img/member/loading.gif" valign="middle">
                              <font size="2" color="#6699FF"><b>検索中です。しばらくお待ちください・・・</b><font>
                              </font></font>
                           </div>
                           <font size="2" color="#6699FF"><font>
                           </font></font>
                        </td>
                     </tr>
                  </tbody>
               </table>
               <ul class="list-form">
                  <?php if($top_type_visible == "1" ): ?>
                  <li class="top_type">
                     <label><input type="radio" name="top_type_radio" value="1" onClick="OnTopChange('<?php echo $args['id']; ?>');" <?php echo ($gTop_type == 1)?"checked":""; ?>><b>当会</b></label>&nbsp;&nbsp;
                     <label><input type="radio" name="top_type_radio" value="2" onClick="OnTopChange('<?php echo $args['id']; ?>');" <?php echo ($gTop_type == 2)?"checked":""; ?>><b>全国</b></label>
                     <input type="hidden" name="top_type" value="<?php echo $gTop_type; ?>">
                     &nbsp;&nbsp;
                     <?php if($gTop_type == "2" ): ?>
                        <?php echo $MemberCrSet->renderTopStation($args['id'],$top_sta); ?>
                     <?php endif; ?>
                  </li>
                  <?php else: ?>
                     <input type="hidden" name="top_type" value="<?php echo $gTop_type; ?>">
                  <?php endif; ?>
                  <?php if($group_leader == 0) { ?>
                  <?php if($gTop_type == "1" ): ?>
                  <li>
                     <label>グループ</label>
                     <!-- <button id="btn2" style="display: none;">TriggerClick</button>
                     <input id="selectGroupTree" style="width:400px"></input>
                    <input type="hidden" name="LG_ID" value="<?php //echo $LG_ID; ?>"> -->
                    <select name="LG_ID" class="lgGid">
                        <option></option>
                        <?php if($set_lg_g_id == "" && !empty($getGroupName)): ?>
                        <option value="<?php echo $getGroupName->TG_ID; ?>"><?php echo $getGroupName->TG_NAME; ?></option>
                        <?php endif; ?>
                        <?php if(!empty($getGroupTree->data)) : 
                           foreach($getGroupTree->data as $item) :
                              ?>
                              <option value="<?php echo $item->LG_ID; ?>" <?php if($LG_ID == $item->LG_ID) echo "selected"; ?>><?php print_space($item->CLS); echo $item->LG_NAME; ?></option>
                              <?php 
                           endforeach;
                        endif; 
                        ?>
                    </select>
                     
                     <label><input type="checkbox" name="incLower" value="1" <?php echo ($_SESSION['incLower'] == 1)?"checked":""; ?> onclick="OnCommand(<?php echo $args['id']; ?>,event);"> 下部含む</label>
                     <input type="checkbox" name="incLower" id="in_wer_check" value="incLower" <?php echo ($_SESSION['incLower'] == 0)?"checked":""; ?> hidden>
                  </li>
                  <?php endif; ?>
                  <?php } ?>
                  <li>
                    <label>キーワード</label>
                    <input type="text" name="search_select<?php echo $args['id']; ?>" value="<?php echo $keyword; ?>" class="search_select">
                    <a class="btn_color btn-add-change" href="Javascript:void(0)" onclick="OnCommand(<?php echo $args['id']; ?>);">検索</a>
                  </li>
               </ul>
            </div>
            <div class="statics">
               <span class="left">【該当件数：<?php if(isset($list_count)) echo $list_count; else echo 0; ?>件】</span>
               <div class="right">
                  <ul class="left listOpe-right">
                    <?php if(!empty($_SESSION['arrSession'])) : ?>
                    <li><a class="setting_sort" href="<?php echo $MemberCrSet->getPageSlug('nakama-logined-setting-sort'); ?>path_list=<?php echo get_page_link();?>&postid=<?php echo $postid; ?>">ソート設定</a></li>
                    <li><a class="setting_view" href="<?php echo $MemberCrSet->getPageSlug('nakama-logined-setting-view'); ?>path_list=<?php echo get_page_link();?>&postid=<?php echo $postid; ?>">表示設定</a></li>
                    <?php endif; ?>
                  </ul>
                  <ul class="right listOpe-right">
                      <li class="nolink_startpage <?php if($current_page == 1) echo 'none_event'; ?>">
                         <a href="Javascript:Pagination(1,<?php echo $args['id']; ?>);"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>admin/assets/img/resultset_first.png"><span>先頭ページ</span></a>
                      </li>
                      <li class="nolink_prevpage <?php if($current_page == 1) echo 'none_event'; ?>">
                         <a href="Javascript:Pagination(<?php echo $current_page-1; ?>,<?php echo $args['id']; ?>);"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>admin/assets/img/resultset_previous.png"><span>前ページ</span> </a>
                      </li>
                      <li>
                         &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $pagination['current_page']; ?> / <?php echo $pagination['total_page']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
                      </li>
                      <li class="link_nextpage <?php if($current_page == $pagination['total_page']) echo 'none_event'; ?>">
                         <a href="Javascript:Pagination(<?php echo $current_page+1; ?>,<?php echo $args['id']; ?>);"><span>次ページ</span> <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>admin/assets/img/resultset_next.png"></a>
                      </li>
                      <li class="link_lastpage <?php if($current_page == $pagination['total_page']) echo 'none_event'; ?>">
                         <a href="Javascript:Pagination(<?php echo $pagination['total_page']; ?>,<?php echo $args['id']; ?>);"><span>最終ページ</span> <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>admin/assets/img/resultset_last.png"></a>
                      </li>
                   </ul>
                </div>
            </div>
            <div class="main-list main-table-scroll">
               <table id="list">
                  <thead>
                     <tr class="ListHeaderMemberList">
                        <td class="first">No</td>
                        <?php if($group_leader == 1) : ?>
                        <td class="second">詳細</td>
                        <?php endif; ?>
                        <?php
                          
                          $arrHeader = array();
                          if($arrShow){
                            $arrShow = json_decode($arrShow);
                            $arrShow = $arrShow->list_item;
                            $arrShowCus = [];
                            if($arrShow){
                              foreach ($arrShow as $key => $item) {
                                array_push($arrShowCus, $item->column_id);
                              }
                            }
                            if($_POST){
                              $arrSortShow = get_option($pIdInfo."_setting_sort");
                              $arrSortShow = json_decode($arrSortShow);
                              $sort = $_POST['sort'.$args['id']];
                              $order = $_POST['order'.$args['id']];
                              if($order == "asc"){
                                $order = 'desc';
                              }else{
                                $order = 'asc';
                              }
                              foreach ($arrSortShow as $key => $item) {
                                if($item->item_id == $sort){
                                  $arrSortShow[$key]->item_sort = $order;
                                }
                              }
                              update_option($pIdInfo."_setting_sort", json_encode($arrSortShow, JSON_UNESCAPED_UNICODE));
                            }
                            $arrSortShow = get_option($pIdInfo."_setting_sort");
                            $arrSortShow = json_decode($arrSortShow);
                            
                            foreach ($arrShowCus as $key => $item) {
                              $rs = $MemberCrSet->customHeaderItem($item, $listMembers->header, $arrSortShow);
                              $arrHeader[] = ["column_id" => $rs['column_id'], "column_name" => $rs['column_name']];
                               ?>
                                <td class="LHLink">
                                  <a href="javascript:OnSort('<?php echo $rs['column_id']; ?>','<?php echo ($rs['column_sort'] != null)?$rs['column_sort']:""?>',<?php echo $args['id']; ?>);">
                                     <?php
                                        echo $rs['column_name'];
                                        echo ($rs['column_sort'] != null)?($rs['column_sort'] == "asc")?"▲":"▼":"";
                                        echo ($rs['column_sort_index'] != null)?$rs['column_sort_index']+1:"";
                                     ?>
                                  </a>
                                </td>
                             <?php
                            }
                          }else{ ?>
                            <?php
                               $arrHeader = array();
                               $arrHidden = array();
                               if(!empty($_SESSION['list_arr_header_hidden']))
                                  $arrHidden = $_SESSION['list_arr_header_hidden'];
                               if(!empty($listMembers->header))
                               foreach ($listMembers->header as $col) {
                                  $arrHeader[] = ["column_id" => $col->column_id, "column_name" => $col->column_name];
                                 
                            ?>
                            <?php if(!in_array($col->column_id,$arrHidden)): ?>
                            <td class="LHLink">
                            <a href="javascript:OnSort('<?php echo $col->column_id; ?>','<?php echo ($col->column_sort != null)?$col->column_sort:""?>',<?php echo $args['id']; ?>);">
                               <?php
                                  echo $col->column_name;
                                  echo ($col->column_sort != null)?($col->column_sort == "asc")?"▲":"▼":"";
                                  echo ($col->column_sort_index != null)?$col->column_sort_index+1:"";
                               ?>
                            </a>
                            </td>
                               <?php endif; ?>
                          <?php }} ?>
                     </tr>
                  </thead>
                  <tbody id="data_list<?php echo $args['id']; ?>">
                     <?php
                      if(!$arrShow){

                        if(!empty($listMembers->body))
                        foreach ($listMembers->body as $key => $res_members) {
                          ?>
                          <tr class="<?php echo ($key%2 == 0)?"ListRow1":"ListRow2"; ?> listrow">
                            <td><?php echo $page_no*$per_page+$key+1; ?></td>
                            <?php if($group_leader == 1) : ?>
                            <td>
                              <?php 
                              $page_link = $MemberCrSet->getPageSlug('nakama-member-detail-member'); 
                              ?>
                              <?php if( $MemberCrSet->getKeyBasedOnName($res_members->nodispCol,"DISP_DETAIL") == 1) { ?>
                              <a class="LRLink" href="javascript:void(0)" onclick="ShowDetail('<?php echo $MemberCrSet->getKeyBasedOnName($res_members->nodispCol,"NODISP_M_ID") ?>','<?php echo $tg_id; ?>', '<?php echo $postid; ?>', '<?php echo $page_link; ?>');">詳細</a>
                              <?php } ?>
                            </td>
                            <?php endif; ?>
                            <?php
                               if(!empty($res_members->dispCol))
                               foreach ($res_members->dispCol as $member) {?>
                            <?php if(!in_array($member->column_id,$arrHidden)): ?>
                            <td>
                             <?php
                                if($member->column_format == "System.Nullable`1[[System.DateTime, mscorlib, Version=4.0.0.0, Culture=neutral, PublicKeyToken=b77a5c561934e089]]"){
                                   $dateTime = !empty($member->column_data)?$MemberCrSet->convertDates($member->column_data, "Y", "年").$MemberCrSet->convertDates($member->column_data, "m", "月").$MemberCrSet->convertDates($member->column_data, "d", "日"):"";
                                   echo $dateTime;
                                }
                                else {
                                   echo $member->column_data;
                                }
                             ?>
                            </td>
                        <?php endif; ?>
                        <?php } ?>
                          </tr>
                        <?php }
                      }else{
                        if(!empty($listMembers->body))
                        foreach ($listMembers->body as $key => $res_members) {
                          ?>
                          <tr class="<?php echo ($key%2 == 0)?"ListRow1":"ListRow2"; ?> listrow">
                            <td><?php echo $page_no*$per_page+$key+1; ?></td>
                            <?php if($group_leader == 1) : ?>
                            <td>
                              <?php 
                              $page_link = $MemberCrSet->getPageSlug('nakama-member-detail-member'); 
                              ?>
                              <?php if( $MemberCrSet->getKeyBasedOnName($res_members->nodispCol,"DISP_DETAIL") == 1) { ?>
                              <a class="LRLink" href="javascript:void(0)" onclick="ShowDetail('<?php echo $MemberCrSet->getKeyBasedOnName($res_members->nodispCol,"NODISP_M_ID") ?>','<?php echo $tg_id; ?>', '<?php echo $postid; ?>', '<?php echo $page_link; ?>');">詳細</a>
                              <?php } ?>
                            </td>
                            <?php endif; ?>
                              <?php
                                  $arrShowCus = [];
                                  if($arrShow){
                                    foreach ($arrShow as $key => $item) {
                                      array_push($arrShowCus, $item->column_id);
                                    }
                                  }
                                  foreach ($arrShowCus as $key => $item) {
                                    $rs = $MemberCrSet->customBodyItem($item, $res_members->dispCol);
                                     ?>
                                    <td>
                                       <?php
                                          if($rs['column_format'] == "System.Nullable`1[[System.DateTime, mscorlib, Version=4.0.0.0, Culture=neutral, PublicKeyToken=b77a5c561934e089]]"){
                                             $dateTime = !empty($rs['column_data'])?$MemberCrSet->convertDates($rs['column_data'], "Y", "年").$MemberCrSet->convertDates($rs['column_data'], "m", "月").$MemberCrSet->convertDates($rs['column_data'], "d", "日"):"";
                                             echo $dateTime;
                                          }
                                          else {
                                             echo $rs['column_data'];
                                          }
                                       ?>
                                      </td>
                                  <?php
                                  } ?>
                               
                               </tr>
                        <?php }
                      }
                      ?>
                  </tbody>
               </table>
            </div>
            <div class="statics">
              <div class="right">
                  <ul class="left listOpe-right">
                    <?php if(!empty($_SESSION['arrSession'])) : ?>
                    <li><a class="setting_sort" href="<?php echo $MemberCrSet->getPageSlug('nakama-logined-setting-sort'); ?>path_list=<?php echo get_page_link();?>&postid=<?php echo $postid; ?>">ソート設定</a></li>
                    <li><a class="setting_view" href="<?php echo $MemberCrSet->getPageSlug('nakama-logined-setting-view'); ?>path_list=<?php echo get_page_link();?>&postid=<?php echo $postid; ?>">表示設定</a></li>
                    <?php endif; ?>
                  </ul>
                  <ul class="right listOpe-right">
                      <li class="nolink_startpage <?php if($current_page == 1) echo 'none_event'; ?>">
                         <a href="Javascript:Pagination(1,<?php echo $args['id']; ?>);"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>admin/assets/img/resultset_first.png"><span>先頭ページ</span></a>
                      </li>
                      <li class="nolink_prevpage <?php if($current_page == 1) echo 'none_event'; ?>">
                         <a href="Javascript:Pagination(<?php echo $current_page-1; ?>,<?php echo $args['id']; ?>);"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>admin/assets/img/resultset_previous.png"><span>前ページ</span> </a>
                      </li>
                      <li>
                         &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $pagination['current_page']; ?> / <?php echo $pagination['total_page']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
                      </li>
                      <li class="link_nextpage <?php if($current_page == $pagination['total_page']) echo 'none_event'; ?>">
                         <a href="Javascript:Pagination(<?php echo $current_page+1; ?>,<?php echo $args['id']; ?>);"><span>次ページ</span> <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>admin/assets/img/resultset_next.png"></a>
                      </li>
                      <li class="link_lastpage <?php if($current_page == $pagination['total_page']) echo 'none_event'; ?>">
                         <a href="Javascript:Pagination(<?php echo $pagination['total_page']; ?>,<?php echo $args['id']; ?>);"><span>最終ページ</span> <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>admin/assets/img/resultset_last.png"></a>
                      </li>
                   </ul>
                </div>
            </div>
            <hr>
            <input type="hidden" name="sort<?php echo $args['id']; ?>" value="<?php echo $columnSort; ?>">
            <input type="hidden" name="order<?php echo $args['id']; ?>" value="<?php echo $orderBy; ?>">
            <input type="hidden" name="page<?php echo $args['id']; ?>" value="1">
            <input type="hidden" name="per_page<?php echo $args['id']; ?>" value="<?php echo $per_page; ?>">
            <input type="hidden" name="lg_id<?php echo $args['id']; ?>" value="<?php echo $LG_ID; ?>">
            <input type="hidden" name="lg_follow<?php echo $args['id']; ?>" value="<?php echo $LG_Follow; ?>">
            <!-- <input type="hidden" name="top_g_id" value="dmshibuya"> -->
            <!-- <input type="hidden" name="max" value="18"> -->
            <!-- <input type="hidden" name="cmd" value=""> -->
            <!-- <input type="hidden" name="other_topgid" value="&#39;dmtachikawa&#39;,&#39;dmhino&#39;,&#39;dmhmurayama&#39;,&#39;hsdk0000&#39;,&#39;hkyg0000000000&#39;,&#39;naka&#39;,&#39;ryokanlist&#39;,&#39;dmmeguro&#39;,&#39;hjodawara&#39;"> -->
            <!-- <input type="hidden" name="page_no" value="9"> -->
            <!-- <input type="hidden" name="search_mode" value=""> -->
            <input type="hidden" name="top_type_visible" value="<?php echo $top_type_visible; ?>">
         </form>
      </div>
      
      <script>
         var data = <?php echo $json_tree; ?>
         
         $('#selectGroupTree').combotree({
            data: data,
            onClick: function(node){
               $('input[name="LG_ID"]').val(node.id);
               var postid = '<?php echo $args['id']; ?>';
               $("form#mainForm"+postid).submit();
            }
         });
         var value = '<?php echo $LG_ID; ?>';
         var t = $('#selectGroupTree').combotree('tree');
         var node = t.tree('find',value);
         if (node){
            $('#selectGroupTree').combotree('setValue', value);
         }
  </script>
   </body>
</html>
