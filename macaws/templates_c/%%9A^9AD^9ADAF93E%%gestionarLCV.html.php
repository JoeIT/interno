<?php /* Smarty version 2.6.18, created on 2011-04-29 10:17:45
         compiled from gestionarLCV.html */ ?>
<body topmargin="0" leftmargin="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#2C7FC5"><img src="css/imagenes/cabecera.jpg" border="0"/></td>
  </tr>
</table>
<br>
<?php unset($this->_sections['id']);
$this->_sections['id']['name'] = 'id';
$this->_sections['id']['loop'] = is_array($_loop=$this->_tpl_vars['sucursales']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['id']['show'] = true;
$this->_sections['id']['max'] = $this->_sections['id']['loop'];
$this->_sections['id']['step'] = 1;
$this->_sections['id']['start'] = $this->_sections['id']['step'] > 0 ? 0 : $this->_sections['id']['loop']-1;
if ($this->_sections['id']['show']) {
    $this->_sections['id']['total'] = $this->_sections['id']['loop'];
    if ($this->_sections['id']['total'] == 0)
        $this->_sections['id']['show'] = false;
} else
    $this->_sections['id']['total'] = 0;
if ($this->_sections['id']['show']):

            for ($this->_sections['id']['index'] = $this->_sections['id']['start'], $this->_sections['id']['iteration'] = 1;
                 $this->_sections['id']['iteration'] <= $this->_sections['id']['total'];
                 $this->_sections['id']['index'] += $this->_sections['id']['step'], $this->_sections['id']['iteration']++):
$this->_sections['id']['rownum'] = $this->_sections['id']['iteration'];
$this->_sections['id']['index_prev'] = $this->_sections['id']['index'] - $this->_sections['id']['step'];
$this->_sections['id']['index_next'] = $this->_sections['id']['index'] + $this->_sections['id']['step'];
$this->_sections['id']['first']      = ($this->_sections['id']['iteration'] == 1);
$this->_sections['id']['last']       = ($this->_sections['id']['iteration'] == $this->_sections['id']['total']);
?>

<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center"  class="header-table">
  <tr>
  <?php if ($this->_tpl_vars['grupo'] == 12): ?><!--12 es el grupo id del administrador-->
    <td  align="right" width="20%"><img src="css/imagenes/sucursal.PNG" border="0" /></td>
    <td align="left" width="30%" style="font-family:Verdana, Geneva, sans-serif; font-style:inherit "><a  id="<?php echo $this->_tpl_vars['sucursales'][$this->_sections['id']['index']]['cod_sucursal']; ?>
" href="sucursalcv.php?cod=<?php echo $this->_tpl_vars['sucursales'][$this->_sections['id']['index']]['cod_sucursal']; ?>
&name=<?php echo $this->_tpl_vars['sucursales'][$this->_sections['id']['index']]['sucursal']; ?>
" class="blue-small-link"><br/>&nbsp;&nbsp;<?php echo $this->_tpl_vars['sucursales'][$this->_sections['id']['index']]['sucursal']; ?>
</a></td>
  <?php else: ?>
       <?php if ($this->_tpl_vars['sucursales'][$this->_sections['id']['index']]['cod_sucursal'] != 1): ?>
       <td  align="right" width="20%"><img src="css/imagenes/sucursal.PNG" border="0" /></td>
       <td align="left" width="30%" style="font-family:Verdana, Geneva, sans-serif; font-style:inherit "><a  id="<?php echo $this->_tpl_vars['sucursales'][$this->_sections['id']['index']]['cod_sucursal']; ?>
" href="sucursalcv.php?cod=<?php echo $this->_tpl_vars['sucursales'][$this->_sections['id']['index']]['cod_sucursal']; ?>
&name=<?php echo $this->_tpl_vars['sucursales'][$this->_sections['id']['index']]['sucursal']; ?>
" class="blue-small-link"><br/>&nbsp;&nbsp;<?php echo $this->_tpl_vars['sucursales'][$this->_sections['id']['index']]['sucursal']; ?>
</a></td>
       <?php endif; ?>
  <?php endif; ?>
  </tr>
</table>
<br>
<?php endfor; endif; ?>
<br />
<br />
</body>