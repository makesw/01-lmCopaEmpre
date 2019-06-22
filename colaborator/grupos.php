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
$resultFases = $connect->query( "select f.*,c.nombre nombreCompeticion from fase f JOIN competicion c ON f.id_competicion = c.id and c.activa=1 order by c.nombre asc, f.numero asc" );

$resultGrupos = $connect->query( "select g.*,f.id idFase, f.nombre nombreFase, c.id idCompeticion, c.nombre nombreCompeticion from grupo g JOIN fase f ON g.id_fase = f.id AND f.activa = 1 JOIN competicion c ON f.id_competicion = c.id AND c.activa = 1" );

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
			<h1 class="page-title">Administración / Grupos</h1>
			<div class="row">
			
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
					<!-- button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-grupo">Crear</button -->
								<div class="table-responsive">
									<table class="table table-bordered table-hover dataTables-comp" >
										<thead>
											<tr>
												<th>Nombre</th>
												<th>Clasifican</th>
												<th>Fase</th>
												<th>Competición</th>												
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php $iter = 1;
											while($row = mysqli_fetch_array($resultGrupos)){?>
											<tr>												
												<td>
													<?php echo $row['nombre']; ?>
												</td>
												<td>
													<?php echo $row['clasifican']; ?>
												</td>
												<td>
													<?php echo $row['nombreFase']; ?>
												</td>		
												<td>
													<?php echo $row['nombreCompeticion']; ?>
												</td>	
												<td>
												<!-- a href="javaScript:editGrupo(<?php echo $row['id']; ?>, <?php echo $row['idFase']; ?>)">
												<i class="fa fa-edit"></i>
												</a -->
												<!-- a title="Borrar" href="javaScript:delGrupo('<?php echo $row['id']; ?>');">
												<i class="icon-cancel icon-larger red-color"></i>
												</a -->												
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

<div id="modal-grupo" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Grupo</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="formCreateGrupo" method="post"> 
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">Competición</label> 
						<div class="col-sm-10"> 
							<select class="form-control" required id="cmbComp" name="cmbComp"> 
								<option value="">Seleccione una competencia</option><?php
								while ( $row = mysqli_fetch_array( $resultCompetencias ) ) {
									echo "<option value='" . $row[ 'id' ] . "'>" . $row[ 'nombre' ] . "</option>";
								}
								?> 
							</select>
						 </div> 
					</div> 
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">Fase</label> 
						<div class="col-sm-10"> 
							<select class="form-control" required id="cmbFase" name="cmbFase">							 
							</select>
						 </div> 
					</div> 
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">Nombre</label> 
						<div class="col-sm-10"> 
							<input type="text" onblur="javascript:aMayusculas(this.value,this.id);" placeholder="Nombre" id="nombre" name="nombre" class="form-control">
						 </div> 
					</div>
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">Clasifican</label> 
						<div class="col-sm-10"> 
							<input type="text" placeholder="Clasifican" id="clasi" name="clasi" class="form-control" maxlength="2" size="10">
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
	"searching": true,
	"bSort" : true,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 20
});
});
	
jQuery( document ).on( 'submit', '#formCreateGrupo', function ( event ) {
	$.ajax( {
		url: 'server.php?action=addUpdGrupo',
		type: 'POST',
		data: new FormData( this ),
		success: function ( data ) {
			//console.log( data );
			location.href = './grupos.php';
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
function editGrupo( id, idFase ) {
	$.ajax( {
			url: 'server.php?action=getDataGrupo&id=' + id,
			type: 'POST',
			data: new FormData( this ),
			success: function ( data ) {
				//console.log(data);
				$("#bthAction").val(2);
				$("#bthValId").val(data.id);
				$("#nombre").val(data.nombre);
				$("#clasi").val(data.clasifican);
				$("#cmbComp" ).val(data.id_competicion);
				elegido=$("#cmbComp" ).val();
				$.post("ajaxFases.php", { elegido: elegido }, function(data){
            		$("#cmbFase").html(data);
					$("#cmbFase" ).val(idFase);
				});     				
        		$('#modal-grupo').modal('show');
			},
			error: function ( data ) {
				//console.log( data );
			},
			cache: false,
			contentType: false,
			processData: false
		} );
}
function delGrupo( id ) {
	if ( confirm( 'Confirma Eliminar?' ) ) {
		$.ajax( {
			url: 'server.php?action=delGrupo&id=' + id,
			type: 'POST',
			data: new FormData( this ),
			success: function ( data ) {
				//console.log( data );
				location.href = './grupos.php';
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
$(document).ready(function(){
   $("#cmbComp").change(function () {
   		$("#cmbComp option:selected").each(function () {
            elegido=$(this).val();
            $.post("ajaxFases.php", { elegido: elegido }, function(data){
            $("#cmbFase").html(data);
            });            
        });
   })
});
function aMayusculas(obj,id){
    obj = obj.toUpperCase();
    document.getElementById(id).value = obj;
}
</script>
<?php $connect->close(); ?>