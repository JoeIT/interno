<?php /* Smarty version 2.6.18, created on 2013-07-10 12:41:57
         compiled from headerAuxiliar.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $this->_tpl_vars['title']; ?>
</title>

<script type="text/javascript" src="ajax/js/prototype.js"></script>
<script type="text/javascript" src="ajax/js/smarty_ajax.js"></script>

<link href="css/fortte-admin.css" rel="stylesheet" type="text/css" />
<link href="css/forttecss.css" rel="stylesheet" type="text/css" />
<link href="css/fortte-css.css" rel="stylesheet" type="text/css" />

<link href="../css/fortte-admin.css" rel="stylesheet" type="text/css" />
<link href="../css/forttecss.css" rel="stylesheet" type="text/css" />
<link href="../css/fortte-css.css" rel="stylesheet" type="text/css" />
</head>
<body class="bodymargin">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#2C7FC5"><img src="css/imagenes/cabecera.jpg" border="0" /></td>
  </tr>
</table>
<!--titulo sucursal-->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#FFCC33" style="color:#03C; font-size:24px; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif;" align="right"><?php echo $this->_tpl_vars['title_sucursal']; ?>
<?php echo $this->_tpl_vars['sucursal_name']; ?>
</td>
  </tr>
</table>
<!--fin titulo sucursal-->

<div align="left" >
<table width="100%" border="0" cellpadding="1" cellspacing="1" class="tableblueSB">

	<tbody >
		<tr>
		  <td width="80" align="center" class="header-table"><a href="seleccionarPeriodo.php?tactMacReg=1" class="blue-small-link"><img src="css/imagenes/compras.jpg" width="20" height="20" border="0" /><br/>
	      Compras</a>		  </td>
		  <td width="80" align="center" class="header-table"><a href="seleccionarPeriodo.php?tactMacReg=0" class="blue-small-link"><img src="css/imagenes/ventas.jpg" width="20" height="20" border="0" /><br/>
	      Ventas</a></td>
		  <td width="80" align="center" class="header-table"><a href="reportesLcv.php" class="blue-small-link"><img src="css/imagenes/gestiones.jpg" width="20" height="20" border="0" /><br/>
		  Buscador</a></td>
		  <td width="80" align="center" class="header-table"><a href="reportes.php" class="blue-small-link"><img src="css/imagenes/gestiones.jpg" width="20" height="20" border="0" /><br/>
		  Reportes</a>
		  </td>
		  <td width="270" class="header-table"></td>
		</tr>
	</tbody>
</table>
</div>