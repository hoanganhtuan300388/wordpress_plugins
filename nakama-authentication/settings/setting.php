<?php
function authen_setting(){ ?>
  <div class="wrap setting_view_list">
    <div style="clear: both;"></div>
    <h1 class="setting_title">なかま連携情報</h1>
    <table class="wp-list-table widefat fixed striped posts wp-table-setting wp-list-table-common">
        <thead>
          <tr>
            <th class="first-th">
              <a href="">NO</a>
            </th>
            <th class="last-th">
              <a><span>ページ設定</span></a>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>
              <a href="?page=event-setting-api">API 設定</a>
              <div class="row-actions">
                <span class="view">
                  <a href="?page=authen-setting-api">編集</a>
                </span>
              </div>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <th class="first-th">
              <a href="">NO</a>
            </th>
            <th class="last-th">
              <a><span>ページ設定</span></a>
            </th>
          </tr>
        </tfoot>
      </table> 
  </div>
  <?php
}
?>
