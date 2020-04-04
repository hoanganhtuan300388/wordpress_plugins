
<?php
  if($nakama_research_w_first == '' || $nakama_research_equa_first == ''){
    update_post_meta($post->ID, 'nakama-research-equa-first', null);
    update_post_meta($post->ID, 'nakama-research-input-first', null);
    update_post_meta($post->ID, 'nakama-research-input-first-2', null);
    update_post_meta($post->ID, 'nakama-research-w-first', null);
  }
  if($nakama_research_w_second == '' || $nakama_research_equa_second == '' || $nakama_research_add_second == ''){
    update_post_meta($post->ID, 'nakama-research-add-second', null);
    update_post_meta($post->ID, 'nakama-research-equa-second', null);
    update_post_meta($post->ID, 'nakama-research-input-second', null);
    update_post_meta($post->ID, 'nakama-research-input-second-2', null);
    update_post_meta($post->ID, 'nakama-research-w-second', null);
  }
  if($nakama_research_w_third == '' || $nakama_research_equa_third == '' || $nakama_research_add_third == ''){
    update_post_meta($post->ID, 'nakama-research-add-third', null);
    update_post_meta($post->ID, 'nakama-research-equa-third', null);
    update_post_meta($post->ID, 'nakama-research-input-third', null);
    update_post_meta($post->ID, 'nakama-research-input-third-2', null);
    update_post_meta($post->ID, 'nakama-research-w-third', null);
  }
  if($nakama_research_w_four == '' || $nakama_research_equa_four == '' || $nakama_research_add_four == ''){
    update_post_meta($post->ID, 'nakama-research-add-four', null);
    update_post_meta($post->ID, 'nakama-research-equa-four', null);
    update_post_meta($post->ID, 'nakama-research-input-four', null);
    update_post_meta($post->ID, 'nakama-research-input-four-2', null);
    update_post_meta($post->ID, 'nakama-research-w-four', null);
  }
  if($nakama_research_w_five == '' || $nakama_research_equa_five == '' || $nakama_research_add_five == ''){
    update_post_meta($post->ID, 'nakama-research-add-five', null);
    update_post_meta($post->ID, 'nakama-research-equa-five', null);
    update_post_meta($post->ID, 'nakama-research-input-five', null);
    update_post_meta($post->ID, 'nakama-research-input-five-2', null);
    update_post_meta($post->ID, 'nakama-research-w-five', null);
  }
  if($nakama_research_w_six == '' || $nakama_research_equa_six == '' || $nakama_research_add_six == ''){
    update_post_meta($post->ID, 'nakama-research-add-six', null);
    update_post_meta($post->ID, 'nakama-research-equa-six', null);
    update_post_meta($post->ID, 'nakama-research-input-six', null);
    update_post_meta($post->ID, 'nakama-research-input-six-2', null);
    update_post_meta($post->ID, 'nakama-research-w-six', null);
  }
  $post = get_post();
  $tgid = get_post_meta($postid, 'top_g_id', true);
  
  $pattern_no = researchCrSet::getPatternNoPosttype($postid);
  $rs_GetWhereList = researchCrSet::research_GetWhereLists($postid, get_post_meta($postid, 'top_g_id', true),$pattern_no);
  $GetWhereList = isset($rs_GetWhereList->WhereItem)?$rs_GetWhereList->WhereItem:"";
  $GetAllWhereList = isset($rs_GetWhereList->allItem)?$rs_GetWhereList->allItem:"";
  update_post_meta($postid, 'nakama-research-add-second', (!empty($GetWhereList[0]->ADD_CLS))? $GetWhereList[0]->ADD_CLS:"");
  update_post_meta($postid, 'nakama-research-add-third', (!empty($GetWhereList[1]->ADD_CLS))? $GetWhereList[1]->ADD_CLS:"");
  update_post_meta($postid, 'nakama-research-add-four', (!empty($GetWhereList[2]->ADD_CLS))? $GetWhereList[2]->ADD_CLS:"");
  update_post_meta($postid, 'nakama-research-add-five', (!empty($GetWhereList[3]->ADD_CLS))? $GetWhereList[3]->ADD_CLS:"");
  update_post_meta($postid, 'nakama-research-add-six', (!empty($GetWhereList[4]->ADD_CLS))? $GetWhereList[4]->ADD_CLS:"");

  update_post_meta($postid, 'nakama-research-w-first', (!empty($GetWhereList[0]->ITEM_ID))? $GetWhereList[0]->ITEM_ID:"");
  update_post_meta($postid, 'nakama-research-w-second', (!empty($GetWhereList[1]->ITEM_ID))? $GetWhereList[1]->ITEM_ID:"");
  update_post_meta($postid, 'nakama-research-w-third', (!empty($GetWhereList[2]->ITEM_ID))? $GetWhereList[2]->ITEM_ID:"");
  update_post_meta($postid, 'nakama-research-w-four', (!empty($GetWhereList[3]->ITEM_ID))? $GetWhereList[3]->ITEM_ID:"");
  update_post_meta($postid, 'nakama-research-w-five', (!empty($GetWhereList[4]->ITEM_ID))? $GetWhereList[4]->ITEM_ID:"");
  update_post_meta($postid, 'nakama-research-w-six', (!empty($GetWhereList[5]->ITEM_ID))? $GetWhereList[5]->ITEM_ID:"");

  update_post_meta($postid, 'nakama-research-equa-first', (!empty($GetWhereList[0]->COND))? $GetWhereList[0]->COND:"");
  update_post_meta($postid, 'nakama-research-equa-second', (!empty($GetWhereList[1]->COND))? $GetWhereList[1]->COND:"");
  update_post_meta($postid, 'nakama-research-equa-third', (!empty($GetWhereList[2]->COND))? $GetWhereList[2]->COND:"");
  update_post_meta($postid, 'nakama-research-equa-four', (!empty($GetWhereList[3]->COND))? $GetWhereList[3]->COND:"");
  update_post_meta($postid, 'nakama-research-equa-five', (!empty($GetWhereList[4]->COND))? $GetWhereList[4]->COND:"");
  update_post_meta($postid, 'nakama-research-equa-six', (!empty($GetWhereList[5]->COND))? $GetWhereList[5]->COND:"");

  update_post_meta($postid, 'nakama-research-input-first', (!empty($GetWhereList[0]->VAL1))? $GetWhereList[0]->VAL1:"");
  update_post_meta($postid, 'nakama-research-input-second', (!empty($GetWhereList[1]->VAL1))? $GetWhereList[1]->VAL1:"");
  update_post_meta($postid, 'nakama-research-input-third', (!empty($GetWhereList[2]->VAL1))? $GetWhereList[2]->VAL1:"");
  update_post_meta($postid, 'nakama-research-input-four', (!empty($GetWhereList[3]->VAL1))? $GetWhereList[3]->VAL1:"");
  update_post_meta($postid, 'nakama-research-input-five', (!empty($GetWhereList[4]->VAL1))? $GetWhereList[4]->VAL1:"");
  update_post_meta($postid, 'nakama-research-input-six', (!empty($GetWhereList[5]->VAL1))? $GetWhereList[5]->VAL1:"");

  update_post_meta($postid, 'nakama-research-input-first-2', (!empty($GetWhereList[0]->VAL2))? $GetWhereList[0]->VAL2:"");
  update_post_meta($postid, 'nakama-research-input-second-2', (!empty($GetWhereList[1]->VAL2))? $GetWhereList[1]->VAL2:"");
  update_post_meta($postid, 'nakama-research-input-third-2', (!empty($GetWhereList[2]->VAL2))? $GetWhereList[2]->VAL2:"");
  update_post_meta($postid, 'nakama-research-input-four-2', (!empty($GetWhereList[3]->VAL2))? $GetWhereList[3]->VAL2:"");
  update_post_meta($postid, 'nakama-research-input-five-2', (!empty($GetWhereList[4]->VAL2))? $GetWhereList[4]->VAL2:"");
  update_post_meta($postid, 'nakama-research-input-six-2', (!empty($GetWhereList[5]->VAL2))? $GetWhereList[5]->VAL2:"");
