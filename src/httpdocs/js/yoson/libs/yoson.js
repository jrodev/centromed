/**
 * Global Namespace para yOSON Architect
 */
window.yOSON = window.yOSON||{
    AppCore   : { /*Implementacion de Core en archivo Core.js */ },
    AppSandbox: { /*Implementacion de Sandbox en archivo Sandbox.js*/ },
    AppSchema : { modules:{}, validations:{} /*, others:{}*/ },
    Utils     : { /* Metodos y funciones extras */ }
}; 

/**
 * Extend Array - Copiando array a otro

Array.prototype.copy = function(aArray) {
   for(var x=0; x<aArray.length; x++){this.push(aArray[x]);}
   //Console.log(arguments.callee.caller);
   return this;
};
*/
yOSON.Utils.arrayCopy = function(a1, a2){
   for(var x=0; x<a2.length; x++){a1.push(a2[x]);}
   return a1;
};


/**
 * isArray crossbrowsing
 */
if (typeof (Array.isArray) === 'undefined') {
    Array.isArray = function (obj) {
        return Object.prototype.toString.call(obj) === '[object Array]';
    }
};

/**
 * Remover Items de un array
 * 
 * Remove the second item from the array           :        array.remove(1);
 * Remove the second-to-last item from the array   :        array.remove(-2);
 * Remove the second and third items from the array:        array.remove(1,2);
 * Remove the last and second-to-last items from the array: array.remove(-2,-1);
 *
 * @return {Array} Array con elemento ya eliminado
 */
 /* Ext Array(detectar existencia: typeof(Array().remove)=='function' o 
  * typeof(Array.prototype.push) o Array.prototype.hasOwnProperty('push')) 
Array.prototype.remove = function(from, to) {
    var rest = this.slice((to || from) + 1 || this.length);
    this.length = from < 0 ? this.length + from : from;
    this.push.apply(this, rest);return this;
};
*/
yOSON.Utils.arrayRemove = function(arr, from, to){
    var rest = arr.slice((to || from) + 1 || arr.length);
    arr.length = from < 0 ? arr.length + from : from;
    arr.push.apply(arr, rest); return arr;
};

/**
 * Implementando Ad Event listener CrossBrowsing. Asociandolo al Namespace: yOSON
 * dentro Utils, para no hacer conflicto con otras implementaciones semejantes.
 */
(function(){
  // I test for features at the beginning of the declaration instead of everytime 
  // that we have to add an event.
  if(document.addEventListener) {
    yOSON.Utils.addEvent = function (elem, type, handler, useCapture){
      elem.addEventListener(type, handler, !!useCapture);
      return handler; // for removal purposes
    }
    yOSON.Utils.removeEvent = function (elem, type, handler, useCapture){
      elem.removeEventListener(type, handler, !!useCapture);
      return true;
    }
  } 
  else if (document.attachEvent) {
    yOSON.Utils.addEvent = function (elem, type, handler) {
      type = "on" + type;
      // Bounded the element as the context 
      // Because the attachEvent uses the window object to add the event and we 
      // don't want to polute it.
      var boundedHandler = function() {
        return handler.apply(elem, arguments);
      };
      elem.attachEvent(type, boundedHandler);
      return boundedHandler; // for removal purposes
    }
    yOSON.Utils.removeEvent = function(elem, type, handler){
      type = "on" + type;
      elem.detachEvent(type, handler);
      return true;
    }
  } 
  else { 
    // FALLBACK ( I did some test for both your code and mine, the tests are at the bottom. )
    // I removed wrapping from your implementation and added closures and memoization.
    // Browser don't support W3C or MSFT model, go on with traditional
    yOSON.Utils.addEvent = function(elem, type, handler){
      type = "on" + type;
      // Applying some memoization to save multiple handlers
      elem.memoize = elem.memoize || {};
      // Just in case we haven't memoize the event type yet.
      // This code will be runned just one time.
      if(!elem.memoize[type]){
        elem.memoize[type] = { counter: 1 };
        elem[type] = function(){
          for(var key in nameSpace){
            if(nameSpace.hasOwnProperty(key)){
              if(typeof nameSpace[key] == "function"){
                nameSpace[key].apply(this, arguments);
              };
            };
          };
        };
      };
      // Thanks to hoisting we can point to nameSpace variable above.
      // Thanks to closures we are going to be able to access its value 
      // when the event is triggered.
      // I used closures for the nameSpace because it improved 44% in 
      // performance in my laptop.
      var nameSpace = elem.memoize[type], id = nameSpace.counter++;
      nameSpace[id] = handler;
      // I return the id for us to be able to remove a specific function binded 
      // to the event.
      return id;
    };
    yOSON.Utils.removeEvent = function(elem, type, handlerID){
      type = "on" + type;
      // I remove the handler with the id
      if(elem.memoize && elem.memoize[type] && elem.memoize[type][handlerID]) 
          elem.memoize[type][handlerID] = undefined;
      return true;
    };

  };
})();


