<?php /* Smarty version 2.6.18, created on 2012-01-17 10:37:11
         compiled from reporteSalida.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'reporteSalida.html', 120, false),array('modifier', 'string_format', 'reporteSalida.html', 128, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $this->_tpl_vars['title']; ?>
</title>
<link href="css/fortte-admin.css" rel="stylesheet" type="text/css" />
<link href="css/forttecss.css" rel="stylesheet" type="text/css" />
<link href="css/fortte-css.css" rel="stylesheet" type="text/css" />
<link href="../css/fortte-admin.css" rel="stylesheet" type="text/css" />
<link href="../css/forttecss.css" rel="stylesheet" type="text/css" />
<link href="../css/fortte-css.css" rel="stylesheet" type="text/css" />
</head>

<body>


<table width="715" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">&nbsp;</td>
    <td align="center" valign="top" class="titulo-reporte"><?php echo $this->_tpl_vars['title']; ?>
 </td>
    <td valign="top" align="right" >
	<table  border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td align="right" class="titulo-reporte2">FOLIO:</td>
		<td align="center" class="allbordes" width="50"><span class="titulo-reporte2"><?php echo $this->_tpl_vars['result']['pagina']; ?>
</span></td>
		</tr>
	</table>	</td>
  </tr>
  <tr>
    <td valign="top"></td>
    <td align="center" valign="top" class="titulo-reporte"><br />
</td>
    <td align="left" valign="top" nowrap="nowrap" class="header-table5">&nbsp;</td>
  </tr>
  <tr>
    <td width="117" valign="top">	</td>
    <td width="401" align="center" valign="top" class="titulo-reporte">
	<table width="180" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td nowrap="NOWRAP" class="obs-quotation" width="100">PER&Iacute;ODO FISCAL&nbsp;</td>
		<td align="center" nowrap="nowrap" class="allbordes"><span class="text-titulo"><?php echo $this->_tpl_vars['result']['periodo']; ?>
</span>&nbsp;</td>
		<td width="60" align="center" nowrap="nowrap" class="allbordes"><span class="text-titulo"><?php echo $this->_tpl_vars['result']['gestion']; ?>
</span>&nbsp;</td>
		</tr>
	</table>	</td>
    <td width="197" align="left" valign="top" nowrap="nowrap" class="header-table5">		FECHA:&nbsp;<?php echo $this->_tpl_vars['diahora']; ?>
	</td>
  </tr>
</table>


<br />

<table width="650" border="0" cellpadding="1" cellspacing="2" class="allbordes">
  <tr>
    <td width="160" align="right" nowrap="nowrap">NOMBRE O RAZ&Oacute;N SOCIAL : </td>
    <td class="allbordes" colspan="3"><span class="text-titulo"><?php echo $this->_tpl_vars['contribuyente']['razon_social']; ?>
</span>&nbsp;</td>
    <td width="46" align="right" nowrap="nowrap">NIT:</td>
    <td width="150" class="allbordes"><span class="text-titulo"><?php echo $this->_tpl_vars['contribuyente']['nit']; ?>
</span>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" nowrap="nowrap">N&deg; DE SUCURSAL : </td>
    <td width="100" class="allbordes"><span class="text-titulo"><?php echo $this->_tpl_vars['contribuyente']['sucursal']; ?>
</span>&nbsp;</td>
	<td width="77" align="right" nowrap="nowrap" >DIRECCI&Oacute;N : </td>
	<td colspan="3" class="allbordes"><span class="text-titulo"><?php echo $this->_tpl_vars['contribuyente']['direccion']; ?>
</span>&nbsp;</td>
  </tr>
</table>
<br />
<span class="text-titulo"><?php echo $this->_tpl_vars['tipovent']; ?>
</span>
<br />
<?php if ($this->_tpl_vars['alfanum'] == null): ?>

<table width="900" border="0" cellspacing="0" cellpadding="0" class="bordesPlomos">
<tr>
<td colspan="8">
</td>
<th colspan="5" align="center" class="header-table5">
CIFRAS EN BOLIVIANOS</th>
</tr>
  <tr>
    <th colspan="3" align="center" class="header-table5">FECHA</th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">
	<?php if ($this->_tpl_vars['title'] == 'LIBRO DE COMPRAS IVA'): ?>
	N&deg; DE NIT DEL<br/>
    PROVEEDOR
	<?php else: ?> 
	N&deg; DE NIT DEL<br/>
    COMPRADOR
	<?php endif; ?></th>
    <th width="210" rowspan="2" align="center" nowrap="nowrap" class="header-table5">NOMBRE O RAZ&Oacute;N SOCIAL</br>
   <?php if ($this->_tpl_vars['title'] == 'LIBRO DE COMPRAS IVA'): ?> DEL PROVEEDOR<?php else: ?>
   DEL COMPRADOR <?php endif; ?> </th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">N&deg; DE<br/>
    FACTURA </th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">N&deg; DE<br/>
    AUTORIZACI&Oacute;N </th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">C&Oacute;DIGO DE
    CONTROL </th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">TOTAL<br/>
    FACTURADO<br/>(A) </th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">TOTAL<br/>
    I.C.E.<br/>(B)</th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">IMPORTES<br/>
    EXENTOS<br/>(C) </th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">IMPORTE<br/>
    NETO<br/>(A-B-C) </th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">
	<?php if ($this->_tpl_vars['title'] == 'LIBRO DE COMPRAS IVA'): ?>CREDITO<br/><?php else: ?>DEBITO<br/><?php endif; ?>
    FISCAL<br/>I.V.A. 
	</th>
	<?php if ($this->_tpl_vars['contribuyente']['sucursal'] == 'Todos'): ?>
<th  rowspan="2" align="center" nowrap="nowrap" class="header-table5">SUCURSAL</th>
<?php endif; ?>
  </tr>
  <tr>
    <th align="center" class="header-table5">D</th>
    <th align="center" class="header-table5">M</th>
    <th align="center" class="header-table5">A</th>
  </tr>
	<?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['result']['res']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ind']['show'] = true;
$this->_sections['ind']['max'] = $this->_sections['ind']['loop'];
$this->_sections['ind']['step'] = 1;
$this->_sections['ind']['start'] = $this->_sections['ind']['step'] > 0 ? 0 : $this->_sections['ind']['loop']-1;
if ($this->_sections['ind']['show']) {
    $this->_sections['ind']['total'] = $this->_sections['ind']['loop'];
    if ($this->_sections['ind']['total'] == 0)
        $this->_sections['ind']['show'] = false;
} else
    $this->_sections['ind']['total'] = 0;
if ($this->_sections['ind']['show']):

            for ($this->_sections['ind']['index'] = $this->_sections['ind']['start'], $this->_sections['ind']['iteration'] = 1;
                 $this->_sections['ind']['iteration'] <= $this->_sections['ind']['total'];
                 $this->_sections['ind']['index'] += $this->_sections['ind']['step'], $this->_sections['ind']['iteration']++):
$this->_sections['ind']['rownum'] = $this->_sections['ind']['iteration'];
$this->_sections['ind']['index_prev'] = $this->_sections['ind']['index'] - $this->_sections['ind']['step'];
$this->_sections['ind']['index_next'] = $this->_sections['ind']['index'] + $this->_sections['ind']['step'];
$this->_sections['ind']['first']      = ($this->_sections['ind']['iteration'] == 1);
$this->_sections['ind']['last']       = ($this->_sections['ind']['iteration'] == $this->_sections['ind']['total']);
?>
  <tr>
    <td align="center" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['fecha'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d") : smarty_modifier_date_format($_tmp, "%d")); ?>
</td>
    <td align="center" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['fecha'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m") : smarty_modifier_date_format($_tmp, "%m")); ?>
</td>
    <td align="center" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['fecha'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")); ?>
</td>
    <td align="center" class="obs-quotation"><?php echo $this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['nit']; ?>
</td>
    <td class="obs-quotation"><?php echo $this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['razon_social']; ?>
</td>
    <td align="center" class="obs-quotation"><?php echo $this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['factura']; ?>
</td>
    <td align="center" class="obs-quotation"><?php echo $this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['autorizacion']; ?>
</td>
    <td align="center" class="obs-quotation"><?php if ($this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['codigo_control'] != null): ?><?php echo $this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['codigo_control']; ?>
<?php else: ?>0<?php endif; ?></td>
    <td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['total_facturado'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['ice'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['exento'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['importe'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['credito_fiscal'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<?php if ($this->_tpl_vars['contribuyente']['sucursal'] == 'Todos'): ?>
    <td align="center" class="obs-quotation"><?php echo $this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['sucursal_id']; ?>
</td>
    <?php endif; ?>
  </tr>
	<?php if ($this->_sections['ind']['last']): ?>
	<tr>
	<td colspan="20"> &nbsp;</td>
	</tr>
	<tr>
	<td colspan="6" rowspan="2" align="center">
	<table width="100" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td >
	<table  border="0" cellspacing="0" cellpadding="0" class="allbordes" width="145">
	<tr>
	<td align="center" width="130" nowrap="nowrap" style="border-bottom:#000000 1px solid;"><?php echo $this->_tpl_vars['persona']['ci']; ?>
&nbsp;</td>
	</tr>
	<tr>
	<td align="center" nowrap="nowrap" class="header-table5">C.I.</td>
	</tr>
	</table>	</td>
	<td width="40">&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td align="center">
	<table border="0" cellspacing="0" cellpadding="0" class="allbordes">
	<tr>
	<td width="250" align="center" nowrap="nowrap" style="border-bottom:#000000 1px solid;"><?php echo $this->_tpl_vars['persona']['nombres']; ?>
&nbsp;<?php echo $this->_tpl_vars['persona']['apellidos']; ?>
&nbsp;</td>
	</tr>
	<tr>
	<td align="center" nowrap="nowrap" class="header-table5">NOMBRE COMPLETO DEL RESPONSABLE </td>
	</tr>
	</table>	</td>
	</tr>
	</table>	</td>
	<td colspan="2" class="header-table5">
	TOTALES PARCIALES	</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['totalfact'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['totalice'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['totalexen'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['totalimp'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['totaliva'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	</tr>	
	
	<tr>
	<?php if ($this->_tpl_vars['totales'] != null): ?>
	<td colspan="2" class="header-table5">TOTALES GENERALES</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['totales']['totalfact'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['totales']['totalice'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['totales']['totalexen'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['totales']['totalimp'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['totales']['totaliva'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>	
	<?php else: ?>
	<td colspan="7">&nbsp;</td>
	<?php endif; ?>	</tr>
	<?php endif; ?>
	<?php endfor; endif; ?>  
</table>

<?php else: ?>

<table width="800" border="0" cellspacing="0" cellpadding="0" class="bordesPlomos">
<tr>
<td colspan="9">
</td>
<th colspan="5" align="center" class="header-table5">
CIFRAS EN BOLIVIANOS</th>
</tr>
  <tr>
    <th colspan="3" align="center" class="header-table5">FECHA</th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">
	<?php if ($this->_tpl_vars['title'] == 'LIBRO DE COMPRAS IVA'): ?>
	N&deg; DE NIT DEL<br/>
    PROVEEDOR
	<?php else: ?> 
	N&deg; DE NIT DEL<br/>
    COMPRADOR
	<?php endif; ?>
	
	
	
	</th>
    <th width="175" rowspan="2" align="center" nowrap="nowrap" class="header-table5">NOMBRE O RAZ&Oacute;N SOCIAL<br/>
   <?php if ($this->_tpl_vars['title'] == 'LIBRO DE COMPRAS IVA'): ?> DEL PROVEEDOR<?php else: ?> <br>DEL COMPRADOR <?php endif; ?>  </th>
	<th rowspan="2" align="center" nowrap="nowrap" class="header-table5">N&deg;<br/>
    ALFANUMERICO </th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">N&deg; DE<br/>
    FACTURA </th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">N&deg; DE<br/>
    AUTORIZACI&Oacute;N </th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">C&Oacute;DIGO DE
    CONTROL </th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">TOTAL<br/>
    FACTURADO<br/>(A) </th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">TOTAL<br/>
    I.C.E.<br/>(B)</th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">IMPORTES<br/>
    EXENTOS<br/>(C) </th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">IMPORTE<br/>
    NETO<br/>(A-B-C) </th>
    <th rowspan="2" align="center" nowrap="nowrap" class="header-table5">
	<?php if ($this->_tpl_vars['title'] == 'LIBRO DE COMPRAS IVA'): ?>CREDITO<br/><?php else: ?>DEBITO<br/><?php endif; ?>
    FISCAL<br/>I.V.A. 
	</th>
	 <?php if ($this->_tpl_vars['contribuyente']['sucursal'] == 'Todos'): ?>
<th  rowspan="2" align="center" nowrap="nowrap" class="header-table5">SUCURSAL</th>
<?php endif; ?>
  </tr>
  <tr>
    <th align="center" class="header-table5">D</th>
    <th align="center" class="header-table5">M</th>
    <th align="center" class="header-table5">A</th>
  </tr>
	<?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['result']['res']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ind']['show'] = true;
$this->_sections['ind']['max'] = $this->_sections['ind']['loop'];
$this->_sections['ind']['step'] = 1;
$this->_sections['ind']['start'] = $this->_sections['ind']['step'] > 0 ? 0 : $this->_sections['ind']['loop']-1;
if ($this->_sections['ind']['show']) {
    $this->_sections['ind']['total'] = $this->_sections['ind']['loop'];
    if ($this->_sections['ind']['total'] == 0)
        $this->_sections['ind']['show'] = false;
} else
    $this->_sections['ind']['total'] = 0;
if ($this->_sections['ind']['show']):

            for ($this->_sections['ind']['index'] = $this->_sections['ind']['start'], $this->_sections['ind']['iteration'] = 1;
                 $this->_sections['ind']['iteration'] <= $this->_sections['ind']['total'];
                 $this->_sections['ind']['index'] += $this->_sections['ind']['step'], $this->_sections['ind']['iteration']++):
$this->_sections['ind']['rownum'] = $this->_sections['ind']['iteration'];
$this->_sections['ind']['index_prev'] = $this->_sections['ind']['index'] - $this->_sections['ind']['step'];
$this->_sections['ind']['index_next'] = $this->_sections['ind']['index'] + $this->_sections['ind']['step'];
$this->_sections['ind']['first']      = ($this->_sections['ind']['iteration'] == 1);
$this->_sections['ind']['last']       = ($this->_sections['ind']['iteration'] == $this->_sections['ind']['total']);
?>
  <tr>
    <td align="center" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['fecha'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d") : smarty_modifier_date_format($_tmp, "%d")); ?>
</td>
    <td align="center" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['fecha'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m") : smarty_modifier_date_format($_tmp, "%m")); ?>
</td>
    <td align="center" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['fecha'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")); ?>
</td>
    <td align="center" class="obs-quotation"><?php echo $this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['nit']; ?>
</td>
    <td class="obs-quotation"><?php echo $this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['razon_social']; ?>
</td>
	<td align="center" class="obs-quotation">&nbsp;<?php echo $this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['alfanumerico']; ?>
</td>
    <td align="center" class="obs-quotation"><?php echo $this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['factura']; ?>
</td>
    <td align="center" class="obs-quotation"><?php echo $this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['autorizacion']; ?>
</td>
    <td align="center" class="obs-quotation">&nbsp;<?php echo $this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['codigo_control']; ?>
</td>
    <td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['total_facturado'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['ice'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['exento'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['importe'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['credito_fiscal'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<?php if ($this->_tpl_vars['contribuyente']['sucursal'] == 'Todos'): ?>
    <td align="center" class="obs-quotation"><?php echo $this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['sucursal_id']; ?>
</td>
    <?php endif; ?>
  </tr>
	<?php if ($this->_sections['ind']['last']): ?>
	<tr>
	<td colspan="20"> <p>&nbsp;</p></td>
	</tr>
	<tr>
	<td colspan="7" rowspan="2" align="center">
	<table width="100" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td >
	<table  border="0" cellspacing="0" cellpadding="0" class="allbordes" width="145">
	<tr>
	<td align="center" width="130" nowrap="nowrap" style="border-bottom:#000000 1px solid;"><?php echo $this->_tpl_vars['persona']['ci']; ?>
&nbsp;</td>
	</tr>
	<tr>
	<td align="center" nowrap="nowrap" class="header-table5">C.I.</td>
	</tr>
	</table>	</td>
	<td width="40">&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td align="center">
	<table border="0" cellspacing="0" cellpadding="0" class="allbordes">
	<tr>
	<td width="250" align="center" nowrap="nowrap" style="border-bottom:#000000 1px solid;"><?php echo $this->_tpl_vars['persona']['nombres']; ?>
&nbsp;<?php echo $this->_tpl_vars['persona']['apellidos']; ?>
&nbsp;</td>
	</tr>
	<tr>
	<td align="center" nowrap="nowrap" class="header-table5">NOMBRE COMPLETO DEL RESPONSABLE </td>
	</tr>
	</table>	</td>
	</tr>
	</table>	</td>
	<td colspan="2" class="header-table5">
	TOTALES PARCIALES	</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['totalfact'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['totalice'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['totalexen'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['totalimp'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['res'][$this->_sections['ind']['index']]['totaliva'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	</tr>	
	
	<tr>
	<?php if ($this->_tpl_vars['totales'] != null): ?>
	<td colspan="2" class="header-table5">TOTALES GENERALES</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['totales']['totalfact'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['totales']['totalice'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['totales']['totalexen'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['totales']['totalimp'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
	<td align="right" class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['totales']['totaliva'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>	
	<?php else: ?>
	<td colspan="7">&nbsp;</td>
	<?php endif; ?>	</tr>
	<?php endif; ?>
	<?php endfor; endif; ?>  
</table>

<?php endif; ?>
</body>
</html>