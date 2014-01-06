<?php
class Compra {
	var $link;

	function Compra($link)
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

	function insert_Compra($fecha,$nit,$razonsocial,$factura,$autorizacion,$codigo_control,$total_facturado,$ice,$exentos,$total_exentos,$iva,$cod_pg,$alfanumerico,$usuario_id,$sucursal_id)
	{
		$data = null;
		$sql = "INSERT INTO tcompra (fecha, nit, razon_social, factura, autorizacion, codigo_control, total_facturado,
				ice, exento, importe, credito_fiscal,cod_pg,usuario_id,fechareg,alfanumerico,sucursal_id) VALUES ('$fecha', '$nit', '$razonsocial',
				'$factura', '$autorizacion', '$codigo_control', '$total_facturado','$ice','$exentos','$total_exentos','$iva','$cod_pg','$usuario_id',now(),'$alfanumerico','$sucursal_id');";		
		mysql_query($sql,$this->link);
	}

	function buscarcompra($codigo)
	{
		$data = null;
		$sql = "select cod_compra,fecha,nit,razon_social,factura,
				autorizacion,codigo_control,total_facturado,ice,
				exento,importe,credito_fiscal,cod_pg,usuario_id,fechareg,sucursal_id 
				from tcompra where cod_compra='$codigo';";
		$res = mysql_query($sql,$this->link);
		$i=0;
		if(	$row = mysql_fetch_array($res)	)
		{
			$data['cod_compra']=$row['cod_compra'];
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
            $data['sucursal']=$row['sucursal_id'];
		}
		return $data;
	}

	function updateCompra($fecha,$nit,$razonsocial,$factura,$autorizacion,$codigo_control,$total_facturado,$ice,$exentos,$total_exentos,$iva,$cod_pg,$usuario_id,$codigo,$sucursal)
	{
		$sql = "UPDATE tcompra SET fecha='$fecha', nit='$nit', razon_social='$razonsocial', factura='$factura', autorizacion='$autorizacion', codigo_control='$codigo_control', total_facturado='$total_facturado', ice='$ice', exento='$exentos', importe='$total_exentos', credito_fiscal='$iva', usuario_id='$usuario_id', fechareg=now(),sucursal_id='$sucursal' WHERE cod_compra='$codigo';";
	
		mysql_query($sql,$this->link);
	}
	
	function eliminarFacturaCompra($codigo)
	{
		$sql = "delete from tcompra where cod_compra=$codigo;";		
		mysql_query($sql,$this->link);
	}
}
?>