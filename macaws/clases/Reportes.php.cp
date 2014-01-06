<?php
class Reportes {
	var $link;

	function Reportes($link)
	{
		$this->link = $link;
	}

	function buscar_Compras($texto_search,$criterio,$gestion,$periodo,$fec,$sucursal)
	{
		$data = null;
		$i=0;
		$text_gestion = "";
		$text_periodo = "";
		$text_fecha = "";
		$text_criterio = "";

		if(strcmp($criterio,"total_facturado")==0)
		$text_criterio = " and tc.$criterio = '$texto_search'";
		else
		$text_criterio = " and tc.$criterio like '%$texto_search%'";

		if($gestion!=0)
		$text_gestion = " and tg.gestion='$gestion'";
		if($periodo!=0)
		{
			if ($periodo<10)
			$periodo="0$periodo";
			$text_periodo = " and tpg.periodo='$periodo'";
		}

		if (isset($fec)&&!empty($fec))
		{
			$text_fecha = " and tc.fecha='$fec'";
		}

        if(strcmp($sucursal,"todos")==0)
		$sql = "select tc.*,tpg.gestion,tpg.periodo,tpg.estado from tcompra as tc, tgestion as tg, tperiodogestion as tpg
				where
				tg.gestion = tpg.gestion 
				and tc.cod_pg = tpg.cod_pg $text_gestion $text_periodo $text_fecha $text_criterio;";
        else
            $sql = "select tc.*,tpg.gestion,tpg.periodo,tpg.estado from tcompra as tc, tgestion as tg, tperiodogestion as tpg
				where
				tg.gestion = tpg.gestion 
				and tc.cod_pg = tpg.cod_pg and tc.sucursal_id = '".$sucursal."' $text_gestion $text_periodo $text_fecha $text_criterio;";
		//echo "$sql<br>";
		$res = mysql_query($sql,$this->link);
		while($row = mysql_fetch_array($res))
		{
			$data[$i]['cod_compra']=$row['cod_compra'];
			$data[$i]['fecha']=$row['fecha'];
			$data[$i]['nit']=$row['nit'];
			$data[$i]['razon_social']=$row['razon_social'];
			$data[$i]['factura']=$row['factura'];
			$data[$i]['autorizacion']=$row['autorizacion'];
			$data[$i]['codigo_control']=$row['codigo_control'];
			$data[$i]['total_facturado']=$row['total_facturado'];
			$data[$i]['ice']=$row['ice'];
			$data[$i]['exento']=$row['exento'];
			$data[$i]['importe']=$row['importe'];
			$data[$i]['credito_fiscal']=$row['credito_fiscal'];
			$data[$i]['cod_pg']=$row['cod_pg'];
			$data[$i]['usuario_id']=$row['usuario_id'];
			$data[$i]['fecha_reg']=$row['fechareg'];
            $data[$i]['sucursal_id']=$row['sucursal_id'];
			$data[$i]['periodo']=$row['periodo'];
			$data[$i]['gestion']=$row['gestion'];
			$data[$i]['estado']=$row['estado'];
			$data[$i]['tipo']="Compra";
			$i++;
		}
		return $data;
	}

