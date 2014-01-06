<?php
class Coneccion {

/*	var $home="localhost";
	var $user="base";
	var $pass="dato0";
	var $database="macaws";*/


   	var $home="localhost";
	var $user="root";
	var $pass="Da98-AA";
	var $database="macaws";

    function conectiondb()
    {
        if (!($link = @mysql_pconnect($this->home, $this->user, $this->pass))) {
            echo "Error when connecting itself to the database";
            exit();
        }
        if (!mysql_select_db($this->database, $link)) {
            echo "Error the database not exists";
            exit();
        }
        return $link;
    }
    function executeSql($sql)
    {
        $link = conectiondb();
        $result = mysql_query($sql, $link);
        return $result;
    }
}

?>