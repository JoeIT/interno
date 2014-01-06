<?php /* Smarty version 2.6.18, created on 2008-10-24 10:04:20
         compiled from cabecera.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<head>
<meta content=" text/html; charset=iso-8859-1" />
<title> <?php echo $this->_tpl_vars['titulo_pagina']; ?>
 </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../templates/css/macaws-css.css">

<!-- librerias ajax -->
<script src="../templates/script/prototype.js" type="text/javascript"></script>
<script src="../templates/script/macaws-scripts.js" type="text/javascript"></script>
<script src="../templates/script/scriptaculous.js" type="text/javascript"></script>
<script type="text/javascript" src="../templates/script/ajax-dynamic-content.js"></script>
<script type="text/javascript" src="../templates/script/ajax.js"></script>
<script type="text/javascript" src="../templates/script/ajax-tooltip.js"></script>
<!-- librería principal del calendario -->
 <script type="text/javascript"  src="../templates/script/calendar.js"> </script>


 <!-- librería para cargar el lenguaje deseado -->
  <script type="text/javascript" src="../templates/script/calendar-es.js"></script>

  <!-- librería que declara la función Calendar.setup, que ayuda a generar un calendario en unas pocas líneas de código -->
  <script type="text/javascript" src="../templates/script/calendar-setup.js" ></script>

</head>

<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#0C5791">
		<tr>
			<td>
				<div align="right"><img src="../../templates/imagenes/cabecera.jpg" alt="Macaws S.R.L." /></div>
			</td>
		</tr>
</table>
<div align="right">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="celda2" >
	  <tr>
		<td width="40%">
			<div align="left">
				&nbsp;&nbsp;&nbsp;<?php echo $_SESSION['fecha_ingreso']; ?>

			</div>
		</td>
		<td>
			<div align="center">
				Usuario: <?php echo $_SESSION['apellidos']; ?>
 <?php echo $_SESSION['nombres']; ?>

			</div>
		</td>
		<td width="33%">
			<div align="right">
				<a href="../../controladores/index_menu.php?funcion=salir" target="_parent">Cerrar Sesi&oacute;n</a> &nbsp;&nbsp;&nbsp;
	        </div>
		</td>
	  </tr>
  </table>
</div>