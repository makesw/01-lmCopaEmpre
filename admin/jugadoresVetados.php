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
$resultJugVetados = $connect->query( "select jv.*,j.documento, concat(j.nombres,' ',j.apellidos) nombreJugador, e.nombre nombreEquipo, c.nombre competicion from jugador_vetado jv join jugador j on jv.documento_jugador = j.documento join equipo e on j.id_equipo = e.id join competicion c on e.id_competicion = c.id" );
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
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
			<h1 class="page-title">Liga / Jugadores Vetados</h1>
			<div class="row">			
			<div class="col-lg-12">
				<div class="panel panel-default">
				<div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-jugVetados" >
                    <thead>
                    	<tr>
                    		<th>
                    			<input type="checkbox" name="select_all" value="1" id="chkAllVetados">Sel.Todo
                    			<a title="Borrar Seleccionados" href="javaScript:delVet();">
                    				<i class="icon-cancel icon-larger red-color"></i>
                    			</a>
                    		</th>
                    		<th>Documento</th>
                    		<th>Nombres</th>
                    		<th>Equipo</th>
                    		<th>Competición</th>
                    		<th>Fecha</th>
                    		<th>Motivo</th>
                    	</tr>
                    </thead>
                    <tbody>
                    <?php
                    while($row = mysqli_fetch_array($resultJugVetados)){
                    	$date_a = "";
                    	if(!empty($row['fecha'])){
                    		$date_a = new DateTime($row['fecha']);
                    		$date_a = $date_a->format('d-m-Y');
                    	}
                    ?>	
                    <tr>	
                    	<td>
                    		<input type="checkbox" name="id[]" value="<?php echo $row['documento']; ?>">
                    	</td>
                    	<td> <?php echo $row['documento']; ?>	</td>
                    	<td> <?php echo $row['nombreJugador']; ?>	</td>
                    	<td> <?php echo $row['nombreEquipo']; ?>	</td>
                    	<td> <?php echo $row['competicion']; ?>	</td>
                    	<td> <?php echo $date_a; ?>	</td>
                    	<td> <textarea rows="2"><?php echo $row['motivo']; ?></textarea></td>	
                    </tr>
                    <?php } ?>
                    </tbody>
                    </table>
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
<div id="modal-payers" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Listado Jugadores</h4>
			</div>
			<div class="modal-body">
				<form id="formVetar" method="post">
					<div class="table-responsive" id="playersSection">
						
					</div>
					<input type="hidden" id="idGrupHid" name="idGrupHid" value="" />
					<button type="submit" class="btn btn-primary">Vetar</button> 
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
<script src="../js/plugins/datatables/jszip.min.js"></script>
<script src="../js/plugins/datatables/pdfmake.min.js"></script>
<script src="../js/plugins/datatables/vfs_fonts.js"></script>
</body>
</html>
<script>
var table;
var arrSelectedPlayers=[];
var checkAll = false;
$(document).ready(function () {
	table = $('.dataTables-jugVetados').DataTable({
	"searching": true,
	"bSort" : false,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 20,
	"oLanguage": {
	   "sSearch": "Buscar: "
	 }
	});
});
function delVet(){
	// Iterate over all checkboxes in the table
	table.$('input[type="checkbox"]').each(function(){
	 // If checkbox doesn't exist in DOM
	 //if(!$.contains(document, this)){
		// If checkbox is checked
		if(this.checked){
		   arrSelectedPlayers.push( this.value );
		}
	 //}
	});
	if(arrSelectedPlayers.length < 1){
		alert('Sleccione al menos un jugador!.');
	}else{		
		if(confirm('¿Confirma Eliminar?')){
			$.ajax({
				type: "POST",
				url: "server.php?action=delVetados",
				data:{ array : JSON.stringify(arrSelectedPlayers) },
				dataType: "json",
				success: function (data) {
					//console.log(data);
					arrSelectedPlayers=[];
					checkAll = false;
					location.href='./jugadoresVetados.php';
				},
				error: function (data) {
					//console.log(data);
					arrSelectedPlayers=[];
					checkAll = false;
					console.log(data);
					location.href='./jugadoresVetados.php';
				}
			});
		}
	}
}
$('#chkAllVetados').on('click', function() { checkAll = true;
   // Get all rows with search applied
     var rows = table.rows({ 'search': 'applied' }).nodes();
	// Check/uncheck checkboxes for all rows in the table
      $('input[type="checkbox"]', rows).prop('checked', this.checked);	
});
</script>
<?php $connect->close(); ?>