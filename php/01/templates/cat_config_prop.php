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
$idconfig  = $_POST['idconfig'];

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
					Parámetros
				</a>
			</li>

		</ul>

		<div class="tab-content">

			<div id="general" class="tab-pane active">
			
				<div class="form-group ">
			    	<label for="llave" class="col-lg-2 control-label">Llave</label>
			    	<div class="col-lg-10 ">
				    	<input type="text" class="form-control altoMoz" maxlength="20" id="llave" name="llave" required autofocus >
		      		</div>
			    </div>

				<div class="form-group ">
			    	<label for="valor" class="col-lg-2 control-label">Valor</label>
			    	<div class="col-lg-10">
				    	<input type="text" class="form-control altoMoz" id="valor" name="valor" required >
		      		</div>
			    </div>

				<div class="form-group ">
			    	<label for="observaciones" class="col-lg-2 control-label">Obs.:</label>
			    	<div class="col-lg-10">
				    	<input type="text" class="form-control altoMoz" id="observaciones" name="observaciones" >
		      		</div>
			    </div>

<!--
				<div class="form-group ">
			    	<label for="status_estado" class="col-lg-2 control-label">Status</label>
			    	<div class="col-lg-10">
						<select class="form-control input-lg"  name="status_estado" id="status_estado" size="1">
							<option value="0">Inactivo</option>
							<option value="1" selected >Activo</option>
						</select>
		      		</div>
			    </div>
-->
			</div>

		</div>

	    <input type="hidden" name="idconfig" id="idconfig" value="<?php echo $idconfig; ?>">
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


	$("#preloaderPrincipal").hide();

	$("#llave").focus();

	var idconfig = <?php echo $idconfig ?>;

	if (idconfig<=0){ // Nuevo Registro
		$("#title").html("Nuevo registro");
	}else{ // Editar Registro
		$("#title").html("Editando el registro: "+idconfig);
		getConfig(idconfig);
	}

	function getConfig(IdConfig){
		var nc = "u="+localStorage.nc+"&idconfig="+IdConfig;
		$.post(obj.getValue(0) + "data/", {o:-1, t:-6, c:nc, p:10, from:0, cantidad:0,s:''},
			function(json){
				if (json.length>0){
					$("#llave").val(json[0].llave);
					$("#valor").val(json[0].valor);
					$("#observaciones").val(json[0].observaciones);
					//$("#status_estado").val(json[0].status_estado);
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
			var IdConfig = (idconfig==0?0:1)
            $.post(obj.getValue(0) + "data/", {o:-1, t:IdConfig, c:queryString, p:2, from:0, cantidad:0, s:''},
            function(json) {
            		if (json[0].msg=="OK"){
            			alert("Datos guardados con éxito.");
						stream.emit("cliente", {mensaje: "PLATSOURCE-CONFIG-PROP-"+IdConfig});
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

		if($("#llave").val().length <= 0){
			alert("Faltan la Llave");
			$("#llave").focus();
			return false;
		}

		if($("#valor").val().length <= 0){
			alert("Faltan el Valor");
			$("#valor").focus();
			return false;
		}

		return true;

	}


	var stream = io.connect(obj.getValue(4));



});

</script>