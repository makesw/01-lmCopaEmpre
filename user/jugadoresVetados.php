<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.html' );
}
require '../conexion.php';
$resultJugVetados = $connect->query( "select jv.*,j.documento, concat(j.nombres,' ',j.apellidos) nombreJugador, e.nombre nombreEquipo  from jugador_vetado jv join jugador j on jv.documento_jugador = j.documento join equipo e on j.id_equipo = e.id" );
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
			<form id="formJuegos">
			<h1 class="page-title">Consultas / Jugadores Vetados</h1>
			<div class="row">	
			<div class="row">			
			<div class="col-lg-12">
				<div class="class="panel panel-minimal"t">
					<div class="panel-body">
						<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover dataTables-jugVetados" >
						<thead>
							<tr>								
								<th>Documento</th>
								<th>Nombres</th>
								<th>Equipo</th>
								<th>Fecha</th>
								<th>Motivo</th>
							</tr>
						</thead>
						<tbody>
						<?php
						while($row = mysqli_fetch_array($resultJugVetados)){
							$date_a = "";
							if(!empty($row['fecha'])){
								$date_a = new DateTime($row['fecha']);
								$date_a = $date_a->format('d-m-Y');
							}
						?>	
						<tr>
							<td> <?php echo $row['documento']; ?>	</td>
							<td> <?php echo $row['nombreJugador']; ?>	</td>
							<td> <?php echo $row['nombreEquipo']; ?>	</td>
							<td> <?php echo $date_a; ?></td>
							<td> <textarea rows="2"><?php echo $row['motivo']; ?></textarea></td>	
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
			</form>
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
<script>
$(document).ready(function () {
$('.dataTables-jugVetados').DataTable({
	"searching": true,
	"bSort" : false,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 10,
	"oLanguage": {
	   "sSearch": "Buscar: "
	 }
});
});	
</script>
<?php $connect->close(); ?>