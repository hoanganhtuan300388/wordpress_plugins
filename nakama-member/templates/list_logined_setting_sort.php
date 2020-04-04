<?php
  $allItemSort = isset($_SESSION['list_arr_header']) ? $_SESSION['list_arr_header'] : "";
  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/config/constant.php'); 
  require_once(__ROOT__.'/controller/memberController.php');
  $members = new memberController();
  $MemberCrSet = new MemberCrSet();
  $path_list = isset($_GET['path_list'])?$_GET['path_list']:"nakama-list-member";
  $postid = isset($_GET['postid'])?$_GET['postid']:"";
  $path_page = "nakama-logined-setting-sort";
  $page_link = $members->getPageSlug('nakama-login');
  if(!isset($_SESSION['arrSession'])){
    wp_redirect($page_link.'page_redirect='.$path_page);
    exit();
  }
  $infoUser = $_SESSION['arrSession'];
  $pIdInfo = $infoUser->P_ID;
  $dataAllItem = MemberCrSet::Member_GetSettingLists($postid, $infoUser->TG_ID, 0);
  if(isset($dataAllItem->NotUsed)){
    $dataAllItem = array_merge($dataAllItem->NotUsed, $dataAllItem->Used);
  }
  if(!get_option($pIdInfo.'_setting_sort')){
    $arrItem = [];
    $arrItem[] = [ "item_id" => "", "item_sort" => ""];
    $arrItem[] = [ "item_id" => "", "item_sort" => ""];
    $arrItem[] = [ "item_id" => "", "item_sort" => ""];
    add_option($pIdInfo."_setting_sort", json_encode($arrItem, JSON_UNESCAPED_UNICODE));
  }
  if ($_POST) {
    $column1 = $_POST['nak-member-sort-column1'];
    $column2 = $_POST['nak-member-sort-column2'];
    $column3 = $_POST['nak-member-sort-column3'];
    $column1_orderby = $_POST['nak-member-sort-column1-orderby'];
    $column2_orderby = $_POST['nak-member-sort-column2-orderby'];
    $column3_orderby = $_POST['nak-member-sort-column3-orderby'];
    $arrPostSortItem = array();
    $arrPostSortOrder = array();
    if($column1 != ''){
      $arrPostSortItem[] = [ 'item_id' => $column1, 'item_sort' => $column1_orderby];
    }
    if($column2 != ''){
      $arrPostSortItem[] = [ 'item_id' => $column2, 'item_sort' => $column2_orderby];
    }
    if($column3 != ''){
      $arrPostSortItem[] = [ 'item_id' => $column3, 'item_sort' => $column3_orderby];
    }
    update_option($pIdInfo."_setting_sort", json_encode($arrPostSortItem, JSON_UNESCAPED_UNICODE));
    $user_setting_sort = $MemberCrSet->Member_postSettingSortUser($postid, $arrPostSortItem, $pIdInfo);
    wp_redirect($path_list);
    exit();
  }
  $arrSortShow = get_option($pIdInfo."_setting_sort");
  $arrSortShow = json_decode($arrSortShow);
  $infoUser = $_SESSION['arrSession'];
  $pIdInfo = $infoUser->P_ID;
  $allItemSortConvert = [];
  if(get_option($pIdInfo.'_setting_list_show')){
    $allItemSort = get_option($pIdInfo."_setting_list_show");
    $allItemSort = json_decode(get_option($pIdInfo."_setting_list_show"))->list_item;
    foreach ($allItemSort as $key => $item) {
        array_push($allItemSortConvert, array('column_id' => $item->column_id, 'column_name' => $item->column_name));
    } 
  }
  $allItemSort = $allItemSortConvert;
  foreach($dataAllItem as $item){
    foreach($allItemSort as $key => $sort_item){
      if($item->ITEM_NAME == $sort_item['column_name']){
        $allItemSort[$key]['id'] = $item->ITEM_ID;
      }
    }
  }
  $column1 = isset($arrSortShow[0]->item_id) ? $arrSortShow[0]->item_id : "";
  $column2 = isset($arrSortShow[1]->item_id) ? $arrSortShow[1]->item_id : "";
  $column3 = isset($arrSortShow[2]->item_id) ? $arrSortShow[2]->item_id : "";

  $column1_orderby = isset($arrSortShow[0]->item_sort) ? $arrSortShow[0]->item_sort : "";
  $column2_orderby = isset($arrSortShow[1]->item_sort) ? $arrSortShow[1]->item_sort : "";
  $column3_orderby = isset($arrSortShow[2]->item_sort) ? $arrSortShow[2]->item_sort : "";