	function buscar_Ventas($texto_search,$criterio,$gestion,$periodo,$fec,$sucursal)
	{
		$data = null;
		$i=0;
		$text_gestion = "";
		$text_periodo = "";
		$text_fecha = "";
		$text_criterio = "";

		if(strcmp($criterio,"total_facturado")==0)
		$text_criterio = " and tv.$criterio = '$texto_search'";
		else
		$text_criterio = " and tv.$criterio like '%$texto_search%'";

		if($gestion!=0)
		$text_gestion = " and tg.gestion='$gestion'";
		if($periodo!=0)
		{
			if ($periodo<10)
			$periodo="0$periodo";
			$text_periodo = " and tpg.periodo='$periodo'";
		}

		if (isset($fec)&&!empty($fec))
		{
			$text_fecha = " and tv.fecha='$fec'";
		}

        if(strcmp($sucursal,"todos")==0)
		$sql = "select tv.*,tpg.gestion,tpg.periodo,tpg.estado from tventa as tv, tgestion as tg, tperiodogestion as tpg
				where
				tg.gestion = tpg.gestion 
				and tv.cod_pg = tpg.cod_pg $text_gestion $text_periodo $text_fecha $text_criterio;";
        else
        {
            $sql = "select tv.*,tpg.gestion,tpg.periodo,tpg.estado from tventa as tv, tgestion as tg, tperiodogestion as tpg
				where
				tg.gestion = tpg.gestion 
				and tv.cod_pg = tpg.cod_pg and tv.sucursal_id = '".$sucursal."' $text_gestion $text_periodo $text_fecha $text_criterio;";
        }
		//echo "$sql<br>";
		$res = mysql_query($sql,$this->link);
		while($row = mysql_fetch_array($res))
		{
			$data[$i]['cod_venta']=$row['cod_venta'];
			$data[$i]['fecha']=$row['fecha'];
			$data[$i]['nit']=$row['nit'];
			$data[$i]['razon_social']=$row['razon_social'];
			$data[$i]['factura']=$row['factura'];
			$data[$i]['autorizacion']=$row['autorizacion'];
			$data[$i]['codigo_control']=$row['codigo_control'];
			$data[$i]['total_facturado']=$row['total_facturado'];
			$data[$i]['ice']=$row['ice'];
			$data[$i]['exento']=$row['exento'];
			$data[$i]['importe']=$row['importe'];
			$data[$i]['credito_fiscal']=$row['credito_fiscal'];
			$data[$i]['cod_pg']=$row['cod_pg'];
			$data[$i]['usuario_id']=$row['usuario_id'];
			$data[$i]['fecha_reg']=$row['fechareg'];
            $data[$i]['sucursal_id']=$row['sucursal_id'];
			$data[$i]['periodo']=$row['periodo'];
			$data[$i]['gestion']=$row['gestion'];
			$data[$i]['estado']=$row['estado'];
			$data[$i]['tipo']="Venta";
			$i++;
		}
		return $data;
	}
	function validarGestionPeriodo($gestion,$periodo)
	{
		$data = null;
		if($periodo<10)
		$periodo="0$periodo";
		$sql = "select usuario_id from tperiodogestion where gestion='$gestion' and periodo='$periodo' and estado='0';";
		//echo $sql;
		$res = mysql_query($sql,$this->link);
		if($row =mysql_fetch_array($res))
		{
			$data= $row['usuario_id'];
		}
		return $data;
	}

	function validarGestionPeriodoCodigo($codigo)
	{
		$data = null;
		if($periodo<10)
		$periodo="0$periodo";
		$sql = "select usuario_id from tperiodogestion where cod_pg='$codigo' and estado='1';";
		//echo $sql;
		$res = mysql_query($sql,$this->link);
		if($row =mysql_fetch_array($res))
		{
			$data= $row['usuario_id'];
		}
		return $data;
	}

	function redondearCantidad($var)
	{
		$var = (round(	($var*100)	))/100;
		return $var;
	}


	function truncarCantidad($var)
	{
		$var = $var*100;
		$var2 = explode(".",$var);
		$var = (floor(	$var2[0]	))/100;
		return $var;
	}

