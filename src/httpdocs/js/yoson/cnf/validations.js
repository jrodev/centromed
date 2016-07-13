/**
 * Validacion de formularios (para validate js)
 */
yOSON.AppSchema.validations = {
    "#mis-datos":{
        ignore  : '',
        rules: {
            title: "required",
            message: "required",
            'captcha[input]': "required"
        },
        messages: {
            title: "Escriba el título",
            message: "Escriba el mensaje",
            'captcha[input]': "Escriba el código de seguridad"
        },
        errorPlacement: function(error, element){
            $(element).parent().append(error);
        }
    },
    "#frm-step2":{
        rules:{
            date:"required",
            nombre:"required",
            apellido:"required",
            direccion:"required",
            stateUbigeo:"required",
            provinciaUbigeo:"required",
            districtUbigeo:"required",
            email:{email:true},
            phone:{required:true}
        },
        messages:{
            date:{required:"Ingrese la fecha de envío"},
            nombre:{required:"Ingrese su Nombre"},
            apellido:{required:"Ingrese su Apellido"},
            direccion:{required:"Ingrese su Dirección"},
            stateUbigeo:{required:"Ingrese el Departamento"},
            provinciaUbigeo:{required:"Ingrese la Provincia"},
            districtUbigeo:{required:"Ingrese el Distrito"},
            email:{email:"Ingrese el email correctamente"},
            phone:{required:"Ingrese su teléfono"}
        }
    }
};