?>
<html lang="ja">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
    <title>会員一覧</title>
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/smart.css">
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/front_setting.css">
  </head>
  <body>
    <div class="container">
      <div class="wrap setting_view_list">
        <form method="post" action="">
          <h1 class="setting_title" style="margin-top: 20px">ソート設定</h1>
          <div class="wrap_setting_sort">
            <table class="setting-table-param setting-display-table input_table">
              <tbody>
                <tr class="first-tr flex-sp">
                  <td class="first RegField">
                    順番
                  </td>
                  <td class="RegField">項目</td>
                  <td class="RegField">方向</td>
                </tr>
                <tr class="flex-sp">
                  <td class="RegItem item-min">1</td>
                  <td class="RegValue">
                    <select name="nak-member-sort-column1" onchange="onChangeSelect(this,1)">
                      <option value=""></option>
                      <?php foreach ($allItemSort as $key => $value) { ?>
                        <option value="<?php echo $value['id']; ?>" <?php echo ($value['id'] ==  $column1)?"selected":""?>><?php echo $value['column_name']; ?></option>
                      <?php } ?>
                    </select>
                  </td>
                  <td class="RegValue">
                    <label><input type="radio" value="ASC" id="m_order1" name="nak-member-sort-column1-orderby"
                      <?php echo ($column1_orderby == "ASC")?"checked":"" ?>> ▲昇順</label>
                    <label><input type="radio" value="DESC" id="m_order1_1" name="nak-member-sort-column1-orderby"
                      <?php echo ($column1_orderby == "DESC")?"checked":"" ?>> ▼降順</label>
                  </td>
                </tr>
                <tr class="flex-sp">
                  <td class="RegItem item-min">2</td>
                  <td class="RegValue">
                    <select name="nak-member-sort-column2" onchange="onChangeSelect(this,2)">
                      <option value=""></option>
                      <?php foreach ($allItemSort as $key => $value) { ?>
                        <option value="<?php echo $value['id']; ?>" <?php echo ($value['id'] ==  $column2)?"selected":""?>><?php echo $value['column_name']; ?></option>
                      <?php } ?>
                    </select>
                  </td>
                  <td class="RegValue">
                    <label><input type="radio" value="ASC" id="m_order2" name="nak-member-sort-column2-orderby"
                      <?php echo ($column2_orderby == "ASC")?"checked":"" ?>> ▲昇順</label>
                    <label><input type="radio" value="DESC" id="m_order2_2" name="nak-member-sort-column2-orderby"
                      <?php echo ($column2_orderby == "DESC")?"checked":"" ?>> ▼降順</label>
                  </td>
                </tr>
                <tr class="flex-sp">
                  <td class="RegItem item-min">3</td>
                  <td class="RegValue">
                    <select name="nak-member-sort-column3" onchange="onChangeSelect(this,3)">
                      <option value=""></option>
                      <?php foreach ($allItemSort as $key => $value) { ?>
                        <option value="<?php echo $value['id']; ?>" <?php echo ($value['id'] ==  $column3)?"selected":""?>><?php echo $value['column_name']; ?></option>
                      <?php } ?>
                    </select>
                  </td>
                  <td class="RegValue">
                    <label><input type="radio" value="ASC" id="m_order3" name="nak-member-sort-column3-orderby"
                      <?php echo ($column3_orderby == "ASC")?"checked":"" ?>> ▲昇順</label>
                    <label><input type="radio" value="DESC" id="m_order3_3" name="nak-member-sort-column3-orderby"
                      <?php echo ($column3_orderby == "DESC")?"checked":"" ?>> ▼降順</label>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div style="clear: both;"></div>
          <table align="center" class="btn_controll">
            <tbody>
              <tr>
                <td align="center">
                  <input type="button" class="base_button" value="戻　る" onclick="onBack();">
                  <input type="submit" class="base_button" value="適　用">
                </td>
              </tr>
            </tbody>
          </table>
        </form>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="<?php echo plugin_dir_url( dirname(__FILE__) ); ?>assets/admin/jquery.multi-selection.v1.js"></script>
        <script type="text/javascript">
          $(".jp-multiselect").jQueryMultiSelection({
            htmlMoveSelectedRight   : "追加 →",
            htmlMoveSelectedLeft    : "← 削除",
            htmlMoveUp              : "↑上に移動",
            htmlMoveDown              : "↓下に移動",
          });
          $(".jp-multiselect").closest('form').submit(function() {
            $("#selected").find('option').prop('selected', true);
          });
          function onBack(){
            window.location.href = "<?php echo $path_list; ?>";
          }
          function onChangeSelect(event,id){
            if(event.value != '')
              $("#m_order"+id).prop('required',true);
            else {
              $("#m_order"+id).prop('required',false);
              $("#m_order"+id+"_"+id).prop('checked',false);
              $("#m_order"+id).prop('checked',false);
            }
          }
        </script>
      </div>
    </div>
  </body>
</html>
