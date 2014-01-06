<?php
class DB_SI_Macaws
{
    //var $home="192.168.0.100";
	var $home="localhost";
	/*var $user="sistemas";
	var $pass="sistema135";*/
/*	var $user="root";
	var $pass="Da98-AA";*/
	var $user="sistemas";
	var $pass="dato0";
	function conectionSI()
	{
		$base="macaws_bd";
 	//if (!($link=mysql_connect($this->home,$this->user,$this->pass))) 
	if(!($link=@mysql_connect("localhost","sistemas","dato0")))
		{ 
		  echo "*********Error when connecting itself to the data base";
	      exit(); 
	   	} 	
   		if (!mysql_select_db($base,$link)) 
	   	{ 
    	  echo ">>>>Error: the data base does not exists"; 
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
