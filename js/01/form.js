if ($("#frmUploadFile").length){

	$("#frmUploadFile").on("submit",function(event){
		event.preventDefault();

		if (validForm()){

				$("#latitud").val(obj.getLat());
				$("#longitud").val(obj.getLon());
				$("#username").val(localStorage.nc);
			
				$("#preload1").show('fadeIn');
				$(".btn-primary").hide('fadeOut');
		        var queryString = $(this).serialize();	
		        //alert(queryString);
		        
		        var data = new FormData();

				jQuery.each($('input[type=file]')[0].files, function(i, file) {
				    data.append('file0', file);
				});

				data.append('data', queryString);

				//alert(obj.getValue(0)+"fu-ul/");

				$.ajax({
				    url:obj.getValue(0)+"fu-ul/",
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
				           		if (parseInt($("#nuevo").val())==0){
				           		}
					           	stream.emit("cliente", {mensaje: "MOD."+json.status}); 	
								
								//$("#frmUploadFile").clear();
								//$(this).reset();
								//$("#divUploadImage").modal('hide');

								$("#preload1").hide('fadeOut');
								$(".btn-primary").show('fadeIn');
								$("#closeFormUpload").click();
				       	}

				    }
				});
		}

	});
}

function validForm(){
	
	if(parseInt($("#categoria").val())<0){
		alert("Seleccione una categoría");
		$("#categoria").focus();
		return false;
	}
	
	//alert($("#comentario").val().length);
	//return false;

	if( $("#comentario").val().length<=0 ){
		//alert("Escriba un su comentario");
		$("#comentario").focus();
		$("#comentario").val("Sin comentario");
		return true;
	}		

	if( (parseInt($("#latitud").val())==0) || (parseInt($("#longitud").val())==0) ){
		alert("No puede realizar esta operación: Falló la Localización");
		$("#categoria").focus();
		return false;
	}

	return true;

}