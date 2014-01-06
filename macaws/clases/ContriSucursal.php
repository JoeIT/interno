<?php
class ContriSucursal {
	var $link;

	function ContriSucursal($link)
	{
		$this->link = $link;
	}

	function obtenerContriSucursal($sucursal)
	{
		$data = null;
        if($sucursal=='todos')
		$sql = "select cod_cont,nit,razon_social,direccion,telefono,casilla,fax from tcontribuyente;";
        else
        $sql="select tcon.cod_cont,tcon.nit,tcon.razon_social,ts.direccion,ts.telefono,ts.casilla,ts.fax from tsucursal as ts,tcontribuyente as tcon where ts.cod_cont=tcon.cod_cont and cod_sucursal='$sucursal';";
		$res = mysql_query($sql);
		if ($row = mysql_fetch_array($res))
		{
			$data['cod_cont']=$row['cod_cont'];
			$data['nit']=$row['nit'];
            if($sucursal=='1' || $sucursal!='1')
			$data['razon_social']=$row['razon_social'];
			$data['direccion']=$row['direccion'];
			$data['telefono']=$row['telefono'];
			$data['casilla']=$row['casilla'];
			$data['fax']=$row['fax'];
			if($sucursal=='todos')
			$data['sucursal']="Todos";
            if($sucursal=='1' )
			$data['sucursal']="Central";
            if($sucursal!='todos' && $sucursal!='1')
            $data['sucursal']=$sucursal-1;
		}
		return $data;
	}
    //carga todas las sucursales existentes
	function Contribuyentes(){
    $data = null;
		$sql = "SELECT * FROM `tcontribuyente`;";
		$res = mysql_query($sql,$this->link);
		$i=0;
		while(	$row = mysql_fetch_array($res)	)
		{
			$data[$i]['cod_cont']=$row['cod_cont'];
			$data[$i]['nit']=$row['nit'];
			$i++;			
		}
		return $data;
	}
}
?>