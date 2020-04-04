<?php
add_action( 'wp_ajax_category_get_data_setting', 'category_get_data_setting' );
function category_get_data_setting() {
    $tg_id = isset( $_REQUEST['tg_id'] ) ? $_REQUEST['tg_id'] : '';
    $dis_id = isset( $_REQUEST['dis_id'] ) ? $_REQUEST['dis_id'] : '';
    $post_id = isset( $_REQUEST['post_id'] ) ? $_REQUEST['post_id'] : '';
    $uservoice = new uservoiceController();
    $getCategoryList = $uservoice->getMDiscussionAuthList( $post_id, $tg_id, $dis_id );
    wp_send_json_success( $getCategoryList );
    die();
}


add_action( 'wp_ajax_save_inquiry_type', 'save_inquiry_type' );
function save_inquiry_type() {
    $type_name = isset( $_REQUEST['type_name'] ) ? $_REQUEST['type_name'] : '';
    $type_id = isset( $_REQUEST['type_id'] ) ? $_REQUEST['type_id'] : '';
    $inquiryTypeList = get_option( 'nakama-uservoice-inquiry-types' );
    if ( empty( $inquiryTypeList ) || !is_array( $inquiryTypeList ) ) {
        $inquiryTypeList = array();
    }

    $action = 'insert';
    if ( count( $inquiryTypeList ) == 0 ) {
        $index = 0;
        $id = 1;
    } else {
        if ( empty( $type_id ) ) {
            $index = max( array_keys( $inquiryTypeList ) ) + 1;
            $id = get_max_id_array( $inquiryTypeList, 'id' );
        } else {
            $checkIsset = false;
            foreach ( $inquiryTypeList as $key => $item ) {
                if ( $item['id'] == $type_id ) {
                    $index = $key;
                    $id = $item['id'];
                    $action = 'edit';
                    $checkIsset = true;
                    continue;
                }
            }

            if ($checkIsset == false) {
                $index = max( array_keys( $inquiryTypeList ) ) + 1;
                $id = get_max_id_array( $inquiryTypeList, 'id' );
            }
        }
    }
    $inquiryTypeList[$index] = array( 'id' => $id, 'name' => $type_name );
    update_option( 'nakama-uservoice-inquiry-types', $inquiryTypeList );
    wp_send_json_success( array( 'id' => $id, 'name' => $type_name, 'action' => $action ) );
    die();
}


add_action( 'wp_ajax_delete_inquiry_type', 'delete_inquiry_type' );
function delete_inquiry_type() {
    $type_id = isset( $_REQUEST['type_id'] ) ? $_REQUEST['type_id'] : '';
    $inquiryTypeList = get_option( 'nakama-uservoice-inquiry-types' );
    $posts = get_posts( array( 'post_type' => 'setting_uservoice' ) );
    foreach ( $inquiryTypeList as $index => $inquiryType ) {
        if ( $inquiryType['id'] == $type_id ) {
            unset( $inquiryTypeList[$index] );
            continue;
        }
    }
    foreach ( $posts as $key => $post ) {
        $inquiryTypes = get_post_meta( $post->ID, 'nakama_uservoice_inquiry_type', true );
        foreach ( $inquiryTypes as $index => $id ) {
            if ( $id == $type_id ) {
                unset( $inquiryTypes[$index] );
                continue;
            }
        }
        update_post_meta( $post->ID, 'nakama_uservoice_inquiry_type', $inquiryTypes );
    }
    update_option( 'nakama-uservoice-inquiry-types', $inquiryTypeList );
    wp_send_json_success( array( 'id' => $type_id ) );
    die();
}


