<?php
$tgid = $input_tg_id;
$pattern_no = ServiceCrSet::getPatternNoPosttype($postid);
$dataGet = ServiceCrSet::GetSettingLists($postid, $tgid,$pattern_no, $type_setting);
$arrHide = (isset($dataGet->NotUsed))?$dataGet->NotUsed:"";
$arrShow = (isset($dataGet->Used))?$dataGet->Used:"";
$arrListShow =  array();
$arrListHide = array();
$arrListAll = array();
$arrListPost = array();

if(!empty($arrShow)){
  foreach ($arrShow as $key => $value) {
    $arrListShow[] = $value->ITEM_ID;
    $arrListAll[$value->ITEM_ID] = $value->ITEM_NAME;
  }
}
if(!empty($arrHide)){
  foreach ($arrHide as $key => $value) {
    $arrListAll[$value->ITEM_ID] = $value->ITEM_NAME;
  }
}
?>
    <h1 class="setting_title">表示設定</h1>
    <div class="per_page">
      <label>一覧表示件数 :</label>
      <select name="nakama_event_per_page" class="">
        <?php
          $current_per_page = get_post_meta($postid, 'nakama_service_per_page', true);
          if (empty($current_per_page)) {
            $current_per_page = get_option('nakama_service-general-per-page');
          }
          for($i = 10; $i <= 100; $i += 10) { ?>
            <option value="<?php echo $i; ?>" <?php echo ($i == $current_per_page)?"selected":""?>><?php echo $i; ?></option>
        <?php } ?>
      </select>
    </div>
    <h1 class="setting_title">表示項目</h1>
    <div id="pre_loading" style="display: none;"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) .'assets/img/loadingAnimation.gif'?>"></div>
    <div class="jp-multiselect">
      <div class="from-panel col-5-dual left">
        <h4> 使用できる項目</h4>
        <select name="from[]" class="unselected form-control" style="height: 500px; width: 100%;" multiple="multiple">
          <?php foreach ($arrHide as $item) { ?>
            <option value="<?php echo $item->ITEM_ID; ?>"><?php echo $item->ITEM_NAME; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="move-panel col-2-dual left center-block" style="margin-top: 80px">
        <ul>
          <li>
            <button type="button" class="btn-move-selected-right btn btn-default col-md-offset-2 btn_r" style="margin-bottom: 10px;"></button>
          </li>
          <li>
            <button type="button" class="btn-move-selected-left btn btn-default col-md-offset-2 btn_l"></button>
          </li>
        </ul>
      </div>
      <div class="to-panel col-5-dual left">
        <h4>現在の項目</h4>
        <select id="selected" name="nak_event_key_list_show[]" class="selected form-control" style="height: 500px; width: 100%;" multiple="multiple">
          <?php foreach ($arrShow as $item) { ?>
            <option value="<?php echo $item->ITEM_ID; ?>"><?php echo $item->ITEM_NAME; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="control-panel col-2-dual left center-block" style="margin-top: 80px">
        <ul>
          <li>
            <button type="button" class="btn-up btn btn-default col-md-offset-2 btn_up" style="margin-bottom: 10px;"></button>
          </li>
          <li>
            <button type="button" class="btn-down btn btn-default col-md-offset-2 btn_down"></button>
          </li>
        </ul>
      </div>
    </div>
    <div style="clear: both;"></div>
    <br>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/dual-list-box.js"></script>
    <script type="text/javascript">
      jQuery(".jp-multiselect").jQueryMultiSelection({
        htmlMoveSelectedRight   : "追加 →",
        htmlMoveSelectedLeft    : "← 削除",
        htmlMoveUp              : "↑上に移動",
        htmlMoveDown              : "↓下に移動",
      });
      jQuery(".jp-multiselect").closest('form').submit(function() {
        jQuery("#selected").find('option').prop('selected', true);
      });
    </script>
