<?php
add_action( 'init', 'create_setting_research_post_type' );
function create_setting_research_post_type() {
	register_post_type( 'setting_research',
	    array(
        'labels' => array(
          'name' => __( 'アンケート' ),
          'singular_name' => __( 'アンケート' ),
        ),
        'supports' => array(
            'title',
        ),
        'public' => true,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'register_meta_box_cb' => 'edit_research_button_fields',
        'show_in_menu' => 'edit.php?post_type=setting_research',
        'show_in_nav_menus' => false
	    )
	);
}

function edit_research_button_fields () {
  add_meta_box( 'research_meta_box_type', '', 'research_meta_box_type');
  add_meta_box( 'research_meta_group_id', '', 'research_meta_group_id');
  add_meta_box( 'research_meta_p_id', '', 'research_meta_p_id');
  add_meta_box( 'research_meta_api_key', '', 'research_meta_api_key');
}

add_action('edit_form_after_title','research_edit_form_after_title_function');
function research_edit_form_after_title_function () {
    if (get_post_type() == 'setting_research') {
        research_display_button_generator_page();
    }
}
function research_display_button_generator_page () {
  $post = get_post();
  $top_g_id = get_post_meta($post->ID, 'top_g_id', true);
  $group_id = get_post_meta($post->ID, 'research_meta_group_id', true);
  $p_id = get_post_meta($post->ID, 'research_meta_p_id', true);
  $api_key = get_post_meta($post->ID, 'research_meta_api_key', true);
  $list_research_pattern_no = get_post_meta($post->ID, 'list_research_pattern_no', true);
  $top_type_visible = get_post_meta($post->ID, 'top_type_visible', true);
  $top_type = get_post_meta($post->ID, 'top_type', true);
  $word_back_color = get_post_meta($post->ID, 'word_back_color', true);
  $mail_address = get_post_meta($post->ID, 'mail_address', true);
  $nakama_research_w_first = get_post_meta($post->ID, 'nakama-research-w-first', true);
  $nakama_research_w_second = get_post_meta($post->ID, 'nakama-research-w-second', true);
  $nakama_research_w_third = get_post_meta($post->ID, 'nakama-research-w-third', true);
  $nakama_research_w_four = get_post_meta($post->ID, 'nakama-research-w-four', true);
  $nakama_research_w_five = get_post_meta($post->ID, 'nakama-research-w-five', true);
  $nakama_research_w_six = get_post_meta($post->ID, 'nakama-research-w-six', true);
  $nakama_research_equa_first = get_post_meta($post->ID, 'nakama-research-equa-first', true);
  $nakama_research_equa_second = get_post_meta($post->ID, 'nakama-research-equa-second', true);
  $nakama_research_equa_third = get_post_meta($post->ID, 'nakama-research-equa-third', true);
  $nakama_research_equa_four = get_post_meta($post->ID, 'nakama-research-equa-four', true);
  $nakama_research_equa_five = get_post_meta($post->ID, 'nakama-research-equa-five', true);
  $nakama_research_equa_six = get_post_meta($post->ID, 'nakama-research-equa-six', true);
  $nakama_research_add_second = get_post_meta($post->ID, 'nakama-research-add-second', true);
  $nakama_research_add_third = get_post_meta($post->ID, 'nakama-research-add-third', true);
  $nakama_research_add_four = get_post_meta($post->ID, 'nakama-research-add-four', true);
  $nakama_research_add_five = get_post_meta($post->ID, 'nakama-research-add-five', true);
  $nakama_research_add_six = get_post_meta($post->ID, 'nakama-research-add-six', true);
  $nakama_research_input_first = get_post_meta($post->ID, 'nakama-research-input-first', true);
  $nakama_research_input_second = get_post_meta($post->ID, 'nakama-research-input-second', true);
  $nakama_research_input_third = get_post_meta($post->ID, 'nakama-research-input-third', true);
  $nakama_research_input_four = get_post_meta($post->ID, 'nakama-research-input-four', true);
  $nakama_research_input_five = get_post_meta($post->ID, 'nakama-research-input-five', true);
  $nakama_research_input_six = get_post_meta($post->ID, 'nakama-research-input-six', true);
  $nakama_research_input_first_2 = get_post_meta($post->ID, 'nakama-research-input-first-2', true);
  $nakama_research_input_second_2 = get_post_meta($post->ID, 'nakama-research-input-second-2', true);
  $nakama_research_input_third_2 = get_post_meta($post->ID, 'nakama-research-input-third-2', true);
  $nakama_research_input_four_2 = get_post_meta($post->ID, 'nakama-research-input-four-2', true);
  $nakama_research_input_five_2 = get_post_meta($post->ID, 'nakama-research-input-five-2', true);
  $nakama_research_input_six_2 = get_post_meta($post->ID, 'nakama-research-input-six-2', true);
  $nak_research_key_list_show = get_post_meta($post->ID, 'nak-research-key-list-show', true);
  $nak_research_per_page = get_post_meta($post->ID, 'nak-research-per-page', true);
  $nak_research_sort_column1 = get_post_meta($post->ID, 'nak-research-sort-column1', true);
  $nak_research_sort_column2 = get_post_meta($post->ID, 'nak-research-sort-column2', true);
  $nak_research_sort_column3 = get_post_meta($post->ID, 'nak-research-sort-column3', true);
  $nak_research_sort_column1_orderby = get_post_meta($post->ID, 'nak-research-sort-column1-orderby', true);
  $nak_research_sort_column2_orderby = get_post_meta($post->ID, 'nak-research-sort-column2-orderby', true);
  $nak_research_sort_column3_orderby = get_post_meta($post->ID, 'nak-research-sort-column3-orderby', true);

  $pattern_no_post_type = get_post_meta($post->ID, 'pattern_no_post_type', true);
  require_once(PLUGIN_research_PATH_SETTING . 'admin/settings/regist.php');
}
add_action('save_post_setting_research', 'update_api_setting_research_meta');
function update_api_setting_research_meta ($post_id) {
  if (get_post_type() == 'setting_research') {
    /*Setting Infomation*/
    update_post_meta($post_id, 'top_g_id', isset($_POST['top_g_id']) ? $_POST['top_g_id'] : '');
    update_post_meta($post_id, 'list_research_pattern_no', isset($_POST['list_research_pattern_no'])?$_POST['list_research_pattern_no']:"");
    update_post_meta($post_id, 'top_type_visible', isset($_POST['top_type_visible'])?$_POST['top_type_visible']:"");
    update_post_meta($post_id, 'top_type', isset($_POST['top_type'])?$_POST['top_type']:'');
    update_post_meta($post_id, 'word_back_color', isset($_POST['word_back_color'])?$_POST['word_back_color']:'');
    update_post_meta($post_id, 'mail_address', isset($_POST['mail_address'])?$_POST['mail_address']:'');
    update_post_meta($post_id, 'pattern_no_post_type', isset($_POST['pattern_no_post_type'])?$_POST['pattern_no_post_type']:"");

    update_post_meta($post_id, 'research_meta_group_id', isset($_POST['research_meta_group_id'])?$_POST['research_meta_group_id']:"");
    update_post_meta($post_id, 'research_meta_p_id', isset($_POST['research_meta_p_id'])?$_POST['research_meta_p_id']:"");
    update_post_meta($post_id, 'research_meta_api_key', isset($_POST['research_meta_api_key'])?$_POST['research_meta_api_key']:"");

    /*End Setting Infomation*/
  
    if(isset($_POST['nakama-research-w-first']))
        update_post_meta($post_id, 'nakama-research-w-first',$_POST['nakama-research-w-first']);
    if(isset($_POST['nakama-research-w-second']))
        update_post_meta($post_id, 'nakama-research-w-second', $_POST['nakama-research-w-second']);
    if(isset($_POST['nakama-research-w-third']))
        update_post_meta($post_id, 'nakama-research-w-third',$_POST['nakama-research-w-third']);
    if(isset($_POST['nakama-research-w-four']))
        update_post_meta($post_id, 'nakama-research-w-four',$_POST['nakama-research-w-four']);
    if(isset($_POST['nakama-research-w-five']))
        update_post_meta($post_id, 'nakama-research-w-five',$_POST['nakama-research-w-five']);
    if(isset($_POST['nakama-research-w-six']))
        update_post_meta($post_id, 'nakama-research-w-six',$_POST['nakama-research-w-six']);
    if(isset($_POST['nakama-research-equa-first']))
        update_post_meta($post_id, 'nakama-research-equa-first',$_POST['nakama-research-equa-first']);
    if(isset($_POST['nakama-research-equa-second']))
        update_post_meta($post_id, 'nakama-research-equa-second',$_POST['nakama-research-equa-second']);
    if(isset($_POST['nakama-research-equa-third']))
        update_post_meta($post_id, 'nakama-research-equa-third',$_POST['nakama-research-equa-third']);
    if(isset($_POST['nakama-research-equa-four']))
        update_post_meta($post_id, 'nakama-research-equa-four',$_POST['nakama-research-equa-four']);
    if(isset($_POST['nakama-research-equa-five']))
        update_post_meta($post_id, 'nakama-research-equa-five',$_POST['nakama-research-equa-five']);
    if(isset($_POST['nakama-research-equa-six']))
        update_post_meta($post_id, 'nakama-research-equa-six',$_POST['nakama-research-equa-six']);
    if(isset($_POST['nakama-research-add-second']))
        update_post_meta($post_id, 'nakama-research-add-second',$_POST['nakama-research-add-second']);
    if(isset($_POST['nakama-research-add-third']))
        update_post_meta($post_id, 'nakama-research-add-third',$_POST['nakama-research-add-third']);
    if(isset($_POST['nakama-research-add-four']))
        update_post_meta($post_id, 'nakama-research-add-four',$_POST['nakama-research-add-four']);
    if(isset($_POST['nakama-research-add-five']))
        update_post_meta($post_id, 'nakama-research-add-five',$_POST['nakama-research-add-five']);
    if(isset($_POST['nakama-research-add-six']))
        update_post_meta($post_id, 'nakama-research-add-six',$_POST['nakama-research-add-six']);
    if(isset($_POST['nakama-research-input-first']))
        update_post_meta($post_id, 'nakama-research-input-first',$_POST['nakama-research-input-first']);
    if(isset($_POST['nakama-research-input-second']))
        update_post_meta($post_id, 'nakama-research-input-second',$_POST['nakama-research-input-second']);
    if(isset($_POST['nakama-research-input-third']))
        update_post_meta($post_id, 'nakama-research-input-third',$_POST['nakama-research-input-third']);
    if(isset($_POST['nakama-research-input-four']))
        update_post_meta($post_id, 'nakama-research-input-four',$_POST['nakama-research-input-four']);
    if(isset($_POST['nakama-research-input-five']))
        update_post_meta($post_id, 'nakama-research-input-five',$_POST['nakama-research-input-five']);
    if(isset($_POST['nakama-research-input-six']))
        update_post_meta($post_id, 'nakama-research-input-six',$_POST['nakama-research-input-six']);
    if(isset($_POST['nakama-research-input-first-2']))
        update_post_meta($post_id, 'nakama-research-input-first-2',$_POST['nakama-research-input-first-2']);
    if(isset($_POST['nakama-research-input-second-2']))
        update_post_meta($post_id, 'nakama-research-input-second-2',$_POST['nakama-research-input-second-2']);
    if(isset($_POST['nakama-research-input-third-2']))
        update_post_meta($post_id, 'nakama-research-input-third-2',$_POST['nakama-research-input-third-2']);
    if(isset($_POST['nakama-research-input-four-2']))
        update_post_meta($post_id, 'nakama-research-input-four-2',$_POST['nakama-research-input-four-2']);
    if(isset($_POST['nakama-research-input-five-2']))
        update_post_meta($post_id, 'nakama-research-input-five-2',$_POST['nakama-research-input-five-2']);
    if(isset($_POST['nnakama-research-input-six-2']))
        update_post_meta($post_id, 'nakama-research-input-six-2',$_POST['nakama-research-input-six-2']);

    if(isset($_POST['nak-research-key-list-show']))
        update_post_meta($post_id, 'nak-research-key-list-show',$_POST['nak-research-key-list-show']);
    if(isset($_POST['nak-research-per-page']))
        update_post_meta($post_id, 'nak-research-per-page',$_POST['nak-research-per-page']);
    if(isset($_POST['nak-research-sort-column1']))
        update_post_meta($post_id, 'nak-research-sort-column1',$_POST['nak-research-sort-column1']);
    if(isset($_POST['nak-research-sort-column2']))
        update_post_meta($post_id, 'nak-research-sort-column2',$_POST['nak-research-sort-column2']);
    if(isset($_POST['nak-research-sort-column3']))
        update_post_meta($post_id, 'nak-research-sort-column3',$_POST['nak-research-sort-column3']);
    if(isset($_POST['nak-research-sort-column1-orderby']))
        update_post_meta($post_id, 'nak-research-sort-column1-orderby',$_POST['nak-research-sort-column1-orderby']);
    if(isset($_POST['nak-research-sort-column2-orderby']))
        update_post_meta($post_id, 'nak-research-sort-column2-orderby',$_POST['nak-research-sort-column2-orderby']);
    if(isset($_POST['nak-research-sort-column3-orderby']))
        update_post_meta($post_id, 'nak-research-sort-column3-orderby',$_POST['nak-research-sort-column3-orderby']);
  }
}

