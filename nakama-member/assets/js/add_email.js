function onSendMail(){

      var form = document.mainForm;

      if(form.file_exit.value == "1"){
        var max = document.mainForm.max_index.value
        for(i=0; i<=max; i++){


          if(form['must' + i].value == "1"){
            if(form[form['entry' + i].value].value == ""){
              alert(form['entryname' + i].value + "を入力してください");
              form[form['entry' + i].value].focus();
              return false;
            }
          }


          if(form.p_c_email != undefined){
            if(getByte(form.p_c_email.value) > 100){
              alert("個人E-MAILは半角１００文字以内で入力して下さい。");
              form.p_c_email.focus();
              return false;
            }
            if(isMail(form.p_c_email.value, '個人E-MAIL')){
              form.p_c_email.focus();
              return false;
            }
            if(IsNarrowPlus3(form.p_c_email.value, '個人E-MAIL')){
              form.p_c_email.focus();
              return false;
            }
          }


          if(form.p_c_email2 != undefined){
            if(form.p_c_email2.value == ""){
              alert("個人E-MAIL再入力を入力してください");
              form.p_c_email2.focus();
              return false;
            }
            if(form.p_c_email.value != form.p_c_email2.value){
              alert("個人E-MAILの内容と確認入力の内容が一致しません。\n個人E-MAILをもう一度確認して下さい");
              form.p_c_email2.value = "";
              form.p_c_email2.focus();
              return false;
            }
          }

          if(form.p_c_pmail != undefined){
            if(getByte(form.p_c_pmail.value) > 100){
              alert("携帯E-MAILは半角１００文字以内で入力して下さい。");
              form.p_c_pmail.focus();
              return false;
            }
            if(isMail(form.p_c_pmail.value, '携帯E-MAIL')){
              form.p_c_pmail.focus();
              return false;
            }
            if(IsNarrowPlus3(form.p_c_pmail.value, '携帯E-MAIL')){
              form.p_c_pmail.focus();
              return false;
            }
          }
        }
      }else{


        if(form.p_c_email.value == ""){
          alert("個人E-MAILを入力して下さい。");
          form.p_c_email.focus();
          return false;
        }


        if(getByte(form.p_c_email.value) > 100){
          alert("個人E-MAILは半角１００文字以内で入力して下さい。");
          form.p_c_email.focus();
          return false;
        }

        if(isMail(form.p_c_email.value, '個人E-MAIL')){
          form.p_c_email.focus();
          return false;
        }


        if(IsNull(form.p_c_email2.value, '個人E-MAIL再入力')) return errProc(form.p_c_email2);
        if(form.p_c_email.value != form.p_c_email2.value){
          alert("個人E-MAILの内容と確認入力の内容が一致しません。\n個人E-MAILをもう一度確認して下さい");
          form.p_c_email2.value = "";
          form.p_c_email2.focus();
          return false;
        }

        if(IsNarrowPlus3(form.p_c_email.value, '個人E-MAIL')){
          form.p_c_email.focus();
          return false;
        }


        if(getByte(form.p_c_pmail.value) > 100){
          alert("携帯E-MAILは半角１００文字以内で入力して下さい。");
          form.p_c_pmail.focus();
          return false;
        }

        if(isMail(form.p_c_pmail.value, '携帯E-MAIL')){
          form.p_c_pmail.focus();
          return false;
        }
        if(IsNarrowPlus3(form.p_c_pmail.value, '携帯E-MAIL')){
          form.p_c_pmail.focus();
          return false;
        }


        if(form.p_c_name.value == ""){
          alert("氏名を入力して下さい。");
          form.p_c_name.focus();
          return false;
        }
        if(getByte(form.p_c_name.value) > 200){
          alert("氏名は半角200文字／全角１００以内で入力して下さい。");
          form.p_c_name.focus();
          return false;
        }


        if(form.g_name.value == ""){
          alert("組織名を入力して下さい。");
          form.g_name.focus();
          return false;
        }
        if(getByte(form.g_name.value) > 1000){
          alert("組織名は半角1000文字／全角５００以内で入力して下さい。");
          form.g_name.focus();
          return false;
        }


        if(getByte(form.representative.value) > 100){
          alert("代表者氏名は半角100文字／全角５０以内で入力して下さい。");
          form.representative.focus();
          return false;
        }

        if(getByte(form.involved.value) > 200){
          alert("会員と異なる場合の会員との関係は半角200文字／全角１００以内で入力して下さい。");
          form.involved.focus();
          return false;
        }
      }


      if(window.confirm("入力された内容で送信しますが、よろしいですか？")){
        form.send.disabled = true;
        form.mode.value = "send";
        form.submit();
      }else{
        return false;
      }
    }
