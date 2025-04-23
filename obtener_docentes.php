<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

if (!isset($_GET['token'])) {
    http_response_code(400);
    echo json_encode(["error" => "Token no proporcionado."]);
    exit;
}

$token = $_GET['token'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=eval_doc;charset=utf8", "root", "estefy04");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT nombre FROM docentes WHERE token = ?");
    $stmt->execute([$token]);

    $docentes = $stmt->fetchAll(PDO::FETCH_COLUMN); 

    echo json_encode($docentes);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
    exit;
}
?>