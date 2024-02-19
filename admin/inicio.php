<?php
include 'config/database.php';
include 'config/config.php';

$db = new Database();
$con = $db->conectar();

include 'header.php';

$hoy = date('Y-m-d');
$lunes = date('Y-m-d', strtotime('monday this week', strtotime($hoy)));
$domingo = date('Y-m-d', strtotime('sunday this week', strtotime($hoy)));

$fechaInicial = new DateTime($lunes);
$fechaFinal = new DateTime($domingo);

$diasVentas = [];

for ($i = $fechaInicial; $i <= $fechaFinal; $i->modify('+1 day')) {
    $diasVentas[] = totalDia($con, $i->format('Y-m-d')); // Almacena cada valor en un array
}

$diasVentas = implode(',', $diasVentas); // Concatena los valores del array en una cadena separada por comas


/// -------------------------------------------------

$listaProductos = productoMasVendidos($con, $lunes, $domingo);
$nombreProductos = [];
$cantidadProductos = [];

foreach ($listaProductos as $producto) {
    $nombreProductos[] = $producto['nombre'];
    $cantidadProductos[] = $producto['cantidad'];
}

$nombreProductos = implode(',', $nombreProductos);
$cantidadProductos = implode(',', $cantidadProductos);

function totalDia($con, $fecha)
{
    $sql = "SELECT IFNULL(SUM(total), 0) AS total FROM compra WHERE DATE(fecha) = '$fecha' AND status LIKE 'COMPLETED'";
    $resultado = $con->query($sql);
    $row = $resultado->fetch(PDO::FETCH_ASSOC);

    return $row['total'];
}

function productoMasVendidos($con, $fechaInicial, $fechaFinal)
{
    $sql = "SELECT SUM(dc.cantidad) AS cantidad, dc.nombre FROM detalle_compra AS dc 
INNER JOIN compra AS c ON dc.id_compra = c.id
WHERE DATE(c.fecha) BETWEEN '$fechaInicial' AND '$fechaFinal'
GROUP BY dc.id_producto, dc.nombre 
ORDER BY SUM(dc.cantidad) DESC
LIMIT 5";


    $resultado = $con->query($sql);
    return $resultado->fetchAll(PDO::FETCH_ASSOC);
}


?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>

        <div class="row">
            <div class="col-5">
                <div class="card mb-4">
                    <div class="card header">
                        Ventas de la semana
                    </div>
                    <div class="card body">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card mb-4">
                    <div class="card header">
                        Pruductos mas vendidos de la semana
                    </div>
                    <div class="card body">
                        <canvas id="chart-productos"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'],
            datasets: [{
                data: [<?php echo $diasVentas ?>],
                backgroundColor: [
                    'rgb(255,99,132)',
                    'rgba(255,99,132)',
                    'rgba(255,99,132)',
                    'rgba(0,255,0)',
                    'rgba(255,99,132)',
                    'rgba(255,99,132)',
                    'rgba(255,99,132)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            Plugin: {
                legend: {
                    display: false
                }
            }
        }
    });

    const ctxProductos = document.getElementById('chart-productos');

    new Chart(ctxProductos, { // Corregido 'new Chart' en lugar de 'new chartProd'
        type: 'pie', // Corregido tipo de gráfico a 'pie'
        data: {
            labels: ['<?php echo $nombreProductos; ?>'],
            datasets: [{

                data: [<?php echo $cantidadProductos; ?>],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php include 'footer.php'; ?>