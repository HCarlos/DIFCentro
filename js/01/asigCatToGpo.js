// JavaScript Document
/*
$(document).on("ready",initAsignCatToGpo);

function initAsignCatToGpo(){

	if ($(".asignaciones1").length){
		$(".btnAsig").on("click",setAsigOpcToGpo);
		$(".btnDel").on("click",deleteOpcFromGpo);
			// loadjscssfile("01/asigCatToGpo.js", "js");
			alert(0);			
	}
}

*/


function getCat(){
	// Listado de Opciones Disponibles
	$("#div1em").html(getPreloader());
	$.post(obj.getValue(0)+"getEC/", { o:obj.index, t:0, p:0 },
 		function(json){
			if (json.length<=0) { return false;}
			$(".listDesde").html('');	
			$.each(json, function(i, item) {
				//alert(item.label);
				$(".listDesde").append('<option value="'+item.data+'">'+item.label+'</option>');	
			});
		$("#div1em").html("");
 	}, "json");
	
	//Listado del Combo de Grupos
	$("#div1em").html(getPreloader());
	$.post(obj.getValue(0)+"getEC/", { o:obj.index, t:1, p:0 },
 		function(json){
			if (json.length<=0) { return false;}
			$("select[name='selCombo']").html('<OPTION VALUE="0" selected >Seleccione una opción</OPTION>');	
			$.each(json, function(i, item) {
				//alert(item.label);
				$('select[name="selCombo"]').append('<option value="'+item.data+'">'+item.label+'</option>');	
			});
			$("#div1em").html("");
 	}, "json");
	
	if ($("#spanUser").length) {
		// Se manda a llamar las opciones asignadas a este grupo
		$('select[name="selCombo"]').on("change",getCatAsigToGpo);
	}
	
     if ($(".btnAsig").length){
		$(".btnAsig").button({
          	icons: {primary: "ui-icon-circle-arrow-e",},
          	text: true
     	})
		$(".btnAsig").on("click",setAsigOpcToGpo);
	}
	   
     if ($(".btnDel").length){
		$(".btnDel").button({
     		icons: {primary: "ui-icon-circle-arrow-w",},
     		text: true
     	});
		$(".btnDel").on("click",deleteOpcFromGpo);
	}
	

}

function getCatAsigToGpo(){
	
	// Opciones asignadas a un determinado grupo
	var x = $('.listDesde option:selected').val();	
	var y = $('select[name="selCombo"] option:selected').val();	
/*	
	if (isDefined(parseInt(x))){
		alert("Seleccione una opción disponible");
		return false;
	}
	if (isDefined(parseInt(y))){
		alert("Seleccione un grupo");
		return false;
	}
*/	
	var d = x+"."+y;
	//alert(d);
	$(".div1em").html(getPreloader());	
	$(".listHasta").html("");	
	$.post(obj.getValue(0)+"getEC/", { o:obj.index, c:d, t:2, p:0 },
 		function(json){
			if (json.length<=0) { $(".div1em").html('');return false;}
			$.each(json, function(i, item) {
				//alert(item.label);
				$(".listHasta").append('<option value="'+item.data+'">'+item.label+'</option>');	
			});
			$(".div1em").html('');	
 	}, "json");
	
}


function setAsigOpcToGpo(){
	
	$(".div1em").html(getPreloader());	
	
	// Opciones asignadas a un determinado grupo
	var x = $('.listDesde option:selected').val();	
	var y = $('select[name="selCombo"] option:selected').val();	
	
	if (isDefined(parseInt(x))){
		alert("Seleccione una opción disponible");
		return false;
	}else{
		x='';
		$(".listDesde option:selected").each(function () {
                x += $(this).val() + "|";
          });

	}
	if (isDefined(parseInt(y)) || y <= 0){
		alert("Seleccione un grupo");
		return false;
	}
	var d = x+"."+y;
	//alert(d);
	$.post(obj.getValue(0)+"getEC/", { o:obj.index, c:d, t:10, p:1 },
 		function(json){
			if (json.length<=0 && json[0].msg=="Error") {$(".div1em").html(""); return false;}
				getCatAsigToGpo();
 	}, "json");
	
}

function deleteOpcFromGpo(){
	// Opciones asignadas a un determinado grupo
	$(".div1em").html(getPreloader());	

	var x = $('.listHasta option:selected').val();	
	
	if (isDefined(parseInt(x))){
		alert("Seleccione una opción disponible");
		return false;
	}else{
		x='';
		$(".listHasta option:selected").each(function () {
                x += $(this).val() + "|";
          });

	}
	$.post(obj.getValue(0)+"getEC/", { o:obj.index, c:x, t:20, p:1 },
 		function(json){
			if (json.length<=0 && json[0].msg=="Error") { $(".div1em").html(""); return false;}
				getCatAsigToGpo();
 	}, "json");
	
}


function getCatFac(){
	//Listado del Combo de Grupos
	$("#msgResponse").html(getPreloader());
	$.post(obj.getValue(0)+"getEC/", { o:obj.index, t:1, p:0 },
 		function(json){
			if (json.length<=0) { return false;}
			$("select[name='selCombo']").html('<OPTION VALUE="0" selected >Seleccione una opción</OPTION>');	
			$.each(json, function(i, item) {
				//alert(item.label);
				$('select[name="selCombo"]').append('<option value="'+item.data+'">'+item.label+'</option>');	
			});
			$("#msgResponse").html("");
 	}, "json");
	
	if ($("#spanUser").length) {
		// Se manda a llamar las opciones asignadas a este grupo
		$('select[name="selCombo"]').on("change",getCatAsigToGpo);
	}
	
     if ($(".btnAsig").length){
		$(".btnAsig").button({
          	icons: {primary: "ui-icon-circle-arrow-e",},
          	text: true
     	})
		$(".btnAsig").on("click",setAsigOpcToGpo);
	}
	   
     if ($(".btnDel").length){
		$(".btnDel").button({
     		icons: {primary: "ui-icon-circle-arrow-w",},
     		text: true
     	});
		$(".btnDel").on("click",deleteOpcFromGpo);
	}
	

}

