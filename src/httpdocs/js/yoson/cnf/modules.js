/*=========================================================================================
 *@ListModules: Listado de todos los Modulos asociados al portal
 **//*===================================================================================*/
yOSON.AppSchema.modules = {
    // Modules
    portal:{
        controllers:{
            index:{
                actions:{
                    index: function(){
                        yOSON.AppCore.runModule('home-slide',{/*oPars*/});
                        yOSON.AppCore.runModule('index-varios',{/*oPars*/});
                    },
                    blog: function(){ /*yOSON.AppCore.runModule('validation',{form:"#frmLogin"})*/ },
                    contactenos: function(){ yOSON.AppCore.runModule('contactenos-sedes') },
                    'solicite-cita2': function(){
                        yOSON.AppCore.runModule('websocket-conection');
                        yOSON.AppCore.runModule('agendar-cita');
                        yOSON.AppCore.runModule('procesar-usuario');
                        yOSON.AppCore.runModule('solicite-cita');
                    },
                    // ...
                    byDefault: function(){ yOSON.AppCore.runModule('contactenos-sedes') },
                },
                allActions: function(){}
            },
            nosotros:{
                actions:{
                    staff: function(){
                        yOSON.AppCore.runModule('nuestros-especialistas');
                    },
                    byDefault: function(){}
                },
                allActions: function(){}
            },
            usuario:{
                actions:{
                    cuenta: function(){
                        yOSON.AppCore.runModule('agendar-cita');
                        yOSON.AppCore.runModule('procesar-usuario');
                        yOSON.AppCore.runModule('cuenta-datos');
                    },
                    reagendar: function(){ yOSON.AppCore.runModule('dis-viewP') },
                    byDefault: function(){}
                },
                allActions: function(){}
            },
            // ...
            byDefault: function(){}
        },
        allControllers:function(){
            yOSON.AppCore.runModule('portal-mapa');
            yOSON.AppCore.runModule('visita-nuestras-sedes',{moduleName:'visita-nuestras-sedes'});
            yOSON.AppCore.runModule('portal-menus'); 
            yOSON.AppCore.runModule('widget-login');
            yOSON.AppCore.runModule('usuario-registrar');
            yOSON.AppCore.runModule('widget-work-here'); // widget trabaje con nosotros
            yOSON.AppCore.runModule('all-varios');
        }
    },
    
    // MODULO DE ADMINSTRACION DEL PORTAL
    admin:{
        controllers:{
            index:{
                actions:{
                    index: function(){
                        yOSON.AppCore.runModule('admin-login',{/*oPars*/});
                    },
                    byDefault: function(){}
                },
                allActions: function(){}
            },
            citas:{
                actions:{
                    index: function(){
                        yOSON.AppCore.runModule('citas-list',{/*oPars*/});
                        yOSON.AppCore.runModule('usuario-registrar',{/*oPars*/});
                        yOSON.AppCore.runModule('portal-mapa');
                        yOSON.AppCore.runModule('agendar-cita');
                        yOSON.AppCore.runModule('cuenta-datos');
                    },
                    process: function(){
                        yOSON.AppCore.runModule('websocket-conection');
                        yOSON.AppCore.runModule('portal-mapa');
                        yOSON.AppCore.runModule('agendar-cita');
                        yOSON.AppCore.runModule('procesar-usuario');
                        yOSON.AppCore.runModule('solicite-cita');
                    },
                    byDefault: function(){}
                },
                allActions: function(){}
            },
            
            horario:{
                actions:{
                    index: function(){
                        yOSON.AppCore.runModule('websocket-horario',{/*oPars*/});
                        yOSON.AppCore.runModule('horario-espec',{/*oPars*/});
                    },
                    especialistas: function(){
                        yOSON.AppCore.runModule('edit-esp-sede',{/*oPars*/});
                    },
                    sedes: function(){
                        yOSON.AppCore.runModule('edit-esp-sede',{/*oPars*/});
                    },
                    byDefault: function(){}
                },
                allActions: function(){}
                
            },
            byDefault: function(){}
            
        },
        allControllers:function(){
            //yOSON.AppCore.runModule('list-product');
        }
    },
    /*-------------------------------------------------------------------------------------
     *@byDefault: De no haber @modules se ejecuta esta por defecto
     **//*-------------------------------------------------------------------------------*/
    byDefault : function(){ /*yOSON.AppCore.runModule('for-all-modules');*/ },
    
    /*-------------------------------------------------------------------------------------
     *@allModules: Modulos que se ejecutaran en todos los modulos
     *@param {Object} oMCA: Variable JSON con el modulo, controlador y action.
     **//*-------------------------------------------------------------------------------*/
    allModules : function(oMCA){

    }    
};