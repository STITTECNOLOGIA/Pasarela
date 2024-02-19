<?php


require '../config/database.php';
require '../config/config.php';



$db = new Database();
$con = $db->conectar();


$sql = "SELECT id_transaccion, fecha, status, total, medio_pago, CONCAT(nombres,' ',apellidos) AS cliente FROM compra INNER JOIN clientes ON compra.id_cliente = clienteS.id ORDER BY DATE(fecha) DESC";
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
  <main class="flex-shrink-0">
    <div class="container mt-3">
      <h4>compras</h4>

      <a href="genera_reporte.php" class="btn btn-success btn-sm">
        Reporte de compras
      </a>


      <hr>

      <table class="table">
        <thead>
          <tr>
            <th>Folio</th>
            <th>Cliente</th>
            <th>total</th>
            <th>fecha</th>
            <th>Detalles</th>
          </tr>
        </thead>

        <tbody>

          <?php while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) { ?>

            <tr>
              <td><?php echo $row['id_transaccion']; ?></td>
              <td><?php echo $row['cliente']; ?></td>
              <td><?php echo $row['total']; ?></td>
              <td><?php echo $row['fecha']; ?></td>
              <td>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detalleModal" data-bs-orden="<?php echo $row['id_transaccion']; ?>">Ver</button>

              </td>
            </tr>

          <?php } ?>

        </tbody>
      </table>

    </div>
  </main>


  <!-- Modal -->
  <div class="modal fade" id="detalleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="detalleModalLabel">Detalles de compra</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

        </div>
      </div>
    </div>
  </div>

  <script>
    const detalleModal = document.getElementById('detalleModal')
    detalleModal.addEventListener('show.bs.modal', event => {
      // Button that triggered the modal
      const button = event.relatedTarget
      // Extract info from data-bs-* attributes
      const orden = button.getAttribute('data-bs-orden')
      const modalBody = detalleModal.querySelector('.modal-body')

      const url = 'http://localhost/pasarela/admin/compras/getCompra.php'

      let formData = new FormData()
      formData.append('orden', orden)

      fetch(url, {
          method: 'post',
          body: formData,
        })
        .then((resp) => resp.json())
        .then(function(data) {
          // Clear the modal body
          modalBody.innerHTML = ''
          // Create HTML elements to display the data
          const fechaParaMostrar = document.createElement('p')
          fechaParaMostrar.innerHTML = `<strong>Fecha: </strong>${data.fecha}`
          modalBody.appendChild(fechaParaMostrar)

          const ordenParaMostrar = document.createElement('p')
          ordenParaMostrar.innerHTML = `<strong>Orden: </strong>${data.orden}`
          modalBody.appendChild(ordenParaMostrar)

          const totalParaMostrar = document.createElement('p')
          totalParaMostrar.innerHTML = `<strong>Total: </strong>${data.total}`
          modalBody.appendChild(totalParaMostrar)

          // Display details, if any
          if (data.detalles && data.detalles.length > 0) {
            const detallesHeader = document.createElement('h5')
            detallesHeader.textContent = 'Detalles de la compra:'
            modalBody.appendChild(detallesHeader)

            const detallesList = document.createElement('ul')
            data.detalles.forEach(detalle => {
              const detalleItem = document.createElement('li')
              detalleItem.textContent = `${detalle.nombre} - Precio: ${detalle.precio}, Cantidad: ${detalle.cantidad}`
              detallesList.appendChild(detalleItem)
            })
            modalBody.appendChild(detallesList)
          }
        })

    })
  </script>



  <?php include '../footer.php'; ?>