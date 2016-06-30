<?php
$email = $_GET['email'];
require_once("../oCentura.php");
$f = oCentura::getInstance();

$arg = "username=$email";
$res = $f->setSaveData(40,$arg,0,0,101);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE-edge,chrome=1">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta http-equiv="cleartype" content="on">
<title>SiacGob</title>
</head>

<body>
<?php
if ($res=="OK"){
?>
<p><?php echo $email; ?>. </p>
<p>Gracias por registrarte. </p>
<p>Ahora ya puedes utilizar nuestra App</p>
<?php
}else{
?>
<p><?php echo $res; ?>. </p>
<?php
}
?>

</body>
</html>
