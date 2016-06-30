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
$idbeneficio  = $_POST['idbeneficio'];

?>
<div class="row-fluid">
<div class="span12" style="padding-left: 1em; padding-right: 1em;">
<div class="tabbable">

	<h3 class="header smaller lighter blue" id="title"></h3>
	<form id="frmData" role="form">

		<ul class="nav nav-tabs" id="myTab">

			<li class="active">
				<a data-toggle="tab" href="#general">
					<i class="red icon-home bigger-110"></i>
					Bloque
				</a>
			</li>

		</ul>

		<div class="tab-content">

			<div id="general" class="tab-pane active">
			
				<div class="form-group ">
			    	<label for="beneficio" class="col-lg-3 control-label">Bloque</label>
			    	<div class="col-lg-9">
				    	<input type="text" class="form-control altoMoz" id="beneficio" name="beneficio" required >
		      		</div>
			    </div>

				<div class="form-group ">
			    	<label for="status_beneficio" class="col-lg-3 control-label">Status</label>
			    	<div class="col-lg-9">
						<select class="form-control input-lg"  name="status_beneficio" id="status_beneficio" size="1">
							<option value="0">Inactivo</option>
							<option value="1" selected >Activo</option>
						</select>
		      		</div>
			    </div>

			</div>

		</div>

	    <input type="hidden" name="idbeneficio" id="idbeneficio" value="<?php echo $idbeneficio; ?>">
	    <input type="hidden" name="user" id="user" value="<?php echo $user; ?>">
	    <div class="form-group w96" style='margin-right: 3em; margin-top: 1em;'>
	    	<button type="button" class="btn btn-default pull-right" data-dismiss="modal" id="closeFormUpload"><i class="icon-signout"></i>Cerrar</button>
	    	<span class="muted"></span>
	    	<button type="submit" class="btn btn-primary pull-right" style='margin-right: 4em;'><i class="icon-save"></i>Guardar</button>
		</div>

	</form>

</div>




</div>
</div>
<!--PAGE CONTENT ENDS-->

<script typy="text/javascript">        

jQuery(function($) {

	var stream = io.connect(obj.getValue(4));


	$("#preloaderPrincipal").hide();

	$("#beneficio").focus();

	var idbeneficio = <?php echo $idbeneficio ?>;

	if (idbeneficio<=0){ // Nuevo Registro
		$("#title").html("Nuevo registro");
	}else{ // Editar Registro
		$("#title").html("Editando el registro: "+idbeneficio);
		getBloque(idbeneficio);
	}

	function getBloque(IdBloque){
		$.post(obj.getValue(0) + "data/", {o:1000, t:2001, c:IdBloque, p:10, from:0, cantidad:0,s:''},
			function(json){
				if (json.length>0){
					$("#beneficio").val(json[0].beneficio);
					$("#status_beneficio").val(json[0].status_beneficio);
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

		if (validForm()){
			var IdBloque = (idbeneficio==0?0:1)
            $.post(obj.getValue(0) + "data/", {o:1000, t:IdBloque, c:queryString, p:2, from:0, cantidad:0, s:''},
            function(json) {
            		if (json[0].msg=="OK"){
            			alert("Datos guardados con éxito.");
						stream.emit("cliente", {mensaje: "SIBDMUN-BENEFICIOS-PROP-"+IdBloque});
						$("#preloaderPrincipal").hide();
						$("#divUploadImage").modal('hide');
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

		if($("#beneficio").val().length <= 0){
			alert("Faltan el Bloque");
			$("#beneficio").focus();
			return false;
		}

		return true;

	}




});

</script>