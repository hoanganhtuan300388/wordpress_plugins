var SERVICE_DISPLAY_IMAGE_MAX_WIDTH = 0;

$(document).ready(function() {
    uploadFileService();
    deleteImageService();
    completeImageService();
    editImageSizeService();
    closePopupImageService();
    onLoadDisplayImage();
});

function onLoadDisplayImage() {
    $('#service-display-image').load(function () {
        SERVICE_DISPLAY_IMAGE_MAX_WIDTH = $(this).width();
        //var height = $(this).height();
        var input_size = $("#service-resize-option input[type=button]");
        for(let i=0; i < input_size.length; i++){
            if (parseInt(input_size[i].value.substring(0 ,input_size[i].value.length -2)) >= SERVICE_DISPLAY_IMAGE_MAX_WIDTH) {
                input_size[i].disabled = true;
            } else {
                input_size[i].disabled = false;
            }
        }
    });
}

function uploadFileService() {
    var home_url = $('#service_home_url').val();
    $('#frm-service-upload-file').ajaxForm({
        url: home_url + '/wp-admin/admin-ajax.php',
        beforeSubmit: validateUploadFileService,
        success: function(response) {
            if (response.data) {
                console.log(response);
                if (response.data.Status) {
                    var data = response.data.data;
                    var item_no = $('#service_itemno').val();
                    var no = $('#service_no').val();
                    if (item_no != '' && no != '') {
                        var situation = $('input[name=situation]:checked').val();
                        window.opener.$('#disp_' + item_no + '_file' + no).text(data.file_name);
                        window.opener.$("input[name='item_" + item_no + "_file" + no + "']").val(data.file_name);
                        //window.opener.$("input[name='item_"+item_no+"_re_file"+no+"']").val('');
                        window.opener.$("input[name='item_" + item_no + "_situation" + no + "']").val(situation);
                        window.opener.$("input[name='item_" + item_no + "_file" + no + "_dir']").val('');
                    } else {
                        window.opener.$('#disp_img').text(data.file_name);
                        window.opener.$("input[name='m_img']").val(data.file_name);
                        window.opener.$("input[name='m_imgRegFlg']").val(1);
                        window.opener.$("input[name='m_img_dir']").val('');
                    }
                    $('#frm-service-reside-file').show();
                    $('#frm-service-upload-file').hide();
                    if (data.file_is_image == true) {
                        $('#service-display-image').attr('src', data.file_url);
                        $('#service-wrap-resize').show();
                        $('#service-wrap-rotation').show();
                        $('#btn-service-showhide-action').show();
                    } else {
                        var folder = $('#service_assets_img_folder').val();
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
                            $('#service-display-image').attr('src', folder + 'composition/' + fileIcon);
                        }
                        $('#service-wrap-resize').hide();
                        $('#service-wrap-rotation').hide();
                        $('#btn-service-showhide-action').hide();
                    }
                } else {
                    alert(response.data.Message);
                }
            }
        },
        error: function() {
            console.log('error');
        },
        complete: function() {
            $('#btn-service-upload-file').removeAttr('disabled');
            console.log('complete');
        }
    });
}

function validateUploadFileService(formData, jqForm, options) {
    var file = $('#service_file_upload').val();

    if (file == '') {
        alert('ファイルのアップロードに失敗しました。');
        return false;
    }

    $('#btn-service-upload-file').attr({'disabled': true});
}

