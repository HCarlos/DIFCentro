<script  src="/js/01/base.js"> </script>

<script typy="text/javascript">
    document.write("<script src='"+obj.getValue(4)+"/socket.io/socket.io.js'>"+"<"+"/script>");
</script>    

<script typy="text/javascript">

	var stream = io.connect(obj.getValue(4));

</script>    

<?php

header("application/json: charset=utf-8");

require_once("../oCentura.php");
$f = oCentura::getInstance();

$tokenpay = $_GET['tokenpay'];
$idtransacpay = $_GET['idtransacpay'];

$arr = array();

if ( isset($tokenpay) && isset($idtransacpay) ){

	$cad="tokenpay=".$tokenpay."&idtransacpay=".$idtransacpay;

	$res = $f->setSaveData(1005,$cad,0,0,5,'');

	if ( trim($res) == "OK" ){
?>

<script typy="text/javascript">        

	stream.emit( "cliente", { mensaje: "SIIM-PAGOCONTRIB-PROP"} );

</script>

<?php

	}

	$arr[0]->message = $res;


	$m = json_encode($arr);

	echo $m;

	}
?>
