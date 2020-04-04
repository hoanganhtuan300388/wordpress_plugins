$(document).ready(function() {
    uploadImageService();
    deleteImageService();
    backImageService();
});

function uploadImageService() {
    var home_url = $('#service_home_url').val();
    $('#frm-service-upload-img').ajaxForm({
        url: home_url + '/wp-admin/admin-ajax.php',
        beforeSubmit: function() {
            var file = $('#service_file_upload').val();

            if (file == '') {
                alert('登録する画像を選択してください。');
                return false;
            }

            var ext = /[^.]+$/.exec(file);
            var ext_list = ['bmp', 'gif', 'jpeg', 'jpg', 'png'];
            if ($.inArray(ext[0], ext_list) == -1) {
                $('#service_file_upload').val('');
                $('#frm-service-upload-img').hide();
                $('#wrap-file-not-image').show();
                return false;
            }

            $('#btn-service-upload-image').attr({'disabled': true});
        },
        success: function(response) {
            if (response.data) {
                console.log(response);
                if (response.data.Status) {
                    var data = response.data.data;
                    $('#service_file_upload').val('');
                    window.opener.$('#disp_img').text(data.file_name);
                    window.opener.$("input[name='m_img']").val(data.file_name);
                    window.opener.$("input[name='m_imgRegFlg']").val(1);
                    window.opener.$("input[name='m_img_dir']").val("");
                    window.close();
                } else {
                    alert(response.data.Message);
                }
            }
        },
        error: function() {
            console.log('error');
        },
        complete: function() {
            $('#btn-service-upload-image').removeAttr('disabled');
            console.log('complete');
        }
    });
}

function deleteImageService() {
    $('#btn-service-delete-image').click(function() {
        var c = confirm('画像を削除してもよろしいですか？');

        if (c == true) {
            window.opener.$('#disp_img').text("");
            window.opener.$("input[name='m_img']").val("");
            window.opener.$("input[name='m_imgRegFlg']").val("");
            window.opener.$("input[name='m_img_dir']").val("");
            $('#txt-display-image').hide();
            $('#wrap-display-image').hide();
            $('#btn-service-delete-image').hide();
            window.close();
        }

        return c;
    });
}

function backImageService() {
    $('#btn-service-back-image').click(function() {
        $('#frm-service-upload-img').show();
        $('#wrap-file-not-image').hide();
    });
}