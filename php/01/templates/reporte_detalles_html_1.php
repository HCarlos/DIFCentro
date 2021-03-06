<?php

include("includes/metas.php");

$data = $_POST['data'];

if (!isset($data)){
	header('Location: http://difcentro.tecnointel.mx/');
}

require_once('../oFunctions.php');
$Q = oFunctions::getInstance();
require_once('../oCentura.php');
$f = oCentura::getInstance();

parse_str($data);

// print $data;

// echo $data;

$rs = $f->getQuerys(2022,$data);

// print count($rs);

// if ( count($rs) > 0 ){
// 	$l=0;
// 	$r0=$r1=$r2=$r3=$r4=0;
// 	foreach ($rs as $i => $value) {

?>

<div  class="row-fluid">
	<div class="span12 widget-container-span ui-sortable">
		<div class="widget-header widget-hea1der-small header-color-default">
			
			<div class="widget-toolbar border pull-right">

			    <button  type="button" class="btn btn-minier btn-primary arrowed-in-right arrowed closeWinRep01 " style="margin: 0 1em !important;" >
			        <i class="icon-angle-left icon-on-right"></i>
			        Regresar
			    </button>

			</div>

			<div class="widget-toolbar border pull-right">

				<button data-toggle="dropdown" class="btn btn-minier btn-success arrowed-in-right arrowed dropdown-toggle">
					<i class="white icon-print bigger-110"></i>												Imprimir
					<span class="caret"></span>
				</button>

				<ul class="dropdown-menu dropdown-success">
					<li>
						<a href="#" id="printFormato1">Formato 1</a>
					</li>
				</ul>

			</div>


			<div class="widget-toolbar orange pull-left no-border ">
				<h3 class="grey lighter  pull-left position-relative wd100prc">
					<i class="icon-print green"></i>
					LISTA DE BENEFICIARIOS (<small><?= $cTipoReporte; ?></small>)
				</h3>
			</div>

		</div>

		<div class="widget-body">
			<div class="widget-main padding-4">
				<div class="content">

					<table aria-describedby="sample-table-2_info" id="sample-table-2" class="table table-striped table-bordered table-hover dataTable">
										
						<thead>
							<tr role="row">
								<th aria-label="idedocta: activate to sort column ascending" style="width: 2%;" aria-controls="sample-table-2" tabindex="0" role="columnheader" class="sorting center" >ID</th>
								<th aria-label="pdf: activate to sort column ascending" style="width: 22.5%;" aria-controls="sample-table-2" tabindex="1" role="columnheader" class="sorting">BLOQUES</th>
								<th aria-label="xml: activate to sort column ascending" style="width: 22.5%;" aria-controls="sample-table-2" tabindex="1" role="columnheader" class="sorting">SERVICIOS</th>
								<th aria-label="concepto: activate to sort column ascending" style="width: 22.5%;" aria-controls="sample-table-2" tabindex="1" role="columnheader" class="sorting">BENEFICIARIO</th>
								<th aria-label="idfamilia: activate to sort column ascending" style="width: 22.5%;" aria-controls="sample-table-2" tabindex="1" role="columnheader" class="sorting">LOCALIDAD</th>
								<th aria-label="familia: activate to sort column ascending" style="width: 3%;" aria-controls="sample-table-2" tabindex="1" role="columnheader" class="sorting center">CNT</th>
								<th aria-label="fecha: activate to sort column ascending" style="width: 5%;" aria-controls="sample-table-2" tabindex="1" role="columnheader" class="sorting center">FECHA</th>
							</tr>
						</thead>
										
						<tbody aria-relevant="all" aria-live="polite" role="alert">
							<?php 
								if ( count($rs) > 0 ){
									foreach ($rs as $i => $value) {
							?>
							<tr>
								<td class="center"><?= str_pad($rs[$i]->idbeneficiootorgado,4,"0",STR_PAD_LEFT); ?></td>
								<td><?= $rs[$i]->beneficio; ?></td>
								<td><?= $rs[$i]->subcategoria; ?></td>
								<td><?= $rs[$i]->beneficiario; ?></td>
								<td><?= $rs[$i]->localidad; ?></td>
								<td class="center"><?= $rs[$i]->cantidad; ?></td>
								<td class="center"><?= $rs[$i]->fecha; ?></td>
							</tr>	
							<?php 
										}
									}
							?>
						</tbody>
					</table>

				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript"> 

jQuery(function($) {

	var oTable;
	var queryString = "<?= $data; ?>";

	$("#alerta").hide();	

	function getTable(){

		oTable = $('#sample-table-2').dataTable({
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
			"aoColumns": [ null, null, null, null, null, null, null],
			"aLengthMenu": [[20, 25, 50, -1], [20, 25, 50, "Todos"]],
			"bRetrieve": true,
			"bDestroy": false
	 	});

	}

	$(".closeWinRep01").on("click",function(event){
		event.preventDefault();
		$("#preloaderPrincipal").hide();
		$("#contentProfile").hide(function(){
			$("#contentProfile").empty();
			$("#contentMain").show();
		});
		resizeScreen();
		return false;
	});

	$("#printFormato1").on('click', function(event) {
		event.preventDefault();

		url = obj.getValue(0)+"print-beneficiario-filter-xls-1/";
		
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

	getTable();

	$("#preloaderPrincipal").hide();

});		

</script>
