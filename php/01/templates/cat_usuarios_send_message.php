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

?>
<div class="row-fluid">
<div class="span12" style="padding-left: 1em; padding-right: 1em;">
<div class="tabbable">

	<h3 class="header smaller lighter blue" id="title"></h3>
	<form id="frmUserSendMessage" role="form">

		<ul class="nav nav-tabs" id="myTab">

			<li class="active">
				<a data-toggle="tab" href="#general">
					<i class="red icon-comments bigger-110"></i>
					Comentario
				</a>
			</li>

		</ul>

		<div class="tab-content">

			<div id="general" class="tab-pane active">
			
				<div class="form-group ">
			    	<label for="mensaje" class="col-lg-2 control-label">Comentario</label>
			    	<div class="col-lg-10">
<!-- <input type="text" class="form-control altoMoz" id="mensaje" name="mensaje" required >
 -->
 					<textarea rows="4" cols="50" class="" id="mensaje" name="mensaje" required></textarea>		      		
		      		</div>
			    </div>

			</div>

		</div>

	    <input type="hidden" name="idusuario" id="idusuario" value="<?php echo $idusuario; ?>">
	    <input type="hidden" name="user" id="user" value="<?php echo $user; ?>">
	    <div class="form-group w96" style='margin-right: 3em; margin-top: 1em;'>
	    	<button type="button" class="btn btn-default pull-right" data-dismiss="modal" id="closeFormUpload"><i class="icon-signout"></i>Cerrar</button>
	    	<span class="muted"></span>
	    	<button type="submit" class="btn btn-primary pull-right" style='margin-right: 4em;'><i class="icon-save"></i>Enviar</button>
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

	$("#title").html("Enviar mensaje");


    $("#frmUserSendMessage").unbind("submit");
	$("#frmUserSendMessage").on("submit",function(event){
		event.preventDefault();

		$("#preloaderPrincipal").show();

	    var queryString = $(this).serialize();	
	    var srcimg = $("#imgFoto").attr('src');
	    var nameuser = $("#nameuser").html();

		var msg = "NT|PlatSource|"+$("#mensaje").val()+"|"+srcimg+"|9000";

	    stream.emit("cliente", {mensaje: msg});

		$("#preloaderPrincipal").hide();

	});

	$("#closeProp").on("click",function(event){
		$("#preloaderPrincipal").hide();
		$("#divUploadImage").modal('hide');
	});




});

</script>