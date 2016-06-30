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
<div  class="row-fluid">
	<table class="ui-pg-table navtable" style="float:left;table-layout:auto;" border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td data-original-title="Add new row" id="add_grid-table" title="" class="ui-pg-button ui-corner-all">
					<div class="ui-pg-div" id="btnAddRegistry" >
						<span class="ui-icon icon-plus-sign purple"></span>
					</div>
				</td>
				<td title="" data-original-title="" class="ui-pg-button ui-state-disabled" style="width:1em;">
					<span class="ui-separator marginLeft2em"></span>
				</td>
				<td data-original-title="Reload Grid" id="refresh_grid-table" title="" class="ui-pg-button ui-corner-all">
					<div class="ui-pg-div" id="btnRefresh">
						<span class="ui-icon icon-refresh green"></span>
					</div>
				</td>
				<!--
				<td title="" data-original-title="" class="ui-pg-button ui-state-disabled" style="width:1em;">
					<span class="ui-separator marginLeft2em"></span>
				</td>
				<td data-original-title="Send Grid" title="" class="ui-pg-button ui-corner-all">
					<div class="ui-pg-div" id="btnSendMessage0">
						<span class="ui-icon icon-comments blue"></span>
					</div>
				</td>
				-->
			</tr>
		</tbody>
	</table>
</div>

<div class="borderTopContainer">
	<div id="user-profile-1" class="user-profile row-fluid">
			<div id="sample-table-2_wrapper" class="dataTables_wrapper" role="grid">

				<table aria-describedby="sample-table-2_info" id="sample-table-2" class="table table-striped table-bordered table-hover dataTable">
									
					<thead>
						<tr role="row">
							<th aria-label="iduser: activate to sort column ascending" style="width: 80px;" colspan="1" rowspan="1" aria-controls="sample-table-2" tabindex="0" role="columnheader" class="sorting" >ID</th>
							<th aria-label="username: activate to sort column ascending" style="width: 80px;" colspan="1" rowspan="1" aria-controls="sample-table-2" tabindex="1" role="columnheader" class="sorting">USERNAME</th>
							<th aria-label="nombre_completo: activate to sort column ascending" style="width: 150px;" colspan="1" rowspan="1" aria-controls="sample-table-2" tabindex="1" role="columnheader" class="sorting">NOMBRE COMPLETO</th>
							<th aria-label="foto: activate to sort column ascending" style="width: 20px;" colspan="1" rowspan="1" aria-controls="sample-table-2" tabindex="1" role="columnheader" class="sorting">IS PHOTO</th>
							<th aria-label="" style="width: 80px;" colspan="1" rowspan="1" role="columnheader" class="sorting_disabled"></th>
						</tr>
					</thead>
									
					<tbody aria-relevant="all" aria-live="polite" role="alert"></tbody>
				</table>

			</div><!--PAGE CONTENT ENDS-->

	</div>
</div>

<div id="inline2">
	
</div>
<!--PAGE CONTENT ENDS-->

<script typy="text/javascript">        

