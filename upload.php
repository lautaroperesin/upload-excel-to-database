<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ventas";

ini_set('max_execution_time', 1800);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $provider = $_POST['provider'];
    $ganancia = $_POST['ganancia'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    
    // Comprueba si $uploadOk está establecido en 0 por un error
    if ($uploadOk == 0) {
        echo "Error: su archivo no fue cargado";
    } else {
        // Intenta mover el archivo cargado al servidor
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            echo "El archivo ". basename($_FILES["file"]["name"]). " se ha subido.";
                
            // ejecutar el archivo de python
            $output_file = $target_dir . "convertido.csv";
            $command = escapeshellcmd("python transform_excel_to_csv.py \"$provider\" \"$target_file\" \"$output_file\"");
            $output = shell_exec($command);

            if ($output === null) {
                echo "Error: falló la ejecución del comando.";
            } //else {
                //echo "Salida del script de Python: <br>" . nl2br($output);
            //}
        } else {
            echo "Error: error al cargar el archivo csv";
        }
    }
                
            // chequea si el archivo fue convertido
            if (file_exists($output_file)) {
                //echo "Archivo convertido correctamente.";
                
                // abre el archivo csv para leerlo
                if (($handle = fopen($output_file, "r")) !== FALSE) {                    
                    fgetcsv($handle, 1000, ",");
                    // conecta con la base de datos
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $count = 0;
                    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                        $producto_id = $data[0];
                        $producto_codigo = $data[1];
                        $producto_nombre = $data[2];
                        $producto_stock_total = $data[3];
                        $producto_tipo_unidad = $data[4];
                        $producto_precio_compra = floatval($data[5]);
                        $producto_precio_venta = $producto_precio_compra * (1 + $ganancia / 100);
                        $producto_marca = $data[7];
                        $producto_modelo = $data[8];
                        $producto_estado = $data[9];
                        $producto_foto = $data[10];
                        $categoria_id = $data[11];
                        $count++;
            
                        $sql = "INSERT INTO producto (producto_id, producto_codigo, producto_nombre, producto_stock_total, producto_tipo_unidad, producto_precio_compra, producto_precio_venta, producto_marca, producto_modelo, producto_estado, producto_foto, categoria_id)
                                VALUES ('$producto_id', '$producto_codigo', '$producto_nombre', '$producto_stock_total', '$producto_tipo_unidad', '$producto_precio_compra', '$producto_precio_venta', '$producto_marca', '$producto_modelo', '$producto_estado', '$producto_foto', '$categoria_id')";
                        if (!$conn->query($sql)) {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                    }
                    echo "Total de productos subidos: " . $count;

                    unlink($target_file);
                    unlink($output_file);
            
                    fclose($handle);

                    echo "PRODUCTOS SUBIDOS EXITOSAMENTE A LA BASE DE DATOS";
                } else {
                    echo "Error: No se puede abrir el archivo CSV convertido";
                }
                // cierra la conexion a la bd
                $conn->close();
            } else {
                echo "Error: el archivo convertido no existe.";
            }

        } else {
            echo "Hubo un error al cargar su archivo";
        }
?>