yOSON.Utils.fillSelect = function(elem, type, handlerID){
    
};


/* gestiona todos los oyentes y los notificadores de la aplicación
 * requires :Core.js
 * type :Object */
yOSON.AppSandbox = function(){
    return {
        /**
         * @param {String} sType Tipo de evento registrado pj. 'load-prods', 'set-point-map'
         * @param {Array} aData Array de paramentros para el metodo evento publicado.
         * @return {Object} Un objeto mapeando los returns de todos metodos asociados a un 
         *                  Nombre de evento.
         *                  para un evento ej: {'methodname1':RETURN1, 'methodname2':RETURN2,  }
         * Cada metodo suscrito a un nombre de metodo devera de retornar de la sguiente forma:
         * un objeto:  {'method':'methodName','return':varToReturn}
         * donde VALOR_DE_RETORNO es lo que retorna, el metodo suscrito especifico, solamente.
         */
        trigger: function(sType, aData){
            var oAction;
            if(typeof(yOSON.AppSandbox.aActions[sType])!=="undefined"){ /*Si existe en las acciones*/
                var nActL=yOSON.AppSandbox.aActions[sType].length;
                var oReturn = {};
                while(nActL--){
                    oAction = yOSON.AppSandbox.aActions[sType][nActL]; /*oAction <> {module:oModule, handler:fpHandler}*/
                    var oRes = oAction.handler.apply(oAction.module, aData); /*handler ??*/
                    if(oRes && oRes.hasOwnProperty('method') && oRes.hasOwnProperty('return')){
                        oReturn[oRes['method']]=oRes['return'];
                    }
                }
                return oReturn;
            }else log("Action["+sType+"]: No existe!");            
        },
        
        stopEvents: function(aEventsToStopListen,oModule){//Sandbox.stopEvents deja de escuchar algunos eventos en cualquier modulo
            var aAuxActions = [];
            var nLenEventsToListen=aEventsToStopListen.length;
            
            for(var nEvent=0; nEvent < nLenEventsToListen; nEvent++){
                var sEvent = aEventsToStopListen[nEvent];
                var nLenActions = yOSON.AppSandbox.aActions[sEvent].length;
                for(var nAction = 0; nAction < nLenActions; nAction++){
                    if(oModule != yOSON.AppSandbox.aActions[sEvent][nAction].module){
                        aAuxActions.push(yOSON.AppSandbox.aActions[sEvent][nAction]);
                    }
                }
                yOSON.AppSandbox.aActions[sEvent] = aAuxActions;
                if(yOSON.AppSandbox.aActions[sEvent].length == 0){delete yOSON.AppSandbox.aActions[sEvent];}
            }

        },
        events: function(aEventsToListen, fpHandler, oModule){ //this.event  empieza a escuchar algunos eventos en cualquier módulo
            /*log('|events-->');log(fpHandler);*/
            var nLenEventsToListen = aEventsToListen.length;
            for(var nEvent = 0; nEvent < nLenEventsToListen; nEvent++){
                var sEvent = aEventsToListen[nEvent];
                if(typeof yOSON.AppSandbox.aActions[sEvent] == "undefined"){ /*Si no existe en las acciones*/
                    yOSON.AppSandbox.aActions[sEvent] = [];
                }
                /*log("Sandbox-listen - line:45 - notifyListen:"+sEvent+" <--> module:"+oModule);*/
                yOSON.AppSandbox.aActions[sEvent].push({module:oModule, handler:fpHandler}); 
            }return this;
        },
        objMerge: function(){//param : obj1 obj2 obj3 ... 
            var out={}, argL=arguments.length;
            if(!argL) return out;
            while(--argL)
                for(var key in arguments[argL])
                    out[key]=arguments[argL][key];
            return out;
        },
        
        /***/
        ajax: function(o){
            if(!jQuery || !jQuery.hasOwnProperty('ajax')){
                log('yOSON.AppSandbox->ajax: Jquery o metodo ajax no existe!');
                return ;
            }
            log("yOSON.AppSandbox->ajax:",o);
            var fncDone = function(rpta){
                if(o.hasOwnProperty('fncDone')){ o.fncDone(rpta); }
                else{ log('fncDone->rpta:',rpta); }
            };
            var fncAlways = function(rpta){
                if(o.hasOwnProperty('fncAlways')){ o.fncAlways(rpta); }
                else{ log('fncAlways->rpta:',rpta); }
            };
            jQuery.ajax({type:o.type,url:o.url,data:o.data}).done(fncDone).always(fncAlways);
        },
        
        request: function(sUrl, oData, oHandlers, sDatatype){//debe utilizarse para realizar llamadas ajax dentro de los modulos
            Core.ajaxCall(sUrl,oData,oHandlers,sDatatype);
        }
    };
};
/*Sandbox.aActions is the static variable that stores all the listeners of all the modules*/
yOSON.AppSandbox.aActions = [];
/**----------------------------------------------------------------------------------------------------
 * applicaction :yOSON.AppScript
 * description :Carga script Javascript o Css en la pagina para luego ejecutar funcionalidades dependientes.
 * example :yOSON.AppScript.charge('lib/plugins/colorbox.js,plugins/colorbox.css', function(){ load! } );
 *//*-------------------------------------------------------------------------------------------------*/
