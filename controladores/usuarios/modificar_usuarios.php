<html>
<head>
	<title>
		Modificar Usuario
	</title>
</head>
<body>
	<h1 align="center"> Editar usuario </h1>
	<form action="index.php" method="POST">
		<table align="center">
			<tr>
				<td>Nick:</td>
				<td><input type="text" name="nick"><br></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type="password" name="pass1"><br></td>
			</tr>
			<tr>
				<td>Repite Password:</td>
				<td><input type="password" name="pass2"><br></td>
			</tr>
			<tr>
				<td>Nombre:</td>
				<td><input type="text" name="nombre"><br></td>
			</tr>
			<tr>
				<td>E-mail:</td>
				<td><input type="text" name="email"><br></td>
			</tr>
			<tr>
				<td>Categoria:</td>
				<td><input type="text" name="categoria"><br></td>
			</tr>
			<tr>
						<input type="hidden" name="funcion" value="registrar">
				<td><br><input type="submit" name="Crear" value="Registrar"></td>
			</tr>
		</table>
	</form> 
</body>
</html>