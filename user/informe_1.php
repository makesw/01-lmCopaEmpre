<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.html' );
}
require '../conexion.php';
$resultCompetencias = $connect->query( "select * from competicion WHERE activa=1  order by nombre asc" );
$resultEscenarios = $connect->query( "select concat(e.nombre,' - ',s.nombre) escena, e.id idEscena from escenario e JOIN sede s ON e.id_sede = s.id order by e.nombre asc" );
$idComp=0;
$idFase=0;
if(isset($_GET[ 'idComp' ])){
	$idComp = $_GET[ 'idComp' ];
}
if(isset($_GET[ 'idFase' ])){
	$idFase = $_GET[ 'idFase' ];
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
			<form id="formJuegos">
			<h1 class="page-title">Consultas / Informe de Juego</h1>
			<div class="row">			
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
						  <div class="form-group">
							<label for="emailaddress">Competición</label>
							<select class="form-control" required id="cmbComp" name="cmbComp"> 
								<option value="">Seleccione una competencia</option><?php
								while ( $row = mysqli_fetch_array( $resultCompetencias ) ) {
									echo "<option value='" . $row[ 'id' ] . "'>" . $row[ 'nombre' ] . "</option>";
								}
								?> 
							</select>
						  </div>
						  <div class="form-group">
							<label for="password">Fase</label>
							<select class="form-control" required id="cmbFase" name="cmbFase">
							</select>
						  </div>
						  <div class="form-group">
						  		<h1 class="page-title">Juegos:</h1>
						  		<div id="divJuegosContent"></div>	
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
</body>
</html>
<script>
var dataTable = 0;
idComp=<?php echo($idComp); ?>;
idFase=<?php echo($idFase); ?>;
$(document).ready(function(){
   $("#cmbComp").change(function () {
   		$("#cmbComp option:selected").each(function () {
            elegido=$(this).val();
			idComp = elegido;
            $.post("ajaxFases.php", { elegido: elegido }, function(data){
            $("#cmbFase").html(data);
            });            
        });
   })
});
$(document).ready(function(){
   $("#cmbFase").change(function () {
	 idFase = $(this).val();
	 $("#divJuegosContent").load('ajaxJuegosInforme1.php?idFase='+this.value+'&idComp='+$("#cmbComp").val());	  	
   })
});	


jQuery( document ).on( 'submit', '#formSaveInforme', function ( event ) {		
	$.ajax({
			type: "POST",
			url: "server.php?action=addManualGame",
			data:{ array : JSON.stringify(arrSelectedTeam) },
			dataType: "json",
			success: function (data) {
				//console.log(data);
				arrSelectedTeam=[];
				location.href = './juegos.php?idComp='+idComp+'&idFase='+idFase;
			},
			error: function (data) {
				//console.log(data);
			},
		});		
	return false;
} );
</script>
<?php $connect->close(); ?>