yOSON.AppScript = (function(statHost, filesVers){

    var urlDirJs  = "";    /*Directorio relativo Js*/
    var urlDirCss = "";    /*Directorio relativo Css*/
    var version   = "";    /*Release version*/
    var ScrFnc    = {/**/}; /* u_r_l:{state:true, fncs:[fn1,..]} Funciones y estado para un script cargandose*/
    /* Constructor */
    (function(url, vers){
        // urlDirJs=url+'js/'; urlDirCss=url+'styles/'; version=(true)?vers:'';
        urlDirJs=url+'/'; 
        urlDirCss=url+'/'; 
        version=(true)?vers:'';
    })(statHost, typeof(filesVers)!=="undefined"?filesVers:'');    
    /* Convierte una cadena url separada de _ */
    var codear = function(url){
        return (url.indexOf('//')!=-1)?url.split('//')[1].split('?')[0].replace(/[\/\.\:]/g,'_'):url.split('?')[0].replace(/[\/\.\:]/g,'_');
    };
    /* Agregando funciones para ejecutar una vez cargado el Script */
    var addFnc = function(url, fnc){
        if( !ScrFnc.hasOwnProperty(codear(url)) ){
            ScrFnc[codear(url)]={state:true, fncs:[]};/* State:true, para seguir agregando mas funcs a fncs) */
        }ScrFnc[codear(url)].fncs.push(fnc);
    };  
    /* Ejecuta las funciones aosciadas a un script */
    var execFncs = function(url){
        ScrFnc[codear(url)].state = false;
        for(var i=0; i<ScrFnc[codear(url)].fncs.length; i++){
            if(ScrFnc[codear(url)].fncs[i]=='undefined'){log(ScrFnc[codear(url)].fncs[i])}
            ScrFnc[codear(url)].fncs[i]();
        }
    };
    /* Cargador de Javascript */
    var loadJs = function(url, fnc){
        var scr = document.createElement("script");
        scr.type = "text/javascript";
        if(scr.readyState){  /*IE*/
            scr.onreadystatechange = function(){
                if(scr.readyState=="loaded" || scr.readyState=="complete"){scr.onreadystatechange=null;fnc(url);}
            };
        }else{scr.onload=function(){fnc(url);}}
        scr.src = url;
        document.getElementsByTagName("head")[0].appendChild(scr);
    };
    /* description :Cargador de Css */
    var loadCss = function(url, fnc){ /*Para WebKit (FF, Opera ...)*/
        
        var link = document.createElement('link');
        link.type='text/css';link.rel='stylesheet';link.href=url;
        document.getElementsByTagName('head')[0].appendChild(link);
        if(document.all){link.onload=function(){fnc(url);}}
        else{
            //log("Creando IMG charge para: "+url);
            var img=document.createElement('img');
            img.onerror=function(){
                //log("dentro de img.onerror: "+url);
                if(fnc){fnc(url);}document.body.removeChild(this);
            }
            document.body.appendChild(img);
            img.src=url;
        }
    };
    /* description :Carga el Script (js o css para luego ejecutar funcionalidades asociadas)
     * dependency : Es necesario tener implementado el metodo remove extendido al objeto array
     **/
    return {
        charge : function(aUrl, fFnc, sMod, lev){
            var THAT = this;  /*Referencia a este objeto*/
            if(aUrl.length===0||aUrl===undefined||aUrl===''){return false;} /*aUrl no valido*/
            if(aUrl.constructor.toString().indexOf('Array')!==-1 && aUrl.length===1){var aUrl = aUrl[0];} /*Array de 1 elemento*/
            var lev = (typeof(lev)!=='number')?1:lev;   /*Nivel de anidamiento en esta funcion*/

            if(aUrl.constructor.toString().indexOf('String')!==-1){    /*Si es una String*/                
                var isJs   = (aUrl.indexOf('.js') !==-1); /*Es script Js*/
                var isCss  = (aUrl.indexOf('.css')!==-1); /*Es script Css*/
                if(!isJs && !isCss)return false;         /*Si no es un script css o js termina la ejecucion*/
                var parts = aUrl.split('/');
                parts[parts.length-1]=((yOSON.min!==undefined && isJs)?yOSON.min:'')+parts[parts.length-1];
                aUrl = parts.join('/');
                var urlDir = isJs?urlDirJs:urlDirCss;
                if(isJs||isCss){  /* Si se va a cargar un Css o Js  (El numero randon es para SF-5.0.3 el cual necesita request diferentes para disparar onerror en img)*/
                    var aUrl = (aUrl.indexOf('http')!==-1) ? (aUrl+version) : (urlDir+aUrl+version+(isCss?(new Date().getTime()):''));
                    if( !ScrFnc.hasOwnProperty(codear(aUrl)) ){  /* Si es que no esta Registrado el script*/
                        addFnc(aUrl, fFnc); isJs?loadJs(aUrl, execFncs):loadCss(aUrl, execFncs); /*neoScr(url, true) true?? no va creo?*/
                    }else{                      /* Si se va a cargar un CSS*/
                        if(ScrFnc[codear(aUrl)].state){addFnc(aUrl,fFnc)}else{fFnc();}
                    }
                }
            }else{
                if(aUrl.constructor.toString().indexOf('Array')!==-1){  /*Si es una Array de 2 a mas aelementos (Arrba de valida 0 a 1 elementos)*/
                    this.charge(aUrl[0], function(){log((lev+1),(sMod.indexOf('popup')!=-1));
                        THAT.charge(yOSON.Utils.arrayRemove(aUrl,0)/*aUrl.remove(0)*/, fFnc, sMod, (lev+2))
                    }, sMod, (lev+1));
                }else{log(aUrl+' - no es un Array');}
            }
        }
    };
})(Global.baseHost, Global.statVers);
/*****************/

