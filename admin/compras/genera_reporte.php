<?php



require '../config/config.php';





require '../header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>


    <script src="https://kit.fontawesome.com/751f7f0eaa.js" crossorigin="anonymous"></script>
    <title>Store</title>
</head>

<body>

    <!--Contenido-->
    <main class="flex-shrink-0">
        <div class="container mt-3">
            <h4>Reporte de compras</h4>

            <form action="reporte_compras.php" method="post" autocomplete="off">

                <div class="row mb-2">
                    <div class="col-12 col-med-4">
                        <label for="fecha_ini" class="form-label">Fecha inicial:</label>
                        <input type="date" class="form-control" name="fecha_ini" id="fecha_ini" required autofocus>
                    </div>

                    <div class="col-12 col-med-4">
                        <label for="fecha_fin" class="form-label">Fecha final:</label>
                        <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Generar</button>
            </form>
        </div>
    </main>








    <?php include '../footer.php'; ?>