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
			Change Password
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

				<div class="width-80 label label-info label-large arrowed-in arrowed-in-right">
					<div class="inline position-relative">
						<a href="#" class="user-title-label dropdown-toggle" data-toggle="dropdown">
							<i class="icon-circle light-green middle"></i>
							&nbsp;
							<span class="white middle bigger-120"><?php echo $nombreCompleto; ?></span>
						</a>



					</div>
				</div>
			</div>

			<div class="space-6"></div>

			<div class="profile-contact-info">


			</div>

			<div class="hr hr12 dotted"></div>

			<div class="clearfix">
			</div>

			<div class="hr hr16 dotted"></div>
		</div>

		<div class="span9">


		<form class="form-horizontal" id="frmProfile">

			<div class="control-group">
				<label class="control-label" for="username">Username</label>
				<div class="controls">
					<span class="input-icon">
					<input class="input-lg" readonly id="username" name="username" value="<?php echo $ret[0]->username; ?>" type="text">
						<i class="icon-user"></i>
					</span>
				</div>
				<input type="hidden" id="username2" name="username2" value="<?php echo $de; ?>">
			</div>

			<div class="control-group">
				<label class="control-label">Password</label>

				<div class="controls">
					<span class="input-icon">
					<input class="input-lg" id="password" name="password" placeholder="Password" type="password">
						<i class="icon-key"></i>
					</span>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label">Re-Password</label>

				<div class="controls">
					<span class="input-icon">
					<input class="input-lg" id="password2" name="password2" placeholder="Re-Password" type="password">
						<i class="icon-key"></i>
					</span>
				</div>
			</div>


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


		</form>


		</div>
	</div>
</div>




<!--PAGE CONTENT ENDS-->

<script typy="text/javascript">        

jQuery(function($) {

	$("#iconSaveCommentResp").hide();
    
    $("#frmProfile").unbind("submit");

	$("#frmProfile").on("submit",function(event){
		event.preventDefault();

	    var queryString = $(this).serialize();	

		if (validForm()){
	    
		    var data = new FormData();

			$("#iconSaveCommentResp").show();

			data.append('data', queryString);

			$.ajax({
			    url:obj.getValue(0)+"fu-wl/",
			    data: data,
			    cache: false,
			    contentType: false,
			    processData: false,
			    dataType: 'json',
			    type: 'POST',
			    success: function(json){
			    	if (json.status!="OK"){
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

		if($("#password").val().length <= 0){
			alert("Falta el Password");
			$("#password").focus();
			return false;
		}

		if($("#password2").val().length <= 0){
			alert("Falta el Re-Password");
			$("#password2").focus();
			return false;
		}

		if($("#password").val() != $("#password2").val()){
			alert("No coinciden los password");
			$("#password").focus();
			return false;
		}


		return true;

	}

	$("#frmProfile").on("reset",function(event){
		window.location.href = obj.getValue(0)+"dashboard/";
	});
	
    $("#preloaderPrincipal").hide();


});

</script>