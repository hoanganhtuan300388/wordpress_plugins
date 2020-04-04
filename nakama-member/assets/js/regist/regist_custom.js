function chkMustInput(){
  var i;
  if(mainForm.P_P_ID != undefined){
    if(mainForm.P_P_ID.length == undefined){
     if(mainForm.P_P_ID.type != "hidden"){
      if(mainForm.P_P_ID.type != "select-one"){
       if(IsNull(mainForm.P_P_ID.value, "個人ID")){
        mainForm.P_P_ID.select();
        mainForm.P_P_ID.focus();
        return false;
       }
      }else{
       if(IsNull(mainForm.P_P_ID.value, "個人ID")){
        mainForm.P_P_ID.focus();
        return false;
       }
      }
     }else{
      if(mainForm.P_P_ID_1 != undefined){
       if(mainForm.P_P_ID_1.type != "hidden"){
        if(IsNull(mainForm.P_P_ID_1.value, "個人ID")){
         mainForm.P_P_ID_1.select();
         mainForm.P_P_ID_1.focus();
         return false;
        }
       }
      }else{
       if(mainForm.P_P_ID_u != undefined){
        if(mainForm.P_P_ID_u.type != "hidden"){
         if(IsNull(mainForm.P_P_ID_u.value, "個人ID")){
          mainForm.P_P_ID_u.select();
          mainForm.P_P_ID_u.focus();
          return false;
         }
        }
       }else{
        if(mainForm.P_P_ID_YEAR != undefined){
         if(mainForm.P_P_ID_YEAR.type != "hidden"){
          if(IsNull(mainForm.P_P_ID_YEAR.value, "個人ID")){
           mainForm.P_P_ID_YEAR.select();
           mainForm.P_P_ID_YEAR.focus();
           return false;
          }
         }
        }else{
         if(mainForm.P_P_ID_YEAR != undefined){
          if(mainForm.P_P_ID_YEAR.type != "hidden"){
           if(IsNull(mainForm.P_P_ID_YEAR.value, "個人ID")){
            mainForm.P_P_ID_YEAR.select();
            mainForm.P_P_ID_YEAR.focus();
            return false;
           }
          }
         }else{
          if(mainForm.P_P_ID_SEL != undefined){
           if(mainForm.P_P_ID_SEL.type != "hidden"){
            if(IsNull(mainForm.P_P_ID_SEL.value, "個人ID")){
             mainForm.P_P_ID_SEL.focus();
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
  if(mainForm.G_NAME != undefined){
    if(mainForm.G_NAME.length == undefined){
     if(mainForm.G_NAME.type != "hidden"){
      if(mainForm.G_NAME.type != "select-one"){
       if(IsNull(mainForm.G_NAME.value, "会社名")){
        mainForm.G_NAME.select();
        mainForm.G_NAME.focus();
        return false;
       }
      }else{
       if(IsNull(mainForm.G_NAME.value, "会社名")){
        mainForm.G_NAME.focus();
        return false;
       }
      }
     }else{
      if(mainForm.G_NAME_1 != undefined){
       if(mainForm.G_NAME_1.type != "hidden"){
        if(IsNull(mainForm.G_NAME_1.value, "会社名")){
         mainForm.G_NAME_1.select();
         mainForm.G_NAME_1.focus();
         return false;
        }
       }
      }else{
       if(mainForm.G_NAME_u != undefined){
        if(mainForm.G_NAME_u.type != "hidden"){
         if(IsNull(mainForm.G_NAME_u.value, "会社名")){
          mainForm.G_NAME_u.select();
          mainForm.G_NAME_u.focus();
          return false;
         }
        }
       }else{
        if(mainForm.G_NAME_YEAR != undefined){
         if(mainForm.G_NAME_YEAR.type != "hidden"){
          if(IsNull(mainForm.G_NAME_YEAR.value, "会社名")){
           mainForm.G_NAME_YEAR.select();
           mainForm.G_NAME_YEAR.focus();
           return false;
          }
         }
        }else{
         if(mainForm.G_NAME_YEAR != undefined){
          if(mainForm.G_NAME_YEAR.type != "hidden"){
           if(IsNull(mainForm.G_NAME_YEAR.value, "会社名")){
            mainForm.G_NAME_YEAR.select();
            mainForm.G_NAME_YEAR.focus();
            return false;
           }
          }
         }else{
          if(mainForm.G_NAME_SEL != undefined){
           if(mainForm.G_NAME_SEL.type != "hidden"){
            if(IsNull(mainForm.G_NAME_SEL.value, "会社名")){
             mainForm.G_NAME_SEL.focus();
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
  if(mainForm.G_KANA != undefined){
    if(mainForm.G_KANA.length == undefined){
     if(mainForm.G_KANA.type != "hidden"){
      if(mainForm.G_KANA.type != "select-one"){
       if(IsNull(mainForm.G_KANA.value, "組織フリガナ")){
        mainForm.G_KANA.select();
        mainForm.G_KANA.focus();
        return false;
       }
      }else{
       if(IsNull(mainForm.G_KANA.value, "組織フリガナ")){
        mainForm.G_KANA.focus();
        return false;
       }
      }
     }else{
      if(mainForm.G_KANA_1 != undefined){
       if(mainForm.G_KANA_1.type != "hidden"){
        if(IsNull(mainForm.G_KANA_1.value, "組織フリガナ")){
         mainForm.G_KANA_1.select();
         mainForm.G_KANA_1.focus();
         return false;
        }
       }
      }else{
       if(mainForm.G_KANA_u != undefined){
        if(mainForm.G_KANA_u.type != "hidden"){
         if(IsNull(mainForm.G_KANA_u.value, "組織フリガナ")){
          mainForm.G_KANA_u.select();
          mainForm.G_KANA_u.focus();
          return false;
         }
        }
       }else{
        if(mainForm.G_KANA_YEAR != undefined){
         if(mainForm.G_KANA_YEAR.type != "hidden"){
          if(IsNull(mainForm.G_KANA_YEAR.value, "組織フリガナ")){
           mainForm.G_KANA_YEAR.select();
           mainForm.G_KANA_YEAR.focus();
           return false;
          }
         }
        }else{
         if(mainForm.G_KANA_YEAR != undefined){
          if(mainForm.G_KANA_YEAR.type != "hidden"){
           if(IsNull(mainForm.G_KANA_YEAR.value, "組織フリガナ")){
            mainForm.G_KANA_YEAR.select();
            mainForm.G_KANA_YEAR.focus();
            return false;
           }
          }
         }else{
          if(mainForm.G_KANA_SEL != undefined){
           if(mainForm.G_KANA_SEL.type != "hidden"){
            if(IsNull(mainForm.G_KANA_SEL.value, "組織フリガナ")){
             mainForm.G_KANA_SEL.focus();
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
  if(mainForm.P_C_NAME != undefined){
    if(mainForm.P_C_NAME.length == undefined){
     if(mainForm.P_C_NAME.type != "hidden"){
      if(mainForm.P_C_NAME.type != "select-one"){
       if(IsNull(mainForm.P_C_NAME.value, "氏名")){
        mainForm.P_C_NAME.select();
        mainForm.P_C_NAME.focus();
        return false;
       }
      }else{
       if(IsNull(mainForm.P_C_NAME.value, "氏名")){
        mainForm.P_C_NAME.focus();
        return false;
       }
      }
     }else{
      if(mainForm.P_C_NAME_1 != undefined){
       if(mainForm.P_C_NAME_1.type != "hidden"){
        if(IsNull(mainForm.P_C_NAME_1.value, "氏名")){
         mainForm.P_C_NAME_1.select();
         mainForm.P_C_NAME_1.focus();
         return false;
        }
       }
      }else{
       if(mainForm.P_C_NAME_u != undefined){
        if(mainForm.P_C_NAME_u.type != "hidden"){
         if(IsNull(mainForm.P_C_NAME_u.value, "氏名")){
          mainForm.P_C_NAME_u.select();
          mainForm.P_C_NAME_u.focus();
          return false;
         }
        }
       }else{
        if(mainForm.P_C_NAME_YEAR != undefined){
         if(mainForm.P_C_NAME_YEAR.type != "hidden"){
          if(IsNull(mainForm.P_C_NAME_YEAR.value, "氏名")){
           mainForm.P_C_NAME_YEAR.select();
           mainForm.P_C_NAME_YEAR.focus();
           return false;
          }
         }
        }else{
         if(mainForm.P_C_NAME_YEAR != undefined){
          if(mainForm.P_C_NAME_YEAR.type != "hidden"){
           if(IsNull(mainForm.P_C_NAME_YEAR.value, "氏名")){
            mainForm.P_C_NAME_YEAR.select();
            mainForm.P_C_NAME_YEAR.focus();
            return false;
           }
          }
         }else{
          if(mainForm.P_C_NAME_SEL != undefined){
           if(mainForm.P_C_NAME_SEL.type != "hidden"){
            if(IsNull(mainForm.P_C_NAME_SEL.value, "氏名")){
             mainForm.P_C_NAME_SEL.focus();
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
  if(mainForm.P_C_KANA != undefined){
    if(mainForm.P_C_KANA.length == undefined){
     if(mainForm.P_C_KANA.type != "hidden"){
      if(mainForm.P_C_KANA.type != "select-one"){
       if(IsNull(mainForm.P_C_KANA.value, "個人フリガナ")){
        mainForm.P_C_KANA.select();
        mainForm.P_C_KANA.focus();
        return false;
       }
      }else{
       if(IsNull(mainForm.P_C_KANA.value, "個人フリガナ")){
        mainForm.P_C_KANA.focus();
        return false;
       }
      }
     }else{
      if(mainForm.P_C_KANA_1 != undefined){
       if(mainForm.P_C_KANA_1.type != "hidden"){
        if(IsNull(mainForm.P_C_KANA_1.value, "個人フリガナ")){
         mainForm.P_C_KANA_1.select();
         mainForm.P_C_KANA_1.focus();
         return false;
        }
       }
      }else{
       if(mainForm.P_C_KANA_u != undefined){
        if(mainForm.P_C_KANA_u.type != "hidden"){
         if(IsNull(mainForm.P_C_KANA_u.value, "個人フリガナ")){
          mainForm.P_C_KANA_u.select();
          mainForm.P_C_KANA_u.focus();
          return false;
         }
        }
       }else{
        if(mainForm.P_C_KANA_YEAR != undefined){
         if(mainForm.P_C_KANA_YEAR.type != "hidden"){
          if(IsNull(mainForm.P_C_KANA_YEAR.value, "個人フリガナ")){
           mainForm.P_C_KANA_YEAR.select();
           mainForm.P_C_KANA_YEAR.focus();
           return false;
          }
         }
        }else{
         if(mainForm.P_C_KANA_YEAR != undefined){
          if(mainForm.P_C_KANA_YEAR.type != "hidden"){
           if(IsNull(mainForm.P_C_KANA_YEAR.value, "個人フリガナ")){
            mainForm.P_C_KANA_YEAR.select();
            mainForm.P_C_KANA_YEAR.focus();
            return false;
           }
          }
         }else{
          if(mainForm.P_C_KANA_SEL != undefined){
           if(mainForm.P_C_KANA_SEL.type != "hidden"){
            if(IsNull(mainForm.P_C_KANA_SEL.value, "個人フリガナ")){
             mainForm.P_C_KANA_SEL.focus();
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
  if(mainForm.P_C_EMAIL != undefined){
    if(mainForm.P_C_EMAIL.length == undefined){
     if(mainForm.P_C_EMAIL.type != "hidden"){
      if(mainForm.P_C_EMAIL.type != "select-one"){
       if(IsNull(mainForm.P_C_EMAIL.value, "個人E-MAIL")){
        mainForm.P_C_EMAIL.select();
        mainForm.P_C_EMAIL.focus();
        return false;
       }
      }else{
       if(IsNull(mainForm.P_C_EMAIL.value, "個人E-MAIL")){
        mainForm.P_C_EMAIL.focus();
        return false;
       }
      }
     }else{
      if(mainForm.P_C_EMAIL_1 != undefined){
       if(mainForm.P_C_EMAIL_1.type != "hidden"){
        if(IsNull(mainForm.P_C_EMAIL_1.value, "個人E-MAIL")){
         mainForm.P_C_EMAIL_1.select();
         mainForm.P_C_EMAIL_1.focus();
         return false;
        }
       }
      }else{
       if(mainForm.P_C_EMAIL_u != undefined){
        if(mainForm.P_C_EMAIL_u.type != "hidden"){
         if(IsNull(mainForm.P_C_EMAIL_u.value, "個人E-MAIL")){
          mainForm.P_C_EMAIL_u.select();
          mainForm.P_C_EMAIL_u.focus();
          return false;
         }
        }
       }else{
        if(mainForm.P_C_EMAIL_YEAR != undefined){
         if(mainForm.P_C_EMAIL_YEAR.type != "hidden"){
          if(IsNull(mainForm.P_C_EMAIL_YEAR.value, "個人E-MAIL")){
           mainForm.P_C_EMAIL_YEAR.select();
           mainForm.P_C_EMAIL_YEAR.focus();
           return false;
          }
         }
        }else{
         if(mainForm.P_C_EMAIL_YEAR != undefined){
          if(mainForm.P_C_EMAIL_YEAR.type != "hidden"){
           if(IsNull(mainForm.P_C_EMAIL_YEAR.value, "個人E-MAIL")){
            mainForm.P_C_EMAIL_YEAR.select();
            mainForm.P_C_EMAIL_YEAR.focus();
            return false;
           }
          }
         }else{
          if(mainForm.P_C_EMAIL_SEL != undefined){
           if(mainForm.P_C_EMAIL_SEL.type != "hidden"){
            if(IsNull(mainForm.P_C_EMAIL_SEL.value, "個人E-MAIL")){
             mainForm.P_C_EMAIL_SEL.focus();
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

   // disp_entr2
   
  if(mainForm.P_C_TEL != undefined){
    if(mainForm.P_C_TEL.length == undefined){
     if(mainForm.P_C_TEL.type != "hidden"){
      if(mainForm.P_C_TEL.type != "select-one"){
       if(IsNull(mainForm.P_C_TEL.value, "個人TEL")){
        mainForm.P_C_TEL.select();
        mainForm.P_C_TEL.focus();
        return false;
       }
      }else{
       if(IsNull(mainForm.P_C_TEL.value, "個人TEL")){
        mainForm.P_C_TEL.focus();
        return false;
       }
      }
     }else{
      if(mainForm.P_C_TEL_1 != undefined){
       if(mainForm.P_C_TEL_1.type != "hidden"){
        if(IsNull(mainForm.P_C_TEL_1.value, "個人TEL")){
         mainForm.P_C_TEL_1.select();
         mainForm.P_C_TEL_1.focus();
         return false;
        }
       }
      }else{
       if(mainForm.P_C_TEL_u != undefined){
        if(mainForm.P_C_TEL_u.type != "hidden"){
         if(IsNull(mainForm.P_C_TEL_u.value, "個人TEL")){
          mainForm.P_C_TEL_u.select();
          mainForm.P_C_TEL_u.focus();
          return false;
         }
        }
       }else{
        if(mainForm.P_C_TEL_YEAR != undefined){
         if(mainForm.P_C_TEL_YEAR.type != "hidden"){
          if(IsNull(mainForm.P_C_TEL_YEAR.value, "個人TEL")){
           mainForm.P_C_TEL_YEAR.select();
           mainForm.P_C_TEL_YEAR.focus();
           return false;
          }
         }
        }else{
         if(mainForm.P_C_TEL_YEAR != undefined){
          if(mainForm.P_C_TEL_YEAR.type != "hidden"){
           if(IsNull(mainForm.P_C_TEL_YEAR.value, "個人TEL")){
            mainForm.P_C_TEL_YEAR.select();
            mainForm.P_C_TEL_YEAR.focus();
            return false;
           }
          }
         }else{
          if(mainForm.P_C_TEL_SEL != undefined){
           if(mainForm.P_C_TEL_SEL.type != "hidden"){
            if(IsNull(mainForm.P_C_TEL_SEL.value, "個人TEL")){
             mainForm.P_C_TEL_SEL.focus();
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
  if(mainForm.P_C_POST != undefined){
    if(mainForm.P_C_POST.length == undefined){
     if(mainForm.P_C_POST.type != "hidden"){
      if(mainForm.P_C_POST.type != "select-one"){
       if(IsNull(mainForm.P_C_POST.value, "個人〒")){
        mainForm.P_C_POST.select();
        mainForm.P_C_POST.focus();
        return false;
       }
      }else{
       if(IsNull(mainForm.P_C_POST.value, "個人〒")){
        mainForm.P_C_POST.focus();
        return false;
       }
      }
     }else{
      if(mainForm.P_C_POST_1 != undefined){
       if(mainForm.P_C_POST_1.type != "hidden"){
        if(IsNull(mainForm.P_C_POST_1.value, "個人〒")){
         mainForm.P_C_POST_1.select();
         mainForm.P_C_POST_1.focus();
         return false;
        }
       }
      }else{
       if(mainForm.P_C_POST_u != undefined){
        if(mainForm.P_C_POST_u.type != "hidden"){
         if(IsNull(mainForm.P_C_POST_u.value, "個人〒")){
          mainForm.P_C_POST_u.select();
          mainForm.P_C_POST_u.focus();
          return false;
         }
        }
       }else{
        if(mainForm.P_C_POST_YEAR != undefined){
         if(mainForm.P_C_POST_YEAR.type != "hidden"){
          if(IsNull(mainForm.P_C_POST_YEAR.value, "個人〒")){
           mainForm.P_C_POST_YEAR.select();
           mainForm.P_C_POST_YEAR.focus();
           return false;
          }
         }
        }else{
         if(mainForm.P_C_POST_YEAR != undefined){
          if(mainForm.P_C_POST_YEAR.type != "hidden"){
           if(IsNull(mainForm.P_C_POST_YEAR.value, "個人〒")){
            mainForm.P_C_POST_YEAR.select();
            mainForm.P_C_POST_YEAR.focus();
            return false;
           }
          }
         }else{
          if(mainForm.P_C_POST_SEL != undefined){
           if(mainForm.P_C_POST_SEL.type != "hidden"){
            if(IsNull(mainForm.P_C_POST_SEL.value, "個人〒")){
             mainForm.P_C_POST_SEL.focus();
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
  if(mainForm.P_C_STA != undefined){
    if(mainForm.P_C_STA.length == undefined){
     if(mainForm.P_C_STA.type != "hidden"){
      if(mainForm.P_C_STA.type != "select-one"){
       if(IsNull(mainForm.P_C_STA.value, "個人都道府県")){
        mainForm.P_C_STA.select();
        mainForm.P_C_STA.focus();
        return false;
       }
      }else{
       if(IsNull(mainForm.P_C_STA.value, "個人都道府県")){
        mainForm.P_C_STA.focus();
        return false;
       }
      }
     }else{
      if(mainForm.P_C_STA_1 != undefined){
       if(mainForm.P_C_STA_1.type != "hidden"){
        if(IsNull(mainForm.P_C_STA_1.value, "個人都道府県")){
         mainForm.P_C_STA_1.select();
         mainForm.P_C_STA_1.focus();
         return false;
        }
       }
      }else{
       if(mainForm.P_C_STA_u != undefined){
        if(mainForm.P_C_STA_u.type != "hidden"){
         if(IsNull(mainForm.P_C_STA_u.value, "個人都道府県")){
          mainForm.P_C_STA_u.select();
          mainForm.P_C_STA_u.focus();
          return false;
         }
        }
       }else{
        if(mainForm.P_C_STA_YEAR != undefined){
         if(mainForm.P_C_STA_YEAR.type != "hidden"){
          if(IsNull(mainForm.P_C_STA_YEAR.value, "個人都道府県")){
           mainForm.P_C_STA_YEAR.select();
           mainForm.P_C_STA_YEAR.focus();
           return false;
          }
         }
        }else{
         if(mainForm.P_C_STA_YEAR != undefined){
          if(mainForm.P_C_STA_YEAR.type != "hidden"){
           if(IsNull(mainForm.P_C_STA_YEAR.value, "個人都道府県")){
            mainForm.P_C_STA_YEAR.select();
            mainForm.P_C_STA_YEAR.focus();
            return false;
           }
          }
         }else{
          if(mainForm.P_C_STA_SEL != undefined){
           if(mainForm.P_C_STA_SEL.type != "hidden"){
            if(IsNull(mainForm.P_C_STA_SEL.value, "個人都道府県")){
             mainForm.P_C_STA_SEL.focus();
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
  if(mainForm.P_C_ADDRESS != undefined){
    if(mainForm.P_C_ADDRESS.length == undefined){
     if(mainForm.P_C_ADDRESS.type != "hidden"){
      if(mainForm.P_C_ADDRESS.type != "select-one"){
       if(IsNull(mainForm.P_C_ADDRESS.value, "個人住所１")){
        mainForm.P_C_ADDRESS.select();
        mainForm.P_C_ADDRESS.focus();
        return false;
       }
      }else{
       if(IsNull(mainForm.P_C_ADDRESS.value, "個人住所１")){
        mainForm.P_C_ADDRESS.focus();
        return false;
       }
      }
     }else{
      if(mainForm.P_C_ADDRESS_1 != undefined){
       if(mainForm.P_C_ADDRESS_1.type != "hidden"){
        if(IsNull(mainForm.P_C_ADDRESS_1.value, "個人住所１")){
         mainForm.P_C_ADDRESS_1.select();
         mainForm.P_C_ADDRESS_1.focus();
         return false;
        }
       }
      }else{
       if(mainForm.P_C_ADDRESS_u != undefined){
        if(mainForm.P_C_ADDRESS_u.type != "hidden"){
         if(IsNull(mainForm.P_C_ADDRESS_u.value, "個人住所１")){
          mainForm.P_C_ADDRESS_u.select();
          mainForm.P_C_ADDRESS_u.focus();
          return false;
         }
        }
       }else{
        if(mainForm.P_C_ADDRESS_YEAR != undefined){
         if(mainForm.P_C_ADDRESS_YEAR.type != "hidden"){
          if(IsNull(mainForm.P_C_ADDRESS_YEAR.value, "個人住所１")){
           mainForm.P_C_ADDRESS_YEAR.select();
           mainForm.P_C_ADDRESS_YEAR.focus();
           return false;
          }
         }
        }else{
         if(mainForm.P_C_ADDRESS_YEAR != undefined){
          if(mainForm.P_C_ADDRESS_YEAR.type != "hidden"){
           if(IsNull(mainForm.P_C_ADDRESS_YEAR.value, "個人住所１")){
            mainForm.P_C_ADDRESS_YEAR.select();
            mainForm.P_C_ADDRESS_YEAR.focus();
            return false;
           }
          }
         }else{
          if(mainForm.P_C_ADDRESS_SEL != undefined){
           if(mainForm.P_C_ADDRESS_SEL.type != "hidden"){
            if(IsNull(mainForm.P_C_ADDRESS_SEL.value, "個人住所１")){
             mainForm.P_C_ADDRESS_SEL.focus();
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
  if(mainForm.P_C_BIKOU13 != undefined){
    if(mainForm.P_C_BIKOU13.length == undefined){
      if(mainForm.P_C_BIKOU13.type != "hidden"){
       if(mainForm.P_C_BIKOU13.type != "select-one"){
        if(IsNull(mainForm.P_C_BIKOU13.value, "個人自由項目１３")){
         mainForm.P_C_BIKOU13.select();
         mainForm.P_C_BIKOU13.focus();
         return false;
        }
       }else{
        if(IsNull(mainForm.P_C_BIKOU13.value, "個人自由項目１３")){
         mainForm.P_C_BIKOU13.focus();
         return false;
        }
       }
      }else{
       if(mainForm.P_C_BIKOU13_1 != undefined){
        if(mainForm.P_C_BIKOU13_1.type != "hidden"){
         if(IsNull(mainForm.P_C_BIKOU13_1.value, "個人自由項目１３")){
          mainForm.P_C_BIKOU13_1.select();
          mainForm.P_C_BIKOU13_1.focus();
          return false;
         }
        }
       }else{
        if(mainForm.P_C_BIKOU13_u != undefined){
         if(mainForm.P_C_BIKOU13_u.type != "hidden"){
          if(IsNull(mainForm.P_C_BIKOU13_u.value, "個人自由項目１３")){
           mainForm.P_C_BIKOU13_u.select();
           mainForm.P_C_BIKOU13_u.focus();
           return false;
          }
         }
        }else{
         if(mainForm.P_C_BIKOU13_YEAR != undefined){
          if(mainForm.P_C_BIKOU13_YEAR.type != "hidden"){
           if(IsNull(mainForm.P_C_BIKOU13_YEAR.value, "個人自由項目１３")){
            mainForm.P_C_BIKOU13_YEAR.select();
            mainForm.P_C_BIKOU13_YEAR.focus();
            return false;
           }
          }
         }else{
          if(mainForm.P_C_BIKOU13_YEAR != undefined){
           if(mainForm.P_C_BIKOU13_YEAR.type != "hidden"){
            if(IsNull(mainForm.P_C_BIKOU13_YEAR.value, "個人自由項目１３")){
             mainForm.P_C_BIKOU13_YEAR.select();
             mainForm.P_C_BIKOU13_YEAR.focus();
             return false;
            }
           }
          }else{
           if(mainForm.P_C_BIKOU13_SEL != undefined){
            if(mainForm.P_C_BIKOU13_SEL.type != "hidden"){
             if(IsNull(mainForm.P_C_BIKOU13_SEL.value, "個人自由項目１３")){
              mainForm.P_C_BIKOU13_SEL.focus();
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

  if(mainForm.P_C_BIKOU14 != undefined){
   if(mainForm.P_C_BIKOU14.length == undefined){
    if(mainForm.P_C_BIKOU14.type != "hidden"){
     if(mainForm.P_C_BIKOU14.type != "select-one"){
      if(IsNull(mainForm.P_C_BIKOU14.value, "個人自由項目１４")){
       mainForm.P_C_BIKOU14.select();
       mainForm.P_C_BIKOU14.focus();
       return false;
      }
     }else{
      if(IsNull(mainForm.P_C_BIKOU14.value, "個人自由項目１４")){
       mainForm.P_C_BIKOU14.focus();
       return false;
      }
     }
    }else{
     if(mainForm.P_C_BIKOU14_1 != undefined){
      if(mainForm.P_C_BIKOU14_1.type != "hidden"){
       if(IsNull(mainForm.P_C_BIKOU14_1.value, "個人自由項目１４")){
        mainForm.P_C_BIKOU14_1.select();
        mainForm.P_C_BIKOU14_1.focus();
        return false;
       }
      }
     }else{
      if(mainForm.P_C_BIKOU14_u != undefined){
       if(mainForm.P_C_BIKOU14_u.type != "hidden"){
        if(IsNull(mainForm.P_C_BIKOU14_u.value, "個人自由項目１４")){
         mainForm.P_C_BIKOU14_u.select();
         mainForm.P_C_BIKOU14_u.focus();
         return false;
        }
       }
      }else{
       if(mainForm.P_C_BIKOU14_YEAR != undefined){
        if(mainForm.P_C_BIKOU14_YEAR.type != "hidden"){
         if(IsNull(mainForm.P_C_BIKOU14_YEAR.value, "個人自由項目１４")){
          mainForm.P_C_BIKOU14_YEAR.select();
          mainForm.P_C_BIKOU14_YEAR.focus();
          return false;
         }
        }
       }else{
        if(mainForm.P_C_BIKOU14_YEAR != undefined){
         if(mainForm.P_C_BIKOU14_YEAR.type != "hidden"){
          if(IsNull(mainForm.P_C_BIKOU14_YEAR.value, "個人自由項目１４")){
           mainForm.P_C_BIKOU14_YEAR.select();
           mainForm.P_C_BIKOU14_YEAR.focus();
           return false;
          }
         }
        }else{
         if(mainForm.P_C_BIKOU14_SEL != undefined){
          if(mainForm.P_C_BIKOU14_SEL.type != "hidden"){
           if(IsNull(mainForm.P_C_BIKOU14_SEL.value, "個人自由項目１４")){
            mainForm.P_C_BIKOU14_SEL.focus();
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

  if(mainForm.P_C_BIKOU15 != undefined){
   if(mainForm.P_C_BIKOU15.length == undefined){
    if(mainForm.P_C_BIKOU15.type != "hidden"){
     if(mainForm.P_C_BIKOU15.type != "select-one"){
      if(IsNull(mainForm.P_C_BIKOU15.value, "個人自由項目１５")){
       mainForm.P_C_BIKOU15.select();
       mainForm.P_C_BIKOU15.focus();
       return false;
      }
     }else{
      if(IsNull(mainForm.P_C_BIKOU15.value, "個人自由項目１５")){
       mainForm.P_C_BIKOU15.focus();
       return false;
      }
     }
    }else{
     if(mainForm.P_C_BIKOU15_1 != undefined){
      if(mainForm.P_C_BIKOU15_1.type != "hidden"){
       if(IsNull(mainForm.P_C_BIKOU15_1.value, "個人自由項目１５")){
        mainForm.P_C_BIKOU15_1.select();
        mainForm.P_C_BIKOU15_1.focus();
        return false;
       }
      }
     }else{
      if(mainForm.P_C_BIKOU15_u != undefined){
       if(mainForm.P_C_BIKOU15_u.type != "hidden"){
        if(IsNull(mainForm.P_C_BIKOU15_u.value, "個人自由項目１５")){
         mainForm.P_C_BIKOU15_u.select();
         mainForm.P_C_BIKOU15_u.focus();
         return false;
        }
       }
      }else{
       if(mainForm.P_C_BIKOU15_YEAR != undefined){
        if(mainForm.P_C_BIKOU15_YEAR.type != "hidden"){
         if(IsNull(mainForm.P_C_BIKOU15_YEAR.value, "個人自由項目１５")){
          mainForm.P_C_BIKOU15_YEAR.select();
          mainForm.P_C_BIKOU15_YEAR.focus();
          return false;
         }
        }
       }else{
        if(mainForm.P_C_BIKOU15_YEAR != undefined){
         if(mainForm.P_C_BIKOU15_YEAR.type != "hidden"){
          if(IsNull(mainForm.P_C_BIKOU15_YEAR.value, "個人自由項目１５")){
           mainForm.P_C_BIKOU15_YEAR.select();
           mainForm.P_C_BIKOU15_YEAR.focus();
           return false;
          }
         }
        }else{
         if(mainForm.P_C_BIKOU15_SEL != undefined){
          if(mainForm.P_C_BIKOU15_SEL.type != "hidden"){
           if(IsNull(mainForm.P_C_BIKOU15_SEL.value, "個人自由項目１５")){
            mainForm.P_C_BIKOU15_SEL.focus();
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
  if(mainForm.P_C_BIKOU16 != undefined){
    if(mainForm.P_C_BIKOU16.length == undefined){
     if(mainForm.P_C_BIKOU16.type != "hidden"){
      if(mainForm.P_C_BIKOU16.type != "select-one"){
       if(IsNull(mainForm.P_C_BIKOU16.value, "個人自由項目１６")){
        mainForm.P_C_BIKOU16.select();
        mainForm.P_C_BIKOU16.focus();
        return false;
       }
      }else{
       if(IsNull(mainForm.P_C_BIKOU16.value, "個人自由項目１６")){
        mainForm.P_C_BIKOU16.focus();
        return false;
       }
      }
     }else{
      if(mainForm.P_C_BIKOU16_1 != undefined){
       if(mainForm.P_C_BIKOU16_1.type != "hidden"){
        if(IsNull(mainForm.P_C_BIKOU16_1.value, "個人自由項目１６")){
         mainForm.P_C_BIKOU16_1.select();
         mainForm.P_C_BIKOU16_1.focus();
         return false;
        }
       }
      }else{
       if(mainForm.P_C_BIKOU16_u != undefined){
        if(mainForm.P_C_BIKOU16_u.type != "hidden"){
         if(IsNull(mainForm.P_C_BIKOU16_u.value, "個人自由項目１６")){
          mainForm.P_C_BIKOU16_u.select();
          mainForm.P_C_BIKOU16_u.focus();
          return false;
         }
        }
       }else{
        if(mainForm.P_C_BIKOU16_YEAR != undefined){
         if(mainForm.P_C_BIKOU16_YEAR.type != "hidden"){
          if(IsNull(mainForm.P_C_BIKOU16_YEAR.value, "個人自由項目１６")){
           mainForm.P_C_BIKOU16_YEAR.select();
           mainForm.P_C_BIKOU16_YEAR.focus();
           return false;
          }
         }
        }else{
         if(mainForm.P_C_BIKOU16_YEAR != undefined){
          if(mainForm.P_C_BIKOU16_YEAR.type != "hidden"){
           if(IsNull(mainForm.P_C_BIKOU16_YEAR.value, "個人自由項目１６")){
            mainForm.P_C_BIKOU16_YEAR.select();
            mainForm.P_C_BIKOU16_YEAR.focus();
            return false;
           }
          }
         }else{
          if(mainForm.P_C_BIKOU16_SEL != undefined){
           if(mainForm.P_C_BIKOU16_SEL.type != "hidden"){
            if(IsNull(mainForm.P_C_BIKOU16_SEL.value, "個人自由項目１６")){
             mainForm.P_C_BIKOU16_SEL.focus();
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

  if(mainForm.P_C_BIKOU19 != undefined){
    if(mainForm.P_C_BIKOU19.length == undefined){
     if(mainForm.P_C_BIKOU19.type != "hidden"){
      if(mainForm.P_C_BIKOU19.type != "select-one"){
       if(IsNull(mainForm.P_C_BIKOU19.value, "個人自由項目１９")){
        mainForm.P_C_BIKOU19.select();
        mainForm.P_C_BIKOU19.focus();
        return false;
       }
      }else{
       if(IsNull(mainForm.P_C_BIKOU19.value, "個人自由項目１９")){
        mainForm.P_C_BIKOU19.focus();
        return false;
       }
      }
     }else{
      if(mainForm.P_C_BIKOU19_1 != undefined){
       if(mainForm.P_C_BIKOU19_1.type != "hidden"){
        if(IsNull(mainForm.P_C_BIKOU19_1.value, "個人自由項目１９")){
         mainForm.P_C_BIKOU19_1.select();
         mainForm.P_C_BIKOU19_1.focus();
         return false;
        }
       }
      }else{
       if(mainForm.P_C_BIKOU19_u != undefined){
        if(mainForm.P_C_BIKOU19_u.type != "hidden"){
         if(IsNull(mainForm.P_C_BIKOU19_u.value, "個人自由項目１９")){
          mainForm.P_C_BIKOU19_u.select();
          mainForm.P_C_BIKOU19_u.focus();
          return false;
         }
        }
       }else{
        if(mainForm.P_C_BIKOU19_YEAR != undefined){
         if(mainForm.P_C_BIKOU19_YEAR.type != "hidden"){
          if(IsNull(mainForm.P_C_BIKOU19_YEAR.value, "個人自由項目１９")){
           mainForm.P_C_BIKOU19_YEAR.select();
           mainForm.P_C_BIKOU19_YEAR.focus();
           return false;
          }
         }
        }else{
         if(mainForm.P_C_BIKOU19_YEAR != undefined){
          if(mainForm.P_C_BIKOU19_YEAR.type != "hidden"){
           if(IsNull(mainForm.P_C_BIKOU19_YEAR.value, "個人自由項目１９")){
            mainForm.P_C_BIKOU19_YEAR.select();
            mainForm.P_C_BIKOU19_YEAR.focus();
            return false;
           }
          }
         }else{
          if(mainForm.P_C_BIKOU19_SEL != undefined){
           if(mainForm.P_C_BIKOU19_SEL.type != "hidden"){
            if(IsNull(mainForm.P_C_BIKOU19_SEL.value, "個人自由項目１９")){
             mainForm.P_C_BIKOU19_SEL.focus();
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
  if(mainForm.P_C_BIKOU21 != undefined){
    if(mainForm.P_C_BIKOU21.length == undefined){
     if(mainForm.P_C_BIKOU21.type != "hidden"){
      if(mainForm.P_C_BIKOU21.type != "select-one"){
       if(IsNull(mainForm.P_C_BIKOU21.value, "個人自由項目２１")){
        mainForm.P_C_BIKOU21.select();
        mainForm.P_C_BIKOU21.focus();
        return false;
       }
      }else{
       if(IsNull(mainForm.P_C_BIKOU21.value, "個人自由項目２１")){
        mainForm.P_C_BIKOU21.focus();
        return false;
       }
      }
     }else{
      if(mainForm.P_C_BIKOU21_1 != undefined){
       if(mainForm.P_C_BIKOU21_1.type != "hidden"){
        if(IsNull(mainForm.P_C_BIKOU21_1.value, "個人自由項目２１")){
         mainForm.P_C_BIKOU21_1.select();
         mainForm.P_C_BIKOU21_1.focus();
         return false;
        }
       }
      }else{
       if(mainForm.P_C_BIKOU21_u != undefined){
        if(mainForm.P_C_BIKOU21_u.type != "hidden"){
         if(IsNull(mainForm.P_C_BIKOU21_u.value, "個人自由項目２１")){
          mainForm.P_C_BIKOU21_u.select();
          mainForm.P_C_BIKOU21_u.focus();
          return false;
         }
        }
       }else{
        if(mainForm.P_C_BIKOU21_YEAR != undefined){
         if(mainForm.P_C_BIKOU21_YEAR.type != "hidden"){
          if(IsNull(mainForm.P_C_BIKOU21_YEAR.value, "個人自由項目２１")){
           mainForm.P_C_BIKOU21_YEAR.select();
           mainForm.P_C_BIKOU21_YEAR.focus();
           return false;
          }
         }
        }else{
         if(mainForm.P_C_BIKOU21_YEAR != undefined){
          if(mainForm.P_C_BIKOU21_YEAR.type != "hidden"){
           if(IsNull(mainForm.P_C_BIKOU21_YEAR.value, "個人自由項目２１")){
            mainForm.P_C_BIKOU21_YEAR.select();
            mainForm.P_C_BIKOU21_YEAR.focus();
            return false;
           }
          }
         }else{
          if(mainForm.P_C_BIKOU21_SEL != undefined){
           if(mainForm.P_C_BIKOU21_SEL.type != "hidden"){
            if(IsNull(mainForm.P_C_BIKOU21_SEL.value, "個人自由項目２１")){
             mainForm.P_C_BIKOU21_SEL.focus();
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
  if(mainForm.P_C_BIKOU24 != undefined){
    if(mainForm.P_C_BIKOU24.length == undefined){
     if(mainForm.P_C_BIKOU24.type != "hidden"){
      if(mainForm.P_C_BIKOU24.type != "select-one"){
       if(IsNull(mainForm.P_C_BIKOU24.value, "個人自由項目２４")){
        mainForm.P_C_BIKOU24.select();
        mainForm.P_C_BIKOU24.focus();
        return false;
       }
      }else{
       if(IsNull(mainForm.P_C_BIKOU24.value, "個人自由項目２４")){
        mainForm.P_C_BIKOU24.focus();
        return false;
       }
      }
     }else{
      if(mainForm.P_C_BIKOU24_1 != undefined){
       if(mainForm.P_C_BIKOU24_1.type != "hidden"){
        if(IsNull(mainForm.P_C_BIKOU24_1.value, "個人自由項目２４")){
         mainForm.P_C_BIKOU24_1.select();
         mainForm.P_C_BIKOU24_1.focus();
         return false;
        }
       }
      }else{
       if(mainForm.P_C_BIKOU24_u != undefined){
        if(mainForm.P_C_BIKOU24_u.type != "hidden"){
         if(IsNull(mainForm.P_C_BIKOU24_u.value, "個人自由項目２４")){
          mainForm.P_C_BIKOU24_u.select();
          mainForm.P_C_BIKOU24_u.focus();
          return false;
         }
        }
       }else{
        if(mainForm.P_C_BIKOU24_YEAR != undefined){
         if(mainForm.P_C_BIKOU24_YEAR.type != "hidden"){
          if(IsNull(mainForm.P_C_BIKOU24_YEAR.value, "個人自由項目２４")){
           mainForm.P_C_BIKOU24_YEAR.select();
           mainForm.P_C_BIKOU24_YEAR.focus();
           return false;
          }
         }
        }else{
         if(mainForm.P_C_BIKOU24_YEAR != undefined){
          if(mainForm.P_C_BIKOU24_YEAR.type != "hidden"){
           if(IsNull(mainForm.P_C_BIKOU24_YEAR.value, "個人自由項目２４")){
            mainForm.P_C_BIKOU24_YEAR.select();
            mainForm.P_C_BIKOU24_YEAR.focus();
            return false;
           }
          }
         }else{
          if(mainForm.P_C_BIKOU24_SEL != undefined){
           if(mainForm.P_C_BIKOU24_SEL.type != "hidden"){
            if(IsNull(mainForm.P_C_BIKOU24_SEL.value, "個人自由項目２４")){
             mainForm.P_C_BIKOU24_SEL.focus();
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