add_action( 'wp_ajax_up_inquiry_type', 'up_inquiry_type' );
function up_inquiry_type() {
    $type_id = isset( $_REQUEST['type_id'] ) ? $_REQUEST['type_id'] : '';
    $inquiryTypeList = get_option( 'nakama-uservoice-inquiry-types' );
    $index = -1;
    foreach ( $inquiryTypeList as $i => $inquiryType ) {
        if ( $inquiryType['id'] == $type_id ) {
            $index = $i;
            continue;
        }
    }

    if ( $index != -1 ) {
        $prevKey = get_prev_key( $inquiryTypeList, $index );
        if ( isset ( $inquiryTypeList[$prevKey] ) ) {
            $currentInquiryType = $inquiryTypeList[$index];
            $upInquiryType = $inquiryTypeList[$prevKey];
            $inquiryTypeList[$index] = $upInquiryType;
            $inquiryTypeList[$prevKey] = $currentInquiryType;
            update_option('nakama-uservoice-inquiry-types', $inquiryTypeList);
        }
    }

    wp_send_json_success( array( 'id' => $type_id ) );
    die();
}


add_action( 'wp_ajax_down_inquiry_type', 'down_inquiry_type' );
function down_inquiry_type() {
    $type_id = isset( $_REQUEST['type_id'] ) ? $_REQUEST['type_id'] : '';
    $inquiryTypeList = get_option( 'nakama-uservoice-inquiry-types' );
    $index = -1;
    foreach ( $inquiryTypeList as $i => $inquiryType ) {
        if ( $inquiryType['id'] == $type_id ) {
            $index = $i;
            continue;
        }
    }

    if ( $index != -1 ) {
        $nextKey = get_next_key( $inquiryTypeList, $index );
        if ( isset ( $inquiryTypeList[$nextKey] ) ) {
            $currentInquiryType = $inquiryTypeList[$index];
            $downInquiryType = $inquiryTypeList[$nextKey];
            $inquiryTypeList[$index] = $downInquiryType;
            $inquiryTypeList[$nextKey] = $currentInquiryType;
            update_option('nakama-uservoice-inquiry-types', $inquiryTypeList);
        }
    }

    wp_send_json_success( array( 'id' => $type_id ) );
    die();
}


add_action( 'wp_ajax_save_function', 'save_function' );
function save_function() {
    $function_name = isset( $_REQUEST['function_name'] ) ? $_REQUEST['function_name'] : '';
    $function_id = isset( $_REQUEST['function_id'] ) ? $_REQUEST['function_id'] : '';
    $functionList = get_option( 'nakama-uservoice-functions' );
    if ( empty( $functionList ) || !is_array( $functionList ) ) {
        $functionList = array();
    }

    $action = 'insert';
    if ( count( $functionList ) == 0 ) {
        $index = 0;
        $id = 1;
    } else {
        if ( empty( $function_id ) ) {
            $index = max( array_keys( $functionList ) ) + 1;
            $id = get_max_id_array( $functionList, 'id' );
        } else {
            $checkIsset = false;
            foreach ( $functionList as $key => $item ) {
                if ( $item['id'] == $function_id ) {
                    $index = $key;
                    $id = $item['id'];
                    $action = 'edit';
                    $checkIsset = true;
                    continue;
                }
            }

            if ($checkIsset == false) {
                $index = max( array_keys( $functionList ) ) + 1;
                $id = get_max_id_array( $functionList, 'id' );
            }
        }
    }
    $functionList[$index] = array( 'id' => $id, 'name' => $function_name );
    update_option( 'nakama-uservoice-functions', $functionList );
    wp_send_json_success( array( 'id' => $id, 'name' => $function_name, 'action' => $action ) );
    die();
}


