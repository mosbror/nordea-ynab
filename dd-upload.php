<?php
require "vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}

function encryptFilename($message_to_encrypt){
    $secret_key = $_ENV["FILE_ENC_SECRET"];
    //$key should have been previously generated in a cryptographically safe way, like openssl_random_pseudo_bytes
    $cipher = "aes-128-cbc";
    if (in_array($cipher, openssl_get_cipher_methods()))
    {
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $hash = openssl_encrypt($message_to_encrypt, $cipher, $secret_key, $options=0, $iv, $tag);
        return [$hash,$cipher,$iv,$tag];
    }
}


$source = $_FILES["upfile"]["tmp_name"];
$source_data = [];
$destination_data = [];
$destination_data[] = ['Date','Payee','Memo','Outflow','Inflow'];
foreach(file($source) as $f){
    $source_data[] = str_getcsv($f,";");
}

foreach($source_data as $row){
    if(validateDate($row[0])){
        if(trim($row[2]) == ""){
            $payee = $row[5];
            $outflow = null;
            $inflow = (str_replace(",",".",$row[1]) * 1);
        }else {
            $payee = $row[5];
            $outflow = (str_replace(",",".",$row[1]) * -1);
            $inflow = null;
        }

        $destination_data[] = [
            $row[0], // Date
            $payee, // Payee
            "", // Memo
            $outflow, // Outflow
            $inflow, // Inflow
        ];
    }
}

$file_path = uniqid("YNAB_".date('Y-m-d_H-i')."_", true).".csv";
$hash_array = encryptFilename($file_path);

$fp = fopen(__DIR__.$_ENV['UPLOAD_PATH'].$file_path, 'x');

// Loop through file pointer and a line
foreach ($destination_data as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp);
?>

<a class="button is-primary is-small" href="download.php?slug=<?php echo base64_encode(serialize($hash_array)); ?>" onclick="remove(this);document.getElementById('downloaded_message').classList.remove('is-hidden');" target="_blank">Download YNAB file</a>
<span id="downloaded_message" class="is-hidden">File downloaded and removed</span>