	function buscar_ComprasReporte($texto_search,$criterio,$gestion,$periodo,$fec,$ini,$fin,$sucursal)
	{
		$data = null;
		$i=0;
		$totalfact = 0;$totalice = 0;
		$totalexen = 0;$totalimp = 0;
		$totaliva = 0;
		$text_gestion = "";
		$text_periodo = "";
		$text_fecha = "";
		$text_criterio = "";
		$limite = "";
		//echo "     intervalo $ini - $fin <br>";
		if(strcmp($criterio,"total_facturado")==0)
		$text_criterio = " and tc.$criterio = '$texto_search'";
		else
		$text_criterio = " and tc.$criterio like '%$texto_search%'";

		if($gestion!=0)
		$text_gestion = " and tg.gestion='$gestion'";
		if($periodo!=0)
		{
			if ($periodo<10)
			$periodo="0$periodo";
			$text_periodo = " and tpg.periodo='$periodo'";
		}

		if (isset($fec)&&!empty($fec))
		{
			$text_fecha = " and tc.fecha='$fec'";
		}
		if(isset($ini)&& isset($fin))
		{
			$limite= " limit $ini,$fin";
		}

        if(strcmp($sucursal,"todos")==0)
		$sql = "select tc.*,tpg.gestion,tpg.periodo,tpg.estado from tcompra as tc, tgestion as tg, tperiodogestion as tpg
				where
				tg.gestion = tpg.gestion 
				and tc.cod_pg = tpg.cod_pg $text_gestion $text_periodo $text_fecha $text_criterio order by tc.fecha $limite;";
        else
        $sql = "select tc.*,tpg.gestion,tpg.periodo,tpg.estado from tcompra as tc, tgestion as tg, tperiodogestion as tpg
				where
				tg.gestion = tpg.gestion 
				and tc.cod_pg = tpg.cod_pg and tc.sucursal_id = '$sucursal' $text_gestion $text_periodo $text_fecha $text_criterio order by tc.fecha $limite;";
		//echo "$sql<br>";
		$res = mysql_query($sql,$this->link);
		while($row = mysql_fetch_array($res))
		{
			$data[$i]['cod_compra']=$row['cod_compra'];
			$data[$i]['fecha']=$row['fecha'];
			$data[$i]['nit']=$row['nit'];
			$data[$i]['razon_social']=$row['razon_social'];
			$data[$i]['factura']=$row['factura'];
			$data[$i]['autorizacion']=$row['autorizacion'];
			$data[$i]['codigo_control']=$row['codigo_control'];
			$data[$i]['total_facturado']= $this->truncarCantidad($row['total_facturado']);
			$data[$i]['ice']= $this->truncarCantidad($row['ice']);
			$data[$i]['exento']= $this->truncarCantidad($row['exento']);
			$data[$i]['importe']= $this->truncarCantidad($row['importe']);
			$data[$i]['credito_fiscal']= $this->truncarCantidad($row['credito_fiscal']);
			$data[$i]['cod_pg']=$row['cod_pg'];
			$data[$i]['usuario_id']=$row['usuario_id'];
            $data[$i]['sucursal_id']=$row['sucursal_id'];
			$data[$i]['fecha_reg']=$row['fechareg'];
			$data[$i]['periodo']=$row['periodo'];
			$data[$i]['gestion']=$row['gestion'];
			$data[$i]['estado']=$row['estado'];
			$data[$i]['tipo']="Compra";
			$totalfact += $row['total_facturado'];
			$totalice += $row['ice'];
			$totalexen += $row['exento'];
			$totalimp += $row['importe'];
			$totaliva += $row['credito_fiscal'];
			$i++;
		}
		if($data!=null)
		{
			$data[$i-1]['totalfact']= $this->truncarCantidad($totalfact);
			$data[$i-1]['totalice']= $this->truncarCantidad($totalice);
			$data[$i-1]['totalexen']= $this->truncarCantidad($totalexen);
			$data[$i-1]['totalimp']= $this->truncarCantidad($totalimp);
			$data[$i-1]['totaliva']= $this->truncarCantidad($totaliva);
		}
		return $data;
	}

