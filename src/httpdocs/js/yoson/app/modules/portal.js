/*---------------------------------------------------------------------------------------------
 * METODOS Y VARIABLES GLOBALES
 * @Description: Metodos y variables que se podrian usaran en todo el portal.
 *//*------------------------------------------------------------------------------------------*/

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
};

// Posicionando scroll en el formulario de citas
var animateScroollTo = function(target){
    $target = $(target);
    $('html, body').stop().animate({
        'scrollTop': $target.offset().top
    }, 900, 'swing'/*, function(){window.location.hash = target;}*/ );
};

/*---------------------------------------------------------------------------------------------
 * MODULOS PARA TODO EL PORTAL
 * @Description: Implementacion de modulos para todo el portal
 *//*------------------------------------------------------------------------------------------*/

/**
 * @section: All
 * @Description: Trabaje con nosotros, ubicado en la parte inferior izquierda el 
 *               cual aparece con el evento scroll.
 */
yOSON.AppCore.addModule('widget-work-here', function(Sb){
    
    var $doctrabajo = $("#doc-trabajo"),
        $document   = $(document);
    
    return {
        init: function(oPars){
            // Si html widget no exite termina ejecucion
            if(!$doctrabajo.length){ 
                log(oPars.moduleName+'->init: Elemento "'+$doctrabajo.selector+'" No encontrado!');
                return ;
            }
            // si exite agregamos funcionalidades
            $("#doc-trabajo").css({"left":"-800px"});
            $document.scroll(function () {
                var top = $(window).scrollTop();
                $doctrabajo.stop(false, false).animate(
                    {"left":(top>=600)?"-223px":"-800px"}, (top >= 600)?250:200
                );
            });
        },
        
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
    
}, ['js/libs/jquery.easing-1.3.pack.js']);

/**
 * @section: Alls
 * @description: Funcionalidades para todas la secciones del portal (Index, nosotros, contactenos ... etc)
 */
yOSON.AppCore.addModule('all-varios', function (Sb) {
    
    // Variables para redes sociales
    var WEB = 'http://fisioterapiaosi.com/'
        ,TITULOWEB = 'RRB - '
        ,t = document.title
        ,getU = function(url){ return (url!=''&&url!='#')?WEB+''+url:location.href; }
        ,fb_click = function(url) {
            window.open(
                'http://www.facebook.com/sharer.php?u=' + encodeURIComponent(getU(url)) + '&t=' + encodeURIComponent(t), 'sharer',
                'toolbar=0,status=0,width=626,height=436');
            return false;
        }
        ,megusta = function(id, url){
            var str = '<iframe src="http://www.facebook.com/plugins/like?href='+getU(url)+'&amp;locale=es_ES&amp;layout=button_count&amp;show_faces=true&amp;width=120&amp;action=like&amp;font=arial&amp;colorscheme=light" scrolling="no" frameborder="0" style="border:none;width:120px;height:22px; margin-top: 0px;"></iframe>'
            //document.getElementById(id).innerHTML = str	
            id.replaceWith(str)
        }
        ,enviaramigo = function(url) {
            $.fancybox(WEB+'pop_enviar_amigo.php?pag='+getU(url)+'',{'autoDimensions':false,'width':390,'height':320,'type':'iframe','scrolling':'no','titleShow':false,'overlayOpacity': 0.8,'autoScale': false,'margin': 0,'padding':0})
        }
        ,open_twitter = function(url, tit) {
            var titulo = (tit!='')?TITULOWEB+tit:t;
            window.open("http://www.twitter.com/intent/tweet?text=A+mi+me+gusta+" + titulo.replace(/ /g, "+", titulo) + "&url="+getU(url), "Twitter", "toolbar=0, status=0, width=550, height=420");
        }
        ,gplus_click = function(url) {
            window.open(
                'https://plus.google.com/share?url=' + encodeURIComponent(getU(url)) + '&t=' + encodeURIComponent(t), 'sharer',
                'toolbar=0,status=0,width=500,height=436');
            return false;
        }
        ,$redSocial = $("#red-social")
        ,$preloadImgs = $('#preload_image')
    ;
    
    return {
        init: function(){
            
            // Implementando funcionalidad de redes sociales
            if ($redSocial.length > 0) {
                $redSocial.each(function () {
                    $(this).find("a").eq(0).click(function (e) {
                        e.preventDefault(); fb_click($(this).attr("href"));
                    });
                    $(this).find("a").eq(1).click(function (e) {
                        e.preventDefault(); open_twitter($(this).attr("href"), $(this).attr("title"));
                    });
                    $(this).find("a").eq(2).click(function (e) {
                        e.preventDefault(); enviaramigo($(this).attr("href"));
                    });
                    megusta($(this).find("a").eq(3), $(this).find("a").eq(3).attr("href"));
                })
            }
            // Precarga de algunas imagenes
            $.each(
                ['images/acv.jpg', 'images/artritis.jpg', 'images/cita.jpg', 'images/dolor-espalda.jpg', 'images/dolor-rodilla.jpg', 'images/escoliosis.jpg', 'images/especialidades.jpg', 'images/f.reumatologica.jpg', 'images/f.traumatologica.jpg', 'images/fibromialgia.jpg', 'images/lesion-articular.jpg', 'images/lesiones-deportivas.jpg', 'images/nosotros.jpg', 'images/porque-osi.jpg', 'images/salud-ocupacional.jpg', 'images/tendinitis.jpg', 'images/trabaja.jpg', 'images/hover-servicios.png'],
                function(i,v){ $preloadImgs.append('<img src="'+v+'" />') }
            );

            // Efecto opacity en paginas internas
            // $("#img").css({"opacity":"0"}).delay(300).animate({"opacity":"1"});
            // $("#nube").css({"opacity":"0"}).delay(800).animate({"opacity":"1"});
            // $("#columna2").css({"opacity":"0"}).delay(650).animate({"opacity":"1"});
            
            // Animacion del baner pricipal de las secciones (a exepcion  de index)
            $("#banner3").children("img").animate({"opacity": "1"}, 1000);
        }
    };
});

/**
 * @section: Alls
 * @Description: Login de usuario en el portal
 */
yOSON.AppCore.addModule('widget-login', function(Sb){
    
    var $telefono     = $('#telefono')
        ,$bstrModal   = $('#bstrModal')
        ,bgImgTlf     = Global.baseHost+'/images/fondo-fono.png'
        ,oImgTlf      = new Image()
        ,$login       = $('#loginTabs a[href="#tabLogin"]')
        ,$register    = $('#loginTabs a[href="#tabRegister"]')
        ,$formLogin   = $('#formLogin')
        ,$modalDialog = $('.modal-dialog')
        ,$loginWarn   = $('#loginWarn')
        ,$userCont    = $('#userCont')
        
        ,reziseWidthModal = function(iNewWidth,idFocus){
            $modalDialog.animate(
                {width: iNewWidth},{
                    duration     : 700, 
                    specialEasing: {width:"easeOutBounce"}, 
                    complete     : function(){ $(idFocus).focus() }
                }
            );    
        }
        // Evento submit para el login
        ,onLoginSubmit = function(evt){
            evt.preventDefault();
            $loginWarn.hide('fast',"easeOutBounce"); // Ocultando msg errors
            var dataPost = $formLogin.find('input,select,textarea').serialize();
            var urlLogin = $formLogin.attr('action');
            Sb.ajax({
                type:"POST", url:urlLogin, data:dataPost,
                fncDone:function(rpta){
                    if(rpta.session){
                        $bstrModal.modal('hide');
                        Sb.trigger('settingPanelUser',[rpta.data.affectedRows[0]]);
                    }else{
                        $loginWarn.show('fast',"easeOutBounce");
                    }
                },
                fncAlways:function(rpta){ log(rpta) }
            });
        }
    ;
    return {
        
        init: function(oPars){
            // Si html widget no exite termina ejecucion
            if(!$telefono.length){ 
                log(oPars.moduleName+'->init: Elemento "'+$telefono.selector+'" No encontrado!');
                return ;
            }
            oImgTlf.onload = function(){
                $telefono.animate({top:-10},{duration: 900, specialEasing:{top:"easeOutBounce"}});
            };
            oImgTlf.onerror = function(){ log('img error!') };
            oImgTlf.src = bgImgTlf; log('oImgTlf:',oImgTlf);
            
            // Setenado en wantTab el tab seleccionado (Para el modal Login & Register)
           $bstrModal.data('wantTab','#tabLogin'); // Tab Predeterminando
            $('a[href="#login"],a[href="#register"]').bind('click', function(){
                $bstrModal.data('wantTab',($(this).attr('href')=='#login')?'#tabLogin':'#tabRegister');
            });
            
            // Adicionando funcionalidad a evento Load-Modal
            $bstrModal.bind('shown.bs.modal', function(){
                // Mostrando Tab especifico, segun click en loguearse o registrase
                $('#loginTabs a[href="'+$bstrModal.data('wantTab')+'"]').trigger('click');
            });
            
            // Cambiando tamaño del modal cuando se cambia de login-register y viceversa
            $login.bind('click', function(){ reziseWidthModal(350,'#usunrodoc'); $(this).trigger('blur') });
            $register.bind('click', function(){ reziseWidthModal(550,'#usunom'); $(this).trigger('blur') });
            
            // AJax Login
            $formLogin.bind('submit', onLoginSubmit);
            
            // Registrando evento settingPanelUser
            Sb.events(['settingPanelUser'], this.settingPanelUser, this); 
        },
        
        settingPanelUser: function(affectedRow){
            $telefono.css('top',-115);
            var innr = '<span>Hola, <a href="/mi-cuenta">'+affectedRow.nom+'</a> | <a href="/logout">Salir</a></span>';
            $userCont.html(innr);
            $formLogin[0].reset(); // Limpiando modal form login
            $telefono.animate({top:-10},{duration: 900, specialEasing:{top:"easeOutBounce"}});
        },
        
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
    
}, ['js/libs/jquery.easing-1.3.pack.js']);

/**
 * @section: Alls
 * @Description: Registro de usuario en el portal
 */
yOSON.AppCore.addModule('usuario-registrar', function(Sb){
    
    var $regfecnac     = $('#formRegister [name="fecnac"]')
        ,$formRegister = $('#formRegister')
        ,$registerWarn = $('#registerWarn')
        
        ,onRegisterSubmit = function(evt){
            evt.preventDefault();
            $registerWarn.hide('fast',"easeOutBounce"); // Ocultando msg errors

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
            
            Sb.ajax({
                type:"POST", url:urlRegister, data:dataPost,
                fncDone: function(rpta){

                    if(!Global.isLogin && rpta.session){
                        Global.isLogin = true;
                        $('#bstrModal').modal('hide');
                        Sb.trigger('settingPanelUser', [rpta.data.affectedRows[0]]);
                    }

                    if (rpta.data.hasOwnProperty('formErr')) {
                        Sb.trigger('settingTooltips',['formRegister',rpta.data.formErr,'usu']);
                    }

                },
                fncAlways: function(){ log(arguments) }
            });
        }
    ;
    
    return {
        init: function(oPars){
            $('#formRegister #usufecnac').bind('blur',function(){
                var val = $.trim($(this).val());
                $regfecnac.val( val?moment(val).format('YYYY-MM-DD'):'' ); 
            }).mask('00/00/0000',{
                clearIfNotMatch:true,
                onComplete:function(val){ $regfecnac.val(moment(val).format('YYYY-MM-DD')) },
                onInvalid:function(){ $regfecnac.val('') }
            });
            
            $('#formRegister #usucel')   .mask('000000000',{clearIfNotMatch:true});
            $('#formRegister #usunrodoc').mask('00000000' ,{clearIfNotMatch:true});
            $('#formLogin #usunrodoc')   .mask('00000000' ,{clearIfNotMatch:true});
            
            // Registrando evento SettingTooltips
            Sb.events(['settingTooltips'], this.settingTooltips, this); 
            
            // adicionando evento submit a formregister
            $formRegister.bind('submit', onRegisterSubmit);
        },
        
        settingTooltips: function(sCId, oErr, sPref){
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
        },
        
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
    
}, 
['vendor/moment/min/moment.min.js','vendor/jQuery-Mask-Plugin/dist/jquery.mask.min.js']);

/**
 * @section: All
 * @Description: Muestra el mapa de gmaps,en un iframe, segun los parametros que se 
 *               mandan a la funcion contenida en el iframe llamanda 'initialize'
 */
yOSON.AppCore.addModule('portal-mapa', function(Sb){
    
    var mapsData = {
        'mapMrflrs':[
            -12.116313916501243,-77.02660493552685,
            '<center style="width: 230px; height: 207px;"><div style="width: 100%; text-align: center;"><img src="images/logo-osi.png" width="167"></div><div style="padding-top:10px; clear: both;"><p style="margin: 0; padding: 0;"><font color="#006980"><span style="font-family:Tahoma, Geneva, sans-serif;font-size:14px;text-align:-webkit-auto; font-weight: bold;">Central Telefónica:<br/>739 0888</span></p><p style="margin: 0; padding: 0;"><br>    <span style="color:#006980;font-family:Tahoma, Geneva, sans-serif;font-size:12px;text-align:-webkit-auto">Calle González Prada 385. Alt. 50 de Paseo de la Republica.<br/>(Frente del Teatro Marsano, Espalda del Hiraoka de Miraflores)<br /><br /><span style="color:#504F51;font-family:Tahoma, Geneva, sans-serif;font-size:11px;text-align:-webkit-auto">Miraflores, Lima (Perú)</span><br></p></div></center>',
            'Calle Gonzáles Prada 385 Alt.50 Paseo de la República.','Miraflores'
        ],
        '1':[
            -12.116313916501243,-77.02660493552685,
            '<center style="width: 230px; height: 167px;"><div style="width: 100%; text-align: center;"><img src="images/logo-osi.png" width="167"></div><div style="padding-top:10px; clear: both;"><p style="margin: 0; padding: 0;"><br>    <span style="color:#006980;font-family:Tahoma, Geneva, sans-serif;font-size:12px;text-align:-webkit-auto">Calle González Prada 385. Alt. 50 de Paseo de la Republica.<br/>(Frente del Teatro Marsano, Espalda del Hiraoka de Miraflores)<br /><br /><span style="color:#504F51;font-family:Tahoma, Geneva, sans-serif;font-size:11px;text-align:-webkit-auto">Miraflores, Lima (Perú)</span><br></p></div></center>',
            'Calle Gonzáles Prada 385 Alt.50 Paseo de la República.','Miraflores'
        ],
        'mapSurco':[
            -12.111373158361898,-76.98923774063587,
            '<center style="width: 230px; height: 207px;"><div style="width: 100%; text-align: center;"><img src="images/logo-osi.png" width="167"></div><div style="padding-top:10px; clear: both;"><p style="margin: 0; padding: 0;"><font color="#006980"><span style="font-family:Tahoma, Geneva, sans-serif;font-size:14px;text-align:-webkit-auto; font-weight: bold;">Central Telefónica:<br/>739 0888</span></p><p style="margin: 0; padding: 0;"><br>    <span style="color:#006980;font-family:Tahoma, Geneva, sans-serif;font-size:12px;text-align:-webkit-auto">Av. Del Pinar 198. Alt. 4 de Av. Primavera.<br/>(Dentro del reconocido Instituto Neurociencias de Lima)<br /><br /><span style="color:#504F51;font-family:Tahoma, Geneva, sans-serif;font-size:11px;text-align:-webkit-auto">Surco, Lima (Perú)</span><br></p></div></center>',
            'Av. Del Pinar 198. Alt. 4 de Av. Primavera.','Surco-Chacarilla'
        ],
        '2':[
            -12.111373158361898,-76.98923774063587,
            '<center style="width: 230px; height: 167px;"><div style="width: 100%; text-align: center;"><img src="images/logo-osi.png" width="167"></div><div style="padding-top:10px; clear: both;"><p style="margin: 0; padding: 0;"><br>    <span style="color:#006980;font-family:Tahoma, Geneva, sans-serif;font-size:12px;text-align:-webkit-auto">Av. Del Pinar 198. Alt. 4 de Av. Primavera.<br/>(Dentro del reconocido Instituto Neurociencias de Lima)<br /><br /><span style="color:#504F51;font-family:Tahoma, Geneva, sans-serif;font-size:11px;text-align:-webkit-auto">Surco, Lima (Perú)</span><br></p></div></center>',
            'Av. Del Pinar 198. Alt. 4 de Av. Primavera.','Surco-Chacarilla'
        ],
        'mapOlivos':[
            -11.990220022835667,-77.06866264343262,
            '<center style="width: 230px; height: 193px;"><div style="width: 100%; text-align: center;"><img src="images/logo-osi.png" width="167"></div><div style="padding-top:10px; clear: both;"><p style="margin: 0; padding: 0;"><font color="#006980"><span style="font-family:Tahoma, Geneva, sans-serif;font-size:14px;text-align:-webkit-auto; font-weight: bold;">Central Telefónica:<br/>739 0888</span></p><p style="margin: 0; padding: 0;"><br>    <span style="color:#006980;font-family:Tahoma, Geneva, sans-serif;font-size:12px;text-align:-webkit-auto">Av. José Santos Chocano 1010 Alt. Entre 5 y 6 de Carlos Izaguirre.<br /><br /><span style="color:#504F51;font-family:Tahoma, Geneva, sans-serif;font-size:11px;text-align:-webkit-auto">Los Olivos, Lima (Perú)</span><br></p></div></center>',
            'Av. José Santos Chocano 1010 Alt. 5 de Carlos Izaguirre','Los olivos'
        ],
        '3':[
            -11.990220022835667,-77.06866264343262,
            '<center style="width: 230px; height: 160px;"><div style="width: 100%; text-align: center;"><img src="images/logo-osi.png" width="167"></div><div style="padding-top:10px; clear: both;"><p style="margin: 0; padding: 0;"><br>    <span style="color:#006980;font-family:Tahoma, Geneva, sans-serif;font-size:12px;text-align:-webkit-auto">Av. José Santos Chocano 1010 Alt. Entre 5 y 6 de Carlos Izaguirre.<br /><br /><span style="color:#504F51;font-family:Tahoma, Geneva, sans-serif;font-size:11px;text-align:-webkit-auto">Los Olivos, Lima (Perú)</span><br></p></div></center>',
            'Av. José Santos Chocano 1010 Alt. 5 de Carlos Izaguirre','Los olivos'
        ],
        'mapLima':[/*...*/'Calle Saco Oliveros 295. Of. 303. Alt. 4 de Av. Arequipa.'],
        '4':[/*...*/'Calle Saco Oliveros 295. Of. 303. Alt. 4 de Av. Arequipa.']
    };

    return {
        init: function(oPars){
            // Publicando evento show map
            // Sb.events(['set-map'], this.setPositionMap, this);
        },
        
        /**
         * Por convencion id y name del iframe son iguales en valor
         * @param {string} sIdPoint Id de lugar: mapMrflrs,mapSurco,..
         * @param {jQuery} $ifrm objeto jQuery asociado al iframe
         * @param {boolean} bIsCita Condicion para la seccion Citas
         * @return {Array} mapData Datos de la nueva posicion del mapa
         **/
        setPositionMap: function(sIdPoint, $ifrm, bIsCita){
            
            var sNameIfrm = $ifrm.attr('name')
               ,frameMapa = window.frames[sNameIfrm]
               ,mapData   = mapsData[sIdPoint]
            ;

            if($ifrm.length){
                if(frameMapa.initialize){
                    frameMapa.initialize(mapData[0], mapData[1], mapData[2], bIsCita);
                }else{
                    $ifrm.bind('load', function(){
                        frameMapa.initialize(mapData[0], mapData[1], mapData[2], bIsCita);
                    });
                }
            }else{
                log('portal-mapa->setPositionMap: iframe "'+sNameIfrm+'" No encontrado!');
            }
            return mapData;
        },
        
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
    
});

/**
 * @section: Index
 * @Description: Funcionalidades de los menus
 */
yOSON.AppCore.addModule('portal-menus', function(Sb){
    
    var $servicio = $(".servicio");
    var oFnc = {
        
        // Menus desplegables en la parte superior
        fncMenuDesplegables:function(){
            var selectors = {"#desp3":"#menu_nosotros","#desp1":"#menu_especialidad","#desp2":"#menu_enfermedad"};
            $.each(selectors, function(i, v){
               $(i).bind("mouseenter mouseleave",function(){$(this).children(v).stop(false, true).slideToggle()});
            });
        },
        
        // Barrita que se posiciona en el item del menu al hacer click
        fncMenuBarritaEfect: function(){
            var pos = 0, $barrita=$(".barrita");
            var wl = [
                ["40px","0px"],["88px","76px"],["117px","205px"],["210px","359px"],
                ["74px","595px"],["155px","685px"],["125px","863px"]
            ];
            $("#menu-content").children("ul").children("li").each(function (i) {
                if ($(this).children("a").hasClass("activo")) pos = i;

                $barrita.stop(true, false).animate({"width":wl[pos][0], "left":wl[pos][1]}, 800);
                $(this).mouseenter(function () {
                    $barrita.stop(true, false).animate({"width": wl[i][0], "left": wl[i][1]}, 800);
                });
                $(this).mouseleave(function () {
                    $barrita.stop(true, false).animate({"width": wl[pos][0], "left": wl[pos][1]}, 800);
                });
                //console.log('pos & i:', pos, i);
            });            
        },

        // Menu debajo del superior con efecto hover
        fncMenuInferiorHover: function(){
            //$servicio.css({"overflow":"hidden", "cursor":"pointer"}); // Ahora esta en estilos.css
            $(".hover-servicio").css({"opacity":"0"}); // Lo que sube al hover
            $(".hover-circulo").css({"top":"-20px"});  // circulo, que baja al hover

            $servicio.each(function(i){
                $(this).bind('mouseenter mouseleave',function(e){
                    var enter = (e.type==='mouseenter');
                    $(".hover-servicio").eq(i).stop(true, false).animate({"opacity":enter?"1":"0","top":enter?"0px":"54px"});
                    $(".icono-servicio").eq(i).stop(true, false).animate({"margin-top":enter?"50px":"15px"},300);
                    $(".hover-circulo").eq(i).stop(true, false).animate({"top":enter?"20px":"-20px"}, 300);
                    $(".tex-servicio").children("a").eq(i).stop(true, false).css({"color":enter?"#BDFDF6":"#fff"});
                });
            });
        }
    };
    
    return {
        init: function(oPars){
            oFnc.fncMenuBarritaEfect();
            oFnc.fncMenuDesplegables();
            oFnc.fncMenuInferiorHover();
        },
        
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
    
});

/**
 * @section: Index
 * @description: links para ver nuestras sedes, ubicados en el footer del portal
 */
yOSON.AppCore.addModule('visita-nuestras-sedes', function(Sb){
    
    var $bstrModalMapa = $('#bstrModalMapa')
        ,$ifrmMapaModal = $('#ifrmMapaModal')
        ,$links = $('.taco2 a')
        ,isCita = (Global.action=='solicite-cita2' || Global.action=='solicite-cita')
        ,$ModPortalMapa = yOSON.AppCore.getModule('portal-mapa').instance
    ;
    
    return {
        init: function(oPars){
            
            if(!$links.length){
                log(oPars.moduleName+'->init: No hay link para ver mapas'); return ;
            }
            var that = this;
            $ifrmMapaModal.bind('load', function(){ that.loadIfrm=true } );
            
            $bstrModalMapa.bind('shown.bs.modal', function(evt){
                log('that.loadIfrm:',that.loadIfrm);
                if(!that.loadIfrm) $ifrmMapaModal.attr('src','/mapa');
                var $link = $(evt.relatedTarget);
                log('id-map:'+$link.attr('id-map'));
                $ModPortalMapa.setPositionMap($link.attr('id-map'), $ifrmMapaModal, isCita);
            });

        }
        ,loadIfrm: false
    };
});

/**
 * @section: Index
 * @description: Slide del home
 */
yOSON.AppCore.addModule('home-slide', function(Sb){
    var $frase = $(".frase")
        ,$flechas = $("#flecha2 a,#flecha1 a")
    ;
    return {
        init:function(){
            
            $frase.animate({"margin-left": -250, opacity: 0}, 0)
            $frase.eq(0).animate({"margin-left": 0, opacity: 1})
            if($.supersized){
                $(function(){
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
                        slides: [// Slideshow Images
                            {image: 'images/banner/banner1.jpg'},
                            {image: 'images/banner/banner2.jpg'},
                            {image: 'images/banner/banner3.jpg'},
                            {image: 'images/banner/banner4.jpg'},
                            {image: 'images/banner/banner5.jpg'}
                        ],
                        // Theme Options			   
                        progress_bar: 1, // Timer for each slide							
                        mouse_scrub: 0
                    });

                    // Funcionalidad de desplazamiento del Slide con las flechas
                    $flechas.bind('click', function(e){
                        e.preventDefault();
                        ($(this).attr('id')=='arrow2')?api.nextSlide():api.prevSlide();
                    });                    
                });
            }
            
        }
    };
});

/**
 * @section: Index
 * @description: Funcionalidades, efectos, estilos etc para el index del portal
 */
yOSON.AppCore.addModule('index-varios', function(Sb){
    
    var $scrollbar1 = $('#scrollbar1')
        ,$list_espe = $("#list_espe")
    ;
    
    return {
        init: function(){
            // Scrolling para testimonios
            if(!$scrollbar1.length || !$scrollbar1.tinyscrollbar){
                log('index-varios->init: No existe #scrollbar1 o tinyscrollbar');
                return ;
            }
            $scrollbar1.tinyscrollbar({sizethumb:17});
            
            // Efectos en contenidos de index
            $list_espe.children(".lista-especialidades").css({"opacity": "0"})
            $list_espe.children(".lista-especialidades").each(function (i) {
                $(this).delay(i * 700).animate({"opacity": "1"});
            });
            $("#texto-red1").css({"left":"-1069px"}).animate({"left":"0px"},1000);
            $("#texto-red2").css({"left":"990px"}).animate({"left":"0px"},1000);
            $("body").css({"overflow-x":"hidden"});
            //$("#testimonios").css({"opacity":"0"}).delay(1000).animate({"opacity":"1"},800);
            //$("#especialidades").css({"opacity":"0"}).delay(1500).animate({"opacity":"1"},800);
            
            //Logos footer - Osi Compañias - animacion hover
            $(".logo-individual").hover(
                function(){ $("img.a", this).stop().animate({"opacity":"0"}, 400) },
                function(){ $("img.a", this).stop().animate({"opacity": "1"}, 300) }
            );
        }
    };
    
},['js/libs/jquery.tinyscrollbar.min.js']);

/**
 * @seccion nosotros
 * @page staff
 * @description Animacion de acordeones y desplegables para 'Nuestros especialistas'.
 */
yOSON.AppCore.addModule('nuestros-especialistas', function(Sb){
    
    var $sub_especialidad2 = $(".sub-especialidad2");
    
    return {
        init: function(){
            // Carrusel para otras especialidades
            $sub_especialidad2.css({"cursor": "pointer"});
            var pos = 0;
            $sub_especialidad2.each(function(i){
                $(this).click(function(e){
                    e.preventDefault();
                    if (pos != i) {
                        $(".staff-detalle").eq(i).slideDown();
                        $(".simbolo").eq(pos).show();
                        $(".simbolo2").eq(pos).hide();
                        $(".simbolo").eq(i).hide();
                        $(".simbolo2").eq(i).show();
                        $(".staff-detalle").eq(pos).slideUp();
                        pos = i;
                    }
                })
            });
        }
    };
});

/**
 * @seccion Contactenos
 * @description Panel para mostrar sedes.
 */
yOSON.AppCore.addModule('contactenos-sedes', function(Sb){
    
    var $mapas = $("#mapas")
        ,$ifrmMapa = $('#ifrmMapa')
        ,$ModPortalMapa = yOSON.AppCore.getModule('portal-mapa').instance
    ;
    
    return {
        init: function(){
            $mapas.find("a").each(function(i){
                $(this).click(function(e){
                    e.preventDefault();
                    $mapas.find("a").removeClass('activo');
                    $(this).addClass('activo');
                    var mapData = $ModPortalMapa.setPositionMap(this.id, $ifrmMapa, false);
                    log('mapData*-*-*->>>>>>:',mapData);
                    $('#mapDirec').html(mapData[3]); // Seteando direccion
                });
            });
        }
    };
    
});

/**
 * @seccion solicitar-cita
 * @description Agendar fecha y hora de la cita
 */
yOSON.AppCore.addModule('agendar-cita', function(Sb){
    
    var $frmhorario    = $('#frmhorario')
        ,$frmespec     = $('#frmespec')
        ,$usufecnac    = $('#usufecnac')
        ,$idhorario    = $('#idhorario')
        ,$datehour     = $('#datehour')
        ,$citaHoras    = $('#citaHoras')
        ,$idseguro     = $('#idseguro')
        ,$frmsede      = $('#frmsede')
        ,$citaVerMapa  = $('#citaVerMapa')
        ,$rowIfrmMapa  = $('#rowIfrmMapa')
        ,$lblDir       = $('#lblDir')
        ,$ifrmMapa     = $('#ifrmMapa')
        ,loadIfrmMap   = false
        ,$ModPortalMapa= yOSON.AppCore.getModule('portal-mapa').instance
        ,setValidHour  = false // Cuando el usuario seleccione una hora valida 
        
        ,onClickLinkVerMapa = function(){
            // Cargando mapa
            if(!loadIfrmMap){
                $ifrmMapa.bind('load',function(){ loadIfrmMap=true; }).attr('src','/mapa');
            }

            $rowIfrmMapa.slideToggle('slow', 'easeOutBounce', function(){
                $frmsede.trigger('change');
                var text = $.trim($citaVerMapa.html());
                $citaVerMapa.html( (text=='Ver mapa')?'Ocultar mapa':'Ver mapa' );
            });
        }

        /***
         * @description Retorna array de html_buttons para que sea seleccionados al click!
         * Trabaja exclusivamente con el metodo "getCitasByEspec" de mas abajo.
         * @param {Object} rpta Json {idshs:'1|2|..',inis:'..',fins:'..',citas:'..'}
         * @returns {Array} Elementos son html de buttons como string 
         */
        ,fncAjaxGetButtonsHours = function(rpta){
            // Todas con la misma longitud
            var idshs=rpta['idshs'].split('|')
                ,inis=rpta['inis'].split('|')
                ,fins=rpta['fins'].split('|')
                ,citas=rpta['citas']?rpta['citas'].split('|'):[]
                ,len=fins.length
                ,df='YYYY-MM-DD HH:mm:ss' // Formato de fecha
                ,hf='hh:mm a' // Formato de hora
                ,now=moment().format(df)
                ,delta=15
                ,gpoHrs ={}
            ;
            // Genearando data para los tags de horas
            for(var i=0; i<len; i++){
                var ini=inis[i], // Fecha inicio para un horario
                    fin=fins[i], // fecha fin para un horario
                    idh=idshs[i] // id de horario
                ;
                gpoHrs[idh]=[];  // Grpo de horas (horario)
                var c=0;
                var next=moment(ini).add(delta*c,'m').format(df); // Siguiente momento (Incr en delta)

                while( moment(next).isBefore(fin) ){
                    var last=!moment(next).isAfter(now); // pasado
                    var exst = ($.inArray(next,citas)>-1); // Ya fue tomado
                    // añadiendo datos para tag hora
                    gpoHrs[idh].push({date:next,hour:moment(next).format(hf),last:last,exst:exst});
                    c++;
                    next=moment(ini).add(delta*c,'m').format(df);
                }
            }
            // generando tags de hora (idh hace referencia a id de gpo)
            var aHorarios = [];
            for(var j in gpoHrs){
                var gpo = gpoHrs[j],str='';
                for(var k in gpo){
                    var dsbd = (gpo[k].exst || gpo[k].last)?'disabled':'';
                    var last = gpo[k].last?' tag-last':'';   // Para diferenciarlo de uno pasado o uno seleccionado por Websocket
                    var rsrv = gpo[k].exst?' tag-reserv':''; // Para diferenciarlo de un pagado de uno seleccionado x websocket
                    var type = gpo[k].exst?'info':'info';    // antes: if true 
                    var hour = gpo[k].hour;
                    var date = gpo[k].date;
                    str+='<button type="button" cita-date="'+date+'" cita-idh="'+j+'" class="btn btn-'+type+rsrv+last+'" '+dsbd+'>'+hour+'</button>';
                }
                aHorarios.push(str);
            }
            return aHorarios;
        }

        ,fncAjaxSetSelEspec = function(rpta) {
            $frmespec.html('<option value="0">- Elija especialista</option>');
            $.each(rpta.data, function(i, o){
                $frmespec.append($('<option />',{value:o.idespecialista,text:o.nom+' '+o.ape}));
            });
            $frmhorario.prop('disabled',false); // Habilitando horario
            $frmespec.prop('disabled', false).focus(); // foco hacia especialistas
        }
        
        ,fncAjaxCreateButtonsHours = function(rpta) {
            
            Sb.trigger('succAjaxCreateButtonsHours', [rpta]);
            
            if( !(rpta.hasOwnProperty('data') && rpta['data'].length) ) {
               log('agendar-cita->No existe data rpta!'); return;
            }
            // Añadiendo los tags horas separando grupos por br's
            $citaHoras.html(fncAjaxGetButtonsHours(rpta.data[0]).join('<br /><br />'));

            // Adicionando funcionalidad click a nuevos elementos menos a los disabled's
            $('#citaHoras button').not(':disabled').bind('click', function(){
                Sb.trigger('onClickBtnsHours', [this]);
            });
            $citaHoras.slideDown('slow','easeOutBounce');
        }

        /***
         * @dependency getEspecByFecha
         * @param {type} val
         * @param {type} fncExt
         * @returns {undefined}
         */


        ,onChangeSelSeguro = function(selVal){
            $frmsede.prop('disabled', !selVal);
            if(!selVal){
                // Reseteando Sede
                $frmsede.prop('selectedIndex',0);
                $frmsede.trigger('change');
            }else{ // open sede
                $frmsede.focus();
                //openSelect("#frmsede");
                $frmsede.trigger('mousedown').trigger('click');
            }
        }

        ,onChangeSelSede = function(val){
            $citaVerMapa.prop('disabled', !val);
            if(val){
                var mapData = $ModPortalMapa.setPositionMap(val, $ifrmMapa, true);
                $lblDir.html(mapData[4]+' - '+mapData[3]); // setting direccion y sede
                $frmhorario.prop('selectedIndex',0).trigger('change').prop('disabled', false).focus();
            }else{
                $rowIfrmMapa.fadeOut('slow', function(){ $citaVerMapa.html('Ver mapa'); });
                $frmhorario.prop('selectedIndex',0).trigger('change'); // Resetando Horario
            }
            $frmhorario.prop('disabled', !val);
        }

        ,onChangeSelHorario = function(val, THIS, MOD){
            // Reseteando data especialista
            $frmespec.html('<option>- Elija especialista</option>');
            $frmespec.prop('selectedIndex',0).trigger('change').prop('disabled',true);

            if(val){
                var idsede = $.trim($frmsede.val());
                var fecha  = $.trim($(THIS).val());

                $frmhorario.prop('disabled', true); // lo habilita el ajax
                $frmespec.prop('disabled', true); // lo habilita el ajax
                MOD.getEspecByFecha(idsede,fecha, fncAjaxSetSelEspec);  // Cargando Especialistas por ajax
            }
        }

        ,onchangeSelEspec = function(idespec, MOD){

            var idsede = parseInt($.trim($frmsede.val()));
            var fecha  = $.trim($frmhorario.val());
            
            $citaHoras.slideUp('slow','easeOutBounce', function(){ log("HorasSlideUp!!!"); }); // oculta anterior resultado
            $idhorario.val(''); // Clear idhr
            $datehour.val('');  // Clear fecha y hora de cita
            
            setValidHour = false; // Aun no selecciona una hora (o seleccionarlo de nuevo).
            
            Sb.trigger('beforeGetCitasByEspec',[idespec]); // Disparando evento

            if(!idsede || !idespec || !fecha) return ;

            MOD.getCitasByEspec(idsede, idespec, fecha, fncAjaxCreateButtonsHours);
        }
        
        /***
         * Evento que se dispara cuando se hace click en los tags de horas cuando se selecciona
         * un especialista en solicite una cita.
         * @param {HtmlElement} THIS
         * @returns {void}
         */
        ,onClickBtnsHours = function(THIS){
            
            log('agendar-cita->event:onClickBtnsHours');
            $idhorario.val( $(THIS).attr('cita-idh') ); // Guadando idhorario
            $datehour.val( $(THIS).attr('cita-date') ); // Guardando dia y hora

            $('#citaHoras button').removeClass('active btn-success').addClass('btn-info');
            $(THIS).removeClass('btn-info').addClass('active btn-success').trigger('blur');
            setValidHour = true;
        }
    ;

    return {
        
        init: function(oPars){
            
            var MOD = this;
            
            // Si ya se esta en session setting mask fecnac
            if(Global.isLogin){
                $usufecnac.val(moment($('#frmUser [name="fecnac"]').val()).format('DD/MM/YYYY'));
            }
            
            // Registrando eventos
            Sb.events(['onClickBtnsHours'], onClickBtnsHours, MOD);
            
            Sb.events(['beforeGetCitasByEspec'], function(idespec){
                log('agendar-cita->init->event->beforeGetCitasByEspec: function void');
            }, MOD);

            // Registrando eventos
            Sb.events(['succAjaxCreateButtonsHours'], function(){
                console.log('*-*-*-*-*>>>>>>>>arguments:',arguments);
                console.log('agendar-cita->succAjaxCreateButtonsHours!!');
            }, MOD);
            
            // Select: Tipo de seguro
            $idseguro.bind('change', function(){
                var selVal = parseInt($(this).val());
                onChangeSelSeguro(selVal);
            });

            // Select: Elegir sede
            $frmsede.bind('change', function(e, val){
                val = parseInt( (typeof(val)==='undefined')?$(this).val():val );
                onChangeSelSede(val);
            });

            // Select: Elegir horario
            $frmhorario.bind('change', function(e, val){
                val = parseInt( (typeof(val)==='undefined')?$(this).val():val );
                onChangeSelHorario(val, this, MOD);
            });

            // Select: Elegir Especialista
            $frmespec.bind('change', function(e, val){ // al ser el ultimos select 'val' no usado
                var idespec = parseInt($.trim($(this).val())); log("frmespec->change");
                onchangeSelEspec(idespec, MOD);
            });

            // Link: mostrar u ocultar mapa
            $citaVerMapa.bind('click', onClickLinkVerMapa);
        },
        
        /**
         * @description Obtiene los especialistas segun la sede y la fecha que se elija por ajax.
         * @param {Integer} idsede Codigo de la sede.
         * @param {String} fecha Fecha en formato: yyyy-mm-dd 
         */
        getEspecByFecha: function(idsede, sFecha, fncExt){ // get-espec/1/2014-10-21
            var getUrl = Global.baseHost+'/get-espec/'+idsede+'/'+sFecha;
            Sb.ajax({type:"GET", url:getUrl, fncDone:fncExt });
        },
        
        /**
         * @description Obtiene las citas por especialista para una fecha dada
         * @param {Integer} idsede Id de la sede
         * @param {Integer} idespec ID del especialista
         * @param {String} sFecha fecha en formato yyyy-mm-dd 
         * @param {Function} Funcion externa para asociarlo a la logica de este metodo (Opcional)
         **/
        getCitasByEspec: function(idsede, idespec, sFecha, fncExt){ // get-citas-espec/1/2/2014-10-29
            var url = Global.baseHost+'/get-citas-espec/'+idsede+'/'+idespec+'/'+sFecha;
            Sb.ajax({type:"GET", url:url, fncDone:fncExt});
        },
        
        getIsValidHour: function(){ return setValidHour; }
    };
},['vendor/moment/min/moment.min.js']);

/***
 * 
 */
yOSON.AppCore.addModule('procesar-usuario', function(Sb){
    
    var $frmUser    = $('#frmUser')
        ,$hdnfecnac = $frmUser.find('[name="fecnac"]') // fecnac que se envia por POST (hidden)
        ,$usufecnac = $frmUser.find('#usufecnac') // El que se ve en el formulario
        ,$usumail   = $('#usumail')
        ,$verifmail = $('#verifmail')
        ,$btnlogin  = $('#btnLogin')
        
        ,settingTooltips = function(sCId, oErr, sPref){
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
        }
        
        ,fncAjaxRegistrar= function(rpta, fncExt){
            // Ajax registro success or done
            Sb.trigger('ajaxRegistrar',[]);
            
            // Si aun no estaba logeado y me logue!
            if(!Global.isLogin && rpta.session){
                Global.isLogin = true;
                Sb.trigger('usuarioLogueado',[rpta.data.affectedRows]);//[0]
            }
            // Formulario valido
            if(rpta.valid){
                Sb.trigger('usuarioValido',[rpta.data.affectedRows]);//[0]
            }
            // Update ok
            if(rpta.state){ Sb.trigger('usuarioUpdate',[]); }
            
            if(rpta.data.hasOwnProperty('formErr')){
                settingTooltips('frmUser', rpta.data.formErr, 'usu');
            } console.log(rpta);
            
            // Funcion externa pasada
            if(fncExt){ fncExt(); }
        }

        ,fncAjaxLogin = function(rpta, fncExt){

            Sb.trigger('ajaxLogin',[]); // Ajax registro success or done
            
            // Si no hay sesion
            if (!rpta.session) {
                log('fncAjaxLogin->rpta.data.formErr:',rpta.data.formErr);
                if(rpta.data.hasOwnProperty('formErr')){
                    settingTooltips('frmLogin', rpta.data.formErr, 'usu');
                } else { alert('Dni o Contraseña incorrectos!'); }
                Sb.trigger('loginIsFalse', []);
            } else { // Sesion!
                Global.isLogin = true; // Setting global param
                Sb.trigger('settingPanelUser', [rpta.data.affectedRows[0]]);// Cambiando panel superior de usuario
                Sb.trigger('loginIsTrue', [rpta.data.affectedRows[0]]);
            }
            log(rpta);
            if(fncExt){ fncExt(); } // Funcion externa pasada
        }
    ;

    return {
        init: function(){
            var MOD = this;
            // Evento blur y add mask para input fecnac que es visible en el form
            $usufecnac.bind('blur',function(){
                var val = $.trim($(this).val());
                $hdnfecnac.val( val?moment(val).format('YYYY-MM-DD'):'' );
            }).mask('00/00/0000',{
                clearIfNotMatch:true,
                onComplete:function(val){ $hdnfecnac.val(moment(val).format('YYYY-MM-DD')); },
                onInvalid:function(){ $hdnfecnac.val(''); }
            });
            
            // Eventos para LOGIN ------------------------------------------------------
            // Inicializando eventos para login
            Sb.events(['beforeAjaxLogin'], function(){
                log('Procesar-usuario>event:beforeAjaxLogin'); 
            }, this);
            
            Sb.events(['ajaxLogin'], function(){
                log('Procesar-usuario>event:ajaxLogin->done'); 
            }, this);

            Sb.events(['loginIsFalse'], function(){
                log('Procesar-usuario>event:loginIsFalse'); 
            }, this);

            Sb.events(['loginIsTrue'], function(affectedRow){
                log('Procesar-usuario>event:loginIsTrue->affectedRow:',affectedRow); 
            }, this);

            // Eventos para REGISTRAR --------------------------------------------------
            // Inicializando eventos para registrar
            Sb.events(['ajaxRegistrar'], function(){
                log('Procesar-usuario>event:ajaxRegistrar->Done-success'); 
            }, this);

            Sb.events(['usuarioLogueado'], function(affectedRow){
                log('Procesar-usuario->event:usuarioLogueado->affectedRow:',affectedRow);
            }, this);

            Sb.events(['usuarioValido'], function(affectedRow){
                log('Procesar-usuario>event:usuarioValido->valido->affectedRow:',affectedRow); 
            }, this);

            Sb.events(['usuarioUpdate'], function(){
                log('Procesar-usuario>event:usuarioUpdate->update'); 
            }, this);

            $btnlogin.bind('click', function(){ MOD.fncLogin(); });
        }
        
        /***
         * @description Funcion que registra al usuario (Se asigna a evento submit o click)
         * @param {type} fncExt
         * @returns {undefined}
         */
        ,fncRegistrar: function(fncExt){

            var m = $.trim($usumail.val()), vm = $.trim($verifmail.val()),
                p = $.trim($frmUser.find('#usupass').val()), 
                vp = $.trim($frmUser.find('#verifpass').val()),
                mailOk = (m && m==vm ), 
                passOk = (p && p==vp );

            if(!mailOk || !passOk){
                var sltr = !mailOk?(!passOk?'#verifmail,#verifpass':'#verifmail'):'#verifpass';
                $(sltr).tooltip({title:'No coinciden!',placement:"top",trigger:"hover"});
                $(sltr).tooltip('show');
                return ;
            }
            // Disparando evento antes de ajax registrar
            Sb.trigger('beforeAjaxRegistrar',[/*No params*/]);

            var dataPost = $frmUser.find('input,select,textarea').serialize();
            var urlReg = Global.baseHost+'/portal/usuario/registrar';

            Sb.ajax({
                type:"POST", url:urlReg, data:dataPost,
                fncDone:function(rpta){ fncAjaxRegistrar(rpta, fncExt); }
            });
        },
        
        /***
         * @description Funcion que loguea al usuario (Se asigna a evento submit u click)
         * @param {type} fncExt
         * @returns {undefined}
         */
        fncLogin: function(fncExt){
            Sb.trigger('beforeAjaxLogin',[/*No params*/]);
            var dataPost = $('#frmLogin').find('input,select,textarea').serialize();
            var urlReg = Global.baseHost+'/portal/usuario/login';
            Sb.ajax({
                type:"POST", url:urlReg, data:dataPost,
                fncDone:function(rpta){ fncAjaxLogin(rpta, fncExt); }
            });
        }
    };
},['vendor/moment/min/moment.min.js']);

/***
 * @description: Modulo para solicitar una cita, que consta de 3 TABS: Datos de cita, 
 * Datos de Usuario y Datos de pago y con 1 boton de desplazamiento: Siguiente.
 * 
 * @dependencies: Module 'agendar-cita'
 */
yOSON.AppCore.addModule('solicite-cita', function(Sb){
    
    var $btnNext    = $('#btnNext')
        ,$frmLogin  = $('#frmLogin')
        ,$lblNom    = $('#lblNom')
        ,$frmcita   = $('#frmcita')
        ,$frmUser   = $('#frmUser')
        ,$usufecnac = $('#usufecnac')
        ,$allTabs   = $('.nav-tabs li a')
        ,$tipoPago  = $('.tipo-pago')
        
        ,$lblFecha  = $('#lblFecha')
        ,$lblHora   = $('#lblHora')
        
        ,$dataUserTitles = $('#titFrmLogin,#titFrmUser')// Titulos paneles de login y registro (en TAB 2)
        ,$bgmodal        = $('#bgmodal')
        ,$inputsForAlignet = $('#inputsForAlignet')
        ,$frmcodprom     = $('#frmcodprom')
        
        ,$ModProcUser    = yOSON.AppCore.getModule('procesar-usuario').instance
        ,$ModAgendarCita = yOSON.AppCore.getModule('agendar-cita').instance

        ,fncStatusTabs = function(index, status){
            var $tab = $('a[href="#citaPaso'+(index)+'"]');
            if(status){
                $tab.attr('data-toggle','tab');
                $tab.parent().removeClass('disabled');
            }else{
                $tab.removeAttr('data-toggle');
                $tab.parent().addClass('disabled');
            }
        }
        
        ,fncTabOk = function(){
            var indexTab = parseInt($('.nav-tabs li.active').attr('data-index'));
            var $nextTab = $('a[href="#citaPaso'+(indexTab+1)+'"]');
            fncStatusTabs(indexTab+1, true);
            $nextTab.trigger('click');
        }
        
        ,onClickBtnNext = function(){
            // Indice del tab (1,2 o 3)
            var indexTab = parseInt($('.nav-tabs li.active').attr('data-index'));

            if (indexTab===1) { fncTabOk(); }
            if (indexTab===2) {
                if(Global.isLogin){
                    var beforeData = $frmUser.find('input,select,textarea').serialize();
                    if($.trim($btnNext.data('frmusu'))!==$.trim(beforeData)){
                        $ModProcUser.fncRegistrar();
                    } else{ fncTabOk(); }
                } else{ $ModProcUser.fncRegistrar(); }
            }
            if (indexTab===3) {
                if(!$.trim($inputsForAlignet.attr('action'))){
                    $frmcita.submit();
                } else {
                    $inputsForAlignet.submit();
                }
            }
        }
 
        ,ajaxSlideUpDone = function(affectedRows){
            $('#frmUser').find('input,select,textarea').tooltip('destroy'); // Limpiando Tooltips
            $('#frmUser h5').html('<span class="glyphicon glyphicon-ok" /> &nbsp;YA SOY PACIENTE');
            $('#frmLogin').html('');
            $('#titFrmUser').trigger('click').unbind('click');

            for(var index in affectedRows){
                var $inp = $('#frmUser [name="'+index+'"]');
                var value = affectedRows[index];
                if($inp.length){ // Si existe input
                    if($inp.prop('tagName')!=='SELECT'){
                        $inp.val(value);
                        if(index==='fecnac'){
                            $usufecnac.val(moment(value).format('DD/MM/YYYY'));
                        }
                    }else{
                        if($inp.attr('id')==='iddistrito'){
                            $inp.find('option[value="'+value+'"]').prop('selected',true);
                        }else{
                            $inp.prop('selectedIndex',(parseInt(value)-1));
                        }
                    }
                }
            }
            $('#verifmail').val(affectedRows['mail']);
            $('#verifpass').val(affectedRows['pass']);
            $('#titFrmLogin,#titFrmUser').unbind('click'); // Quitando slideToggle
            fncStatusTabs(3, true); // Habilitando Tab3
            $btnNext.prop('disabled', false); // Habilitando Button Next
        }
    
        ,onClickAllTabs = function(e){
            
            var setValidHour = $ModAgendarCita.getIsValidHour(); // Se eligio fecha y hora valida
            var disabled = $(this).parent().hasClass('disabled'); // Este tab esta disabled
            $(this).bind('blur');
            
            log('setValidHour,disabled:',[setValidHour,disabled]);
            if(!setValidHour || disabled){ e.preventDefault(); return; }
            
            // Index de este Tab
            var indexTab = parseInt($(this).parent().attr('data-index'));
            log('indexTab:',indexTab);
            var selectedPay = $('input[name="codpago"]:checked').length; 
            $btnNext.html((indexTab===3)?'Pagar':'Siguiente &gt;&gt;&gt;');
            $btnNext.prop('disabled', (indexTab===3 && !selectedPay));
            animateScroollTo('#myTab');
            
        }
        
        ,idsForAlignet = {EMail:'usumail',FirstName:'usunom',LastName:'usuape',Phone:'usucel'}
        
        ,procInputsForAlignet = function(tipoPago){
            if(tipoPago!=='visa' && tipoPago!=='mastercard'){
                $inputsForAlignet.attr('action', '');
                $inputsForAlignet.html(''); return ;
            }
            // else
            $inputsForAlignet.attr('action', Global.alignet.action); // Set action form
            var inputs = Global.alignet.inputs;
            for(var field in inputs){
                var value = inputs[field];
                if(field==='billing' || field==='shipping'){
                    for(var i in value){
                        var name = field+i;
                        var val = value[i]?value[i]:$('#'+idsForAlignet[i]).val(); // si no tiene valor lo sacamos de los inputs
                        $inputsForAlignet.append('<input type="hidden" value="'+val+'" name="'+name+'" />');
                    }
                    continue;
                }
                $inputsForAlignet.append('<input type="hidden" value="'+value+'" name="'+field+'" />');
            }
        }

    ;
    
    return {
        init:function(){
            // Guardando instancia del modulo
            var MOD = this;
            
            // Habilitando y deshabilitando boton pagar para frase admosi.
            $frmcodprom.bind('keyup', function(){
                var isAdmOsi = ($.trim($(this).val())==='admosi');
                if(isAdmOsi){
                    $tipoPago.css('border','1px');  // clean all tipos pago
                    procInputsForAlignet('admosi'); // clean inputs alignet
                } 
                $('#frmcodpago5').prop('checked', isAdmOsi);
                $btnNext.prop('disabled', !isAdmOsi);
            });
            
            // SELECT TIPO DE SEGURO OCULTO por eso seleccionamos y trigeamos el select.
            $('#idseguro').prop('selectedIndex',1).trigger('change')
            
            // Funcionalidad botton siguiente
            $btnNext.bind('click', onClickBtnNext);
            
            // TAB 1: Datos de Cita ---------------------------------------------------------------
            // Subcribiendose a eventos creados en el modulo
            
            Sb.events(['onClickBtnsHours'], function(THIS){
                $lblFecha.html(moment($(THIS).attr('cita-date')).format('DD/MM/YYYY')); // set tab3 Fecha
                $lblHora .html(moment($(THIS).attr('cita-date')).format('hh:mm a'));    // set tab3 Hora
                $btnNext.prop('disabled', false); 
            }, this);

            Sb.events(['beforeGetCitasByEspec'], function(idespec){
                $btnNext.prop('disabled',true);
                fncStatusTabs(2, false);
                fncStatusTabs(3, false);
            }, this);

            //Sb.events(['onClickBtnsHours'], function(){ $bgmodal.fadeIn('fast'); }, this);

            // TAB 2: Datos de Usuario ------------------------------------------------------------
            // Eventos para los titulo de Login & registro
            $dataUserTitles.bind('click', function(){
                log('window.slidesLoginRegisterClosed:'+MOD.slidesLoginRegisterClosed);
                if(!MOD.slidesLoginRegisterClosed){
                    $('#inpsLogin,#inpsUser').slideToggle('fast', function(){
                        $btnNext.prop('disabled',($('#inpsUser').css('display')==='none'));
                    });
                }
                if(MOD.slidesLoginRegisterClosed){
                    var $inps = $(this).parent().parent().next();
                    $inps.slideDown('fast',function(){ MOD.slidesLoginRegisterClosed=false; });
                }
            });
            
            // EVENTOS PARA REGISTRARSE
            // Subcribiendose a eventos creados en el modulo: procesar-usuario
            Sb.events(['beforeAjaxRegistrar'], function(){ $bgmodal.fadeIn('fast'); }, this);
            
            Sb.events(['ajaxRegistrar'], function(){ $bgmodal.fadeOut('fast'); }, this);

            Sb.events(['usuarioLogueado'], function(affectedRows){
                // Cambiando panel superior de usuario 
                Sb.trigger('settingPanelUser', [affectedRows]); 
                // Close login and delete your content form
                $frmLogin.html('');
                // Cambiando titulo
                $('#frmUser h5').html('<span class="glyphicon glyphicon-ok" /> &nbsp;YA SOY PACIENTE');
                // Deshabilitando campos
                $('#idusuario').val(affectedRows['idusuario']);
                $('#usutipodoc').parent().slideUp('slow','easeOutBounce');
                $('#usunrodoc').parent().slideUp('slow','easeOutBounce');
            }, this);

            Sb.events(['usuarioValido'], function(affectedRow){
                log('IEnom:', affectedRow['nom']);
                log('IEape:', affectedRow['ape']);
                if(affectedRow){
                    $lblNom.html(affectedRow['nom']+' '+affectedRow['ape']);
                }
                // Si es valido, cambiamos estados de TABS y pasamos a siguiente TAB!
                if(fncTabOk) fncTabOk(); 
            }, this);

            Sb.events(['usuarioUpdate'], function(){
                $btnNext.data('frmusu', $('#frmUser').find('input,select,textarea').serialize());
            }, this);
            
            // EVENTOS PARA LOGUEARSE
            // Subcribiendose a eventos creados en el modulo: procesar-usuario
            Sb.events(['beforeAjaxLogin'], function(){ $bgmodal.fadeIn('fast'); }, this);
            
            Sb.events(['ajaxLogin'], function(){ $bgmodal.fadeOut('fast'); }, this);
            
            Sb.events(['loginIsFalse'], function(){ }, this);
            
            Sb.events(['loginIsTrue'], function(affectedRows){
                $('#frmLogin').slideUp('fast',function(){ ajaxSlideUpDone(affectedRows); } );
            } , this);
            
            $allTabs.bind('click', onClickAllTabs); // Click en los Tabs
            
            // Click en botones formas de pagos
            $tipoPago.bind('click', function(){
                
                var tipoPago = $(this).attr('name-pago'),codPromVal=$frmcodprom.val();
                procInputsForAlignet(tipoPago); // Creando y procesando inputs para alignet
                
                $frmcodprom.val((codPromVal==='admosi')?'':codPromVal); // Limpiando codigo promocional admosi
                $tipoPago.css('border','1px'); // clean all tipos pagos
                $(this).css('border','1px dashed #449D44');
                $(this).find('input[type="radio"]').prop('checked',true);
                $btnNext.prop('disabled', false); // Hbilitando boton Pagar
            });
        },
        // 
        slidesLoginRegisterClosed:true,
    };
},['vendor/moment/min/moment.min.js']);

/**
 * Datos de usuario logueado
 **/
yOSON.AppCore.addModule('cuenta-datos', function(Sb){
    
    var $inpFecnac      = $('#formRegister [name="fecnac"]') // fecha de nacimiento q se envia por post
        ,$usufecnac     = $('#usufecnac') //Mascara        
        ,$bstrModal     = $('#bstrModal')
        ,$idseguro      = $('#idseguro')
        ,$btnReagendar  = $('#btnReagendar')
        ,datapost       = {};
    ;
    
    return {
        init:function(){
            // Seteando input fecnac mascara con el valor a enviar por post (type hidden)
            $usufecnac.val(moment($inpFecnac.val()).format('DD/MM/YYYY'));
            
            $bstrModal.bind('shown.bs.modal', function(e){
                log($(e.relatedTarget).data('whatever'));
                datapost['idcita'] = $.trim($(e.relatedTarget).data('whatever'));

                var $link = $("[data-whatever='"+datapost['idcita']+"']"); log($link);
                if($.trim($link.attr('id-usu'))!=''){
                    $('#idusuario').val($link.attr('id-usu'));
                }
            });
            
            // Reagendar
            $btnReagendar.bind('click', function(){

                if(!confirm('¿Esta seguro que desea reagendar esta cita?')) return ;

                $btnReagendar.prop('disabled', true);

                datapost['idusuario'] = $.trim($('#idusuario').val());
                datapost['idseguro']  = $.trim($('#idseguro').val());
                datapost['idhorario'] = $.trim($('#idhorario').val());
                datapost['datehour']  = $.trim($('#datehour').val());

                log('datapost:',datapost);
                Sb.ajax({
                    type:"POST",url:Global.baseHost+'/portal/usuario/reagendar',data:datapost,
                    fncDone:function(rpta){
                        if(rpta.success){ log('Reagendado!'); }
                        $('a[data-whatever="'+datapost['idcita']+'"]').addClass('disabled').html('Cambiado');
                        $('#txtDatehour'+datapost['idcita']).html(datapost['datehour']); //console.log($txtDatehour,datapost['datehour']);
                        $bstrModal.modal('hide');
                        $btnReagendar.prop('disabled', false);
                    }
                });
            });
            
            // Seteando values dentro de modal reagendar
            $bstrModal.bind('hidden.bs.modal', function(e){
                $idseguro.prop('selectedIndex',0);
                $idseguro.trigger('change');
            });
        }
    };
},['vendor/moment/min/moment.min.js']);

/**
 *
 **/
yOSON.AppCore.addModule('websocket-conection', function(Sb){
    
    var getUA = function(){
        if($.browser.hasOwnProperty('mozilla')) return 'Firefox';
        if($.browser.hasOwnProperty('chrome'))  return 'chrome';
        if($.browser.hasOwnProperty('msie'))    return 'msie';
        return 'otros';
    };
    
    return {
        init: function () {
            if( typeof(WebSocket) === "function" ) {
                
                var tagDateSelected = '';
                //var conn = new WebSocket('ws://ws.centromedicoosi.com:8089');
                var conn = new WebSocket('ws://ws.centromedicoosi.com:8089'); // ws://localhost:8089
                
                conn.onopen = function(e) {
                    console.log("Connection established!", e);
                };

                conn.onmessage = function(e) {
                    
                    var wsRpta   = (new Function('return '+e.data))();  
                    var jsonTags = wsRpta['data'];
                    console.log('WebSocket->Rpta:',wsRpta);
                    
                    // Clean segun lo que se almacena en websocket server
                    var $allTagsSelWs = $('#citaHoras').find(':disabled')
                                                       .not('.tag-reserv')
                                                       .not('.tag-last'); //$('#citaHoras .btn-warning')
                    $allTagsSelWs.prop('disabled',false);
                    
                    for(var cn in jsonTags){
                        if(jsonTags[cn]!==tagDateSelected){
                            var $otherTagSelected = $('button[cita-date="'+jsonTags[cn]+'"]');
                            $otherTagSelected.prop('disabled',true);
                        }
                    }
                };
                
                // Evento que se dispara cuando termina ajax para mostrar tags de horas para un especialista
                Sb.events(['succAjaxCreateButtonsHours'], function(rpta){
                    console.log("succAjaxCreateButtonsHours->rpta:",rpta);
                    Global.currentIdsHrs = rpta.data[0].idshs;
                    conn.send('{"action":"showTags","data":"'+rpta.data[0].idshs+'","ua":"'+getUA()+'"}');
                }, this);
                
                // Evento que se dispara antes del metodo GetCitasByEspec cuando se hace change a los selects
                Sb.events(['beforeGetCitasByEspec'], function (idespec) {
                    console.log('idespec:',idespec);
                    // selectedIndex valido y diferente de cero
                    if(!isNaN(idespec)){
                        if (idespec) {
                            // conn.send('{"action":"delTags"}'); // Se indica a todos que se dejo de ver los idhs
                            // cuando se ocultan los tagshoras se quita los idhs como no usados en este momento
                            conn.send('{"action":"changeTags","data":"'+Global.currentIdsHrs+'","ua":"'+getUA()+'"}');
                        } else {
                            conn.send('{"action":"hideTags","data":"'+Global.currentIdsHrs+'","ua":"'+getUA()+'"}');
                        }
                    }
                }, this);
                
                // Evento que se dispara al hacer click en los tags de horas para un especialista
                Sb.events(['onClickBtnsHours'], function(THIS){
                    log('Element clicked:', THIS);
                    tagDateSelected = $(THIS).attr('cita-date');
                    conn.send('{"action":"clickTag","idh":'+$(THIS).attr('cita-idh')+',"date":"'+tagDateSelected+'","ua":"'+getUA()+'"}');
                }, this);
            }
        }
    };
    
});