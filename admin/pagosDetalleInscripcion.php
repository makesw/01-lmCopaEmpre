<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
    header( 'Location: ../index.html' );
}else{
    if($_SESSION[ 'dataSession' ]['perfil'] != 'admin'){
        header( 'Location: ../salir.php' );
    }
}
require '../conexion.php';
$resultPagos = $connect->query( "select * from pago where pago.id_equipo = ".$_GET[ 'idEqui' ]." and id_competicion = ".$_GET[ 'id_comp' ]." and (id_tipo_pago = 1 or UPPER(detalle) like '%INSCRIP%' ) order by fecha desc" );
$equipo = mysqli_fetch_array($connect->query( "select * from equipo WHERE id=".$_GET[ 'idEqui' ] ));
$competicion = mysqli_fetch_array($connect->query( "select * from competicion WHERE id=".$_GET[ 'id_comp' ] ));


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
			<h1 class="page-title">Administración / <a href="pagosInscripcion.php?opt=1&idComp=<?php echo $competicion['id']; ?>">Pagos Inscripción</a> / Detalle</h1>
			<div class="row">
			
			<div class="col-lg-12">
				<div class="panel panel-default">
				<div class="panel-heading clearfix">
						<h3 class="panel-title"><?php echo "Equipo: ".$equipo[ 'nombre' ]." |  Competición: ".$competicion[ 'nombre' ]; ?></h3>						
					</div>
					<div class="panel-body">					
						<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover dataTables-pagos" >
						<thead>
							<tr>								
								<th>Valor</th>
								<th>Fecha</th>
								<th>Tipo Pago</th>
								<th>Descripción</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
							while($row = mysqli_fetch_array($resultPagos)){
								$date_f = new DateTime($row['fecha']);
								$date_f = $date_f->format('d-m-Y');
								$tipo = "Abono";
								if($row['descuento']){$tipo="Descuento";}
							?>
							<tr>								
								<td class="text-center"><?php echo ' $ '.number_format($row['abono']);?></td>
								<td class="text-center"><?php echo $date_f;?></td>
								<td class="text-center"><?php echo $tipo;?></td>
								<td class="text-center"><?php echo $row['detalle'];?></td>
								<td class="text-center">
									<a title="Borrar" href="javaScript:delPago('<?php echo $row['id']; ?>');">
								 	<i class="icon-cancel icon-larger red-color"></i> </a>
								</td>							
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
</body>
</html>
<script>
$(document).ready(function () {
$('.dataTables-pagos').DataTable({
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 10,
	"searching": false,
	"bSort" : false,		
	dom: '<"html5buttons" B>lTfgitp',
		buttons: [				
			{
				extend: 'excelHtml5',
				title: '<?php echo 'DetallePagosInscripcion'.$equipo['nombre']; ?>',
				exportOptions: {
					columns: [ 0, 1, 2 ]
				}
			},
			{
				extend: 'pdfHtml5',
				title: '<?php echo $equipo['nombre']; ?>',
				exportOptions: {
					columns: ':visible'
				}
			}
		]
	
	
});
});
function delPago( idAbono ) {
	if ( confirm( 'Confirma Eliminar?' ) ) {
		$.ajax( {
			url: 'server.php?action=delPago&id='+idAbono,
			type: 'POST',
			data: new FormData(  ),
			success: function ( data ) {
				//console.log( data );
				location.reload();
			},
			error: function ( data ) {
				//console.log( data );
			},
			cache: false,
			contentType: false,
			processData: false
		} );
	}
}	
</script>
<?php $connect->close(); ?>