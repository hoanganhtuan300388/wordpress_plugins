IMAGE_EM = 0;
IMAGE_CLICK_NUMBER = 1;

$(document).ready(function() {
    closeAddFileModal();
    validateSendUserVoiceForm();
    submitSendVoice();
    uploadFileSendUserVoice();
    slidaImage();
});

function openAddFIleModal(fileNumber) {
    IMAGE_CLICK_NUMBER = 1;
    $('.modal-upload-file').show();
    $('#uservoice_send_file_number').val(fileNumber);
    $('#uservoice_send_file_upload').val('');
    $('#frm-upload-file').show();
    $('#frm-reside-file').hide();
    $('#ImageResizeImg').attr('src', '');
}

function closeAddFileModal() {
    $('.box-head').click(function() {
        $('.modal-upload-file').hide();
        $('#uservoice_send_file_number').val('');
        $('#uservoice_send_image_file').val('');
    });
}

function clearImageControl(fileNumber) {
    $('#uservoice_send_file_' + fileNumber).val('');
    $('#uservoice_send_file_display_' + fileNumber).val('');
    $('#btn-upload-file-' + fileNumber).show();
    $('#uservoice_img_display_' + fileNumber).hide();
    $('#btn_clear' + fileNumber).hide();
}

function uploadFileSendUserVoice() {
    var home_url = $('#uservoice_send_home_url').val();
    $('#frm-upload-file').ajaxForm({
        url: home_url + '/wp-admin/admin-ajax.php',
        beforeSubmit: validateUploadFileSend,
        success: function(response) {
            if (response.data) {
                console.log(response.data);
                if (response.data.Status) {
                    var fileNumber = $('#uservoice_send_file_number').val();
                    if (fileNumber) {
                        var data = response.data.data;
                        if (data.file_is_image == true) {
                            //$('#uservoice_img_display_' + fileNumber).css({'width': '96px'}).attr('src', data.file_url);
                            var width = data.file_imagesize[0];
                            var height = data.file_imagesize[1];
                            $('#frm-upload-file').hide();
                            $('#frm-reside-file').show();
                            $('#ImageResizeGrid').css({'font-size': width + 'px'});
                            $('#uservoice_send_image_file').val(data.file_path);
                            //$('#ImageResizeImg').attr('src', data.file_url);
                            IMAGE_EM = height/width;
                            $('#ImageResizeGrid').html('<img src="' + data.file_url + '" id="ImageResizeImg" style="width: 1em; height: ' + IMAGE_EM + 'em" />');
                            $("#uservoice_send_image_slide").slider('setAttribute', 'max', width);
                            $("#uservoice_send_image_slide").slider('setAttribute', 'min', 30);
                            $("#uservoice_send_image_slide").slider('setAttribute', 'value', width);
                            $("#uservoice_send_image_slide").slider('refresh');
                        } else {
                            $('#uservoice_send_file_' + fileNumber).val(data.file_path);
                            $('#uservoice_send_file_display_' + fileNumber).val(data.file_url);
                            $('#uservoice_send_file_number').val('');
                            $('#btn-upload-file-' + fileNumber).hide();
                            $('#uservoice_img_display_' + fileNumber).show();
                            $('#btn_clear' + fileNumber).show();
                            var folder = $('#uservoice_send_file_folder').val();
                            $('.modal-upload-file').hide();
                            var ext = data.file_ext;
                            var fileIcon = '';
                            if ($.inArray(ext, ['doc', 'docx']) !== -1) {
                                fileIcon = 'word.gif';
                            } else if ($.inArray(ext, ['xls', 'xlsx']) !== -1) {
                                fileIcon = 'excel.gif';
                            } else if ($.inArray(ext, ['ppt', 'pptx']) !== -1) {
                                fileIcon = 'ppt.gif';
                            } else if ($.inArray(ext, ['csv']) !== -1) {
                                fileIcon = 'csv.gif';
                            } else if ($.inArray(ext, ['pdf']) !== -1) {
                                fileIcon = 'pdf.gif';
                            } else if ($.inArray(ext, ['wmv', 'mp4', 'mp3', 'avi', 'mov', 'asf', 'flv', 'mts']) !== -1) {
                                fileIcon = 'douga.png';
                            } else if ($.inArray(ext, ['txt']) !== -1) {
                                fileIcon = 'text.gif';
                            } else if ($.inArray(ext, ['zip']) !== -1) {
                                fileIcon = 'zip.s.gif';
                            } else {
                                fileIcon = 'file.gif';
                            }
                            if (fileIcon != '') {
                                $('#uservoice_img_display_' + fileNumber).css({'width': '50px'}).attr('src', folder + fileIcon);
                            }
                        }
                    }
                } else {
                    swal({
                        title: '',
                        type: 'error',
                        html: response.data.Message
                    });
                }
            }
        },
        error: function() {
            console.log('error');
        },
        complete: function() {
            $('#btn-uservoice-upload-file').removeAttr('disabled');
            console.log('complete');
        }
    });
}

