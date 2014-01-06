<?php
class ConBell
{
	/*var $home="localhost";
	var $user="root";
	var $pass="";
	var $database="bellagio";*/
	var $home="208.109.29.67";
	var $user="belluser_db001";
	var $pass="blldb11909g";
	var $database="belldb_001";

	function conectiondb()
	{
		if (!($link=@mysql_connect($this->home,$this->user,$this->pass)))
		//if(!($link=@mysql_connect("localhost","sistemas","dato0")))
		{
		  echo "Error when connecting b_client database";
	      exit();
	   	}
   		if (!mysql_select_db($this->database,$link))
	   	{
    	  echo "Error the database not exists";
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