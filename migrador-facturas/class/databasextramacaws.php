<?php
class DBMacaws
{
/*	var $home="192.168.0.100";
	var $user="sistemas";
	var $pass="sistema135";
	*/
	var $home="localhost";
	var $user="sistemas";
	var $pass="dato0";
	function conectiondb()
	{
		$base="extra_macaws";
		//if (!($link=mysql_connect($this->home,$this->user,$this->pass)))
		if(!($link=@mysql_connect("localhost","sistemas","dato0")))
		{
		  echo "-------Error when connecting itself to the database".mysql_error();
	      exit();
	   	}
   		if (!mysql_select_db($base,$link))
	   	{
    	  echo "Error the database not exist";
	      exit();
   		}
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