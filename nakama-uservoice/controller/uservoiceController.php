<?php

final class uservoiceController {

    function getMDiscussionList( $post_id, $tg_id ) {
        $url = USER_VOICE_URL . "Setting/GetMDiscussionList?tg_id=$tg_id";
        $response = uservoice_get_common_call_api( $post_id, $url, array(), 'GET' );
        return $response;
    }


    function getMDiscussionAuthList( $post_id, $tg_id, $dis_id ) {
        $url = USER_VOICE_URL . "Setting/GetMDiscussionAuthList?tg_id=$tg_id&dis_id=$dis_id";
        $response = uservoice_get_common_call_api( $post_id, $url, array(), 'GET' );
        return $response;
    }


    function uploadTopImage( $post_id, $file_upload ) {
        $file_is_image = false;

        if( ! empty( $file_upload['type'] ) && strpos( $file_upload['type'], 'image/' ) !== false ) {
            $file_is_image = true;
        }

        $data = array(
            'file_upload_name' => $file_upload['name'],
            'file_upload_tmp' => base64_encode( file_get_contents( $file_upload['tmp_name'] ) ),
            'file_is_image' => $file_is_image
        );
        $url = USER_VOICE_URL . "Shared/UploadTopImage";
        $response = uservoice_get_common_call_api( $post_id, $url, $data, 'POST' );
        return $response;
    }


    function rotationTopImage( $post_id, $file_rotation )
    {
        $data = array(
            'file_rotation' => $file_rotation
        );
        $url = USER_VOICE_URL . "Shared/RotationTopImage";
        $response = uservoice_get_common_call_api( $post_id, $url, $data, 'POST' );
        return $response;
    }


    function resizeTopImage( $post_id, $file_resize, $width, $height )
    {
        $data = array(
            'file_resize' => $file_resize,
            'width' => $width,
            'height' => $height
        );
        $url = USER_VOICE_URL . "Shared/ResizeTopImage";
        $response = uservoice_get_common_call_api( $post_id, $url, $data, 'POST' );
        return $response;
    }


    function getUserVoiceSend() {
        $data_uv = array(
            'inquiry_type' => '',
            'c_name' => '',
            'mail' => '',
            'function' => '',
            'body' => '',
            'file_1' => '',
            'file_2' => '',
            'file_3' => ''
        );

        if ( isset( $_GET['func'] ) && $_GET['func'] == 'back' ) {
            if (!session_id()) {
                session_start();
            }

            if ( !empty( $_SESSION['user_voice_back_data'] ) ) {
                $data_uv = $_SESSION['user_voice_back_data'];
            }
        }

        return $data_uv;
    }


    function postUserVoiceSend() {
        $user_voice_data = array(
            'tg_id' => '',
            'dis_id' => '',
            'category' => '',
            'post_id' => '',
            'inquiry_type' => '',
            'c_name' => '',
            'mail' => '',
            'function' => '',
            'body' => '',
            'file_1' => '',
            'file_2' => '',
            'file_3' => '',
            'file_display_1' => '',
            'file_display_2' => '',
            'file_display_3' => ''
        );

        if ( !empty ( $_POST ) ) {
            if ( isset( $_POST['uservoice_send_tg_id'] ) ) {
                $user_voice_data['tg_id'] = $_POST['uservoice_send_tg_id'];
            }
            if ( isset( $_POST['uservoice_send_dis_id'] ) ) {
                $user_voice_data['dis_id'] = $_POST['uservoice_send_dis_id'];
            }
            if ( isset( $_POST['uservoice_send_category'] ) ) {
                $user_voice_data['category'] = $_POST['uservoice_send_category'];
            }
            if ( isset( $_POST['uservoice_send_post_id'] ) ) {
                $user_voice_data['post_id'] = $_POST['uservoice_send_post_id'];
            }
            if ( isset( $_POST['uservoice_send_inquiry_type'] ) ) {
                $user_voice_data['inquiry_type'] = $_POST['uservoice_send_inquiry_type'][0];
            }
            if ( isset( $_POST['uservoice_send_c_name'] ) ) {
                $user_voice_data['c_name'] = $_POST['uservoice_send_c_name'];
            }
            if ( isset( $_POST['uservoice_send_mail'] ) ) {
                $user_voice_data['mail'] = $_POST['uservoice_send_mail'];
            }
            if ( isset( $_POST['uservoice_send_tg_id'] ) ) {
                $user_voice_data['tg_id'] = $_POST['uservoice_send_tg_id'];
            }
            if ( isset( $_POST['uservoice_send_function'] ) ) {
                $user_voice_data['function'] = $_POST['uservoice_send_function'];
            }
            if ( isset( $_POST['uservoice_send_body'] ) ) {
                $user_voice_data['body'] = $_POST['uservoice_send_body'];
            }
            if ( isset( $_POST['uservoice_send_file_1'] ) ) {
                $user_voice_data['file_1'] = $_POST['uservoice_send_file_1'];
            }
            if ( isset( $_POST['uservoice_send_file_2'] ) ) {
                $user_voice_data['file_2'] = $_POST['uservoice_send_file_2'];
            }
            if ( isset( $_POST['uservoice_send_file_3'] ) ) {
                $user_voice_data['file_3'] = $_POST['uservoice_send_file_3'];
            }
            if ( isset( $_POST['uservoice_send_file_display_1'] ) ) {
                $user_voice_data['file_display_1'] = $_POST['uservoice_send_file_display_1'];
            }
            if ( isset( $_POST['uservoice_send_file_display_2'] ) ) {
                $user_voice_data['file_display_2'] = $_POST['uservoice_send_file_display_2'];
            }
            if ( isset( $_POST['uservoice_send_file_display_3'] ) ) {
                $user_voice_data['file_display_3'] = $_POST['uservoice_send_file_display_3'];
            }

            if ( !session_id() ) {
                session_start();
            }

            $_SESSION['user_voice_back_data'] = array(
                'inquiry_type' => $user_voice_data['inquiry_type'],
                'c_name' => $user_voice_data['c_name'],
                'mail' => $user_voice_data['mail'],
                'function' => $user_voice_data['function'],
                'body' => $user_voice_data['body'],
                'file_1' => $user_voice_data['file_1'],
                'file_2' => $user_voice_data['file_2'],
                'file_3' => $user_voice_data['file_3'],
                'file_display_1' => $user_voice_data['file_display_1'],
                'file_display_2' => $user_voice_data['file_display_2'],
                'file_display_3' => $user_voice_data['file_display_3']
            );
        } else {

        }

        return $user_voice_data;
    }


