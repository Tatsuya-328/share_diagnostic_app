(function ($) {
    $(document).on('click', '.addElement', function(e) {
        e.preventDefault();
        addElement()
    }).on('click', '.removeElement', function(e) {
        e.preventDefault();
        var maxDisplayNum = parseInt($('#maxElementNum').val());
        console.log(maxDisplayNum+"max")
        var element = $("#Element" + maxDisplayNum)

        console.log(element.find('.imageAlt').val() + "elemaltval")
        if (element.find('.imageAlt').val() || element.find('.imageFileName').val()) {
            if (confirm("画像を削除してもよろしいですか？")) {
                removeElement(maxDisplayNum)
            }
        } else {
            removeElement(maxDisplayNum)
        }
    });

    // 要素追加
    function addElement(alt="") {
        var maxDisplayNum = incrementMax(parseInt($("#maxElementNum").val()));
        // console.log(maxDisplayNum + "addElementM")
        // コピーを作成
        var html = $($('.imageForm').html()).clone(true)
        $(html).attr('id', "Element" + maxDisplayNum)
        // console.log('htmlmaxDisplayNum:'+maxDisplayNum)
        $(html).find('.imageTitle').text("画像" + (maxDisplayNum));

        $(html).find('.imageNum').val(maxDisplayNum);

        $(html).find('.imageAlt').attr("name", "image_alt" + maxDisplayNum);
        $(html).find('.imageAlt').attr("id", "image_alt" + maxDisplayNum);
        $(html).find('.imageAlt').val("");

        $(html).find('.imageFileName').attr("name", "image_filename" + maxDisplayNum);
        $(html).find('.imageFileName').attr("id", "image_filename" + maxDisplayNum);

        $(html).find('.imgSrc').attr("id", "imgSrc" + maxDisplayNum);
        $(html).find('.imgSrc').remove()
        // console.log($(html).find('.imageTitle'))
        $(html).appendTo($('#imageFormBox'))
    }

    // 要素削除
    function removeElement(maxDisplayNum) {
        decrementMax(maxDisplayNum);
        $("#Element" + maxDisplayNum).remove()
    }

    function decrementMax(num) {
        return setmaxDisplayNum(num - 1)
    }

    function incrementMax(num) {
        console.log('num+'+num)
        return setmaxDisplayNum(num + 1)
    }

    function setmaxDisplayNum(num) {
        toggleIfChangedButton(num)
        console.log('max:'+ $('#maxElementNum').val())
        $('#maxElementNum').val(num);
        return num;
    }

    function toggleIfChangedButton(num) {
        if (num == parseInt($("#maxCount").val())) {
            $('.addElement').prop('disabled', true)
        } else {
            $('.addElement').prop('disabled', false)
        }
        if (num == 1) {
            $('.removeElement').prop('disabled', true)
        } else {
            $('.removeElement').prop('disabled', false)
        }
    }


})(jQuery);