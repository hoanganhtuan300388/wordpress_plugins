//掲載一覧画面表示
function showServiceList(post_id, top_g_id, category, page_link) {
    var buf;
    buf = page_link + 'top_g_id=' + top_g_id + '&post_id=' + post_id + '&category=' + category;

    gToolWnd = open(buf,
        'DetailWnd',
        'width=850,height=750,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

//サービス詳細画面表示
function showRequestServiceDetail(post_id, top_g_id, svc_info_no, page_link) {
    var buf;
    buf = page_link + 'top_g_id=' + top_g_id + '&post_id=' + post_id + '&svc_info_no=' + svc_info_no;

    gToolWnd = open(buf,
        'DetailWnd',
        'width=850,height=750,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

//申請中掲載一覧画面表示
function showRequestServiceSelect(page_link) {
    var buf;
    buf = page_link;

    gToolWnd = open(buf,
        'DetailWnd',
        'width=850,height=750,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

function getDetailService(url) {
    window.open(url, '_self', 'width=1020,height=750,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

function getURL(url) {
    window.open(url, '_self', 'width=1020,height=750,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}

function showRequestServiceList(post_id, top_g_id, page_no, page_link) {
    var buf;
    buf = page_link + 'top_g_id=' + top_g_id + '&post_id=' + post_id + '&page_no=' + page_no;

    gToolWnd = open(buf,
        'DetailWnd',
        'width=980,height=750,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
}
