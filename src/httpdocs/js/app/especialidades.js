/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//  js/mapas.js ===========================================================================
var popupmapas = function () {
    $("#mapa1 a").fancybox({
        type: "iframe",
        height: 495,
        width: 628,
        autoCenter: true,
        scrolling: 'auto'
    })

    $("#mapa2 a").fancybox({
        type: "iframe",
        height: 495,
        width: 628,
        autoCenter: true,
        scrolling: 'auto'
    })

    $("#mapa3 a").fancybox({
        type: "iframe",
        height: 495,
        width: 628,
        autoCenter: true,
        scrolling: 'auto'
    })

    $("#mapa4 a").fancybox({
        type: "iframe",
        height: 495,
        width: 628,
        autoCenter: true,
        scrolling: 'auto'
    })

}
$(document).on("ready", popupmapas);

//js/banner.js  ===========================================================================
var fondo = function () {
    jQuery(function ($) {
        $(".frase").animate({"margin-left": -250, opacity: 0}, 0)
        $(".frase").eq(0).animate({"margin-left": 0, opacity: 1})
        $.supersized({
            // Functionality
            slideshow: 1, // Slideshow on/off
            autoplay: 1, // Slideshow starts playing automatically
            start_slide: 1, // Start slide (0 is random)
            stop_loop: 0, // Pauses slideshow on last slide
            random: 0, // Randomize slide order (Ignores start slide)
            slide_interval: 3000, // Length between transitions
            transition: 1, // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
            transition_speed: 2000, // Speed of transition
            new_window: 0, // Image links open in new window/tab
            pause_hover: 0, // Pause slideshow on hover
            keyboard_nav: 0, // Keyboard navigation on/off
            performance: 1, // 0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)
            image_protect: 1, // Disables image dragging and right click with Javascript

            // Size - Position						   
            min_width: 1600, // Min width allowed (in pixels)
            min_height: 1, // Min height allowed (in pixels)
            vertical_center: 0, // Vertically center background
            horizontal_center: 1, // Horizontally center background
            fit_always: 0, // Image will never exceed browser width or height (Ignores min. dimensions)
            fit_portrait: 0, // Portrait images will not exceed browser height
            fit_landscape: 1, // Landscape images will not exceed browser width

            // Components							
            slide_links: 'blank', // Individual links for each slide (Options: false, 'number', 'name', 'blank')
            thumb_links: 1, // Individual thumb links for each slide
            thumbnail_navigation: 0, // Thumbnail navigation
            slides: imagenes_fondo, // Slideshow Images

            // Theme Options			   
            progress_bar: 1, // Timer for each slide							
            mouse_scrub: 0

        });
    });
}

/***********************************************************************************/
$(document).ready(function(e) {
   $("#img").css({"opacity":"0"}); 
   $("#nube").css({"opacity":"0"});
   $("#columna2").css({"opacity":"0"});
});

$(document).ready(function(){
	$("#img").delay(300).animate({"opacity":"1"})
	$("#nube").delay(800).animate({"opacity":"1"})
 	$("#columna2").delay(650).animate({"opacity":"1"})
})

/************************************************************************************************************/

//js/util.js  ===========================================================================

