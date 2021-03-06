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
$idcontribucion  = $_POST['idcontribucion'];

?>

<h3 class="header smaller lighter blue">
    <span id="title"></span>
    <a class="label label-info arrowed-in-right arrowed closeFormUpload pull-right">Regresar</a>
</h3>

<form id="frmData"  class="form" role="form">


                <table>

                    <tr>
                        <td><label for="concepto" class="textRight">Concepto</label></td>
                        <td class="wd80prc">
                            <span class="add-on"><i class="icon-asterisk red"></i></span>

<!--                             <select name="concepto" id="concepto" size="1" style="width:80% !important;" > </select> -->
                            <input class="input-large form-control altoMoz wd100prc show" id="search" name="search" type="text" placeholder="Concepto" autofocus>

                        </td>
                        <td colspan="4"></td>
                    </tr>

                    <tr id="vsm0">
                        <td><label for="param0" class="textRight">VSM</label></td>
                        <td>
                            <span class="add-on"><i class="icon-asterisk red"></i></span>
                            <select name="param0" id="param0" size="1" > </select>
                        </td>
                        <td colspan="4"></td>
                    </tr>

<!--
                    <tr>
                        <td><label for="param1" class="textRight">Costo</label></td>
                        <td>
                            <span class="add-on"><i class="icon-asterisk red"></i></span>
                            <select name="param1" id="param1" size="1" style="width:80% !important;" > </select>
                        </td>
                        <td colspan="4"></td>
                    </tr>
