<?php /* Smarty version 2.6.18, created on 2011-04-29 12:47:32
         compiled from seleccionarPeriodo.html */ ?>
<?php if ($this->_tpl_vars['id'] == 1): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array('title' => $this->_tpl_vars['title'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "headerAuxiliar.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['periodos'] != null): ?>
<br/>
<table width="500" border="1" cellspacing="0" cellpadding="0" class="tableblue">
  <caption class="search-box">
    Seleccionar Periodo
  </caption>
  <tbody>
	<tr> 
	<?php if ($this->_tpl_vars['tipo'] == 1): ?> 	
	<?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['periodos']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
	<td align="center">
	<form action="compra.php" method="post">
	Gesti&oacute;n: <span class="text6"><?php echo $this->_tpl_vars['periodos'][$this->_sections['ind']['index']]['gestion']; ?>
</span>  
	<br/>
	 Periodo: <span class="text6"><?php echo $this->_tpl_vars['periodos'][$this->_sections['ind']['index']]['periodo']; ?>
</span>
	<br />
	<input name="cod_pg" type="hidden" value="<?php echo $this->_tpl_vars['periodos'][$this->_sections['ind']['index']]['cod_pg']; ?>
" />
	<input name="emplear" type="submit" class="text3" value="Ingresar Compra" />
	</form>
	</td>	
	<?php endfor; endif; ?>
	<?php else: ?>
	<?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['periodos']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
	<td align="center">
	<form action="venta.php" method="post">
	Gesti&oacute;n: <span class="text6"><?php echo $this->_tpl_vars['periodos'][$this->_sections['ind']['index']]['gestion']; ?>
</span>
	<br/>
	 Periodo: <span class="text6"><?php echo $this->_tpl_vars['periodos'][$this->_sections['ind']['index']]['periodo']; ?>
</span> 			
	<br />
	<input name="cod_pg" type="hidden" value="<?php echo $this->_tpl_vars['periodos'][$this->_sections['ind']['index']]['cod_pg']; ?>
" />
	<input name="emplear" type="submit" class="text3" value="Ingresar Venta" />
	</form>
	</td>	
	<?php endfor; endif; ?>
	<?php endif; ?>	
	</tr> 
  </tbody>
</table>

<?php else: ?>
<table width="700" border="1" cellpadding="0" cellspacing="0" class="error-box" >
  <tr>
    <td align="center" width="700">
	<p>
	No existe una Gesti&oacute;n con un Periodo abierto.<br/>
	Registre o aperture un periodo para continuar.<br/>
     <?php if ($this->_tpl_vars['id'] == 1): ?>
	<input name="Button" type="button" class="text6" onclick="location='/sistema/macaws/lcv.php'" value="Registrar Gesti&oacute;n-Periodo"/> 
	<?php else: ?>
	<input name="Button" type="button" class="text6" onclick="location='/sistema/macaws/generalcv.php'" value="Salir"/> 
	<?php endif; ?>
	</p>		
	</td>
  </tr>
</table>

<?php endif; ?>
</body>
</html>