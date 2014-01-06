<?php /* Smarty version 2.6.18, created on 2009-03-21 11:08:14
         compiled from baja_activo.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'commify', 'baja_activo.html', 89, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cabecera.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <br/>
<?php echo '

<script language="javascript">
/*function actualizar (paginaservidor,combo,lista,clave){

new Ajax.Updater(lista, paginaservidor+\'?\'+clave+\'=\'+$F(combo), { 
	  method: \'get\', 
	   
	});

//alert (paginaservidor+\'?\'+clave+\'=\'+$F(combo)+\' - \' + secundaria.innerHTML);
}
*/
function actualizar (paginaservidor, combo, lista, clave){
	new Ajax.Updater(lista, paginaservidor+\'?\'+clave+\'=\'+$F(combo), { 
	  method: \'get\'	});
}

function correlativo (paginaservidor,combo,lista,clave){

new Ajax.Updater(lista, paginaservidor, { 
  method: \'get\', 
  parameters: { cuenta: $F(combo)} 
});

//alert (paginaservidor+" "+combo+" "+lista+" "+$(combo).value);
}
/*function actualizar (paginaservidor,combo,lista,clave){

new Ajax.Updater(lista, paginaservidor, { 
  method: \'get\', 
  parameters: { primaria: $F(combo)} 
});

//alert (paginaservidor+" "+combo+" "+lista+" "+$(combo).value);
}*/
</script>
'; ?>

<form name="orden" method="get" action="../controladores/darbaja.php"  align="center">
 
 <div align="center">Detalle   activos </div> 

<input type="hidden" name="funcion" value="validar" />
<input type="hidden" name="act_id" value="<?php echo $this->_tpl_vars['detalle']['act_id']; ?>
" />
<table  border="0" align="center" cellpadding="0" cellspacing="3" >
  <tr>
    <th colspan="12"><div align="center">Responsable /Activo </div></th>
	
	
	
  </tr>
  <tr style="font-family:tahoma; font-size:11px"><td colspan="12" >Nombre: <?php echo $this->_tpl_vars['nombre_asis']; ?>
</td></tr>
  <tr  style="font-family:tahoma; font-size:11px"><td colspan="12" >Responsable Primario: <?php echo $this->_tpl_vars['nombre_asis2']; ?>
</td></tr>
  <tr  style="font-family:tahoma; font-size:11px"><td colspan="12"><?php echo $this->_tpl_vars['detalle']['desgru']; ?>
</td></tr>
  <td class="enunciados" align="center" >Numero</td>
  <td class="enunciados" align="center" >Fecha Compra </td>
    <td class="enunciados" align="center" >Descripcion</td>
    <td class="enunciados" align="center" > Unidad  </td>
    <td class="enunciados" align="center" >Serie</td>
    <td class="enunciados" align="center" >Primaria  </td>
    <td class="enunciados" align="center" >Secunadaria</td>
    <td class="enunciados" align="center" >Vida Util  </td>
    <td class="enunciados" align="center" >Valor de Compra </td>
	<td class="enunciados" align="center" >Valor Residual </td>
	<td class="enunciados" align="center" >Adquisicion</td>
	<td class="enunciados" align="center" >Detalle Adquisicion</td>
  </tr>
    <?php $this->assign('CSSclass', 0); ?>
	 <?php $this->assign('clases', "lista-normal"); ?>
     
		<?php $this->assign('CSSclass', ($this->_tpl_vars['CSSclass']+1)); ?>
	    <?php if (( $this->_tpl_vars['CSSclass'] % 2 ) == 0): ?>
		    <?php $this->assign('clases', "lista-seleccionada"); ?>
	    <?php else: ?>
		    <?php $this->assign('clases', "lista-normal"); ?>
	    <?php endif; ?>
  
  
    <tr  align="center" bgcolor="#E5E5E5"  style="font-family:tahoma; font-size:11px"> 
    <td align="center" ><?php echo $this->_tpl_vars['detalle']['num_act']; ?>
</td>
	<td align="center" ><?php echo $this->_tpl_vars['detalle']['fecha']; ?>
</td>
    <td align="center" ><?php echo $this->_tpl_vars['detalle']['desact']; ?>
</td>
    <td align="center" ><?php echo $this->_tpl_vars['detalle']['unidad']; ?>
</td>
    <td align="center" ><?php echo $this->_tpl_vars['detalle']['serie']; ?>
</td>
	<td align="center" ><?php echo $this->_tpl_vars['detalle']['despri']; ?>
</td>
    <td align="center" ><?php echo $this->_tpl_vars['detalle']['dessec']; ?>
</td>
	<td align="center" ><?php echo $this->_tpl_vars['detalle']['vida_util']; ?>
 </td>
    <td align="center" ><?php echo ((is_array($_tmp=$this->_tpl_vars['detalle']['valor_compra'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</td>
	<td align="center" ><?php echo ((is_array($_tmp=$this->_tpl_vars['detalle']['valor_residual'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
</td>
	<td align="center" ><?php echo $this->_tpl_vars['adquis']['nombre']; ?>
 </td>
	<td align="center" ><?php echo $this->_tpl_vars['detalle']['det_adqui']; ?>
 </td>
  </tr>
</table>


  <br />
  <table border="0" cellspacing="2" cellpadding="0">
    <?php if ($this->_tpl_vars['foto'] != ""): ?>
  <tr><?php $_from = $this->_tpl_vars['foto']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
    <td width="6%"><img src="personal_resizeImage.php?max=150&amp;imgorig=../controladores/UploadedFiles/<?php echo $this->_tpl_vars['item']['fotografia']; ?>
" alt="<?php echo $this->_tpl_vars['item']['fotografia']; ?>
" name="imagen_fotografia" border="1" align="middle" id="imagen_fotografia"/>
          <input type="hidden" name="foto" value="<?php echo $this->_tpl_vars['item']['fotografia']; ?>
" />
      <input type="hidden" name="numerito" value="<?php echo $this->_tpl_vars['numerito']; ?>
" /></td>
    <?php endforeach; endif; unset($_from); ?>
    <td width="94%">&nbsp;</td>
  </tr>
    <?php else: ?>
  <tr >"no existe foto para el activo"</tr>
    <?php endif; ?>
  </table>
  <p>&nbsp;</p>
  <fieldset style="width:95%;">
<legend style="color:#6699CC">Dar de baja </legend>
<table width="95%" border="0" cellspacing="2" cellpadding="2">
 	 
  <tr> 
   <?php if ($this->_tpl_vars['errores']['observacion'] != null): ?>
     <td class="error-box"><?php echo $this->_tpl_vars['errores']['observacion']; ?>
 </td>
	 <?php endif; ?>
     <td >&nbsp;</td>
  </tr>

  <tr>
   <td class="enunciados"><div align="left">Observaciones</div></td>
   <td><textarea name="observaciones" cols="35" rows="4"></textarea></td>
   <td class="enunciados">Fecha</td>
   <td><span align="left" class="entrada" style="margin:5px;vertical-align:top;">
			<img src="../../templates/imagenes/fecha.jpg" class="imagenes"/>
			<input type="text" name="fecha" value="<?php echo $this->_tpl_vars['fecha']; ?>
" readonly style="border:none;"/>
		</span>
        <input type="button" id="fecha_boton" class="boton" value="..." /></td>
  </tr>
</table>

  </fieldset>
    <input type="submit" value="Insertar Baja" name="insertar_baja" class="enviar" onmouseover="this.className='boton'" onmouseout="this.className='enviar'" />
    </p>
</center>
</form>
<?php echo '
<script type="text/javascript">
  							 Calendar.setup({
						     inputField     :    "fecha",     // id del campo de texto
						     ifFormat     :     "%Y-%m-%d" ,   // formato de la fecha que se escriba en el campo de texto
						     button     :    "fecha_boton"     // el id del botón que lanzará el calendario
							});
						</script>
'; ?>

</body>