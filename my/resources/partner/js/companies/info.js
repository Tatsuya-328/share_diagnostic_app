(function($) {
    $('#closeRejectForm').on('click', function() {
        $('#rejectForm').fadeOut(400)
        setTimeout(function(){ $('#showRejectForm').show() }, 400)
    });
    $('#showRejectForm').on('click', function() {
        $('#rejectForm').fadeIn();
        $(this).hide()
    });

    function init() {
        if ($("#message").hasClass('parsley-error')) {
            $('#rejectForm').show();
            $('#showRejectForm').hide()
        }
        var hash = location.hash
        var trigger = ""
        // 選択されているハッシュタグのタブをクリックさせる
        $('a[data-toggle="tab"]').each(function(cnt, row) {
            if ($(row).attr('href') == hash) {
                trigger = row
                $(trigger).click()
                return false
            }
        })
    }

    init()

    $('#applyRegisterForm').on('submit', function() {
        if ($('#mainImage')[0] == null) {
            return confirm('画像が設定されていません。この会社を公開申請に出しますか？') ? true : false;
        }
        return true
    })


    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        // クリックした先のアンカーにリンクさせる
        location.href = $(e.target).attr('href')
    });
})(jQuery);

