function OnReg(){
    $("#mainForm").submit();
    if (typeof(Storage) !== 'undefined') {
        sessionStorage.removeItem('allitem');
    }
    $('input[type="button"]').attr("disabled", "disabled");
    $('.preLoading').css('display', 'block');
}

function OnReinput(){
    window.history.back();
    if (typeof(Storage) !== 'undefined') {
        sessionStorage.setItem('confirm_back', 'back');
    }
    $('input[type="button"]').attr("disabled", "disabled");
    $('.preLoading').css('display', 'block');
}