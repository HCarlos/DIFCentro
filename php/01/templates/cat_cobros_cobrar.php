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

$user = $_POST['user'];
$idcontribucion  = $_POST['idcontribucion'];

?>

<h3 class="header smaller lighter blue">
    <span id="title"></span>
    <a class="label label-info arrowed-in-right arrowed closeFormUpload pull-right">Regresar</a>
</h3>

                <table>

                    <tr>
                        <td>Concepto</td>
                        <td class="wd100prc ">
                            <input class="wd80prc altoMoz marginLeft1em" name="concepto" id="concepto" type="text" readonly>

                        </td>
                    </tr>

                    <tr>
                        <td>Importe</td>
                        <td>
                            <input class=" altoMoz marginLeft1em textRight" name="importe" id="importe" type="text" pattern="[-+]?[0-9]*[.,]?[0-9]+" required readonly>
                        </td>
                    </tr>

                    <tr>
                        <td>Contribuyente</td>
                        <td class="wd100prc">
                            <input class="wd80prc altoMoz marginLeft1em" name="contribuyente" id="contribuyente" type="text" readonly>
                        </td>
                    </tr>

                    <tr>
                        <td>Observaciones</td>
                        <td class="wd100prc">
                            <input class="wd80prc altoMoz marginLeft1em" name="observaciones" id="observaciones" type="text" readonly>
                        </td>
                    </tr>

                </table>
 

    <input type="hidden" name="idcontribucion" id="idcontribucion" value="<?php echo $idcontribucion; ?>">
    <input type="hidden" name="user" id="user" value="<?php echo $user; ?>">
    <div class="form-group center" style='margin-right: 3em; margin-top: 1em;'>
    	<button type="submit" class="btn btn-primary pull-right"><i class="icon-save"></i>Guardar como Pagado</button>
        <button type="button" class="btn btn-info pull-left" style='margin-right: 4em;' id="printTicket"><i class="icon-print" ></i>Imprimir Recibo</button>
	</div>

</div>
</div>

<!--PAGE CONTENT ENDS-->

<script typy="text/javascript">        

jQuery(function($) {

	$("#preloaderPrincipal").hide();

    localStorage.vsm =  obj.getConfig(2);    

    $("#clave_nivel").focus();

	var idcontribucion = <?php echo $idcontribucion ?>;

    var arrItem    = [];

    $(".btn-primary").hide();


    $(".btn-primary").on("click",function(event){
        event.stopPropagation();

        var resp =  confirm("En este momento se dispone a pagar este servicio.\n\n Sus datos estan correctos?");

        if (resp){

		    var queryString = "user="+localStorage.nc+"&idcontribucion="+idcontribucion;	

            $.post(obj.getValue(0) + "data/", {o:1005, t:3, c:queryString, p:2, from:0, cantidad:0, s:''},
            function(json) {
            		if (json[0].msg=="OK"){
            			alert("Datos guardados con éxito.");
                        printTicket(idcontribucion);
						stream.emit("cliente", {mensaje: "SIIM-CONTRIBUCIONES-PROP-"+idcontribucion+"-"+localStorage.nc});
						$("#preloaderPrincipal").hide();
						$("#contentProfile").hide(function(){
							$("#contentProfile").html("");
							$("#contentMain").show();
						});
        			}else{
						$("#preloaderPrincipal").hide();
        				alert(json[0].msg);	
        			}
        	}, "json");

        }

	});


    $("#printTicket").on("click",function(event){
        event.preventDefault();
        printTicket(idcontribucion);
    });

    function printTicket(IdContribucion){

        var url, PARAMS;
        
        url = obj.getValue(0) + "ticket-cobro-04/";
        
        PARAMS = {user:localStorage.nc, idcontribucion:IdContribucion  };

        

        var temp=document.createElement("form");
        temp.action=url;
        temp.method="POST";
        temp.target="_blank"
        temp.style.display="none";
        for(var x in PARAMS) {
            var opt=document.createElement("textarea");
            opt.name=x;
            opt.value=PARAMS[x];
            temp.appendChild(opt);
        }
        document.body.appendChild(temp);
        temp.submit();

    }    

	function getPago(IdContribucion){
        
        $("#preloaderPrincipal").show();
        var nc = "u="+localStorage.nc+"&idcontribucion="+IdContribucion; 
		$.post(obj.getValue(0) + "data/", {o:27, t:2011, c:nc, p:10, from:0, cantidad:0,s:''},
			function(json){
				if (json.length>0){

					idcontribucion = json[0].idcontribucion;

					$("#concepto").val(json[0].ingreso);
                    $("#importe").val(json[0].subtotal);
                    $("#contribuyente").val(json[0].contribuyente);
                    $("#observaciones").val(json[0].observaciones);

                    $("#title").html("Reg: " + json[0].idcontribucion);

					$("#preloaderPrincipal").hide();

                    $("#concepto").focus();
                    
                    console.log(json[0].status_contribucion);

                    if ( parseInt(json[0].status_contribucion,0) == 0 ){
                        $(".btn-primary").show();
                    }

				}
		},'json');
        
	}

	$(".closeFormUpload").on("click",function(event){
		event.preventDefault();
		$("#preloaderPrincipal").hide();
		$("#contentProfile").hide(function(){
			$("#contentProfile").html("");
			$("#contentMain").show();
		});
		resizeScreen();
		return false;
	});



    getPago(idcontribucion);

	var stream = io.connect(obj.getValue(4));


});

</script>