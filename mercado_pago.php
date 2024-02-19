<?php
// SDK de Mercado Pago
require 'vendor/autoload.php';
// Agrega credenciales
MercadoPago\SDK::setAccessToken('TEST-5838720071590582-072121-7367aa7e4051ffeee39624ecf1a0db5a-1304968173');


// Crea un objeto de preferencia
$preference = new MercadoPago\Preference();

// Crea un ítem en la preferencia
$item = new MercadoPago\Item();
$item->id = '0001';
$item->title = 'Mi producto';
$item->quantity = 1;
$item->unit_price = 7500;
$item->currency_id = "COP";
$preference->items = array($item);
$preference->back_urls = array(
    'success' => "http://localhost/pasarela/captura.php",
    'failure' => "http://localhost/pasarela/fallo.php"
);

$preference->auto_return = "approved";
$preference->binary_mode = true;

$preference->save();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MercadoPago</title>

    <!-- SDK MercadoPago.js -->
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>


<body>
    <h3>MercadoPago</h3>
    <div class="checkout-btn"></div>

    <script>
        const mp = new MercadoPago('TEST-2254068a-e480-40ff-9bd2-2e0ecc351ed1', {
            locale: 'es-CO'
        });

        mp.checkout({
            preference: {
                id: '<?php echo $preference->id; ?>'
            },
            render: {
                container: '.checkout-btn',
                label: 'Pagar con Mercado Libre'
            }
        });
    </script>
</body>

</html>