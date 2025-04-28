<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'eval_doc';
$username = 'root';
$password = 'estefy04';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al conectar con la base de datos: ' . $e->getMessage()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $user = $input['usuario'] ?? '';
    $pass = $input['password'] ?? '';

    if (!empty($user) && !empty($pass)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE usuario = :username AND password = :password");
        $stmt->bindParam(':username', $user);
        $stmt->bindParam(':password', $pass);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode([
                'success' => true,
                'message' => 'Inicio de sesión exitoso.',
                'redirect' => 'main.html',
                'nombre' => $userData['usuario'],
                'tipo_usuario' => $userData['tipo_usuario']  
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Por favor, completa todos los campos.']);
    }
}
?>