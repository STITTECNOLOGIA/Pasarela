<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=AeUAzRfE3B3X6bVS98Fnh7rYNURfcmhK1HEUWAlvywAFeun2tzFndrxmKEuf7CCgVA3vK6RVLZN7c5pz"></script>
</head>

<body>
    <div id="paypal-button-container"></div>
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
                            value: 100
                        }

                    }]
                });
            },

            onApprove: function(data, actions) {
                actions.order.capture().then(function(detalles) {
                    console.log(detalles);
                    Swal.fire({
                        icon: 'success',
                        title: 'Pago exitoso',
                        text: 'El pago se ha completado con éxito.',
                    });
                });
            },

            onCancel: function(data) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pago cancelado',
                    text: 'El pago ha sido cancelado.',
                });
                console.log(data);
            }

        }).render('#paypal-button-container');
    </script>


</body>

</html>