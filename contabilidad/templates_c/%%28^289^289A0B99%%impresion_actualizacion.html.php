<?php /* Smarty version 2.6.18, created on 2009-03-20 11:23:21
         compiled from impresion_actualizacion.html */ ?>

<html>
<head>
<title> Resumen de Activod fijos</title></head>
<link rel="stylesheet" type="text/css" href="../templates/css/activos-css.css">


<?php echo '
<script defer>
function setInstallStyles(fOK) {
	document.getElementById("installOK").runtimeStyle.display = fOK ? "block" : "none";
}
function okInstall() {
	setInstallStyles(true);
}
function noInstall() {
	setInstallStyles(false);
}
function viewinit() {
	if (!factory.object) {
		noInstall();
		return
	} else {
		okInstall();
		factory.printing.header = "";
		factory.printing.footer = "";
		factory.printing.portrait = false;
		factory.printing.leftMargin = 0;
		factory.printing.topMargin = 0;
		factory.printing.rightMargin = 0;
		factory.printing.bottomMargin = 0;


		// enable control buttons
		var templateSupported = factory.printing.IsTemplateSupported();
		var controls = idControls.all.tags("input");
		for ( i = 0; i < controls.length; i++ ) {
			controls[i].disabled = false;
			if ( templateSupported && controls[i].className == "ie55" )
				controls[i].style.display = "inline";
			}
		}
}
</script>

<style type="text/css">
body{
	margin-bottom:1cm;
	margin-left:0.7cm;
	margin-right:07cm;
	margin-top:1.4cm;
	width:27.9cm;
}

.titulo{
	background-color:#330099;
	color:#FFFFFF;
	font-weight:bold;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	height:0.4cm;
	text-align:center;
}

.contenido1{
	border-color: #000000;
	color:#000000;
	text-align:center;
	vertical-align:middle;
}

.dato{
	background-color:#FFFFCC;
	text-align:center;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	vertical-align:middle;
}

.n_asignacion{
	visibility:hidden;
}


.boton {
	background-color: #F6F6F2;
	border-color:#95BAD5;
	border-width:2px;
	color: #000000;
	cursor:pointer;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	padding: 2px;
}

.enviar {
	background-color:#175C93;
	border-color:#95BAD5;
	border-width:2px;
	color:#FCFFFF;
	cursor:pointer;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	padding:2px;
}
</style>

<style type="text/css" media="print">
body{
	margin-bottom:1cm;
	margin-left:2cm;
	margin-right:0.7cm;
	margin-top:0.15cm;
	padding: 0cm;
	width:27.9cm;
}

.oculto{
	visibility:hidden;
}

.dato{
	background-color:#FFFFCC;
	text-align:center;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	vertical-align:middle;
}


.noprint {
	display: none;
}
</style>

'; ?>

</head>

<body scroll="auto" onLoad="viewinit()">

<!-- MeadCo ScriptX Control -->
<object id="factory" style="display:none" viewastext classid="clsid:1663ED61-23EB-11D2-B92F-008048FDD814" codebase="smsx.cab#Version=6,3,435,20">
</object>


	<p><?php if ($this->_tpl_vars['ver']): ?></p>


<?php if ($this->_tpl_vars['descrisecu']['locsecundaria'] != ''): ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['excel'] != null): ?>
		<img src="../../templates/imagenes/excel.gif" width="15" height="15" alt="<?php echo $this->_tpl_vars['item']; ?>
.xls" border="0" align="middle">
		<a href="../reportes/actualizaciones/<?php echo $this->_tpl_vars['excel']; ?>
.xls">
			OP: <?php echo $this->_tpl_vars['excel']; ?>
-		</a>
		<?php endif; ?>
<p><?php endif; ?>
  
<?php if ($this->_tpl_vars['ver1']): ?></p><div  style="font-size:18px">CUADRO DE ACTUALIZACIONES</div>
<?php if ($this->_tpl_vars['excel'] != null): ?>
		<img src="../../templates/imagenes/excel.gif" width="15" height="15" alt="<?php echo $this->_tpl_vars['item']; ?>
.xls" border="0" align="middle">
		<a href="../reportes/actualizaciones/<?php echo $this->_tpl_vars['excel']; ?>
.xls">
			OP: <?php echo $this->_tpl_vars['excel']; ?>
-		</a>
		<?php endif; ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['ver2']): ?>
<?php if ($this->_tpl_vars['excel'] != null): ?>
		<img src="../../templates/imagenes/excel.gif" width="15" height="15" alt="<?php echo $this->_tpl_vars['item']; ?>
.xls" border="0" align="middle">
		<a href="../reportes/actualizaciones/<?php echo $this->_tpl_vars['excel']; ?>
.xls">
			OP: <?php echo $this->_tpl_vars['excel']; ?>
-		</a>
		<?php endif; ?>
<div style="font-size:18px">CUADRO DE ACTUALIZACIONES</div>

<p><?php endif; ?>
  <?php if ($this->_tpl_vars['ver3']): ?></p>
<div  style="font-size:18px">CUADRO DE ACTUALIZACIONES</div>
<?php if ($this->_tpl_vars['excel'] != null): ?>
		<img src="../../templates/imagenes/excel.gif" width="15" height="15" alt="<?php echo $this->_tpl_vars['item']; ?>
.xls" border="0" align="middle">
		<a href="../reportes/actualizaciones/<?php echo $this->_tpl_vars['excel']; ?>
.xls">
			OP: <?php echo $this->_tpl_vars['excel']; ?>
-		</a>
		<?php endif; ?>
<?php endif; ?>


<br>
</br>
</br>
</br>
</br>
</br>

  <div id=idControls class="noprint" style="text-align:center;width:100%; clear:both"></div>


</body>
</html>