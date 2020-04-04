function OnRelease(){
    var form = document.mainForm;
    form.release.value = "1";
    form.method = "post";
    form.action = "confirm.asp";
    form.submit();
   }
   
   
   function OnLoad(){
    var form = document.mainForm;
   
     SetData();
   
     if(form.FAX_TIME_FROM != undefined) {
       
       if (form.FAX_TIMEZONE.type != "hidden"){
         
         if(form.FAX_TIMEZONE[4].checked == true){
           form.FAX_TIME_FROM_H.disabled = false;
           form.FAX_TIME_FROM_N.disabled = false;
           form.FAX_TIME_TO_H.disabled   = false;
           form.FAX_TIME_TO_N.disabled   = false;
         } else {
           form.FAX_TIME_FROM_H.disabled = true;
           form.FAX_TIME_FROM_N.disabled = true;
           form.FAX_TIME_TO_H.disabled   = true;
           form.FAX_TIME_TO_N.disabled   = true;
         }
       }
     }
   
   }
   
   function OnConfirm(){
    var form = document.mainForm;
   
   
    if(!chkMustInput()) return;
   
   
     var e_flg;
     var e_flg2;
     var f_flg;
     e_flg = 0
     e_flg2 = 0
     f_flg = 0
   
     if(document.mainForm.M_MEMBER_TYPE != undefined && document.mainForm.M_CONTACTDEST != undefined &&
        document.mainForm.M_CO_C_EMAIL != undefined && document.mainForm.M_CO_C_FAX_1 != undefined){
       if(document.mainForm.M_CO_C_EMAIL.type != "hidden" && document.mainForm.M_CO_C_FAX_1.type != "hidden"){
   
         var in_mail = false;      
         var in_fax = false;       
         var sel_contact = "";     
         var focus_obj_mail = "";  
         var focus_obj_fax = "";   
         switch (form.M_CONTACTDEST.value){
         
         case "0":
           if(form.P_C_EMAIL != undefined){
             if(form.P_C_EMAIL.value != ""){
               in_mail = true;
             }
           }
           if(form.P_C_PMAIL != undefined){
             if(form.P_C_PMAIL.value != ""){
               in_mail = true;
             }
           }
           if(form.P_C_FAX_1 != undefined){
             if(form.P_C_FAX_1.value != ""){
               in_fax = true;
             }
           }
           sel_contact = "個人データ登録先";
           focus_obj_mail = form.P_C_EMAIL;
           focus_obj_fax = form.P_C_FAX_1;
           break;
   
         
         case "4":
           if(form.G_EMAIL != undefined){
             if(form.G_EMAIL.value != ""){
               in_mail = true;
             }
           }
           if(form.G_FAX_1 != undefined){
             if(form.G_FAX_1.value != ""){
               in_fax = true;
             }
           }
           sel_contact = "組織データ登録先";
           focus_obj_mail = form.G_EMAIL;
           focus_obj_fax = form.G_FAX_1;
           break;
   
         
         case "5":
           if(form.P_P_EMAIL != undefined){
             if(form.P_P_EMAIL.value != ""){
               in_mail = true;
             }
           }
           if(form.P_P_FAX_1 != undefined){
             if(form.P_P_FAX_1.value != ""){
               in_fax = true;
             }
           }
           sel_contact = "プライベート情報登録先";
           focus_obj_mail = form.P_P_EMAIL;
           focus_obj_fax = form.P_P_FAX_1;
           break;
   
         
         case "2":
           if(form.M_CO_C_EMAIL != undefined){
             if(form.M_CO_C_EMAIL.value != ""){
               in_mail = true;
             }
           }
           if(form.M_CO_C_FAX_1 != undefined){
             if(form.M_CO_C_FAX_1.value != ""){
               in_fax = true;
             }
           }
           sel_contact = "新たに設定";
           focus_obj_mail = form.M_CO_C_EMAIL;
           focus_obj_fax = form.M_CO_C_FAX_1;
           break;
   
         }
   
         
         switch (form.M_MEMBER_TYPE.value){
         
         case "1":
           if(in_mail == false && in_fax == true){
             if(!confirm("メールアドレスが未入力です。\n" +
                      "連絡手段を【FAX会員】に変更してもよろしいですか？\n\n" +
                      "『は　い』　⇒　連絡手段を【FAX会員】に変更します\n" +
                      "『いいえ』　⇒　連絡手段の変更は行いません")) {
               if(focus_obj_mail != undefined){
                 if(focus_obj_mail.type != "hidden"){
                   if(!confirm("連絡先指定【" + sel_contact +"】のメールアドレスが\n未入力ですがよろしいですか？")) {
                       focus_obj_mail.focus();
                       return;
                   }
                 }
               }
             }else{
               form.M_MEMBER_TYPE.value = 2;
             }
           }else if(in_mail == false && in_fax == false){
             if(!confirm("メールアドレス、FAX番号が未登録です。\n" +
                      "連絡手段を【不明】に変更してもよろしいですか？\n\n" +
                      "『は　い』　⇒　連絡手段を【不明】に変更します\n" +
                      "『いいえ』　⇒　連絡手段の変更は行いません")) {
               if(focus_obj_mail != undefined){
                 if(focus_obj_mail.type != "hidden"){
                   if(!confirm("連絡先指定【" + sel_contact +"】のメールアドレスが\n未入力ですがよろしいですか？")) {
                     errProc(focus_obj_mail);
                     return;
                   }
                 }
               }
             }else{
               form.M_MEMBER_TYPE.value = 0;
             }
           }else{
           }
           break;
   
         
         case "2":
           if(in_mail == true && in_fax == false){
             if(!confirm("FAX番号が未入力です。\n" +
                      "連絡手段を【メール会員】に変更してもよろしいですか？\n\n" +
                      "『は　い』　⇒　連絡手段を【メール会員】に変更します\n" +
                      "『いいえ』　⇒　連絡手段の変更は行いません")) {
               if(focus_obj_fax != undefined){
                 if(focus_obj_fax.type != "hidden"){
                   if(!confirm("連絡先指定【" + sel_contact +"】のFAX番号が\n未入力ですがよろしいですか？")) {
                     errProc(focus_obj_fax);
                     return;
                   }
                 }
               }
             }else{
               form.M_MEMBER_TYPE.value = 1;
             }
           }else if(in_mail == true && in_fax == true){
             if(confirm("メールアドレスが入力されています。\n" +
                      "連絡手段を【メール会員】に変更してもよろしいですか？\n\n" +
                      "『は　い』　⇒　連絡手段を【メール会員】に変更します\n" +
                      "『いいえ』　⇒　連絡手段の変更は行いません")) {
               form.M_MEMBER_TYPE.value = 1;
             }
           }else if(in_mail == false && in_fax == false){
             if(!confirm("メールアドレス、FAX番号が未入力です。\n" +
                      "連絡手段を【不明】に変更してもよろしいですか？\n\n" +
                      "『は　い』　⇒　連絡手段を【不明】に変更します\n" +
                      "『いいえ』　⇒　連絡手段の変更は行いません")) {
               if(focus_obj_fax != undefined){
                 if(focus_obj_fax.type != "hidden"){
                   if(!confirm("連絡先指定【" + sel_contact +"】のFAX番号が\n未入力ですがよろしいですか？")) {
                     errProc(focus_obj_fax);
                     return;
                   }
                 }
               }
             }else{
               form.M_MEMBER_TYPE.value = 0;
             }
           }
           break;
   
         
         default:
           if(in_mail == true){
             if(confirm("メールアドレスが入力されています。\n" +
                      "連絡手段を【メール会員】に変更してもよろしいですか？\n\n" +
                      "『は　い』　⇒　連絡手段を【メール会員】に変更します\n" +
                      "『いいえ』　⇒　連絡手段の変更は行いません")) {
               form.M_MEMBER_TYPE.value = 1;
             }
           }else if(in_fax == true){
             if(confirm("FAX番号が入力されています。\n" +
                      "連絡手段を【FAX会員】に変更してもよろしいですか？\n\n" +
                      "『は　い』　⇒　連絡手段を【FAX会員】に変更します\n" +
                      "『いいえ』　⇒　連絡手段の変更は行いません")) {
               form.M_MEMBER_TYPE.value = 2;
             }
           }
           break;
         }
   
       }
     }
   
   
     if(form.M_STATUS != undefined){
       if(form.M_STATUS.value == "1"){
         var tmpY;
         var tmpM;
         var tmpD;
         var tmpY2;
         var tmpM2;
         var tmpD2;
         
         if(form.M_ADMISSION_DATE_Y != undefined && form.HDN_ADMISSION_DATE_Y != undefined){
           tmpY = form.M_ADMISSION_DATE_Y.value;
           if(getByte(form.M_ADMISSION_DATE_M.value) == 1) {
             tmpM = "0" + form.M_ADMISSION_DATE_M.value;
           }else{
             tmpM = form.M_ADMISSION_DATE_M.value;
           }
           if(getByte(form.M_ADMISSION_DATE_D.value) == 1) {
             tmpD = "0" + form.M_ADMISSION_DATE_D.value;
           }else{
             tmpD = form.M_ADMISSION_DATE_D.value;
           }
           tmpY2 = form.HDN_ADMISSION_DATE_Y.value;
           if(getByte(form.HDN_ADMISSION_DATE_M.value) == 1) {
             tmpM2 = "0" + form.HDN_ADMISSION_DATE_M.value;
           }else{
             tmpM2 = form.HDN_ADMISSION_DATE_M.value;
           }
           if(getByte(form.HDN_ADMISSION_DATE_D.value) == 1) {
             tmpD2 = "0" + form.HDN_ADMISSION_DATE_D.value;
           }else{
             tmpD2 = form.HDN_ADMISSION_DATE_D.value;
           }
           if(tmpY2 != "" && tmpM2 != "" && tmpD2 != ""){
             if(tmpY != tmpY2 || tmpM != tmpM2 || tmpD != tmpD2){
               if(!confirm(form.HDN_ADMISSION_NAME.value + "を変更しようとしています。本当によろしいですか？")) return;
             }
           }
         }
         
         if(form.M_WITHDRAWAL_DATE_Y != undefined && form.HDN_WITHDRAWAL_DATE_Y != undefined){
           tmpY = form.M_WITHDRAWAL_DATE_Y.value;
           if(getByte(form.M_WITHDRAWAL_DATE_M.value) == 1) {
             tmpM = "0" + form.M_WITHDRAWAL_DATE_M.value;
           }else{
             tmpM = form.M_WITHDRAWAL_DATE_M.value;
           }
           if(getByte(form.M_WITHDRAWAL_DATE_D.value) == 1) {
             tmpD = "0" + form.M_WITHDRAWAL_DATE_D.value;
           }else{
             tmpD = form.M_WITHDRAWAL_DATE_D.value;
           }
           tmpY2 = form.HDN_WITHDRAWAL_DATE_Y.value;
           if(getByte(form.HDN_WITHDRAWAL_DATE_M.value) == 1) {
             tmpM2 = "0" + form.HDN_WITHDRAWAL_DATE_M.value;
           }else{
             tmpM2 = form.HDN_WITHDRAWAL_DATE_M.value;
           }
           if(getByte(form.HDN_WITHDRAWAL_DATE_D.value) == 1) {
             tmpD2 = "0" + form.HDN_WITHDRAWAL_DATE_D.value;
           }else{
             tmpD2 = form.HDN_WITHDRAWAL_DATE_D.value;
           }
           if(tmpY2 != "" && tmpM2 != "" && tmpD2 != ""){
             if(tmpY != tmpY2 || tmpM != tmpM2 || tmpD != tmpD2){
               if(!confirm(form.HDN_WITHDRAWAL_NAME.value + "を変更しようとしています。本当によろしいですか？")) return;
             }
           }
         }
       }
     }
   
    if(!CheckInputData()) return;
   
   
   
     document.mainForm.M_MEMBER_TYPE.disabled = false;
     document.mainForm.M_LG_G_ID_SEL.disabled = false;
     document.mainForm.M_LG_G_ID.disabled = false;
   
     if(document.mainForm.G_O_NAME.value != '1'){
       document.mainForm.G_LIST_NODISP.value = '0';
     }
   
   }
   
   function ShowLowerGroupName(gid){
    var i;
    var obj = document.mainForm.M_LG_G_ID_SEL;
    if(obj.type == "hidden") return;
    for(i=0;i<obj.options.length;i++){
     if(obj.options[i].value==gid){
      obj.selectedIndex = i;
      break;
     }
    }
    if(i==obj.options.length){
     obj.selectedIndex = 0;
    }
    document.mainForm.M_LG_NAME.value = obj.options[obj.selectedIndex].text;
   }
   
   function OnMakeGidAuto(){
    document.mainForm.G_G_ID.value = GetMakeGid();
    OnBlurGid();
   }
   
   function OnMakePidAuto(){
    var ifmGetData = getData4;
    var ifmSetUrl;
    ifmSetUrl = "../rs/commonRsSearch.asp?fncName=MakePid";
    ifmGetData.location.href = ifmSetUrl;
   }
   
   function retSearchVal_MakePid(retSearchVal){
    if(retSearchVal == "Err"){
      alert("IDの採番に失敗しました。");
      return;
    }
    document.mainForm.P_P_ID.value = retSearchVal;
    OnBlurPid();
   }
   
   function OnMakeGidAuto2(){
    var ifmGetData = getData3;
    var ifmSetUrl;
    ifmSetUrl = "../rs/commonRsSearch.asp?fncName=MakeGid";
    ifmGetData.location.href = ifmSetUrl;
   }
   
   function retSearchVal_MakeGid(retSearchVal){
    if(retSearchVal == "Err"){
      alert("IDの採番に失敗しました。");
      return;
    }
    document.mainForm.G_G_ID.value = retSearchVal;
    OnBlurGid();
   }
   
   
   function retSearchVal_CheckPidGid(retSearchVal,ArgVal){
    
    if(ArgVal > 0){
      OnMakeGidAuto2();
    }
    
    if(retSearchVal > 0){
      OnMakePidAuto();
    }
   }
   
   function SetData(){
    var form = document.mainForm;
   
   
    if(form.M_CLAIMDEST != undefined){
     
     changeClaimForm();
    }
    if(form.M_CONTACTDEST != undefined){
     
     changeContactForm();
    }
    
    if(form.M_CLAIM_CLASS != undefined){
     OnClaimClassChange(mainForm.M_CLAIM_CLASS.value);
    }
    
   
   
    if(form.M_LG_G_ID != undefined){
     ShowLowerGroupName(form.M_LG_G_ID.value);
    }
   
   }
   
   
   function OnZipCallbackG(zipcode, sta, addr1, addr2){
    var form = document.mainForm;
    form.G_POST_u.value = zipcode.slice(0,3);
    form.G_POST_l.value = zipcode.slice(3,8);
    form.G_STA.value = sta;
    form.G_ADDRESS.value = addr1;
    form.G_ADDRESS2.value = addr2;
   }
   
   function OnZipCallbackP(zipcode, sta, addr1, addr2){
    var form = document.mainForm;
    form.P_C_POST_u.value = zipcode.split("-")[0];
    form.P_C_POST_l.value = zipcode.split("-")[1];
    form.P_C_STA.value = sta;
    form.P_C_ADDRESS.value = addr1;
    form.P_C_ADDRESS2.value = addr2;
   }
   
   function OnZipCallbackPP(zipcode, sta, addr1, addr2){
    var form = document.mainForm;
    form.P_P_POST_u.value = zipcode.split("-")[0];
    form.P_P_POST_l.value = zipcode.split("-")[1];
    form.P_P_STA.value = sta;
    form.P_P_ADDRESS.value = addr1;
    form.P_P_ADDRESS2.value = addr2;
   }
   
   function OnSearchLowerGroup(){
    gToolWnd = window.open('./window/search_lg.asp?top_g_id=dmshibuya&formName=mainForm.M_LG_G_ID',
     'SearchWnd',
     'width=760,height=500,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes')
   }
   
   function OnSearchCategory(){
    gToolWnd = window.open('./window/search_category.asp?formName=mainForm.G_CATEGORY',
     'SearchWnd',
     'width=760,height=500,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes')
   }
   
   function OnSearchBankG(){
     if(document.mainForm.G_BANK_CODE.value == ''){
       alert('銀行コードを選択してください。');
       return;
     }
     gToolWnd = window.open('./window/search_bank.asp?formName=mainForm.G_BRANCH_CODE&bank_code=' + document.mainForm.G_BANK_CODE.value,
      'SearchWnd',
      'width=760,height=500,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes')
   }
   function OnSearchBankP(){
     if(document.mainForm.P_BANK_CODE.value == ''){
       alert('銀行コードを選択してください。');
       return;
     }
     gToolWnd = window.open('./window/search_bank.asp?formName=mainForm.P_BRANCH_CODE&bank_code=' + document.mainForm.P_BANK_CODE.value,
      'SearchWnd',
      'width=760,height=500,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes')
   }
   function OnSearchBankM(){
     if(document.mainForm.M_BANK_CODE.value == ''){
       alert('銀行コードを選択してください。');
       return;
     }
     gToolWnd = window.open('./window/search_bank.asp?formName=mainForm.M_BRANCH_CODE&bank_code=' + document.mainForm.M_BANK_CODE.value,
      'SearchWnd',
      'width=760,height=500,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes')
   }
   
   
   function CheckNeed(elem, bCheck){
    var i;
    var buf;
    var tag;
    
    if(bCheck){
     for(i=0;i<elem.length;i++){
      
      elem[i].innerHTML = '<span style="color: #FF0000">※</span>' + elem[i].innerHTML;
     }
    
    }else{
     for(i=0;i<elem.length;i++){
      
      buf = elem[i].innerHTML;
      buf = buf.toUpperCase();
      
      tag = '<span style="color: #FF0000">※</span>';
      tag = tag.toUpperCase();
      
      buf = buf.replace(new RegExp(tag, "g"), "");
      tag = tag.toUpperCase();
      
      buf = buf.replace(new RegExp(tag, "g"), "");
      
      elem[i].innerHTML = buf;
     }
    }
   }
   function OnContactDestChangeRadio(val,msg){
    var form = document.mainForm;
    form.M_CONTACTDEST.value = val;
     
     changeContactForm(true);
   }
   
   function OnClaimDestChangeRadio(val,msg){
    var form = document.mainForm;
    form.M_CLAIMDEST.value = val;
     
     changeClaimForm(true);
   }
   
   function OnExplanationFile(url){
     gToolWnd = window.open(url,
      'SearchWnd',
      'width=760,height=500,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes')
   }
   
   
   function IsContactSame(val){
    var form = document.mainForm;
    
    switch (val) {
    
    case "0":
     if(form.M_CO_G_NAME != undefined){
       if(form.M_CO_G_NAME.value     != form.G_NAME.value)       return false;
     }
     if(form.M_CO_G_KANA != undefined){
       if(form.M_CO_G_KANA.value     != form.G_KANA.value)       return false;
     }
     if(form.M_CO_C_NAME.value     != form.P_C_NAME.value)       return false;
     if(form.M_CO_C_KANA.value     != form.P_C_KANA.value)       return false;
       if(form.M_CO_C_AFFILIATION != undefined){
         if(form.M_CO_C_AFFILIATION.value != form.S_AFFILIATION_NAME.value)  return false;
       }
       if(form.M_CO_C_POSITION != undefined){
         if(form.M_CO_C_POSITION.value    != form.S_OFFICIAL_POSITION.value) return false;
       }
     if(form.M_CO_C_EMAIL.value    != form.P_C_EMAIL.value)      return false;
     if(form.M_CO_C_CC_EMAIL != undefined){
       if(form.M_CO_C_CC_EMAIL.value != form.P_C_CC_EMAIL.value)   return false;
     }
     if(form.M_CO_C_TEL.value      != form.P_C_TEL.value)        return false;
     if(form.M_CO_C_TEL_1.value    != form.P_C_TEL_1.value)      return false;
     if(form.M_CO_C_TEL_2.value    != form.P_C_TEL_2.value)      return false;
     if(form.M_CO_C_TEL_3.value    != form.P_C_TEL_3.value)      return false;
     if(form.M_CO_C_FAX.value      != form.P_C_FAX.value)        return false;
     if(form.M_CO_C_FAX_1.value    != form.P_C_FAX_1.value)      return false;
     if(form.M_CO_C_FAX_2.value    != form.P_C_FAX_2.value)      return false;
     if(form.M_CO_C_FAX_3.value    != form.P_C_FAX_3.value)      return false;
     if(form.M_CO_C_POST.value     != form.P_C_POST.value)       return false;
     if(form.M_CO_C_POST_u.value   != form.P_C_POST_u.value)     return false;
     if(form.M_CO_C_POST_l.value   != form.P_C_POST_l.value)     return false;
     if(form.M_CO_C_STA.value      != form.P_C_STA.value)        return false;
     if(form.M_CO_C_ADDRESS.value  != form.P_C_ADDRESS.value)    return false;
     if(form.M_CO_C_ADDRESS2.value != form.P_C_ADDRESS2.value)   return false;
     return true;
    
    case "2":
      return true;
    
    case "4":
     if(form.M_CO_G_NAME != undefined){
       if(form.M_CO_G_NAME.value      != form.G_NAME.value)          return false;
     }
     if(form.M_CO_G_KANA != undefined){
       if(form.M_CO_G_KANA.value      != form.G_KANA.value)          return false;
     }
     if(form.M_CO_C_NAME.value      != form.P_C_NAME.value)        return false;
     if(form.M_CO_C_KANA.value      != form.P_C_KANA.value)        return false;
   
       if(form.M_CO_C_AFFILIATION != undefined){
         if(form.M_CO_C_AFFILIATION.value != form.S_AFFILIATION_NAME.value)  return false;
       }
       if(form.M_CO_C_POSITION != undefined){
         if(form.M_CO_C_POSITION.value    != form.S_OFFICIAL_POSITION.value) return false;
       }
   
     if(form.M_CO_C_EMAIL.value     != form.G_EMAIL.value)       return false;
     if(form.M_CO_C_CC_EMAIL != undefined){
       if(form.M_CO_C_CC_EMAIL.value  != form.G_CC_EMAIL.value)    return false;
     }
     if(form.M_CO_C_TEL.value       != form.G_TEL.value)         return false;
     if(form.M_CO_C_TEL_1.value     != form.G_TEL_1.value)       return false;
     if(form.M_CO_C_TEL_2.value     != form.G_TEL_2.value)       return false;
     if(form.M_CO_C_TEL_3.value     != form.G_TEL_3.value)       return false;
     if(form.M_CO_C_FAX.value       != form.G_FAX.value)         return false;
     if(form.M_CO_C_FAX_1.value     != form.G_FAX_1.value)       return false;
     if(form.M_CO_C_FAX_2.value     != form.G_FAX_2.value)       return false;
     if(form.M_CO_C_FAX_3.value     != form.G_FAX_3.value)       return false;
     if(form.M_CO_C_POST.value      != form.G_POST.value)        return false;
     if(form.M_CO_C_POST_u.value    != form.G_POST_u.value)      return false;
     if(form.M_CO_C_POST_l.value    != form.G_POST_l.value)      return false;
     if(form.M_CO_C_STA.value       != form.G_STA.value)         return false;
     if(form.M_CO_C_ADDRESS.value   != form.G_ADDRESS.value)     return false;
     if(form.M_CO_C_ADDRESS2.value  != form.G_ADDRESS2.value)    return false;
     return true;
    
    case "5":
     if(form.M_CO_G_NAME != undefined){
       if(form.M_CO_G_NAME.value      != "")      return false;
     }
     if(form.M_CO_G_KANA != undefined){
       if(form.M_CO_G_KANA.value      != "")      return false;
     }
     if(form.M_CO_C_NAME.value      != form.P_C_NAME.value)      return false;
     if(form.M_CO_C_KANA.value      != form.P_C_KANA.value)      return false;
   
     if(form.M_CO_C_AFFILIATION != undefined){
       if(form.M_CO_C_AFFILIATION.value != "") return false;
     }
     if(form.M_CO_C_POSITION != undefined){
       if(form.M_CO_C_POSITION.value    != "") return false;
     }
   
     if(form.M_CO_C_EMAIL.value     != form.P_P_EMAIL.value)     return false;
     if(form.M_CO_C_CC_EMAIL != undefined){
       if(form.M_CO_C_CC_EMAIL.value  != form.P_P_CC_EMAIL.value)  return false;
     }
     if(form.M_CO_C_TEL.value       != form.P_P_TEL.value)       return false;
     if(form.M_CO_C_TEL_1.value     != form.P_P_TEL_1.value)     return false;
     if(form.M_CO_C_TEL_2.value     != form.P_P_TEL_2.value)     return false;
     if(form.M_CO_C_TEL_3.value     != form.P_P_TEL_3.value)     return false;
     if(form.M_CO_C_FAX.value       != form.P_P_FAX.value)       return false;
     if(form.M_CO_C_FAX_1.value     != form.P_P_FAX_1.value)     return false;
     if(form.M_CO_C_FAX_2.value     != form.P_P_FAX_2.value)     return false;
     if(form.M_CO_C_FAX_3.value     != form.P_P_FAX_3.value)     return false;
     if(form.M_CO_C_POST.value      != form.P_P_POST.value)      return false;
     if(form.M_CO_C_POST_u.value    != form.P_P_POST_u.value)    return false;
     if(form.M_CO_C_POST_l.value    != form.P_P_POST_l.value)    return false;
     if(form.M_CO_C_STA.value       != form.P_P_STA.value)       return false;
     if(form.M_CO_C_ADDRESS.value   != form.P_P_ADDRESS.value)   return false;
     if(form.M_CO_C_ADDRESS2.value  != form.P_P_ADDRESS2.value)  return false;
     return true;
    default:
     return false;
    }
   }
   
   function IsClaimDestSame(val){
    var form = document.mainForm;
    
    switch (val) {
    
    case "0":
     if(form.M_CL_G_NAME != undefined){
       if(form.M_CL_G_NAME.value     != form.G_NAME.value)       return false;
     }
     if(form.M_CL_G_KANA != undefined){
       if(form.M_CL_G_KANA.value     != form.G_KANA.value)       return false;
     }
     if(form.M_CL_C_NAME.value     != form.P_C_NAME.value)       return false;
     if(form.M_CL_C_KANA.value     != form.P_C_KANA.value)       return false;
   
     if(form.M_CL_C_AFFILIATION != undefined){
       if(form.M_CL_C_AFFILIATION.value != form.S_AFFILIATION_NAME.value)  return false;
     }
     if(form.M_CO_C_POSITION != undefined){
       if(form.M_CL_C_POSITION.value    != form.S_OFFICIAL_POSITION.value) return false;
     }
   
     if(form.M_CL_C_EMAIL.value    != form.P_C_EMAIL.value)      return false;
     if(form.M_CL_C_CC_EMAIL != undefined){
       if(form.M_CL_C_CC_EMAIL.value != form.P_C_CC_EMAIL.value)   return false;
     }
     if(form.M_CL_C_TEL.value      != form.P_C_TEL.value)        return false;
     if(form.M_CL_C_TEL_1.value    != form.P_C_TEL_1.value)      return false;
     if(form.M_CL_C_TEL_2.value    != form.P_C_TEL_2.value)      return false;
     if(form.M_CL_C_TEL_3.value    != form.P_C_TEL_3.value)      return false;
     if(form.M_CL_C_FAX.value      != form.P_C_FAX.value)        return false;
     if(form.M_CL_C_FAX_1.value    != form.P_C_FAX_1.value)      return false;
     if(form.M_CL_C_FAX_2.value    != form.P_C_FAX_2.value)      return false;
     if(form.M_CL_C_FAX_3.value    != form.P_C_FAX_3.value)      return false;
     if(form.M_CL_C_POST.value     != form.P_C_POST.value)       return false;
     if(form.M_CL_C_POST_u.value   != form.P_C_POST_u.value)     return false;
     if(form.M_CL_C_POST_l.value   != form.P_C_POST_l.value)     return false;
     if(form.M_CL_C_STA.value      != form.P_C_STA.value)        return false;
     if(form.M_CL_C_ADDRESS.value  != form.P_C_ADDRESS.value)    return false;
     if(form.M_CL_C_ADDRESS2.value != form.P_C_ADDRESS2.value)   return false;
     return true;
    
    case "2":
     return true;
    
    case "4":
     if(form.M_CL_G_NAME != undefined){
       if(form.M_CL_G_NAME.value      != form.G_NAME.value)        return false;
     }
     if(form.M_CL_G_KANA != undefined){
       if(form.M_CL_G_KANA.value      != form.G_KANA.value)        return false;
     }
     if(form.M_CL_C_NAME.value      != form.P_C_NAME.value)        return false;
     if(form.M_CL_C_KANA.value      != form.P_C_KANA.value)        return false;
   
     if(form.M_CL_C_AFFILIATION != undefined){
       if(form.M_CL_C_AFFILIATION.value != form.S_AFFILIATION_NAME.value)  return false;
     }
     if(form.M_CO_C_POSITION != undefined){
       if(form.M_CL_C_POSITION.value    != form.S_OFFICIAL_POSITION.value) return false;
     }
   
     if(form.M_CL_C_EMAIL.value     != form.G_EMAIL.value)       return false;
     if(form.M_CL_C_CC_EMAIL != undefined){
       if(form.M_CL_C_CC_EMAIL.value  != form.G_CC_EMAIL.value)    return false;
     }
     if(form.M_CL_C_TEL.value       != form.G_TEL.value)         return false;
     if(form.M_CL_C_TEL_1.value     != form.G_TEL_1.value)       return false;
     if(form.M_CL_C_TEL_2.value     != form.G_TEL_2.value)       return false;
     if(form.M_CL_C_TEL_3.value     != form.G_TEL_3.value)       return false;
     if(form.M_CL_C_FAX.value       != form.G_FAX.value)         return false;
     if(form.M_CL_C_FAX_1.value     != form.G_FAX_1.value)       return false;
     if(form.M_CL_C_FAX_2.value     != form.G_FAX_2.value)       return false;
     if(form.M_CL_C_FAX_3.value     != form.G_FAX_3.value)       return false;
     if(form.M_CL_C_POST.value      != form.G_POST.value)        return false;
     if(form.M_CL_C_POST_u.value    != form.G_POST_u.value)      return false;
     if(form.M_CL_C_POST_l.value    != form.G_POST_l.value)      return false;
     if(form.M_CL_C_STA.value       != form.G_STA.value)         return false;
     if(form.M_CL_C_ADDRESS.value   != form.G_ADDRESS.value)     return false;
     if(form.M_CL_C_ADDRESS2.value  != form.G_ADDRESS2.value)    return false;
     return true;
    
    case "5":
     if(form.M_CL_G_NAME != undefined){
       if(form.M_CL_G_NAME.value      != "")      return false;
     }
     if(form.M_CL_G_KANA != undefined){
       if(form.M_CL_G_KANA.value      != "")      return false;
     }
     if(form.M_CL_C_NAME.value      != form.P_C_NAME.value)      return false;
     if(form.M_CL_C_KANA.value      != form.P_C_KANA.value)      return false;
   
     if(form.M_CL_C_AFFILIATION != undefined){
       if(form.M_CL_C_AFFILIATION.value != "")  return false;
     }
     if(form.M_CO_C_POSITION != undefined){
       if(form.M_CL_C_POSITION.value    != "") return false;
     }
   
     if(form.M_CL_C_EMAIL.value     != form.P_P_EMAIL.value)     return false;
     if(form.M_CL_C_CC_EMAIL != undefined){
       if(form.M_CL_C_CC_EMAIL.value  != form.P_P_CC_EMAIL.value)  return false;
     }
     if(form.M_CL_C_TEL.value       != form.P_P_TEL.value)       return false;
     if(form.M_CL_C_TEL_1.value     != form.P_P_TEL_1.value)     return false;
     if(form.M_CL_C_TEL_2.value     != form.P_P_TEL_2.value)     return false;
     if(form.M_CL_C_TEL_3.value     != form.P_P_TEL_3.value)     return false;
     if(form.M_CL_C_FAX.value       != form.P_P_FAX.value)       return false;
     if(form.M_CL_C_FAX_1.value     != form.P_P_FAX_1.value)     return false;
     if(form.M_CL_C_FAX_2.value     != form.P_P_FAX_2.value)     return false;
     if(form.M_CL_C_FAX_3.value     != form.P_P_FAX_3.value)     return false;
     if(form.M_CL_C_POST.value      != form.P_P_POST.value)      return false;
     if(form.M_CL_C_POST_u.value    != form.P_P_POST_u.value)    return false;
     if(form.M_CL_C_POST_l.value    != form.P_P_POST_l.value)    return false;
     if(form.M_CL_C_STA.value       != form.P_P_STA.value)       return false;
     if(form.M_CL_C_ADDRESS.value   != form.P_P_ADDRESS.value)   return false;
     if(form.M_CL_C_ADDRESS2.value  != form.P_P_ADDRESS2.value)  return false;
     return true;
    default:
     return false;
    }
   }
   
   function OnClaimClassChange(val){
    
    CheckNeed(m_mainFeeInfo, false);
    
    if(val==""){
     CheckNeed(m_mainFeeInfo, false);
    
    }else{
     CheckNeed(m_mainFeeInfo, true);
    }
    
    if(val=="0"){
     CheckNeed(m_claimClassNeed, true);
    
    }else{
     CheckNeed(m_claimClassNeed, false);
    }
   }
   
   function OnBankCodeChangeAll(){
    var bankCode; 
    var mrr;      
    var res;      
    var i, j;     
    var buf;      
    var list;     
    var bank, branch;
    var form = document.mainForm;
   
    bankCode = "";
    for (j=1; j<=3; j++) {
     bank = "";
     switch (j) {
      case 1:
       if(form.M_BANK_CODE != undefined && form.M_BRANCH_CODE != undefined){
        bank = form.M_BANK_CODE;
        branch = form.M_BRANCH_CODE;
       }
       break;
      case 2:
       bankCode = bankCode + ",,";
       if(form.P_BANK_CODE != undefined && form.P_BRANCH_CODE != undefined){
        bank = form.P_BANK_CODE;
        branch = form.P_BRANCH_CODE;
       }
       break;
      case 3:
       bankCode = bankCode + ",,";
       if(form.G_BANK_CODE != undefined && form.G_BRANCH_CODE != undefined){
        bank = form.G_BANK_CODE;
        branch = form.G_BRANCH_CODE;
       }
       break;
     }
   
     if (bank == "") {
     } else {
   
      if(bank.type == "hidden") {
      
      } else if(bank.selectedIndex < 0) {
       branch.length           = 0;
       branch.length           = 1;
       branch.options[0].value = "";
       branch.options[0].text  = "";
      } else {
        bankCode = bankCode + bank.options[bank.selectedIndex].value;
        
        if(bank.options[bank.selectedIndex].value==""){
         branch.length           = 0;
         branch.length           = 1;
         branch.options[0].value = "";
         branch.options[0].text  = "";
        }
      }
     }
    }
   
    if(bankCode.replace(',,', '')==""){
     branch.length           = 0;
     branch.length           = 1;
     branch.options[0].value = "";
     branch.options[0].text  = "";
     return;
    }
   
    var ifmGetData = getData;
    var ifmSetUrl;
    ifmSetUrl = "./rs/commonRsSearch.asp?fncName=GetBranchListAll2&bankCode=" + bankCode + "&ArgVal=3";
    ifmGetData.location.href = ifmSetUrl;
   }
   
   
   function OnBankCodeChange(bank, branch, ArgVal){
     var bankCode; 
     var mrr;      
     var res;      
     var i;        
     var buf;      
     var list;     
     if(bank.type == "hidden") return;
     
     if(bank.selectedIndex < 0) return;
     bankCode = bank.options[bank.selectedIndex].value;
     
     if(bankCode==""){
       branch.length           = 0;
       branch.length           = 1;
       branch.options[0].value = "";
       branch.options[0].text  = "";
       return;
     }
     var ifmGetData = getData;
     var ifmSetUrl;
     ifmSetUrl = "./rs/commonRsSearch.asp?fncName=GetBranchList&bankCode=" + bankCode + "&ArgVal=" + ArgVal;
     ifmGetData.location.href = ifmSetUrl;
   }
   
   function retSearchVal_GetBranchListAll2(retSearchVal, ArgVal){
     var i, j;
     var buf;
     var list;
     var bank, branch;
     var sBranchCode;
     var form = document.mainForm;
   
     retSearchVal = retSearchVal.split(',,');
   
     for (j=1; j<=3; j++) {
       switch (j) {
       case 1:
         if (form.S_BRANCH_CODE.value == ''){
           sBranchCode = "";
         }else{
           sBranchCode = form.S_BRANCH_CODE.value;
         }
          bank = form.M_BANK_CODE;
          branch = form.M_BRANCH_CODE;
         break;
   
       case 2:
         if (form.S_BRANCH_CODE2.value == ''){
           sBranchCode = "";
         }else{
           sBranchCode = form.S_BRANCH_CODE2.value;
         }
          bank = form.P_BANK_CODE;
          branch = form.P_BRANCH_CODE;
         break;
   
       case 3:
         if (form.S_BRANCH_CODE3.value == ''){
           sBranchCode = "001";
         }else{
           sBranchCode = form.S_BRANCH_CODE3.value;
         }
          bank = form.G_BANK_CODE;
          branch = form.G_BRANCH_CODE;
         break;
       }
   
       if(bank.type == "hidden") {
       
       } else {
         list = retSearchVal[j-1].split("&");
         branch.length = 0;
         branch.length = list.length;
         if (list[0] != "") branch.length++;
         branch.options[0].value = "";
         branch.options[0].text  = "";
         for (i = 1; i < branch.length; i++) {
           if (list[i - 1] == "") continue;
           buf = list[i - 1].split("=");
           branch.options[i].value = buf[0];
           branch.options[i].text  = buf[0] + " " + buf[1];
   
           if (branch.options[i].value == sBranchCode) {
             branch.selectedIndex = i;
           }
         }
       }
     }
   }
   
   function retSearchVal_GetBranchList(retSearchVal, ArgVal){
     var i;
     var buf;
     var list;
     var bank, branch;
     var form = document.mainForm;
     var sBranchCode;
   
     if (form.S_BRANCH_CODE.value == ''){
       sBranchCode = "";
     }else{
       sBranchCode = form.S_BRANCH_CODE.value;
     }
   
     switch (ArgVal) {
     case "2":
       bank = form.P_BANK_CODE;
       branch = form.P_BRANCH_CODE;
       break;
     case "3":
       bank = form.G_BANK_CODE;
       branch = form.G_BRANCH_CODE;
       break;
     default:
       bank = form.M_BANK_CODE;
       branch = form.M_BRANCH_CODE;
     }
   
     list = retSearchVal.split("&");
     branch.length = 0;
     branch.length = list.length;
     if (list[0] != "") branch.length++;
     branch.options[0].value = "";
     branch.options[0].text  = "";
     for (i = 1; i < branch.length; i++) {
       if (list[i - 1] == "") continue;
       buf = list[i - 1].split("=");
       branch.options[i].value = buf[0];
       branch.options[i].text  = buf[0] + " " + buf[1];
   
       if (branch.options[i].value == sBranchCode) {
         branch.selectedIndex = i;
       }
     }
   
   }
   
   function SetToday(objY, objM, objD){
     var t = new Date();
     var y;
     y = t.getYear()
     if (y < 2000) { y += 1900; }
     objY.value = y;
     objM.value = ("00" + (t.getMonth() + 1)).slice(-2);
     objD.value = ("00" + t.getDate()).slice(-2);
   }
   
   function OnZipCallbackCl(zipcode, sta, addr1, addr2){
       console.log(111111);
     var form = document.mainForm;
     form.M_CL_C_POST_u.value = zipcode.split("-")[0];
     form.M_CL_C_POST_l.value = zipcode.split("-")[1];
     form.M_CL_C_STA.value = sta;
     form.M_CL_C_ADDRESS.value = addr1;
     form.M_CL_C_ADDRESS2.value = addr2;
   }
   
   function OnZipCallbackCo(zipcode, sta, addr1, addr2){
    console.log(2222);
     var form = document.mainForm;
     form.M_CO_C_POST_u.value = zipcode.split("-")[0];
     form.M_CO_C_POST_l.value = zipcode.split("-")[1];
     form.M_CO_C_STA.value = sta;
     form.M_CO_C_ADDRESS.value = addr1;
     form.M_CO_C_ADDRESS2.value = addr2;
   }
   function OnFeeStatus(){
     gToolWnd = window.open('./FeeStatus.asp','DetailWnd',
       'width=950,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
   }
   function ShowImage(url){
     location.href = "./window/ImageView.asp?img=" + url;
   }
   function OnCheck(p_id){
     var ifmGetData6 = getData6;
     var ifmSetUrl6;
     var pmail;
     var form = document.mainForm;
     pmail = form.P_C_PMAIL.value;
     if(pmail != ''){
       if(!confirm("携帯簡易ログインURLを送信します。\nよろしいですか？")){
         return false;
       }
       ifmSetUrl6 = "url_notice.asp?p_id=" + p_id + "&pmail=" + pmail;
       ifmGetData6.location.href = ifmSetUrl6;
       alert('携帯簡易ログインURLを送信しました。');
     }
   }
   
   
   function change_listnodisp(val){
    var form=document.mainForm;
    if(val == "1"){
     if(form.G_O_NAME != undefined){
       form.G_O_NAME.value = "1";
     }
     if(form.G_O_KANA != undefined){
       form.G_O_KANA.value = "1";
     }
    }else{
     if(form.G_O_NAME != undefined){
       form.G_O_NAME.value = "2";
     }
     if(form.G_O_KANA != undefined){
       form.G_O_KANA.value = "2";
     }
    }
   }
   
   
   function RegistFelicaId(p_id){
     location.href = "regist_felicaid.asp?p_id=" + p_id;
   }
   
   function ActivePointDetail(gid,pid){
     gToolWnd = window.open('./ActivePointDetail.asp?gid=' + gid + '&pid=' + pid,'DetailWnd',
       'width=950,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
   }
   
   //組織パスワード表示
   function chgMastGPassword(){
     var form = document.mainForm;
     var tmpVal1, tmpVal2;
     if(form.G_PASSWORD != undefined){
       if(form.G_PASSWORD.type != "hidden"){
         tmpVal1 = form.G_PASSWORD.value;
         tmpVal2 = form.G_PASSWORD2.value;
         if(form.G_PASSWORD.type != "text"){
           $('input[name="G_PASSWORD"]').replaceWith('<input style="ime-mode: disabled; width:120px;" type="text" name="G_PASSWORD" value="' + tmpVal1 + '">');
           $('input[name="G_PASSWORD2"]').replaceWith('<input style="ime-mode: disabled; width:120px;" type="text" name="G_PASSWORD2" value="' + tmpVal2 + '">');
           form.dispGPassBtn.value = "パスワード非表示";
         }else{
           $('input[name="G_PASSWORD"]').replaceWith('<input style="ime-mode: disabled; width:120px;" type="password" name="G_PASSWORD" value="' + tmpVal1 + '">');
           $('input[name="G_PASSWORD2"]').replaceWith('<input style="ime-mode: disabled; width:120px;" type="password" name="G_PASSWORD2" value="' + tmpVal2 + '">');
           form.dispGPassBtn.value = "パスワード表示";
         }
       }
     }
   }
   //個人パスワード表示
   function chgMastPPassword(){
     var form = document.mainForm;
     var tmpVal1, tmpVal2;
     if(form.P_PASSWORD != undefined){
       if(form.P_PASSWORD.type != "hidden"){
         tmpVal1 = form.P_PASSWORD.value;
         tmpVal2 = form.P_PASSWORD2.value;
         if(form.P_PASSWORD.type != "text"){
           $('input[name="P_PASSWORD"]').replaceWith('<input style="ime-mode: disabled; width:120px;" type="text" name="P_PASSWORD" value="' + tmpVal1 + '">');
           $('input[name="P_PASSWORD2"]').replaceWith('<input style="ime-mode: disabled; width:120px;" type="text" name="P_PASSWORD2" value="' + tmpVal2 + '">');
           form.dispPPassBtn.value = "パスワード非表示";
         }else{
           $('input[name="P_PASSWORD"]').replaceWith('<input style="ime-mode: disabled; width:120px;" type="password" name="P_PASSWORD" value="' + tmpVal1 + '">');
           $('input[name="P_PASSWORD2"]').replaceWith('<input style="ime-mode: disabled; width:120px;" type="password" name="P_PASSWORD2" value="' + tmpVal2 + '">');
           form.dispPPassBtn.value = "パスワード表示";
         }
       }
     }
   }
   
   
   function CheckFrameParent(objname) {
     var objKey = document.getElementsByName(objname);
     if (objKey[0].checked == false) {
       for(var i = 1; i < objKey.length; i++) {
         objKey[i].checked = false;
       }
     }
   }
   
   
   function CheckFrameChild(objname) {
     var objKey = document.getElementsByName(objname);
     var flg = false;
     for(var i = 1; i < objKey.length; i++) {
       if (objKey[i].checked) {
         flg = true;
         break;
       }
     }
     objKey[0].checked = flg;
   }
   
   
   
   function changeContactForm(msg){
     var form = document.mainForm;
     if (form.M_CO_C_NAME != undefined) {
       var contactdest     = document.getElementById("M_CONTACTDEST");
   
       switch(contactdest.value) {
         
         case "0":
           
           checkDispIni(form.M_CO_C_NAME, form.P_C_NAME);
           checkDispIni(form.M_CO_C_KANA, form.P_C_KANA);
           checkDispIni(form.M_CO_G_NAME, form.G_NAME);
           checkDispIni(form.M_CO_G_KANA, form.G_KANA);
           checkDispIni(form.M_CO_C_AFFILIATION, form.S_AFFILIATION_NAME);
           checkDispIni(form.M_CO_C_POSITION, form.S_OFFICIAL_POSITION);
           checkDispIni(form.M_CO_C_EMAIL, form.P_C_EMAIL);
           checkDispIni(form.M_CO_C_CC_EMAIL, form.P_C_CC_EMAIL);
           checkDispIni(form.M_CO_C_TEL, form.P_C_TEL);
           checkDispIni(form.M_CO_C_TEL_1, form.P_C_TEL_1);
           checkDispIni(form.M_CO_C_TEL_2, form.P_C_TEL_2);
           checkDispIni(form.M_CO_C_TEL_3, form.P_C_TEL_3);
           checkDispIni(form.M_CO_C_FAX, form.P_C_FAX);
           checkDispIni(form.M_CO_C_FAX_1, form.P_C_FAX_1);
           checkDispIni(form.M_CO_C_FAX_2, form.P_C_FAX_2);
           checkDispIni(form.M_CO_C_FAX_3, form.P_C_FAX_3);
           checkDispIni(form.M_CO_C_POST, form.P_C_POST);
           checkDispIni(form.M_CO_C_POST_u, form.P_C_POST_u);
           checkDispIni(form.M_CO_C_POST_l, form.P_C_POST_l);
           checkDispIni(form.M_CO_C_STA, form.P_C_STA);
           checkDispIni(form.M_CO_C_ADDRESS, form.P_C_ADDRESS);
           checkDispIni(form.M_CO_C_ADDRESS2, form.P_C_ADDRESS2);
   
           checkDispReadOnly(form.M_CO_G_NAME, false);
           checkDispReadOnly(form.M_CO_G_KANA, false);
           checkDispReadOnly(form.M_CO_C_AFFILIATION, false);
           checkDispReadOnly(form.M_CO_C_POSITION, false);
           
           checkDispColor(form.M_CO_G_NAME, "");
           checkDispColor(form.M_CO_G_KANA, "");
           checkDispColor(form.M_CO_C_AFFILIATION, "");
           checkDispColor(form.M_CO_C_POSITION, "");
   
           break;
   
         
         case "4":
           
           checkDispIni(form.M_CO_C_NAME, form.P_C_NAME);
           checkDispIni(form.M_CO_C_KANA, form.P_C_KANA);
           checkDispIni(form.M_CO_G_NAME, form.G_NAME);
           checkDispIni(form.M_CO_G_KANA, form.G_KANA);
           checkDispIni(form.M_CO_C_AFFILIATION, form.S_AFFILIATION_NAME);
           checkDispIni(form.M_CO_C_POSITION, form.S_OFFICIAL_POSITION);
           checkDispIni(form.M_CO_C_EMAIL, form.G_EMAIL);
           checkDispIni(form.M_CO_C_CC_EMAIL, form.G_CC_EMAIL);
           checkDispIni(form.M_CO_C_TEL, form.G_TEL);
           checkDispIni(form.M_CO_C_TEL_1, form.G_TEL_1);
           checkDispIni(form.M_CO_C_TEL_2, form.G_TEL_2);
           checkDispIni(form.M_CO_C_TEL_3, form.G_TEL_3);
           checkDispIni(form.M_CO_C_FAX, form.G_FAX);
           checkDispIni(form.M_CO_C_FAX_1, form.G_FAX_1);
           checkDispIni(form.M_CO_C_FAX_2, form.G_FAX_2);
           checkDispIni(form.M_CO_C_FAX_3, form.G_FAX_3);
           checkDispIni(form.M_CO_C_POST, form.G_POST);
           checkDispIni(form.M_CO_C_POST_u, form.G_POST_u);
           checkDispIni(form.M_CO_C_POST_l, form.G_POST_l);
           checkDispIni(form.M_CO_C_STA, form.G_STA);
           checkDispIni(form.M_CO_C_ADDRESS, form.G_ADDRESS);
           checkDispIni(form.M_CO_C_ADDRESS2, form.G_ADDRESS2);
   
           checkDispReadOnly(form.M_CO_G_NAME, false);
           checkDispReadOnly(form.M_CO_G_KANA, false);
           checkDispReadOnly(form.M_CO_C_AFFILIATION, false);
           checkDispReadOnly(form.M_CO_C_POSITION, false);
           
           checkDispColor(form.M_CO_G_NAME, "");
           checkDispColor(form.M_CO_G_KANA, "");
           checkDispColor(form.M_CO_C_AFFILIATION, "");
           checkDispColor(form.M_CO_C_POSITION, "");
   
           break;
   
         
         case "5":
           
           checkDispIni(form.M_CO_C_NAME, form.P_C_NAME);
           checkDispIni(form.M_CO_C_KANA, form.P_C_KANA);
           checkDisp(form.M_CO_G_NAME, "");
           checkDisp(form.M_CO_G_KANA, "");
           checkDisp(form.M_CO_C_AFFILIATION, "");
           checkDisp(form.M_CO_C_POSITION, "");
           checkDispIni(form.M_CO_C_EMAIL, form.P_P_EMAIL);
           checkDispIni(form.M_CO_C_CC_EMAIL, form.P_P_CC_EMAIL);
           checkDispIni(form.M_CO_C_TEL, form.P_P_TEL);
           checkDispIni(form.M_CO_C_TEL_1, form.P_P_TEL_1);
           checkDispIni(form.M_CO_C_TEL_2, form.P_P_TEL_2);
           checkDispIni(form.M_CO_C_TEL_3, form.P_P_TEL_3);
           checkDispIni(form.M_CO_C_FAX, form.P_P_FAX);
           checkDispIni(form.M_CO_C_FAX_1, form.P_P_FAX_1);
           checkDispIni(form.M_CO_C_FAX_2, form.P_P_FAX_2);
           checkDispIni(form.M_CO_C_FAX_3, form.P_P_FAX_3);
           checkDispIni(form.M_CO_C_POST, form.P_P_POST);
           checkDispIni(form.M_CO_C_POST_u, form.P_P_POST_u);
           checkDispIni(form.M_CO_C_POST_l, form.P_P_POST_l);
           checkDispIni(form.M_CO_C_STA, form.P_P_STA);
           checkDispIni(form.M_CO_C_ADDRESS, form.P_P_ADDRESS);
           checkDispIni(form.M_CO_C_ADDRESS2, form.P_P_ADDRESS2);
   
           checkDispReadOnly(form.M_CO_G_NAME, true);
           checkDispReadOnly(form.M_CO_G_KANA, true);
           checkDispReadOnly(form.M_CO_C_AFFILIATION, true);
           checkDispReadOnly(form.M_CO_C_POSITION, true);
           
           checkDispColor(form.M_CO_G_NAME, "#FFFFCC");
           checkDispColor(form.M_CO_G_KANA, "#FFFFCC");
           checkDispColor(form.M_CO_C_AFFILIATION, "#FFFFCC");
           checkDispColor(form.M_CO_C_POSITION, "#FFFFCC");
   
           break;
   
         
         case "2":
   
         if(form.m_chg.value == "1"){
           
           checkDispBoth(form.M_CO_C_NAME       , form.coNew_M_CO_C_NAME);
           checkDispBoth(form.M_CO_C_KANA       , form.coNew_M_CO_C_KANA);
           checkDispBoth(form.M_CO_G_NAME       , form.coNew_M_CO_G_NAME);
           checkDispBoth(form.M_CO_G_KANA       , form.coNew_M_CO_G_KANA);
           checkDispBoth(form.M_CO_C_AFFILIATION, form.coNew_M_CO_C_AFFILIATION);
           checkDispBoth(form.M_CO_C_POSITION   , form.coNew_M_CO_C_POSITION);
           checkDispBoth(form.M_CO_C_EMAIL      , form.coNew_M_CO_C_EMAIL);
           checkDispBoth(form.M_CO_C_CC_EMAIL   , form.coNew_M_CO_C_CC_EMAIL);
           form.coNew_M_CO_C_TEL.value = form.coNew_M_CO_C_TEL_1.value + "-" + form.coNew_M_CO_C_TEL_2.value + "-" + form.coNew_M_CO_C_TEL_3.value;
           checkDispBoth(form.M_CO_C_TEL        , form.coNew_M_CO_C_TEL);
           checkDispBoth(form.M_CO_C_TEL_1      , form.coNew_M_CO_C_TEL_1);
           checkDispBoth(form.M_CO_C_TEL_2      , form.coNew_M_CO_C_TEL_2);
           checkDispBoth(form.M_CO_C_TEL_3      , form.coNew_M_CO_C_TEL_3);
           form.coNew_M_CO_C_FAX.value = form.coNew_M_CO_C_FAX_1.value + "-" + form.coNew_M_CO_C_FAX_2.value + "-" + form.coNew_M_CO_C_FAX_3.value;
           checkDispBoth(form.M_CO_C_FAX        , form.coNew_M_CO_C_FAX);
           checkDispBoth(form.M_CO_C_FAX_1      , form.coNew_M_CO_C_FAX_1);
           checkDispBoth(form.M_CO_C_FAX_2      , form.coNew_M_CO_C_FAX_2);
           checkDispBoth(form.M_CO_C_FAX_3      , form.coNew_M_CO_C_FAX_3);
           form.coNew_M_CO_C_POST.value = form.coNew_M_CO_C_POST_u.value + "-" + form.coNew_M_CO_C_POST_l.value;
           checkDispBoth(form.M_CO_C_POST       , form.coNew_M_CO_C_POST);
           checkDispBoth(form.M_CO_C_POST_u     , form.coNew_M_CO_C_POST_u);
           checkDispBoth(form.M_CO_C_POST_l     , form.coNew_M_CO_C_POST_l);
           checkDispBoth(form.M_CO_C_STA        , form.coNew_M_CO_C_STA);
           checkDispBoth(form.M_CO_C_ADDRESS    , form.coNew_M_CO_C_ADDRESS);
           checkDispBoth(form.M_CO_C_ADDRESS2   , form.coNew_M_CO_C_ADDRESS2);
         }
   
           checkDispReadOnly(form.M_CO_G_NAME, false);
           checkDispReadOnly(form.M_CO_G_KANA, false);
           checkDispReadOnly(form.M_CO_C_AFFILIATION, false);
           checkDispReadOnly(form.M_CO_C_POSITION, false);
           
           checkDispColor(form.M_CO_G_NAME, "");
           checkDispColor(form.M_CO_G_KANA, "");
           checkDispColor(form.M_CO_C_AFFILIATION, "");
           checkDispColor(form.M_CO_C_POSITION, "");
   
           setContactEnabled();
           break;
       }
     }
   }
   
   function changeClaimForm(msg){
     var form = document.mainForm;
   
     if (form.M_CL_C_NAME != undefined) {
       var claimdest     = document.getElementById("M_CLAIMDEST");
   
       switch(claimdest.value) {
         
         case "0":
           
           checkDispIni(form.M_CL_C_NAME, form.P_C_NAME);
           checkDispIni(form.M_CL_C_KANA, form.P_C_KANA);
           checkDispIni(form.M_CL_G_NAME, form.G_NAME);
           checkDispIni(form.M_CL_G_KANA, form.G_KANA);
           checkDispIni(form.M_CL_C_AFFILIATION, form.S_AFFILIATION_NAME);
           checkDispIni(form.M_CL_C_POSITION, form.S_OFFICIAL_POSITION);
           checkDispIni(form.M_CL_C_EMAIL, form.P_C_EMAIL);
           checkDispIni(form.M_CL_C_CC_EMAIL, form.P_C_CC_EMAIL);
           checkDispIni(form.M_CL_C_TEL, form.P_C_TEL);
           checkDispIni(form.M_CL_C_TEL_1, form.P_C_TEL_1);
           checkDispIni(form.M_CL_C_TEL_2, form.P_C_TEL_2);
           checkDispIni(form.M_CL_C_TEL_3, form.P_C_TEL_3);
           checkDispIni(form.M_CL_C_FAX, form.P_C_FAX);
           checkDispIni(form.M_CL_C_FAX_1, form.P_C_FAX_1);
           checkDispIni(form.M_CL_C_FAX_2, form.P_C_FAX_2);
           checkDispIni(form.M_CL_C_FAX_3, form.P_C_FAX_3);
           checkDispIni(form.M_CL_C_POST, form.P_C_POST);
           checkDispIni(form.M_CL_C_POST_u, form.P_C_POST_u);
           checkDispIni(form.M_CL_C_POST_l, form.P_C_POST_l);
           checkDispIni(form.M_CL_C_STA, form.P_C_STA);
           checkDispIni(form.M_CL_C_ADDRESS, form.P_C_ADDRESS);
           checkDispIni(form.M_CL_C_ADDRESS2, form.P_C_ADDRESS2);
           checkDispIni(form.S_BRANCH_CODE, form.P_BRANCH_CODE);
           checkDispIni(form.M_ACCAUNT_TYPE, form.P_ACCAUNT_TYPE);
           checkDispIni(form.M_ACCAUNT_NUMBER, form.P_ACCAUNT_NUMBER);
           checkDispIni(form.M_ACCAUNT_NAME, form.P_ACCAUNT_NAME);
           checkDispIni(form.M_CUST_NO, form.P_CUST_NO);
           checkDispIni(form.M_SAVINGS_CODE, form.P_SAVINGS_CODE);
           checkDispIni(form.M_SAVINGS_NUMBER, form.P_SAVINGS_NUMBER);
   
           
           checkDispIni(form.M_BANK_CODE, form.P_BANK_CODE);
           OnBankCodeChange(form.P_BANK_CODE, form.M_BRANCH_CODE);
           checkDispIni(form.M_BANK_SET_NAME, form.P_BANK_CODE);
           checkDispIni(form.M_BRANCH_SET_NAME, form.P_BRANCH_CODE);
           checkDispIni(form.M_BRANCH_CODE, form.P_BRANCH_CODE);
   
           checkDispReadOnly(form.M_CL_G_NAME, false);
           checkDispReadOnly(form.M_CL_G_KANA, false);
           checkDispReadOnly(form.M_CL_C_AFFILIATION, false);
           checkDispReadOnly(form.M_CL_C_POSITION, false);
           
           checkDispColor(form.M_CL_G_NAME, "");
           checkDispColor(form.M_CL_G_KANA, "");
           checkDispColor(form.M_CL_C_AFFILIATION, "");
           checkDispColor(form.M_CL_C_POSITION, "");
   
           break;
   
         
         case "4":
           
           checkDispIni(form.M_CL_C_NAME, form.P_C_NAME);
           checkDispIni(form.M_CL_C_KANA, form.P_C_KANA);
           checkDispIni(form.M_CL_G_NAME, form.G_NAME);
           checkDispIni(form.M_CL_G_KANA, form.G_KANA);
           checkDispIni(form.M_CL_C_AFFILIATION, form.S_AFFILIATION_NAME);
           checkDispIni(form.M_CL_C_POSITION, form.S_OFFICIAL_POSITION);
           checkDispIni(form.M_CL_C_EMAIL, form.G_EMAIL);
           checkDispIni(form.M_CL_C_CC_EMAIL, form.G_CC_EMAIL);
           checkDispIni(form.M_CL_C_TEL, form.G_TEL);
           checkDispIni(form.M_CL_C_TEL_1, form.G_TEL_1);
           checkDispIni(form.M_CL_C_TEL_2, form.G_TEL_2);
           checkDispIni(form.M_CL_C_TEL_3, form.G_TEL_3);
           checkDispIni(form.M_CL_C_FAX, form.G_FAX);
           checkDispIni(form.M_CL_C_FAX_1, form.G_FAX_1);
           checkDispIni(form.M_CL_C_FAX_2, form.G_FAX_2);
           checkDispIni(form.M_CL_C_FAX_3, form.G_FAX_3);
           checkDispIni(form.M_CL_C_POST, form.G_POST);
           checkDispIni(form.M_CL_C_POST_u, form.G_POST_u);
           checkDispIni(form.M_CL_C_POST_l, form.G_POST_l);
           checkDispIni(form.M_CL_C_STA, form.G_STA);
           checkDispIni(form.M_CL_C_ADDRESS, form.G_ADDRESS);
           checkDispIni(form.M_CL_C_ADDRESS2, form.G_ADDRESS2);
           checkDispIni(form.S_BRANCH_CODE, form.G_BRANCH_CODE);
           checkDispIni(form.M_ACCAUNT_TYPE, form.G_ACCAUNT_TYPE);
           checkDispIni(form.M_ACCAUNT_NUMBER, form.G_ACCAUNT_NUMBER);
           checkDispIni(form.M_ACCAUNT_NAME, form.G_ACCAUNT_NAME);
           checkDispIni(form.M_CUST_NO, form.G_CUST_NO);
           checkDispIni(form.M_SAVINGS_CODE, form.G_SAVINGS_CODE);
           checkDispIni(form.M_SAVINGS_NUMBER, form.G_SAVINGS_NUMBER);
   
           
           checkDispIni(form.M_BANK_CODE, form.G_BANK_CODE);
           OnBankCodeChange(form.G_BANK_CODE, form.M_BRANCH_CODE);
           checkDispIni(form.M_BANK_SET_NAME, form.G_BANK_CODE);
           checkDispIni(form.M_BRANCH_SET_NAME, form.G_BRANCH_CODE);
           checkDispIni(form.M_BRANCH_CODE, form.G_BRANCH_CODE);
   
           checkDispReadOnly(form.M_CL_G_NAME, false);
           checkDispReadOnly(form.M_CL_G_KANA, false);
           checkDispReadOnly(form.M_CL_C_AFFILIATION, false);
           checkDispReadOnly(form.M_CL_C_POSITION, false);
           
           checkDispColor(form.M_CL_G_NAME, "");
           checkDispColor(form.M_CL_G_KANA, "");
           checkDispColor(form.M_CL_C_AFFILIATION, "");
           checkDispColor(form.M_CL_C_POSITION, "");
   
           break;
   
         
         case "5":
           
           checkDispIni(form.M_CL_C_NAME, form.P_C_NAME);
           checkDispIni(form.M_CL_C_KANA, form.P_C_KANA);
           checkDisp(form.M_CL_G_NAME, "");
           checkDisp(form.M_CL_G_KANA, "");
           checkDisp(form.M_CL_C_AFFILIATION, "");
           checkDisp(form.M_CL_C_POSITION, "");
           checkDispIni(form.M_CL_C_EMAIL, form.P_P_EMAIL);
           checkDispIni(form.M_CL_C_CC_EMAIL, form.P_P_CC_EMAIL);
           checkDispIni(form.M_CL_C_TEL, form.P_P_TEL);
           checkDispIni(form.M_CL_C_TEL_1, form.P_P_TEL_1);
           checkDispIni(form.M_CL_C_TEL_2, form.P_P_TEL_2);
           checkDispIni(form.M_CL_C_TEL_3, form.P_P_TEL_3);
           checkDispIni(form.M_CL_C_FAX, form.P_P_FAX);
           checkDispIni(form.M_CL_C_FAX_1, form.P_P_FAX_1);
           checkDispIni(form.M_CL_C_FAX_2, form.P_P_FAX_2);
           checkDispIni(form.M_CL_C_FAX_3, form.P_P_FAX_3);
           checkDispIni(form.M_CL_C_POST, form.P_P_POST);
           checkDispIni(form.M_CL_C_POST_u, form.P_P_POST_u);
           checkDispIni(form.M_CL_C_POST_l, form.P_P_POST_l);
           checkDispIni(form.M_CL_C_STA, form.P_P_STA);
           checkDispIni(form.M_CL_C_ADDRESS, form.P_P_ADDRESS);
           checkDispIni(form.M_CL_C_ADDRESS2, form.P_P_ADDRESS2);
           checkDispIni(form.S_BRANCH_CODE, form.P_BRANCH_CODE);
           checkDispIni(form.M_ACCAUNT_TYPE, form.P_ACCAUNT_TYPE);
           checkDispIni(form.M_ACCAUNT_NUMBER, form.P_ACCAUNT_NUMBER);
           checkDispIni(form.M_ACCAUNT_NAME, form.P_ACCAUNT_NAME);
           checkDispIni(form.M_CUST_NO, form.P_CUST_NO);
           checkDispIni(form.M_SAVINGS_CODE, form.P_SAVINGS_CODE);
           checkDispIni(form.M_SAVINGS_NUMBER, form.P_SAVINGS_NUMBER);
   
           
           checkDispIni(form.M_BANK_CODE, form.P_BANK_CODE);
           OnBankCodeChange(form.P_BANK_CODE, form.M_BRANCH_CODE);
           checkDispIni(form.M_BANK_SET_NAME, form.P_BANK_CODE);
           checkDispIni(form.M_BRANCH_SET_NAME, form.P_BRANCH_CODE);
           checkDispIni(form.M_BRANCH_CODE, form.P_BRANCH_CODE);
   
           checkDispReadOnly(form.M_CL_G_NAME, true);
           checkDispReadOnly(form.M_CL_G_KANA, true);
           checkDispReadOnly(form.M_CL_C_AFFILIATION, true);
           checkDispReadOnly(form.M_CL_C_POSITION, true);
           
           checkDispColor(form.M_CL_G_NAME, "#FFFFCC");
           checkDispColor(form.M_CL_G_KANA, "#FFFFCC");
           checkDispColor(form.M_CL_C_AFFILIATION, "#FFFFCC");
           checkDispColor(form.M_CL_C_POSITION, "#FFFFCC");
   
           break;
   
         
         case "2":
   
   
         if(form.m_chg.value == "1"){
           
           checkDispBoth(form.M_CL_C_NAME        , form.clNew_M_CL_C_NAME);
           checkDispBoth(form.M_CL_C_KANA        , form.clNew_M_CL_C_KANA);
           checkDispBoth(form.M_CL_G_NAME        , form.clNew_M_CL_G_NAME);
           checkDispBoth(form.M_CL_G_KANA        , form.clNew_M_CL_G_KANA);
           checkDispBoth(form.M_CL_C_AFFILIATION , form.clNew_M_CL_C_AFFILIATION);
           checkDispBoth(form.M_CL_C_POSITION    , form.clNew_M_CL_C_POSITION);
           checkDispBoth(form.M_CL_C_EMAIL       , form.clNew_M_CL_C_EMAIL);
           checkDispBoth(form.M_CL_C_CC_EMAIL    , form.clNew_M_CL_C_CC_EMAIL);
           form.clNew_M_CL_C_TEL.value = form.clNew_M_CL_C_TEL_1.value + "-" + form.clNew_M_CL_C_TEL_2.value + "-" + form.clNew_M_CL_C_TEL_3.value;
           checkDispBoth(form.M_CL_C_TEL         , form.clNew_M_CL_C_TEL);
           checkDispBoth(form.M_CL_C_TEL_1       , form.clNew_M_CL_C_TEL_1);
           checkDispBoth(form.M_CL_C_TEL_2       , form.clNew_M_CL_C_TEL_2);
           checkDispBoth(form.M_CL_C_TEL_3       , form.clNew_M_CL_C_TEL_3);
           form.clNew_M_CL_C_FAX.value = form.clNew_M_CL_C_FAX_1.value + "-" + form.clNew_M_CL_C_FAX_2.value + "-" + form.clNew_M_CL_C_FAX_3.value;
           checkDispBoth(form.M_CL_C_FAX         , form.clNew_M_CL_C_FAX);
           checkDispBoth(form.M_CL_C_FAX_1       , form.clNew_M_CL_C_FAX_1);
           checkDispBoth(form.M_CL_C_FAX_2       , form.clNew_M_CL_C_FAX_2);
           checkDispBoth(form.M_CL_C_FAX_3       , form.clNew_M_CL_C_FAX_3);
           form.clNew_M_CL_C_POST.value = form.clNew_M_CL_C_POST_u.value + "-" + form.clNew_M_CL_C_POST_l.value;
           checkDispBoth(form.M_CL_C_POST        , form.clNew_M_CL_C_POST);
           checkDispBoth(form.M_CL_C_POST_u      , form.clNew_M_CL_C_POST_u);
           checkDispBoth(form.M_CL_C_POST_l      , form.clNew_M_CL_C_POST_l);
           checkDispBoth(form.M_CL_C_STA         , form.clNew_M_CL_C_STA);
           checkDispBoth(form.M_CL_C_ADDRESS     , form.clNew_M_CL_C_ADDRESS);
           checkDispBoth(form.M_CL_C_ADDRESS2    , form.clNew_M_CL_C_ADDRESS2);
           checkDispBoth(form.M_ACCAUNT_TYPE     , form.clNew_M_ACCAUNT_TYPE);
           checkDispBoth(form.M_ACCAUNT_NUMBER   , form.clNew_M_ACCAUNT_NUMBER);
           checkDispBoth(form.M_ACCAUNT_NAME     , form.clNew_M_ACCAUNT_NAME);
           checkDispBoth(form.M_CUST_NO          , form.clNew_M_CUST_NO);
           checkDispBoth(form.M_SAVINGS_CODE     , form.clNew_M_SAVINGS_CODE);
           checkDispBoth(form.M_SAVINGS_NUMBER   , form.clNew_M_SAVINGS_NUMBER);
           checkDispBoth(form.M_BANK_CODE        , form.clNew_M_BANK_CODE);
           checkDispBoth(form.S_BRANCH_CODE      , form.clNew_S_BRANCH_CODE);
           OnBankCodeChange(form.M_BANK_CODE, form.M_BRANCH_CODE);
           checkDispBoth(form.M_BRANCH_CODE, form.clNew_M_BRANCH_CODE);
         }
   
           checkDispReadOnly(form.M_CL_G_NAME, false);
           checkDispReadOnly(form.M_CL_G_KANA, false);
           checkDispReadOnly(form.M_CL_C_AFFILIATION, false);
           checkDispReadOnly(form.M_CL_C_POSITION, false);
           
           checkDispColor(form.M_CL_G_NAME, "");
           checkDispColor(form.M_CL_G_KANA, "");
           checkDispColor(form.M_CL_C_AFFILIATION, "");
           checkDispColor(form.M_CL_C_POSITION, "");
   
   
           setBillingEnabled();
           break;
       }
     }
   }
   
   
   function changeContactFormValue(){
     var form = document.mainForm;
     var contactdest     = document.getElementById("M_CONTACTDEST");
   
     switch(contactdest.value) {
       
       case "0":
         checkDispBoth(form.P_C_NAME, form.M_CO_C_NAME);
         checkDispBoth(form.P_C_KANA, form.M_CO_C_KANA);
         checkDispBoth(form.G_NAME, form.M_CO_G_NAME);
         checkDispBoth(form.G_KANA, form.M_CO_G_KANA);
         checkDispBoth(form.S_AFFILIATION_NAME, form.M_CO_C_AFFILIATION);
         checkDispBoth(form.S_OFFICIAL_POSITION, form.M_CO_C_POSITION);
         checkDispBoth(form.P_C_EMAIL, form.M_CO_C_EMAIL);
         checkDispBoth(form.P_C_CC_EMAIL, form.M_CO_C_CC_EMAIL);
         form.M_CO_C_TEL.value = form.M_CO_C_TEL_1.value + "-" + form.M_CO_C_TEL_2.value + "-" + form.M_CO_C_TEL_3.value;
         checkDispBoth(form.P_C_TEL, form.M_CO_C_TEL);
         checkDispBoth(form.P_C_TEL_1, form.M_CO_C_TEL_1);
         checkDispBoth(form.P_C_TEL_2, form.M_CO_C_TEL_2);
         checkDispBoth(form.P_C_TEL_3, form.M_CO_C_TEL_3);
         form.M_CO_C_FAX.value = form.M_CO_C_FAX_1.value + "-" + form.M_CO_C_FAX_2.value + "-" + form.M_CO_C_FAX_3.value;
         checkDispBoth(form.P_C_FAX, form.M_CO_C_FAX);
         checkDispBoth(form.P_C_FAX_1, form.M_CO_C_FAX_1);
         checkDispBoth(form.P_C_FAX_2, form.M_CO_C_FAX_2);
         checkDispBoth(form.P_C_FAX_3, form.M_CO_C_FAX_3);
         form.M_CO_C_POST.value = form.M_CO_C_POST_u.value + "-" + form.M_CO_C_POST_l.value;
         checkDispBoth(form.P_C_POST, form.M_CO_C_POST);
         checkDispBoth(form.P_C_POST_u, form.M_CO_C_POST_u);
         checkDispBoth(form.P_C_POST_l, form.M_CO_C_POST_l);
         checkDispBoth(form.P_C_STA, form.M_CO_C_STA);
         checkDispBoth(form.P_C_ADDRESS, form.M_CO_C_ADDRESS);
         checkDispBoth(form.P_C_ADDRESS2, form.M_CO_C_ADDRESS2);
         break;
   
       
       case "4":
         checkDispBoth(form.P_C_NAME, form.M_CO_C_NAME);
         checkDispBoth(form.P_C_KANA, form.M_CO_C_KANA);
         checkDispBoth(form.G_NAME, form.M_CO_G_NAME);
         checkDispBoth(form.G_KANA, form.M_CO_G_KANA);
         checkDispBoth(form.S_AFFILIATION_NAME, form.M_CO_C_AFFILIATION);
         checkDispBoth(form.S_OFFICIAL_POSITION, form.M_CO_C_POSITION);
         checkDispBoth(form.G_EMAIL, form.M_CO_C_EMAIL);
         checkDispBoth(form.G_CC_EMAIL, form.M_CO_C_CC_EMAIL);
         form.M_CO_C_TEL.value = form.M_CO_C_TEL_1.value + "-" + form.M_CO_C_TEL_2.value + "-" + form.M_CO_C_TEL_3.value;
         checkDispBoth(form.G_TEL, form.M_CO_C_TEL);
         checkDispBoth(form.G_TEL_1, form.M_CO_C_TEL_1);
         checkDispBoth(form.G_TEL_2, form.M_CO_C_TEL_2);
         checkDispBoth(form.G_TEL_3, form.M_CO_C_TEL_3);
         form.M_CO_C_FAX.value = form.M_CO_C_FAX_1.value + "-" + form.M_CO_C_FAX_2.value + "-" + form.M_CO_C_FAX_3.value;
         checkDispBoth(form.G_FAX, form.M_CO_C_FAX);
         checkDispBoth(form.G_FAX_1, form.M_CO_C_FAX_1);
         checkDispBoth(form.G_FAX_2, form.M_CO_C_FAX_2);
         checkDispBoth(form.G_FAX_3, form.M_CO_C_FAX_3);
         form.M_CO_C_POST.value = form.M_CO_C_POST_u.value + "-" + form.M_CO_C_POST_l.value;
         checkDispBoth(form.G_POST, form.M_CO_C_POST);
         checkDispBoth(form.G_POST_u, form.M_CO_C_POST_u);
         checkDispBoth(form.G_POST_l, form.M_CO_C_POST_l);
         checkDispBoth(form.G_STA, form.M_CO_C_STA);
         checkDispBoth(form.G_ADDRESS, form.M_CO_C_ADDRESS);
         checkDispBoth(form.G_ADDRESS2, form.M_CO_C_ADDRESS2);
         break;
   
       
       case "5":
         checkDispBoth(form.P_C_NAME, form.M_CO_C_NAME);
         checkDispBoth(form.P_C_KANA, form.M_CO_C_KANA);
         checkDispBoth(form.P_P_EMAIL, form.M_CO_C_EMAIL);
         checkDispBoth(form.P_P_CC_EMAIL, form.M_CO_C_CC_EMAIL);
         form.M_CO_C_TEL.value = form.M_CO_C_TEL_1.value + "-" + form.M_CO_C_TEL_2.value + "-" + form.M_CO_C_TEL_3.value;
         checkDispBoth(form.P_P_TEL, form.M_CO_C_TEL);
         checkDispBoth(form.P_P_TEL_1, form.M_CO_C_TEL_1);
         checkDispBoth(form.P_P_TEL_2, form.M_CO_C_TEL_2);
         checkDispBoth(form.P_P_TEL_3, form.M_CO_C_TEL_3);
         form.M_CO_C_FAX.value = form.M_CO_C_FAX_1.value + "-" + form.M_CO_C_FAX_2.value + "-" + form.M_CO_C_FAX_3.value;
         checkDispBoth(form.P_P_FAX, form.M_CO_C_FAX);
         checkDispBoth(form.P_P_FAX_1, form.M_CO_C_FAX_1);
         checkDispBoth(form.P_P_FAX_2, form.M_CO_C_FAX_2);
         checkDispBoth(form.P_P_FAX_3, form.M_CO_C_FAX_3);
         form.M_CO_C_POST.value = form.M_CO_C_POST_u.value + "-" + form.M_CO_C_POST_l.value;
         checkDispBoth(form.P_P_POST, form.M_CO_C_POST);
         checkDispBoth(form.P_P_POST_u, form.M_CO_C_POST_u);
         checkDispBoth(form.P_P_POST_l, form.M_CO_C_POST_l);
         checkDispBoth(form.P_P_STA, form.M_CO_C_STA);
         checkDispBoth(form.P_P_ADDRESS, form.M_CO_C_ADDRESS);
         checkDispBoth(form.P_P_ADDRESS2, form.M_CO_C_ADDRESS2);
         break;
   
       
       case "2":
         break;
     }
   
     if(contactdest.value=="2"){
       
       checkDispBoth(form.coNew_M_CO_C_NAME       , form.M_CO_C_NAME);
       checkDispBoth(form.coNew_M_CO_C_KANA       , form.M_CO_C_KANA);
       checkDispBoth(form.coNew_M_CO_G_NAME       , form.M_CO_G_NAME);
       checkDispBoth(form.coNew_M_CO_G_KANA       , form.M_CO_G_KANA);
       checkDispBoth(form.coNew_M_CO_C_AFFILIATION, form.M_CO_C_AFFILIATION);
       checkDispBoth(form.coNew_M_CO_C_POSITION   , form.M_CO_C_POSITION);
       checkDispBoth(form.coNew_M_CO_C_EMAIL      , form.M_CO_C_EMAIL);
       checkDispBoth(form.coNew_M_CO_C_CC_EMAIL   , form.M_CO_C_CC_EMAIL);
       form.M_CO_C_TEL.value = form.M_CO_C_TEL_1.value + "-" + form.M_CO_C_TEL_2.value + "-" + form.M_CO_C_TEL_3.value;
       checkDispBoth(form.coNew_M_CO_C_TEL        , form.M_CO_C_TEL);
       checkDispBoth(form.coNew_M_CO_C_TEL_1      , form.M_CO_C_TEL_1);
       checkDispBoth(form.coNew_M_CO_C_TEL_2      , form.M_CO_C_TEL_2);
       checkDispBoth(form.coNew_M_CO_C_TEL_3      , form.M_CO_C_TEL_3);
       form.M_CO_C_FAX.value = form.M_CO_C_FAX_1.value + "-" + form.M_CO_C_FAX_2.value + "-" + form.M_CO_C_FAX_3.value;
       checkDispBoth(form.coNew_M_CO_C_FAX        , form.M_CO_C_FAX);
       checkDispBoth(form.coNew_M_CO_C_FAX_1      , form.M_CO_C_FAX_1);
       checkDispBoth(form.coNew_M_CO_C_FAX_2      , form.M_CO_C_FAX_2);
       checkDispBoth(form.coNew_M_CO_C_FAX_3      , form.M_CO_C_FAX_3);
         form.M_CO_C_POST.value = form.M_CO_C_POST_u.value + "-" + form.M_CO_C_POST_l.value;
       checkDispBoth(form.coNew_M_CO_C_POST       , form.M_CO_C_POST);
       checkDispBoth(form.coNew_M_CO_C_POST_u     , form.M_CO_C_POST_u);
       checkDispBoth(form.coNew_M_CO_C_POST_l     , form.M_CO_C_POST_l);
       checkDispBoth(form.coNew_M_CO_C_STA        , form.M_CO_C_STA);
       checkDispBoth(form.coNew_M_CO_C_ADDRESS    , form.M_CO_C_ADDRESS);
       checkDispBoth(form.coNew_M_CO_C_ADDRESS2   , form.M_CO_C_ADDRESS2);
     }
   
   }
   
   
   function changeClaimFormValue(){
     var form = document.mainForm;
     var claimdest     = document.getElementById("M_CLAIMDEST");
     var contactdest     = document.getElementById("M_CONTACTDEST");
   
     switch (claimdest.value) {
       
       case "0":
         checkDispBoth(form.P_C_NAME, form.M_CL_C_NAME);
         checkDispBoth(form.P_C_KANA, form.M_CL_C_KANA);
         checkDispBoth(form.G_NAME, form.M_CL_G_NAME);
         checkDispBoth(form.G_KANA, form.M_CL_G_KANA);
         checkDispBoth(form.S_AFFILIATION_NAME, form.M_CL_C_AFFILIATION);
         checkDispBoth(form.S_OFFICIAL_POSITION, form.M_CL_C_POSITION);
         checkDispBoth(form.P_C_EMAIL, form.M_CL_C_EMAIL);
         checkDispBoth(form.P_C_CC_EMAIL, form.M_CL_C_CC_EMAIL);
         form.M_CL_C_TEL.value = form.M_CL_C_TEL_1.value + "-" + form.M_CL_C_TEL_2.value + "-" + form.M_CL_C_TEL_3.value;
         checkDispBoth(form.P_C_TEL, form.M_CL_C_TEL);
         checkDispBoth(form.P_C_TEL_1, form.M_CL_C_TEL_1);
         checkDispBoth(form.P_C_TEL_2, form.M_CL_C_TEL_2);
         checkDispBoth(form.P_C_TEL_3, form.M_CL_C_TEL_3);
         form.M_CL_C_FAX.value = form.M_CL_C_FAX_1.value + "-" + form.M_CL_C_FAX_2.value + "-" + form.M_CL_C_FAX_3.value;
         checkDispBoth(form.P_C_FAX, form.M_CL_C_FAX);
         checkDispBoth(form.P_C_FAX_1, form.M_CL_C_FAX_1);
         checkDispBoth(form.P_C_FAX_2, form.M_CL_C_FAX_2);
         checkDispBoth(form.P_C_FAX_3, form.M_CL_C_FAX_3);
         form.M_CL_C_POST.value = form.M_CL_C_POST_u.value + "-" + form.M_CL_C_POST_l.value;
         checkDispBoth(form.P_C_POST, form.M_CL_C_POST);
         checkDispBoth(form.P_C_POST_u, form.M_CL_C_POST_u);
         checkDispBoth(form.P_C_POST_l, form.M_CL_C_POST_l);
         checkDispBoth(form.P_C_STA, form.M_CL_C_STA);
         checkDispBoth(form.P_C_ADDRESS, form.M_CL_C_ADDRESS);
         checkDispBoth(form.P_C_ADDRESS2, form.M_CL_C_ADDRESS2);
         checkDispBoth(form.P_BRANCH_CODE, form.M_BRANCH_CODE);
         checkDispBoth(form.P_BANK_CODE, form.M_BANK_CODE);
         checkDispBoth(form.P_ACCAUNT_TYPE, form.M_ACCAUNT_TYPE);
         checkDispBoth(form.P_ACCAUNT_NUMBER, form.M_ACCAUNT_NUMBER);
         checkDispBoth(form.P_ACCAUNT_NAME, form.M_ACCAUNT_NAME);
         checkDispBoth(form.P_CUST_NO, form.M_CUST_NO);
         checkDispBoth(form.P_SAVINGS_CODE, form.M_SAVINGS_CODE);
         checkDispBoth(form.P_SAVINGS_NUMBER, form.M_SAVINGS_NUMBER);
   
         
         checkDispIni(form.P_BANK_CODE, form.M_BANK_CODE);
         OnBankCodeChange(form.M_BANK_CODE, form.P_BRANCH_CODE, '2');
         checkDispIni(form.P_BANK_SET_NAME, form.M_BANK_CODE);
         checkDispIni(form.P_BRANCH_SET_NAME, form.M_BRANCH_CODE);
         setTimeout("checkDispIni(document.mainForm.P_BRANCH_CODE, document.mainForm.M_BRANCH_CODE)", 1000);
         break;
   
       
       case "4":
         checkDispBoth(form.P_C_NAME, form.M_CL_C_NAME);
         checkDispBoth(form.P_C_KANA, form.M_CL_C_KANA);
         checkDispBoth(form.G_NAME, form.M_CL_G_NAME);
         checkDispBoth(form.G_KANA, form.M_CL_G_KANA);
         checkDispBoth(form.S_AFFILIATION_NAME, form.M_CL_C_AFFILIATION);
         checkDispBoth(form.S_OFFICIAL_POSITION, form.M_CL_C_POSITION);
         checkDispBoth(form.G_EMAIL, form.M_CL_C_EMAIL);
         checkDispBoth(form.G_CC_EMAIL, form.M_CL_C_CC_EMAIL);
         form.M_CL_C_TEL.value = form.M_CL_C_TEL_1.value + "-" + form.M_CL_C_TEL_2.value + "-" + form.M_CL_C_TEL_3.value;
         checkDispBoth(form.G_TEL, form.M_CL_C_TEL);
         checkDispBoth(form.G_TEL_1, form.M_CL_C_TEL_1);
         checkDispBoth(form.G_TEL_2, form.M_CL_C_TEL_2);
         checkDispBoth(form.G_TEL_3, form.M_CL_C_TEL_3);
         form.M_CL_C_FAX.value = form.M_CL_C_FAX_1.value + "-" + form.M_CL_C_FAX_2.value + "-" + form.M_CL_C_FAX_3.value;
         checkDispBoth(form.G_FAX, form.M_CL_C_FAX);
         checkDispBoth(form.G_FAX_1, form.M_CL_C_FAX_1);
         checkDispBoth(form.G_FAX_2, form.M_CL_C_FAX_2);
         checkDispBoth(form.G_FAX_3, form.M_CL_C_FAX_3);
         form.M_CL_C_POST.value = form.M_CL_C_POST_u.value + "-" + form.M_CL_C_POST_l.value;
         checkDispBoth(form.G_POST, form.M_CL_C_POST);
         checkDispBoth(form.G_POST_u, form.M_CL_C_POST_u);
         checkDispBoth(form.G_POST_l, form.M_CL_C_POST_l);
         checkDispBoth(form.G_STA, form.M_CL_C_STA);
         checkDispBoth(form.G_ADDRESS, form.M_CL_C_ADDRESS);
         checkDispBoth(form.G_ADDRESS2, form.M_CL_C_ADDRESS2);
         checkDispBoth(form.G_ACCAUNT_TYPE, form.M_ACCAUNT_TYPE);
         checkDispBoth(form.G_ACCAUNT_NUMBER, form.M_ACCAUNT_NUMBER);
         checkDispBoth(form.G_ACCAUNT_NAME, form.M_ACCAUNT_NAME);
         checkDispBoth(form.G_CUST_NO, form.M_CUST_NO);
         checkDispBoth(form.G_SAVINGS_CODE, form.M_SAVINGS_CODE);
         checkDispBoth(form.G_SAVINGS_NUMBER, form.M_SAVINGS_NUMBER);
   
         
         checkDispIni(form.G_BANK_CODE, form.M_BANK_CODE);
         OnBankCodeChange(form.M_BANK_CODE, form.G_BRANCH_CODE, '3');
         checkDispIni(form.G_BANK_SET_NAME, form.M_BANK_CODE);
         checkDispIni(form.G_BRANCH_SET_NAME, form.M_BRANCH_CODE);
         setTimeout("checkDispIni(document.mainForm.G_BRANCH_CODE, document.mainForm.M_BRANCH_CODE)", 1000);
         break;
   
       
       case "5":
         checkDispBoth(form.P_C_NAME, form.M_CL_C_NAME);
         checkDispBoth(form.P_C_KANA, form.M_CL_C_KANA);
         checkDispBoth(form.P_P_EMAIL, form.M_CL_C_EMAIL);
         checkDispBoth(form.P_P_CC_EMAIL, form.M_CL_C_CC_EMAIL);
         form.M_CL_C_TEL.value = form.M_CL_C_TEL_1.value + "-" + form.M_CL_C_TEL_2.value + "-" + form.M_CL_C_TEL_3.value;
         checkDispBoth(form.P_P_TEL, form.M_CL_C_TEL);
         checkDispBoth(form.P_P_TEL_1, form.M_CL_C_TEL_1);
         checkDispBoth(form.P_P_TEL_2, form.M_CL_C_TEL_2);
         checkDispBoth(form.P_P_TEL_3, form.M_CL_C_TEL_3);
         form.M_CL_C_FAX.value = form.M_CL_C_FAX_1.value + "-" + form.M_CL_C_FAX_2.value + "-" + form.M_CL_C_FAX_3.value;
         checkDispBoth(form.P_P_FAX, form.M_CL_C_FAX);
         checkDispBoth(form.P_P_FAX_1, form.M_CL_C_FAX_1);
         checkDispBoth(form.P_P_FAX_2, form.M_CL_C_FAX_2);
         checkDispBoth(form.P_P_FAX_3, form.M_CL_C_FAX_3);
         form.M_CL_C_POST.value = form.M_CL_C_POST_u.value + "-" + form.M_CL_C_POST_l.value;
         checkDispBoth(form.P_P_POST, form.M_CL_C_POST);
         checkDispBoth(form.P_P_POST_u, form.M_CL_C_POST_u);
         checkDispBoth(form.P_P_POST_l, form.M_CL_C_POST_l);
         checkDispBoth(form.P_P_STA, form.M_CL_C_STA);
         checkDispBoth(form.P_P_ADDRESS, form.M_CL_C_ADDRESS);
         checkDispBoth(form.P_P_ADDRESS2, form.M_CL_C_ADDRESS2);
         checkDispBoth(form.P_ACCAUNT_TYPE, form.M_ACCAUNT_TYPE);
         checkDispBoth(form.P_ACCAUNT_NUMBER, form.M_ACCAUNT_NUMBER);
         checkDispBoth(form.P_ACCAUNT_NAME, form.M_ACCAUNT_NAME);
         checkDispBoth(form.P_CUST_NO, form.M_CUST_NO);
         checkDispBoth(form.P_SAVINGS_CODE, form.M_SAVINGS_CODE);
         checkDispBoth(form.P_SAVINGS_NUMBER, form.M_SAVINGS_NUMBER);
   
         
         checkDispIni(form.P_BANK_CODE, form.M_BANK_CODE);
         OnBankCodeChange(form.M_BANK_CODE, form.P_BRANCH_CODE, '2');
         checkDispIni(form.P_BANK_SET_NAME, form.M_BANK_CODE);
         checkDispIni(form.P_BRANCH_SET_NAME, form.M_BRANCH_CODE);
         setTimeout("checkDispIni(document.mainForm.P_BRANCH_CODE, document.mainForm.M_BRANCH_CODE)", 1000);
         break;
   
       
       case "2":
         break;
       
     }
   
     if(claimdest.value=="2"){
       
       checkDispBoth(form.clNew_M_CL_C_NAME        , form.M_CL_C_NAME);
       checkDispBoth(form.clNew_M_CL_C_KANA        , form.M_CL_C_KANA);
       checkDispBoth(form.clNew_M_CL_G_NAME        , form.M_CL_G_NAME);
       checkDispBoth(form.clNew_M_CL_G_KANA        , form.M_CL_G_KANA);
       checkDispBoth(form.clNew_M_CL_C_AFFILIATION , form.M_CL_C_AFFILIATION);
       checkDispBoth(form.clNew_M_CL_C_POSITION    , form.M_CL_C_POSITION);
       checkDispBoth(form.clNew_M_CL_C_EMAIL       , form.M_CL_C_EMAIL);
       checkDispBoth(form.clNew_M_CL_C_CC_EMAIL    , form.M_CL_C_CC_EMAIL);
       form.M_CL_C_TEL.value = form.M_CL_C_TEL_1.value + "-" + form.M_CL_C_TEL_2.value + "-" + form.M_CL_C_TEL_3.value;
       checkDispBoth(form.clNew_M_CL_C_TEL         , form.M_CL_C_TEL);
       checkDispBoth(form.clNew_M_CL_C_TEL_1       , form.M_CL_C_TEL_1);
       checkDispBoth(form.clNew_M_CL_C_TEL_2       , form.M_CL_C_TEL_2);
       checkDispBoth(form.clNew_M_CL_C_TEL_3       , form.M_CL_C_TEL_3);
       form.M_CL_C_FAX.value = form.M_CL_C_FAX_1.value + "-" + form.M_CL_C_FAX_2.value + "-" + form.M_CL_C_FAX_3.value;
       checkDispBoth(form.clNew_M_CL_C_FAX         , form.M_CL_C_FAX);
       checkDispBoth(form.clNew_M_CL_C_FAX_1       , form.M_CL_C_FAX_1);
       checkDispBoth(form.clNew_M_CL_C_FAX_2       , form.M_CL_C_FAX_2);
       checkDispBoth(form.clNew_M_CL_C_FAX_3       , form.M_CL_C_FAX_3);
       form.M_CL_C_POST.value = form.M_CL_C_POST_u.value + "-" + form.M_CL_C_POST_l.value;
       checkDispBoth(form.clNew_M_CL_C_POST        , form.M_CL_C_POST);
       checkDispBoth(form.clNew_M_CL_C_POST_u      , form.M_CL_C_POST_u);
       checkDispBoth(form.clNew_M_CL_C_POST_l      , form.M_CL_C_POST_l);
       checkDispBoth(form.clNew_M_CL_C_STA         , form.M_CL_C_STA);
       checkDispBoth(form.clNew_M_CL_C_ADDRESS     , form.M_CL_C_ADDRESS);
       checkDispBoth(form.clNew_M_CL_C_ADDRESS2    , form.M_CL_C_ADDRESS2);
       checkDispBoth(form.clNew_M_ACCAUNT_TYPE     , form.M_ACCAUNT_TYPE);
       checkDispBoth(form.clNew_M_ACCAUNT_NUMBER   , form.M_ACCAUNT_NUMBER);
       checkDispBoth(form.clNew_M_ACCAUNT_NAME     , form.M_ACCAUNT_NAME);
       checkDispBoth(form.clNew_M_CUST_NO          , form.M_CUST_NO);
       checkDispBoth(form.clNew_M_SAVINGS_CODE     , form.M_SAVINGS_CODE);
       checkDispBoth(form.clNew_M_SAVINGS_NUMBER   , form.M_SAVINGS_NUMBER);
       checkDispBoth(form.clNew_M_BANK_CODE        , form.M_BANK_CODE);
       checkDispBoth(form.clNew_S_BRANCH_CODE      , form.M_BRANCH_CODE);
       checkDispBoth(form.clNew_M_BRANCH_CODE      , form.M_BRANCH_CODE);
     }
   
   }
   
   
   function changeContactFormDisp(){
     var form = document.mainForm;
     var claimdest     = document.getElementById("M_CLAIMDEST");
     var contactdest     = document.getElementById("M_CONTACTDEST");
   
   
   
     if ((contactdest.value == 5) && (claimdest.value==5)) {
       
       checkDispReadOnly(form.M_CL_G_NAME, true);
       checkDispReadOnly(form.M_CL_G_KANA, true);
       checkDispReadOnly(form.M_CL_C_AFFILIATION, true);
       checkDispReadOnly(form.M_CL_C_POSITION, true);
       
       checkDispColor(form.M_CL_G_NAME, "#FFFFCC");
       checkDispColor(form.M_CL_G_KANA, "#FFFFCC");
       checkDispColor(form.M_CL_C_AFFILIATION, "#FFFFCC");
       checkDispColor(form.M_CL_C_POSITION, "#FFFFCC");
     } else {
       checkDispReadOnly(form.M_CL_G_NAME, false);
       checkDispReadOnly(form.M_CL_G_KANA, false);
       checkDispReadOnly(form.M_CL_C_AFFILIATION, false);
       checkDispReadOnly(form.M_CL_C_POSITION, false);
       
       checkDispColor(form.M_CL_G_NAME, "");
       checkDispColor(form.M_CL_G_KANA, "");
       checkDispColor(form.M_CL_C_AFFILIATION, "");
       checkDispColor(form.M_CL_C_POSITION, "");
     }
   
   
   }
   
   
   function checkDisp(form1, form2){
     if (form1 != undefined) {
       form1.value = form2;
     }
   }
   
   function checkDispBoth(form1, form2){
     if (form1 != undefined && form2 != undefined) {
       form1.value = form2.value;
     }
   }
   
   function checkDispIni(form1, form2){
     if (form1 != undefined && form2 != undefined) {
       if (form2.type != "hidden" && form2.readOnly != true) {
         form1.value = form2.value;
   
         checkDispReadOnly(form1, false);
         checkDispColor(form1, "")
   
       } else {
         form1.value = form2.value;
   
         checkDispReadOnly(form1, true);
         checkDispColor(form1, "#FFFFCC")
   
       }
     }
   }
   
   function checkDispReadOnly(form1, flg){
     if (form1 != undefined) {
       form1.readOnly = flg;
     }
   }
   
   function checkDispColor(form1, color){
     if (form1 != undefined) {
       form1.style.backgroundColor = color;
     }
   }
   
   function checkDispDisabled(form1, value){
     if (form1 != undefined) {
       form1.disabled = value;
     }
   }
   
   function setContactDisabled(){
     var form = document.mainForm;
   
     checkDispReadOnly(form.M_CO_G_NAME        , true);
     checkDispReadOnly(form.M_CO_G_KANA        , true);
     checkDispReadOnly(form.M_CO_C_NAME        , true);
     checkDispReadOnly(form.M_CO_C_KANA        , true);
     checkDispReadOnly(form.M_CO_C_EMAIL       , true);
     checkDispReadOnly(form.M_CO_C_CC_EMAIL    , true);
     checkDispReadOnly(form.M_CO_C_TEL         , true);
     checkDispReadOnly(form.M_CO_C_TEL_1       , true);
     checkDispReadOnly(form.M_CO_C_TEL_2       , true);
     checkDispReadOnly(form.M_CO_C_TEL_3       , true);
     checkDispReadOnly(form.M_CO_C_FAX         , true);
     checkDispReadOnly(form.M_CO_C_FAX_1       , true);
     checkDispReadOnly(form.M_CO_C_FAX_2       , true);
     checkDispReadOnly(form.M_CO_C_FAX_3       , true);
     checkDispReadOnly(form.M_CO_C_POST        , true);
     checkDispReadOnly(form.M_CO_C_POST_u      , true);
     checkDispReadOnly(form.M_CO_C_POST_l      , true);
     checkDispReadOnly(form.M_CO_C_STA         , true);
     checkDispReadOnly(form.M_CO_C_ADDRESS     , true);
     checkDispReadOnly(form.M_CO_C_ADDRESS2    , true);
     checkDispReadOnly(form.M_CO_C_AFFILIATION , true);
     checkDispReadOnly(form.M_CO_C_POSITION    , true);
   
     checkDispColor(form.M_CO_G_NAME         , "#FFFFCC");
     checkDispColor(form.M_CO_G_KANA         , "#FFFFCC");
     checkDispColor(form.M_CO_C_NAME         , "#FFFFCC");
     checkDispColor(form.M_CO_C_KANA         , "#FFFFCC");
     checkDispColor(form.M_CO_C_EMAIL        , "#FFFFCC");
     checkDispColor(form.M_CO_C_CC_EMAIL     , "#FFFFCC");
     checkDispColor(form.M_CO_C_TEL_1        , "#FFFFCC");
     checkDispColor(form.M_CO_C_TEL_2        , "#FFFFCC");
     checkDispColor(form.M_CO_C_TEL_3        , "#FFFFCC");
     checkDispColor(form.M_CO_C_FAX_1        , "#FFFFCC");
     checkDispColor(form.M_CO_C_FAX_2        , "#FFFFCC");
     checkDispColor(form.M_CO_C_FAX_3        , "#FFFFCC");
     checkDispColor(form.M_CO_C_POST_u       , "#FFFFCC");
     checkDispColor(form.M_CO_C_POST_l       , "#FFFFCC");
     checkDispColor(form.M_CO_C_STA          , "#FFFFCC");
     checkDispColor(form.M_CO_C_ADDRESS      , "#FFFFCC");
     checkDispColor(form.M_CO_C_ADDRESS2     , "#FFFFCC");
     checkDispColor(form.M_CO_C_AFFILIATION  , "#FFFFCC");
     checkDispColor(form.M_CO_C_POSITION     , "#FFFFCC");
   
     checkDispDisabled(form.search_button1 , true);
   }
   
   function setBillingDisabled(){
     var form = document.mainForm;
   
     checkDispReadOnly(form.M_CL_G_NAME        , true);
     checkDispReadOnly(form.M_CL_G_KANA        , true);
     checkDispReadOnly(form.M_CL_C_NAME        , true);
     checkDispReadOnly(form.M_CL_C_KANA        , true);
     checkDispReadOnly(form.M_CL_C_EMAIL       , true);
     checkDispReadOnly(form.M_CL_C_CC_EMAIL    , true);
     checkDispReadOnly(form.M_CL_C_TEL         , true);
     checkDispReadOnly(form.M_CL_C_TEL_1       , true);
     checkDispReadOnly(form.M_CL_C_TEL_2       , true);
     checkDispReadOnly(form.M_CL_C_TEL_3       , true);
     checkDispReadOnly(form.M_CL_C_FAX         , true);
     checkDispReadOnly(form.M_CL_C_FAX_1       , true);
     checkDispReadOnly(form.M_CL_C_FAX_2       , true);
     checkDispReadOnly(form.M_CL_C_FAX_3       , true);
     checkDispReadOnly(form.M_CL_C_POST        , true);
     checkDispReadOnly(form.M_CL_C_POST_u      , true);
     checkDispReadOnly(form.M_CL_C_POST_l      , true);
     checkDispReadOnly(form.M_CL_C_STA         , true);
     checkDispReadOnly(form.M_CL_C_ADDRESS     , true);
     checkDispReadOnly(form.M_CL_C_ADDRESS2    , true);
     checkDispReadOnly(form.M_CL_C_AFFILIATION , true);
     checkDispReadOnly(form.M_CL_C_POSITION    , true);
     
     checkDispColor(form.M_CL_G_NAME         , "#FFFFCC");
     checkDispColor(form.M_CL_G_KANA         , "#FFFFCC");
     checkDispColor(form.M_CL_C_NAME         , "#FFFFCC");
     checkDispColor(form.M_CL_C_KANA         , "#FFFFCC");
     checkDispColor(form.M_CL_C_EMAIL        , "#FFFFCC");
     checkDispColor(form.M_CL_C_CC_EMAIL     , "#FFFFCC");
     checkDispColor(form.M_CL_C_TEL_1        , "#FFFFCC");
     checkDispColor(form.M_CL_C_TEL_2        , "#FFFFCC");
     checkDispColor(form.M_CL_C_TEL_3        , "#FFFFCC");
     checkDispColor(form.M_CL_C_FAX_1        , "#FFFFCC");
     checkDispColor(form.M_CL_C_FAX_2        , "#FFFFCC");
     checkDispColor(form.M_CL_C_FAX_3        , "#FFFFCC");
     checkDispColor(form.M_CL_C_POST_u       , "#FFFFCC");
     checkDispColor(form.M_CL_C_POST_l       , "#FFFFCC");
     checkDispColor(form.M_CL_C_STA          , "#FFFFCC");
     checkDispColor(form.M_CL_C_ADDRESS      , "#FFFFCC");
     checkDispColor(form.M_CL_C_ADDRESS2     , "#FFFFCC");
     checkDispColor(form.M_CL_C_AFFILIATION  , "#FFFFCC");
     checkDispColor(form.M_CL_C_POSITION     , "#FFFFCC");
   
     checkDispReadOnly(form.M_BANK_CODE      , true);
     checkDispReadOnly(form.M_BRANCH_CODE    , true);
     checkDispReadOnly(form.M_ACCAUNT_TYPE   , true);
     checkDispReadOnly(form.M_ACCAUNT_NUMBER , true);
     checkDispReadOnly(form.M_ACCAUNT_NAME   , true);
     checkDispReadOnly(form.M_CUST_NO        , true);
     checkDispReadOnly(form.M_SAVINGS_CODE   , true);
     checkDispReadOnly(form.M_SAVINGS_NUMBER , true);
   
     checkDispColor(form.M_BANK_CODE       , "#FFFFCC");
     checkDispColor(form.M_BRANCH_CODE     , "#FFFFCC");
     checkDispColor(form.M_ACCAUNT_TYPE    , "#FFFFCC");
     checkDispColor(form.M_ACCAUNT_NUMBER  , "#FFFFCC");
     checkDispColor(form.M_ACCAUNT_NAME    , "#FFFFCC");
     checkDispColor(form.M_CUST_NO         , "#FFFFCC");
     checkDispColor(form.M_SAVINGS_CODE    , "#FFFFCC");
     checkDispColor(form.M_SAVINGS_NUMBER  , "#FFFFCC");
   
     checkDispDisabled(form.M_BANK_CODE    , true);
     checkDispDisabled(form.M_BRANCH_CODE  , true);
     checkDispDisabled(form.search_button2 , true);
     checkDispDisabled(form.search_button3 , true);
     checkDispDisabled(form.search_button_m, true);
     checkDispDisabled(form.M_ACCAUNT_TYPE , true);
   
   }
   
   function setContactEnabled(){
     var form = document.mainForm;
   
     checkDispReadOnly(form.M_CO_G_NAME        , false);
     checkDispReadOnly(form.M_CO_G_KANA        , false);
     checkDispReadOnly(form.M_CO_C_NAME        , false);
     checkDispReadOnly(form.M_CO_C_KANA        , false);
     checkDispReadOnly(form.M_CO_C_EMAIL       , false);
     checkDispReadOnly(form.M_CO_C_CC_EMAIL    , false);
     checkDispReadOnly(form.M_CO_C_TEL         , false);
     checkDispReadOnly(form.M_CO_C_TEL_1       , false);
     checkDispReadOnly(form.M_CO_C_TEL_2       , false);
     checkDispReadOnly(form.M_CO_C_TEL_3       , false);
     checkDispReadOnly(form.M_CO_C_FAX         , false);
     checkDispReadOnly(form.M_CO_C_FAX_1       , false);
     checkDispReadOnly(form.M_CO_C_FAX_2       , false);
     checkDispReadOnly(form.M_CO_C_FAX_3       , false);
     checkDispReadOnly(form.M_CO_C_POST        , false);
     checkDispReadOnly(form.M_CO_C_POST_u      , false);
     checkDispReadOnly(form.M_CO_C_POST_l      , false);
     checkDispReadOnly(form.M_CO_C_STA         , false);
     checkDispReadOnly(form.M_CO_C_ADDRESS     , false);
     checkDispReadOnly(form.M_CO_C_ADDRESS2    , false);
     checkDispReadOnly(form.M_CO_C_AFFILIATION , false);
     checkDispReadOnly(form.M_CO_C_POSITION    , false);
   
     checkDispColor(form.M_CO_G_NAME         , "");
     checkDispColor(form.M_CO_G_KANA         , "");
     checkDispColor(form.M_CO_C_NAME         , "");
     checkDispColor(form.M_CO_C_KANA         , "");
     checkDispColor(form.M_CO_C_EMAIL        , "");
     checkDispColor(form.M_CO_C_CC_EMAIL     , "");
     checkDispColor(form.M_CO_C_TEL_1        , "");
     checkDispColor(form.M_CO_C_TEL_2        , "");
     checkDispColor(form.M_CO_C_TEL_3        , "");
     checkDispColor(form.M_CO_C_FAX_1        , "");
     checkDispColor(form.M_CO_C_FAX_2        , "");
     checkDispColor(form.M_CO_C_FAX_3        , "");
     checkDispColor(form.M_CO_C_POST_u       , "");
     checkDispColor(form.M_CO_C_POST_l       , "");
     checkDispColor(form.M_CO_C_STA          , "");
     checkDispColor(form.M_CO_C_ADDRESS      , "");
     checkDispColor(form.M_CO_C_ADDRESS2     , "");
     checkDispColor(form.M_CO_C_AFFILIATION  , "");
     checkDispColor(form.M_CO_C_POSITION     , "");
   
     checkDispDisabled(form.search_button1 , false);
   }
   
   function setBillingEnabled(){
     var form = document.mainForm;
   
     checkDispReadOnly(form.M_CL_G_NAME        , false);
     checkDispReadOnly(form.M_CL_G_KANA        , false);
     checkDispReadOnly(form.M_CL_C_NAME        , false);
     checkDispReadOnly(form.M_CL_C_KANA        , false);
     checkDispReadOnly(form.M_CL_C_EMAIL       , false);
     checkDispReadOnly(form.M_CL_C_CC_EMAIL    , false);
     checkDispReadOnly(form.M_CL_C_TEL         , false);
     checkDispReadOnly(form.M_CL_C_TEL_1       , false);
     checkDispReadOnly(form.M_CL_C_TEL_2       , false);
     checkDispReadOnly(form.M_CL_C_TEL_3       , false);
     checkDispReadOnly(form.M_CL_C_FAX         , false);
     checkDispReadOnly(form.M_CL_C_FAX_1       , false);
     checkDispReadOnly(form.M_CL_C_FAX_2       , false);
     checkDispReadOnly(form.M_CL_C_FAX_3       , false);
     checkDispReadOnly(form.M_CL_C_POST        , false);
     checkDispReadOnly(form.M_CL_C_POST_u      , false);
     checkDispReadOnly(form.M_CL_C_POST_l      , false);
     checkDispReadOnly(form.M_CL_C_STA         , false);
     checkDispReadOnly(form.M_CL_C_ADDRESS     , false);
     checkDispReadOnly(form.M_CL_C_ADDRESS2    , false);
     checkDispReadOnly(form.M_CL_C_AFFILIATION , false);
     checkDispReadOnly(form.M_CL_C_POSITION    , false);
     
     checkDispColor(form.M_CL_G_NAME         , "");
     checkDispColor(form.M_CL_G_KANA         , "");
     checkDispColor(form.M_CL_C_NAME         , "");
     checkDispColor(form.M_CL_C_KANA         , "");
     checkDispColor(form.M_CL_C_EMAIL        , "");
     checkDispColor(form.M_CL_C_CC_EMAIL     , "");
     checkDispColor(form.M_CL_C_TEL_1        , "");
     checkDispColor(form.M_CL_C_TEL_2        , "");
     checkDispColor(form.M_CL_C_TEL_3        , "");
     checkDispColor(form.M_CL_C_FAX_1        , "");
     checkDispColor(form.M_CL_C_FAX_2        , "");
     checkDispColor(form.M_CL_C_FAX_3        , "");
     checkDispColor(form.M_CL_C_POST_u       , "");
     checkDispColor(form.M_CL_C_POST_l       , "");
     checkDispColor(form.M_CL_C_STA          , "");
     checkDispColor(form.M_CL_C_ADDRESS      , "");
     checkDispColor(form.M_CL_C_ADDRESS2     , "");
     checkDispColor(form.M_CL_C_AFFILIATION  , "");
     checkDispColor(form.M_CL_C_POSITION     , "");
   
     checkDispReadOnly(form.M_BANK_CODE      , false);
     checkDispReadOnly(form.M_BRANCH_CODE    , false);
     checkDispReadOnly(form.M_ACCAUNT_TYPE   , false);
     checkDispReadOnly(form.M_ACCAUNT_NUMBER , false);
     checkDispReadOnly(form.M_ACCAUNT_NAME   , false);
     checkDispReadOnly(form.M_CUST_NO        , false);
     checkDispReadOnly(form.M_SAVINGS_CODE   , false);
     checkDispReadOnly(form.M_SAVINGS_NUMBER , false);
   
     checkDispColor(form.M_BANK_CODE       , "");
     checkDispColor(form.M_BRANCH_CODE     , "");
     checkDispColor(form.M_ACCAUNT_TYPE    , "");
     checkDispColor(form.M_ACCAUNT_NUMBER  , "");
     checkDispColor(form.M_ACCAUNT_NAME    , "");
     checkDispColor(form.M_CUST_NO         , "");
     checkDispColor(form.M_SAVINGS_CODE    , "");
     checkDispColor(form.M_SAVINGS_NUMBER  , "");
   
     checkDispDisabled(form.M_BANK_CODE    , false);
     checkDispDisabled(form.M_BRANCH_CODE  , false);
     checkDispDisabled(form.search_button2 , false);
     checkDispDisabled(form.search_button3 , false);
     checkDispDisabled(form.search_button_m, false);
     checkDispDisabled(form.M_ACCAUNT_TYPE , false);
   
   }
   
   var g_focusItem = null;
   var g_focusElem = null;
   
   
   function SetDicItem(itemPos){
     var i;
     var itemName = "";
     for(i=0;i<document.all.length;i++){
       if(document.all[i].name == document.activeElement.name){
         itemName = document.all[i - itemPos].innerHTML;
         break;
       }
     }
     g_focusItem = itemName;
     g_focusElem = document.activeElement.name;
   }
   
   function ShowDicWnd(url){
     var dicName;
     switch(g_focusItem){
     case "役職":
       dicName = "役職名";
       break;
     }
     return open(url + "?form=document.mainForm&item=" + dicName + "&text=" + g_focusElem,
       'SearchWnd',
       'width=300,height=150,left=600,top=0,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
   }
   
   function ShowDicWnd2(url,dicName,eleName){
     return open(url + "?form=document.mainForm&item=" + dicName + "&text=" + eleName,
       'SearchWnd',
       'width=300,height=150,left=600,top=0,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
   }
   
   function OnZipCode(name1, name2){
    var flag = 0;
	if(name1 == "G_POST_u"){
		flag = 2;
	}
	var zip1 = document.getElementsByName(name1);
	zip1 = zip1[0].value;
	var zip2 = document.getElementsByName(name2);
	zip2 = zip2[0].value;
	var buf = document.URL.split("/");
	var path;
	if(zip1.length != 3){
		alert("郵便番号は３桁以上を入力して下さい。");
		return false;
	}
	buf = '/nakama-member-zipcode?zipcode=' + zip1 + zip2+'&flag='+flag;
  	gToolWnd = open(buf,
      'DetailWnd',
      'width=750,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
   }
   
   function pdCheckInputData(bChange){
     var form = document.mainForm;
     var messageflg = 1;
     var m_card_open;
     var m_open = form.P_O_NAME.value;
     if(form.P_CARD_OPEN == false || form.P_CARD_OPEN.type == "hidden"){
       messageflg = 0;
     }else{
       m_card_open = form.P_CARD_OPEN.value;
     }
     var change_flg="0";
     if(bChange == true){
       change_flg="1";
     }
     
     if(bChange == false){
       if(IsNull(form.P_P_ID.value, "個人ID")){
         return errProc(form.P_P_ID);
       }
       if(IsLength(form.P_P_ID.value, 4, 20, "個人ID")){
         return errProc(form.P_P_ID);
       }
       if(IsNarrowPlus(form.P_P_ID.value, "個人ID")){
         return errProc(form.P_P_ID);
       }
     }
     
     if(form.P_PASSWORD.type != "hidden"){
       if(IsNull(form.P_PASSWORD.value, "パスワード")){
         return errProc(form.P_PASSWORD);
       }
       if(IsLength(form.P_PASSWORD.value, 4, 20, "パスワード")){
         return errProc(form.P_PASSWORD);
       }
       if(IsNarrowPassword(form.P_PASSWORD.value, "パスワード")){
         return errProc(form.P_PASSWORD);
       }
     }
   
   
     if(form.P_PASSWORD.value != form.P_PASSWORD2.value){
       alert("パスワードの内容と確認入力の内容が一致しません。\nパスワードをもう一度確認して下さい");
       form.P_PASSWORD2.value = "";
       form.P_PASSWORD2.focus();
       return false;
     }
     
     if(form.P_C_NAME.type != "hidden"){
       if(IsNull(form.P_C_NAME.value, "氏名")){
         return errProc(form.P_C_NAME);
       }
       if(IsLengthB(form.P_C_NAME.value, 0, 200, "氏名")){
         return errProc(form.P_C_NAME);
       }
     }
     
     if(form.P_C_KANA.type != "hidden"){
       if(messageflg == 1 && m_open != form.P_O_KANA.value){
         messageflg = 0;
       }
       if(IsNull(form.P_C_KANA.value, "個人フリガナ")){
         return errProc(form.P_C_KANA);
       }
       if(IsLengthB(form.P_C_KANA.value, 0, 200, "個人フリガナ")){
         return errProc(form.P_C_KANA);
       }
     }
     
     if(form.P_C_SEX.type != "hidden"){
       if(messageflg == 1 && m_open != form.P_O_SEX.value){
         messageflg = 0;
       }
     }
     
     if(form.P_C_URL.type != "hidden"){
       if(messageflg == 1 && m_open != form.P_O_URL.value){
         messageflg = 0;
       }
       if(IsLength(form.P_C_URL.value, 0, 100, "個人URL")){
         return errProc(form.P_C_URL);
       }
       if(IsNarrowPlus(form.P_C_URL.value, "個人URL")){
         return errProc(form.P_C_URL);
       }
     }
     
     if(form.P_C_EMAIL.type != "hidden"){
       if(messageflg == 1 && m_open != form.P_O_EMAIL.value){
         messageflg = 0;
       }
       if(IsLength(form.P_C_EMAIL.value, 0, 100, "個人E-MAIL")){
         return errProc(form.P_C_EMAIL);
       }
     }
     if(IsNarrowPlus3(form.P_C_EMAIL.value, "個人E-MAIL")){
       return errProc(form.P_C_EMAIL);
     }
     if(isMail(form.P_C_EMAIL.value, "個人E-MAIL")){
       return errProc(form.P_C_EMAIL);
     }
   
     if(form.P_C_EMAIL2 != undefined){
       if(form.P_C_EMAIL2.type != "hidden"){
         if(IsLength(form.P_C_EMAIL2.value, 0, 100, "個人E-MAIL再入力")){
           return errProc(form.P_C_EMAIL2);
         }
       }
       if(IsNarrowPlus3(form.P_C_EMAIL2.value, "個人E-MAIL再入力")){
         return errProc(form.P_C_EMAIL2);
       }
       if(isMail(form.P_C_EMAIL2.value, "個人E-MAIL再入力")){
         return errProc(form.P_C_EMAIL2);
       }
       if(form.P_C_EMAIL.value != form.P_C_EMAIL2.value){
         alert("個人E-MAILの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
         form.P_C_EMAIL2.value = "";
         form.P_C_EMAIL2.focus();
         return false;
       }
     }
   
     if(form.P_C_CC_EMAIL != undefined){
       if(form.P_C_CC_EMAIL.type != "hidden"){
         if(messageflg == 1 && m_open != form.P_O_C_CC_EMAIL.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_CC_EMAIL.value, 0, 100, "個人追加送信先E-MAIL")){
           return errProc(form.P_C_CC_EMAIL);
         }
       }
       if(IsNarrowPlus3(form.P_C_CC_EMAIL.value, "個人追加送信先E-MAIL")){
         return errProc(form.P_C_CC_EMAIL);
       }
       if(isMail(form.P_C_CC_EMAIL.value, "個人追加送信先E-MAIL")){
         return errProc(form.P_C_CC_EMAIL);
       }
     }
     
     if(form.P_C_TEL.type != "hidden"){
       if(messageflg == 1 && m_open != form.P_O_TEL.value){
         messageflg = 0;
       }
     }
     if(form.P_C_TEL_1.value != "" || form.P_C_TEL_2.value != "" || form.P_C_TEL_3.value != ""){
       if(form.P_C_TEL_1.value == ""){
         alert("個人TEL\nを登録する場合は\n全ての欄を入力して下さい")
         return errProc(form.P_C_TEL_1);
       }
       if(form.P_C_TEL_2.value == ""){
         alert("個人TEL\nを登録する場合は\n全ての欄を入力して下さい")
         return errProc(form.P_C_TEL_2);
       }
       if(form.P_C_TEL_3.value == ""){
         alert("個人TEL\nを登録する場合は\n全ての欄を入力して下さい")
         return errProc(form.P_C_TEL_3);
       }
       if(IsNarrowTelNum(form.P_C_TEL_1.value, "個人TEL")){
         return errProc(form.P_C_TEL_1);
       }
       if(IsNarrowTelNum(form.P_C_TEL_2.value, "個人TEL")){
         return errProc(form.P_C_TEL_2);
       }
       if(IsNarrowTelNum(form.P_C_TEL_3.value, "個人TEL")){
         return errProc(form.P_C_TEL_3);
       }
       form.P_C_TEL.value = form.P_C_TEL_1.value + "-" + form.P_C_TEL_2.value + "-" + form.P_C_TEL_3.value;
       if(form.P_C_TEL.type != "hidden"){
         if(IsLength(form.P_C_TEL.value, 0, 20, "個人TEL")){
           return errProc(form.P_C_TEL_1);
         }
       }
     } else {
       form.P_C_TEL.value = "";
     }
     
     if(form.P_C_FAX.type != "hidden"){
       if(messageflg == 1 && m_open != form.P_O_FAX.value){
         messageflg = 0;
       }
     }
     if(form.P_C_FAX_1.value != "" || form.P_C_FAX_2.value != "" || form.P_C_FAX_3.value != ""){
       if(form.P_C_FAX_1.value == ""){
         alert("個人FAX\nを登録する場合は\n全ての欄を入力して下さい")
         return errProc(form.P_C_FAX_1);
       }
       if(form.P_C_FAX_2.value == ""){
         alert("個人FAX\nを登録する場合は\n全ての欄を入力して下さい")
         return errProc(form.P_C_FAX_2);
       }
       if(form.P_C_FAX_3.value == ""){
         alert("個人FAX\nを登録する場合は\n全ての欄を入力して下さい")
         return errProc(form.P_C_FAX_3);
       }
       if(IsNarrowTelNum(form.P_C_FAX_1.value, "個人FAX")){
         return errProc(form.P_C_FAX_1);
       }
       if(IsNarrowTelNum(form.P_C_FAX_2.value, "個人FAX")){
         return errProc(form.P_C_FAX_2);
       }
       if(IsNarrowTelNum(form.P_C_FAX_3.value, "個人FAX")){
         return errProc(form.P_C_FAX_3);
       }
       form.P_C_FAX.value = form.P_C_FAX_1.value + "-" + form.P_C_FAX_2.value + "-" + form.P_C_FAX_3.value;
       if(form.P_C_FAX.type != "hidden"){
         if(IsLength(form.P_C_FAX.value, 0, 20, "個人FAX")){
           form.P_C_FAX_1.value = form.P_C_FAX_2.value = form.P_C_FAX_3.value = ""
           form.P_C_FAX_1.focus();
           return false;
         }
       }
     } else {
       form.P_C_FAX.value = "";
     }
     
     if(form.P_C_PTEL.type != "hidden"){
       if(messageflg == 1 && m_open != form.P_O_PTEL.value){
         messageflg = 0;
       }
     }
     if(form.P_C_PTEL_1.value != "" || form.P_C_PTEL_2.value != "" || form.P_C_PTEL_3.value != ""){
       if(form.P_C_PTEL_1.value == ""){
         alert("携帯\nを登録する場合は\n全ての欄を入力して下さい")
         return errProc(form.P_C_PTEL_1);
       }
       if(form.P_C_PTEL_2.value == ""){
         alert("携帯\nを登録する場合は\n全ての欄を入力して下さい")
         return errProc(form.P_C_PTEL_2);
       }
       if(form.P_C_PTEL_3.value == ""){
         alert("携帯\nを登録する場合は\n全ての欄を入力して下さい")
         return errProc(form.P_C_PTEL_3);
       }
       if(IsNarrowTelNum(form.P_C_PTEL_1.value, "携帯")){
         return errProc(form.P_C_PTEL_1);
       }
       if(IsNarrowTelNum(form.P_C_PTEL_2.value, "携帯")){
         return errProc(form.P_C_PTEL_2);
       }
       if(IsNarrowTelNum(form.P_C_PTEL_3.value, "携帯")){
         return errProc(form.P_C_PTEL_3);
       }
       form.P_C_PTEL.value = form.P_C_PTEL_1.value + "-" + form.P_C_PTEL_2.value + "-" + form.P_C_PTEL_3.value;
       if(form.P_C_PTEL.type != "hidden"){
         if(IsLength(form.P_C_PTEL.value, 0, 20, "携帯")){
           form.P_C_PTEL_1.value = form.P_C_PTEL_2.value = form.P_C_PTEL_3.value = ""
           form.P_C_PTEL_1.focus();
           return false;
         }
       }
     } else {
       form.P_C_PTEL.value = "";
     }
     
     if(form.P_C_PMAIL.type != "hidden"){
       if(messageflg == 1 && m_open != form.P_O_PMAIL.value){
         messageflg = 0;
       }
       if(IsLength(form.P_C_PMAIL.value, 0, 100, "携帯メール")){
         return errProc(form.P_C_PMAIL);
       }
     }
     if(IsNarrowPlus3(form.P_C_PMAIL.value, "携帯メール")){
       return errProc(form.P_C_PMAIL);
     }
     if(isMail(form.P_C_PMAIL.value, "携帯メール")){
       return errProc(form.P_C_PMAIL);
     }
   
     if(form.P_C_PMAIL2 != undefined){
       if(form.P_C_PMAIL2.type != "hidden"){
         if(IsLength(form.P_C_PMAIL2.value, 0, 100, "携帯メール再入力")){
           return errProc(form.P_C_PMAIL2);
         }
       }
       if(IsNarrowPlus3(form.P_C_PMAIL2.value, "携帯メール再入力")){
         return errProc(form.P_C_PMAIL2);
       }
       if(isMail(form.P_C_PMAIL2.value, "携帯メール再入力")){
         return errProc(form.P_C_PMAIL2);
       }
       if(form.P_C_PMAIL.value != form.P_C_PMAIL2.value){
         alert("携帯メールの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
         form.P_C_PMAIL2.value = "";
         form.P_C_PMAIL2.focus();
         return false;
       }
     }
   
     if(form.P_C_POST.type != "hidden"){
       if(messageflg == 1 && m_open != form.P_O_POST.value){
         messageflg = 0;
       }
     }
     if(form.P_C_POST_u.value != "" || form.P_C_POST_l.value != ""){
       if(form.P_C_POST_u.value == ""){
         alert("個人〒\nを登録する場合は\n全ての欄を入力して下さい");
         return errProc(form.P_C_POST_u);
       }
       if(IsLength(form.P_C_POST_u.value, 3, 3, "個人〒(上３桁)")){
         return errProc(form.P_C_POST_u);
       }
       if(IsNarrowNum(form.P_C_POST_u.value, "個人〒")){
         return errProc(form.P_C_POST_u);
       }
       if(form.P_C_POST_l.value == ""){
         alert("個人〒\nを登録する場合は\n全ての欄を入力して下さい");
         return errProc(form.P_C_POST_l);
       }
       if(IsLength(form.P_C_POST_l.value, 4, 4, "個人〒(下４桁)")){
         return errProc(form.P_C_POST_l);
       }
       if(IsNarrowNum(form.P_C_POST_l.value, "個人〒")){
         return errProc(form.P_C_POST_l);
       }
       form.P_C_POST.value = form.P_C_POST_u.value + "-" + form.P_C_POST_l.value;
     } else {
       form.P_C_POST.value = "";
     }
     
     if(form.P_C_STA.type != "hidden"){
       if(messageflg == 1 && m_open != form.P_O_STA.value){
         messageflg = 0;
       }
     }
     
     if(form.P_C_ADDRESS.type != "hidden"){
       if(messageflg == 1 && m_open != form.P_O_ADDRESS.value){
         messageflg = 0;
       }
     }
     if(IsLengthB(form.P_C_ADDRESS.value, 0, 500, "個人住所１")){
       return errProc(form.P_C_ADDRESS);
     }
     
     if(form.P_C_ADDRESS.type != "hidden"){
       if(messageflg == 1 && m_open != form.P_O_ADDRESS.value){
         messageflg = 0;
       }
       if(IsLengthB(form.P_C_ADDRESS2.value, 0, 100, "建物ビル名")){
         return errProc(form.P_C_ADDRESS2);
       }
     }
     
     if(form.P_C_IMG.type != "hidden"){
       if(messageflg == 1 && m_open != form.P_O_IMG.value){
         messageflg = 0;
       }
     }
     
     if(form.P_C_IMG2 == true){
       if(form.P_C_IMG2.type != "hidden"){
         if(messageflg == 1 && m_open != form.P_O_IMG2.value){
           messageflg = 0;
         }
       }
     }
     
     if(form.P_C_IMG3 == true){
       if(form.P_C_IMG3.type != "hidden"){
         if(messageflg == 1 && m_open != form.P_O_IMG3.value){
           messageflg = 0;
         }
       }
     }
     
     if(form.P_C_APPEAL.type != "hidden"){
       if(messageflg == 1 && m_open != form.P_O_APPEAL.value){
         messageflg = 0;
       }
       if(IsLength(form.P_C_APPEAL.value, 0, 500, "個人アピール")){
         return errProc(form.P_C_APPEAL);
       }
     }
     
     if(form.P_C_KEYWORD.type != "hidden"){
       if(IsLength(form.P_C_KEYWORD.value, 0, 500, "事務局検索用コメント")){
         return errProc(form.P_C_KEYWORD);
       }
     }
     
     if('dmshibuya' != 'jeca2'){
       if('dmshibuya' == ''){
       }else{
         if(form.P_C_BIKOU1 == true || form.P_C_BIKOU1 == "[object HTMLTextAreaElement]"){
           if(form.P_C_BIKOU1.type == "text" || form.P_C_BIKOU1.type == "textarea"){
             if(messageflg == 1 && m_open != form.P_O_BIKOU1.value){
               messageflg = 0;
             }
             if(IsLength(form.P_C_BIKOU1.value, 0, 2000, "当会を何で知りましたか？")){
               return errProc(form.P_C_BIKOU1);
             }
           }
         }
       }
     }
     if(form.P_C_BIKOU2 == true || form.P_C_BIKOU2 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU2.type == "text" || form.P_C_BIKOU2.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU2.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU2.value, 0, 2000, "個人自由項目２")){
           return errProc(form.P_C_BIKOU2);
         }
       }
     }
     if(form.P_C_BIKOU3 == true || form.P_C_BIKOU3 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU3.type == "text" || form.P_C_BIKOU3.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU3.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU3.value, 0, 2000, "個人自由項目３")){
           return errProc(form.P_C_BIKOU3);
         }
       }
     }
     if(form.P_C_BIKOU4 == true || form.P_C_BIKOU4 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU4.type == "text" || form.P_C_BIKOU4.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU4.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU4.value, 0, 2000, "個人自由項目４")){
           return errProc(form.P_C_BIKOU4);
         }
       }
     }
     if(form.P_C_BIKOU5 == true || form.P_C_BIKOU5 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU5.type == "text" || form.P_C_BIKOU5.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU5.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU5.value, 0, 2000, "個人自由項目５")){
           return errProc(form.P_C_BIKOU5);
         }
       }
     }
     if(form.P_C_BIKOU6 == true || form.P_C_BIKOU6 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU6.type == "text" || form.P_C_BIKOU6.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU6.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU6.value, 0, 2000, "個人自由項目６")){
           return errProc(form.P_C_BIKOU6);
         }
       }
     }
     if(form.P_C_BIKOU7 == true || form.P_C_BIKOU7 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU7.type == "text" || form.P_C_BIKOU7.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU7.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU7.value, 0, 2000, "個人自由項目７")){
           return errProc(form.P_C_BIKOU7);
         }
       }
     }
     if(form.P_C_BIKOU8 == true || form.P_C_BIKOU8 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU8.type == "text" || form.P_C_BIKOU8.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU8.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU8.value, 0, 2000, "個人自由項目８")){
           return errProc(form.P_C_BIKOU8);
         }
       }
     }
     if(form.P_C_BIKOU9 == true || form.P_C_BIKOU9 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU9.type == "text" || form.P_C_BIKOU9.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU9.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU9.value, 0, 2000, "個人自由項目９")){
           return errProc(form.P_C_BIKOU9);
         }
       }
     }
     if(form.P_C_BIKOU10 == true || form.P_C_BIKOU10 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU10.type == "text" || form.P_C_BIKOU10.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU10.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU10.value, 0, 2000, "個人自由項目１０")){
           return errProc(form.P_C_BIKOU10);
         }
       }
     }
     if(form.P_C_BIKOU11 == true || form.P_C_BIKOU11 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU11.type == "text" || form.P_C_BIKOU11.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU11.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU11.value, 0, 2000, "個人自由項目１１")){
           return errProc(form.P_C_BIKOU11);
         }
       }
     }
     if(form.P_C_BIKOU12 == true || form.P_C_BIKOU12 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU12.type == "text" || form.P_C_BIKOU12.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU12.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU12.value, 0, 2000, "個人自由項目１２")){
           return errProc(form.P_C_BIKOU12);
         }
       }
     }
     if(form.P_C_BIKOU13 == true || form.P_C_BIKOU13 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU13.type == "text" || form.P_C_BIKOU13.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU13.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU13.value, 0, 2000, "個人自由項目１３")){
           return errProc(form.P_C_BIKOU13);
         }
       }
     }
     if(form.P_C_BIKOU14 == true || form.P_C_BIKOU14 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU14.type == "text" || form.P_C_BIKOU14.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU14.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU14.value, 0, 2000, "個人自由項目１４")){
           return errProc(form.P_C_BIKOU14);
         }
       }
     }
     if(form.P_C_BIKOU15 == true || form.P_C_BIKOU15 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU15.type == "text" || form.P_C_BIKOU15.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU15.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU15.value, 0, 2000, "個人自由項目１５")){
           return errProc(form.P_C_BIKOU15);
         }
       }
     }
     if(form.P_C_BIKOU16 == true || form.P_C_BIKOU16 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU16.type == "text" || form.P_C_BIKOU16.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU16.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU16.value, 0, 2000, "個人自由項目１６")){
           return errProc(form.P_C_BIKOU16);
         }
       }
     }
     if(form.P_C_BIKOU17 == true || form.P_C_BIKOU17 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU17.type == "text" || form.P_C_BIKOU17.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU17.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU17.value, 0, 2000, "個人自由項目１７")){
           return errProc(form.P_C_BIKOU17);
         }
       }
     }
     if(form.P_C_BIKOU18 == true || form.P_C_BIKOU18 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU18.type == "text" || form.P_C_BIKOU18.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU18.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU18.value, 0, 2000, "個人自由項目１８")){
           return errProc(form.P_C_BIKOU18);
         }
       }
     }
     if(form.P_C_BIKOU19 == true || form.P_C_BIKOU19 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU19.type == "text" || form.P_C_BIKOU19.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU19.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU19.value, 0, 2000, "個人自由項目１９")){
           return errProc(form.P_C_BIKOU19);
         }
       }
     }
     if(form.P_C_BIKOU20 == true || form.P_C_BIKOU20 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU20.type == "text" || form.P_C_BIKOU20.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU20.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU20.value, 0, 2000, "個人自由項目２０")){
           return errProc(form.P_C_BIKOU20);
         }
       }
     }
     if(form.P_C_BIKOU21 == true || form.P_C_BIKOU21 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU21.type == "text" || form.P_C_BIKOU21.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU21.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU21.value, 0, 2000, "個人自由項目２１")){
           return errProc(form.P_C_BIKOU21);
         }
       }
     }
     if(form.P_C_BIKOU22 == true || form.P_C_BIKOU22 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU22.type == "text" || form.P_C_BIKOU22.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU22.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU22.value, 0, 2000, "個人自由項目２２")){
           return errProc(form.P_C_BIKOU22);
         }
       }
     }
     if(form.P_C_BIKOU23 == true || form.P_C_BIKOU23 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU23.type == "text" || form.P_C_BIKOU23.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU23.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU23.value, 0, 2000, "個人自由項目２３")){
           return errProc(form.P_C_BIKOU23);
         }
       }
     }
     if(form.P_C_BIKOU24 == true || form.P_C_BIKOU24 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU24.type == "text" || form.P_C_BIKOU24.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU24.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU24.value, 0, 2000, "個人自由項目２４")){
           return errProc(form.P_C_BIKOU24);
         }
       }
     }
     if(form.P_C_BIKOU25 == true || form.P_C_BIKOU25 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU25.type == "text" || form.P_C_BIKOU25.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU25.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU25.value, 0, 2000, "個人自由項目２５")){
           return errProc(form.P_C_BIKOU25);
         }
       }
     }
     if(form.P_C_BIKOU26 == true || form.P_C_BIKOU26 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU26.type == "text" || form.P_C_BIKOU26.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU26.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU26.value, 0, 2000, "個人自由項目２６")){
           return errProc(form.P_C_BIKOU26);
         }
       }
     }
     if(form.P_C_BIKOU27 == true || form.P_C_BIKOU27 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU27.type == "text" || form.P_C_BIKOU27.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU27.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU27.value, 0, 2000, "個人自由項目２７")){
           return errProc(form.P_C_BIKOU27);
         }
       }
     }
     if(form.P_C_BIKOU28 == true || form.P_C_BIKOU28 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU28.type == "text" || form.P_C_BIKOU28.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU28.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU28.value, 0, 2000, "個人自由項目２８")){
           return errProc(form.P_C_BIKOU28);
         }
       }
     }
     if(form.P_C_BIKOU29 == true || form.P_C_BIKOU29 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU29.type == "text" || form.P_C_BIKOU29.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU29.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU29.value, 0, 2000, "個人自由項目２９")){
           return errProc(form.P_C_BIKOU29);
         }
       }
     }
     if(form.P_C_BIKOU30 == true || form.P_C_BIKOU30 == "[object HTMLTextAreaElement]"){
       if(form.P_C_BIKOU30.type == "text" || form.P_C_BIKOU30.type == "textarea"){
         if(messageflg == 1 && m_open != form.P_O_BIKOU30.value){
           messageflg = 0;
         }
         if(IsLength(form.P_C_BIKOU30.value, 0, 2000, "個人自由項目３０")){
           return errProc(form.P_C_BIKOU30);
         }
       }
     }
    
     if(form.m_selGid != undefined){
       if(form.m_selGid.type != "hidden"){
         if(messageflg == 1 && m_open != form.P_O_G_ID.value){
           messageflg = 0;
         }
       }
     }
     
     if(form.S_AFFILIATION_NAME.type != "hidden"){
       if(messageflg == 1 && m_open != form.P_O_AFFILIATION.value){
         messageflg = 0;
       }
     }
     
     if(form.S_OFFICIAL_POSITION.type != "hidden"){
       if(messageflg == 1 && m_open != form.P_O_OFFICIAL.value){
         messageflg = 0;
       }
     }
     
     if(form.P_HANDLE_NAME != undefined){
       if(form.P_HANDLE_NAME.type != "hidden"){
         if(IsLengthB(form.P_HANDLE_NAME.value, 0, 200, "会議室ニックネーム")){
           return errProc(form.P_HANDLE_NAME);
         }
       }
     }
     
     if(form.P_MEETING_NAME_MARK != undefined){
       if(form.P_MEETING_NAME_MARK.type != "hidden"){
         if(IsLengthB(form.P_MEETING_NAME_MARK.value, 0, 200, "会議室公開ネーム表示マーク")){
           return errProc(form.P_MEETING_NAME_MARK);
         }
       }
     }
   
   
     if(form.P_P_URL.type != "hidden"){
       if(IsLength(form.P_P_URL.value, 0, 100, "URL")){
         return errProc(form.P_P_URL);
       }
       if(IsNarrowPlus(form.P_P_URL.value, "URL")){
         return errProc(form.P_P_URL);
       }
     }
     
     if(form.P_P_EMAIL.type != "hidden"){
       if(IsLength(form.P_P_EMAIL.value, 0, 100, "E-MAIL")){
         return errProc(form.P_P_EMAIL);
       }
       if(IsNarrowPlus3(form.P_P_EMAIL.value, "E-MAIL")){
         return errProc(form.P_P_EMAIL);
       }
       if(isMail(form.P_P_EMAIL.value, "E-MAIL")){
         return errProc(form.P_P_EMAIL);
       }
     }
   
     if(form.P_P_EMAIL2 != undefined){
       if(form.P_P_EMAIL2.type != "hidden"){
         if(IsLength(form.P_P_EMAIL2.value, 0, 100, "プライベートE-MAIL再入力")){
           return errProc(form.P_P_EMAIL2);
         }
       }
       if(IsNarrowPlus3(form.P_P_EMAIL2.value, "プライベートE-MAIL再入力")){
         return errProc(form.P_P_EMAIL2);
       }
       if(isMail(form.P_P_EMAIL2.value, "プライベートE-MAIL再入力")){
         return errProc(form.P_P_EMAIL2);
       }
       if(form.P_P_EMAIL.value != form.P_P_EMAIL2.value){
         alert("プライベートE-MAILの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
         form.P_P_EMAIL2.value = "";
         form.P_P_EMAIL2.focus();
         return false;
       }
     }
   
     if(form.P_P_CC_EMAIL != undefined){
       if(form.P_P_CC_EMAIL.type != "hidden"){
         if(IsLength(form.P_P_CC_EMAIL.value, 0, 100, "追加送信先E-MAIL")){
           return errProc(form.P_P_CC_EMAIL);
         }
         if(IsNarrowPlus3(form.P_P_CC_EMAIL.value, "追加送信先E-MAIL")){
           return errProc(form.P_P_CC_EMAIL);
         }
         if(isMail(form.P_P_CC_EMAIL.value, "追加送信先E-MAIL")){
           return errProc(form.P_P_CC_EMAIL);
         }
       }
     }
     
     if(form.P_P_TEL_1.value != "" || form.P_P_TEL_2.value != "" || form.P_P_TEL_3.value != ""){
       if(form.P_P_TEL_1.value == ""){
         alert("電話番号\nを登録する場合は\n全ての欄を入力して下さい");
         return errProc(form.P_P_TEL_1);
       }
       if(form.P_P_TEL_2.value == ""){
         alert("電話番号\nを登録する場合は\n全ての欄を入力して下さい");
         return errProc(form.P_P_TEL_2);
       }
       if(form.P_P_TEL_3.value == ""){
         alert("電話番号\nを登録する場合は\n全ての欄を入力して下さい");
         return errProc(form.P_P_TEL_3);
       }
       if(IsNarrowTelNum(form.P_P_TEL_1.value, "電話番号")){
         return errProc(form.P_P_TEL_1);
       }
       if(IsNarrowTelNum(form.P_P_TEL_2.value, "電話番号")){
         return errProc(form.P_P_TEL_2);
       }
       if(IsNarrowTelNum(form.P_P_TEL_3.value, "電話番号")){
         return errProc(form.P_P_TEL_3);
       }
       form.P_P_TEL.value = form.P_P_TEL_1.value + "-" + form.P_P_TEL_2.value + "-" + form.P_P_TEL_3.value;
   
       if(form.P_P_TEL.type != "hidden"){
         if(IsLength(form.P_P_TEL.value, 0, 20, "電話番号")){
           return errProc(form.P_P_TEL_1);
         }
       }
     } else {
       form.P_P_TEL.value = "";
     }
   
     
     if(form.P_P_FAX_1.value != "" || form.P_P_FAX_2.value != "" || form.P_P_FAX_3.value != ""){
       if(form.P_P_FAX_1.value == ""){
         alert("FAX番号\nを登録する場合は\n全ての欄を入力して下さい");
         return errProc(form.P_P_FAX_1);
       }
       if(form.P_P_FAX_2.value == ""){
         alert("FAX番号\nを登録する場合は\n全ての欄を入力して下さい");
         return errProc(form.P_P_FAX_2);
       }
       if(form.P_P_FAX_3.value == ""){
         alert("FAX番号\nを登録する場合は\n全ての欄を入力して下さい");
         return errProc(form.P_P_FAX_3);
       }
       if(IsNarrowTelNum(form.P_P_FAX_1.value, "FAX番号")){
         return errProc(form.P_P_FAX_1);
       }
       if(IsNarrowTelNum(form.P_P_FAX_2.value, "FAX番号")){
         return errProc(form.P_P_FAX_2);
       }
       if(IsNarrowTelNum(form.P_P_FAX_3.value, "FAX番号")){
         return errProc(form.P_P_FAX_3);
       }
       form.P_P_FAX.value = form.P_P_FAX_1.value + "-" + form.P_P_FAX_2.value + "-" + form.P_P_FAX_3.value;
   
       if(form.P_P_FAX.type != "hidden"){
         if(IsLength(form.P_P_FAX.value, 0, 20, "FAX番号")){
           form.P_P_FAX_1.value = form.P_P_FAX_2.value = form.P_P_FAX_3.value = ""
           form.P_P_FAX_1.focus();
           return false;
         }
       }
     } else {
       form.P_P_FAX.value = "";
     }
   
     
     if(form.P_P_PTEL_1.value != "" || form.P_P_PTEL_2.value != "" || form.P_P_PTEL_3.value != ""){
       if(form.P_P_PTEL_1.value == ""){
         alert("携帯番号\nを登録する場合は\n全ての欄を入力して下さい");
         return errProc(form.P_P_PTEL_1);
       }
       if(form.P_P_PTEL_2.value == ""){
         alert("携帯番号\nを登録する場合は\n全ての欄を入力して下さい");
         return errProc(form.P_P_PTEL_2);
       }
       if(form.P_P_PTEL_3.value == ""){
         alert("携帯番号\nを登録する場合は\n全ての欄を入力して下さい");
         return errProc(form.P_P_PTEL_3);
       }
       if(IsNarrowTelNum(form.P_P_PTEL_1.value, "携帯番号")){
         return errProc(form.P_P_PTEL_1);
       }
       if(IsNarrowTelNum(form.P_P_PTEL_2.value, "携帯番号")){
         return errProc(form.P_P_PTEL_2);
       }
       if(IsNarrowTelNum(form.P_P_PTEL_3.value, "携帯番号")){
         return errProc(form.P_P_PTEL_3);
       }
       form.P_P_PTEL.value = form.P_P_PTEL_1.value + "-" + form.P_P_PTEL_2.value + "-" + form.P_P_PTEL_3.value;
   
       if(form.P_P_PTEL.type != "hidden"){
         if(IsLength(form.P_P_PTEL.value, 0, 20, "携帯番号")){
           form.P_P_PTEL_1.value = form.P_P_PTEL_2.value = form.P_P_PTEL_3.value = ""
           form.P_P_PTEL_1.focus();
           return false;
         }
       }
     } else {
       form.P_P_PTEL.value = "";
     }
   
     
     if(form.P_P_PMAIL.type != "hidden"){
       if(IsLength(form.P_P_PMAIL.value, 0, 100, "携帯メールアドレス")){
         return errProc(form.P_P_PMAIL);
       }
       if(IsNarrowPlus3(form.P_P_PMAIL.value, "携帯メールアドレス")){
         return errProc(form.P_P_PMAIL);
       }
       if(isMail(form.P_P_PMAIL.value, "携帯メールアドレス")){
         return errProc(form.P_P_PMAIL);
       }
     }
   
     if(form.P_P_PMAIL2 != undefined){
       if(form.P_P_PMAIL2.type != "hidden"){
         if(IsLength(form.P_P_PMAIL2.value, 0, 100, "携帯メールアドレス再入力")){
           return errProc(form.P_P_PMAIL2);
         }
       }
       if(IsNarrowPlus3(form.P_P_PMAIL2.value, "携帯メールアドレス再入力")){
         return errProc(form.P_P_PMAIL2);
       }
       if(isMail(form.P_P_PMAIL2.value, "携帯メールアドレス再入力")){
         return errProc(form.P_P_PMAIL2);
       }
       if(form.P_P_PMAIL.value != form.P_P_PMAIL2.value){
         alert("携帯メールアドレスの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
         form.P_P_PMAIL2.value = "";
         form.P_P_PMAIL2.focus();
         return false;
       }
     }
   
     if(form.P_P_POST_u.value != "" || form.P_P_POST_l.value != ""){
       if(form.P_P_POST_u.type != "hidden"){
         if(form.P_P_POST_u.value == ""){
           alert("郵便番号\nを登録する場合は\n全ての欄を入力して下さい");
           return errProc(form.P_P_POST_u);
         }
         if(IsLength(form.P_P_POST_u.value, 3, 3, "郵便番号(上３桁)")){
           return errProc(form.P_P_POST_u);
         }
         if(IsNarrowNum(form.P_P_POST_u.value, "郵便番号")){
           return errProc(form.P_P_POST_u);
         }
       }
       if(form.P_P_POST_l.type != "hidden"){
         if(form.P_P_POST_l.value == ""){
           alert("郵便番号\nを登録する場合は\n全ての欄を入力して下さい");
           return errProc(form.P_P_POST_l);
         }
         if(IsLength(form.P_P_POST_l.value, 4, 4, "郵便番号(下４桁)")){
           return errProc(form.P_P_POST_l);
         }
         if(IsNarrowNum(form.P_P_POST_l.value, "郵便番号")){
           return errProc(form.P_P_POST_l);
         }
         form.P_P_POST.value = form.P_P_POST_u.value + "-" + form.P_P_POST_l.value;
       }
     } else {
       form.P_P_POST.value = "";
     }
   
     
     if(form.P_P_ADDRESS.type != "hidden"){
       if(IsLengthB(form.P_P_ADDRESS.value, 0, 500, "住所１")){
         return errProc(form.P_P_ADDRESS);
       }
     }
     
     if(form.P_P_ADDRESS2.type != "hidden"){
       if(IsLengthB(form.P_P_ADDRESS2.value, 0, 100, "住所２")){
         return errProc(form.P_P_ADDRESS2);
       }
     }
     
     if((form.P_P_BIRTH_YEAR.value != "") || (form.P_P_BIRTH_MONTH.value != "") && (form.P_P_BIRTH_DAY.value != "")){
       if(IsDateImp(form.m_birthImperialP.value, form.P_P_BIRTH_YEAR, form.P_P_BIRTH_MONTH, form.P_P_BIRTH_DAY, "生年月日") != 0){
         return false;
       }
       form.P_P_BIRTH.value = MakeYMD(form.m_birthImperialP.value, form.P_P_BIRTH_YEAR.value, form.P_P_BIRTH_MONTH.value, form.P_P_BIRTH_DAY.value);
     } else {
       form.P_P_BIRTH.value = "";
     }
     
     if(form.P_P_HOBBY.type != "hidden"){
       if(IsLengthB(form.P_P_HOBBY.value, 0, 100, "趣味")){
         return errProc(form.P_P_HOBBY);
       }
     }
     
     if(form.P_P_FAMILY.type != "hidden"){
       if(IsLengthB(form.P_P_FAMILY.value, 0, 30, "家族構成")){
         return errProc(form.P_P_FAMILY);
       }
     }
     
     if((form.P_P_MOURNING_DATE_START_YEAR.value != "") || (form.P_P_MOURNING_DATE_START_MONTH.value != "") || (form.P_P_MOURNING_DATE_START_DAY.value != "")){
       if(IsDateImp(form.m_mournStartImperialP.value, form.P_P_MOURNING_DATE_START_YEAR, form.P_P_MOURNING_DATE_START_MONTH, form.P_P_MOURNING_DATE_START_DAY, "喪中開始年月日") != 0){
         return false;
       }
       form.P_P_MOURNING_DATE_START.value = MakeYMD(form.m_mournStartImperialP.value, form.P_P_MOURNING_DATE_START_YEAR.value, form.P_P_MOURNING_DATE_START_MONTH.value, form.P_P_MOURNING_DATE_START_DAY.value);
     } else {
       form.P_P_MOURNING_DATE_START.value = "";
     }
     
     if((form.P_P_MOURNING_DATE_END_YEAR.value != "") || (form.P_P_MOURNING_DATE_END_MONTH.value != "") || (form.P_P_MOURNING_DATE_END_DAY.value != "")){
       if(IsDateImp(form.m_mournEndImperialP.value, form.P_P_MOURNING_DATE_END_YEAR, form.P_P_MOURNING_DATE_END_MONTH, form.P_P_MOURNING_DATE_END_DAY, "喪中終了年月日") != 0){
         return false;
       }
       form.P_P_MOURNING_DATE_END.value = MakeYMD(form.m_mournEndImperialP.value, form.P_P_MOURNING_DATE_END_YEAR.value, form.P_P_MOURNING_DATE_END_MONTH.value, form.P_P_MOURNING_DATE_END_DAY.value);
     } else {
       form.P_P_MOURNING_DATE_END.value = "";
     }
     
     if(form.P_P_GRADUATION_YEAR != undefined){
       if(form.P_P_GRADUATION_YEAR.type != "hidden"){
         if((form.P_P_GRADUATION_YEAR.value != "")){
           if(IsLengthB(form.P_P_GRADUATION_YEAR.value, 0, 12, "卒業年度（退職年度）")){
             form.P_P_GRADUATION_YEAR.select();
             form.P_P_GRADUATION_YEAR.focus();
             return false;
           }
         }
       }
     }
     
     if(form.P_P_DEPARTMENT != undefined){
       if(form.P_P_DEPARTMENT.type != "hidden"){
         if(IsLengthB(form.P_P_DEPARTMENT.value, 0, 100, "学部（所属）")){
           return errProc(form.P_P_DEPARTMENT);
         }
       }
     }
     
     if(form.P_P_GRADUATION_POSITION != undefined){
       if(form.P_P_GRADUATION_POSITION.type != "hidden"){
         if(IsLengthB(form.P_P_GRADUATION_POSITION.value, 0, 100, "卒業時の学校/学部又は退職時の会社名/役職")){
           return errProc(form.P_P_GRADUATION_POSITION);
         }
       }
     }
     
     if(form.P_P_COUNTRY != undefined){
       if(form.P_P_COUNTRY.type != "hidden"){
         if(IsLengthB(form.P_P_COUNTRY.value, 0, 100, "国名")){
           return errProc(form.P_P_COUNTRY);
         }
       }
     }
     if(messageflg != 0){
       if(m_card_open != "0" && m_open == "0"){
         var openName1;
         var openName2;
         var message = "";
         switch(m_card_open){
         case "0":
           openName1 = "公開しない";
           break;
         case "1":
           openName1 = "一般公開";
           break;
         default:
           openName1 = "会員にのみ公開";
           break;
         }
         switch(m_open){
         case "0":
           openName2 = "公開しない";
           break;
         case "1":
           openName2 = "一般公開";
           break;
         default:
           openName2 = "会員にのみ公開";
           break;
         }
         message = "名刺情報公開設定：【" + openName1 + "】\n各名刺データ公開設定：【" + openName2 + "】\nとなっております。";
         if(m_card_open == "0" || m_open == "0"){
           message = message + "\n\nこのままですと名刺データは公開されませんがよろしいですか？"
         }else{
           message = message + "\n\nこのままですと名刺データは会員にのみ公開されますがよろしいですか？"
         }
         if(!confirm(message)){
           return false;
         }
       }
     }
     return true;
   }
   
   
   function gdCheckInputData(bChange){
     var form = document.mainForm;
     var ifmGetData = getData;
     var ifmSetUrl;
   
     
     if(bChange == false){
       if(form.G_G_ID.type != "hidden"){
         if(IsNull(form.G_G_ID.value, "組織ID")){
           return errProc(form.G_G_ID);
         }
         if(IsLength(form.G_G_ID.value, 4, 20, "組織ID")){
           return errProc(form.G_G_ID);
         }
         if(IsNarrow(form.G_G_ID.value, "組織ID")){
           return errProc(form.G_G_ID);
         }
       }
     }
     
     if(form.G_PASSWORD.type != "hidden"){
       if(IsNull(form.G_PASSWORD.value, "パスワード")){
         return errProc(form.G_PASSWORD);
       }
       if(IsLength(form.G_PASSWORD.value, 4, 20, "パスワード")){
         return errProc(form.G_PASSWORD);
       }
       if(IsNarrowPassword(form.G_PASSWORD.value, "パスワード")){
         return errProc(form.G_PASSWORD);
       }
   
   
   
       if(form.G_PASSWORD2.type != "hidden"){
         if(form.G_PASSWORD.value != form.G_PASSWORD2.value){
           alert("パスワードの内容と確認入力の内容が一致しません。\nパスワードをもう一度確認して下さい");
           form.G_PASSWORD2.value = "";
           form.G_PASSWORD2.focus();
           return false;
         }
       }
     }
   
     
     if(form.G_NAME.type != "hidden"){
       if(IsLengthB(form.G_NAME.value, 0, 1000, "組織名")){
         return errProc(form.G_NAME);
       }
     }
     
     if(form.G_KANA.type != "hidden"){
       if(IsLengthB(form.G_KANA.value, 0, 1000, "組織フリガナ")){
         return errProc(form.G_KANA);
       }
     }
     
     if(form.G_SNAME.type != "hidden"){
       if(IsLengthB(form.G_SNAME.value, 0, 100, "組織略称")){
         return errProc(form.G_SNAME);
       }
     }
     
     if(form.G_CATEGORY_CODE.type != "hidden"){
       if(IsLength(form.G_CATEGORY_CODE.value, 0, 6, "業種コード")){
         return errProc(form.G_CATEGORY_CODE);
       }
       if(IsNarrow(form.G_CATEGORY_CODE.value, "業種コード")){
         return errProc(form.G_CATEGORY_CODE);
       }
     }
    
     if(form.G_CATEGORY.type != "hidden"){
       if(IsLengthB(form.G_CATEGORY.value, 0, 1000, "業種")){
         return errProc(form.G_CATEGORY);
       }
     }
    
     if(form.G_URL.type != "hidden"){
       if(IsLength(form.G_URL.value, 0, 100, "組織URL")){
        return errProc(form.G_URL);
       }
       if(IsNarrowPlus(form.G_URL.value, "組織URL")){
        return errProc(form.G_URL);
       }
     }
   
     
     if(form.G_P_URL != undefined){
       if(form.G_P_URL.type != "hidden"){
         if(IsLength(form.G_P_URL.value, 0, 100, "組織携帯URL")){
           return errProc(form.G_P_URL);
         }
         if(IsNarrowPlus(form.G_P_URL.value, "組織携帯URL")){
           return errProc(form.G_P_URL);
         }
       }
     }
   
     
     if(form.G_EMAIL.type != "hidden"){
       if(IsLength(form.G_EMAIL.value, 0, 100, "組織E-MAIL")){
         return errProc(form.G_EMAIL);
       }
       if(IsNarrowPlus3(form.G_EMAIL.value, "組織E-MAIL")){
         return errProc(form.G_EMAIL);
       }
       if(isMail(form.G_EMAIL.value, "組織E-MAIL")){
         return errProc(form.G_EMAIL);
       }
     }
   
     if(form.G_EMAIL2 != undefined){
       if(form.G_EMAIL2.type != "hidden"){
         if(IsLength(form.G_EMAIL2.value, 0, 100, "組織E-MAIL再入力")){
           return errProc(form.G_EMAIL2);
         }
       }
       if(IsNarrowPlus3(form.G_EMAIL2.value, "組織E-MAIL再入力")){
         return errProc(form.G_EMAIL2);
       }
       if(isMail(form.G_EMAIL2.value, "組織E-MAIL再入力")){
         return errProc(form.G_EMAIL2);
       }
       if(form.G_EMAIL.value != form.G_EMAIL2.value){
         alert("組織E-MAILの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
         form.G_EMAIL2.value = "";
         form.G_EMAIL2.focus();
         return false;
       }
     }
   
     if(form.G_CC_EMAIL != undefined){
       if(form.G_CC_EMAIL.type != "hidden"){
         if(IsLength(form.G_CC_EMAIL.value, 0, 100, "組織追加送信先E-MAIL")){
           return errProc(form.G_CC_EMAIL);
         }
         if(IsNarrowPlus3(form.G_CC_EMAIL.value, "組織追加送信先E-MAIL")){
           return errProc(form.G_CC_EMAIL);
         }
         if(isMail(form.G_CC_EMAIL.value, "組織追加送信先E-MAIL")){
           return errProc(form.G_CC_EMAIL);
         }
       }
     }
   
     
     if(form.G_TEL_1.value != "" || form.G_TEL_2.value != "" || form.G_TEL_3.value != ""){
       if(form.G_TEL_1.value == ""){
         alert("組織TEL\nを登録する場合は\n全ての欄を入力して下さい");
         return errProc(form.G_TEL_1);
       }
       if(form.G_TEL_2.value == ""){
         alert("組織TEL\nを登録する場合は\n全ての欄を入力して下さい");
         return errProc(form.G_TEL_2);
       }
       if(form.G_TEL_3.value == ""){
         alert("組織TEL\nを登録する場合は\n全ての欄を入力して下さい");
         return errProc(form.G_TEL_3);
       }
       if(IsNarrowTelNum(form.G_TEL_1.value, "組織TEL")){
         return errProc(form.G_TEL_1);
       }
       if(IsNarrowTelNum(form.G_TEL_2.value, "組織TEL")){
         return errProc(form.G_TEL_2);
       }
       if(IsNarrowTelNum(form.G_TEL_3.value, "組織TEL")){
         return errProc(form.G_TEL_3);
       }
       form.G_TEL.value = form.G_TEL_1.value + "-" + form.G_TEL_2.value + "-" + form.G_TEL_3.value;
       if(form.G_TEL.type != "hidden"){
         if(IsLength(form.G_TEL.value, 0, 20, "組織TEL")){
           form.G_TEL_1.focus();
           return false;
         }
       }
     } else {
       form.G_TEL.value = "";
     }
     
     if(form.G_FAX_1.value != "" || form.G_FAX_2.value != "" || form.G_FAX_3.value != ""){
       if(form.G_FAX_1.value == ""){
         alert("組織FAX\nを登録する場合は\n全ての欄を入力して下さい");
         return errProc(form.G_FAX_1);
       }
       if(form.G_FAX_2.value == ""){
         alert("組織FAX\nを登録する場合は\n全ての欄を入力して下さい");
         return errProc(form.G_FAX_2);
       }
       if(form.G_FAX_3.value == ""){
         alert("組織FAX\nを登録する場合は\n全ての欄を入力して下さい");
         return errProc(form.G_FAX_3);
       }
       if(IsNarrowTelNum(form.G_FAX_1.value, "組織FAX")){
         return errProc(form.G_FAX_1);
       }
       if(IsNarrowTelNum(form.G_FAX_2.value, "組織FAX")){
         return errProc(form.G_FAX_2);
       }
       if(IsNarrowTelNum(form.G_FAX_3.value, "組織FAX")){
         return errProc(form.G_FAX_3);
       }
       form.G_FAX.value = form.G_FAX_1.value + "-" + form.G_FAX_2.value + "-" + form.G_FAX_3.value;
       if(form.G_FAX.type != "hidden"){
         if(IsLength(form.G_FAX.value, 0, 20, "組織FAX")){
           form.G_FAX_1.value = form.G_FAX_2.value = form.G_FAX_3.value = ""
           form.G_FAX_1.focus();
           return false;
         }
       }
     } else {
       form.G_FAX.value = "";
     }
     
     if((form.G_FOUND_YEAR.value != "") || (form.G_FOUND_MONTH.value != "") || (form.G_FOUND_DAY.value != "")){
       if(IsDateImp(form.m_foundImperialG.value, form.G_FOUND_YEAR, form.G_FOUND_MONTH, form.G_FOUND_DAY, "設立年月日")){
         return false;
       }
       form.G_FOUND_DATE.value = MakeYMD(form.m_foundImperialG.value, form.G_FOUND_YEAR.value, form.G_FOUND_MONTH.value, form.G_FOUND_DAY.value);
     } else {
       form.G_FOUND_DATE.value = "";
     }
     
     if(form.G_CAPITAL.type != "hidden"){
       if(IsLength(form.G_CAPITAL.value, 0, 13, "資本金")){
         return errProc(form.G_CAPITAL);
       }
       if(IsNarrowNum(form.G_CAPITAL.value, "資本金")){
         return errProc(form.G_CAPITAL);
       }
     }
     
     if(form.G_REPRESENTATIVE.type != "hidden"){
       if(IsLengthB(form.G_REPRESENTATIVE.value, 0, 100, "代表者")){
         return errProc(form.G_REPRESENTATIVE);
       }
     }
     
     if(form.G_REPRESENTATIVE_KANA.type != "hidden"){
       if(IsLengthB(form.G_REPRESENTATIVE_KANA.value, 0, 100, "代表者フリガナ")){
         return errProc(form.G_REPRESENTATIVE_KANA);
       }
     }
     
     if(form.G_POST_u.value != "" || form.G_POST_l.value != ""){
       if(form.G_POST_u.type != "hidden"){
         if(form.G_POST_u.value == ""){
           alert("組織〒\nを登録する場合は\n全ての欄を入力して下さい");
           return errProc(form.G_POST_u);
         }
         if(IsLength(form.G_POST_u.value, 3, 3, "組織〒(上３桁)")){
           return errProc(form.G_POST_u);
         }
         if(IsNarrowNum(form.G_POST_u.value, "組織〒")){
           return errProc(form.G_POST_u);
         }
       }
       if(form.G_POST_l.type != "hidden"){
         if(form.G_POST_l.value == ""){
           alert("組織〒\nを登録する場合は\n全ての欄を入力して下さい");
           return errProc(form.G_POST_l);
         }
         if(IsLength(form.G_POST_l.value, 4, 4, "組織〒(下４桁)")){
           return errProc(form.G_POST_l);
         }
         if(IsNarrowNum(form.G_POST_l.value, "組織〒")){
           return errProc(form.G_POST_l);
         }
         form.G_POST.value = form.G_POST_u.value + "-" + form.G_POST_l.value;
       }
     } else {
       form.G_POST.value = "";
     }
     
     if('dmshibuya' == 'jeca2'){
       if(form.G_STA.value == ""){
         alert("組織都道府県を指定してください。");
         return errProc(form.G_STA);
       }
     }
     
     if(form.G_ADDRESS.type != "hidden"){
       if(IsLengthB(form.G_ADDRESS.value, 0, 500, "組織住所１")){
         return errProc(form.G_ADDRESS);
       }
     }
     
     if(form.G_ADDRESS2.type != "hidden"){
       if(IsLengthB(form.G_ADDRESS2.value, 0, 100, "組織住所２")){
         return errProc(form.G_ADDRESS2);
       }
     }
     
     if(form.G_APPEAL.type != "hidden"){
   
       if(IsLength(form.G_APPEAL.value, 0, 500, "組織アピール")){
         return errProc(form.G_APPEAL);
       }
   
     }
     
     if(form.G_APPEAL.value != ""){
       form.G_O_APPEAL.value = "1";
     }else{
       form.G_O_APPEAL.value = "0";
     }
     
     if(form.G_KEYWORD.type != "hidden"){
       if(IsLength(form.G_KEYWORD.value, 0, 500, "事務局検索用企業コメント")){
         return errProc(form.G_KEYWORD);
       }
     }
     
     if(form.G_IMG.value != ""){
       form.G_O_IMG.value = "1";
     }else{
       form.G_O_IMG.value = "0";
     }
     
     if(form.G_LOGO.value != ""){
       form.G_O_LOGO.value = "1";
     }else{
       form.G_O_LOGO.value = "0";
     }
     
     if(form.G_HOGEN_CODE.type != "hidden"){
       if(IsLength(form.G_HOGEN_CODE.value, 0, 6, "法源コード")){
         return errProc(form.G_HOGEN_CODE);
       }
       if(IsNarrow(form.G_HOGEN_CODE.value, "法源コード")){
         return errProc(form.G_HOGEN_CODE);
       }
     }
     
     if(form.G_BIKOU1 == true){
       if(form.G_BIKOU1.type == "text"){
         if(IsLength(form.G_BIKOU1.value, 0, 2000, "組織自由項目１")){
           return errProc(form.G_BIKOU1);
         }
       }
     }
     if(form.G_BIKOU2 == true){
       if(form.G_BIKOU2.type == "text"){
         if(IsLength(form.G_BIKOU2.value, 0, 2000, "組織自由項目２")){
           return errProc(form.G_BIKOU2);
         }
       }
     }
     if(form.G_BIKOU3 == true){
       if(form.G_BIKOU3.type == "text"){
         if(IsLength(form.G_BIKOU3.value, 0, 2000, "組織自由項目３")){
           return errProc(form.G_BIKOU3);
         }
       }
     }
     if(form.G_BIKOU4 == true){
       if(form.G_BIKOU4.type == "text"){
         if(IsLength(form.G_BIKOU4.value, 0, 2000, "組織自由項目４")){
           return errProc(form.G_BIKOU4);
         }
       }
     }
     if(form.G_BIKOU5 == true){
       if(form.G_BIKOU5.type == "text"){
         if(IsLength(form.G_BIKOU5.value, 0, 2000, "組織自由項目５")){
           return errProc(form.G_BIKOU5);
         }
       }
     }
     if(form.G_BIKOU6 == true){
       if(form.G_BIKOU6.type == "text"){
         if(IsLength(form.G_BIKOU6.value, 0, 2000, "組織自由項目６")){
           return errProc(form.G_BIKOU6);
         }
       }
     }
     if(form.G_BIKOU7 == true){
       if(form.G_BIKOU7.type == "text"){
         if(IsLength(form.G_BIKOU7.value, 0, 2000, "組織自由項目７")){
           return errProc(form.G_BIKOU7);
         }
       }
     }
     if(form.G_BIKOU8 == true){
       if(form.G_BIKOU8.type == "text"){
         if(IsLength(form.G_BIKOU8.value, 0, 2000, "組織自由項目８")){
           return errProc(form.G_BIKOU8);
         }
       }
     }
     if(form.G_BIKOU9 == true){
       if(form.G_BIKOU9.type == "text"){
         if(IsLength(form.G_BIKOU9.value, 0, 2000, "組織自由項目９")){
           return errProc(form.G_BIKOU9);
         }
       }
     }
     if(form.G_BIKOU10 == true){
       if(form.G_BIKOU10.type == "text"){
         if(IsLength(form.G_BIKOU10.value, 0, 2000, "組織自由項目１０")){
           return errProc(form.G_BIKOU10);
         }
       }
     }
     if(form.G_BIKOU11 == true){
       if(form.G_BIKOU11.type == "text"){
         if(IsLength(form.G_BIKOU11.value, 0, 2000, "組織自由項目１１")){
           return errProc(form.G_BIKOU11);
         }
       }
     }
     if(form.G_BIKOU12 == true){
       if(form.G_BIKOU12.type == "text"){
         if(IsLength(form.G_BIKOU12.value, 0, 2000, "組織自由項目１２")){
           return errProc(form.G_BIKOU12);
         }
       }
     }
     if(form.G_BIKOU13 == true){
       if(form.G_BIKOU13.type == "text"){
         if(IsLength(form.G_BIKOU13.value, 0, 2000, "組織自由項目１３")){
           return errProc(form.G_BIKOU13);
         }
       }
     }
     if(form.G_BIKOU14 == true){
       if(form.G_BIKOU14.type == "text"){
         if(IsLength(form.G_BIKOU14.value, 0, 2000, "組織自由項目１４")){
           return errProc(form.G_BIKOU14);
         }
       }
     }
     if(form.G_BIKOU15 == true){
       if(form.G_BIKOU15.type == "text"){
         if(IsLength(form.G_BIKOU15.value, 0, 2000, "組織自由項目１５")){
           return errProc(form.G_BIKOU15);
         }
       }
     }
   
     if(form.G_BIKOU16 == true){
       if(form.G_BIKOU16.type == "text"){
         if(IsLength(form.G_BIKOU16.value, 0, 2000, "組織自由項目１６")){
           return errProc(form.G_BIKOU16);
         }
       }
     }
     if(form.G_BIKOU17 == true){
       if(form.G_BIKOU17.type == "text"){
         if(IsLength(form.G_BIKOU17.value, 0, 2000, "組織自由項目１７")){
           return errProc(form.G_BIKOU17);
         }
       }
     }
     if(form.G_BIKOU18 == true){
       if(form.G_BIKOU18.type == "text"){
         if(IsLength(form.G_BIKOU18.value, 0, 2000, "組織自由項目１８")){
           return errProc(form.G_BIKOU18);
         }
       }
     }
     if(form.G_BIKOU19 == true){
       if(form.G_BIKOU19.type == "text"){
         if(IsLength(form.G_BIKOU19.value, 0, 2000, "組織自由項目１９")){
           return errProc(form.G_BIKOU19);
         }
       }
     }
     if(form.G_BIKOU20 == true){
       if(form.G_BIKOU20.type == "text"){
         if(IsLength(form.G_BIKOU20.value, 0, 2000, "組織自由項目２０")){
           return errProc(form.G_BIKOU20);
         }
       }
     }
     if(form.G_BIKOU21 == true){
       if(form.G_BIKOU21.type == "text"){
         if(IsLength(form.G_BIKOU21.value, 0, 2000, "組織自由項目２１")){
           return errProc(form.G_BIKOU21);
         }
       }
     }
     if(form.G_BIKOU22 == true){
       if(form.G_BIKOU22.type == "text"){
         if(IsLength(form.G_BIKOU22.value, 0, 2000, "組織自由項目２２")){
           return errProc(form.G_BIKOU22);
         }
       }
     }
     if(form.G_BIKOU23 == true){
       if(form.G_BIKOU23.type == "text"){
         if(IsLength(form.G_BIKOU23.value, 0, 2000, "組織自由項目２３")){
           return errProc(form.G_BIKOU23);
         }
       }
     }
     if(form.G_BIKOU24 == true){
       if(form.G_BIKOU24.type == "text"){
         if(IsLength(form.G_BIKOU24.value, 0, 2000, "組織自由項目２４")){
           return errProc(form.G_BIKOU24);
         }
       }
     }
     if(form.G_BIKOU25 == true){
       if(form.G_BIKOU25.type == "text"){
         if(IsLength(form.G_BIKOU25.value, 0, 2000, "組織自由項目２５")){
           return errProc(form.G_BIKOU25);
         }
       }
     }
     if(form.G_BIKOU26 == true){
       if(form.G_BIKOU26.type == "text"){
         if(IsLength(form.G_BIKOU26.value, 0, 2000, "組織自由項目２６")){
           return errProc(form.G_BIKOU26);
         }
       }
     }
     if(form.G_BIKOU27 == true){
       if(form.G_BIKOU27.type == "text"){
         if(IsLength(form.G_BIKOU27.value, 0, 2000, "組織自由項目２７")){
           return errProc(form.G_BIKOU27);
         }
       }
     }
     if(form.G_BIKOU28 == true){
       if(form.G_BIKOU28.type == "text"){
         if(IsLength(form.G_BIKOU28.value, 0, 2000, "組織自由項目２８")){
           return errProc(form.G_BIKOU28);
         }
       }
     }
     if(form.G_BIKOU29 == true){
       if(form.G_BIKOU29.type == "text"){
         if(IsLength(form.G_BIKOU29.value, 0, 2000, "組織自由項目２９")){
           return errProc(form.G_BIKOU29);
         }
       }
     }
     if(form.G_BIKOU30 == true){
       if(form.G_BIKOU30.type == "text"){
         if(IsLength(form.G_BIKOU30.value, 0, 2000, "組織自由項目３０")){
           return errProc(form.G_BIKOU30);
         }
       }
     }
   
     
     if(form.G_WORKFORCE != undefined){
       if(form.G_WORKFORCE.type != "hidden"){
         if(IsLength(form.G_WORKFORCE.value, 0, 5, "従業員数")){
           return errProc(form.G_WORKFORCE);
         }
         if(IsNarrowNum(form.G_WORKFORCE.value, "従業員数")){
           return errProc(form.G_WORKFORCE);
         }
       }
     }
     
     if(form.G_PARTTIME != undefined){
       if(form.G_PARTTIME.type != "hidden"){
         if(IsLength(form.G_PARTTIME.value, 0, 5, "パート・アルバイト数")){
           return errProc(form.G_PARTTIME);
         }
         if(IsNarrowNum(form.G_PARTTIME.value, "パート・アルバイト数")){
           return errProc(form.G_PARTTIME);
         }
       }
     }
     
     if(form.G_ANNVAL_BUSINESS != undefined){
       if(form.G_ANNVAL_BUSINESS.type != "hidden"){
         if(IsLength(form.G_ANNVAL_BUSINESS.value, 0, 14, "年商")){
           return errProc(form.G_ANNVAL_BUSINESS);
         }
         if(IsNarrowNum(form.G_ANNVAL_BUSINESS.value, "年商")){
           return errProc(form.G_ANNVAL_BUSINESS);
         }
       }
     }
     
     if(form.G_LICENSE != undefined){
       if(form.G_LICENSE.type != "hidden"){
         if(IsLength(form.G_LICENSE.value, 0, 100, "資格・免許類")){
           return errProc(form.G_LICENSE);
         }
       }
     }
     
     if(form.G_PATENT != undefined){
       if(form.G_PATENT.type != "hidden"){
         if(IsLength(form.G_PATENT.value, 0, 100, "特許関係")){
           return errProc(form.G_PATENT);
         }
       }
     }
     
     if(form.G_HOLD_CAR != undefined){
       if(form.G_HOLD_CAR.type != "hidden"){
         if(IsLength(form.G_HOLD_CAR.value, 0, 4, "車台数")){
           return errProc(form.G_HOLD_CAR);
         }
         if(IsNarrowNum(form.G_HOLD_CAR.value, "車台数")){
           return errProc(form.G_HOLD_CAR);
         }
       }
     }
     
     if(form.G_E_TAX != undefined){
       if(form.G_E_TAX.type != "hidden"){
         if(IsLength(form.G_E_TAX.value, 0, 100, "e_Tax")){
           return errProc(form.G_E_TAX);
         }
       }
     }
     
     if(form.G_ADD_MARKETING16 != undefined){
       if(form.G_ADD_MARKETING16.type != "hidden"){
         if(IsLength(form.G_ADD_MARKETING16.value, 0, 50, "マーケティング自由項目１６")){
           return errProc(form.G_ADD_MARKETING16);
         }
       }
     }
     if(form.G_ADD_MARKETING17 != undefined){
       if(form.G_ADD_MARKETING17.type != "hidden"){
         if(IsLength(form.G_ADD_MARKETING17.value, 0, 50, "マーケティング自由項目１７")){
           return errProc(form.G_ADD_MARKETING17);
         }
       }
     }
     if(form.G_ADD_MARKETING18 != undefined){
       if(form.G_ADD_MARKETING18.type != "hidden"){
         if(IsLength(form.G_ADD_MARKETING18.value, 0, 50, "マーケティング自由項目１８")){
           return errProc(form.G_ADD_MARKETING18);
         }
       }
     }
     if(form.G_ADD_MARKETING19 != undefined){
       if(form.G_ADD_MARKETING19.type != "hidden"){
         if(IsLength(form.G_ADD_MARKETING19.value, 0, 50, "マーケティング自由項目１９")){
           return errProc(form.G_ADD_MARKETING19);
         }
       }
     }
     if(form.G_ADD_MARKETING20 != undefined){
       if(form.G_ADD_MARKETING20.type != "hidden"){
         if(IsLength(form.G_ADD_MARKETING20.value, 0, 50, "マーケティング自由項目２０")){
           return errProc(form.G_ADD_MARKETING20);
         }
       }
     }
     var groupRegCheck = true;
     
     if(form.NoneRMf != undefined){
       if(form.NoneRMf.value == "1"){
         groupRegCheck = false;
       }
     }
     if(groupRegCheck == true){
       if(form.G_G_ID.type != "hidden"){
         
         ifmSetUrl = "../rs/commonRsSearch.asp?fncName=CheckGid&gid=" + form.G_G_ID.value;
         ifmGetData.location.href = ifmSetUrl;
       }
     }
     return true;
   }
   
   function retSearchVal_CheckGid(retSearchVal){
     var form = document.mainForm;
     if(retSearchVal != 0){
       alert("入力された組織IDは既に使用されています。\n別の組織IDを使用してください。");
       return errProc(form.G_G_ID);
     }
   }
   
   
   function sdCheckInputData(bMdReg){
     var form = document.mainForm;
     
     if(form.S_AFFILIATION_NAME.type != "hidden"){
       if(IsLengthB(form.S_AFFILIATION_NAME.value, 0, 100, "所属")){
         return errProc(form.S_AFFILIATION_NAME);
       }
     }
     
     if(form.S_OFFICIAL_POSITION.type != "hidden"){
       if(IsLengthB(form.S_OFFICIAL_POSITION.value, 0, 100, "役職")){
         return errProc(form.S_OFFICIAL_POSITION);
       }
     }
     
     if(bMdReg){
       if(form.m_chg == "0"){
         if(form.AFFILIATION_NAME2.type != "hidden"){
           if(IsLengthB(form.AFFILIATION_NAME2.value, 0, 100, "所属")){
             return errProc(form.AFFILIATION_NAME2);
           }
         }
         if(form.OFFICIAL_POSITION2.type != "hidden"){
           if(IsLengthB(form.OFFICIAL_POSITION2.value, 0, 100, "役職")){
             return errProc(form.OFFICIAL_POSITION2);
           }
         }
       }
     }
     
     if(form.S_X_COMMENT != undefined){
       if(form.S_X_COMMENT.type != "hidden"){
         if(IsLength(form.S_X_COMMENT.value, 0, 500, "コメント")){
           return errProc(form.S_X_COMMENT);
         }
       }
     }
     return true;
   }
   
   
   function mdCheckInputData(bMDReg){
     var form = document.mainForm;
     
     if(form.M_LG_G_ID != undefined){
       if(form.M_LG_G_ID.type != "hidden"){
         if(form.M_LG_G_ID.value == ""){
           alert("下部組織を指定してください。");
           return errProc(form.M_LG_G_ID);
         }
         if(IsNarrow(form.M_LG_G_ID.value, "下部組織ID")){
           return errProc(form.M_LG_G_ID);
         }
       }
     }
     
     if(form.M_ADMISSION_DATE_Y != undefined){
       if(form.M_ADMISSION_DATE_Y.type != "hidden"){
         if((form.M_ADMISSION_DATE_Y.value != "") || (form.M_ADMISSION_DATE_M.value != "") || (form.M_ADMISSION_DATE_D.value != "")){
           if(IsDateImp(form.m_admImperialM.value, form.M_ADMISSION_DATE_Y, form.M_ADMISSION_DATE_M, form.M_ADMISSION_DATE_D, "入会年月日")){
             return false;
           }
           form.M_ADMISSION_DATE.value = MakeYMD(form.m_admImperialM.value, form.M_ADMISSION_DATE_Y.value, form.M_ADMISSION_DATE_M.value, form.M_ADMISSION_DATE_D.value);
         } else {
           form.M_ADMISSION_DATE.value = "";
         }
       }
     }
     
     if(form.M_WITHDRAWAL_DATE_Y != undefined){
       if(form.M_WITHDRAWAL_DATE_Y.type != "hidden"){
         if((form.M_WITHDRAWAL_DATE_Y.value != "") || (form.M_WITHDRAWAL_DATE_M.value != "") || (form.M_WITHDRAWAL_DATE_D.value != "")){
           if(IsDateImp(form.m_witImperialM.value, form.M_WITHDRAWAL_DATE_Y, form.M_WITHDRAWAL_DATE_M, form.M_WITHDRAWAL_DATE_D, "退会年月日")){
             return false;
           }
           form.M_WITHDRAWAL_DATE.value = MakeYMD(form.m_witImperialM.value, form.M_WITHDRAWAL_DATE_Y.value, form.M_WITHDRAWAL_DATE_M.value, form.M_WITHDRAWAL_DATE_D.value);
         } else {
           form.M_WITHDRAWAL_DATE.value = "";
         }
       }
     }
     
     if(form.M_CHANGE_DATE_Y != undefined){
       if(form.M_CHANGE_DATE_Y.type != "hidden"){
         if((form.M_CHANGE_DATE_Y.value != "") || (form.M_CHANGE_DATE_M.value != "") || (form.M_CHANGE_DATE_D.value != "")){
           if(IsDateImp(form.m_chaImperialM.value, form.M_CHANGE_DATE_Y, form.M_CHANGE_DATE_M, form.M_CHANGE_DATE_D, "異動年月日")){
             return false;
           }
           form.M_CHANGE_DATE.value = MakeYMD(form.m_chaImperialM.value, form.M_CHANGE_DATE_Y.value, form.M_CHANGE_DATE_M.value, form.M_CHANGE_DATE_D.value);
         } else {
           form.M_CHANGE_DATE.value = "";
         }
       }
     }
     
     if(form.M_CHANGE_DATE_Y != undefined){
       if(form.M_CHANGE_DATE_Y.type != "hidden"){
         if(IsLength(form.M_CHANGE_REASON.value, 0, 50, "移動理由") != 0){
           return errProc(form.M_CHANGE_REASON);
         }
       }
     }
     
     if(form.M_CLAIM_CLASS != undefined && form.M_FEE_RANK != undefined){
       if(form.M_CLAIM_CLASS.type != "hidden" && form.M_FEE_RANK.type != "hidden"){
         if(form.M_CLAIM_CLASS.value != ""){
           if(form.M_FEE_RANK.value == ""){
             alert("会費ランクを入力して下さい。");
             return errProc(form.M_FEE_RANK);
           }
         }
       }
     }
     
     if(form.M_CLAIM_CLASS != undefined && form.M_CLAIM_CYCLE != undefined){
       if(form.M_CLAIM_CLASS.type != "hidden" && form.M_CLAIM_CYCLE.type != "hidden"){
         if(form.M_CLAIM_CLASS.value != ""){
           if(form.M_CLAIM_CYCLE.value == ""){
             alert("請求サイクルを入力して下さい。");
             return errProc(form.M_CLAIM_CYCLE);
           }
         }
       }
     }
     
     if(form.M_FEE_MEMO != undefined){
       if(form.M_FEE_MEMO.type != "hidden"){
         if(IsLengthB(form.M_FEE_MEMO.value, 0, 100, "会費メモ") != 0){
           return errProc(form.M_FEE_MEMO);
         }
       }
     }
     
     if(form.M_BANK_CODE != undefined){
       if(form.M_BANK_CODE.type != "hidden"){
         if(form.M_CLAIM_CLASS != undefined){
           if(form.M_CLAIM_CLASS.type != "hidden"){
             if(form.M_CLAIM_CLASS.value == "0"){
               if(form.M_BANK_CODE.value == ""){
                 if(form.M_BANK_CODE.disabled == true){
                   alert("銀行コードが未登録です。\n請求先指定を変更、または\n指定先の銀行コードを入力してください。");
                   return errProc(form.M_CLAIMDEST);
                 }else{
                   alert("銀行コードを入力して下さい。");
                   return errProc(form.M_BANK_CODE);
                 }
               }
             }
           }
         }
       }
     }
     
     if(form.M_BRANCH_CODE != undefined){
       if(form.M_BRANCH_CODE.type != "hidden"){
         if(form.M_CLAIM_CLASS != undefined){
           if(form.M_CLAIM_CLASS.type != "hidden"){
             if(form.M_CLAIM_CLASS.value == "0"){
               if(form.M_BRANCH_CODE.value == ""){
                 if(form.M_BRANCH_CODE.disabled == true){
                   if(form.M_BANK_CODE != undefined){
                     //ゆうちょ以外
                     if (form.M_BANK_CODE.value != "9900"){
                       alert("支店コードが未登録です。\n請求先指定を変更、または\n指定先の支店コードを入力してください。");
                       return errProc(form.M_CLAIMDEST);
                     }
                   }
                 }else{
                   if(form.M_BANK_CODE != undefined){
                     //ゆうちょ以外
                     if (form.M_BANK_CODE.value != "9900"){
                       alert("支店コードを入力して下さい。");
                       return errProc(form.M_BRANCH_CODE);
                     }
                   }
                 }
               }
             }
           }
         }
       }
     }
     
     if(form.M_ACCAUNT_NUMBER != undefined){
       if(form.M_ACCAUNT_NUMBER.type != "hidden"){
         if(form.M_CLAIM_CLASS != undefined){
           if(form.M_CLAIM_CLASS.type != "hidden"){
             if(form.M_CLAIM_CLASS.value == "0"){
               if(form.M_BANK_CODE.value != "9900"){
                 if(IsNull(form.M_ACCAUNT_NUMBER.value, "口座番号")){
                   return errProc(form.M_ACCAUNT_NUMBER);
                 }
               }
             }
           }
         }
         if(IsLength(form.M_ACCAUNT_NUMBER.value, 0, 7, "口座番号")){
           return errProc(form.M_ACCAUNT_NUMBER);
         }
         if(IsNarrowNum(form.M_ACCAUNT_NUMBER.value, "口座番号")){
           return errProc(form.M_ACCAUNT_NUMBER);
         }
       }
     }
     
     if(form.M_ACCAUNT_NAME != undefined){
       if(form.M_ACCAUNT_NAME.type != "hidden"){
         if(form.M_CLAIM_CLASS != undefined){
           if(form.M_CLAIM_CLASS.type != "hidden"){
             if(form.M_CLAIM_CLASS.value == "0"){
               if(IsNull(form.M_ACCAUNT_NAME.value, "口座名義")){
                 return errProc(form.M_ACCAUNT_NAME);
               }
             }
           }
         }
         if(IsLength(form.M_ACCAUNT_NAME.value, 0, 30, "口座名義")){
           return errProc(form.M_ACCAUNT_NAME);
         }
         if(IsNarrowAccaunt(form.M_ACCAUNT_NAME.value, "口座名義")){
           return errProc(form.M_ACCAUNT_NAME);
         }
       }
     }
     
     if(form.M_CUST_NO != undefined){
       if(form.M_CUST_NO.type != "hidden"){
         if(IsLength(form.M_CUST_NO.value, 0, 12, "顧客番号")){
           return errProc(form.M_CUST_NO);
         }
         if(IsNarrowNum(form.M_CUST_NO.value, "顧客番号")){
           return errProc(form.M_CUST_NO);
         }
       }
     }
     
     if(form.M_SAVINGS_CODE != undefined){
       if(form.M_SAVINGS_CODE.type != "hidden"){
         if(form.M_CLAIM_CLASS != undefined){
           if(form.M_CLAIM_CLASS.type != "hidden"){
             if(form.M_CLAIM_CLASS.value == "0"){
               if(form.M_BANK_CODE.value == "9900"){
                 if(IsNull(form.M_SAVINGS_CODE.value, "貯金記号")){
                   return errProc(form.M_SAVINGS_CODE);
                 }
               }
             }
           }
         }
         if(IsLengthF(form.M_SAVINGS_CODE.value, 5, "貯金記号")){
           return errProc(form.M_SAVINGS_CODE);
         }
         if(IsLength(form.M_SAVINGS_CODE.value, 0, 5, "貯金記号")){
           return errProc(form.M_SAVINGS_CODE);
         }
         if(IsNarrowNum(form.M_SAVINGS_CODE.value, "貯金記号")){
           return errProc(form.M_SAVINGS_CODE);
         }
       }
     }
     
     if(form.M_SAVINGS_NUMBER != undefined){
       if(form.M_SAVINGS_NUMBER.type != "hidden"){
         if(form.M_CLAIM_CLASS != undefined){
           if(form.M_CLAIM_CLASS.type != "hidden"){
             if(form.M_CLAIM_CLASS.value == "0"){
               if(form.M_BANK_CODE.value == "9900"){
                 if(IsNull(form.M_SAVINGS_NUMBER.value, "貯金番号")){
                   return errProc(form.M_SAVINGS_NUMBER);
                 }
               }
             }
           }
         }
         if(IsLengthF(form.M_SAVINGS_NUMBER.value, 8, "貯金番号")){
           return errProc(form.M_SAVINGS_NUMBER);
         }
         if(IsLength(form.M_SAVINGS_NUMBER.value, 0, 8, "貯金番号")){
           return errProc(form.M_SAVINGS_NUMBER);
         }
         if(IsNarrowNum(form.M_SAVINGS_NUMBER.value, "貯金番号")){
           return errProc(form.M_SAVINGS_NUMBER);
         }
       }
     }
     
     if(form.M_CO_G_NAME != undefined){
       if(form.M_CO_G_NAME.type != "hidden"){
         if(IsLengthB(form.M_CO_G_NAME.value, 0, 200, "連絡先組織名")){
           return errProc(form.M_CO_G_NAME);
         }
       }
     }
     
     if(form.M_CO_G_KANA != undefined){
       if(form.M_CO_G_KANA.type != "hidden"){
         if(IsLengthB(form.M_CO_G_KANA.value, 0, 200, "連絡先組織名フリガナ")){
           return errProc(form.M_CO_G_KANA);
         }
       }
     }
     
     if(form.M_CO_C_NAME != undefined){
       if(form.M_CO_C_NAME.type != "hidden"){
         if(IsLengthB(form.M_CO_C_NAME.value, 0, 200, "連絡先名称")){
           return errProc(form.M_CO_C_NAME);
         }
       }
     }
     
     if(form.M_CO_C_KANA != undefined){
       if(form.M_CO_C_KANA.type != "hidden"){
         if(IsLengthB(form.M_CO_C_KANA.value, 0, 200, "連絡先フリガナ")){
           return errProc(form.M_CO_C_KANA);
         }
       }
     }
     
     if(form.M_CO_C_EMAIL != undefined){
       if(form.M_CO_C_EMAIL.type != "hidden"){
         if(IsLength(form.M_CO_C_EMAIL.value, 0, 100, "連絡先E-MAIL")){
           return errProc(form.M_CO_C_EMAIL);
         }
         if(IsNarrowPlus3(form.M_CO_C_EMAIL.value, "連絡先E-MAIL")){
           return errProc(form.M_CO_C_EMAIL);
         }
         if(isMail(form.M_CO_C_EMAIL.value, "連絡先E-MAIL")){
           return errProc(form.M_CO_C_EMAIL);
         }
       }
     }
   
     if(form.M_CO_C_EMAIL2 != undefined){
       if(form.M_CO_C_EMAIL2.type != "hidden"){
         if(IsLength(form.M_CO_C_EMAIL2.value, 0, 100, "連絡先E-MAIL再入力")){
           return errProc(form.M_CO_C_EMAIL2);
         }
       }
       if(IsNarrowPlus3(form.M_CO_C_EMAIL2.value, "連絡先E-MAIL再入力")){
         return errProc(form.M_CO_C_EMAIL2);
       }
       if(isMail(form.M_CO_C_EMAIL2.value, "連絡先E-MAIL再入力")){
         return errProc(form.M_CO_C_EMAIL2);
       }
       if(form.M_CO_C_EMAIL.value != form.M_CO_C_EMAIL2.value){
         alert("連絡先E-MAILの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
         form.M_CO_C_EMAIL2.value = "";
         form.M_CO_C_EMAIL2.focus();
         return false;
       }
     }
   
     if(form.M_CO_C_CC_EMAIL != undefined){
       if(form.M_CO_C_CC_EMAIL.type != "hidden"){
         if(IsLength(form.M_CO_C_CC_EMAIL.value, 0, 100, "連絡先追加送信先E-MAIL")){
           return errProc(form.M_CO_C_CC_EMAIL);
         }
         if(IsNarrowPlus3(form.M_CO_C_CC_EMAIL.value, "連絡先追加送信先E-MAIL")){
           return errProc(form.M_CO_C_CC_EMAIL);
         }
         if(isMail(form.M_CO_C_CC_EMAIL.value, "連絡先追加送信先E-MAIL")){
           return errProc(form.M_CO_C_CC_EMAIL);
         }
       }
     }
     
     if(form.M_CONTACTDEST != undefined && form.M_CO_C_TEL_1 != undefined){
       if(form.M_CO_C_TEL_1.type != "hidden"){
         if(form.M_CO_C_TEL_1.value != "" || form.M_CO_C_TEL_2.value != "" || form.M_CO_C_TEL_3.value != ""){
           if(form.M_CO_C_TEL_1.value == ""){
             alert("連絡先TEL\nを登録する場合は\n全ての欄を入力して下さい");
             return errProc(form.M_CO_C_TEL_1);
           }
           if(form.M_CO_C_TEL_2.value == ""){
             alert("連絡先TEL\nを登録する場合は\n全ての欄を入力して下さい");
             return errProc(form.M_CO_C_TEL_2);
           }
           if(form.M_CO_C_TEL_3.value == ""){
             alert("連絡先TEL\nを登録する場合は\n全ての欄を入力して下さい");
             return errProc(form.M_CO_C_TEL_3);
           }
           if(IsNarrowTelNum(form.M_CO_C_TEL_1.value, "連絡先TEL")){
             return errProc(form.M_CO_C_TEL_1);
           }
           if(IsNarrowTelNum(form.M_CO_C_TEL_2.value, "連絡先TEL")){
             return errProc(form.M_CO_C_TEL_2);
           }
           if(IsNarrowTelNum(form.M_CO_C_TEL_3.value, "連絡先TEL")){
             return errProc(form.M_CO_C_TEL_3);
           }
           form.M_CO_C_TEL.value = form.M_CO_C_TEL_1.value + "-" + form.M_CO_C_TEL_2.value + "-" + form.M_CO_C_TEL_3.value;
           if(IsLength(form.M_CO_C_TEL.value, 0, 20, "連絡先TEL")){
             form.M_CO_C_TEL_1.focus();
             return false;
           }
         } else {
           form.M_CO_C_TEL.value = "";
         }
       }
     }
     
     if(form.M_CO_C_FAX_1 != undefined){
       if(form.M_CO_C_FAX_1.type != "hidden"){
         if(form.M_CO_C_FAX_1.value != "" || form.M_CO_C_FAX_2.value != "" || form.M_CO_C_FAX_3.value != ""){
           if(form.M_CO_C_FAX_1.value == ""){
             alert("連絡先FAX\nを登録する場合は\n全ての欄を入力して下さい");
             return errProc(form.M_CO_C_FAX_1);
           }
           if(form.M_CO_C_FAX_2.value == ""){
             alert("連絡先FAX\nを登録する場合は\n全ての欄を入力して下さい");
             return errProc(form.M_CO_C_FAX_2);
           }
           if(form.M_CO_C_FAX_3.value == ""){
             alert("連絡先FAX\nを登録する場合は\n全ての欄を入力して下さい");
             return errProc(form.M_CO_C_FAX_3);
           }
           if(IsNarrowTelNum(form.M_CO_C_FAX_1.value, "連絡先FAX")){
             return errProc(form.M_CO_C_FAX_1);
           }
           if(IsNarrowTelNum(form.M_CO_C_FAX_2.value, "連絡先FAX")){
             return errProc(form.M_CO_C_FAX_2);
           }
           if(IsNarrowTelNum(form.M_CO_C_FAX_3.value, "連絡先FAX")){
             return errProc(form.M_CO_C_FAX_3);
           }
           form.M_CO_C_FAX.value = form.M_CO_C_FAX_1.value + "-" + form.M_CO_C_FAX_2.value + "-" + form.M_CO_C_FAX_3.value;
           if(IsLength(form.M_CO_C_FAX.value, 0, 20, "連絡先FAX")){
             form.M_CO_C_FAX_1.value = form.M_CO_C_FAX_2.value = form.M_CO_C_FAX_3.value = ""
             form.M_CO_C_FAX_1.focus();
             return false;
           }
         } else {
           form.M_CO_C_FAX.value = "";
         }
       }
     }
     
     if(form.M_CONTACTDEST != undefined && form.M_CO_C_POST_u != undefined){
       if(form.M_CO_C_POST_u.type != "hidden"){
         if(form.M_CO_C_POST_u.value != "" || form.M_CO_C_POST_l.value != ""){
           if(form.M_CO_C_POST_u.value == ""){
             alert("連絡先〒\nを登録する場合は\n全ての欄を入力して下さい");
             return errProc(form.M_CO_C_POST_u);
           }
           if(IsLength(form.M_CO_C_POST_u.value, 3, 3, "連絡先〒(上３桁)")){
             return errProc(form.M_CO_C_POST_u);
           }
           if(IsNarrowNum(form.M_CO_C_POST_u.value, "連絡先〒")){
             return errProc(form.M_CO_C_POST_u);
           }
           if(form.M_CO_C_POST_l.value == ""){
             alert("連絡先〒\nを登録する場合は\n全ての欄を入力して下さい");
             return errProc(form.M_CO_C_POST_l);
           }
           if(IsLength(form.M_CO_C_POST_l.value, 4, 4, "連絡先〒(下４桁)")){
             return errProc(form.M_CO_C_POST_l);
           }
           if(IsNarrowNum(form.M_CO_C_POST_l.value, "連絡先〒")){
             return errProc(form.M_CO_C_POST_l);
           }
           form.M_CO_C_POST.value = form.M_CO_C_POST_u.value + "-" + form.M_CO_C_POST_l.value;
         } else {
           form.M_CO_C_POST.value = "";
         }
       }
     }
     
     if(form.M_CO_C_ADDRESS != undefined){
       if(form.M_CO_C_ADDRESS.type != "hidden"){
         if(IsLengthB(form.M_CO_C_ADDRESS.value, 0, 500, "連絡先住所１")){
           return errProc(form.M_CO_C_ADDRESS);
         }
       }
     }
     
     if(form.M_CO_C_ADDRESS2 != undefined){
       if(form.M_CO_C_ADDRESS2.type != "hidden"){
         if(IsLengthB(form.M_CO_C_ADDRESS2.value, 0, 100, "連絡先住所２")){
           return errProc(form.M_CO_C_ADDRESS2);
         }
       }
     }
     
     if(form.M_CL_G_NAME != undefined){
       if(form.M_CL_G_NAME.type != "hidden"){
         if(IsLengthB(form.M_CL_G_NAME.value, 0, 200, "請求先組織名")){
           return errProc(form.M_CL_G_NAME);
         }
       }
     }
     
     if(form.M_CL_G_KANA != undefined){
       if(form.M_CL_G_KANA.type != "hidden"){
         if(IsLengthB(form.M_CL_G_KANA.value, 0, 200, "請求先組織名フリガナ")){
           return errProc(form.M_CL_G_KANA);
         }
       }
     }
     
     if(form.M_CL_C_NAME != undefined){
       if(form.M_CL_C_NAME.type != "hidden"){
         if(IsLengthB(form.M_CL_C_NAME.value, 0, 200, "請求先名称")){
           return errProc(form.M_CL_C_NAME);
         }
       }
     }
     
     if(form.M_CL_C_KANA != undefined){
       if(form.M_CL_C_KANA.type != "hidden"){
         if(IsLengthB(form.M_CL_C_KANA.value, 0, 200, "請求先フリガナ")){
           return errProc(form.M_CL_C_KANA);
         }
       }
     }
     
     if(form.M_CL_C_EMAIL != undefined){
       if(form.M_CL_C_EMAIL.type != "hidden"){
         if(IsLength(form.M_CL_C_EMAIL.value, 0, 100, "請求先E-MAIL")){
           return errProc(form.M_CL_C_EMAIL);
         }
         if(IsNarrowPlus3(form.M_CL_C_EMAIL.value, "請求先E-MAIL")){
           return errProc(form.M_CL_C_EMAIL);
         }
         if(isMail(form.M_CL_C_EMAIL.value, "請求先E-MAIL")){
           return errProc(form.M_CL_C_EMAIL);
         }
       }
     }
   
     if(form.M_CL_C_EMAIL2 != undefined){
       if(form.M_CL_C_EMAIL2.type != "hidden"){
         if(IsLength(form.M_CL_C_EMAIL2.value, 0, 100, "請求先E-MAIL再入力")){
           return errProc(form.M_CL_C_EMAIL2);
         }
       }
       if(IsNarrowPlus3(form.M_CL_C_EMAIL2.value, "請求先E-MAIL再入力")){
         return errProc(form.M_CL_C_EMAIL2);
       }
       if(isMail(form.M_CL_C_EMAIL2.value, "請求先E-MAIL再入力")){
         return errProc(form.M_CL_C_EMAIL2);
       }
       if(form.M_CL_C_EMAIL.value != form.M_CL_C_EMAIL2.value){
         alert("請求先E-MAILの内容と確認入力の内容が一致しません。\nもう一度入力して下さい");
         form.M_CL_C_EMAIL2.value = "";
         form.M_CL_C_EMAIL2.focus();
         return false;
       }
     }
   
     if(form.M_CL_C_CC_EMAIL != undefined){
       if(form.M_CL_C_CC_EMAIL.type != "hidden"){
         if(IsLength(form.M_CL_C_CC_EMAIL.value, 0, 100, "請求先追加送信先E-MAIL")){
           return errProc(form.M_CL_C_CC_EMAIL);
         }
         if(IsNarrowPlus3(form.M_CL_C_CC_EMAIL.value, "請求先追加送信先E-MAIL")){
           return errProc(form.M_CL_C_CC_EMAIL);
         }
         if(isMail(form.M_CL_C_CC_EMAIL.value, "請求先追加送信先E-MAIL")){
           return errProc(form.M_CL_C_CC_EMAIL);
         }
       }
     }
     
     if(form.M_CLAIMDEST != undefined && form.M_CL_C_TEL_1 != undefined){
       if(form.M_CL_C_TEL_1.type != "hidden"){
         if(form.M_CLAIMDEST.value == "2"){
           if(form.M_CL_C_TEL_1.value != "" || form.M_CL_C_TEL_2.value != "" || form.M_CL_C_TEL_3.value != ""){
             if(form.M_CL_C_TEL_1.value == ""){
               alert("請求先TEL\nを登録する場合は\n全ての欄を入力して下さい");
               return errProc(form.M_CL_C_TEL_1);
             }
             if(form.M_CL_C_TEL_2.value == ""){
               alert("請求先TEL\nを登録する場合は\n全ての欄を入力して下さい");
               return errProc(form.M_CL_C_TEL_2);
             }
             if(form.M_CL_C_TEL_3.value == ""){
               alert("請求先TEL\nを登録する場合は\n全ての欄を入力して下さい");
               return errProc(form.M_CL_C_TEL_3);
             }
             if(IsNarrowTelNum(form.M_CL_C_TEL_1.value, "請求先TEL")){
               return errProc(form.M_CL_C_TEL_1);
             }
             if(IsNarrowTelNum(form.M_CL_C_TEL_2.value, "請求先TEL")){
               return errProc(form.M_CL_C_TEL_2);
             }
             if(IsNarrowTelNum(form.M_CL_C_TEL_3.value, "請求先TEL")){
               return errProc(form.M_CL_C_TEL_3);
             }
             form.M_CL_C_TEL.value = form.M_CL_C_TEL_1.value + "-" + form.M_CL_C_TEL_2.value + "-" + form.M_CL_C_TEL_3.value;
             if(IsLength(form.M_CL_C_TEL.value, 0, 20, "請求先TEL")){
               form.M_CL_C_TEL_1.focus();
               return false;
             }
           } else {
             form.M_CL_C_TEL.value = "";
           }
         }
       }
     }
     
     if(form.M_CL_C_FAX_1 != undefined){
       if(form.M_CL_C_FAX_1.type != "hidden"){
         if(form.M_CL_C_FAX_1.value != "" || form.M_CL_C_FAX_2.value != "" || form.M_CL_C_FAX_3.value != ""){
           if(form.M_CL_C_FAX_1.value == ""){
             alert("請求先FAX\nを登録する場合は\n全ての欄を入力して下さい");
             return errProc(form.M_CL_C_FAX_1);
           }
           if(form.M_CL_C_FAX_2.value == ""){
             alert("請求先FAX\nを登録する場合は\n全ての欄を入力して下さい");
             return errProc(form.M_CL_C_FAX_2);
           }
           if(form.M_CL_C_FAX_3.value == ""){
             alert("請求先FAX\nを登録する場合は\n全ての欄を入力して下さい");
             return errProc(form.M_CL_C_FAX_3);
           }
           if(IsNarrowTelNum(form.M_CL_C_FAX_1.value, "請求先FAX")){
             return errProc(form.M_CL_C_FAX_1);
           }
           if(IsNarrowTelNum(form.M_CL_C_FAX_2.value, "請求先FAX")){
             return errProc(form.M_CL_C_FAX_2);
           }
           if(IsNarrowTelNum(form.M_CL_C_FAX_3.value, "請求先FAX")){
             return errProc(form.M_CL_C_FAX_3);
           }
           form.M_CL_C_FAX.value = form.M_CL_C_FAX_1.value + "-" + form.M_CL_C_FAX_2.value + "-" + form.M_CL_C_FAX_3.value;
           if(IsLength(form.M_CL_C_FAX.value, 0, 20, "請求先FAX")){
             form.M_CL_C_FAX_1.value = form.M_CL_C_FAX_2.value = form.M_CL_C_FAX_3.value = "";
             form.M_CL_C_FAX_1.focus();
             return false;
           }
         } else {
           form.M_CL_C_FAX.value = "";
         }
       }
     }
     
     if(form.M_CLAIMDEST != undefined && form.M_CL_C_POST_u != undefined){
       if(form.M_CL_C_POST_u.type != "hidden"){
         if(form.M_CL_C_POST_u.value != "" || form.M_CL_C_POST_l.value != ""){
           if(form.M_CL_C_POST_u.value == ""){
             alert("請求先〒\nを登録する場合は\n全ての欄を入力して下さい");
             return errProc(form.M_CL_C_POST_u);
           }
           if(IsLength(form.M_CL_C_POST_u.value, 3, 3, "請求先〒(上３桁)")){
             return errProc(form.M_CL_C_POST_u);
           }
           if(IsNarrowNum(form.M_CL_C_POST_u.value, "請求先〒")){
             return errProc(form.M_CL_C_POST_u);
           }
           if(form.M_CL_C_POST_l.value == ""){
             alert("請求先〒\nを登録する場合は\n全ての欄を入力して下さい");
             return errProc(form.M_CL_C_POST_l);
           }
           if(IsLength(form.M_CL_C_POST_l.value, 4, 4, "請求先〒(下４桁)")){
             return errProc(form.M_CL_C_POST_l);
           }
           if(IsNarrowNum(form.M_CL_C_POST_l.value, "請求先〒")){
             return errProc(form.M_CL_C_POST_l);
           }
           form.M_CL_C_POST.value = form.M_CL_C_POST_u.value + "-" + form.M_CL_C_POST_l.value;
         } else {
           form.M_CL_C_POST.value = "";
         }
       }
     }
     
     if(form.M_CL_C_ADDRESS != undefined){
       if(form.M_CL_C_ADDRESS.type != "hidden"){
         if(IsLengthB(form.M_CL_C_ADDRESS.value, 0, 500, "請求先住所１")){
           return errProc(form.M_CL_C_ADDRESS);
         }
       }
     }
     
     if(form.M_CL_C_ADDRESS2 != undefined){
       if(form.M_CL_C_ADDRESS2.type != "hidden"){
         if(IsLengthB(form.M_CL_C_ADDRESS2.value, 0, 100, "請求先住所２")){
           return errProc(form.M_CL_C_ADDRESS2);
         }
       }
     }
     
     if(form.M_R_ID != undefined){
       if(form.M_R_ID.type != "hidden"){
         if(IsLength(form.M_R_ID.value, 4, 20, "推薦者個人ID")){
           return errProc(form.M_R_ID);
         }
         if(IsNarrowPlus(form.M_R_ID.value, "推薦者個人ID")){
           return errProc(form.M_R_ID);
         }
       }
     }
     
     if(form.M_X_COMMENT != undefined){
       if(form.M_X_COMMENT.type != "hidden"){
         if(IsLength(form.M_X_COMMENT.value, 0, 500, "コメント")){
           return errProc(form.M_X_COMMENT);
         }
       }
     }
     
     if(form.FAX_TIMEZONE != undefined){
       var fax_timezone;
       if(form.FAX_TIMEZONE.type != "hidden"){
         for(var i = 0; i < form.FAX_TIMEZONE.length; i++){
           if(form.FAX_TIMEZONE[i].checked == true){
             fax_timezone = form.FAX_TIMEZONE[i].value
           }
         }
         if(fax_timezone=="4"){
           if(form.FAX_TIME_FROM_H.value==""||form.FAX_TIME_FROM_N.value==""||form.FAX_TIME_TO_H.value==""||form.FAX_TIME_TO_N.value==""){
             alert("FAX送信時間は必ず入力して下さい。");
             form.FAX_TIME_FROM_H.focus();
             return false;
           }
           form.FAX_TIME_FROM.value=form.FAX_TIME_FROM_H.value + ":" + form.FAX_TIME_FROM_N.value;
           form.FAX_TIME_TO.value=form.FAX_TIME_TO_H.value + ":" + form.FAX_TIME_TO_N.value;
         }else{
           form.FAX_TIME_FROM_H[0].selected;
           form.FAX_TIME_FROM_N[0].selected;
           form.FAX_TIME_TO_H[0].selected;
           form.FAX_TIME_TO_N[0].selected;
           form.FAX_TIME_FROM.value="";
           form.FAX_TIME_TO.value="";
         }
       }
     }
     
     if(form.GM_BIKOU1 != undefined){
       if(form.GM_BIKOU1.type == "text"){
         if(IsLength(form.GM_BIKOU1.value, 0, 2000, "予備項目０１")){
           return errProc(form.GM_BIKOU1);
         }
       }
     }
     if(form.GM_BIKOU2 != undefined){
       if(form.GM_BIKOU2.type == "text"){
         if(IsLength(form.GM_BIKOU2.value, 0, 2000, "会員自由項目２")){
           return errProc(form.GM_BIKOU2);
         }
       }
     }
     if(form.GM_BIKOU3 != undefined){
       if(form.GM_BIKOU3.type == "text"){
         if(IsLength(form.GM_BIKOU3.value, 0, 2000, "予備項目０２")){
           return errProc(form.GM_BIKOU3);
         }
       }
     }
     if(form.GM_BIKOU4 != undefined){
       if(form.GM_BIKOU4.type == "text"){
         if(IsLength(form.GM_BIKOU4.value, 0, 2000, "会員自由項目４")){
           return errProc(form.GM_BIKOU4);
         }
       }
     }
     if(form.GM_BIKOU5 != undefined){
       if(form.GM_BIKOU5.type == "text"){
         if(IsLength(form.GM_BIKOU5.value, 0, 2000, "会員自由項目５")){
           return errProc(form.GM_BIKOU5);
         }
       }
     }
     if(form.GM_BIKOU6 != undefined){
       if(form.GM_BIKOU6.type == "text"){
         if(IsLength(form.GM_BIKOU6.value, 0, 2000, "会員自由項目６")){
           return errProc(form.GM_BIKOU6);
         }
       }
     }
     if(form.GM_BIKOU7 != undefined){
       if(form.GM_BIKOU7.type == "text"){
         if(IsLength(form.GM_BIKOU7.value, 0, 2000, "会員自由項目７")){
           return errProc(form.GM_BIKOU7);
         }
       }
     }
     if(form.GM_BIKOU8 != undefined){
       if(form.GM_BIKOU8.type == "text"){
         if(IsLength(form.GM_BIKOU8.value, 0, 2000, "会員自由項目８")){
           return errProc(form.GM_BIKOU8);
         }
       }
     }
     if(form.GM_BIKOU9 != undefined){
       if(form.GM_BIKOU9.type == "text"){
         if(IsLength(form.GM_BIKOU9.value, 0, 2000, "会員自由項目９")){
           return errProc(form.GM_BIKOU9);
         }
       }
     }
     if(form.GM_BIKOU10 != undefined){
       if(form.GM_BIKOU10.type == "text"){
         if(IsLength(form.GM_BIKOU10.value, 0, 2000, "会員自由項目１０")){
           return errProc(form.GM_BIKOU10);
         }
       }
     }
     if(form.GM_BIKOU11 != undefined){
       if(form.GM_BIKOU11.type == "text"){
         if(IsLength(form.GM_BIKOU11.value, 0, 2000, "会員自由項目１１")){
           return errProc(form.GM_BIKOU11);
         }
       }
     }
     if(form.GM_BIKOU12 != undefined){
       if(form.GM_BIKOU12.type == "text"){
         if(IsLength(form.GM_BIKOU12.value, 0, 2000, "会員自由項目１２")){
           return errProc(form.GM_BIKOU12);
         }
       }
     }
     if(form.GM_BIKOU13 != undefined){
       if(form.GM_BIKOU13.type == "text"){
         if(IsLength(form.GM_BIKOU13.value, 0, 2000, "会員自由項目１３")){
           return errProc(form.GM_BIKOU13);
         }
       }
     }
     if(form.GM_BIKOU14 != undefined){
       if(form.GM_BIKOU14.type == "text"){
         if(IsLength(form.GM_BIKOU14.value, 0, 2000, "会員自由項目１４")){
           return errProc(form.GM_BIKOU14);
         }
       }
     }
     if(form.GM_BIKOU15 != undefined){
       if(form.GM_BIKOU15.type == "text"){
         if(IsLength(form.GM_BIKOU15.value, 0, 2000, "会員自由項目１５")){
           return errProc(form.GM_BIKOU15);
         }
       }
     }
     if(form.GM_BIKOU16 != undefined){
       if(form.GM_BIKOU16.type == "text"){
         if(IsLength(form.GM_BIKOU16.value, 0, 2000, "会員自由項目１６")){
           return errProc(form.GM_BIKOU16);
         }
       }
     }
     if(form.GM_BIKOU17 != undefined){
       if(form.GM_BIKOU17.type == "text"){
         if(IsLength(form.GM_BIKOU17.value, 0, 2000, "会員自由項目１７")){
           return errProc(form.GM_BIKOU17);
         }
       }
     }
     if(form.GM_BIKOU18 != undefined){
       if(form.GM_BIKOU18.type == "text"){
         if(IsLength(form.GM_BIKOU18.value, 0, 2000, "会員自由項目１８")){
           return errProc(form.GM_BIKOU18);
         }
       }
     }
     if(form.GM_BIKOU19 != undefined){
       if(form.GM_BIKOU19.type == "text"){
         if(IsLength(form.GM_BIKOU19.value, 0, 2000, "会員自由項目１９")){
           return errProc(form.GM_BIKOU19);
         }
       }
     }
     if(form.GM_BIKOU20 != undefined){
       if(form.GM_BIKOU20.type == "text"){
         if(IsLength(form.GM_BIKOU20.value, 0, 2000, "会員自由項目２０")){
           return errProc(form.GM_BIKOU20);
         }
       }
     }
     if(form.GM_BIKOU21 != undefined){
       if(form.GM_BIKOU21.type == "text"){
         if(IsLength(form.GM_BIKOU21.value, 0, 2000, "会員自由項目２１")){
           return errProc(form.GM_BIKOU21);
         }
       }
     }
     if(form.GM_BIKOU22 != undefined){
       if(form.GM_BIKOU22.type == "text"){
         if(IsLength(form.GM_BIKOU22.value, 0, 2000, "会員自由項目２２")){
           return errProc(form.GM_BIKOU22);
         }
       }
     }
     if(form.GM_BIKOU23 != undefined){
       if(form.GM_BIKOU23.type == "text"){
         if(IsLength(form.GM_BIKOU23.value, 0, 2000, "会員自由項目２３")){
           return errProc(form.GM_BIKOU23);
         }
       }
     }
     if(form.GM_BIKOU24 != undefined){
       if(form.GM_BIKOU24.type == "text"){
         if(IsLength(form.GM_BIKOU24.value, 0, 2000, "会員自由項目２４")){
           return errProc(form.GM_BIKOU24);
         }
       }
     }
     if(form.GM_BIKOU25 != undefined){
       if(form.GM_BIKOU25.type == "text"){
         if(IsLength(form.GM_BIKOU25.value, 0, 2000, "会員自由項目２５")){
           return errProc(form.GM_BIKOU25);
         }
       }
     }
     if(form.GM_BIKOU26 != undefined){
       if(form.GM_BIKOU26.type == "text"){
         if(IsLength(form.GM_BIKOU26.value, 0, 2000, "会員自由項目２６")){
           return errProc(form.GM_BIKOU26);
         }
       }
     }
     if(form.GM_BIKOU27 != undefined){
       if(form.GM_BIKOU27.type == "text"){
         if(IsLength(form.GM_BIKOU27.value, 0, 2000, "会員自由項目２７")){
           return errProc(form.GM_BIKOU27);
         }
       }
     }
     if(form.GM_BIKOU28 != undefined){
       if(form.GM_BIKOU28.type == "text"){
         if(IsLength(form.GM_BIKOU28.value, 0, 2000, "会員自由項目２８")){
           return errProc(form.GM_BIKOU28);
         }
       }
     }
     if(form.GM_BIKOU29 != undefined){
       if(form.GM_BIKOU29.type == "text"){
         if(IsLength(form.GM_BIKOU29.value, 0, 2000, "会員自由項目２９")){
           return errProc(form.GM_BIKOU29);
         }
       }
     }
     if(form.GM_BIKOU30 != undefined){
       if(form.GM_BIKOU30.type == "text"){
         if(IsLength(form.GM_BIKOU30.value, 0, 2000, "会員自由項目３０")){
           return errProc(form.GM_BIKOU30);
         }
       }
     }
     if(form.GM_BIKOU31 != undefined){
       if(form.GM_BIKOU31.type == "text"){
         if(IsLength(form.GM_BIKOU31.value, 0, 2000, "会員自由項目３１")){
           return errProc(form.GM_BIKOU31);
         }
       }
     }
     if(form.GM_BIKOU32 != undefined){
       if(form.GM_BIKOU32.type == "text"){
         if(IsLength(form.GM_BIKOU32.value, 0, 2000, "会員自由項目３２")){
           return errProc(form.GM_BIKOU32);
         }
       }
     }
     if(form.GM_BIKOU33 != undefined){
       if(form.GM_BIKOU33.type == "text"){
         if(IsLength(form.GM_BIKOU33.value, 0, 2000, "会員自由項目３３")){
           return errProc(form.GM_BIKOU33);
         }
       }
     }
     if(form.GM_BIKOU34 != undefined){
       if(form.GM_BIKOU34.type == "text"){
         if(IsLength(form.GM_BIKOU34.value, 0, 2000, "会員自由項目３４")){
           return errProc(form.GM_BIKOU34);
         }
       }
     }
     if(form.GM_BIKOU35 != undefined){
       if(form.GM_BIKOU35.type == "text"){
         if(IsLength(form.GM_BIKOU35.value, 0, 2000, "会員自由項目３５")){
           return errProc(form.GM_BIKOU35);
         }
       }
     }
     if(form.GM_BIKOU36 != undefined){
       if(form.GM_BIKOU36.type == "text"){
         if(IsLength(form.GM_BIKOU36.value, 0, 2000, "会員自由項目３６")){
           return errProc(form.GM_BIKOU36);
         }
       }
     }
     if(form.GM_BIKOU37 != undefined){
       if(form.GM_BIKOU37.type == "text"){
         if(IsLength(form.GM_BIKOU37.value, 0, 2000, "会員自由項目３７")){
           return errProc(form.GM_BIKOU37);
         }
       }
     }
     if(form.GM_BIKOU38 != undefined){
       if(form.GM_BIKOU38.type == "text"){
         if(IsLength(form.GM_BIKOU38.value, 0, 2000, "会員自由項目３８")){
           return errProc(form.GM_BIKOU38);
         }
       }
     }
     if(form.GM_BIKOU39 != undefined){
       if(form.GM_BIKOU39.type == "text"){
         if(IsLength(form.GM_BIKOU39.value, 0, 2000, "会員自由項目３９")){
         return errProc(form.GM_BIKOU39);
         }
       }
     }
     if(form.GM_BIKOU40 != undefined){
       if(form.GM_BIKOU40.type == "text"){
         if(IsLength(form.GM_BIKOU40.value, 0, 2000, "会員自由項目４０")){
           return errProc(form.GM_BIKOU40);
         }
       }
     }
     if(form.GM_BIKOU41 != undefined){
       if(form.GM_BIKOU41.type == "text"){
         if(IsLength(form.GM_BIKOU41.value, 0, 2000, "会員自由項目４１")){
           return errProc(form.GM_BIKOU41);
         }
       }
     }
     if(form.GM_BIKOU42 != undefined){
       if(form.GM_BIKOU42.type == "text"){
         if(IsLength(form.GM_BIKOU42.value, 0, 2000, "会員自由項目４２")){
           return errProc(form.GM_BIKOU42);
         }
       }
     }
     if(form.GM_BIKOU43 != undefined){
       if(form.GM_BIKOU43.type == "text"){
         if(IsLength(form.GM_BIKOU43.value, 0, 2000, "会員自由項目４３")){
           return errProc(form.GM_BIKOU43);
         }
       }
     }
     if(form.GM_BIKOU44 != undefined){
       if(form.GM_BIKOU44.type == "text"){
         if(IsLength(form.GM_BIKOU44.value, 0, 2000, "会員自由項目４４")){
           return errProc(form.GM_BIKOU44);
         }
       }
     }
     if(form.GM_BIKOU45 != undefined){
       if(form.GM_BIKOU45.type == "text"){
         if(IsLength(form.GM_BIKOU45.value, 0, 2000, "会員自由項目４５")){
           return errProc(form.GM_BIKOU45);
         }
       }
     }
     if(form.GM_BIKOU46 != undefined){
       if(form.GM_BIKOU46.type == "text"){
         if(IsLength(form.GM_BIKOU46.value, 0, 2000, "会員自由項目４６")){
           return errProc(form.GM_BIKOU46);
         }
       }
     }
     if(form.GM_BIKOU47 != undefined){
       if(form.GM_BIKOU47.type == "text"){
         if(IsLength(form.GM_BIKOU47.value, 0, 2000, "会員自由項目４７")){
           return errProc(form.GM_BIKOU47);
         }
       }
     }
     if(form.GM_BIKOU48 != undefined){
       if(form.GM_BIKOU48.type == "text"){
         if(IsLength(form.GM_BIKOU48.value, 0, 2000, "会員自由項目４８")){
           return errProc(form.GM_BIKOU48);
         }
       }
     }
     if(form.GM_BIKOU49 != undefined){
       if(form.GM_BIKOU49.type == "text"){
         if(IsLength(form.GM_BIKOU49.value, 0, 2000, "会員自由項目４９")){
           return errProc(form.GM_BIKOU49);
         }
       }
     }
     if(form.GM_BIKOU50 != undefined){
       if(form.GM_BIKOU50.type == "text"){
         if(IsLength(form.GM_BIKOU50.value, 0, 2000, "会員自由項目５０")){
           return errProc(form.GM_BIKOU50);
         }
       }
     }
     return true;
   }
   
   function errProc(elem){
     if(elem.type != "hidden"){
       if(elem.type != "select-one") elem.select();
       elem.focus();
     }
     return false;
   }
   
   function CheckInputData_searver(pdFlg, gdFlg, sdFlg, mdFlg, bChange, bMdReg){
        $("#mainForm").submit();
   }
   
   function retSearchVal_CheckInputData(PCEmail, PCPmail, PPEmail, PPPmail, PPID, GGID){
     var form = document.mainForm;
     var RegCheck = true;
     
     if(form.NoneRMf != undefined){
       if(form.NoneRMf.value == "1"){
         RegCheck = false;
       }
     }
     if(form.entry_active == undefined){
       if(PPID != 0 && RegCheck == true){
         alert("入力された個人IDは既に使用されています。\n別の個人IDを使用してください。");
         return errProc(form.P_P_ID);
       }
   
       if(form.P_C_EMAIL.type != "hidden"){
         if(PCEmail != 0){
           alert("入力された個人E-MAILは既に登録されています。\n別の個人E-MAILを入力してください。");
           return errProc(form.P_C_EMAIL);
         }
       }
       if(form.P_C_PMAIL.type != "hidden"){
         if(PCPmail != 0){
           alert("入力された携帯メールは既に登録されています。\n別の携帯メールを入力してください。");
           return errProc(form.P_C_PMAIL);
         }
       }
       if(form.P_P_EMAIL.type != "hidden"){
         if(PPEmail != 0){
           alert("入力されたプライベートE-MAILは既に登録されています。\n別のプライベートE-MAILを入力してください。");
           return errProc(form.P_P_EMAIL);
         }
       }
       if(form.P_P_PMAIL.type != "hidden"){
         if(PPPmail != 0){
           alert("入力されたプライベート携帯メールアドレスは既に登録されています。\n別のプライベート携帯メールアドレスを入力してください。");
           return errProc(form.P_P_PMAIL);
         }
       }
   
       if(GGID != 0 && RegCheck == true){
         alert("入力された組織IDは既に使用されています。\n別の組織IDを使用してください。");
         return errProc(form.G_G_ID);
       }
     }
   
   
     if(form.M_CONTACTDEST != undefined){
     
       changeContactForm();
     }
     if(form.M_CLAIMDEST != undefined){
     
       changeClaimForm();
     }
   
   
     var act_win;
     if(form.NewRegCheckFlg != undefined){
       act_win = "confirm_active_ex.asp";
     }else{
   
       act_win = "confirm.asp";
   
     }
   
     
     if(form.flgFee.value == '1' && form.M_STATUS.value != '1'){
       form.method = "post";
       form.action = act_win + "?feewin=1";
       form.submit();
     }else{
       form.method = "post";
       form.action = act_win + "";
       form.submit();
     }
   
   }
   
   function dispSwitch(mode){
     var form = document.mainForm;
     if(mode=='on'){
     
       form.FAX_TIME_FROM_H.disabled = false;
       form.FAX_TIME_FROM_N.disabled = false;
       form.FAX_TIME_TO_H.disabled   = false;
       form.FAX_TIME_TO_N.disabled   = false;
     }else{
     
       form.FAX_TIME_FROM_H.disabled = true;
       form.FAX_TIME_FROM_N.disabled = true;
       form.FAX_TIME_TO_H.disabled   = true;
       form.FAX_TIME_TO_N.disabled   = true;
     }
   }
   
   function funcHanToZen(obj){
     var ifmGetData = getData;
     var ifmSetUrl;
     ifmSetUrl = "./rs/commonRsSearch.asp?fncName=rsHanToZen&value=" + obj.value;
     ifmSetUrl = ifmSetUrl + "&ArgVal=" + obj.name;
     ifmGetData.location.href = ifmSetUrl;
   }
   function retSearchVal_rsHanToZen(retVal, objname){
     var form = document.mainForm;
   
     form[objname].value = retVal;
   
     switch (objname){
     case "P_C_ADDRESS":
     case "P_C_ADDRESS2":
       changeContactForm();
       changeClaimForm();
       break;
   
     case "P_P_ADDRESS":
     case "P_P_ADDRESS2":
       changeContactForm();
       changeClaimForm();
       break;
   
     case "G_ADDRESS":
     case "G_ADDRESS2":
       changeContactForm();
       changeClaimForm();
       break;
   
     case "M_CO_C_ADDRESS":
     case "M_CO_C_ADDRESS2":
       changeContactFormValue();
       changeClaimForm();
       break;
   
     case "M_CL_C_ADDRESS":
     case "M_CL_C_ADDRESS2":
       changeClaimFormValue();
       changeClaimForm();
       break;
   
     default:
       break;
     }
   
   }
   function funcZenToHan(obj){
     var ifmGetData = getData;
     var ifmSetUrl;
     ifmSetUrl = "./rs/commonRsSearch.asp?fncName=rsZenToHan&value=" + obj.value;
     ifmSetUrl = ifmSetUrl + "&ArgVal=" + obj.name;
     ifmGetData.location.href = ifmSetUrl;
   }
   function retSearchVal_rsZenToHan(retVal, objname){
     var form = document.mainForm;
     var tmp;
   
     switch (objname){
     case "G_ACCAUNT_NAME":
       tmp = retVal;
       form[objname].value = retVal;
       if(getByte(tmp) != tmp.length){
         alert("口座名義に半角文字に変換できない文字が含まれています。入力内容を確認してください。");
         return;
       }
   
       changeContactForm();
       changeClaimForm();
       break;
   
     case "P_ACCAUNT_NAME":
       tmp = retVal;
       form[objname].value = retVal;
       if(getByte(tmp) != tmp.length){
         alert("口座名義に半角文字に変換できない文字が含まれています。入力内容を確認してください。");
         return;
       }
   
       changeContactForm();
       changeClaimForm();
       break;
   
     case "M_ACCAUNT_NAME":
       tmp = retVal;
       form[objname].value = retVal;
       if(getByte(tmp) != tmp.length){
         alert("口座名義に半角文字に変換できない文字が含まれています。入力内容を確認してください。");
         return;
       }
   
       changeClaimFormValue();
       break;
   
     default:
       form[objname].value = retVal;
       break;
     }
   
   }
   
   function funcHiraganaToKatakana(obj){
     var ifmGetData = getData;
     var ifmSetUrl;
   
     ifmSetUrl = "./rs/commonRsSearch.asp?fncName=rsHiraganaToKatakana&value=" + obj.value;
   
     ifmSetUrl = ifmSetUrl + "&ArgVal=" + obj.name;
     ifmGetData.location.href = ifmSetUrl;
   }
   function retSearchVal_rsHiraganaToKatakana(retVal, objname){
   
     var form = document.mainForm;
   
   
     form[objname].value = retVal;
   
     switch (objname){
     case "P_C_KANA":
     case "G_KANA":
       changeContactForm();
       changeClaimForm();
       break;
   
     case "M_CO_G_KANA":
     case "M_CO_C_KANA":
     case "M_CL_G_KANA":
     case "M_CL_C_KANA":
       changeClaimFormValue();
       changeClaimForm();
   
     default:
       break;
     }
   
   }
   
   function chkMustInput(){
                   var i;
                 
                 if(mainForm.P_PASSWORD != undefined){
                  if(mainForm.P_PASSWORD.length == undefined){
                   if(mainForm.P_PASSWORD.type != "hidden"){
                    if(mainForm.P_PASSWORD.type != "select-one"){
                     if(IsNull(mainForm.P_PASSWORD.value, "個人パスワード")){
                      mainForm.P_PASSWORD.select();
                      mainForm.P_PASSWORD.focus();
                      return false;
                     }
                    }else{
                     if(IsNull(mainForm.P_PASSWORD.value, "個人パスワード")){
                      mainForm.P_PASSWORD.focus();
                      return false;
                     }
                    }
                   }else{
                    if(mainForm.P_PASSWORD_1 != undefined){
                     if(mainForm.P_PASSWORD_1.type != "hidden"){
                      if(IsNull(mainForm.P_PASSWORD_1.value, "個人パスワード")){
                       mainForm.P_PASSWORD_1.select();
                       mainForm.P_PASSWORD_1.focus();
                       return false;
                      }
                     }
                    }else{
                     if(mainForm.P_PASSWORD_u != undefined){
                      if(mainForm.P_PASSWORD_u.type != "hidden"){
                       if(IsNull(mainForm.P_PASSWORD_u.value, "個人パスワード")){
                        mainForm.P_PASSWORD_u.select();
                        mainForm.P_PASSWORD_u.focus();
                        return false;
                       }
                      }
                     }else{
                      if(mainForm.P_PASSWORD_YEAR != undefined){
                       if(mainForm.P_PASSWORD_YEAR.type != "hidden"){
                        if(IsNull(mainForm.P_PASSWORD_YEAR.value, "個人パスワード")){
                         mainForm.P_PASSWORD_YEAR.select();
                         mainForm.P_PASSWORD_YEAR.focus();
                         return false;
                        }
                       }
                      }else{
                       if(mainForm.P_PASSWORD_YEAR != undefined){
                        if(mainForm.P_PASSWORD_YEAR.type != "hidden"){
                         if(IsNull(mainForm.P_PASSWORD_YEAR.value, "個人パスワード")){
                          mainForm.P_PASSWORD_YEAR.select();
                          mainForm.P_PASSWORD_YEAR.focus();
                          return false;
                         }
                        }
                       }else{
                        if(mainForm.P_PASSWORD_SEL != undefined){
                         if(mainForm.P_PASSWORD_SEL.type != "hidden"){
                          if(IsNull(mainForm.P_PASSWORD_SEL.value, "個人パスワード")){
                           mainForm.P_PASSWORD_SEL.focus();
                           return false;
                          }
                         }
                        }
                       }
                      }
                     }
                    }
                   }
                  }
                 }
                 
                 if(mainForm.P_PASSWORD2 != undefined){
                  if(mainForm.P_PASSWORD2.length == undefined){
                   if(mainForm.P_PASSWORD2.type != "hidden"){
                    if(mainForm.P_PASSWORD2.type != "select-one"){
                     if(IsNull(mainForm.P_PASSWORD2.value, "個人パスワード再入力")){
                      mainForm.P_PASSWORD2.select();
                      mainForm.P_PASSWORD2.focus();
                      return false;
                     }
                    }else{
                     if(IsNull(mainForm.P_PASSWORD2.value, "個人パスワード再入力")){
                      mainForm.P_PASSWORD2.focus();
                      return false;
                     }
                    }
                   }else{
                    if(mainForm.P_PASSWORD2_1 != undefined){
                     if(mainForm.P_PASSWORD2_1.type != "hidden"){
                      if(IsNull(mainForm.P_PASSWORD2_1.value, "個人パスワード再入力")){
                       mainForm.P_PASSWORD2_1.select();
                       mainForm.P_PASSWORD2_1.focus();
                       return false;
                      }
                     }
                    }else{
                     if(mainForm.P_PASSWORD2_u != undefined){
                      if(mainForm.P_PASSWORD2_u.type != "hidden"){
                       if(IsNull(mainForm.P_PASSWORD2_u.value, "個人パスワード再入力")){
                        mainForm.P_PASSWORD2_u.select();
                        mainForm.P_PASSWORD2_u.focus();
                        return false;
                       }
                      }
                     }else{
                      if(mainForm.P_PASSWORD2_YEAR != undefined){
                       if(mainForm.P_PASSWORD2_YEAR.type != "hidden"){
                        if(IsNull(mainForm.P_PASSWORD2_YEAR.value, "個人パスワード再入力")){
                         mainForm.P_PASSWORD2_YEAR.select();
                         mainForm.P_PASSWORD2_YEAR.focus();
                         return false;
                        }
                       }
                      }else{
                       if(mainForm.P_PASSWORD2_YEAR != undefined){
                        if(mainForm.P_PASSWORD2_YEAR.type != "hidden"){
                         if(IsNull(mainForm.P_PASSWORD2_YEAR.value, "個人パスワード再入力")){
                          mainForm.P_PASSWORD2_YEAR.select();
                          mainForm.P_PASSWORD2_YEAR.focus();
                          return false;
                         }
                        }
                       }else{
                        if(mainForm.P_PASSWORD2_SEL != undefined){
                         if(mainForm.P_PASSWORD2_SEL.type != "hidden"){
                          if(IsNull(mainForm.P_PASSWORD2_SEL.value, "個人パスワード再入力")){
                           mainForm.P_PASSWORD2_SEL.focus();
                           return false;
                          }
                         }
                        }
                       }
                      }
                     }
                    }
                   }
                  }
                 }
                   return true;
                 }