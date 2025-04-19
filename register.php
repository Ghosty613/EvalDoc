<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json'); 

$host = 'localhost';
$dbname = 'eval_doc';
$username = 'root';
$password = 'estefy04';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isset($_POST['email']) || !isset($_POST['user']) || !isset($_POST['password'])) {
            // Redirigir con un parámetro de error
            header("Location: register.html?error=Faltan campos requeridos");
            exit();
        }

        $email = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
        $user = htmlspecialchars(trim($_POST['user']), ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars(trim($_POST['password']), ENT_QUOTES, 'UTF-8');

        $tipo_usuario = 'alumno';

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR usuario = ?");
        $stmt->execute([$email, $user]);

        if ($stmt->rowCount() > 0) {
            // Redirigir con mensaje de error si ya existe el usuario
            header("Location: register.html?error=El usuario o correo ya existe");
            exit();
        }

        // Insertar nuevo usuario
        $sql = "INSERT INTO users (email, usuario, password, tipo_usuario) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email, $user, $password, $tipo_usuario]);

        // Redirigir con mensaje de éxito
        header("Location: register.html?success=Usuario registrado exitosamente");
        exit();
    } else {
        header("Location: register.html?error=Método no permitido");
        exit();
    }
} catch (PDOException $e) {
    header("Location: register.html?error=Error en el servidor: " . $e->getMessage());
    exit();
}
?>
