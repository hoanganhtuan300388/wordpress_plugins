jQuery(document).ready(function () {
    changeMettingRoom();
    saveInquiryType();
    saveFunction();
    saveFileType();
});

function changeMettingRoom() {
    jQuery('#nakama_uservoice_param_meeting').change(function () {
        var dis_id = jQuery(this).val();
        var post_id = jQuery("input[name=post_ID]").val();
        var tg_id = jQuery("input[name=nakama_uservoice_param_tg_id]").val();

        var data = {
            action: 'category_get_data_setting',
            dis_id: dis_id,
            post_id: post_id,
            tg_id: tg_id
        };

        //console.log('changeMettingRoom request', data);
        jQuery('.img_theme_loading').show();

        jQuery.ajax({
            url: '../wp-admin/admin-ajax.php',
            type : "POST",
            data: data,
            success: function(response) {
                //console.log(response.data);
                jQuery('#nakama_uservoice_param_category').empty().append('<option selected="selected" value="">--------</option>');

                if(response.data) {
                    jQuery(response.data.data).each(function(index, item) {
                        var option = '<option value="' + item.CATEGORY + '">' + item.CATEGORY + '</option>';
                        jQuery('#nakama_uservoice_param_category').append(option);
                    });
                }
            },
            error: function() {},
            complete: function () {
                jQuery('.img_theme_loading').hide();
            }
        });
    });
}

function saveInquiryType() {
    jQuery('#btn-save-inquiry-type').click(function(event) {
        event.stopImmediatePropagation();
        if (jQuery('#nakama_uservoice_inquiry_type_name').val() != '') {
            var _btnSave = jQuery(this);
            _btnSave.attr({'disabled': true});
            var inquiry_type_name = jQuery('#nakama_uservoice_inquiry_type_name').val();
            var inquiry_type_id = jQuery('#nakama_uservoice_inquiry_type_id').val();
            var post_id = jQuery("input[name=post_ID]").val();

            var data = {
                action: 'save_inquiry_type',
                type_id: inquiry_type_id,
                type_name: inquiry_type_name,
                post_id: post_id
            };

            jQuery('.img_theme_loading_inquiry_type').show();

            jQuery.ajax({
                url: '../wp-admin/admin-ajax.php',
                type : "POST",
                data: data,
                success: function(response) {
                    if (response.data) {
                        var id = response.data.id;
                        var name = response.data.name;
                        if (response.data.action == 'insert') {
                            var strAppend = '<div class="item-list-display-item" id="item-list-display-item-' + id + '">';
                            strAppend += '<input class="chk-uservoice-item" name="nakama_uservoice_inquiry_type[]" id="nakama_uservoice_inquiry_type_' + id + '"  value="' + id + '" type="checkbox">';
                            strAppend += '&nbsp;<label for="nakama_uservoice_inquiry_type_' + id + '" id="nakama_uservoice_lbl_inquiry_type_' + id + '">' + name + '</label>'
                            strAppend += '<div class="item-display-action">';
                            strAppend += '<a href="javascript:void(0)" onclick="deleteInquiryType(' + id + ')">削除</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                            strAppend += '<a href="javascript:void(0)" onclick="editInquiryType(' + id + ', \'' + name + '\')">編集</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                            strAppend += '<a href="javascript:void(0)" onclick="editInquiryTypeUp(' + id + ')">↑</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                            strAppend += '<a href="javascript:void(0)" onclick="editInquiryTypeDown(' + id + ')">↓</a>';
                            strAppend += '</div>';
                            strAppend += '</div>';
                            jQuery('.item-type-list-display').append(strAppend);
                            jQuery('.item-type-list-display').animate({
                                scrollTop: jQuery('.item-type-list-display').height(),
                            },1000, function() {});
                        } else if (response.data.action == 'edit') {
                            var strAppend = '<a href="javascript:void(0)" onclick="deleteInquiryType(' + id + ')">削除</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                            strAppend += '<a href="javascript:void(0)" onclick="editInquiryType(' + id + ', \'' + name + '\')">編集</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                            strAppend += '<a href="javascript:void(0)" onclick="editInquiryTypeUp(' + id + ')">↑</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                            strAppend += '<a href="javascript:void(0)" onclick="editInquiryTypeDown(' + id + ')">↓</a>';
                            jQuery('#nakama_uservoice_lbl_inquiry_type_' + id).text(name);
                            jQuery('#nakama_uservoice_lbl_inquiry_type_' + id).next().html(strAppend);
                        }
                    }
                    jQuery('#nakama_uservoice_inquiry_type_name').val('');
                    jQuery('#nakama_uservoice_inquiry_type_id').val('');
                },
                error: function() {},
                complete: function () {
                    jQuery('.img_theme_loading_inquiry_type').hide();
                    _btnSave.removeAttr('disabled');
                }
            });
        } else {
            jQuery('#nakama_uservoice_inquiry_type_name').focus();
        }
    });
}

