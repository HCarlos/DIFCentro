<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Mexico_City');

header("html/text; charset=utf-8");  
header("Cache-Control: no-cache");

require_once("../oCentura.php");
$f = oCentura::getInstance();

$user = $_POST['user'];
$idfactura = $_POST['idfactura'];
$idfamilia = $_POST['idfamilia'];
$isfe = $_POST['isfe'];

// $arr = $f->getQuerys(10009,"idfamilia=".$idfam."&u=".$user,0,0,0,array(),'alumno');
// if (count($arr)>0){

?>
<style type="text/css">

	.anchoConcepto{width: 100% !important;}
	.itemNC{width: 7em !important;}
	.td1{width: 7em !important; height: 1em !important; text-align: right !important;}

</style>

<div class="row-fluid">
	<div class="span12 ">
		<form id="formTimFac1" role="form">
			<div class="widget-box transparent invoice-box">
				<div class="widget-header widget-header-large">
					<h3 class="grey lighter pull-left position-relative">
						<i class="icon-leaf green"></i>
						Nota de Crédito
					</h3>

					<div class="widget-toolbar no-border hidden-480">
						<h3 class="label label-info arrowed-in-right arrowed lighter closeFE0 pull-right" style="cursor:pointer !important;" >Regresar</h3>
					</div>

					<div class="widget-toolbar no-border invoice-info">
						<span class="invoice-info-label">NC:</span>
						<span class="red" id="spfactura"></span>

						<br>
						<span class="invoice-info-label">Fecha:</span>
						<span class="blue"><?php echo date('d-m-Y'); ?></span>
					</div>

				</div>


				<div class="widget-body">
					<div class="widget-main padding-24">
						<div class="row-fluid">
							<div class="row-fluid">
								<div class="span6">
									<div class="row-fluid">
										<div class="wd100prc label label-large label-info arrowed-in arrowed-right">
											<b>Datos del Emisor (Empresa) y del Receptor (Cliente) </b>
										</div>
									</div>

									<div class="row-fluid">
										<ul class="unstyled spaced">
											<li>
												<i class="icon-caret-right blue"></i>
												<select id="idemisorfiscal" name="idemisorfiscal" size="1" class='altoMoz wd94prc' readonly></select>
											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<select id="idregfis" name="idregfis" size="1" class='altoMoz wd94prc' ></select>
											</li>

											<li>
												<i class="icon-caret-right blue" ></i> 
												<span id="rf0"></span>
											</li>

											<li>
												<i class="icon-caret-right blue" ></i>
												<span id="rf1"></span>
											</li>


										</ul>
									</div>
								</div><!--/span-->

								<div class="span6">
									<div class="row-fluid">
										<div class="wd100prc label label-large label-success arrowed-in arrowed-right">
											<b>Datos Complementarios</b>
										</div>
									</div>

									<div class="row-fluid">
									
									<table class="marginTop1em spaced">
										<tr>
											<td class="wd20prc">Método de Pago</td>
											<td class="wd80prc">
												<select id="idmetododepago" name="idmetododepago" sieze="1" class="marginLeft1em altoMoz wd80prc">
												</select> 
											</td>
										<tr>	
										<tr>
											<td class="wd20prc">Referencia</td>
											<td class="wd80prc">
												<input type="text" id="referencia" name="referencia" class="marginLeft1em altoMoz wd80prc" autofocus/>
											</td>
										<tr>	
										<tr>
											<td class="wd20prc">Email</td>
											<td class="wd80prc">
												<input type="email" id="email1" name="email1" class="marginLeft1em altoMoz wd80prc"/>
											</td>
										<tr>	
									</table>
									
									</div>
								</div><!--/span-->
							</div><!--row-->

							<div class="space"></div>

							<div class="row-fluid">
								<table class="table table-striped table-bordered" id="tblDelFac">
									<thead>
										<tr>
											<th class="center">#</th>
											<th>Descripción</th>
											<th class="td1 textRight">Importe</th>
											<th class="td1 textRight">Descuento</th>
											<th class="td1 textRight">Subtotal</th>
											<th class="td1 textRight">Recargo</th>
											<th class="td1 textRight">Total</th>
										</tr>
									</thead>

									<tbody>

									</tbody>

								</table>
							</div>

							<div class="hr hr8 hr-double hr-dotted"></div>

							<div class="row-fluid">
								<div class="span5 pull-right">
									<h4 class="pull-right">
										Importe : $ 
										<span class="grey pull-right" id="importefactura"></span><br/>
										Descto : $ 
										<span class="grey pull-right" id="desctofactura"></span><br/>
										Importe 2 : $ 
										<span class="grey pull-right" id="subtotalfactura"></span><br/>
										Recargo : $ 
										<span class="grey pull-right" id="recargofactura"></span><br/>
										SubTotal : $ 
										<span class="grey pull-right" id="importe2factura"></span><br/>
										IVA : $ 
										<span class="grey pull-right" id="ivafactura"></span><br/>
										Total : $ 
										<span class="red pull-right" id="totalfactura"></span>
									</h4>
									<button class="btn btn-info" id="createNotaCredito">Guardar</button>
									<button class="btn btn-default" id="calcularTotal">Calcular</button>

										<input type="hidden" id="serie" name="serie" value=""/>
										<input type="hidden" id="subtotal" name="subtotal" value="0"/>
										<input type="hidden" id="descto" name="descto" value="0"/>
										<input type="hidden" id="importe" name="importe" value="0" />
										<input type="hidden" id="recargo" name="recargo" value="0"/>
										<input type="hidden" id="importe2" name="importe2" value="0"/>
										<input type="hidden" id="iva" name="iva" value="0"/>
										<input type="hidden" id="total" name="total" value="0"/>
										<input type="hidden" id="idfactura" name="idfactura" value="0"/>
										<input type="hidden" id="padre" name="padre" value="0"/>
										<input type="hidden" id="idemp" name="idemp" value="0"/>
										<input type="hidden" id="metodo_pago" name="metodo_pago"  value=""/>
										<input type="hidden" id="tutor" name="tutor" value="0"/>
										<input type="hidden" id="u" name="u" value=""/>
										<input type="hidden" id="idcliente" name="idcliente" value="0"/>
										<input type="hidden" id="cadOrd" name="cadOrd" value=""/>

								</div>
								<button class="btn btn-primary pull-left" id="timbrarNotaCredito">Timbrar Nota de Crédito</button>

								<div class="span7 pull-left"> <!-- Extra Information --> </div>
							</div>

							<div class="space-6"></div>

							<div class="row-fluid">
								<div class="span12 well">
									<!-- Thank you for choosing Ace Company products.
