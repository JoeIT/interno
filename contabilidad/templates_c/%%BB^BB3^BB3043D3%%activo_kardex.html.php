<?php /* Smarty version 2.6.18, created on 2009-04-24 09:46:45
         compiled from activo_kardex.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'commify', 'activo_kardex.html', 243, false),)), $this); ?>
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
  <p></p>
  <p>            
  
         <?php unset($this->_sections['pagina']);
$this->_sections['pagina']['name'] = 'pagina';
$this->_sections['pagina']['loop'] = is_array($_loop=$this->_tpl_vars['res_kardex']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<td rowspan="2" align="center"  class="contenido1" style="width:2.7cm;">
			<img src="../../templates/imagenes/logo-macaws2.jpg" align="middle" style="height:1.4cm;width:1.8cm;"/>	</td>
		<td class="contenido1" style="font-size:13px;font-weight:bold;width:8.9cm" align="center"><p>FORMULARIO DE CONTROL DE ACTIVOS </p>
		  <p><br/>
          </p></td>
		<td align="center" class="contenido1" style="text-align:center;width:3.1cm;"><div align="center">CODIGO<br>
	    </div></td>
	  </tr>
	  <tr>
	    <td class="contenido1" style="font-size:13px;font-weight:bold;width:8.9cm" align="center"><p>KARDEX ACTIVO FIJO </p>
          </td>
        <td align="center" class="contenido1" style="text-align:center;width:3.1cm;"><span class="contenido1" style="text-align:center;width:3.1cm;"><?php echo $this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['grupo']; ?>
-<?php echo $this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['num_act']; ?>
</span></td>
    </tr>
</table>
   <br>
   <br>
<br>

<table  border="1" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td colspan="7"><div align="center">Caracteristicas del Bien </div></td>
  </tr>
  <tr>
    <td colspan="3" aling="left"  style="background: #FFFFFF;  font-size:12px;"><strong>Descripci&oacute;n:</strong></td>
    <td colspan="4" style=" font-size:12px" align="left"><?php echo $this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['descripcion']; ?>
</td>
  </tr>
  <tr>
    <td  align="left" style="background: #FFFFFF;  font-size:12px;"><strong>Unidad:</strong></td>
    <td style="font-size:12px;" align="left"><?php echo $this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['unidad']; ?>
</td>
    <td  align="left" style="background: #FFFFFF;  font-size:12px;"><strong>Serie:</strong></td>
    <td colspan="4" style="font-size:12px" align="left"><?php echo $this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['serie']; ?>
&nbsp;</td>
  </tr>
  <tr>
    <td height="36"  align="left" style="background: #FFFFFF;  font-size:12px;"><strong>Origen de Activo fijo: </strong></td>
    <td  align="left" style="background: #FFFFFF;  font-size:12px;">Factura
      <input type="checkbox" name="checkbox2" value="checkbox" <?php if ($this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['ad_id'] == 2): ?> checked="checked"<?php endif; ?> disabled="disabled" > 
      Numero:<?php if ($this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['ad_id'] == 2): ?><?php echo $this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['det_adqui']; ?>
 <?php endif; ?></td>
    <td  align="left" style="background: #FFFFFF;  font-size:12px;">Importaci&oacute;n
      <input type="checkbox" name="checkbox222" value="checkbox" <?php if ($this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['ad_id'] == 3): ?> checked="checked"<?php endif; ?>  disabled="disabled" >
      Ref: <?php if ($this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['ad_id'] == 3): ?><?php echo $this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['det_adqui']; ?>
 <?php endif; ?></td>
    <td  align="left" style="background: #FFFFFF;  font-size:12px;">Recibo Retenci&oacute;n
      <input type="checkbox" name="checkbox" value="checkbox" <?php if ($this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['ad_id'] == 1): ?>checked="checked"<?php endif; ?> disabled="disabled"></td>
    <td  align="left" style="background: #FFFFFF;  font-size:12px;"> Fabricaci&oacute;n interna
      <input type="checkbox" name="checkbox22" value="checkbox" <?php if ($this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['ad_id'] == 4): ?> checked="checked"<?php endif; ?>  disabled="disabled" ></td>
    <td  align="left" style="background: #FFFFFF;  font-size:12px;">Datos Iniciales
      <input type="checkbox" name="checkbox23" value="checkbox" <?php if ($this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['ad_id'] == 5): ?> checked="checked" <?php endif; ?> disabled="disabled" ></td>
    <td  align="left" style="background: #FFFFFF;  font-size:12px;">Ref FAF <br>
        <br></td>
  </tr>
</table>
<br>
<br>
<br>

 <table width="100%" border="1" cellspacing="0" cellpadding="0">
       <tr>
         <td colspan="8"><div align="center">Otra Informaci&oacute;n </div></td>
       </tr>
       <tr>
         <td colspan="8"  align="left" style="background: #FFFFFF;  font-size:12px;"><strong>Clasificaci&oacute;n del Bien:</strong> <?php echo $this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['desgru']; ?>
</td>
       </tr>
       <tr>
         <td colspan="8"  align="left" style="background: #FFFFFF;  font-size:12px;"><strong>Ubicaci&oacute;n del Bien:</strong> <?php echo $this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['despri']; ?>
-<?php echo $this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['dessecun']; ?>
</td>
       </tr>
       <tr>
         <td height="18" colspan="5"  align="left" style="background: #FFFFFF;  font-size:12px;"><strong>Fecha:</strong><?php echo $this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['fecha']; ?>
 </td>
       </tr>
       <tr>
         <td width="11%" rowspan="3"  align="left" style="background: #FFFFFF;  font-size:12px;"><strong>Precio:</strong></td>
		 
         <td width="12%" rowspan="3"  align="left" style="background: #FFFFFF;  font-size:12px;">Bs</td>
         <td width="25%" rowspan="3" style="font-size:12px"><div align="left"><?php echo $this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['valor_compra']; ?>
</div></td>
         <td colspan="2"  align="left" style="background: #FFFFFF;  font-size:12px;"><div align="left"><strong>Tasa Cambio </strong></div></td>
       </tr>
       <tr>
         <td width="22%"  align="left" style="background: #FFFFFF;  font-size:12px;"><strong>Dolar</strong> </td>
         <td width="30%"  align="left" style="background: #FFFFFF;  font-size:12px;"> <strong>UFV</strong> </td>
       </tr>
       <tr>
         <td  style="font-size:12px;"><div align="left"><?php echo ((is_array($_tmp=$this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['tipo_cambio'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
         <td style="font-size:12px;"><div align="left"><?php echo $this->_tpl_vars['res_kardex'][$this->_sections['pagina']['index']]['ufv']; ?>
</div></td>
       </tr>
     </table>
<br>
<br>
<br>
 <table  width="100%" border="1" cellpadding="0" cellspacing="0" 1border="0">
        <tr>
          <td colspan="4"><div align="center">Responsable / Asignado </div></td>
        </tr>
        <tr>
          <td align="center" style="background: #FFFFFF;  font-size:12px;"><strong>Nombre</strong> </td>
          <td colspan="3" align="center" style="background: #FFFFFF;  font-size:12px;"><div align="left"><?php echo $this->_tpl_vars['asignado']; ?>
</div></td>
        </tr>
        <tr>
          <td align="center" style="background: #FFFFFF;  font-size:12px;"><strong>Cargo</strong></td>
          <td align="center" style="background: #FFFFFF;  font-size:12px;"><div align="left"><?php echo $this->_tpl_vars['respo']['cargo']; ?>
</div></td>
          <td align="center" style="background: #FFFFFF;  font-size:12px;"><strong>Area</strong></td>
          <td align="center" style="background: #FFFFFF;  font-size:12px;" width="9%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['respo']['area']; ?>
</td>
        </tr>
        <tr>
          <td align="center" style="background: #FFFFFF;  font-size:12px;"><strong>Teléfono Domicilio</strong></td>
          <td>&nbsp;</td>
          <td align="center" style="background: #FFFFFF;  font-size:12px;"><strong>Teléfono Celular </strong></td>
          <td  width="9%">&nbsp;</td>
        </tr>
  </table>
  <br>
<br>
<br>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
       <tr>
         <td colspan="8"><div align="center">Historial</div></td>
       </tr>
       <tr>
         <td width="5%"  align="left" style="background: #FFFFFF;  font-size:12px;"><div align="center"><strong>N&deg;</strong></div></td>
         <td width="8%"  align="left" style="background: #FFFFFF;  font-size:12px;"><div align="center"><strong>Fecha</strong></div></td>
         <td width="21%"  align="left" style="background: #FFFFFF;  font-size:12px;"><div align="center"><strong>Responsable </strong></div></td>
         <td  align="left" style="background: #FFFFFF;  font-size:12px;" width="12%"><div align="center"><strong>Ci </strong></div></td>
         <td  align="left" style="background: #FFFFFF;  font-size:12px;" width="13%"><div align="center"><strong> Telefono/Celular </strong></div></td>
         <td  align="left" style="background: #FFFFFF;  font-size:12px;" width="19%"><div align="center"><strong>Recepcion Conforme y Aceptacion Clausula de Responsabilidad </strong></div></td>
		 <td align="left" style="background: #FFFFFF;  font-size:12px;" width="13%"><div align="center"><strong>Fecha</strong></div></td>
         <td  align="left" style="background: #FFFFFF;  font-size:12px;" width="9%" ><div align="center"><strong>Entregue/Devolví Conforme </strong> </div></td>
       </tr>
	   
       <tr>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
		 <td>&nbsp;</td>
       </tr>
	     <tr>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
		 <td>&nbsp;</td>
         <td>&nbsp;</td>
       </tr>
	     <tr>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
		 <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
       </tr>
	     <tr>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
		 <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
       </tr>
	    <tr>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
		 <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
       </tr> <tr>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
		 <td>&nbsp;</td>
       </tr>
     </table>
<br>
<br>

	  <table border="1" cellspacing="0" cellpadding="0">
       <tr>
         <td><div align="center">Cláusula de Responsabilidad</div></td>
       </tr>
       <tr>
         <td style="font-size:12px;">&quot;Como Funcionario de Macaws SRL declaro que el activo fijo expresado en el siguente documento esta bajo mi responsabilidad, por lo cual le daré un uso adecuado en el desempe&ntilde;o de mis funciones y a la destinaci&oacute;n institucional prevista para este activo. Me comprometo a informar oportunamente a la Unidad de activos Fijos sobre cualquier traslado temporal o definitivo de este activo mediante el cumplimiento de los procesos respectivos. En consecuencia, serán asumidos por mi el da&ntilde;o o la pérdida de los mismos debido a mi negligencia o incumplimiento de los instructivos relacionados con su uso y conservación"</td>
       </tr>
    </table>  </tr>
  </table>

	<?php endfor; endif; ?>   
	  
	  

</p>
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
</tr>

</table>

</body>
</html>