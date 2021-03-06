<?php
$post = get_post();
if ( isset( $_GET['message'] )) {
  $postid = $post->ID;
  // post setting view
  $arrSetting = $nak_research_key_list_show;
  $arrPostSetting = array();
  if(!empty($arrSetting)){
    foreach ($arrSetting as $key => $value) {
      $arrPostSetting[] = ["item_id" => $value];
    }
  }
  researchCrSet::research_postSettingLists($postid, $arrPostSetting);
  // post setting where
  $arrPost = array();
  $handleArr = array();
  if($nakama_research_w_second != '' && $nakama_research_equa_second != '' && $nakama_research_add_second != ''){
    $handleArr[] = $nakama_research_add_second;
  }
  if($nakama_research_w_third != '' && $nakama_research_equa_third != '' && $nakama_research_add_third != ''){
    $handleArr[] = $nakama_research_add_third;
  }
  if($nakama_research_w_four != '' && $nakama_research_equa_four != '' && $nakama_research_add_four != ''){
    $handleArr[] = $nakama_research_add_four;
  }
  if($nakama_research_w_five != '' && $nakama_research_equa_five != '' && $nakama_research_add_five != ''){
    $handleArr[] = $nakama_research_add_five;
  }
  if($nakama_research_w_six != '' && $nakama_research_equa_six != '' && $nakama_research_add_six != ''){
    $handleArr[] = $nakama_research_add_six;
  }

  if($nakama_research_w_first != ''){
    $arrPost[] = array(
      'ADD_CLS' => (isset($handleArr[0]))?$handleArr[0]:0,
      'ITEM_ID' => $nakama_research_w_first,
      'COND' => $nakama_research_equa_first,
      'VAL1' => $nakama_research_input_first,
      'VAL2' => $nakama_research_input_first_2,
      'LTG_ID' => '',
      'LG_TYPE' => 0,
      'LG_ID' => '',
      'INC_LOW' => 0,
    );
  }
  if($nakama_research_w_second != ''){
    $arrPost[] = array(
      'ADD_CLS' => (isset($handleArr[1]))?$handleArr[1]:0,
      'ITEM_ID' => $nakama_research_w_second,
      'COND' => $nakama_research_equa_second,
      'VAL1' => $nakama_research_input_second,
      'VAL2' => $nakama_research_input_second_2,
      'LTG_ID' => '',
      'LG_TYPE' => 0,
      'LG_ID' => '',
      'INC_LOW' => 0,
    );
  }
  if($nakama_research_w_third != ''){
    $arrPost[] = array(
      'ADD_CLS' => (isset($handleArr[2]))?$handleArr[2]:0,
      'ITEM_ID' => $nakama_research_w_third,
      'COND' => $nakama_research_equa_third,
      'VAL1' => $nakama_research_input_third,
      'VAL2' => $nakama_research_input_third_2,
      'LTG_ID' => '',
      'LG_TYPE' => 0,
      'LG_ID' => '',
      'INC_LOW' => 0
    );
  }
  if($nakama_research_w_four != ''){
    $arrPost[] = array(
      'ADD_CLS' => (isset($handleArr[3]))?$handleArr[3]:0,
      'ITEM_ID' => $nakama_research_w_four,
      'COND' => $nakama_research_equa_four,
      'VAL1' => $nakama_research_input_four,
      'VAL2' => $nakama_research_input_four_2,
      'LTG_ID' => '',
      'LG_TYPE' => 0,
      'LG_ID' => '',
      'INC_LOW' => 0
    );
  }
  if($nakama_research_w_five != ''){
    $arrPost[] = array(
      'ADD_CLS' => (isset($handleArr[4]))?$handleArr[4]:0,
      'ITEM_ID' => $nakama_research_w_five,
      'COND' => $nakama_research_equa_five,
      'VAL1' => $nakama_research_input_five,
      'VAL2' => $nakama_research_input_five_2,
      'LTG_ID' => '',
      'LG_TYPE' => 0,
      'LG_ID' => '',
      'INC_LOW' => 0
    );
  }
  if($nakama_research_w_six != ''){
    $arrPost[] = array(
      'ADD_CLS' => 0,
      'ITEM_ID' => $nakama_research_w_six,
      'COND' => $nakama_research_equa_six,
      'VAL1' => $nakama_research_input_six,
      'VAL2' => $nakama_research_input_six_2,
      'LTG_ID' => '',
      'LG_TYPE' => 0,
      'LG_ID' => '',
      'INC_LOW' => 0
    );
  }
  researchCrSet::research_postSettingWhere($postid, $arrPost);
  // setting sort
  $column1 = $nak_research_sort_column1;
  $column2 = $nak_research_sort_column2;
  $column3 = $nak_research_sort_column3;
  $column1_orderby = $nak_research_sort_column1_orderby;
  $column2_orderby = $nak_research_sort_column2_orderby;
  $column3_orderby = $nak_research_sort_column3_orderby;
  $arrPostSortItem = array();
  if($column1 != ''){
    $arrPostSortItem[] = [ "item_id" => $column1 , "item_sort" => $column1_orderby];
  }
  if($column2 != ''){
    $arrPostSortItem[] = [ "item_id" => $column2, "item_sort" => $column2_orderby];
  }
  if($column3 != ''){
    $arrPostSortItem[] = [ "item_id" => $column3, "item_sort" => $column3_orderby];
  }
  $postSettingSort = researchCrSet::research_postSettingSort($postid, $arrPostSortItem);
}
?>
<div class="list_research type_research">
  <ul id="tab_research">
    <li class="ui-tabs-active">
      <span class="first">連携情報設定</span>
    </li>
    <li>
      <span class="second">初期表示条件設定</span>
    </li>
    <li>
      <span class="third">表示項目設定</span>
    </li>
    <li>
      <span class="four">ソート設定</span>
    </li>
  </ul>
  <div class="content_view">
      <span id="img_loading_tab" style="display: none;">
        <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) .'assets/img/loadingAnimation.gif' ?>" alt="">
      </span>
      <div class="wrap setting_view_list window_close_class content-in content-out-first">
        <?php include(PLUGIN_research_PATH_SETTING . 'admin/settings/setting_information.php'); ?>
      </div>
      <div class="wrap setting_view_list content-in content-out-second" style="display: none">
      </div>
      <div class="wrap setting_view_list content-in content-out-third" id="second" style="display: none;">
      </div>
      <div class="wrap setting_view_list content-in content-out-four" style="display: none;">
      </div>
  </div>
</div>
