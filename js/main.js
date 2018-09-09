$('input[name="rosen"]:radio').change(function () {
    var radio_option = $(this).val();
    if (radio_option == 0) {
        $('.tweet_list > div').css("display", "none");
        $('.tweet_list > .biwako_kyoto_kobe').css("display", "block");
    } else if (radio_option == 1) {
        $('.tweet_list > div').css("display", "none");
        $('.tweet_list > .kosei').css("display", "block");
    } else if (radio_option == 2) {
        $('.tweet_list > div').css("display", "none");
        $('.tweet_list > .kusatsu').css("display", "block");
    } else if (radio_option == 3) {
        $('.tweet_list > div').css("display", "none");
        $('.tweet_list > .kanjo').css("display", "block");
    } else if (radio_option == 4) {
        $('.tweet_list > div').css("display", "none");
        $('.tweet_list > .takaraduka_tozai').css("display", "block");
    } else if (radio_option == 5) {
        $('.tweet_list > div').css("display", "none");
        $('.tweet_list > .kansai').css("display", "block");
    } else if (radio_option == 6) {
        $('.tweet_list > div').css("display", "none");
        $('.tweet_list > .nara').css("display", "block");
    } else if (radio_option == 7) {
        $('.tweet_list > div').css("display", "none");
        $('.tweet_list > .expless').css("display", "block");
    }
});

var windowWidth = $(window).width();
if (windowWidth <= 768) {
    $('.js-slider').slick({
        infinite: false,
        prevArrow: '<i class="far fa-arrow-alt-circle-left"></i>',
        nextArrow: '<i class="far fa-arrow-alt-circle-right slick_next"></i>',
        mobileFirst: true,
        adaptiveHeight: false,
        appendArrows: '.arrows',
        arrows: false,
    });

    setTimeout(function () {
        $('#arrows').fadeOut("slow");
    }, 5000);
}

function check() {
    if (document.search_form.search_word.value == "") {
        window.alert("検索ワードが入力されていません！");
        return false;
    } else
        return true;
}