	function buscar_VentasReportes($texto_search,$criterio,$gestion,$periodo,$fec,$ini,$fin,$tipoventa=1,$sucursal)
	{
		$data = null;
		$i=0;
		$totalfact = 0;$totalice = 0;
		$totalexen = 0;$totalimp = 0;
		$totaliva = 0;
		$text_gestion = "";
		$text_periodo = "";
		$text_fecha = "";
		$limite = "";
		$tipodeventa="";
		
		if($tipoventa==2)
		$tipodeventa=" and tv.tipo='1' ";
		elseif ($tipoventa==3)
		$tipodeventa=" and tv.tipo='2' ";
				

		$text_criterio = "";
		$text_criterio = " and tv.$criterio like '%$texto_search%'";

		if($gestion!=0)
		$text_gestion = " and tg.gestion='$gestion'";
		if($periodo!=0)
		{
			if ($periodo<10)
			$periodo="0$periodo";
			$text_periodo = " and tpg.periodo='$periodo'";
		}

		if (isset($fec)&&!empty($fec))
		{
			$text_fecha = " and tv.fecha='$fec'";
		}

		if (isset($fec)&&!empty($fec))
		{
			$text_fecha = " and tc.fecha='$fec'";
		}
		if(isset($ini)&& isset($fin))
		{
			$limite= " limit $ini,$fin ";
		}
        if(strcmp($sucursal,"todos")==0)
		$sql = "select tv.*,tpg.gestion,tpg.periodo,tpg.estado from tventa as tv, tgestion as tg, tperiodogestion as tpg
				where
				tg.gestion = tpg.gestion 
				and tv.cod_pg = tpg.cod_pg $text_gestion $text_periodo $text_fecha $text_criterio $tipodeventa order by tv.fecha,tv.factura $limite;";
        else
        $sql = "select tv.*,tpg.gestion,tpg.periodo,tpg.estado from tventa as tv, tgestion as tg, tperiodogestion as tpg
				where
				tg.gestion = tpg.gestion 
				and tv.cod_pg = tpg.cod_pg and tv.sucursal_id = '$sucursal'  $text_gestion $text_periodo $text_fecha $text_criterio $tipodeventa order by tv.fecha,tv.factura $limite;";
		//echo "$sql<br>";
		$res = mysql_query($sql,$this->link);
		while($row = mysql_fetch_array($res))
		{
			$data[$i]['cod_venta']=$row['cod_venta'];
			$data[$i]['fecha']=$row['fecha'];
			$data[$i]['nit']=$row['nit'];
			$data[$i]['razon_social']=$row['razon_social'];
			$data[$i]['factura']=$row['factura'];
			$data[$i]['autorizacion']=$row['autorizacion'];
			$data[$i]['codigo_control']=$row['codigo_control'];
			$data[$i]['total_facturado']=$this->truncarCantidad($row['total_facturado']);
			$data[$i]['ice']=$this->truncarCantidad($row['ice']);
			$data[$i]['exento']=$this->truncarCantidad($row['exento']);
			$data[$i]['importe']=$this->truncarCantidad($row['importe']);
			$data[$i]['credito_fiscal']=$this->truncarCantidad($row['credito_fiscal']);
			$data[$i]['cod_pg']=$row['cod_pg'];
			$data[$i]['usuario_id']=$row['usuario_id'];
            $data[$i]['sucursal_id']=$row['sucursal_id'];
			$data[$i]['fecha_reg']=$row['fechareg'];
			$data[$i]['periodo']=$row['periodo'];
			$data[$i]['gestion']=$row['gestion'];
			$data[$i]['estado']=$row['estado'];
			$data[$i]['tipo']="Venta";
			$totalfact += $row['total_facturado'];
			$totalice += $row['ice'];
			$totalexen += $row['exento'];
			$totalimp += $row['importe'];
			$totaliva += $row['credito_fiscal'];
			$i++;
		}
		if($data!=null)
		{
			$data[$i-1]['totalfact']=$this->truncarCantidad($totalfact);
			$data[$i-1]['totalice']=$this->truncarCantidad($totalice);
			$data[$i-1]['totalexen']=$this->truncarCantidad($totalexen);
			$data[$i-1]['totalimp']=$this->truncarCantidad($totalimp);
			$data[$i-1]['totaliva']=$this->truncarCantidad($totaliva);
		}
		//print_r("<pre>");
		//print_r($data);
		return $data;
	}

	function CompraTotalesGenerales($gestion,$periodo,$sucursal)
	{
		$data = null;
		$totalfact = 0;$totalice = 0;
		$totalexen = 0;$totalimp = 0;
		$totaliva = 0;
		if($periodo!=0)
		{
			if ($periodo<10)
			$periodo="0$periodo";
		}
        if($sucursal=='todos')
		$sql = "select tc.* from tcompra as tc, tgestion as tg, tperiodogestion as tpg
				where
				tg.gestion = tpg.gestion 
				and tc.cod_pg = tpg.cod_pg 
				and tpg.periodo = '$periodo'
				and tg.gestion = '$gestion';";
        else
        $sql = "select tc.* from tcompra as tc, tgestion as tg, tperiodogestion as tpg
				where
				tg.gestion = tpg.gestion 
				and tc.cod_pg = tpg.cod_pg 
				and tpg.periodo = '$periodo'
				and tg.gestion = '$gestion' and sucursal_id = '$sucursal';";
		//echo "$sql<br>";
		$res = mysql_query($sql,$this->link);
		while($row = mysql_fetch_array($res))
		{
			$totalfact += $row['total_facturado'];
			$totalice += $row['ice'];
			$totalexen += $row['exento'];
			$totalimp += $row['importe'];
			$totaliva += $row['credito_fiscal'];
		}
		$data['totalfact']= $this->truncarCantidad($totalfact);
		$data['totalice']= $this->truncarCantidad($totalice);
		$data['totalexen']= $this->truncarCantidad($totalexen);
		$data['totalimp']= $this->truncarCantidad($totalimp);
		$data['totaliva']= $this->truncarCantidad($totaliva);

		return $data;
	}