jQuery(function($) {

	var oTable;

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
			"aoColumns": [ null, null,  null, null, { "bSortable": false }],
			"aLengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "Todos"]],
			"bRetrieve": true,
			"bDestroy": false
	 	});
	}

	function fillTable(){
						
		var tB = "";

		$("#preloaderPrincipal").show();
		var nc = "u="+localStorage.nc;
		var is_photo;
		$.post(obj.getValue(0) + "data/", {o:0, t:-1, c:nc, p:10, from:0, cantidad:0,s:''},
			function(json){
					$.each(json, function(i, item) {
						is_photo = item.foto!==""?"<i class='fa fa-user'></i> SI":"";
						tB +=' 			<tr class="odd">';
						tB +='';
						tB +='				<td class=" ">';
						tB +='					<a class="modUsuarioPro" href="#" id="idosr-'+item.iduser+'" >'+padl(item.iduser,4)+'</a>';
						tB +='				</td>';
						tB +='				<td class=" " >'+item.username+'</td>';
						tB +='				<td class=" " >'+item.apellidos+' '+item.nombres+'</td>';
						tB +='				<td class="center" >'+is_photo+'</td>';
						tB +='				<td class=" ">';
						tB +='					<div class="hidden-phone visible-desktop action-buttons">';
						tB +='';
						tB +='						<a class="green modUsuarioPro" href="#" id="iduser-'+item.iduser+'" >';
						tB +='							<i class="icon-pencil bigger-130"></i>';
						tB +='						</a>';
						tB +='	';
						tB +='						<a class="red delUsuario" href="#"  id="delUsuario-'+item.iduser+'" >';
						tB +='							<i class="icon-trash bigger-130"></i>';
						tB +='						</a>';
						tB +='	';
						tB +='						<a class="blue pwdUsuario" href="#"  id="pwdUsuario-'+item.iduser+'" >';
						tB +='							<i class="icon-key bigger-130"></i>';
						tB +='						</a>';
						tB +='					</div>';
						tB +='';
						tB +='				</td>';
						tB +='			</tr>';
					});

					$('#sample-table-2 > tbody').html(tB);

					$("#preloaderPrincipal").hide();

					$(".modUsuarioPro").on("click",function(event){
						event.preventDefault();
						var arr = event.currentTarget.id.split('-');
						obj.setIsTimeLine(false);
						getPropUsuario(arr[1]);
					});

					$(".pwdUsuario").on("click",function(event){
						event.preventDefault();
						var arr = event.currentTarget.id.split('-');
						obj.setIsTimeLine(false);
						getChangePWD(arr[1]);
					});

					$(".delUsuario").on("click",function(event){
						event.preventDefault();
						$("#iconSaveCommentResp").show();
						var resp =  confirm("Desea eliminar este registro?");
						//alert(resp);
						//return false;
						if (resp){
							var arr = event.currentTarget.id.split('-');
							//alert(arr[1]);
							obj.setIsTimeLine(false);
				            $.post(obj.getValue(0) + "data/", {o:0, t:2, c:arr[1], p:2, from:0, cantidad:0, s:''},
				            function(json) {
				            		if (json[0].msg=="OK"){
										onClickFillTable();
				        			}else{
				        				alert(json[0].msg);	
				        			}
				        	}, "json");
			        	}

					});

					if (init==true){
						getTable();
						init = false;
					}else{
						oTable.fnClearTable();
						oTable.fnDraw();
					}

        	},
        'json'
        );
							
	}
	
	var init = true;			
	fillTable();

	$("#btnRefresh").on("click",function(event){
		event.preventDefault();
		onClickFillTable();
	})

	function onClickFillTable(){
		if(oTable != null){
			oTable.fnDestroy();
			$('#sample-table-2 > tbody').empty();
			init = true;
		}
		fillTable();
	}

	$("#btnAddRegistry").on("click",function(event){
		event.preventDefault();

		obj.setIsTimeLine(false);
		getPropUsuario(0);

	})

	function getPropUsuario(IdUsuario){

		var nc = localStorage.nc; //.split("@");
		$.post(obj.getValue(0) + "cat-usuario-prop/", {
				user: nc,
				iduser: IdUsuario
			},
            function(html) {
            	$("#contentMain").hide(function(){
	                $("#contentProfile").html(html);
	                $("#contentProfile").show();
	                $('#breadcrumb').html(getBar('Inicio,Catálogo de Usuarios'));
            	});
            }, "html");
        return false;

	}

	function getChangePWD(IdUsuario){

		var nc = localStorage.nc; //.split("@");
		$.post(obj.getValue(0) + "cat-usuario-change-pwd/", {
				user: nc,
				iduser: IdUsuario
			},
            function(html) {
            	$("#contentMain").hide(function(){
	                $("#contentProfile").html(html);
	                $("#contentProfile").show();
	                $('#breadcrumb').html(getBar('Inicio,Cambiando Password de Usuario'));
            	});
            }, "html");
        return false;

	}

	$("#btnSendMessage0").on("click",function(event){
		event.preventDefault();

		var nc = localStorage.nc; //.split("@");
		$.post(obj.getValue(0) + "cat-usuario-send-message/", {
				user: nc
			},
			function(html) {
				$("#divUploadImage").html(html);
				$("#divUploadImage").modal('toggle');
		}, "html");


	});

/*
	var stream = io.connect(obj.getValue(4));
	stream.on("servidor", jsNewUsuario);
	function jsNewUsuario(datosServer) {
		var ms = datosServer.mensaje.split("-");
		if (ms[1]=='USUARIO') {
			onClickFillTable();
		}
	}
*/
	

});



</script>