<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ventas";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    // Acción de vaciar la tabla de producto
    $sql = "DELETE FROM producto";
    if ($conn->query($sql) === TRUE) {
        echo "Productos eliminados correctamente";
    } else {
        echo "Error al eliminar los productos: " . $conn->error;
    }
}
?>