<?php /* Smarty version 2.6.18, created on 2008-10-24 10:04:20
         compiled from rep_baja.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'commify', 'rep_baja.html', 238, false),)), $this); ?>
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

<form name="asignacion_rep" method="get" action="../controladores/rep_bajas.php"  align="center">
<table width="70%" border="0" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <th colspan="13" > Reporte Dados de Baja </th>
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
    <td>&nbsp;</td>
    <td class="body-sector"><input name="todo" type="radio" value="1" <?php if ($this->_tpl_vars['todo'] == 1): ?> checked="checked" <?php endif; ?> />
      Todo</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php if ($this->_tpl_vars['error']['err_gru'] != null): ?>
  <?php endif; ?>
</table>
</fieldset>
</div>
<p>&nbsp;</p>
<p align="center">
  <input type="submit" value="Generar" name="actualizacion" class="enviar" onmouseover="this.className='boton'" onmouseout="this.className='enviar'" />
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
    <tr> </tr>
    <tr class="enunciados">
      <th class="enunciados" style="text-align:center">Numero Activo </th>
      <th class="enunciados" style="text-align:center">Fecha Compra </th>
      <th class="enunciados" style="text-align:center" width="30%">Descripcion</th>
      <th class="enunciados" style="text-align:center">T/C</th>
      <th class="enunciados" style="text-align:center" >VidaUtil<br />
        (meses)</th>
      <th class="enunciados" style="text-align:center">Valor Compra </th>
      <th class="enunciados" style="text-align:center">Valor al <br />
        <?php echo $this->_tpl_vars['fecha_antes']; ?>
<br />
        <?php echo $this->_tpl_vars['valor_dolar']; ?>
$</th>
      <th class="enunciados" style="text-align:center">Incremento<br />
        por<br />
        Actualizacion con T/C </th>
      <th class="enunciados" style="text-align:center">Valor al<br />
          <span class="enunciados" style="text-align:center"> <?php echo $this->_tpl_vars['fecha_fin']; ?>
 </span><br />
        <?php echo $this->_tpl_vars['valor_dolar2']; ?>
$</th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Valor UFV al <?php echo $this->_tpl_vars['fecha_inter']; ?>
 <br />
        <?php echo $this->_tpl_vars['valor_ufv']; ?>
ufv</th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Valor<br />
        mes anterior <br />
        <?php echo $this->_tpl_vars['valor_ufv']; ?>
</th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Incremento<br />
        por<br />
        Actualizacion con UFV</th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Valor UFV al <?php echo $this->_tpl_vars['fecha_fin']; ?>
<br /></th>
      <th class="enunciados" style="text-align:center">Depreciacion Acumulada en $ al<br />
        <?php echo $this->_tpl_vars['fecha_antes']; ?>
 </th>
      <th class="enunciados" style="text-align:center">Incremento de depreciacion </th>
      <th class="enunciados" style="text-align:center">Depreciacion  del periodo </th>
      <th class="enunciados" style="text-align:center">Depreciacion acumulada en $<br />
        <?php echo $this->_tpl_vars['fecha_fin']; ?>
 </th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Depreciacion Acumulada en <br />
        UFV<br />
        al <?php echo $this->_tpl_vars['fecha_antes']; ?>
</th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Incremento de depreciacion UFV</th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Depreciacion  del periodo UFV </th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Depreciacion acumulada final <br />
        en UFV<br />
        al <?php echo $this->_tpl_vars['fecha_fin']; ?>
 </th>
      <th class="enunciados" style="text-align:center">Vida Restante </th>
    </tr>
  </thead>
  <?php $_from = $this->_tpl_vars['lista_activo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['grupo'] => $this->_tpl_vars['valores_activo']):
?>
  <th colspan="22"><div align="left"><?php echo $this->_tpl_vars['grupo']; ?>
</div>
          <div align="left"> </div></th>
  <tbody>
    <!-- loop los estilos  -->
  </tbody>
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
  <?php $this->assign('totalD8', 0); ?>
  
  
  <?php $this->assign('CSSclass', 0); ?>
  <?php $this->assign('clases', "lista-normal"); ?>
  <?php $_from = $this->_tpl_vars['valores_activo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['activo'] => $this->_tpl_vars['valor']):
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
                <td nowrap="nowrap"><div align="center"><?php echo $this->_tpl_vars['valor']['numero']; ?>
</div></td>
    <td nowrap="nowrap"><div align="center"><?php echo $this->_tpl_vars['valor']['fecha']; ?>
</div></td>
    <td ><div align="center"><?php echo $this->_tpl_vars['valor']['descripcion']; ?>
</div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['tipo_cambio'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td><div align="center"><?php echo $this->_tpl_vars['valor']['vida_util']; ?>
</div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['valor_compra'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total1', ($this->_tpl_vars['total1']+$this->_tpl_vars['valor']['valor_compra'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['valor'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total2', ($this->_tpl_vars['total2']+$this->_tpl_vars['valor']['valor'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['incremento'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total3', ($this->_tpl_vars['total3']+$this->_tpl_vars['valor']['incremento'])); ?></div></td>
    <td nowrap="nowrap"><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['valor1'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total4', ($this->_tpl_vars['total4']+$this->_tpl_vars['valor']['valor1'])); ?></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 5) : smarty_modifier_commify($_tmp, 5)); ?>
</div></td>
    <td bgcolor="#CCCCCC"><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['valor_ufvantes'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td bgcolor="#CCCCCC"><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['incremento_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total5', ($this->_tpl_vars['total5']+$this->_tpl_vars['valor']['incremento_ufv'])); ?></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['valor_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total6', ($this->_tpl_vars['total6']+$this->_tpl_vars['valor']['valor_ufv'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_acumulada'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD1', ($this->_tpl_vars['totalD1']+$this->_tpl_vars['valor']['depre_acumulada'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['incremento_depre'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD2', ($this->_tpl_vars['totalD2']+$this->_tpl_vars['valor']['incremento_depre'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_periodo'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD3', ($this->_tpl_vars['totalD3']+$this->_tpl_vars['valor']['depre_periodo'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_fin'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD4', ($this->_tpl_vars['totalD4']+$this->_tpl_vars['valor']['depre_fin'])); ?></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_acumulada_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD5', ($this->_tpl_vars['totalD5']+$this->_tpl_vars['valor']['depre_acumulada_ufv'])); ?></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['incremento_depreufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD6', ($this->_tpl_vars['totalD6']+$this->_tpl_vars['valor']['incremento_depreufv'])); ?></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_periodo_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD7', ($this->_tpl_vars['totalD7']+$this->_tpl_vars['valor']['depre_periodo_ufv'])); ?></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_fin_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 5) : smarty_modifier_commify($_tmp, 5)); ?>
<?php $this->assign('totalD8', ($this->_tpl_vars['totalD8']+$this->_tpl_vars['valor']['depre_fin_ufv'])); ?></div></td>
    <td><?php echo $this->_tpl_vars['valor']['vida_restante']; ?>
</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
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
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total2'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total3'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total4'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td bgcolor="#CCCCCC">&nbsp;</td>
    <td bgcolor="#CCCCCC">&nbsp;</td>
    <td bgcolor="#CCCCCC" style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total5'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td bgcolor="#CCCCCC" style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total6'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
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
  <?php endforeach; endif; unset($_from); ?>
  <tbody>
  </tbody>
</table>
<p><?php endif; ?>
  
<?php if ($this->_tpl_vars['ver1']): ?></p><div align="center" style="font-size:18px">CUADRO DE ACTUALIZACIONES</div>
<div  align="left" style="font-size:13px; font-weight:bold">LOCALIZACION PRIMARIA: <?php echo $this->_tpl_vars['descripri']['localizacion']; ?>
-<?php echo $this->_tpl_vars['descripri']['descripcion']; ?>
</div>
<div align="left" style="font-size:13px; font-weight:bold">LOCALIZACION SECUNDARIA: <?php echo $this->_tpl_vars['descrisecu']['locsecundaria']; ?>
-<?php echo $this->_tpl_vars['descrisecu']['descripcion']; ?>
  </div>

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
      <th class="enunciados" style="text-align:center">Valor al <br />
        <?php echo $this->_tpl_vars['fecha_antes']; ?>
 con $ <br />
        <?php echo $this->_tpl_vars['valor_dolar']; ?>
</th>
      <th class="enunciados" style="text-align:center">Incremento<br />
        por<br />
        Actualizacion con T/C </th>
      <th class="enunciados" style="text-align:center">Valor al<br />
        <span class="enunciados" style="text-align:center"><?php echo $this->_tpl_vars['fecha_fin']; ?>
</span> con $ <br />
        <?php echo $this->_tpl_vars['valor_dolar2']; ?>
</th>
		 <th class="enunciados" style="text-align:center">Depreciacion Acumulada en $ <br>al <?php echo $this->_tpl_vars['fecha_antes']; ?>
</th>
		 <th class="enunciados" style="text-align:center">Incremento de Depreciacion</th>
		 <th class="enunciados" style="text-align:center">Depreciacion Periodo</th>
		 <th class="enunciados" style="text-align:center">Depreciacion final <br>al <?php echo $this->_tpl_vars['fecha_fin']; ?>
</th>
		 <th class="enunciados" style="text-align:center">Vida Restante</th>
      
		
    </tr>
	<?php $_from = $this->_tpl_vars['lista_activo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['grupo'] => $this->_tpl_vars['valores_activo']):
?> 	
	<th colspan="14"><div align="left"><?php echo $this->_tpl_vars['grupo']; ?>
 </div>
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
     <?php $_from = $this->_tpl_vars['valores_activo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['activo'] => $this->_tpl_vars['valor']):
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
      <td nowrap="nowrap"><div align="center"><?php echo $this->_tpl_vars['valor']['numero']; ?>
</div></td>
	  <td><div align="center"><?php echo $this->_tpl_vars['valor']['fecha']; ?>
</div></td>
    <td><div align="center"><?php echo $this->_tpl_vars['valor']['descripcion']; ?>
</div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['tipo_cambio'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td><div align="center"><?php echo $this->_tpl_vars['valor']['vida_util']; ?>
</div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['valor_compra'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total1', ($this->_tpl_vars['total1']+$this->_tpl_vars['valor']['valor_compra'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['valor'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total2', ($this->_tpl_vars['total2']+$this->_tpl_vars['valor']['valor'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['incremento'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total3', ($this->_tpl_vars['total3']+$this->_tpl_vars['valor']['incremento'])); ?></div></td>
    <td nowrap="nowrap"><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['valor1'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total4', ($this->_tpl_vars['total4']+$this->_tpl_vars['valor']['valor1'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_acumulada'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD1', ($this->_tpl_vars['totalD1']+$this->_tpl_vars['valor']['depre_acumulada'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['incremento_depre'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD2', ($this->_tpl_vars['totalD2']+$this->_tpl_vars['valor']['incremento_depre'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_periodo'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD3', ($this->_tpl_vars['totalD3']+$this->_tpl_vars['valor']['depre_periodo'])); ?></div></td>
	<td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_fin'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD4', ($this->_tpl_vars['totalD4']+$this->_tpl_vars['valor']['depre_fin'])); ?></div></td>
	<td><?php echo $this->_tpl_vars['valor']['vida_restante']; ?>
</td>

  
  </tr>
  <?php endforeach; endif; unset($_from); ?>
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
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total2'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total3'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total4'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
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
  <?php endforeach; endif; unset($_from); ?>
  <tbody>
  </tbody>
</table>
<?php endif; ?>
<?php if ($this->_tpl_vars['ver2']): ?>
<div align="center" style="font-size:18px">CUADRO DE ACTUALIZACIONES</div>
<div  align="left" style="font-size:13px; font-weight:bold">LOCALIZACION PRIMARIA: <?php echo $this->_tpl_vars['descripri']['localizacion']; ?>
-<?php echo $this->_tpl_vars['descripri']['descripcion']; ?>
</div>
<div align="left" style="font-size:13px; font-weight:bold">LOCALIZACION SECUNDARIA: <?php echo $this->_tpl_vars['descrisecu']['locsecundaria']; ?>
-<?php echo $this->_tpl_vars['descrisecu']['descripcion']; ?>
  </div>
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
      <th class="enunciados" style="text-align:center">Valor UFV al <span class="enunciados" style="text-align:center"><?php echo $this->_tpl_vars['fecha_antes']; ?>
</span> con ufv <br />
        <?php echo $this->_tpl_vars['valor_ufv']; ?>
</th>
		 <th class="enunciados" style="text-align:center">Valor del mes anterior <?php echo $this->_tpl_vars['fecha_antes']; ?>
 con ufv <br />
      <span class="enunciados" style="text-align:center"><?php echo $this->_tpl_vars['valor_ufv']; ?>
</span></th>
         <th class="enunciados" style="text-align:center">Incremento<br />
        por<br />
        Actualizacion con UFV</th>
      <th class="enunciados" style="text-align:center">Valor UFV al <?php echo $this->_tpl_vars['fecha_fin']; ?>
 con ufv <br />
      <span class="enunciados" style="text-align:center"><?php echo $this->_tpl_vars['valor_ufv2']; ?>
ufv</span></th>
	  
	  <th class="enunciados" style="text-align:center">Depreciacion Acumulada en<br>
	  UFV
	  <br>
	    al<?php echo $this->_tpl_vars['fecha_antes']; ?>
 </th>
      <th class="enunciados" style="text-align:center">Incremento de depreciacion UFV</th>
      <th class="enunciados" style="text-align:center">Depreciacion  del periodo UFV </th>
      <th class="enunciados" style="text-align:center">Depreciacion acumulada fianl al <?php echo $this->_tpl_vars['fecha_fin']; ?>
</th>
      <th class="enunciados" style="text-align:center">Vida Restante </th>
      
    </tr>
	<?php $_from = $this->_tpl_vars['lista_activo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['grupo'] => $this->_tpl_vars['valores_activo']):
?> 	
	<th colspan="16"><div align="left"><?php echo $this->_tpl_vars['grupo']; ?>
</div>
        <div align="left">	  </div></th>
  </thead>
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
    <?php $_from = $this->_tpl_vars['valores_activo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['activo'] => $this->_tpl_vars['valor']):
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
    <td nowrap="nowrap"><div align="center"><?php echo $this->_tpl_vars['valor']['numero']; ?>
</div></td>
	<td><div align="center"><?php echo $this->_tpl_vars['valor']['fecha']; ?>
</div></td>
    <td><div align="center"><?php echo $this->_tpl_vars['valor']['descripcion']; ?>
</div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['tipo_cambio'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td><div align="center"><?php echo $this->_tpl_vars['valor']['vida_util']; ?>
</div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['valor_compra'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total1', ($this->_tpl_vars['total1']+$this->_tpl_vars['valor']['valor_compra'])); ?></div></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['valor'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 5) : smarty_modifier_commify($_tmp, 5)); ?>
</div></td>
	<td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['valor_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total7', ($this->_tpl_vars['total7']+$this->_tpl_vars['valor']['valor_ufv'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['incremento_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total5', ($this->_tpl_vars['total5']+$this->_tpl_vars['valor']['incremento_ufv'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['valor_ufv2'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total6', ($this->_tpl_vars['total6']+$this->_tpl_vars['valor']['valor_ufv2'])); ?></div></td>
	
   <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_acumulada_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD1', ($this->_tpl_vars['totalD1']+$this->_tpl_vars['valor']['depre_acumulada_ufv'])); ?></div></td>
	<td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['incremento_depre_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD2', ($this->_tpl_vars['totalD2']+$this->_tpl_vars['valor']['incremento_depre_ufv'])); ?></div></td>
	<td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_periodo_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD3', ($this->_tpl_vars['totalD3']+$this->_tpl_vars['valor']['depre_periodo_ufv'])); ?></div></td>
	<td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_fin_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD4', ($this->_tpl_vars['totalD4']+$this->_tpl_vars['valor']['depre_fin_ufv'])); ?></div></td>
	<td><?php echo $this->_tpl_vars['valor']['vida_restante']; ?>
</td>

  </tr>
  <?php endforeach; endif; unset($_from); ?>
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
    <td></td>
	<td></td>
	<td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total7'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
     <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total5'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
     <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total6'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
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
  <?php endforeach; endif; unset($_from); ?>
  <tbody>
  </tbody>
</table>
<p><?php endif; ?>
  <?php if ($this->_tpl_vars['ver3']): ?></p>
<div align="center" style="font-size:18px">CUADRO DE ACTUALIZACIONES</div>
<div  align="left" style="font-size:13px; font-weight:bold">LOCALIZACION PRIMARIA: <?php echo $this->_tpl_vars['descripri']['localizacion']; ?>
-<?php echo $this->_tpl_vars['descripri']['descripcion']; ?>
</div>
<div align="left" style="font-size:13px; font-weight:bold">LOCALIZACION SECUNDARIA: <?php echo $this->_tpl_vars['descrisecu']['locsecundaria']; ?>
-<?php echo $this->_tpl_vars['descrisecu']['descripcion']; ?>
  </div>
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
      <th class="enunciados" style="text-align:center">Valor al <br />
        <?php echo $this->_tpl_vars['fecha_antes']; ?>
 con $ <br />
        <?php echo $this->_tpl_vars['valor_dolar']; ?>
</th>
      <th class="enunciados" style="text-align:center">Incremento<br />
        por<br />
        Actualizacion con T/C </th>
      <th class="enunciados" style="text-align:center">Valor al<br />
          <span class="enunciados" style="text-align:center"> <?php echo $this->_tpl_vars['fecha_inter']; ?>
 </span>con $ <br />
        <?php echo $this->_tpl_vars['valor_dolar2']; ?>
</th>
      <th class="enunciados" style="text-align:center">Valor UFV al <?php echo $this->_tpl_vars['fecha_inter']; ?>
 <br />
        <?php echo $this->_tpl_vars['valor_ufv']; ?>
</th>
		<th class="enunciados" style="text-align:center"><div align="center">Valor  al <?php echo $this->_tpl_vars['fecha_inter']; ?>
 con ufv <br />
		    <?php echo $this->_tpl_vars['valor_ufv']; ?>

		</div></th>
      <th class="enunciados" style="text-align:center">Incremento<br />
        por<br />
        Actualizacion con UFV</th>
      <th class="enunciados" style="text-align:center">Valor UFV al <?php echo $this->_tpl_vars['fecha_fin']; ?>
<br />
          <span class="enunciados" style="text-align:center"><?php echo $this->_tpl_vars['valor_ufv2']; ?>
</span></th>
      <th class="enunciados" style="text-align:center">Depreciacion Acumulada en $al <?php echo $this->_tpl_vars['fecha_enates']; ?>
 </th>
      <th class="enunciados" style="text-align:center">Incremento de depreciacion $ </th>
      <th class="enunciados" style="text-align:center">Depreciacion  del periodo </th>
      <th class="enunciados" style="text-align:center">Depreciacion acumulada  final $ al <?php echo $this->_tpl_vars['fecha_fin']; ?>
 </th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Depreciacion Acumulada en UFV <?php echo $this->_tpl_vars['fecha_inicio']; ?>
</th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Incremento de depreciacion UFV</th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Depreciacion  del periodo UFV </th>
      <th bgcolor="#CCCCCC" class="enunciados" style="text-align:center">Depreciacion acumulada final <?php echo $this->_tpl_vars['fecha_fin']; ?>
 </th>
      <th class="enunciados" style="text-align:center">Vida Restante </th>
    </tr>
	<?php $_from = $this->_tpl_vars['lista_activo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['grupo'] => $this->_tpl_vars['valores_activo']):
?> 	
	<th colspan="22"><div align="left"><?php echo $this->_tpl_vars['grupo']; ?>
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
      <?php $_from = $this->_tpl_vars['valores_activo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['activo'] => $this->_tpl_vars['valor']):
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
                <td nowrap="nowrap"><div align="center"><?php echo $this->_tpl_vars['valor']['desgru']; ?>
-<?php echo $this->_tpl_vars['valor']['numero']; ?>
</div></td>
    <td nowrap="nowrap"><div align="center"><?php echo $this->_tpl_vars['valor']['fecha']; ?>
</div></td>
    <td><div align="center"><?php echo $this->_tpl_vars['valor']['descripcion']; ?>
</div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['tipo_cambio'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td><div align="center"><?php echo $this->_tpl_vars['valor']['vida_util']; ?>
</div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['valor_compra'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total1', ($this->_tpl_vars['total1']+$this->_tpl_vars['valor']['valor_compra'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['valor'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total2', ($this->_tpl_vars['total2']+$this->_tpl_vars['valor']['valor'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['incremento'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total3', ($this->_tpl_vars['total3']+$this->_tpl_vars['valor']['incremento'])); ?></div></td>
    <td nowrap="nowrap"><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['valor1'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total4', ($this->_tpl_vars['total4']+$this->_tpl_vars['valor']['valor1'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 5) : smarty_modifier_commify($_tmp, 5)); ?>
</div></td>
	<td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['valor_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total7', ($this->_tpl_vars['total7']+$this->_tpl_vars['valor']['valor_ufv'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['incremento_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total5', ($this->_tpl_vars['total5']+$this->_tpl_vars['valor']['incremento_ufv'])); ?></div></td>
    <td><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['valor_ufv2'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('total6', ($this->_tpl_vars['total6']+$this->_tpl_vars['valor']['valor_ufv2'])); ?></div></td>
  	 <td><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_acumulada'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD1', ($this->_tpl_vars['totalD1']+$this->_tpl_vars['valor']['depre_acumulada'])); ?></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['incremento_depre'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD2', ($this->_tpl_vars['totalD2']+$this->_tpl_vars['valor']['incremento_depre'])); ?></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_periodo'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD3', ($this->_tpl_vars['totalD3']+$this->_tpl_vars['valor']['depre_periodo2'])); ?></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_fin'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD4', ($this->_tpl_vars['totalD4']+$this->_tpl_vars['valor']['depre_fin'])); ?></td>
    <td bgcolor="#CCCCCC"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_acumulada_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD5', ($this->_tpl_vars['totalD5']+$this->_tpl_vars['valor']['depre_acumulada_ufv'])); ?></td>
    <td bgcolor="#CCCCCC"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['incremento_depreufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD6', ($this->_tpl_vars['totalD6']+$this->_tpl_vars['valor']['incremento_depreufv'])); ?></td>
    <td bgcolor="#CCCCCC"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_periodo_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD7', ($this->_tpl_vars['totalD7']+$this->_tpl_vars['valor']['depre_periodo_ufv'])); ?></td>
    <td bgcolor="#CCCCCC"><?php echo ((is_array($_tmp=$this->_tpl_vars['valor']['depre_fin_ufv'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
<?php $this->assign('totalD8', ($this->_tpl_vars['totalD8']+$this->_tpl_vars['valor']['depre_fin_ufv'])); ?></td>
    <td><?php echo $this->_tpl_vars['valor']['vida_restante']; ?>
</td>
  
  
  </tr>
  <?php endforeach; endif; unset($_from); ?>
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
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total2'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total3'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total4'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td></td>
	 <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total7'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total5'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</div></td>
    <td style="font-weight:bold" ><div align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['total6'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
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
   
  </tr><?php endforeach; endif; unset($_from); ?>
  <tbody>
  </tbody>
</table>
<?php endif; ?>

</body>