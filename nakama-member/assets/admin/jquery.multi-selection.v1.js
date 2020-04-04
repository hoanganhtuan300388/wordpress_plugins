/*!
 * jQuery Multi Selection
 * https://github.com/xenialaw/jQuery-Multi-Selection
 *
 * Includes jQuery Library
 * http://www.jquery.com/
 *
 * 
 * Copyright 2018 © Soyo Solution Company. and other contributors
 * Released under the MIT license
 * http://jquery.org/license
 *
 * Version           : v1.0
 * Author            : Xenia Law
 * Created date      : 2018-09-14
 * Last updated date : 2018-09-19
 */

(function ( $ ) {
    $.fn.jQueryMultiSelection = function(options) {
        
        var thisEle = this;
        var _opts = $.extend({
                        enableDynamicAddContent : false,
                        ajaxSourceUrl           : "../dist/list.json",
                        btnGetJson              : "#btn-ajax",
                        selectMeunFrom          : ".from-panel select",
                        selectMeunTo            : ".to-panel select",
                        
                        btnMoveAllRight         : ".btn-move-all-right",
                        btnMoveSelectedRight    : ".btn-move-selected-right",
                        btnMoveAllLeft          : ".btn-move-all-left",
                        btnMoveSelectedLeft     : ".btn-move-selected-left",
                        btnDelete               : ".btn-delete",
                        btnMoveUp               : ".btn-up",
                        btnMoveDown             : ".btn-down",
                        
                        htmlMoveAllRight        : "&rsaquo;&rsaquo;",
                        htmlMoveSelectedRight   : "&rsaquo;",
                        htmlMoveAllLeft         : "&lsaquo;&lsaquo;",
                        htmlMoveSelectedLeft    : "&lsaquo;",
                        htmlDelete              : "Delete",
                        htmlMoveUp              : "Up",
                        htmlMoveDown            : "Down",
                    }, options);
        jQueryMultiSelection = {
            init: function(){
                setInterface();
                setDeleteBtnListener();
                setUpBtnListener();
                setDownBtnListener();
                setMoveBtnsListener();
                if(_opts.enableDynamicAddContent) setAjaxBtnListener();
            }
        };
        
        function setInterface(){
            $(_opts.btnMoveAllRight).html(_opts.htmlMoveAllRight);
            $(_opts.btnMoveSelectedRight).html(_opts.htmlMoveSelectedRight);
            $(_opts.btnMoveAllLeft).html(_opts.htmlMoveAllLeft);
            $(_opts.btnMoveSelectedLeft).html(_opts.htmlMoveSelectedLeft);
            $(_opts.btnDelete).html(_opts.htmlDelete);
            $(_opts.btnMoveUp).html(_opts.htmlMoveUp);
            $(_opts.btnMoveDown).html(_opts.htmlMoveDown);
        }

        $('.unselected').change(function() {
            $('.btn_r').prop('disabled', false);
        });
        $('.unselected').click(function(e) {
            var listUnselected = $($('select.unselected')[0])
            for(var i = 0; i < listUnselected[0].length; i++){
                $(listUnselected[0][i]).prop("selected",false)
            }
            $(e.target).prop("selected",true)
        });
        $('.selected').click(function(e) {
            var listSelected = $($('select.selected')[0])
            for(var i = 0; i < listSelected[0].length; i++){
                $(listSelected[0][i]).prop("selected",false)
            }
            $(e.target).prop("selected",true)
        });

        $('.selected').change(function() {
            $('.btn_l').prop('disabled', false);
            $('.btn_up').prop('disabled', false);
            $('.btn_down').prop('disabled', false);
        });
        $('.btn_r').prop('disabled', true);
        $('.unselected').has('option').each(function(){
            if($(this).is(':selected')){
                $('.btn_r').prop('disabled', false);
                return false;
            } else {
                $('.btn_r').prop('disabled', true);
            }
        })
        $('.btn_l').prop('disabled', true);
        $('.btn_up').prop('disabled', true);
        $('.btn_down').prop('disabled', true);
        $('.selected').has('option').each(function(){
            if($(this).is(':selected')){
                $('.btn_l').prop('disabled', false);
                $('.btn_up').prop('disabled', false);
                $('.btn_down').prop('disabled', false);
                return false;
            } else {
                $('.btn_l').prop('disabled', true);
                $('.btn_up').prop('disabled', true);
                $('.btn_down').prop('disabled', true);
            }
        })

        function setDeleteBtnListener(){
            $(_opts.btnDelete).click( function () { 
                $(this).parent().prev().find("select option:selected").each(function(){
                    $(this).remove();
                });
            });
        }
        
        function setUpBtnListener(){
            $(_opts.btnMoveUp).click( function () { 
                $(this).parent().parent().parent().prev().find("select option:selected").each(function(){
                    var options = $(this).parent().find("option");
                    var newPos  = options.index(this) - 1;
                    if (newPos > -1) {
                        options.eq(newPos).before("<option value='"+$(this).val()+"' selected='selected'>"+$(this).text()+"</option>");
                        $(this).remove();
                    }
                });
            });
        }
        
        function setDownBtnListener(){
            $(_opts.btnMoveDown).click( function () { 
                $(this).parent().parent().parent().prev().find("select option:selected").each(function(){
                    var options = $(this).parent().find("option");
                    var newPos  = options.index(this) + 1;
                    if (newPos < options.size()) {
                        options.eq(newPos).after("<option value='"+$(this).val()+"' selected='selected'>"+$(this).text()+"</option>");
                        $(this).remove();
                    }
                });
            });
        }
        
        function setMoveBtnsListener(){
            $(_opts.btnMoveAllRight).click(function(){ _multiTransfer(this, true, true)});
            $(_opts.btnMoveSelectedRight).click(function(){ 
                _multiTransfer(this, false, true); 
                $('.btn_r').prop('disabled', true);
                $('.btn_l').prop('disabled', false);
                $('.btn_up').prop('disabled', false);
                $('.btn_down').prop('disabled', false);
            });
            $(_opts.btnMoveAllLeft).click(function(){ _multiTransfer(this, true, false)});
            $(_opts.btnMoveSelectedLeft).click(function(){
                _multiTransfer(this, false, false);
                $('.btn_l').prop('disabled', true);
                $('.btn_r').prop('disabled', false);
                $('.btn_up').prop('disabled', true);
                $('.btn_down').prop('disabled', true);
            });
        }
        
        function _multiTransfer(btnEle, isAll, isToRight){
            var nextList = $(btnEle).parent().parent().parent().next().find("select");
            var prevList = $(btnEle).parent().parent().parent().prev().find("select");
            var fromList = isToRight ? prevList: nextList;
            var toList   = isToRight ? nextList: prevList;
            var selector = isAll     ? fromList.find("option"): fromList.find("option:selected");
            var se = toList.find(":selected");
            for(var i=0; i < $(toList)[0].length; i++)
            {
                $($(toList)[0][i]).prop("selected", false);
            }
            selector.each(function() {
                $(this).remove();
                if(se.val() == undefined){
                    toList.append($(this));
                }
                else {
                    $(this).insertBefore(se);
                }
            });
        }
        function setAjaxBtnListener(){
            $(_opts.btnGetJson).one( "click", function() {
                var thisBtnEle = $(this);
                $.ajax({
                    url: _opts.ajaxSourceUrl,
                    dataType: "json",
                    data: { format: "json"},
                    success: function( response ) {
                        var tempStr = "";
                        var jsonItemsSize = Object.size(response);
                        for(var i =0; i<jsonItemsSize; i++){
                            tempStr += '<option title="'+i+'" value="'+response[i].value+'">'+response[i].text+"</option>";
                        }
                        thisEle.find(_opts.selectMeunFrom).append(tempStr);
                    },
                    complete:function(){},
                    error: function(  jqXHR,textStatus,errorThrown ) {}
                });
            });
        }
        
        Object.size = function(obj) {
            var size = 0, key;
            for (key in obj) { if (obj.hasOwnProperty(key)) size++; }
            return size;
        };
        
        jQueryMultiSelection.init();
        // disable shift + click
        function mouseDown(e) {
            var shiftPressed=0;
            var evt = e?e:window.event;
            if (parseInt(navigator.appVersion)>3) {
             if (document.layers && navigator.appName=="Netscape")
                  shiftPressed=(evt.modifiers-0>3);
             else shiftPressed=evt.shiftKey;
             if (shiftPressed) {
              return false;
             }
            }
            return true;
        }
        if (parseInt(navigator.appVersion)>3) {
            document.onmousedown = mouseDown;
            if (document.layers && navigator.appName=="Netscape") 
                document.captureEvents(Event.MOUSEDOWN);
        }
        // disable ctrl + a
        $(function(){   
            $(document).keydown(function(objEvent) {        
                if (objEvent.ctrlKey) {          
                    if (objEvent.keyCode == 65) {
                        return false;
                    }            
                }        
            });
        });
        
    };
}( jQuery ));