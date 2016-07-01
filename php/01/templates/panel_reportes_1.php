<?php

include("includes/metas.php");

?>

<div  class="row-fluid">
	<div class="span12 widget-container-span ui-sortable">
		<div class="widget-header widget-hea1der-small header-color-green">
			<h5 class="smaller">
				<i class="icon-print"></i>
				Panel de Reportes
			</h5>
		</div>

		<div class="widget-body">
			<div class="widget-main padding-4">
				<div class="content">

					<form class="form-horizontal" class="form" role="form" id="frmPanelRep1">

						<table class="tblReports">
							<tr>
								<td><label for="tiporeporte">Tipo Reporte:</label></td>
								<td colspan="3">
									<select id="tiporeporte" name="tiporeporte">
										<option value="0" selected>Por rango de fechas</option>
										<option value="1">Fechas, Comunidad</option>
										<option value="2">Fechas, Comunidad, Bloque</option>
										<option value="3">Fecha, Comunidad, Servicio</option>
									</select>							
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
							<tr>
								<td><label for="idlocalidad">Comunidad:</label></td>
								<td colspan="3">
									<select id="idlocalidad" name="idlocalidad">
									</select>							
								</td>
							</tr>
							<tr>
								<td><label for="idbeneficio">Bloques</label></td>
								<td colspan="3">
									<select id="idbeneficio" name="idbeneficio">
									</select>							
								</td>
							</tr>
							<tr>
								<td><label for="idsubcatben">Servicio</label></td>
								<td colspan="3">
									<select id="idsubcatben" name="idsubcatben">
									</select>							
								</td>
							</tr>
						</table>
						<div class="form-actions">
							<button class="btn btn-success" type="submit">
								<i class="icon-ok bigger-110"></i>
								Consultar
							</button>
						</div>

						<input type="hidden" id="u" name="u" />
						<input type="hidden" id="cidbeneficio" name="cidbeneficio" value="" />
						<input type="hidden" id="cidsubcatben" name="cidsubcatben" value="" />
						<input type="hidden" id="cconceptos" name="cconceptos" value="" />
						<input type="hidden" id="concepto" name="concepto" value="" />

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

	/*
	$("#rowTBeca").hide(); 
	$(".rowVencimientos").hide(); 
	$(".serviciosCobrados").hide();
	*/ 

	$("#u").val( localStorage.nc );

    $("#frmPanelRep1").on("unsubmit");
    $("#frmPanelRep1").on("submit", function(event) {
        event.preventDefault();
        /*
        if ( !ValidateForm() ){
        	return false;
        }
        var opt = parseInt( $(".formato:checked").val() );
        var tr = parseInt($("#tiporeporte").val(),0);
        var url, PARAMS, queryString, nRep;
        
        $("#cconceptos").val( $("#conceptos option:selected").text() );
        $("#ctipobeca").val( $("#tipoBeca option:selected").text() );
        $("#cidbeneficio").val( $("#idbeneficio option:selected").text() );
        $("#cidsubcatben").val( $("#idsubcatben option:selected").text() );
        $("#concepto").val( $("#idlocalidad option:selected").text() );

        queryString = $("#frmPanelRep1").serialize();

        // alert(queryString);
 
        if ( opt == 0) {
	        switch( tr ){
	        	case 0:
					nRep = "rep-caja-niv-concepto-arji/";
	        		break;
	        	case 1:
					nRep = "rep-caja-movimientos-arji/";
	        		break;
	        	case 2:
					nRep = "rep-caja-niv-concepto-descto/";
	        		break;
	        	case 3:
					nRep = "rep-caja-vencimiento-1/";
	        		break;
	        	case 4:
					
					// nRep = "rep-caja-vencimiento-1/";
					
					getViewRecordatorios();
					return false;
	        		break;

	        	case 5:
					nRep = "rep-caja-niv-concepto-recargo/";
	        		break;
	        }

			url = obj.getValue(0)+nRep;
			
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
		}else{


	        switch( tr ){
	        	case 0:
					// nRep = "rep-caja-niv-concepto-arji/";
					return false;
	        		break;
	        	case 1:
					nRep = "rep-caja-movimientos-arji/";
	        		break;
	        }
			
			url = obj.getValue(0)+nRep;
			*/
		
			var queryString = $("#frmPanelRep1").serialize();

			PARAMS = {data:queryString};

	        $("#contentProfile").empty();
	        $("#contentMain").hide(function(){
		        $("#preloaderPrincipal").show();
		        obj.setIsTimeLine(false);
		        var nc = localStorage.nc;
		        $.post(obj.getValue(0) + "reporte-detalle-html-1/", PARAMS,
		            function(html) {	                
		                $("#contentProfile").html(html).show('slow',function(){
			                $('#breadcrumb').html(getBar('Inicio, Listado de Beneficios '));
		                });
		            }, "html");
	        });
	        return false;
		// }
    });



	$('.date-picker').datepicker({
    	format: 'dd-mm-yyyy',
		autoclose: true
	});

	$('.date-picker').on('changeDate', function(event){
	    $(this).datepicker('hide');
	    //validDate();
	});

	$('.date-picker').val(obj.getDateToday());

	function ValidateForm(){
		var startDate = $('#fi').val(); //.replace('-','/');
		var endDate = $('#ff').val(); //.replace('-','/');
		
		// alert(startDate.getTime());
		// alert(endDate.getTime());
		

		if( obj.getDateDiff(startDate,endDate,'-') < 0 ){
		   alert("Rango de fecha incongruente");
		   return false;
		}		
		return true;
	}

	function getBeneficios(){
	    var nc = "u="+localStorage.nc;
	    $("#idbeneficio").empty();
	    $.post(obj.getValue(0)+"data/", { o:1, t:300, p:0,c:nc,from:0,cantidad:0, s:"" },
	        function(json){
	           $.each(json, function(i, item) {
	                $("#idbeneficio").append('<option value="'+item.data+'"> '+item.label+'</option>');
	            });

	        }, "json"
	    );  
	}

	function getSubcategorias(){
	    var nc = "u="+localStorage.nc;
	    $.post(obj.getValue(0)+"data/", { o:1, t:2, p:10, c:nc, from:0, cantidad:0, s:"" },
	        function(json){
	           $.each(json, function(i, item) {
	                $("#idsubcatben").append('<option value="'+item.idsubcatben+'">'+item.subcategoria+'</option>');
	            });

	        }, "json"
	    );  
	}


    function getCatLoc(){
        var nc = "u="+localStorage.nc;
        $("#idlocalidad").empty();
        $.post(obj.getValue(0)+"data/", { o:1, t:310, p:0,c:nc,from:0,cantidad:0, s:"" },
            function(json){
               $.each(json, function(i, item) {
                    $("#idlocalidad").append('<option value="'+item.data+'"> '+item.label+'</option>');
                });

            }, "json"
        );  
    }

    getBeneficios();
    getSubcategorias();
    getCatLoc();

	
});

function resizeScreen() {
	
    // $("#contentMain").css("min-height", obj.getMinHeight());
    // $("#contentProfile").css("min-height", obj.getMinHeight());

}

</script>