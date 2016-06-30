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
$idlocalidad  = $_POST['idlocalidad'];

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
					Localidad
				</a>
			</li>

		</ul>

		<div class="tab-content">

			<div id="general" class="tab-pane active">
			
				<div class="form-group ">
			    	<label for="status_localidad" class="col-lg-3 control-label">Delegación</label>
			    	<div class="col-lg-9">
	                    <select name="iddelegacion" id="iddelegacion" size="1" style="width:100% !important;" > 
	                    </select>
		      		</div>
			    </div>

				<div class="form-group ">
			    	<label for="status_localidad" class="col-lg-3 control-label">Categoría</label>
			    	<div class="col-lg-9">
	                    <select name="idcategorialocalidad" id="idcategorialocalidad" size="1" style="width:100% !important;" > 
	                    </select>
		      		</div>
			    </div>

				<div class="form-group ">
			    	<label for="localidad" class="col-lg-3 control-label">Localidad</label>
			    	<div class="col-lg-9">
				    	<input type="text" class="form-control altoMoz" id="localidad" name="localidad" required >
		      		</div>
			    </div>

			</div>

		</div>

	    <input type="hidden" name="idlocalidad" id="idlocalidad" value="<?php echo $idlocalidad; ?>">
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

	$("#localidad").focus();

	var idlocalidad = <?php echo $idlocalidad ?>;

	function getLocalidad(IdLocalidad){
		$.post(obj.getValue(0) + "data/", {o:1007, t:2019, c:IdLocalidad, p:10, from:0, cantidad:0,s:''},
			function(json){
				if (json.length>0){
					$("#idlocalidad").val(json[0].idlocalidad);
					$("#localidad").val(json[0].localidad);
					$("#iddelegacion").val(json[0].iddelegacion);
					$("#idcategorialocalidad").val(json[0].idcategorialocalidad);
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
			var IdLocalidad = (idlocalidad==0?0:1)
            $.post(obj.getValue(0) + "data/", {o:1007, t:IdLocalidad, c:queryString, p:2, from:0, cantidad:0, s:''},
            function(json) {
            		if (json[0].msg=="OK"){
            			alert("Datos guardados con éxito.");
						stream.emit("cliente", {mensaje: "SIBDMUN-LOCALIDADES-PROP-"+IdLocalidad});
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

		if($("#localidad").val().length <= 0){
			alert("Faltan el Localidad");
			$("#localidad").focus();
			return false;
		}

		return true;

	}


	function getDelegaciones(){
	    var nc = "u="+localStorage.nc;
	    $("#iddelegacion").empty();
	    $.post(obj.getValue(0)+"data/", { o:1, t:307, p:0,c:nc,from:0,cantidad:0, s:"" },
	        function(json){
	           $.each(json, function(i, item) {
	                $("#iddelegacion").append('<option value="'+item.data+'"> '+item.label+'</option>');
	            });

	           getCatLoc( $("#grupo1") );   
	            
	        }, "json"
	    );  
	}

	function getCatLoc(){
	    var nc = "u="+localStorage.nc;
	    $("#idcategorialocalidad").empty();
	    $.post(obj.getValue(0)+"data/", { o:1, t:308, p:0,c:nc,from:0,cantidad:0, s:"" },
	        function(json){
	           $.each(json, function(i, item) {
	                $("#idcategorialocalidad").append('<option value="'+item.data+'"> '+item.label+'</option>');
	            });

				if (idlocalidad<=0){ 
					$("#title").html("Nuevo registro");
				}else{ // Editar Registro
					$("#title").html("Editando el registro: "+idlocalidad);
					getLocalidad(idlocalidad);
				}
	            
	        }, "json"
	    );  
	}



	getDelegaciones();


});

</script>