function deleteInquiryType(inquiry_type_id) {
    var confirm = window.confirm('問い合わせ種類を削除しますか？');

    if (confirm == true) {
        var data = {
            action: 'delete_inquiry_type',
            type_id: inquiry_type_id
        };

        jQuery('.img_theme_loading_inquiry_type').show();

        jQuery.ajax({
            url: '../wp-admin/admin-ajax.php',
            type : "POST",
            data: data,
            success: function(response) {
                if (response.data) {
                    jQuery('#item-list-display-item-' + inquiry_type_id).remove();
                }
            },
            error: function() {},
            complete: function () {
                jQuery('.img_theme_loading_inquiry_type').hide();
            }
        });
    }
}

function editInquiryType(inquiry_type_id, inquiry_type_name) {
    jQuery('#nakama_uservoice_inquiry_type_id').val(inquiry_type_id).focus();
    jQuery('#nakama_uservoice_inquiry_type_name').val(inquiry_type_name);
}

function editInquiryTypeUp(inquiry_type_id) {
    var currentItem = jQuery('#item-list-display-item-' + inquiry_type_id);
    var prevItem = currentItem.prev();

    if (prevItem.length !== 0) {
        var data = {
            action: 'up_inquiry_type',
            type_id: inquiry_type_id
        };

        jQuery('.img_theme_loading_inquiry_type').show();

        jQuery.ajax({
            url: '../wp-admin/admin-ajax.php',
            type : "POST",
            data: data,
            success: function(response) {
                if (response.data) {
                    currentItem.insertBefore(prevItem);
                }
            },
            error: function() {},
            complete: function () {
                jQuery('.img_theme_loading_inquiry_type').hide();
            }
        });
    }
}

function editInquiryTypeDown(inquiry_type_id) {
    var currentItem = jQuery('#item-list-display-item-' + inquiry_type_id);
    var nextItem = currentItem.next();

    if (nextItem.length !== 0) {
        var data = {
            action: 'down_inquiry_type',
            type_id: inquiry_type_id
        };

        jQuery('.img_theme_loading_inquiry_type').show();

        jQuery.ajax({
            url: '../wp-admin/admin-ajax.php',
            type : "POST",
            data: data,
            success: function(response) {
                if (response.data) {
                    currentItem.insertAfter(nextItem);
                }
            },
            error: function() {},
            complete: function () {
                jQuery('.img_theme_loading_inquiry_type').hide();
            }
        });
    }
}

