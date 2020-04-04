<?php
add_action( 'init', 'create_setting_member_post_type' );
function create_setting_member_post_type() {
    register_post_type( 'setting_member',
        array(
            'labels' => array(
                'name' => __( 'フォーム設定' ),
                'singular_name' => __( 'フォーム設定' ),
                'add_new_item' => 'フォーム新規追加',
                'edit_item' => 'フォームの編集',
            ),
            'supports' => array(
                'title',
            ),
            'public' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'register_meta_box_cb' => 'edit_member_button_fields',
            'show_in_menu' => 'edit.php?post_type=setting_member',
            'show_in_nav_menus' => false
        )
    );
}
function edit_member_button_fields () {
    add_meta_box( 'member_meta_box_type', '', 'member_meta_box_type');
    add_meta_box( 'member_meta_group_id', '', 'member_meta_group_id');
    add_meta_box( 'member_meta_p_id', '', 'member_meta_p_id');
    add_meta_box( 'member_meta_api_key', '', 'member_meta_api_key');
    add_meta_box( 'list_member_tg_id', '', 'list_member_tg_id');
    add_meta_box( 'list_member_p_id', '', 'list_member_p_id');
    add_meta_box( 'member_meta_short_code', '', 'member_meta_short_code');
}
add_action('edit_form_after_title','member_edit_form_after_title_function');

