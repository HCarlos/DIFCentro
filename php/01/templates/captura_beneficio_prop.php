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
                        <td><label for="idbeneficiario" class="textRight">Beneficiario</label></td>
                        <td>
                            <span class="add-on"><i class="icon-asterisk red"></i></span>
                            
                            <!-- 
                            <select name="idbeneficiario" id="idbeneficiario" size="1" style="width:75% !important;" > 
                            </select> 
                            -->

                            <input class="altoMoz" name="beneficiario" id="beneficiario" type="text" value="" style="width:75% !important;" disabled required >

                            <span class="add-on">
                            <button class="btn btn-minier btn-warning" id="btnFindBen" style="margin-bottom: 1em;">
                                <i class="icon-filter  bigger-130 icon-only"></i>
                            </button>
                            </span>
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
    <input type="hidden" name="idbeneficiario" id="idbeneficiario" value="0">
    <input type="hidden" name="cbeneficiario" id="cbeneficiario" value="">
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
    var IdBeneficiario = 0;

    var arrItem    = [];

    var stream = io.connect(obj.getValue(4));

    function getBenOtor(IdBeneficioOtorgado){
        $.post(obj.getValue(0) + "data/", {o:1008, t:2021, c:IdBeneficioOtorgado, p:10, from:0, cantidad:0,s:''},
            function(json){
                if (json.length>0){
                    $("#idsubcatben").val(json[0].idsubcatben);
                    $("#cantidad").val(json[0].cantidad);
                    IdBeneficiario = json[0].idbeneficiario;
                    $("#idbeneficiario").val(json[0].idbeneficiario);
                    $("#beneficiario").val(json[0].beneficiario);
                    $("#cbeneficiario").val(json[0].beneficiario);
                    $("#fecha").val(json[0].fecha);
                    $("#idbeneficiario").focus();
                }
        },'json');
    }

    $("#frmData").unbind("submit");
    $("#frmData").on("submit",function(event){
        event.preventDefault();

        $("#preloaderPrincipal").show();

        var queryString = $(this).serialize();  
        
        var data = new FormData();

        if (validForm()){
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
        }else{
             $("#preloaderPrincipal").hide();
        }

    });

    $("#closeProp").on("click",function(event){
        $("#preloaderPrincipal").hide();
        $("#divUploadImage").modal('hide');
    });

    function validForm(){

        if($("#cantidad").val().length <= 0){
            alert("Faltan la Cantidad");
            $("#cantidad").focus();
            return false;
        }

        if($("#idbeneficiario").val() <= 0){
            alert("Faltan el Beneficiario");
            $("#btnFindBen").focus();
            return false;
        }

        if( IdBeneficiario <= 0 ){
            alert("Faltan el Beneficiario");
            $("#btnFindBen").focus();
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

    function getBeneficiarios(){
        var nc = "u="+localStorage.nc;
        $("#idbeneficiario").empty();
        $.post(obj.getValue(0)+"data/", { o:1, t:311, p:0,c:nc,from:0,cantidad:0, s:"" },
            function(json){
               $.each(json, function(i, item) {
                    $("#idbeneficiario").append('<option value="'+item.data+'"> '+item.label+'</option>');
                });
                
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
            $("#contentProfile").empty();
            $("#contentMain").show();
        });
        resizeScreen();
        return false;
    }


    $('.date-picker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });

    $('.date-picker').val(obj.getDateToday());

    getSubCatBen();
    getBeneficiarios();

    $("#btnFindBen").on('click', function(event) {
        event.preventDefault();
        $("#contentLevel3").empty();
        $("#contentProfile").hide(function(){
            $("#preloaderPrincipal").show();
            obj.setIsTimeLine(false);
            var nc = localStorage.nc;
            $.post(obj.getValue(0) + "find-beneficiario/", {
                    user: nc,
                },
                function(html) {                    
                    $("#contentLevel3").html(html).show('slow',function(){
                        $('#breadcrumb').html(getBar('Inicio, Captura de Servicios'));
                    });
                }, "html");
        });
        return false;

    });


	var stream = io.connect(obj.getValue(4));

    stream.on("servidor", jsNewConcepto);
    function jsNewConcepto(datosServer) {
        var ms = datosServer.mensaje.split("|");
        //alert(datosServer);
        //obj.setIsTimeLine(true);
        if (ms[1]=='FINDBEN') {
            //onClickFillTable();
            IdBeneficiario = ms[2];
            $("#idbeneficiario").val(ms[2]);
            $("#beneficiario").val(ms[3]);
            $("#cbeneficiario").val(ms[3]);
        }
    }



});

</script>