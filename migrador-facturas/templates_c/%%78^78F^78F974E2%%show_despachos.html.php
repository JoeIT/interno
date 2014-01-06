<?php /* Smarty version 2.6.9, created on 2009-01-13 14:20:45
         compiled from show_despachos.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../templates/pda-cases.css" rel="stylesheet" type="text/css">
<?php echo '
<script> 
function cerrarse(){ 
window.close() 
} 
</script> 
'; ?>

</head>

<body>
<p><span class="bold-med-text4">User<b>:</b></span> <span class="bold-med-text2"><b><?php echo $this->_tpl_vars['LOGINUS']; ?>
</b></span><span class="text-b"><b> |</b></span><b> 

<a href="javascript:onClick=window.close();" target="_top"><img src="../images/logout.jpg" border="0" alt="Logout" onclick="cerrarse"></a></b></p>


<?php echo '
<script type="text/javascript" language="javascript">
   var http_request = false;
   function makePOSTRequest(url, parameters) {
      http_request = false;
      if (window.XMLHttpRequest) { // Mozilla, Safari,...
         http_request = new XMLHttpRequest();
         if (http_request.overrideMimeType) {
         	// set type accordingly to anticipated content type
            //http_request.overrideMimeType(\'text/xml\');
            http_request.overrideMimeType(\'text/html\');
         }
      } else if (window.ActiveXObject) { // IE
         try {
            http_request = new ActiveXObject("Msxml2.XMLHTTP");
         } catch (e) {
            try {
               http_request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
         }
      }
      if (!http_request) {
         alert(\'Cannot create XMLHTTP instance\');
         return false;
      }
      
      http_request.onreadystatechange = alertContents;
      http_request.open(\'POST\', url, true);
      http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      http_request.setRequestHeader("Content-length", parameters.length);
      http_request.setRequestHeader("Connection", "close");
      http_request.send(parameters);
   }

   function alertContents() {
      if (http_request.readyState == 4) {
         if (http_request.status == 200) {
            //alert(http_request.responseText);
            result = http_request.responseText;
            document.getElementById(\'myspan\').innerHTML = result;            
         } else {
            alert(\'There was a problem with the request.\');
         }
      }
   }
   
   function post(obj) {
      var poststr = "qty=" + encodeURI( document.getElementById("cantidad").value );
      makePOSTRequest(\'http://192.168.0.5/scr/show_despachos.php\', poststr);
   }
</script>
'; ?>

<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="border:solid 1px #999999">
  <tr>
    <td><div align="center" class="big-text">Lista de despachos recientes desde Bolivia <br />
    <?php if ($this->_tpl_vars['mensaje'] != ""): ?>&nbsp;<span class="green-med-text"><?php echo $this->_tpl_vars['mensaje']; ?>
</span><?php endif; ?>
	<?php if ($this->_tpl_vars['mensaje_err'] != ""): ?>&nbsp;<span class="red-medium-text"><?php echo $this->_tpl_vars['mensaje_err']; ?>
</span><?php endif; ?></div></td>
  </tr>
  <tr>
    <td>
      <form id="fcant" name="fcant" method="post" action="javascript:post(document.getElementById('fcant'));">
	    <div align="left"><span class="bold-medium-text">Cantidad de despachos a mostrar:</span> 
          <select name="cantidad" id="cantidad" size="1" onchange="javascript:post(this.parentNode);">
            <option>5</option>
            <option>6</option>
            <option>7</option>
            <option>8</option>
            <option>9</option>
            <option>10</option>
          </select>
        </div>
      </form>    </td>
  </tr>
  <tr>
    <td height="36">
	<form name="fdesp" id="fdesp" method="post" action="../scr/show_despachos.php">
	<table border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td class="bold-medium-text">Seleccione el depacho para migrar:</td>
        <td> <select name="iddespacho">
	      
		  <option value="0">- - Despachos- -</option>
		<?php unset($this->_sections['nz']);
$this->_sections['nz']['name'] = 'nz';
$this->_sections['nz']['loop'] = is_array($_loop=$this->_tpl_vars['despachos']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['nz']['show'] = true;
$this->_sections['nz']['max'] = $this->_sections['nz']['loop'];
$this->_sections['nz']['step'] = 1;
$this->_sections['nz']['start'] = $this->_sections['nz']['step'] > 0 ? 0 : $this->_sections['nz']['loop']-1;
if ($this->_sections['nz']['show']) {
    $this->_sections['nz']['total'] = $this->_sections['nz']['loop'];
    if ($this->_sections['nz']['total'] == 0)
        $this->_sections['nz']['show'] = false;
} else
    $this->_sections['nz']['total'] = 0;
if ($this->_sections['nz']['show']):

            for ($this->_sections['nz']['index'] = $this->_sections['nz']['start'], $this->_sections['nz']['iteration'] = 1;
                 $this->_sections['nz']['iteration'] <= $this->_sections['nz']['total'];
                 $this->_sections['nz']['index'] += $this->_sections['nz']['step'], $this->_sections['nz']['iteration']++):
$this->_sections['nz']['rownum'] = $this->_sections['nz']['iteration'];
$this->_sections['nz']['index_prev'] = $this->_sections['nz']['index'] - $this->_sections['nz']['step'];
$this->_sections['nz']['index_next'] = $this->_sections['nz']['index'] + $this->_sections['nz']['step'];
$this->_sections['nz']['first']      = ($this->_sections['nz']['iteration'] == 1);
$this->_sections['nz']['last']       = ($this->_sections['nz']['iteration'] == $this->_sections['nz']['total']);
?>
          
	      <option value="<?php echo $this->_tpl_vars['despachos'][$this->_sections['nz']['index']]['iddespacho']; ?>
"><?php echo $this->_tpl_vars['despachos'][$this->_sections['nz']['index']]['nombre_despacho']; ?>
 - (<?php echo $this->_tpl_vars['despachos'][$this->_sections['nz']['index']]['fecha_ref']; ?>
)</option>
	      
		  <?php endfor; endif; ?>
            
	      </select> </td>
      </tr>
      <tr>
        <td align="right" class="bold-medium-text">Observaciones:</td>
        <td><textarea name="obs" cols="20" id="obs"></textarea></td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td> <input name="send" type="submit" id="send" value="Migrar" /></td>
      </tr>
    </table>
	</form>	</td>
  </tr>
</table>
</body>
</html>