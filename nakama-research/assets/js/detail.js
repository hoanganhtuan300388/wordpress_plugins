function ShowQrCode(url){
  var buf;
  var gToolWnd;

  buf = 'ViewQrCode.asp?url=' + url;
  gToolWnd = open(buf,
      'QrWnd',
      'height=170,width=135,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

function ShowMap(Address){
  var buf;
  var gToolWnd;

  buf = 'ViewMap.asp?Address=' + Address;
  gToolWnd = open(buf,
      'MapWnd',
      'height=400,width=400,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

window.focus();