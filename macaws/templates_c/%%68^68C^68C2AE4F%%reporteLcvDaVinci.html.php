<?php /* Smarty version 2.6.18, created on 2011-05-14 10:23:08
         compiled from reporteLcvDaVinci.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'reporteLcvDaVinci.html', 43, false),array('modifier', 'string_format', 'reporteLcvDaVinci.html', 81, false),array('modifier', 'lower', 'reporteLcvDaVinci.html', 125, false),array('modifier', 'trim', 'reporteLcvDaVinci.html', 125, false),)), $this); ?>
<link href="../css/fortte-admin.css" rel="stylesheet" type="text/css" />
<link href="../css/fortte-css.css" rel="stylesheet" type="text/css" />
<link href="../css/forttecss.css" rel="stylesheet" type="text/css" />

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
    <td valign="top" align="right" >&nbsp;</td>
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
		<td align="center" nowrap="nowrap" class="allbordes"><span class="text-titulo"><?php echo $this->_tpl_vars['periodo']; ?>
</span>&nbsp;</td>
		<td width="60" align="center" nowrap="nowrap" class="allbordes"><span class="text-titulo"><?php echo $this->_tpl_vars['gestion']; ?>
</span>&nbsp;</td>
		</tr>
	</table>	</td>
    <td width="197" align="left" valign="top" nowrap="nowrap" class="header-table5">		FECHA:&nbsp;<?php echo ((is_array($_tmp='now')) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y") : smarty_modifier_date_format($_tmp, "%d/%m/%Y")); ?>

	</td>
  </tr>
</table>
<br />
<?php if ($this->_tpl_vars['tipo'] == 2): ?>
<table width="900" border="1" cellspacing="0" cellpadding="2">
<thead>
  <tr>
    <td width="116" align="center" valign="middle" class="styleText">NIT</td>
    <td align="center" valign="middle" class="styleText">RAZON SOCIAL</td>
    <td  align="center" valign="middle" class="styleText">N&deg; FACTURA</td>
    <td  align="center" valign="middle" class="styleText">N&deg; POLIZA</td>
    <td  align="center" valign="middle" class="styleText">N&deg; AUTORIZACION</td>
    <td width="100" align="center" valign="middle" class="styleText">FECHA</td>
    <td width="50" align="center" valign="middle" class="styleText">TOTAL</td>
    <td width="20" align="center" valign="middle" class="styleText">ICE</td>
    <td width="20" align="center" valign="middle" class="styleText">EXENTO</td>
    <td width="20" align="center" valign="middle" class="styleText">NETO</td>
    <td width="20" align="center" valign="middle" class="styleText">IVA CF</td>
    <td width="20" align="center" valign="middle" class="styleText">COD. CONTROL</td>
    <td width="20" align="center" valign="middle" class="styleText">SUCURSAL</td>
  </tr>
</thead>
<tbody>
<?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['result']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
    <td align="center" class="textHelp"><?php echo $this->_tpl_vars['result'][$this->_sections['ind']['index']]['nit']; ?>
</td>
    <td class="tiny-text"><?php echo $this->_tpl_vars['result'][$this->_sections['ind']['index']]['razon_social']; ?>
</td>
	<?php if ($this->_tpl_vars['result'][$this->_sections['ind']['index']]['poliza']): ?>
    	<td align="center" class="tiny-text">0</td>
	    <td align="center" class="tiny-text"><?php echo $this->_tpl_vars['result'][$this->_sections['ind']['index']]['factura']; ?>
</td>
	<?php else: ?>
	    <td align="center" class="tiny-text"><?php echo $this->_tpl_vars['result'][$this->_sections['ind']['index']]['factura']; ?>
</td>
	    <td align="center" class="tiny-text">0</td>
	<?php endif; ?>
    <td align="center" class="tiny-text"><?php echo $this->_tpl_vars['result'][$this->_sections['ind']['index']]['autorizacion']; ?>
</td>
    <td class="tiny-text"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['fecha'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d") : smarty_modifier_date_format($_tmp, "%d")); ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['fecha'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m") : smarty_modifier_date_format($_tmp, "%m")); ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['fecha'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")); ?>
</td>
    <td class="tiny-text"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['total_facturado'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td class="tiny-text"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['ice'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td class="tiny-text"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['exento'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td class="tiny-text"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['importe'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td width="9%" class="tiny-text"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['credito_fiscal'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td width="14%" nowrap="nowrap" class="tiny-text"><?php if ($this->_tpl_vars['result'][$this->_sections['ind']['index']]['codigo_control'] != null): ?><?php echo $this->_tpl_vars['result'][$this->_sections['ind']['index']]['codigo_control']; ?>
<?php else: ?>0<?php endif; ?></td>
    <td class="tiny-text"><?php echo $this->_tpl_vars['result'][$this->_sections['ind']['index']]['sucursal']; ?>
</td>
  </tr>
<?php endfor; endif; ?>
</tbody>
</table>
<?php else: ?>
 <?php if ($this->_tpl_vars['tipo'] == 3): ?>
<table width="900" border="1" cellspacing="0" cellpadding="2">
<thead>
  <tr>
    <td width="116" align="center" valign="middle" class="text2">NIT</td>
    <td align="center" valign="middle" class="text2">RAZON SOCIAL</td>
    <td  align="center" valign="middle" class="text2">N&deg; FACTURA</td>
     <td  align="center" valign="middle" class="text2">N&deg; AUTORIZACION</td>
    <td width="100" align="center" valign="middle" class="text2">FECHA</td>
    <td width="50" align="center" valign="middle" class="text2">TOTAL</td>
    <td width="20" align="center" valign="middle" class="text2">ICE</td>
    <td width="20" align="center" valign="middle" class="text2">EXENTO</td>
    <td width="20" align="center" valign="middle" class="text2">NETO</td>
    <td width="20" align="center" valign="middle" class="text2">IVA DF</td>
    <td width="20" align="center" valign="middle" class="text2">ESTADO</td>
	<td width="20" align="center" valign="middle" class="text2">COD. CONTROL</td>
  	<td width="20" align="center" valign="middle" class="text2">SUCURSAL</td>
    </tr>
</thead>
<tbody>
<?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['result']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
    <td align="center" class="tiny-text"><?php echo $this->_tpl_vars['result'][$this->_sections['ind']['index']]['nit']; ?>
</td>
    <td class="tiny-text"><?php echo $this->_tpl_vars['result'][$this->_sections['ind']['index']]['razon_social']; ?>
</td>
	<td align="center" class="tiny-text"><?php echo $this->_tpl_vars['result'][$this->_sections['ind']['index']]['factura']; ?>
</td>
	<td align="center" class="tiny-text"><?php echo $this->_tpl_vars['result'][$this->_sections['ind']['index']]['autorizacion']; ?>
</td>
    <td class="tiny-text"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['fecha'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d") : smarty_modifier_date_format($_tmp, "%d")); ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['fecha'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m") : smarty_modifier_date_format($_tmp, "%m")); ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['fecha'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")); ?>
</td>
    <td class="tiny-text"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['total_facturado'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td class="tiny-text"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['ice'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td class="tiny-text"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['exento'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td class="tiny-text"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['importe'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td width="9%" class="tiny-text"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['credito_fiscal'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
    <td  nowrap="nowrap" class="tiny-text"><?php if (((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['razon_social'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)))) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp)) == 'anulado' || ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['razon_social'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)))) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp)) == 'anulada'): ?>A<?php elseif (((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['razon_social'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)))) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp)) == 'extraviado' || ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['razon_social'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)))) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp)) == 'extraviada'): ?>E<?php elseif (((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['razon_social'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)))) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp)) == 'no utilizado' || ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['ind']['index']]['razon_social'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)))) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp)) == 'no utilizada'): ?>N<?php else: ?>V<?php endif; ?></td>
<td width="14%" nowrap="nowrap" class="tiny-text"><?php if ($this->_tpl_vars['result'][$this->_sections['ind']['index']]['codigo_control'] != null): ?><?php echo $this->_tpl_vars['result'][$this->_sections['ind']['index']]['codigo_control']; ?>
<?php else: ?>0<?php endif; ?></td>
	 <td align="center" class="tiny-text"><?php echo $this->_tpl_vars['result'][$this->_sections['ind']['index']]['sucursal']; ?>
</td>
  </tr>
<?php endfor; endif; ?>
</tbody>
</table>

  <?php endif; ?>
<?php endif; ?>


</body>
</html>