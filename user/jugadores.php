<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
    header( 'Location: ../index.html' );
}
require '../conexion.php';
$createPlayer = true;
//consultar datos de la competicion actual:
$competencia = mysqli_fetch_array($connect->query( "select c.* from competicion c join equipo e on c.id = e.id_competicion and e.id = ".$_GET[ 'idEqui' ]." limit 1"));
//consultar Jugadores del equipo:
$resultJugadores = $connect->query( "select * from jugador WHERE id_equipo=".$_GET[ 'idEqui' ]." and activo is null ORDER BY nombres asc" );
//Consultar datos de equipo
$equipo = mysqli_fetch_array($connect->query( "select * from equipo WHERE id=".$_GET[ 'idEqui' ] ));
//Validación seguridad, consultar equipo del usuario en sesion:
$equipoUserSession = mysqli_fetch_array($connect->query( "select e.*, c.nombre competicion from equipo e join competicion c on e.id_usuario = ".$_SESSION[ 'dataSession' ]['id']." and e.id_competicion = c.id order by nombre asc" ));
if( $equipoUserSession!= null && ($equipoUserSession['id']!=$equipo['id'])){
    header( 'Location: ../salir.php' );
}
//1. validar catidad máxima jugadores por lo definido en competencia o en efecto máximo 25:
$totalJugadores = mysqli_fetch_array($connect->query( "select count(1) total from jugador WHERE id_equipo=".$_GET[ 'idEqui' ]." and activo is null" ));
$maxJug = 25;
if($competencia['nummxjug']!=null && $competencia['nummxjug']>0){
    if( $totalJugadores['total']>=$competencia['nummxjug'] ){
        $createPlayer = false;
        $maxJug = $competencia['nummxjug'];
    }
}else{
    if( $totalJugadores['total']>=25 ){
        $createPlayer = false;
    }
}

//2. Consultar cuantos juegos tiene el equipo en la competencia actual:
$juegos = mysqli_fetch_array($connect->query( "select count(1) total from juego j join fase f on j.id_fase = f.id and (j.id_equipo_1= ".$equipo['id']." or j.id_equipo_2=".$equipo['id'].") and (j.fecha <now() ) and j.informado=1 and f.id_competicion = ".$equipo['id_competicion']));