function saveFunction() {
    jQuery('#btn-save-function').click(function(event) {
        event.stopImmediatePropagation();
        if (jQuery('#nakama_uservoice_function_name').val() != '') {
            var _btnSave = jQuery(this);
            _btnSave.attr({'disabled': true});
            var function_name = jQuery('#nakama_uservoice_function_name').val();
            var function_id = jQuery('#nakama_uservoice_function_id').val();
            var post_id = jQuery("input[name=post_ID]").val();

            var data = {
                action: 'save_function',
                function_id: function_id,
                function_name: function_name,
                post_id: post_id
            };

            jQuery('.img_theme_loading_function').show();

            jQuery.ajax({
                url: '../wp-admin/admin-ajax.php',
                type : "POST",
                data: data,
                success: function(response) {
                    if (response.data) {
                        var id = response.data.id;
                        var name = response.data.name;
                        if (response.data.action == 'insert') {
                            var strAppend = '<div class="item-list-display-item" id="item-function-list-display-item-' + id + '">';
                            strAppend += '<input class="chk-uservoice-item" name="nakama_uservoice_function[]" id="nakama_uservoice_function_' + id + '"  value="' + id + '" type="checkbox">';
                            strAppend += '&nbsp;<label for="nakama_uservoice_function_' + id + '" id="nakama_uservoice_lbl_function_' + id + '">' + name + '</label>'
                            strAppend += '<div class="item-display-action">';
                            strAppend += '<a href="javascript:void(0)" onclick="deleteFunction(' + id + ')">削除</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                            strAppend += '<a href="javascript:void(0)" onclick="editFunction(' + id + ', \'' + name + '\')">編集</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                            strAppend += '<a href="javascript:void(0)" onclick="editFunctionUp(' + id + ')">↑</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                            strAppend += '<a href="javascript:void(0)" onclick="editFunctionDown(' + id + ')">↓</a>';
                            strAppend += '</div>';
                            strAppend += '</div>';
                            jQuery('.item-function-list-display').append(strAppend);
                            jQuery('.item-function-list-display').animate({
                                scrollTop: jQuery('.item-function-list-display').height(),
                            },1000, function() {});
                        } else if (response.data.action == 'edit') {
                            var strAppend = '<a href="javascript:void(0)" onclick="deleteFunction(' + id + ')">削除</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                            strAppend += '<a href="javascript:void(0)" onclick="editFunction(' + id + ', \'' + name + '\')">編集</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                            strAppend += '<a href="javascript:void(0)" onclick="editFunctionUp(' + id + ')">↑</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                            strAppend += '<a href="javascript:void(0)" onclick="editFunctionDown(' + id + ')">↓</a>';
                            jQuery('#nakama_uservoice_lbl_function_' + id).text(name);
                            jQuery('#nakama_uservoice_lbl_function_' + id).next().html(strAppend);
                        }
                    }
                    jQuery('#nakama_uservoice_function_name').val('');
                    jQuery('#nakama_uservoice_function_id').val('');
                },
                error: function() {},
                complete: function () {
                    jQuery('.img_theme_loading_function').hide();
                    _btnSave.removeAttr('disabled');
                }
            });
        } else {
            jQuery('#nakama_uservoice_function_name').focus();
        }
    });
}

function deleteFunction(function_id) {
    var confirm = window.confirm('機能を削除しますか？');

    if (confirm == true) {
        var data = {
            action: 'delete_function',
            function_id: function_id
        };

        jQuery('.img_theme_loading_function').show();

        jQuery.ajax({
            url: '../wp-admin/admin-ajax.php',
            type : "POST",
            data: data,
            success: function(response) {
                if (response.data) {
                    jQuery('#item-function-list-display-item-' + function_id).remove();
                }
            },
            error: function() {},
            complete: function () {
                jQuery('.img_theme_loading_function').hide();
            }
        });
    }
}

function editFunction(function_id, function_name) {
    jQuery('#nakama_uservoice_function_id').val(function_id).focus();
    jQuery('#nakama_uservoice_function_name').val(function_name);
}

