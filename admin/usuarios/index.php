<?php


require '../config/database.php';
require '../config/config.php';



$db = new Database();
$con = $db->conectar();


$sql = "SELECT usuarios.id, CONCAT(clientes.nombres, ' ',clientes.apellidos) AS cliente, usuarios.usuario, usuarios.
activacion, 
CASE 
WHEN usuarios.activacion = 1 THEN 'Activo' 
WHEN usuarios.activacion = 0 THEN 'No activado'
ELSE 'Desabilitado' 
END AS estatus
FROM usuarios 
INNER JOIN clientes ON usuarios.id_cliente = clientes.id";
$resultado = $con->query($sql);

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
    <main>
        <div class="container">
            <h4>Usuarios</h4>


            <hr>

            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Estatus </th>
                        <th>Detalles</th>
                    </tr>
                </thead>

                <tbody>

                    <?php while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) { ?>

                        <tr>
                            <td><?php echo $row['cliente']; ?></td>
                            <td><?php echo $row['usuario']; ?></td>
                            <td><?php echo $row['estatus']; ?></td>

                            <td>

                                <a href="cambiar_password.php?user_id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-key"></i>Cambiar Pass</a>

                                <?php if ($row['activacion'] == 1) : ?>



                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#eliminaModal" data-bs-user="<?php echo $row['id']; ?>">Baja</button>
                                <?php else : ?>
                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#activaModal" data-bs-user="<?php echo $row['id']; ?>">Activar</button>


                                <?php endif; ?>
                            </td>
                        </tr>

                    <?php } ?>

                </tbody>
            </table>

        </div>
    </main>



    <!-- Modal -->
    <div class="modal fade" id="eliminaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="detalleModalLabel">Alerta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Desea deshabilitar este usuario?
                </div>
                <div class="modal-footer">
                    <form action="deshabilita.php" method="post">
                        <input type="hidden" name="id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger">Deshabilitar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="activaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="detalleModalLabel">Alerta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Desea habilitar este usuario?
                </div>
                <div class="modal-footer">
                    <form action="activa.php" method="post">
                        <input type="hidden" name="id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Habilitar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <script>
        const eliminaModal = document.getElementById('eliminaModal');
        eliminaModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const user = button.getAttribute('data-bs-user');
            const inputId = eliminaModal.querySelector('.modal-footer input[name="id"]');
            inputId.value = user;
        });

        const activaModal = document.getElementById('activaModal');
        activaModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const user = button.getAttribute('data-bs-user');
            const inputId = activaModal.querySelector('.modal-footer input[name="id"]');
            inputId.value = user;
        });
    </script>




    <?php include '../footer.php'; ?>