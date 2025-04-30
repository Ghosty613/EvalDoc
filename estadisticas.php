<?php
// Conexión a la base de datos
$pdo = new PDO("mysql:host=localhost;dbname=eval_doc", "root", "estefy04");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Obtener nombreUsuario desde GET
if (!isset($_GET['usuario'])) die("Nombre de usuario no proporcionado.");
$nombreUsuario = $_GET['usuario'];

// Obtener ID del alumno
$stmt = $pdo->prepare("SELECT id FROM users WHERE usuario = :usuario AND tipo_usuario = 'alumno'");
$stmt->execute(['usuario' => $nombreUsuario]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) die("Usuario no encontrado.");
$idAlumno = $user['id'];

// Obtener lista de docentes evaluados por el alumno
$stmt = $pdo->prepare("
    SELECT DISTINCT d.id, d.nombre
    FROM evaluaciones e
    INNER JOIN docentes d ON e.id_docente = d.id
    WHERE e.id_alumno = :idAlumno
    ORDER BY d.nombre
");
$stmt->execute(['idAlumno' => $idAlumno]);
$docentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener ID del docente seleccionado desde GET (si existe)
$docenteSeleccionado = isset($_GET['docente_id']) ? (int) $_GET['docente_id'] : null;

// Si se seleccionó un docente, obtener evaluaciones para ese docente
$puntuaciones = [0, 0, 0, 0, 0, 0];
$evaluaciones_detalle = [];

if ($docenteSeleccionado) {
    $stmt = $pdo->prepare("
        SELECT e.calificacion, d.nombre AS nombre_docente
        FROM evaluaciones e
        INNER JOIN docentes d ON e.id_docente = d.id
        WHERE e.id_alumno = :idAlumno AND d.id = :docenteId
    ");
    $stmt->execute(['idAlumno' => $idAlumno, 'docenteId' => $docenteSeleccionado]);
    $evaluaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($evaluaciones as $eval) {
        $evaluaciones_detalle[] = $eval;

        $cal = $eval['calificacion'];
        if ($cal < 1) $puntuaciones[0]++;
        elseif ($cal < 2) $puntuaciones[1]++;
        elseif ($cal < 3) $puntuaciones[2]++;
        elseif ($cal < 4) $puntuaciones[3]++;
        elseif ($cal < 5) $puntuaciones[4]++;
        else $puntuaciones[5]++;
    }
}

$puntuaciones_json = json_encode($puntuaciones);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas por Docente</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { text-align: center; }
        canvas { width: 100%; max-width: 600px; margin: 0 auto; display: block; }
        select { padding: 5px; font-size: 16px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    </style>
</head>
<body>

<h1>Estadísticas de Evaluaciones</h1>

<form method="GET" id="filtroForm">
    <input type="hidden" name="usuario" value="<?= htmlspecialchars($nombreUsuario) ?>">
    <label for="docente_id">Selecciona un docente:</label>
    <select name="docente_id" id="docente_id" onchange="document.getElementById('filtroForm').submit();">
        <option value="">-- Selecciona --</option>
        <?php foreach ($docentes as $docente): ?>
            <option value="<?= $docente['id'] ?>" <?= $docenteSeleccionado == $docente['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($docente['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<?php if ($docenteSeleccionado): ?>
    <canvas id="grafica" width="400" height="200"></canvas>

    <h2>Detalle de Calificaciones</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre del Docente</th>
                <th>Calificación</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($evaluaciones_detalle as $fila): ?>
                <tr>
                    <td><?= htmlspecialchars($fila['nombre_docente']) ?></td>
                    <td><?= $fila['calificacion'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        const data = {
            labels: ['Insuficiente', 'Deficiente', 'Regular', 'Bueno', 'Muy Bueno', 'Excelente'],
            datasets: [{
                label: 'Número de Evaluaciones',
                data: <?= $puntuaciones_json ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(255, 159, 64, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(54, 162, 235, 0.5)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(255,159,64,1)',
                    'rgba(255,206,86,1)',
                    'rgba(75,192,192,1)',
                    'rgba(153,102,255,1)',
                    'rgba(54,162,235,1)'
                ],
                borderWidth: 1
            }]
        };

        new Chart(document.getElementById('grafica'), {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });
    </script>
<?php endif; ?>

</body>
</html>