<?php
$pdo = new PDO("mysql:host=localhost;dbname=eval_doc", "root", "estefy04");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Obtener nombreUsuario desde GET
if (!isset($_GET['usuario'])) {
    die("Nombre de usuario no proporcionado.");
}
$nombreUsuario = $_GET['usuario'];

// Buscar el ID del alumno
$stmt = $pdo->prepare("SELECT id, usuario FROM users WHERE usuario = :usuario AND tipo_usuario = 'alumno'");
$stmt->execute(['usuario' => $nombreUsuario]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Usuario no encontrado o no es un alumno.");
}

$idAlumno = $user['id'];
$numeroControl = $user['usuario']; // Suponiendo que el "usuario" es el número de control

// Obtener evaluaciones del alumno
$stmt = $pdo->prepare("
    SELECT e.id, e.periodo, e.fecha, d.nombre AS docente
    FROM evaluaciones e
    JOIN docentes d ON e.id_docente = d.id
    WHERE e.id_alumno = :idAlumno
");
$stmt->execute(['idAlumno' => $idAlumno]);
$evaluaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generar un hash más corto para el certificado
function generarHash($evaluacion) {
    $data = $evaluacion['id'] . $evaluacion['id_alumno'] . $evaluacion['fecha']; // Datos únicos de la evaluación
    return substr(hash('sha256', $data), 0, 8); // Generar un hash y truncarlo a los primeros 8 caracteres
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado de Evaluaciones</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        .firma { margin-top: 40px; text-align: right; }
    </style>
</head>
<body>
    <h1>Certificado de Evaluaciones</h1>

    <p>Número de control: <strong>__________</strong></p> <!-- Espacio en blanco para número de control -->

    <table>
        <thead>
            <tr>
                <th>Docente</th>
                <th>Periodo</th>
                <th>Fecha</th>
                <th>Clave Verificación</th> <!-- Columna para el hash -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($evaluaciones as $eval): ?>
            <tr>
                <td><?= htmlspecialchars($eval['docente']) ?></td>
                <td><?= htmlspecialchars($eval['periodo']) ?></td>
                <td><?= htmlspecialchars($eval['fecha']) ?></td>
                <td><?= htmlspecialchars(generarHash($eval)) ?></td> <!-- Mostrar el hash truncado -->
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="firma">
        <p>__________________________</p>
        <p>Firma del Alumno</p>
    </div>

    <script>
        window.onload = () => window.print();
    </script>
</body>
</html>