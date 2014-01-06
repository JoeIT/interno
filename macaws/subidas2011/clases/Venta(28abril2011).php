<?php
class Venta {
	var $link;

	function Venta($link)
	{
		$this->link = $link;
	}

	function remplazar($val)
	{
		$valor = $val;
		$valor = addslashes($val);
		/*if (!strpos("\'", $valor)) {
		str_replace("'", "\'", $valor);
		}
		echo $valor ;*/
		return $valor;
	}

	function validarFactura($factura,$autorizacion,$nit)
	{
		$data = false;
		$sql = "select cod_compra from tcompra where factura='$factura' and autorizacion='$autorizacion' and nit='$nit' ";
		
		$res = mysql_query($sql,$this->link);
		if ($row  = mysql_fetch_array($res)) {
			$data = true;
		}
		else
		{
			$sql = "select cod_venta from tventa where factura='$factura' and autorizacion='$autorizacion' and nit='$nit' ";
		
			$res = mysql_query($sql,$this->link);
			if ($row  = mysql_fetch_array($res)) {
				$data = true;
			}
		}
		return $data;
	}

	function validarFacturaEdicion($factura,$autorizacion,$nit,$codigo)
	{
		$data = false;
		$sql = "select cod_compra from tcompra where factura='$factura' and autorizacion='$autorizacion' and nit='$nit' and cod_compra <> $codigo;";
		
		$res = mysql_query($sql,$this->link);
		if ($row  = mysql_fetch_array($res)) {
			$data = true;
		}
		else
		{
			$sql = "select cod_venta from tventa where factura='$factura' and autorizacion='$autorizacion' and nit='$nit' and cod_venta <> $codigo ";
		
			$res = mysql_query($sql,$this->link);
			if ($row  = mysql_fetch_array($res)) {
				$data = true;
			}
		}
		return $data;
	}
	
	function insert_Venta($fecha,$nit,$razonsocial,$factura,$autorizacion,$codigo_control,$total_facturado,$ice,$exentos,$total_exentos,$iva,$cod_pg,$usuario_id,$tipo)
	{
		$data = null;
		$sql = "INSERT INTO tventa (fecha, nit, razon_social, factura, autorizacion, codigo_control, total_facturado,
				ice, exento, importe, credito_fiscal,cod_pg,usuario_id,fechareg,tipo) VALUES ('$fecha', '$nit', '$razonsocial',
				'$factura', '$autorizacion', '$codigo_control', '$total_facturado','$ice','$exentos','$total_exentos','$iva','$cod_pg','$usuario_id',now(),'$tipo');";				
		//echo $sql;
		mysql_query($sql,$this->link);
	}
	
	function buscarVenta($codigo)
	{
		$data = null;
		$sql = "select cod_venta,fecha,nit,razon_social,factura,
				autorizacion,codigo_control,total_facturado,ice,
				exento,importe,credito_fiscal,cod_pg,usuario_id,fechareg,tipo 
				from tventa where cod_venta='$codigo';";
		$res = mysql_query($sql,$this->link);
		$i=0;
		if(	$row = mysql_fetch_array($res)	)
		{
			$data['cod_venta']=$row['cod_compra'];
			$data['fecha']=$row['fecha'];
			$data['nit']=$row['nit'];
			$data['razon_social']=$row['razon_social'];
			$data['factura']=$row['factura'];
			$data['autorizacion']=$row['autorizacion'];
			$data['codigo_control']=$row['codigo_control'];
			$data['total_facturado']=$row['total_facturado'];
			$data['ice']=$row['ice'];
			$data['exento']=$row['exento'];
			$data['importe']=$row['importe'];
			$data['credito_fiscal']=$row['credito_fiscal'];
			$data['cod_pg']=$row['cod_pg'];
			$data['usuario_id']=$row['usuario_id'];
			$data['fechareg']=$row['fechareg'];
			$data['tipovent']=$row['tipo'];			
		}
		return $data;
	}
	
	function updateVenta($fecha,$nit,$razonsocial,$factura,$autorizacion,$codigo_control,$total_facturado,$ice,$exentos,$total_exentos,$iva,$cod_pg,$usuario_id,$tipoventa,$codigo)
	{
		$sql = "UPDATE tventa SET fecha='$fecha', nit='$nit', razon_social='$razonsocial', factura='$factura', autorizacion='$autorizacion', codigo_control='$codigo_control', total_facturado='$total_facturado', ice='$ice', exento='$exentos', importe='$total_exentos', credito_fiscal='$iva', usuario_id='$usuario_id', fechareg=now(),tipo='$tipoventa' WHERE cod_venta='$codigo';";
		mysql_query($sql,$this->link);
	}
	
	function eliminarFacturaVenta($codigo)
	{
		$sql = "delete from tventa where cod_venta=$codigo;";
		mysql_query($sql,$this->link);
	}
	
}
?>