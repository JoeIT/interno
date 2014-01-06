<?php /* Smarty version 2.6.18, created on 2008-11-08 10:05:55
         compiled from rep_responsable.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cabecera.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <br />

<body>
<div align="center">
<fieldset style="width:75%;">
<table border="0" align="center" cellpadding="0" cellspacing="3" width="97%">
    <tr>
      <th>Reporte de Responsables </th>
    </tr>
  </table>
<legend style="color:#6699CC"></legend><br />
<form action="../controladores/kardex.php" method="get">
<table width="75%" border="0" align="center" cellpadding="3" cellspacing="2">
  <tr>
   <?php if ($this->_tpl_vars['error']['err'] != null): ?>
  <tr>
    <td colspan="4"><div class="error-box" style="width:100%;"><?php echo $this->_tpl_vars['error']['err']; ?>
</div></td>
  </tr>
  <?php endif; ?>
   <?php if ($this->_tpl_vars['error']['err_kardex'] != null): ?>
  <tr>
    <td colspan="4"><div class="error-box" style="width:100%;"><?php echo $this->_tpl_vars['error']['err_kardex']; ?>
</div></td>
  </tr>
  <?php endif; ?>
   <?php if ($this->_tpl_vars['error']['err_resp'] != null): ?>
  <tr>
    <td colspan="4"><div class="error-box" style="width:100%;"><?php echo $this->_tpl_vars['error']['err_resp']; ?>
</div></td>
  </tr>
  <?php endif; ?>
   <td width="20%"  class="enunciados"><div align="left">Responsable:</div></td>
    <td colspan="2" class="body-sector"  >
	<div align="left" class="entrada" style="margin:5px; border:none">
	        <div align="left"><span class="entrada" style="margin:5px; border:none">
	          <span class="entrada" style="margin:5px;">
	            
              <input type="text" name="nombre_asis2" id="nombre_asis2" style="border:none;"  onkeypress="return handleEnter(this, event)" value="<?php echo $this->_tpl_vars['nombre_asis2']; ?>
" size="40"/>
              </span> </span>
	          <input type="hidden" name="resp_id2" id="resp_id2"  value="<?php echo $this->_tpl_vars['resp_id2']; ?>
" />
	          <img src="../../templates/imagenes/combo.jpg" class="imagenes" onClick="<?php echo 'document.getElementById(\'nombre_asis2\').focus();document.getElementById(\'nombre_asis2\').select();'; ?>
" style="cursor:pointer;" alt="Seleccionar Nombre"/></div>
	</div>
        <span id="spinner" style="display:none"> </span>
        <div id="lista_nombres_asis" class="autorelleno">
          <div align="left"></div>
        </div>
      <?php echo '
      <script>
			new Ajax.Autocompleter("nombre_asis2", "lista_nombres_asis", "../controladores/activo.php?opcion=busqueda_ajax1", {method:"post", paramName: "nombre", minChars: 1, indicator: "spinner", afterUpdateElement : mostrar_categoria});
			function mostrar_categoria(text,li) {
				cadena = li.id;
				//caracteristicas = cadena.split("-");
				//var str = document.getElementById("nombre_asis").value;
				document.getElementById("resp_id2").value = cadena;
				document.getElementById("nombre_asis2").value = str.substr(0)
			}
		</script>
      '; ?>
</td>
  </tr>
   <?php if ($this->_tpl_vars['error']['err_asig'] != null): ?>
  <tr>
    <td colspan="4"><div class="error-box" style="width:100%;"><?php echo $this->_tpl_vars['error']['err_asig']; ?>
</div></td>
  </tr>
  <?php endif; ?>
  <tr>
   <?php if ($this->_tpl_vars['error']['err_kar'] != null): ?>
  <tr>
    <td colspan="4"><div class="error-box" style="width:100%;"><?php echo $this->_tpl_vars['error']['err_kar']; ?>
</div></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="enunciados" ><div align="left">Asignado:</div></td>
     <td width="65%"  class="body-sector" >
	<div align="left" class="entrada" style="margin:5px; border:none" > 
	<span class="entrada" style="margin:5px; border:none">
	<span class="entrada" style="margin:5px;">
     
			 <input type="text" name="nombre_asis" id="nombre_asis" style="border:none;"  onkeypress="return handleEnter(this, event)" value="<?php echo $this->_tpl_vars['nombre_asis']; ?>
" size="40"/>
        </span></span>
            <input type="hidden" name="resp_id" id="resp_id"  value="<?php echo $this->_tpl_vars['resp_id']; ?>
" />
			
            <img src="../../templates/imagenes/combo.jpg" class="imagenes" onClick="<?php echo 'document.getElementById(\'nombre_asis\').focus();document.getElementById(\'nombre_asis\').select();'; ?>
" style="cursor:pointer;" alt="Seleccionar Nombre"/></div>
        <span id="spinner" style="display:none"> </span>
        <div id="lista_nombres_asis" class="autorelleno"></div>
      <?php echo '
      <script>
			new Ajax.Autocompleter("nombre_asis", "lista_nombres_asis", "../controladores/activo.php?opcion=busqueda_ajax1", {method:"post", paramName: "nombre", minChars: 1, indicator: "spinner", afterUpdateElement : mostrar_categoria});
			function mostrar_categoria(text,li) {
				cadena = li.id;
				//caracteristicas = cadena.split("-");
				//var str = document.getElementById("nombre_asis").value;
				document.getElementById("resp_id").value = cadena;
				document.getElementById("nombre_asis").value = str.substr(0)
			}
		</script>
      '; ?>
 </td>
  </tr>
   <?php if ($this->_tpl_vars['error']['radio'] != null): ?>
  <tr>
    <td colspan="4"><div class="error-box" style="width:100%;"><?php echo $this->_tpl_vars['error']['radio']; ?>
</div></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="enunciados">Resumen:</td>
    <td colspan="2"><input name="radio" type="radio" value="1"  <?php if ($this->_tpl_vars['radio'] == 1): ?> checked="checked" <?php endif; ?>></td>
  </tr>
  <tr>
    <td class="enunciados">Kardex:</td>
    <td><input name="radio" type="radio" value="2" <?php if ($this->_tpl_vars['radio'] == 2): ?> checked="checked" <?php endif; ?>></td>
    <td width="15%"><input type="submit" value="Aceptar" name="kardex" class="enviar" onMouseOver="this.className='boton'" onMouseOut="this.className='enviar'" ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</fieldset>
</div>
</body>
</html>