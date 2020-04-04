var now    = new Date()
var absnow = now
var Win    = navigator.userAgent.indexOf('Win')!=-1
var Mac    = navigator.userAgent.indexOf('Mac')!=-1
var X11    = navigator.userAgent.indexOf('X11')!=-1
var Moz    = navigator.userAgent.indexOf('Gecko')!=-1
var msie   = navigator.userAgent.indexOf('MSIE')!=-1
var bwlang = getBrowserLANG()
var _utf   = "あ".length > 1
var nonja  = ( _utf || bwlang == 'en')

var supm
var supd

if( nonja )
    var week   = new Array('sun','mon','tue','wed','thu','fri','sat');
else
    var week   = new Array('日','月','火','水','木','金','土');
if( Mac && msie ){ var gox=2000 ; var goy=2000 }
else             { var gox=-400 ; var goy=-200 }
if(document.layers){var n4_left=300 ; var n4_top= 100 }
calendarLay['calendar']=new calendarLay('calendar',-100,-100,'')
function wrtCalendarLay(oj,e,datetype,arg1){
    set_event__wrtCalendarLay()

    // 日付タイプデフォルト値設定と空白文字列除去
    if(!arguments[2])datetype='yyyy/mm/dd';
    else arguments[2].split(' ').join('').split('　').join('')

    // 月移動フラグデフォルト設定
    if(!arguments[3])arg1=0

    wrtCalendarLay.arg1=arg1
    wrtCalendarLay.oj=oj
    wrtCalendarLay.datetype=datetype

    // 現在初期化
    if(arg1==0)now = new Date()

    // 年月日取得
    nowdate  = now.getDate()
    nowmonth = now.getMonth()
    nowyear  = now.getYear()

    // 月移動処理
    if(nowmonth==11 && arg1 > 0){        //12月でarg1が+なら
        nowmonth = -1 + arg1 ; nowyear++   //月はarg1-1;1年加算
    } else if(nowmonth==0 && arg1 < 0){  //1月でarg1が-なら
        nowmonth = 12 + arg1 ; nowyear--   //月はarg1+12;1年減算
    } else {
        nowmonth +=  arg1                  //2-11月なら月は+arg1
    }

    // 2000年問題対応
    if(nowyear<1900)nowyear=1900+nowyear

    // 現在月を確定
    now   = new Date(nowyear,nowmonth,1)

    // YYYYMM作成
    nowyyyymm=nowyear*100+nowmonth

    // YYYY/MM作成
    nowtitleyyyymm=nowyear+'/'+(nowmonth + 1)

    // カレンダー構築用基準日の取得
    fstday   = now                                           //今月の1日
    startday = fstday - ( fstday.getDay() * 1000*60*60*24 )  //最初の日曜日
    startday = new Date(startday)

    // カレンダー構築用HTML
    ddata = ''
    ddata += '<form>\n'
    ddata += '<table border=0 bgcolor="#ffffff"  bordercolor="#dddddd" width=250 height=140\n'
    ddata += 'style="\n'
    ddata += 'border-top       : 1px outset #ffffff;\n'
    ddata += 'border-right     : 1px outset #888888;\n'
    ddata += 'border-bottom    : 1px outset #555555;\n'
    ddata += 'border-left      : 1px outset #ffffff;"\n'
    ddata += '>\n'

    // Month
    ddata += '  <tr id="trmonth" bgcolor="#696969" bordercolor="#6699ff" width=200 height=14>\n'
    ddata += '  <td colspan=7 width=250 height=14 align="right"><nobr><b>\n'
    ddata += '  <a href="javascript:wrtCalendarLay(window.document.'+oj.form.name+'.'+oj.name+',null,\''+datetype+'\',-1)"><font color="#FFFFFF"><<</font></a>&nbsp;\n'
    ddata += '  <a href="javascript:wrtCalendarLay(window.document.'+oj.form.name+'.'+oj.name+',null,\''+datetype+'\',0)"><font color="#FFFFFF">'+nowtitleyyyymm+'</font></a>&nbsp;\n'
    ddata += '  <a href="javascript:wrtCalendarLay(window.document.'+oj.form.name+'.'+oj.name+',null,\''+datetype+'\',1)"><font color="#FFFFFF">>></font></a>&nbsp;&nbsp;\n'
    ddata += '  <input type=button value="閉じる" \n'
    ddata += 'onClick="moveLAYOJ(getstyleOj(\'calendar\'),'+gox+','+goy+')">\n'
    ddata += '</b></nobr></td>\n'
    ddata += '  </tr>\n'

    // Week
    ddata += '   <tr width=250 height=14>\n'

    ddata += '   <th width=30 height=14 bgcolor="#FF66FF">\n'
    ddata += '   <font size="2">\n'
    ddata +=       week[0]
    ddata += '   </font>\n'
    ddata += '   </th>\n'
    for (i=1;i<6;i++){
        ddata += '   <th width=30 height=14 bgcolor="#FFFF99">\n'
        ddata += '   <font size="2">\n'
        ddata +=       week[i]
        ddata += '   </font>\n'
        ddata += '   </th>\n'
    }
    ddata += '   <th width=30 height=14 bgcolor="#3399FF">\n'
    ddata += '   <font size="2">\n'
    ddata +=       week[6]
    ddata += '   </font>\n'
    ddata += '   </th>\n'

    ddata += '   </tr>\n'

    // Date
    for(j=0;j<6;j++){
        ddata += '   <tr bgcolor=#eeeeee>\n'
        for(i=0;i<7;i++){
            nextday     = startday.getTime() + (i * 1000*60*60*24)
            wrtday      = new Date(nextday)
            wrtdate     = wrtday.getDate()
            wrtmonth    = wrtday.getMonth()
            wrtyear     = wrtday.getYear()
            if(wrtyear < 1900) wrtyear = 1900 + wrtyear
            wrtyyyymm   = wrtyear * 100 + wrtmonth
            wrtyyyymmdd = ''+wrtyear +'/'+ (wrtmonth+1) +'/'+wrtdate
            getday      = getWeek(wrtyyyymmdd)
            var outputdate=eval( getDatetype(datetype))
            wrtdateA  = '<a HREF="javascript:function v(){'
            wrtdateA += 'document.'+oj.form.name+'.'+oj.name+'.value=(\''+outputdate
            wrtdateA += '\');if(!(Mac&&document.layers))calendarLay[\'calendar\'].moveLAYOJ(getstyleOj(\'calendar\'),'
            wrtdateA += gox+','+goy+');stop_event__wrtCalendarLay()};v()"   >\n'
            wrtdateA += '<font color=#000000 size=2>\n'
            wrtdateA += wrtdate
            wrtdateA += '</font>\n'
            wrtdateA += '</a>\n'

            if(wrtyyyymm != nowyyyymm){
                ddata += ' <td bgcolor=#cccccc width=30 height=14 align="center">\n'
                ddata += wrtdateA

            } else if(   wrtdate == absnow.getDate()
                && wrtmonth == absnow.getMonth()
                && wrtday.getYear() == absnow.getYear()){
                ddata += ' <td bgcolor=#99FF66 width=30 height=14 align="center">\n'
                ddata += '<font color="#ffffff">'+wrtdateA+'</font>\n'

            } else {
                ddata += ' <td width=30 height=14 align="center">\n'
                ddata += wrtdateA
            }
            ddata += '   </td>\n'
        }
        ddata += '   </tr>\n'

        startday = new Date(nextday)
        startday = startday.getTime() + (1000*60*60*24)
        startday = new Date(startday)
    }
    // ステータス行 日付タイプ
    ddata += '</table>\n'
    ddata += '</form>\n'
    ddata += '</body>\n'
    ddata += '</html>\n'

    calendarLay['calendar'].outputLAYOJ(getLayOj('calendar'),'')//一時クリア
    calendarLay['calendar'].outputLAYOJ(getLayOj('calendar'),ddata)

    if(e!=null){
        if(navigator.userAgent.indexOf('Gecko')!=-1){   //n6,m1用
//        var left = e.currentTarget.offsetLeft - 90
//        var top  = e.currentTarget.offsetTop  + 35
            var left = getMouseX(e) - 90
            var top  = getMouseY(e) - 167
        } else {
            var left = getMouseX(e) - 90
            var top  = getMouseY(e) + 35
        }
        if(document.layers){ var left = n4_left ; var top  = n4_top }//n4修正
        calendarLay['calendar'].moveLAYOJ(getstyleOj('calendar'),left,top)

    }

}

