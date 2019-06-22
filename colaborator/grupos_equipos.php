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
$idComp=0;
$idFase=0;
if(isset($_GET[ 'idComp' ])){
	$idComp = $_GET[ 'idComp' ];
}
if(isset($_GET[ 'idFase' ])){
	$idFase = $_GET[ 'idFase' ];
}

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="cache-control" content="no-cache" />
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
			<h1 class="page-title">Administración / Equipos * Grupo</h1>
			<div class="row">			
			<div class="col-lg-12">
				<div class="panel panel-default">				
					<div class="panel-body">
						<form>
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
						</form>							
					</div>
				</div>			
				
			</div>
			</div>	
			<h1 class="page-title">Grupos</h1>		
			<div class="row">
				<div id="divGroupContent"></div>
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

<div id="modal-asoc-equi" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Agregar Equipo a Grupo</h4>
			</div>
			<div class="modal-body">
				<form id="formAddEquiGru" method="post">
					<div class="table-responsive" id="tbodyAddEquiGru">
						
					</div>
					<input type="hidden" id="idGrupHid" name="idGrupHid" value="" />
					<button type="submit" class="btn btn-primary">Agregar</button> 
				</form>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<div id="modal-alert" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Alerta!</h4>
			</div>
			<div class="modal-body">
				<form id="formSede" method="post">
					<div class="panel-body">
						<div class="alert alert-danger" role="alert"><strong>Alerta!</strong> <p id="textAlert"></p></div>
					</div>
				</form>
			</div>
		</div>
	</div>
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
idComp=<?php echo($idComp); ?>;
idFase=<?php echo($idFase); ?>;
paginationTable = false;
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
   	 $("#divGroupContent").load('ajaxGrupos.php?idFase='+this.value);
   })
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
			//console.log( data );
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
				if(data.activa == 1){
					$("#activa").prop("checked", true);
				}else{
					$("#activa").prop("checked", false);
				}				
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
function addEquToGru( idGrupo ){
	$.post("ajaxPosiciones.php?grue=1&idComp="+idComp+"&idGrupo="+idGrupo+"&idFase="+idFase, { idFase: idFase }, function(data){
		//console.log(data);
		$("#tbodyAddEquiGru").html(data);
		if(!paginationTable){
			$('.dataTables-addEquiGru').DataTable({
				"searching": true,
				"bLengthChange": false,
				"bInfo": false,
				"pageLength": 10
			});
		}
		paginationTable = true;
	}); 	
	$("#idGrupHid").val(idGrupo);
	$('#modal-asoc-equi').modal('show');
}	
jQuery( document ).on( 'submit', '#formAddEquiGru', function ( event ) {
	$.ajax( {
		url: 'server.php?action=addEquiGru',
		type: 'POST',
		data: new FormData( this ),
		success: function ( data ) {
			//console.log( data );
			location.href = './grupos_equipos.php?idComp='+idComp+'&idFase='+idFase;
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
if( idComp != null ){
	$("#cmbComp").val(idComp);
	 $.post("ajaxFases.php", { elegido: idComp }, function(data){
	$("#cmbFase").html(data);
	$("#cmbFase").val(idFase);
	});
	$("#divGroupContent").load('ajaxGrupos.php?idFase='+idFase);
	
}
function delEquiGru( id ) {
	if ( confirm( 'Confirma Eliminar?' ) ) {
		$.ajax( {
			url: 'server.php?action=delEquiGru&id=' + id,
			type: 'POST',
			data: new FormData( this ),
			success: function ( data ) {
				//console.log( data );
				if(!data.error){
					location.href = './grupos_equipos.php?idComp='+idComp+'&idFase='+idFase;
				}else{
					$('#textAlert').text(data.description);
					$('#modal-alert').modal('show');	
				}
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
</script>
<?php $connect->close(); ?>