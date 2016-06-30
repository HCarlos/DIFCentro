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

?>


<div class="widget-box ">
	
	<div class="widget-header widget-header-flat">

			<h5>
				<i class="icon-money"></i>
				Lista de Conceptos a Pagar en Tiempo Real
			</h5>

			<span class="widget-toolbar">
				<a href="#" data-action="collapse">
					<i class="icon-chevron-up"></i>
				</a>
			</span>

			<span class="widget-toolbar">
				<a href="#" data-action="reload" id="btnRefreshContrib">
					<i class="icon-refresh"></i>
				</a>
			</span>
 
	</div>

	<div class="widget-body">
		<div class="widget-main padding-4">

			<table aria-describedby="sample-table-2_info" id="sample-table-3" class="table table-striped table-bordered table-hover dataTable">

				<thead>
					<tr role="row">
						<th aria-label="idcontribucion: activate to sort column ascending" style="width: 10px;" colspan="1" rowspan="1" aria-controls="sample-table-3" tabindex="0" role="columnheader" class="sorting" >ID</th>
						<th aria-label="fecha: activate to sort column ascending" style="width: 80px;" colspan="1" rowspan="1" aria-controls="sample-table-3" tabindex="1" role="columnheader" class="sorting">FECHA</th>
						<th aria-label="contribuyente: activate to sort column ascending" style="width: 120px;" colspan="1" rowspan="1" aria-controls="sample-table-3" tabindex="2" role="columnheader" class="sorting">CONTRIBUYENTE</th>
						<th aria-label="conceptp: activate to sort column ascending" style="width: 120px;" colspan="1" rowspan="1" aria-controls="sample-table-3" tabindex="3" role="columnheader" class="sorting">CONCEPTOS</th>
						<th aria-label="cantidad: activate to sort column ascending" style="width: 10px;" colspan="1" rowspan="1" aria-controls="sample-table-3" tabindex="4" role="columnheader" class="sorting">CANT</th>
						<th aria-label="importe: activate to sort column ascending" style="width: 50px;" colspan="1" rowspan="1" aria-controls="sample-table-3" tabindex="5" role="columnheader" class="sorting">IMPORTE</th>
						<th aria-label="status: activate to sort column ascending" style="width: 50px;" colspan="1" rowspan="1" aria-controls="sample-table-3" tabindex="6" role="columnheader" class="sorting">STATUS</th>
						<th aria-label="" style="width: 50px;" colspan="1" rowspan="1" role="columnheader" class="sorting_disabled"></th>
					</tr>
				</thead>
				<tbody  aria-relevant="all" aria-live="polite" role="alert" >
				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td colspan="3">TOTAL</td>
						<td class="hidden-480 textRight" id="tCant"></td>
						<td class="hidden-480 textRight" id="tTotal"></td>
						<td></td>
						<td></td>
					</tr>
				</tfoot>
			</table>

		
		</div><!--/widget-main-->
	</div><!--/widget-body-->
</div>


<script typy="text/javascript">        

