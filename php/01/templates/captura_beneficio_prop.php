<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Mexico_City');

header("html/text; charset=utf-8");  
header("Cache-Control: no-cache");

require_once("../oFunctions.php");
$F = oFunctions::getInstance();

require_once("../oCentura.php");
$f = oCentura::getInstance();

$user = $_POST['user'];
$idbeneficiootorgado  = $_POST['idbeneficiootorgado'];

?>

<h3 class="header smaller lighter blue">
    <span id="title"></span>
    <a class="label label-info arrowed-in-right arrowed closeFormUpload pull-right">Regresar</a>
</h3>

<form id="frmData"  class="form" role="form">


                <table>

                    <tr>
                        <td><label for="ap_paterno" class="textRight">Ap. Paterno</label></td>
                        <td>
                            <span class="add-on"><i class="icon-asterisk red"></i></span>
                            <input class="altoMoz" name="ap_paterno" id="ap_paterno" type="text" required >
                        </td>
                        <td colspan="4"></td>
                    </tr>

                    <tr>
                        <td><label for="ap_materno" class="textRight">Ap. Materno</label></td>
                        <td>
                            <span class="add-on"><i class="icon-asterisk red"></i></span>
                            <input class="altoMoz" name="ap_materno" id="ap_materno" type="text" required >
                        </td>
                        <td colspan="4"></td>
                    </tr>

                    <tr>
                        <td><label for="nombre" class="textRight">Nombre</label></td>
                        <td>
                            <span class="add-on"><i class="icon-asterisk red"></i></span>
                            <input class="altoMoz" name="nombre" id="nombre" type="text" required >
                                <label class="textLeft">Sexo</label>
                                    <span>
                                      <input type="radio" name="sexo" id="inlineRadio1" value="0" > Indefinido
                                    </span>
                                    <span>
                                      <input type="radio" name="sexo" id="inlineRadio2" value="1"> Hombre
                                    </span>
                                    <span>
                                      <input type="radio" name="sexo" id="inlineRadio3" value="2"> Mujer
                                    </span>
                         </td>
                        <td colspan="4">
                        </td>
                    </tr>


                    <tr>
                        <td><label for="telefono" class="textRight">Teléfono</label></td>
                        <td>
                            <span class="add-on"><i class="icon-asterisk white"></i></span>
                            <input class="altoMoz" name="telefono" id="telefono" type="text" >
                        </td>
                        <td colspan="4"></td>
                    </tr>

                    <tr>
                        <td><label for="correo_electronico" class="textRight">Correo-Electrónico</label></td>
                        <td>
                            <span class="add-on"><i class="icon-asterisk white"></i></span>
                            <input class="altoMoz" name="correo_electronico" id="correo_electronico" type="text" >
                        </td>
                        <td colspan="4"></td>
                    </tr>

                    <tr>
                        <td><label for="idsubcatben" class="textRight">Servicio</label></td>
                        <td>
                            <span class="add-on"><i class="icon-asterisk red"></i></span>
                            <select name="idsubcatben" id="idsubcatben" size="1" style="width:80% !important;" > 
                            </select>
                        </td>
                        <td colspan="4"></td>
                    </tr>

                    <tr>
                        <td><label for="cantidad" class="textRight">Cantidad</label></td>
                        <td>
                            <span class="add-on"><i class="icon-asterisk red"></i></span>
                            <input class="altoMoz" name="cantidad" id="cantidad" type="text" pattern="[-+]?[0-9]*[.,]?[0-9]+" required >
                        </td>
                        <td colspan="4"></td>
                    </tr>

                    <tr>
                        <td><label for="idlocalidad" class="textRight">Localidad</label></td>
                        <td>
                            <span class="add-on"><i class="icon-asterisk red"></i></span>
                            <select name="idlocalidad" id="idlocalidad" size="1" style="width:80% !important;" > 
                            </select>
                        </td>
                        <td colspan="4"></td>
                    </tr>

                    <tr>
                        <td><label for="fecha">Fecha </label></td>
                        <td>
                            <span class="add-on"><i class="icon-asterisk red"></i></span>
                            <input class="date-picker altoMoz" id="fecha" name="fecha" data-date-format="dd-mm-yyyy" type="text">
                            <span class="add-on">
                                <i class="icon-calendar"></i>
                            </span>
                        </td>
                    </tr>

                </table>
 

    <input type="hidden" name="idbeneficiootorgado" id="idbeneficiootorgado" value="<?php echo $idbeneficiootorgado; ?>">
    <input type="hidden" name="user" id="user" value="<?php echo $user; ?>">
    <div class="form-group w96" style='margin-right: 3em; margin-top: 1em;'>
    	<button type="submit" class="btn btn-primary pull-right" style='margin-right: 4em;'><i class="icon-save"></i>Guardar</button>
	</div>

