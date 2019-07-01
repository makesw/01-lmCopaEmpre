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
$resultTSuspencion = $connect->query( "select * from tipo_sancion order by nombre asc" );
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

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
      <script src="../../js/html5shiv.min.js"></script>
      <script src="../../js/respond.min.js"></script>
<![endif]-->

	<!--[if lte IE 8]>
	<script src="../../js/plugins/flot/excanvas.min.js"></script>
<![endif]-->
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
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading clearfix">
							<h3 class="panel-title">Tipos de Sanción</h3>
						</div>
						<div class="panel-body">
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-create-tsanc">Agregar</button>
							<div class="table-responsive" style="overflow-x: inherit !important;">
								<table class="table table-striped table-bordered table-hover dataTables-tpsancion" >
									<thead>
											<tr>
												<th>#</th>
												<th>Nombre</th>
												<th>Puntos</th>
												<th>Fechas</th>
												<th>Valor</th>
												<th>Veta Jugador</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php $iter = 1;
									while($tSusp = mysqli_fetch_array($resultTSuspencion)){?>
											<tr>
												<td>
													<?php echo $iter; ?>
												</td>
												<td>
													<?php echo $tSusp['nombre']; ?>
												</td>
												<td>
													<?php echo $tSusp['puntos']; ?>
												</td>
												<td>
													<?php echo $tSusp['fechas_suspencion']; ?>
												</td>
												<td>
													<?php echo $tSusp['valor']; ?>
												</td>
												<td>
													<?php echo $tSusp['veta_jugador']==1?'SI':'NO'; ?>
												</td>
												<td>
												<a href="javaScript:editTsancion(<?php echo $tSusp['id']; ?>)">
												<i class="fa fa-edit"></i>
												</a>
												<a title="Borrar" href="javaScript:delTsanc('<?php echo $tSusp['id']; ?>');">
												<i class="icon-cancel icon-larger red-color"></i>
												</a>												
												</td>
											</tr>
											<?php $iter++; } ?>
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
	
	<div id="modal-create-tsanc" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Crear Tipo Sanción</h4>
				</div>
				<div class="modal-body">
					<form id="formTsancion" method="post">
						<div class="panel-body">
							<div class="form-group">
								<label for="nombre">Nombre</label>
								<input type="text" class="form-control" name="nombre" placeholder="nombre" required id="tpSancionName" onblur="javascript:aMayusculas(this.value,this.id);">
								<label for="puntos">Puntos Negativos</label>
								<input type="number" max="0" class="form-control" value="0" name="puntos" placeholder="puntos" required>
								<label for="fechas">Fechas Suspención</label>
								<input type="number" min="0" class="form-control" value="0" name="fechas" placeholder="fechas" required>
								<label for="fechas">Valor $</label>
								<input type="number" min="0" class="form-control" name="valor" value="0" placeholder="valor" required>
								<label for="veta">Veta Jugador</label><br/>								
								<input type="checkbox" id="veta" name="veta">
							</div>
							<button type="submit" class="btn btn-primary">Guardar</button>
						</div>
					</form>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<div id="modal-edit-tsanc" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Editar Tipo Sanción</h4>
				</div>
				<div class="modal-body">
					<form id="formEditTsancion" method="post">
						<div class="panel-body">
							<div class="form-group">
								<label for="emailaddress">Nombre</label>
								<input type="text" class="form-control" name="nombreEts" id="nombreEts" placeholder="nombre" required onblur="javascript:aMayusculas(this.value,this.id);">
								<label for="puntosEts">Puntos Negativos</label>
								<input type="number" max="0" class="form-control" name="puntosEts" id="puntosEts" placeholder="puntos" required>
								<label for="fechasEts">Fechas Suspención</label>
								<input type="number" class="form-control" name="fechasEts" id="fechasEts"  placeholder="fechas" required>
								<label for="valorEts">Valor $</label>
								<input type="number" class="form-control" name="valorEts" id="valorEts" value="0" placeholder="valor" required>
								<label for="vetaEdit">Veta Jugador</label><br/>						
								<input type="checkbox" id="vetaEdit" name="vetaEdit">
							</div>
							<button type="submit" class="btn btn-primary">Guardar</button>
						</div>
						<input type="hidden" id="btnhIdEts" name="btnhIdEts" value="" />
					</form>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>		
	
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

	<script>
		jQuery( document ).on( 'submit', '#formTsancion', function ( event ) {
			$.ajax( {
				url: 'server.php?action=addTSancion',
				type: 'POST',
				data: new FormData( this ),
				success: function ( data ) {
					//console.log( data );
					location.href = './tipoSanciones.php';
				},
				error: function ( data ) {
					//console.log( data );
				},
				cache: false,
				contentType: false,
				processData: false
			} );
			return false;
		} );
		function editTsancion( id ) {
			$.ajax( {
					url: 'server.php?action=getDataTsancion&id=' + id,
					type: 'POST',
					data: new FormData(  ),
					success: function ( data ) {
						//console.log(data);
						$("#nombreEts").val(data.nombre);
						$("#puntosEts").val(data.puntos);
						$("#fechasEts").val(data.fechas_suspencion);
						$("#valorEts").val(data.valor);
						$("#btnhIdEts").val(data.id);
						if(data.beta_jugador==1){
							$("#vetaEdit").prop("checked", true);
						}else{
							$("#vetaEdit").prop("checked", false);
						}
						$('#modal-edit-tsanc').modal('show');
					},
					error: function ( data ) {
						//console.log( data );
					},
					cache: false,
					contentType: false,
					processData: false
				} );
		}
		jQuery( document ).on( 'submit', '#formEditTsancion', function ( event ) {
			$.ajax( {
				url: 'server.php?action=editTSancion',
				type: 'POST',
				data: new FormData( this ),
				success: function ( data ) {
					//console.log( data );
					location.href = './tipoSanciones.php';
				},
				error: function ( data ) {
					//console.log( data );
				},
				cache: false,
				contentType: false,
				processData: false
			} );
			return false;
		} );
		
		function delTsanc( id ) {
			if ( confirm( 'Confirma Eliminar?' ) ) {
				$.ajax( {
					url: 'server.php?action=delTsanc&id=' + id,
					type: 'POST',
					data: new FormData(  ),
					success: function ( data ) {
						//console.log( data );
						location.href = './tipoSanciones.php';
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
		
		$(document).ready(function () {
		$('.dataTables-tpsancion').DataTable({
			"searching": true,
			"bLengthChange": false,
			"bInfo": false,
			"pageLength": 5
		});
	});
		
	function aMayusculas(obj,id){
		obj = obj.toUpperCase();
		document.getElementById(id).value = obj;
	}
	</script>
</body>
</html>
<?php $connect->close(); ?>