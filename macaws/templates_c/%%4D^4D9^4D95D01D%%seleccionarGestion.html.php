<?php /* Smarty version 2.6.18, created on 2007-07-17 10:53:16
         compiled from seleccionarGestion.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array('title' => $this->_tpl_vars['title'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br/>
<table border="0" cellspacing="0" cellpadding="0" class="tableblue">
<caption class="search-box">Gestiones Abiertas
</caption>
 <tr>    
	<?php if ($this->_tpl_vars['gestabi'] != null): ?>
	<?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['gestabi']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
	<td width="170" valign="top" align="center">
		<span class="text6">GESTI&Oacute;N</span><br/>
		<?php echo $this->_tpl_vars['gestabi'][$this->_sections['ind']['index']]['gestiont']; ?>
<br/>
		<a href="adminPeriodos.php?mcwgessel=<?php echo $this->_tpl_vars['gestabi'][$this->_sections['ind']['index']]['gestion']; ?>
" class="styleText">Usar esta gesti&oacute;n.</a>	</td>
	<?php endfor; endif; ?>
	<?php else: ?>
	<td align="center" nowrap="nowrap" class="error-box" width="500">
	No existe una gesti&oacute;n abierta.<br/>
	<input type="button" class="text6" onclick="location='/sistema/macaws/adminGestion.php'" value="Registrar nueva Gestión"/>	</td>
	<?php endif; ?>	
  </tr>
</table>
</body>
</html>