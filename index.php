<head>
    <link rel="stylesheet" href="styles.css">
	<script>
        function confirmDelete() {
            return confirm("¿Está seguro de que desea vaciar la tabla de productos?");
        }
    </script>
</head>
<div class="form-group form-control">
<form class="form-container" action="upload.php" method="post" enctype="multipart/form-data">
        		<label for="provider">Proveedor:</label>
        			<select class="select-prov" name="provider" id="provider">
            			<option value="di_paolo_mayorista">Di Paolo Mayorista</option>
            			<option value="williams_distribuciones">Williams Distribuciones</option>
            			<option value="celeste_y_blanca">Celeste y Blanca</option>
            			<option value="rio_distribuidora">Río Distribuidora</option>
            			<option value="tallamar_sa">Tallamar S.A.</option>
						<option value="marplast">Mar Plast S.A</option>
        			</select><br><br>
				<label for="ganancia">Porcentaje de Ganancia:</label>
				<p>Ingresar numero entero (sin %)</p>
        		<input class="input-ganancia" type="number" name="ganancia" id="ganancia" required><br><br>
        		<label for="file">Selecciona el archivo Excel:</label>
        		<input type="file" name="file" id="file" accept=".xlsx, .xls"><br><br>
        		<input class="btn-subir" type="submit" value="Subir archivo"><br><br>
			</form>
			<form class="form-container" action="vaciar_tabla.php" method="post" onsubmit="return confirmDelete();">
					<input type="hidden" name="action" value="delete">
					<input class="btn-eliminar" type="submit" value="Eliminar todos los productos">
			</form>
</div>