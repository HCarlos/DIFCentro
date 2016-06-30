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
$idingreso  = $_POST['idingreso'];

?>
	<h3 class="header smaller lighter blue" id="title"></h3>
	<form id="frmData" role="form">

			

				<table>
						<tr>
							<td><label for="idsubclase" >Sub Clase</label></td>
							<td colspan="4"><select class="form-control altoMoz marginLeft1em"  name="idsubclase" id="idsubclase" size="1"></select></td>
						</tr>
						<tr>
							<td><label for="ingreso">Ingreso</label></td>
							<td colspan="4"><input type="text" class="form-control altoMoz marginLeft1em" id="ingreso" name="ingreso" ></td>
						</tr>
						<tr>
							<td><label for="tipo" >Tipo</label></td>
							<td>
								<select class="form-control altoMoz marginLeft1em"  name="tipo" id="tipo" size="1">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="9">9</option>
								</select>							
							</td>
							<td><div class="marginLeft2em"></div></td>
							<td><label for="porcentaje_ingreso">Porcentaje</label></td>
							<td><input type="text" class="form-control altoMoz" id="porcentaje_ingreso" name="porcentaje_ingreso"  pattern="[-+]?[0-9]*[.,]?[0-9]+" ></td>
						</tr>
						<tr>
							<td><label for="dsm_min" >DSM Min</label></td>
							<td><input type="text" class="form-control altoMoz marginLeft1em" id="dsm_min" name="dsm_min"  pattern="[-+]?[0-9]*[.,]?[0-9]+" ></td>
							<td></td>
							<td><label for="dsm_max" >DSM Max</label></td>
							<td><input type="text" class="form-control altoMoz" id="dsm_max" name="dsm_max"  pattern="[-+]?[0-9]*[.,]?[0-9]+" ></td>
						</tr>
						<tr>
							<td><label for="costo_min" >Costo Min</label></td>
							<td><input type="text" class="form-control altoMoz marginLeft1em" id="costo_min" name="costo_min"  pattern="[-+]?[0-9]*[.,]?[0-9]+" ></td>
							<td></td>
							<td><label for="costo_max" >Costo Max</label></td>
							<td><input type="text" class="form-control altoMoz" id="costo_max" name="costo_max"  pattern="[-+]?[0-9]*[.,]?[0-9]+" ></td>
						</tr>
						<tr>
							<td><label for="clave" >Clave</label></td>
							<td><input type="text" class="form-control altoMoz marginLeft1em" id="clave" name="clave" ></td>
							<td></td>
							<td><label for="ano">Año</label></td>
							<td>
								<select class="form-control altoMoz"  name="ano" id="ano" size="1">
									<option value="2015">2015</option>
									<option value="2016">2016</option>
								</select>							
							</td>
						</tr>
						<tr>
							<td><label for="status_ingreso" >Status</label></td>
							<td>
								<select class="form-control altoMoz marginLeft1em"  name="status_ingreso" id="status_ingreso" size="1">
									<option value="0">Inactivo</option>
									<option value="1" selected >Activo</oaltoMozption>
								</select>
							</td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
				</table>


	    <input type="hidden" name="idingreso" id="idingreso" value="<?php echo $idingreso; ?>">
	    <input type="hidden" name="user" id="user" value="<?php echo $user; ?>">
	    <div class="form-group w96" style='margin-right: 3em; margin-top: 1em;'>
	    	<button type="button" class="btn btn-default pull-right" data-dismiss="modal" id="closeProp"><i class="icon-signout"></i>Cerrar</button>
	    	<span class="muted"></span>
	    	<button type="submit" class="btn btn-primary pull-right" style='margin-right: 4em;'><i class="icon-save"></i>Guardar</button>
		</div>

	</form>
<div style="position: relative; display: inline-block; width: 100%; height: 2em;" ></div>
<!--PAGE CONTENT ENDS-->

<script typy="text/javascript">        

jQuery(function($) {

	var stream = io.connect(obj.getValue(4));


	$("#preloaderPrincipal").hide();


	var idingreso = <?php echo $idingreso ?>;


	function getIngreso(IdIngreso){
		$.post(obj.getValue(0) + "data/", {o:1004, t:2009, c:IdIngreso, p:10, from:0, cantidad:0,s:''},
			function(json){
				if (json.length>0){
					$("#ingreso").val(json[0].ingreso);
					$("#tipo").val(json[0].tipo);
					$("#porcentaje_ingreso").val(json[0].porcentaje_ingreso);
					$("#dsm_min").val(json[0].dsm_min);
					$("#dsm_max").val(json[0].dsm_max);
					$("#costo_min").val(json[0].costo_min);
					$("#costo_max").val(json[0].costo_max);
					$("#dsm_min").val(json[0].dsm_min);
					$("#idsubclase").val(json[0].idsubclase);
					$("#clave").val(json[0].clave);
					$("#ano").val(json[0].ano);
					$("#status_ingreso").val(json[0].status_ingreso);
					$("#subclase").focus();
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

		var IdIngreso = (idingreso==0?0:1)
        $.post(obj.getValue(0) + "data/", {o:1004, t:IdIngreso, c:queryString, p:2, from:0, cantidad:0, s:''},
        function(json) {
        		if (json[0].msg=="OK"){

        			alert("Datos guardados con éxito.");
					stream.emit("cliente", {mensaje: "PLATSOURCE-INGRESOS-PROP-"+IdIngreso});
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

	});

	$("#closeProp").on("click",function(event){
		event.preventDefault();
		$("#preloaderPrincipal").hide();
		$("#contentProfile").hide(function(){
			$("#contentProfile").html("");
			$("#contentMain").show();
		});
		resizeScreen();
		return false;
	});



	function getSubClase(){
	    var nc = "u="+localStorage.nc;
	    $("#idsubclase").html('');
	    $.post(obj.getValue(0)+"data/", { o:1, t:303, p:0,c:nc,from:0,cantidad:0, s:"" },
	        function(json){
	           $.each(json, function(i, item) {
	                //if (item.label.trim() != ""){
		                $("#idsubclase").append('<option value="'+item.data+'"> '+item.data+'.- '+item.label+'</option>');
	                //}
	            });

				if (idingreso<=0){ // Nuevo Registro
					$("#title").html("Nuevo registro");
				}else{ // Editar Registro
					$("#title").html("Editando el registro: "+idingreso);
					getIngreso(idingreso);
				}

	        }, "json"
	    );  
	}

	getSubClase();

	$('#clave').mask("9999-999999");



});

</script>