function imageResizeUserVoice() {
    var file = $('#uservoice_send_image_file').val();
    var post_id = $('#uservoice_file_upload_post_id').val();
    var width = $('#ImageResizeImg').width();
    var height = $('#ImageResizeImg').height();
    var home_url = $('#uservoice_send_home_url').val();

    $.ajax({
        url: home_url + '/wp-admin/admin-ajax.php',
        type : "POST",
        data: {
            'file_resize': file,
            'post_id': post_id,
            'width': width,
            'height': height,
            'action': 'send_reside_file'
        },
        beforeSend: function() {
            $('#btn-uservoice-choice-image').attr({'disabled': true});
        },
        success: function(response) {
            if (response.data) {
                if (response.data.Status) {
                    var fileNumber = $('#uservoice_send_file_number').val();
                    var data = response.data.data;
                    $('#uservoice_send_file_' + fileNumber).val($('#uservoice_send_image_file').val());
                    $('#uservoice_send_file_display_' + fileNumber).val(data.file_url);
                    $('#uservoice_send_file_number').val('');
                    $('#btn-upload-file-' + fileNumber).hide();
                    $('#btn_clear' + fileNumber).show();
                    $('#uservoice_img_display_' + fileNumber).show().attr('src', data.file_url);
                    $('.modal-upload-file').hide();
                } else {
                    swal({
                        title: '',
                        type: 'error',
                        html: response.data.Message
                    });
                }
            }
        },
        error: function() {
            console.log('error');
        },
        complete: function() {
            $('#btn-uservoice-choice-image').removeAttr('disabled');
            console.log('complete');
        }
    });
}

function imageRotationUserVoice() {
    var file = $('#uservoice_send_image_file').val();
    var post_id = $('#uservoice_file_upload_post_id').val();
    var home_url = $('#uservoice_send_home_url').val();

    $.ajax({
        url: home_url + '/wp-admin/admin-ajax.php',
        type : "POST",
        data: {
            'file_rotation': file,
            'post_id': post_id,
            'action': 'send_rotation_file'
        },
        beforeSend: function() {
            //$('#ImageResizeImg').fadeOut('slow');
            $('#btn-uservoice-rotation-image').attr({'disabled': true});
        },
        success: function(response) {
            if(response.data) {
                if (response.data.Status) {
                    var data = response.data.data;
                    console.log(data.file_url);
                    if (IMAGE_CLICK_NUMBER%2 === 1) {
                        $('#ImageResizeGrid').html('<img src="' + data.file_url + '" id="ImageResizeImg" style="width: ' + IMAGE_EM + '; height: 1em" />');
                    } else {
                        $('#ImageResizeGrid').html('<img src="' + data.file_url + '" id="ImageResizeImg" style="width: 1em; height: ' + IMAGE_EM + 'em" />');
                    }
                    IMAGE_CLICK_NUMBER++;
                } else {
                    swal({
                        title: '',
                        type: 'error',
                        html: response.data.Message
                    });
                }
            }
        },
        error: function() {
            $('#ImageResizeImg').fadeIn('slow');
            console.log('error');
        },
        complete: function() {
            $('#btn-uservoice-rotation-image').removeAttr('disabled');
            console.log('complete');
        }
    });
}

function validateUploadFileSend(formData, jqForm, options) {
    var file = $('#uservoice_send_file_upload').val();

    if (file == '') {
        swal({
            title: '',
            type: 'error',
            html: 'アップロードできない拡張子のファイルです。'
        });
        return false;
    } else {
        var file_validate = $('#uservoice_file_validate').val().split(',');
        var file_ext = file.split('.').pop();
        if (file_validate == '' || $.inArray(file_ext, file_validate) == -1) {
            swal({
                title: '',
                type: 'error',
                html: 'ファイルのアップロードは「' + file_ext + '」タイプのファイルにしてください。'
            });
            return false;
        }
    }

    $('#btn-uservoice-upload-file').attr({'disabled': true});
}

function validateSendUserVoiceForm() {
    $("#frm-send-user-voice").validate({
        rules: {
            "uservoice_send_function": "required",
            "uservoice_send_body": "required",
            "uservoice_send_inquiry_type[]": "required",
            "uservoice_send_c_name": "required",
            "uservoice_send_mail": {"required":true, "email": true}
        },
        messages: {
            "uservoice_send_function": {
                required: "",
            },
            "uservoice_send_body": {
                required: "",
            },
            "uservoice_send_inquiry_type[]": {
                required: "",
            },
            "uservoice_send_c_name": {
                required: "",
            },
            "uservoice_send_mail": {
                required: "",
            }
        }
    });
}

function submitSendVoice() {
    $('#btn-send-user-voice').click(function() {
        if($('#frm-send-user-voice').valid() == true) {
            $('.error-info').hide();
        } else {
            $('.error-info').show();
        }
    });
}

function slidaImage() {
    $('#uservoice_send_image_slide').slider({
        formatter: function(value) {
            $('#ImageResizeGrid').css({'font-size': value + 'px'});
            return value;
        }
    });
}