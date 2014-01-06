<?php
session_start();
if ($_SESSION["level"] != 2 )
{
	session_destroy();
	header("Location: /scr/index.php");
}
else
{
	header("Location: /fortte-admin/logout_menu.php");
}
?>