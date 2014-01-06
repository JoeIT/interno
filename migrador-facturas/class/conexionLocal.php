<?php
class Coneccion
{
	//var $home="67.227.155.78";
	var $home="64.91.227.44";
	var $user="forttec_usr433d";
	var $pass="Er56-df3G?1";
	var $database="forttec_forttedb";
/*
	var $home="localhost";
	var $user="root";
	var $pass="";
	var $database="fdb_m001";*/
	function conectiondb()
	{
		if (!($link=@mysql_pconnect($this->home,$this->user,$this->pass)))
		//if(!($link=@mysql_connect("67.227.188.182","forttec_usr433d","Er56-df3G?1")))
		{
		  echo "*-*-*-Error when connecting itself to the database".mysql_error();
	      exit();
	   	}
   		if (!mysql_select_db($this->database,$link))
	   	{
    	  echo "Error the database not exists";
	      exit();
   		}
		set_time_limit(20000);
   		return $link;
	}
	function executeSql($sql)
	{
		$link=conectiondb();
		$result=mysql_query($sql,$link);
		return $result;
	}
	
}


?>