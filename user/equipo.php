<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.html' );
}
require '../conexion.php';
$equipo = mysqli_fetch_array($connect->query( "select e.*, c.nombre competicion from equipo e join competicion c on e.id_usuario = ".$_SESSION[ 'dataSession' ]['id']." and e.id_competicion = c.id order by nombre asc" ));

$resultCompetencias = $connect->query( "select * from competicion WHERE activa=1 and (id_parent = 0 or id_parent = null ) and habilitar_inscripciones = 1 order by nombre asc" );

//2. Consultar cuantos juegos tiene el equipo en la competencia actual:
$cantJuegos = 0;
if( !empty($equipo['id']) ){
	$juegos = mysqli_fetch_array($connect->query( "select count(1) total from juego j join fase f on j.id_fase = f.id and (j.id_equipo_1= ".$equipo['id']." or j.id_equipo_2=".$equipo['id'].") and (j.fecha <now() ) and f.id_competicion = ".$equipo['id_competicion']));
	$cantJuegos = $juegos['total'];
}


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
			<h1 class="page-title">Equipo</h1>
			<strong>Nota:</strong> Puedes modificar el nombre del equipo y la competición, sólo hasta antes de la primera fecha de la competencia actual.
			<div class="row">
				<div class="panel-heading no-border clearfix">
				<?php if(empty($equipo['id'])){ ?>
					<button type="button" id="btnCrear" class="btn btn-primary" data-toggle="modal" data-target="#modal-equi">Crear</button>
				<?php } ?>
					<ul class="panel-tool-options">
						<a href="javaScript:editEqui(<?php echo $equipo['id']; ?>)">
							<i class="fa fa-edit"></i>
						</a>	
					</ul> 
				</div> 
				<div class="panel-body">				
					<div class="login-avatar">
						<img style="text-align: center;width: 40%;height: auto;" src="<?php echo $equipo['url_foto']; ?>">
					</div>
					<div class="text-center">
						<h4><strong><?php echo $equipo['nombre']; ?></strong></h4>
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
													<strong><h5>COMPETICIÓN</h5></strong>
													<p><?php echo $equipo['competicion']; ?></p>
												</div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="product-view">
												<div class="product-thumb">
												</div>
												<div class="product-detail">
													<strong><h5>COLOR</h5></strong>
													<p><div style="width: 50px;height: 50px;background:<?php echo $equipo['color']; ?>;"></div></p>
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
													<a href="jugadores.php?idEqui=<?php echo $equipo['id']; ?>">
													<button class="btn btn-success btn-outline" type="button">Gestionar Jugadores</button>
													</a>
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
									<input type="text" placeholder="Nombre" onblur="javascript:aMayusculas(this.value,this.id);" id="nombre" name="nombre" class="form-control" <?php if($cantJuegos>0){ echo("disabled"); }?>>
								 </div> 
							</div> 							
							<div class="form-group"> 
								<label class="col-sm-2 control-label" for="puntosp">Color</label> 
								<div class="col-sm-10">
									<input type="color" id="color" name="color" class="form-control">
								 </div> 
							</div>							
							<div class="form-group"> 
							<label class="col-sm-2 control-label" for="nombre">Competición</label> 
							<div class="col-sm-10"> 
								<select class="form-control" required id="cmbComp" name="cmbComp" <?php if($cantJuegos>0){ echo("disabled"); }?> > 
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
							<input type="hidden" id="hidNombre" name="hidNombre" value="" />
							<input type="hidden" id="hidComp" name="hidComp" value="" />
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
jQuery( document ).on( 'submit', '#formCreateEqui', function ( event ) {
	$.ajax( {
		url: 'server.php?action=addUpdEqui',
		type: 'POST',
		data: new FormData( this ),
		success: function ( data ) {
			console.log( data );
			location.href = './equipo.php';
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
			data: new FormData( this ),
			success: function ( data ) {
				//console.log(data);
				$("#bthAction").val(2);
				$("#bthValId").val(data.id);
				$("#nombre").val(data.nombre);
				$("#color").val(data.color);
				$("#hidNombre").val(data.nombre);
				$("#hidComp").val(data.id_competicion);
				$("#cmbComp").val(data.id_competicion);
								
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
			data: new FormData( this ),
			success: function ( data ) {
				//console.log( data );
				location.href = './equipo.php';
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
</script>
<?php $connect->close(); ?>