function rotationImageService(ev, rotation) {
    var item_no = $('#service_itemno').val();
    var no = $('#service_no').val();
    var tg_id = $('#service_top_g_id').val();
    if (item_no != '' && no != '') {
        var img = window.opener.$("input[name='item_" + item_no + "_file" + no + "']").val();
        var file_dir = window.opener.$("input[name='item_" + item_no + "_file" + no + "_dir']").val();
        var is_not_resize = '';
    } else {
        var img = window.opener.$("input[name='m_img']").val();
        var file_dir = window.opener.$("input[name='m_img_dir']").val();
        var is_not_resize = 1;
    }
    var file = '/temp/'+tg_id+'/'+img;
    var post_id = $('#service_file_upload_post_id').val();
    var home_url = $('#service_home_url').val();

    $.ajax({
        url: home_url + '/wp-admin/admin-ajax.php',
        type : "POST",
        data: {
            'file_rotation': file,
            'post_id': post_id,
            'type': rotation,
            'file_dir': file_dir,
            'is_not_resize': is_not_resize,
            'action': 'service_rotation_file'
        },
        beforeSend: function() {
            //$('#service-display-image').fadeOut('slow');
            $(ev).attr({'disabled': true});
        },
        success: function(response) {
            if(response.data) {
                console.log(response);
                if (response.data.Status) {
                    var data = response.data.data;
                    $('#service-display-image').attr('src', data.file_url);
                    $('#btn-service-reset-image').show();
                    if (item_no != '' && no != '') {
                        window.opener.$("input[name='item_" + item_no + "_re_file" + no + "']").val(img);
                        window.opener.$("input[name='item_" + item_no + "_file" + no + "_dir']").val('');
                    } else {
                        window.opener.$("input[name='m_img_dir']").val('');
                    }
                } else {
                    alert(response.data.Message);
                }
            }
        },
        error: function() {
            console.log('error');
        },
        complete: function() {
            //$('#service-display-image').fadeIn('slow');
            $(ev).removeAttr('disabled');
            console.log('complete');
        }
    });
}

function resizeImageService(size) {
    $('#service-display-image').css('width', size + 'px');
    var item_no = $('#service_itemno').val();
    var no = $('#service_no').val();
    var tg_id = $('#service_top_g_id').val();
    if (item_no != '' && no != '') {
        var img = window.opener.$("input[name='item_" + item_no + "_file" + no + "']").val();
        var file_dir = window.opener.$("input[name='item_" + item_no + "_file" + no + "_dir']").val();
        var is_not_resize = '';
    } else {
        var img = window.opener.$("input[name='m_img']").val();
        var file_dir = window.opener.$("input[name='m_img_dir']").val();
        var is_not_resize = 1;
    }
    var file = '/temp/'+tg_id+'/'+img;
    var post_id = $('#service_file_upload_post_id').val();
    var home_url = $('#service_home_url').val();
    var width = $('#service-display-image').width();
    var height = $('#service-display-image').height();
    var size_old = $('#service-display-image').attr('width');
    $('#service_chg_size').val('');
    $.ajax({
        url: home_url + '/wp-admin/admin-ajax.php',
        type : "POST",
        data: {
            'file_resize': file,
            'post_id': post_id,
            'width': width,
            'height': height,
            'file_dir': file_dir,
            'is_not_resize': is_not_resize,
            'action': 'service_resize_file'
        },
        beforeSend: function() {
            let input_size =$("#service-wrap-resize input[type=button]");
            for(let i=0; i<input_size.length;i++){
                if(input_size[i].value!=size+"px"){
                       input_size[i].disabled=true;
                }
            }

            $('#btn-service-image-edit-size').attr({'disabled': true});

        },
        success: function(response) {
            if (response.data) {
                console.log(response);
                if (response.data.Status) {
                    var data = response.data.data;
                    $('#service-display-image').attr('src', data.file_url);
                    $('#btn-service-reset-image').show();
                    if (item_no != '' && no != '') {
                        window.opener.$("input[name='item_" + item_no + "_re_file" + no + "']").val(img);
                        window.opener.$("input[name='item_" + item_no + "_file" + no + "_dir']").val('');
                    } else {
                        window.opener.$("input[name='m_img_dir']").val('');
                    }
                } else {
                    alert(response.data.Message);
                }
            }
        },
        error: function() {
            console.log('error');
        },
        complete: function() {
            $('#btn-service-image-edit-size').removeAttr('disabled');
            let input_size =$("#service-wrap-resize input[type=button]");
            for(let i=0; i<input_size.length;i++){
                if(input_size[i].value!=size+"px" ){
                    input_size[i].disabled=false;
                }
                if( parseInt(input_size[i].value.substring(0,input_size[i].value.length -2 )) >= size_old){
                    input_size[i].disabled=true;
                }
            }
            console.log('complete');
        }
    });
}

