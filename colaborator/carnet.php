<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
    header( 'Location: ../index.html' );
}else{
    if($_SESSION[ 'dataSession' ]['perfil'] != 'colaborador'){
        header( 'Location: ../salir.php' );
    }
}
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.html' );
}
require '../conexion.php';
$resultJugadores = $connect->query( "select j.id, j.documento, concat(j.nombres,'',j.apellidos) nombreJugador, numero, j.url_foto, e.nombre equipo, c.nombre competicion from jugador j join equipo e on j.id_equipo = e.id join inscripcion i on i.id_equipo = e.id join competicion c on i.id_competicion = c.id and c.activa =1 order by nombreJugador asc" );
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Mouldifi - A fully responsive, HTML5 based admin theme">
	<meta name="keywords" content="Responsive, HTML5, admin theme, business, professional, Mouldifi, web design, CSS3">
	<title>Carnets</title>
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
			<h1 class="page-title">Administración / Carnets</h1>
			<div class="row">
			
			<div class="col-lg-12">
				<div class="panel panel-default">
				<div class="panel-heading clearfix">
						<h3 class="panel-title">Listado Jugadores</h3>						
					</div>
					<div class="panel-body">
					<div class="table-responsive">
					<table class="table table-users table-bordered table-hover dataTables-jugadores" >
						<thead>
							<tr>
								<th>Foto</th>
								<th>Documento</th>
								<th>Nombre</th>	
								<th>Número</th>
								<th>Equipo</th>
								<th>competición</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
							while($row = mysqli_fetch_array($resultJugadores)){
							?>
							<tr>
								<td class="size-80"><img title="" alt="" src="<?php echo $row['url_foto'];?>" class="avatar img-circle"></td>
								<td class="size-80"><?php echo $row['documento']; ?></td>	
								<td><strong><?php echo $row['nombreJugador']; ?></strong></td>
								<td><?php echo $row['numero'];?></td>
								<td><?php echo $row['equipo'];?></td>
								<td><?php echo $row['competicion'];?></td>
								<td class="size-80 text-center">									
									
									<div class="btn-group">
										  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Acciones <span class="caret"></span>
										  </button>
										  <ul class="dropdown-menu">
											<li><a href="javaScript:editJug(<?php echo $row['id']; ?>);">Editar Jugador</a></li>
											<li><a href="templateCarnet.php?idPlayer=<?php echo $row['id'];?>">Imprimir Carnet</a></li>
										  </ul>
									</div>
									
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
<div id="modal-jug" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Jugador</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="formCreateJug" method="post">
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">Foto</label> 
						<div class="col-sm-10"> 
							<input type="file" value="" class="upload-img" id="foto" name="foto" onchange="readURL(this);"/>
						 </div> 
					</div> 
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">documento</label> 
						<div class="col-sm-10"> 
							<input type="text" placeholder="Documento" id="documento" name="documento" class="form-control"  required>
						 </div> 
					</div>
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">Nombres</label> 
						<div class="col-sm-10"> 
							<input type="text" placeholder="Nombres" id="nombres" name="nombres" class="form-control"  required>
						 </div> 
					</div>
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">Apellidos</label> 
						<div class="col-sm-10"> 
							<input type="text" placeholder="Apellidos" id="apellidos" name="apellidos" class="form-control"  required>
						 </div> 
					</div> 
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">#</label> 
						<div class="col-sm-10"> 
							<input type="text" placeholder="Numero" id="numero" name="numero" class="form-control"  >
						 </div> 
					</div>
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">Teléfono</label> 
						<div class="col-sm-10"> 
							<input type="text" placeholder="Teléfono" id="telefono" name="telefono" class="form-control"  >
						 </div> 
					</div>
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">Correo</label> 
						<div class="col-sm-10"> 
							<input type="email" placeholder="Teléfono" id="correo" name="correo" class="form-control"  >
						 </div> 
					</div>
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="activa">Delegado</label> 
						<div class="col-sm-10">
							<input type="checkbox" id="delegado" name="delegado">
						 </div> 
					</div>           																	
					<div class="form-group"> 
						<div class="col-sm-offset-2 col-sm-10"> 
							<button type="submit" class="btn btn-primary">Guardar</button> 
						</div> 
					</div> 
					<input type="hidden" id="bthAction" name="bthAction" value="1" />
					<input type="hidden" id="bthValId" name="bthValId" value="" />
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
	$('.dataTables-jugadores').DataTable({
		"bLengthChange": false,
		"bInfo": false,
		"pageLength": 10,
		"searching": true,
		"bSort" : true

	});
});
function editJug( id ) {
	$.ajax( {
			url: 'server.php?action=getDataJug&id=' + id,
			type: 'POST',
			data: new FormData( this ),
			success: function ( data ) {
				//console.log(data);
				$("#bthAction").val(2);
				$("#bthValId").val(data.id);
				$("#documento").val(data.documento);
				$("#nombres").val(data.nombres);
				$("#apellidos").val(data.apellidos);
				$("#numero").val(data.numero);
				$("#telefono").val(data.telefono);
				$("#correo").val(data.correo);				
				if(data.es_delegado == 1){
					$("#delegado").prop("checked", true);
				}else{
					$("#delegado").prop("checked", false);
				}				
				$('#modal-jug').modal('show');
			},
			error: function ( data ) {
				//console.log( data );
			},
			cache: false,
			contentType: false,
			processData: false
		} );
}	
jQuery( document ).on( 'submit', '#formCreateJug', function ( event ) {
	$.ajax( {
		url: 'server.php?action=addUpdJug',
		type: 'POST',
		data: new FormData( this ),
		success: function ( data ) {
			//console.log( data );
			location.href = './carnet.php';
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
function readURL( input ) {
	if ( input.files && input.files[ 0 ] ) {
		var typeFile = input.files[ 0 ].type;
		if( typeFile!="image/jpeg" && typeFile!="image/jpg" && typeFile!="image/png" ){
			alert("Tipo de imagen inválido");
			$("#foto").val('');
		}
	}
}
</script>
<?php $connect->close(); ?>