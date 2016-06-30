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
$iduser  = $_POST['iduser'];

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
					USUARIO
				</a>
			</li>
		</ul>

		<div class="tab-content">

			<div id="general" class="tab-pane active">
			
				<table>
					<tr>
						<td>
					    	<label for="username" class="tbl80W">Username</label>
						</td>
						<td>
							<input class="altoMoz tbl80W" name="username" id="username" type="text" readonly>	
						</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>
					    	<label for="password1"  class=" tbl80W">Password</label>
						</td>
						<td>
							<input class="altoMoz tbl80W" name="password1" id="password1" type="password">	
						</td>
						<td>
					    	<label for="password2"  class="marginLeft2em tbl80W">Re-Password</label>
						</td>
						<td>
							<input class="altoMoz tbl80W" name="password2" id="password2" type="password">	
						</td>

					</tr>


				</table>
			</div>		

		</div>

	    <input type="hidden" name="iduser" id="iduser" value="<?php echo $iduser; ?>">
	    <input type="hidden" name="iduser2" id="iduser2" value="0">
	    <input type="hidden" name="idunidadadmitiva2" id="idunidadadmitiva2" value="0">
	    <input type="hidden" name="user" id="user" value="<?php echo $user; ?>">
	    <div class="form-group w96" style='margin-right: 3em; margin-top: 1em;'>
	    	<button type="button" class="btn btn-default pull-right" data-dismiss="modal" id="closeFormUpload"><i class="icon-signout"></i>Cerrar</button>
	    	<span class="muted"></span>
	    	<button type="submit" class="btn btn-primary pull-right" style='margin-right: 4em;'><i class="icon-save"></i>Publicar</button>
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

	var iduser = <?php echo $iduser ?>;
	var idunidadadmitiva = 0;
	var idusernivelacceso = 0;
	var idsubarea = 0;
	var inicio = true;

	$("#idunidadadmitiva2").val(localStorage.IDUA);
	$("#iduser2").val(localStorage.IdUser);

	function getData(IdUser){
		//alert(IdUser);
		$.post(obj.getValue(0) + "data/", {o:0, t:-2, c:IdUser, p:10, from:0, cantidad:0,s:''},
			function(json){
				if (json.length>0){
					$("#username").val(json[0].username);
					$("#password1").val("");
					$("#password2").val("");
					$("#password1").focus();
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
            $.post(obj.getValue(0) + "data/", {o:0, t:3, c:queryString, p:2, from:0, cantidad:0, s:''},
            function(json) {
            		if (json[0].msg=="OK"){
            			alert("Datos guardados con éxito.");
						stream.emit("cliente", {mensaje: "PLATSOURCE-USUARIO-"+3});
        			}else{
        				alert(json[0].msg);	
        			}
					$("#preloaderPrincipal").hide();
					$("#contentProfile").hide(function(){
						$("#contentProfile").html("");
						$("#contentMain").show();
					});
					resizeScreen();
					return false;
        	}, "json");
		}else{
			$("#preloaderPrincipal").hide();
		}

	});


	$("#closeFormUpload").on("click",function(event){
		event.preventDefault();
		$("#preloaderPrincipal").hide();
		$("#contentProfile").hide(function(){
			$("#contentProfile").html("");
			$("#contentMain").show();
		});
		resizeScreen();
		return false;
	});

	function validForm(){


		if( $("#password1").val() <= ""){
			alert("Falta el Password");
			$("#password1").focus();
			return false;
		}

		if( $("#password2").val() <= ""){
			alert("Falta el Re-password");
			$("#password2").focus();
			return false;
		}

		if( $("#password1").val() != $("#password2").val()){
			alert("El Password no coincide");
			$("#password2").focus();
			return false;
		}

		return true;

	}

	getData(iduser);

});

</script>