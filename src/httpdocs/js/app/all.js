// MENU DESPLEGABLE -------------------------------------------------------------------
$(document).ready(function (e) {
    // primera
    $("#desp3").bind("mouseenter", function () {
        $(this).children("#menu_nosotros").stop(false, true).slideToggle()
    });
    $("#desp3").bind("mouseleave", function () {
        $(this).children("#menu_nosotros").stop(false, true).slideToggle()
    })
    // segunda
    $("#desp1").bind("mouseenter", function () {
        $(this).children("#menu_especialidad").stop(false, true).slideToggle()
    });
    $("#desp1").bind("mouseleave", function () {
        $(this).children("#menu_especialidad").stop(false, true).slideToggle()
    })
    // tercera
    $("#desp2").bind("mouseenter", function () {
        $(this).children("#menu_enfermedad").stop(false, true).slideToggle()
    });
    $("#desp2").bind("mouseleave", function () {
        $(this).children("#menu_enfermedad").stop(false, true).slideToggle()
    })

    // --------------------------------------------------------------------------------
    $(".servicio").css({"overflow": "hidden", "cursor": "pointer"})
    $(".hover-servicio").css({"opacity": "0"});
    $(".hover-circulo").css({"top": "-20px"});

    $(".servicio").each(function (i) {
        $(this).mouseenter(function () {
            $(".hover-servicio").eq(i).stop(true, false).animate({"opacity": "1", "top": "0px"});
            $(".icono-servicio").eq(i).stop(true, false).animate({"margin-top": "50px"}, 300);
            $(".hover-circulo").eq(i).stop(true, false).animate({"top": "20px"}, 300);
            $(".tex-servicio").children("a").eq(i).stop(true, false).css({"color": "#BDFDF6"});
        })
        $(this).mouseleave(function () {
            $(".hover-servicio").eq(i).stop(true, false).animate({"opacity": "0", "top": "54px"});
            $(".icono-servicio").eq(i).stop(true, false).animate({"margin-top": "15px"}, 300);
            $(".hover-circulo").eq(i).stop(true, false).animate({"top": "-20px"}, 300);
            $(".tex-servicio").children("a").eq(i).stop(true, false).css({"color": "#fff"});

        })
    });

    /***********************banner principal navegador***********************************************************/
    var pos = 0;
    var wl = [
        ["40px","0px"],["88px","76px"],["117px","205px"],["210px","359px"],
        ["74px","595px"],["155px","685px"],["125px","863px"]
    ];
    $("#menu-content").children("ul").children("li").each(function (i) {
        if ($(this).children("a").hasClass("activo")) pos = i;

        $(".barrita").stop(true, false).animate({"width":wl[pos][0], "left":wl[pos][1]}, 800);
        $(this).mouseenter(function () {
            $(".barrita").stop(true, false).animate({"width": wl[i][0], "left": wl[i][1]}, 800);
        });
        $(this).mouseleave(function () {
            $(".barrita").stop(true, false).animate({"width": wl[pos][0], "left": wl[pos][1]}, 800);
        });
        console.log('pos & i:', pos, i);
    });

});

