<?php 
function error_message_html($ar=array()){ 
$cad = "";
if (count($ar)>0){
$cad .= "<ul>";
	foreach($ar as $item=>$value){ 
	     $var1 = str_replace("Table 'diariop_dbG2.","",$ar[$item]);
	     $var1 = str_replace("' doesn't exist","",$var1);
		 
		$cad .= "<li>".$var1."</li>";
	}
$cad .= "</ul>";
}
return $cad;
} 

function sol_env_int($folio=""){ 
$cad = '
<div class="ui-widget">
	<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		Hemos recibido su solicitud y será atendía con el folio:<br/><br/>
		<strong>'.$folio.'    </strong>ID Cliente: <strong>2230</strong><span>  </span> <br/><br/>
		No olvide consultar el estatus de su solicitud en esta misma página.
</div>
</div>
</p>
<button aria-disabled="false" role="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" id="button" onClick="location.href='."'http://www.villahermosa.gob.mx'".'"><span class="ui-button-text">Ir a P&aacute;gina Principal</span></button>
';
return $cad;
} 

?>