add_action( 'wp_ajax_delete_function', 'delete_function' );
function delete_function() {
    $function_id = isset( $_REQUEST['function_id'] ) ? $_REQUEST['function_id'] : '';
    $functionList = get_option( 'nakama-uservoice-functions' );
    $posts = get_posts( array( 'post_type' => 'setting_uservoice' ) );
    foreach ( $functionList as $index => $function ) {
        if ( $function['id'] == $function_id ) {
            unset( $functionList[$index] );
            continue;
        }
    }
    foreach ( $posts as $key => $post ) {
        $functions = get_post_meta( $post->ID, 'nakama_uservoice_function', true );
        foreach ( $functions as $index => $id ) {
            if ( $id == $function_id ) {
                unset( $functions[$index] );
                continue;
            }
        }
        update_post_meta( $post->ID, 'nakama_uservoice_function', $functions );
    }
    update_option( 'nakama-uservoice-functions', $functionList );
    wp_send_json_success( array( 'id' => $function_id ) );
    die();
}


add_action( 'wp_ajax_up_function', 'up_function' );
function up_function() {
    $function_id = isset( $_REQUEST['function_id'] ) ? $_REQUEST['function_id'] : '';
    $functionList = get_option( 'nakama-uservoice-functions' );
    $index = -1;
    foreach ( $functionList as $i => $function ) {
        if ( $function['id'] == $function_id ) {
            $index = $i;
            continue;
        }
    }

    if ( $index != -1 ) {
        $prevKey = get_prev_key( $functionList, $index );
        if ( isset ( $functionList[$prevKey] ) ) {
            $currentFunction = $functionList[$index];
            $upFunction = $functionList[$prevKey];
            $functionList[$index] = $upFunction;
            $functionList[$prevKey] = $currentFunction;
            update_option('nakama-uservoice-functions', $functionList);
        }
    }

    wp_send_json_success( array( 'id' => $function_id ) );
    die();
}


add_action( 'wp_ajax_down_function', 'down_function' );
function down_function() {
    $function_id = isset( $_REQUEST['function_id'] ) ? $_REQUEST['function_id'] : '';
    $functionList = get_option( 'nakama-uservoice-functions' );
    $index = -1;
    foreach ( $functionList as $i => $function ) {
        if ( $function['id'] == $function_id ) {
            $index = $i;
            continue;
        }
    }

    if ( $index != -1 ) {
        $nextKey = get_next_key( $functionList, $index );
        if ( isset ( $functionList[$nextKey] ) ) {
            $currentFunction = $functionList[$index];
            $downFunction = $functionList[$nextKey];
            $functionList[$index] = $downFunction;
            $functionList[$nextKey] = $currentFunction;
            update_option('nakama-uservoice-functions', $functionList);
        }
    }

    wp_send_json_success( array( 'id' => $function_id ) );
    die();
}


add_action( 'wp_ajax_save_file_type', 'save_file_type' );
function save_file_type() {
    $category_id = isset( $_REQUEST['category_id'] ) ? $_REQUEST['category_id'] : '';
    $file_type_name = isset( $_REQUEST['file_type_name'] ) ? $_REQUEST['file_type_name'] : '';
    $file_type_id = isset( $_REQUEST['file_type_id'] ) ? $_REQUEST['file_type_id'] : '';
    $fileTypeList = get_option( 'nakama-uservoice-file-types' );

    foreach ( $fileTypeList as $index => $category ) {
        if ( $category['id'] == $category_id ) {
            $types = $category['types'];
            $action = 'insert';
            $index_category = $index;
            if ( count( $types ) == 0 ) {
                $index = 0;
                $id = get_max_id_type_array( $fileTypeList, 'id' );;
            } else {
                if ( empty( $file_type_id ) ) {
                    $index = max( array_keys( $types ) ) + 1;
                    $id = get_max_id_type_array( $fileTypeList, 'id' );
                } else {
                    $checkIsset = false;
                    foreach ( $types as $key => $item ) {
                        if ( $item['id'] == $file_type_id ) {
                            $index = $key;
                            $id = $item['id'];
                            $action = 'edit';
                            $checkIsset = true;
                            continue;
                        }
                    }

                    if ($checkIsset == false) {
                        $index = max( array_keys( $types ) ) + 1;
                        $id = get_max_id_type_array( $fileTypeList, 'id' );
                    }
                }
            }
            $types[$index] = array( 'id' => $id, 'name' => $file_type_name );
            $fileTypeList[$index_category]['types'] = $types;
        }
    }

    update_option( 'nakama-uservoice-file-types', $fileTypeList );
    wp_send_json_success( array( 'category_id' => $category_id, 'id' => $id, 'name' => $file_type_name, 'action' => $action ) );
    die();
}


