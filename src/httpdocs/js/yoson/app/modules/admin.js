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

/*--------------------------------------------------------------------------------------------------------
 * @Module     : Carousel
 * @Description: Carousel de formularios
 *//*----------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('admin-login', function(Sb){
    
    var $admFormLogin  = $('#admLoginForm');
    
    return {

        init: function(oParams){
            
            $admFormLogin.bind('submit', function(evt){
                evt.preventDefault();
                $('#loginWarn').hide('fast',"easeOutBounce"); // Ocultando msg errors
                var dataPost = $admFormLogin.find('input,select,textarea').serialize();
                var urlLogin = $admFormLogin.attr('action');
                $.ajax({type:"POST", url:urlLogin, data:dataPost})
                .done(function(rpta){
                    if(rpta.session) {
                        location.href = Global.baseHost+'/admin/dashboard';
                        //$('#bstrModal').modal('hide');
                        //settingPanelUser(rpta.data.affectedRows[0]);
                    } else {
                        console.log(rpta);
                        $('#loginWarn').show('fast',"easeOutBounce");
                    }
                })
                .always(function(rpta){ console.log(rpta); });
            });
        },

        destroy: function(){ }
    };
    
});

/**
 **/
yOSON.AppCore.addModule('citas-list', function(Sb){
    
    var $dropDownLinks = $('#items .dropdown-menu li:not(.disabled)').find('a')
        ,$formRegister = $('#formRegister')
        ,$formInputs   = $formRegister.find('select,input,button')
        ,$searchCita   = $('#searchCita')
        ,$usufecnac    = $('#usufecnac')
        ,$bstrModal    = $('#bstrModal')
        ,$daterange    = $('input[name="daterange"]')
        ,estCit        = ['', 'Pendiente', 'Atendido', 'Postergado', 'Cancelado']
        ,lblCit        = ['', 'info', 'success', 'warning', 'danger']
        ,fncDropDown   = function(evt){
            log("dropDownLinks Click!!!");
            evt.preventDefault();
            var idcita  = $.trim($(this).attr('idcita'));
            var estcita = $.trim($(this).attr('estcita'));
            var prevest = $.trim($(this).attr('prevest'));
            $.ajax({
                type:"POST",
                url:Global.baseHost+'/admin/estado-cita',
                data:{idcita:idcita, estcita:estcita}
            }).done(
                (function(){
                    return function(rpta){
                        if(rpta.success) {
                            var btns = $('#btnEst'+idcita).find('button');
                            $('#btnEst'+idcita+' ul.dropdown-menu').find('li').removeClass('disabled'); // Quitando class disabled
                            $('#btnEst'+idcita+' ul.dropdown-menu [estcita="'+estcita+'"]').parent().addClass('disabled');
                            btns.removeClass('btn-'+lblCit[prevest]).addClass('btn-'+lblCit[estcita]);
                            btns[0].innerHTML = estCit[estcita];
                        }
                    };
                })(estCit,lblCit,idcita,estcita,prevest)
            )
            .always(function(rpta){ console.log(rpta); });
        }
    ;
    
    return {
        init: function(){
            // 
            $daterange.daterangepicker({
                opens: 'center',
                format: 'YYYY-MM-DD',
                locale: {
                    applyLabel:'Establecer',
                    cancelLabel:'Limpiar',
                    fromLabel:'desde',
                    toLabel:'hasta'
                }
            });

            $daterange.on('cancel.daterangepicker', function(ev, picker){
                $daterange.val('');
            });
            
            $('#ifrmMapa').attr('src','/mapa');
            $dropDownLinks.bind('click', fncDropDown);
            
            // Edit usuario dentro de listado
            $formInputs.prop('disabled',true); // deshabilitando inputs y selects
            
            $bstrModal.bind('shown.bs.modal', function(e){

                console.log($(e.relatedTarget).data('whatever'));

                $.ajax({
                    type : "GET",
                    url  : window.Global.baseHost+'/admin/get-usuario',
                    data : {idusu:$.trim($(e.relatedTarget).data('whatever'))}
                })
                .done(function(rpta){
                    if(rpta.success){
                        console.log(rpta.data.formVal);
                        rpta.data.formVal['verifmail'] = rpta.data.formVal.mail;
                        rpta.data.formVal['verifpass'] = rpta.data.formVal.pass;

                        $formInputs.prop('disabled',false);
                        $formRegister.populate(rpta.data.formVal); // Llenado inputs de data
                        $usufecnac.val( // haciendo el cambio del dato dentro del input hidden hacia #usufecnac
                            moment($('#formRegister [name="fecnac"]').val()).format('DD/MM/YYYY')
                        );
                    }
                })
                .always(function(rpta){ console.log(rpta); });

            });
            
            $bstrModal.bind('hidden.bs.modal', function(e){
                $formRegister[0].reset();
                $formInputs.prop('disabled',true); // deshabilitando inputs y selects
            });
                        
            // chosen implement
            $('.chosen-select').chosen();
            $('.chosen-select-deselect').chosen({allow_single_deselect: true});

            // Busqueda de citas
            $searchCita.bind('submit', function(e){
                e.preventDefault();
                var sede = $(this).find('select:eq(0)').val();  // Sedes
                var esp  = $(this).find('select:eq(1)').val();  // Especialistas
                var dia  = $.trim($(this).find('input[name="daterange"]').val());  // dias
                var pag  = 1; //$.trim($(this).find('input[name="page"]').val()); // cadavez q se hace una busqueda nueva empieza en la primera pag
                var text = $.trim($(this).find('input[name="text"]').val()); // texto
                var acti = Global.baseHost+'/admin/citas/'+sede+'/'+esp+'/'+encodeURI(dia?dia:0)+'/'+pag+'/'+encodeURI(text?text:'');
                console.log('acti:',acti);
                location.href = acti;
                //$(this).attr('action',acti);
                //$(this).submit();
            });
        }
    };

},
['js/libs/jquery.chosen.js','vendor/jquery.populate/jquery.populate.js']);


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
                type:"POST", url:urlRegister+'?adm=1', data:dataPost,
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
 **/
