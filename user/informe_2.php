<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.html' );
}
require '../conexion.php';
$idJuego = 0;
$idFase = 0;
$btnGoles = 0;
if ( isset( $_GET[ 'idJuego' ] ) ) {
	$idJuego = $_GET[ 'idJuego' ];
}
if ( isset( $_GET[ 'idFase' ] ) ) {
	$idFase = $_GET[ 'idFase' ];
}
if ( isset( $_GET[ 'btnGoles' ] ) ) {
	$btnGoles = $_GET[ 'btnGoles' ];
}

$juego = mysqli_fetch_array( $connect->query( "select j.*, e.nombre nombreEscena, s.nombre sede from juego j JOIN escenario e ON j.id_escenario = e.id AND j.id =" . $idJuego . " AND j.fecha is not null JOIN sede s ON e.id_sede = s.id" ) );
$resultJugadores1 = $connect->query( "select ju.*, a.id_jugador asistencia  from juego j JOIN equipo e ON j.id_equipo_1 = e.id AND j.fecha is not null AND j.id = " . $idJuego . " JOIN jugador ju ON e.id = ju.id_equipo and ju.activo is NULL LEFT JOIN asistencia a ON a.id_juego = j.id AND a.id_jugador = ju.id ORDER BY nombres ASC" );
$resultJugadores2 = $connect->query( "select ju.*, a.id_jugador asistencia  from juego j JOIN equipo e ON j.id_equipo_2 = e.id AND j.fecha is not null AND j.id = " . $idJuego . " JOIN jugador ju ON e.id = ju.id_equipo and ju.activo is NULL LEFT JOIN asistencia a ON a.id_juego = j.id AND a.id_jugador = ju.id ORDER BY nombres ASC" );
$resultGoles1 = $connect->query( "select concat(j.nombres,' ',j.apellidos) nombres, g.* from gol g JOIN jugador j ON g.id_jugador = j.id AND g.id_juego =".$idJuego." JOIN juego jueg ON g.id_juego = jueg.id AND jueg.id_equipo_1 = j.id_equipo ORDER BY g.minuto desc,g.id desc");
$resultGoles2 = $connect->query( "select concat(j.nombres,' ',j.apellidos) nombres, g.* from gol g JOIN jugador j ON g.id_jugador = j.id AND g.id_juego =".$idJuego." JOIN juego jueg ON g.id_juego = jueg.id AND jueg.id_equipo_2 = j.id_equipo ORDER BY g.minuto desc, g.id desc");
$resultSanciones1 = $connect->query( "select concat(j.nombres,' ',j.apellidos) nombres, ts.*, s.minuto, s.id id_san from sancion s JOIN jugador j ON s.id_jugador = j.id AND s.id_juego =".$idJuego." JOIN juego jueg ON s.id_juego = jueg.id AND jueg.id_equipo_1 = j.id_equipo JOIN tipo_sancion ts ON s.id_tipo_sancion = ts.id ORDER BY s.minuto, s.id desc");
$resultSanciones2 = $connect->query( "select concat(j.nombres,' ',j.apellidos) nombres, ts.*, s.minuto, s.id id_san from sancion s JOIN jugador j ON s.id_jugador = j.id AND s.id_juego =".$idJuego." JOIN juego jueg ON s.id_juego = jueg.id AND jueg.id_equipo_2 = j.id_equipo JOIN tipo_sancion ts ON s.id_tipo_sancion = ts.id ORDER BY s.minuto, s.id desc");

$goles1 = mysqli_fetch_array( $connect->query( "select SUM(g.valor) as totalGoles from gol g JOIN jugador j ON g.id_jugador = j.id AND g.id_juego =".$idJuego." JOIN juego jueg ON g.id_juego = jueg.id AND jueg.id_equipo_1 = j.id_equipo")); 

$goles2 = mysqli_fetch_array( $connect->query( "select SUM(g.valor) as totalGoles from gol g JOIN jugador j ON g.id_jugador = j.id AND g.id_juego =".$idJuego." JOIN juego jueg ON g.id_juego = jueg.id AND jueg.id_equipo_2 = j.id_equipo") ); 


$resultTSanciones = $connect->query( "select * from tipo_sancion order by nombre asc");

