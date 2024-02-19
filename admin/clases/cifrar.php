
/*
define("KEY_CIFRADO", "Q1W2E3R4T5");
define("METODO", "abc-125-hjk");

function cifrar($data){
    $iv_length = openssl_cipher_iv_length(METODO);
    $iv = openssl_random_pseudo_bytes($iv_length);
    $cipher = openssl_encrypt($data, METODO, KEY_CIFRADO, OPENSSL_RAW_DATA, $iv);

    return base64_encode($iv) . ':' . base64_encode($cipher);
}

/*
function descifrar($input){
    $parts = explode(':', $input);
    $iv = base64_decode($parts[0]);
    $cipher = base64_decode($parts[1]);

    $decrypted = openssl_decrypt($cipher, METODO, KEY_CIFRADO, OPENSSL_RAW_DATA, $iv);

    if ($decrypted === false) {
        die('Error al descifrar los datos.');
    }

    return $decrypted;
}/*
