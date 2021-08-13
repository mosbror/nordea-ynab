<?php
require "vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

function decryptFilename($cipher_data)
{
    $secret_key = $_ENV["FILE_ENC_SECRET"];
    $cipher = "aes-128-gcm";
    if (in_array($cipher, openssl_get_cipher_methods())) {
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);

        return openssl_decrypt($cipher_data[0], $cipher_data[1], $secret_key, $options = 0, $cipher_data[2],
            $cipher_data[3]);

    }
}

$file_path = decryptFilename(unserialize(base64_decode($_GET['slug'])));
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename='.$file_path);
header('Pragma: no-cache');
header('Content-Length: ' . filesize(__DIR__.$_ENV['UPLOAD_PATH'].$file_path));
readfile(__DIR__.$_ENV['UPLOAD_PATH'].$file_path);
unlink(__DIR__.$_ENV['UPLOAD_PATH'].$file_path);
