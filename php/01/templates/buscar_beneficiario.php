<?php

include("includes/metas.php");

?>
<div class="row-fluid">
<div class="span12" style="padding-left: 1em; padding-right: 1em;">
<div class="tabbable">

	<h3 class="header smaller lighter blue" id="title">Buscar Familia</h3>
	<form id="frmData" role="form">

		<div class="tab-content">

			<input class="input-large form-control altoMoz wd60prc show" id="search" name="search" type="text" placeholder="Familia" autofocus>

		</div>

	    <input type="hidden" name="idfamilia" id="idfamilia" value="0">
	    <div class="form-group w96" style='margin-right: 3em; margin-top: 1em;'>
	    	<button type="button" class="btn btn-default pull-right" data-dismiss="modal" id="closeFormUpload"><i class="icon-signout"></i>Cerrar</button>
	    	<span class="muted"></span>
	    	<button type="submit" class="btn btn-primary pull-right" style='margin-right: 4em;'><i class="icon-ok"></i>Aceptar</button>
		</div>

	</form>

</div>




</div>
</div>
<!--PAGE CONTENT ENDS-->

<script typy="text/javascript">        

jQuery(function($) {

	var stream = io.connect(obj.getValue(4));


	var IdFamilia = 0;
	var cFamilia = "";
	var data = [];

	$("#preloaderPrincipal").show();

	$("#search").focus();

    $("#frmData").unbind("submit");
	$("#frmData").on("submit",function(event){
		event.preventDefault();

		$("#preloaderPrincipal").show();

		    var queryString = $(this).serialize();	
		    
			stream.emit("cliente", {mensaje: "SIBDMUN|IDBENEFICIARIO|"+IdFamilia+"|"+cFamilia+"|"+localStorage.nc});
			$("#preloaderPrincipal").hide();
			$("#contentProfile").hide(function(){
				$("#contentProfile").empty();
				$("#contentMain").show();
			});

	});


	//custom autocomplete (category selection)
	$.widget( "custom.catcomplete", $.ui.autocomplete, {
		_renderMenu: function( ul, items ) {
			var that = this,
			currentCategory = "";
			$.each( items, function( index, item ) {
				if ( item.category != currentCategory ) {
					ul.append( "<li class='ui-autocomplete-category' id='act-"+item.indice+"' >" + item.category + "</li>" );
					currentCategory = item.category;
				}
				that._renderItemData( ul, item );
			});
		}
	
	});


    function getPersonas(){
        var nc = "u="+localStorage.nc;
        $.ajax({ url: obj.getValue(0)+"data/",
            data: { o:1, t:29, p:0,c:nc,from:0,cantidad:0, s:" order by label asc " },
            dataType: "json",
            type: "POST",
            success: function(json){
               $.each(json, function(i, item) {
                    //$("#idpersona").append('<option value="'+item.data+'"> '+item.label+'</option>');
                    if ( item.label == null ){
                        console.log(item.label);
                	}else{
                        //console.log(item.label);
                    	data[i]={ label: item.data+ ' - '+item.label+ ' - '+item.tutor , category: "Familia", indice: item.data, tutor: item.tutor };
                	};
                });
				$( "#search" ).catcomplete({
					delay: 0,
					source: data,
					autoFocus: true,
					select: function(event, ui) { 
						if (ui.item){
							cFamilia = ui.item.value;
							IdFamilia = ui.item.indice;
			        	}
			      	},
					open: function() {
						$('.ui-autocomplete-categorya').next('.ui-menu-item').addClass('ui-first');
					} 			      	
				});
				
				$("#search").focus();

				$("#preloaderPrincipal").hide();	



            }
		});
	
	}

	$("#closeFormUpload").on("click",function(event){
		event.preventDefault();
		$("#preloaderPrincipal").hide();
		$("#contentProfile").hide(function(){
			$("#contentProfile").empty();
			$("#contentMain").show();
		});
		resizeScreen();
		return false;
	});

    getPersonas();

    // alert("Hola Mundo");

});

</script>