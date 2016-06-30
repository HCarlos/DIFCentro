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
					Datos Generales
				</a>
			</li>

			<li >
				<a data-toggle="tab" href="#foto">
					<i class="green icon-picture bigger-110"></i>
					Foto
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
							<input class="altoMoz tbl80W" name="username" id="username" type="text">	
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

					<tr>
						<td>
					    	<label for="apellidos" class="tbl80W">Apellidos: </label>
						</td>
						<td>
							<input class="altoMoz tbl200W" name="apellidos" id="apellidos" type="text">	
						</td>
						<td>
					    	<label for="nombres" class="marginLeft2em tbl80W">Nombres</label>
						</td>
						<td>
							<input class="altoMoz tbl200W" name="nombres" id="nombres" type="text">	
						</td>
					</tr>

				
					<tr>
						<td>
					    	<label for="correoelectronico" class=" tbl80W">Email</label>
						</td>
						<td>
							<input class="altoMoz tbl100W" name="correoelectronico" id="correoelectronico" type="text">	
						</td>
						<td>
					    	<label for="idusernivelacceso"  class="marginLeft2em tbl100W">Nivel de Acceso</label>
						</td>
						<td>
							<select class="tbl250W "  name="idusernivelacceso" id="idusernivelacceso" size="1">
							</select>
						</td>
					</tr>

					<tr>
						<td>
					    	<label for="status_usuario" class=" tbl80W">Status</label>
						</td>
						<td>
							<select class="tbl250W"  name="status_usuario" id="status_usuario" size="1">
								<option value="0">Inactivo</option>
								<option value="1" selected >Activo</option>
							</select>
						</td>
						<td colspan="3">
						</td>
					</tr>

				</table>
			</div>		
			<div id="foto" class="tab-pane">

				<div class="span3 center">
					<div>
						<span class="profile-picture">
							<img id="avatar" class="editable editable-click editable-empty" 
									alt="" 
									src="">
						</span>

						<div class="width-80 label label-info label-large arrowed-in arrowed-in-right">
						</div>
					</div>

				</div>

			</div>

		</div>

	    <input type="hidden" name="iduser" id="iduser" value="<?php echo $iduser; ?>">
	    <input type="hidden" name="iduser2" id="iduser2" value="0">
	    <input type="hidden" name="idunidadadmitiva2" id="idunidadadmitiva2" value="0">
	    <input type="hidden" name="user" id="user" value="<?php echo $user; ?>">
	    <input type="hidden" name="username2" id="username2" value="">
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
					$("#idunidadadmitiva").val(json[0].idunidadadmitiva);					
					$("#username").val(json[0].username);
					$("#username2").val(json[0].username);
					$("#password1").val(json[0].password);
					$("#password2").val(json[0].password);
					$("#apellidos").val( json[0].apellidos );
					$("#nombres").val(json[0].nombres);
					$("#correoelectronico").val(json[0].correoelectronico);
					$("#idusernivelacceso").val(json[0].idusernivelacceso);
					$("#status_usuario").val(json[0].status_usuario);
					$("#password1").prop("disabled","disabled");
					$("#password2").prop("disabled","disabled");
					
                	var strx = json[0].foto.split(".");
                	var imgPath = obj.getValue(0) + "upload/"+strx[0]+"-big."+strx[1];
					$("#avatar").attr("src",imgPath);

					if (inicio){
						idunidadadmitiva = json[0].idunidadadmitiva;
						idsubarea = json[0].idsubarea;
						idusernivelacceso = json[0].idusernivelacceso;
						getSubareas();
					}
					$("#username").focus();
				}
		},'json');
	}

    $("#frmData").unbind("submit");
	$("#frmData").on("submit",function(event){
		event.preventDefault();

		$("#preloaderPrincipal").show();

	    var queryString = $(this).serialize();	

	    // alert(queryString);
	    
		var data = new FormData();

		if (validForm()){
			var IdUser = (iduser==0?0:1)
            $.post(obj.getValue(0) + "data/", {o:0, t:IdUser, c:queryString, p:2, from:0, cantidad:0, s:''},
            function(json) {
            		if (json[0].msg=="OK"){
            			alert("Datos guardados con éxito.");
						stream.emit("cliente", {mensaje: "PLATSOURCE-USUARIO-"+IdUser});
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

	function getUserNivelAcceso(){
	    var nc = "u="+localStorage.nc+"&idemp="+localStorage.IdEmp;
	    $.post(obj.getValue(0)+"data/", { o:0, t:2, p:0,c:nc,from:0,cantidad:0, s:"" },
	        function(json){
	           $.each(json, function(i, item) {
	                $("#idusernivelacceso").append('<option value="'+item.data+'"> '+item.label+'</option>');
	            });

				if (iduser<=0){ // Nuevo Registro
					$("#title").html("Nuevo registro");
					$("#username").val("");
					$("#password1").val("");
					$("#password2").val("");
					$("#username").focus();

				}else{ // Editar Registro
					$("#title").html("Editando el registro: "+iduser);
					getData(iduser);
				}

	        }, "json"
	    );  
	}

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

		if( $("#username").val() <= ""){
			alert("Falta el Username");
			$("#username").focus();
			return false;
		}

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

	getUserNivelAcceso();

});

</script>