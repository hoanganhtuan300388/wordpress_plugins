jQuery(document).ready(function($) {
  $(".copy_shortcode").click(function(event) {
    var copyText = $(this).parent().prev().children('input');
    copyText.select();
    document.execCommand("copy");
    event.preventDefault();
  });
});


