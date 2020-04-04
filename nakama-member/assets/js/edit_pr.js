function OnRelease(){}
function OnConfirm(){
    var form = document.mainForm;
    if(form.G_APPEAL.value == ''){
        alert("相識アピール\nは必ず入力してください");
    }
    else {
        form.submit();
    } 
}