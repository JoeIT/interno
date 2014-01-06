<?php
/**
 * @author Erika Ballesteros
 * @copyright 2011
 */
 
class Sucursal{
    var $link;

	function Sucursal($link)
	{
		$this->link = $link;
	}
    
    function ingresar_sucursal($contribuyente,$direccion,$telefono,$casilla,$fax,$sucursal)
	{
	   	$data = null;
		$sql = "
			INSERT INTO `tsucursal` (`cod_cont`, `direccion`, `telefono`, `casilla`, `fax`,`sucursal`) VALUES 
			('".$contribuyente."', '".$direccion."', '".$telefono."','".$casilla."','".$fax."','".$sucursal."')
			";	
            //echo $sql;	
		mysql_query($sql,$this->link);
	}
    
 	//carga todas las sucursales existentes
	function busqueda_sucursal(){
    $data = null;
		$sql = "SELECT *  FROM `tsucursal` WHERE estado<>1;";
		$res = mysql_query($sql,$this->link);
		$i=0;
		while(	$row = mysql_fetch_array($res)	)
		{
			$data[$i]['cod_sucursal']=$row['cod_sucursal'];
			$data[$i]['sucursal']=$row['sucursal'];
			$i++;			
		}
		return $data;
	}
}
?>