-->  

                    <tr>
                        <td><label for="precio_unitario" class="textRight">Costo</label></td>
                        <td>
                            <span class="add-on"><i class="icon-asterisk red"></i></span>
                            <input class="altoMoz" name="precio_unitario" id="precio_unitario" type="text" pattern="[-+]?[0-9]*[.,]?[0-9]+" required readonly>
                        </td>
                        <td colspan="4"></td>
                    </tr>

                    <tr>
                        <td><label for="cantidad" class="textRight">Cantidad</label></td>
                        <td>
                            <span class="add-on"><i class="icon-asterisk red"></i></span>
                            <input class="altoMoz" name="cantidad" id="cantidad" type="text" pattern="[-+]?[0-9]*[.,]?[0-9]+" required>
                        </td>
                        <td colspan="4"></td>
                    </tr>

                    <tr>
                        <td><label for="importe" class="textRight">Importe</label></td>
                        <td>
                            <span class="add-on"><i class="icon-asterisk red"></i></span>
                            <input class="altoMoz" name="importe" id="importe" type="text" pattern="[-+]?[0-9]*[.,]?[0-9]+" required readonly>
                        </td>
                        <td colspan="4"></td>
                    </tr>

                    <tr>
                        <td><label for="contribuyente" class="textRight">Contribuyente</label></td>
                        <td colspan="3">
                            <span class="add-on"><i class="icon-asterisk red"></i></span>
                            <input class="wd80prc altoMoz" name="contribuyente" id="contribuyente" type="text" >
                        </td>
                        <td></td>
                    </tr>

                    <tr>
                        <td><label for="observaciones" class="textRight">Observaciones</label></td>
                        <td colspan="3">
                            <span class="add-on"><i class="icon-asterisk red"></i></span>
                            <input class="wd80prc altoMoz" name="observaciones" id="observaciones" type="text" >
                        </td>
                        <td></td>
                    </tr>

                </table>
 

    <input type="hidden" name="concepto" id="concepto" value="0">
    <input type="hidden" name="idcontribucion" id="idcontribucion" value="<?php echo $idcontribucion; ?>">
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

    $("#clave_nivel").focus();

    $("#vsm0").hide();

	var idcontribucion = <?php echo $idcontribucion ?>;

    var IdIngreso = 0;
    var cIngreso = "";
    var data = [];

    var arrItem    = [];
    var Tipo = 0;

    $("#frmData").unbind("submit");
	$("#frmData").on("submit",function(event){
		event.preventDefault();

        if (validForm()){
            
            var resp =  confirm("En este momento se dispone a guardar sus datos.\n\n Sus datos estan correctos?");

            //alert(resp);
            //return false;
            
            if (resp){

    		    var queryString = $(this).serialize();	

                // alert(queryString);

    			var IdContribucion = (idcontribucion==0?0:1)
                $.post(obj.getValue(0) + "data/", {o:1005, t:IdContribucion, c:queryString, p:2, from:0, cantidad:0, s:''},
                function(json) {
                		if (json[0].msg=="OK"){
                			alert("Datos guardados con éxito.");
    						stream.emit("cliente", {mensaje: "SIIM-CONTRIBUCIONES-PROP-"+idcontribucion+"-"+localStorage.nc});
    						$("#preloaderPrincipal").hide();
    						$("#contentProfile").hide(function(){
    							$("#contentProfile").html("");
    							$("#contentMain").show();
    						});
            			}else{
    						$("#preloaderPrincipal").hide();
            				alert(json[0].msg);	
            			}
            	}, "json");

            }

        }else{
            $("#preloaderPrincipal").hide();
        }

	});

    $.widget( "custom.catcomplete", $.ui.autocomplete, {
        _renderMenu: function( ul, items ) {
            var that = this,
            currentCategory = "";
            $.each( items, function( index, item ) {
                if ( item.category != currentCategory ) {
                    ul.append( "<li class='ui-autocomplete-category' id='act-"+item.indice+"' >" + item.category + "</li>" );
                    currentCategory = item.category;
                }
                that._renderItemData( ul, item );
            });
        }
    
    });

    function getConceptosUser(){
        var nc = "u="+localStorage.nc;
        arrItem = [];
 /*
        arrItem = [];
        $("#concepto").html('<option value="-1">Seleccione un Concepto</option>');
        $.post(obj.getValue(0)+"data/", { o:1, t:304, p:0,c:nc,from:0,cantidad:0, s:"" },
            function(json){
*/
        $.ajax({ url: obj.getValue(0)+"data/",
            data: { o:1, t:304, p:0,c:nc,from:0,cantidad:0, s:"" },
            dataType: "json",
            type: "POST",
            success: function(json){


               $.each(json, function(i, item) {
                
                    arrItem[item.idingreso] = {
                                    idingreso:item.idingreso, 
                                    ingreso:item.ingreso, 
                                    vsm_min:parseInt(item.dsm_min,0),
                                    vsm_max:parseInt(item.dsm_max,0),
                                    costo_min:parseFloat(item.costo_min).toFixed(2),
                                    costo_max:parseFloat(item.costo_max).toFixed(2),
                                    tipo:parseInt(item.tipo,0)
                                };
                                
                    data[i]={ label: item.ingreso, category: "Concepto", indice: item.idingreso };
            
                    //$("#concepto").append('<option value="'+item.idingreso+'"> '+item.ingreso+'</option>');
                });

                $( "#search" ).catcomplete({
                    delay: 0,
                    source: data,
                    autoFocus: true,
                    select: function(event, ui) { 
                        if (ui.item){
                            cIngreso = ui.item.value;
                            IdIngreso = ui.item.indice;
                            Tipo = IdIngreso;
                            $("#concepto").val(Tipo);
                            pintaPagosList(Tipo);
                        }
                    },
                    open: function() {
                        $('.ui-autocomplete-categorya').next('.ui-menu-item').addClass('ui-first');
                    }                   
                });
                
                $("#search").focus();


                if (idcontribucion<=0){ // Nuevo Registro
                    $("#title").html("Nuevo registro");
                    $("#clave_nivel").focus();
                }else{ // Editar Registro
                    $("#title").html("Editando el Pago: "+idcontribucion);
                    //getPago(idcontribucion);
                }


            }
        });
    
    }

    $("#concepto").on("change",function(event){
        event.stopPropagation();
        var index = $(this).val();
        Tipo = index;
        pintaPagosList(index);
    });

    function pintaPagosList(index){
        $("#vsm0").hide();
        $("#precio_unitario").attr("readonly", true);
        switch( arrItem[index].tipo ){
            case 1:
            case 2:
            case 3:
            case 6:
            case 7:
            case 8:
            case 9:
                    
                    $("#precio_unitario").attr("readonly", false);
                    $("#precio_unitario").focus();

                    break;
            case 4:
            case 5:
                    $("#vsm0").show();
                    $("#param0").html('<option value="-1">Seleccione el Costo</option>');

                    for(var i=arrItem[index].vsm_min; i <= arrItem[index].vsm_max; i++){
                        $("#param0").append('<option value="'+i+'"> '+i+'</option>');
                    }
                    

                    break;
        }


    }

    $("#precio_unitario").on("change",function(event){
        event.stopPropagation();
        calculoPago();
    });  

    $("#cantidad").on("change",function(event){
        event.stopPropagation();
        calculoPago();
    });  

    $("#param0").on("change",function(event){
        event.stopPropagation();
        calculoPago();
        $("#cantidad").focus();

    });

    function calculoPago(){

        var cant, val, vsm, costo, imp ;

        var cant = parseFloat( $("#cantidad").val() ).toFixed(2);

        switch( arrItem[Tipo].tipo ){
            case 1:
            case 2:
            case 3:
            case 6:
            case 7:
            case 8:
            case 9:
                    val = parseFloat( $("#precio_unitario").val() );
                    costo = val;
                    imp = cant * costo;
                    break;
            case 4:
            case 5:
                    val = parseInt( $("#param0").val(), 0);
                    vsm = parseFloat( localStorage.vsm ).toFixed(2);
                    costo = val * vsm;
                    imp = cant * costo;
                    break;
        }
        
        $("#precio_unitario").val( costo );
        $("#importe").val ( imp );

    }

	function getPago(IdContribucion){
        
        $("#preloaderPrincipal").show();
		$.post(obj.getValue(0) + "data/", {o:27, t:10008, c:IdContribucion, p:10, from:0, cantidad:0,s:''},
			function(json){
				if (json.length>0){

					idcontribucion = json[0].idcontribucion;

					$("#concepto").val(json[0].idingreso);
                    $("#cantidad").val(json[0].cantidad);
                    $("#precio_unitario").val(json[0].precio_unitario);
                    $("#importe").val(json[0].importe);
                    $("#contribuyente").val(json[0].contribuyente);
                    $("#observaciones").val(json[0].observaciones);

                    $("#title").html("Reg: " + json[0].idcontribucion);

					$("#preloaderPrincipal").hide();

                    $("#clave_nivel").focus();

				}
		},'json');
        
	}


    function validForm(){

/*
        if ($("#concepto").val() == "-1"){
            alert("Faltan el Concepto");
            $("#concepto").focus();
            return false;
        }
*/

        if ( parseInt($("#concepto").val(),0) <= 0 ){
            alert("Faltan el Concepto");
            $("#concepto").focus();
            return false;
        }

        if ($("#param0").val() == "-1"){
            alert("Faltan el VSM");
            $("#param0").focus();
            return false;
        }

        if ($("#cantidad").val().length <= 0){
            alert("Faltan la Cantidad");
            $("#cantidad").focus();
            return false;
        }

        if ($("#contribuyente").val().length <= 0){
            alert("Faltan la Contribuyente");
            $("#contribuyente").focus();
            return false;
        }

        return true;

    }

	// close Form
	$(".closeFormUpload").on("click",function(event){
		event.preventDefault();
		$("#preloaderPrincipal").hide();
		$("#contentProfile").hide(function(){
			$("#contentProfile").html("");
			$("#contentMain").show();
		});
		resizeScreen();
		return false;
	});



    getConceptosUser();

    $("#search").focus();

	var stream = io.connect(obj.getValue(4));


});

</script>