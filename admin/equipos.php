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
//las del ultimo año:
$resultEquipos = $connect->query( "select distinct e.*, CONCAT(u.nombres,' ',u.apellidos) delegado, c.nombre competicion from equipo e JOIN usuario u on e.id_usuario = u.id left join competicion c on e.id_competicion = c.id order by e.nombre asc" );
$resultUsuarios = $connect->query( "select * from usuario where activo =1 order by id asc");
$resultCompetencias = $connect->query( "select * from competicion WHERE activa=1 and (id_parent = 0 or id_parent = null ) order by nombre asc" );
$idComp=0;
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
	
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
	
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
			<h1 class="page-title">Administración / Equipos</h1>
			<div class="row">
			
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-equi">Crear</button>
								<div class="table-responsive">
									<table class="table table-bordered table-hover dataTables-comp" >
										<thead>
											<tr>
												<th>#</th>
												<th>Nombre</th>
												<th>Color</th>
												<th>Usuario</th>
												<th>Competición</th>
												<th></th>
												<!--th></th -->
											</tr>
										</thead>
										<tbody>
											<?php $iter = 1;
											while($row = mysqli_fetch_array($resultEquipos)){
											?>
											<tr>
												<td>
													<?php echo $iter; ?>
												</td>
												<td>
													<?php echo $row['nombre']; ?>
												</td>
												<td>
													<div style="text-align: center;width: 50%;background-color:<?php echo $row['color']; ?>; height: 15px;"></div>
												</td>												
												<td>
													<?php echo $row['delegado']; ?>
												</td>
												<td>
													<?php echo $row['competicion']; ?>
												</td>					
												<!--td>
												<a href="javaScript:editEqui(<?php echo $row['id']; ?>)">
												<i class="fa fa-edit"></i>
												</a>
												<a title="Borrar" href="javaScript:delEqui('<?php echo $row['id']; ?>');">
												<i class="icon-cancel icon-larger red-color"></i>
												</a>												
												</td -->
												<!--td><a href="jugadores.php?idEqui=<?php echo $row['id']; ?>">
													<button class="btn btn-success btn-outline" type="button">Jugadores</button>
													</a>
												</td -->
												<td>
													<div class="btn-group">
													  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														Acciones <span class="caret"></span>
													  </button>
													  <ul class="dropdown-menu">
														<li><a href="javaScript:editEqui(<?php echo $row['id']; ?>)">Editar</a></li>	
														 <li><a href="javaScript:viewPhoto('<?php echo $row['url_foto']; ?>');">Ver Foto</a></li>
														  <li><a href="jugadores.php?idEqui=<?php echo $row['id']; ?>">Gestionar Jugadores</a></li>
														  <li><a href="javaScript:delEqui('<?php echo $row['id']; ?>');">Borrar</a></li>
													  </ul>
												</div>
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

<div id="modal-equi" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Equipo</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="formCreateEqui" method="post"> 
						 	<div class="form-group"> 
								<label class="col-sm-2 control-label" for="nombre">Nombre</label> 
								<div class="col-sm-10"> 
									<input type="text" onblur="javascript:aMayusculas(this.value,this.id);" placeholder="Nombre" id="nombre" name="nombre" class="form-control">
								 </div> 
							</div> 							
							<div class="form-group"> 
								<label class="col-sm-2 control-label" for="puntosp">Color</label> 
								<div class="col-sm-10">
									<input type="color" id="color" name="color" class="form-control">
								 </div> 
							</div>
							<div class="form-group"> 
							<label class="col-sm-2 control-label" for="nombre">Usuario</label> 
							<div class="col-sm-10"> 
								<select required id="cmbUser" name="cmbUser" class="selectpicker" data-show-subtext="true" data-live-search="true"> 
									<?php
									while ( $row = mysqli_fetch_array( $resultUsuarios ) ) {
										echo "<option  data-subtext='" . $row[ 'id' ] ."' value='" . $row[ 'id' ] . "'>" . $row[ 'correo' ]. "</option>";
									}
									?> 
									</select>								 
								 </div> 
							</div>
							<div class="form-group"> 
							<label class="col-sm-2 control-label" for="nombre">Competición</label> 
							<div class="col-sm-10"> 
								<select class="form-control" required id="cmbComp" name="cmbComp"> 
									<?php
									while ( $rowe = mysqli_fetch_array( $resultCompetencias ) ) {
										echo "<option value='" . $rowe[ 'id' ] . "'>" . $rowe[ 'nombre' ] . "</option>";
									}
									?> 
									</select>
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

<div id="modal-viewPhoto" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Foto Equipo</h4>
			</div>
			<div class="modal-body">
				<div class="login-avatar">
					<img id="imgTeam" style="text-align: center;width: 40%;height: auto;" src="">
				</div>
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
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
</body>
</html>
<script>
$(document).ready(function () {
$('.dataTables-comp').DataTable({
	"searching": true,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 10
});
});
	
jQuery( document ).on( 'submit', '#formCreateEqui', function ( event ) {
	$.ajax( {
		url: 'server.php?action=addUpdEqui',
		type: 'POST',
		data: new FormData( this ),
		success: function ( data ) {
			//console.log( data );
			location.href = './equipos.php';
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
function editEqui( id ) { 
	$.ajax( {
			url: 'server.php?action=getDataEqui&id=' + id,
			type: 'POST',
			data: new FormData(  ),
			success: function ( data ) {
				console.log(data);
				$("#bthAction").val(2);
				$("#bthValId").val(data.id);
				$("#nombre").val(data.nombre);
				$("#color").val(data.color);
				$("#cmbComp").val(data.id_competicion);
				$("#cmbUser").val(data.id_usuario);								
				$('#modal-equi').modal('show');
			},
			error: function ( data ) {
				//console.log( data );
			},
			cache: false,
			contentType: false,
			processData: false
		} );
}
function delEqui( id ) {
	if ( confirm( 'Confirma Eliminar?' ) ) {
		$.ajax( {
			url: 'server.php?action=delEqui&id=' + id,
			type: 'POST',
			data: new FormData(  ),
			success: function ( data ) {
				//console.log( data );
				location.href = './equipos.php';
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
function aMayusculas(obj,id){
    obj = obj.toUpperCase();
    document.getElementById(id).value = obj;
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
function viewPhoto( urlfoto ) { 
	$("#imgTeam").attr("src",urlfoto);						
	$('#modal-viewPhoto').modal('show');
}
</script>
<?php $connect->close(); ?>