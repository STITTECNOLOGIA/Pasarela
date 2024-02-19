<?php

require '../config/database.php';
require '../config/config.php';
require '../header.php';



$db = new Database();
$con = $db->conectar();

$sql = "SELECT id, nombre, descripcion, precio, descuento, stock, id_categoria FROM productos WHERE activo = 1";
$resultado = $con->query($sql);
$producto = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>



<main>
    <div class="container-fluid px-4">
        <h2 class="mt-4">Productos</h2>

        <a href="nuevo.php" class="btn btn-primary">Nuevo</a>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Stock</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($producto as $productos) { ?>
                        <tr>
                            <td><?php echo $productos['nombre']; ?></td>
                            <td><?php echo $productos['precio']; ?></td>
                            <td><?php echo $productos['stock']; ?></td>
                            <td>
                                <a href="edita.php?id=<?php echo $productos['id'] ?>" class="btn btn-warning btn-sm">Editar</a>

                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalElimina" data-bs-id="<?php echo $productos['id']; ?>">
                                    <i class="fas fa-trash"></i>

                                </button>


                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>


        </div>

    </div>
</main>
<!-- Modal Body -->

<div class="modal fade" id="modalElimina" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Confirmar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Desea eliminar el registro?
            </div>
            <div class="modal-footer">
                <form action="elimina.php" method="post">
                    <input type="hidden" name="id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Optional: Place to the bottom of scripts -->
<script>
    let eliminaModal = document.getElementById('modalElimina')
    eliminaModal.addEventListener('show.bs.modal', function(event) {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        let modalInput = eliminaModal.querySelector('.modal-footer input')
        modalInput.value = id
    })
</script>



<?php require '../footer.php'; ?>