function research_setting_admin_style() {
  wp_enqueue_style('admin-research-styles', plugin_dir_url( __FILE__ ).'admin/assets/css/style.css');
  wp_enqueue_script('admin-research-setting-js',plugins_url('admin/assets/js/admin_research.js',__FILE__),'','',true);
}
add_action('admin_enqueue_scripts', 'research_setting_admin_style');

/* SHORT CODE COLUMN */
add_filter('manage_setting_research_posts_columns', 'add_setting_research_shortcode_column');
function add_setting_research_shortcode_column($cols){
  unset($cols['date']);
  $cols['tg_id'] = '団体ID';
  $cols['shortcode'] = 'ショートコード';
  $cols['date'] = 'Date';
  return $cols;
}
add_action('manage_setting_research_posts_custom_column', 'display_setting_research_shortcode_column', 10, 2);
function display_setting_research_shortcode_column( $column, $post_id){
  $tg_id = get_post_meta( $post_id, "top_g_id",true );
  switch ($column) {
    case 'shortcode' :
      echo '<div class="input-group">
              <input class="shortcode-input form-control" data-id="'.$post_id.'" id="shortcode-'.$post_id.'" value="[research-setting id='.$post_id.']" readonly>
              <span class="input-group-addon copy-button"><i class="glyphicon glyphicon-copy"></i></span>
            </div>';
      break;
    case 'tg_id' :
      echo $tg_id;
      break;
  }
}
/* END SHORTCODE COLUMN */
?>