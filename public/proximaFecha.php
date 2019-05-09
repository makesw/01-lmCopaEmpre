<?php
require '../conexion.php';
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
$idComp=0;
if(isset($_GET[ 'idComp' ])){
	$idComp = $_GET[ 'idComp' ];
}
$arrayProxFecha = array();
//$competicion = $connect->query( "select * from competicion where id=".$idComp);
//Juegos Oficiales
$resultJuegos = $connect->query( "select j.*, e.nombre nombreEscena, g.nombre nombreGrupo, j.tipo from juego j join escenario e ON j.id_escenario = e.id and j.fecha >= NOW() join fase f on j.id_fase = f.id and f.id_competicion =".$idComp." join grupo g on j.id_grupo = g.id order by j.fecha asc, hora_inicio asc" );
//Adicionar al arreglo los juegos oficiales:
while($row = mysqli_fetch_array($resultJuegos)){
	$arrayProxFecha [] = $row;
}
//Juegos Amistosos:
$resultJuegos = $connect->query( "select j.*, e.nombre nombreEscena, '' nombreGrupo, j.tipo from juego j join escenario e ON j.id_escenario = e.id and j.fecha >= NOW() and j.tipo = 'AMISTOSO' and j.id_competicion =".$idComp." order by j.fecha asc, hora_inicio asc" );
//Adicionar al arreglo los juegos amistosos:
while($row = mysqli_fetch_array($resultJuegos)){
	$arrayProxFecha [] = $row;
}
//Juegos Aplazados:
$resultJuegos = $connect->query( "select j.*, '' nombreEscena, '' nombreGrupo, j.tipo from juego j where j.aplazado = 1 and j.id_competicion =".$idComp." order by j.fecha asc, hora_inicio asc" );
//Adicionar al arreglo los juegos aplazados:
while($row = mysqli_fetch_array($resultJuegos)){
	$arrayProxFecha [] = $row;
}