	function ventaTotalesGenerales($gestion,$periodo,$tipoventa=1,$sucursal)
	{
		$data = null;
		$totalfact = 0;$totalice = 0;
		$totalexen = 0;$totalimp = 0;
		$totaliva = 0;
		$tipodeventa="";
		
		if($periodo!=0)
		{
			if ($periodo<10)
			$periodo="0$periodo";
		}
		
		if($tipoventa==2)
		$tipodeventa=" and tv.tipo='1' ";
		elseif ($tipoventa==3)
		$tipodeventa=" and tv.tipo='2' ";
		if(strcmp($sucursal,"todos")==0)
		$sql = "select tv.* from tventa as tv, tgestion as tg, tperiodogestion as tpg
				where
				tg.gestion = tpg.gestion 
				and tv.cod_pg = tpg.cod_pg 
				and tpg.periodo = '$periodo'
				and tg.gestion = '$gestion' $tipodeventa;";
        else
		$sql = "select tv.* from tventa as tv, tgestion as tg, tperiodogestion as tpg
				where
				tg.gestion = tpg.gestion 
				and tv.cod_pg = tpg.cod_pg 
				and tpg.periodo = '$periodo'
				and tg.gestion = '$gestion' $tipodeventa and sucursal_id='$sucursal';";
		//echo "$sql<br>";
		$res = mysql_query($sql,$this->link);
		while($row = mysql_fetch_array($res))
		{
			$totalfact += $row['total_facturado'];
			$totalice += $row['ice'];
			$totalexen += $row['exento'];
			$totalimp += $row['importe'];
			$totaliva += $row['credito_fiscal'];
		}
		$data['totalfact']= $this->truncarCantidad($totalfact);
		$data['totalice']= $this->truncarCantidad($totalice);
		$data['totalexen']= $this->truncarCantidad($totalexen);
		$data['totalimp']= $this->truncarCantidad($totalimp);
		$data['totaliva']= $this->truncarCantidad($totaliva);

		return $data;
	}

	function calculatePagesReport($intervalo,$tipo,$gestion,$periodo,$tipovc=1,$sucursal)
	{
		$data = 0;
		$sql=null;
		$paginas = null;
		$per2=$periodo;
		if($periodo<10)
		$per2="0$periodo";

		if($tipo==0)
        {
            if(strcmp($sucursal,'todos')==0)
		      $sql="select count(*) as total from tcompra as tc, tperiodogestion as tp where
			  tc.cod_pg = tp.cod_pg and tp.gestion ='$gestion' and tp.periodo ='$per2';";
            else
                $sql="select count(*) as total from tcompra as tc, tperiodogestion as tp where
			          tc.cod_pg = tp.cod_pg and tp.gestion ='$gestion' and tp.periodo ='$per2' and sucursal_id='$sucursal';";
         }
		if($tipo==1)
		{
			if($tipovc==2)
			{
              if(strcmp($sucursal,'todos')==0)
				$sql="select count(*) as total from tventa as tv, tperiodogestion as tp where
			  tv.cod_pg = tp.cod_pg and tp.gestion ='$gestion' and tp.periodo ='$per2' and tv.tipo='1';";
              else
               $sql="select count(*) as total from tventa as tv, tperiodogestion as tp where
			         tv.cod_pg = tp.cod_pg and tp.gestion ='$gestion' and tp.periodo ='$per2' and tv.tipo='1'and sucursal_id='$sucursal';";
			}
			elseif ($tipovc==3)
			{
			  if(strcmp($sucursal,'todos')==0)
				$sql="select count(*) as total from tventa as tv, tperiodogestion as tp where
			  tv.cod_pg = tp.cod_pg and tp.gestion ='$gestion' and tp.periodo ='$per2' and tv.tipo='2';";
              else
                $sql="select count(*) as total from tventa as tv, tperiodogestion as tp where
			          tv.cod_pg = tp.cod_pg and tp.gestion ='$gestion' and tp.periodo ='$per2' and tv.tipo='2' and sucursal_id='$sucursal';";

			}
			else
			{
			  if(strcmp($sucursal,'todos')==0)
				$sql="select count(*) as total from tventa as tv, tperiodogestion as tp where
			  tv.cod_pg = tp.cod_pg and tp.gestion ='$gestion' and tp.periodo ='$per2';";
              else
                $sql="select count(*) as total from tventa as tv, tperiodogestion as tp where
			          tv.cod_pg = tp.cod_pg and tp.gestion ='$gestion' and tp.periodo ='$per2' and sucursal_id='$sucursal';";
			}
		}

		$res = mysql_query($sql,$this->link);
		if(	$row = mysql_fetch_array($res)	)
		{
			$data = $row['total'];
		}
		$data = ceil(	($data/$intervalo)	);

		if($tipo==1)
		{
			for($i = 0; $i<($data); $i++)
			{
				$valor ="tiporep=".$tipo."&iniInt=".($i*$intervalo)."&endInt=".$intervalo."&gestion=".$gestion."&periodo=".$periodo."&pag=".($i+1)."&ttl=".$data."&tvent=".$tipovc."&sucursal=".$sucursal;
				$paginas[$i]['indice']=$valor;
			}
		}
		else
		{
			for($i = 0; $i<($data); $i++)
			{
				$valor ="tiporep=".$tipo."&iniInt=".($i*$intervalo)."&endInt=".$intervalo."&gestion=".$gestion."&periodo=".$periodo."&pag=".($i+1)."&ttl=".$data."&alfa=".$tipovc."&sucursal=".$sucursal;
				$paginas[$i]['indice']=$valor;
			}
		}
		return $paginas;
	}
	
