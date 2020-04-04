<%@ LANGUAGE="VBSCRIPT" %>
<% Option Explicit %>
<%
Dim TITLE     'タイトル
Dim TITLE_IMG 'タイトル背景画像パス
Dim AF_ID     'アフィリエイトID
Dim AF_PARA   '団体毎のアフィリエイトパラメータ

'セッションの値を変数にセット
TITLE     = Session("Affiliate_Title_Name")
TITLE_IMG = Session("Affiliate_Title_Img_Path")
AF_ID     = Session("AffiliateId")
AF_PARA   = Session("AffiliatePara")

'セッションの値を破棄
Session("Affiliate_Title_Name")     = ""
Session("Affiliate_Title_Img_Path") = ""
Session("AffiliateId")              = ""
Session("AffiliatePara")            = ""

'*********************************************************************************************
' アフィリエイトのURLの例（「％＞」の部分は半角に直して使ってください）
' http://pt.afl.rakuten.co.jp/c/<%= AF_ID ％＞/<%= AF_PARA ％＞?url=[対象アフィリエイトのURL]
'*********************************************************************************************

'********************************************
' ここから上の部分は必須なので消さないように
'********************************************
%>
<head>
<style TYPE="text/css">
<!--
.titletxt1{ font-size: 24pt;font-family: cursive; color: #0080FF;}
.titletxt2{ }
a { text-decoration: none; font-weight: bold; }
a:link    { color: #0080FF; } 
a:visited { color: #0080FF; } 
a:hover   { color: #0080FF; } 
a:active  { color: #0080FF; }
-->
</style>
</head>
<!-- ↓↓↓***************タイトル部分***************↓↓↓ -->
  <table width="720" align="center" height="50" border="0" cellpadding="0" cellspacing="0" style="background-repeat : no-repeat;">
    <tr>
      <td width="1"><img src="<%= TITLE_IMG %>"></td>
      <td valign="bottom">
        <div class="titletxt1">&nbsp;&nbsp;&nbsp;&nbsp;<font size="4"><%= TITLE %></font></div>
      </td>
    </tr>
  </table><br>
<!-- ↑↑↑***************タイトル部分***************↑↑↑ -->
<br>
<!-- ↓↓↓*****************一覧部分*****************↓↓↓ -->
  <table width="720" align="center" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="left"><img src="img/sitemap_kokunai.gif" border="0"></td>
      <td align="left"><img src="img/sitemap_kaigai.gif" border="0"></td>
    </tr>

    <tr>
      <td align="left" class="titletxt2">
        <img src="img/yy004_04.gif" border="0">
        宿泊予約
      </td>
      <td align="left">
        <img src="img/yy004_04.gif" border="0">
        <a href="http://pt.afl.rakuten.co.jp/c/<%= AF_ID %>/<%= AF_PARA %>?url=http://travel.rakuten.co.jp/air/" target="_blank">航空券予約</a>
      </td>
    </tr>

    <tr>
      <td align="left">
        &nbsp;&nbsp;
        ├&nbsp;<a href="http://pt.afl.rakuten.co.jp/c/<%= AF_ID %>/<%= AF_PARA %>?url=http://travel.rakuten.co.jp/special/business/" target="_blank">出張特集</a>
      </td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td align="left">
        &nbsp;&nbsp;
        ├&nbsp;<a href="http://pt.afl.rakuten.co.jp/c/<%= AF_ID %>/<%= AF_PARA %>?url=http://travel.rakuten.co.jp/onsen.html" target="_blank">温泉宿</a>
      </td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td align="left">
        &nbsp;&nbsp;
        ├&nbsp;<a href="http://pt.afl.rakuten.co.jp/c/<%= AF_ID %>/<%= AF_PARA %>?url=http://travel.rakuten.co.jp/platinum/" target="_blank">最高級ホテル・旅館</a>
      </td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td align="left">
        &nbsp;&nbsp;
        └&nbsp;<a href="http://pt.afl.rakuten.co.jp/c/<%= AF_ID %>/<%= AF_PARA %>?url=http://travel.rakuten.co.jp/premium/" target="_blank">高級ホテル・旅館</a>
      </td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td align="left">
        <img src="img/yy004_04.gif" border="0">
        <a href="http://pt.afl.rakuten.co.jp/c/<%= AF_ID %>/<%= AF_PARA %>?url=http://travel.rakuten.co.jp/package/" target="_blank">ANA楽パック予約</a>
      </td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td align="left">
        <img src="img/yy004_04.gif" border="0">
        <a href="http://pt.afl.rakuten.co.jp/c/<%= AF_ID %>/<%= AF_PARA %>?url=http://travel.rakuten.co.jp/bus/new_index.html" target="_blank">高速バス予約</a>
      </td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td align="left" class="titletxt2">
        <img src="img/yy004_04.gif" border="0">
        特集＆キャンペーン一覧
      </td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td align="left">
        &nbsp;&nbsp;
        ├&nbsp;<a href="http://pt.afl.rakuten.co.jp/c/<%= AF_ID %>/<%= AF_PARA %>?url=http://travel.rakuten.co.jp/tdr/" target="_blank">東京ディズニーリゾート</a>
      </td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td align="left">
        &nbsp;&nbsp;
        ├&nbsp;<a href="http://pt.afl.rakuten.co.jp/c/<%= AF_ID %>/<%= AF_PARA %>?url=http://travel.rakuten.co.jp/usj/" target="_blank">ユニバーサルスタジオジャパン</a>
      </td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td align="left">
        &nbsp;&nbsp;
        └&nbsp;<a href="http://pt.afl.rakuten.co.jp/c/<%= AF_ID %>/<%= AF_PARA %>?url=http://travel.rakuten.co.jp/index_pet.html" target="_blank">ペットと泊まれる宿予約</a>
      </td>
      <td>&nbsp;</td>
    </tr>
<!-- ↑↑↑*****************一覧部分*****************↑↑↑ -->
