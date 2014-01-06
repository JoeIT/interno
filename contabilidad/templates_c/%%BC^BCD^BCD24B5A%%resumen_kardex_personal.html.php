<?php /* Smarty version 2.6.18, created on 2009-04-24 17:31:17
         compiled from resumen_kardex_personal.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 

<html>
<head>
<title> Resumen de Activod fijos</title>
</head>
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
		factory.printing.portrait = true;
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
fuera
<div id="installOK" align="center" style="vertical-align:top;" >
dentro
  <p></p>
  <p>             
        
         
</p>

	 
	     <table align="center"  style="page-break-after:always;">
	
	   
	 	 
  <tr>
	  <td valign="top"><div align="center"><strong>Resumen activos </strong><br>
	     </div>
	    <table  width="100%" border="1" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="2">Responsable / Asignado </td>
        </tr>
        <tr>
          <td align="left" style="background: #FFFFFF;  font-size:12px;"><strong>Responsable Primario</strong></td>
          <td align="left" style="background: #FFFFFF;  font-size:12px;"><?php echo $this->_tpl_vars['responsable']; ?>
</td>
        </tr>
        <tr>
          <td align="left" style="background: #FFFFFF;  font-size:12px;"><strong>Nombre</strong></td>
          <td align="left" style="background: #FFFFFF;  font-size:12px;"><?php echo $this->_tpl_vars['asignado']; ?>
</td>
        </tr>
        <tr>
          <td align="left" style="background: #FFFFFF;  font-size:12px;"><strong>Cargo</strong></td>
          <td align="left" style="background: #FFFFFF;  font-size:12px;"><?php echo $this->_tpl_vars['respo']['cargo']; ?>
</td>
        </tr>
      </table>

  </br>
  <br>

     <table  border="1" align="center" cellpadding="0" cellspacing="0" >
  <tr>
    <td colspan="9"><div align="center">Activos fijos</div></td>
  </tr>
  <td align="center" style="background: #FFFFFF;  font-size:12px;"><strong> Numero </strong></td>
      <td align="center" style="background: #FFFFFF;  font-size:12px;"><strong>Descripcion</strong></td>
    <td align="center" style="background: #FFFFFF;  font-size:12px;"><strong> Unidad </strong> </td>
    <td align="center" style="background: #FFFFFF;  font-size:12px;" nowrap="nowrap"><strong>Serie</strong></td>
    
    
  </tr>
  
  <?php $_from = $this->_tpl_vars['res_kardex']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['grupo'] => $this->_tpl_vars['valores']):
?>
  <th colspan="28"><div align="left" style="font-size:12px"><?php echo $this->_tpl_vars['grupo']; ?>
</div>
          <div align="left"> </div></th>
    <?php $_from = $this->_tpl_vars['valores']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['acti'] => $this->_tpl_vars['kardex']):
?> 
  <tr>
      <td  width="10%" align="center" style="background: #FFFFFF;  font-size:12px;"><?php echo $this->_tpl_vars['kardex']['numero']; ?>
</td>
    <td width="50%" align="left" style="background: #FFFFFF;  font-size:12px;"><?php echo $this->_tpl_vars['kardex']['descripcion']; ?>
</td>
    <td width="10%" align="center" style="background: #FFFFFF;  font-size:12px;"><?php echo $this->_tpl_vars['kardex']['unidad']; ?>
</td>
    <td width="10%" align="center" style="background: #FFFFFF;  font-size:12px;"><?php if ($this->_tpl_vars['kardex']['serie'] == ''): ?>----<?php else: ?><?php echo $this->_tpl_vars['kardex']['serie']; ?>
<?php endif; ?></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>  
  <?php endforeach; endif; unset($_from); ?>
</table>
 
    </tr>
	  <tr>
	    <td valign="top">      
	    </tr>
</table>


	   <div id=idControls class="noprint" style="text-align:center; clear:both">
		<table border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:0.1cm;width:20.3cm;">
		<tr>
			<td style="text-align:center;">
				<hr size="1">
				<input disabled type="button" value="Configurar Impresora" onClick="factory.printing.Print(true)" class="boton">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input disabled type="button" value="Configurar P&aacute;gina" onClick="factory.printing.PageSetup()" class="boton">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input disabled type="button" value="Vista preliminar" onClick="factory.printing.Preview()" class="enviar" onMouseOver="this.className='boton'" onMouseOut="this.className='enviar'">
				<hr size="1">			</td>
		</tr>
		</table>
		
	</div>
</div>

</body>
</html>