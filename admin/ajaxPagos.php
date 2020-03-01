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
//Consultar competicion:
$competicion = mysqli_fetch_array($connect->query( "select * from competicion where id =".$idComp ));	
//Consultar pagos de competicion
$resultPagos = $connect->query( "select p.*, e.nombre, tp.nombre tipoPago from pago p join equipo e on p.id_equipo = e.id and p.id_competicion = ".$idComp."  
and (descuento is null or descuento =0) and UPPER(detalle) not like '%INSCRIP%' left join tipo_pago tp on p.id_tipo_pago = tp.id order by  e.nombre asc" );	
	
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
	
?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-pagos">
<thead>
	<tr>		
		<th>Equipo</th>
		<th>Valor Pagado</th>
		<th>Fecha</th>
		<th>Tipo de Pago</th>
		<th>Descripción</th>
	</tr>
</thead>
<tbody>
<?php
$total = 0;
while($row = mysqli_fetch_array($resultPagos)){    
    $date_f = new DateTime($row['fecha']);
    $date_f = $date_f->format('d-m-Y');
    $total += $row['abono'];
?>	
<tr>	
	<td><?=$row['nombre'];?></td>
	<td><?='$ '.number_format($row['abono']);?></td>
	<td><?=$date_f;?></td>
	<td><?=$row['tipoPago']; ?>	</td>
	<td><?=$row['detalle'];?></strong>	</td>
</tr>
<?php } ?>
</tbody >
<tfoot>
	<tr>
		<th></th>
		<th><div id="divSum">00</div></th>
		<th></th>
		<th></th>
		<th></th>
	</tr>
</tfoot>
</table>
</div>
<?php } ?>
<script>
$(document).ready(function () {
$table = $('.dataTables-pagos').DataTable({
	"searching": true,
	"bSort" : true,
	"bLengthChange": false,
	"bInfo": false,
	"pageLength": 20,
	"oLanguage": {
	   "sSearch": "Buscar: "
	 },
	dom: '<"html5buttons" B>lTfgitp',
		buttons: [				
			{
				extend: 'excelHtml5', footer: true, title: 'ReportePagos_<?=$competicion['nombre'];?>',
				exportOptions: {
					columns: [ 0, 1, 2, 3, 4 ]
				}
			}
		],
	drawCallback: function () {	  
      var api = this.api();
      $( '#divSum' ).text('$ '+new Intl.NumberFormat("es-CO").format(api.column( 1, {filter:'applied'} ).data().sum()));
      /*$( api.table().footer() ).html(
		'<tr>'+
    		'<th></th>'+
    		'<th><strong> $'+ new Intl.NumberFormat("es-CO").format(api.column( 1, {filter:'applied'} ).data().sum()) +'</strong></th>'+
    		'<th></th>'+
    		'<th></th>'+
    		'<th></th>'+
    	'</tr>'       
      );*/
    }
});
});
</script>
<?php $connect->close(); ?>