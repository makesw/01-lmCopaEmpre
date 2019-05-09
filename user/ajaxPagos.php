<?php 
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.html' );
}
require '../conexion.php';
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:0;
//Consultar datos de la competicion:
$competicion = mysqli_fetch_array($connect->query( "select * from competicion where id =".$idComp ));	
//Consultar equipo:
$resultequipo = $connect->query( "select e.* from equipo e join inscripcion i on e.id = i.id_equipo and i.id_competicion =".$idComp." and e.id_usuario = ".$_SESSION[ 'dataSession' ]['id']."" );	


setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-pagos" >
<thead>
	<tr>
		<th>Equipo</th>
		<th>Valor Competición $</th>
		<th>Abonado</th>
		<th>Descuento</th>
		<th>Saldo</th>		
		<th></th>
	</tr>
</thead>
<tbody>
<?php
while($rowEquipo = mysqli_fetch_array($resultequipo)){
	
//Consultar descuentos otorgados al equipo en la competencia	
$descuentosEquipo = mysqli_fetch_array($connect->query(("select ifnull(sum(abono),0) total from pago where id_equipo = ".$rowEquipo['id']." and id_competicion = ".$idComp." and descuento =1" )));

//Consultar pagos del equipo en la competencia
$pagosEquipo = mysqli_fetch_array($connect->query(("select ifnull(sum(abono),0) total from pago where id_equipo = ".$rowEquipo['id']." and id_competicion = ".$idComp." and (descuento is null or descuento =0)" )));	

//Calcular saldo de la competencia:
$saldo = $competicion['valor'] - $pagosEquipo['total'] - $descuentosEquipo['total'];
	
?>	
<tr>	
	<td> <?php echo $rowEquipo['nombre']; ?>	</td>
	<td> <?php echo '$ '.number_format($competicion['valor']); ?>	</td>
	<td style="color:#016910;"> <strong><?php echo '$ '.number_format($pagosEquipo['total']); ?></strong>	</td>
	<td> <?php echo $descuentosEquipo['total']; ?>	</td>
	<td style="color:#ef4040;"> <strong><?php echo '$ '.number_format($saldo); ?></strong>	</td>	
	<th> <a href="pagosDetalle.php?idEqui=<?php echo $rowEquipo['id']; ?>&id_comp=<?php echo $idComp; ?>"><button class="btn btn-info btn-outline" type="button"> Ver Detalle</button></a> </th>	
</tr>
<?php } ?>
</tbody>
</table>
</div>
<?php $connect->close(); ?>