setlocale (LC_ALL,"spanish");
setlocale(LC_ALL,'es_ES');
header('Content-Type: text/html; charset=utf-8');

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

	<!-- Main container -->
	<div class="main-container">

		<!-- Main content -->
		<div class="main-content">
			<form id="formJuegos">
			<!-- h1 class="page-title">Próxima Fecha / <?php /*echo $competicion['nombre']; */?></h1 -->
			<div class="row">			
			<div class="col-lg-12">
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover dataTables-juegos" >
				<thead>
					<tr>		
						<th></th>
						<th></th>						
					</tr>
				</thead>
				<tbody>
					<?php 
					for($i=0; $i<count($arrayProxFecha); $i++){
					$teamA = null;
					$teamB = null;
					$resultA = $connect->query( "select * from equipo where id=".$arrayProxFecha[$i]['id_equipo_1']);
					$resultB = $connect->query( "select * from equipo where id=".$arrayProxFecha[$i]['id_equipo_2']);
					if( $resultA !=null && $resultB != null){
						$teamA = mysqli_fetch_array($resultA);
						$teamB = mysqli_fetch_array($resultB);
					}
					$date_a = "";
					if(!empty($arrayProxFecha[$i]['fecha'])){
						$date_a = strftime("%A, %d de %b %Y",strtotime($arrayProxFecha[$i]['fecha']));
					}
					?>
					<tr>
						<td>
							<div class="row">
								<div class="col-sm-6">													
								<ul class="list-item member-list">
									<li>
										<div class="user-detail">
											<h5><div style="margin-right: 2px;float: left;border: 2px solid #555;width: 20px;height: 20px;-moz-border-radius: 50%;-webkit-border-radius: 50%;border-radius: 50%;text-align: center;background-color:<?php echo $teamA['color']; ?>;"></div> <?php echo $arrayProxFecha[$i]['nombre1']; ?></h5>
											vs
											<h5><div style="margin-right: 2px;float: left;border: 2px solid #555;width: 20px;height: 20px;-moz-border-radius: 50%;-webkit-border-radius: 50%;border-radius: 50%;text-align: center;background-color:<?php echo $teamB['color']; ?>;"></div><?php echo $arrayProxFecha[$i]['nombre2']; ?></h5>
										</div>									
									</li>	
								</ul>
								</div>
								<div class="col-sm-6">													
								<ul class="list-item member-list">
									<?php 
									if($arrayProxFecha[$i]['aplazado']!=1){ ?>
										<li>
											<div class="user-detail">
												<strong>Fecha:</strong><?php echo utf8_encode($date_a);?><br/>
												<strong>Hora: </strong><?php echo $arrayProxFecha[$i]['hora_inicio']; ?><br/>
												<strong>Campo: </strong><?php echo $arrayProxFecha[$i]['nombreEscena']; ?><br/>
												<strong>Tipo:</strong><?php echo $arrayProxFecha[$i]['tipo']; ?>
											</div>									
										</li>
									<?php }else{ ?>
										<li>
											<div class="user-detail">
												<strong>APLAZADO</strong>
											</div>									
										</li>
									<?php } ?>
								</ul>
								</div>
							</div>
						</td>	
						<td>
							<?php if(isset($arrayProxFecha[$i+1]['id'])){
							$teamA = null;
							$teamB = null;
							$resultA = $connect->query( "select * from equipo where id=".$arrayProxFecha[$i+1]['id_equipo_1']);
							$resultB = $connect->query( "select * from equipo where id=".$arrayProxFecha[$i+1]['id_equipo_2']);
							if( $resultA !=null && $resultB != null){
								$teamA = mysqli_fetch_array($resultA);
								$teamB = mysqli_fetch_array($resultB);
							}
							$date_a = "";
							if(!empty($arrayProxFecha[$i+1]['fecha'])){
								$date_a = strftime("%A, %d de %b %Y",strtotime($arrayProxFecha[$i+1]['fecha']));
							}
							
							?>
							<div class="row">
								<div class="col-sm-6">													
								<ul class="list-item member-list">
									<li>
										<div class="user-detail">
											<h5><div style="margin-right: 2px;float: left;border: 2px solid #555;width: 20px;height: 20px;-moz-border-radius: 50%;-webkit-border-radius: 50%;border-radius: 50%;text-align: center;background-color:<?php echo $teamA['color']; ?>;"></div> <?php echo $arrayProxFecha[$i+1]['nombre1']; ?></h5>
											vs
											<h5><div style="margin-right: 2px;float: left;border: 2px solid #555;width: 20px;height: 20px;-moz-border-radius: 50%;-webkit-border-radius: 50%;border-radius: 50%;text-align: center;background-color:<?php echo $teamB['color']; ?>;"></div><?php echo $arrayProxFecha[$i+1]['nombre2']; ?></h5>
										</div>									
									</li>	
								</ul>
								</div>
								<div class="col-sm-6">													
								<ul class="list-item member-list">
									<?php 
									if($arrayProxFecha[$i+1]['aplazado']!=1){ ?>
									<li>
										<div class="user-detail">
											<strong>Fecha:</strong><?php echo utf8_encode($date_a);?><br/>
											<strong>Hora: </strong><?php echo $arrayProxFecha[$i+1]['hora_inicio']; ?><br/>
											<strong>Campo: </strong><?php echo $arrayProxFecha[$i+1]['nombreEscena']; ?><br/>
											<strong>Tipo:</strong><?php echo $arrayProxFecha[$i+1]['tipo']; ?>
										</div>																	
									</li>
									<?php }else{ ?>
										<li>
											<div class="user-detail">
												<strong>APLAZADO</strong>
											</div>									
										</li>
									<?php } ?>
								</ul>
								</div>
							</div>
							<?php } ?>
						</td>
					</tr>
					<?php 
						$i += 1; 
					}
					?>
				</tbody>
				</table>
				</div>
			</div>
			</div>
		<!-- /main content -->

		</div>
	<!-- /main container -->
	</div>
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
</body>
</html>
<script>
$(document).ready(function () {
$('.dataTables-juegos').DataTable({
	"language": {
      "emptyTable": "No data available in table--"
    },
	"searching": true,
	"bSort" : false,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 20,
	"oLanguage": {
	   "sSearch": "Buscar: ",
	   "sEmptyTable": "No existen registros publicados."
	 }
});
});
</script>
<?php $connect->close(); ?>