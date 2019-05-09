<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.html' );
}
require '../conexion.php';
//consultar jugadores de equipo asignado:
$jugadores = $connect->query( "select sum(valor) totalAbonado,e.id_competicion,e.id idEquipo, e.nombre,j.id idPlayer,concat(j.nombres,' ',j.apellidos) nombreJug, ppj.valor from jugador j join equipo e on j.id_equipo = e.id and e.id_usuario = ".$_SESSION[ 'dataSession' ]['id']." and j.activo is null left join pago_jugador ppj on j.id = ppj.id_jugador group by idPlayer order by totalAbonado desc, nombreJug asc" );
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
			<h1 class="page-title">Gestión / Pagos Por Jugador</h1>
			<div class="row">			
			<div class="col-lg-12">
				<div class="panel panel-minimal">
					<div class="panel-body">
						<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover dataTables-pagosPlayer" >
					<thead>
						<tr>
							<th>Nombre Jugador</th>
							<th>Abonado $</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php $toalCount = 0;
						while($row = mysqli_fetch_array($jugadores)){	
						?>	
						<tr>	
							<td><?php echo $row['nombreJug']; ?></td>							
							<td><?php echo $row['totalAbonado']; ?></td>
							<td><button type="button" class="btn btn-success btn-outline" data-toggle="modal" onClick="javascript:fnAddPayPlayer(<?php echo $row['idPlayer']; ?>,<?php echo $row['id_competicion']; ?>);">Abonar</button>
							<button class="btn btn-info btn-outline" type="button" onClick="javascript:fnViewPaysPlayer(<?php echo $row['idPlayer']; ?>,<?php echo $row['id_competicion']; ?>,'<?php echo $row['nombreJug']; ?>');">Ver Detalle</button>
							</td>
						</tr>
						<?php  $toalCount+=$row['totalAbonado']; } ?>
					</tbody>
					<tfoot>
						<tr>
							<th></th>
							<th>Total: $ <?php echo $toalCount; ?></th>
							<th></th>
						</tr>
					</tfoot>
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

<div id="modal-pag" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Registrar Abono Jugador</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="formRegistrarAbono" method="post">					
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="valor">Valor$</label> 
						<div class="col-sm-10"> 
							<input type="number" placeholder="$valor" id="valor" name="valor" class="form-control"  required>
						</div> 
					</div>
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">Fecha</label> 
						<div class="col-sm-10"> 
							<input type="date" name="fecha" id="fecha" required>
						 </div> 
					</div>																		
					<div class="form-group"> 
						<div class="col-sm-offset-2 col-sm-10"> 
							<button type="submit" class="btn btn-primary">Registrar</button> 
						</div> 
					</div>
					<input type="hidden" id="hdIdComp" name="hdIdComp" value="" />
					<input type="hidden" id="hdIdPlayer" name="hdIdPlayer" value="" />
				</form>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<div id="modal-listPaysplayer" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><div id="idModalTitle"></div></h4>
			</div>
			<div class="modal-body">
				<div class="panel-body">
					<div id="divListPayPaysPlayer"></div>						
				</div>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
</body>
</html>
<script>
$(document).ready(function () {
$('.dataTables-pagosPlayer').DataTable({
	"searching": true,
	"bSort" : false,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 20,
	"oLanguage": {
	   "sSearch": "Buscar: "
	 },
	dom: '<"html5buttons" B>lTfgitp',
		buttons: [				
			{
				extend: 'excelHtml5', footer: true, title: 'PagosPorJugador',
				exportOptions: {
					columns: ':visible'
				}
			},
			{
				extend: 'pdfHtml5', footer: true, title: 'PagosPorJugador',
				exportOptions: {
					columns: ':visible'
				}
			}
		]
});
});	
function fnAddPayPlayer( idPlayer, idComp ) {
	$("#hdIdComp").val(idComp);
	$("#hdIdPlayer").val(idPlayer);
	$('#modal-pag').modal('show');
}
function fnViewPaysPlayer( idPlayer, idComp, namePlayer ) {
	$("#divListPayPaysPlayer").load('ajaxListPagosPlayer.php?idPlayer='+idPlayer+'&idComp='+idComp);	
	$('#idModalTitle').text('Ver Pagos de Jugador: '+namePlayer);
	$('#modal-listPaysplayer').modal('show');
}
jQuery( document ).on( 'submit', '#formRegistrarAbono', function ( event ) {
	$.ajax( {
		url: 'server.php?action=addAbonoPlayer',
		type: 'POST',
		data: new FormData( this ),
		success: function ( data ) {
			//console.log( data );
			location.href = './pagosPorJugador.php';
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
function delPayPlayer( id ) {
	if ( confirm( 'Confirma Eliminar?' ) ) {
		$.ajax( {
			url: 'server.php?action=delPayPlayer&id='+id,
			type: 'POST',
			data: new FormData( this ),
			success: function ( data ) {
				//console.log( data );
				location.reload();
			},
			error: function ( data ) {
				console.log( data );
			},
			cache: false,
			contentType: false,
			processData: false
		} );
	}
}
</script>
<?php $connect->close(); ?>