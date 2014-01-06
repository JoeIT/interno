<?php
require_once("../class/conexionBell.php");
class Shipment
{
	var $link;
	function Shipment($link){

		$this->link=$link;
	}

	function addDispatch($arrSales,$iddespacho,$detail,$obs,$status){

		$cod_dispatch=$this->newDispatch($iddespacho,$detail,$obs,$status);
		$this->newDispatchDetail($arrSales,$cod_dispatch);
	}

	function newDispatch($iddespacho,$detail,$observations,$status){

		$sql="insert into tshipping_list (iddespacho,date_upload,detail,observations,status)
		values ($iddespacho,now(),'$detail','$observations',$status)";
		$result=mysql_query($sql,$this->link);
		$cod_dispatch=mysql_insert_id();
		return $cod_dispatch;
	}

	function newDispatchDetail($arrSales,$cod_dispatch){
		for($i=0;$i<sizeof($arrSales);$i++)
		{
			if($arrSales[$i]['nrosale']!=0)
			{
				$nrosale=$arrSales[$i]['nrosale'];
				$qty=$arrSales[$i]['qty'];
				$sql="insert into tshipping_list_detail(nrosale,cod_dispatch,date_upload,qty)
				values ($nrosale,$cod_dispatch,now(),$qty)";
				$result=mysql_query($sql,$this->link);
			}
		}
	}
	function existsMigration($iddespacho){

		$sql="select cod_dispatch from tshipping_list where iddespacho=$iddespacho";
		$result=mysql_query($sql,$this->link);
		$exists=false;
		if($row=mysql_fetch_array($result))
			$exists=true;
		return $exists;
	}
	///////////////////////////////////////////////////////////////
	function showPendingList(){

		$sql="select *
		from tshipping_list
		where status=0";
		$result=mysql_query($sql,$this->link);
		$data=null;
		$i=0;
		while($row=mysql_fetch_array($result))
		{
			$data[$i]['cod_dispatch']=$row['cod_dispatch'];
			$data[$i]['date_upload']=$row['date_upload'];
			$data[$i]['detail']=$row['detail'];
			$data[$i]['obs']=$row['observations'];
			$data[$i]['total_items']=$this->getTotalItems($row['cod_dispatch']);
			$i++;
		}
		return $data;
	}
	function getTotalItems($cod_dispatch){

		$sql="select sum(qty) as total
		from tshipping_list_detail
		where cod_dispatch=$cod_dispatch";
		$result=mysql_query($sql,$this->link);
		$total=0;
		if($row=mysql_fetch_array($result))
			$total=$row['total'];

		return $total;
	}
	function upLoadShipmentList($cod_dispatch){

		$sql="select nrosale
		from tshipping_list_detail
		where cod_dispatch=$cod_dispatch";
		$result=mysql_query($sql,$this->link);
		while($row=mysql_fetch_array($result))
		{
			$this->changeStatusDispatchDetail($row['nrosale'],$cod_dispatch);
			$this->sendToShipmentList($row['nrosale']);
			$this->addState($row['nrosale'],"Ready for shipping");
		}
		$this->changeStatusDispatch($cod_dispatch);
	}
	function addState($nrosale,$description)
	 {
	 	$date=date("YmdHis");
		$sql="Insert into tstate (nrosale,date,description) values($nrosale,$date,'$description')";
		$result=mysql_query($sql,$this->link);
	 }
	function changeStatusDispatchDetail($nrosale,$cod_dispatch){

		$sql="update tshipping_list_detail
		set status=1
		where nrosale=$nrosale and cod_dispatch=$cod_dispatch";
		$result=mysql_query($sql,$this->link);
	}
	function changeStatusDispatch($cod_dispatch){

		$sql="update tshipping_list
		set status=1
		where cod_dispatch=$cod_dispatch";
		$result=mysql_query($sql,$this->link);
	}
	function sendToShipmentList($nrosale){

		$origin=$this->verifyOrigin($nrosale);
		$sql="update tsale
			set state='S'
			where nrosale=$nrosale";
		if(strcmp("fortte",$origin)==0)
			$result=mysql_query($sql,$this->link);
		else
		{
			$link2=new ConBell();
			$link2=$link2->conectiondb();
			$result=mysql_query($sql,$link2);
		}
	}
	function verifyOrigin($nrosale)
	{
		$origin="";
		$sql="select nrosale from tsale where nrosale=$nrosale";
		$result=mysql_query($sql,$this->link);
		if($row=mysql_fetch_array($result))
			$origin="fortte";
		else
			$origin="bellagio";
		return $origin;
	}
	function showDetailsSpecificList($cod_dispatch){

		$sql="select nrosale,qty,date_upload
		from tshipping_list_detail
		where cod_dispatch=$cod_dispatch order by nrosale";
		$result=mysql_query($sql,$this->link);
		$data=null;
		$i=0;
		while($row=mysql_fetch_array($result))
		{
			$data[$i]['nrosale']=$row['nrosale'];
			$data[$i]['date_upload']=$row['date_upload'];
			$data[$i]['qty']=$row['qty'];
			$i++;
		}
		return $data;
	}
	function genStickers($nrosales)
	{
		$i=$k=0;$data=null;
		for($i=0;$i<sizeof($nrosales);$i++)
		{
			if(($this->isSpecial($nrosales[$i]['nrosale']))==0){
				$this->getDetailNormal($nrosales[$i]['nrosale'],$data,$k);
			}
			else
				$this->getDetailSpecial($nrosales[$i]['nrosale'],$data,$k);
		}
		return $data;
	}
	function isSpecial($nrosale)
	{
		$sql="select special from tsale where nrosale=$nrosale";
		$result=mysql_query($sql,$this->link);
		return ($row=mysql_fetch_array($result))?$row['special']:-1;
	}
	function getDetailNormal($nrosale,&$data,&$k)
	{
		$sql="SELECT sd.nrosale,sd.quantity, sh.first_name,sh.last_name
		,sh.address1,sh.address2,sh.zipcode,sh.city,sh.email,sh.country,sh.state,sh.telephone,pr.name
		,pr.modelo,co.textcolor,cl.textclip,me.description
		FROM tsaledescription sd, tdescriptionproduct sp, tshipping sh, tsale s,tproduct pr,tcolor co, tclip cl,
		tmethodsh me
		WHERE sd.cod_description = sp.cod_description
		and s.nrosale=sd.nrosale
		and pr.cod_product=sp.cod_product
		and co.cod_color=sp.cod_color
		and cl.cod_clip=sp.cod_clip
		and sd.cod_description=sp.cod_description
		and s.cod_user=sh.cod_user
		and me.cod_shippingmth=s.cod_shippingmth
		AND sd.nrosale=$nrosale";
		$result=mysql_query($sql,$this->link);
		while($row=mysql_fetch_array($result))
		{
			for($j=0;$j<$row['quantity'];$j++)
			{
				$div=bcmod ($k,4);
				$data[$k]['nrosale'] = $row['nrosale'];
				$data[$k]['name']=$row['first_name'];
				$data[$k]['last']=$row['last_name'];
				$data[$k]['zip']=$row['zipcode'];
				$data[$k]['tel']=$row['telephone'];
				$data[$k]['add1']=$row['address1'];
				$data[$k]['add2']=$row['address2'];
				$data[$k]['email']=$row['email'];
				$data[$k]['country']=$row['country'];
				$data[$k]['state']=$row['state'];
				$data[$k]['city']=$row['city'];
				$data[$k]['nameprod']=$row['name'];
				$data[$k]['model']=$row['modelo'];
				$data[$k]['color']=$row['textcolor'];
				$data[$k]['clip']=$row['textclip'];
				$data[$k]['embo']="";
				$data[$k]['envio']=$row['description'];
				$data[$k]['div'] =$div;
				$k++;
		  }
	   }
	}
	function getDetailSpecial($nrosale,&$data,&$k)
	{
		$sql="SELECT sd.nrosale,sd.quantity,sh.first_name,sh.last_name
		,sh.address1,sh.address2,sh.zipcode,sh.city,sh.email,sh.country,sh.state,sh.telephone,pr.name,pr.modelo,co.textcolor,cl.textclip
		,sp.shield,sp.embossed,sp.place,me.description
		FROM tsaledescription sd, tspecial sp, tshipping sh, tsale s,tproduct pr,tcolor co, tclip cl,
		tmethodsh me
		WHERE sd.cod_special = sp.cod_special
		and s.nrosale=sd.nrosale
		and pr.cod_product=sp.cod_product
		and co.cod_color=sp.cod_color
		and cl.cod_clip=sp.cod_clip
		and sd.cod_special=sp.cod_special
		and s.cod_shipping=sh.cod_shipping
		and me.cod_shippingmth=s.cod_shippingmth
		AND sd.nrosale= $nrosale ";
	//	echo $sql;
		$result=mysql_query($sql,$this->link);
		while($row=mysql_fetch_array($result))
		{
			for($j=0;$j<$row['quantity'];$j++)
			{
				$div=bcmod ($k,4);
				$data[$k]['nrosale'] = $row['nrosale'];
				$data[$k]['name']=$row['first_name'];
				$data[$k]['last']=$row['last_name'];
				$data[$k]['zip']=$row['zipcode'];
				$data[$k]['tel']=$row['telephone'];
				$data[$k]['add1']=$row['address1'];
				$data[$k]['add2']=$row['address2'];
				$data[$k]['email']=$row['email'];
				$data[$k]['country']=$row['country'];
				$data[$k]['state']=$row['state'];
				$data[$k]['city']=$row['city'];
				$data[$k]['nameprod']=$row['name'];
				$data[$k]['model']=$row['modelo'];
				$data[$k]['color']=$row['textcolor'];
				$data[$k]['shield']=$row['shield'];
				$data[$k]['clip']=$row['textclip'];
				$data[$k]['embo']=$row['embossed'];
				$data[$k]['envio']=$row['description'];
				$data[$k]['div'] =$div;
				$k++;
		  }
		}		
	}
}
?>