We believe you will be satisfied by our services. -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</form>
	</div>
</div>


<script type="text/javascript"> 

jQuery(function($) {

	accounting.settings = {
		currency: {
			symbol : " ", 
			format: "%s%v",
			decimal : ".",
			thousand: ",",  
			precision : 2   
		},
		number: {
			precision : 0,  
			thousand: ",",
			decimal : "."
		}
	}

	var stream = io.connect(obj.getValue(4));
	var IdFamilia = <?php echo $idfamilia; ?>;
	var IdFactura = <?php echo $idfactura; ?>;
	var Tutor = "<?php echo $tutor; ?>";
	var init = true;
	var IsFE = <?php echo $isfe; ?>;


	$("#idemp").val( localStorage.IdEmp );
	$("#tutor").val( Tutor );

	if (IsFE){
		$("#timbrarNotaCredito").hide();
	}
		
	$("#preloaderPrincipal").show();

	$(".closeFE0").on("click",function(event){
        event.preventDefault();
        $("#preloaderPrincipal").hide();
        closeWindow();
	});

	function closeWindow(){
        $("#contentProfile").hide(function(){
            $("#contentProfile").html("");
            $('#breadcrumb').html(getBar('Inicio, Facturas por Familia '));
            $("#contentMain").show();
        });
        resizeScreen();
        return false;
	}

    $("#idemisorfiscal").on("change",function(event){
    	getEmiFis();
    });

    function getMetodoPago(){
       $("#preloaderPrincipal").show();
       $("#idmetododepago").html("");
        var nc = "u="+localStorage.nc;
        $.post(obj.getValue(0)+"data/", { o:1, t:31, p:0,c:nc,from:0,cantidad:0, s:"" },
            function(json){
                var nc,it,df;
               $.each(json, function(i, item) {
               		df = parseInt(item.isdefault,0);
               		df = df==1?' selected':'';
                    $("#idmetododepago").append('<option value="'+item.data+'" '+df+'> '+item.label+'</option>');
                });
            	$("#preloaderPrincipal").hide();
               getEmiFisCombo();
            }, "json"
        );  
    }

    function getEmiFisCombo(){
       $("#preloaderPrincipal").show();
       $("#idemisorfiscal").html("");
        var nc = "u="+localStorage.nc;
        $.post(obj.getValue(0)+"data/", { o:1, t:26, p:0,c:nc,from:0,cantidad:0, s:"" },
            function(json){
                var nc,it;
               $.each(json, function(i, item) {
               		var it = item.data.split("-");
                    $("#idemisorfiscal").append('<option value="'+it[0]+'"> '+item.label+'</option>');
                });
            	$("#preloaderPrincipal").hide();
               getEmiFis();
            }, "json"
        );  
    }

    function getEmiFis(){
       $("#preloaderPrincipal").show();
       var it = $("#idemisorfiscal").val();
        $.post(obj.getValue(0)+"data/", { o:28, t:10006, p:10,c:it,from:0,cantidad:0, s:"" },
            function(json){
            	$("#preloaderPrincipal").hide();
            	if (init) {
 					getRegFisCombo();
 				};        	
            }, "json"
        );  
    }

    $("#idregfis").on("change",function(event){
    	getRegFis();
    });

    function getRegFisCombo(){
       $("#preloaderPrincipal").show();
       $("#idregfis").html("");
        var nc = "u="+localStorage.nc+"&idfamilia="+IdFamilia;
        $.post(obj.getValue(0)+"data/", { o:1, t:30, p:0,c:nc,from:0,cantidad:0, s:"" },
            function(json){
                var nc;
               $.each(json, function(i, item) {
                    $("#idregfis").append('<option value="'+item.data+'"> '+item.label+'</option>');
                });
            	$("#preloaderPrincipal").hide();
            	if (init){
                	getRegFis();
            	}
            }, "json"
        );  
    }

    function getRegFis(){
       	$("#preloaderPrincipal").show();
        $.post(obj.getValue(0)+"data/", { o:28, t:28, p:10,c:$("#idregfis").val(),from:0,cantidad:0, s:"" },
            function(json){
            	$("#rf0").html(json[0].calle+" "+json[0].num_ext+' '+json[0].num_int+' '+json[0].colonia);
            	$("#rf1").html(json[0].localidad+" "+json[0].cp+' '+json[0].estado);
            	$("#email1").val(json[0].email1);
            	$("#preloaderPrincipal").hide();
            	getFacDet();
            }, "json"
        );  
    }

    function getFacDet(){
       $("#preloaderPrincipal").show();
       $("#tblDelFac > tbody").html("");

        var nc = "u="+localStorage.nc+"&idfactura="+IdFactura;
        $.post(obj.getValue(0)+"data/", { o:28, t:10014, p:10, c:nc, from:0, cantidad:0, s:"" },
            function(json){
            	var cad0, cad1, cad2;
            	cad2 = "";
               $.each(json, function(i, item) {
               		cad1 = json[i].alumno+" - "+json[i].curp+" - "+json[i].rfc+" - "+json[i].matricula_oficial;
					cad0 = "<tr>";
						cad0 += "<td class='center'>"+parseInt(json[i].idfacdet)+"</td>";
						cad0 += "<td class='anchoConcepto'><span class='blue'>"+json[i].descrip_prod+"</span><br/><small  class='chikirimbita grey'>"+cad1+"</small></td>";
						cad0 += "<td class='td1'><input type='text' name='subtotal_"+i+"' id='subtotal_"+i+"' value='"+json[i].subtotal+"' class='textRight altoMoz itemNC' /> </td>";
						cad0 += "<td class='td1'><input type='text' name='descto_"+i+"' id='descto_"+i+"' value='"+json[i].descto+"' class='textRight altoMoz itemNC' /> </td>";
						cad0 += "<td class='td1'><input type='text' name='importe_"+i+"' id='importe_"+i+"' value='"+json[i].importe+"' class='textRight altoMoz itemNC' readonly /> </td>";
						cad0 += "<td class='td1'><input type='text' name='recargo_"+i+"' id='recargo_"+i+"' value='"+json[i].recargo+"' class='textRight altoMoz itemNC' /> </td>";
						cad0 += "<td class='td1'>";
						cad0 += "<input type='text' name='total_"+i+"' id='total_"+i+"' value='"+json[i].total+"' class='textRight altoMoz itemNC' readonly />";
						cad0 += "<input type='hidden' id='idfacdet_"+i+"' name='idfacdet_"+i+"' value='"+json[i].idfacdet+"' />";
						cad0 += "<input type='hidden' id='idedocta_"+i+"' name='idedocta_"+i+"' value='"+json[i].idedocta+"' />";
						cad0 += "<input type='hidden' id='idproducto_"+i+"' name='idproducto_"+i+"' value='"+json[i].idproducto+"' />";
						cad0 += "<input type='hidden' id='producto_"+i+"' name='producto_"+i+"' value='"+json[i].descrip_prod+"' />";
						cad0 += "</td>";
					cad0 += "</tr>";
					cad2 += cad2!=""?";":"";
     				cad2 +="1"+"|"+json[i].descrip_prod+"|"+json[i].total+"|"+json[i].total+"|"+json[i].alumno+"|"+json[i].curp+"|"+json[i].clave_registro_nivel+"|"+json[i].nivel_fiscal+"|"+json[i].rfc;
					$("#tblDelFac > tbody").append(cad0);
                });

				$("#cadOrd").val(cad2);

			    $(".itemNC").on("change",function(event){
			    	event.preventDefault();
			    	calcularTotales();
			    });

            	$("#preloaderPrincipal").hide();
            	
            	getFacEncab();

            }, "json"
        );

    }

    function getFacEncab(){
       $("#preloaderPrincipal").show();
        var nc = "u="+localStorage.nc+"&idfactura="+IdFactura;
        $.post(obj.getValue(0)+"data/", { o:28, t:10013, p:10, c:nc, from:0, cantidad:0, s:"" },
            function(json){

            	$("#importefactura").html( accounting.formatMoney(json[0].subtotal) );
            	$("#desctofactura").html( accounting.formatMoney(json[0].descto) );
            	$("#subtotalfactura").html( accounting.formatMoney(json[0].importe) );
            	$("#recargofactura").html( accounting.formatMoney(json[0].recargo) );
            	$("#importe2factura").html( accounting.formatMoney(json[0].importe2) );
            	$("#ivafactura").html( accounting.formatMoney(json[0].iva) );
            	$("#totalfactura").html( accounting.formatMoney(json[0].total) );


            	$("#subtotal").val( json[0].subtotal );
            	$("#iva").val( json[0].iva );
            	$("#total").val( json[0].total );

            	$("#idemisorfiscal").val( json[0].idemisorfiscal );

            	$("#idregfis").val( json[0].idregfis );

            	$("#idmetododepago").val( json[0].metodo_de_pago );
            	$("#referencia").val( json[0].referencia );

            	$("#spfactura").html(IdFactura);
            	$("#idfactura").val( IdFactura );
            	
            	$("#padre").val( IdFactura );

            	$("#serie").val( json[0].idre);

            	$("#idcliente").val( json[0].idcliente );
            	$("#u").val( localStorage.nc );

            	init = false;
            	//IsFE = parseInt(json[0].isfe,0)==1?true:false;

            	$("#preloaderPrincipal").hide();
			    $("#referencia").focus();

            }, "json"
        );  
    }

    function calcularTotales(){

    	var nSubtotal = 0;
    	var nDescto = 0;
    	var nImporte = 0;
    	var nRec = 0;
    	var nImporte2 = 0;
    	var nIva = 0;
    	var nTotal = 0;

    	for (var i = 0; i <= 100; i++) {
    	
    		if (  parseInt( $("#idfacdet_"+i).val() ,0) > 0 ) {

    			var n0 = parseFloat( $("#subtotal_"+i).val() );
    			var n1 = parseFloat( $("#descto_"+i).val() );

		    	$("#importe_"+i).val( n0 - n1 );

    			var n2 = parseFloat( $("#importe_"+i).val() );
    			var n3 = parseFloat( $("#recargo_"+i).val() );


		    	$("#total_"+i).val( n2 + n3 );

    			var n4 = parseFloat( $("#total_"+i).val() ) ;
		    	
		    	nImporte += n0;
		    	nDescto += n1;		    	
		    	
		    	nSubtotal += n2;
		    	nRec += n3;

		    	nImporte2 += n2 + n3

	    		nIva = 0;

		    	nTotal += n4;
				
				
            	$("#importefactura").html( accounting.formatMoney(nImporte) );
            	$("#desctofactura").html( accounting.formatMoney(nDescto) );
            	$("#subtotalfactura").html( accounting.formatMoney(nSubtotal) );

            	$("#recargofactura").html( accounting.formatMoney(nRec) );
            	$("#importe2factura").html( accounting.formatMoney(nImporte2) );
            	$("#ivafactura").html( accounting.formatMoney(nIva) );
            	$("#totalfactura").html( accounting.formatMoney(nTotal) );

		    	$("#importe").val( nImporte );
		    	$("#descto").val( nDescto );
		    	$("#subtotal").val( nSubtotal );
		    	$("#recargo").val( nRec );
		    	$("#importe2").val( nImporte2 );
		    	$("#iva").val( nIva );
		    	$("#total").val( nTotal );
				
			
    		} else {

    			break;

    		}	

    	}

    }

    $("#idmetododepago").on("change",function(event){
    	event.preventDefault();
	    $("#referencia").focus();
    });

    $("#createNotaCredito").on("click",function(event){
    	event.preventDefault();
    	
    	var mp = $("#idmetododepago option:selected").text();
    	$("#metodo_pago").val(mp);
    	
        var nc = "u="+localStorage.nc+"&idfactura="+IdFactura;
    	var queryString = $("#formTimFac1").serialize();
		
		var isef = IsFE==true?11:12;

		calcularTotales();

		var isContinue = confirm("Desea guardar la información actual?");

		if (!isContinue){
			return false;
		}
		
        $.post(obj.getValue(0) + "data/", {o:28, t:isef, c:queryString, p:12, from:0, cantidad:0, s:''},
            function(json){

            	if (json[0].msg=="OK"){

            		if (isef == 12) {
            			alert("Nota modificada con éxito");
            		}else{
            			alert("Nota creada con éxito");
            		}

					closeWindow();            		

            	}else{

            		alert("No fue posible crear la nota de crédito");

            	}
				
            }, "json"
        );  
 
    });


    $("#calcularTotal").on("click",function(event){
    	event.preventDefault();
    	calcularTotales();
    });	


    $("#timbrarNotaCredito").on("click",function(event){
    	event.preventDefault();
    	
        var nc = "u="+localStorage.nc+"&idfactura="+IdFactura;

    	var mp = $("#idmetododepago option:selected").text();
    	$("#metodo_pago").val(mp);

    	var queryString = $("#formTimFac1").serialize();
		
		var isef = IsFE==true?11:12;

		calcularTotales();

		var isContinue = confirm("Desea TIMBRAR la información actual?");

		if (!isContinue){
			return false;
		}

		// alert(queryString);

        $.post(obj.getValue(0)+"data/", { o:28, t:10013, p:10, c:nc, from:0, cantidad:0, s:"" },
            function(json){
            	//alert(json[0].isfe);
            	if ( parseInt(json[0].isfe) == 1  ){
            		alert("Esta Factura ya Fue Timbrada");
            		closeWindow();
            		return false;
            	}else{


			        var PARAMS = {data:queryString};  
			        var IdEmp = localStorage.IdEmp;
			        var url = obj.getValue(0)+"timbrar-nc-"+IdEmp+"-0/";

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
			        closeWindow();
			        return temp;


            	}
            }, "json"
        );  





 
    });

    getMetodoPago();

});

</script>
