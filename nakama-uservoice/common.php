<?php
function uservoice_pagination( $total,$current_page,$per_page ) {
    if ( $total > $per_page ) {
        $total_page = ceil( $total/$per_page );
    } else {
        $total_page = 1;
    }

    if ( $current_page > $total_page )
        $current_page = $total_page;

    if ( $current_page < 1 )
        $current_page = 1;

    return array(
        "total_page" => $total_page,
        "current_page" => $current_page,
    );
}


function uservoice_get_common_call_api( $post_id = '', $url, $body_parameters, $method ) {
    $group_id = get_post_meta( $post_id, 'uservoice_meta_group_id', true );
    $personal_id = get_post_meta( $post_id, 'uservoice_meta_p_id', true );
    $api_key = get_post_meta( $post_id, 'uservoice_meta_api_key', true );

    $headers = array(
        'Content-Type' => 'application/json; charset=utf-8',
        'NAKAMA-KEY' => $api_key,
        'TG_ID'=> $group_id,
        'P_ID'=> $personal_id,
    );

    $request = wp_remote_post($url, array(
        'headers' => $headers,
        'body' => ( $body_parameters ) ? json_encode( $body_parameters ) : "",
        'method' => $method,
        'data_format' => 'body',
        'timeout' => 45,
    ));

    if( is_wp_error( $request ) ) {
        return false;
    }

    return json_decode( wp_remote_retrieve_body( $request ) );
}


//Check slug exists
function uservoice_the_slug_exists( $post_name ) {
    global $wpdb;

    if( $wpdb->get_row( "SELECT post_name FROM wp_posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A' ) ) {
        return true;
    } else {
        return false;
    }
}


function uservoice_create_new_page( $title, $slug ) {
    $title_check = get_page_by_title( $title );

    $page = array(
        'post_type' => 'page',
        'post_title' => $title,
        'post_status' => 'publish',
        'post_name' => $slug,
        'post_slug' => $slug
    );

    if ( !isset( $title_check->ID ) && !uservoice_the_slug_exists( $slug ) ) {
        return wp_insert_post( $page );
    }
}