function deleteImageService() {
    $('#btn-service-delete-image').click(function() {
        var c = confirm('削除された画像は元に戻せませんが、削除してよろしいですか？');

        if (c == true) {
            var item_no = $('#service_itemno').val();
            var no = $('#service_no').val();
            $('#service_chg_size').val('');
            $('#service_file_upload').val('');
            $('#service-display-image').attr('src', '#');
            if (item_no != '' && no != '') {
                window.opener.$('#disp_' + item_no + '_file' + no).text('');
                window.opener.$("input[name='item_" + item_no + "_file" + no + "']").val('');
                window.opener.$("input[name='item_" + item_no + "_re_file" + no + "']").val('');
                window.opener.$("input[name='item_" + item_no + "_situation" + no + "']").val('');
                window.opener.$("input[name='item_" + item_no + "_file" + no + "_dir']").val('');
            } else {
                window.opener.$('#disp_img').text("");
                window.opener.$("input[name='m_img']").val("");
                window.opener.$("input[name='m_imgRegFlg']").val("");
                window.opener.$("input[name='m_img_dir']").val("");
            }
            $('#frm-service-reside-file').hide();
            $('#frm-service-upload-file').show();
            $('#btn-service-reset-image').hide();
        }

        return c;
    });
}

function completeImageService() {
    $('#btn-service-choice-image').click(function() {
        var item_no = $('#service_itemno').val();
        var no = $('#service_no').val();
        var situation = $('input[name=situation]:checked').val();
        window.opener.$("input[name='item_"+item_no+"_situation"+no+"']").val(situation);
        window.close();
    });
}

function editImageSizeService() {
    $('#btn-service-image-edit-size').click(function() {
        var size_file = $('#service_chg_size').val();

        if (!(!isNaN(parseFloat(size_file)) && isFinite(size_file))) {
            alert('サイズは数値を入力して下さい。');
            $('#service_chg_size').focus();
            return false;
        }

        if (eval(size_file) < 10) {
            alert('サイズは10以上の数値を入力して下さい。');
            $('#service_chg_size').focus();
            return false;
        }

        if (eval(size_file) > SERVICE_DISPLAY_IMAGE_MAX_WIDTH) {
            alert("サイズは" + SERVICE_DISPLAY_IMAGE_MAX_WIDTH + "以下の数値を入力して下さい。");
            $('#service_chg_size').focus();
            return false;
        }

        resizeImageService(size_file);
    });
}

function resetImageService(reset_file_url) {
    var item_no = $('#service_itemno').val();
    var no = $('#service_no').val();
    var tg_id = $('#service_top_g_id').val();
    var img = window.opener.$("input[name='item_"+item_no+"_file"+no+"']").val();
    var file_dir = window.opener.$("input[name='item_"+item_no+"_file"+no+"_dir']").val();
    var file = '/temp/'+tg_id+'/'+img;
    var post_id = $('#service_file_upload_post_id').val();
    var home_url = $('#service_home_url').val();

    $.ajax({
        url: home_url + '/wp-admin/admin-ajax.php',
        type : "POST",
        data: {
            'file_reset': file,
            'post_id': post_id,
            'file_dir': file_dir,
            'action': 'service_reset_file'
        },
        beforeSend: function() {
            $('#btn-service-reset-image').attr({'disabled': true});
        },
        success: function(response) {
            if (response.data) {
                console.log(response);
                if (response.data.Status) {
                    alert('画像を元に戻しました。');
                    var data = response.data.data;
                    $('#btn-service-reset-image').hide();
                    $('#service-wrap-resize').show();
                    $('#service-wrap-rotation').show();
                    $('#service-display-image').attr('src', data.file_url).css('width', '');
                    window.opener.$("input[name='item_"+item_no+"_re_file"+no+"']").val('');
                    window.opener.$("input[name='item_"+item_no+"_file"+no+"_dir']").val('');
                } else {
                    alert(response.data.Message);
                }
            }
        },
        error: function() {
            console.log('error');
        },
        complete: function() {
            $('#btn-service-reset-image').removeAttr('disabled');
            console.log('complete');
        }
    });
}

function closePopupImageService() {
    $('#btn-service-close-popup').click(function() {
        window.close();
    });
}

function showHideAction() {
    if ($('#service-wrap-resize').is(":hidden")) {
        $('#btn-service-showhide-action').text('[ オプションを隠す ]');
        $('#service-wrap-resize').show();
        $('#service-wrap-rotation').show();
    } else {
        $('#btn-service-showhide-action').text('[ オプションを表示 ]');
        $('#service-wrap-resize').hide();
        $('#service-wrap-rotation').hide();
    }
}