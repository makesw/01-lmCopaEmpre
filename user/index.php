<?php 
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.html' );
}
require '../conexion.php';
//print_r($_SESSION);
//consultar equipo de usuario:
$equipo = mysqli_fetch_array($connect->query( "select * from equipo WHERE id_usuario=".$_SESSION[ 'dataSession' ]['id'] ));
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
<link rel='shortcut icon' type='image/x-icon' href='../images/favicon.ico' />
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
      <script src="../js/html5shiv.min.js"></script>
      <script src="../js/respond.min.js"></script>
<![endif]-->

<!--[if lte IE 8]>
	<script src="../js/plugins/flot/excanvas.min.js"></script>
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
  <div class="main-container gray-bg">
  
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
			<div class="row"><br/>
				<div style="text-align: center;vertical-align: top;">						
						<strong>BIENVENIDO A LEAGUE MANAGER</strong><br/><br/><br/>
						<img src="../images/welcome_image.png"/>
				</div><br/><br/>
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
<div id="modal-Terminos" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Términos y Condiciones</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="formTerminos" method="post"> 
						 	<p style="text-align: justify;">Yo <strong><?php echo $_SESSION[ 'dataSession' ]['nombres'].' '.$_SESSION[ 'dataSession' ]['apellidos']; ?></strong> identificado con la Documento de Identidad. # <strong><?php echo $_SESSION[ 'dataSession' ]['documento']; ?></strong> actuando en calidad de delegado del equipo: <strong><?php echo $equipo['nombre']; ?></strong> como consta en la planilla de inscripción enviada al correo tfcopaemprendedores@gmail.com, certifico que he leído la invitación y el reglamento interno de futbol Copa Emprendedores del respectivo torneo al que nos vamos a inscribir, y en nombre de mi equipo aceptamos  las condiciones de lo allí expresado haciéndonos responsables por las exigencias y sugerencias que en dicha invitación y reglamento se manifiestan. También nos comprometemos como equipo a conservar todos los valores éticos y morales de una competición como lo son El Respeto , El Juego Limpio , La tolerancia…etc.
							<br/><br/>
							Agradeciendo la atención a la presente.
							<br/><br/>	
							Atentamente,
							<br/><br/>
							www.copaemprendedores.com</p>
					<br/>
					<button type="button" class="btn btn-primary" onClick="javascript:aceptarTerminos();">Aceptar</button>
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

<!--ChartJs-->
<script src="../js/plugins/chartjs/Chart.min.js"></script>
<script>
$(document).ready(function () {
	var $checkbox = $('.todo-list .checkbox input[type=checkbox]');

	$checkbox.change(function () {
		if ($(this).is(':checked')) {
			$(this).parent().addClass('checked');
		} else {
			$(this).parent().removeClass('checked');
		}
	});

	$checkbox.each(function (index) {
		if ($(this).is(':checked')) {
			$(this).parent().addClass('checked');
		} else {
			$(this).parent().removeClass('checked');
		}
	});
});
<?php 	
	if($_SESSION[ 'dataSession' ]!= null && (!$_SESSION[ 'dataSession' ]['acepto_terminos'] || $_SESSION[ 'dataSession' ]['acepto_terminos']==0)){
?>
	$('#modal-Terminos').modal('show');	
<?php }?>
function aceptarTerminos( ) {
	$.ajax( {
		url: 'ajaxTerminos.php',
		type: 'POST',
		data: new FormData( this ),
		success: function ( data ) {
			//console.log( data );
			location.reload();
		},
		error: function ( data ) {
			//console.log( data );
		},
		cache: false,
		contentType: false,
		processData: false
	} );
	return false;
}
</script>
</body>
</html>
<?php $connect->close(); ?>