// 曜日取得
function getWeek(date){
    if(arguments.length>0)date=date
    else date=null
    if(  Mac && msie )//MacIE5用
        week   = new Array('sun','mon','tue','wed','thu','fri','sat');
    var now  = new Date(date) ;
    return week[now.getDay()] ;
}

// 出力日付のデータタイプ
function getDatetype(datetype){

    if(nonja || ( Mac && msie )){ //漢字式表記の回避
        if ( datetype == 'yyyy年mm月dd日(曜)')  datetype = 'yyyy/mm/dd(曜)'
        else if( datetype == 'mm月dd日')        datetype = 'mm/dd'
        else if( datetype == 'mm月dd日(曜)')    datetype = 'mm/dd(曜)'
    }
    supm=''
    if(wrtmonth+1<10){
        supm='0'
    }
    supd=''
    if(wrtdate<10){
        supd='0'
    }
    switch(datetype){
        case 'yyyy'
        : dtate= "''+wrtyear                                                            " ; break ;
        case 'yyyy/mm'
        : dtate= "''+wrtyear +'/'+  supm + (wrtmonth+1)                                 " ; break ;
        case 'yyyy/mm/dd'
        : dtate= "''+wrtyear +'/'+  supm + (wrtmonth+1) +'/'+ supd + wrtdate           " ; break ;
        case 'mm/dd'
        : dtate= "''+               supm + (wrtmonth+1) +'/'+ supd + wrtdate           " ; break ;
        case 'mm'
        : dtate= "''+               supm + (wrtmonth+1)                                 " ; break ;
        case 'dd'
        : dtate= "''+                                         supd + wrtdate           " ; break ;
        case 'yyyy/mm/dd[曜]'
        : dtate= "''+wrtyear +'/'+  supm + (wrtmonth+1) +'/'+ supd + wrtdate +' ['+getday +']'  " ; break ;
        case 'yyyy/mm/dd(曜)'
        : dtate= "''+wrtyear +'/'+  supm + (wrtmonth+1) +'/'+ supd + wrtdate +' ('+getday +')'  " ; break ;
        case 'mm/dd(曜)'
        : dtate= "''+               supm + (wrtmonth+1) +'/'+ supd + wrtdate +' ('+getday +')'  " ; break ;
        case 'yyyy年mm月dd日(曜)'
        : dtate= "''+wrtyear +'年'+ supm + (wrtmonth+1) +'月'+ supd + wrtdate +'日('+getday +')'" ; break ;
        case 'mm月dd日'
        : dtate= "''+               supm + (wrtmonth+1) +'月'+ supd + wrtdate +'日'             " ; break ;
        case 'mm月dd日(曜)'
        : dtate= "''+               supm + (wrtmonth+1) +'月'+ supd + wrtdate +'日('+getday +')'" ; break ;
        default
        : dtate= "''+wrtyear +'/'+  supm + (wrtmonth+1) +'/'+ supd + wrtdate                    " ;
    }
    return dtate
}

