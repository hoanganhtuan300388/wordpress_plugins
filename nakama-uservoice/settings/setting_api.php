<?php function uservoice_management_plugin_settings_page() { ?>
    <div class="wrap setting_view_list">
        <h1 class="setting_title">API設定</h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'nakama-uservoice-plugin-settings-group' ); ?>
            <?php do_settings_sections( 'nakama-uservoice-plugin-settings-group' ); ?>
            <table class="form-table nakama-setting">
                <tr valign="top">
                    <th scope="row">団体ID</th>
                    <td>
                        <input type="text" name="nakama-uservoice-group-id" onchange="renPublicPID(event)" value="<?php echo esc_attr( get_option( 'nakama-uservoice-group-id' ) ); ?>" />
                        <span class="exam_label">例：cloudnakama</span>
                    </td>
                </tr>
                <input type="hidden" name="nakama-uservoice-personal-id" id="nakama-uservoice-personal-id" value="<?php echo esc_attr( get_option( 'nakama-uservoice-personal-id' ) ); ?>" />
                <tr valign="top">
                    <th scope="row">APIキー</th>
                    <td>
                        <textarea rows="3" cols="80" name="nakama-uservoice-api-key"><?php echo get_option( 'nakama-uservoice-api-key' ); ?></textarea>
                        <span class="exam_label">例：Hgfhbd5tG5zzZfJodjNyDEVDkEcFf9HPj1vptBELi7GFVzcM8aaUf9ocCPFbzTzZYTN+y5+SA4XnqNtOAzeQiQ==</span>
                    </td>
                </tr>
                <!--<tr>
                    <th scope="row">
                        一覧表示件数 :
                    </th>
                    <td>
                        <select name="nakama-uservoice-general-per-page" class="">
                            <?php $current_per_page = get_option( "nakama-uservoice-general-per-page" ); ?>
                            <?php for( $i = 10; $i <= 100; $i += 10 ) { ?>
                                <option value="<?php echo $i; ?>" <?php echo ( $i == $current_per_page ) ? "selected" : "" ?>><?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>-->
            </table>
            <?php submit_button( "変更保存" ); ?>
        </form>
    </div>
    <script>
        function renPublicPID(e){
            var p_id = "public_"+e.target.value;
            jQuery("#nakama-uservoice-personal-id").val(p_id);
        }
    </script>
<?php } ?>