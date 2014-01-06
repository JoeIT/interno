<?php
class ContriSucursal {
	var $link;

	function ContriSucursal($link)
	{
		$this->link = $link;
	}

	function obtenerContriSucursal()
	{
		$data = null;
		$sql = "select cod_cont,nit,razon_social,direccion,telefono,casilla,fax from tcontribuyente;";
		$res = mysql_query($sql);
		if ($row = mysql_fetch_array($res))
		{
			$data['cod_cont']=$row['cod_cont'];
			$data['nit']=$row['nit'];
			$data['razon_social']=$row['razon_social'];
			$data['direccion']=$row['direccion'];
			$data['telefono']=$row['telefono'];
			$data['casilla']=$row['casilla'];
			$data['fax']=$row['fax'];
			$data['sucursal']="Central";
		}
		return $data;
	}
}
?>