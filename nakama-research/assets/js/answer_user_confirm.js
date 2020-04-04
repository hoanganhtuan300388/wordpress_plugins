history.forward();

function OnCommand(cmd){
  var form = document.mainForm;
  switch (cmd) {
  case "next":

    if(form.tos_consent[0].checked != true){
      alert("ご入力内容の取扱いに関する事項をよく確認し、必ず「同意する」を選択してください。");
      return;
    }

    form.action = "ans_research.asp";
    break;
  defalt:
    form.action = "agreement.asp";
    break;
  }
  form.submit();
}