function member_edit_form_after_title_function () {
    if (get_post_type() == 'setting_member') {
        member_display_button_generator_page();
    }
}
function member_display_button_generator_page () {
    $post = get_post();
    $type_setting = get_post_meta($post->ID, 'member_meta_box_type', true);
    $group_id = get_post_meta($post->ID, 'member_meta_group_id', true);
    $p_id = get_post_meta($post->ID, 'member_meta_p_id', true);
    $api_key = get_post_meta($post->ID, 'member_meta_api_key', true);
    $list_member_tg_id = get_post_meta($post->ID, 'list_member_tg_id', true);
    $list_member_p_id = get_post_meta($post->ID, 'list_member_p_id', true);
    $list_member_pattern_no = get_post_meta($post->ID, 'list_member_pattern_no', true);
    $list_member_site_id = get_post_meta($post->ID, 'list_member_site_id', true);
    $list_member_page_no = get_post_meta($post->ID, 'list_member_page_no', true);
    $list_member_title = get_post_meta($post->ID, 'list_member_title', true);
    $list_member_information_category = get_post_meta($post->ID, 'list_member_information_category', true);
    $list_member_disp_sort = get_post_meta($post->ID, 'list_member_disp_sort', true);
    $list_img_visible = get_post_meta($post->ID, 'list_img_visible', true);
    $img_visible_card_list = get_post_meta($post->ID, 'img_visible_card_list', true);
    $nakama_w_first = get_post_meta($post->ID, 'nakama_w_first', true);
    $nakama_w_first = get_post_meta($post->ID, 'nakama_w_first', true);
    $short_code = get_post_meta($post->ID, 'member_meta_short_code', true);
    $nakama_member_w_first = get_post_meta($post->ID, 'nakama-member-w-first', true);
    $nakama_member_w_second = get_post_meta($post->ID, 'nakama-member-w-second', true);
    $nakama_member_w_third = get_post_meta($post->ID, 'nakama-member-w-third', true);
    $nakama_member_w_four = get_post_meta($post->ID, 'nakama-member-w-four', true);
    $nakama_member_w_five = get_post_meta($post->ID, 'nakama-member-w-five', true);
    $nakama_member_w_six = get_post_meta($post->ID, 'nakama-member-w-six', true);
    $nakama_member_equa_first = get_post_meta($post->ID, 'nakama-member-equa-first', true);
    $nakama_member_equa_second = get_post_meta($post->ID, 'nakama-member-equa-second', true);
    $nakama_member_equa_third = get_post_meta($post->ID, 'nakama-member-equa-third', true);
    $nakama_member_equa_four = get_post_meta($post->ID, 'nakama-member-equa-four', true);
    $nakama_member_equa_five = get_post_meta($post->ID, 'nakama-member-equa-five', true);
    $nakama_member_equa_six = get_post_meta($post->ID, 'nakama-member-equa-six', true);
    $nakama_member_add_second = get_post_meta($post->ID, 'nakama-member-add-second', true);
    $nakama_member_add_third = get_post_meta($post->ID, 'nakama-member-add-third', true);
    $nakama_member_add_four = get_post_meta($post->ID, 'nakama-member-add-four', true);
    $nakama_member_add_five = get_post_meta($post->ID, 'nakama-member-add-five', true);
    $nakama_member_add_six = get_post_meta($post->ID, 'nakama-member-add-six', true);
    $nakama_member_input_first = get_post_meta($post->ID, 'nakama-member-input-first', true);
    $nakama_member_input_second = get_post_meta($post->ID, 'nakama-member-input-second', true);
    $nakama_member_input_third = get_post_meta($post->ID, 'nakama-member-input-third', true);
    $nakama_member_input_four = get_post_meta($post->ID, 'nakama-member-input-four', true);
    $nakama_member_input_five = get_post_meta($post->ID, 'nakama-member-input-five', true);
    $nakama_member_input_six = get_post_meta($post->ID, 'nakama-member-input-six', true);
    $nakama_member_input_first_2 = get_post_meta($post->ID, 'nakama-member-input-first-2', true);
    $nakama_member_input_second_2 = get_post_meta($post->ID, 'nakama-member-input-second-2', true);
    $nakama_member_input_third_2 = get_post_meta($post->ID, 'nakama-member-input-third-2', true);
    $nakama_member_input_four_2 = get_post_meta($post->ID, 'nakama-member-input-four-2', true);
    $nakama_member_input_five_2 = get_post_meta($post->ID, 'nakama-member-input-five-2', true);
    $nakama_member_input_six_2 = get_post_meta($post->ID, 'nakama-member-input-six-2', true);
    $nak_member_key_list_show = get_post_meta($post->ID, 'nak-member-key-list-show', true);
    $nak_member_per_page = get_post_meta($post->ID, 'nak-member-per-page', true);
    $nak_member_sort_column1 = get_post_meta($post->ID, 'nak-member-sort-column1', true);
    $nak_member_sort_column2 = get_post_meta($post->ID, 'nak-member-sort-column2', true);
    $nak_member_sort_column3 = get_post_meta($post->ID, 'nak-member-sort-column3', true);
    $nak_member_sort_column1_orderby = get_post_meta($post->ID, 'nak-member-sort-column1-orderby', true);
    $nak_member_sort_column2_orderby = get_post_meta($post->ID, 'nak-member-sort-column2-orderby', true);
    $nak_member_sort_column3_orderby = get_post_meta($post->ID, 'nak-member-sort-column3-orderby', true);

    $top_g_id = get_post_meta($post->ID, 'top_g_id', true);
    $set_lg_g_id = get_post_meta($post->ID, 'set_lg_g_id', true);
    $set_g_id = get_post_meta($post->ID, 'set_g_id', true);
    $mail_address = get_post_meta($post->ID, 'mail_address', true);
    $linkage_type = get_post_meta($post->ID, 'linkage_type', true);
    $lg_login = get_post_meta($post->ID, 'lg_login', true);
    $lg_disp = get_post_meta($post->ID, 'lg_disp', true);
    $disp_menu = get_post_meta($post->ID, 'disp_menu', true);
    $login_name = get_post_meta($post->ID, 'login_name', true);
    $login_caption = get_post_meta($post->ID, 'login_caption', true);
    $group_leader = get_post_meta($post->ID, 'group_leader', true);
    $top_type_visible = get_post_meta($post->ID, 'top_type_visible', true);
    $top_type = get_post_meta($post->ID, 'top_type', true);
    $marketing_visible = get_post_meta($post->ID, 'marketing_visible', true);
    $marketing_mail = get_post_meta($post->ID, 'marketing_mail', true);
    $category_visible = get_post_meta($post->ID, 'category_visible', true);
    $regist_name = get_post_meta($post->ID, 'regist_name', true);
    $regist_caption = get_post_meta($post->ID, 'regist_caption', true);
    $disp_header_file_reg = get_post_meta($post->ID, 'disp_header_file_reg', true);
    $merumaga_flg = get_post_meta($post->ID, 'merumaga_flg', true);
    $disp_merumaga_file = get_post_meta($post->ID, 'disp_merumaga_file', true);
    $disp_entry_file = get_post_meta($post->ID, 'disp_entry_file', true);
    $input_open = get_post_meta($post->ID, 'input_open', true);
    $entry_setting2 = get_post_meta($post->ID, 'entry_setting2', true);
    $entry_setting3 = get_post_meta($post->ID, 'entry_setting3', true);
    $entry_setting4 = get_post_meta($post->ID, 'entry_setting4', true);
    $entry_setting5 = get_post_meta($post->ID, 'entry_setting5', true);
    $entry_setting1 = get_post_meta($post->ID, 'entry_setting1', true);
    $mail_subject = get_post_meta($post->ID, 'mail_subject', true);
    $mail_body = get_post_meta($post->ID, 'mail_body', true);
    $confirm_name = get_post_meta($post->ID, 'confirm_name', true);
    $confirm_caption = get_post_meta($post->ID, 'confirm_caption', true);
    $disp_header_file_reg_end = get_post_meta($post->ID, 'disp_header_file_reg_end', true);
    $ReleaseDisp_end = get_post_meta($post->ID, 'ReleaseDisp_end', true);
    $disp_merumaga_file_end = get_post_meta($post->ID, 'disp_merumaga_file_end', true);
    $input_open_end = get_post_meta($post->ID, 'input_open_end', true);
    $entry_setting2_end = get_post_meta($post->ID, 'entry_setting2_end', true);
    $entry_setting3_end = get_post_meta($post->ID, 'entry_setting3_end', true);
    $entry_setting4_end = get_post_meta($post->ID, 'entry_setting4_end', true);
    $entry_setting5_end = get_post_meta($post->ID, 'entry_setting5_end', true);
    $entry_setting1_end = get_post_meta($post->ID, 'entry_setting1_end', true);
    $mail_subject_end = get_post_meta($post->ID, 'mail_subject_end', true);
    $mail_body_end = get_post_meta($post->ID, 'mail_body_end', true);
    $query_name = get_post_meta($post->ID, 'query_name', true);
    $query_caption = get_post_meta($post->ID, 'query_caption', true);
    $mail_name = get_post_meta($post->ID, 'mail_name', true);
    $mail_caption = get_post_meta($post->ID, 'mail_caption', true);
    $mail_auto_update = get_post_meta($post->ID, 'mail_auto_update', true);
    $auto_reg_flg = get_post_meta($post->ID, 'auto_reg_flg', true);
    $member_card_name = get_post_meta($post->ID, 'member_card_name', true);
    $member_card_caption = get_post_meta($post->ID, 'member_card_caption', true);

    $nak_member_magazine_nakama_url = get_post_meta($post->ID, 'nak_member_magazine_nakama_url', true);
    $nak_member_magazine_tg_id = get_post_meta($post->ID, 'nak_member_magazine_tg_id', true);
    $nak_member_magazine_mail_address = get_post_meta($post->ID, 'nak_member_magazine_mail_address', true);
    $nak_member_magazine_g_id = get_post_meta($post->ID, 'nak_member_magazine_g_id', true);
    $nak_member_magazine_disp_header_file_reg = get_post_meta($post->ID, 'nak_member_magazine_disp_header_file_reg', true);
    $nak_member_magazine_disp_header_file_del = get_post_meta($post->ID, 'nak_member_magazine_disp_header_file_del', true);
    $nak_member_magazine_caption = get_post_meta($post->ID, 'nak_member_magazine_caption', true);
    $nakama_member_type_magazine = get_post_meta($post->ID, 'nakama_member_type_magazine', true);
    $pattern_no_post_type = get_post_meta($post->ID, 'pattern_no_post_type', true);

    require_once(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/regist.php');
}
function member_setting_admin_style() {
    wp_enqueue_style('admin-member-styles', plugin_dir_url( __FILE__ ).'admin/assets/css/style.css');
    wp_enqueue_script('admin-member-setting-js',plugins_url('admin/assets/js/admin_member.js',__FILE__),'','',true);
}
add_action('admin_enqueue_scripts', 'member_setting_admin_style');

/* META BOX */
function member_meta_box_type(){}
function member_meta_group_id(){}
function member_meta_p_id(){}
function member_meta_api_key(){}
function list_member_tg_id(){}
function list_member_p_id(){}
function list_member_pattern_no(){}
function list_member_site_id(){}
function list_member_page_no(){}
function list_member_title(){}
function list_member_information_category(){}
function list_member_disp_sort(){}
function member_list_img_visible(){}
function member_img_visible_card_list(){}
function member_meta_short_code(){}
/* END META BOX*/

add_action('save_post_setting_member', 'update_api_setting_member_meta');

function update_api_setting_member_meta ($post_id) {
    if (get_post_type() == 'setting_member') {
        update_post_meta($post_id, 'member_meta_box_type', isset($_POST['member_meta_box_type'])?$_POST['member_meta_box_type']:"");
        update_post_meta($post_id, 'member_meta_group_id', isset($_POST['member_meta_group_id'])?$_POST['member_meta_group_id']:"");
        update_post_meta($post_id, 'member_meta_p_id', isset($_POST['member_meta_p_id'])?$_POST['member_meta_p_id']:"");
        update_post_meta($post_id, 'member_meta_api_key', isset($_POST['member_meta_api_key'])?$_POST['member_meta_api_key']:"");
        update_post_meta($post_id, 'list_member_tg_id', isset($_POST['list_member_tg_id'])?$_POST['list_member_tg_id']:"");
        update_post_meta($post_id, 'list_member_p_id', isset($_POST['list_member_p_id'])?$_POST['list_member_p_id']:"");
        update_post_meta($post_id, 'list_member_pattern_no', isset($_POST['list_member_pattern_no'])?$_POST['list_member_pattern_no']:"");
        update_post_meta($post_id, 'list_member_site_id', isset($_POST['list_member_site_id'])?$_POST['list_member_site_id']:"");
        update_post_meta($post_id, 'list_member_page_no', isset($_POST['list_member_page_no'])?$_POST['list_member_page_no']:"");
        update_post_meta($post_id, 'list_member_title', isset($_POST['list_member_title'])?$_POST['list_member_title']:"");
        update_post_meta($post_id, 'list_member_information_category', isset($_POST['list_member_information_category'])?$_POST['list_member_information_category']:"");
        update_post_meta($post_id, 'list_member_disp_sort', isset($_POST['list_member_disp_sort'])?$_POST['list_member_disp_sort']:"");
        update_post_meta($post_id, 'list_img_visible', isset($_POST['list_img_visible'])?$_POST['list_img_visible']:"");
        update_post_meta($post_id, 'img_visible_card_list', isset($_POST['img_visible_card_list'])?$_POST['img_visible_card_list']:"");
        update_post_meta($post_id, 'member_meta_short_code', isset($_POST['member_meta_short_code'])?$_POST['member_meta_short_code']:"");
        update_post_meta($post_id, 'pattern_no_post_type', isset($_POST['pattern_no_post_type'])?$_POST['pattern_no_post_type']:"");
        if(isset($_POST['nakama-member-w-first']))
            update_post_meta($post_id, 'nakama-member-w-first',$_POST['nakama-member-w-first']);
        if(isset($_POST['nakama-member-w-second']))
            update_post_meta($post_id, 'nakama-member-w-second', $_POST['nakama-member-w-second']);
        if(isset($_POST['nakama-member-w-third']))
            update_post_meta($post_id, 'nakama-member-w-third',$_POST['nakama-member-w-third']);
        if(isset($_POST['nakama-member-w-four']))
            update_post_meta($post_id, 'nakama-member-w-four',$_POST['nakama-member-w-four']);
        if(isset($_POST['nakama-member-w-five']))
            update_post_meta($post_id, 'nakama-member-w-five',$_POST['nakama-member-w-five']);
        if(isset($_POST['nakama-member-w-six']))
            update_post_meta($post_id, 'nakama-member-w-six',$_POST['nakama-member-w-six']);
        if(isset($_POST['nakama-member-equa-first']))
            update_post_meta($post_id, 'nakama-member-equa-first',$_POST['nakama-member-equa-first']);
        if(isset($_POST['nakama-member-equa-second']))
            update_post_meta($post_id, 'nakama-member-equa-second',$_POST['nakama-member-equa-second']);
        if(isset($_POST['nakama-member-equa-third']))
            update_post_meta($post_id, 'nakama-member-equa-third',$_POST['nakama-member-equa-third']);
        if(isset($_POST['nakama-member-equa-four']))
            update_post_meta($post_id, 'nakama-member-equa-four',$_POST['nakama-member-equa-four']);
        if(isset($_POST['nakama-member-equa-five']))
            update_post_meta($post_id, 'nakama-member-equa-five',$_POST['nakama-member-equa-five']);
        if(isset($_POST['nakama-member-equa-six']))
            update_post_meta($post_id, 'nakama-member-equa-six',$_POST['nakama-member-equa-six']);
        if(isset($_POST['nakama-member-add-second']))
            update_post_meta($post_id, 'nakama-member-add-second',$_POST['nakama-member-add-second']);
        if(isset($_POST['nakama-member-add-third']))
            update_post_meta($post_id, 'nakama-member-add-third',$_POST['nakama-member-add-third']);
        if(isset($_POST['nakama-member-add-four']))
            update_post_meta($post_id, 'nakama-member-add-four',$_POST['nakama-member-add-four']);
        if(isset($_POST['nakama-member-add-five']))
            update_post_meta($post_id, 'nakama-member-add-five',$_POST['nakama-member-add-five']);
        if(isset($_POST['nakama-member-add-six']))
            update_post_meta($post_id, 'nakama-member-add-six',$_POST['nakama-member-add-six']);
        if(isset($_POST['nakama-member-input-first']))
            update_post_meta($post_id, 'nakama-member-input-first',$_POST['nakama-member-input-first']);
        if(isset($_POST['nakama-member-input-second']))
            update_post_meta($post_id, 'nakama-member-input-second',$_POST['nakama-member-input-second']);
        if(isset($_POST['nakama-member-input-third']))
            update_post_meta($post_id, 'nakama-member-input-third',$_POST['nakama-member-input-third']);
        if(isset($_POST['nakama-member-input-four']))
            update_post_meta($post_id, 'nakama-member-input-four',$_POST['nakama-member-input-four']);
        if(isset($_POST['nakama-member-input-five']))
            update_post_meta($post_id, 'nakama-member-input-five',$_POST['nakama-member-input-five']);
        if(isset($_POST['nakama-member-input-six']))
            update_post_meta($post_id, 'nakama-member-input-six',$_POST['nakama-member-input-six']);
        if(isset($_POST['nakama-member-input-first-2']))
            update_post_meta($post_id, 'nakama-member-input-first-2',$_POST['nakama-member-input-first-2']);
        if(isset($_POST['nakama-member-input-second-2']))
            update_post_meta($post_id, 'nakama-member-input-second-2',$_POST['nakama-member-input-second-2']);
        if(isset($_POST['nakama-member-input-third-2']))
            update_post_meta($post_id, 'nakama-member-input-third-2',$_POST['nakama-member-input-third-2']);
        if(isset($_POST['nakama-member-input-four-2']))
            update_post_meta($post_id, 'nakama-member-input-four-2',$_POST['nakama-member-input-four-2']);
        if(isset($_POST['nakama-member-input-five-2']))
            update_post_meta($post_id, 'nakama-member-input-five-2',$_POST['nakama-member-input-five-2']);
        if(isset($_POST['nnakama-member-input-six-2']))
            update_post_meta($post_id, 'nakama-member-input-six-2',$_POST['nakama-member-input-six-2']);

        if(isset($_POST['nak-member-key-list-show']))
            update_post_meta($post_id, 'nak-member-key-list-show',$_POST['nak-member-key-list-show']);
        if(isset($_POST['nak-member-per-page']))
            update_post_meta($post_id, 'nak-member-per-page',$_POST['nak-member-per-page']);
        if(isset($_POST['nak-member-sort-column1']))
            update_post_meta($post_id, 'nak-member-sort-column1',$_POST['nak-member-sort-column1']);
        if(isset($_POST['nak-member-sort-column2']))
            update_post_meta($post_id, 'nak-member-sort-column2',$_POST['nak-member-sort-column2']);
        if(isset($_POST['nak-member-sort-column3']))
            update_post_meta($post_id, 'nak-member-sort-column3',$_POST['nak-member-sort-column3']);
        if(isset($_POST['nak-member-sort-column1-orderby']))
            update_post_meta($post_id, 'nak-member-sort-column1-orderby',$_POST['nak-member-sort-column1-orderby']);
        if(isset($_POST['nak-member-sort-column2-orderby']))
            update_post_meta($post_id, 'nak-member-sort-column2-orderby',$_POST['nak-member-sort-column2-orderby']);
        if(isset($_POST['nak-member-sort-column3-orderby']))
            update_post_meta($post_id, 'nak-member-sort-column3-orderby',$_POST['nak-member-sort-column3-orderby']);

        if(isset($_POST['top_g_id']))
            update_post_meta($post_id, 'top_g_id',$_POST['top_g_id']);
        if(isset($_POST['set_lg_g_id']))
            update_post_meta($post_id, 'set_lg_g_id',$_POST['set_lg_g_id']);
        if(isset($_POST['set_g_id']))
            update_post_meta($post_id, 'set_g_id',$_POST['set_g_id']);
        if(isset($_POST['mail_address']))
            update_post_meta($post_id, 'mail_address',$_POST['mail_address']);
        if(isset($_POST['linkage_type']))
            update_post_meta($post_id, 'linkage_type',$_POST['linkage_type']);
        if(isset($_POST['lg_login']))
            update_post_meta($post_id, 'lg_login',$_POST['lg_login']);
        if(isset($_POST['lg_disp']))
            update_post_meta($post_id, 'lg_disp',$_POST['lg_disp']);
        if(isset($_POST['disp_menu']))
            update_post_meta($post_id, 'disp_menu',$_POST['disp_menu']);
        if(isset($_POST['login_name']))
            update_post_meta($post_id, 'login_name',$_POST['login_name']);
        if(isset($_POST['login_caption']))
            update_post_meta($post_id, 'login_caption',$_POST['login_caption']);
        if(isset($_POST['group_leader']))
            update_post_meta($post_id, 'group_leader',$_POST['group_leader']);
        if(isset($_POST['top_type_visible']))
            update_post_meta($post_id, 'top_type_visible',$_POST['top_type_visible']);
        if(isset($_POST['top_type']))
            update_post_meta($post_id, 'top_type',$_POST['top_type']);
        if(isset($_POST['marketing_visible']))
            update_post_meta($post_id, 'marketing_visible',$_POST['marketing_visible']);
        if(isset($_POST['marketing_mail']))
            update_post_meta($post_id, 'marketing_mail',$_POST['marketing_mail']);

        if(isset($_POST['category_visible']))
            update_post_meta($post_id, 'category_visible',$_POST['category_visible']);
        if(isset($_POST['regist_name']))
            update_post_meta($post_id, 'regist_name',$_POST['regist_name']);
        if(isset($_POST['regist_caption']))
            update_post_meta($post_id, 'regist_caption',$_POST['regist_caption']);
        if(isset($_POST['disp_header_file_reg']))
            update_post_meta($post_id, 'disp_header_file_reg',$_POST['disp_header_file_reg']);
        if(isset($_POST['merumaga_flg']))
            update_post_meta($post_id, 'merumaga_flg',$_POST['merumaga_flg']);
        if(isset($_POST['disp_merumaga_file']))
            update_post_meta($post_id, 'disp_merumaga_file',$_POST['disp_merumaga_file']);
        if(isset($_POST['disp_entry_file']))
            update_post_meta($post_id, 'disp_entry_file',$_POST['disp_entry_file']);
        if(isset($_POST['input_open']))
            update_post_meta($post_id, 'input_open',$_POST['input_open']);
        if(isset($_POST['entry_setting2']))
            update_post_meta($post_id, 'entry_setting2',$_POST['entry_setting2']);
        if(isset($_POST['entry_setting3']))
            update_post_meta($post_id, 'entry_setting3',$_POST['entry_setting3']);
        if(isset($_POST['entry_setting4']))
            update_post_meta($post_id, 'entry_setting4',$_POST['entry_setting4']);
        if(isset($_POST['entry_setting5']))
            update_post_meta($post_id, 'entry_setting5',$_POST['entry_setting5']);
        if(isset($_POST['entry_setting1']))
            update_post_meta($post_id, 'entry_setting1',$_POST['entry_setting1']);
        if(isset($_POST['mail_subject']))
            update_post_meta($post_id, 'mail_subject',$_POST['mail_subject']);
        if(isset($_POST['mail_body']))
            update_post_meta($post_id, 'mail_body',$_POST['mail_body']);
        if(isset($_POST['confirm_name']))
            update_post_meta($post_id, 'confirm_name',$_POST['confirm_name']);
        if(isset($_POST['confirm_caption']))
            update_post_meta($post_id, 'confirm_caption',$_POST['confirm_caption']);
        if(isset($_POST['disp_header_file_reg_end']))
            update_post_meta($post_id, 'disp_header_file_reg_end',$_POST['disp_header_file_reg_end']);
        if(isset($_POST['ReleaseDisp_end']))
            update_post_meta($post_id, 'ReleaseDisp_end',$_POST['ReleaseDisp_end']);
        if(isset($_POST['disp_merumaga_file_end']))
            update_post_meta($post_id, 'disp_merumaga_file_end',$_POST['disp_merumaga_file_end']);
        if(isset($_POST['input_open_end']))
            update_post_meta($post_id, 'input_open_end',$_POST['input_open_end']);

        if(isset($_POST['entry_setting2_end']))
            update_post_meta($post_id, 'entry_setting2_end',$_POST['entry_setting2_end']);
        if(isset($_POST['entry_setting3_end']))
            update_post_meta($post_id, 'entry_setting3_end',$_POST['entry_setting3_end']);
        if(isset($_POST['entry_setting4_end']))
            update_post_meta($post_id, 'entry_setting4_end',$_POST['entry_setting4_end']);
        if(isset($_POST['entry_setting5_end']))
            update_post_meta($post_id, 'entry_setting5_end',$_POST['entry_setting5_end']);
        if(isset($_POST['entry_setting1_end']))
            update_post_meta($post_id, 'entry_setting1_end',$_POST['entry_setting1_end']);
        if(isset($_POST['mail_subject_end']))
            update_post_meta($post_id, 'mail_subject_end',$_POST['mail_subject_end']);
        if(isset($_POST['mail_body_end']))
            update_post_meta($post_id, 'mail_body_end',$_POST['mail_body_end']);
        if(isset($_POST['query_name']))
            update_post_meta($post_id, 'query_name',$_POST['query_name']);
        if(isset($_POST['query_caption']))
            update_post_meta($post_id, 'query_caption',$_POST['query_caption']);
        if(isset($_POST['mail_name']))
            update_post_meta($post_id, 'mail_name',$_POST['mail_name']);
        if(isset($_POST['mail_caption']))
            update_post_meta($post_id, 'mail_caption',$_POST['mail_caption']);
        if(isset($_POST['mail_auto_update']))
            update_post_meta($post_id, 'mail_auto_update',$_POST['mail_auto_update']);
        if(isset($_POST['auto_reg_flg']))
            update_post_meta($post_id, 'auto_reg_flg',$_POST['auto_reg_flg']);
        if(isset($_POST['member_card_name']))
            update_post_meta($post_id, 'member_card_name',$_POST['member_card_name']);
        if(isset($_POST['member_card_caption']))
            update_post_meta($post_id, 'member_card_caption',$_POST['member_card_caption']);

        if(isset($_POST['nak_member_magazine_nakama_url']))
            update_post_meta($post_id, 'nak_member_magazine_nakama_url',$_POST['nak_member_magazine_nakama_url']);
        if(isset($_POST['nak_member_magazine_tg_id']))
            update_post_meta($post_id, 'nak_member_magazine_tg_id',$_POST['nak_member_magazine_tg_id']);
        if(isset($_POST['nak_member_magazine_mail_address']))
            update_post_meta($post_id, 'nak_member_magazine_mail_address',$_POST['nak_member_magazine_mail_address']);
        if(isset($_POST['nak_member_magazine_g_id']))
            update_post_meta($post_id, 'nak_member_magazine_g_id',$_POST['nak_member_magazine_g_id']);
        if(isset($_POST['nak_member_magazine_disp_header_file_reg']))
            update_post_meta($post_id, 'nak_member_magazine_disp_header_file_reg',$_POST['nak_member_magazine_disp_header_file_reg']);
        if(isset($_POST['nak_member_magazine_disp_header_file_del']))
            update_post_meta($post_id, 'nak_member_magazine_disp_header_file_del',$_POST['nak_member_magazine_disp_header_file_del']);
        if(isset($_POST['nak_member_magazine_caption']))
            update_post_meta($post_id, 'nak_member_magazine_caption',$_POST['nak_member_magazine_caption']);
        update_post_meta($post_id, 'nakama_member_type_magazine', $_POST['type_magazine']);

    }
}

/* SHORT CODE COLUMN */
add_filter('manage_setting_member_posts_columns', 'add_setting_member_shortcode_column'); // gofundraise_button

function add_setting_member_shortcode_column($cols)
{
    unset($cols['date']);
    $cols['type'] = '種類';
    $cols['tg_id'] = '団体ID';
    $cols['shortcode'] = 'ショートコード';
    $cols['date'] = 'Date';
    return $cols;
}
add_filter( 'months_dropdown_results', 'rkv_remove_month_filters', 10, 2 );
function rkv_remove_month_filters( $months, $post_type ) {
    return in_array( $post_type, array( 'setting_member' ) ) ? array() : $months;
}
add_action('manage_setting_member_posts_custom_column', 'display_setting_member_shortcode_column', 10, 2);

function display_setting_member_shortcode_column( $column, $post_id)
{
    $type = get_post_meta($post_id, "member_meta_box_type",true);
    if($type == 'list_member'){
        $rsType = '会員一覧';
    }elseif($type == 'div_regist'){
        $rsType = '新規会員登録';
    }elseif($type == 'div_confirm'){
        $rsType = "内容確認・更新";

    }elseif($type == 'div_inquery'){
        $rsType = "ＩＤ・パスワード問合せ";

    }elseif($type == 'div_mail'){
        $rsType = "メールアドレス登録・変更";

    }elseif($type == 'div_card'){
        $rsType = "会員証発行";

    }elseif($type == 'setting_magazine'){
        $rsType = "メルマガ登録・ 解除";

    }elseif($type == 'copy_member'){
        $rsType = "グループ複写登録";

    }elseif($type == 'multiple_update'){
        $rsType = "担当者別内容確認・変更";
    }

    if($type == "setting_magazine")
        $tg_id = get_post_meta( $post_id, "nak_member_magazine_tg_id",true );
    else
        $tg_id = get_post_meta( $post_id, "top_g_id",true );
    switch ( $column ) {
        case 'shortcode' :
            echo '<div class="input-group">
                <input class="shortcode-input form-control" data-id="'.$post_id.'" id="shortcode-<?php echo $post_id ?>" value="[member-setting id='.$post_id.']" readonly>
                <span class="input-group-addon copy-button"><i class="glyphicon glyphicon-copy"></i></span>
            </div>';
            break;
        case 'tg_id' :
            echo $tg_id;
            break;
        case "type":
            echo $rsType;
            break;
    }
}
/* END SHORTCODE COLUMN */
add_action( 'restrict_manage_posts', 'wpse45436_admin_setting_member_filter_restrict_manage_setting_member' );
function wpse45436_admin_setting_member_filter_restrict_manage_setting_member(){
    $type = '';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }

    if ('setting_member' == $type){
        $values = array(
            'list_member' => '会員一覧',
            'div_regist' => '新規会員登録',
            'div_confirm' => '内容確認・更新',
            'div_inquery' => 'ＩＤ・パスワード問合せ',
            'div_mail' => 'メールアドレス登録・変更',
            'div_card' => '会員証発行',
            'setting_magazine' => 'メルマガ登録・ 解除',
            'copy_member' => 'グループ複写登録',
            'multiple_update' => '担当者別内容確認・変更',
        );
        $current_v = isset($_GET['member_meta_box_type'])? $_GET['member_meta_box_type']:'';
        ?>
        <select name="member_meta_box_type">
            <option value=""><?php _e('種類 ', 'wose45436'); ?></option>
            <?php
            foreach ($values as $k => $value) {
                $selected = ($current_v == $k)?"selected":"";
                echo '<option value="'.$k.'" '.$selected.'>'.$value.'</option>';
            }
            ?>
        </select>
        <?php
    }
}

function postSearchRebuild( $query ) {
    $post_type = isset($_GET['post_type'])?$_GET['post_type']:"";
    if($post_type == 'setting_member'):
        $current_v = isset($_GET['member_meta_box_type'])? $_GET['member_meta_box_type']:'';
        $meta_query = array('relation' => 'OR');
        array_push($meta_query, array(
            'key' => 'member_meta_box_type',
            'value' => $current_v,
            'compare' => 'LIKE'
        ));
        $query->set("meta_query", $meta_query);
    endif;
}
add_filter( "pre_get_posts", "postSearchRebuild");
?>