$date = new DateTime( $juego[ 'fecha' ] );
$fecha = $date->format( 'Y-m-d' );

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
				<h1 class="page-title">Consultas / Informe de Juego</h1>
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading clearfix">
								<h3 class="panel-title">
									<?php echo($juego['nombre1'].' Vs '.$juego['nombre2']) ?>
								</h3>
							</div>
							<div class="panel-body">
								<div class="col-lg-12">
									<div class="form-inline">
										<div class="form-group">
											 | <strong>Hora:</strong> <?php echo($juego['hora_inicio']); ?>
										</div>
										<div class="form-group">
											 | <strong>Fecha:</strong> <?php echo($fecha); ?>
										</div>
										<div class="form-group">
											 | <strong>Jornada:</strong> <?php echo($juego['jornada']); ?>
										</div>
										<div class="form-group">
											 | <strong>Lugar:</strong> <?php echo($juego['sede']); ?>
										</div>
										<div class="form-group">
											 | <strong>Escenario:</strong> <?php echo($juego['nombreEscena']); ?> |
										</div>
									</div>
								</div>
								<br/>
								<div class="col-lg-12">
								<strong><br/>Nota: </strong>Jugadores marcados con Rojo = Vetado, Jugador Marcado en azul = Sancionado
								</div>
								<br/>															
								<div class="col-lg-6">
									<div class="table-responsive">
										<table id="table_juga1" class="table table-striped table-bordered table-hover dataTables-juga1">
											<thead>
												<tr>
													<th>#</th>
													<th>Documento</th>
													<th>Nombres</th>
													<th>Asistencia</th>
												</tr>
											</thead>
											<tbody>
												<?php
												while ( $row1 = mysqli_fetch_array( $resultJugadores1 ) ) {
													$jugador_sancionado = false;
													//consultar jugador vetado:
													$jugador_vetado = mysqli_fetch_array( $connect->query( "select count(1) total from jugador_vetado where documento_jugador = ".$row1['documento'] ));
													if( $jugador_vetado['total']<1){
														//consultar jugador vetado:
														$jugador_vetado = mysqli_fetch_array( $connect->query( "select count(1) total from jugador_vetado where documento_jugador = ".$row1['documento'] ));
														//validar jugador sancion:
														$sancion = mysqli_fetch_array( $connect->query( "select s.*, ts.veta_jugador,ts.fechas_suspencion from sancion s join tipo_sancion ts ON s.id_jugador = ".$row1['id']." and s.id_tipo_sancion = ts.id join juego j on s.id_juego join fase f on j.id_fase = f.id and f.id = ".$juego['id_fase']." and f.activa =1 join competicion c on f.id_competicion = c.id and c.activa = 1 order by s.fecha desc limit 1") );	
														if( $sancion['fechas_suspencion']>0 ){
															$juegos = mysqli_fetch_array( $connect->query( "select count(1) total from juego j join fase f on j.id_fase = f.id and f.id = ".$juego['id_fase']." and f.activa = 1 and (j.id_equipo_1 = ".$row1['id_equipo']." OR j.id_equipo_2 = ".$row1['id_equipo'].") and fecha BETWEEN '".$sancion['fecha']."' AND now() and j.informado = 1 join competicion c on f.id_competicion = c.id and c.activa =1") );
															if($sancion['fechas_suspencion']>$juegos['total']){
																$jugador_sancionado = true;
															}
														}
													}
													
												?>
												<tr <?php if($jugador_vetado['total'] > 0){echo 'style="background-color: #ffb1be;"';}elseif($jugador_sancionado){echo 'style="background-color: #c7eaf9;"';} ?>>
													<td>
														<?php echo $row1['numero']; ?>
													</td>
													<td>
														<?php echo $row1['documento']; ?>
													</td>
													<td>
														<?php echo $row1['nombres'].' '.$row1['apellidos']; ?>
													</td>
													<td>
														<?php echo !empty($row1['asistencia'])?'<i class="fa fa-check"></i>':''; ?>
													</td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="table-responsive">
										<table id="table_juga2" class="table table-striped table-bordered table-hover dataTables-juga2">
											<thead>
												<tr> 
													<th>#</th>
													<th>Documento</th>
													<th>Nombres</th>
													<th>Asistencia</th>
												</tr>
											</thead>
											<tbody>
												<?php
												while ( $row2 = mysqli_fetch_array( $resultJugadores2 ) ) {
												$jugador_sancionado = false;
												//consultar jugador vetado:
												$jugador_vetado = mysqli_fetch_array( $connect->query( "select count(1) total from jugador_vetado where documento_jugador = ".$row2['documento'] ));
												if( $jugador_vetado['total']<1){
													//consultar jugador vetado:
													$jugador_vetado = mysqli_fetch_array( $connect->query( "select count(1) total from jugador_vetado where documento_jugador = ".$row2['documento'] ));
													//validar jugador sancion:
													$sancion = mysqli_fetch_array( $connect->query( "select s.*, ts.veta_jugador,ts.fechas_suspencion from sancion s join tipo_sancion ts ON s.id_jugador = ".$row2['id']." and s.id_tipo_sancion = ts.id join juego j on s.id_juego join fase f on j.id_fase = f.id and f.id = ".$juego['id_fase']." and f.activa =1 join competicion c on f.id_competicion = c.id and c.activa = 1 order by s.fecha desc limit 1") );	
													if( $sancion['fechas_suspencion']>0 ){
														$juegos = mysqli_fetch_array( $connect->query( "select count(1) total from juego j join fase f on j.id_fase = f.id and f.id = ".$juego['id_fase']." and f.activa = 1 and (j.id_equipo_1 = ".$row2['id_equipo']." OR j.id_equipo_2 = ".$row2['id_equipo'].") and fecha BETWEEN '".$sancion['fecha']."' AND now() and j.informado = 1 join competicion c on f.id_competicion = c.id and c.activa =1") );
														if($sancion['fechas_suspencion']>$juegos['total']){
															$jugador_sancionado = true;
														}
													}
													
												}
												
												?>
												<tr <?php if($jugador_vetado['total'] > 0){echo 'style="background-color: #ffb1be;"';}elseif($jugador_sancionado){echo 'style="background-color: #c7eaf9;"';} ?>>
													<td>
														<?php echo $row2['numero']; ?>
													</td>
													<td>
														<?php echo $row2['documento']; ?>
													</td>
													<td>
														<?php echo $row2['nombres'].' '.$row2['apellidos']; ?>
													</td>
													<td>
														<?php echo !empty($row2['asistencia'])?'<i class="fa fa-check"></i>':''; ?>
													</td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="panel minimal panel-default">
										<div class="panel-heading clearfix">
											<div class="panel-title">Resultado</div>
										</div>
										<!-- panel body -->
										<div class="panel-body">
											<div class="row col-with-divider">
												<div class="col-xs-6 text-center stack-order">
													<h1 class="no-margins"><strong><?php echo($goles1['totalGoles']); ?></strong></h1>
												</div>
												<div class="col-xs-6 text-center stack-order">
													<h1 class="no-margins"><strong><?php echo($goles2['totalGoles']); ?></strong></h1>
												</div>
												
											</div>
											<div class="row col-with-divider">
												<div class="col-xs-6 text-center stack-order">
													<div class="table-responsive">
														<table class="table">		
															<tbody>
																<?php
																while ( $row1 = mysqli_fetch_array( $resultGoles1 ) ) {
																?> 
																<tr> 
																	<td><?php echo($row1['valor']); ?></td> 
																	<td><?php echo($row1['nombres']); ?></td> 
																	<td><?php echo($row1['minuto']); ?>'</td>
																</tr>
																<?php } ?>						
															</tbody> 
														</table>
													</div>
												</div>
												<div class="col-xs-6 text-center stack-order">
													<div class="table-responsive">
														<table class="table">		
															<tbody>
																<?php
																while ( $row2 = mysqli_fetch_array( $resultGoles2 ) ) {
																?> 
																<tr> 
																	<td><?php echo($row2['valor']); ?></td>
																	<td><?php echo($row2['nombres']); ?></td> 
																	<td><?php echo($row2['minuto']); ?>'</td>
																</tr>
																<?php } ?>						
															</tbody> 
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!--<div class="col-lg-12">
									<div class="form-group">										
										<div id="btnInfGoles" hidden="true">
										<button type="button" class="btn btn-primary" onClick="javascript:fnUpdateGoles();">Informar Goles</button>
										</div>
									</div>
								</div> -->								
								<div class="col-lg-12">
									<div class="panel minimal panel-default">
										<div class="panel-heading clearfix">
											<div class="panel-title">Sanciones</div>
										</div>
										<!-- panel body -->
										<div class="panel-body">										
											<div class="row col-with-divider">
												<div class="col-xs-6 text-center stack-order">
													<div class="table-responsive">
														<table class="table">		
															<tbody>
																<?php
																while ( $row1 = mysqli_fetch_array( $resultSanciones1 ) ) {
																?> 
																<tr> 
																	<td><?php echo($row1['nombres']);?></td> 
																	<td><?php echo($row1['nombre']);?></td>
																	<td><?php echo($row1['minuto']);?>'</td>
																</tr>
																<?php } ?>						
															</tbody> 
														</table>
													</div>
												</div>
												<div class="col-xs-6 text-center stack-order">
													<div class="table-responsive">
														<table class="table">		
															<tbody>
																<?php
																while ( $row2 = mysqli_fetch_array( $resultSanciones2 ) ) {
																?> 
																<tr> 
																	<td><?php echo($row2['nombres']); ?></td> 
																	<td><?php echo($row2['nombre']); ?></td>
																	<td><?php echo($row2['minuto']); ?>'</td>
																</tr>
																<?php } ?>						
															</tbody> 
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<form id="formInforme" method="post">
								<div class="col-lg-12"><br/>
									<div class="form-group">
										<label for="emailaddress">Informe</label>
										<textarea class="form-control" placeholder="Informe" name="informe" rows="4"><?php echo($juego['informe']); ?></textarea>
									</div>
								</div>							
								</form>
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
	<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
</body>
</html>
<script>
$( document ).ready( function () {
$( '.dataTables-juga1' ).DataTable( {
	"searching": false,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 10
} );
$( '.dataTables-juga2' ).DataTable( {
	"searching": false,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 10
} );
} );
</script>
<?php $connect->close(); ?>