jQuery(function($) {

	localStorage.vsm =  obj.getConfig(2);

	var oTable;

	function getTableCobros(){

		oTable = $('#sample-table-3').dataTable({
	        "oLanguage": {
	                    	"sLengthMenu": "_MENU_ registros por página",
	                    	"oPaginate": {
	                                		"sPrevious": "Prev",
	                                		"sNext": "Next"
	                                     },
	                        "sSearch": "Buscar",
	                        "sProcessing":"Procesando...",
	                        "sLoadingRecords":"Cargando...",
	 						"sZeroRecords": "No hay registros",
	            			"sInfo": "_START_ - _END_ de _TOTAL_ registros",
	            			"sInfoEmpty": "No existen datos",
	            			"sInfoFiltered": "(De _MAX_ registros)"                                        
	        			},	
	        "aaSorting": [[ 0, "desc" ]],			
			"aoColumns": [ null, null, null, null, null, null, null, { "bSortable": false }],
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
			"bRetrieve": true,
			"bDestroy": false
	 	});
	}


	$("#btnRefreshContrib").on("click",function(event){
		event.stopPropagation();
		fillTable();
	})

	function fillTable(){
		if(oTable != null){
			oTable.fnDestroy();
			$('#sample-table-3 > tbody').empty();
			init = true;
		}
		getCobrosList0()();
	}


	function getCobrosList0(){
        
        $("#preloaderPrincipal").hide();
	    var nc = "u="+localStorage.nc;
	    var st,tcant,ttotal;
		$.post(obj.getValue(0) + "data/", {o:1005, t:2012, c:nc, p:11, from:0, cantidad:0,s:'order by fecha desc'},
			function(json){
				if (json.length>0){
					tcant = 0;
					ttotal = 0;
			        $("#sample-table-3 > tbody").html('');
			        $("#sample-table-3 > tbody").empty();
		            $.each(json, function(i, item) {

		           		st =  '<tr>';
						st += '		<td>'+item.idcontribucion+'</td>';
						st += '		<td class="hidden-480 textRight">'+item.cFecha+'</td>';
						st += '		<td>'+item.contribuyente+'</td>';
						st += '		<td>'+item.ingreso+'</td>';
						st += '		<td class="hidden-480 textRight">'+parseFloat(item.cantidad)+'</td>';
						st += '		<td class="hidden-480 textRight">'+commaSeparateNumber(item.subtotal)+'</td>';
						if (item.status_contribucion == 0){
							st += '		<td class="center"><i class="normal-icon icon-time pink bigger-130"></i></td>';
						}else if (item.status_contribucion == 1){
							st += '		<td class="center"><i class="icon-ok green bigger-130"></i></td>';
						}else{
							st += '		<td class="center"></td>';
						}
							st += '		<td class="center">';
							st += '			<div class="hidden-phone visible-desktop action-buttons">';
						if (item.status_contribucion == 0){
							st += '				<a class="green pagarContrib" href="#"  id="pagarContrib-'+item.idcontribucion+'-'+item.creado_por_usuario+'" title="Realizar Pago">';
							st += '					<i class="icon-ok bigger-130"></i>';
							st += '				</a>';
						}else if (item.status_contribucion == 1){
							st += '				<a class="red cancelpagoContrib" href="#"  id="cancelpagoContrib-'+item.idcontribucion+'-'+item.creado_por_usuario+'" title="Deshacer Pago" >';
							st += '					<i class="icon-CANCEL bigger-130"></i>';
							st += '				</a>';
						}else{
						}
							st += '			</div>';
							st += '		</td>';
						st += '</tr>';

						$("#sample-table-3 > tbody").append(st);

						tcant  += parseFloat(item.cantidad);
						ttotal += parseFloat(item.subtotal);

		            });

			        $("#tCant").html(tcant);
			        $("#tTotal").html( commaSeparateNumber( ttotal.toFixed(2) ) );

					$(".pagarContrib").on("click",function(event){
						event.preventDefault();
						event.stopPropagation();
						$("#iconSaveCommentResp").show();
						var arr = event.currentTarget.id.split('-');
						obj.setIsTimeLine(false);
						getConfirmPago(arr[1]);

						/*
						var resp =  confirm("Desea PAGAR el registro "+arr[1]+" ?  \n\nEsta operación es irreversible.");
						//alert(resp);
						//return false;
						if (resp){
							//alert(arr[1]);
							obj.setIsTimeLine(false);
							var nc = "user="+localStorage.nc+"&idcontribucion="+arr[1];
				            $.post(obj.getValue(0) + "data/", {o:1005, t:3, c:nc, p:2, from:0, cantidad:0, s:''},
				            function(json) {
				            		if (json[0].msg=="OK"){
    								    stream.emit( "cliente", { mensaje: "SIIM-PAGOCONTRIB-PROP-"+arr[1]+"-"+arr[2] } );
										getCobrosList0();
				        			}else{
				        				alert(json[0].msg);	
				        			}
				        	}, "json");
			        	}
			        	*/
			        

					});

					$(".cancelpagoContrib").on("click",function(event){
						event.preventDefault();
						event.stopPropagation();
						$("#iconSaveCommentResp").show();
						var arr = event.currentTarget.id.split('-');
						var resp =  confirm("Desea CANCELAR el Pago del registro "+arr[1]+" ?");
						//alert(resp);
						//return false;
						if (resp){
							//alert(arr[1]);
							obj.setIsTimeLine(false);
							var nc = "user="+localStorage.nc+"&idcontribucion="+arr[1];
				            $.post(obj.getValue(0) + "data/", {o:1005, t:4, c:nc, p:2, from:0, cantidad:0, s:''},
				            function(json) {
				            		if (json[0].msg=="OK"){
    								    stream.emit( "cliente", { mensaje: "SIIM-CANCEL_PAGO_CONTRIB-PROP-"+arr[1]+"-"+arr[2] } );
										getCobrosList0();
				        			}else{
				        				alert(json[0].msg);	
				        			}
				        	}, "json");
			        	}

					});

					if (init==true){
						getTableCobros();
						init = false;
					}else{
						oTable.fnClearTable();
						oTable.fnDraw();
					}


				}
		        
		        $("#preloaderPrincipal").hide();

		},'json');
        
	}

	function getConfirmPago(IdContribucion){
        $("#contentProfile").html("");
        $("#contentMain").hide(function(){
	        $("#preloaderPrincipal").show();
	        obj.setIsTimeLine(false);
	        var nc = localStorage.nc;
			$.post(obj.getValue(0) + "imprimir-recibo-de-pago/", {
					user: nc,
					idcontribucion: IdContribucion
				},
	            function(html) {	                
	                $("#contentProfile").html(html).show('slow',function(){
		                $('#breadcrumb').html(getBar('Inicio, Catálogo de Ingresos '));
	                });
	            }, "html");
        });
        return false;
	}

	var init = true;
	getCobrosList0();

	var stream = io.connect(obj.getValue(4));

	stream.on("servidor", jsNewConcepto);
	function jsNewConcepto(datosServer) {
		var ms = datosServer.mensaje.split("-");
		
		if (( ms[1] == 'CONTRIBUCIONES' ) && ( parseInt(ms[3],0) > 0 ) && ( localStorage.nc == ms[4] )) {
			fillTable();
		}

		if ( ms[1] == 'PAGOCONTRIB' ) {
			fillTable();
		}

		if (( ms[1] == 'CANCEL_PAGO_CONTRIB' ) && ( localStorage.nc == ms[4] )) {
			fillTable();
		}

	}

	$("#preloaderPrincipal").hide();

});



</script>