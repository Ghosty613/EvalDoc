<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
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

if (!isset($_GET['token'])) {
    http_response_code(400);
    echo json_encode(["error" => "Token no proporcionado."]);
    exit;
}

$token = $_GET['token'];

$stmt = $pdo->prepare("SELECT nombre FROM docentes WHERE token = :token");
$stmt->execute(['token' => $token]);
$docentes = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($docentes);
exit;
?>