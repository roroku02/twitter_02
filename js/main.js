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
//スマホ向けUI
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

//PC向けUI
if (windowWidth >= 769) {
    $('.slider-item').waypoint(function (direction) {
        var activePoint = $(this.element);
        if (direction === 'down') {
            activePoint.addClass('active');
        } else {
            activePoint.removeClass('active');
        }
    }, { offset: '90%' });
}

function end_loading() {
    //$('.content_load').css("display", "none");
    $('.content_load').remove();

}

function check() {
    if (document.search_form.search_word.value == "") {
        window.alert("検索ワードが入力されていません！");
        return false;
    } else
        return true;
}

function toggle() {
    $(".toggle_box").slideToggle('slow');
}

function load() {
    $('.load').append('<img src="images/loading.gif" alt="">');
    $('#fade').css({
        position: "absolute",
        top: "0px",
        left: "0px",
        width: "100%",
        height: "100%",
        backgroundColor: "#fff",
        opacity: ".8",
        zIndex: "1"
    })
}

$('.page_top > a').on('click', function () {
    $("html,body").animate({ scrollTop: 0 }, "700");
});