	function buscar_ComprasAlfanumericoReporte($texto_search,$criterio,$gestion,$periodo,$fec,$ini,$fin,$sucursal)
	{
	   if (isset($sucursal)&&!empty($sucursal))
       {
		$data = null;
		$i=0;
		$totalfact = 0;$totalice = 0;
		$totalexen = 0;$totalimp = 0;
		$totaliva = 0;
		$text_gestion = "";
		$text_periodo = "";
		$text_fecha = "";
		$text_criterio = "";
		$limite = "";
		//echo "     intervalo $ini - $fin <br>";
		if(strcmp($criterio,"total_facturado")==0)
		$text_criterio = " and tc.$criterio = '$texto_search'";
		else
		$text_criterio = " and tc.$criterio like '%$texto_search%'";

		if($gestion!=0)
		$text_gestion = " and tg.gestion='$gestion'";
		if($periodo!=0)
		{
			if ($periodo<10)
			$periodo="0$periodo";
			$text_periodo = " and tpg.periodo='$periodo'";
		}

		if (isset($fec)&&!empty($fec))
		{
			$text_fecha = " and tc.fecha='$fec'";
		}
		if(isset($ini)&& isset($fin))
		{
			$limite= " limit $ini,$fin";
		}
        if(strcmp($sucursal,"todos")==0)
		$sql = "select tc.*,tpg.gestion,tpg.periodo,tpg.estado from tcompra as tc, tgestion as tg, tperiodogestion as tpg
				where
				tg.gestion = tpg.gestion 
				and tc.cod_pg = tpg.cod_pg $text_gestion $text_periodo $text_fecha $text_criterio order by tc.fecha $limite;";
        else
        $sql = "select tc.*,tpg.gestion,tpg.periodo,tpg.estado from tcompra as tc, tgestion as tg, tperiodogestion as tpg
                where
                tg.gestion = tpg.gestion 
                and tc.cod_pg = tpg.cod_pg and sucursal_id='$sucursal' $text_gestion $text_periodo $text_fecha $text_criterio order by tc.fecha $limite;";
        }
        //echo "$sql<br>";
		$res = mysql_query($sql,$this->link);
		while($row = mysql_fetch_array($res))
		{
			$data[$i]['cod_compra']=$row['cod_compra'];
			$data[$i]['fecha']=$row['fecha'];
			$data[$i]['nit']=$row['nit'];
			$data[$i]['razon_social']=$row['razon_social'];
			$data[$i]['factura']=$row['factura'];
			$data[$i]['autorizacion']=$row['autorizacion'];
			$data[$i]['codigo_control']=$row['codigo_control'];
			$data[$i]['total_facturado']= $this->truncarCantidad($row['total_facturado']);
			$data[$i]['ice']= $this->truncarCantidad($row['ice']);
			$data[$i]['exento']= $this->truncarCantidad($row['exento']);
			$data[$i]['importe']= $this->truncarCantidad($row['importe']);
			$data[$i]['credito_fiscal']= $this->truncarCantidad($row['credito_fiscal']);
			$data[$i]['cod_pg']=$row['cod_pg'];
			$data[$i]['usuario_id']=$row['usuario_id'];
            $data[$i]['sucursal_id']=$row['sucursal_id'];
			$data[$i]['fecha_reg']=$row['fechareg'];
			$data[$i]['periodo']=$row['periodo'];
			$data[$i]['gestion']=$row['gestion'];
			$data[$i]['estado']=$row['estado'];
			$data[$i]['alfanumerico']=$row['alfanumerico'];
			$data[$i]['tipo']="Compra";
			
			$totalfact += $row['total_facturado'];
			$totalice += $row['ice'];
			$totalexen += $row['exento'];
			$totalimp += $row['importe'];
			$totaliva += $row['credito_fiscal'];
			$i++;
		}
		if($data!=null)
		{
			$data[$i-1]['totalfact']= $this->truncarCantidad($totalfact);
			$data[$i-1]['totalice']= $this->truncarCantidad($totalice);
			$data[$i-1]['totalexen']= $this->truncarCantidad($totalexen);
			$data[$i-1]['totalimp']= $this->truncarCantidad($totalimp);
			$data[$i-1]['totaliva']= $this->truncarCantidad($totaliva);
		}
		return $data;
	}
	
