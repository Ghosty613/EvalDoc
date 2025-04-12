<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost'; 
$dbname = 'eval_doc';  
$username = 'root';
$password = 'estefy04'; 

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"));
if (!$data) {
    http_response_code(400);
    echo "No se recibieron datos.";
    exit;
}

if (!isset($data->usuario) || !isset($data->password)) {
    http_response_code(400);
    echo "Datos incompletos.";
    exit;
}

$usuario = $data->usuario;
$password = $data->password;

$sql = "SELECT * FROM users WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Usuario encontrado";
    $row = $result->fetch_assoc();

    if (password_verify($password, $row['password'])) {
        echo "Login exitoso";
    } else {
        echo "Usuario o contraseña no coinciden";
    }
} else {
    echo "Usuario no encontrado";
}

$stmt->close();
$conn->close();
?>