/*-----------------------------------------------------------------------------------------------------
 * Core :      : Estructure v1.0
 * @Description: Codigo para la manipulacion de los modulos en la aplicacion
 * Dependency :: yOSON.AppSandbox & yOSON.AppScript in appSandBox.js
 *//*-------------------------------------------------------------------------------------------------*/
/*
var loadElement = function(id, fnc, t){
    var t    = t?t:1;
    var oE   = document.getElementById(id);
    var time = window.setInterval(function(){
        if(oE){
            window.clearInterval(time);
            log(oE); fnc();
        }else{ log("nada"); }
    }, t);
    //window.load = function(){ window.clearInterval(time) };
};
loadElement('login-and-register',function(){alert('Exite!')},1);*/

yOSON.AppCore = (function(win, undefined){
    /*member :Core*/
    var oSandBox = new yOSON.AppSandbox(); /*private :Entorno de trabajo de todos los modulos (Caja de arena)*/
    var oModules = {};                     /*private :Almacena todos los modulos registrados*/
    var debug    = false;                  /*private :Habilitar-Deshabilitar modo de depuracion*/
    win.cont     = 0;
    
    /***
     * 
     * @param {String} sModuleId
     * @returns {Function}
     */
    var doInstance = function(sModuleId){
        // ejecutamos la definicion de un modulo pasandole como parametros Sb(oSandBox) y
        // opcionalmente idMod(sModuleId), dicha ejecucion lo almacenamos en var instance.
        var instance = oModules[sModuleId].definition( oSandBox, sModuleId );
        var name, method;
        if(!debug){
            for(name in instance){
                method = instance[name];
                if(typeof method == "function"){
                    instance[name] = function(name, method){
                        return function(){
                            try{
                                return method.apply(this,arguments);
                            }catch(ex){
                                log('catch ex:',ex);
                                log('instance name:',name);
                                log('sModuleId:',sModuleId);
                                log(name + "(): " + ex.message);
                            }
                        };
                    }(name, method);
                }
            }
        } return instance; /*retornamos la Instancia del modulo*/
    };
    
    return {
        /***
         * Adiciona un modulo a la aplicacion, para ser ejecutado posteriormente.
         * @param {String} sModuleId ID del modulo (El nombre debe de ser unico).
         * @param {Function} fDefinition funcion de definicion que retorna on objeto de la 
         *                               forma: {..init:function(){}, destroy:function(){}..}
         * @param {Array} aDeps Array con rutas absolutas o relativas de dependecias
         *                        que pueden ser JS o CSS.       
         * @returns {void}
         */
        addModule: function (sModuleId, fDefinition, aDeps) {
            var deps = (aDeps === undefined) ? [] : aDeps;
            if(!Array.isArray(deps)) {
                throw 'yOSON->addModule("'+sModuleId+'") error:dependecias no estan en un array';
                return ;
            }
            if( oModules[sModuleId] === undefined ) {
                oModules[sModuleId] = {
                    definition: fDefinition,
                    dependency: deps,
                    instance  : null // Se asigna mas abajo 
                };
            } else {
                console.log('oModules[sModuleId]:',oModules[sModuleId],'aDeps:',deps);
                throw 'module "'+sModuleId+'" is already defined, Please set it again'; 
            }
        },
        
        /***
         * Obtiene un modulo previamente registrado
         * @param {type} Identificador del modulo
         * @returns {Object} Modulo previamente registrado
         */
        getModule: function(sModuleId){
            if (sModuleId && oModules[sModuleId]){ return oModules[sModuleId]; }
            else{ throw 'Method getModule: param "sModuleId:'+sModuleId+'" is not defined or module not found'; }
        },
        
        /***
         * Ejecuta un modulo previamente registrado
         * @param {String} sModuleId Identificador del modulo
         * @param {Object} oParams json de paramentros
         * @returns {void}
         */
        runModule: function(sModuleId, oParams){
            
            if(oModules[sModuleId] !== undefined){
                
                if(oParams === undefined){ var oParams = {}; }
                oParams.moduleName = sModuleId;  /*Un primer valor de oParams*/
                var mod = this.getModule(sModuleId);
                var thisInstance = mod.instance = doInstance(sModuleId); 
                log('Module['+sModuleId+']->instance:',thisInstance);
                if(thisInstance.hasOwnProperty('init')){
                    if(mod.dependency.length>0){
                        yOSON.AppScript.charge(
                            yOSON.Utils.arrayCopy([], mod.dependency),// [].copy(mod.dependency), 
                            function(){ thisInstance.init(oParams); }, 
                            sModuleId+window.cont, 1
                        );
                    }else{ thisInstance.init(oParams); }
                    
                }else{
                    throw 'init() function is not defined in the module:"'+oModules[sModuleId]+'"';
                }
                
            }else{ throw 'module "'+sModuleId+'" is not defined or module not found'; }
        },
        
        /***
         * Ejecuta varios modulos
         * @param {Array} aModuleIds arreglo de nombres modulos a ejecutar
         * @returns {void}
         */
        runModules: function(aModuleIds){
            for(var id in aModuleIds){ this.runModule(aModuleIds[id]); }
        }
    }
})(window);

// Application Load
/*yOSON.Utils.addEvent(window,'load',function(){
    var modu = Global.module;
    var ctrl = Global.controller;
    var acti = Global.action;
    
    log('MODULE:'+modu+' - CONTROLLER:'+ctrl+' - ACTION:'+acti);

    yOSON.AppSchema.modules.allModules();
    if(modu=='' || !yOSON.AppSchema.modules.hasOwnProperty(modu)){
        yOSON.AppSchema.modules.byDefault();
    }
    else{
        yOSON.AppSchema.modules[ modu ].allControllers();
        if(ctrl=='' || !yOSON.AppSchema.modules[ modu ].controllers.hasOwnProperty(ctrl)){
            yOSON.AppSchema.modules[ modu ].controllers.byDefault();
        }else{
            yOSON.AppSchema.modules[ modu ].controllers[ ctrl ].allActions();
            if(acti=='' || !yOSON.AppSchema.modules[ modu ].controllers[ ctrl ].actions.hasOwnProperty(acti)){
                yOSON.AppSchema.modules[ modu ].controllers[ ctrl ].actions.byDefault();
            }else{
                yOSON.AppSchema.modules[ modu ].controllers[ ctrl ].actions[ acti ]();
            }
        }
    }
});*/