//3. Solo hasta la fecha 4 se permite adicionar jugadores
if($juegos['total'] >= 4){
    $createPlayer = false;
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
			<h1 class="page-title"><a href="equipo.php">Equipo</a> / Jugadores / Equipo: <?php echo $equipo[ 'nombre' ]; ?></h1>
			<div class="row">			
			<div class="col-lg-12">
				<div class="panel panel-minimal">
					<div>
					<strong>Notas:</strong>
					<br>*La cantidad máxima de jugadores que puedes iscribir es: <strong><?php echo $maxJug; ?></strong><br/>
					*Después de la fecha <strong>4</strong> no se puede crear más jugadores
					<br/>*Después de la primera fecha no se puede editar el documento de un jugador
					<br/>	
					<button <?php if(!$createPlayer){ ?>disabled<?php } ?> type="button" class="btn btn-primary" onClick="javascript:createPlayer();">Crear</button><br/>
					<div class="table-responsive">
					<table id="playersTable" class="table table-users table-bordered table-hover dataTables-comp">
						<thead>
							<tr>
								<th></th>
								<th>Foto</th>
								<th>Documento</th>
								<th>Nombre</th>	
								<th>Número</th>
								<th>Teléfono</th>
								<th>Correo</th>
								<th>Goles</th>
								<th>Delegado</th>
								<th>Accciones</th>															
							</tr>
						</thead>
						<tbody>
							<?php $iter = 1;
							while($row = mysqli_fetch_array($resultJugadores)){
							
							//consultar Goles:
							$goles = mysqli_fetch_array($connect->query( "select COALESCE(sum(g.valor),0)  goles from gol g join jugador j on g.id_jugador = j.id and j.id = ".$row['id']." join equipo e on j.id_equipo = e.id and e.id =".$row['id_equipo']." join juego jueg on g.id_juego = jueg.id join fase f on jueg.id_fase = f.id join competicion c on f.id_competicion = c.id and c.id = e.id_competicion" ));
							
							//Validar jugador vetado:
							$vetado = mysqli_fetch_array($connect->query( "select count(1) total from jugador_vetado WHERE documento_jugador='".$row[ 'documento' ]."'"));
								
							?>
							<tr <?php if($vetado['total']>0){?> style="background-color: crimson;" title="Jugador Jugador Vetado" <?php } ?>>
								<td class="size-80"><?php echo $iter; ?></td>
								<td class="size-80"><img title="" alt="" src="<?php echo $row['url_foto'];?>" class="avatar img-circle"></td>
								<td class="size-80"><?php echo $row['documento']; ?></td>	
								<td><strong><?php echo $row['nombres'].' '.$row['apellidos']; ?></strong></td>
								<td><?php echo $row['numero'];?></td>
								<td><?php echo $row['telefono'];?></td>
								<td class="text-center"><?php echo $row['correo'];?></td>
								<td class="text-center"><?php echo $goles['goles'];?></td>
								<td class="size-80 text-center">
									<span class="badge badge-bordered"><?php echo $row['es_delegado']==1?'SI':'';?></span>
								</td>
								<td class="size-80 text-center">									
									<?php if($vetado['total']<1){ ?>
									<a href="javaScript:editJug(<?php echo $row['id']; ?>)">
									<i class="fa fa-edit"></i>
									</a>									
									<a title="Borrar" href="javaScript:delPlayer('<?php echo $row['id']; ?>');">
									<i class="icon-cancel icon-larger red-color"></i>
									</a>		
									<?php } ?>
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
							<input type="number" placeholder="Documento" id="documento" name="documento" class="form-control"  required>
							<input type="hidden" id="docHiden" name="docHiden">
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
					<div class="alert alert-danger" role="alert" hidden="true" id="div-msg-fail"></div>
					<div class="form-group"> 
						<div class="col-sm-offset-2 col-sm-10"> 
							<button type="submit" class="btn btn-primary">Guardar</button> 
						</div> 
					</div> 
					<input type="hidden" id="bthAction" name="bthAction" value="1" />
					<input type="hidden" id="bthValId" name="bthValId" value="" />
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
$(document).ready(function() {
    $('#playersTable').DataTable( {
    	"searching": true,
    	"bLengthChange": false,
    	"bInfo": false,
    	"pageLength": 10,
    	dom: '<"html5buttons" B>lTfgitp',
    	buttons: [				
			{
				extend: 'excelHtml5',
				title: '<?php echo 'Jugadores-'.$equipo['nombre']; ?>',
				exportOptions: {
					columns: [ 2, 3, 4, 5, 6, 7, 8 ]
				}
			},
			{
				extend: 'pdfHtml5',
				title: '<?php echo 'Jugadores-'.$equipo['nombre']; ?>',
				exportOptions: {
					columns: [ 2, 3, 4, 5, 6, 7, 8 ]
				}
			}
		]
    } );
} );
	
jQuery( document ).on( 'submit', '#formCreateJug', function ( event ) {
	$.ajax( {
		url: 'server.php?action=addUpdJug',
		type: 'POST',
		data: new FormData( this ),
		success: function ( data ) {
			console.log( data );
			if( data.error ){
				$('#div-msg-fail').text(data.description);			
				$('#div-msg-fail').show();
				setTimeout(function(){
					$('#div-msg-fail').hide();
				},4000);
			}else{
				location.href = './jugadores.php?idEqui=<?php echo $equipo[ 'id'];?>';
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
function editJug( id ) {
	$.ajax( {
			url: 'server.php?action=getDataJug&id=' + id,
			type: 'POST',
			data: new FormData( this ),
			success: function ( data ) {
				//console.log(data);
				$("#bthAction").val(2);
				<?php if($juegos['total'] > 0) {?> $('#documento').prop('readonly', true);<?php } ?>
				$("#bthValId").val(data.id);
				$("#bthValEqui").val(<?php echo $equipo[ 'id'];?>);
				$("#documento").val(data.documento);
				$("#docHiden").val(data.telefono);
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
function delPlayer( id ) {
	if ( confirm( 'Confirma Eliminar?' ) ) {
		$.ajax( {
			url: 'server.php?action=delPlayer&id=' + id,
			type: 'POST',
			data: new FormData( this ),
			success: function ( data ) {
				//console.log( data );
				location.href = './jugadores.php?idEqui=<?php echo $equipo[ 'id'];?>';
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
function readURL( input ) {
	if ( input.files && input.files[ 0 ] ) {
		var typeFile = input.files[ 0 ].type;
		if( typeFile!="image/jpeg" && typeFile!="image/jpg" && typeFile!="image/png" ){
			alert("Tipo de imagen inválido");
			$("#foto").val('');
		}
	}
}
function createPlayer() {
	$('#documento').prop('readonly', false);
	$("#bthValEqui").val('');
	$("#documento").val('');
	$("#docHiden").val('');
	$("#nombres").val('');
	$("#apellidos").val('');
	$("#numero").val('');
	$("#telefono").val('');
	$("#correo").val('');				
	$("#delegado").prop("checked", false);
	$("#bthValEqui").val(<?php echo $equipo[ 'id'];?>);
					
	$('#modal-jug').modal('show');
}
</script>
<?php $connect->close(); ?>