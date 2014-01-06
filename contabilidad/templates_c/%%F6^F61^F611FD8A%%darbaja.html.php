<?php /* Smarty version 2.6.18, created on 2009-03-21 11:07:44
         compiled from darbaja.html */ ?>
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

<form name="orden" method="get" action="../controladores/activo.php"  align="center">
  <table border="0" align="center" cellpadding="0" cellspacing="3" width="35%">
    <tr>
      <th>Dado de Baja </th>
    </tr>
  </table>
  <table width="35%" border="0" align="center" cellpadding="0" cellspacing="3">
				<tr> <?php if ($this->_tpl_vars['error'] != null): ?>
					 <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['error']; ?>
</td> </tr><?php endif; ?>
	<?php if ($this->_tpl_vars['errores']['err_pri'] != null): ?><tr> 
					 <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['errores']['err_pri']; ?>
 </td> </tr> <?php endif; ?>
  
  <?php if ($this->_tpl_vars['errores']['err_sec'] != null): ?><tr> 
					 <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['errores']['err_sec']; ?>
 </td> </tr> <?php endif; ?>

 <?php if ($this->_tpl_vars['errores']['err_gru'] != null): ?><tr> 
					 <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['errores']['err_gru']; ?>
 </td> </tr> <?php endif; ?>
  
  <?php if ($this->_tpl_vars['errores']['err_confirm'] != null): ?><tr> 
					 <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['errores']['err_confirm']; ?>
 </td> </tr> <?php endif; ?>
  
	
	 
  
    <td width="22%" class="enunciados">Localizacion Primaria</td>
	 
    <td width="23%">
      <select name="pri" id="pri2" onchange="actualizar ('combo.php','pri2','secundaria','primaria')" >
                <option value="selc">Seleccionar</option>
          
                          
  <?php $_from = $this->_tpl_vars['primaria']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>	 
	   	
            
        
        
          
          <option value="<?php echo $this->_tpl_vars['item']['primaria_id']; ?>
"><?php echo $this->_tpl_vars['item']['descripcion']; ?>
</option>
          
          
         
            
         	    	   
	  
	<?php endforeach; endif; unset($_from); ?>	
  
          
      
      
        
        </select></td>
    </tr>
  <tr>
    <td class="enunciados">Localizacion Secundaria </td>
    <td><div id="secundaria" ></div>    </td>
    </tr>
  <tr>
    <td class="enunciados">Cuenta:</td>
    <td><select name="gru" id="sec"  >
      <option value="selc">Seleccionar</option>
      
    
      
          
  <?php $_from = $this->_tpl_vars['grupo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>	 
	   
      		
        
      
    
      <option value="<?php echo $this->_tpl_vars['item']['grupo_id']; ?>
"><?php echo $this->_tpl_vars['item']['descripcion']; ?>
</option>
      
    
      
        
         	    	   
	  
	<?php endforeach; endif; unset($_from); ?>	
  
      
    
  
    </select></td>
  </tr>
  <tr>
    <td class="enunciados">Numero Activo: </td>
    <td><input type="text" name="num_act" /></td>
  </tr>
</table>

  <center><p><input type="submit" value="Buscar Activo" name="baja" class="enviar" onmouseover="this.className='boton'" onmouseout="this.className='enviar'">
  </p></center>
</form>
  <p><?php if ($this->_tpl_vars['busqueda1']): ?>  </p>
<table align="center"  width="95%" cellpadding="3">
    <tr>
      <th colspan="6"> Lista de Activos </th>
    </tr>
    <tr>
      <td width="13%" class="enunciados" style="text-align:center">Activo</td>
      <td width="11%" class="enunciados" style="text-align:center">Loc Primaria </td>
      <td width="14%" class="enunciados" style="text-align:center">Loc secundaria </td>
      <td width="5%" class="enunciados" style="text-align:center">Grupo</td>
      <td width="13%" class="enunciados" style="text-align:center">Num correlativo </td>
      <td width="44%" class="enunciados" style="text-align:center">Descripcion</td>
    </tr>
 <?php $this->assign('CSSclass', 0); ?>
	 <?php $this->assign('clases', "lista-normal"); ?>
     <?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['lista1']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
	<!--
	solo para modificar los activos que ya tinen sin responsables
	-->  
	  <!--<a href="../controladores/modificar_activo.php?funcion=detalle&elegido=<?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['act_id']; ?>
 " name="<?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['act_id']; ?>
" id="<?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['act_id']; ?>
"><?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['numero']; ?>
</a>
-->

		<a href="../controladores/darbaja.php?funcion=detalle&elegido=<?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['act_id']; ?>
 " name="<?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['act_id']; ?>
" id="<?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['act_id']; ?>
"><?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['numero']; ?>
</a>
      <td><?php if ($this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['localizacion'] < 10): ?>0<?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['localizacion']; ?>
<?php else: ?><?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['localizacion']; ?>
<?php endif; ?></td>
			   <td align="center">
			   <?php if ($this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['locsecundaria'] < 10): ?>0<?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['locsecundaria']; ?>
<?php else: ?><?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['locsecundaria']; ?>
<?php endif; ?></td>
			   
			       <td><?php if ($this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['grupo_id'] < 10): ?>0<?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['grupo_id']; ?>
<?php else: ?><?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['grupo_id']; ?>
<?php endif; ?></td>
                   <td><?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['num_act']; ?>
</td>
                   <td><?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['descripcion']; ?>
</td>
    </tr>
    <?php endfor; endif; ?>
  <tr>
    <td colspan="6"><hr>    </td>
    </tr>
  </table>  
<br />
 
<?php endif; ?>
<p>&nbsp;</p>
  <p>&nbsp;    </p>

</form>

</body>