// JavaScript Document
//var WEB = 'http://www.ekonollantas.com/';
var WEB = 'http://fisioterapiaosi.com/';
var TITULOWEB = 'RRB - '
function fb_click(url) {
    u = (url != '' && url != '#') ? WEB + '' + url : location.href;
    t = document.title;
    window.open(
            'http://www.facebook.com/sharer.php?u=' + encodeURIComponent(u) + '&t=' + encodeURIComponent(t), 'sharer',
            'toolbar=0,status=0,width=626,height=436');
    return false;
}
function megusta(id, url) {
    u = (url != '' && url != '#') ? WEB + '' + url : location.href;
    var str = '<iframe src="http://www.facebook.com/plugins/like?href=' + u + '&amp;locale=es_ES&amp;layout=button_count&amp;show_faces=true&amp;width=120&amp;action=like&amp;font=arial&amp;colorscheme=light" scrolling="no" frameborder="0" style="border:none;width:120px;height:22px; margin-top: 0px;"></iframe>'
    //document.getElementById(id).innerHTML = str	
    id.replaceWith(str)
}
function enviaramigo(url) {
    var u = (url != '' && url != '#') ? WEB + url : location.href;
    $.fancybox(WEB + 'pop_enviar_amigo.php?pag=' + u + '', {'autoDimensions': false, 'width': 390, 'height': 320, 'type': 'iframe', 'scrolling': 'no', 'titleShow': false, 'overlayOpacity': 0.8, 'autoScale': false, 'margin': 0, 'padding': 0})
}
function open_twitter(url, tit) {
    titulo = (tit != '') ? TITULOWEB + tit : document.title;
    u = (url != '' && url != '#') ? WEB + '' + url : location.href;
    window.open("http://www.twitter.com/intent/tweet?text=A+mi+me+gusta+" + titulo.replace(/ /g, "+", titulo) + "&url=" + u, "Twitter", "toolbar=0, status=0, width=550, height=420");
}

function gplus_click(url) {
    u = (url != '' && url != '#') ? WEB + '' + url : location.href;
    t = document.title;
    window.open(
            'https://plus.google.com/share?url=' + encodeURIComponent(u) + '&t=' + encodeURIComponent(t), 'sharer',
            'toolbar=0,status=0,width=500,height=436');
    return false;
}

var rs = function () {
    if ($("#red-social").length > 0) {
        $("#red-social").each(function () {
            $(this).find("a").eq(0).click(function (e) {
                e.preventDefault();
                fb_click($(this).attr("href"))
            })
            $(this).find("a").eq(1).click(function (e) {
                e.preventDefault();
                open_twitter($(this).attr("href"), $(this).attr("title"))
            })
            $(this).find("a").eq(2).click(function (e) {
                e.preventDefault();
                enviaramigo($(this).attr("href"))
            })
            megusta($(this).find("a").eq(3), $(this).find("a").eq(3).attr("href"))
        })
    }
}
$(document).on("ready", rs);

//js/preload.js  ===========================================================================

$(window).load(function () {
    var banners = new Array('images/acv.jpg', 'images/artritis.jpg', 'images/cita.jpg', 'images/dolor-espalda.jpg', 'images/dolor-rodilla.jpg', 'images/escoliosis.jpg', 'images/especialidades.jpg', 'images/f.reumatologica.jpg', 'images/f.traumatologica.jpg', 'images/fibromialgia.jpg', 'images/lesion-articular.jpg', 'images/lesiones-deportivas.jpg', 'images/nosotros.jpg', 'images/porque-osi.jpg', 'images/salud-ocupacional.jpg', 'images/tendinitis.jpg', 'images/trabaja.jpg', 'images/hover-servicios.png');
    //setTimeout(function(){
    for (n = 0; n < banners.length; n++) {
        $('#preload_image').append('<img src="' + banners[n] + '" />')
    }
    //},5000)
});

// Code inline the index.php ----------------------------------------------------------

$(document).ready(function (e) {
    $("#banner3").children("img").animate({"opacity": "1"}, 1000);

    $(".sub-especialidad2").css({"cursor": "pointer"});
    var pos = 0;
    $(".sub-especialidad2").each(function (i) {
        $(this).click(function (e) {
            e.preventDefault();
            if (pos != i) {
                $(".staff-detalle").eq(i).slideDown();
                $(".simbolo").eq(pos).show();
                $(".simbolo2").eq(pos).hide();
                $(".simbolo").eq(i).hide();
                $(".simbolo2").eq(i).show();
                $(".staff-detalle").eq(pos).slideUp();

                pos = i
            }
        })
    })

});

$(document).ready(function (e) {
    $("#doc-trabajo").css({"left": "-800px"});
});

$(document).scroll(function () {
    //var efecto=$(window).height()-86;
    var top = $(window).scrollTop();
    //alert(top)
    if (top >= 600) {
        $("#doc-trabajo").stop(false, false).animate({"left": "-223px"}, 400);
    } else if (top < 600) {
        $("#doc-trabajo").stop(false, false).animate({"left": "-800px"}, 400);
    }

})