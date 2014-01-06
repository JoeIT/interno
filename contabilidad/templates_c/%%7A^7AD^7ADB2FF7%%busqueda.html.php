<?php /* Smarty version 2.6.18, created on 2009-07-22 13:36:05
         compiled from busqueda.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cabecera.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <br />
<?php echo '

<script language="javascript">
/*function actualizar (paginaservidor,combo,lista,clave){

var miAjax = new Ajax(paginaservidor,
{
	method: \'get\',
	data:clave+\'=\'+$(combo).value,
	
	update: $(lista)
	
});
miAjax.request();

//alert (paginaservidor+" "+combo+" "+lista+" "+$(combo).value);
}*/
function actualizar (paginaservidor,combo,lista,clave){

new Ajax.Updater(lista, paginaservidor, { 
  method: \'get\', 
  parameters: { primaria: $F(combo)} 
});

//alert (paginaservidor+" "+combo+" "+lista+" "+$(combo).value);
}
</script>
'; ?>

<form name="orden" method="get" action="../controladores/buscar.php"  align="center">
  <table border="0" align="center" cellpadding="0" cellspacing="3" width="35%">
    <tr>
      <th>Busqueda por Nombre </th>
    </tr>
  </table>
  <table width="35%" border="0" align="center" cellpadding="0" cellspacing="3">
				<tr> <?php if ($this->_tpl_vars['error'] != null): ?>
					 <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['error']; ?>
</td> </tr><?php endif; ?>
	<?php if ($this->_tpl_vars['errores']['err_pri'] != null): ?><tr> 
					 <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['errores']['err_pri']; ?>
 </td> </tr> <?php endif; ?>
  
  
  <tr>
    <td width="22%" class="enunciados">Nombre: </td>
    <td width="23%"><input type="text" name="nombre_act" onkeypress="return handleEnter(this, event)"/></td>
  </tr>
</table>

  <center>
    <p>
      <input type="submit" value="Buscar Activo" name="buscar" class="enviar" onmouseover="this.className='boton'" onmouseout="this.className='enviar'"  />
    </p>
  </center>
</form>
  <p><?php if ($this->_tpl_vars['busqueda1']): ?>  </p>
  <table align="center"   cellpadding="3">
    <tr>
      <th colspan="2"> Lista de Activos </th>
    </tr>
    <tr>
      <td class="enunciados" style="text-align:center">Activo</td>
      <td  class="enunciados" style="text-align:center">Descripcion</td>
    </tr>
 <?php $this->assign('CSSclass', 0); ?>
	 <?php $this->assign('clases', "lista-normal"); ?>
     <?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['act']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<?php $this->assign('CSSclass', ($this->_tpl_vars['CSSclass']+1)); ?>
	    <?php if (( $this->_tpl_vars['CSSclass'] % 2 ) == 0): ?>
		    <?php $this->assign('clases', "lista-seleccionada"); ?>
	    <?php else: ?>
		    <?php $this->assign('clases', "lista-normal"); ?>
	    <?php endif; ?>
    <tr class="<?php echo $this->_tpl_vars['clases']; ?>
" onmouseover="this.style.cursor = 'pointer';this.className = 'lista_terrible';" onmouseout="this.className = '<?php echo $this->_tpl_vars['clases']; ?>
';" align="center"> 
     
	  
      <td align="center">

		<a href="../controladores/buscar.php?funcion=detalle&elegido=<?php echo $this->_tpl_vars['act'][$this->_sections['ind']['index']]['act_id']; ?>
 " name="<?php echo $this->_tpl_vars['act'][$this->_sections['ind']['index']]['act_id']; ?>
" id="<?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['act_id']; ?>
"><?php echo $this->_tpl_vars['act'][$this->_sections['ind']['index']]['numero']; ?>
</a>
      <td><?php echo $this->_tpl_vars['act'][$this->_sections['ind']['index']]['descripcion']; ?>
</td>
    </tr>
    <?php endfor; endif; ?>
  <tr>
    <td colspan="2"><hr>    </td>
    </tr>
  </table>
  <br />
 
<?php endif; ?>
<p>&nbsp;</p>
  <p>&nbsp;    </p>

</form>

</body>