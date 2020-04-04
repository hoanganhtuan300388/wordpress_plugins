<?php
  $post = get_post();
  $tg_id = get_post_meta($postid, 'top_g_id', true);
  $pattern_no = MemberCrSet::getPatternNoPosttype($postid);
  $dataGetSort = MemberCrSet::Member_GetSettingSort($postid, $tg_id,$pattern_no);
  $member_key_list_show = (isset($dataGetSort->allItem))?$dataGetSort->allItem:"";
  $member_key_list_sort = (isset($dataGetSort->SortItem))?$dataGetSort->SortItem:"";
  update_post_meta($post->ID, 'nak-member-sort-column1', (!empty($member_key_list_sort[0]->ITEM_ID))? $member_key_list_sort[0]->ITEM_ID:"");
  update_post_meta($post->ID, 'nak-member-sort-column2', (!empty($member_key_list_sort[1]->ITEM_ID))? $member_key_list_sort[1]->ITEM_ID:"");
  update_post_meta($post->ID, 'nak-member-sort-column3', (!empty($member_key_list_sort[2]->ITEM_ID))? $member_key_list_sort[2]->ITEM_ID:"");
  update_post_meta($post->ID, 'nak-member-sort-column1-orderby', (!empty($member_key_list_sort[0]->ITEM_ORDER))? $member_key_list_sort[0]->ITEM_ORDER:"");
  update_post_meta($post->ID, 'nak-member-sort-column2-orderby', (!empty($member_key_list_sort[1]->ITEM_ORDER))? $member_key_list_sort[1]->ITEM_ORDER:"");
  update_post_meta($post->ID, 'nak-member-sort-column3-orderby', (!empty($member_key_list_sort[2]->ITEM_ORDER))? $member_key_list_sort[2]->ITEM_ORDER:"");

  $column1 = get_post_meta($postid, 'nak-member-sort-column1', true);
  $column2 = get_post_meta($postid, 'nak-member-sort-column2', true);
  $column3 = get_post_meta($postid, 'nak-member-sort-column3', true);
  $column1_orderby = get_post_meta($postid, 'nak-member-sort-column1-orderby', true);
  $column2_orderby = get_post_meta($postid, 'nak-member-sort-column2-orderby', true);
  $column3_orderby = get_post_meta($postid, 'nak-member-sort-column3-orderby', true);

  ?>
  <p>会員一覧のソート順を設定します。 </p>
  <p style="color:red"><strong>※「表示項目設定」を行わないと「ソート設定」を行うことはできません。</strong></p>
  <h1 class="setting_title" style="margin-top: 20px">ソート設定</h1>
  <img id="pre_loading" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) .'assets/img/loadingAnimation.gif' ?>" alt="" style="display: none">
  <div class="wrap_setting_sort">
    <table class="setting-table-param setting-display-table setting-sort">
      <tbody>
        <tr class="first-tr">
          <td class="first">
            順番
          </td>
          <td>項目</td>
          <td>方向</td>
        </tr>
        <tr>
          <td>1</td>
          <td>
            <select name="nak-member-sort-column1" onchange="onChangeSelect(this,1)">
              <option value=""></option>
              <?php
              foreach ($member_key_list_show as $key => $item) {
              ?>
                <option value="<?php echo $item->ITEM_ID; ?>" <?php echo ($item->ITEM_ID ==  $column1)?"selected":""?>><?php echo $item->ITEM_NAME; ?></option>
              <?php } ?>
             </select>
          </td>
          <td>
            <label><input type="radio" value="ASC" id="m_order1" name="nak-member-sort-column1-orderby"
              <?php echo ($column1_orderby == "ASC")?"checked":"" ?>> ▲昇順</label>
            <label><input type="radio" value="DESC" id="m_order1_1" class="inline" name="nak-member-sort-column1-orderby"
              <?php echo ($column1_orderby == "DESC")?"checked":"" ?>> ▼降順</label>
          </td>
        </tr>
        <tr>
          <td>2</td>
          <td>
            <select name="nak-member-sort-column2" onchange="onChangeSelect(this,2)">
              <option value=""></option>
              <?php foreach ($member_key_list_show as $key => $item) {
              ?>
                <option value="<?php echo $item->ITEM_ID; ?>" <?php echo ($item->ITEM_ID ==  $column2)?"selected":""?>><?php echo $item->ITEM_NAME; ?></option>
              <?php } ?>
             </select>
          </td>
          <td>
            <label><input type="radio" value="ASC" id="m_order2" name="nak-member-sort-column2-orderby"
              <?php echo ($column2_orderby == "ASC")?"checked":"" ?>> ▲昇順</label>
            <label><input type="radio" value="DESC" id="m_order2_2" class="inline" name="nak-member-sort-column2-orderby"
              <?php echo ($column2_orderby == "DESC")?"checked":"" ?>> ▼降順</label>
          </td>
        </tr>
        <tr>
          <td>3</td>
          <td>
            <select name="nak-member-sort-column3" onchange="onChangeSelect(this,3)">
              <option value=""></option>
              <?php foreach ($member_key_list_show as $key => $item) {
              ?>
                <option value="<?php echo $item->ITEM_ID; ?>" <?php echo ($item->ITEM_ID ==  $column3)?"selected":""?>><?php echo $item->ITEM_NAME; ?></option>
              <?php } ?>
             </select>
          </td>
          <td>
            <label><input type="radio" value="ASC" id="m_order3" name="nak-member-sort-column3-orderby"
              <?php echo ($column3_orderby == "ASC")?"checked":"" ?>> ▲昇順</label>
            <label><input type="radio" value="DESC" id="m_order3_3" class="inline" name="nak-member-sort-column3-orderby"
              <?php echo ($column3_orderby == "DESC")?"checked":"" ?>> ▼降順</label>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div style="clear: both;"></div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript">
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
