<?php
require_once 'config/config.php';

// SDK de Mercado Pago
require 'vendor/autoload.php';
// Agrega credenciales
MercadoPago\SDK::setAccessToken(TOKEN_MP);

$preference = new MercadoPago\Preference();
$productos_mp = array();

$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
$lista_carrito = array();

if ($productos != null) {
  foreach ($productos as $clave => $cantidad) {
    $sql = $con->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id=? AND activo=1");
    $sql->execute([$clave]);
    $lista_carrito[] = $sql->fetchAll(PDO::FETCH_ASSOC);
  }
} else {
  header("Location: index.php");
  exit;
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="images/logo.png" type="image/x-icon" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
  <link href="css/estilos.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/751f7f0eaa.js" crossorigin="anonymous"></script>
  <!-- SDK MercadoPago.js -->
  <script src="https://sdk.mercadopago.com/js/v2"></script>
  <title>Store</title>
</head>

<body>
  <!--Menu de navegación-->
  <?php include 'menu.php'; ?>
  <!--Contenido-->
  <main>
    <div class="container">
      <div class="row">
        <div class="col-6">
          <h4>Detalles del pago</h4>
          <div class="row">
            <div class="col-12">
              <!--Boton de PAYPAL-->
              <div id="paypal-button-container"></div>
            </div>
          </div>
          <div class="col-12">
            <!--Boton de Mercado pago-->
            <div class="checkout-btn"></div>
          </div>
        </div>
        <div class="col-6">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Producto</th>
                  <th>Subtotal</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php if ($lista_carrito == null) {
                  echo '<tr><td colspan="5" class="text-center"><b>Lista vacia</b></td></tr>';
                } else {
                  $total = 0;
                  foreach ($lista_carrito as $producto) {
                    $producto = $producto[0];
                    $_id = $producto['id'];
                    $nombre = $producto['nombre'];
                    $precio = $producto['precio'];
                    $descuento = $producto['descuento'];
                    $cantidad = $producto['cantidad'];
                    $precio_desc = $precio - (($precio * $descuento) / 100);
                    $subtotal =  $cantidad * $precio_desc;
                    $total += $subtotal;
                    // Crea un ítem en la preferencia
                    $item = new MercadoPago\Item();
                    $item->id = $_id;
                    $item->title = $nombre;
                    $item->quantity = $cantidad;
                    $item->unit_price = $precio_desc;
                    $item->currency_id = "COP";

                    array_push($productos_mp, $item);
                    unset($item);
                ?>
                    <tr>
                      <td><?php echo $nombre; ?></td>
                      <td>
                        <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 2, '.', '.'); ?></div>
                      </td>
                    </tr>
                  <?php } ?>
                  <tr>

                    <td colspan="2">
                      <p class="h3 text-end" id="total"><?php echo MONEDA . number_format($total, 2, '.', '.'); ?></p>
                    </td>
                  </tr>

              </tbody>
            <?php } ?>
            </table>

          </div>
        </div>
      </div>
    </div>
    </div>
    <?php

    $preference->items = $productos_mp;
    $preference->back_urls = array(
      'success' => "http://localhost/pasarela/captura.php",
      'failure' => "http://localhost/pasarela/fallo.php"
    );

    $preference->auto_return = "approved";
    $preference->binary_mode = true;

    $preference->save();
    ?>
  </main>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>" &currency=<?php echo CURRENCY; ?>></script>

  <!--Script de implementacion de PAYPAL-->

  <script>
    paypal.Buttons({
      style: {
        color: 'blue',
        shape: 'pill',
        label: 'pay'
      },
      createOrder: function(data, actions) {
        return actions.order.create({
          purchase_units: [{
            amount: {
              value: <?php echo $total; ?>
            }
          }]
        });
      },
      onApprove: function(data, actions) {
        let URL = 'clases/captura.php';
        actions.order.capture().then(function(detalles) {
          console.log(detalles);
          Swal.fire({
            icon: 'success',
            title: 'Pago exitoso',
            text: 'El pago se ha completado con éxito.'
          });
          return fetch(URL, {
            method: 'post',
            headers: {
              'content-type': 'application/json'
            },
            body: JSON.stringify({
              detalles: detalles
            })
          }).then(function(response) {
            window.location.href = "completado.php?key=" + detalles['id'];
          })
        });
      },
      onCancel: function(data) {
        Swal.fire({
          icon: 'error',
          title: 'Pago cancelado',
          text: 'El pago ha sido cancelado.'
        });
        console.log(data);
      }
    }).render('#paypal-button-container');

    //<!--Script de implementacion de MercadoPago-->

    const mp = new MercadoPago('TEST-2254068a-e480-40ff-9bd2-2e0ecc351ed1', {
      locale: 'es-CO'
    });

    mp.checkout({
      preference: {
        id: '<?php echo $preference->id; ?>'
      },
      render: {
        container: '.checkout-btn',
        type: 'wallet',
        label: 'Pagar con Mercado Pago' // Cambia el texto del botón de pago (opcional)
        // Muestra un botón de pago con la marca Mercado Pago

      }
    });
  </script>
</body>

</html>