//--レイヤー生成
function calendarLay(layName,x,y,datetype){
    this.id      = layName   // ドラッグできるようにするレイヤー名
    this.x       = x         // 初期left位置
    this.y       = y         // 初期top位置
    this.datetype = datetype // YYYY/MM/DD
    this.day     = new Array()
    if(document.layers)      //n4用
        this.div='<layer name="'+layName+'" left="'+x+'" top="'+y+'"\n'
            +'       onfocus="clickElement=\''+layName
            +'\';mdown_wrtCalendarLay(event);return false">\n'
            +'<a     href="javascript:void(0)"\n'
            +'       onmousedown="clickElement=\''+layName
            +'\';mdown_wrtCalendarLay(event);return false">\n'
            + '</a></layer>\n'
    else                     //n4以外用
        this.div='<div  id="'+layName+'" class="dragLays"\n'
            +'      onmousedown="clickElement=\''+layName
            +'\';mdown_wrtCalendarLay(event);return false"\n'
            +'      style="position:absolute;left:'+x+'px;top:'+y+'px">\n'
            + '</div>\n'
    document.write(this.div)
    return
}
calendarLay.prototype.moveLAYOJ   = moveLAYOJ   //メソッドを追加する
calendarLay.prototype.outputLAYOJ = outputLAYOJ //メソッドを追加する
calendarLay.prototype.zindexLAYOJ = zindexLAYOJ //メソッドを追加する

//--レイヤー移動
function moveLAYOJ(oj,x,y){
    if(document.getElementById){  //e5,e6,n6,m1,o6用
        oj.left = x
        oj.top  = y
    } else if(document.all){      //e4用
        oj.pixelLeft = x
        oj.pixelTop  = y
    } else if(document.layers)    //n4用
        oj.moveTo(x,y)
}
//--HTML出力
function outputLAYOJ(oj,html){
    if(document.getElementById) oj.innerHTML=html  //n6,m1,e5,e6用
    else if(document.all) oj.innerHTML=html //e4用
    else if(document.layers)                       //n4用
        with(oj.document){
            open()
            write(html)
            close()
        }
}
//--奥行きZ座標set
function zindexLAYOJ(oj,zindex){
    if(document.getElementById) oj.zIndex=zindex  //n6,m1,e5,e6,o6用
    else if(document.all)       oj.zIndex=zindex  //e4用
    else if(document.layers)    oj.zIndex=zindex  //n4用
}