add_action( 'wp_ajax_delete_file_type', 'delete_file_type' );
function delete_file_type() {
    $file_type_id = isset( $_REQUEST['file_type_id'] ) ? $_REQUEST['file_type_id'] : '';
    $fileTypeList = get_option( 'nakama-uservoice-file-types' );
    $posts = get_posts( array( 'post_type' => 'setting_uservoice' ) );
    foreach ( $fileTypeList as $category_index => $category ) {
        foreach ( $category['types'] as $index => $file_type ) {
            if ( $file_type['id'] == $file_type_id ) {
                unset( $fileTypeList[$category_index]['types'][$index] ) ;
                continue;
            }
        }
    }
    foreach ( $posts as $key => $post ) {
        $file_types = get_post_meta( $post->ID, 'nakama_uservoice_file_type', true );
        foreach ( $file_types as $index => $id ) {
            if ( $id == $file_type_id ) {
                unset( $file_types[$index] );
                continue;
            }
        }
        update_post_meta( $post->ID, 'nakama_uservoice_file_type', $file_types );
    }
    update_option( 'nakama-uservoice-file-types', $fileTypeList );
    wp_send_json_success( array( 'id' => $file_type_id ) );
    die();
}


add_action( 'wp_ajax_up_file_type', 'up_file_type' );
function up_file_type() {
    $file_type_id = isset( $_REQUEST['file_type_id'] ) ? $_REQUEST['file_type_id'] : '';
    $category_id = isset( $_REQUEST['category_id'] ) ? $_REQUEST['category_id'] : '';
    $fileTypeList = get_option( 'nakama-uservoice-file-types' );
    $index = -1;
    $category_index = -1;
    foreach ( $fileTypeList as $category_i => $category ) {
        foreach ( $category['types'] as $i => $file_type ) {
            if ( $file_type['id'] == $file_type_id ) {
                $index = $i;
                $category_index = $category_i;
                continue;
            }
        }
    }

    if ( $index != -1 && $category_index != -1 ) {
        $prevKey = get_prev_key( $fileTypeList[$category_index]['types'], $index );
        if ( isset ( $fileTypeList[$category_index]['types'][$prevKey] ) ) {
            $currentFileType = $fileTypeList[$category_index]['types'][$index];
            $upFileType = $fileTypeList[$category_index]['types'][$prevKey];
            $fileTypeList[$category_index]['types'][$index] = $upFileType;
            $fileTypeList[$category_index]['types'][$prevKey] = $currentFileType;
            update_option( 'nakama-uservoice-file-types', $fileTypeList );
        }
    }

    wp_send_json_success( array( 'id' => $file_type_id, 'category_id' => $category_id ) );
    die();
}


add_action( 'wp_ajax_down_file_type', 'down_file_type' );
function down_file_type() {
    $file_type_id = isset( $_REQUEST['file_type_id'] ) ? $_REQUEST['file_type_id'] : '';
    $category_id = isset( $_REQUEST['category_id'] ) ? $_REQUEST['category_id'] : '';
    $fileTypeList = get_option( 'nakama-uservoice-file-types' );
    $index = -1;
    $category_index = -1;
    foreach ( $fileTypeList as $category_i => $category ) {
        foreach ( $category['types'] as $i => $file_type ) {
            if ( $file_type['id'] == $file_type_id ) {
                $index = $i;
                $category_index = $category_i;
                continue;
            }
        }
    }

    if ( $index != -1 && $category_index != -1 ) {
        $nextKey = get_next_key( $fileTypeList[$category_index]['types'], $index );
        if ( isset ( $fileTypeList[$category_index]['types'][$nextKey] ) ) {
            $currentFileType = $fileTypeList[$category_index]['types'][$index];
            $upFileType = $fileTypeList[$category_index]['types'][$nextKey];
            $fileTypeList[$category_index]['types'][$index] = $upFileType;
            $fileTypeList[$category_index]['types'][$nextKey] = $currentFileType;
            update_option( 'nakama-uservoice-file-types', $fileTypeList );
        }
    }

    wp_send_json_success( array( 'id' => $file_type_id, 'category_id' => $category_id ) );
    die();
}


