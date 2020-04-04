<?php
function uservoice_create_shortcode_setting( $args ) {
    ob_start();
    $postid = $args['id'];
    $dataSetting = get_post_meta( $postid );
    $tg_id = $dataSetting['top_g_id'][0];
    $dis_id = $dataSetting['nakama_uservoice_param_meeting'][0];
    $category = $dataSetting['nakama_uservoice_param_category'][0];
    $PattenNo =  $dataSetting['pattern_no_post_type'][0];
    $inquiry_type_list = get_option( 'nakama-uservoice-inquiry-types' );
    if ( empty( $inquiry_type_list ) || !is_array( $inquiry_type_list ) ) {
        $inquiry_type_list = array();
    }
    $inquiry_type_setting = get_post_meta( $postid, 'nakama_uservoice_inquiry_type', true );
    if ( empty( $inquiry_type_setting ) || !is_array( $inquiry_type_setting ) ) {
        $inquiry_type_setting = array();
    }
    $function_list = get_option( 'nakama-uservoice-functions' );
    if ( empty( $function_list ) || !is_array( $function_list ) ) {
        $function_list = array();
    }
    $function_setting = get_post_meta( $postid, 'nakama_uservoice_function', true );
    if ( empty( $function_setting ) || !is_array( $function_setting ) ) {
        $function_setting = array();
    }
    $file_type_list = get_option( 'nakama-uservoice-file-types' );
    if ( empty( $file_type_list ) || !is_array( $file_type_list ) ) {
        $file_type_list = array();
    }
    $file_type_setting = get_post_meta( $postid, 'nakama_uservoice_file_type', true );
    if ( empty( $file_type_setting ) || !is_array( $file_type_setting ) ) {
        $file_type_setting = array();
    }

    include( 'shortcode_send_user_voice.php' );

    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
add_shortcode( 'customer-voice', 'uservoice_create_shortcode_setting' );

function get_function_setting( $post_id ) {
    $functionList = get_option( 'nakama-uservoice-functions' );
    $settings = get_post_meta( $post_id, 'nakama_uservoice_function', true );
    $function_setting = array();
    foreach ( $settings as $index => $id ) {
        foreach ( $functionList as $function ) {
            if ( $function['id'] == $id ) {
                $function_setting[$index] = $function;
                continue;
            }
        }
    }
    return $function_setting;
}