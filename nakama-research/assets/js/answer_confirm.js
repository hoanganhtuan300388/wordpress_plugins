history.forward();

function OnCommand(cmd){
  var form = document.mainForm;
  switch (cmd) {
  case "next":

    form.action = "ans_research.asp";
    break;
  defalt:
    form.action = "agreement.asp";
    break;
  }
  form.submit();
}