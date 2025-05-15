<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://repo.packagist.org/packages.json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if(curl_errno($ch)) {
    echo 'Erro: ' . curl_error($ch);
} else {
    echo "Conexão SSL bem-sucedida!";
}
curl_close($ch);