yOSON.AppCore.addModule('edit-esp-sede', function(Sb){
    
    // EDIT ESPECIALISTA
    var $btnEdit   = $('.row-espec a.btn-info')
       ,$btnDelete = $('.row-espec a.btn-warning')
       ,$btnNew    = $('#rowAddEspec a')
       ,$frmEspec  = $('#frmEspec')
       ,$btnChk    = $('input[name="activo"]')
    ;
    
    return {
        init: function(){
            $btnEdit.bind('click', function () {
                var $tr = $(this).parents('tr:eq(0)');
                var $inpts = $tr.find('input');

                $inpts.each(function (i, inp) {
                    var inpName = $(inp).attr('name');
                    var inValue = $(inp).val();
                    var $inpFrm = $frmEspec.find('[name="' + inpName + '"]');
                    if ($inpFrm.prop('tagName') === 'SELECT') {
                        $inpFrm.find('option[value="' + inValue + '"]').prop('selected', true);
                    } else {
                        if ($inpFrm.attr('type') === 'checkbox') {
                            $inpFrm.prop('checked', $(inp).prop('checked'));
                        } else {
                            $inpFrm.val($(inp).val());
                            console.log(inpName, $inpFrm);
                        }
                    }
                });
            });

            $btnNew.bind('click', function () {
                $('input[name="idespecialista"]').val('');
                $('#frmEspec')[0].reset();
            });

            $btnChk.bind('click', function () {
                $(this).val($(this).prop('checked') ? '1' : '0');
            });

            $btnDelete.bind('click', function () {
                if (confirm('Seguro que quiere eliminar este registro?')) {
                    location.href = "?idesp=" + $(this).attr('id-esp');
                }
            });
        }
    };
});

/**
 *
 */
