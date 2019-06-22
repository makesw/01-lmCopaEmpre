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
$resultJugadores = $connect->query( "select j.* from jugador j WHERE j.id_equipo=".$_GET[ 'idEqui' ]." and j.activo is null and j.documento
NOT IN (select documento_jugador from jugador_vetado) ORDER BY nombres asc" );
$equipo = mysqli_fetch_array($connect->query( "select * from equipo WHERE id=".$_GET[ 'idEqui' ] ));
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
			<h1 class="page-title">Administración / <a href="vetarJugadores.php">Equipos</a> / Vetar Jugadores</h1>
			<div class="row">
			
			<div class="col-lg-12">
				<div class="panel panel-default">
				<div class="panel-heading clearfix">
						<h3 class="panel-title">Listado Jugadores de Equipo: <?php echo $equipo[ 'nombre' ]; ?></h3>						
					</div>
					<div class="panel-body">				
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover dataTables-players" id="tblPlayers">
						<thead>
							<tr>
								<th>Documento</th>
								<th>Nombre</th>
								<th>Accciones</th>
							</tr>
						</thead>
						<tbody>
							<?php $iter = 1;
							while($row = mysqli_fetch_array($resultJugadores)){
							?>
							<tr>
								<td class="size-80"><?php echo $row['documento']; ?></td>	
								<td><strong><?php echo $row['nombres'].' '.$row['apellidos']; ?></strong></td>								
								<td class="size-80 text-center">
									<button class="btn btn-danger btn-outline" type="button" onclick="javaScript:vetPlayer('<?php echo $row['documento']; ?>');">Vetar</button>												
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

<div id="modal-jug" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Vetar Jugador</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="formVetarJug" method="post">
				
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="motivo">Motivo</label> 
						<div class="col-sm-10"> 
							<textarea cols="30" rows="3" required="required" name="motivo" id="motivo"></textarea>
						 </div> 
					</div>
					
					<div class="alert alert-danger" role="alert" hidden="true" id="div-msg-fail"></div>
					<div class="form-group"> 
						<div class="col-sm-offset-2 col-sm-10"> 
							<button type="submit" class="btn btn-primary">Vetar</button> 
						</div> 
					</div> 
					<input type="hidden" id="bthDocVetPlayer" name="bthDocVetPlayer" value="0" />
					<input type="hidden" id="bthValEqui" name="bthValEqui" value="<?php echo $equipo[ 'id']; ?>" />
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
</body>
</html>
<script>
$(document).ready(function () {
var table =$('.dataTables-players').DataTable({
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 10,
	"searching": true,
	"bSort" : false
});
});

jQuery( document ).on( 'submit', '#formVetarJug', function ( event ) {
	$.ajax( {
		url: 'server.php?action=vetarJug',
		type: 'POST',
		data: new FormData( this ),
		success: function ( data ) {
			//console.log( data );
			if( data.error ){
				$('#div-msg-fail').text(data.description);			
				$('#div-msg-fail').show();
				setTimeout(function(){
					$('#div-msg-fail').hide();
				},4000);
			}else{
				location.href = './jugadoresVetar.php?idEqui=<?php echo $equipo[ 'id'];?>';
			}	
		},
		error: function ( data ) {
			console.log( data );
		},
		cache: false,
		contentType: false,
		processData: false
	} );
	return false;
} );
function vetPlayer( documento ) {
	$("#bthDocVetPlayer").val(documento);
	$('#modal-jug').modal('show');
}

</script>
<?php $connect->close(); ?>