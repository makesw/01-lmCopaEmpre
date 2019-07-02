<?php ob_start();
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
    header( 'Location: ../index.html' );
}else{
    if($_SESSION[ 'dataSession' ]['perfil'] != 'colaborador'){
        header( 'Location: ../salir.php' );
    }
}
require '../conexion.php';
$idPlayer = $_GET[ 'idPlayer' ];
$player = mysqli_fetch_array($connect->query("select ju.*, e.nombre nombreEquipo, c.nombre nombreComp  from jugador ju join equipo e on ju.id_equipo = e.id and ju.id = ".$idPlayer." join competicion c on e.id_competicion = c.id limit 1"));
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
$styleCarnet = mysqli_fetch_array($connect->query("select * from carnet limit 1"));

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Carnet</title>
</head>
<br/>
<body style="text-align: -webkit-center;font-family: Source Sans Pro, sans-serif !important;">
	<table style="width: 340px;border: none;padding-left: 15px;border-collapse:inherit;border-spacing:0px;font-family: Source Sans Pro, sans-serif !important;">
		<tr style="height: 50px;">
			<td style="width: 100%;background-color: <?php echo $styleCarnet['color_header']; ?>;" colspan="2" valign="top">
				<table>
					<tr>
						<td valign="top">
							<img style="padding-left: 5px; width: 100px !important;height: 49px !important;" src="<?php echo $styleCarnet['url_logo'] ?>" alt="">
						</td>
						<td valign="top" style="text-align: center;padding:5px 0px 0px 2px;font-size: 14px;font-weight: bold;color:<?php echo $styleCarnet['text_color_header']; ?>;">
							<?php if(empty($styleCarnet['text_header'])){ echo $player['nombreComp'];} else { echo $styleCarnet['text_header'];} ?>
						</td>
					</tr>
				</table>
			</td>			
		</tr>
		<tr style="background-color:<?php echo $styleCarnet['color_body'];?>;">
			<td  style="width: 50%; font-weight: 600;">
				<img style="padding:2px 0px 0px 2px; width: 105px !important;height: 110px !important;" src="<?php echo $player['url_foto'];?>" alt="">
			</td>
			<td style="width: 50%;font-weight: 800;text-align: left;">
				<table style="font-size: 12px;">
					<tr>
						<td style="font-weight: 1000;color:<?php echo $styleCarnet['text_color_body'];?>;">DOCUMENTO:</td>						
					</tr>
					<tr>
						<td><?php echo $player['documento'];?></td>
					</tr>
					<tr>
						<td style="font-weight: 1000;color:<?php echo $styleCarnet['text_color_body'];?>;">NOMBRE:</td>						
					</tr>
					<tr>
						<td><?php echo $player['nombres'].' '.$player['apellidos'];?></td>
					</tr>
					<tr>
						<td style="font-weight: 1000;color:<?php echo $styleCarnet['text_color_body'];?>;">EQUIPO:</td>					
					</tr>
					<tr>
						 <td><?php echo $player['nombreEquipo'];?></td>
					</tr>
				</table>	
			</td>
		</tr>
		<tr style="height: 30px;">
			<td colspan="2" style="font-size: 12px;padding-left: 5px;font-weight: 600;background-color: <?php echo $styleCarnet['color_footer']; ?>;color:<?php echo $styleCarnet['text_color_footer'];?>">
				<strong>www.copaemprendedores.com | Cel: 3002666817</strong>
			</td>		
		</tr>
	</table>	
</body>
</html>
<?php
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$html = ob_get_clean();
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('carnet_'.$player['id'].'.pdf');
?>