yOSON.AppCore.addModule('horario-espec', function(Sb){
    
    var $frmsede    = $('#frmsede')
        ,$frmroom   = $('#frmroom')
        ,$frmroom2  = $('#frmroom2')
        ,$modalSave = $('#modalSave')
        ,$modalEdit = $('#modalEdit')
        ,$modalSaveTitle = $('#modalSaveTitle')
        ,$horarioCitas = $('#horarioCitas')  // tbody dentro modal editar horario
        ,$frmstart  = $('#frmstart')
        ,$frmend    = $('#frmend')
        ,$calendar  = $('#calendar')
        ,eventData  = {post:{},cal:{}} // Variable temporal para la edicion o actualizacion de algun event
    ;
    
    return {
        init: function(){
            
            window.FullCal = window.FullCal||{

                feeds:{
                    urlRoomsBySede : Global.baseHost+"/admin/horario/list-rooms/$1", // $1=idSede
                    urlEventsByRoom: Global.baseHost+"/admin/horario/index/$1/$2",   // $1=idSede,$2=idRoom
                    urlSaveHorario : Global.baseHost+"/admin/horario/save-horario"
                },

                utils:{
                    /**
                     * @description: Metodo para verificar si se estan solapando un evento con
                     * otro, de ser asi se regresa a su posicion anterior si hacer nada.
                     * @params {Event} event Evento de tipo objeto de fullcalendar.
                     * @return {bolean} Retorna True o False segun si se solapan o no los eventos
                     */
                    isOverlapping: function(event){
                        var events = $calendar.fullCalendar('clientEvents');
                        for(var i in events){
                            if(events[i].idhorario != event.idhorario){
                                /*console.log('event.title:'+event.title+' - '+'events[i].title:'+events[i].title);
                                console.log('event['+event.start+','+event.end+'] && events['+i+']['+events[i].start+','+events[i].end+']');
                                console.log(events[i].idhorario+'!='+event.idhorario);*/
                                if(event.start>=events[i].start && event.start<events[i].end){
                                    console.log('1:'+event.start+'<='+events[i].start+' && '+event.start+'>'+events[i].end);
                                    return true;
                                }
                                //end-time in between any of the events
                                if(event.end>events[i].start && event.end<=events[i].end){
                                    console.log('2:'+event.end+'<'+events[i].start+' && '+event.end+'>='+events[i].end);
                                    return true;
                                }
                                //Event dragged contain other event
                                if(event.start<=events[i].start && event.end>=events[i].end){
                                    console.log('2:'+event.start+'<='+events[i].start+' && '+event.end+'>='+events[i].end);
                                    return true;
                                }
                            }
                        }
                        console.log(false);
                        return false;
                    },

                    /**
                     * @params {String} url Url de la forma /pat/to/$1/and/$2/foo/ ...
                     * @params {Array} pars parametros para reemplazar $1,$2,$3 .. de url
                     * @return {String} url formateado
                     * */
                    setUrl: function(url, pars){
                        return url.replace(/\$([1-9]+)/gi, function(res, match){
                            return pars[parseInt(match)-1];
                        });
                    }
                }

            };

            // Argumentos para cargar eventos
            window.FullCal.argsEvts = {
                idRoom         : 0,
                urlEventsByRoom: window.FullCal.utils.setUrl(window.FullCal.feeds.urlEventsByRoom, [1,0]), // 1 id sede (miraflores)
            };

            window.FullCal.callbacks = {

                /**
                 * @description: Cargando rooms segun la el idsede
                 * @param {Integer|String} idSede ID del consultario (rooms) donde estara el especialista
                 * @returns {undefined}
                 */
                roomsBySede: function(idSede){

                    var urlRoomsBySede = FullCal.utils.setUrl(FullCal.feeds.urlRoomsBySede, [idSede]),
                        $sedeAndRoom = $('#frmsede,#frmroom')
                    ;
                    $sedeAndRoom.prop('disabled',true);

                    $.ajax({type:"POST", url:urlRoomsBySede})
                    .done(function(rpta) { // Respuesta correcta
                        $frmroom.html('<option value="0"> - Todos</option>'); // limpiando select
                        $frmroom2.html('<option value="0"> - Todos</option>'); // limpiando select
                        $.each(rpta.data, function(i, o){
                            $frmroom.append($('<option />',{value:o.idroom, text:o.descr}));
                            $frmroom2.append($('<option />',{value:o.idroom, text:o.descr}));
                        });
                        $frmroom.trigger('change'); // Llenando de eventos el calendar
                        $sedeAndRoom.prop('disabled',false);
                    })
                    .always(function(rpta){ // Siempre se ejecuta
                        console.log(rpta);
                        $sedeAndRoom.prop('disabled',false);
                    });
                },

                /**
                 * Evento que se lanza cuando selecciones una o varias cajas libres del calendario
                 * @param {Moment} start Inicio seleccionado
                 * @param {Moment} end Fin seleccionado
                 * @return {void} Return vacio. 
                 **/
                onSelect: function(start, end){
                    console.log('$modalSaveTitle:',$modalSaveTitle);
                    $modalSaveTitle.html('<b>Nuevo horario</b>');
                    $frmstart.val($.trim(start.format('YYYY-MM-DD HH:mm:ss')));
                    $frmend.val($.trim(end.format('YYYY-MM-DD HH:mm:ss')));
                    eventData.cal = {start:start, end:end};
                    $modalSave.modal('show');
                    return ;
                },

                /**
                 * 
                 **/
                onEventDrop: function(event, delta, revertFunc, jsEvent, ui, view ) {

                    console.log('drop event:===>>>', event);
                    if(window.FullCal.utils.isOverlapping(event)){
                        console.log('isOverlapping'); revertFunc(); return ;
                    };

                    $('#bgmodal').fadeIn('slow'); // Ocultando calendar
                    var dataPost = {
                        idhorario:event.idhorario,
                        horaini  :event.start.format('YYYY-MM-DD HH:mm:ss'),
                        horafin  :event.end.format('YYYY-MM-DD HH:mm:ss')
                    };

                    $.ajax({type:"POST", url:window.FullCal.feeds.urlSaveHorario, data:dataPost})
                    .done(function(rpta) { console.log("rpta*-*-*-*-*->>>",rpta); })
                    .always(function(){ $('#bgmodal').fadeOut('slow'); });
                },

                /**
                 * 
                 **/
                onEventResize: function(event, delta, revertFunc, jsEvent) {
                    if(window.FullCal.utils.isOverlapping(event)){
                        console.log('isOverlapping'); revertFunc(); return ;
                    };

                    $('#bgmodal').fadeIn('slow'); // Ocultando calendar
                    var dataPost = {
                        idhorario:event.idhorario,
                        horafin  :event.end.format('YYYY-MM-DD HH:mm:ss')
                    };

                    $.ajax({type:"POST", url:window.FullCal.feeds.urlSaveHorario, data:dataPost})
                    .done(function(rpta) { console.log("onEventResize->done->rpta:",rpta); })
                    .always(function(rpta){ $('#bgmodal').fadeOut('slow'); });
                },

                /**
                 * 
                 **/
                onEventRender: function(event, element) {
                    $(element).data('desc',event.desc);
                    //console.log('eventRender-element:',element);
                },

                /**
                 *
                 **/
                eventClick: function(event) {
                    console.log('eventClick-event:',event);
                    //$modalSaveTitle.html('<b>Editar horario</b>');
                    Sb.ajax({
                        type:"GET", 
                        url:Global.baseHost+'/admin/citas-by-horario/'+event.idhorario, 
                        fncDone:function(rpta){
                            var rowsCitas = [];
                            for(var i in rpta.data){
                                var r = rpta.data[i];
                                rowsCitas.push(
                                    '\n<tr>\n\
                                        <td>'+r['idcita']+'</td>\n\
                                        <td>'+r['nom']+'</td>\n\
                                        <td>'+r['datehour']+'</td>\n\
                                        <td>'+r['codpago']+'</td>\n\
                                        <td>'+r['estcita']+'</td>\n\
                                    <tr>\n'
                                );
                            }
                            $horarioCitas.html(rowsCitas.join(''));
                        } 
                    });
                    $modalEdit.modal('show');
                },

                /**
                 *  
                 **/
                chargeEvents: function() {

                    var idSede = $frmsede.val(); // Si fuera 0 cargara para todos los rooms (Consultorios)

                    var idRoom = $(this).val();// Si fuera 0 cargara para todos los rooms (Consultorios)
                    window.FullCal.argsEvts.idRoom = idRoom;

                    var urlEventsByRoom = window.FullCal.utils.setUrl(window.FullCal.feeds.urlEventsByRoom, [idSede,idRoom]);
                    window.FullCal.argsEvts.urlEventsByRoom = urlEventsByRoom;

                    $frmroom2.prop('disabled', (true && parseInt(idRoom)) );

                    console.log('urlEventsByRoom:*-*-*-*-*-*>>>>>>',urlEventsByRoom);

                    $('#calendar').fullCalendar('removeEvents');
                    $('#calendar').fullCalendar('refetchEvents');
                },

                /**
                 * Guardando datos en Mysql
                 **/
                onModalSave: function(){
                    console.log('save...');
                    $('#frmsede,#frmroom').prop('disabled',true); // deshabilitando combos superiores
                    $('#bgmodal').fadeIn('slow'); // Ocultando calendar

                    var dias = {'lunes':1,'martes':2,'miércoles':3,'jueves':4,'viernes':5,'sábado':6};
                    var dia = moment($.trim($frmstart.val())).format('dddd'); console.log(dia,$frmstart.val());

                    eventData.post = {
                        idespecialista: $('#frmesp').val(),
                        idroom        : $('#frmroom2').val(),
                        fecha         : moment($frmstart.val()).format('YYYY-MM-DD'), // podria start o end
                        coddia        : dias[dia], // 1:Lunes, 2:martes, ... etc
                        nomdia        : dia, // Lunes,martes, ... etc
                        horaini       : $.trim($frmstart.val()), // No es necesario usar moment format ya se uso en onSelect
                        horafin       : $.trim($frmend.val())    // No es necesario usar moment format ya se uso en onSelect
                    };
                    eventData.cal.title = $('#frmesp option:selected').text();

                    eventData.cal = $.extend({},eventData.post,eventData.cal); // Adicionando data post a event cal (mergeando datos)
                    console.log(eventData.post,eventData.cal);
                    $.ajax({type:"POST", url:window.FullCal.feeds.urlSaveHorario, data:eventData.post})
                    .done(function(rpta) { // Respuesta correcta
                        eventData.cal['idhorario'] = rpta.data.rowAffected['idhorario'];
                        $calendar.fullCalendar('renderEvent', eventData.cal, true); // stick? = true
                    })
                    .always(function(rpta){ // Siempre se ejecuta
                        console.log(rpta);
                        $('#bgmodal').fadeOut('slow'); // Mostrando calendar
                        $('#frmsede,#frmroom').prop('disabled',false); // Mostrando combos superiores
                        $calendar.fullCalendar('unselect');
                        eventData = {post:{},cal:{}}; // Resetinng eventData
                    });

                    $modalSave.modal('hide');
                }
            };

            window.FullCal.config = {
                //lang: 'es', //for lang-all.js
                //slotDuration: '00:30:00', minima fraccion de los tiempos (por default 30min)
                //allDayDefault:false,
                slotEventOverlap:false, // No superpocision entre eventos
                minTime: '07:00:00',
                maxTime: '22:00:00',
                axisFormat: 'h(:mm)a', // Con es.js cambia a numeros, de esta forma prevalecen am y pm en las horas
                weekNumbers: false,
                //weekends: false, // Quitar sabados y domingos
                hiddenDays: [0], // Ocultando Domingo ( [domingo, lunes, ... ,sabado] de 0 a 6 )
                aspectRatio: 2,
                allDaySlot:false,  // Parte superior "Todo el dia" por default es true
                //allDayText:'Todo', // Por default dice "Todo el dia" (si se llama a es.js u otro no funciona)
                editable: true, // Habilita rezise, edit data, drop .. etc para los eventos
                header: { left:'prev,next today', center:'title', right:'month,agendaWeek,agendaDay'},
                //defaultDate: '2014-10-22',
                defaultView: 'agendaWeek',
                // events: window.FullCal.callbacks.chargeEvents,
                /*[
                    {id:991,title:'All Day Event',start: '2014-10-20'},
                    {id:995,title:'Meeting', start:'2014-10-22T13:30:00', end:'2014-10-22T14:30:00'},
                    {id:996,title:'Lunch', start:'2014-10-20T12:00:00', end:'2014-10-20T14:00:00'},
                ]*/
                dragOpacity: {month:.2, 'default':.5},
                selectable: true,
                selectHelper: true,
                //eventLimit: true,
                select: window.FullCal.callbacks.onSelect,
                eventDrop: window.FullCal.callbacks.onEventDrop,
                eventResize: window.FullCal.callbacks.onEventResize,
                eventRender: window.FullCal.callbacks.onEventRender, // Redenrizar un evento
                eventClick: window.FullCal.callbacks.eventClick // Click en un evento

                // NOTE: How can I set Fullcalendar options dynamically ?
                // $('#calendar').fullCalendar('getView').calendar.options
            }

            // Cargando consultorios segun la sede por defecto y ejecutandolo en el ready
            $frmsede.bind('change', function(){
                window.FullCal.callbacks.roomsBySede($(this).val());
            }).trigger('change');

            // Carga de eventos el Calendar
            $frmroom.bind('change', window.FullCal.callbacks.chargeEvents);

            // page is now ready, initialize the calendar...
            console.log('$calendar.length--->',$calendar.length);
            if($calendar.length){

                $calendar.fullCalendar(window.FullCal.config);

                // addEventSource
                $calendar.fullCalendar('addEventSource', function (start, end, timezone, callback) {
                    console.log('addEventSource args:', arguments);
                    var urlEventsByRoom = window.FullCal.argsEvts.urlEventsByRoom
                        ,idRoom         = window.FullCal.argsEvts.idRoom
                        ,startWeek      = $.trim($('#calendar').fullCalendar('getView').start.format())
                        ,endWeek        = $.trim($('#calendar').fullCalendar('getView').end.format())
                    ;

                    $.ajax({
                        url:urlEventsByRoom+'?startWeek='+startWeek+'&endWeek='+endWeek,
                        dataType:'json',
                        success: function(evts) {
                            for(var i in evts.data){
                                evts.data[i].startEditable = (evts.data[i].startEditable==='1');
                                evts.data[i].reservedInDb = !(evts.data[i].startEditable==='1'); // reservado desde el servidor mismo no desde WS
                                evts.data[i].durationEditable = (evts.data[i].durationEditable==='1');
                                if(!evts.data[i].startEditable){ evts.data[i].color='#f0ad4e'; }
                            }
                            callback(evts.data);
                            $('#frmroom2 option[value="'+idRoom+'"]').prop('selected',true);
                        }
                    });
                });
            }

            // Add modal background to calendar
            $calendar.append('<div id="bgmodal"></div>');

            // Modal event
            $('#fcBtnModalSave').bind('click', window.FullCal.callbacks.onModalSave);
            
        }
    };
});

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