?>
  <p> 会員一覧の初期表示条件を設定します。 </p>
  <p style="color:red"><strong>※「連携情報設定」を行わないと「初期表示条件設定」を行うことはできません。</strong></p>
  <h1 class="setting_title">初期表示条件</h1>
      <img id="pre_loading" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) .'assets/img/loadingAnimation.gif' ?>" alt="" style="display: none">
      <table id="table_setting_display">
          <tr>
            <td>
              <select class="add_where" style="display: none;">
                <option selected="" value=""></option>
              </select>
            </td>
            <td id="nakama-research-w-first">
              <?php echo researchCrSet::research_renderSelectField($postid,"0", "nakama-research-w-first",$GetAllWhereList); ?>
            </td>
            <td>
              <?php echo researchCrSet::research_renderSelectEqua($postid,"nakama-research-equa-first", "OnSelChange", "0"); ?>
            </td>
            <td>
              <input class="setting_where_value" type="text" name="nakama-research-input-first" size="30" value="<?php echo get_post_meta($postid,'nakama-research-input-first',true); ?>">
            </td>
            <td class="select_range">
              ~ <input class="setting_where_value_2" type="text" name="nakama-research-input-first-2" size="30" value="<?php echo get_post_meta($postid,'nakama-research-input-first-2',true); ?>">
            </td>
          </tr>
          <tr>
            <td>
                <?php echo researchCrSet::research_renderAddWhere($postid,'nakama-research-add-second'); ?>
            </td>
            <td id="nakama-research-w-second">
              <?php echo researchCrSet::research_renderSelectField($postid,"1", 'nakama-research-w-second',$GetAllWhereList); ?>
            </td>
            <td>
              <?php echo researchCrSet::research_renderSelectEqua($postid,"nakama-research-equa-second", "OnSelChange", "0"); ?>
            </td>
            <td>
              <input class="setting_where_value" type="text" name="nakama-research-input-second" size="30" value="<?php echo get_post_meta($postid,'nakama-research-input-second',true); ?>">
            </td>
            <td  class="select_range">
              ~ <input class="setting_where_value_2" type="text" name="nakama-research-input-second-2" size="30" value="<?php echo get_post_meta($postid,'nakama-research-input-second-2',true); ?>">
            </td>
          </tr>
          <tr>
            <td>
                <?php echo researchCrSet::research_renderAddWhere($postid,'nakama-research-add-third'); ?>
            </td>
            <td id="nakama-research-w-third">
              <?php echo researchCrSet::research_renderSelectField($postid,"2", 'nakama-research-w-third',$GetAllWhereList); ?>
            </td>
            <td>
              <?php echo researchCrSet::research_renderSelectEqua($postid,"nakama-research-equa-third", "OnSelChange", "0"); ?>
            </td>
            <td>
              <input class="setting_where_value" type="text" name="nakama-research-input-third" size="30" value="<?php echo get_post_meta($postid,'nakama-research-input-third',true); ?>">
            </td>
            <td class="select_range">
              ~ <input class="setting_where_value_2" type="text" name="nakama-research-input-third-2" size="30" value="<?php echo get_post_meta($postid,'nakama-research-input-third-3',true); ?>">
            </td>
          </tr>
          <tr>
            <td>
                <?php echo researchCrSet::research_renderAddWhere($postid,'nakama-research-add-four'); ?>
            </td>
            <td id="nakama-research-w-four">
              <?php echo researchCrSet::research_renderSelectField($postid,"3", 'nakama-research-w-four',$GetAllWhereList); ?>
            </td>
            <td>
              <?php echo researchCrSet::research_renderSelectEqua($postid,"nakama-research-equa-four", "OnSelChange", "0"); ?>
            </td>
            <td>
              <input class="setting_where_value" type="text" name="nakama-research-input-four" size="30" value="<?php echo get_post_meta($postid,'nakama-research-input-four',true); ?>">
            </td>
            <td class="select_range">
              ~ <input class="setting_where_value_2" type="text" name="nakama-research-input-four-2" size="30" value="<?php echo get_post_meta($postid,'nakama-research-input-four-2',true); ?>">
            </td>
          </tr>
          <tr>
            <td>
                <?php echo researchCrSet::research_renderAddWhere($postid,'nakama-research-add-five'); ?>
            </td>
            <td id="nakama-research-w-five">
              <?php echo researchCrSet::research_renderSelectField($postid,"4", 'nakama-research-w-five',$GetAllWhereList); ?>
            </td>
            <td>
              <?php echo researchCrSet::research_renderSelectEqua($postid,"nakama-research-equa-five", "OnSelChange", "0"); ?>
            </td>
            <td>
              <input class="setting_where_value" type="text" name="nakama-research-input-five" size="30" value="<?php echo get_post_meta($postid,'nakama-research-input-five',true); ?>">
            </td>
            <td class="select_range">
              ~ <input class="setting_where_value_2" type="text" name="nakama-research-input-five-2" size="30" value="<?php echo get_post_meta($postid,'nakama-research-input-five-2',true); ?>">
            </td>
          </tr>
          <tr>
            <td>
                <?php echo researchCrSet::research_renderAddWhere($postid,'nakama-research-add-six'); ?>
            </td>
            <td id="nakama-research-w-six">
              <?php echo researchCrSet::research_renderSelectField($postid,"5", 'nakama-research-w-six',$GetAllWhereList); ?>
            </td>
            <td>
              <?php echo researchCrSet::research_renderSelectEqua($postid,"nakama-research-equa-six", "OnSelChange", "0"); ?>
            </td>
            <td>
              <input class="setting_where_value" type="text" name="nakama-research-input-six" size="30" value="<?php echo get_post_meta($postid,'nakama-research-input-six',true); ?>">
            </td>
            <td  class="select_range">
              ~ <input class="setting_where_value_2" type="text" name="nakama-research-input-six-2" size="30" value="<?php echo get_post_meta($postid,'nakama-research-input-six-2',true); ?>">
            </td>
          </tr>
      </table>
      <br>
      <script>
        jQuery('select.choise_codition').each(function(index, el) {
          jQuery(this).change(function(event) {
            var choise = jQuery(this).val();
            var parent = jQuery(this).parents('tr');
            if(choise == '3'){
              parent.find('td.select_range').show();
            }else{
              parent.find('td.select_range').hide();
            }
          });
          var choise = jQuery(this).val();
          var parent = jQuery(this).parents('tr');
          if(choise == '3'){
            parent.find('td.select_range').show();
          }else{
            parent.find('td.select_range').hide();
          }
        });
      </script>