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
$idsubclase  = $_POST['idsubclase'];

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
					Sub Sub Clase
				</a>
			</li>

		</ul>

		<div class="tab-content">

			<div id="general" class="tab-pane active">

				<div class="form-group ">
			    	<label for="cuenta" class="col-lg-3 control-label">Cuenta</label>
			    	<div class="col-lg-9">
				    	<input type="text" class="form-control altoMoz" id="cuenta" name="cuenta" >
		      		</div>
			    </div>
			
				<div class="form-group ">
			    	<label for="subclase" class="col-lg-3 control-label">Sub Clase</label>
			    	<div class="col-lg-9">
				    	<input type="text" class="form-control altoMoz" id="subclase" name="subclase" >
		      		</div>
			    </div>

				<div class="form-group ">
			    	<label for="idclase" class="col-lg-3 control-label">Clase</label>
			    	<div class="col-lg-9">
						<select class="form-control altoMoz"  name="idclase" id="idclase" size="1">
						</select>
		      		</div>
			    </div>


				<div class="form-group ">
			    	<label for="status_subclase" class="col-lg-3 control-label">Status</label>
			    	<div class="col-lg-9">
						<select class="form-control input-lg"  name="status_subclase" id="status_subclase" size="1">
							<option value="0">Inactivo</option>
							<option value="1" selected >Activo</option>
						</select>
		      		</div>
			    </div>

			</div>

		</div>

	    <input type="hidden" name="idsubclase" id="idsubclase" value="<?php echo $idsubclase; ?>">
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


	var idsubclase = <?php echo $idsubclase ?>;


	function getSubClase(IdSubClase){
		$.post(obj.getValue(0) + "data/", {o:1001, t:2007, c:IdSubClase, p:10, from:0, cantidad:0,s:''},
			function(json){
				if (json.length>0){
					$("#cuenta").val(json[0].cuenta);
					$("#subclase").val(json[0].subclase);
					$("#idclase").val(json[0].idclase);
					$("#status_subclase").val(json[0].status_subclase);
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

		var IdSubClase = (idsubclase==0?0:1)
        $.post(obj.getValue(0) + "data/", {o:1003, t:IdSubClase, c:queryString, p:2, from:0, cantidad:0, s:''},
        function(json) {
        		if (json[0].msg=="OK"){
        			alert("Datos guardados con éxito.");
					stream.emit("cliente", {mensaje: "PLATSOURCE-SUBCLASES-PROP-"+IdSubClase});
					$("#preloaderPrincipal").hide();
					$("#divUploadImage").modal('hide');
    			}else{
					$("#preloaderPrincipal").hide();
    				alert(json[0].msg);	
    			}
    	}, "json");

	});

	$("#closeProp").on("click",function(event){
		$("#preloaderPrincipal").hide();
		$("#divUploadImage").modal('hide');
	});

	function validForm(){

		if($("#subclase").val().length <= 0){
			alert("Faltan la Clase");
			$("#subclase").focus();
			return false;
		}

		return true;

	}


	function getClases(){
	    var nc = "u="+localStorage.nc;
	    $("#idclase").html('');
	    $.post(obj.getValue(0)+"data/", { o:1, t:302, p:0,c:nc,from:0,cantidad:0, s:"" },
	        function(json){
	           $.each(json, function(i, item) {
	                $("#idclase").append('<option value="'+item.data+'"> '+item.label+'</option>');
	            });

				if (idsubclase<=0){ // Nuevo Registro
					$("#title").html("Nuevo registro");
				}else{ // Editar Registro
					$("#title").html("Editando el registro: "+idsubclase);
					getSubClase(idsubclase);
				}

	        }, "json"
	    );  
	}

	getClases();

	$('#cuenta').mask("9999-999999");



});

</script>