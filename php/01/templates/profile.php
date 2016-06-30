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

$de       = $_POST['user'];
$ret = $f->getQuerys(0,$de);
$xpl = explode(".",$ret[0]->foto);
if ($ret[0]->foto!=""){
	$foto = "/upload/". $xpl[0].'-big.'.$xpl[1];
}else{
	$foto = "/images/emoticons/user-big.png";
}
$nombreCompleto = $ret[0]->nombres." ".$ret[0]->apellidos;
?>

<div style="display: block;">
	<div class="page-header position-relative">
		<h1>
			Profile
			<small>
				<i class="icon-double-angle-right"></i>
				<?php echo $nombreCompleto; ?>
			</small>
		</h1>
	</div>

	<div id="user-profile-1" class="user-profile row-fluid">

		<div class="span3 center">
			<div>
				<span class="profile-picture">
					<img id="avatar" class="editable editable-click editable-empty" 
							alt="<?php echo $ret[0]->apellidos.", ".$ret[0]->nombres; ?>" 
							src="<?php echo $foto; ?>">
				</span>

				<div class="space-4"></div>

				<div class="width-80 label label-info label-large arrowed-in arrowed-in-right" id="lblNameProf"><?php echo $nombreCompleto; ?></div>
			</div>

			<div class="space-6"></div>

			<div class="profile-contact-info">
				<!--
			-->

				<div class="space-6"></div>
				<!--
			-->
			</div>

			<div class="hr hr12 dotted"></div>
<!--
-->
			<div class="hr hr16 dotted"></div>


		</div>

		<div class="span9">


		<form class="form-horizontal" id="frmProfile">

			<div class="control-group">
				<label class="control-label" for="username">Username</label>
				<div class="controls">
					<span class="input-icon">
					<input class="input-lg altoMoz" readonly id="username" name="username" value="<?php echo $ret[0]->username; ?>" type="text">
					</span>
				</div>
				<input type="hidden" id="username2" name="username2" value="<?php echo $de; ?>">
			</div>

			<div class="control-group">
				<label class="control-label" for="apellidos">Apellidos</label>

				<div class="controls">
					<span class="input-icon">
					<input class="input-lg altoMoz" id="apellidos" name="apellidos" placeholder="Apellidos" type="text" value="<?php echo $ret[0]->apellidos; ?>">
					</span>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="nombres">Nombres</label>

				<div class="controls">
					<span class="input-icon">
					<input class="input-lg altoMoz" id="nombres" name="nombres" placeholder="Nombres" type="text" value="<?php echo $ret[0]->nombres; ?>">
					</span>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="correoelectronico">E-Mail 1</label>

				<div class="controls">
					<span class="input-icon">
					<input class="input-lg altoMoz" id="correoelectronico" name="correoelectronico" type="email" value="<?php echo $ret[0]->correoelectronico; ?>" required>
					</span>

				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="teloficina">Teléfono</label>

				<div class="controls">
					<span class="input-icon">
					<input class="input-lg altoMoz" id="teloficina" name="teloficina" type="text" value="<?php echo $ret[0]->teloficina; ?>" >
					</span>

				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="telpersonal">Celular</label>

				<div class="controls">
					<span class="input-icon">
					<input class="input-lg altoMoz" id="telpersonal" name="telpersonal" type="text" value="<?php echo $ret[0]->telpersonal; ?>" >
					</span>

				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="registrosporpagina">Reg / Pág</label>

				<div class="controls">
					<span class="input-icon">
					<input class="input-lg altoMoz" id="registrosporpagina" name="registrosporpagina" type="text" value="<?php echo $ret[0]->registrosporpagina; ?>" >
					</span>

				</div>
			</div>

<!-- 			<div class="form-group ">
		    	<label for="cmbEval" class="control-label">Evaluación</label>
				<div class="controls">
					<span class="input-icon">
						<select class="form-control input-lg"  name="cmbEval" id="cmbEval" size="1">
	                        <option value='1' selected>Evaluación 1</option>
	                        <option value='2' >Evaluación 2</option>
						</select>
					</span>

				</div>
		    </div>

 -->			
 				<div class="control-group">
				<label class="control-label hide" for="param1">Parámetro 1</label>

				<div class="controls">
					<span class="input-icon">
					<input class="input-lg altoMoz" id="param1" name="param1" type="hidden" value="<?php echo $ret[0]->param1; ?>" readonly>
					</span>

				</div>
			</div>


			<div class="control-group " id="pnlToken">
				<label class="control-label" for="token">Token</label>

				<div class="controls">
					<span class="input-icon">
					<input class="input-lg altoMoz" id="token" name="token" type="text" value="<?php echo $ret[0]->token; ?>">
					</span>
					
						<!--
						<i class="icon-ok bigger-110"></i>
						Validar!
						-->
						<button class="btn btn-link" id="btnValidToken">Validar!</button>
					


				</div>
			</div>




			<input id="token_source" name="token_source" type="hidden" value="<?php echo $ret[0]->token_source; ?>">
			<input id="token_validated" name="token_validated" type="hidden" value="<?php echo $ret[0]->token_validated; ?>">

			<div class="form-actions">


		        <i id="iconSaveCommentResp" class="icon-spinner icon-spin orange bigger-125"></i>

				<button class="btn btn-info" type="submit">
					<i class="icon-ok bigger-110"></i>
					Guardar
				</button>

				&nbsp; &nbsp; &nbsp;
				<button class="btn" type="reset">
					<i class="icon-close bigger-110"></i>
					Cerrar
				</button>
			</div>

			<div class="form-group ">
		    	<label for="idusernivelacceso" id="lblusernivelacceso" class="control-label">Categoría</label>
				<div class="controls">
					<span class="input-icon">
						<select class="form-control input-lg"  name="idusernivelacceso" id="idusernivelacceso" size="1">
						</select>
					</span>

				</div>
		    </div>


		</form>

		</div>
	</div>