	function reporteDaVinci($tipo,$periodo,$gestion,$sucursal)
	{
		$data=null;
		$sql = "";
		if ($periodo<10)
		$periodo="0$periodo";
		if($sucursal=="todos")
        {
		if($tipo == 2)
		$sql = "select tc.nit, tc.razon_social, tc.factura, tc.autorizacion, 
				tc.fecha, tc.total_facturado, tc.ice, tc.exento, 
				tc.importe, tc.credito_fiscal, tc.codigo_control,tc.sucursal_id 
				from tcompra as tc, tperiodogestion as tp where
				tc.cod_pg = tp.cod_pg and tp.gestion ='$gestion' 
				and tp.periodo ='$periodo' order by tc.fecha,tc.factura ;";
		if($tipo == 3)
		$sql = "select tc.nit, tc.razon_social, tc.factura, tc.autorizacion, 
				tc.fecha, tc.total_facturado, tc.ice, tc.exento, 
				tc.importe, tc.credito_fiscal, tc.codigo_control,tc.sucursal_id 
				from tventa as tc, tperiodogestion as tp where
				tc.cod_pg = tp.cod_pg and tp.gestion ='$gestion' 
				and tp.periodo ='$periodo' order by tc.fecha,tc.factura;";
		}
        else
        {
            if($tipo == 2)
		$sql = "select tc.nit, tc.razon_social, tc.factura, tc.autorizacion, 
				tc.fecha, tc.total_facturado, tc.ice, tc.exento, 
				tc.importe, tc.credito_fiscal, tc.codigo_control,tc.sucursal_id 
				from tcompra as tc, tperiodogestion as tp where
				tc.cod_pg = tp.cod_pg and tp.gestion ='$gestion' and sucursal_id='$sucursal' 
				and tp.periodo ='$periodo' order by tc.fecha,tc.factura ;";
		if($tipo == 3)
		$sql = "select tc.nit, tc.razon_social, tc.factura, tc.autorizacion, 
				tc.fecha, tc.total_facturado, tc.ice, tc.exento, 
				tc.importe, tc.credito_fiscal, tc.codigo_control,tc.sucursal_id  
				from tventa as tc, tperiodogestion as tp where
				tc.cod_pg = tp.cod_pg and tp.gestion ='$gestion' and sucursal_id='$sucursal'
				and tp.periodo ='$periodo' order by tc.fecha,tc.factura;";
        }
		
		$result=mysql_query($sql,$this->link);
		$i=0;
		
		while($row = mysql_fetch_array($result))
		{
		 if($tipo==2)
		 {	$data[$i]['nit']=$row['nit'];
			$data[$i]['razon_social']=$row['razon_social'];
			$data[$i]['factura']=$row['factura'];
			
			$data[$i]['poliza']=true;
			if(stristr($row['factura'], 'c') === FALSE)
			$data[$i]['poliza']=false;
			
			$data[$i]['factura']=$row['factura'];
			$data[$i]['autorizacion']=$row['autorizacion'];
			$data[$i]['fecha']=$row['fecha'];
			$data[$i]['total_facturado']=$row['total_facturado'];
			$data[$i]['ice']=$row['ice'];
			$data[$i]['exento']=$row['exento'];
			$data[$i]['importe']=$row['importe'];
			$data[$i]['credito_fiscal']=$row['credito_fiscal'];
			$data[$i]['codigo_control']=$row['codigo_control'];	
            $data[$i]['sucursal']=$row['sucursal_id'];			
			$i++;
			
		 }
		 if($tipo==3)
    	 {
		 	
		 	$data[$i]['nit']=$row['nit'];
			$data[$i]['razon_social']=$row['razon_social'];
			$data[$i]['factura']=$row['factura'];
			$data[$i]['autorizacion']=$row['autorizacion'];
			$data[$i]['fecha']=$row['fecha'];
			$data[$i]['total_facturado']=$row['total_facturado'];
			$data[$i]['ice']=$row['ice'];
			$data[$i]['exento']=$row['exento'];
			$data[$i]['importe']=$row['importe'];
			$data[$i]['credito_fiscal']=$row['credito_fiscal'];
			$data[$i]['codigo_control']=$row['codigo_control'];	
            $data[$i]['sucursal']=$row['sucursal_id'];			
			$i++;
		 	
		 	 	
		 }		 
		 
		}		
		return $data;
	}
	
	
	function reporteSDIDUI($tipo,$periodo,$gestion)
	{
		$data=null;
		$sql = "";
		if ($periodo<10)
		$periodo="0$periodo";
		
		if($tipo == 4)
		$sql = "select tc.nit as nitc, tc.razon_social, tc.factura, tc.autorizacion, 
				tc.fecha, tc.total_facturado, tc.ice, tc.exento, 
				tc.importe, tc.credito_fiscal, tc.codigo_control,tcon.nit 
				from tcompra as tc, tperiodogestion as tp, tcontribuyente as tcon
				where
				tc.cod_pg = tp.cod_pg and tp.gestion ='$gestion' 
				and tc.factura like '%c%' and tcon.cod_cont='1'
				and tp.periodo ='$periodo' order by tc.fecha;";
		
		
		$result=mysql_query($sql,$this->link);
		$i=0;
		
		while($row = mysql_fetch_array($result))
		{
		 if($tipo==4)
		 {	$data[$i]['nitc']=$row['nitc'];
			$data[$i]['razon_social']=$row['razon_social'];
			$data[$i]['factura']=$row['factura'];
			$data[$i]['factura']=$row['factura'];
			$data[$i]['autorizacion']=$row['autorizacion'];
			$data[$i]['fecha']=$row['fecha'];
			$data[$i]['total_facturado']=$row['total_facturado'];
			$data[$i]['ice']=$row['ice'];
			$data[$i]['exento']=$row['exento'];
			$data[$i]['importe']=$row['importe'];
			$data[$i]['credito_fiscal']=$row['credito_fiscal'];
			$data[$i]['codigo_control']=$row['codigo_control'];		
			$data[$i]['nit']=$row['nit'];		
			$i++;
		 }
			 
 
		 
		 
		}		
		return $data;
	}
	function reporteSDIFACT($tipo,$periodo,$gestion)
	{
		$data=null;
		$sql = "";
		if ($periodo<10)
		$periodo="0$periodo";
		
		if($tipo == 5)
			$sql="select tc.nit as nitc, tc.razon_social, tc.factura, tc.autorizacion, 
				tc.fecha, tc.total_facturado, tc.ice, tc.exento, 
				tc.importe, tc.credito_fiscal, tc.codigo_control,tcon.nit
				from tcompra as tc, tperiodogestion as tp, tcontribuyente as tcon
				where tc.cod_pg = tp.cod_pg and tp.gestion ='$gestion' 
				and tcon.cod_cont='1'	and tp.periodo ='$periodo' and tc.factura not in( select  tc.factura 
                                                                                from tcompra as tc
				                                                                 where tc.factura  like '%c%' ) 
                order by tc.fecha;";
		
		
				
		$result=mysql_query($sql,$this->link);
		$i=0;
		
		while($row = mysql_fetch_array($result))
		{
		 if($tipo==5)
		 {	$data[$i]['nitc']=$row['nitc'];
			$data[$i]['razon_social']=$row['razon_social'];
			$data[$i]['factura']=$row['factura'];
			$data[$i]['factura']=$row['factura'];
			$data[$i]['autorizacion']=$row['autorizacion'];
			$data[$i]['fecha']=$row['fecha'];
			$data[$i]['total_facturado']=$row['total_facturado'];
			$data[$i]['ice']=$row['ice'];
			$data[$i]['exento']=$row['exento'];
			$data[$i]['importe']=$row['importe'];
			$data[$i]['credito_fiscal']=$row['credito_fiscal'];
			$data[$i]['codigo_control']=$row['codigo_control'];		
			$data[$i]['nit']=$row['nit'];	
			$i++;
		 }
			 
 
		 
		 
		}		
		return $data;
	}
	
}
?>
