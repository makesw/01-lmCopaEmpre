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
$resultEscenarios = $connect->query( "select concat(e.nombre,' - ',s.nombre) escena, e.id idEscena from escenario e JOIN sede s ON e.id_sede = s.id order by e.nombre asc" );
$idComp=0;
$idFase=0;
$jornada=0;
if(isset($_GET[ 'idComp' ])){
	$idComp = $_GET[ 'idComp' ];
}
if(isset($_GET[ 'idFase' ])){
	$idFase = $_GET[ 'idFase' ];
}
if(isset($_GET[ 'jornada' ])){
	$jornada = $_GET[ 'jornada' ];
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
			<h1 class="page-title">Administración / Juegos</h1>
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
							<label for="password">Jornada</label>
							<select class="form-control" required id="cmbJornada" name="cmbJornada">
							</select>
						  </div>					
					</div>
				</div>			
				
			</div>
			</div>
			<div class="row">			
			<div class="col-lg-12">
				<div class="class="panel panel-minimal"t">
					<div class="panel-body">
						<div id="divJuegosContent"></div>						
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
<div id="modal-programar" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modal-programar-tittle">Programar</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="formProgramJuego" method="post">					
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">Hora Inicio</label> 
						<div class="col-sm-10"> 
							<select class="form-control" id="cmbHoraInicio" name="cmbHoraInicio" required>
								<option value=""></option>
								<option value="01">01</option>
								<option value="02">02</option>
								<option value="03">03</option>
								<option value="04">04</option>
								<option value="05">05</option>
								<option value="06">06</option>
								<option value="07">07</option>
								<option value="08">08</option>
								<option value="09">09</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>					
							</select>
						 </div> 
					</div>
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">Minuto Inicio</label> 
						<div class="col-sm-10"> 
							<input type="number" class="form-control" name="minInicio" required id="minInicio" size="2" maxlength="2"> 
						 </div> 
					</div>
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">AM/PM</label> 
						<div class="col-sm-10"> 
							<select class="form-control" id="cmbAmpmInicio" name="cmbAmpmInicio">
								<option selected value="AM">AM</option>									
								<option value="PM">PM</option>
							</select>
						 </div> 
					</div>
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">Hora Fin</label> 
						<div class="col-sm-10"> 
							<select class="form-control" id="cmbHoraFin" name="cmbHoraFin" required>
								<option value=""></option>
								<option value="01">01</option>
								<option value="02">02</option>
								<option value="03">03</option>
								<option value="04">04</option>
								<option value="05">05</option>
								<option value="06">06</option>
								<option value="07">07</option>
								<option value="08">08</option>
								<option value="09">09</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>					
							</select>
						 </div> 
					</div>
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">Minuto Fin</label> 
						<div class="col-sm-10"> 
							<input type="number" class="form-control" name="minFin" required id="minFin" size="2" maxlength="2"> 
						 </div> 
					</div>
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">AM/PM</label> 
						<div class="col-sm-10"> 
							<select class="form-control" id="cmbAmpmFin" name="cmbAmpmFin">
								<option selected value="AM">AM</option>									
								<option value="PM">PM</option>
							</select>
						 </div> 
					</div>
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">Escenario</label> 
						<div class="col-sm-10"> 
							<select class="form-control" name="cmbEscena" id="cmbEscena"> 
								<option value=""></option>
								<?php while ( $rowEsc = mysqli_fetch_array( $resultEscenarios ) ) { ?>
									<option value="<?php echo $rowEsc['idEscena']; ?>"><?php echo $rowEsc['escena']; ?></option>
								<?php } ?>
							</select>
						 </div> 
					</div>
					<div class="form-group"> 
						<label class="col-sm-2 control-label" for="nombre">Fecha</label> 
						<div class="col-sm-10"> 
							<input type="date" name="fecha" id="fecha">
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
				</form>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<div id="modal-agregar" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modal-programar-tittle">Seleccione dos Equipos</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="formAddGame" method="post">					
					<div class="table-responsive">
						<table id="tbl_teams" class="table table-striped table-bordered table-hover dataTables-equipos" >
							<thead>
								<tr>
									<th>Nombre</th>
									<th></th>
								</tr>
							</thead>
							<tbody id="tbodyEquipos1">
						</table>
					</div>					
					<div class="alert alert-danger" role="alert" hidden="true" id="div-msg-fail"></div>
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
var arrSelectedTeam=[];
var dataTable = 0;
$('#tbl_teams tbody').on('change', 'input[type="checkbox"]', function(){
	if($(this).is(':checked')){
		arrSelectedTeam.push(this.value);
	}else{
		arrSelectedTeam.splice(arrSelectedTeam.indexOf(this.value), 1);
	}
});	
idComp=<?php echo($idComp); ?>;
idFase=<?php echo($idFase); ?>;
jornada=<?php echo($jornada); ?>;
if( idComp != null ){
	$("#cmbComp").val(idComp);
	$.post("ajaxFases.php", { elegido: idComp }, function(data){
		$("#cmbFase").html(data);
		$("#cmbFase").val(idFase);
	});
	
	
	$.post("ajaxJornadas.php", { elegido: idFase }, function(data){
		$("#cmbJornada").html(data);
		$("#cmbJornada").val(jornada);
	});
	
	$("#divJuegosContent").load('ajaxJuegos.php?idFase='+idFase+'&jornada='+jornada);	
}
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
	 $("#divJuegosContent").load('ajaxJuegos.php?idFase='+this.value+'&idComp='+$("#cmbComp").val());	  	
   })
});
$(document).ready(function(){
   $("#cmbFase").change(function () {
   		$("#cmbFase option:selected").each(function () {
            elegido=$(this).val();
			idFase = elegido;
            $.post("ajaxJornadas.php", { elegido: elegido }, function(data){
            $("#cmbJornada").html(data);
            });            
        });
   })
});
$(document).ready(function(){
   $("#cmbJornada").change(function () {
	 jornada = $(this).val();
	 $("#divJuegosContent").load('ajaxJuegos.php?idFase='+idFase+'&idComp='+idComp+'&jornada='+jornada);	  	
   })
});
function genGames( idFase ){
	idaYvuelta = 0;
	if($('input[name=checkIdaVuelta]:checked', '#formJuegos').val()){
		idaYvuelta = 1;	
	}
	$.ajax( {
		url: 'server.php?action=genGames&idFase='+idFase+'&idaYvuelta='+idaYvuelta,
		type: 'POST',
		data: new FormData(  ),
		success: function ( data ) {
			//console.log( data );
			location.href = './juegos.php?idComp='+idComp+'&idFase='+idFase+'&jornada='+jornada;
		},
		error: function ( data ) {
			//console.log( data );
		},
		cache: false,
		contentType: false,
		processData: false
	} ); 	
}	
function delJuegos( idFase ) {
	if ( confirm( 'Confirma Eliminar?' ) ) {
		$.ajax( {
			url: 'server.php?action=delJuegos&idFase=' + idFase,
			type: 'POST',
			data: new FormData(  ),
			success: function ( data ) {
				//console.log( data );
				location.href = './juegos.php?idComp='+idComp+'&idFase='+idFase+'&jornada='+jornada;
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
function fnProgramar( idJuego, nombre1, nombre2 ) { 
	$('#modal-programar-tittle').text('Programar: '+nombre1+' Vs '+nombre2);
	$('#bthValId').val(idJuego);
	$.post("server.php?action=getDataJuego&idJuego="+idJuego, { idJuego: idJuego }, function(data){
		if(data.hora_inicio!=null){
			var fields = data.hora_inicio.split(':');
			$("#cmbHoraInicio").val(fields[0]);
			$("#minInicio").val(fields[1]);
			$("#cmbAmpmInicio").val(fields[2]);
		}else{
			$("#cmbHoraInicio").val("");
			$("#minInicio").val(null);
		}
		if(data.hora_fin!=null){	
			var fields = data.hora_fin.split(':');
			$("#cmbHoraFin").val(fields[0]);
			$("#minFin").val(fields[1]);
			$("#cmbAmpmFin").val(fields[2]);
		}else{
			$("#cmbHoraFin").val("");
			$("#minFin").val(null);
		}
		$("#cmbEscena").val(data.id_escenario);
		if(data.fecha!= null){
			var fecha = new Date(data.fecha);
			var day = fecha.getDate()<10?"0"+fecha.getDate():fecha.getDate();
			var dateStr = fecha.getFullYear()+'-'+(fecha.getMonth()+1)+'-'+day;
			$("#fecha").val(dateStr);
		}else{
			$("#fecha").val(null);
		}
		//console.log( data );
	});	
	
	$('#modal-programar').modal('show');
}
	
jQuery( document ).on( 'submit', '#formProgramJuego', function ( event ) {
$.ajax( {
		url: 'server.php?action=programJuego',
		type: 'POST',
		data: new FormData( this ),
		success: function ( data ) {
			console.log( data );
			if( data.error ){
			$('#div-msg-fail').text(data.description);			
			$('#div-msg-fail').show();
				setTimeout(function(){
					//location.href = './juegos.php?idComp='+idComp+'&idFase='+idFase;
					$('#div-msg-fail').hide();
				},4000);
			}else{
				if( data.alerta ){
					$('#div-msg-fail').text(data.descAlerta);
					$("#div-msg-fail").attr("class", "alert alert-warning");
					$('#div-msg-fail').show();
					setTimeout(function(){
						$('#div-msg-fail').hide();
						location.href = './juegos.php?idComp='+idComp+'&idFase='+idFase+'&jornada='+jornada;
					},4000);
				}else{
					location.href = './juegos.php?idComp='+idComp+'&idFase='+idFase+'&jornada='+jornada;	
				}
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
function fnClearProg( idJuego ) {
	if ( confirm( 'Confirma Limpiar Programación?' ) ) {
		$.ajax( {
			url: 'server.php?action=clearProg&id=' + idJuego,
			type: 'POST',
			data: new FormData(  ),
			success: function ( data ) {
				//console.log( data );
				location.href = './juegos.php?idComp='+idComp+'&idFase='+idFase+'&jornada='+jornada;
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
function fnAddJuego() { 
	if(dataTable == 0){
		$.post("ajaxEquipos.php?opt=2", { idComp: idComp }, function(data){
			//console.log(data);
			$("#tbodyEquipos1").html(data);	
			$('.dataTables-equipos').DataTable({
				"searching": true,
				"bLengthChange": false,
				"bInfo": false,
				"pageLength": 10
			});				
		}); 
		dataTable= 1;
		$('#modal-agregar').modal('show');
	}else{
		$('#modal-agregar').modal('show');	
	}	
}
jQuery( document ).on( 'submit', '#formAddGame', function ( event ) {
	if(arrSelectedTeam.length == 2){
		$.ajax({
				type: "POST",
				url: "server.php?action=addManualGame",
				data:{ array : JSON.stringify(arrSelectedTeam) },
				dataType: "json",
				success: function (data) {
					//console.log(data);
					arrSelectedTeam=[];
					location.href = './juegos.php?idComp='+idComp+'&idFase='+idFase+'&jornada='+jornada;
				},
				error: function (data) {
					//console.log(data);
				},
			});		
	}else{
		alert('Por favor seleccione solo 2 equipos.')
	}
	return false;
} );
function delGame( id ) {
	if ( confirm( 'Confirma Eliminar?' ) ) {
		$.ajax( {
			url: 'server.php?action=delJuego&id=' + id,
			type: 'POST',
			data: new FormData(  ),
			success: function ( data ) {
				//console.log( data );
				if(!data.error){
					location.href = './juegos.php?idComp='+idComp+'&idFase='+idFase+'&jornada='+jornada;
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
function fnAplazar( idJuego ) {
	if ( confirm( 'Confirma Aplazar el Juego?' ) ) {
		$.ajax( {
			url: 'server.php?action=aplazarGame&id=' + idJuego,
			type: 'POST',
			data: new FormData(  ),
			success: function ( data ) {
				//console.log( data );
				location.href = './juegos.php?idComp='+idComp+'&idFase='+idFase+'&jornada='+jornada;
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