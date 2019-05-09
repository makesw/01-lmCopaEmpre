<?php
require '../conexion.php';
$idComp=0;
if(isset($_GET[ 'idComp' ])){
	$idComp = $_GET[ 'idComp' ];
}
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Mouldifi - A fully responsive, HTML5 based admin theme">
<meta name="keywords" content="Responsive, HTML5, admin theme, business, professional, Mouldifi, web design, CSS3">
<title>Inicio</title>
<!-- Site favicon -->
<link rel='shortcut icon' type='image/x-icon' href='../images/favicon.ico' />
<!-- /site favicon -->

<!-- Entypo font stylesheet -->
<link href="../css/entypo.css" rel="stylesheet">
<!-- /entypo font stylesheet -->

<!-- Font awesome stylesheet -->
<link href="../css/font-awesome.min.css" rel="stylesheet">
<!-- /font awesome stylesheet -->

<!-- Bootstrap stylesheet min version -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<!-- /bootstrap stylesheet min version -->

<!-- Mouldifi core stylesheet -->
<link href="../css/mouldifi-core.css" rel="stylesheet">
<!-- /mouldifi core stylesheet -->

<link href="../css/mouldifi-forms.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="../js/html5shiv.min.js"></script>
      <script src="../js/respond.min.js"></script>
<![endif]-->

<!--[if lte IE 8]>
	<script src="../js/plugins/flot/excanvas.min.js"></script>
<![endif]-->
</head>
<body>
<div>

	<a href="posiciones.php?idComp=<?php echo $idComp;?>"><button class="btn btn-primary btn-block" type="button">POSICIONES</button></a>
	<a href="resultados.php?idComp=<?php echo $idComp;?>"><button class="btn btn-primary btn-block" type="button">RESULTADOS</button></a>
	<a href="posicionesGral.php?idComp=<?php echo $idComp;?>"><button class="btn btn-primary btn-block" type="button">POSICIONES-GENERAL</button></a>
	<a href="goleadores.php?idComp=<?php echo $idComp;?>"><button class="btn btn-primary btn-block" type="button">GOLEDORES</button></a>
	<a href="vallaMenosVencida.php?idComp=<?php echo $idComp;?>"><button class="btn btn-primary btn-block" type="button">VALLA MENOS VENCIDA</button></a>
	<a href="juegoLimpio.php?idComp=<?php echo $idComp;?>"><button class="btn btn-primary btn-block" type="button">JUEGO LIMPIO</button></a>
	<a href="sancionados.php?idComp=<?php echo $idComp;?>"><button class="btn btn-primary btn-block" type="button">SANCIONES</button></a>

</div>
</body>
</html>