function editFunctionUp(function_id) {
    var currentItem = jQuery('#item-function-list-display-item-' + function_id);
    var prevItem = currentItem.prev();

    if (prevItem.length !== 0) {
        var data = {
            action: 'up_function',
            function_id: function_id
        };

        jQuery('.img_theme_loading_function').show();

        jQuery.ajax({
            url: '../wp-admin/admin-ajax.php',
            type : "POST",
            data: data,
            success: function(response) {
                if (response.data) {
                    currentItem.insertBefore(prevItem);
                }
            },
            error: function() {},
            complete: function () {
                jQuery('.img_theme_loading_function').hide();
            }
        });
    }
}

function editFunctionDown(function_id) {
    var currentItem = jQuery('#item-function-list-display-item-' + function_id);
    var nextItem = currentItem.next();

    if (nextItem.length !== 0) {
        var data = {
            action: 'down_function',
            function_id: function_id
        };

        jQuery('.img_theme_loading_function').show();

        jQuery.ajax({
            url: '../wp-admin/admin-ajax.php',
            type : "POST",
            data: data,
            success: function(response) {
                if (response.data) {
                    currentItem.insertAfter(nextItem);
                }
            },
            error: function() {},
            complete: function () {
                jQuery('.img_theme_loading_function').hide();
            }
        });
    }
}

function saveFileType()
{
    jQuery('#btn-save-file-type').click(function(event) {
        event.stopImmediatePropagation();

        if (jQuery('#nakama_uservoice_file_type_cate').val() == '') {
            jQuery('#nakama_uservoice_file_type_cate').focus();
            return;
        }

        if (jQuery('#nakama_uservoice_file_type_name').val() == '') {
            jQuery('#nakama_uservoice_file_type_name').focus();
            return;
        }

        var _btnSave = jQuery(this);
        _btnSave.attr({'disabled': true});
        var category_id = jQuery('#nakama_uservoice_file_type_cate').val();
        var file_type_name = jQuery('#nakama_uservoice_file_type_name').val();
        var file_type_id = jQuery('#nakama_uservoice_file_type_id').val();
        var post_id = jQuery("input[name=post_ID]").val();

        var data = {
            action: 'save_file_type',
            category_id: category_id,
            file_type_id: file_type_id,
            file_type_name: file_type_name,
            post_id: post_id
        };

        jQuery('.img_theme_loading_file_type').show();

        jQuery.ajax({
            url: '../wp-admin/admin-ajax.php',
            type : "POST",
            data: data,
            success: function(response) {
                if (response.data) {
                    console.log('response.data', response.data);
                    var id = response.data.id;
                    var name = response.data.name;
                    var category_id = response.data.category_id;
                    jQuery('#item-category-file-type-list-display-item-' + category_id).show();
                    if (response.data.action == 'insert') {
                        var strAppend = '<div class="item-list-display-item" id="item-file-type-list-display-item-' + id + '">';
                        strAppend += '&nbsp;&nbsp;&nbsp;&nbsp;';
                        strAppend += '<input class="chk-uservoice-item" name="nakama_uservoice_file_type[]" id="nakama_uservoice_file_type_' + id + '"  value="' + id + '" type="checkbox">';
                        strAppend += '&nbsp;<label for="nakama_uservoice_file_type_' + id + '" id="nakama_uservoice_lbl_file_type_' + id + '">' + name + '</label>'
                        strAppend += '<div class="item-display-action">';
                        strAppend += '<a href="javascript:void(0)" onclick="deleteFileType(' + id + ', ' + category_id + ')">削除</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                        strAppend += '<a href="javascript:void(0)" onclick="editFileType(' + id + ', \'' + name + '\', ' + category_id + ')">編集</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                        strAppend += '<a href="javascript:void(0)" onclick="editFileTypeUp(' + id + ', ' + category_id + ')">↑</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                        strAppend += '<a href="javascript:void(0)" onclick="editFileTypeDown(' + id + ', ' + category_id + ')">↓</a>';
                        strAppend += '</div>';
                        strAppend += '</div>';
                        jQuery('#item-category-file-type-list-display-item-' + category_id).append(strAppend);
                    } else if (response.data.action == 'edit') {
                        var strAppend = '<a href="javascript:void(0)" onclick="deleteFileType(' + id + ', ' + category_id + ')">削除</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                        strAppend += '<a href="javascript:void(0)" onclick="editFileType(' + id + ', \'' + name + '\', ' + category_id + ')">編集</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                        strAppend += '<a href="javascript:void(0)" onclick="editFileTypeUp(' + id + ', ' + category_id + ')">↑</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                        strAppend += '<a href="javascript:void(0)" onclick="editFileTypeDown(' + id + ', ' + category_id + ')">↓</a>';
                        jQuery('#nakama_uservoice_lbl_file_type_' + id).text(name);
                        jQuery('#nakama_uservoice_lbl_file_type_' + id).next().html(strAppend);
                    }
                }
                jQuery('#nakama_uservoice_file_type_cate').val('').prop('selected', true);
                jQuery("#nakama_uservoice_file_type_cate option").prop('disabled', false);
                jQuery('#nakama_uservoice_file_type_name').val('');
                jQuery('#nakama_uservoice_file_type_id').val('');
            },
            error: function() {},
            complete: function () {
                jQuery('.img_theme_loading_file_type').hide();
                _btnSave.removeAttr('disabled');
            }
        });
    });
}

