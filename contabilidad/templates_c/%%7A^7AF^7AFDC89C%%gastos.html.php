<?php /* Smarty version 2.6.18, created on 2008-10-27 09:57:57
         compiled from gastos.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'commify', 'gastos.html', 357, false),)), $this); ?>

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

.sortcol {
	cursor: pointer;
	padding-right: 20px;
	background-repeat: no-repeat;
	background-position: right center;
}
.sortasc {
	background-color: #DDFFAC;
	background-image: url(up.gif);
}
.sortdesc {
	background-color: #B9DDFF;
	background-image: url(down.gif);
}
.nosort {
	cursor: default;
}


</style>
<script type="text/javascript" src="../templates/scriptaculous/lib/prototype.js"></script>
<script type="text/javascript" src="../templates/fastinit.js"></script>
<script type="text/javascript" src="../templates/tablesort.js"></script>	
		
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

<form name="orden" method="get" action="../controladores/gastos.php"  align="center">
<table width="70%" border="0" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <th colspan="13" > Gastos </th>
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
  <?php if ($this->_tpl_vars['error']['fecha_inter'] != null): ?>
  <tr>
    <td colspan="4"><div class="error-box" style="width:100%;"><?php echo $this->_tpl_vars['error']['fecha_inter']; ?>
</div></td>
  </tr>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['error']['gest'] != null): ?>
  <tr>
    <td></td>
	<td></td>
    <td colspan="4"><div class="error-box" style="width:100%;">
      <div align="right"><?php echo $this->_tpl_vars['error']['gest']; ?>
</div>
    </div></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td height="44" class="enunciados">Fecha Corte: </td>
    <td class="body-sector"><input type="text" name="corte"  value="<?php echo $this->_tpl_vars['corte']; ?>
"/></td>
    <td class="enunciados">Gestion:</td>
    <td class="body-sector"><select name="gest" id="select2" >
      <option value="selc">Seleccionar</option>
      
      
  		 <?php $_from = $this->_tpl_vars['gestion']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>	          
      
      <option value="<?php echo $this->_tpl_vars['item']['gestion_id']; ?>
"><?php echo $this->_tpl_vars['item']['gestion']; ?>
</option>
      
  	<?php endforeach; endif; unset($_from); ?>	
     
 
      
      
        
    
    </select>
        <br />
      <a  href="javascript:ventanaSecundaria('gestion.php?popup=true')"> registrar gestion </a> </td>
  </tr>
   <?php if ($this->_tpl_vars['error']['repor'] != null): ?>
  <?php endif; ?>
</table>

  <div align="center">
<fieldset style="width:70%;">
<legend style="color:#6699CC">Reporte Por</legend><br />
<table width="70%" border="0" align="left" cellpadding="2" cellspacing="2">
   <?php if ($this->_tpl_vars['error']['todo'] != null): ?>
  <tr>
    <td colspan="4"><div class="error-box" style="width:100%;"><?php echo $this->_tpl_vars['error']['todo']; ?>
</div></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td height="33">&nbsp;</td>
    <td class="body-sector"><input name="todo" type="radio" value="1" <?php if ($this->_tpl_vars['todo'] == 1): ?> checked="checked" <?php endif; ?> />
      Todo</td>
    <td class="body-sector">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="body-sector"><input name="todo" type="radio" value="2" <?php if ($this->_tpl_vars['todo'] == 2): ?> checked="checked" <?php endif; ?> />
      Especifico </td>
    <td class="body-sector">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="enunciados">Localizacion Primaria; </td>
    <td><div id="primaria">
        <select name="pri" id="pri2" onchange="actualizar ('combo1.php','pri2','secundaria','primaria')" >
          <option value="selc">Seleccionar</option>
          
          

		 <?php $_from = $this->_tpl_vars['primaria']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>	          
                
        
        
          <option value="<?php echo $this->_tpl_vars['item']['primaria_id']; ?>
"  ><?php echo $this->_tpl_vars['item']['descripcion']; ?>
</option>
          
        
                 

		<?php endforeach; endif; unset($_from); ?>	
     
 
      
      
        </select>
    </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="enunciados">Localizacion Secundaria:</td>
    <td><div id="secundaria" >
      <select name="secun" id="select"  >
        <option value="selc">Seleccionar</option>
        
          
            
		 
	   
		<?php $_from = $this->_tpl_vars['secundaria']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>	 
	   	    
            
      
          
          
        <option value="<?php echo $this->_tpl_vars['item']['secundaria_id']; ?>
" ><?php echo $this->_tpl_vars['item']['descripcion']; ?>
</option>
        
          
          
                      	    	   
	  
	<?php endforeach; endif; unset($_from); ?>	
  
				
      
        
        
      </select>
    </div></td>
    <td>&nbsp;</td>
  </tr>
  <?php if ($this->_tpl_vars['error']['err_gru'] != null): ?>
  <tr>
    <td colspan="4"><div class="error-box" style="width:100%;"><?php echo $this->_tpl_vars['error']['err_gru']; ?>
</div></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td>&nbsp;</td>
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
    <td>&nbsp;</td>
  </tr>
</table>
</fieldset>
</div>
<p>&nbsp;</p>
<p align="center">
  <input type="submit" value="Generar" name="gastos" class="enviar" onmouseover="this.className='boton'" onmouseout="this.className='enviar'" />
</p>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
</form>    

<table border="0" cellpadding="0" cellspacing="2">
        
  <tbody>
    <!-- loop los estilos  -->
  </tbody>
  <?php $this->assign('total1', 0); ?>
  <?php $this->assign('total2', 0); ?>
  <?php $this->assign('total3', 0); ?>
  <?php $this->assign('total4', 0); ?>
  <?php $this->assign('total5', 0); ?>
  <?php $this->assign('total6', 0); ?>
  <?php $this->assign('CSSclass', 0); ?>
  <?php $this->assign('clases', "&quot;lista-normal&quot;"); ?>
  <?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['lista_activo']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
  <?php $this->assign("&quot;CSSclass&quot;", "&quot;".($this->_tpl_vars['CSSclass']+1)."&quot;"); ?>
  <?php if (( $this->_tpl_vars['CSSclass'] % 2 ) == 0): ?>
  <?php $this->assign('clases', "&quot;lista-seleccionada&quot;"); ?>
  <?php else: ?>
  <?php $this->assign('clases', "&quot;lista-normal&quot;"); ?>
  <?php endif; ?>
  
  
  
  
  
  
  
  
  <?php endfor; endif; ?>
  <tbody>
  </tbody>
</table>
	
			
<p><?php if ($this->_tpl_vars['ver']): ?></p>
<div align="center" style="font-size:18px">CUADRO DE GASTOS </div>
<?php if ($this->_tpl_vars['descrisecu']['locsecundaria'] != ''): ?>
<div  align="left" style="font-size:13px; font-weight:bold">LOCALIZACION PRIMARIA: <?php echo $this->_tpl_vars['descripri']['localizacion']; ?>
-<?php echo $this->_tpl_vars['descripri']['descripcion']; ?>
</div>
<div align="left" style="font-size:13px; font-weight:bold">LOCALIZACION SECUNDARIA: <?php echo $this->_tpl_vars['descrisecu']['locsecundaria']; ?>
-<?php echo $this->_tpl_vars['descrisecu']['descripcion']; ?>
  </div><?php endif; ?>
<div id="content">
<table class="sortable" border="0" cellspacing="2" cellpadding="0">
  <thead>
    <tr> </tr>
    <tr class="enunciados">
      <th class="enunciados" style="text-align:center">Numero Activo </th>
      <th class="enunciados" style="text-align:center">Fecha Compra</th>
      <th class="enunciados" style="text-align:center" width="30%">Descripcion</th>
      <th class="enunciados" style="text-align:center">T/C</th>
      <th class="enunciados" style="text-align:center" >VidaUtil<br />
        (meses)</th>
      <th class="enunciados" style="text-align:center">Valor Compra </th>
       <!-- <th class="enunciados" style="text-align:center">obs</th>-->
      <th class="enunciados" style="text-align:center">Depreciacion Acumulada en $ al <?php echo $this->_tpl_vars['fecha_antes']; ?>
 </th>
      <th class="enunciados" style="text-align:center">Incremento de depreciacion $ </th>
      <th class="enunciados" style="text-align:center">Depreciacion  del periodo </th>
      <th class="enunciados" style="text-align:center">Depreciacion acumulada <span class="enunciados" style="text-align:center">final </span>en $ al <?php echo $this->_tpl_vars['fecha_fin']; ?>
 </th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Depreciacion Acumulada en <br>
        UFV al <?php echo $this->_tpl_vars['fecha_antes']; ?>
 </th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Incremento de depreciacion UFV</th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Depreciacion  del periodo UFV </th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Depreciacion acumulada final en <br> 
      UFVal <?php echo $this->_tpl_vars['fecha_fin']; ?>
 </th>
      <th class="enunciados" style="text-align:center">Vida Restante </th>
    </tr>
 <tr>
  <th colspan="22"><div align="left"><?php echo $this->_tpl_vars['descripcion']['descripcion']; ?>
</div>
  <div align="left">	  </div></th>
 </tr>
  </thead>
     <tbody>
  <?php $this->assign('total1', 0); ?>
  <?php $this->assign('total2', 0); ?>
  <?php $this->assign('total3', 0); ?>
  <?php $this->assign('total4', 0); ?>
  <?php $this->assign('total5', 0); ?>
  <?php $this->assign('total6', 0); ?>
  
  <?php $this->assign('totalD1', 0); ?>
  <?php $this->assign('totalD2', 0); ?>
  <?php $this->assign('totalD3', 0); ?>
  <?php $this->assign('totalD4', 0); ?>
  <?php $this->assign('totalD5', 0); ?>
  <?php $this->assign('totalD6', 0); ?>
  <?php $this->assign('totalD7', 0); ?>
  
  <?php $this->assign('CSSclass', 0); ?>
  <?php $this->assign('clases', "lista-normal"); ?>
  <?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['lista_activo']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
';">
      <!-- hast aqui son estilos-->
	 
      <td nowrap="nowrap"><div align="center"><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['numero']; ?>
</div></td>
    <td nowrap="nowrap"><div align="center"><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['fecha']; ?>
</div></td>
    <td><div align="center"><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['descripcion']; ?>
</div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['tipo_cambio'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td><div align="center"><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['vida_util']; ?>
</div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['valor_compra'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total1', ($this->_tpl_vars['total1']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['valor_compra'])); ?></div></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_acumulada'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD1', ($this->_tpl_vars['totalD1']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_acumulada'])); ?></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['incremento_depre'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD2', ($this->_tpl_vars['totalD2']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['incremento_depre'])); ?></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_periodo'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD3', ($this->_tpl_vars['totalD3']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_periodo'])); ?></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_fin'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD4', ($this->_tpl_vars['totalD4']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_fin'])); ?></td>
    <td bgcolor="#CCCCCC"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_acumulada_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD5', ($this->_tpl_vars['totalD5']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_acumulada_ufv'])); ?></td>
    <td bgcolor="#CCCCCC"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['incremento_depreufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD6', ($this->_tpl_vars['totalD6']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['incremento_depreufv'])); ?></td>
    <td bgcolor="#CCCCCC"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_periodo_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD7', ($this->_tpl_vars['totalD7']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_periodo_ufv'])); ?></td>
    <td bgcolor="#CCCCCC"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_fin_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD8', ($this->_tpl_vars['totalD8']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_fin_ufv'])); ?></td>
    <td><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['vida_restante']; ?>
</td>
  </tr>

  
  
  <?php endfor; endif; ?>
  </tbody>
  <TFOOT>
  <tr class="<?php echo $this->_tpl_vars['clases']; ?>
" onmouseover="this.style.cursor = 'pointer';this.className = 'lista_terrible';" onmouseout="this.className = '<?php echo $this->_tpl_vars['clases']; ?>
';">
    <td nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total1'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    
     <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD1'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD2'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD3'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD4'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
   	<td bgcolor="#CCCCCC" style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD5'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td bgcolor="#CCCCCC" style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD6'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td bgcolor="#CCCCCC" style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD7'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td bgcolor="#CCCCCC" style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD8'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
  </tr>
  </TFOOT>
  </table>
</div>
<p><?php endif; ?>
  
<?php if ($this->_tpl_vars['ver1']): ?></p>
<div align="center" style="font-size:18px">CUADRO DE GASTOS </div>
<?php if ($this->_tpl_vars['descrisecu']['locsecundaria'] != ''): ?>
<div  align="left" style="font-size:13px; font-weight:bold">LOCALIZACION PRIMARIA: <?php echo $this->_tpl_vars['descripri']['localizacion']; ?>
-<?php echo $this->_tpl_vars['descripri']['descripcion']; ?>
</div>
<div align="left" style="font-size:13px; font-weight:bold">LOCALIZACION SECUNDARIA: <?php echo $this->_tpl_vars['descrisecu']['locsecundaria']; ?>
-<?php echo $this->_tpl_vars['descrisecu']['descripcion']; ?>
  </div><?php endif; ?>

<table class="sortable" border="0" cellspacing="2" cellpadding="0">
  <thead>
    
    <tr class="enunciados">
      <th class="enunciados" style="text-align:center">Numero Activo </th>
	  <th class="enunciados" style="text-align:center">Fecha Compra </th>
      <th class="enunciados" style="text-align:center" width="30%">Descripcion</th>
      <th class="enunciados" style="text-align:center">T/C</th>
      <th class="enunciados" style="text-align:center" >VidaUtil<br />
        (meses)</th>
      <th class="enunciados" style="text-align:center">Valor Compra </th>
      
		 <th class="enunciados" style="text-align:center">Depreciacion Acumulada en $ al <?php echo $this->_tpl_vars['fecha_antes']; ?>
 </th>
		 <th class="enunciados" style="text-align:center">Incremento de Depreciacion</th>
		 <th class="enunciados" style="text-align:center">Depreciacion Periodo</th>
		 <th class="enunciados" style="text-align:center">Depreciacion acumulada <span class="enunciados" style="text-align:center">final</span> en $  al <?php echo $this->_tpl_vars['fecha_fin']; ?>
 </th>
		 <th class="enunciados" style="text-align:center">Vida Restante</th>
         <!-- <th class="enunciados" style="text-align:center">obs</th>-->
    </tr>
  <th colspan="14"><div align="left"><?php echo $this->_tpl_vars['descripcion']['descripcion']; ?>
</div>
  <div align="left">	  </div></th>

  </thead>
  <tbody>
    <!-- loop los estilos  -->
  </tbody>
  <?php $this->assign('total1', 0); ?>
 <?php $this->assign('total2', 0); ?>
 <?php $this->assign('total3', 0); ?>
 <?php $this->assign('total4', 0); ?>

 <?php $this->assign('totalD1', 0); ?>
 <?php $this->assign('totalD2', 0); ?>
 <?php $this->assign('totalD3', 0); ?>
 <?php $this->assign('totalD4', 0); ?>
 
  <?php $this->assign('CSSclass', 0); ?> 
	 <?php $this->assign('clases', "lista-normal"); ?>
     <?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['lista_activo']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		 <?php if ($this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['numero'] != ''): ?> 
  <tr class="<?php echo $this->_tpl_vars['clases']; ?>
" onmouseover="this.style.cursor = 'pointer';this.className = 'lista_terrible';" onmouseout="this.className = '<?php echo $this->_tpl_vars['clases']; ?>
';">
      <!-- hast aqui son estilos-->
	  
      <td nowrap="nowrap"><div align="center"><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['numero']; ?>
</div></td>
	  <td><div align="center"><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['fecha']; ?>
</div></td>
    <td><div align="center"><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['descripcion']; ?>
</div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['tipo_cambio'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td><div align="center"><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['vida_util']; ?>
</div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['valor_compra'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total1', ($this->_tpl_vars['total1']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['valor_compra'])); ?></div></td>
     <td><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_acumulada'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD1', ($this->_tpl_vars['totalD1']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_acumulada'])); ?></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['incremento_depre'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD2', ($this->_tpl_vars['totalD2']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['incremento_depre'])); ?></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_periodo'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD3', ($this->_tpl_vars['totalD3']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_periodo'])); ?></td>
	 <td><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_fin'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD4', ($this->_tpl_vars['totalD4']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_fin'])); ?></td>
	 <td><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['vida_restante']; ?>
</td>
  </tr>
  <?php endif; ?>
  <?php endfor; endif; ?>
  <tr class="<?php echo $this->_tpl_vars['clases']; ?>
" onmouseover="this.style.cursor = 'pointer';this.className = 'lista_terrible';" onmouseout="this.className = '<?php echo $this->_tpl_vars['clases']; ?>
';">
    <td height="19" nowrap="nowrap">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	<td>&nbsp;</td>
   <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total1'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
     <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD1'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD2'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD3'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD4'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
  </tr>
  
  <tbody>
  </tbody>
</table>
<?php endif; ?>
<?php if ($this->_tpl_vars['ver2']): ?>
<div align="center" style="font-size:18px">CUADRO DE GASTOS </div>
<?php if ($this->_tpl_vars['descrisecu']['locsecundaria'] != ''): ?>
<div  align="left" style="font-size:13px; font-weight:bold">LOCALIZACION PRIMARIA: <?php echo $this->_tpl_vars['descripri']['localizacion']; ?>
-<?php echo $this->_tpl_vars['descripri']['descripcion']; ?>
</div>
<div align="left" style="font-size:13px; font-weight:bold">LOCALIZACION SECUNDARIA: <?php echo $this->_tpl_vars['descrisecu']['locsecundaria']; ?>
-<?php echo $this->_tpl_vars['descrisecu']['descripcion']; ?>
  </div>
<?php endif; ?>

<table border="0" cellspacing="2" cellpadding="0">
  <thead>
    <tr class="enunciados">
      <th class="enunciados" style="text-align:center">Numero Activo </th>
      <th class="enunciados" style="text-align:center">Fecha Compra </th>
      <th class="enunciados" style="text-align:center" width="30%">Descripcion</th>
      <th class="enunciados" style="text-align:center">T/C</th>
      <th class="enunciados" style="text-align:center" >VidaUtil<br />
        (meses)</th>
      <th class="enunciados" style="text-align:center">Valor Compra </th>
	   <th class="enunciados" style="text-align:center">Valor de la otra gestion </th>
        <th class="enunciados" style="text-align:center">Depreciacion Acumulada en <br>
      UFV al <?php echo $this->_tpl_vars['fecha_antes']; ?>
 </th>
      <th class="enunciados" style="text-align:center">Incremento de depreciacion UFV</th>
      <th class="enunciados" style="text-align:center">Depreciacion  del periodo UFV </th>
      <th class="enunciados" style="text-align:center">Depreciacion  final en <br>UFV al <?php echo $this->_tpl_vars['fecha_fin']; ?>
 </th>
      <th class="enunciados" style="text-align:center">Vida Restante </th>
      <!-- <th class="enunciados" style="text-align:center">obs</th>-->
    </tr>
  </thead>
  <th colspan="16"><div align="left"><?php echo $this->_tpl_vars['descripcion']['descripcion']; ?>
</div>
          <div align="left"> </div></th>
  <tbody>
    <!-- loop los estilos  -->
  </tbody>
  <?php $this->assign('total1', 0); ?>
  <?php $this->assign('total5', 0); ?>
  <?php $this->assign('total6', 0); ?>
  <?php $this->assign('total7', 0); ?>
  
  <?php $this->assign('totalD1', 0); ?>
  <?php $this->assign('totalD2', 0); ?>
  <?php $this->assign('totalD3', 0); ?>
  <?php $this->assign('totalD4', 0); ?>
  
  
  
  <?php $this->assign('CSSclass', 0); ?>
  <?php $this->assign('clases', "lista-normal"); ?>
  <?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['lista_activo']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
';">
                <!-- hast aqui son estilos-->
                <td nowrap="nowrap"><div align="center"><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['numero']; ?>
</div></td>
    <td><div align="center"><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['fecha']; ?>
</div></td>
    <td><div align="center"><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['descripcion']; ?>
</div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['tipo_cambio'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td><div align="center"><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['vida_util']; ?>
</div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['valor_compra'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total1', ($this->_tpl_vars['total1']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['valor_compra'])); ?></div></td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['valor'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</td>
     <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_acumulada_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD1', ($this->_tpl_vars['totalD1']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_acumulada_ufv'])); ?></div></td>
	<td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['incremento_depre_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD2', ($this->_tpl_vars['totalD2']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['incremento_depre_ufv'])); ?></div></td>
	<td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_periodo_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD3', ($this->_tpl_vars['totalD3']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_periodo_ufv'])); ?></div></td>
	<td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_fin_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD4', ($this->_tpl_vars['totalD4']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_fin_ufv'])); ?></div></td>
	<td><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['vida_restante']; ?>
</td>
  </tr>
  <?php endfor; endif; ?>
  <tr class="<?php echo $this->_tpl_vars['clases']; ?>
" onmouseover="this.style.cursor = 'pointer';this.className = 'lista_terrible';" onmouseout="this.className = '<?php echo $this->_tpl_vars['clases']; ?>
';">
    <td nowrap="nowrap">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total1'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
   <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD1'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD2'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD3'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
	<td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD4'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
  </tr>
  <tbody>
  </tbody>
</table>
<?php endif; ?>
<?php if ($this->_tpl_vars['ver3']): ?>
<div align="center" style="font-size:18px">CUADRO DE GASTOS </div>
<?php if ($this->_tpl_vars['descrisecu']['locsecundaria'] != ''): ?>
<div  align="left" style="font-size:13px; font-weight:bold">LOCALIZACION PRIMARIA: <?php echo $this->_tpl_vars['descripri']['localizacion']; ?>
-<?php echo $this->_tpl_vars['descripri']['descripcion']; ?>
</div>
<div align="left" style="font-size:13px; font-weight:bold">LOCALIZACION SECUNDARIA: <?php echo $this->_tpl_vars['descrisecu']['locsecundaria']; ?>
-<?php echo $this->_tpl_vars['descrisecu']['descripcion']; ?>
  </div><?php endif; ?>

<table border="0" cellspacing="2" cellpadding="0">
  <thead>
   
    <tr class="enunciados">
      <th class="enunciados" style="text-align:center">Numero Activo </th>
      <th class="enunciados" style="text-align:center">Fecha Compra </th>
      <th class="enunciados" style="text-align:center" width="30%">Descripcion</th>
      <th class="enunciados" style="text-align:center">T/C</th>
      <th class="enunciados" style="text-align:center" >VidaUtil<br />
        (meses)</th>
      <th class="enunciados" style="text-align:center">Valor Compra </th>
      	   <th class="enunciados" style="text-align:center">Depreciacion Acumulada en $ al<br><?php echo $this->_tpl_vars['fecha_antes']; ?>
</th>
           <th class="enunciados" style="text-align:center">Incremento de depreciacion  </th>
           <th class="enunciados" style="text-align:center">Depreciacion  del periodo </th>
      <th class="enunciados" style="text-align:center">Depreciacion acumulada $ final al<br> <?php echo $this->_tpl_vars['fecha_fin']; ?>
 </th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Depreciacion Acumulada en <br>UFV al <br><?php echo $this->_tpl_vars['fecha_antes']; ?>
</th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Incremento de depreciacion UFV</th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Depreciacion  del periodo UFV </th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Depreciacion acumulada en <br>UFV al <br><?php echo $this->_tpl_vars['fecha_fin']; ?>
 </th>
      <th class="enunciados" style="text-align:center">Vida Restante </th>
    </tr>
  <th colspan="22"><div align="left"><?php echo $this->_tpl_vars['descripcion']['descripcion']; ?>
</div>
  <div align="left">	  </div></th>

  <tbody>
    <!-- loop los estilos  -->
  </tbody>
  <?php $this->assign('total1', 0); ?>
  <?php $this->assign('total2', 0); ?>
  <?php $this->assign('total3', 0); ?>
  <?php $this->assign('total4', 0); ?>
  <?php $this->assign('total5', 0); ?>
  <?php $this->assign('total6', 0); ?>
  <?php $this->assign('total7', 0); ?>
  
  <?php $this->assign('totalD1', 0); ?>
  <?php $this->assign('totalD2', 0); ?>
  <?php $this->assign('totalD3', 0); ?>
  <?php $this->assign('totalD4', 0); ?>
  <?php $this->assign('totalD5', 0); ?>
  <?php $this->assign('totalD6', 0); ?>
  <?php $this->assign('totalD7', 0); ?>
  <?php $this->assign('totalD8', 0); ?>
  
  <?php $this->assign('CSSclass', 0); ?>
  <?php $this->assign('CSSclass', 0); ?>
	 <?php $this->assign('clases', "lista-normal"); ?>
     <?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['lista_activo']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
';">
                <!-- hast aqui son estilos-->
                <td nowrap="nowrap"><div align="center"><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['numero']; ?>
</div></td>
    <td nowrap="nowrap"><div align="center"><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['fecha']; ?>
</div></td>
    <td><div align="center"><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['descripcion']; ?>
</div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['tipo_cambio'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td><div align="center"><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['vida_util']; ?>
</div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['valor_compra'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total1', ($this->_tpl_vars['total1']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['valor_compra'])); ?></div></td>
     <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_acumulada'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD1', ($this->_tpl_vars['totalD1']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_acumulada'])); ?></div></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['incremento_depre'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD2', ($this->_tpl_vars['totalD2']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['incremento_depre'])); ?></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_periodo'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD3', ($this->_tpl_vars['totalD3']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_periodo'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_fin'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD4', ($this->_tpl_vars['totalD4']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_fin'])); ?></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_acumulada_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD5', ($this->_tpl_vars['totalD5']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_acumulada_ufv'])); ?></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['incremento_depreufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD6', ($this->_tpl_vars['totalD6']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['incremento_depreufv'])); ?></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_periodo_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD7', ($this->_tpl_vars['totalD7']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_periodo_ufv'])); ?></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_fin_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD8', ($this->_tpl_vars['totalD8']+$this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['depre_fin_ufv'])); ?></div></td>
    <td><?php echo $this->_tpl_vars['lista_activo'][$this->_sections['ind']['index']]['vida_restante']; ?>
</td>
  </tr>
  <?php endfor; endif; ?>
  <tr class="<?php echo $this->_tpl_vars['clases']; ?>
" onmouseover="this.style.cursor = 'pointer';this.className = 'lista_terrible';" onmouseout="this.className = '<?php echo $this->_tpl_vars['clases']; ?>
';">
    <td nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total1'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
   <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD1'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD2'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD3'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD4'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
	<td bgcolor="#CCCCCC" style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD5'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td bgcolor="#CCCCCC" style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD6'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td bgcolor="#CCCCCC" style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD7'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
	<td bgcolor="#CCCCCC" style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalD8'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
  </tr>
  <tbody>
  </tbody>
</table>
<?php endif; ?>
</body>