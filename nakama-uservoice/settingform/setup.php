<?php
add_action( 'init', 'create_setting_uservoice_post_type' );


function create_setting_uservoice_post_type() {
    register_post_type( 'setting_uservoice',
        array(
            'labels' => array(
                'name' => __( 'お客様の声' ),
                'singular_name' => __( 'お客様の声' ),
            ),
            'supports' => array(
                'title',
            ),
            'public' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'register_meta_box_cb' => 'edit_uservoice_button_fields',
            'show_in_menu' => 'edit.php?post_type=setting_uservoice',
            'show_in_nav_menus' => false
        )
    );
}


function edit_uservoice_button_fields () {
    add_meta_box( 'uservoice_meta_box_type', '', 'uservoice_meta_box_type');
    add_meta_box( 'uservoice_meta_group_id', '', 'uservoice_meta_group_id');
    add_meta_box( 'uservoice_meta_p_id', '', 'uservoice_meta_p_id');
    add_meta_box( 'uservoice_meta_api_key', '', 'uservoice_meta_api_key');
}


add_action('edit_form_after_title','uservoice_edit_form_after_title_function');
function uservoice_edit_form_after_title_function () {
    if ( get_post_type() == 'setting_uservoice' ) {
        uservoice_setting_page();
    }
}


function uservoice_setting_page () {
    $post = get_post();
    $top_g_id = get_post_meta( $post->ID, 'top_g_id', true );
    $group_id = get_post_meta( $post->ID, 'uservoice_meta_group_id', true );
    $p_id = get_post_meta( $post->ID, 'uservoice_meta_p_id', true );
    $api_key = get_post_meta( $post->ID, 'uservoice_meta_api_key', true );
    $dis_id = get_post_meta( $post->ID, 'nakama_uservoice_param_meeting', true );
    $category = get_post_meta( $post->ID, 'nakama_uservoice_param_category', true );
    $inquiry_type = get_post_meta( $post->ID, 'nakama_uservoice_inquiry_type', true );
    if ( empty( $inquiry_type ) ) {
        $inquiry_type = array();
    }
    $function = get_post_meta( $post->ID, 'nakama_uservoice_function', true );
    if ( empty( $function ) ) {
        $function = array();
    }
    $file_type = get_post_meta( $post->ID, 'nakama_uservoice_file_type', true );
    if ( empty( $file_type ) ) {
        $file_type = array();
    }

    $pattern_no_post_type = get_post_meta($post->ID, 'pattern_no_post_type', true);
    require_once(PLUGIN_uservoice_PATH_SETTING . 'admin/settings/regist.php');
}


add_action( 'save_post_setting_uservoice', 'update_api_setting_uservoice_meta' );
function update_api_setting_uservoice_meta ( $post_id ) {
    if ( get_post_type() == 'setting_uservoice' ) {
        if ( isset( $_POST['nakama_uservoice_param_meeting'] ) ) {
            update_post_meta( $post_id, 'nakama_uservoice_param_meeting', $_POST['nakama_uservoice_param_meeting'] );
        }
        if ( isset( $_POST['nakama_uservoice_param_category'] ) ) {
            update_post_meta( $post_id, 'nakama_uservoice_param_category', $_POST['nakama_uservoice_param_category'] );
        }
        if ( isset( $_POST['nakama_uservoice_inquiry_type'] ) ) {
            update_post_meta( $post_id, 'nakama_uservoice_inquiry_type', $_POST['nakama_uservoice_inquiry_type'] );
        } else {
            update_post_meta( $post_id, 'nakama_uservoice_inquiry_type', array() );
        }
        if ( isset( $_POST['nakama_uservoice_function'] ) ) {
            update_post_meta( $post_id, 'nakama_uservoice_function', $_POST['nakama_uservoice_function'] );
        } else {
            update_post_meta( $post_id, 'nakama_uservoice_function', array() );
        }
        if ( isset( $_POST['nakama_uservoice_file_type'] ) ) {
            update_post_meta( $post_id, 'nakama_uservoice_file_type', $_POST['nakama_uservoice_file_type'] );
        } else {
            update_post_meta( $post_id, 'nakama_uservoice_file_type', array() );
        }
    }
}


function uservoice_setting_admin_style() {
    wp_enqueue_style( 'admin-uservoice-styles', plugin_dir_url( __FILE__ ) . 'admin/assets/css/style.css' );
    wp_enqueue_script( 'admin-uservoice-setting-js', plugins_url( 'admin/assets/js/admin_uservoice.js',__FILE__ ),'','',true );
}
add_action( 'admin_enqueue_scripts', 'uservoice_setting_admin_style' );


add_filter( 'manage_setting_uservoice_posts_columns', 'add_setting_uservoice_shortcode_column' );
function add_setting_uservoice_shortcode_column($cols){
    unset($cols['date']);
    $cols['tg_id'] = '団体ID';
    $cols['shortcode'] = 'ショートコード';
    $cols['date'] = 'Date';
    return $cols;
}


add_action( 'manage_setting_uservoice_posts_custom_column', 'display_setting_uservoice_shortcode_column', 10, 2 );
function display_setting_uservoice_shortcode_column( $column, $post_id ) {
    $tg_id = get_post_meta( $post_id, "top_g_id",true );
    switch ( $column ) {
        case 'shortcode':
            echo '<div class="input-group">
              <input class="shortcode-input form-control" data-id="'.$post_id.'" id="shortcode-'.$post_id.'" value="[customer-voice id='.$post_id.']" readonly>
              <span class="input-group-addon copy-button"><i class="glyphicon glyphicon-copy"></i></span>
            </div>';
            break;
        case 'tg_id':
            echo $tg_id;
            break;
    }
}