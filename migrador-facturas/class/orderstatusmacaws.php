<?php
class OrdenStatus
{
	var $link;
	function OrdenStatus($link)
	{
		$this->link=$link;
	}

	 function getDetailOrder($nrosale)
	 {
	  $detail=null;
	  $i=0;
	  $sql="select d.cod_detalle, o.nro_orden,o.fecha_orden
	  from detalle d, orden o
	  where d.cod_orden = o.cod_orden
	  and d.pedido =$nrosale";
	  $result=mysql_query($sql,$this->link);
	  while($row=mysql_fetch_array($result))
	  {
	  	$asignacion=$this->getInfoAsignacion($row['cod_detalle']);
	  	
		$detail[$i]['nropedido']=$nrosale;
	  	$detail[$i]['nro_orden']=$row['nro_orden'];
		$detail[$i]['fecha_orden']=$row['fecha_orden'];
		$detail[$i]['fecha_asig']=$asignacion['fecha_asig'];		
		$detail[$i]['fecha_recep']=$this->getFechaRecepcion($asignacion['id']);		
		$detail[$i]['despacho']=$this->getDespacho($row['cod_detalle']);
		
		$i++;
	  }

	  return $detail;
	 }
	 function getInfoAsignacion($cod_detalle)
	 {
	 	$sql="select id, fecha_a from asignacion where cod_detalle=$cod_detalle";
		$result=mysql_query($sql,$this->link);
		$asignacion=null;
		if($row=mysql_fetch_array($result))
		{
			$asignacion['id']=$row['id'];
			$asignacion['fecha_asig']=$row['fecha_a'];
		}
		return $asignacion;
	 }

	 function getFechaRecepcion($id)
	 {
	 	$fecha_recep="";
		if(is_numeric($id))
		{
			$sql="select fecha from recepcion where asignacion=$id";
			$result=mysql_query($sql,$this->link);
			if($row=mysql_fetch_array($result))
				$fecha_recep=$row['fecha'];
		}
		return $fecha_recep;
	 }

	 function getDespacho($cod_detalle)
	 {
	 	$sql="select d.nombrecillo
		 from despacho d,elemento e
		where d.iddespacho=e.iddespacho
		and e.cod_detalle=$cod_detalle";
		$result=mysql_query($sql,$this->link);
		$despacho="";
		if($row=mysql_fetch_array($result))
			$despacho=$row['nombrecillo'];

		return $despacho;
	 }

	 function showLatestDespachos($limit)
	 {
		 $sql="select iddespacho,nombrecillo,dia
		 from despacho order by iddespacho desc limit 0,$limit";
		 $result=mysql_query($sql,$this->link);
		 $data=null;
		 $i=0;

		 while($row=mysql_fetch_array($result))
		 {
			 $data[$i]['iddespacho']=$row['iddespacho'];
			 $data[$i]['fecha_ref']=$row['dia'];
			 $data[$i]['nombre_despacho']=$row['nombrecillo'];
			 $i++;
		 }
		 return $data;
	 }
	 function getOrdersFromDespacho($iddespacho)
	 {
		 $sql="select distinct d.pedido, sum(e.card)as cantidad
		 from elemento e, detalle d
		 where e.cod_detalle=d.cod_detalle and e.iddespacho=$iddespacho
		 group by d.pedido
		 order by d.pedido";
		 $result=mysql_query($sql,$this->link);
		 $data=null;
		 $i=0;
		 while($row=mysql_fetch_array($result))
		 {
			 $data[$i]['nrosale']=$row['pedido'];
			 $data[$i]['qty']=$row['cantidad'];
			 $i++;
		 }
		 return $data;
	 }
	 
	///////////////////////// /////////////////////para nuevo sistema interno
	 function showLatestDespachos_SI($limit)
	 {
		 $sql="select despacho_id, nombre_despacho,fecha_despacho
		 from despacho order by despacho_id desc limit 0,$limit";
		
		 $result=mysql_query($sql,$this->link);
		 $data=null;
		 $i=0;
	 while($row=mysql_fetch_array($result))
		 {
			 $data[$i]['iddespacho']=$row['despacho_id'];
			 $data[$i]['fecha_ref']=$row['fecha_despacho'];
			 $data[$i]['nombre_despacho']=$row['nombre_despacho'];
			 $i++;
		 }
		 return $data;
	 }
	 
 
	 
 
function getOrdersFromDespacho_SI($iddespacho)
	 {
		 $sql="SELECT op.pedido, sum(d.cantidad)as cantidad 
FROM tdetalleordenesproduccion op, despacho_detalle d, tdetalle_asignacion a
WHERE  d.asignacion_id=a.asignacion_detalle_id 
and a.detalle_id=op.detalle_id 
and d.despacho_id=$iddespacho 
group by op.pedido
order by op.pedido";
		 //echo $sql;
		 $result=mysql_query($sql,$this->link);
		 $data=null;
		 $i=0;
		 while($row=mysql_fetch_array($result))
		 {
			 $data[$i]['nrosale']=$row['pedido'];
			 $data[$i]['qty']=$row['cantidad'];
			 $i++;
		 }
		 return $data;
	 }
	 
 
 function getDetailOrder_SI($nrosale)
	 {
	  $detail=null;
	  $i=0;
	  	  
	  $sql=" SELECT tda.detalle_id, tdop.pedido, top.num_orden as nro_orden, top.fecha as fecha_orden,
	   tda.fecha_inicio AS fecha_asig, tre.fecha AS fecha_recep, CONCAT(de.nombre_despacho, ' ', fecha_despacho) AS despacho
FROM
`tdetalleordenesproduccion` tdop LEFT JOIN `tdetalle_asignacion` tda ON tda.detalle_id = tdop.detalle_id LEFT JOIN `trecepcion` tre ON tda.asignacion_detalle_id = tre.asignacion_id LEFT JOIN `despacho_detalle` dde ON dde.asignacion_id = tda.asignacion_detalle_id LEFT JOIN `despacho` de ON dde.despacho_id = de.despacho_id
, `tordenesproduccion` top
WHERE
top.orden_id = tdop.orden_id
AND tdop.pedido =$nrosale ";
	  	 
	  $result=mysql_query($sql,$this->link);
	  while($row=mysql_fetch_array($result))
	  {
	  	
		$detail[$i]['nropedido']=$nrosale;
	  	$detail[$i]['nro_orden']=$row['nro_orden'];
		$detail[$i]['fecha_orden']=$row['fecha_orden'];
		$detail[$i]['fecha_asig']=$row['fecha_asig'];		
		$detail[$i]['fecha_recep']=$row['fecha_recep'];;		
		$detail[$i]['despacho']=$row['despacho'];;		
		$i++;
	  }

	  return $detail;
	 }
	
	 
}
?>