    function postUserVoiceConfirm() {
        if ( !empty ( $_POST ) ) {
            $data = array(
                'tg_id' => '',
                'dis_id' => '',
                'thread_cls' => '',
                'title' => '',
                'body' => '',
                'category' => '',
                'c_name' => '',
                'mail' => '',
                'view_cnt' => 1,
                'file_1' => '',
                'file_2' => '',
                'file_3' => ''
            );

            $post_id = $_POST['uservoice_send_post_id'];
            if ( isset( $_POST['uservoice_send_tg_id'] ) ) {
                $data['tg_id'] = $_POST['uservoice_send_tg_id'];
            }
            if ( isset( $_POST['uservoice_send_dis_id'] ) ) {
                $data['dis_id'] = $_POST['uservoice_send_dis_id'];
            }
            if ( isset( $_POST['uservoice_send_category'] ) ) {
                $data['category'] = $_POST['uservoice_send_category'];
            }
            if ( isset( $_POST['uservoice_send_inquiry_type'] ) ) {
                $data['thread_cls'] = $_POST['uservoice_send_inquiry_type'];
            }
            if ( isset( $_POST['uservoice_send_function'] ) ) {
                $data['title'] = $_POST['uservoice_send_function'];
            }
            if ( isset( $_POST['uservoice_send_c_name'] ) ) {
                $data['c_name'] = $_POST['uservoice_send_c_name'];
            }
            if ( isset( $_POST['uservoice_send_mail'] ) ) {
                $data['mail'] = $_POST['uservoice_send_mail'];
            }
            if ( isset( $_POST['uservoice_send_body'] ) ) {
                $data['body'] = $_POST['uservoice_send_body'];
            }
            if ( isset( $_POST['uservoice_send_file_1'] ) ) {
                $data['file_1'] = $_POST['uservoice_send_file_1'];
            }
            if ( isset( $_POST['uservoice_send_file_2'] ) ) {
                $data['file_2'] = $_POST['uservoice_send_file_2'];
            }
            if ( isset( $_POST['uservoice_send_file_3'] ) ) {
                $data['file_3'] = $_POST['uservoice_send_file_3'];
            }

            if ( !session_id() ) {
                session_start();
            }

            if ( isset ( $_SESSION['user_voice_back_data'] ) ) {
                unset( $_SESSION['user_voice_back_data'] );
                $url = USER_VOICE_URL . "Shared/EntryCustomerVoiceConfirm";
                $response = uservoice_get_common_call_api( $post_id, $url, $data, 'POST' );
                return $response;
            }
        } else {

        }
    }


    function getDisplayFile($file_display)
    {
        $result = '#';
        if ( !empty( $file_display ) ) {
            if ( $this->checkIsImage(  $file_display ) ) {
                $result = $file_display;
            } else {
                $info = pathinfo( $file_display );
                $ext = $info['extension'];

                if ( in_array( $ext, array( 'doc', 'docx' ) ) ) {
                    $file = 'word.gif';
                } else if ( in_array( $ext, array( 'xls', 'xlsx' ) ) ) {
                    $file = 'excel.gif';
                } else if ( in_array( $ext, array( 'ppt', 'pptx' ) ) ) {
                    $file = 'ppt.gif';
                } else if ( in_array( $ext, array( 'csv' ) ) ) {
                    $file = 'csv.gif';
                } else if ( in_array( $ext, array( 'pdf' ) ) ) {
                    $file = 'pdf.gif';
                } else if ( in_array( $ext, array( 'wmv', 'mp4', 'mp3', 'avi', 'mov', 'asf', 'flv', 'mts' ) ) ) {
                    $file = 'douga.png';
                } else if ( in_array( $ext, array( 'txt' ) ) ) {
                    $file = 'text.gif';
                } else if ( in_array( $ext, array( 'zip' ) ) ) {
                    $file = 'zip.s.gif';
                } else {
                    $file = 'file.gif';
                }

                $result = plugins_url( 'nakama-uservoice/assets/img/' ) . $file;
            }
        }

        return $result;
    }

    public function checkIsImage($img_url) {
        $img_exts = array( "gif", "jpg", "jpeg", "png", "bmp" );
        $check_ext = false;
        $url_ext = pathinfo( $img_url, PATHINFO_EXTENSION );
        foreach ( $img_exts as $img_ext ) {
            if ( strpos( $url_ext, $img_ext ) !== false ) {
                $check_ext = true;
            }
        }
        return @is_array( getimagesize( $img_url ) ) || $check_ext === true;
    }
}