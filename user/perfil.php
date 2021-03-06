<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.html' );
}
require '../conexion.php';
$usuario = mysqli_fetch_array($connect->query( "select u.*, up.perfil from usuario u JOIN usuario_perfil up ON u.id = up.id_usuario  AND u.usuario_creador<>'system' AND u.id=".$_SESSION[ 'dataSession' ]['id'] ));
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
			<h1 class="page-title">Perfil</h1>
			<div class="row">
						
					<div class="panel panel-default">
						<div class="panel-heading no-border clearfix">
							<ul class="panel-tool-options"> 
								<a href="javaScript:editUser(<?php echo $usuario['id']; ?>)" title="Actualizar">
									<i class="fa fa-edit"></i>
									</a>	
							</ul> 
						</div> 
						<!-- panel body --> 
						<div class="panel-body">
							<div class="login-avatar">
								<img class="img-circle" src="<?php echo $_SESSION['dataSession']['url_foto'];?>" alt="" title="">
							</div>
							<div class="text-center">
								<h4><strong><?php echo $usuario['nombres'].' '.$usuario['apellidos']; ?></strong></h4>
							</div>
							
							<div class="carousel slide" id="carousel3">
							<div class="carousel-inner">
								<div class="item gallery active">
									<div class="row">
										<div class="col-sm-6">
											<div class="product-view">
												<div class="product-thumb">
												</div>
												<div class="product-detail">
													<strong><h5>TELÉFONO</h5></strong>
													<p><?php echo $usuario['telefono'];?></p>
												</div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="product-view">
												<div class="product-thumb">
												</div>
												<div class="product-detail">
													<strong><h5>CORREO</h5></strong>
													<p><?php echo $usuario['correo'];?></p>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="product-view">
												<div class="product-thumb">
												</div>
												<div class="product-detail">
													<strong><h5>CONTRASEÑA</h5></strong>
													<p><?php echo $usuario['password'];?></p>
												</div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="product-view">
												<div class="product-thumb">
												</div>
												<div class="product-detail">
													<strong><h5>TIPO PERFIL</h5></strong>
													<p><?php echo $usuario['perfil'];?></p>
												</div>
											</div>
										</div>
									</div>
								</div>								
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

<div id="modal-crear" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Usuario</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="formCreate" method="post"> 
						 	<div class="form-group"> 
								<label class="col-sm-2 control-label" for="nombre">Nombres</label> 
								<div class="col-sm-10"> 
									<input type="text" placeholder="Nombres" required id="nombres" name="nombres" class="form-control">
								 </div> 
							</div> 
							<div class="form-group"> 
								<label class="col-sm-2 control-label" for="nombre">Apellidos</label> 
								<div class="col-sm-10"> 
									<input type="text" placeholder="Apellidos" required id="apellidos" name="apellidos" class="form-control">
								 </div> 
							</div> 
							<div class="form-group"> 
								<label class="col-sm-2 control-label" for="nombre">Teléfono</label> 
								<div class="col-sm-10"> 
									<input type="text" placeholder="telefono" id="telefono" name="telefono" class="form-control">
								 </div> 
							</div> 
							<div class="form-group"> 
								<label class="col-sm-2 control-label"  for="nombre">Correo</label> 
								<div class="col-sm-10"> 
									<input type="text" placeholder="correo" required id="correo" name="correo" class="form-control">
								 </div> 
							</div> 
							<div class="form-group"> 
								<label class="col-sm-2 control-label" for="nombre">Contraseña</label> 
								<div class="col-sm-10"> 
									<input type="password" placeholder="password" required id="password" name="password" class="form-control">
								 </div> 
							</div>
							<div class="form-group"> 
								<label class="col-sm-2 control-label" for="nombre">Foto</label> 
								<div class="col-sm-10"> 
									<input type="file" value="" class="upload-img" id="foto" name="foto" onchange="readURL(this);"/>
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
$('.dataTables-comp').DataTable({
	"searching": false,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 5
});
});
	
jQuery( document ).on( 'submit', '#formCreate', function ( event ) {
	$.ajax( {
		url: 'server.php?action=addUpdUser',
		type: 'POST',
		data: new FormData( this ),
		success: function ( data ) {
			console.log( data );
			location.href = './perfil.php';
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
function editUser( id ) {
	$.ajax( {
			url: 'server.php?action=getDataUser&id=' + id,
			type: 'POST',
			data: new FormData(  ),
			success: function ( data ) {
				//console.log(data);
				$("#bthAction").val(2);
				$("#bthValId").val(data.id);
				$("#nombres").val(data.nombres);
				$("#apellidos").val(data.apellidos);
				$("#telefono").val(data.telefono);
				$("#correo").val(data.correo);
				$("#password").val(data.password);
				$("#cmbPerfil").val(data.perfil);
				if(data.activo == 1){
					$("#activo").prop("checked", true);
				}else{
					$("#activo").prop("checked", false);
				}				
				$('#modal-crear').modal('show');
			},
			error: function ( data ) {
				//console.log( data );
			},
			cache: false,
			contentType: false,
			processData: false
		} );
}
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