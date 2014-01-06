<?php

$con=@mysql_connect("localhost","root","");
mysql_select_db("macaws_bd",$con);

$numero_asignacion = 1;
$descuentro_retraso = 5;
$cantidad_incremento = 200;

$consulta= "
SELECT
  CONCAT(de.nombre_despacho, ' ', de.fecha_despacho) AS despacho
, tda.asignacion_detalle_id AS codigo
, o.cup_num AS op
, CONCAT(p.apellidos, ' ', p.nombres) AS maquinista
, f.nombre_familia AS producto
, e.nombre_estilo AS tipo
, tco.descripcion AS color
, tdop.observacion AS obs
, SUM(tre.cantidad) AS entregada
, tda.fecha_finalizacion AS f_prevista
, tda.fecha_reprogramacion AS reprog
, MAX(tre.fecha) AS f_real
, MAX(tre.dias_retraso) AS diferencia
, (trech.cantidad) AS rechazados
, tclf.nombre_fallo
, tda.observaciones AS observacion
FROM
  `despacho` de
, `despacho_detalle` dede
, `tdetalle_asignacion` tda
, `tdetalleordenesproduccion` tdop
, `tordenesproduccion` o
, `personal` p
, `familia` f
, `estilo` e
, `tpropiedades` tpr
, `tcolores` tco
, `trecepcion` tre  LEFT JOIN `trechazo` trech ON tre.asignacion_id = trech.asignacion_id LEFT JOIN `tclasificacion_fallos` tclf ON trech.clasificacion_fallo_id = tclf.clasificacion_fallo_id
WHERE
de.despacho_id = dede.despacho_id
AND dede.asignacion_id = tda.asignacion_detalle_id
AND tda.detalle_id = tdop.detalle_id
AND tdop.orden_id = o.orden_id
AND tda.personal_id = p.personal_id
AND tdop.familia_id = f.familia_id
AND f.estilo_id = e.estilo_id
AND tdop.propiedad_id = tpr.prop_id
AND tpr.color_id = tco.color_id
AND tda.asignacion_detalle_id = tre.asignacion_id
AND de.despacho_id = ".$numero_asignacion."

GROUP BY tda.asignacion_detalle_id, trech.rechazo_id
ORDER BY tda.asignacion_detalle_id
";


echo "<h3>Pruebas para maquinista</h3>";

$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
while($row = mysql_fetch_array($resultado)){
	//********************** maquinistas **********************
	$asignacion = $row['codigo'];
	$nombre_fallo = $row['nombre_fallo'];
	
	$maquinistas[$asignacion]['codigo'] = $row['codigo'];
	$maquinistas[$asignacion]['op'] = $row['op'];
	$maquinistas[$asignacion]['maquinista'] = $row['maquinista'];
	$maquinistas[$asignacion]['producto'] = $row['producto'];
	$maquinistas[$asignacion]['tipo'] = $row['tipo'];
	$maquinistas[$asignacion]['color'] = $row['color'];
	$maquinistas[$asignacion]['obs'] = $row['obs'];
	$maquinistas[$asignacion]['entregada'] = $row['entregada'];
	$maquinistas[$asignacion]['f_prevista'] = $row['f_prevista'];
	$maquinistas[$asignacion]['reprog'] = $row['reprog'];
	$maquinistas[$asignacion]['f_real'] = $row['f_real'];
	$maquinistas[$asignacion]['diferencia'] = $row['diferencia'];

	$maquinistas[$asignacion][$nombre_fallo] += $row['rechazados'];
	$maquinistas[$asignacion]['rechazados'] = $maquinistas[$asignacion]['B'] + $maquinistas[$asignacion]['C'];
	$maquinistas[$asignacion]['aceptada'] = $maquinistas[$asignacion]['entregada'] - $maquinistas[$asignacion]['rechazados'];
	$maquinistas[$asignacion]['porcentaje'] = ($maquinistas[$asignacion]['rechazados'] * 100)/$maquinistas[$asignacion]['entregada'];

	$maquinistas[$asignacion]['d_retraso'] = $descuentro_retraso * $maquinistas[$asignacion]['diferencia'];
	$maquinistas[$asignacion]['d_rechazo'] = $maquinistas[$asignacion]['porcentaje']."%";
	
	if (($maquinistas[$asignacion]['entregada'] >= $cantidad_incremento) && ($maquinistas[$asignacion]['rechazados'] == 0))
		$maquinistas[$asignacion]['incremento'] = "SI";
	else
		$maquinistas[$asignacion]['incremento'] = "NO";
	
	$maquinistas[$asignacion]['observacion'] = $row['observacion'];
	
	
	//********************** limpiezas **********************
	//********************** arreglos **********************
}



echo "<pre>";
print_r($maquinistas);
echo "</pre>";