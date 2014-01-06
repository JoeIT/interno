<?php /* Smarty version 2.6.18, created on 2013-07-18 17:26:16
         compiled from reporte_transferencia.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 

<html>
<head>
<title> Resumen de Activod fijos</title></head>
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

<div id="installOK" align="center" style="vertical-align:top;" >
  <p>       
        
        <?php unset($this->_sections['pagina']);
$this->_sections['pagina']['name'] = 'pagina';
$this->_sections['pagina']['loop'] = is_array($_loop=$this->_tpl_vars['num_paginas']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['pagina']['show'] = true;
$this->_sections['pagina']['max'] = $this->_sections['pagina']['loop'];
$this->_sections['pagina']['step'] = 1;
$this->_sections['pagina']['start'] = $this->_sections['pagina']['step'] > 0 ? 0 : $this->_sections['pagina']['loop']-1;
if ($this->_sections['pagina']['show']) {
    $this->_sections['pagina']['total'] = $this->_sections['pagina']['loop'];
    if ($this->_sections['pagina']['total'] == 0)
        $this->_sections['pagina']['show'] = false;
} else
    $this->_sections['pagina']['total'] = 0;
if ($this->_sections['pagina']['show']):

            for ($this->_sections['pagina']['index'] = $this->_sections['pagina']['start'], $this->_sections['pagina']['iteration'] = 1;
                 $this->_sections['pagina']['iteration'] <= $this->_sections['pagina']['total'];
                 $this->_sections['pagina']['index'] += $this->_sections['pagina']['step'], $this->_sections['pagina']['iteration']++):
$this->_sections['pagina']['rownum'] = $this->_sections['pagina']['iteration'];
$this->_sections['pagina']['index_prev'] = $this->_sections['pagina']['index'] - $this->_sections['pagina']['step'];
$this->_sections['pagina']['index_next'] = $this->_sections['pagina']['index'] + $this->_sections['pagina']['step'];
$this->_sections['pagina']['first']      = ($this->_sections['pagina']['iteration'] == 1);
$this->_sections['pagina']['last']       = ($this->_sections['pagina']['iteration'] == $this->_sections['pagina']['total']);
?>
       
</p>
  <br>
  <br>
  
  	<?php if ($this->_tpl_vars['num_paginas'] == $this->_tpl_vars['pagina']): ?>
	  	     <table align="center" >
	  <?php else: ?>
	     <table align="center"  style="page-break-after:always;">
	  <?php endif; ?>
	  <tr>
	  <td valign="top">
  <table height="66" border="1" align="center" cellpadding="0" cellspacing="0" style="border-color:#E9E9E9;height:1.4cm;margin:0cm;width:20.3cm; clear:both;">
	  <tr>
		<td  class="contenido1" style="width:2.7cm;" align="center">
			<img src="../../templates/imagenes/logo-macaws2.jpg" align="middle" style="height:1.4cm;width:1.8cm;"/>	</td>
		<td class="contenido1" style="font-size:13px;font-weight:bold;width:8.9cm" align="center"><p>RESUMEN TRANSFERENCIA </p>
		  <p><span class="contenido1" style="font-size:11px;font-weight:bold;width:8.9cm"><span class="contenido1" style="font-size:11px;font-weight:bold;width:8.9cm">Pagina: <?php $this->assign('pagina', $this->_sections['pagina']['index_prev']+2); ?><?php echo $this->_tpl_vars['pagina']; ?>
 / </span><?php echo $this->_tpl_vars['num_paginas']; ?>
 </span><br/>
          </p></td>
		<td class="contenido1" style="text-align:center;width:3.1cm;" align="center"><div align="center"><br>
		</div></td>
	  </tr>
</table>
	 <br>
	 <br>
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
  
  <th colspan="28"><div align="left" style="font-size:12px"><?php echo $this->_tpl_vars['desgru']; ?>
</div>
          <div align="left"> </div></th>
    
  <tr>
      <td  width="10%" align="center" style="background: #FFFFFF;  font-size:12px;">
	  <?php if ($this->_tpl_vars['gru'] < 10): ?>0<?php echo $this->_tpl_vars['gru']; ?>
-<?php echo $this->_tpl_vars['num_act']; ?>
<?php else: ?><?php echo $this->_tpl_vars['gru']; ?>
-<?php echo $this->_tpl_vars['num_act']; ?>
<?php endif; ?></td>
    <td width="50%" align="left" style="background: #FFFFFF;  font-size:12px;"><?php echo $this->_tpl_vars['descripcion']; ?>
</td>
    <td width="10%" align="center" style="background: #FFFFFF;  font-size:12px;"><?php if ($this->_tpl_vars['unidad'] == ''): ?>--<?php else: ?><?php echo $this->_tpl_vars['unidad']; ?>
<?php endif; ?></td>
    <td width="10%" align="center" style="background: #FFFFFF;  font-size:12px;"><?php if ($this->_tpl_vars['serie'] == 'NULL' || $this->_tpl_vars['serie'] == ''): ?>--<?php else: ?><?php echo $this->_tpl_vars['serie']; ?>
<?php endif; ?></td>
  </tr>
</table>
    </br>
	</br>
    



    <table width="100%" border="1" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="9"><div align="center">Historial</div></td>
      </tr>
      <tr>
        <td  align="left" style="background: #FFFFFF;  font-size:12px;"><strong>N&deg;</strong></td>
        <td  align="left" style="background: #FFFFFF;  font-size:12px;"><strong>Fecha</strong></td>
        <td  align="left" style="background: #FFFFFF;  font-size:12px;"><strong>Ref FAF</strong></td>
        <td  align="left" style="background: #FFFFFF;  font-size:12px;"><strong>Actividad</strong></td>
        <td  align="left" style="background: #FFFFFF;  font-size:12px;"><strong> Responsable </strong></td>
        <td  align="left" style="background: #FFFFFF;  font-size:12px;"><strong>Area</strong></td>
        <td  align="left" style="background: #FFFFFF;  font-size:12px;"><strong>Localizacion Primaria</strong> </td>
        <td  align="left" style="background: #FFFFFF;  font-size:12px;"><strong>Secundaria</strong></td>
       
      </tr>
      <?php $_from = $this->_tpl_vars['transferencia']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['acti'] => $this->_tpl_vars['historial']):
?>
  <tr>
    <td style="background: #FFFFFF;  font-size:12px;"><?php echo $this->_tpl_vars['acti']+1; ?>
</td>
    <td style="background: #FFFFFF;  font-size:12px;"><?php echo $this->_tpl_vars['historial']['fecha']; ?>
</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td style="background: #FFFFFF;  font-size:12px;"><?php echo $this->_tpl_vars['historial']['completo']; ?>
</td>
    <td style="background: #FFFFFF;  font-size:12px;"><?php if ($this->_tpl_vars['historial']['area'] == ''): ?>--<?php else: ?><?php echo $this->_tpl_vars['historial']['area']; ?>
<?php endif; ?></td>
    <td style="background: #FFFFFF;  font-size:12px;"><?php echo $this->_tpl_vars['historial']['despri']; ?>
</td>
    <td style="background: #FFFFFF;  font-size:12px;"><?php echo $this->_tpl_vars['historial']['dessec']; ?>
</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>  
    </table>
    
	 <br>
	 <br>
	 <br>
<?php endfor; endif; ?>	  </tr>
</table>
	   <div id=idControls class="noprint" style="text-align:center;width:100%; clear:both">
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