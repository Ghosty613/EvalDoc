<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

if (isset($_POST['nombreUsuario'])) {
    $nombreUsuario = $_POST['nombreUsuario'];

    $stmt = $pdo->prepare("SELECT id FROM users WHERE usuario = :usuario");
    $stmt->execute(['usuario' => $nombreUsuario]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        $id_alumno = $usuario['id'];
        
        $calificacion = isset($_POST['calificacion']) ? $_POST['calificacion'] : 0;

        if (isset($_POST['token_docente'])) {
            $token_docente = $_POST['token_docente'];

            $stmt = $pdo->prepare("SELECT id FROM docentes WHERE token = :token_docente");
            $stmt->execute(['token_docente' => $token_docente]);
            $docente = $stmt->fetch();

            if ($docente) {
                $id_docente = $docente['id'];

                $stmt = $pdo->prepare("INSERT INTO evaluaciones (id_alumno, id_docente, periodo, calificacion) 
                                       VALUES (:id_alumno, :id_docente, :periodo, :calificacion)");
                $stmt->execute([
                    'id_alumno' => $id_alumno,
                    'id_docente' => $id_docente,
                    'periodo' => '1-2025', 
                    'calificacion' => $calificacion
                ]);

                echo json_encode(['success' => true, 'message' => 'Evaluación guardada con éxito']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Docente no encontrado']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Token de docente no proporcionado']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nombre de usuario no proporcionado']);
}
?>