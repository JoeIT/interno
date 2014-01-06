<?php
class Gestionperiodo {
	var $link;

	function Gestionperiodo($link)
	{
		$this->link = $link;
	}


	function remplazar($val)
	{
		$valor = $val;
		$valor = addslashes($val);
		return $valor;
	}

	function obener_periodoGestionOpened()
	{
		$data = null;
		$sql = "select tp.cod_pg,tp.periodo,tp.gestion,tp.estado,tp.obs,tp.iva from tperiodogestion as tp,tgestion as tg where tp.estado = 1 and tg.estado = 1 and tp.gestion = tg.gestion;";

		$res = mysql_query($sql,$this->link);
		$i = 0;
		while($row = mysql_fetch_array($res))
		{
			if($row['estado']!=0)
			{
				$data[$i]['cod_pg']=$row['cod_pg'];
				$data[$i]['periodo']=$row['periodo'];
				$data[$i]['gestion']=$row['gestion'];
				$data[$i]['estado']=$row['estado'];
				$data[$i]['obs']=$row['obs'];
				$data[$i]['iva']=$row['iva'];
				$i++;
			}
		}
		return $data;
	}

	function obener_periodoGestionOpened2()
	{
		$data = null;
		$sql = "select cod_pg,periodo,gestion,estado,obs,iva from tperiodogestion where estado = 1 group by gestion;";

		$res = mysql_query($sql,$this->link);
		$i = 0;
		while($row = mysql_fetch_array($res))
		{
			if($row['estado']!=0)
			{
				$data[$i]['cod_pg']=$row['cod_pg'];
				$data[$i]['periodo']=$row['periodo'];
				$data[$i]['gestion']=$row['gestion'];
				$data[$i]['estado']=$row['estado'];
				$data[$i]['obs']=$row['obs'];
				$data[$i]['iva']=$row['iva'];
				$i++;
			}
		}
		return $data;
	}

	function obener_periodoGestionOpened3()
	{
		$data = null;
		$sql = "select gestion,estado,obs from tgestion where estado = 1 group by gestion;";

		$res = mysql_query($sql,$this->link);
		$i = 0;
		while($row = mysql_fetch_array($res))
		{

			$data[$i]['gestiont']=$row['gestion'];
			$data[$i]['gestion']=base64_encode($row['gestion']);
			$data[$i]['estado']=$row['estado'];
			$data[$i]['obs']=$row['obs'];
			$i++;
		}
		return $data;
	}

	function obenerPeriodosGestion($gestion)
	{
		$data = null;
		$sql = "select tp.cod_pg,tp.periodo,tp.gestion,tp.estado,tp.obs,tp.iva from tperiodogestion as tp,tgestion as tg where tp.estado = 1 and tg.estado = 1 and tp.gestion = tg.gestion and tg.gestion='$gestion';";

		$res = mysql_query($sql,$this->link);
		$i = 0;
		while($row = mysql_fetch_array($res))
		{
			if($row['estado']!=0)
			{
				$data[$i]['cod_pg']=$row['cod_pg'];
				$data[$i]['periodo']=$row['periodo'];
				$data[$i]['gestion']=$row['gestion'];
				$data[$i]['estado']=$row['estado'];
				$data[$i]['obs']=$row['obs'];
				$data[$i]['iva']=$row['iva'];
				$i++;
			}
		}
		return $data;
	}

	function obener_periodoGestion($cod_pg)
	{
		$data = null;
		$sql = "select tp.cod_pg,tp.periodo,tp.gestion,tp.estado,tp.obs,tp.iva from tperiodogestion as tp,tgestion as tg where tp.cod_pg = '$cod_pg' and tp.estado='1' and tg.estado = 1 and tp.gestion = tg.gestion;";
		$res = mysql_query($sql,$this->link);
		while($row = mysql_fetch_array($res))
		{
			$data['cod_pg']=$row['cod_pg'];
			$data['periodo']=$row['periodo'];
			$data['gestion']=$row['gestion'];
			$data['estado']=$row['estado'];
			$data['obs']=$row['obs'];
			$data['iva']=$row['iva'];
		}
		return $data;
	}

	function obtenerUltimoPeriodoGestion($gestion = "")
	{
		$data = null;
		$sql = "select cod_pg,periodo,gestion,estado,obs,iva from tperiodogestion where cod_pg in
				(select MAX(cod_pg) from tperiodogestion where gestion = '$gestion');";
		$res = mysql_query($sql,$this->link);
		while($row = mysql_fetch_array($res))
		{
			$data['periodo']=$row['periodo'];
			$data['gestion']=$row['gestion'];
		}
		return $data;
	}

	function CantidadPeriodosAbiertos($gestion = "")
	{
		$data = null;
		if(isset($gestion) && !empty($gestion))
		$sql = "select cod_pg from tperiodogestion tp where estado = 1 and gestion ='$gestion';";
		else
		$sql = "select cod_pg from tperiodogestion tp where estado = 1 ";

		$res = mysql_query($sql,$this->link);
		$i = 0;
		while($row = mysql_fetch_array($res))
		$i++;

		return $i;
	}

	function todosPeriodoGestion($gestion)
	{
		$data = null;
		$sql = "select cod_pg,periodo,gestion,estado,obs,iva from tperiodogestion where gestion ='$gestion' order by periodo;";
		$res = mysql_query($sql,$this->link);
		$i=0;
		while($row = mysql_fetch_array($res))
		{
			$data[$i]['cod_pg']=$row['cod_pg'];
			$data[$i]['periodo']=$row['periodo'];
			$data[$i]['gestion']=$row['gestion'];
			$data[$i]['estado']=$row['estado'];
			$data[$i]['obs']=$row['obs'];
			$data[$i]['iva']=$row['iva'];
			$i++;
		}
		return $data;
	}

	function insert_PeriodoGestion($gestion,$periodo,$obs,$iva,$usuario_id)
	{

		$obs=$this->remplazar($obs);
		$sql = "insert into tperiodogestion (gestion,periodo,estado,obs,iva,usuario_id) values('$gestion','$periodo','1','$obs','$iva','$usuario_id');";
		mysql_query($sql,$this->link);
	}

	function close_Periodo($cod_pg)
	{
		$sql = "update tperiodogestion set estado = 0 where cod_pg='$cod_pg';";
		mysql_query($sql,$this->link);
	}

	function open_Periodo($cod_pg)
	{
		$sql = "update tperiodogestion set estado = 1 where cod_pg='$cod_pg';";
		mysql_query($sql,$this->link);
	}

	function obtenerRazonSocialNit($nit)
	{
		$data = null;
		$sql = "select razon_social from tcompra where nit='$nit';";
		$res=mysql_query($sql,$this->link);
		if ($row = mysql_fetch_array($res) )
		{
			$data = $row['razon_social'];
		}
		else
		{
			$sql = "select razon_social from tventa where nit='$nit';";
			$res=mysql_query($sql,$this->link);
			if ($row = mysql_fetch_array($res) )
			{
				$data = $row['razon_social'];
			}
		}
		return $data;
	}

}
?>