// Despliega un select
var openSelect = function (selector) {
    var element = $(selector)[0], worked = false;
    if (document.createEvent) { // all browsers
        var e = document.createEvent("MouseEvents");
        e.initMouseEvent("mousedown", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
        worked = element.dispatchEvent(e);
    } else if (element.fireEvent) { // ie
        worked = element.fireEvent("onmousedown");
    }
    if (!worked) { // unknown browser / error
        alert("It didn't worked in your browser.");
    }
}

/*
 * Datos de mapa para pasarlos al iframe /mapa
 * usado en: contactenos, solicite-cita, cuenta(reagendar)
 */
window.mapsData = {
    'mapMrflrs':[
        -12.116313916501243,
        -77.02660493552685,
        '<center style="width: 230px; height: 207px;"><div style="width: 100%; text-align: center;"><img src="images/logo-osi.png" width="167"></div><div style="padding-top:10px; clear: both;"><p style="margin: 0; padding: 0;"><font color="#006980"><span style="font-family:Tahoma, Geneva, sans-serif;font-size:14px;text-align:-webkit-auto; font-weight: bold;">Central Telefónica:<br/>739 0888</span></p><p style="margin: 0; padding: 0;"><br>    <span style="color:#006980;font-family:Tahoma, Geneva, sans-serif;font-size:12px;text-align:-webkit-auto">Calle González Prada 385. Alt. 50 de Paseo de la Republica.<br/>(Frente del Teatro Marsano, Espalda del Hiraoka de Miraflores)<br /><br /><span style="color:#504F51;font-family:Tahoma, Geneva, sans-serif;font-size:11px;text-align:-webkit-auto">Miraflores, Lima (Perú)</span><br></p></div></center>',
        'Calle Gonzáles Prada 385 Alt.50 Paseo de la República.',
        'Miraflores'
    ],
    '1':[
        -12.116313916501243,
        -77.02660493552685,
        '<center style="width: 230px; height: 167px;"><div style="width: 100%; text-align: center;"><img src="images/logo-osi.png" width="167"></div><div style="padding-top:10px; clear: both;"><p style="margin: 0; padding: 0;"><br>    <span style="color:#006980;font-family:Tahoma, Geneva, sans-serif;font-size:12px;text-align:-webkit-auto">Calle González Prada 385. Alt. 50 de Paseo de la Republica.<br/>(Frente del Teatro Marsano, Espalda del Hiraoka de Miraflores)<br /><br /><span style="color:#504F51;font-family:Tahoma, Geneva, sans-serif;font-size:11px;text-align:-webkit-auto">Miraflores, Lima (Perú)</span><br></p></div></center>',
        'Calle Gonzáles Prada 385 Alt.50 Paseo de la República.',
        'Miraflores'
    ],
    'mapSurco':[
        -12.111373158361898,
        -76.98923774063587,
        '<center style="width: 230px; height: 207px;"><div style="width: 100%; text-align: center;"><img src="images/logo-osi.png" width="167"></div><div style="padding-top:10px; clear: both;"><p style="margin: 0; padding: 0;"><font color="#006980"><span style="font-family:Tahoma, Geneva, sans-serif;font-size:14px;text-align:-webkit-auto; font-weight: bold;">Central Telefónica:<br/>739 0888</span></p><p style="margin: 0; padding: 0;"><br>    <span style="color:#006980;font-family:Tahoma, Geneva, sans-serif;font-size:12px;text-align:-webkit-auto">Av. Del Pinar 198. Alt. 4 de Av. Primavera.<br/>(Dentro del reconocido Instituto Neurociencias de Lima)<br /><br /><span style="color:#504F51;font-family:Tahoma, Geneva, sans-serif;font-size:11px;text-align:-webkit-auto">Surco, Lima (Perú)</span><br></p></div></center>',
        'Av. Del Pinar 198. Alt. 4 de Av. Primavera.',
        'Surco-Chacarilla'
    ],
    '2':[
        -12.111373158361898,
        -76.98923774063587,
        '<center style="width: 230px; height: 167px;"><div style="width: 100%; text-align: center;"><img src="images/logo-osi.png" width="167"></div><div style="padding-top:10px; clear: both;"><p style="margin: 0; padding: 0;"><br>    <span style="color:#006980;font-family:Tahoma, Geneva, sans-serif;font-size:12px;text-align:-webkit-auto">Av. Del Pinar 198. Alt. 4 de Av. Primavera.<br/>(Dentro del reconocido Instituto Neurociencias de Lima)<br /><br /><span style="color:#504F51;font-family:Tahoma, Geneva, sans-serif;font-size:11px;text-align:-webkit-auto">Surco, Lima (Perú)</span><br></p></div></center>',
        'Av. Del Pinar 198. Alt. 4 de Av. Primavera.',
        'Surco-Chacarilla'
    ],
    'mapOlivos':[
        -11.990220022835667,
        -77.06866264343262,
        '<center style="width: 230px; height: 193px;"><div style="width: 100%; text-align: center;"><img src="images/logo-osi.png" width="167"></div><div style="padding-top:10px; clear: both;"><p style="margin: 0; padding: 0;"><font color="#006980"><span style="font-family:Tahoma, Geneva, sans-serif;font-size:14px;text-align:-webkit-auto; font-weight: bold;">Central Telefónica:<br/>739 0888</span></p><p style="margin: 0; padding: 0;"><br>    <span style="color:#006980;font-family:Tahoma, Geneva, sans-serif;font-size:12px;text-align:-webkit-auto">Av. José Santos Chocano 1010 Alt. Entre 5 y 6 de Carlos Izaguirre.<br /><br /><span style="color:#504F51;font-family:Tahoma, Geneva, sans-serif;font-size:11px;text-align:-webkit-auto">Los Olivos, Lima (Perú)</span><br></p></div></center>',
        'Av. José Santos Chocano 1010 Alt. 5 de Carlos Izaguirre',
        'Los olivos'
    ],
    '3':[
        -11.990220022835667,
        -77.06866264343262,
        '<center style="width: 230px; height: 160px;"><div style="width: 100%; text-align: center;"><img src="images/logo-osi.png" width="167"></div><div style="padding-top:10px; clear: both;"><p style="margin: 0; padding: 0;"><br>    <span style="color:#006980;font-family:Tahoma, Geneva, sans-serif;font-size:12px;text-align:-webkit-auto">Av. José Santos Chocano 1010 Alt. Entre 5 y 6 de Carlos Izaguirre.<br /><br /><span style="color:#504F51;font-family:Tahoma, Geneva, sans-serif;font-size:11px;text-align:-webkit-auto">Los Olivos, Lima (Perú)</span><br></p></div></center>',
        'Av. José Santos Chocano 1010 Alt. 5 de Carlos Izaguirre',
        'Los olivos'
    ],
    'mapLima':[
        /*...*/
        'Calle Saco Oliveros 295. Of. 303. Alt. 4 de Av. Arequipa.'
    ],
    '4':[
        /*...*/
        'Calle Saco Oliveros 295. Of. 303. Alt. 4 de Av. Arequipa.'
    ]
};

// MODAL LOGIN - REGISTRO ----------------------------------------------------------------------
// Resize modal segun sea Login o Registrarse
var settingPanelUser = function(affectedRow){
    $('#telefono').css('top',-115);
    var innr = '<span>Hola, <a href="/mi-cuenta">'+affectedRow.nom+'</a> | <a href="/logout">Salir</a></span>';
    $('#userCont').html(innr);
    $('#formLogin')[0].reset(); // Limpiando modal form login
    $('#telefono').animate({top:-10},{duration: 900, specialEasing:{top:"easeOutBounce"}});
};

$(function(){
    // SHOW PANEL LOGIN ------------------------------------------------------------------------
    // Mostrando panel superior-derecho Telefono & Link login y registrarse.
    var bgImgTlf = Global.baseHost+'/images/fondo-fono.png';
    var oImgTlf  = new Image();
    oImgTlf.onload = function(){
        $('#telefono').animate({top:-10},{duration: 900, specialEasing:{top:"easeOutBounce"}});
    };
    oImgTlf.onerror = function(){ console.log('img error!') };
    oImgTlf.src = bgImgTlf;
    console.log('oImgTlf:',oImgTlf);
    
    // LOGIN -----------------------------------------------------------------------------------
    // Setenado en Global.wantTab el tab seleccionado 
    Global.wantTab = '#tabLogin'; // Tab Predeterminando
    $('a[href="#login"],a[href="#register"]').bind('click', function(){
        Global.wantTab = ($(this).attr('href')=='#login')?'#tabLogin':'#tabRegister';
    });
    
    // Adicionando funcionalidad a evento Load-Modal
    $('#bstrModal').bind('shown.bs.modal', function(){
        // Mostrando Tab especifico, segun click en loguearse o registrase
        $('#loginTabs a[href="'+Global.wantTab+'"]').trigger('click');
    });
    
    var $login     = $('#loginTabs a[href="#tabLogin"]');
    var $register  = $('#loginTabs a[href="#tabRegister"]');
    var $formLogin = $('#formLogin');
    
    var reziseWidthModal = function(iNewWidth,idFocus){
        $('.modal-dialog').animate(
            {width: iNewWidth},{
                duration     : 700, 
                specialEasing: {width:"easeOutBounce"}, 
                complete     : function(){ $(idFocus).focus() }
            }
        );    
    };
    
    // Cambiando tamaño del modal cuando se cambia de login-register y viceversa
    $login.bind('click', function(){ reziseWidthModal(350,'#usunrodoc'); $(this).trigger('blur') });
    $register.bind('click', function(){ reziseWidthModal(550,'#usunom'); $(this).trigger('blur') });

    // AJAX LOGIN ------------------------------------------------------------------------------
    $formLogin.bind('submit', function(evt){
        evt.preventDefault();
        $('#loginWarn').hide('fast',"easeOutBounce"); // Ocultando msg errors
        var dataPost = $('#formLogin').find('input,select,textarea').serialize();
        var urlLogin = $('#formLogin').attr('action');
        $.ajax({type:"POST", url:urlLogin, data:dataPost})
        .done(function(rpta){
            if(rpta.session){
                $('#bstrModal').modal('hide');
                settingPanelUser(rpta.data.affectedRows[0]);
            }else{
                $('#loginWarn').show('fast',"easeOutBounce");
            }
        })
        .always(function(rpta){
            console.log(rpta);
        });
    });
    
    // AJAX REGISTRAR -------------------------------------------------------------------------------------
    /**
     * @requires Bootstrap Twitter JS.
     * Establece los tooltips para los inputs con errores (por comvencion
     * oErr devuelve como indices los ids de los inputs de contenedor con id)
     * igual a sId.
     * @param {String} sId Id del contenedor de los inputs con error.
     * @param {Object} oErr Id de los inputs con msgs de error.
     * @param {String} sPref Prefijo asociado a los id's (segun convencion).
     * @return {void} 
     **/
    var settingTooltips = function(sCId, oErr, sPref){
        $('#'+sCId).find('input,select,textarea').tooltip('destroy'); // delete before
        for(var id in oErr){
            var aMsg = [];
            for(var lbl in oErr[id]){
                aMsg.push((lbl=='isEmpty')?'No puede ser vacio':oErr[id][lbl]);
            }
            var inpId = (id.indexOf('id')==0)?id:(sPref+id); // si contiene 'id' a inicio no va sPref
            $('#'+sCId+' #'+inpId).tooltip({title:aMsg.join('|'),placement:"top",trigger:"hover focus"});
            $('#'+sCId+' #'+inpId).tooltip('show');
        }
    };
    
    // Creando mascara para fecha
    // El input que se envia por post(fecnac) esta oculto(hidden) pero se setea aca!
    $regfecnac = $('#formRegister [name="fecnac"]'); // fecha de nacimiento q se envia por post
    $('#formRegister #usufecnac').bind('blur',function(){
        var val = $.trim($(this).val());
        $regfecnac.val( val?moment(val).format('YYYY-MM-DD'):'' ); 
    }).mask('00/00/0000',{
        clearIfNotMatch:true,
        onComplete:function(val){ $regfecnac.val(moment(val).format('YYYY-MM-DD')) },
        onInvalid:function(/*args*/){ $regfecnac.val('') }
    });
    
    $('#formRegister #usucel')   .mask('000000000',{clearIfNotMatch:true});
    $('#formRegister #usunrodoc').mask('00000000' ,{clearIfNotMatch:true});
    $('#formLogin #usunrodoc')   .mask('00000000' ,{clearIfNotMatch:true});
    
    var $formRegister = $('#formRegister');
    
    $formRegister.bind('submit', function(evt){
        evt.preventDefault();
        $('#registerWarn').hide('fast',"easeOutBounce"); // Ocultando msg errors
        
        // Validando verifar email y pass
        var m = $.trim($('#usumail').val()), vm = $.trim($('#verifmail').val()),
            p = $.trim($('#inpsUser #usupass').val()), vp = $.trim($('#inpsUser #verifpass').val()),
            mailOk = (m && m==vm ), 
            passOk = (p && p==vp );

        if(!mailOk || !passOk){
            var sltr = !mailOk?(!passOk?'#verifmail,#verifpass':'#verifmail'):'#verifpass';
            $(sltr).tooltip({title:'No coinciden!',placement:"top",trigger:"hover"});
            $(sltr).tooltip('show');
            return ;
        }
        
        var dataPost    = $formRegister.find('input,select,textarea').serialize();
        var urlRegister = $formRegister.attr('action');
        $.ajax({type:"POST", url:urlRegister, data:dataPost})
        .done(function(rpta){
            
            if(!Global.isLogin && rpta.session){
                Global.isLogin = true;
                $('#bstrModal').modal('hide');
                $('#telefono').css('top',-115);
                var innr = '<span>Hola, <a href="/mi-cuenta">'+rpta.data.affectedRows.nom+'</a> | <a href="/logout">Salir</a></span>';
                $('#userCont').html(innr);
                $('#formLogin')[0].reset(); // Limpiando modal form login
                $('#telefono').animate({top:-10},{duration: 900, specialEasing:{top:"easeOutBounce"}});
            }
            
            if (rpta.data.hasOwnProperty('formErr')) {
                settingTooltips('formRegister', rpta.data.formErr, 'usu');
            }
            
        })
        .always(function(rpta){
            console.log(rpta);
        });
    });
    
    // 
});