</div>




<!--PAGE CONTENT ENDS-->

<script typy="text/javascript">        

jQuery(function($) {


	$("#iconSaveCommentResp").hide();
	
	$("#idusernivelacceso").hide();
	$("#lblusernivelacceso").hide();


	if (!localStorage.eval0){
		localStorage.eval0 = 1;
	}else{
		$("#cmbEval").val(localStorage.eval0);
	}

	$("#cmbEval").on("change",function(event){
		localStorage.eval0 = $("#cmbEval").val();
	});


	var tv = parseInt($("#token_validated").val(),0);

	if ( tv == "1" ){
		$("#btnValidToken").hide();
		$("#token").attr('readonly', 'readonly');
		//$("#pnlToken").hide();
	}	

	var idusernivelacceso = <?php echo $ret[0]->idusernivelacceso; ?>;



    $("#frmProfile").unbind("submit");
	$("#frmProfile").on("submit",function(event){
		event.preventDefault();

		$("#iconSaveCommentResp").show();

	    var queryString = $(this).serialize();	

		var data = new FormData();

		data.append('data', queryString);	    

		if (validForm()){
	    	//alert(queryString);
			$.ajax({
			    url:obj.getValue(0)+"fu-tl/",
			    data: data,
			    cache: false,
			    contentType: false,
			    processData: false,
			    dataType: 'json',
			    type: 'POST',
			    success: function(json){
			    	if (json.status!="OK"){
			           
                        var nc = localStorage.nc.split("@");
                        $.post(obj.getValue(0) + "data/", {o:0, t:0, c:nc[0], p:10},
                        function(json) {
                        	var strx = json[0].foto.split(".");
                        	var imgPath = obj.getValue(0) + "upload/"+strx[0]+"-big."+strx[1];
                        	$("#apellidos").val|(json[0].apellidos);
                        	$("#nombres").val(json[0].nombres);
                        	$("#lblNameProf").html(json[0].apellidos+" "+json[0].nombres);
                    	}, "json");
			           alert(json.message);

			       	} else{
			           		alert(json.message)
			       	}
					$("#iconSaveCommentResp").hide();

			    }
			});

		}

	});


	function validForm(){

		if($("#apellidos").val().length <= 0){
			alert("Faltan los Apellidos");
			$("#apellidos").focus();
			return false;
		}

		if($("#nombres").val().length <= 0){
			alert("Falta los nombres");
			$("#nombres").focus();
			return false;
		}

		if($("#correoelectronico").val().length <= 0){
			alert("Falta el Corre Electrónico");
			$("#correoelectronico").focus();
			return false;
		}

		return true;

	}

	function getCategorias(){
	    var nc = "u="+localStorage.nc;
	    $.post(obj.getValue(0)+"data/", { o:0, t:2, p:0,c:nc,from:0,cantidad:0, s:"" },
	        function(json){
	            var nc;
	           $.each(json, function(i, item) {
	                nc = item.label.split("@");
	                $("#idusernivelacceso").append('<option value="'+item.data+'"> '+item.label+'</option>');
	            });    

	            $("#idusernivelacceso").val(idusernivelacceso);       
	            $("#idusernivelacceso").show();
	            $("#lblusernivelacceso").show();
				$("#preloaderSingle").hide();
	        }, "json"
	    );  
	}


	//if (idusernivelacceso==4){
    var uParam1 = obj.getkeyUP(idusernivelacceso,1);
    if (uParam1 !== -1) {
		getCategorias();
	}

    $(".addMyFriends").unbind("click");

	$(".addMyFriends").on("click",function(event){
		event.preventDefault();
		
		$("#iconSaveCommentResp").show();

        var nc = localStorage.nc.split("@");

		var IdArr = event.currentTarget.id.split("-");

		if (nc[0]==IdArr[1]){
			alert("No se puede seguir al mismo Usuario");
			$("#iconSaveCommentResp").hide();
			return false;
		}
		
        $.post(obj.getValue(0) + "data/", {o:0, t:300, c:"de0="+nc[0]+"&de1="+IdArr[1], p:8},
        function(json) {
        	if (json[0].msg=="OK" ){        		
        		alert("Este usuario se ha agregado a tu lista de Amigos");
        	}else{
        		alert(json[0].msg);
        	}
			$("#iconSaveCommentResp").hide();
	       
    	}, "json");

	});


	$("#frmProfile").on("reset",function(event){
		window.location.href = obj.getValue(0)+"dashboard/";
	});

	$("#btnValidToken").on("click",function(event){

		event.preventDefault();
		var queryString = $("#frmProfile").serialize();
		$.post(obj.getValue(0) + "sendToken/", {
				data: queryString
			},
			function(json) {
				if (json[0].msg == "OK") {
					alert("Se ha enviado el TOKEN que debe copiar y pegar en este campo.\n\n Revise su correo incluyendo su bandeja de correo no deseado y siga las instrucciones.");
					window.location.href = obj.getValue(0);
					$("#preloaderSingle").hide();

				} else {
					alert(json[0].msg);
				}

		}, "json");


	});

	$("#preloaderPrincipal").hide();

});

</script>