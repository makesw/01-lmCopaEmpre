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

if(isset($_GET[ 'opt' ]) && $_GET[ 'opt' ]==1 ){
$idComp = isset($_GET[ 'idComp' ])?$_GET[ 'idComp' ]:0;
//Consultar datos de la competicion:
$competicion = mysqli_fetch_array($connect->query( "select * from competicion where id =".$idComp ));	
//Consultar equipos de la competicion:
$resultequipos = $connect->query( "select e.* from equipo e join inscripcion i on e.id = i.id_equipo and i.id_competicion =".$idComp );	
	
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
	
?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-pagos">
<thead>
	<tr>		
		<th>Equipo</th>
		<th>Valor Inscripción</th>
		<th>Total Abonado</th>
		<th>Total Descuentos</th>								
		<th>Saldo Inscripción</th>
		<th></th>
	</tr>
</thead>
<tbody>
<?php
$totalAbonado = 0;
$totalDescuentos = 0;
$totalSaldo = 0;
while($rowEquipo = mysqli_fetch_array($resultequipos)){
	
//Consultar descuentos otorgados al equipo en la competencia	
$dtosIscrip = mysqli_fetch_array($connect->query(("select ifnull(sum(abono),0) total from pago where id_equipo = ".$rowEquipo['id']." and id_competicion = ".$idComp." and descuento =1 and (id_tipo_pago = 1 or UPPER(detalle) like '%INSCRIP%' )" )));

$pagosInscrip = mysqli_fetch_array($connect->query(("select ifnull(sum(abono),0) total from pago where id_equipo = ".$rowEquipo['id']." and id_competicion = ".$idComp." and (descuento is null or descuento =0) and (id_tipo_pago = 1 or UPPER(detalle) like '%INSCRIP%' ) " )));

//Calcular saldo de la competencia:
$saldo = $competicion['valor'] - $pagosInscrip['total'] - $dtosIscrip['total'];

$totalAbonado += $pagosInscrip['total'];	
$totalDescuentos += $dtosIscrip['total'];
$totalSaldo += $saldo;

?>	
<tr>	
	<td> <?php echo $rowEquipo['nombre']; ?>	</td>
	<td> <?php echo '$ '.number_format($competicion['valor']); ?>	</td>
	<td style="color:#016910;"> <strong><?php echo '$ '.number_format($pagosInscrip['total']); ?></strong>	</td>
	<td> <?php echo $dtosIscrip['total']; ?>	</td>
	<td style="color:#ef4040;"> <strong><?php echo '$ '.number_format($saldo); ?></strong>	</td>	
	<td>
		<div class="btn-group">
			  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Acciones <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu">
				<li><a href="javascript:fnAddPago(<?php echo $rowEquipo['id']; ?>);">Agregar Pago</a></li>
				<li><a href="javascript:fnAddDto(<?php echo $rowEquipo['id']; ?>);">Agregar Descuento</a></li>				 
				<li><a href="pagosDetalleInscripcion.php?idEqui=<?php echo $rowEquipo['id']; ?>&id_comp=<?php echo $_GET['idComp']; ?>">Ver Detalle</a></li>
			  </ul>
		</div>
	</td>
</tr>
<?php } ?>
</tbody >
<tfoot>
	<tr>
		<th></th>
		<th></th>
		<th><div id="divSumAbonado"></div></th>
		<th><div id="divSumDescuento"></div></th>
		<th style="color:#ef4040;"><div id="divSumSaldo"></div></th>
		<th></th>
	</tr>
</tfoot>
</table>
</div>

<?php } ?>
<script>
$(document).ready(function () {
$('.dataTables-pagos').DataTable({
	"searching": true,
	"bSort" : false,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 20,
	"oLanguage": {
	   "sSearch": "Buscar: "
	 },
	dom: '<"html5buttons" B>lTfgitp',
		buttons: [				
			{
				extend: 'excelHtml5', footer: true, title: 'PagosInscripcion_<?=$competicion['nombre'];?>',
				exportOptions: {
					columns: [ 0, 1, 2, 3, 4 ]
				}
			}
		],
		drawCallback: function () {	  
		      var api = this.api();
		      $( '#divSumAbonado' ).text('$ '+new Intl.NumberFormat("es-CO").format(api.column( 2 ).data().sum()));
		      $( '#divSumDescuento' ).text('$ '+new Intl.NumberFormat("es-CO").format(api.column( 3 ).data().sum()));
		      $( '#divSumSaldo' ).text('$ '+new Intl.NumberFormat("es-CO").format(api.column( 4 ).data().sum()));
		    }
});
});
</script>
<?php $connect->close(); ?>