<?php
  if($nakama_member_w_first == '' || $nakama_member_equa_first == ''){
    update_post_meta($post->ID, 'nakama-member-equa-first', null);
    update_post_meta($post->ID, 'nakama-member-input-first', null);
    update_post_meta($post->ID, 'nakama-member-input-first-2', null);
    update_post_meta($post->ID, 'nakama-member-w-first', null);
  }
  if($nakama_member_w_second == '' || $nakama_member_equa_second == '' || $nakama_member_add_second == ''){
    update_post_meta($post->ID, 'nakama-member-add-second', null);
    update_post_meta($post->ID, 'nakama-member-equa-second', null);
    update_post_meta($post->ID, 'nakama-member-input-second', null);
    update_post_meta($post->ID, 'nakama-member-input-second-2', null);
    update_post_meta($post->ID, 'nakama-member-w-second', null);
  }
  if($nakama_member_w_third == '' || $nakama_member_equa_third == '' || $nakama_member_add_third == ''){
    update_post_meta($post->ID, 'nakama-member-add-third', null);
    update_post_meta($post->ID, 'nakama-member-equa-third', null);
    update_post_meta($post->ID, 'nakama-member-input-third', null);
    update_post_meta($post->ID, 'nakama-member-input-third-2', null);
    update_post_meta($post->ID, 'nakama-member-w-third', null);
  }
  if($nakama_member_w_four == '' || $nakama_member_equa_four == '' || $nakama_member_add_four == ''){
    update_post_meta($post->ID, 'nakama-member-add-four', null);
    update_post_meta($post->ID, 'nakama-member-equa-four', null);
    update_post_meta($post->ID, 'nakama-member-input-four', null);
    update_post_meta($post->ID, 'nakama-member-input-four-2', null);
    update_post_meta($post->ID, 'nakama-member-w-four', null);
  }
  if($nakama_member_w_five == '' || $nakama_member_equa_five == '' || $nakama_member_add_five == ''){
    update_post_meta($post->ID, 'nakama-member-add-five', null);
    update_post_meta($post->ID, 'nakama-member-equa-five', null);
    update_post_meta($post->ID, 'nakama-member-input-five', null);
    update_post_meta($post->ID, 'nakama-member-input-five-2', null);
    update_post_meta($post->ID, 'nakama-member-w-five', null);
  }
  if($nakama_member_w_six == '' || $nakama_member_equa_six == '' || $nakama_member_add_six == ''){
    update_post_meta($post->ID, 'nakama-member-add-six', null);
    update_post_meta($post->ID, 'nakama-member-equa-six', null);
    update_post_meta($post->ID, 'nakama-member-input-six', null);
    update_post_meta($post->ID, 'nakama-member-input-six-2', null);
    update_post_meta($post->ID, 'nakama-member-w-six', null);
  }

  if ( isset( $_GET['message'] )) {
    $arrPost = array();
    $handleArr = array();
    if($nakama_member_w_second != '' && $nakama_member_equa_second != '' && $nakama_member_add_second != ''){
      $handleArr[] = $nakama_member_add_second;
    }
    if($nakama_member_w_third != '' && $nakama_member_equa_third != '' && $nakama_member_add_third != ''){
      $handleArr[] = $nakama_member_add_third;
    }
    if($nakama_member_w_four != '' && $nakama_member_equa_four != '' && $nakama_member_add_four != ''){
      $handleArr[] = $nakama_member_add_four;
    }
    if($nakama_member_w_five != '' && $nakama_member_equa_five != '' && $nakama_member_add_five != ''){
      $handleArr[] = $nakama_member_add_five;
    }
    if($nakama_member_w_six != '' && $nakama_member_equa_six != '' && $nakama_member_add_six != ''){
      $handleArr[] = $nakama_member_add_six;
    }

    if($nakama_member_w_first != ''){
      $arrPost[] = array(
        'ADD_CLS' => (isset($handleArr[0]))?$handleArr[0]:0,
        'ITEM_ID' => $nakama_member_w_first,
        'COND' => $nakama_member_equa_first,
        'VAL1' => $nakama_member_input_first,
        'VAL2' => $nakama_member_input_first_2,
        'LTG_ID' => '',
        'LG_TYPE' => 0,
        'LG_ID' => '',
        'INC_LOW' => 0,
      );
    }
    if($nakama_member_w_second != ''){
      $arrPost[] = array(
        'ADD_CLS' => (isset($handleArr[1]))?$handleArr[1]:0,
        'ITEM_ID' => $nakama_member_w_second,
        'COND' => $nakama_member_equa_second,
        'VAL1' => $nakama_member_input_second,
        'VAL2' => $nakama_member_input_second_2,
        'LTG_ID' => '',
        'LG_TYPE' => 0,
        'LG_ID' => '',
        'INC_LOW' => 0,
      );
    }
    if($nakama_member_w_third != ''){
      $arrPost[] = array(
        'ADD_CLS' => (isset($handleArr[2]))?$handleArr[2]:0,
        'ITEM_ID' => $nakama_member_w_third,
        'COND' => $nakama_member_equa_third,
        'VAL1' => $nakama_member_input_third,
        'VAL2' => $nakama_member_input_third_2,
        'LTG_ID' => '',
        'LG_TYPE' => 0,
        'LG_ID' => '',
        'INC_LOW' => 0
      );
    }
    if($nakama_member_w_four != ''){
      $arrPost[] = array(
        'ADD_CLS' => (isset($handleArr[3]))?$handleArr[3]:0,
        'ITEM_ID' => $nakama_member_w_four,
        'COND' => $nakama_member_equa_four,
        'VAL1' => $nakama_member_input_four,
        'VAL2' => $nakama_member_input_four_2,
        'LTG_ID' => '',
        'LG_TYPE' => 0,
        'LG_ID' => '',
        'INC_LOW' => 0
      );
    }
    if($nakama_member_w_five != ''){
      $arrPost[] = array(
        'ADD_CLS' => (isset($handleArr[4]))?$handleArr[4]:0,
        'ITEM_ID' => $nakama_member_w_five,
        'COND' => $nakama_member_equa_five,
        'VAL1' => $nakama_member_input_five,
        'VAL2' => $nakama_member_input_five_2,
        'LTG_ID' => '',
        'LG_TYPE' => 0,
        'LG_ID' => '',
        'INC_LOW' => 0
      );
    }
    if($nakama_member_w_six != ''){
      $arrPost[] = array(
        'ADD_CLS' => 0,
        'ITEM_ID' => $nakama_member_w_six,
        'COND' => $nakama_member_equa_six,
        'VAL1' => $nakama_member_input_six,
        'VAL2' => $nakama_member_input_six_2,
        'LTG_ID' => '',
        'LG_TYPE' => 0,
        'LG_ID' => '',
        'INC_LOW' => 0
      );
    }
    $post = MemberCrSet::Member_postSettingWhere($postid, $arrPost);
  }
  $post = get_post();
  $tgid = get_post_meta($postid, 'top_g_id', true);
  if(empty($tgid))
    $tgid = get_option("settingform_save_tg_id");
  $pattern_no = MemberCrSet::getPatternNoPosttype($postid);
  $rs_GetWhereList = MemberCrSet::Member_GetWhereLists($postid, get_post_meta($postid, 'top_g_id', true),$pattern_no);
  $GetWhereList = isset($rs_GetWhereList->WhereItem)?$rs_GetWhereList->WhereItem:"";
  $GetAllWhereList = isset($rs_GetWhereList->allItem)?$rs_GetWhereList->allItem:"";
  update_post_meta($postid, 'nakama-member-add-second', (!empty($GetWhereList[0]->ADD_CLS))? $GetWhereList[0]->ADD_CLS:"");
  update_post_meta($postid, 'nakama-member-add-third', (!empty($GetWhereList[1]->ADD_CLS))? $GetWhereList[1]->ADD_CLS:"");
  update_post_meta($postid, 'nakama-member-add-four', (!empty($GetWhereList[2]->ADD_CLS))? $GetWhereList[2]->ADD_CLS:"");
  update_post_meta($postid, 'nakama-member-add-five', (!empty($GetWhereList[3]->ADD_CLS))? $GetWhereList[3]->ADD_CLS:"");
  update_post_meta($postid, 'nakama-member-add-six', (!empty($GetWhereList[4]->ADD_CLS))? $GetWhereList[4]->ADD_CLS:"");

  update_post_meta($postid, 'nakama-member-w-first', (!empty($GetWhereList[0]->ITEM_ID))? $GetWhereList[0]->ITEM_ID:"");
  update_post_meta($postid, 'nakama-member-w-second', (!empty($GetWhereList[1]->ITEM_ID))? $GetWhereList[1]->ITEM_ID:"");
  update_post_meta($postid, 'nakama-member-w-third', (!empty($GetWhereList[2]->ITEM_ID))? $GetWhereList[2]->ITEM_ID:"");
  update_post_meta($postid, 'nakama-member-w-four', (!empty($GetWhereList[3]->ITEM_ID))? $GetWhereList[3]->ITEM_ID:"");
  update_post_meta($postid, 'nakama-member-w-five', (!empty($GetWhereList[4]->ITEM_ID))? $GetWhereList[4]->ITEM_ID:"");
  update_post_meta($postid, 'nakama-member-w-six', (!empty($GetWhereList[5]->ITEM_ID))? $GetWhereList[5]->ITEM_ID:"");

  update_post_meta($postid, 'nakama-member-equa-first', (!empty($GetWhereList[0]->COND))? $GetWhereList[0]->COND:"");
  update_post_meta($postid, 'nakama-member-equa-second', (!empty($GetWhereList[1]->COND))? $GetWhereList[1]->COND:"");
  update_post_meta($postid, 'nakama-member-equa-third', (!empty($GetWhereList[2]->COND))? $GetWhereList[2]->COND:"");
  update_post_meta($postid, 'nakama-member-equa-four', (!empty($GetWhereList[3]->COND))? $GetWhereList[3]->COND:"");
  update_post_meta($postid, 'nakama-member-equa-five', (!empty($GetWhereList[4]->COND))? $GetWhereList[4]->COND:"");
  update_post_meta($postid, 'nakama-member-equa-six', (!empty($GetWhereList[5]->COND))? $GetWhereList[5]->COND:"");

  update_post_meta($postid, 'nakama-member-input-first', (!empty($GetWhereList[0]->VAL1))? $GetWhereList[0]->VAL1:"");
  update_post_meta($postid, 'nakama-member-input-second', (!empty($GetWhereList[1]->VAL1))? $GetWhereList[1]->VAL1:"");
  update_post_meta($postid, 'nakama-member-input-third', (!empty($GetWhereList[2]->VAL1))? $GetWhereList[2]->VAL1:"");
  update_post_meta($postid, 'nakama-member-input-four', (!empty($GetWhereList[3]->VAL1))? $GetWhereList[3]->VAL1:"");
  update_post_meta($postid, 'nakama-member-input-five', (!empty($GetWhereList[4]->VAL1))? $GetWhereList[4]->VAL1:"");
  update_post_meta($postid, 'nakama-member-input-six', (!empty($GetWhereList[5]->VAL1))? $GetWhereList[5]->VAL1:"");

  update_post_meta($postid, 'nakama-member-input-first-2', (!empty($GetWhereList[0]->VAL2))? $GetWhereList[0]->VAL2:"");
  update_post_meta($postid, 'nakama-member-input-second-2', (!empty($GetWhereList[1]->VAL2))? $GetWhereList[1]->VAL2:"");
  update_post_meta($postid, 'nakama-member-input-third-2', (!empty($GetWhereList[2]->VAL2))? $GetWhereList[2]->VAL2:"");
  update_post_meta($postid, 'nakama-member-input-four-2', (!empty($GetWhereList[3]->VAL2))? $GetWhereList[3]->VAL2:"");
  update_post_meta($postid, 'nakama-member-input-five-2', (!empty($GetWhereList[4]->VAL2))? $GetWhereList[4]->VAL2:"");
  update_post_meta($postid, 'nakama-member-input-six-2', (!empty($GetWhereList[5]->VAL2))? $GetWhereList[5]->VAL2:"");
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
            <td id="nakama-member-w-first">
              <?php echo MemberCrSet::member_renderSelectField($postid,"0", "nakama-member-w-first",$GetAllWhereList); ?>
            </td>
            <td>
              <?php echo MemberCrSet::member_renderSelectEqua($postid,"nakama-member-equa-first", "OnSelChange", "0"); ?>
            </td>
            <td>
              <input class="setting_where_value" type="text" name="nakama-member-input-first" size="30" value="<?php echo get_post_meta($postid,'nakama-member-input-first',true); ?>">
            </td>
            <td class="select_range">
              ~ <input class="setting_where_value_2" type="text" name="nakama-member-input-first-2" size="30" value="<?php echo get_post_meta($postid,'nakama-member-input-first-2',true); ?>">
            </td>
          </tr>
          <tr>
            <td>
                <?php echo MemberCrSet::member_renderAddWhere($postid,'nakama-member-add-second'); ?>
            </td>
            <td id="nakama-member-w-second">
              <?php echo MemberCrSet::member_renderSelectField($postid,"1", 'nakama-member-w-second',$GetAllWhereList); ?>
            </td>
            <td>
              <?php echo MemberCrSet::member_renderSelectEqua($postid,"nakama-member-equa-second", "OnSelChange", "0"); ?>
            </td>
            <td>
              <input class="setting_where_value" type="text" name="nakama-member-input-second" size="30" value="<?php echo get_post_meta($postid,'nakama-member-input-second',true); ?>">
            </td>
            <td  class="select_range">
              ~ <input class="setting_where_value_2" type="text" name="nakama-member-input-second-2" size="30" value="<?php echo get_post_meta($postid,'nakama-member-input-second-2',true); ?>">
            </td>
          </tr>
          <tr>
            <td>
                <?php echo MemberCrSet::member_renderAddWhere($postid,'nakama-member-add-third'); ?>
            </td>
            <td id="nakama-member-w-third">
              <?php echo MemberCrSet::member_renderSelectField($postid,"2", 'nakama-member-w-third',$GetAllWhereList); ?>
            </td>
            <td>
              <?php echo MemberCrSet::member_renderSelectEqua($postid,"nakama-member-equa-third", "OnSelChange", "0"); ?>
            </td>
            <td>
              <input class="setting_where_value" type="text" name="nakama-member-input-third" size="30" value="<?php echo get_post_meta($postid,'nakama-member-input-third',true); ?>">
            </td>
            <td class="select_range">
              ~ <input class="setting_where_value_2" type="text" name="nakama-member-input-third-2" size="30" value="<?php echo get_post_meta($postid,'nakama-member-input-third-3',true); ?>">
            </td>
          </tr>
          <tr>
            <td>
                <?php echo MemberCrSet::member_renderAddWhere($postid,'nakama-member-add-four'); ?>
            </td>
            <td id="nakama-member-w-four">
              <?php echo MemberCrSet::member_renderSelectField($postid,"3", 'nakama-member-w-four',$GetAllWhereList); ?>
            </td>
            <td>
              <?php echo MemberCrSet::member_renderSelectEqua($postid,"nakama-member-equa-four", "OnSelChange", "0"); ?>
            </td>
            <td>
              <input class="setting_where_value" type="text" name="nakama-member-input-four" size="30" value="<?php echo get_post_meta($postid,'nakama-member-input-four',true); ?>">
            </td>
            <td class="select_range">
              ~ <input class="setting_where_value_2" type="text" name="nakama-member-input-four-2" size="30" value="<?php echo get_post_meta($postid,'nakama-member-input-four-2',true); ?>">
            </td>
          </tr>
          <tr>
            <td>
                <?php echo MemberCrSet::member_renderAddWhere($postid,'nakama-member-add-five'); ?>
            </td>
            <td id="nakama-member-w-five">
              <?php echo MemberCrSet::member_renderSelectField($postid,"4", 'nakama-member-w-five',$GetAllWhereList); ?>
            </td>
            <td>
              <?php echo MemberCrSet::member_renderSelectEqua($postid,"nakama-member-equa-five", "OnSelChange", "0"); ?>
            </td>
            <td>
              <input class="setting_where_value" type="text" name="nakama-member-input-five" size="30" value="<?php echo get_post_meta($postid,'nakama-member-input-five',true); ?>">
            </td>
            <td class="select_range">
              ~ <input class="setting_where_value_2" type="text" name="nakama-member-input-five-2" size="30" value="<?php echo get_post_meta($postid,'nakama-member-input-five-2',true); ?>">
            </td>
          </tr>
          <tr>
            <td>
                <?php echo MemberCrSet::member_renderAddWhere($postid,'nakama-member-add-six'); ?>
            </td>
            <td id="nakama-member-w-six">
              <?php echo MemberCrSet::member_renderSelectField($postid,"5", 'nakama-member-w-six',$GetAllWhereList); ?>
            </td>
            <td>
              <?php echo MemberCrSet::member_renderSelectEqua($postid,"nakama-member-equa-six", "OnSelChange", "0"); ?>
            </td>
            <td>
              <input class="setting_where_value" type="text" name="nakama-member-input-six" size="30" value="<?php echo get_post_meta($postid,'nakama-member-input-six',true); ?>">
            </td>
            <td  class="select_range">
              ~ <input class="setting_where_value_2" type="text" name="nakama-member-input-six-2" size="30" value="<?php echo get_post_meta($postid,'nakama-member-input-six-2',true); ?>">
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