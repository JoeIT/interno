<?php
class Personal {
	var $link;

	function Personal($link)
	{
		$this->link = $link;
	}
	
	function obtenerPersonal($id_personal)
	{
		$data = null;
		$sql = "select ci,nombres,apellidos,usuario_id from personal where usuario_id='$id_personal'";
		$res = mysql_query($sql,$this->link);
		if($row = mysql_fetch_array($res))
		{
			$data['ci']=$row['ci'];
			$data['nombres']=$row['nombres'];
			$data['apellidos']=$row['apellidos'];
			$data['usuario_id']=$row['usuario_id'];
		}
		return $data;
	}


	
}
?>