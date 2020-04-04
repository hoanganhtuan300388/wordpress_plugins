function OnChangePersonalData(p_id){
	window.opener.location.href = "PersonalDataReg/input.asp?chg=1&p_id=" + p_id;
	window.opener.focus();
	window.close();
}
function OnChangeGroupMemberData(g_id, p_id){
	window.opener.location.href = "GroupMng/MemberReg.asp?g_id=" + g_id + "&p_id=" + p_id;
	window.opener.focus();
	window.close();
}
function OnShowSameGroupMember(gid){
	window.opener.ShowSameGroupMember(gid);
	window.close();
}
window.focus();

function ShowImage(imgType){
	var imgPath;
	
	if (imgType == "1") {
		imgPath = "https://wn.cococica.com/omm/imgs/group/logo/dmshibuyag0052.";
	} else if(imgType == "2") {
		imgPath = "https://wn.cococica.com/omm/imgs/group/img/dmshibuyag0052.";
	} else if(imgType == "3") {
		imgPath = "https://wn.cococica.com/omm/imgs/personal/dmshibuyap0052.";
	} else if(imgType == "4") {
		imgPath = "https://wn.cococica.com/omm/imgs/personal/img2/dmshibuyap0052.";
	} else if(imgType == "5") {
		imgPath = "https://wn.cococica.com/omm/imgs/personal/img3/dmshibuyap0052.";
	}
	location.href = "./window/ImageView.asp?img=" + imgPath;
}
function GroupDetail(tg_id,p_id){
	location.reload();
}
function OnChangeGid(gid){
	location.href =  "Detail.asp" +
	"?mode=1" +
	"&g_id=" + gid +
	"&p_id=dmshibuyap0052" +
	"&pHistNo=" +
	"&lg_g_id=" +
	"&gmlist=";
}
function OnMouseOver(buttonObj){
	buttonObj.style.backgroundColor = "#abcdef";
	buttonObj.style.color = "blue";
}
function OnMouseOut2(buttonObj){
	buttonObj.style.backgroundColor = "buttonface";
	buttonObj.style.color = "buttontext";
}
function OnFeeStatus(){
	gToolWnd = window.open('./FeeStatus.asp',
		'FeeWnd',
		'width=950,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}
