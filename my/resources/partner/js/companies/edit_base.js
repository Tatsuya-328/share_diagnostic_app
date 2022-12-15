(function($) {

    function showAddress(data) {
        $("#prefecture_id option").filter(function(index, row) {
            return $(this).text() == data["prefecture_name"]
        }).prop('selected', true);
        $('#address1').val(data['address1']);
        $('#address2').val("");
        $('#address_building').val("");
        if ($("#errorSearchAddress").is(':visible')) {
            $("#errorSearchAddress").hide();
        }
    }

    function showError() {
        $("#errorSearchAddress").show();
    }

    function searchAddress() {
        var postCode = $('#post_code').val();
        var url = $('#basePostCodeDomain').val();
        ZipCodeJp.setZipCodeBaseUrl(url);
        ZipCodeJp.getAddressesOfZipCode(postCode, function(err, addresses) {
            if (Object.keys(addresses).length != 0) {
                showAddress(addresses);
            } else {
                showError();
            }
        });
    }

    $("#searchAddress").on('click', searchAddress);

    function copyNotification() {
        $("#copiedNotice").toggle(true);
        setTimeout(function(){
            $("#copiedNotice").toggle(false);
        }, 2000);
    }

    $(".cityName").on("click", function(e) {
        $(this).select();
        $("#copyCityBtn").attr('data-clipboard-target',  "#" + $(this).attr('id'));
    });

    var board = new Clipboard("#copyCityBtn");
    board.on('success', function() {
        $(".close").click();
        copyNotification();
    });
    board.on("error", function() {
        alert("コピーに失敗しました。");
    });

})(jQuery);