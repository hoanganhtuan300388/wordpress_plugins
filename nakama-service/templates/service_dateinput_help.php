<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE11"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/req_service.css"/>

    <script src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>/assets/js/jquery-1.6.3.min.js"
            type="text/javascript"></script>
    <script src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>/assets/js/common.js" type="text/javascript"></script>
    <script src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>/assets/js/calendarlay.js"
            type="text/javascript"></script>

</head>
<body>
<form name="mainForm" id="mainForm" class="dateinput">
    <table class="header" align="center">
        <tbody>
        <tr>
            <td class="textwhite">■日付項目の入力・表示例</td>
        </tr>
        </tbody>
    </table>
    <br>
    <br>
    <table class="table_b" align="center" cellpadding="4">
        <tbody>
        <tr>
            <td class="table_i">
                日付
            </td>
            <td class="table_d">
                <input type="text" name="service_date_item1" maxlength="10" size="15" class="alphameric"
                       value="2012/08/01"><input type="button" value="..."
                                                 onclick="wrtCalendarLay(this.form.service_date_item1,event)">
                <select name="service_time_item2">
                    <option value="">--</option>
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13" selected="">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                </select>時<br>
                <input type="text" class="input_text" name="service_item" maxlength="50" value="（開場は12時より開始）">
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;">
                <br>
                <table class="opn_table_b" align="center">
                    <tbody>
                    <tr>
                        <td class="opn_table_i">
                            日付
                        </td>
                        <td class="opn_table_d">
                            2012年8月1日13時 （開場は12時より開始）
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br>
            </td>
        </tr>
        </tbody>
    </table>
    <br>
    <table class="table_b" align="center" cellpadding="4">
        <tbody>
        <tr>
            <td class="table_i">
                日付
            </td>
            <td class="table_d">
                <input type="text" name="service_date_item2" maxlength="10" size="15" class="alphameric"
                       value="2012/08/20"><input type="button" value="..."
                                                 onclick="wrtCalendarLay(this.form.service_date_item2,event)">
                <select name="service_time_item2">
                    <option value="" selected="">--</option>
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                </select>時<br>
                <input type="text" class="input_text" name="service_item" maxlength="50" value="～ 2012年8月25日 の6日間">
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;">
                <br>
                <table class="opn_table_b" align="center">
                    <tbody>
                    <tr>
                        <td class="opn_table_i">
                            日付
                        </td>
                        <td class="opn_table_d">
                            2012年8月20日 ～ 2012年8月25日 の6日間
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br>
            </td>
        </tr>
        </tbody>
    </table>
</form>
</body>
</html>