// ----------------------------------------------------------------------------------------------------------------
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
                $ifrmMapa.bind('load',function(){ loadIfrmMap=true; }).attr('src','/admin/mapa');
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
                    var type = gpo[k].exst?'warning':'info';
                    var hour = gpo[k].hour;
                    var date = gpo[k].date;
                    str+='<button type="button" cita-date="'+date+'" cita-idh="'+j+'" class="btn btn-'+type+'" '+dsbd+'>'+hour+'</button>';
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
                openSelect("#frmsede");
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
            
            $citaHoras.slideUp('slow','easeOutBounce'); // oculta anterior resultado
            $idhorario.val(''); // Clear idhr
            $datehour.val('');  // Clear fecha y hora de cita
            
            setValidHour = false; // Aun no selecciona una hora (o seleccionarlo de nuevo).
            
            Sb.trigger('beforeGetCitasByEspec',[]); // Disparando evento

            if(!idsede || !idespec || !fecha) return ;

            MOD.getCitasByEspec(idsede, idespec, fecha, fncAjaxCreateButtonsHours);
        }

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
            Sb.events(['onClickBtnsHours'], onClickBtnsHours  , MOD);
            
            Sb.events(['beforeGetCitasByEspec'], function(){
                log('agendar-cita->init->event->beforeGetCitasByEspec: function void');
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
                var idespec = parseInt($.trim($(this).val()));
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
},['vendor/moment/min/moment.min.js','js/jquery.easing.js']);

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
            if($().mask){
                $usufecnac.bind('blur',function(){
                    var val = $.trim($(this).val());
                    $hdnfecnac.val( val?moment(val).format('YYYY-MM-DD'):'' );
                }).mask('00/00/0000',{
                    clearIfNotMatch:true,
                    onComplete:function(val){ $hdnfecnac.val(moment(val).format('YYYY-MM-DD')); },
                    onInvalid:function(){ $hdnfecnac.val(''); }
                });
            }

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
                //$btnNext.prop('disabled',true);
                //fncStatusTabs(2, false);
                //fncStatusTabs(3, false);
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
// ----------------------------------------------------------------------------------------------------------------

