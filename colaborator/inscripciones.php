<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
    header( 'Location: ../index.html' );
}else{
    if($_SESSION[ 'dataSession' ]['perfil'] != 'colaborador'){
        header( 'Location: ../salir.php' );
    }
}
require '../conexion.php';
$resultCompetencias = $connect->query( "select * from competicion WHERE activa=1  order by nombre asc" );
$idCompRld=0;
if(isset($_GET[ 'idComp' ])){
	$idCompRld = $_GET[ 'idComp' ];
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
			<h1 class="page-title">Administración / Inscripciones</h1>
			<div class="row">			
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
					  <div class="form-group"> 
							<label class="col-sm-2 control-label" for="nombre">Competición</label> 
							<div class="col-sm-10"> 
								<select class="form-control" required id="cmbComp" name="cmbComp"> 
									<option value="-1">Seleccione una competencia</option><?php
									while ( $row = mysqli_fetch_array( $resultCompetencias ) ) {
										echo "<option value='" . $row[ 'id' ] . "'>" . $row[ 'nombre' ] . "</option>";
									}
									?> 
								</select>
							 </div> 
						</div> 																		
					</div>
				</div>			
				<!-- button type="button" class="btn btn-primary" onClick="javascript:fnInscribir();">Inscribir Equipo</button -->
			</div>
			</div>		
			<div class="row">			
			<div class="col-lg-12">
				<div class="class="panel panel-minimal"t">
					<div class="panel-body">
						<div id="divTeamInscritos"></div>						
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

<div id="modal-inscribir" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Inscribir Equipo a Competición</h4>
			</div>
			<div class="modal-body">
				<form id="formInscription" method="post">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover dataTables-equipos">
						<thead>
							<tr>		
								<th>Nombre</th>
								<th>Competicion</th>
								<th>Seleccione</th>
							</tr>
						</thead>
						<tbody id="tblBodyEqui">	
						</tbody >						
						</table>
					</div>
					<input type="hidden" id="idGrupHid" name="idGrupHid" value="" />
					<button type="submit" class="btn btn-primary">Inscribir</button> 
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
$('.dataTables-equipos').DataTable({
	"searching": true,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 10
});
});
$(document).ready(function(){
   $("#cmbComp").change(function () {
		$("#divTeamInscritos").load('ajaxInscritos.php?opt=1&idComp='+$("#cmbComp").val());
   })
});	
function fnInscribir(){
	if( $("#cmbComp").val() <  1 ){
		alert('Por favor seleeccione un competencia.');
	}else{
			idComp = $("#cmbComp").val();
			 $.post("ajaxEquipos.php?opt=3&idComp="+idComp, { idComp: idComp }, function(data){
				 $("#tblBodyEqui").html(data);
				 $("#modal-inscribir").modal('show');
			 });
	}
}
jQuery( document ).on( 'submit', '#formInscription', function ( event ) {
	$.ajax( {
		url: 'server.php?action=inscriptionEqui&idComp='+$("#cmbComp").val(),
		type: 'POST',
		data: new FormData( this ),
		success: function ( data ) {
			//console.log( data );
			location.href = './inscripciones.php?idComp='+$("#cmbComp").val();
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
function delInscrip( id ) {
	if ( confirm( 'Confirma Eliminar?' ) ) {
		$.ajax( {
			url: 'server.php?action=delInscrip&id=' + id,
			type: 'POST',
			data: new FormData(  ),
			success: function ( data ) {
				//console.log( data );
				location.href = './inscripciones.php?idComp='+$("#cmbComp").val();
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
idCompRld=<?php echo($idCompRld); ?>;
if( idCompRld != 0 ){
	$("#divTeamInscritos").load('ajaxInscritos.php?opt=1&idComp='+idCompRld);
	$("#cmbComp").val(idCompRld);
}
</script>
<?php $connect->close(); ?>