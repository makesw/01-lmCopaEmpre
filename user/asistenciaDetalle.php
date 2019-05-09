<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.html' );
}
require '../conexion.php';
$idComp=0;
$idJugador=0;
$idEquipo=0;
if(isset($_GET[ 'idComp' ])){
	$idComp = $_GET[ 'idComp' ];
}
if(isset($_GET[ 'idJugador' ])){
	$idJugador = $_GET[ 'idJugador' ];
}
if(isset($_GET[ 'idEquipo' ])){
	$idEquipo = $_GET[ 'idEquipo' ];
}
$resultAsistencias = $connect->query( "select concat (ju.nombres,' ',ju.apellidos) nombreJugador,concat (j.nombre1,' vs ',j.nombre2) juego, j.fecha from asistencia a join juego j on a.id_juego = j.id and a.id_jugador = ".$idJugador." join fase f on j.id_fase = f.id and f.id_competicion =".$idComp." join jugador ju on a.id_jugador = ju.id and ju.id_equipo = ".$idEquipo );
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
	<link rel='shortcut icon' type='image/x-icon' href='../images/favicon.ico'/>
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
	<link href="../css/plugins/datatables/jquery.dataTables.css" rel="stylesheet">
<link href="../js/plugins/datatables/extensions/Buttons/css/buttons.dataTables.css" rel="stylesheet">

</head>

<body>

<!-- Page container -->
<div class="page-container">

	<!-- Page Sidebar -->
	<div class="page-sidebar">
		<?php include("./header.html");?>
		<?php include("./menu.html");?>
	</div>
	<!-- /page sidebar -->

	<!-- Main container -->
	<div class="main-container">
		<!-- Main header -->
		<div class="main-header row">
			<div class="col-sm-6 col-xs-7">

				<!-- User info -->
				<ul class="user-info pull-left">
					<li class="profile-info dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="false"> <img width="44" class="img-circle avatar" alt="" src="<?php echo $_SESSION['dataSession']['url_foto'];?>"><?php echo $_SESSION['dataSession']['nombres'].' '.$_SESSION['dataSession']['apellidos'] ?></a>

			</div>


			<div class="col-sm-6 col-xs-5">
				<div class="pull-right">
					<a title="Salir" href="../salir.php"><img src="../images/btn-close.png" style="height: 30px;widows: 30px;" /></a>
				</div>
			</div>

		</div>
		<!-- /main header -->

		<!-- Main content -->
		<div class="main-content">
			<div class="row">							
			<h1 class="page-title"><a href="asistencia.php?idComp=<?php echo $idComp; ?>">Asistencia a Juegos</a> / Detalle</h1>		
			<div class="row">			
			<div class="col-lg-12">
				<div class="class="panel panel-minimal"t">
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover dataTables-asis" >
							<thead>
								<tr>
									<th>Jugador</th>
									<th>Juego</th>
									<th>Fecha</th>
								</tr>
							</thead>
							<tbody>
							<?php
							while($row = mysqli_fetch_array($resultAsistencias)){							
							?>	
							<tr>	
								<td> <?php echo $row['nombreJugador']; ?> </td>
								<td> <?php echo $row['juego']; ?> </td>
								<td> <?php echo $row['fecha']; ?> </td>							
							</tr>
							<?php } ?>
							</tbody>
							</table>
						</div>								
					</div>
				</div>			
				
			</div>
			</div>		
			<!-- Footer -->
			<?php include("./footer.html");?>
			<!-- /footer -->
		</div>
		<!-- /main content -->

	</div>
	<!-- /main container -->

</div>
<!-- /page container -->	
<!--Load JQuery-->
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/plugins/metismenu/jquery.metisMenu.js"></script>
<script src="../js/plugins/blockui-master/jquery-ui.js"></script>
<script src="../js/plugins/blockui-master/jquery.blockUI.js"></script>
<script src="../js/functions.js"></script>

<script src="../js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../js/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="../js/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="../js/plugins/datatables/jszip.min.js"></script>
<script src="../js/plugins/datatables/pdfmake.min.js"></script>
<script src="../js/plugins/datatables/vfs_fonts.js"></script>
<script src="../js/plugins/datatables/extensions/Buttons/js/buttons.html5.js"></script>
<script src="../js/plugins/datatables/extensions/Buttons/js/buttons.colVis.js"></script>
<script src="../js/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="../js/plugins/datatables/jszip.min.js"></script>
<script src="../js/plugins/datatables/pdfmake.min.js"></script>
<script src="../js/plugins/datatables/vfs_fonts.js"></script>
</body>
</html>
<?php $connect->close(); ?>