<?php
/*
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Mexico_City');

header("html/text; charset=utf-8");  
header("Cache-Control: no-cache");
*/
?>

<div  class="row-fluid">
	<div class="span12 widget-container-span ui-sortable">
		<div class="widget-header widget-hea1der-small header-color-green">
			<h5 class="smaller">
				<i class="icon-print"></i>
				Panel de Reportes
			</h5>
			<!--
			<div class="widget-toolbar">
				<a href="#" data-action="settings">
					<i class="icon-cog"></i>
				</a>

				<a href="#" data-action="reload">
					<i class="icon-refresh"></i>
				</a>

				<a href="#" data-action="collapse">
					<i class="icon-chevron-up"></i>
				</a>

				<a href="#" data-action="close">
					<i class="icon-remove"></i>
				</a>
			</div>
		-->
		</div>

		<div class="widget-body">
			<div class="widget-main padding-4">
				<div class="content">

				<form class="form-horizontal" class="form" role="form" id="frmPanelReports1">

					<table class="tblReports">
						<tr>
							<td><label for="tiporeporte">Tipo Reporte:</label></td>
							<td>
								<select id="tiporeporte" name="tiporeporte">
									<option value="0">Agrupas por Conceptos</option>
									<option value="1" selected>Movimientos diarios</option>
								</select>							
							</td>
							<td><label for="usuario">Usuario:</label></td>
							<td>
								<select id="usuario" name="usuario">
								</select>							
							</td>
						</tr>
						<tr>
							<td><label for="concepto">Concepto:</label></td>
							<td colspan="3">
								<select id="concepto" name="concepto"></select>							
							</td>
						</tr>
 						<tr>
							<td><label for="fi">Desde: </label></td>
							<td>
								
									<input class="date-picker altoMoz" id="fi" name="fi" data-date-format="dd-mm-yyyy" type="text">
									<span class="add-on">
										<i class="icon-calendar"></i>
									</span>
								
							</td>
							<td><label for="ff">Hasta: </label></td>
							<td>
									<input class="date-picker altoMoz" id="ff" name="ff" data-date-format="dd-mm-yyyy" type="text">
									<span class="add-on">
										<i class="icon-calendar"></i>
									</span>
								
							</td>
						</tr>
					</table>

					<div class="form-actions center">
						<button class="btn btn-success" type="submit">
							<i class="icon-ok bigger-110"></i>
							Consultar
						</button>
					</div>

					<input type="hidden" id="u" name="u" value="" />
					<input type="hidden" id="cusuario" name="cusuario" value="" />

				</form>











				</div>
			</div>
		</div>	
	</div>
</div>


<div id="inline2">
	
</div>
<!--PAGE CONTENT ENDS-->

<script typy="text/javascript">        

jQuery(function($) {

	$("#preloaderPrincipal").hide();

	$("#u").val( localStorage.nc );
	

	var arrItem = new Array();

    $("#frmPanelReports1").on("unsubmit");
    $("#frmPanelReports1").on("submit", function(event) {
        event.preventDefault();
        if ( !ValidateForm() ){
        	return false;
        }

        var url, PARAMS, queryString, nRep;

		var usr = $("#usuario option:selected").text();
		$("#cusuario").val( usr );

        queryString = $("#frmPanelReports1").serialize();
        
        switch( parseInt($("#tiporeporte").val(),0) ){
        	case 0:
				nRep = "rep-caja-niv-concepto-siim/";
				alert("Módulo en Construcción");
				return false;
        		break;
        	case 1:
				nRep = "rep-caja-movimientos-siim/";
        		break;
        }

		url = obj.getValue(0)+nRep;

		// alert(queryString);
		
		PARAMS = {data:queryString};

		var temp=document.createElement("form");
		temp.action=url;
		temp.method="POST";
		temp.target="_blank";
		temp.style.display="none";
		for(var x in PARAMS) {
			var opt=document.createElement("textarea");
			opt.name=x;
			opt.value=PARAMS[x];
			temp.appendChild(opt);
		}
		document.body.appendChild(temp);
		temp.submit();
		return temp;
    });


    function getConceptos(){
        var nc = "u="+localStorage.nc;
        arrItem = [];
        $("#concepto").html('<option value="0">< T o d o s ></option>');
        $.post(obj.getValue(0)+"data/", { o:1, t:305, p:0,c:nc,from:0,cantidad:0, s:"" },
            function(json){
               $.each(json, function(i, item) {
                    arrItem[item.idingreso] = {
                                    idingreso:item.idingreso, 
                                    ingreso:item.ingreso, 
                                    vsm_min:parseInt(item.dsm_min,0),
                                    vsm_max:parseInt(item.dsm_max,0),
                                    costo_min:parseFloat(item.costo_min).toFixed(2),
                                    costo_max:parseFloat(item.costo_max).toFixed(2),
                                    tipo:item.tipo
                                };
                    $("#concepto").append('<option value="'+item.idingreso+'"> '+item.ingreso+'</option>');
                });

				getUsuarios();

            }, "json"
        );  
    }

    function getUsuarios(){
        var nc = "u="+localStorage.nc;
        $("#usuario").html('<option value="0" selected>< T o d o s ></option>');
        $.post(obj.getValue(0)+"data/", { o:1, t:306, p:0,c:nc,from:0,cantidad:0, s:"" },
            function(json){
               $.each(json, function(i, item) {
                    $("#usuario").append('<option value="'+item.data+'"> '+item.label+'</option>');
                });

                if (idcontribucion<=0){ // Nuevo Registro
                    $("#title").html("Nuevo registro");
                    $("#clave_nivel").focus();
                }else{ // Editar Registro
                    $("#title").html("Editando el Pago: "+idcontribucion);
                    //getPago(idcontribucion);
                }

            }, "json"
        );  
    }

	$('.date-picker').datepicker().next().on(ace.click_event, function(){
		$(this).prev().focus();
	});
	$('.date-picker').mask('99-99-9999');
	$('.date-picker').val(obj.getDateToday());


	function ValidateForm(){
		var startDate = $('#fi').val().replace('-','/');
		var endDate = $('#ff').val().replace('-','/');

		if(startDate > endDate){
		   alert("Rango de fecha incongruente");
		   return false;
		}		
		return true;
	}

	getConceptos();


	
});

function resizeScreen() {
	
    $("#contentMain").css("min-height", obj.getMinHeight());
    $("#contentProfile").css("min-height", obj.getMinHeight());

}

</script>