//--layNameで指定したオブジェクトを返す(必ずonload後に実行すること)
function getLayOj(layName){
    if(document.getElementById)
        return document.getElementById(layName)           //e5,e6,n6,m1,o6用
    else if(document.all)   return document.all(layName)    //e4用
    else if(document.layers)return document.layers[layName] //n4用
}
function getstyleOj(clickElement){
    return (!!document.layers)?getLayOj(clickElement)
        :getLayOj(clickElement).style
}

//--マウスX座標get
function getMouseX(e){
    if(window.opera)                            //o6用
        return e.clientX
    else if(document.all)                       //e4,e5,e6用
        return document.body.scrollLeft+event.clientX
    else if(document.layers||document.getElementById)
        return e.pageX                          //n4,n6,m1用
}

//--マウスY座標get
function getMouseY(e){
    if(window.opera)                            //o6用
        return e.clientY
    else if(document.all)                       //e4,e5,e6用
        return document.body.scrollTop+event.clientY
    else if(document.layers||document.getElementById)
        return e.pageY                          //n4,n6,m1用
}

//--レイヤ－左辺X座標get
function getLEFT(layName){
    if(document.all)                            //e4,e5,e6,o6用
        return document.all(layName).style.pixelLeft
    else if(document.getElementById)            //n6,m1用
        return (document.getElementById(layName).style.left!="")
            ?parseInt(document.getElementById(layName).style.left):""
    else if(document.layers)                    //n4用
        return document.layers[layName].left
}

//--レイヤ－上辺Y座標get
function getTOP(layName){
    if(document.all)                          //e4,e5,e6,o6用
        return document.all(layName).style.pixelTop
    else if(document.getElementById)          //n6,m1用
        return (document.getElementById(layName).style.top!="")
            ?parseInt(document.getElementById(layName).style.top):""
    else if(document.layers)                  //n4用
        return document.layers[layName].top
}

//--マウスカーソルを動かした時レイヤーもmoveLAYOJで動かす
function mmove_wrtCalendarLay(e) {
    if(!window.clickElement) return
    if (getLayOj(clickElement)) {
        movetoX = getMouseX(e) - offsetX
        movetoY = getMouseY(e) - offsetY
        var oj=getstyleOj(clickElement)
        calendarLay[clickElement].moveLAYOJ(oj,movetoX,movetoY)
        return false
    }
}

//--マウスボタンを押し下げた時
//  レイヤー内のカーソルoffset位置取得
function mdown_wrtCalendarLay(e) {
    if(navigator.userAgent.indexOf('Gecko')!=-1)   //n6,m1用
        if(e.currentTarget.className != 'dragLays') return
        else clickElement = e.currentTarget.id
    var selLay = getLayOj(clickElement)
    if (selLay){
        offsetX = getMouseX(e) - getLEFT(selLay.id)
        offsetY = getMouseY(e) - getTOP(selLay.id)
        if(document.layers){
            offsetX = getMouseX(e)+10 ; offsetY = getMouseY(e)+10
        }
    }
    return false
}

//--マウスボタンを上げた時ドラッグ解除
var zcount = 0
function mup_wrtCalendarLay(e) {
    if(!window.clickElement) return
    if (getLayOj(clickElement)) {
        calendarLay[clickElement].zindexLAYOJ(
            getstyleOj(clickElement),zcount++)
        clickElement=null
    }
}

//--イベントキャプチャー開始
function set_event__wrtCalendarLay(){
    document.onmousemove = mmove_wrtCalendarLay   //n4,m1,n6,e4,e5,e6,o6用
    document.onmouseup   = mup_wrtCalendarLay     //n4,m1,n6,e4,e5,e6,o6用
    if(navigator.userAgent.indexOf('Gecko')!=-1)  //m1,n6用
        document.onmousedown = mdown_wrtCalendarLay
    if(document.layers){                          //n4用
        document.captureEvents(Event.MOUSEMOVE)
        document.captureEvents(Event.MOUSEUP)
    }
}

//--イベントキャプチャー停止
function stop_event__wrtCalendarLay(){
    document.onmousemove = null                   //n4,m1,n6,e4,e5,e6,o6用
    document.onmouseup   = null                   //n4,m1,n6,e4,e5,e6,o6用
    if(navigator.userAgent.indexOf('Gecko')!=-1)  //m1,n6用
        document.onmousedown = null
    if(document.layers){                          //n4用
        document.releaseEvents(Event.MOUSEMOVE)
        document.releaseEvents(Event.MOUSEUP)
    }
}

//--ブラウザの言語を取得
function getBrowserLANG(){
    if(document.all)
        return navigator.browserLanguage      //e4,e5,e6,o6用
    else if(document.layers)
        return navigator.language             //n4用
    else if(document.getElementById)
        return navigator.language.substr(0,2) //n6,n7,m1用
}
