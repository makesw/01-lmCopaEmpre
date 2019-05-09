<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.html' );
}
$idJug=0;
if(isset($_GET[ 'idJug' ])){
	$idJug = $_GET[ 'idJug' ];
}
require '../conexion.php';
$jugador = mysqli_fetch_array( $connect->query("select j.*, e.nombre nombreEquipo from jugador j join equipo e on j.id = ".$idJug." and j.id_equipo = e.id") );
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Mouldifi - A fully responsive, HTML5 based admin theme">
<meta name="keywords" content="Responsive, HTML5, admin theme, business, professional, Mouldifi, web design, CSS3">
<title>LegueManager | Carnet Jugador</title>
<!-- Site favicon -->
<link rel='shortcut icon' type='image/x-icon' href='images/favicon.ico' />
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

<link href="../css/plugins/select2/select2.css" rel="stylesheet">
<link href="../css/mouldifi-forms.css" rel="stylesheet">


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
<![endif]-->
</head>
<body>
<!-- Page container -->
<div class="page-container">
   <!-- Main container -->
  <div class="main-container">
  	
	<!-- Main content -->
    <div class="main-content">	
		<div class="row">
			<div class="col-md-12">
			
				<!-- Card grid view -->
				<div class="cards-container box-view grid-view">
					<div class="row">						
						<div class="col-lg-4 col-sm-6 ">
						
							<!-- Card -->
							<div class="card">
							
								<!-- Card header -->
								<div class="card-header">
									<div class="card-photo">
										<img class="img-circle avatar" src="<?php echo $jugador['url_foto']; ?>" alt="<?php echo $jugador['nombres'].' '.$jugador['apellidos']; ?>" title="<?php echo $jugador['nombres'].' '.$jugador['apellidos']; ?>">
									</div>
									<div class="card-short-description">
										<h5><span class="user-name"><a href="#/"><?php echo $jugador['nombres'].' '.$jugador['apellidos']; ?></a></span></h5>
										<p><span class="badge badge-primary">Equipo: <?php echo $jugador['nombreEquipo']; ?></span></p>
									</div>
								</div>
								<!-- /card header -->
								
								<!-- Card content -->
								<div class="card-content">
									<p>Hi, This is my short intro text. Lorem ipsum is a dummy content sit dollar. You can copy and paste this dummy content from anywhere and to anywhere. Its all free and must be a good to folllow in prectice</p>
								</div>
								<!-- /card content -->
								
								<!-- Card footer -->
								<div class="card-footer clearfix">
									<ul class="list-inline">
										<li class="pull-right dropup">
											<a href="#/" data-toggle="dropdown"><i class="icon-dot-3 icon-more"></i></a>
											<ul class="dropdown-menu dropdown-menu-right">
												<li><a href="">Change Setting</a></li>
												<li><a href="">View Profile</a></li>
												<li><a href="">Send Message</a></li>
											</ul>
										</li>
									</ul>
								</div>
								<!-- /card footer -->
								
							</div>
							<!-- /card -->
							
						</div>						
					</div>
				</div>
				<!-- /card grid view -->
				
			</div>
		</div>	
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
<!-- Select2-->
<script src="../js/plugins/select2/select2.full.min.js"></script>
<script src="../js/functions.js"></script>
<script>
	$(document).ready(function () {
		$(".select2").select2();
	});
</script>
</body>
</html>
