<?php /* Smarty version 2.6.18, created on 2009-07-22 09:29:52
         compiled from rep_ingreso.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cabecera.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <br />
<?php echo '
<style>

body { 
   overflow:scroll;  
}

</style>




<script>

function actualizar (paginaservidor, combo, lista, clave){
	new Ajax.Updater(lista, paginaservidor+\'?\'+clave+\'=\'+$F(combo), { 
	  method: \'get\'	});
}

function correlativo (paginaservidor,combo,lista,clave){

new Ajax.Updater(lista, paginaservidor, { 
  method: \'get\', 
  parameters: { cuenta: $F(combo)} 
});
}
</script>

'; ?>

<form name="asignacion_rep" method="get" action="../controladores/rep_ingreso.php"  align="center">
<table width="70%" border="0" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <th colspan="13" > Reporte de activos ingresados </th>
  </tr>
  <?php if ($this->_tpl_vars['error']['fecha'] != null): ?>
  <tr>
    <td colspan="4"><div class="error-box" style="width:100%;"><?php echo $this->_tpl_vars['error']['fecha']; ?>
</div></td>
  </tr>
  <?php endif; ?>
   <?php if ($this->_tpl_vars['error']['control'] != null): ?>
  <tr>
    <td colspan="4"><div class="error-box" style="width:100%;"><?php echo $this->_tpl_vars['error']['control']; ?>
</div></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="enunciados"> Fecha Inicio: </td>
    <td class="body-sector"><span align="left" class="entrada" style="margin:5px;vertical-align:top;"> <img src="../../templates/imagenes/fecha.jpg" class="imagenes"/>
          <input type="text" name="fecha_inicio" id="fecha_inicio" value="<?php echo $this->_tpl_vars['fecha_inicio']; ?>
" readonly="READONLY" style="border:none;" onkeypress="return enter_handle(this, event, 1, 1)" />
      </span>
        <input type="button" name="b_fecha_inicio" id="b_fecha_inicio" value="..." class="boton" onkeypress="return enter_handle(this, event, 1, 1)" />
      <?php echo '
      <script type="text/javascript">Calendar.setup({inputField:"fecha_inicio", ifFormat:"%Y-%m-%d",button:"b_fecha_inicio"});</script>
      '; ?>
 </td>
    <td class="enunciados"> Fecha Fin: </td>
    <td class="body-sector"><span align="left" class="entrada" style="margin:5px;vertical-align:top;"> <img src="../../templates/imagenes/fecha.jpg" class="imagenes"/>
          <input type="text" name="fecha_fin" id="fecha_fin" value="<?php echo $this->_tpl_vars['fecha_fin']; ?>
" readonly="READONLY" style="border:none;" onkeypress="return enter_handle(this, event, 1, 1)" />
      </span>
        <input type="button" name="b_fecha_fin" id="b_fecha_fin" value="..." class="boton" onkeypress="return enter_handle(this, event, 1, 1)" />
      <?php echo '
      <script type="text/javascript">Calendar.setup({inputField:"fecha_fin", ifFormat:"%Y-%m-%d",button:"b_fecha_fin"});</script>
      '; ?>
 </td>
  </tr>
   <?php if ($this->_tpl_vars['error']['fecha_inter'] != null): ?>  <?php endif; ?>
  <?php if ($this->_tpl_vars['error']['gest'] != null): ?>
  <?php endif; ?>
</table>
<div align="center">

</div>
<p>&nbsp;</p>
<p align="center">
  <input type="submit" value="Generar" name="generar" class="enviar" onmouseover="this.className='boton'" onmouseout="this.className='enviar'" />
</p>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
</form>    

<table border="0" cellpadding="0" cellspacing="2">
        
  <tbody>
    <!-- loop los estilos  -->
  </tbody>
 
  <tbody>
  </tbody>
</table>

<p><?php if ($this->_tpl_vars['ver']): ?></p>

 
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

		
		<a href="../controladores/rep_ingreso.php?funcion=detalle&elegido=<?php echo $this->_tpl_vars['act'][$this->_sections['ind']['index']]['act_id']; ?>
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
 

<p><?php endif; ?>


</body>