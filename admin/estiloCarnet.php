<?php
session_start();
if (! isset($_SESSION['dataSession'])) {
    header('Location: ../index.html');
} else {
    if ($_SESSION['dataSession']['perfil'] != 'admin') {
        header('Location: ../salir.php');
    }
}
require '../conexion.php';
$styleCarnet = mysqli_fetch_array($connect->query("select * from carnet WHERE id=1"));
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description"
	content="Mouldifi - A fully responsive, HTML5 based admin theme">
<meta name="keywords"
	content="Responsive, HTML5, admin theme, business, professional, Mouldifi, web design, CSS3">
<title>Inicio</title>
<!-- Site favicon -->
<link rel='shortcut icon' type='image/x-icon'
	href='../images/favicon.ico' />
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
						<li class="profile-info dropdown"><a data-toggle="dropdown"
							class="dropdown-toggle" href="#" aria-expanded="false"> <img
								width="44" class="img-circle avatar" alt=""
								src="<?php echo $_SESSION['dataSession']['url_foto'];?>"><?php echo $_SESSION['dataSession']['nombres'].' '.$_SESSION['dataSession']['apellidos'] ?></a>
				
				</div>
				<div class="col-sm-6 col-xs-5">
					<div class="pull-right">
						<a title="Salir" href="../salir.php"><img
							src="../images/btn-close.png" style="height: 30px; widows: 30px;" /></a>
					</div>
				</div>

			</div>
			<!-- /main header -->

			<!-- Main content -->
			<div class="main-content">
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading clearfix">
								<h3 class="panel-title">Personalizar Platilla Carnet</h3>
							</div>
							<div class="panel-body">
								<form class="form-horizontal" id="formSaveTempCarnet" method="post">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="color_header">Color
											Fondo Cabecera</label>
										<div class="col-sm-10">
											<input type="color"
												value="<?php echo $styleCarnet['color_header']; ?>"
												placeholder="color_header" id="color_header"
												name="color_header" class="form-control">
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-2 control-label" for="text_header">Texto
											Cabecera</label>
										<div class="col-sm-10">
											<input type="text" onblur="javascript:aMayusculas(this.value,this.id);"
												value="<?php echo $styleCarnet['text_header']; ?>"
												placeholder="text_header" id="text_header"
												name="text_header" class="form-control" maxlength="50">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-2 control-label" for="text_color_header">Color
											Texto Cabecera</label>
										<div class="col-sm-10">
											<input type="color"
												value="<?php echo $styleCarnet['text_color_header']; ?>"
												id="text_color_header" name="text_color_header"
												class="form-control">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-2 control-label" for="color_body">Color
											Fondo Medio</label>
										<div class="col-sm-10">
											<input type="color"
												value="<?php echo $styleCarnet['color_body']; ?>"
												placeholder="color_body" id="color_body" name="color_body"
												class="form-control">
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-2 control-label" for="text_color_body">Color
											Texto Medio</label>
										<div class="col-sm-10">
											<input type="color"
												value="<?php echo $styleCarnet['text_color_body']; ?>"
												placeholder="text_color_body" id="text_color_body"
												name="text_color_body" class="form-control" maxlength="50">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-2 control-label" for="color_footer">Color
											Fondo Base</label>
										<div class="col-sm-10">
											<input type="color"
												value="<?php echo $styleCarnet['color_footer']; ?>"
												placeholder="color_footer" id="color_footer"
												name="color_footer" class="form-control">
										</div>
									</div>
									
									<!-- div class="form-group">
										<label class="col-sm-2 control-label" for="text_body_1">Texto
											1</label>
										<div class="col-sm-10">
											<input type="text" onblur="javascript:aMayusculas(this.value,this.id);"
												value="<?php echo $styleCarnet['text_body_1']; ?>"
												placeholder="text_body_1" id="text_body_1"
												name="text_body_1" class="form-control" maxlength="50">
										</div>
									</div -->

									<!-- div class="form-group">
										<label class="col-sm-2 control-label" for="text_body_2">Texto
											2</label>
										<div class="col-sm-10">
											<input type="text" onblur="javascript:aMayusculas(this.value,this.id);"
												value="<?php echo $styleCarnet['text_body_2']; ?>"
												placeholder="text_body_2" id="text_body_2"
												name="text_body_2" class="form-control" maxlength="50">
										</div>
									</div -->

									<!-- div class="form-group">
										<label class="col-sm-2 control-label" for="text_body_3">Texto
											3</label>
										<div class="col-sm-10">
											<input type="text" onblur="javascript:aMayusculas(this.value,this.id);"
												value="<?php echo $styleCarnet['text_body_3']; ?>"
												placeholder="text_body_3" id="text_body_3"
												name="text_body_3" class="form-control" maxlength="50">
										</div>
									</div -->								

									<div class="form-group">
										<label class="col-sm-2 control-label" for="text_footer">Texto
											Base</label>
										<div class="col-sm-10">
											<input type="text" onblur="javascript:aMayusculas(this.value,this.id);"
												value="<?php echo $styleCarnet['text_footer']; ?>"
												placeholder="text_footer" id="text_footer"
												name="text_footer" class="form-control" maxlength="50">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-2 control-label" for="text_color_footer">Color
											Texto Base</label>
										<div class="col-sm-10">
											<input type="color"
												value="<?php echo $styleCarnet['text_color_footer']; ?>"
												placeholder="text_color_footer" id="text_color_footer"
												name="text_color_footer" class="form-control" maxlength="50">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-2 control-label" for="url_logo">Url Logo</label>
										<div class="col-sm-10">
											<input type="text" onblur="javascript:aMayusculas(this.value,this.id);"
												value="<?php echo $styleCarnet['url_logo']; ?>"
												placeholder="url_logo" id="url_logo" name="url_logo"
												class="form-control" maxlength="100">
										</div>
									</div>


									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<button class="btn btn-primary" type="submit">Guardar</button> - 
											<button type="button" class="btn btn-primary"
												data-toggle="modal" data-target="#modal-template">Ver
												Plantilla</button>
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

	<div id="modal-template" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Plantilla Carnet</h4>
				</div>
				<div class="modal-body">

					<table
						style="width: 340px; border: none; padding-left: 15px; border-collapse: inherit; border-spacing: 0px; font-family: Source Sans Pro, sans-serif !important;">
						<tr style="height: 50px;">
							<td style="width: 100%;background-color: <?php echo $styleCarnet['color_header']; ?>;" colspan="2" valign="top">
								<table>
									<tr>
										<td valign="top"><img
											style="padding-left: 5px; width: 100px !important; height: 49px !important;"
											src="<?php echo $styleCarnet['url_logo'] ?>" alt=""></td>
										<td valign="top" style="text-align: center;padding:5px 0px 0px 2px;font-size: 14px;font-weight: bold;color:<?php echo $styleCarnet['text_color_header']; ?>;">
							<?php if(empty($styleCarnet['text_header'])){ echo 'NombreTorneo';} else { echo $styleCarnet['text_header'];} ?>
						</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr style="background-color:<?php echo $styleCarnet['color_body'];?>;">
							<td style="width: 50%; font-weight: 600;"><img
								style="padding: 2px 0px 0px 2px; width: 105px !important; height: 110px !important;"
								src="../images/default-user.png" alt=""></td>
							<td style="width: 50%; font-weight: 800; text-align: left;">
								<table style="font-size: 12px;">
									<tr>
										<td style="font-weight: 1000;color:<?php echo $styleCarnet['text_color_body'];?>;"><?php echo $styleCarnet['text_body_1'];?>:</td>
									</tr>
									<tr>
										<td></td>
									</tr>
									<tr>
										<td style="font-weight: 1000;color:<?php echo $styleCarnet['text_color_body'];?>;"><?php echo $styleCarnet['text_body_2']; ?>:</td>
									</tr>
									<tr>
										<td></td>
									</tr>
									<tr>
										<td style="font-weight: 1000;color:<?php echo $styleCarnet['text_color_body'];?>;"><?php echo $styleCarnet['text_body_3']; ?>:</td>
									</tr>
									<tr>
										<td></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr style="height: 30px;">
							<td colspan="2" style="font-size: 12px;padding-left: 5px;font-weight: 600;background-color: <?php echo $styleCarnet['color_footer']; ?>;color:<?php echo $styleCarnet['text_color_footer'];?>">
								<strong><?php echo $styleCarnet['text_footer']; ?></strong>
							</td>
						</tr>
					</table>

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
	<script
		src="../js/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
	<script src="../js/plugins/datatables/jszip.min.js"></script>
	<script src="../js/plugins/datatables/pdfmake.min.js"></script>
	<script src="../js/plugins/datatables/vfs_fonts.js"></script>
	<script
		src="../js/plugins/datatables/extensions/Buttons/js/buttons.html5.js"></script>
	<script
		src="../js/plugins/datatables/extensions/Buttons/js/buttons.colVis.js"></script>

	<script>
		jQuery( document ).on( 'submit', '#formSaveTempCarnet', function ( event ) {
			$.ajax( {
				url: 'server.php?action=saveTemplateCarnet',
				type: 'POST',
				data: new FormData( this ),
				success: function ( data ) {
					//console.log( data );
					location.href = './estiloCarnet.php';
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
		
	function aMayusculas(obj,id){
		obj = obj.toUpperCase();
		document.getElementById(id).value = obj;
	}
	</script>
</body>
</html>
<?php $connect->close(); ?>