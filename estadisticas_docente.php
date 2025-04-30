<?php
$pdo = new PDO("mysql:host=localhost;dbname=eval_doc", "root", "estefy04");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Obtener nombre del usuario desde GET
if (!isset($_GET['usuario'])) die("Nombre de usuario no proporcionado.");
$nombreUsuario = $_GET['usuario'];

$stmt = $pdo->prepare("SELECT id, nombre FROM docentes WHERE nombre = :nombre");
$stmt->execute(['nombre' => $nombreUsuario]);
$docente = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$docente) die("Docente no encontrado en la tabla docentes.");

$idDocente = $docente['id'];
$nombreDocente = $docente['nombre'];

// Obtener las evaluaciones de ese docente (anónimas)
$stmt = $pdo->prepare("
    SELECT calificacion, fecha, periodo
    FROM evaluaciones
    WHERE id_docente = :idDocente
    ORDER BY fecha
");
$stmt->execute(['idDocente' => $idDocente]);
$evaluaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular distribución de puntuaciones
$puntuaciones = [0, 0, 0, 0, 0, 0];
foreach ($evaluaciones as $eval) {
    $cal = $eval['calificacion'];
    if ($cal < 1) $puntuaciones[0]++;
    elseif ($cal < 2) $puntuaciones[1]++;
    elseif ($cal < 3) $puntuaciones[2]++;
    elseif ($cal < 4) $puntuaciones[3]++;
    elseif ($cal < 5) $puntuaciones[4]++;
    else $puntuaciones[5]++;
}

$puntuaciones_json = json_encode($puntuaciones);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Evaluaciones Recibidas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1, h2 { text-align: center; }
        canvas { width: 100%; max-width: 600px; margin: 0 auto; display: block; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    </style>
</head>
<body>

<h1>Evaluaciones Recibidas</h1>
<h2><?= htmlspecialchars($nombreDocente) ?></h2>

<canvas id="grafica" width="400" height="200"></canvas>

<h3>Detalle Anónimo de Evaluaciones</h3>
<table>
    <thead>
        <tr>
            <th>Periodo</th>
            <th>Calificación</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($evaluaciones as $fila): ?>
            <tr>
                <td><?= htmlspecialchars($fila['periodo']) ?></td>
                <td><?= $fila['calificacion'] ?></td>
                <td><?= $fila['fecha'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    const data = {
        labels: ['Insuficiente', 'Deficiente', 'Regular', 'Bueno', 'Muy Bueno', 'Excelente'],
        datasets: [{
            label: 'Evaluaciones recibidas',
            data: <?= $puntuaciones_json ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(255, 159, 64, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)',
                'rgba(54, 162, 235, 0.5)'
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
                    ticks: { display: false },
                    grid: { display: false }
                }
            }
        }
    });
</script>

</body>
</html>