</form>

</div>
</div>

<!--PAGE CONTENT ENDS-->

<script typy="text/javascript">        

jQuery(function($) {

	$("#preloaderPrincipal").hide();

    localStorage.vsm =  obj.getConfig(2);    


	var idbeneficiootorgado = <?php echo $idbeneficiootorgado ?>;

    var arrItem    = [];

    var stream = io.connect(obj.getValue(4));

    function getBenOtor(IdBeneficioOtorgado){
        $.post(obj.getValue(0) + "data/", {o:1008, t:2021, c:IdBeneficioOtorgado, p:10, from:0, cantidad:0,s:''},
            function(json){
                if (json.length>0){
                    $("#ap_paterno").val(json[0].ap_paterno);
                    $("#ap_materno").val(json[0].ap_materno);
                    $("#nombre").val(json[0].nombre);
                    $("#telefono").val(json[0].telefono);
                    $("#correo_electronico").val(json[0].correo_electronico);
                    $("#idsubcatben").val(json[0].idsubcatben);
                    $("#cantidad").val(json[0].cantidad);
                    $("#idlocalidad").val(json[0].idlocalidad);
                    $("#fecha").val(json[0].fecha);
                    $("input[name=sexo][value="+json[0].sexo+"]").prop('checked', true);
                    $("#ap_paterno").focus();
                }
        },'json');
    }

    $("#frmData").unbind("submit");
    $("#frmData").on("submit",function(event){
        event.preventDefault();

        $("#preloaderPrincipal").show();

        var queryString = $(this).serialize();  
        
        //alert(queryString)
        // return false;

        var data = new FormData();

        // if (validForm()){
            var IdBeneficioOtorgado = (idbeneficiootorgado==0?0:1)
            $.post(obj.getValue(0) + "data/", {o:1008, t:IdBeneficioOtorgado, c:queryString, p:2, from:0, cantidad:0, s:''},
            function(json) {
                    if (json[0].msg=="OK"){
                        alert("Datos guardados con éxito.");
                        stream.emit("cliente", {mensaje: "SIBDMUN-BENEFICIO_OTORGADO-PROP-"+IdBeneficioOtorgado});
                        $("#preloaderPrincipal").hide();
                        closeWindow();
                    }else{
                        $("#preloaderPrincipal").hide();
                        alert(json[0].msg); 
                    }
            }, "json");
        // }else{
        //     $("#preloaderPrincipal").hide();
        // }

    });

    $("#closeProp").on("click",function(event){
        $("#preloaderPrincipal").hide();
        $("#divUploadImage").modal('hide');
    });

    function validForm(){

        if($("#localidad").val().length <= 0){
            alert("Faltan el Localidad");
            $("#localidad").focus();
            return false;
        }

        return true;

    }


    function getSubCatBen(){
        var nc = "u="+localStorage.nc;
        $("#idsubcatben").empty();
        $.post(obj.getValue(0)+"data/", { o:1, t:309, p:0,c:nc,from:0,cantidad:0, s:"" },
            function(json){
               $.each(json, function(i, item) {
                    $("#idsubcatben").append('<option value="'+item.data+'"> '+item.label+'</option>');
                });

               getCatLoc();   
                
            }, "json"
        );  
    }

    function getCatLoc(){
        var nc = "u="+localStorage.nc;
        $("#idlocalidad").empty();
        $.post(obj.getValue(0)+"data/", { o:1, t:310, p:0,c:nc,from:0,cantidad:0, s:"" },
            function(json){
               $.each(json, function(i, item) {
                    $("#idlocalidad").append('<option value="'+item.data+'"> '+item.label+'</option>');
                });

                if (idbeneficiootorgado<=0){ 
                    $("#title").html("Nuevo registro");
                    //$("input:radio[name='sexo']").val(0);
                    $("input[name=sexo][value=0]").prop('checked', true);
                    $("#ap_paterno").focus();
                }else{ // Editar Registro
                    $("#title").html("Editando el registro: "+idbeneficiootorgado);
                    getBenOtor(idbeneficiootorgado);
                }
                
            }, "json"
        );  
    }


	// close Form
	$(".closeFormUpload").on("click",function(event){
		event.preventDefault();
        closeWindow();
	});

    function closeWindow() {
        $("#preloaderPrincipal").hide();
        $("#contentProfile").hide(function(){
            $("#contentProfile").html("");
            $("#contentMain").show();
        });
        resizeScreen();
        return false;
    }


    $('.date-picker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });

/*
    $('.date-picker').on('changeDate', function(event){
        $(this).datepicker('hide');
        //validDate();
    });
*/

    $('.date-picker').val(obj.getDateToday());

    getSubCatBen();

	var stream = io.connect(obj.getValue(4));


});

</script>