add_action( 'wp_ajax_send_upload_file', 'send_upload_file' );
add_action( 'wp_ajax_nopriv_send_upload_file', 'send_upload_file' );
function send_upload_file() {
    $post_id = isset( $_REQUEST['uservoice_file_upload_post_id'] ) ? $_REQUEST['uservoice_file_upload_post_id'] : '';
    $file_upload = isset( $_FILES['uservoice_send_file_upload'] ) ? $_FILES['uservoice_send_file_upload'] : '';
    $uservoice = new uservoiceController();
    $uploadFileResponse = $uservoice->uploadTopImage( $post_id, $file_upload );
    wp_send_json_success( $uploadFileResponse );
    die();
}


add_action( 'wp_ajax_send_reside_file', 'send_reside_file' );
add_action( 'wp_ajax_nopriv_send_reside_file', 'send_reside_file' );
function send_reside_file() {
    $post_id = isset( $_REQUEST['post_id'] ) ? $_REQUEST['post_id'] : '';
    $width = isset( $_REQUEST['width'] ) ? $_REQUEST['width'] : '';
    $height = isset( $_REQUEST['height'] ) ? $_REQUEST['height'] : '';
    $file_resize = isset( $_REQUEST['file_resize'] ) ? $_REQUEST['file_resize'] : '';
    $uservoice = new uservoiceController();
    $resizeFileResponse = $uservoice->resizeTopImage( $post_id, $file_resize, $width, $height );
    wp_send_json_success( $resizeFileResponse );
    die();
}


add_action( 'wp_ajax_send_rotation_file', 'send_rotation_file' );
add_action( 'wp_ajax_nopriv_send_rotation_file', 'send_rotation_file' );
function send_rotation_file() {
    $post_id = isset( $_REQUEST['post_id'] ) ? $_REQUEST['post_id'] : '';
    $file_rotation = isset( $_REQUEST['file_rotation'] ) ? $_REQUEST['file_rotation'] : '';
    $uservoice = new uservoiceController();
    $rotationFileResponse = $uservoice->rotationTopImage( $post_id, $file_rotation );
    wp_send_json_success( $rotationFileResponse );
    die();
}


function get_max_id_array( $list, $key ) {
    $max = 0;

    foreach ( $list as $item ) {
        if ( $item[$key] > $max ) {
            $max = $item[$key];
        }
    }

    return $max + 1;
}

function get_max_id_type_array( $list, $key ) {
    $max = 0;

    foreach ( $list as $item ) {
        $types = $item['types'];

        foreach ( $types as $type ) {
            if ( $type[$key] > $max ) {
                $max = $type[$key];
            }
        }
    }

    return $max + 1;
}


function get_next_key( $array, $key ) {
    $keys = array_keys( $array );
    if ( isset ( $keys[array_search( $key, $keys ) + 1] ) ) {
        return $keys[array_search( $key, $keys ) + 1];
    } else {
        return -1;
    }
}


function get_prev_key( $array, $key ) {
    $keys = array_keys( $array );
    if ( isset ( $keys[array_search( $key, $keys ) - 1] ) ) {
        return $keys[array_search( $key, $keys ) - 1];
    } else {
        return -1;
    }
}