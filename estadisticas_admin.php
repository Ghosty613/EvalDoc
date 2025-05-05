<?php
$pdo = new PDO("mysql:host=localhost;dbname=eval_doc", "root", "estefy04");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Obtener todas las evaluaciones agrupadas por docente
$stmt = $pdo->prepare("
    SELECT d.nombre AS docente, COUNT(e.id) AS total_evaluaciones, 
           ROUND(AVG(e.calificacion), 2) AS promedio
    FROM evaluaciones e
    INNER JOIN docentes d ON e.id_docente = d.id
    GROUP BY e.id_docente
    ORDER BY d.nombre ASC
");
$stmt->execute();
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Para gráfica
$nombres = [];
$promedios = [];

foreach ($datos as $fila) {
    $nombres[] = $fila['docente'];
    $promedios[] = $fila['promedio'];
}

$nombres_json = json_encode($nombres);
$promedios_json = json_encode($promedios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Evaluaciones por Docente</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        canvas { width: 100%; max-width: 800px; margin: 20px auto; display: block; }
        #main { text-decoration: none; }
    </style>
</head>
<body>

<a href="main.html" id="main">←</a>
<h1>Evaluaciones por Docente</h1>

<canvas id="grafica"></canvas>

<table>
    <thead>
        <tr>
            <th>Nombre del Docente</th>
            <th>Total de Evaluaciones</th>
            <th>Promedio</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($datos as $fila): ?>
            <tr>
                <td><?= htmlspecialchars($fila['docente']) ?></td>
                <td><?= $fila['total_evaluaciones'] ?></td>
                <td><?= $fila['promedio'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    const ctx = document.getElementById('grafica');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= $nombres_json ?>,
            datasets: [{
                label: 'Promedio por Docente',
                data: <?= $promedios_json ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    ticks: { display: true }
                }
            }
        }
    });
</script>

</body>
</html>