/**
 * @param {Object} Sb SandBox
 */
yOSON.AppCore.addModule('cuenta-datos', function(Sb){
    
    var $inpFecnac      = $('#formRegister [name="fecnac"]') // fecha de nacimiento q se envia por post
        ,$usufecnac     = $('#usufecnac') //Mascara        
        ,$bstrModal     = $('#bstrModalReagendar')
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
                if($.trim($link.attr('id-usu'))!==''){
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
                    type:"POST",url:Global.baseHost+'/portal/usuario/reagendar?adm',data:datapost,
                    fncDone:function(rpta){
                        if(rpta.success){ log('Reagendado!'); }
                        $('a[data-whatever="'+datapost['idcita']+'"]').addClass('disabled').html('Cambiado');
                        $('#txtDatehour'+datapost['idcita']).html(datapost['datehour']); //console.log($txtDatehour,datapost['datehour']);
                        $bstrModal.modal('hide');
                        $btnReagendar.prop('disabled', false);
                        location.href = '';
                    }
                });
            });
            
            // Habilitando BtnReagendar al hacer click en los tags de hora
            Sb.events(['onClickBtnsHours'], function(THIS){ $btnReagendar.prop('disabled', false); }, this);
            
            // Seteando values dentro de modal reagendar
            $bstrModal.bind('hidden.bs.modal', function(e){
                $idseguro.prop('selectedIndex',0);
                $idseguro.trigger('change');
            });
        }
    };
},['vendor/moment/min/moment.min.js']);

