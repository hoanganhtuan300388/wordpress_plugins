(function($) {
    var name_space = 'autokana';
    $.fn[name_space] = function(options) {

        var elements = this;
        var elm_old_val = $(this).val();
        var options = $.extend({
          'suffix' : '_kana',
          'target' : false
        }, options);

        var check_value = function () {
          var val = $(this).val().replace(elm_old_val, '');
          var rmf = $.data(this, name_space);
          var hit = [];
          $.each(val.split('').reverse(), function () {
            var c = this.charCodeAt();
            //hiragana?
            if (c < 0x3041 || c > 0x3096) return false;
            hit.push(this);
          });
          if (!hit.length) return;
          rmf.old = hit.reverse().join('');
          var tmpstr = rmf.old;
          var i, c, a = [];
          for(i=tmpstr.length-1;0<=i;i--){
            c = tmpstr.charCodeAt(i);
            a[i] = (0x3041 <= c && c <= 0x3096) ? c + 0x0060 : c;
          }
          rmf.old = String.fromCharCode.apply(null, a);
        };

        var set_value = function () {
          var rmf = $.data(this, name_space);
          if (!rmf.old) return;
          var target = options.suffix
            ? $('[name="'+options.suffix+'"]')
            : $(options.target)
          ;
          var kana = $(target);
          kana.val(kana.val() + rmf.old);
          rmf.old = '';
          elm_old_val = $(this).val();
        };

        elements.each(function() {
          var key_func = function (env) {
            //space OR enter?
            if(env.keyCode != 32 && env.keyCode != 13){
              //A-Z?
              if(env.keyCode >= 48 && env.keyCode <= 90){
                check_value.call(this);
              }
            }else{
              if(env.keyCode == 32){
                var rmf = $.data(this, name_space);
                if(rmf.old) return;
                rmf.old = '@';
              }
              set_value.call(this);
            }
          };
          ( function (v) {
            return ([$.data(v.get(0), name_space, {}), v])[1];
          } )( $(this) )
            .keyup(key_func)
            .keydown(key_func)
            .keypress(key_func)
            .bind('text', check_value)
            .blur(set_value)
          ;
        });
        return this;
    };
})(jQuery);