function deleteFileType(file_type_id, category_id) {
    var confirm = window.confirm('アップロードファイルの種類を削除しますか？');

    if (confirm == true) {
        var data = {
            action: 'delete_file_type',
            file_type_id: file_type_id,
            category_id: category_id
        };

        jQuery('.img_theme_loading_file_type').show();

        jQuery.ajax({
            url: '../wp-admin/admin-ajax.php',
            type : "POST",
            data: data,
            success: function(response) {
                if (response.data) {
                    jQuery('#item-file-type-list-display-item-' + file_type_id).remove();
                }
            },
            error: function() {},
            complete: function () {
                jQuery('.img_theme_loading_file_type').hide();
            }
        });
    }
}

function editFileType(file_id, file_name, category_id) {
    jQuery('#nakama_uservoice_file_type_cate').val(category_id).prop('selected', true);
    jQuery("#nakama_uservoice_file_type_cate option:not(:selected)").prop('disabled', true);
    jQuery('#nakama_uservoice_file_type_id').val(file_id).focus();
    jQuery('#nakama_uservoice_file_type_name').val(file_name);
}

function editFileTypeUp(file_type_id, category_id) {
    var currentItem = jQuery('#item-file-type-list-display-item-' + file_type_id);
    var prevItem = currentItem.prev();

    if (prevItem.prop('tagName') !== 'LABEL') {
        var data = {
            action: 'up_file_type',
            file_type_id: file_type_id,
            category_id: category_id
        };

        jQuery('.img_theme_loading_file_type').show();

        jQuery.ajax({
            url: '../wp-admin/admin-ajax.php',
            type : "POST",
            data: data,
            success: function(response) {
                if (response.data) {
                    currentItem.insertBefore(prevItem);
                }
            },
            error: function() {},
            complete: function () {
                jQuery('.img_theme_loading_file_type').hide();
            }
        });
    }
}

function editFileTypeDown(file_type_id, category_id) {
    var currentItem = jQuery('#item-file-type-list-display-item-' + file_type_id);
    var nextItem = currentItem.next();

    if (nextItem.length !== 0) {
        var data = {
            action: 'down_file_type',
            file_type_id: file_type_id,
            category_id: category_id
        };

        jQuery('.img_theme_loading_file_type').show();

        jQuery.ajax({
            url: '../wp-admin/admin-ajax.php',
            type : "POST",
            data: data,
            success: function(response) {
                if (response.data) {
                    currentItem.insertAfter(nextItem);
                }
            },
            error: function() {},
            complete: function () {
                jQuery('.img_theme_loading_file_type').hide();
            }
        });
    }
}