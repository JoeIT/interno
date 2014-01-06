<?php
class Gestion {
	var $link;

	function Gestion($link)
	{
		$this->link = $link;
	}

	function remplazar($val)
	{
		$valor = $val;
		$valor = addslashes($val);
		return $valor;
	}
	
	function listarTodasGestiones()
	{
		$fata = null;
		$sql = "select gestion,estado,obs,usuario_id
		 from tgestion order by gestion DESC;";
		$res = mysql_query($sql,$this->link);
		$i = 0;
		while($row=mysql_fetch_array($res))
		{
			$data[$i]['gestion']=$row['gestion'];
			$data[$i]['estado']=$row['estado'];
			$data[$i]['obs']=$row['obs'];
			$data[$i]['usuario_id']=$row['usuario_id'];
			$i++;
		}
		return $data;		
	}
	
	function listarGestionesAbiertas()
	{
		$data = null;
		$sql = "select gestion,estado,obs,usuario_id from tgestion where estado ='1';";
		$res = mysql_query($sql,$this->link);
		$i=0;
		while ($row = mysql_fetch_array($res)) 
		{
			$data[$i]['gestion']=$row['gestion'];
			$data[$i]['estado']=$row['estado'];
			$data[$i]['obs']=$row['obs'];
			$data[$i]['usuario_id']=$row['usuario_id'];			
			$i++;
		}
		return $data;
	}
	
	function totalGestionesAbiertas()
	{
		$data = null;
		$sql = "select count(gestion) as total from tgestion where estado ='1' group by estado;";
		$res = mysql_query($sql,$this->link);		
		while ($row = mysql_fetch_array($res)) 
		{
			$data = $row['total'];
		}
		return $data;
	}
	
	function exiteGestionEstado($gestion)
	{
		$data = null;
		$sql = "select estado from tgestion where gestion = '$gestion';";
		$res = mysql_query($sql,$this->link);
		if($row  = mysql_fetch_array($res))
		$data = $row['estado'];
		return $data;
	}

	function insertGestion($gestion,$obs,$usuario_id)
	{
		$obs = $this->remplazar($obs);
		$sql = "insert into tgestion (gestion,estado,obs,usuario_id) values('$gestion','1','$obs','$usuario_id');";
		mysql_query($sql,$this->link);
	}

	function close_gestion($gestion)
	{
		$sql = "update tgestion set estado = '0' where gestion ='$gestion';";
		mysql_query($sql,$this->link);
		$sql = "update tperiodogestion set estado = '0' where gestion ='$gestion';";
		mysql_query($sql,$this->link);
	}
	
	function open_gestion($gestion)
	{
		$sql = "update tgestion set estado = 1 where gestion ='$gestion';";
		mysql_query($sql,$this->link);
	}
	
	
}
?>