/***
 * 
 */
yOSON.AppCore.addModule('websocket-horario', function(Sb){
    
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
                var conn = new WebSocket('ws://localhost:8089'); // ws://ws.centromedicoosi.com:8089
                conn.onopen = function(e) {
                    console.log("Connection established!", e);
                    $(document).ajaxSuccess(function(){
                        conn.send('{"action":"getIdhs","data":"","ua":"'+getUA()+'"}');
                    });
                };
                
                conn.onmessage = function(e) {
                    
                    var wsRpta = (new Function('return '+e.data))();
                    console.log('e.data:',wsRpta);
                    
                    var evts = $('#calendar').fullCalendar('clientEvents');
                    var idhs = wsRpta.idhs;
                    console.log("idhs:", idhs);
                    for (var i in evts) {
                        // Limpiando y reseteando segun vayan interactuando
                        console.log("=====>:",evts[i]);
                        if (!evts[i].reservedInDb) {
                            evts[i].durationEditable = true;
                            evts[i].startEditable = true;
                            evts[i].color = "#6BA5C2";
                        }
                        var idh = evts[i].idhorario;
                        for (var j in idhs) {
                            if (idh===j) {
                                evts[i].durationEditable = false;
                                evts[i].startEditable = false;
                                evts[i].color = "#F4C27A";
                            }
                        }
                    }
                    $('#calendar').fullCalendar('rerenderEvents');
                }
            }
        }
    }
});